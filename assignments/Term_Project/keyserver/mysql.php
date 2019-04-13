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
        WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'GPGKeys')
      ";

      $sql = "CREATE TABLE GPGKeys (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        keyid TEXT NOT NULL,
        fingerprint TEXT NOT NULL,
        hash TEXT NOT NULL,
        uploaderipaddress TEXT NOT NULL,
        checksum TEXT NOT NULL,
        dateadded TEXT NOT NULL
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

  public function insertData($keyid, $fingerprint, $hash, $ipaddress, $checksum, $dateadded) {
    try {
      $conn = $this->connectMySQL();
      $statement = $conn->prepare("INSERT INTO GPGKeys (keyid, fingerprint, hash, uploaderipaddress, checksum, dateadded)
                                   VALUES (:keyid, :fingerprint, :hash, :uploaderipaddress, :checksum, :dateadded)");

      $statement->execute([
        'keyid' => $keyid,
        'fingerprint' => $fingerprint,
        'hash' => $hash,
        'uploaderipaddress' => $ipaddress,
        'checksum' => $checksum,
        'dateadded' => $dateadded
      ]);
      /*
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      keyid TEXT NOT NULL,
      fingerprint TEXT NOT NULL,
      hash TEXT NOT NULL,
      uploaderipaddress TEXT NOT NULL,
      checksum TEXT NOT NULL,
      dateadded TEXT NOT NULL
      */
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Insert Data into Table Failed: " . $e->getMessage() . "</p><br>";
    }
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

      /*
      $sql = "CREATE TABLE GPGKeys (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        keyid TEXT NOT NULL,
        fingerprint TEXT NOT NULL,
        hash TEXT NOT NULL,
        uploaderipaddress TEXT NOT NULL,
        checksum TEXT NOT NULL,
        dateadded TEXT NOT NULL
      )";
      */

      $sqlData = sqlData();
      $sqlData->setRowID($results['id']);
      $sqlData->setKeyID($results['keyid']);
      $sqlData->setFingerprint($results['fingerprint']);
      $sqlData->setHash($results['hash']);
      $sqlData->setIP($results['uploaderipaddress']);
      $sqlData->setCheckSum($results['checksum']);
      $sqlData->setAdded($results['dateadded']);

      return $sqlData;
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Read Data from Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }
}

class sqlData {
  private $rowid = Null;
  private $keyid = Null;
  private $fingerprint = Null;
  private $hash = Null;
  private $uploaderipaddress = Null;
  private $checksum = Null;
  private $dateadded = Null;

  private function setRowID($rowid) {
    $this->rowid = $rowid;
  }

  public function getRowID() {
    return $this->rowid;
  }

  private function setKeyID($keyid) {
    $this->keyid = $keyid;
  }

  public function getKeyID() {
    return $this->keyid;
  }

  private function setFingerprint($fingerprint) {
    $this->fingerprint = $fingerprint;
  }

  public function getFingerprint() {
    return $this->fingerprint;
  }

  private function setHash($hash) {
    $this->hash = $hash;
  }

  public function getHash() {
    return $this->hash;
  }

  private function setIP($ipaddress) {
    $this->ipaddress = $ipaddress;
  }

  public function getIP() {
    return $this->ipaddress;
  }

  private function setCheckSum($checksum) {
    $this->checksum = $checksum;
  }

  public function getCheckSum() {
    return $this->checksum;
  }

  private function setAdded($dateadded) {
    $this->dateadded = $dateadded;
  }

  public function getAdded() {
    return $this->dateadded;
  }
}
?>