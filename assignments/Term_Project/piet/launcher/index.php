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
      $PageTitle="Piet Launcher!!!";
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
      print('Piet Launcher!!!');
      print('</h1>');

      print('
        <form id="seturl" class="unselectable" method="post">
      ');

      $websocketurl = isset($_REQUEST["piet-url"]) ? $_REQUEST["piet-url"] : "";
      if(isset($websocketurl) && trim($websocketurl) !== '') {
        print('<input id="piet_url" name="piet_url" type="text" placeholder="Program ID..." value="' . $websocketurl . '" autocomplete="off">');
      } else {
        print('<input id="piet_url" name="piet_url" type="text" placeholder="Program ID..." value="5cbe099942391" autocomplete="off">');
      }

      $arguments = isset($_REQUEST["piet-arguments"]) ? $_REQUEST["piet-arguments"] : "";
      if(isset($arguments) && trim($arguments) !== '') {
        print('<input id="arguments" name="piet-arguments" type="text" value="' . $arguments . '" placeholder="Program Arguments..." autocomplete="off">');
      } else {
        print('<input id="arguments" name="piet-arguments" type="text" placeholder="Program Arguments..." autocomplete="off">');
      }

      print('
          <button type="submit">Set URL</button>
          <br>
        </form>
      ');
      print("<pre class='ligature copy' id='received'></pre>");
    }

    public function printForm() {
      print('<form id="sendCommand" class="unselectable" method="post">
              <input type="text" id="command" placeholder="Command..." autocomplete="off" required><br>
              <button type="submit">Send Command</button>
            </form>');
    }
  }
?>