<?php
  function customPageHeader() {
    print('<link rel="stylesheet" href="todo.css">');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="TO-DO List";
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
      print('<form><ul class="todo">');

      //<input type="checkbox" name="todo" disabled="disabled" checked>

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Make Site Work on Screen Readers' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Improve APC Interface' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Add API' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Finish Assignments' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Add Material Design Theme' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Finish Service Worker' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Add Support for Those That Are Dyslexic' .
            '</li>');

      print('<li class="todo"><input type="checkbox" name="todo" disabled="disabled"> ' .
            'Improve Site Efficiency Using Chrome Developer Audits' .
            '</li>');

      print('</ul></form>');
    }
  }
?>