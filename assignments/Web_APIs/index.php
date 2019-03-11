<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment11.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
    //print("\n\t\t" . '<script src="/js/charts.js"></script>');
    print("\n\t\t" . '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>');
  }

  $loadPage = new loadPage();
  $sqlCommands = new sqlCommands();
  $mainPage = new homeworkAssignmentEleven();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->printForm();

  $mainPage->checkMySQLValues();
  $mainPage->printMySQLData();

  list($mushrooms, $onions, $bacon, $pepperoni) = $sqlCommands->sumData();
  $mainPage->setSumData($mushrooms, $onions, $bacon, $pepperoni);
  $mainPage->drawSumTable();
  $mainPage->drawTable();

  $loadPage->loadFooter();

  class homeworkAssignmentEleven {
    private $mushrooms, $onions, $bacon, $pepperoni;

    public function setSumData($mushrooms, $onions, $bacon, $pepperoni) {
      $this->mushrooms = $mushrooms;
      $this->onions = $onions;
      $this->bacon = $bacon;
      $this->pepperoni = $pepperoni;
    }

    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 11</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<h1>Assignment 11 has not been created yet! Please come back later!</h1>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function drawSumTable() {
      $total = $this->mushrooms + $this->onions + $this->bacon + $this->pepperoni;
      print("<fieldset id=\"sum-field\">
        <legend>Total Votes</legend>
        <table>
          <thead>
            <tr>
              <th>Total</th>
              <th>Mushrooms</th>
              <th>Onions</th>
              <th>Bacon</th>
              <th>Pepperoni</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td name=\"Total\">" . $total . "</td>
              <td name=\"Mushrooms\">$this->mushrooms</td>
              <td name=\"Onions\">$this->onions</td>
              <td name=\"Bacon\">$this->bacon</td>
              <td name=\"Pepperoni\">$this->pepperoni</td>
            </tr>
          </tbody>
        </table>
      </fieldset>");
    }

    public function drawTable() {
      $total = $this->mushrooms + $this->onions + $this->bacon + $this->pepperoni;
      //print("<p>$this->mushrooms, $this->onions, $this->bacon, $this->pepperoni</p>");

      print("<fieldset id=\"chart-field\"><legend>Bar Chart</legend>");

      print("
      <script type=\"text/javascript\" src=\"chart.js\"></script>
      <script type=\"text/javascript\">
        CHARTDATA.init([$this->mushrooms, $this->onions, $this->bacon, $this->pepperoni]);
        CHARTDATA.setData();
      </script>
      <div id=\"chart-div\"></div>
      <h3>Total Votes: " . $total . "</h3>");

      print("</fieldset>");
    }

    public function printForm() {
      print("<fieldset><legend>Submit Data</legend>");

      print('<form method="POST">
      <label>Favorite Pizza Toppings:</label><br>
      <label>Bacon </label><input type="checkbox" name="bacon" value="true" checked><br>
      <label>Mushrooms </label><input type="checkbox" name="mushrooms" value="true"><br>
      <label>Onions </label><input type="checkbox" name="onions" value="true"><br>
      <label>Pepperoni </label><input type="checkbox" name="pepperoni" value="true"><br>
      <br>
      <button type="submit">Vote</button>');

      print("</form></fieldset>");
    }

    public function printMySQLData() {
      try {
        global $sqlCommands;
        $conn = $sqlCommands->connectMySQL();

        // This allows me to determine if table is empty (or even exists)
        $sql = "SELECT id FROM Assignment11 LIMIT 1";
        $tableExists = false;
        foreach ($conn->query($sql) as $row) {
          $tableExists = true;
        }
      } catch(PDOException $e) {
        print($e->getMessage());
      }

      if($tableExists) {
        print('
          <fieldset>
            <legend>Last 3 MySQL Entries</legend>
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Mushrooms</th>
                  <th>Onions</th>
                  <th>Bacon</th>
                  <th>Pepperoni</th>
                  </tr>
                  </thead>
                  <tbody>');

        global $sqlCommands;
        $sqlCommands->readData();

        print('
            </tbody>
          </table>
        </fieldset>');
      }
    }

    public function checkMySQLValues() {
      if(!empty($_REQUEST)) {
        $this->verifyMySQLVars();
      }
    }

    public function verifyMySQLVars() {
        // I could refuse to add this to MySQL if the value is not set. It is not set up this way though.
        $mushrooms = isset($_REQUEST["mushrooms"]) ? filter_var($_REQUEST["mushrooms"], FILTER_VALIDATE_BOOLEAN) : false;
        $onions = isset($_REQUEST["onions"]) ? filter_var($_REQUEST["onions"], FILTER_VALIDATE_BOOLEAN) : false;
        $bacon = isset($_REQUEST["bacon"]) ? filter_var($_REQUEST["bacon"], FILTER_VALIDATE_BOOLEAN) : false;
        $pepperoni = isset($_REQUEST["pepperoni"]) ? filter_var($_REQUEST["pepperoni"], FILTER_VALIDATE_BOOLEAN) : false;

        // Convert to True or False Strings
        $mushrooms = $mushrooms ? "True" : "False";
        $onions = $onions ? "True" : "False";
        $bacon = $bacon ? "True" : "False";
        $pepperoni = $pepperoni ? "True" : "False";

        //$sqlCommands = new sqlCommands(); // I cannot set this unless I want to specify the auth multiple times.
        global $sqlCommands;
        $sqlCommands->insertData((string) $mushrooms, (string) $onions, (string) $bacon, (string) $pepperoni);
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 11 - Web APIs";
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
          WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'Assignment11')
        ";

        // https://stackoverflow.com/questions/1262174/mysql-why-use-varchar20-instead-of-varchar255
        // lastname VARCHAR(30) NOT NULL,

        // https://www.eversql.com/sql-syntax-check-validator/ - Validate's SQL syntax
        // https://wtools.io/generate-sql-create-table - SQL Syntax Generator
        // http://sqlfiddle.com/ - Not Checked Out Yet

        $sql = "CREATE TABLE Assignment11 (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          Mushrooms TEXT NOT NULL,
          Onions TEXT NOT NULL,
          Bacon TEXT NOT NULL,
          Pepperoni TEXT NOT NULL
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

    public function insertData($mushrooms, $onions, $bacon, $pepperoni) {
      try {
        $conn = $this->connectMySQL();
        $statement = $conn->prepare("INSERT INTO Assignment11 (Mushrooms, Onions, Bacon, Pepperoni)
                                     VALUES (:Mushrooms, :Onions, :Bacon, :Pepperoni)");

        $statement->execute([
          'Mushrooms' => $mushrooms,
          'Onions' => $onions,
          'Bacon' => $bacon,
          'Pepperoni' => $pepperoni,
        ]);
      } catch(PDOException $e) {
          echo "<p>Insert Data into Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function readData() {
      try {
        $conn = $this->connectMySQL();

        // http://php.net/manual/en/function.htmlspecialchars.php
        //$sql = "SELECT * FROM Assignment11"; // Display Everything
        $sql = "SELECT * FROM Assignment11 ORDER BY id DESC LIMIT 3"; // Limit to Last 3 Entries (Reverse Order) - https://stackoverflow.com/a/14057040/6828099
        foreach ($conn->query($sql) as $row) {
          print("
          <tr>
            <td name=\"ID\">" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
            <td name=\"Mushrooms\">" . htmlspecialchars($row['Mushrooms'], ENT_QUOTES, 'UTF-8') . "</td>
            <td name=\"Onions\">" . htmlspecialchars($row['Onions'], ENT_QUOTES, 'UTF-8') . "</td>
            <td name=\"Bacon\">" . htmlspecialchars($row['Bacon'], ENT_QUOTES, 'UTF-8') . "</td>
            <td name=\"Pepperoni\">" . htmlspecialchars($row['Pepperoni'], ENT_QUOTES, 'UTF-8') . "</td>
          </tr>");
        }
      } catch(PDOException $e) {
          echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function sumData() {
      try {
        $conn = $this->connectMySQL();

        // http://php.net/manual/en/function.htmlspecialchars.php
        //$sql = "SELECT * FROM Assignment11"; // Display Everything
        $sql = "SELECT * FROM Assignment11 ORDER BY id"; // Limit to Last 10 Entries (Reverse Order) - https://stackoverflow.com/a/14057040/6828099
        $mushrooms = $onions = $bacon = $pepperoni = 0;
        foreach ($conn->query($sql) as $row) {
          $mushrooms = filter_var(htmlspecialchars($row["Mushrooms"], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_BOOLEAN) ? ($mushrooms + 1) : $mushrooms;
          $onions = filter_var(htmlspecialchars($row["Onions"], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_BOOLEAN) ? ($onions + 1) : $onions;
          $bacon = filter_var(htmlspecialchars($row["Bacon"], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_BOOLEAN) ? ($bacon + 1) : $bacon;
          $pepperoni = filter_var(htmlspecialchars($row["Pepperoni"], ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_BOOLEAN) ? ($pepperoni + 1) : $pepperoni;
        }

        return [$mushrooms, $onions, $bacon, $pepperoni];
      } catch(PDOException $e) {
          echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
      }
    }
  }
?>