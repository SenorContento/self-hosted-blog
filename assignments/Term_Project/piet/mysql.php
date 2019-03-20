<?php
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
      print("<p>Sorry, but you are missing a value to connect to the MySQL server! Not Attempting Connection!!!</p>");
      die();
    }

    $return_response = $this->connectMySQL();
    if(gettype($return_response) === "string") {
      print("<p>Connection to MySQL Failed: " . $return_response . "! Not Attempting Connection!!!</p>");
      die();
    } else {
      print("<p>Connected to MySQL Successfully!!!</p>"); //object
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
        programabout TEXT NOT NULL
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
        echo "<p>Create Table Failed: " . $e->getMessage() . "</p>";
    }
  }

  public function insertData($programid, $programname, $filename, $ipaddress, $programabout) {
    try {
      $conn = $this->connectMySQL();
      $statement = $conn->prepare("INSERT INTO programs (programid, programname, filename, uploaderipaddress, programabout)
                                   VALUES (:programid, :programname, :filename, :uploaderipaddress, :programabout)");

      $statement->execute([
        'programid' => $programid,
        'programname' => $programname,
        'filename' => $filename,
        'uploaderipaddress' => $ipaddress,
        'programabout' => $programabout,
      ]);
    } catch(PDOException $e) {
        echo "<p>Insert Data into Table Failed: " . $e->getMessage() . "</p>";
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
        </tr>");
      }
    } catch(PDOException $e) {
        echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
    }
  }
}
?>