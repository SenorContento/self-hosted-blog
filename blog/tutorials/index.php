<?php
  $loadPage = new loadPage();
  $mainPage = new tutorialsIndex();

  $loadPage->loadHeader();
  $mainPage->printWarning();
  $loadPage->loadFooter();

  class tutorialsIndex {
    public function printWarning() {
      print('<center><h1>There are no available tutorials right now! Please come back later!</h1></center>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Tutorials";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>