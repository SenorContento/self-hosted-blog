<?php
  function customPageHeader() {
    // The function has to be left outside the classes in order to get loaded by the header.php file.
    print('<!--This is here to bookmark how to load a custom page header!!!-->');
  }

  $loadPage = new loadPage();
  $getUserAgent = new userAgent();

  $loadPage->loadHeader();
  $getUserAgent->setVars();
  $getUserAgent->printCurrentUserAgent();
  $getUserAgent->printAllUserAgentShort();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="Get User Agent Test Page";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }

  class userAgent {
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
        $this->log_path = "/var/log/nginx";
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

    public function printCurrentUserAgent() {
      print("<p>Your User Agent: \"" . $_SERVER['HTTP_USER_AGENT'] . "\"</p></br>");
    }

    public function printAllUserAgentShort() {
      print("<p>All User Agents (Short): </br>");
      print("<pre style=\"display: inline-block; text-align: left;\"><code>");

      // Removing the awk command between gunzip and cut causes the command to organize access by IP Address instead of User Agent
      system($this->exec_find_path . " \"" . $this->log_path . "\" -name \"access.log*\" -follow -type f -print0 | " .
      $this->exec_xargs_path . " -0 " . $this->exec_gunzip_path . " -cf | " .
      $this->exec_awk_path . " -F'\"' '/GET/ {print $6}' | " .
      $this->exec_cut_path . " -d' ' -f1 | " .
      $this->exec_sort_path . " | " .
      $this->exec_uniq_path . " -c | " .
      $this->exec_sort_path . " -rn");

      print("</code></pre></p>");
    }
  }
?>