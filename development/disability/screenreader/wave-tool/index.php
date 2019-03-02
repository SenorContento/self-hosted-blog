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
      $PageTitle="Links to Wave Tool";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<div style="display: inline-block; text-align: left;"><h1>');
      print('<a class="index-link" href="https://wave.webaim.org/">Visual Screenreader (For Development)</a>');
      print('<a class="index-link" href="https://webaim.org/techniques/screenreader/">Why Developing for Screenreaders is Important</a>');
      print('<a class="index-link" href="https://webaim.org/articles/screenreader_testing/">Best Practices for Testing</a>');
      print('<a class="index-link" href="https://webaim.org/techniques/semanticstructure/">HTML Tags to Be Careful With</a>');
      print('</h1></div>');
    }
  }
?>