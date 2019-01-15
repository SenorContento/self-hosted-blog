<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentNine();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  //$mainPage->printTable();
  $mainPage->printWarning();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentNine {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 9</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 9 has not been created yet! Please come back later!</h1></center>');
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
      $PageTitle="Assignment 9 - Python";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>