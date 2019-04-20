<?php
  // https://en.wikipedia.org/wiki/Unicode
  // https://en.wikipedia.org/wiki/Private_Use_Areas

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
    print("\n\t\t" . '<meta name="description" content=" - Private Unicode Pages Tester!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Private,Unicode,Pages">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printCharacters();
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    // https://stackoverflow.com/a/6058533/6828099
    public function printCharacters() {
      $this->printPlaneZero();
      //$this->printPlaneFifteen();
      //$this->printPlaneSixteen();
    }

    // Why NonCharacters - https://stackoverflow.com/a/5219494/6828099
    // 66 NonCharacters - http://www.unicode.org/faq/private_use.html
    public function printPlaneZero() {
      // U+E000 - U+F8FF
      // 6,400 Code Points
      // NonCharacters - U+FDD0 - U+FDEF, U+FFFE, and U+FFFF

      // Apple - U+F8FF - 

      // The Filter's Not Necessary, But It is Good For Reference Later
      $filter = [];
      $finished = false;
      $start = 0xFDD0;
      $end = 0xFDEF;
      $character = $start;
      while(!$finished) {
        //print("Character: U+" . dechex($character) . " - ");
        //print(mb_convert_encoding('&#x' . dechex($character), 'UTF-8', 'HTML-ENTITIES'));
        //print("<br>");

        array_push($filter, dechex($character));

        $character = $character + 0x01;

        if($character > $end) {
          $finished = true;
        }
      }

      array_push($filter, dechex(0xFFFE));
      array_push($filter, dechex(0xFFFF));

      //var_dump($filter);
      //print("<br><br>");

      $finished = false;
      $start = 0xE000;
      $end = 0xF8FF;
      $character = $start;
      while(!$finished) {
        if(!in_array(dechex($character), $filter)) {
          print("Character: U+" . dechex($character) . " - ");
          print(mb_convert_encoding('&#x' . dechex($character), 'UTF-8', 'HTML-ENTITIES'));
          print("<br>");
        }

        $character = $character + 0x01;

        if($character > $end) {
          $finished = true;
        }
      }
    }

    public function printPlaneFifteen() {
      // U+F0000 - U+FFFFD
      // 65,534 Code Points
      // NonCharacters - U+FFFFE and U+FFFFF

      $finished = false;
      $start = 0xF0000;
      $end = 0xFFFFD;
      $character = $start;
      while(!$finished) {
        print("Character: U+" . dechex($character) . " - ");
        print(mb_convert_encoding('&#x' . dechex($character), 'UTF-8', 'HTML-ENTITIES'));
        print("<br>");

        $character = $character + 0x01;

        if($character > $end) {
          $finished = true;
        }
      }
    }

    public function printPlaneSixteen() {
      // U+100000 - U+10FFFD
      // 65,534 Code Points
      // NonCharacters - U+10FFFE and U+10FFFF

      $finished = false;
      $start = 0x100000;
      $end = 0x10FFFD;
      $character = $start;
      while(!$finished) {
        print("Character: U+" . dechex($character) . " - ");
        print(mb_convert_encoding('&#x' . dechex($character), 'UTF-8', 'HTML-ENTITIES'));
        print("<br>");

        $character = $character + 0x01;

        if($character > $end) {
          $finished = true;
        }
      }
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle=" - Private Unicode Pages Tester!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>