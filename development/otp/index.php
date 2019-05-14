<?php
  require_once('rfc6238.php');
  $secretkey = 'SECRETKEYHERE';  //your secret code

  print(TokenAuth6238::getTokenCode($secretkey,0));
?>
