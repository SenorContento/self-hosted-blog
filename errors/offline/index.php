<?php
  $loadPage = new loadPage();
  $mainPage = new offlineErrorMessage();

  $loadPage->loadHeader();
  $mainPage->displayMessage();
  $loadPage->loadFooter();

  class offlineErrorMessage {
    public function displayMessage() {
      print('<h1>You are offline!!!</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Offline";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>