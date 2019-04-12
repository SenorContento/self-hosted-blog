<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="keyserver.css">');
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="delayed.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');

    print("\n\t\t" . '<script src="stylize.js"></script>');
    print("\n\t\t" . '<script src="fileupload.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="PGP Keyserver!!!">');
    print("\n\t\t" . '<meta name="keywords" content="GPG,Keyserver">');
    //print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->checkUpload("https://keyserver.senorcontento.com");
  $mainPage->checkSearch("https://keyserver.senorcontento.com");

  $mainPage->printUploadForm();
  $mainPage->printSearchForm();

  $mainPage->searchKeyserver("https://keyserver.senorcontento.com", "senorcontento.com");
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function checkUpload($keyserver) {
      // https://stackoverflow.com/a/6609181/6828099
      if(isset($_FILES["pgp-key"])) {
        $uploaded_file_name = htmlspecialchars($_FILES["pgp-key"]["name"], ENT_QUOTES, 'UTF-8');
        $uploaded_file = $_FILES["pgp-key"]["tmp_name"];

        if(empty($uploaded_file)) {
          print("<div class=\"error\">No File Uploaded!!!</div><br>");
          return -5;
        }

        $file_contents = file_get_contents($uploaded_file);

        $url = $keyserver . "/pks/add";
        $data = array('keytext' => $file_contents);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */ }

        if(!empty($result)) {
          // https://stackoverflow.com/a/6143979/6828099
          $lines = explode("\n", $result);
          $lines = array_slice($lines, -2);
          $line = implode("\n", $lines);

          $message = explode("<br>", $line)[1];

          print("<div class=\"success\">$message</div><br>");
        } else {
          print("<div class=\"error\">Error Uploading Key (Incorrect Format?)!!!</div><br>");
        }
      }
    }

    public function checkSearch($keyserver) {
      // Search Data Here!!!
    }

    public function printUploadForm() {
      // https://stackoverflow.com/a/23706177/6828099
      print('
      <!--This Pre Tag Exists to Help With Javascript Resizing-->
      <pre class="sizing-tag-hidden" id="sizing-tag"></pre>

      <fieldset>
        <legend>Upload PGP Key</legend>
        <form method="post" enctype="multipart/form-data">
          <div class="file-input">
            <label for="pgp-file-input" class="upload">Select (ASCII-Armored) OpenPGP Key: </label>
            <span class="upload-break">
              <label class="file-button"><span id="pgp-filename" class="select-file-message">No Key Selected</span><input type="file" name="pgp-key" class="upload-box" id="pgp-file-input"></label>
              <input type="submit" class="submit-button" value="Upload Key" name="submit">
            </span>
          </div>
        </form>
      </fieldset>
      ');
    }

    public function printSearchForm() {
      // https://stackoverflow.com/a/23706177/6828099
      print('
      <!--This Pre Tag Exists to Help With Javascript Resizing-->
      <!--<pre class="sizing-tag-hidden" id="sizing-tag"></pre>-->

      <fieldset>
        <legend>Search PGP Keys</legend>
        <form method="post" enctype="multipart/form-data">
          <div class="file-input">
            <label for="pgp-file-input" class="upload">Select (ASCII-Armored) OpenPGP Key: </label>
            <span class="upload-break">
              <label class="file-button"><span id="pgp-filename" class="select-file-message">No Key Selected</span><input type="file" name="pgp-key" class="upload-box" id="pgp-file-input"></label>
              <input type="submit" class="submit-button" value="Upload Key" name="submit">
            </span>
          </div>
        </form>
      </fieldset>
      ');
    }

    public function searchKeyserver($keyserver, $query) {
      // https://www.php.net/manual/en/domnodelist.item.php
      // https://www.w3schools.com/php/func_string_nl2br.asp
      // https://stackoverflow.com/a/371563/6828099
      // https://www.php.net/manual/en/domelement.getattribute.php

      $page = file_get_contents($keyserver . "/pks/lookup?search=$query&fingerprint=on");

      $html = new DOMDocument();
      $html->loadHTML($page);

      $keys = $html->documentElement->getElementsByTagName('pre');
      //print("X: $keys");

      // Start Table
      print('
          <table>
            <thead>
              <tr>
                <th>Download Key (Binary)</th>
                <th>Key Info</th>
              </tr>
            </thead>
            <tbody>');

      $isFirst = true;
      foreach ($keys as $key) {
        if ($isFirst) {
          $isFirst = false;
          continue;
        }

        $keyurl = explode("=",$key->getElementsByTagName('a')[0]->getAttribute("href"));
        $keyid = $keyurl[sizeof($keyurl)-1];
        //var_dump($keyid);

        //print("KeyID: $keyid");

        print("<tr><td>");
        print("<a class='key-download' href='$keyserver/pks/lookup?op=get&options=mr&search=$keyid'>$keyid</a>");
        print("</td><td>");
        print("<span class='key-info'>" . nl2br($this->removeFirstLine(htmlspecialchars($key->nodeValue, ENT_QUOTES, 'UTF-8'))) . "</span>");
        print("</td></tr>");
      }

      // End Table
      print('
      </tbody>
    </table>');

      /*$x = $html->documentElement;
      foreach ($x->childNodes AS $item) {
        print $item->nodeName . " = " . $item->nodeValue . "<br>";
      }*/

      //print $html->saveHTML();
    }

    private function removeFirstLine($lines) {
      // https://stackoverflow.com/a/7740611/6828099
      return preg_replace('/^.+\n/', '', $lines);
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="GPG Keyserver Interface!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>