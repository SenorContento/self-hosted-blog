<?php
  $loadPage = new loadPage();
  $mainPage = new forbiddenErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class forbiddenErrorMessage {
    public function displayMessage() {
      print('<h1>403 - Forbidden (No Access Period, Not Just Unauthorized)</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="403 - Forbidden";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>