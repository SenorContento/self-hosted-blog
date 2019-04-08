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

  $loadPage = new loadPage();
  $mainPage = new contactMe();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printForm();
  $loadPage->loadFooter();

  class contactMe {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
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