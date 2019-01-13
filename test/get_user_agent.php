<?php
function customPageHeader() {
  // The function has to be left outside the classes in order to get loaded by the header.php file.
  print('<!--This is here to bookmark how to load a custom page header!!!-->');
}

$loadPage = new loadPage();
$getUserAgent = new userAgent();
//if(isset($_GET['download_csv'])) $fails->generateTable();

$loadPage->loadHeader();
$getUserAgent->printCurrentUserAgent();
$getUserAgent->printAllUserAgentShort();
$loadPage->loadFooter();

class loadPage {
  public function loadHeader() {
    $PageTitle="Get User Agent Test Page";
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
  }

  public function loadFooter() {
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php");
  }
}

class userAgent {
  //public $log_path = "/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Nginx Server Logs/01:12:19/gzip_logs/";
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

  public function printCurrentUserAgent() {
    print("<p>Your User Agent: \"" . $_SERVER['HTTP_USER_AGENT'] . "\"</p></br>");
  }

  public function printAllUserAgentShort() {
    print("<p>All User Agents (Short): </br>");
    print("<pre><code>");

    system($this->exec_find_path . " \"" . $this->log_path . "\" -name \"access.log*\" -follow -type f -print0 | " . $this->exec_xargs_path . " -0 " . $this->exec_gunzip_path . " -cf | " . $this->exec_awk_path . " -F'\"' '/GET/ {print $6}' | " . $this->exec_cut_path . " -d' ' -f1 | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " -c | " . $this->exec_sort_path . " -rn");
    //system($this->exec_awk_path . " -F'\"' '/GET/ {print $6}' /var/log/nginx/access.log | /usr/bin/cut -d' ' -f1 | /usr/bin/sort | /usr/bin/uniq -c | /usr/bin/sort -rn");

    print("</code></pre>" . '\n' . "</p>");
  }
} ?>