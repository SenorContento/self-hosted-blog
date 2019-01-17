<?php
  $loadPage = new loadPage();
  $mainPage = new unauthorizedErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class unauthorizedErrorMessage {
    public function displayMessage() {
      print('<h1>401 - Unauthorized (Needs Proper Authentication)</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="401 - Unauthorized";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>