<?php
  function customPageHeader() {
    print("\n\t\t" . '<style>' .
    "\n\t\t\t" . 'img.assignment1 {' .
    "\n\t\t\t\t" . 'max-width: 100px;' .
    "\n\t\t\t\t" . 'height: 100px;' .
    "\n\t\t\t" . '}' .
    "\n\t\t" . '</style>');

    /*
      img.assignment1 {
        max-width: 100px;
        height: 100px;
      }
    */
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentOne();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();

  $mainPage->printArchiveLink();
  $mainPage->printTable();
  $mainPage->printDemoHeaders();
  $mainPage->printBills();
  $mainPage->printOtherTags();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  class homeworkAssignmentOne {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 1</a>');
      //print('<br>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printTable() {
      /*
         HTML-CSS
         9.75 / 10
         48.75 / 50
         97.5 %
         Individual Feedback
         Table missing <thead> and <tbody>
      */

      print('<div id="table">
        <fieldset>
          <legend>Example Table</legend>
          <table>
            <thead>
              <tr>
                <th>Students</th>
                <th>Grades</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Alex</td>
                <td>100%</td>
              </tr>
              <tr>
                <td>Josh</td>
                <td>90%</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td>Grade Average</td>
                <td>95%</td>
              </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>');
    }

    public function printDemoHeaders() {
      print('<div id="headers">
        <fieldset>
          <legend>Example Headers</legend>
          <h1>Header 1</h1>
          <h2>Header 2</h2>
          <h3>Header 3</h3>
          <h4>Header 4</h4>
          <h5>Header 5</h5>
          <h6>Header 6</h6>
        </fieldset>
      </div>');
    }

    public function printBills() {
      print('<div id="money-money-money">
        <fieldset>
          <legend><a href="https://www.treasury.gov/resource-center/faqs/Currency/Pages/edu_faq_currency_portraits.aspx">People on Bills</a></legend>
          <ul>
            <li>$1 - George Washington</li>
            <li>$2 - Thomas Jefferson</li>
            <li>$5 - Abraham Lincoln</li>
            <li>$10 - Alexander Hamilton</li>
            <li>$20 - Andrew Jackson</li>
            <li>$50 - Ulysses S. Grant</li>
            <li>$100 - Benjamin Franklin</li>

            <li>$500 - William McKinley</li>
            <li>$1,000 - Grover Cleveland</li>
            <li>$5,000 - James Madison</li>
            <li>$10,000 - Salmon P. Chase</li>
            <li>$100,000 - Woodrow Wilson</li>
          </ul>
        </fieldset>
      </div>');
    }

    public function printOtherTags() {
      print('<div id="main_body">
        <fieldset>
          <legend class="iamlegend">Other Tags</legend>
          <p>This is in a paragraph tag!!!</p>
          <br/>
          <img class="assignment1" src="/images/png/WhatIs42-Logo.png"
                alt="Logo of Personal Enlightenment!!!"
                title="Logo of Personal Enlightenment!!!"></img>
          <!--  If for some reason you wanted the Gimp Project File, it is located at /images/xcf/WhatIs42-Logo.xcf -->
          <br/>
          <a href="https://start.duckduckgo.com/?kae=t&kak=-1&kal=-1&kao=-1&kaq=-1&k1=-1&kax=-1&kam=google-maps&kap=-1&kau=-1&kaj=m&kp=-2">Try DuckDuckGo.com</a>
        </fieldset>
      </div>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 1 - HTML-CSS";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>