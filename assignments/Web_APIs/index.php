<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEleven();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  //$mainPage->printTable();
  //$mainPage->printSourceCodeLink();
  $mainPage->printWarning();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentEleven {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 11</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 11 has not been created yet! Please come back later!</h1></center>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
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
      $PageTitle="Assignment 11 - Web APIs";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>