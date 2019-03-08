<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment11.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
    print("\n\t\t" . '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEleven();

  $loadPage->loadHeader();

  //$mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  $mainPage->printWarning();

  $mainPage->drawTable();

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

    public function drawTable() {
      print("
      <script type=\"text/javascript\" src=\"chart.js\"></script>
      <div id=\"chart_div\"></div>");
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
?>