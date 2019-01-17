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
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>