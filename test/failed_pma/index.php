<?php $PageTitle="CSCI 3000 - Web Development";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>

<p>No Real Logins Show Up Here because Real Logins use POST requests, these attacks use GET requests.</p>

<p>Failed Attempts at Hacking PHPMyAdmin:</p>
<pre><code>
User  Password
--------------
<?php system("cat /var/log/nginx/access.log | /bin/grep \"phpmyadmin/index.php?pma_username\" | /usr/bin/cut -d'?' -f2 | /usr/bin/cut -d'&' -f1,2 | /usr/bin/cut -d'=' -f2- | /usr/bin/awk -F\"&pma_password=\" '{print $1 \"\t\" $2}"); ?>
</code><pre>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>