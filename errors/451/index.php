<?php
  $loadPage = new loadPage();
  $mainPage = new legalErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class legalErrorMessage {
    public function displayMessage() {
      print('<h1>Fahrenheit 451 - Taken Down Due to Legal Reasons</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Fahrenheit 451 - Taken Down Due to Legal Reasons";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>