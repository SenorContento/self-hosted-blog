<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    print("\n\t\t" . '<link rel="stylesheet" href="search.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
    print("\n\t\t" . '<script src="ajax-api.js"></script>');
  }

  function customMetadata() {
    //print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Template For Alex\'s PHP Pages!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Search,Term,Project,MySQL,PHP,AJAX">');
    //print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    //print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  $mainPage->printForm();
  $mainPage->printTable();
  $loadPage->loadFooter();

  class mainPage {
    public function printForm() {
      print('<!--This Pre Tag Exists to Help With Javascript Resizing-->
            <pre class="sizing-tag-hidden" id="sizing-tag"></pre>');
      // https://blog.senorcontento.com/sitedata/general/debug/debugger
      // https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/general/debug/debugger.md
      print('<a name="ajax-control-form"></a>
      <fieldset>
        <legend>Search Engine</legend>
        <div class="form">
          <form id="search">
            <!--This Pre Tag Exists to Help With Javascript Resizing-->
            <div id="sizing-tag-search">
              <label for="data">Search: </label><input type="text" class="search-input" id="data"><br>
            </div>

            <br>

            <div id="search-parameters" class="search-parameters">
              <div class="radio">
                <span class="radio-option"><label for="radio-key-id">GPG Key ID </label><input id="radio-key-id" class="radio-search" name="parameter" value="keyid" type="radio" checked="checked"></span>
                <!--<span id="radio-newline" class="hidden-newline-mobile"><br></span>-->
                <span class="radio-option"><label for="radio-program-id">Piet Program ID </label><input id="radio-program-id" class="radio-search" name="parameter" value="programid" type="radio"></span>
              </div>

              <span id="submit-newline" class="hidden-newline-mobile"><br></span>
              <a id="submit" class="submit-button search-button">Submit API Request</a>
            </div>
          </form>
        </div>
      </fieldset>');
    }

    public function printTable() {
      print("<div id='search-results'></div>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Search Term Project!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>