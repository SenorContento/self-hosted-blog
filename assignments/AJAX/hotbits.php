<?php
  $mainPage = new HotbitsAPI();
  $mainPage->setVars();
  $mainPage->printAPI();

  /*$sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();*/

  class HotbitsAPI {
    public $exec_ent_path;
    public $exec_cat_path;

    function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->exec_ent_path = "/home/web/programs/ent";
        $this->exec_cat_path = "/bin/cat";
        $this->exec_mkdir_path = "/bin/mkdir";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->exec_ent_path = "/Users/senor/Documents/.Programs/ent";
        $this->exec_cat_path = "/bin/cat";
        $this->exec_mkdir_path = "/bin/mkdir";
      }
    }

    public function printAPI() {
      //print($this->grabData(2048));
      print($this->getRandomness($this->grabData(2048)));
    }

    public function grabData($bytes) {
      //getenv('alex.server.api.hotbits');
      try {
        if(!is_int($bytes) || $bytes > 2048 || $bytes < 1)
          throw new Exception("InvalidByteCount"); // Too many, too few, or not even a number (integer)!!!

        header("Content-Type: application/json");

        if(getenv('alex.server.type') === "production") {
          # The below variables are for the production server - getenv('alex.server.api.hotbits')
          return $this->requestData($this->setParameters("pseudo", "json", $bytes)); // Rate Limit Not Available Yet
        } else if(getenv('alex.server.type') === "development") {
          # The below variables are for testing on localhost
          return $this->requestData($this->setParameters("pseudo", "json", $bytes)); // 10 Bytes - Normal Testing
        }
      } catch(Exception $e) {
        throw $e; // Pass the exception upwards!
      }
    }

    public function getRandomness($result) {
      try {
        header("Content-Type: text/plain");
        return $this->checkRandomness($this->convertToArray($result));
      } catch(Exception $e) {
        print("Exception: " . $e->getMessage());
      }
    }

    private function setParameters($apikey, $fmt, $nbytes) {
      /*
       * These below values are the default values assumed by Hotbit's Server.
       * If they are not explicitly specified, this is what they default to.
       */

      /*
          $data = array('nbytes' => '128', // Maximum # of Bytes: 2048 - Not Active When fmt = password
                        'fmt' => 'hex', // Available Formats: hex, bin, c, xml, json, password

                        // This Section is Only Active when fmt = password
                        'npass' => '1', // Number of Passwords
                        'lpass' => '8', // Length of Each Password
                        'pwtype' => '3', // 0=>lowercase, 1=>mixed-case, 2=>letters-and-numbers, 3=>2-and-punctuation

                        // Only one of these options can be active
                        'apikey' => '', // API Key: Needed to Get Real Data from Geiger Counter
                        'pseudo' => 'pseudo' // Flag for Fake Data: Best for Testing - No Rate Limit/Banning
                  );
      */

      if($apikey === "pseudo") {
        $data = array('nbytes' => $nbytes, // Maximum # of Bytes: 2048 - Not Active When fmt = password
                      'fmt' => $fmt, // Available Formats: hex, bin, c, xml, json, password
                      'pseudo' => 'pseudo' // Flag for Fake Data: Best for Testing - No Rate Limit/Banning
                    );
      } else {
        $data = array('nbytes' => $nbytes, // Maximum # of Bytes: 2048 - Not Active When fmt = password
                      'fmt' => $fmt, // Available Formats: hex, bin, c, xml, json, password

                      // Only one of these options can be active
                      'apikey' => $apikey, // API Key: Needed to Get Real Data from Geiger Counter
                    );
      }

      return $data;
    }

    private function setParametersPassword($apikey, $lpass, $npass, $pwtype) {
      if($apikey === "pseudo") {
        $data = array('fmt' => 'password', // Available Formats: hex, bin, c, xml, json, password

                      'npass' => $npass, // Number of Passwords
                      'lpass' => $lpass, // Length of Each Password
                      'pwtype' => $pwtype, // 0=>lowercase, 1=>mixed-case, 2=>letters-and-numbers, 3=>2-and-punctuation

                      'pseudo' => 'pseudo' // Flag for Fake Data: Best for Testing - No Rate Limit/Banning
                    );
      } else {
        $data = array('fmt' => 'password', // Available Formats: hex, bin, c, xml, json, password

                      'npass' => $npass, // Number of Passwords
                      'lpass' => $lpass, // Length of Each Password
                      'pwtype' => $pwtype, // 0=>lowercase, 1=>mixed-case, 2=>letters-and-numbers, 3=>2-and-punctuation

                      'apikey' => $apikey, // API Key: Needed to Get Real Data from Geiger Counter
                    );
      }

      return $data;
    }

    private function requestData($data) {
      try {
        // https://stackoverflow.com/a/6609181/6828099
        $url = 'https://www.fourmilab.ch/cgi-bin/Hotbits.api';

        $options = array(
          'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
          )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context); // http://php.net/manual/en/function.file-get-contents.php - string $filename, bool $use_include_path = FALSE, resource $context, ...
        //$result = false;

        if ($result === FALSE) {
          throw new Exception("Result Returned FALSE!!!");
        }

        /*
         * Will be in HTML format unless fmt is bin, xml, or json.
         *
         * Bin is Raw
         * XML is Raw
         * JSON is Raw
         *
         * Will be in HTML format if hex, c, or password.
         *
         * Hex is in an HTML pre tag
         * C is in an HTML pre tag
         * Password is in an HTML textarea
         */

        return $result; // It is on the caller to anticipate the correct format.
      } catch(Exception $e) {
        throw $e; // $result === false calls here
      }
    }

    public function convertToArray($result) {
      // http://php.net/manual/en/function.json-decode.php
      $array = null;
      if($this->isJson($result)) {
        $array = json_decode($result, true)["data"];
      } else {
        throw new Exception("Format Not Supported!!!");// Supported Formats are JSON!!!");
      }

      return $array;
    }

    // https://stackoverflow.com/a/6041773/6828099
    private function isJson($string) {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    }

    public function checkRandomness($array) {
      // pipe-data-to | /home/web/programs/ent
      // http://www.fourmilab.ch/random/random.zip
      $binary = pack("C*", ...$array);
      // var_dump(unpack("C*", $binary));
      // print(bin2hex($binary));
      // https://stackoverflow.com/a/49409847/6828099

      exec($this->exec_mkdir_path . ' -p /tmp/hotbits/');
      file_put_contents($File = "/tmp/hotbits/" . uniqid(), $binary);
      //print($this->exec_cat_path . ' ' . escapeshellarg($File) . ' | ' . $this->exec_ent_path . ' -c');
      $results = shell_exec($this->exec_cat_path . ' ' . escapeshellarg($File) . ' | ' . $this->exec_ent_path . ' -c');
      //print("Results: " . $results);
      return $results;
    }
  }
?>