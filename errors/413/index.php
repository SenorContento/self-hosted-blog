<?php
  $loadPage = new loadPage();
  $mainPage = new fileNotFoundErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class fileNotFoundErrorMessage {
    public function displayMessage() {
      print('<h1>413 - File Too Large</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="413 - File Too Large";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>