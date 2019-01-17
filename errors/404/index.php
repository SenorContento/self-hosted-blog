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
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>