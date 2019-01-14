<?php
  $loadPage = new loadPage();
  $mainPage = new offlineErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class offlineErrorMessage {
    public function displayMessage() {
      print('<h1>You are offline!!!</a>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Offline";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>