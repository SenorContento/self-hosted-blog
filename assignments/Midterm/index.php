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
      print('<a href="Part_1" style="text-align: center;display: block">Go to Homework Assignment 6 - Midterm (Part 1)</a>');
      print('<a href="Part_2" style="text-align: center;display: block">Go to Homework Assignment 7 - Midterm (Part 2)</a>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 6 and 7 - Midterm";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>