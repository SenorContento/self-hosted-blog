<?php
// This is to test that PHP sendmail works correctly on my Real Server
$to = "web@senorcontento.com";
$from = "web@senorcontento.com";

$subject = "PHP Mail Test";

$headers = "From: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$headers .= "BCC: random@senorcontento.com\r\n";
$headers .= "CC: postmaster@senorcontento.com";

$message = "<!DOCTYPE HTML>";
$message .= "<html>";
$message .= '<body bgcolor="blue">';
$message .= "<h1>Hello World!</h1>";
$message .= "</body>";
$message .= "</html>";

mail($to,$subject,$message,$headers);
?>