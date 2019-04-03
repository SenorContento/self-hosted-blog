<?php
// https://stackoverflow.com/a/2397010/6828099
//defined('INCLUDED') or die();
if(!defined('INCLUDED')) {
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
  include($root . "/errors/404/index.php");
  die();
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
    try {
      $conn = $this->connectMySQL();

      // https://stackoverflow.com/a/8829122/6828099
      $checkTableSQL = "SELECT count(*)
        FROM information_schema.TABLES
        WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'VirusScans')
      ";

      $sql = "CREATE TABLE VirusScans (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        programid TEXT NOT NULL,
        failed BOOLEAN NOT NULL
      )";

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
        //echo $sql . "<br>" . $e->getMessage();
        echo "<p class=\"mysql-error\">Create Table Failed: " . $e->getMessage() . "</p><br>";
    }
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
      $sql = "SELECT * FROM VirusScans WHERE programid=:programid LIMIT 1";
      $query = $conn->prepare($sql);
      $query->execute(['programid' => $programid]);
      $results = $query->fetch();

      //var_dump($results);

      //print("ID: " . htmlspecialchars($results['id'], ENT_QUOTES, 'UTF-8') . "\n");
      //print("Program ID: " . htmlspecialchars($results['programid'], ENT_QUOTES, 'UTF-8') . "\n");
      //print("Failed: " . htmlspecialchars($results['failed'], ENT_QUOTES, 'UTF-8') . "\n");

      return["id" => $results['id'], "programid" => $results['programid'], "failed" => $results['failed']];
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Read Data from Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }
}
?>