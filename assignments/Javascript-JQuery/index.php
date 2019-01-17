<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment2.css">');
    print("\n\t\t" . '<script src="https://code.jquery.com/jquery-3.3.1.js"' .
          'integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="' .
          'crossorigin="anonymous"></script>');
    print("\n\t\t" . '<!-- The integrity hash is explained at https://www.srihash.org/ -->');
    print("\n\t\t" . '<!-- Since service workers cannot save content offline that isn\'t from the same host, if I use it for offline use, I will change to the internal JQuery script -->');
    //print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');

    print("\n\n\t\t" . '<!-- The script below changes the way the dropdown menu functions -->');
    print("\n\n\t\t" . '<script>' .
          "\n\t\t\t" . '$(document).ready(function() {' .
          "\n\t\t\t\t" .   '$("#dropdown").click(function() {' .
          //"\n\t\t\t\t\t" .    'alert("Display: " + $("#hidden").css("display"));' .
          "\n\t\t\t\t\t" .    'if($("#hidden").css("display") == \'none\' || $("#hidden").css("display") == \'\') {' .
          "\n\t\t\t\t\t\t" .      '$("#hidden").css(\'display\', \'inline-block\');' .
          "\n\t\t\t\t\t\t" .      '$("#arrowup").css(\'display\', \'inline\');' .
          "\n\t\t\t\t\t\t" .      '$("#arrowdown").css(\'display\', \'none\');' .
          "\n\t\t\t\t\t" .    '} else {' .
          "\n\t\t\t\t\t\t" .      '$("#hidden").css(\'display\', \'none\');' .
          "\n\t\t\t\t\t\t" .      '$("#arrowup").css(\'display\', \'none\');' .
          "\n\t\t\t\t\t\t" .      '$("#arrowdown").css(\'display\', \'inline\');' .
          "\n\t\t\t\t\t" .    '}' .
          "\n\t\t\t\t" .   '})' .
          "\n\t\t\t" . '});' . '</script>');

    print("\n\n\t\t" . '<!-- The script below changes the way the more link functions -->');
    print("\n\n\t\t" . '<script>' .
          "\n\t\t\t" . '$(document).ready(function() {' .

          "\n\t\t\t\t" .   '$("#show-li").click(function() {' .
          "\n\t\t\t\t\t" .      '$("#show-li").css(\'display\', \'none\');' .
          "\n\t\t\t\t\t" .      '$("#hidden-more-link").css(\'display\', \'block\');' .
          "\n\t\t\t\t" .   '})' .

          "\n\t\t\t\t" .   '$("#hideme-more-link").click(function() {' .
          "\n\t\t\t\t\t" .      '$("#show-li").css(\'display\', \'inline\');' .
          "\n\t\t\t\t\t" .      '$("#hidden-more-link").css(\'display\', \'none\');' .
          "\n\t\t\t\t" .   '})' .

          "\n\t\t\t" . '});' . '</script>');

    print("\n\n\t\t" . '<!-- The script below changes the way the slideshow functions -->');
    print("\n\t\t" . '<!-- The code was borrowed from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_slideshow_rr -->');
    print("\n\t\t" . '<script>' .
          "\n\t\t" . '$(document).ready(function() {' .
          "\n\t\t\t" . 'var myIndex = 0;' .
          "\n\t\t\t" . 'carousel();' .

          "\n\n\t\t\t" . 'function carousel() {' .
              "\n\t\t\t" . 'var i;' .
              "\n\t\t\t" . 'var x = document.getElementsByClassName("mySlides");' .
              "\n\t\t\t" . 'for (i = 0; i < x.length; i++) {' .
                "\n\t\t\t" . 'x[i].style.display = "none";' .
              "\n\t\t\t" . '}' .
              "\n\t\t\t" . 'myIndex++;' .
              "\n\t\t\t" . 'if (myIndex > x.length) {myIndex = 1}' .
              "\n\t\t\t" . 'x[myIndex-1].style.display = "block";' .
              "\n\t\t\t" . 'setTimeout(carousel, 4000); // Change image every 4 seconds' .
            "\n\t\t\t" . '}' .
            "\n\t\t" . '});' .
          "\n\t\t" . '</script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentTwo();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();
  $mainPage->printDropDown("Dropdown Menu Example", $mainPage->generateMenu());
  $mainPage->printMoreLink("Show More Link Demo", $mainPage->generateParagraph());
  $mainPage->printSlideshowSimple($mainPage->generateArrayofImages());
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentTwo {
    public $exec_date_path = "/bin/date";

    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 2</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 2 has not been created yet! Please come back later!</h1></center>');
    }

    public function generateMenu() {
      $menu = '<a href="/" class="button menu-button">Main Page</a>' . '<br>' .
              '<a href="/test/failed_pma/" class="button menu-button">Failed PMA Attacks</a>' . '<br>' .
              '<a href="https://www.google.com/" class="button menu-button">Google</a>';

      return $menu;
    }

    public function generateParagraph() {
      $menu = '<p>This is a demo paragraph that will be shown when the more link has been clicked!!!' .
      '<br><br>' . 'Here\'s your Unix Timestamp in UTC: ' . shell_exec($this->exec_date_path . ' -u') . '</p>';

      return $menu;
    }

    public function printDropDown($title, $elements) {
      print("<fieldset>" .
            "<legend>" . "Dropdown Example" . "</legend>");

      print('<div id="dropdown-div">' .
              '<a id="dropdown" class="button">' .
                $title .
                '<span id="arrowdown">&#x25BC;</span>' .
                '<span id="arrowup">&#x25B2;</span>' .
              '</a>' .

              '<br>' .

              '<div id="hidden" class="more">' .
                $elements .
              '</div>' .
            '</div>');

      print("</fieldset>");
    }

    public function printMoreLink($title, $elements) {
      print("<fieldset>" .
            "<legend>" . "More Link Example" . "</legend>");

      print('<ul class="show-li-ul">' .
              '<li id="show-li">' .
                '<a id="showme">' . $title . '</a>' .
              '</li>' .
            '</ul>');

      print('<div id="hidden-more-link" class="show-more">' .
              $elements .
              '<br>' .
              '<a id="hideme-more-link">Hide ' . $title . '</a>' .
            '</div>');

      print("</fieldset>");
    }

    public function generateArrayofImages() {
      /*$slideString = "/images/jpg/that-red-tree/original.jpg" . ',' .
                     "/images/jpg/that-red-tree/crayon.jpg" . ',' .
                     "/images/jpg/that-red-tree/boxy.jpg" . ',' .
                     "/images/jpg/that-red-tree/red-yellow-shift.jpg" . ',' .
                     "/images/jpg/that-red-tree/red-shift-solo.jpg";

      $slides = explode(',', $slideString);*/

      $slides = array("/images/jpg/that-red-tree/original.jpg" => "The original photo before I modified it in Gimp and G'Mic.",
                     "/images/jpg/that-red-tree/crayon.jpg" => "Heavy use of G'Mic to produce this gridlike 'drawing.'",
                     "/images/jpg/that-red-tree/boxy.jpg" => "Used G'Mic to apply boxes to screen and adjust images to boxes.",
                     "/images/jpg/that-red-tree/red-yellow-shift.jpg" => "Used plain Gimp to apply 2 different color shifts.",
                     "/images/jpg/that-red-tree/red-shift-solo.jpg" => "Used plain Gimp to apply a single color shift.");

      return $slides;
    }

    public function printSlideshowSimple($slides) {
      print("<fieldset>" .
            "<legend>" . "Slideshow Example" . "</legend>");

      print("<p>You can click the images to go to the originals. Also, the images have descriptions set using alt and title atttributes. The slideshow is set to 4 seconds as you can click the images to admire them for longer.");

      print('<div class="carousel">');

      print('<!-- So, for some reason the title attribute does not work on mobile.' .
            'It should work as it works on XKCD\'s website. I suspect is has something to' .
            'do with the anchor tag that surrounds it. -->');

      foreach($slides as $url => $title) {
        print('<a href="' . $url . '" title="' . $title . '" alt="' . $title . '"><img class="mySlides" alt="' . $title . '" title="' . $title . '" src="' . $url . '"></img></a>');
      }

      /*foreach($slides as $element) {
        print('<img class="mySlides" src="' . $element . '"></img>');
      }*/

      print('</div>');

      print("</fieldset>");
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