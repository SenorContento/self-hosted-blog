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
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>