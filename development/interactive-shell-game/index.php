<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    print("\n\t\t" . '<script src="shell.js"></script>');
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
      print('<h1 class="redblue popup ligature">');
      // https://blog.teamtreehouse.com/an-introduction-to-websockets
      print('Something cool will be here soon!!!');
      print('</h1>');
    }
  }
?>