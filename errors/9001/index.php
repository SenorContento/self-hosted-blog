<?php
  $loadPage = new loadPage();
  $mainPage = new over9000Message();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class over9000Message {
    public function displayMessage() {
      print('<h1>9001 - Error Over 9000!!!</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="9001 - Error Over 9000!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>