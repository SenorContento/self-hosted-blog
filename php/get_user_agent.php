<?php $PageTitle="Get User Agent Test Page";

  function customPageHeader(){
    print('<!--This Comment is Here Just to Mark Custom Header Inclusion in PHP!!!-->');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");

  print("<p>Your User Agent: \"" . $_SERVER['HTTP_USER_AGENT'] . "\"</br></br>");

  print("Other User Agents: </br><pre>");
  system("/usr/bin/awk -F'\"' '/GET/ {print $6}' /var/log/nginx/access.log | /usr/bin/cut -d' ' -f1 | /usr/bin/sort | /usr/bin/uniq -c | /usr/bin/sort -rn");
  print("</pre></p>");

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>