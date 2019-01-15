<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentIndex();

  $loadPage->loadHeader();

  $mainPage->printCSSAlignment();
  $mainPage->printAssignment1(); //HTML-CSS
  $mainPage->printAssignment2(); //JavaScript-JQuery
  //$mainPage->printAssignment3(); //Responsive
  //$mainPage->printAssignment4(); //PHP
  //$mainPage->printAssignment5(); //MySQL

  //$mainPage->printAssignment6(); //Midterm (Part 1)
  //$mainPage->printAssignment7(); //Midterm (Part 2)
  //$mainPage->printMidterm(); //Midterm (Index)

  //$mainPage->printAssignment8(); //AJAX
  //$mainPage->printAssignment9(); //Python
  //$mainPage->printAssignment10(); //C#.NET-MSSQL
  //$mainPage->printAssignment11(); //Web APIs
  //$mainPage->printAssignment12(); //Web Security
  //$mainPage->printAssignment13(); //Term Project
  $mainPage->printEndCSSAlignment();

  $loadPage->loadFooter();

  class homeworkAssignmentIndex {
    public function printCSSAlignment() {
      print('<div style="text-align: center;">');
      print('<div style="display: inline-block; text-align: left;">');
    }

    public function printEndCSSAlignment() {
      print('</div></div>');
    }

    public function printAssignment1() {
      print('<a href="HTML-CSS">Go to HTML-CSS Assignment</a></br>');
    }

    public function printAssignment2() {
      print('<a href="Javascript-JQuery">Go to Javascript-JQuery Assignment</a></br>');
    }

    public function printAssignment3() {
      print('<a href="Responsive">Go to Responsive Assignment</a></br>');
    }

    public function printAssignment4() {
      print('<a href="PHP">Go to PHP Assignment</a></br>');
    }

    public function printAssignment5() {
      print('<a href="MySQL">Go to MySQL Assignment</a></br>');
    }

    /* Midterm */

    public function printAssignment6() {
      print('<a href="Midterm/Part_1">Go to Midterm (Part 1)</a></br>');
    }

    public function printAssignment7() {
      print('<a href="Midterm/Part_2">Go to Midterm (Part 2)</a></br>');
    }

    public function printMidterm() {
      print('<a href="Midterm">Go to Midterm</a></br>');
    }

    /* End Midterm */

    public function printAssignment8() {
      print('<a href="AJAX">Go to AJAX Assignment</a></br>');
    }

    public function printAssignment9() {
      print('<a href="Python">Go to Python Assignment</a></br>');
    }

    public function printAssignment10() {
      print('<a href="C-Sharp.NET_MSSQL">Go to C#.NET-MSSQL Assignment</a></br>');
    }

    public function printAssignment11() {
      print('<a href="Web_APIs">Go to Web APIs Assignment</a></br>');
    }

    public function printAssignment12() {
      print('<a href="Web_Security">Go to Web Security Assignment</a></br>');
    }

    public function printAssignment13() {
      print('<a href="Term_Project">Go to Term Project</a></br>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Homework Assignment Index";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>