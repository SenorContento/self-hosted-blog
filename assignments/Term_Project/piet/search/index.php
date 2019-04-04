<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  function customMetadata() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->mainBody();
  $mainPage->printForm();
  $loadPage->loadFooter();

  class mainPage {
    public function printForm() {
      print("Hello");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Piet Search Engine!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>