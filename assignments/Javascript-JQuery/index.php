<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment2.css">');
    print("\n\t\t" . '<script src="https://code.jquery.com/jquery-3.3.1.js"' .
          'integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="' .
          'crossorigin="anonymous"></script>');
    print("\n\t\t" . '<!-- The integrity hash is explained at https://www.srihash.org/ -->');
    print("\n\t\t" . '<!-- Since service workers cannot save content offline that isn\'t from the same host, if I use it for offline use, I will change to the internal JQuery script -->');
    //print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');

    print("\n\n\t\t" . '<script>' .
          'function dropDownTag() {' .
             'var hidden = document.getElementById("hidden");' .
	           'if(hidden.style.display == \'none\' || hidden.style.display == \'\') {' .
	              'hidden.style.display = \'block\';' .
	              'this.$.arrowup.style.display = \'inline\';' .
	              'this.$.arrowdown.style.display = \'none\';' .
	           '} else {' .
	              'hidden.style.display = \'none\';' .
	              'this.$.arrowup.style.display = \'none\';' .
	              'this.$.arrowdown.style.display = \'inline\';' .
	           '}' .
           '}' . '</script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentTwo();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  $mainPage->printWarning();
  $mainPage->printDropDown("Test", "Example");
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentTwo {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 2</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 2 has not been created yet! Please come back later!</h1></center>');
    }

    public function printDropDown($title, $elements) {
      print('<div class="inline-block">' .
              '<a id="dropdown" class="button">' .
                $title .
                '<span id="arrowdown">&#x25BC;</span>' .
                '<span id="arrowup">&#x25B2;</span>' .
              '</a>' .

              '<div id="hidden" onclick="dropDownTag();" class="more">' .
                $elements .
              '</div>' .
            '</div>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 2 - Javascript-JQuery";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>