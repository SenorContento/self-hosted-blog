<?php
  // https://stackoverflow.com/a/12202218/6828099 - Interesting Read on PDO Injection
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentTwelve();

  $loadPage->loadHeader();

  //$mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  $mainPage->printWarning();

  //$mainPage->printTable();

  $loadPage->loadFooter();

  class homeworkAssignmentTwelve {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 12</a>');
    }

    public function printWarning() {
      print('<h1>Assignment 12 has not been created yet! Please come back later!</h1>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 12 - Web Security";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }
?>