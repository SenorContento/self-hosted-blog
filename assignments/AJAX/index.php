<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment8.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEight();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  //$mainPage->printWarning();
  $mainPage->printForm();
  $mainPage->printUserControls();
  $mainPage->printDebugOutput();
  $mainPage->printResponsesTable();

  $loadPage->loadFooter();

  class homeworkAssignmentEight {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 8 has not been created yet! Please come back later!</h1></center>');
    }

    /* AJAX Requests
     *
     * http://api.jquery.com/category/ajax/
     *
     * https://www.w3schools.com/jquery/jquery_ajax_load.asp
     * http://api.jquery.com/load/
     * .load()
     *
     * https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
     * http://api.jquery.com/jQuery.get/
     * jQuery.get()
     *
     * http://api.jquery.com/jQuery.getJSON/
     * jQuery.getJSON()
     *
     * https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
     * http://api.jquery.com/jQuery.post/
     * jQuery.post()
     *
     * http://api.jquery.com/jQuery.ajax/
     * jQuery.ajax()
    */

    public function printForm() {
      // https://blog.senorcontento.com/sitedata/general/debug/debugger
      // https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/general/debug/debugger.md
      print('<script src="ajax-api.js"></script>');

      print('
      <fieldset>
        <legend>AJAX Control Form</legend>
        <div class="form">
          <form id="hotbits">
            <label>URL: </label><input type="text" id="url" value="/api/hotbits">
            <label>Data: </label><input type="text" id="data" value=\'{"retrieve": true, "id": 1}\'><br>

            <a id="submit">Submit</a>
          </form>
        </div>
      </fieldset>');
    }

    public function printUserControls() {
      /* API Methods (POST) - Hotbits and Cryptography
       *
       * 1 - bytes(int) and generator(string)
       * 2 - retrieve(bool) and id(int)
       * 3 - analyze(bool) and id(int)
       * 4 - analyze(bool) and id(int) and count(bool)
       */

       /* Responses - Hotbits
        *
        * 1 - (JSON) New Data Straight from Hotbits (Do Both Random and Pseudorandom)
        * 2 - (JSON) Old Data Already In MySQL Database (Do Both Random and Pseudorandom)
        * 3 - (JSON) Analyze Data From MySQL Database
        * 4 - (JSON) Analyze Data From MySQL Database (and Provide Byte Counts)
        */

        /* Responses - Cryptography
         *
         * 1 - (JSON) New Data - Encrypt and Decrypt Test File
         * 2 - (JSON) Old Data - Encrypt and Decrypt Test File
         * 3 - (HTML) Analyze Data From MySQL Database
         * 4 - (HTML) Analyze Data From MySQL Database (and Provide Byte Counts)
         */

      print('
      <fieldset>
        <legend>AJAX User Controls</legend>
        <div class="form">
          <form id="user-controls">
            <label class="form-label-color">Select a JSON Request: </label>
            <select id="option-color" name="color">
              <option value="red">Work In Progress...</option>
              <option value="red">Currently Does Nothing!!!</option>
              <option value="red">Red</option>
              <option value="green">Green</option>
              <option value="blue">Blue</option>
            </select>
          </form>
        </div>
      </fieldset>');
    }

    public function printDebugOutput() {
      print('
      <fieldset>
        <legend>AJAX Debug Output</legend>
        <div id="highlight">
          <pre id="ajax-output-debug">Output Not Generated...</pre>
        </div>
      </fieldset>');
    }

    public function printResponsesTable() {
      print('
      <fieldset id="response-table">
        <legend>AJAX Response</legend>
        <table id="ajax-table">
          <thead>
            <tr>
              <th>Index</th>
              <th>Item</th>
              </tr>
          </thead>
          <tbody id="ajax-table-body">
            <tr class="ajax-table-tr">
              <td class="index-table">Output Not Generated...</td>
              <td class="item-table">Output Not Generated...</td>
              </tr>
          </tbody>
        </table>
      </fieldset>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 8 - AJAX";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>