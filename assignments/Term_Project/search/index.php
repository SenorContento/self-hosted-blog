<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="delayed.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');
  }

  function customMetadata() {
    //print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Template For Alex\'s PHP Pages!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Search,Term,Project,MySQL,PHP,AJAX">');
    //print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    //print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->printForm();
  $loadPage->loadFooter();

  class mainPage {
    public function printForm() {
      print("<p>This Page is Just A Template!!!</p>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Search Term Project!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>