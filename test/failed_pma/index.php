<?php $PageTitle="CSCI 3000 - Web Development";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>

<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.</p>

<p>Failed Attempts at Hacking PHPMyAdmin:</p>
<pre><code>
<?php
//$failed_attempts = shell_exec("cat /var/www/html/test/failed_pma/fails.log");
//$failed_attempts = shell_exec("/bin/bash /var/www/html/test/failed_pma/test.sh");

/* test.sh

count=1
while [ $count -lt 10000 ]
do
  filename="node${count}.shtml"
  echo "$count"
  count=`expr $count + 1`
done

*/

#$failed_attempts = shell_exec("/bin/zcat -f /var/log/nginx/access.log* | /bin/grep \"pma_username\" | /usr/bin/awk -F\"pma_username=\" '{print $2}' | /usr/bin/cut -d'&' -f1,2 | /usr/bin/cut -d' ' -f1 | /usr/bin/awk -F\"&pma_password=\" '{print $1 \"\\t\" $2}'");
$failed_attempts = shell_exec("find \"/var/log/nginx/\" -name \"access.log*\" -follow -type f -print0 | xargs -0 gunzip -cf | grep \"pma_username\" | awk -F\"pma_username=\" '{print $2}' | cut -d'&' -f1,2 | cut -d' ' -f1 | awk -F\"&pma_password=\" '{print $1 \"\\t\" $2}'");

# The below command is for testing on localhost
#$failed_attempts = shell_exec("find \"/Users/senor/Desktop/Nginx Server Logs/01:12:19/access_logs/\" -name \"access.log*\" -follow -type f -print0 | xargs -0 cat | grep \"pma_username\" | awk -F\"pma_username=\" '{print $2}' | cut -d'&' -f1,2 | cut -d' ' -f1 | awk -F\"&pma_password=\" '{print $1 \"\\t\" $2}'");

$failcount = shell_exec("/bin/echo " . escapeshellarg($failed_attempts) . " | /usr/bin/wc -l");
$uniq_failcount = shell_exec("/bin/echo " . escapeshellarg($failed_attempts) . " | /usr/bin/sort | /usr/bin/uniq | /usr/bin/wc -l");

print("Unique Fails: " . $uniq_failcount);
print("Total Fails: " . $failcount);

print("
User  Password
--------------");

system("/bin/echo " . escapeshellarg($failed_attempts) . " | /usr/bin/sort | /usr/bin/uniq");
?>
</code><pre>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>