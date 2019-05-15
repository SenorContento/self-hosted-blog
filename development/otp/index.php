<?php
  require_once('rfc6238.php');

  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    print("\n\t\t" . '<link rel="stylesheet" href="codegenerator.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="OTP Code Generator!!!">');
    print("\n\t\t" . '<meta name="keywords" content="PHP,OTP,Code,Generator">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printForm();
  $mainPage->generateCode();
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function printForm() {
      print('
        <div class="form">
          <form method="post">
            <label class="generator_code" for="generator_code">OTP Generator Code: </label><input class="generator_code" name="generator_code" type="text">
            <input type="submit">
          </form>
        </div>');
    }

    public function generateCode() {
      if(!empty($_REQUEST)) {
        $code = isset($_REQUEST["generator_code"]) ? $_REQUEST["generator_code"] : false;

        if($code) {
          print("<h1 class='redblue popup ligature'>" . TokenAuth6238::getTokenCode($code,0) . "</h1>");
        }
      }
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="OTP Code Generator!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>
