<?php
  // https://stackoverflow.com/a/12202218/6828099 - Interesting Read on PDO Injection
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentTwelve();

  $loadPage->loadHeader();

  //$mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();
  $mainPage->printPageLinks();

  //$mainPage->printTable();

  $loadPage->loadFooter();

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Assignment 12 for CSCI3000 Web Development Class. Named: Web Security Assignment">');
    print("\n\t\t" . '<meta name="keywords" content="HTML,CSS,PHP">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  class homeworkAssignmentTwelve {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 12</a>');
    }

    public function printWarning() {
      print('<h1>Assignment 12 has not been created yet! Please come back later!</h1>');
    }

    public function printPageLinks() {
      print('<div class="index-container">');
      print('<div class="index-alignment">');
      print("<h1>");
      print('<a class="index-link" href="safe.php">Go to Form With Proper SQL Validation</a><br>');
      print('<a class="index-link" href="inject.php">Go to Form With Improper SQL Validation</a>');
      print("</h1>");
      print("</div></div>");
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 12 - Web Security";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>