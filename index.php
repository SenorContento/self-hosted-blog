<?php $PageTitle="CSCI 3000 - Web Development";

  function customPageHeader(){
    print('<!--This Comment is Here Just to Mark Custom Header Inclusion in PHP!!!-->');
  }

  include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/header.php"); ?>
    <a href="/HTML-CSS/">Assignment 1 - HTML-CSS</a>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . "/php_data/footer.php"); ?>