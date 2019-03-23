<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    print("\n\t\t" . '<script src="shell.js"></script>');
    print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $mainPage->printForm();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="Super Secret Game Or Is It!?!?!?";
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
      print('<h1 class="redblue popup ligature unselectable">');
      // https://blog.teamtreehouse.com/an-introduction-to-websockets
      print('Super Secret Terminal Game!!!');
      print('</h1>');

      print("<pre class='ligature copy' id='received'></pre>");
    }

    public function printForm() {
      print('<form id="sendCommand" class="unselectable" method="post">
              <input type="text" id="command" placeholder="Command..." autocomplete="off" required><br>
              <!--<button type="submit">Send Command</button>-->
            </form>');
    }
  }
?>