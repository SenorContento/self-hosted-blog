<?php $PageTitle="PHPMyAdmin Failed Password Attempts";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>

<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.</p>

<p>Failed Attempts at Hacking PHPMyAdmin:</p>
<pre><code>
<?php
$fails = new PHPMyAdmin_Fails();
$fails->printTable();

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

  # The below variables are for testing on localhost
  /*public $log_path = "/Users/senor/Documents/Class/2019/Spring/CSCI 3000/Nginx Server Logs/01:12:19/gzip_logs/";
  public $exec_find_path = "find";
  public $exec_xargs_path = "xargs";
  public $exec_gunzip_path = "gunzip";
  public $exec_grep_path = "grep";
  public $exec_awk_path = "awk";
  public $exec_cut_path = "cut";
  public $exec_echo_path = "echo";
  public $exec_sort_path = "sort";
  public $exec_uniq_path = "uniq";
  public $exec_wc_path = "wc";*/

  public function downloadCSV() {
    print("<h1>Test</h1>");
  }

  public function printTable() {
    $failed_attempts = $this->generateTable();
    $failed_attempts_parsed = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts) . " | " . $this->exec_awk_path . " -F\"&pma_password=\" '{print \"<tr><td>\"$1\"</td><td>\"$2\"</td></tr>\"}'");

    $failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_wc_path . " -l");
    $uniq_failcount = shell_exec($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " | " . $this->exec_wc_path . " -l");

    print("Unique Fails: " . $uniq_failcount);
    print("Total Fails: " . $failcount);

    print("<br><br><table>");
    print("<tr><th>User</th><th>Password</tr>");

    system($this->exec_echo_path . " " . escapeshellarg($failed_attempts_parsed) . " | " . $this->exec_sort_path . " | " . $this->exec_uniq_path . " | " . $this->exec_grep_path . " -v \"<tr><td></td><td></td></tr>\"");
    print("</table>");
  }

  public function generateTable() {
    $failed_attempts = shell_exec($this->exec_find_path . " \"" . $this->log_path . "\" -name \"access.log*\" -follow -type f -print0 | " . $this->exec_xargs_path . " -0 " . $this->exec_gunzip_path . " -cf | " . $this->exec_grep_path . " \"pma_username\" | " . $this->exec_awk_path . " -F\"pma_username=\" '{print $2}' | " . $this->exec_cut_path . " -d'&' -f1,2 | " . $this->exec_cut_path . " -d' ' -f1");

    if(isset($_GET['download_csv'])) $this->downloadCSV();

    return $failed_attempts;
  }
} ?>
</code><pre>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>