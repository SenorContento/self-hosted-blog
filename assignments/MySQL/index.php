<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment5.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');
  }

  $loadPage = new loadPage();
  $sqlCommands = new sqlCommands();
  $mainPage = new homeworkAssignmentFive();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();
  $mainPage->printSourceCodeLink();

  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->checkSQLiteValues();
  $mainPage->printMySQLData();
  $mainPage->checkValues();
  $mainPage->printForm();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

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
        print("<p>Connection to MySQL Failed: " . $return_response . "!!!</p>");
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
          WHERE (TABLE_SCHEMA = '$this->database') AND (TABLE_NAME = 'Assignment5')
        ";

        /*
          First Name:
          Last Name:

          Color:

          Hot Food:
          Cold Food:
        */

        // https://stackoverflow.com/questions/1262174/mysql-why-use-varchar20-instead-of-varchar255
        // lastname VARCHAR(30) NOT NULL,

        // https://www.eversql.com/sql-syntax-check-validator/ - Validate's SQL syntax
        // https://wtools.io/generate-sql-create-table - SQL Syntax Generator
        // http://sqlfiddle.com/ - Not Checked Out Yet

        $sql = "CREATE TABLE Assignment5 (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          firstname TEXT NOT NULL,
          lastname TEXT NOT NULL,
          color TEXT NOT NULL,
          food TEXT NOT NULL
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

    public function insertData($fname, $lname, $color, $food) {
      try {
        $conn = $this->connectMySQL();
        $statement = $conn->prepare("INSERT INTO Assignment5 (firstname, lastname, color, food)
                                     VALUES (:fname, :lname, :color, :food)");

        $statement->execute([
          'fname' => $fname,
          'lname' => $lname,
          'color' => $color,
          'food' => $food,
        ]);
      } catch(PDOException $e) {
          echo "<p>Insert Data into Table Failed: " . $e->getMessage() . "</p>";
      }
    }

    public function readData() {
      try {
        $conn = $this->connectMySQL();

        //$sql = "SELECT * FROM Assignment5"; // Display Everything
        $sql = "SELECT * FROM Assignment5 ORDER BY id DESC LIMIT 10"; // Limit to Last 10 Entries (Reverse Order) - https://stackoverflow.com/a/14057040/6828099
        foreach ($conn->query($sql) as $row) {
          print("
          <tr>
            <td>" . $row['id'] . "</td>
            <td>" . $row['firstname'] . "</td>
            <td>" . $row['lastname'] . "</td>
            <td>" . $row['color'] . "</td>
            <td>" . $row['food'] . "</td>
          </tr>");
        }
      } catch(PDOException $e) {
          echo "<p>Read Data from Table Failed: " . $e->getMessage() . "</p>";
      }
    }
  }

  class homeworkAssignmentFive {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 5</a>');
      //print('<br>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<h1>Assignment 5 has not been created yet! Please come back later!</h1>');
    }

    public function checkValues() {
      if(!empty($_POST)) {
        //$this->verifySQLiteVars();
        $this->printData();
      }
    }

    public function checkSQLiteValues() {
      if(!empty($_POST)) {
        /* I am intentionally separating this from checkValues(),
         * so I can insert data into the database before I read the database
         */
        $this->verifySQLiteVars();
      }
    }

    public function verifySQLiteVars() {
        // I could refuse to add this to SQLite if the value is not set. It is not set up this way though.
        $fname = $this->getValue('first_name');
        $lname = $this->getValue('last_name');
        $color = $this->getValue('color');
        $food = $this->getValue('food');

        //$sqlCommands = new sqlCommands(); // I cannot set this unless I want to specify the auth multiple times.
        global $sqlCommands;
        $sqlCommands->insertData($fname, $lname, $color, $food);
    }

    public function getValue($value) {
      $return_me = '';

      print("<script>");
      if(isset($_POST[$value]) && $_POST[$value] !== '') {
        /* Experimenting Around with Keeping Form Options Selected After Submit */
        /*print('
          $(document).ready(function() {
            $(".' . $value . '").val("red");
          });
        ');*/

        $return_me = $_POST[$value];
      } else {
        print('
                $(document).ready(function() {
                  $("label.form-label-' . $value . '").css("color","red");
                  $("label.form-label-' . $value . '").text("Missing " + $("label.form-label-' . $value . '").text());
                });
              ');

        // This breaks the radio labels, but the only way that the option will be missing a value is if the user manually edits it out. E.g. Developer Tools

        $return_me = "Not Set";
      }

      print('</script>');
      return $return_me;
    }

    public function printMySQLData() {
      print('
      <fieldset>
        <legend>Last 10 MySQL Entries</legend>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Color</th>
              <th>Food</th>
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

    public function printData() {
      print('
      <fieldset>
        <legend>Post Data</legend>
        <table>
          <thead>
            <tr>
              <th>Form Item Name</th>
              <th>Form Item Value</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>First Name</td>
              <td>' . $this->getValue('first_name') . '</td>
            </tr>
            <tr>
              <td>Last Name</td>
              <td>' . $this->getValue('last_name') . '</td>
            </tr>

            <tr>
              <td>Color</td>
              <td>' . $this->getValue('color') . '</td>
            </tr>

            <tr>
              <td>Food</td>
              <td>' . $this->getValue('food') . '</td>
            </tr>
          </tbody>
        </table>
      </fieldset>');
    }

    public function printForm() {
      print('
      <fieldset>
        <legend>Example Form</legend>
        <div class="form">
          <form method="post">');

          if(isset($_POST['first_name']) && $_POST['first_name'] !== '') {
            print('
              <label class="form-label-first_name">First Name: </label><input name="first_name" type="text" value="' . $_POST['first_name'] . '"><br>
            ');
          } else {
            print('
              <label class="form-label-first_name">First Name: </label><input name="first_name" type="text"><br>
            ');
          }

          if(isset($_POST['last_name']) && $_POST['last_name'] !== '') {
            print('
              <label class="form-label-last_name">Last Name: </label><input name="last_name" type="text" value="' . $_POST['last_name'] . '">
            ');
          } else {
            print('
              <label class="form-label-last_name">Last Name: </label><input name="last_name" type="text">
            ');
          }

          print('
            <br><br>

            <label class="form-label-color">Pick a Color: </label>
            <select id="option-color" name="color">');

          if(isset($_POST['color']) && $_POST['color'] !== '') {
            print('
              <script>
                $(document).ready(function() {
                  $("#option-color option[value=' . $_POST['color'] . ']").prop("selected", true)
                });
              </script>
            ');
          }

          print('
              <option value="red">Red</option>
              <option value="green">Green</option>
              <option value="blue">Blue</option>
            </select>

            <br><br>');


          print('
            <!--<label>Hot Food: </label><input name="food" value="hot_food" type="radio" checked><br>-->
            <label class="form-label-food">Hot Food: </label><input id="radio-hot-food" class="radio-food" name="food" value="hot-food" type="radio" checked="checked"><br>
            <label class="form-label-food">Cold Food: </label><input id="radio-cold-food" class="radio-food" name="food" value="cold-food" type="radio">
          ');

          if(isset($_POST['food']) && $_POST['food'] !== '') {
            print("
              <script>
                $(document).ready(function() {
                  $('#radio-" . $_POST['food'] . "').attr('checked', true);
                });
              </script>
            ");
          }

          print('
            <br><br>

            <input type="submit">
          </form>
        </div>
      </fieldset>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 5 - MySQL";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>