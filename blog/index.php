<?php
  function customPageHeader() {
    // Intentionally left blank!!!
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
      include_once($root . "/php_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/php_data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div style="display: inline-block; text-align: left;"><h1>');
      print('<a class="index-link" href="/blog/about-my-blog/">About My Blog</a>');
      print('<a class="index-link" href="/blog/hacking-the-router/">Hacking My Sagemcom 1704N Router</a>');
      print('</h1></div>');
    }
  }
?>