<?php $PageTitle="APC UPS Battery Stats";

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php");
  print("<pre><code>");

  system('/sbin/apcaccess');
  print('</br>');
  system('/bin/date -u');
  
  print("</code></pre>");
  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>