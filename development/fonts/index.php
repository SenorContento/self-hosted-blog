<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="font-debug.css">');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="CSCI 3000 - Web Development";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div style="display: inline-block; text-align: left;">');
      print(file_get_contents("debug.html"));
      print('</div>');
    }
  }
?>