<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment8.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEight();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  //$mainPage->printWarning();

  $mainPage->printTopLink();

  $mainPage->printForm();
  $mainPage->printUserControls();
  $mainPage->printDebugOutput();
  $mainPage->printResponsesTable();

  $mainPage->printGoToTopLink();

  $loadPage->loadFooter();

  class homeworkAssignmentEight {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 8</a>');
      //print('<br>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 8 has not been created yet! Please come back later!</h1></center>');
    }

    public function printTopLink() {
      print('<a name="top"></a>');
    }

    public function printGoToTopLink() {
      print('<a class="jump" href="#top">Go To Top</a>');
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

      print('<a name="ajax-control-form"></a>
      <fieldset>
        <legend>AJAX Control Form</legend>
        <div class="form">
          <form id="hotbits">
            <label for="url">URL: </label><input type="text" id="url" value="/api/hotbits">
            <label for="data">Data: </label><input type="text" id="data" value=\'{"id":1,"retrieve":true}\'><br>

            <a class="jump" href="#ajax-response-table">Go To Table</a>
            <a id="submit">Submit API Request</a>
          </form>
        </div>
      </fieldset>');
    }

    public function printUserControls() {
      /* API Methods (POST)
       *
       * 1 - [Both] bytes(int) and generator(string)
       * 2 - [Both] retrieve(bool) and id(int) and [Cryptography Only] download(string)
       * 3 - [Hotbits Only] analyze(bool) and id(int) and format(string)
       * 4 - [Hotbits Only] analyze(bool) and id(int) and count(bool) and format(string)
       */

       /* Responses - Hotbits
        *
        * 1 - (JSON) New Data Straight from Hotbits (Do Both Random and Pseudorandom)
        * 2 - (JSON) Old Data Already In MySQL Database (Do Both Random and Pseudorandom)
        * 3 - (JSON) Analyze Data From MySQL Database - format (CSV, JSON, HTML)
        * 4 - (JSON) Analyze Data From MySQL Database (and Provide Byte Counts) - format (CSV, JSON, HTML)
        */

        /* Responses - Cryptography
         *
         * 1 - (JSON) New Data - Encrypt and Decrypt Test File - download (base64 and zip)
         * 2 - (JSON) Old Data - Encrypt and Decrypt Test File - download (base64 and zip)
         */

      print('
      <fieldset>
        <legend>AJAX User Controls</legend>
        <div class="form">
          <form id="user-controls">
            <label for="option-user-controls">Select a Preset Request: </label>
            <select id="option-user-controls" name="user-controls">
              <option value="grab-new-data">(JSON) Retrieve New Data (Bytes and Generator)</option>
              <option value="retrieve-existing-data" selected="selected">(JSON) Retrieve Existing Data (ID)</option>
              <option value="analyze-count-html">(HTML) Analyze Existing Data With Byte Count (ID)</option>
              <option value="encryption-new-data">(Zip Archive) New Encrypt/Decrypt Test File (Bytes and Generator)</option>
              <option value="encryption-existing-data">(Zip Archive) Existing Encrypt/Decrypt Test File (ID)</option>
              <option value="free-form">Free Form (Anything Goes)</option>
            </select>

            <label for="option-request-type">Request Type: </label>
            <select id="option-request-type" name="request-type">
              <option value="bytes" disabled>Bytes</option>
              <option value="retrieve" selected="selected">Retrieve</option>
              <option value="analyze" disabled>Analyze</option>
            </select>

            <label for="option-count">Count Bytes: </label>
            <select id="option-count" name="count" disabled>
              <option value="true" selected="selected">Yes</option>
              <option value="false">No</option>
            </select>

            <br>

            <label for="option-generator-type">Generator: </label>
            <select id="option-generator-type" name="request-type" disabled>
              <option value="random">Random</option>
              <option value="pseudo">Pseudorandom</option>
            </select>

            <label for="bytes">Bytes (2048 Max): </label><input type="text" id="bytes" value="2048" disabled>
            <label for="rowID">Row ID: </label><input type="text" id="rowID" value="1">

            <br>

            <label for="option-format-type">Format (Hotbits Only): </label>
            <select id="option-format-type" name="request-type" disabled>
              <option value="json">JSON</option>
              <option value="html">HTML</option>
              <option value="csv">CSV</option>
            </select>

            <label for="option-download-type">Download (Cryptography Only): </label>
            <select id="option-download-type" name="request-type" disabled>
              <option value="base64">Base64</option>
              <option value="zip" disabled>Zip (Choose Base64) - I Only Work With Direct Download</option>
            </select>

            <br>

            <a id="build-api-request">Build JSON String</a>
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
      print('<a name="ajax-response-table"></a>
      <fieldset id="response-table">
        <legend>AJAX Response</legend>
        <a class="jump" href="#ajax-control-form">Go Back To Form</a><br><br>
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
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/php_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/php_data/footer.php");
    }
  }
?>