<?php
  $loadPage = new loadPage();
  $mainPage = new debugExplanation();

  $loadPage->loadHeader();
  //$mainPage->printWarning();
  $mainPage->printPhraseDebugInfo();
  $mainPage->printSWDebugExplanation();

  $loadPage->loadFooter();

  class debugExplanation {
    public function printWarning() {
      print('<center><h1>The explanation is not available right now! Please come back later!</h1></center>');
    }

    public function printPhraseDebugInfo() {
      print('<p>The phrase came "Did you see the fish walking outside?" was something I made up at a Wendy\'s when their was a lot of rain pouring outside and I was messing with a friend. It was a made you look gag.</p>');
    }

    public function printSWDebugExplanation() {
      print('<p>The link to <a class="reset-service-worker" onClick="resetServiceWorker(\'clear-cache\');">clear the cache</a> clears all caches saved by the service worker and then reinstalls the service worker from scratch. ' .
            'Reinstalling the service worker will also redownload the cache if you are online. The problem is, reinstalling the service worker too many times programmatically causes the browser to stop reinstalling it. It will not be able to redownload the files to cache. ' .
            'That\'s where the link to <a class="reset-service-worker" onClick="resetServiceWorker(\'uninstall-service-worker\');">uninstall the service worker</a> comes in. It clears the cache and then uninstalls the service worker. ' .
            'In order for the service worker to reinstall and redownload the cache, you will then have to close all tabs and windows related to this site.</p>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Tutorials";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>