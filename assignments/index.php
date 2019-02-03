<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentIndex();

  $loadPage->loadHeader();

  $mainPage->printCSSAlignment();
  $mainPage->printAssignment1(); //HTML-CSS
  $mainPage->printAssignment2(); //JavaScript-JQuery
  $mainPage->printAssignment3(); //Responsive
  $mainPage->printAssignment4(); //PHP
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
      print('<h1>');
    }

    public function printEndCSSAlignment() {
      print('</h1>');
      print('</div></div>');
    }

    public function printAssignment1() {
      print('<a class="index-link" href="HTML-CSS">Go to HTML-CSS Assignment</a>');
    }

    public function printAssignment2() {
      print('<a class="index-link" href="Javascript-JQuery">Go to Javascript-JQuery Assignment</a>');
    }

    public function printAssignment3() {
      print('<a class="index-link" href="Responsive">Go to Responsive Assignment</a>');
    }

    public function printAssignment4() {
      print('<a class="index-link" href="PHP">Go to PHP Assignment</a>');
    }

    public function printAssignment5() {
      print('<a class="index-link" href="MySQL">Go to MySQL Assignment</a>');
    }

    /* Midterm */

    public function printAssignment6() {
      print('<a class="index-link" href="Midterm/Part_1">Go to Midterm (Part 1)</a>');
    }

    public function printAssignment7() {
      print('<a class="index-link" href="Midterm/Part_2">Go to Midterm (Part 2)</a>');
    }

    public function printMidterm() {
      print('<a class="index-link" href="Midterm">Go to Midterm</a>');
    }

    /* End Midterm */

    public function printAssignment8() {
      print('<a class="index-link" href="AJAX">Go to AJAX Assignment</a>');
    }

    public function printAssignment9() {
      print('<a class="index-link" href="Python">Go to Python Assignment</a>');
    }

    public function printAssignment10() {
      print('<a class="index-link" href="C-Sharp.NET_MSSQL">Go to C#.NET-MSSQL Assignment</a>');
    }

    public function printAssignment11() {
      print('<a class="index-link" href="Web_APIs">Go to Web APIs Assignment</a>');
    }

    public function printAssignment12() {
      print('<a class="index-link" href="Web_Security">Go to Web Security Assignment</a>');
    }

    public function printAssignment13() {
      print('<a class="index-link" href="Term_Project">Go to Term Project</a>');
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