<?php
  function customPageHeader() {
    // Intentionally left blank!!!
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="CSCI 3000 - Web Development";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div style="display: inline-block; text-align: left;"><h1>');
      print('<a class="index-link" href="/assignments/">Class Assignments</a>');
      print('<a class="index-link" href="/debug/failed_pma/">Failed Login Attempts for PHPMyAdmin</a>');
      print('<a class="index-link" href="/apis/">3rd Party Databases and APIs to Checkout</a>');
      print('</h1></div>');
    }
  }
?>