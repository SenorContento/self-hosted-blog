<?php
// https://stackoverflow.com/a/2397010/6828099
//defined('INCLUDED') or die();
if(!defined('INCLUDED')) {
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
  include($root . "/errors/404/index.php");
  die();
}

class sqlCommandsPiet {
  private $server, $username, $password, $database;

  public function setLogin($server, $username, $password, $database) {
    $this->server = $server;
    $this->username = $username;
    $this->password = $password;
    $this->database = $database;
  }

  public function testConnection() {
    if($this->server === NULL || $this->username === NULL || $this->password === NULL || $this->database === NULL) {
      print("<p class=\"mysql-error\">Sorry, but you are missing a value to connect to the MySQL server! Not Attempting Connection!!!</p><br>");
      die();
    }

    $return_response = $this->connectMySQL();
    if(gettype($return_response) === "string") {
      print("<p class=\"mysql-error\">Connection to MySQL Failed: " . $return_response . "! Not Attempting Connection!!!</p><br>");
      die();
    } else {
      //print("<p class=\"mysql-success\">Connected to MySQL Successfully!!!</p><br>"); //object
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
    echo "<p class=\"mysql-error\">Creating Tables is Not Supported!!!</p><br>";
  }

  public function insertData() {
    echo "<p class=\"mysql-error\">Inserting Data is Not Supported!!!</p><br>";
  }

  public function readData($programid) {
    try {
      $conn = $this->connectMySQL();
      //print("Database: $this->database\n");
      //print("Program ID: $programid\n\n");

      // http://php.net/manual/en/function.htmlspecialchars.php
      // https://phpdelusions.net/pdo_examples/select
      //$sql = "SELECT * FROM programs"; // Display Everything
      // https://stackoverflow.com/a/7357296/6828099
      $sql = "SELECT * FROM programs WHERE programid LIKE concat('%', :programid, '%') LIMIT 20";
      $query = $conn->prepare($sql);
      $query->execute(['programid' => $programid]);
      $results = $query->fetchAll();

      $keys = [];
      foreach($results as $key) {
        //print("ID: " . $key['id']);
        array_push($keys,["id" => $key['id'], "programid" => $key['programid'], "programname" => $key['programname'], "filename" => $key['filename'], "programabout" => $key['programabout'], "programabout" => $key['programabout'], "checksum" => $key['checksum'], "allowed" => $key['allowed'], "banreason" => $key['banreason'], "dateadded" => $key['dateadded']]);
      }

      //var_dump($keys);

      return $keys;
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Read Data from Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }
}
?>