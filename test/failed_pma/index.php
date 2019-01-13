<?php
function customPageHeader() {
  // The function has to be left outside the classes in order to get loaded by the header.php file.
  print('<!--This is here to bookmark how to load a custom page header!!!-->');
}

$loadPage = new loadPage();
$fails = new PHPMyAdmin_Fails();

if(isset($_GET['download_csv'])) $fails->generateTable();

$loadPage->loadHeader();
$fails->printTable();
$loadPage->loadFooter();

class loadPage {
  public function loadHeader() {
    $PageTitle="PHPMyAdmin Failed Password Attempts";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
  }

  public function loadFooter() {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
  }
}

class PHPMyAdmin_Fails {
  # The below variables are for the production server
  public $log_path = "/var/log/nginx/";
  public $exec_find_path = "/usr/bin/find";
  public $exec_xargs_path = "/usr/bin/xargs";
  public $exec_gunzip_path = "/bin/gunzip";
  public $exec_grep_path = "/bin/grep";
  public $exec_awk_path = "/usr/bin/awk";
  public $exec_cut_path = "/usr/bin/cut";
  public $exec_echo_path = "/bin/echo";
  public $exec_sort_path = "/usr/bin/sort";
  public $exec_uniq_path = "/usr/bin/uniq";
  public $exec_wc_path = "/usr/bin/wc";
  public $exec_sed_path = "/bin/sed";

  # The below variables are for testing on localhost
  /*public $log_path = "/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Nginx Server Logs/01:12:19/gzip_logs/";
  public $exec_find_path = "find";
  public $exec_xargs_path = "xargs";
  public $exec_gunzip_path = "gunzip";
  public $exec_grep_path = "grep";
  public $exec_awk_path = "gawk";
  public $exec_cut_path = "cut";
  public $exec_echo_path = "echo";
  public $exec_sort_path = "sort";
  public $exec_uniq_path = "uniq";
  public $exec_wc_path = "wc";
  public $exec_sed_path = "sed";*/

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
    $failed_attempts_parsed = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts) . " | " . $this->exec_awk_path . " -F\"&pma_password=\" -v x=\"\\\"\" '{print \",\\=\"x$1x\",\\=\"x$2x\"\"}'");

    print(",User,Password");
    system($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " | " . $this->exec_grep_path . " -v \",=\\\"\\\",=\\\"\\\"\"" . " | " . $this->exec_sed_path . " 's/=\"\"//g'"); //,="",=""

    die();
  }

  public function printTable() {
    $failed_attempts = $this->generateTable();
    $failed_attempts_parsed = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts) . " | " . $this->exec_awk_path . " -F\"&pma_password=\" '{print \"<tr><td>\"$1\"</td><td>\"$2\"</td></tr>\"}'");

    $failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_wc_path . " -l");
    $uniq_failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " | " . $this->exec_wc_path . " -l");

    print("<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.");
    print("</br></br><a href=\"?download_csv\">Download Table as CSV</a></p>");

    print("<p>Failed Attempts at Hacking PHPMyAdmin:</p>");

    print("<pre><code>");
    print("Unique Fails: " . $uniq_failcount);
    print("Total Fails: " . $failcount);
    print("</pre></code>");

    print("<br><br><table>");
    print("<tr><th>User</th><th>Password</tr>");

    system($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " | " . $this->exec_grep_path . " -v \"<tr><td></td><td></td></tr>\"");
    print("</table>");
  }

  public function generateTable() {
    $failed_attempts = shell_exec($this->exec_find_path . " \"" . $this->log_path . "\" -name \"access.log*\" -follow -type f -print0 | " . $this->exec_xargs_path . " -0 " . $this->exec_gunzip_path . " -cf | " . $this->exec_grep_path . " \"pma_username\" | " . $this->exec_awk_path . " -F\"pma_username=\" '{print $2}' | " . $this->exec_cut_path . " -d'&' -f1,2 | " . $this->exec_cut_path . " -d' ' -f1");

    if(isset($_GET['download_csv'])) $this->downloadCSV($failed_attempts);

    return $failed_attempts;
  }
}