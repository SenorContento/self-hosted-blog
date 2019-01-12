<?php $PageTitle="CSCI 3000 - Web Development";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>

<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.</p>

<p>Failed Attempts at Hacking PHPMyAdmin:</p>
<pre><code>
<?php
$failed_attempts = shell_exec("/bin/zcat -f /var/log/nginx/access.log* | /bin/grep \"pma_username\" | /usr/bin/awk -F\"pma_username=\" '{print $2}' | /usr/bin/cut -d'&' -f1,2 | /usr/bin/cut -d' ' -f1 | /usr/bin/awk -F\"&pma_password=\" '{print $1 \"\\t\" $2}'");
$encoded_fails = base64_encode($failed_attempts);

$failcount = shell_exec("/bin/echo \"" . $encoded_fails . "\" | /usr/bin/base64 -d | wc -l");
$uniq_failcount = shell_exec("/bin/echo \"" . $encoded_fails . "\" | /usr/bin/base64 -d | sort | uniq | wc -l");

print("Unique Fails: " . $uniq_failcount);
print("Total Fails: " . $failcount);

print("
User  Password
--------------
" . base64_decode($encoded_fails));
?>
</code><pre>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>