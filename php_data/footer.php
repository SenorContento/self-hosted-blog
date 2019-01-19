<?php
  $loadFooter = new loadFooter();
  $loadFooter->printStartFooter();
  $loadFooter->printVisibleFooter();
  $loadFooter->printCustomPageFooter();
  $loadFooter->printEndFooter();

  class loadFooter {
    public function printVisibleFooter() {
      print("\n\t\t" . '<footer>' .
            "\n\t\t\t" . '<p class="footer-message">' .
            "\n\t\t\t\t" . 'Hello, this is a demo message for the footer. I am replacing this with a clear cache button for development.' .
            "\n\t\t\t" . '</p>' .
            "\n\t\t" . '</footer>');
    }

    public function printStartFooter() {
      print("\n\t\t" . '</div>');
    }

    public function printCustomPageFooter() {
      if(function_exists('customPageFooter')) {
        print("\n");
        customPageFooter();
      }
    }

    public function printEndFooter() {
      print("\n\t" . '</body>');
      print("\n" . '</html>');
    }
  }
?>