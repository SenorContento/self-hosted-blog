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
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>