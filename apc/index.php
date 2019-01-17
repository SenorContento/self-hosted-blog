<?php
  $loadPage = new loadPage();
  $mainPage = new apcBatteryStats();

  $loadPage->loadHeader();
  $mainPage->setVars();
  $mainPage->displayBatteryLong();
  $mainPage->displayDateUTC();
  $loadPage->loadFooter();

  class apcBatteryStats {
    public $exec_apc_path;
    public $exec_date_path;

    function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->exec_apc_path = "/sbin/apcaccess";
        $this->exec_date_path = "/bin/date";

      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->exec_apc_path = "/bin/test 5 -eq 6";
        $this->exec_date_path = "/bin/date";
      }
    }

    public function displayBatteryLong() {
      print("<pre><code>");
      system($this->exec_apc_path . ' || echo "Sorry, but the apcaccess command cannot be found!!!"');
      print("</code></pre>");
    }

    public function displayDateUTC() {
      print('</br><p>');
      system($this->exec_date_path . ' -u || echo "Sorry, but the date command cannot be found!!!"');
      print('</p>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Test APC UPS Battery Stats";
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
    }

    public function loadFooter() {
      include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
    }
  }
?>