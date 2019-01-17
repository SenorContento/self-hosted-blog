<?php
  $loadHeader = new loadHeader();
  $loadHeader->printStartHeader();

  if(isset($PageTitle)) {
    $loadHeader->printTitle($PageTitle);
  } else {
    $loadHeader->printTitle('web.SenorContento.com');
  }

  $loadHeader->printAuthorMetadata();
  $loadHeader->printMobileStyling();

  $loadHeader->printStylesheets();
  $loadHeader->printScripts();

  $loadHeader->printIcons();
  $loadHeader->printCustomPageHeader();
  $loadHeader->printEndHeader();
  $loadHeader->printStartBody();
  $loadHeader->printNotificationMessage();

  class loadHeader {
    public function printStartHeader() {
      print('<!DOCTYPE html>');
      print("\n" . '<html lang="en">');
      print("\n\t" . '<head>');
    }

    public function printTitle($pageTitle) {
      print("\n\t\t" . '<title>' . $pageTitle . '</title>');
    }

    public function printAuthorMetadata() {
      print("\n\t\t" . '<link rel="author" href="/humans.txt" />');
    }

    public function printMobileStyling() {
      print("\n\n\t\t" . '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
      print("\n\t\t" . '<meta name="theme-color" content="green">');
    }

    public function printStylesheets() {
      print("\n\n\t\t" . '<link rel="stylesheet" href="/css/main.css">');

      //print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web.min.css">');
      //print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web.css">');
      print("\n\n\t\t" . '<link rel="stylesheet" href="/css/material-components-web-dark-custom.css">');

      //print("\n\n\t\t" . '<link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">');
      //print("\n\n\t\t" . '<link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.css">');
    }

    public function printScripts() {
      //print("\n\n\t\t" . '<script src="/js/material-components-web.min.js" type="text/javascript"></script>');
      print("\n\n\t\t" . '<script src="/js/material-components-web.js" type="text/javascript"></script>');

      //print("\n\n\t\t" . '<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js" type="text/javascript"></script>');
      //print("\n\n\t\t" . '<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.js" type="text/javascript"></script>');
    }

    public function printIcons() {
      print("\n\n\t\t" . '<link rel="icon" href="/images/svg/SenorContento.svg">');
      print("\n\t\t" . '<link rel="icon" href="/images/png/SenorContento-1024x1024.png">');
    }

    public function printCustomPageHeader() {
      print("\n");
      if (function_exists('customPageHeader')){
        customPageHeader();
      }
    }

    public function printEndHeader() {
      print("\n\t" . '</head>');
    }

    public function printStartBody() {
      print("\n\t" . "<body>" .
      "<center>");
    }

    public function printNotificationMessage() {
      print("\n\t\t" . '<!--This header below is an experiment with CSS and PHP. It is not complete, so just ignore it!!!-->');
      print("\n\t\t" . '<header>');

      print("\n\t\t\t" . '<p>I am replacing my theme with <a href="https://material.io/">Material Design</a>.');
      print(" " . 'Instructions for <a href="https://material.io/collections/developer-tutorials/#web">Web Development</a>.</p>');
      print("\n\t\t" . '</header>');
      print("\n\t\t" . '<div id="container">');
    }
  }
?>