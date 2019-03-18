<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    print("\n\t\t" . '<script src="shell.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->mainBody();
  $mainPage->printForm();
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

      print("<pre class='ligature' id='received'></pre>");
    }

    public function printForm() {
      print('<form id="message-form" action="#" method="post">
              <textarea id="message" placeholder="Write your message here..." required></textarea>
              <button type="submit">Send Message</button>
              <button type="button" id="close">Close Connection</button>
            </form>');
    }
  }
?>