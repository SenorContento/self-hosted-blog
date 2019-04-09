<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="contact.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="delayed.css">');
    print("\n\t\t" . '<script src="stylize.js"></script>');
    print("\n\t\t" . '<script src="fileupload.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Contact Form!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Contact,Me">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  define('INCLUDED', 1);
  require_once 'mail.php';

  $loadPage = new loadPage();
  $mainPage = new contactMe();
  $mail = new contactMail();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->readData($mail);
  $mainPage->printForm();
  $loadPage->loadFooter();

  class contactMe {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
    }

    public function readData($mail) {
      // name - Name
      // message - Message
      // email - Email Address
      // attachment - Attachment
      if(!empty($_REQUEST)) {
        try {
          $namevalidated = empty($_REQUEST["name"]) ? false : true;
          $messagevalidated = empty($_REQUEST["message"]) ? false : true;

          if($namevalidated && $messagevalidated) {
            $validated = true;
          } else {
            $validated = false;

            if(!$namevalidated) {
              throw new Exception("name");
            } else if(!$messagevalidated) {
              throw new Exception("message");
            } else {
              throw new Exception("unknown");
            }
          }
        } catch(Exception $e) {
          // $e->getMessage()
          print("<div class='error'>Sorry, but the form field, " . $e->getMessage() . ", is missing!!!</div>");
        }

        if($validated) {
          $name_limit = 20;
          $message_limit = 365;
          $email_limit = 254;

          $name = htmlspecialchars(substr($_REQUEST['name'], 0, $name_limit), ENT_QUOTES, 'UTF-8');
          $message = htmlspecialchars(substr($_REQUEST['message'], 0, $message_limit), ENT_QUOTES, 'UTF-8');
          //$email = htmlspecialchars(substr($this->getValue('email'), 0, $email_limit), ENT_QUOTES, 'UTF-8');

          $body = "Name: $name" . "<br>";
          $body .= "Message: $message";

          $to = getenv('alex.server.mail.contact.to');
          $from = getenv('alex.server.mail.from');
          if(isset($_FILES["attachment"]["size"]) && $_FILES["attachment"]["size"] > 0) {
            //print("Attachment!!!");
            //var_dump($_FILES["attachment"]);
            $subject = "Term Project - Contact Me Form - Attachment";
            $mail->sendMailAttachment($to, $from, $subject, $body, $_FILES["attachment"]);
          } else {
            //print("No Attachment!!!");
            $subject = "Term Project - Contact Me Form";
            $mail->sendMail($to, $from, $subject, $body);
          }
        }
      }
    }

    public function printForm() {
      print('
      <h1 class="contact-message">Contact Me</h1>

      <!--This Pre Tag Exists to Help With Javascript Resizing-->
      <pre class="sizing-tag-hidden" id="sizing-tag"></pre>

      <div class="form-div">
        <form method="post" enctype="multipart/form-data">
          <div class="minified" data-tip="Limited to 20 Characters!!!">
            <div class="div-name-input minified">
              <label for="name" class="name">Name: </label>
              <input class="name-input" id="name" name="name" type="text" maxlength="20" required>
            </div>
          </div>

          <div class="minified" data-tip="Limited to 365 Characters!!!">
            <div class="about-textarea minified">
              <label for="message" class="about">Message: </label><br>
              <textarea class="textarea" id="message" name="message" maxlength="365" required></textarea><br>
            </div>
          </div>

          <!-- I don\'t have a site antispam filter set up, so I am not adding support for this yet!!!-->
          <!--<div class="minified" data-tip="Limited to 254 Characters!!!">-->
          <!-- RFC 2821 - http://www.rfc-editor.org/errata/eid1690 -->
            <!--<div class="div-name-input minified">
              <label for="email" class="email">Send Me A Copy (Optional): </label>
              <input class="email-input" id="email" name="email" type="email" placeholder="example@example.com" maxlength="254">
            </div>
          </div>-->

          <div class="file-input">
            <label for="attachment" class="upload">Add Attachment (Optional): </label>
            <span class="upload-break">
              <label class="file-button"><span id="attachment-filename" class="select-file-message">No File Selected</span><input type="file" name="attachment" class="upload-box" id="attachment"></label>
              <input type="submit" class="submit-button" value="Send Mail" name="submit">
            </span>
          </div>
        </form>
      </div>
      ');
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Contact Me";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>