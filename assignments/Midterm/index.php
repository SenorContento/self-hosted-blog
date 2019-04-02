<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentSix();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  $mainPage->printIndex();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Assignments 6 and 7 for CSCI3000 Web Development Class. Named: Midterm">');
    print("\n\t\t" . '<meta name="keywords" content="HTML,CSS,PHP,Javascript">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  class homeworkAssignmentSix {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 6</a>');
      //print('<br>');
    }

    public function printIndex() {
      print('<div class="index-container">');
      print('<div class="index-alignment">');
      print("<h1>");
      print('<a href="Part_1" class="index-link">Go to Midterm (Part 1)</a></br>');
      print('<a href="Part_2" class="index-link">Go to Midterm (Part 2)</a>');
      print("</h1>");
      print("</div></div>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 6 and 7 - Midterm";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>