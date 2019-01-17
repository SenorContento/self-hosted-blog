<?php
  $loadPage = new loadPage();
  $mainPage = new serverErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class serverErrorMessage {
    public function displayMessage() {
      print('<h1>500 - Internal Server Error (Something Broke On The Server\'s End)</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="500 - Internal Server Error";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>