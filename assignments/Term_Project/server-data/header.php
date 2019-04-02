<?php
  $loadHeader = new loadHeader();
  $loadHeader->printStartHeader();

  if(isset($PageTitle)) {
    $loadHeader->printTitle($PageTitle);
  } else if(getenv('alex.server.page.title') !== false) {
    $loadHeader->printTitle(getenv('alex.server.page.title'));
  } else {
    //$loadHeader->printTitle('web.SenorContento.com');
    $loadHeader->printTitle('Alex\'s Site');
  }

  $loadHeader->printMetadata();
  $loadHeader->printCustomMetadata();
  $loadHeader->printMobileStyling();

  $loadHeader->printStylesheets();
  $loadHeader->printScripts();

  $loadHeader->printIcons();
  //$loadHeader->printServiceWorker();

  $loadHeader->printCustomPageHeader();
  $loadHeader->printEndHeader();
  $loadHeader->printStartBody();
  //$loadHeader->printNotificationMessage();
  $loadHeader->printEndStartBody();

  class loadHeader {
    public $domain = "https://term.senorcontento.com";

    public function printStartHeader() {
      print('<!DOCTYPE html>');
      print("\n" . '<html lang="en">');
      print("\n\t" . '<head>');
      print("\n\t\t" . '<!-- Server Name: "' . getenv('alex.server.name') . '" and Server Type: "' . getenv('alex.server.type') . '" -->');
    }

    public function printTitle($pageTitle) {
      print("\n\t\t" . '<title>' . $pageTitle . '</title>');
    }

    public function printMetadata() {
      //print("\n\t\t" . '<meta charset="UTF-8">');
      print("\n\t\t" . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
      print("\n\t\t" . '<link rel="canonical" href="' . $this->domain . $_SERVER['REQUEST_URI'] . '" />');
      print("\n\t\t" . '<link rel="author" href="https://web.senorcontento.com/humans.txt" />');
      print("\n\t\t" . '<meta name="author" content="Alex Contento">');
      print("\n\t\t" . '<meta name="copyright" content="GPL-3 License">');
      print("\n\t\t" . '<meta name="license" content="/LICENSE.md">');
    }

    public function printMobileStyling() {
      print("\n\n\t\t" . '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
      print("\n\t\t" . '<meta name="theme-color" content="green">');
    }

    public function printStylesheets() {
      print("\n\n\t\t" . '<link rel="stylesheet" href="/css/main.css">');

      //print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web.min.css">');
      //print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web.css">');
      //print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web-dark-custom.css">');

      //print("\n\n\t\t" . '<link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">');
      //print("\n\n\t\t" . '<link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.css">');
    }

    public function printScripts() {
      //print("\n\n\t\t" . '<script src="/js/material-components-web.min.js" type="text/javascript"></script>');
      //print("\n\n\t\t" . '<script src="/js/material-components-web.js"></script>');

      //print("\n\n\t\t" . '<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js" type="text/javascript"></script>');
      //print("\n\n\t\t" . '<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.js" type="text/javascript"></script>');
    }

    public function printIcons() {
      print("\n\n\t\t" . '<link rel="icon" href="/images/svg/SenorContento.svg">');
      print("\n\t\t" . '<link rel="icon" href="/images/png/SenorContento-1024x1024.png">');
    }

    public function printServiceWorker() {
      print("\n\n\t\t" . '<link rel="manifest" href="/manifest.json">');

      print("\n" . '
        <script>
          // Push Notifications - https://developers.google.com/web/fundamentals/codelabs/push-notifications/
          if (\'serviceWorker\' in navigator && \'PushManager\' in window) {
            console.log("Will the service worker register?");
            navigator.serviceWorker.register(\'/service-worker.js\')
              .then(function(reg) {
                console.log("Yes, it registered.");
              }).catch(function(err) {
                console.log("No, it didn\'t register. This happened: ", err);
              });
            }
        </script>');
    }

    public function printCustomPageHeader() {
      print("\n");
      if (function_exists('customPageHeader')){
        customPageHeader();
      }
    }

    public function printCustomMetadata() {
      print("\n");
      if (function_exists('customMetadata')){
        customMetadata();
      }
    }

    public function printEndHeader() {
      print("\n\t" . '</head>');
    }

    public function printStartBody() {
      print("\n\t" . "<body>");
    }

    public function printEndStartBody() {
      print('<div id="container">');
    }

    public function printNotificationMessage() {
      print('<header>');
      print('<p>I am going to replace the header with a navigation header later!!!</p>');

      /*
       * So, for this site, I am not bothering with converting it to Material Design.
       * Material Design is something I need to use from the beginning, not retroactively add in later.
       */
      /*print("\n\t\t\t" . '<p class="header-message">I am replacing my theme with <a href="https://material.io/">Material Design</a>.');
      print(" " . 'Instructions for <a href="https://material.io/collections/developer-tutorials/#web">Web Development</a>.</p>');*/
      print('</header>');
    }
  }
?>