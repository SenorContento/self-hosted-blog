<?php
  // https://stackoverflow.com/a/3406181/6828099
  // This is used to convert all warnings, errors, etc... into exceptions that I can handle.
  set_error_handler(
    function ($severity, $message, $file, $line) {
      throw new ErrorException($message, $severity, $severity, $file, $line);
    }
  );

  $mainPage = new TorAPI();
  $mainPage->setVars();
  $mainPage->printAPI();

  class TorAPI {
    //public $exec_ent_path;

    function setVars() {
      $this->nodes_url = "https://check.torproject.org/exit-addresses";

      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        //$this->exec_ent_path = "/home/web/programs/ent";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        //$this->exec_ent_path = "/Users/senor/Documents/.Programs/ent";
      }
    }

    public function printAPI() {
      try {
        if(!empty($_REQUEST)) {
          $ip = isset($_REQUEST["ip"]) ? $_REQUEST["ip"] : NULL;

          if(isset($ip)) {
            $nodes = $this->requestExitNodes();
            header("Content-Type: application/json");

            $found = false;
            for($i=1; $i<sizeof($nodes); $i++) {
                //print("IP Address: " . $nodes[$i]["ExitAddress"] . "<br>");
                if("$ip" === $nodes[$i]["ExitAddress"]) {
                  $found = true;
                  $jsonArray = ["exitnode" => "true"];
                  break;
                }
            }

            if(!$found) {
              $jsonArray = ["exitnode" => "false"];
            }

            $json = json_encode($jsonArray);
            print($json);
            die();
          }

          header("Content-Type: application/json");

          $jsonArray = ["error" => "Sorry, but no valid request sent!"];
          $json = json_encode($jsonArray);
          print($json);
        } else {
          header("Content-Type: application/json");

          $jsonArray = ["error" => "Please send a POST or GET request!"];
          $json = json_encode($jsonArray);
          print($json);
        }
      } catch(Exception $e) {
        header("Content-Type: application/json");

        $jsonArray = ["error" => "Request Error! Exception: " . $e->getMessage()];
        $json = json_encode($jsonArray);
        print($json);
        die();
      }
    }

    private function requestExitNodes() {
      try {
        /*$data = array('nbytes' => $nbytes, // Maximum # of Bytes: 2048 - Not Active When fmt = password
                      'fmt' => $fmt, // Available Formats: hex, bin, c, xml, json, password
                      'pseudo' => 'pseudo' // Flag for Fake Data: Best for Testing - No Rate Limit/Banning
                    );*/

        // https://stackoverflow.com/a/6609181/6828099
        $options = array(
          'http' => array(
            'user_agent' => getenv('alex.server.user_agent'),
            //'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
            //'content' => http_build_query($data)
          )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($this->nodes_url, false, $context); // http://php.net/manual/en/function.file-get-contents.php - string $filename, bool $use_include_path = FALSE, resource $context, ...
        //$result = false;
        //var_dump($result);

        if ($result === FALSE) {
          throw new Exception("Result Returned FALSE!!!");
        }

        //return $result; // It is on the caller to anticipate the correct format. If needed, I could use an array to specify type and data ["type"->"json", "data"->"{}"];
        $nodes = [];
        $results = explode("\n", $result);

        $count = 0;
        $count2 = 0;
        foreach($results as $line) {
          $count += 1;
          //print("$count - Line: $line<br>");

          $parsed = explode(" ", $line);
          //print("$parsed[1]<br>");
          if(strpos($line, "ExitNode") !== false) {
            $nodes[$count2]["ExitNode"] = "$parsed[1]";
          } else if(strpos($line, "Published") !== false) {
            $nodes[$count2]["Published"] = "$parsed[1] $parsed[2]";
          } else if(strpos($line, "LastStatus") !== false) {
            $nodes[$count2]["LastStatus"] = "$parsed[1] $parsed[2]";
          } else if(strpos($line, "ExitAddress") !== false) {
            //print("$parsed[2]<br>");
            $nodes[$count2]["ExitAddress"] = "$parsed[1]";
            $nodes[$count2]["ExitAddress-Date"] = "$parsed[2] $parsed[3]";
          } else {
            //$nodes[$count2]["Unknown"][$count] = "$parsed[1]";
          }

          if("$count" === "4") {
            //print("End<br>");
            $count = 0;
            $count2 += 1;
          }
        }

        return $nodes;
      } catch(Exception $e) {
        throw $e; // $result === false calls here
      }
    }
  }
?>