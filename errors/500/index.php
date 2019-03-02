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
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }
?>