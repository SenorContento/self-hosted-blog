<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="banme.css">');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();

  $mainPage->setVars();
  $mainPage->mainBody();

  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="Am I Banned?";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }

  class mainPage {
    public $exec_fail2ban_path;
    public $exec_fail2ban_log;

    public $exec_grep_path;
    public $exec_cut_path;
    public $exec_tail_path;

    function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->exec_fail2ban_path = "sudo /usr/bin/fail2ban-client status ";
        $this->exec_grep_path = "/bin/grep";
        $this->exec_cut_path = "/usr/bin/cut";
        $this->exec_fail2ban_log = "/bin/cat /var/log/fail2ban.log";
        $this->exec_tail_path = "/usr/bin/tail";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->exec_fail2ban_path = "/bin/cat \"/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Fail2Ban/banned-command.txt\"";
        $this->exec_grep_path = "/usr/bin/grep";
        $this->exec_cut_path = "/usr/bin/cut";
        $this->exec_fail2ban_log = "/bin/cat \"/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Fail2Ban/fail2ban.log\"";
        $this->exec_tail_path = "/usr/bin/tail";
      }
    }

    public function mainBody() {
      list($timebanned, $timeremaining, $banned) = $this->getBanTimeInfo("thp-ssh", 600, $_SERVER["REMOTE_ADDR"]);
      //$banned_check = $this->checkBan("thp-ssh") ? "Yes" : "No";

      // This Code is Still in Alpha. Copy At Your Own Risk!!!
      print("<p>I will explain what this page is about later!!! Just Know It Has To Do With Fail2Ban and SSH!!!</p>");

      print('<h1 style="display: inline-block; text-align: left;">');
      //print("Banned: " . $banned_check);

      print("<br>Current # of Bans: " . $this->getCurrentBanCount("thp-ssh"));
      print("<br>Total # of Bans: " . $this->getTotalBanCount("thp-ssh"));
      print("<br>");

      if($banned === 0) {
        print("Banned: No");
      } else if($banned === -1) {
        print("Banned: Never");
      } else if($banned === 1) {
        print("Banned: Yes");
        print("<br>Time Banned: " . $timebanned->format("y-m-d h:i:s") + " UTC");
        // Add IP Address Somewhere Around Here
        print("<br>Time Remaining: " . $timeremaining->format("%r %y-%m-%d %h:%i:%s") + " UTC");
      }
      print('</h1>');
    }

    public function getCurrentBanCount($service) {
      $service = (getenv('alex.server.type') === "development") ? "" : $service;
      $ban_count = shell_exec($this->exec_fail2ban_path . $service . " | " . $this->exec_grep_path . " \"Currently banned\" | " . $this->exec_cut_path . " -d':' -f2");

      return trim($ban_count);
    }

    public function getTotalBanCount($service) {
      $service = (getenv('alex.server.type') === "development") ? "" : $service;
      $ban_count = shell_exec($this->exec_fail2ban_path . $service . " | " . $this->exec_grep_path . " \"Total banned\" | " . $this->exec_cut_path . " -d':' -f2");

      return trim($ban_count);
    }

    public function checkBan($service) {
      // I originally was going to use this method, but since the ban log provides this info too.
      // I have decided against it. I am still leaving it in this file for future reference.

      // This Command was added to sudoers, I do not allow arbitrary sudo access to web.
      // sudo /usr/bin/fail2ban-client status thp-ssh

      $service = (getenv('alex.server.type') === "development") ? "" : $service;
      $ban_list = shell_exec($this->exec_fail2ban_path . $service . " | " . $this->exec_grep_path . " \"Banned IP list\" | " . $this->exec_cut_path . " -d':' -f2");
      $ban_array = explode(" ", $ban_list);
      $ban_array_trimmed = array_map('trim', $ban_array); // https://stackoverflow.com/a/5762453/6828099

      #print("<p>");
      #print_r($ban_array);
      #print("Your IP: \"" . $_SERVER["REMOTE_ADDR"] . "\"");
      #print("First IP: \"" . $ban_array[0] . "\"");
      #print("Banned: " . in_array($_SERVER["REMOTE_ADDR"], $ban_array_trimmed));
      #print("</p>");

      #if(strval($_SERVER["REMOTE_ADDR"]) === trim(strval($ban_array[0]))) {
      #  print("<b>Banned!!!</b>");
      #} else {
      #  print("<b>Not Banned!!!</b>");
      #}

      return in_array($_SERVER["REMOTE_ADDR"], $ban_array_trimmed) ? true : false;
    }

    public function getBanTimeInfo($service, $timelimit, $ipaddress) {
      $this->exec_fail2ban_path;
      $ban_time = shell_exec($this->exec_fail2ban_log . " | " .
                             $this->exec_grep_path . " fail2ban.actions | " .
                             $this->exec_grep_path . " \"" . $service . "\" | " .
                             $this->exec_grep_path . " \"NOTICE\" | " .
                             $this->exec_grep_path . " \"" . $ipaddress . "\" | " .
                             $this->exec_tail_path . " -n 1");

      $ban_time_array = explode(" ", $ban_time);

      //print("<h1>\"" . $ban_time . "\"</h1>");
      //print_r($ban_time_array);

      if($ban_time === "" || $ban_time === null) { // It equals null
        //print("<h1>Never Banned!!!</h1>");
        return [new DateTime(date('Y-m-d H:i:s T', 0)), new DateTime(date('Y-m-d H:i:s T', 0)), -1];
      }

      //print("<br>Time Banned: " . $ban_time_array[0] . " " . substr($ban_time_array[1], 0, -4));
      //print("<br>Banned?: " . $ban_time_array[14]);

      // $ban_time_array[0] . " " . $ban_time_array[1]; // Format 2019-03-12 21:16:28,880
      $timebanned_string = $ban_time_array[0] . " " . substr($ban_time_array[1], 0, -4);
      $timebanned = new DateTime(date('Y-m-d H:i:s T', strtotime($timebanned_string))); // Format 2019-02-23T06:09:06Z
      $now = new DateTime(date('Y-m-d H:i:s T', time()));
      //print("Formatted: $timebanned<br>");
      $timeunbanned = new DateTime(date('Y-m-d H:i:s T', strtotime('+ ' . $timelimit . ' seconds', strtotime($timebanned_string))));
      $timeremaining = $now->diff($timeunbanned);
      //$timeremaining = $timebanned->diff($now);
      print_r($timeremaining);

      //print("<h1>");
      //print("TimeBanned String: " . $timebanned_string);
      //print($timeremaining->format("%y-%m-%d %h:%i:%s"));
      //print("</h1>");

      if($ban_time_array[14] === "Unban") {
        //print("<h1>Unbanned!!!</h1>");
        return [$timebanned, $timeremaining, 0];
      }

      //print("<h1>Banned!!!</h1>");
      return [$timebanned, $timeremaining, 1];
    }
  }
?>