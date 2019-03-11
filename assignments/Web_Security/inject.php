<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment12.css">');
    //print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  $loadPage = new loadPage();
  $sqlCommands = new sqlCommands();
  $mainPage = new InjectionMySQL();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  $mainPage->printForm();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          "assignment12inject",
                          getenv('alex.server.assignment12.inject.password'),
                          "sqlinjectionzone");

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->getValues();

  $loadPage->loadFooter();

  class InjectionMySQL {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printForm() {
      print("<fieldset><legend>Submit Data</legend>");

      print('<form method="POST">
      <label>Submit Unsafe Form Data:</label><br>');

      $inject = isset($_REQUEST["inject"]) ? filter_var($_REQUEST["inject"], FILTER_VALIDATE_BOOLEAN) : false;
      if($inject) {
        print('<label>Retrieve Row 1 - Normal </label><input id="retrieve-row" name="inject" value="false" type="radio"><br>
        <label>Retrieve Row 1 - Injection </label><input id="retrieve-row" name="inject" value="true" type="radio" checked="checked"><br>');
      } else {
        print('<label>Retrieve Row 1 - Normal </label><input id="retrieve-row" name="inject" value="false" type="radio" checked="checked"><br>
        <label>Retrieve Row 1 - Injection </label><input id="retrieve-row" name="inject" value="true" type="radio"><br>');
      }

      print('<br>
      <button type="submit">Submit Form</button> <button name="reset" value="true" type="submit">Reset Table</button>');

      print("</form></fieldset>");
    }

    public function getValues() {
      global $sqlCommands;
      $reset = isset($_REQUEST["reset"]) ? filter_var($_REQUEST["reset"], FILTER_VALIDATE_BOOLEAN) : false;
      $inject = isset($_REQUEST["inject"]) ? filter_var($_REQUEST["inject"], FILTER_VALIDATE_BOOLEAN) : false;

      if($reset) {
        $sqlCommands->resetTable();
        $sqlCommands->insertData("Example Data");
        $sqlCommands->readDataUnsafe(false);
      } else if($inject) {
        $sqlCommands->readDataUnsafe(true);
      } else {
        $sqlCommands->readDataUnsafe(false);
      }
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 12 - Unsafe SQL";
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

    public function readDataUnsafe($isinject) {
      try {
        $conn = $this->connectMySQL();

        // http://php.net/manual/en/function.htmlspecialchars.php
        //$sql = "SELECT * FROM Assignment12"; // Display Everything
        //$unsafe = "1 OR 1=1 --";

        // I am not actually going to let arbitrary SQL Injection on my server,
        // so even though I am letting SQL injection happen on this server, it is
        // going to be controlled. The only reason I don't just simulate the injection
        // is because my grade depends on actually having an injection.
        if($isinject) {
          $unsafe = "1 OR 1=1; INSERT INTO Assignment12 (statement) VALUES (\"I am an Injected SQL Row!!!\") --";
        } else {
          $unsafe = "1";
        }
        //$sql = "INSERT INTO Assignment12 (statement) VALUES (\"Test\")";
        $sql = "SELECT * FROM Assignment12 WHERE id = " . $unsafe . " LIMIT 1";
        $injectme = $conn->prepare($sql);
        $injectme->execute(array($unsafe));
        $result = $injectme->fetchAll(PDO::FETCH_ASSOC);

        print("<p id=\"injection-status\">");
        //print("Results: ");
        //var_dump($result);

        print("The Form Data: \"<span style='color: red'>" . $unsafe . "</span>\"");
        print(" was injected as <br>");

        print("SQL Query: ");
        print("\"<span style='color: red'>" . "SELECT * FROM Assignment12 WHERE id = " . $unsafe . " LIMIT 1" . "</span>\"");
        print("</p>");

        print("<p id=\"injection-info\">");
        print("Because of the nature of this SQL statement, you may have to inject twice to read the first injection's results!!!");
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