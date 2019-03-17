<?php
  $loadPage = new loadPage();
  $mainPage = new tutorialsIndex();

  $loadPage->loadHeader();
  $mainPage->printWarning();
  $loadPage->loadFooter();

  class tutorialsIndex {
    public function printWarning() {
      print('<h1>There are no available tutorials right now! Please come back later!</h1>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Tutorials";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>