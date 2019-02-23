<?php
  $mainPage = new HotbitsAPI();
  $sqlCommands = new sqlCommands();
  $manager = new databaseManager();

  $mainPage->setVars();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->printAPI();

  class HotbitsAPI {
    public $exec_ent_path;
    public $exec_cat_path;
    public $exec_mkdir_path;

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
      global $manager;

      try {
        if(!empty($_POST)) {
          if(isset($_POST["bytes"])) {
            header("Content-Type: application/json");
            print($manager->formatForSQL($this->grabData((int) $_POST["bytes"])));
          } else if(isset($_POST["retrieve"]) && isset($_POST["id"])) {
            // https://stackoverflow.com/questions/7336861/how-to-convert-string-to-boolean-php#comment8848275_7336873
            if(filter_var($_POST["retrieve"], FILTER_VALIDATE_BOOLEAN)) {
              header("Content-Type: text/plain");
              print($manager->readSQLToJSON((int) $_POST["id"]));
            } else {
              header("Content-Type: application/json");

              $jsonArray = ["error" => "Sorry, but 'retrieve' is false!"];
              $json = json_encode($jsonArray);
              print($json);
              //die();
            }
          } else if(isset($_POST["analyze"]) && isset($_POST["id"])) {
            if(isset($_POST["count"]) && filter_var($_POST["count"], FILTER_VALIDATE_BOOLEAN) && filter_var($_POST["analyze"], FILTER_VALIDATE_BOOLEAN)) {
              header("Content-Type: text/plain");
              print($this->getRandomnessCount($manager->readSQLToJSON((int) $_POST["id"]), TRUE));
            } else if(!filter_var($_POST["analyze"], FILTER_VALIDATE_BOOLEAN)) {
              header("Content-Type: application/json");

              $jsonArray = ["error" => "Sorry, but 'analyze' is false!"];
              $json = json_encode($jsonArray);
              print($json);
            } else {
              header("Content-Type: text/plain");
              print($this->getRandomness($manager->readSQLToJSON((int) $_POST["id"])));
            }
          } else {
            header("Content-Type: application/json");

            $jsonArray = ["error" => "Sorry, but no valid request sent!"];
            $json = json_encode($jsonArray);
            print($json);
          }
        } else {
          header("Content-Type: application/json");

          $jsonArray = ["error" => "Please send a POST request!"];
          $json = json_encode($jsonArray);
          print($json);
        }
      } catch(Exception $e) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Request Error! Exception: " . $e->getMessage()];
        $json = json_encode($jsonArray);
        print($json);
      }
    }

    public function grabData($bytes) {
      //getenv('alex.server.api.hotbits');
      try {
        if(!is_int($bytes) || $bytes > 2048 || $bytes < 1)
          throw new Exception("InvalidByteCount"); // Too many, too few, or not even a number (integer)!!!

        //header("Content-Type: application/json");

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

    public function grabDataOffline($bytes) {
      // This function exists purely for testing with the data on localhost while offline
      if(!is_int($bytes) || $bytes > 2048 || $bytes < 1)
        throw new Exception("InvalidByteCount"); // Too many, too few, or not even a number (integer)!!!

      //header("Content-Type: application/json");

      /*
       * It appears I get 12,288 total bytes to download and 120 total requests.
       * I do not know when the counter resets, but I am hoping it is daily.
       * I calculated this out by adding the 2,048 bytes I requested plus the 10,240
       * bytes from quotaBytesRemaining at debug-real.json. This was my first non-pseudo
       * request in a while (around a month). Also, there are 119 requests left and I only
       * used one request in over a month.
       *
       * The page: https://www.fourmilab.ch/fourmilog/archives/2017-06/001684.html
       * says that there are 12,208 bytes total per 24 hours period. There's still the
       * 120 total request limit (which is also part of the 24 hour period).
       */

      $file = file_get_contents("debug-real.json");
      //$file = file_get_contents("debug.json");

      return $file; //file_get_contents("debug.json");
    }

    public function getRandomness($result) {
      try {
        //header("Content-Type: text/plain");
        return $this->checkRandomness($this->convertToArray($result));
      } catch(Exception $e) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Exception: " . $e->getMessage()];
        $json = json_encode($jsonArray);
        print($json);
      }
    }

    public function getRandomnessCount($result, $count) {
      try {
        //header("Content-Type: text/plain");
        return $this->checkRandomnessCount($this->convertToArray($result), $count);
      } catch(Exception $e) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Exception: " . $e->getMessage()];
        $json = json_encode($jsonArray);
        print($json);
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
      // No Function Overloading :( - https://stackoverflow.com/a/4697712/6828099
      return $this->checkRandomnessCount($array, false);
    }

    function checkRandomnessCount($array, $count) {
      // pipe-data-to | /home/web/programs/ent
      // http://www.fourmilab.ch/random/random.zip
      $binary = pack("C*", ...$array);
      // var_dump(unpack("C*", $binary));
      // print(bin2hex($binary));
      // https://stackoverflow.com/a/49409847/6828099

      exec($this->exec_mkdir_path . ' -p /tmp/hotbits/');
      file_put_contents($File = "/tmp/hotbits/" . uniqid(), $binary);

      if($count === true) {
        //print($this->exec_cat_path . ' ' . escapeshellarg($File) . ' | ' . $this->exec_ent_path . ' -c');
        $results = shell_exec($this->exec_cat_path . ' ' . escapeshellarg($File) . ' | ' . $this->exec_ent_path . ' -c');
      } else {
        $results = shell_exec($this->exec_cat_path . ' ' . escapeshellarg($File) . ' | ' . $this->exec_ent_path);
      }

      //print("Results: " . $results);
      return $results;
    }
  }

  class sqlCommands {
    private $server, $username, $password, $database;

    public function setLogin($server, $username, $password, $database) {
      $this->server = $server;
      $this->username = $username;
      $this->password = $password;
      $this->database = $database;
    }

    public function testConnection() {
      if($this->server === NULL || $this->username === NULL || $this->password === NULL || $this->database === NULL) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Sorry, but you are missing a value to connect to the MySQL server! Not Attempting Connection!!!"];
        $json = json_encode($jsonArray);
        print($json);
        die();
      }

      $return_response = $this->connectMySQL();
      if(gettype($return_response) === "string") {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Connection to MySQL Failed: " . $return_response . "!"];
        $json = json_encode($jsonArray);
        print($json);
        die();
      } else {
        /* //object
         *
         * $jsonArray = ["error" => "Connected to MySQL Successfully!!!"];
         * $json = json_encode($jsonArray);
         * print($json);
        */
      }
    }

    public function connectMySQL() {
      try {
        $conn = new PDO("mysql:host=$this->server;dbname=$this->database", $this->username, $this->password);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
      }
      catch(PDOException $e) {
        return $e->getMessage();
      }
    }

    public function createTable() {
      try {
        $conn = $this->connectMySQL();

        // https://stackoverflow.com/a/8829122/6828099
        $checkTableSQL = "SELECT count(*)
          FROM information_schema.TABLES
          WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'Hotbits')
        ";

        /*
         * "version": string,
         * "schema": string,
         * "status": int,
         * "requestInformation": {
         *    "serverVersion": string,
         *    "generationTime": string,
         *    "bytesRequested": int,
         *    "bytesReturned": int,
         *    "quotaRequestsRemaining": int,
         *    "quotaBytesRemaining": int,
         *    "generatorType": string
         * },
         * "data": [ int ];
         */

        // https://stackoverflow.com/a/5562383/6828099 - INT(6) - Display Width
        // https://dev.mysql.com/doc/refman/5.7/en/json.html - MySQL JSON Format (MySQL 5.7.8+)
        // Aparently schema is a special keyword that cannot be used in normal tables
        $sql = "CREATE TABLE Hotbits (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          version TEXT NOT NULL,
          jsonSchema TEXT NOT NULL,
          status INT NOT NULL,
          serverVersion TEXT NOT NULL,
          generationTime TEXT NOT NULL,
          bytesRequested INT NOT NULL,
          bytesReturned INT NOT NULL,
          quotaRequestsRemaining INT NOT NULL,
          quotaBytesRemaining INT NOT NULL,
          generatorType TEXT NOT NULL,
          data TEXT NOT NULL
        )";
        // data JSON NOT NULL - Turns out my RPI Server does not support this format for MySQL.

        $tableExists = false;
        // http://php.net/manual/en/pdo.query.php
        foreach ($conn->query($checkTableSQL) as $row) {
          if($row['count(*)'] > 0)
            $tableExists = true;
        }

        if(!$tableExists) {
          // use exec() because no results are returned
          $conn->exec($sql);
        }
      } catch(PDOException $e) {
          header("Content-Type: application/json");

          //echo $sql;
          $jsonArray = ["error" => "Create Table Failed: " . $e->getMessage()];
          $json = json_encode($jsonArray);
          print($json);
      }
    }

    public function insertData($version, $schema, $status, $serverVersion, $generationTime, $bytesRequested, $bytesReturned, $quotaRequestsRemaining, $quotaBytesRemaining, $generatorType, $data) {
      try {
        $conn = $this->connectMySQL();
        $statement = $conn->prepare("INSERT INTO Hotbits (version, jsonSchema, status, serverVersion, generationTime, bytesRequested, bytesReturned, quotaRequestsRemaining, quotaBytesRemaining, generatorType, data)
                                     VALUES (:version, :jsonSchema, :status, :serverVersion, :generationTime, :bytesRequested, :bytesReturned, :quotaRequestsRemaining, :quotaBytesRemaining, :generatorType, :data)");

        $statement->execute([
          'version' => $version, // TEXT
          'jsonSchema' => $schema, // TEXT
          'status' => $status, // INT

          'serverVersion' => $serverVersion, // TEXT
          'generationTime' => $generationTime, // TEXT

          'bytesRequested' => $bytesRequested, // INT
          'bytesReturned' => $bytesReturned, // INT

          'quotaRequestsRemaining' => $quotaRequestsRemaining, // INT
          'quotaBytesRemaining' => $quotaBytesRemaining, // INT

          'generatorType' => $generatorType, // TEXT

          'data' => $data,  // JSON
        ]);

        // https://stackoverflow.com/a/9753751/6828099
        return $conn->lastInsertId();
      } catch(PDOException $e) {
          header("Content-Type: application/json");

          $jsonArray = ["error" => "Insert Data into Table Failed: " . $e->getMessage()];
          $json = json_encode($jsonArray);
          print($json);
      }
    }

    public function readData($id) {
      try {
        $conn = $this->connectMySQL();

        $statement = $conn->prepare("SELECT * FROM Hotbits WHERE id=(:rowID)");
        $statement->execute(['rowID' => $id]);

        $rows = $statement->fetchAll();

        if(sizeof($rows) === 0)
          throw new Exception("Invalid rowID!!!");

        // http://php.net/manual/en/pdostatement.fetchall.php
        foreach ($rows as $row) {
          // This is intentionally supposed to run only one iteration.
          // https://stackoverflow.com/a/3579950/6828099
          return [$row['id'], $row['version'], $row['jsonSchema'], $row['status'], $row['serverVersion'], $row['generationTime'], $row['bytesRequested'], $row['bytesReturned'], $row['quotaRequestsRemaining'], $row['quotaBytesRemaining'], $row['generatorType'], $row['data']];
        }
      } catch(PDOException $e) {
          header("Content-Type: application/json");

          $jsonArray = ["error" => "Read Data from Table Failed: " . $e->getMessage()];
          $json = json_encode($jsonArray);
          print($json);
      }
    }
  }

  class databaseManager {
    public function readSQLToJSON($id) {
      global $sqlCommands;
      list($id, $version, $schema, $status, $serverVersion, $generationTime, $bytesRequested, $bytesReturned, $quotaRequestsRemaining, $quotaBytesRemaining, $generatorType, $data) = $sqlCommands->readData($id);
      //print("Data: " . $id);

      // https://stackoverflow.com/a/8529687/6828099
      $jsonArray = ["rowID" => (int) $id,
                    "version" => $version,
                    "schema" => $schema,
                    "status" => (int) $status,

                    "requestInformation" => ["serverVersion" => $serverVersion,
                                             "generationTime" => $generationTime,

                                             "bytesRequested" => (int) $bytesRequested,
                                             "bytesReturned" => (int) $bytesReturned,

                                             "quotaRequestsRemaining" => (int) $quotaRequestsRemaining,
                                             "quotaBytesRemaining" => (int) $quotaBytesRemaining,

                                             "generatorType" => $generatorType
                                            ],

                    "data" => json_decode($data, true)
                   ];

      // https://stackoverflow.com/a/30315200/6828099
      $json = json_encode($jsonArray, JSON_UNESCAPED_SLASHES);
      //$json = json_encode($jsonArray, JSON_PRETTY_PRINT);

      //header("Content-Type: application/json");
      return $json;
    }

    public function formatForSQL($json) {
      try {
        $decoded = json_decode($json, true);

        $version = $decoded["version"]; // TEXT
        $schema = $decoded["schema"]; // TEXT
        $status = $decoded["status"]; // INT

        $serverVersion = $decoded["requestInformation"]["serverVersion"]; // TEXT
        $generationTime = $decoded["requestInformation"]["generationTime"]; // TEXT

        $bytesRequested = $decoded["requestInformation"]["bytesRequested"]; // INT
        $bytesReturned = $decoded["requestInformation"]["bytesReturned"]; // INT

        $quotaRequestsRemaining = $decoded["requestInformation"]["quotaRequestsRemaining"]; // INT
        $quotaBytesRemaining = $decoded["requestInformation"]["quotaBytesRemaining"]; // INT

        $generatorType = $decoded["requestInformation"]["generatorType"]; // TEXT

        // http://php.net/manual/en/function.json-encode.php
        //$data = "{}"; //$decoded["data"]; // JSON
        //$data = $decoded["data"]; // JSON
        $data = json_encode($decoded["data"]);

        //print("Data: " . "\"" . $version . "\"" . $schema . "\"" . $status . "\"" . $serverVersion . "\"" . $generationTime . "\"" . $bytesRequested . "\"" . $bytesReturned . "\"" . $quotaRequestsRemaining . "\"" . $quotaBytesRemaining . "\"" . $generatorType . "\"" . $data);

        //$sqlCommands = new sqlCommands(); // I cannot set this unless I want to specify the auth multiple times.
        global $sqlCommands;
        $id = $sqlCommands->insertData($version, $schema, $status, $serverVersion, $generationTime, $bytesRequested, $bytesReturned, $quotaRequestsRemaining, $quotaBytesRemaining, $generatorType, $data);

        // http://php.net/manual/en/function.array-push.php
        // https://stackoverflow.com/a/13638998/6828099 - Pretty Print JSON
        //print("Last ID: " . $id);
        //array_push($decoded, ["rowID" => $id]);
        //$decoded[] = ["rowID" => $id];
        $decoded["rowID"] = (int) $id;
        $json = json_encode($decoded, JSON_UNESCAPED_SLASHES);
        //$json = json_encode($decoded, JSON_PRETTY_PRINT);

        return $json;
      } catch(Exception $e) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "databaseManager->formatForSQL: " . $e->getMessage()];
        $json = json_encode($jsonArray);
        print($json);
      }
    }
  }
?>