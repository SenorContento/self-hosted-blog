<?php
  $loadPage = new loadPage();
  $mainPage = new overloadedMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class overloadedMessage {
    public function displayMessage() {
      print('<h1>503 - Overloaded Or Down For Maintenance</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="503 - Overloaded Or Down For Maintenance";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>