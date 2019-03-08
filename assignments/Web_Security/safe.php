<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="assignment12.css">');
    //print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  $loadPage = new loadPage();
  $sqlCommands = new sqlCommands();
  $mainPage = new SafeMySQL();

  $loadPage->loadHeader();

  $mainPage->printForm();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          "assignment12safe",
                          getenv('alex.server.assignment12.safe.password'),
                          "sqlinjectionfree");

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->getValues();

  $loadPage->loadFooter();

  class SafeMySQL {
    public function printForm() {
      print('<p>I am Safe!!!</p>');
    }

    public function getValues() {
      global $sqlCommands;
      $reset = isset($_REQUEST["reset"]) ? filter_var($_REQUEST["reset"], FILTER_VALIDATE_BOOLEAN) : false;
      $inject = isset($_REQUEST["inject"]) ? filter_var($_REQUEST["inject"], FILTER_VALIDATE_BOOLEAN) : false;

      if($reset) {
        $sqlCommands->resetTable();
        $sqlCommands->insertData("Example Data");
        $sqlCommands->readDataSafe(false);
      } else if($inject) {
        $sqlCommands->readDataSafe(true);
      } else {
        $sqlCommands->readDataSafe(false);
      }
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 12 - Safe SQL";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
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
          WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'Assignment12')
        ";

        $sql = "CREATE TABLE Assignment12 (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          statement TEXT NOT NULL
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

    public function dropTable() {
      try {
        $conn = $this->connectMySQL();

        // https://stackoverflow.com/a/8829122/6828099
        $checkTableSQL = "SELECT count(*)
          FROM information_schema.TABLES
          WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'Assignment12')
        ";

        $sql = "DROP TABLE Assignment12";

        $tableExists = false;
        // http://php.net/manual/en/pdo.query.php
        foreach ($conn->query($checkTableSQL) as $row) {
          if($row['count(*)'] > 0)
            $tableExists = true;
        }

        if($tableExists) {
          // use exec() because no results are returned
          $conn->exec($sql);
        }
      } catch(PDOException $e) {
          //echo $sql . "<br>" . $e->getMessage();
          echo "<p>Drop Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function resetTable() {
      $this->dropTable();
      $this->createTable();
    }

    public function insertData($statementstring) {
      try {
        $conn = $this->connectMySQL();
        $statement = $conn->prepare("INSERT INTO Assignment12 (statement)
                                     VALUES (:statement)");

        $statement->execute([
          'statement' => $statementstring,
        ]);
      } catch(PDOException $e) {
          echo "<p>Insert Data into Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function readData() {
      try {
        $conn = $this->connectMySQL();

        // http://php.net/manual/en/function.htmlspecialchars.php
        //$sql = "SELECT * FROM Assignment12"; // Display Everything
        $sql = "SELECT * FROM Assignment12 ORDER BY id DESC LIMIT 10"; // Limit to Last 10 Entries (Reverse Order) - https://stackoverflow.com/a/14057040/6828099
        foreach ($conn->query($sql) as $row) {
          print("
          <tr>
            <td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
            <td>" . htmlspecialchars($row['statement'], ENT_QUOTES, 'UTF-8') . "</td>
          </tr>");
        }
      } catch(PDOException $e) {
          echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function readDataSafe($isinject) {
      try {
        $conn = $this->connectMySQL();

        // http://php.net/manual/en/function.htmlspecialchars.php
        //$sql = "SELECT * FROM Assignment12"; // Display Everything
        //$unsafe = "1 OR 1=1 --";

        if($isinject) {
          $unsafe = "1 OR 1=1; INSERT INTO Assignment12 (statement) VALUES (\"I am an Injected SQL Row!!!\") --";
        } else {
          $unsafe = "1";
        }

        //$sql = "INSERT INTO Assignment12 (statement) VALUES (\"Test\")";
        $sql = "SELECT * FROM Assignment12 WHERE id = ? LIMIT 1";
        $injectme = $conn->prepare($sql);

        $injectme->execute(array($unsafe));
        $result = $injectme->fetchAll(PDO::FETCH_ASSOC);

        print("<p>");
        //print("Results: ");
        //var_dump($result);

        print("<br>SQL Query: ");
        print("SELECT * FROM Assignment12 WHERE id = " . $unsafe . " LIMIT 1");
        print("</p>");

        $count = 1;
        print("<table><thead><th>ID</th><th>Statement</th></thead><tbody>");
        foreach($result as $row) {
          if($count == 11) {
            print("
            <tr>
              <td>$count</td>
              <td>Sorry, I am limiting this to $count entries!!! Resetting Table!!!</td>
            </tr>");
            $this->resetTable();
            $this->insertData("The Table Was Reset!!!");
            break;
          } else {
            print("
            <tr>
              <td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
              <td>" . htmlspecialchars($row['statement'], ENT_QUOTES, 'UTF-8') . "</td>
            </tr>");
            $count++;
          }
        }
        print("</tbody></table>");
      } catch(PDOException $e) {
        echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
      }
    }
  }
?>