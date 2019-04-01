<?php
  // TODO: Read From MySQL Database To See If Failed Virus Scan
  // https://stackoverflow.com/a/3406181/6828099
  // This is used to convert all warnings, errors, etc... into exceptions that I can handle.
  set_error_handler(
    function ($severity, $message, $file, $line) {
      throw new ErrorException($message, $severity, $severity, $file, $line);
    }
  );

  // https://stackoverflow.com/a/2397010/6828099
  define('INCLUDED', 1);
  require_once 'mysql.php';

  $mainPage = new AntivirusCheck();
  $sqlCommands = new sqlCommands();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.piet.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  //$sqlCommands->createTable();

  $mainPage->printAPI();

  class AntivirusCheck {
    public function printAPI() {
      try {
        if(!empty($_REQUEST)) {
          $programid = isset($_REQUEST["programid"]) ? $_REQUEST["programid"] : NULL;

          if(isset($programid)) {
            header("Content-Type: application/json");

            //$generator = isset($_REQUEST["generator"]) ? $_REQUEST["generator"] : "pseudo";
            //print($manager->formatForSQL($this->grabData($bytes, $generator))); // To specify a custom generator

            global $sqlCommands;
            print($sqlCommands->readData($programid));
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