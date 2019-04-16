<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="delayed.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Bootchart SVG for Server!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Bootchart,Bootchartd,Startup,Timing">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $mainPage->setVars();
  $mainPage->printImage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->loadImage();
  $loadPage->loadFooter();

  class mainPage {
    public $load_svg_path;

    function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->load_svg_path = "/var/log/bootchart.svgz";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->load_svg_path = "bootchart.svgz";
      }
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function printImage() {
      $svg = isset($_REQUEST["svg"]) ? $_REQUEST["svg"] : NULL;

      if(isset($svg)) {
        header('Content-Type: image/svg+xml');
        header('Content-Encoding: gzip');

        print(file_get_contents("$this->load_svg_path"));

        die();
      }
    }

    public function loadImage() {
      print('<embed src=".?svg=yes" type="image/svg+xml"/>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Bootchart Startup Info!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>