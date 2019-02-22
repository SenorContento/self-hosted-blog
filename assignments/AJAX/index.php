<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="assignment8.css">');
    print("\n\t\t" . '<script src="/js/jquery-3.3.1.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEight();

  $loadPage->loadHeader();

  $mainPage->printSourceCodeLink();
  $mainPage->printWarning();

  $loadPage->loadFooter();

  class homeworkAssignmentEight {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 8 has not been created yet! Please come back later!</h1></center>');
    }

    /*
      http://api.jquery.com/category/ajax/

      * .load()

      * jQuery.get()
      * jQuery.getJSON()

      * jQuery.post()

      * jQuery.ajax()
    */

    public function getHTML() {
      // https://www.w3schools.com/jquery/jquery_ajax_load.asp
      // http://api.jquery.com/load/
      // .load()
    }

    public function getData() {
      // https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
      // http://api.jquery.com/jQuery.get/
      // jQuery.get()
    }

    public function getJSON() {
      // http://api.jquery.com/jQuery.getJSON/
      // jQuery.getJSON()
    }

    public function postData() {
      // https://www.w3schools.com/jquery/jquery_ajax_get_post.asp
      // http://api.jquery.com/jQuery.post/
      // jQuery.post()
    }

    public function ajaxRequest() {
      // http://api.jquery.com/jQuery.ajax/
      // jQuery.ajax()
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