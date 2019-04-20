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
    print("\n\t\t" . '<meta name="description" content="Language Test!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Language,Test,PHP">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  // https://stackoverflow.com/a/26423093/6828099
  // https://www.php.net/uasort
  // https://www.w3resource.com/php/function-reference/uasort.php
  function languagesort($x, $y) {
    if($x == $y) return 0;
    if($x == Null) return -1;

    return ($x > $y) ? -1 : 1;
  }

  // https://www.php.net/manual/en/function.array-key-first.php
  // https://stackoverflow.com/a/1028677/6828099
  function array_key_first($array) {
    reset($array);
    return key($array);
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printGreeting();
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function printGreeting() {
      // https://stackoverflow.com/a/1352139/6828099
      // https://stackoverflow.com/a/8552941/6828099
      $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

      //en-US,en;q=0.9,es;q=0.8
      $preferred = $this->parsePreferredLanguages($language);
      $first = array_key_first($preferred);

      print("<p>Language Header \"$language\"</p>");
      print("<p>Preferred Language \"$first\"</p>");

      if($first === "en-US" || $first === "en") {
        // English
        print("<h1>Hello!</h1>");
      } else if($first === "es") {
        // Spanish
        print("<h1>¡Hola!</h1>");
      } else if($first === "ja") {
        // Japanese
        print("<h1>こんにちは</h1>");
      } else if($first === "he") {
        // Hebrew
        print("<h1>שלום</h1>");
      } else {
        // Fallback If No Match
        print("<h1>Hello!</h1>");
      }
    }

    private function parsePreferredLanguages($header) {
      $languages = explode(",", $header);
      //var_dump($languages);

      $preferred = [];
      foreach($languages as $language) {
        //print("Language: " . explode(";", $language)[0]);

        $langarray = explode(";", $language);
        $lang = $langarray[0];

        $qvalue = !empty($langarray[1]) ? $langarray[1] : NULL;
        $preferred["$lang"] = "$qvalue";
      }

      // https://www.w3schools.com/php/php_arrays_sort.asp
      //arsort($preferred);
      uasort($preferred, "languagesort");
      //var_dump($preferred);

      return $preferred;
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Language Test Page!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>