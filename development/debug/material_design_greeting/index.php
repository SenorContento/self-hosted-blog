<?php
  function customPageHeader() {
    print("\t\t" . '<link rel="manifest" href="/manifest.json">');

    print("\n\n\t\t" . '<style>' .
    "\n\t\t\t" . 'div.greet-message {' .
    "\n\t\t\t\t" . 'color: #90EE90;' .
    "\n\t\t\t" . '}' .
    "\n\t\t" . '</style>');

    /*
      div.greet-message {
        color: #90EE90;
      }
    */

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
  $greeting = new materialDesignTest();

  $loadPage->loadHeader();

  $greeting->loadTextFields();
  $greeting->loadScripts();

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

  class materialDesignTest {
    public function loadTextFields() {
      print('<br><br>');
      print('<p>The below greeting demo is a test to ensure Material Design works as intended! The demo was modified from <a href="https://stackblitz.com/edit/mdc-web-quick-start-demo">this live demo program</a>.</p>');

      print('<h6 class="mdc-typography mdc-typography--headline6 demo-headline">Demo Greeting</h6>');

      print('<div class="mdc-text-field">');
      print('<input type="text" id="first-name-input" class="mdc-text-field__input">');
      print('<label class="mdc-floating-label" for="first-name">First Name</label>');
      print('<div class="mdc-line-ripple"></div>');
      print('</div>');

      print('<br>');

      print('<div class="mdc-text-field">');
      print('<input type="text" id="last-name-input" class="mdc-text-field__input">');
      print('<label class="mdc-floating-label" for="last-name">Last Name</label>');
      print('<div class="mdc-line-ripple"></div>');
      print('</div>');

      print('<br>');

      print('<button class="mdc-button greet-button">Greet</button>');

      print('<br>');

      //print('<div class="mdc-typography mdc-typography--overline greet-message"></div>');
      print('<div class="mdc-typography mdc-typography--overline greet-message"></div>');

      print('');

      print('<br>');

      print('<p>The above "theme" isn\'t a true dark theme, it is just what I could hack together in 30 minutes from the light theme.</p>');
    }

    public function loadScripts() {
      print("<script>");

      print('const buttons = document.querySelectorAll(\'.mdc-button\');');
      print("\n\t" . 'for (const button of buttons) {');
      print("\n\t\t" . 'mdc.ripple.MDCRipple.attachTo(button);');
      print("\n\t" . '}');

      //print("mdc.textField.MDCTextField.attachTo(document.querySelector('.mdc-text-field'));");
      print("\n\n" . 'const textFields = document.querySelectorAll(\'.mdc-text-field\');');
      print("\n\t" . 'for (const textField of textFields) {');
      print("\n\t\t" . 'mdc.textField.MDCTextField.attachTo(textField);');
      print("\n\t" . '}');

      print("\n\n" . 'const greetMessageEl = document.querySelector(\'.greet-message\');');
      print("\n" . 'const greetButton = document.querySelector(\'.greet-button\');');

      print("\n\n" . 'greetButton.addEventListener(\'click\', () => {');

      print("\n\t" . 'const firstNameInput = document.querySelector(\'#first-name-input\').value;');
      print("\n\t" . 'const lastNameInput = document.querySelector(\'#last-name-input\').value;');

      print("\n\n\t" . 'let name;');
      print("\n\t" . 'if (firstNameInput && lastNameInput) {');
      print("\n\t\t" . 'name = firstNameInput + \' \' + lastNameInput;');

      // This works because this code is never reached if the first statement was true
      print("\n\t" . '} else if (firstNameInput) {');
      print("\n\t\t" . 'name = firstNameInput');
      print("\n\t" . '} else if (lastNameInput) {');
      print("\n\t\t" . 'name = lastNameInput');

      print("\n\t" . '} else {');
      print("\n\t\t" . 'name = \'Anonymous\';');
      print("\n\t" . '}');

      print("\n\n\t" . 'greetMessageEl.textContent = \'Hello, \' + name + \'!\';');
      print("\n\t" . '});');

      print("</script>");
    }
  }
?>