<?php
  ini_set('display_errors',1);
  error_reporting(E_ALL);

  // https://stackoverflow.com/a/3406181/6828099
  // This is used to convert all warnings, errors, etc... into exceptions that I can handle.
  set_error_handler(
    function ($severity, $message, $file, $line) {
      // This if statement executes if the statement has an @ symbol in front of it.
      // http://php.net/manual/en/function.set-error-handler.php
      if (0 === error_reporting()) {
        //print("Help! Help! I'm Being Suppressed!!! Monty Python - https://www.youtube.com/watch?v=ZtYU87QNjPw");
        return false;
      }

      //print("$message, $severity, $file, $line");
      //if($message !== "openssl_encrypt(): Using an empty Initialization Vector (iv) is potentially insecure and not recommended")

      throw new ErrorException($message, $severity, $severity, $file, $line);
    }
  );

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
    print("\n\t\t" . '<meta name="keywords" content="GPG,PGP,Keyserver">');
    //print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  // https://stackoverflow.com/a/2397010/6828099
  define('INCLUDED', 1);
  require_once 'mysql.php';

  $sqlCommands = new sqlCommands();
  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $mainPage->setVars();
  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          getenv('alex.server.phpmyadmin.database'));

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->checkUpload("https://keyserver.senorcontento.com", $sqlCommands);

  $mainPage->printUploadForm();
  $mainPage->printSearchForm();

  $mainPage->checkSearch("https://keyserver.senorcontento.com");
  $loadPage->loadFooter();

  class mainPage {
    public $exec_gpg_path;
    public $exec_head_path;
    public $exec_tail_path;

    public function setVars() {
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->exec_gpg_path = "/usr/bin/gpg";
        $this->exec_head_path = "/usr/bin/head";
        $this->exec_tail_path = "/usr/bin/tail";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->exec_gpg_path = "/usr/local/bin/gpg";
        $this->exec_head_path = "/usr/bin/head";
        $this->exec_tail_path = "/usr/bin/tail";
      }
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function checkUpload($keyserver, $sqlCommands) {
      // https://stackoverflow.com/a/6609181/6828099
      if(isset($_FILES["pgp-key"])) {
        $uploaded_file_name = htmlspecialchars($_FILES["pgp-key"]["name"], ENT_QUOTES, 'UTF-8');
        $uploaded_file = $_FILES["pgp-key"]["tmp_name"];

        if(empty($uploaded_file)) {
          print("<div class=\"error\">No File Uploaded!!!</div><br>");
          return -5;
        }

        $file_contents = @file_get_contents($uploaded_file);

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

          date_default_timezone_set("UTC"); // Set Time To UTC Format
          $dateadded = time(); // Get Current Server Time
          $checksum = hash_file("sha256", $uploaded_file);

          $command = $this->exec_gpg_path . " --quiet \"" . $uploaded_file . "\" | " . $this->exec_head_path . " -n2 | " . $this->exec_tail_path . " -n1";
          exec($command, $results, $returncode);
          print("Results: ");
          var_dump($results);
          $fingerprint = trim($results[0]);
          $keyid = substr($fingerprint,-16);

          //print("Results: '$results'");
          print("<div class=\"success\">$message</div><br>");
          // Hashes - https://bitbucket.org/jpclizbe/sks-keyserver/src/496fa83faa6fb4ce31b8ba08baaca58e33c559fd/dbserver.ml?fileviewer=file-view-default
          $sqlCommands->insertData($keyid, $fingerprint, "Hash Not Supported Yet!!!", $_SERVER["REMOTE_ADDR"], $checksum, $dateadded);
        } else {
          print("<div class=\"error\">Error Uploading Key (Incorrect Format?)!!!</div><br>");
        }
      }
    }

    public function checkSearch($keyserver) {
      // Search Data Here!!!

      $key = isset($_REQUEST["key_value"]) ? $_REQUEST["key_value"] : NULL;
      if(!empty($key)) {
        $this->searchKeyserver($keyserver, $key);
      }
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
            <div class="div-key-input minified">
              <label for="key_value" class="name">Search OpenPGP Keys: </label><span class="hidden-newline-mobile"><br></span>
              <input class="key-input" id="key_value" name="key_value" type="text" required>
              <input type="submit" class="submit-button" value="Search Keys" name="submit">
            </div>
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

      try {
        $page = file_get_contents($keyserver . "/pks/lookup?search=$query&fingerprint=on&op=vindex&hash=on");
      } catch(ErrorException $e) {
        //print("Error: " . $e->getMessage());
        $error = explode(":", $e->getMessage());
        //print("Message: '" . $error[3] . "'");
        //print("Found: '" . (int) strpos($error[3], '404 Not found') . "'");

        // https://stackoverflow.com/a/4366748/6828099
        if(strpos($error[3], '404 Not found') !== false) {
          // file_get_contents(https://keyserver.senorcontento.com/pks/lookup?search=google&fingerprint=on&op=vindex&hash=on): failed to open stream: HTTP request failed! HTTP/1.1 404 Not found
          print("<div class=\"error\">Key Not Found!!!</div><br>");
        } else {
          print("<div class=\"error\">Cannot Connect To Keyserver!!!</div><br>");
        }

        /*if(empty($page)) {
          print("<div class=\"error\">Cannot Connect To Keyserver!!!</div><br>");
          return -9;
        }*/

        return -9;
      }

      $html = new DOMDocument();
      $html->loadHTML($page);

      $keys = $html->documentElement->getElementsByTagName('pre');
      //print("X: $keys");

      // Start Table
      print('<div class="table-div">
          <table>
            <thead>
              <tr>
                <th>Download Key (ASCII-Armored)</th>
                <th>Key Info</th>
              </tr>
            </thead>
            <tbody>');

      $isFirst = true;
      foreach ($keys as $key) {
        // Hides Information Header
        if($isFirst) {
          $isFirst = false;
          continue;
        }

        // [0] is raw binary of requested key(s). [1] is information about key signatures.
        $keyurl = explode("=",$key->getElementsByTagName('a')[0]->getAttribute("href"));
        $keyid = $keyurl[sizeof($keyurl)-1];
        //var_dump($keyid);

        //print("KeyID: $keyid");

        print("<tr><td data-column-name='Download Key (ASCII-Armored)'>");
        print("<a class='key-download' href='$keyserver/pks/lookup?op=get&options=mr&search=$keyid'>$keyid</a>");
        print("</td><td data-column-name='Key Info'>");
        print("<span class='key-info'><span class='hidden-newline-mobile'><br><br></span>" . nl2br($this->removeFirstLine(htmlspecialchars($key->nodeValue, ENT_QUOTES, 'UTF-8'))) . "</span>");
        print("</td></tr>");
      }

      // End Table
      print('
      </tbody>
    </table></div>');

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