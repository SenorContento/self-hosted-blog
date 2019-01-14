<?php
  $loadPage = new loadPage();
  $mainPage = new apcBatteryStats();

  $loadPage->loadHeader();
  $mainPage->displayBatteryLong();
  $mainPage->displayDateUTC();
  $loadPage->loadFooter();

  class apcBatteryStats {
    public $exec_apc_path = "/sbin/apcaccess";
    public $exec_date_path = "/bin/date";

    public function displayBatteryLong() {
      print("<pre><code>");
      system($this->exec_apc_path . ' || echo "Sorry, but the apcaccess command cannot be found!!!"');
      print("</code></pre>");
    }

    public function displayDateUTC() {
      print('</br><p>');
      system($this->exec_date_path . ' -u');
      print('</p>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="APC UPS Battery Stats";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>