<?php $PageTitle="APC UPS Battery Stats";

  function customPageHeader() {
    print('<link rel="stylesheet" href="/css/main.css">');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>
      <pre><code><?php system('/sbin/apcaccess');
      print('</br>');
      system('/bin/date -u'); ?></code></pre>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>