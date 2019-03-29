<?php
// This is to test that PHP sendmail works correctly on my Real Server
// https://stackoverflow.com/a/12313090
// https://stackoverflow.com/a/23650991

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

// Message Headers
$messagehead = "--" . $checksum . "\r\n";
$messagehead .= "Content-Type: text/html; charset=ISO-8859-1" . "\r\n" . "\r\n";
//$messagehead .= "Content-Transfer-Encoding: 8bit" . "\r\n";


$bgcolor = "cyan";
// Header
$head = "<!DOCTYPE HTML>";
$head .= "<html>";
$head .= '<body bgcolor="' . $bgcolor . '" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
$head .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
$head .= '<tr>';
$head .= '<td width="650" valign="top" align="center" bgcolor="' . $bgcolor . '">';

$content = "<h1 style='color: red'>Hello World!!!</h1>";

$content .= "<p style='color: red'>";
$max = 200;
for ($i = 1; $i <= $max; $i++) {
    $content .= "Number: $i/$max" . "<br>" . "\r\n";
}
$content .= "</p>";

// Footer
$foot = '</td>';
$foot .= '</tr>';
$foot .= '</table>';
$foot .= "</body>";
$foot .= "</html>";

// Attachment
$filename = "date.txt";
date_default_timezone_set('UTC');
$file = date('l jS \of F Y h:i:s A e');

$attachment = "--" . $checksum . "\r\n";
$attachment .= 'Content-Type: text/plain; name="' . $filename . '"' . "\r\n";
$attachment .= "Content-Transfer-Encoding: base64" . "\r\n";
$attachment .= "Content-Disposition: attachment" . "\r\n" . "\r\n";
$attachment .= base64_encode($file) . "\r\n";
$attachment .= "--" . $checksum . "--";

// Combine Message and Attachment
$body = $messagehead . $head . $content . $foot. "\r\n" . $attachment;

header("Content-Type: text/plain");
print($body);
//print(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));

mail($to,$subject,$body,$headers);
?>