<?php
  // https://stackoverflow.com/a/3406181/6828099
  // This is used to convert all warnings, errors, etc... into exceptions that I can handle.
  set_error_handler(
    function ($severity, $message, $file, $line) {
      throw new ErrorException($message, $severity, $severity, $file, $line);
    }
  );

  // https://stackoverflow.com/a/2397010/6828099
  define('INCLUDED', 1);
  require_once 'mysql-pgp.php';
  require_once 'mysql-piet.php';

  $mainPage = new SearchEngine();
  $sqlPGP = new sqlCommandsPGP();
  $sqlPiet = new sqlCommandsPiet();

  $sqlPGP->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlPGP->testConnection();
  $sqlPGP->connectMySQL();

  $sqlPiet->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.piet.database'));

  $sqlPiet->testConnection();
  $sqlPiet->connectMySQL();


  $mainPage->printAPI();

  class SearchEngine {
    public function printAPI() {
      try {
        if(!empty($_REQUEST)) {
          $programid = isset($_REQUEST["programid"]) ? $_REQUEST["programid"] : NULL;
          $keyid = isset($_REQUEST["keyid"]) ? $_REQUEST["keyid"] : NULL;

          if(isset($programid)) {
            header("Content-Type: application/json");

            //$generator = isset($_REQUEST["generator"]) ? $_REQUEST["generator"] : "pseudo";
            //print($manager->formatForSQL($this->grabData($bytes, $generator))); // To specify a custom generator

            global $sqlPiet;
            $piet_response = $sqlPiet->readData($programid);
            // htmlspecialchars($results['failed'], ENT_QUOTES, 'UTF-8')

            if(sizeof($piet_response) === 0) {
              $jsonArray = ["error" => "Program Doesn't Exist!!!"];
            } else {
              $jsonArray = $piet_response;
            }

            $json = json_encode($jsonArray);
            print($json);

            die();
          } else if(isset($keyid)) {
            header("Content-Type: application/json");

            //$generator = isset($_REQUEST["generator"]) ? $_REQUEST["generator"] : "pseudo";
            //print($manager->formatForSQL($this->grabData($bytes, $generator))); // To specify a custom generator

            global $sqlPGP;
            $key_response = $sqlPGP->readData($keyid);
            // htmlspecialchars($results['failed'], ENT_QUOTES, 'UTF-8')

            if(sizeof($key_response) === 0) {
              $jsonArray = ["error" => "PGP Key Doesn't Exist!!!"];
            } else {
              $jsonArray = $key_response;
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

    // I really just need a PHP file I can include all my standard functions from
    // Perhaps I can create a "Standard Functions" class and just include the file to use it.
    private function boolToString($bool) {
      return $bool ? 'true' : 'false';
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
  }
?>