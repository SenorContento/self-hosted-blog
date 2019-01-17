<?php
  $loadFooter = new loadFooter();
  $loadFooter->printStartFooter();
  $loadFooter->printCustomPageFooter();
  $loadFooter->printEndFooter();

  class loadFooter {
    public function printStartFooter() {
      print("\n\t\t" . '</div>' . '</center>');
      print("\n\t" . '</body>');
    }

    public function printCustomPageFooter() {
      if(function_exists('customPageFooter')) {
        print("\n");
        customPageFooter();
      }
    }

    public function printEndFooter() {
      print("\n" . '</html>');
    }
  }
?>