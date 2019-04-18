<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    print("\n\t\t" . '<script src="unblur.js"></script>');
  }

  function customPageFooter() {
    print("\n\t\t" . '<link rel="stylesheet" href="blur.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Blur to Crisp Image Loader!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Blur,Crisp,Image,Loader">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printImage();
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function printImage() {
      // https://css-tricks.com/the-blur-up-technique-for-loading-background-images/
      print("<div class='image'><img onload='loadImage(this, \"/images/jpg/that-red-tree/crayon.jpg\")' src='/images/jpg/blur/that-red-tree/crayon.jpg' class='redtree blur'></img></div><br>");
      print("<div class='image'><img onload='loadImage(this, \"/images/jpg/that-red-tree/original.jpg\")' src='/images/jpg/blur/that-red-tree/original.jpg' class='redtree blur'></img></div>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Blur to Crisp Image Loader Test!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>