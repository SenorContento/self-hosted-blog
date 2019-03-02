<?php
  $loadPage = new loadPage();
  $mainPage = new fileNotFoundErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class fileNotFoundErrorMessage {
    public function displayMessage() {
      print('<h1>404 - File Not Found</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="404 - File Not Found";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/php_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/php_data/footer.php");
    }
  }
?>