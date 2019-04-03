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
      print('<div class="index-container">');
      print('<div class="index-alignment">');
      print('<h1>');

      print('<a class="index-link" href="/assignments/">Class Assignments</a><br>');
      print('<a class="index-link" href="/development/failed_pma/">Failed Login Attempts for PHPMyAdmin</a><br>');
      print('<a class="index-link" href="/development/apis/">3rd Party Databases and APIs to Checkout</a><br>');
      print('</h1></div>');
    }
  }
?>