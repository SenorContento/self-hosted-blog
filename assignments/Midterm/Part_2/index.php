<?php
  //$loadPage = new loadPage();
  $mainPage = new homeworkAssignmentSeven();

  //$loadPage->loadHeader();

  $mainPage->redirectToAssignment();
  //$mainPage->printArchiveLink();
  //$mainPage->printTable();
  //$mainPage->printSourceCodeLink();
  //$mainPage->printWarning();
  //$mainPage->printArchiveLink();

  //$loadPage->loadFooter();

  class homeworkAssignmentSeven {
    public function redirectToAssignment() {
      header("Location: brandon_gomez.php");
      exit();
    }

    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 7</a>');
      //print('<br>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<h1>Assignment 7 has not been created yet! Please come back later!</h1>');
    }

    public function printTable() {
      print('<div id="table">
        <fieldset>
          <legend>Example Table</legend>
          <table>
            <tr>
              <th>Students</th>
              <th>Grades</th>
            </tr>
            <tr>
              <td>Alex</td>
              <td>100%</td>
            </tr>
            <tr>
              <td>Josh</td>
              <td>90%</td>
            </tr>
            </table>
          </fieldset>
      </div>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 7 - Midterm (Part 2)";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }
?>