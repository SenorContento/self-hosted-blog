<?php
  function customPageHeader() {
    print("\t\t" . '<link rel="manifest" href="/manifest.json">');

    print("\n" . '
        <script>
          if (\'serviceWorker\' in navigator) {
            console.log("Will the service worker register?");
            navigator.serviceWorker.register(\'service-worker.js\')
              .then(function(reg) {
                console.log("Yes, it did.");
              }).catch(function(err) {
                console.log("No it didn\'t. This happened: ", err)
              });
            }
        </script>');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();

  $mainPage->mainBody();
  $mainPage->loadTextFields();
  $mainPage->loadScripts();

  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="CSCI 3000 - Web Development";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }

  class mainPage {
    public function mainBody() {
      print('<a href="/HTML-CSS/">Assignment 1 - HTML-CSS</a>');

      print('<br><br>');
      print('<a href="/test/failed_pma/">Failed Login Attempts for PHPMyAdmin</a>');
    }

    public function loadTextFields() {
      print('<br><br>');
      print('<center><p>The below textbox is a test to ensure Material Design works as intended!</p>');

      print('<div class="mdc-text-field">');
      print('<input type="text" id="my-text-field" class="mdc-text-field__input">');
      print('<label class="mdc-floating-label" for="my-text-field">Material Design Textbox</label>');
      print('<div class="mdc-line-ripple"></div>');
      print('</div></center>');
    }

    public function loadScripts() {
      print("<script>mdc.textField.MDCTextField.attachTo(document.querySelector('.mdc-text-field'));</script>");
    }
  }
?>