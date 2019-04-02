<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentThirteen();

  $loadPage->loadHeader();

  //$mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  //$mainPage->redirectMe();
  $mainPage->printWarning();
  $mainPage->printSourceCodeLink();
  //$mainPage->printDomainURL();

  //$mainPage->printTable();

  $loadPage->loadFooter();

  class homeworkAssignmentThirteen {
    public function redirectMe() {
      //print("Address: " . $_SERVER['SERVER_NAME']); // The fact that this works (and looking at curl), I can conclude that PHP waits until execution is finished before it even begins to send data. It's like my Python Script.
      $host = (getenv('alex.server.type') === "production") ? "term.senorcontento.com" : "term.project";

      if($host !== $_SERVER['SERVER_NAME']) {
        header("Location: https://" . $host . "/");
        exit();
      }
    }

    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 13</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<div class="warning">Assignment 13 has not been created yet! Please come back later!</div><br>');
    }

    public function printSourceCodeLink() {
      // I had to manually specify the source URL as the term project being on it's own domain messed up the link - /blob/master/assignments/Term_Project/index.php
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
    }

    public function printDomainURL() {
      print('<br>');
      print('<a id="domain-url" href="https://term.web.senorcontento.com/">Check Out This Project On It\'s Own Domain!!!</a>');
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
      $PageTitle="Assignment 13 - Term Project";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>