<?php $PageTitle="CSCI 3000 - Web Development";

  function customPageHeader() {
    print('<link rel="manifest" href="/manifest.json">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>
    <a href="/HTML-CSS/">Assignment 1 - HTML-CSS</a>
<?php
  function customPageFooter() {
    print('
  <script>
    if (\'serviceWorker\' in navigator) {
      console.log("Will the service worker register?");
      navigator.serviceWorker.register(\'service-worker.js\')
        .then(function(reg){
          console.log("Yes, it did.");
        }).catch(function(err) {
          console.log("No it didn\'t. This happened: ", err)
        });
      }
  </script>
'); }

include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>