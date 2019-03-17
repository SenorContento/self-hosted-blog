<?php
  $loadPage = new loadPage();
  $mainPage = new apcBatteryStats();

  $loadPage->loadHeader();
  $mainPage->setVars();
  $mainPage->displayBatteryLong();
  //$mainPage->displayDateUTC();
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
        //$this->exec_apc_path = "/bin/test 5 -eq 6";
        $this->exec_apc_path = "/bin/cat /Users/senor/Documents/Class/2019/Spring/CSCI\ 3000/APC/apc-access-command.txt";
        $this->exec_date_path = "/bin/date";
      }
    }

    public function displayBatteryLong() {
      /*print("<pre><code>");
      system($this->exec_apc_path . ' || echo "Sorry, but the apcaccess command cannot be found!!!"');
      print("</code></pre>");*/

      $apcstats = shell_exec($this->exec_apc_path);
      $info = [];

      $separator = "\r\n";
      $line = strtok($apcstats, $separator);

      // https://stackoverflow.com/a/14789147/6828099
      while ($line !== false) {
        # do something with $line
        $parseme = explode(":", $line);

        if(sizeof($parseme) == 2) {
          $info[trim($parseme[0])] = trim($parseme[1]);
        } else if(sizeof($parseme) > 2) {
          $value = "";
          // This helps deal with those lines with multiple colons
          for($x=1; $x<sizeof($parseme); $x++) {
            $value = $value . $parseme[$x] . ":";
          }
          $value = rtrim($value,":");

          $info[trim($parseme[0])] = trim($value);
        }
        $line = strtok($separator);
      }

      print("<table><thead>");
      print("<th>Info</th>");
      print("<th>Response</th>");
      print("</thead><tbody>");

      foreach($info as $key => $value){
        print("<tr><td>$key</td><td>$value</td></tr>");
      }

      print("</tbody></table>");
    }

    public function displayDateUTC() {
      print('</br><p>');
      system($this->exec_date_path . ' -u || echo "Sorry, but the date command cannot be found!!!"');
      print('</p>');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="APC UPS Battery Stats";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>