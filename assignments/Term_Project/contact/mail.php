<?php

if(!defined('INCLUDED')) {
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
  $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
  include($root . "/errors/404/index.php");
  die();
}

class contactMail {
  public function sendMail($to, $from, $subject, $body) {
    $this->sendMailAttachment($to, $from, $subject, $body, Null);
  }

  public function sendMailAttachment($to, $from, $subject, $body, $attachment) {
    $checksum = md5(time());

    $headers = "From: $from" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    //$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= 'Content-Type: multipart/mixed; boundary="' . $checksum . '"' . "\r\n";
    $headers .= "Alex-Term-Project-Contact-Form: true" . "\r\n";

    //$headers .= "BCC: random@example.com\r\n";
    //$headers .= "CC: postmaster@example.com";

    // Message Headers
    $messagehead = "--" . $checksum . "\r\n";
    $messagehead .= "Content-Type: text/html; charset=ISO-8859-1" . "\r\n";
    $messagehead .= "Content-Transfer-Encoding: base64" . "\r\n" . "\r\n";


    $bgcolor = "cyan";
    // Header
    $head = "<!DOCTYPE HTML>";
    $head .= "<html>";
    $head .= '<body bgcolor="' . $bgcolor . '" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
    $head .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
    $head .= '<tr>';
    $head .= '<td width="650" valign="top" align="center" bgcolor="' . $bgcolor . '">';
    $head .= '<p style="display: inline-block;text-align: left;">';

    $content = $body;

    // Footer
    $foot = '</p>';
    $foot = '</td>';
    $foot .= '</tr>';
    $foot .= '</table>';
    $foot .= "</body>";
    $foot .= "</html>";

    if(!empty($attachment)) {
      $fileobject = $attachment;
      $filename = htmlspecialchars($fileobject["name"], ENT_QUOTES, 'UTF-8');
      $file = file_get_contents($fileobject["tmp_name"]);
      $fileType = mime_content_type($fileobject["tmp_name"]);

      // Attachment
      $mailattachment = "--" . $checksum . "\r\n";
      $mailattachment .= 'Content-Type: ' . $fileType . '; name="' . $filename . '"' . "\r\n";
      $mailattachment .= "Content-Transfer-Encoding: base64" . "\r\n";
      $mailattachment .= "Content-Disposition: attachment" . "\r\n" . "\r\n";
      $mailattachment .= base64_encode($file) . "\r\n";
      $mailattachment .= "--" . $checksum . "--";

      // Combine Message and Attachment
      $body = $messagehead . base64_encode($head . $content . $foot) . "\r\n" . $mailattachment;
    } else {
      $body = $messagehead . base64_encode($head . $content . $foot);
    }

    mail($to,$subject,$body,$headers);
  }
}
?>