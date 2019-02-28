<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentThree();

  $loadPage->loadHeader();

  $mainPage->printArchiveLink();
  //$mainPage->printWarning();
  $mainPage->printSourceCodeLink();
  $mainPage->printNotice();

  $mainPage->printTabbedContent();
  $mainPage->printMainContent();
  $mainPage->printTable();
  $mainPage->printLargeImage($mainPage->generateArrayofImages()); //$mainPage->printSlideshowSimple($mainPage->generateArrayofImages());
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  function customPageHeader() {
    print('<link rel="stylesheet" href="assignment3.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');

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

  class homeworkAssignmentThree {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 3</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 3 has not been created yet! Please come back later!</h1></center>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printNotice() {
      print('<b>The header and footer css code is in /css/main.css. It is part of my main site and not just this assignment.</b>');

      /*
        Ensure your page has the following:
         * Table with 8+ columns that forces the table to have a minimum width of 1000 pixels
         * Slideshow or other large format image that has JS manipulation that has a width of at least 800 pixels on desktop
         * Header/Footer content areas
         * Main content area that is broken up into at least 3 columns on part of the page in desktop view

        Extra Credit (2pts):
         * Tabbed content in desktop view that switches to vertical expandable content in tablet/phone views
      */
    }

    public function printTabbedContent() {
      // https://www.w3schools.com/howto/howto_js_tabs.asp
      print('
      <fieldset>
        <legend>Tabbed/Vertical Expandable Content</legend>

        <div class="tab">
          <button class="tablinks" onclick="openCity(event, \'London\')" id="defaultOpen">London</button>
          <button class="tablinks" onclick="openCity(event, \'Paris\')">Paris</button>
          <button class="tablinks" onclick="openCity(event, \'Tokyo\')">Tokyo</button>
        </div>

        <div id="London" class="tabcontent">
          <h3>London</h3>
          <p>London is the capital city of England.</p>
        </div>

        <div id="Paris" class="tabcontent">
          <h3>Paris</h3>
          <p>Paris is the capital of France.</p>
        </div>

        <div id="Tokyo" class="tabcontent">
          <h3>Tokyo</h3>
          <p>Tokyo is the capital of Japan.</p>
        </div>
      </fieldset>');

      print("<script>
                function openCity(evt, cityName) {
                  var i, tabcontent, tablinks;
                  tabcontent = document.getElementsByClassName(\"tabcontent\");
                  for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = \"none\";
                  }
                  tablinks = document.getElementsByClassName(\"tablinks\");
                  for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(\" active\", \"\");
                  }
                  document.getElementById(cityName).style.display = \"block\";
                  evt.currentTarget.className += \" active\";
                }

              document.getElementById(\"defaultOpen\").click();
            </script>");
    }

    public function printMainContent() {
      // https://css-tricks.com/snippets/css/a-guide-to-flexbox/
      // https://www.w3schools.com/css/css3_flexbox.asp
      print('
      <fieldset>
        <legend>Main Content</legend>
        <div id="main_content">
          <div id="column1" class="column"><p>Content 1</p></div>
          <div id="column2" class="column"><p>Content 2</p></div>
          <div id="column3" class="column"><p>Content 3</p></div>
        </div>
      </fieldset>');
    }

    public function printLargeImage($slides) {
      print('
      <fieldset>
        <legend>Large Image</legend>');

      print('<div class="carousel">');

      foreach($slides as $url => $title) {
        print('<div class="mySlides">' .
                '<a href="' . $url . '" ' .
                   'title="' . $title . '">' .

              '<img class="mySlidesImage"' .
                   'alt="' . $title . '" ' .
                   'title="' . $title . '" ' .
                   'src="' . $url . '">' .
                '</a>' .

                '<p>' . $title . '</p>' .
                '</div>');
        }

        print('</div>');

        print("</fieldset>");

      print('</fieldset>');
    }

    public function generateArrayofImages() {
      /*$slideString = "/images/jpg/that-red-tree/original.jpg" . ',' .
                     "/images/jpg/that-red-tree/crayon.jpg" . ',' .
                     "/images/jpg/that-red-tree/boxy.jpg" . ',' .
                     "/images/jpg/that-red-tree/red-yellow-shift.jpg" . ',' .
                     "/images/jpg/that-red-tree/red-shift-solo.jpg";

      $slides = explode(',', $slideString);*/

      // https://stackoverflow.com/a/18164240/6828099
      if(strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')) {
        $slides = array("/images/webp/that-red-tree/original.webp" => "The original photo before I modified it in Gimp and G'Mic.",
                       "/images/webp/that-red-tree/crayon.webp" => "Heavy use of G'Mic to produce this gridlike 'drawing.'",
                       "/images/webp/that-red-tree/boxy.webp" => "Used G'Mic to apply boxes to screen and adjust images to boxes.",
                       "/images/webp/that-red-tree/red-yellow-shift.webp" => "Used plain Gimp to apply 2 different color shifts.",
                       "/images/webp/that-red-tree/red-shift-solo.webp" => "Used plain Gimp to apply a single color shift.");
      } else {
        $slides = array("/images/jpg/that-red-tree/original.jpg" => "The original photo before I modified it in Gimp and G'Mic.",
                       "/images/jpg/that-red-tree/crayon.jpg" => "Heavy use of G'Mic to produce this gridlike 'drawing.'",
                       "/images/jpg/that-red-tree/boxy.jpg" => "Used G'Mic to apply boxes to screen and adjust images to boxes.",
                       "/images/jpg/that-red-tree/red-yellow-shift.jpg" => "Used plain Gimp to apply 2 different color shifts.",
                       "/images/jpg/that-red-tree/red-shift-solo.jpg" => "Used plain Gimp to apply a single color shift.");
      }

      return $slides;
    }

    public function printTable() {
      // https://css-tricks.com/responsive-data-tables/
      /*
        I am not a big fan of long tables like this, but, it is required for this assignment.
        However, it provides a learning experience and code I can use in the future if needed.
      */
      print('<div id="table">
        <fieldset>
          <legend>Example Table</legend>
          <table>
            <tr>
              <th>Header 1</th>
              <th>Header 2</th>
              <th>Header 3</th>
              <th>Header 4</th>
              <th>Header 5</th>
              <th>Header 6</th>
              <th>Header 7</th>
              <th>Header 8</th>
              <th>Header 9</th>
            </tr>
            <tr>
              <td>1x1</td>
              <td>1x2</td>
              <td>1x3</td>
              <td>1x4</td>
              <td>1x5</td>
              <td>1x6</td>
              <td>1x7</td>
              <td>1x8</td>
              <td>1x9</td>
            </tr>
            <tr>
              <td>2x1</td>
              <td>2x2</td>
              <td>2x3</td>
              <td>2x4</td>
              <td>2x5</td>
              <td>2x6</td>
              <td>2x7</td>
              <td>2x8</td>
              <td>2x9</td>
            </tr>
            </table>
          </fieldset>
      </div>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 3 - Responsive";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>