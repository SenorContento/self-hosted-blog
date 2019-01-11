<?php $PageTitle="APC UPS Battery Stats";
  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>
      <!--If only inheritance was a part of the css standard-->
      <pre><code style="color: #90EE90"><?php system('/sbin/apcaccess');
      print('</br>');
      system('/bin/date -u'); ?></code></pre>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>