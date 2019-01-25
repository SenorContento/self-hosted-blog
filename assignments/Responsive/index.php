<?php
  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentThree();

  $loadPage->loadHeader();

  //$mainPage->printArchiveLink();
  $mainPage->printWarning();
  $mainPage->printNotice();

  $mainPage->printTabbedContent();
  $mainPage->printMainContent();
  $mainPage->printTable();
  $mainPage->printLargeImage();
  //$mainPage->printArchiveLink();

  $loadPage->loadFooter();

  function customPageHeader() {
    print('<link rel="stylesheet" href="assignment3.css">');
  }

  class homeworkAssignmentThree {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 3</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 3 has not been created yet! Please come back later!</h1></center>');
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
      print('
      <fieldset>
        <legend>Tabbed/Vertical Expandable Content</legend>
        <p>Tabbed/Vertical Expandable Content goes here!!!</p>
      </fieldset>');
    }

    public function printMainContent() {
      print('
      <fieldset>
        <legend>Main Content</legend>
        <p>Main Content goes here!!!</p>
      </fieldset>');
    }

    public function printLargeImage() {
      print('
      <fieldset>
        <legend>Large Image</legend>
        <p>Large image goes here!!! It will have to have JS manipulation, so it maybe a slideshow.</p>
      </fieldset>');
    }

    public function printTable() {
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