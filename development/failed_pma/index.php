<?php
  function customPageHeader() {
    // The function has to be left outside the classes in order to get loaded by the header.php file.
    print('<!--This is here to bookmark how to load a custom page header!!!-->');
  }

  $loadPage = new loadPage();
  $fails = new PHPMyAdmin_Fails();
  $fails->setVars();

  if(isset($_GET['download_csv'])) $fails->generateTable();

  $loadPage->loadHeader();
  $fails->printTable();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="PHPMyAdmin Failed Password Attempts";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }

  class PHPMyAdmin_Fails {
    public $log_path;
    public $exec_find_path;
    public $exec_xargs_path;
    public $exec_gunzip_path;
    public $exec_grep_path;
    public $exec_awk_path;
    public $exec_cut_path;
    public $exec_echo_path;
    public $exec_sort_path;
    public $exec_uniq_path;
    public $exec_wc_path;
    public $exec_sed_path;

    function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        #$this->log_path = "/var/log/nginx"; # Disabled because logs are big now and I moved PHPMyAdmin
        $this->log_path = "/home/web/nginx/logs"; # To Display Archived Logs from Hacking Attempt

        $this->exec_find_path = "/usr/bin/find";
        $this->exec_xargs_path = "/usr/bin/xargs";
        $this->exec_gunzip_path = "/bin/gunzip";
        $this->exec_grep_path = "/bin/grep";
        $this->exec_awk_path = "/usr/bin/awk";
        $this->exec_cut_path = "/usr/bin/cut";
        $this->exec_echo_path = "/bin/echo";
        $this->exec_sort_path = "/usr/bin/sort";
        $this->exec_uniq_path = "/usr/bin/uniq";
        $this->exec_wc_path = "/usr/bin/wc";
        $this->exec_sed_path = "/bin/sed";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->log_path = "/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Nginx Server Logs/01:12:19/gzip_logs";
        $this->exec_find_path = "find";
        $this->exec_xargs_path = "xargs";
        $this->exec_gunzip_path = "gunzip";
        $this->exec_grep_path = "grep";
        $this->exec_awk_path = "gawk";
        $this->exec_cut_path = "cut";
        $this->exec_echo_path = "echo";
        $this->exec_sort_path = "sort";
        $this->exec_uniq_path = "uniq";
        $this->exec_wc_path = "wc";
        $this->exec_sed_path = "sed";
      }
    }

    function downloadHeaders($filename) {
      //Disable Caching
      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Thur, 01 Jan 1970 00:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");

      //Force Download
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");

      //Encoding Response
      header("Content-Disposition: attachment;filename={$filename}");
      header("Content-Transfer-Encoding: binary");
    }

    public function downloadCSV($failed_attempts) {
      $this->downloadHeaders("PHPMyAdmin_Fails_" . date("m-d-Y") . ".csv");
      $failed_attempts_parsed = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts) . " | " .
      $this->exec_awk_path . " -F\"&pma_password=\" -v x=\"\\\"\" '{print \"1,\\=\"x$1x\",\\=\"x$2x\"\"}'");

      print("0,User,Password");
      system($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " .
      $this->exec_sort_path . " | " .
      $this->exec_uniq_path . " | " .
      $this->exec_grep_path . " -v \"1,=\\\"\\\",=\\\"\\\"\"" . " | " .
      $this->exec_sed_path . " 's/=\"\"//g'"); //,="",=""

      die();
    }

    public function printTable() {
      $failed_attempts = $this->generateTable();
      // Currently Awk does not want to work with htmlspecialchars
      //print("Original: " . mb_detect_encoding($failed_attempts));
      //print("HTMLSpecialChars: " . mb_detect_encoding(htmlspecialchars(escapeshellarg($failed_attempts), ENT_NOQUOTES, 'UTF-8')));
      $failed_attempts_parsed = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts) . " | " .
      $this->exec_awk_path . " -F\"&pma_password=\" '{print \"<tr><td>\"$1\"</td><td>\"$2\"</td></tr>\"}'");

      $failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " .
      $this->exec_wc_path . " -l");

      $uniq_failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " .
      $this->exec_sort_path . " | " .
      $this->exec_uniq_path . " | " .
      $this->exec_wc_path . " -l");

      print("<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.");
      print("</br></br><a href=\"?download_csv\">Download Table as CSV</a></p>");

      print("<p>Failed Attempts at Hacking PHPMyAdmin:</p>");

      print("<pre><code>");
      print("Unique Fails: " . preg_replace('/\s+/', '', $uniq_failcount) . "\n");
      print("Total Fails: " . preg_replace('/\s+/', '', $failcount));
      print("</pre></code>");

      print("<br><br><table>");
      print("<tr><th>User</th><th>Password</tr>");

      system($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " .
      $this->exec_sort_path . " | " .
      $this->exec_uniq_path . " | " .
      $this->exec_grep_path . " -v \"<tr><td></td><td></td></tr>\"");

      print("</table>");
    }

    public function generateTable() {
      $failed_attempts = shell_exec($this->exec_find_path . " \"" . // Injection Free
      $this->log_path . "\" -name \"access.log*\" -follow -type f -print0 | " . // Injection Free
      $this->exec_xargs_path . " -0 " . $this->exec_gunzip_path . " -cf | " . // Injection Free
      $this->exec_grep_path . " \"pma_username\" | " . // Appears to Be Good
      $this->exec_awk_path . " -F\"pma_username=\" '{print $2}' | " . // Appears to Be Good
      $this->exec_cut_path . " -d'&' -f1,2 | " . // Appears to Be Good
      $this->exec_cut_path . " -d' ' -f1"); // Appears to Be Good

      if(isset($_GET['download_csv'])) $this->downloadCSV($failed_attempts);

      return $failed_attempts;
    }
  }
?>