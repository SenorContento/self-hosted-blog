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
      print('<div style="display: inline-block; text-align: left;"><h1>');
      print('<a href="/assignments/">Class Assignments</a>');
      print('</br>');

      print('<a href="/test/failed_pma/">Failed Login Attempts for PHPMyAdmin</a>');
      print('</br>');
      print('<a href="/apis/">3rd Party Databases and APIs to Checkout</a>');
      print('</h1></div>');
    }
  }
?>