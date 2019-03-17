<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentSix();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  $mainPage->printIndex();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentSix {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 6</a>');
      //print('<br>');
    }

    public function printIndex() {
      print("<h1>");
      print('<a href="Part_1" class="index-link" style="text-align: center;display: block">Go to Midterm (Part 1)</a>');
      print('<a href="Part_2" class="index-link" style="text-align: center;display: block">Go to Midterm (Part 2)</a>');
      print("</h1>");
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