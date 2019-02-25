<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment8.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEight();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  $mainPage->printWarning();
  $mainPage->printForm();

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
        <legend>Example Form</legend>
        <div class="form">
          <form id="hotbits">
            <label>URL: </label><input type="text" id="url" value="/assignments/AJAX/hotbits.php">
            <label>Data: </label><input type="text" id="data" value=\'{"retrieve": true, "id": 1}\'><br>

            <a id="submit">Submit</a>
          </form>

          <br>

          <h3>Ajax Output</h3>
          <pre id="ajax-output-debug"><div class="highlight">Output Not Generated...</div></pre>

          <br>

          <table>
            <thead>
              <tr>
                <th>Index</th>
                <th>Item</th>
                </tr>
            </thead>
            <tbody id="ajax-table-body">
              <tr>
                <td class="index-table">Output Not Generated...</td>
                <td class="item-table">Output Not Generated...</td>
                </tr>
            </tbody>
          </table>
        </div>
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