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
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div style="display: inline-block; text-align: left;"><h1>');
      print('<a class="index-link" href="/assignments/">Class Assignments</a>');
      print('<a class="index-link" href="/development/failed_pma/">Failed Login Attempts for PHPMyAdmin</a>');
      print('<a class="index-link" href="/development/apis/">3rd Party Databases and APIs to Checkout</a>');
      print('</h1></div>');
    }
  }
?>