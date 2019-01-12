<?php $PageTitle="Get User Agent Test Page";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");

  print("<p>Your User Agent: \"" . $_SERVER['HTTP_USER_AGENT'] . "\"</p></br>");

  print("<p>Other User Agents: </br><pre><code>");
  system("/usr/bin/awk -F'\"' '/GET/ {print $6}' /var/log/nginx/access.log | /usr/bin/cut -d' ' -f1 | /usr/bin/sort | /usr/bin/uniq -c | /usr/bin/sort -rn");
  print("</code></pre></p>");

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>