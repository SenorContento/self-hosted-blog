<?php
// This is to test that PHP sendmail works correctly on my Real Server
// https://stackoverflow.com/a/12313090

//$to = "web@senorcontento.com";
$to = "random@senorcontento.com";
$from = "web@senorcontento.com";

$subject = "PHP Mail Test";

$checksum = md5(time());

$headers = "From: $from" . "\r\n";
$headers .= "MIME-Version: 1.0" . "\r\n";
//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$headers .= 'Content-Type: multipart/mixed; boundary="' . $checksum . '"' . "\r\n";

//$headers .= "BCC: random@senorcontento.com\r\n";
//$headers .= "CC: postmaster@senorcontento.com";

// Message
$message = "--" . $checksum . "\r\n";
$message .= "Content-Type: text/html; charset=ISO-8859-1" . "\r\n" . "\r\n";
//$message .= "Content-Transfer-Encoding: 8bit" . "\r\n";
$message .= "<!DOCTYPE HTML>";
$message .= "<html>";
$message .= '<body bgcolor="blue">';
$message .= "<h1>Hello World!</h1>";
$message .= "</body>";
$message .= "</html>";

// Attachment
$filename = "test.txt";
$attachment = "--" . $checksum . "\r\n";
$attachment .= 'Content-Type: text/plain; name="' . $filename . '"' . "\r\n";
//$attachment .= "Content-Transfer-Encoding: base64" . "\r\n";
$attachment .= "Content-Disposition: attachment" . "\r\n" . "\r\n";
$attachment .= "This is a text file!!!\r\n";
$attachment .= "--" . $checksum . "--";

// Combine Message and Attachment
$body = $message . "\r\n" . $attachment;

header("Content-Type: text/plain");
print($body);
//print(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));

mail($to,$subject,$body,$headers);
?>