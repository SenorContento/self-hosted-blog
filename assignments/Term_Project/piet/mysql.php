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
        WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'programs')
      ";

      $sql = "CREATE TABLE programs (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        programid TEXT NOT NULL,
        programname TEXT NOT NULL,
        filename TEXT NOT NULL,
        uploaderipaddress TEXT NOT NULL,
        programabout TEXT NOT NULL,
        checksum TEXT NOT NULL,
        allowed BOOLEAN NOT NULL,
        banreason TEXT,
        reported BOOLEAN NOT NULL DEFAULT 0,
        cleared BOOLEAN NOT NULL DEFAULT 0,
        dateadded TEXT NOT NULL,
        reportreason TEXT
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

  public function insertData($programid, $programname, $filename, $ipaddress, $programabout, $checksum, $allowed, $banreason, $dateadded) {
    try {
      $conn = $this->connectMySQL();
      $statement = $conn->prepare("INSERT INTO programs (programid, programname, filename, uploaderipaddress, programabout, checksum, allowed, banreason, dateadded)
                                   VALUES (:programid, :programname, :filename, :uploaderipaddress, :programabout, :checksum, :allowed, :banreason, :dateadded)");

      $statement->execute([
        'programid' => $programid,
        'programname' => $programname,
        'filename' => $filename,
        'uploaderipaddress' => $ipaddress,
        'programabout' => $programabout,
        'checksum' => $checksum,
        'allowed' => $allowed,
        'banreason' => $banreason,
        'dateadded' => $dateadded
      ]);
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Insert Data into Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }

  public function readChecksum($checksum) {
    try {
      $conn = $this->connectMySQL();
      $statement = $conn->prepare("SELECT * FROM programs where checksum=:checksum LIMIT 1");

      $statement->execute([
        'checksum' => $checksum
      ]);

      foreach($statement->fetchAll() as $row) {
        # This should only execute once anyway
        return[true, $row['programid'], $row['checksum'], $row['allowed']];
      }

      return[false, "Null", $checksum, 1];
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Read Checksum from Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }

  public function readData() {
    try {
      $conn = $this->connectMySQL();

      // http://php.net/manual/en/function.htmlspecialchars.php
      //$sql = "SELECT * FROM programs"; // Display Everything
      $sql = "SELECT * FROM programs ORDER BY id DESC LIMIT 10"; // Limit to Last 10 Entries (Reverse Order) - https://stackoverflow.com/a/14057040/6828099
      foreach ($conn->query($sql) as $row) {
        print("
        <tr>
          <td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['programid'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['programname'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['filename'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['uploaderipaddress'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['programabout'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['checksum'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['allowed'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['banreason'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['reported'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['cleared'], ENT_QUOTES, 'UTF-8') . "</td>
          <td>" . htmlspecialchars($row['dateadded'], ENT_QUOTES, 'UTF-8') . "</td>
        </tr>");
      }
    } catch(PDOException $e) {
        echo "<p class=\"mysql-error\">Read Data from Table Failed: " . $e->getMessage() . "</p><br>";
    }
  }
}
?>