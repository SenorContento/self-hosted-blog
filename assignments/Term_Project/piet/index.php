<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="piet.css">');
    //print("\n\t\t" . '<script src="/js/jquery-3.3.1.min.js"></script>');
  }

  require_once 'mysql.php';

  $loadPage = new loadPage();
  $sqlCommands = new sqlCommands();
  $mainPage = new PietUploader();

  $loadPage->loadHeader();

  // While the PHP form uses the web user, the bash script uses the piet user.
  // The piet user will only have read permission while PHP will get read/write.
  // I created a whole separate database for the piet project.
  $sqlCommands->setLogin(getenv('alex.server.phpmyadmin.host'),
                          getenv('alex.server.phpmyadmin.username'),
                          getenv('alex.server.phpmyadmin.password'),
                          "piet");

  $sqlCommands->testConnection();
  $sqlCommands->connectMySQL();
  $sqlCommands->createTable();

  $mainPage->setVars();

  $mainPage->verifyMySQLVars($mainPage->checkUpload());

  $mainPage->printSourceCodeLink();
  $mainPage->printUploadForm();

  $loadPage->loadFooter();

  class PietUploader {
    function setVars() {
      $this->piet_upload_path = "";

      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        $this->piet_upload_path = "/var/web/term-uploads/";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        $this->piet_upload_path = "./uploads/";
      }
    }

    public function printSourceCodeLink() {
      // I had to manually specify the source URL as the term project being on it's own domain messed up the link - /blob/master/assignments/Term_Project/index.php
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function printUploadForm() {
      print('
      <form method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="piet-image" id="piet-image">
        <input type="submit" value="Upload Image" name="submit">
      </form>
      ');
    }

    public function verifyMySQLVars($metadata) {
        // I could refuse to add this to MySQL if the value is not set. It is not set up this way though.
        if(is_array($metadata)) { // Verifies a File Was Actually Uploaded
          $programid = explode("_", $metadata[0])[1]; // Name looks like "piet_5c92bb736591c", I want "5c92bb736591c" out of it.
          $programname = $this->getValue('program_name');
          $programabout = $this->getValue('program_about');

          $filename = $metadata[1];
          $ipaddress = $_SERVER["REMOTE_ADDR"];

          //$sqlCommands = new sqlCommands(); // I cannot set this unless I want to specify the auth multiple times.
          global $sqlCommands;
          $sqlCommands->insertData($programid, $programname, $filename, $ipaddress, $programabout);
        }
    }

    public function getValue($value) {
      $return_me = '';

      if(isset($_REQUEST[$value]) && $_REQUEST[$value] !== '') {
        $return_me = $_REQUEST[$value];
      } else {
        $return_me = "Not Set";
      }

      return $return_me;
    }

    public function checkUpload() {
      // https://www.w3schools.com/php/php_file_upload.asp
      if(isset($_FILES["piet-image"])) {
        //$id = isset($_REQUEST["id"]) ? (int) $_REQUEST["id"] : NULL; // Single Line If Statement
        $randomid = uniqid('piet_');
        $target_dir = $this->piet_upload_path;
        $target_file = $target_dir . $randomid . ".png";
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Change to Mime Type

        // Check If File Size Is Under 1 MB (1024 KB)
        if($_FILES["piet-image"]["size"] > 1024000) {
          print("<span class=\"error\">Image Cannot Be Over 1 MB (1024 KB)!!!</span><br>");
          return -1;
        }

        // Check If PNG Format
        if($imageFileType != "png") {
          print("<span class=\"error\">Image Has to Be A PNG File!!!</span><br>");
          return -2;
        }

        // Loop Until File Doesn't Exist
        while(file_exists($target_file)) {
          // This could be made more efficient with the original randomid and $target_file
          $randomid = uniqid('piet_');
          $target_file = $target_dir . $randomid . ".png";
        }

        if(move_uploaded_file($_FILES["piet-image"]["tmp_name"], $target_file)) {
          print('<span id="uploaded">Uploaded: ' . $_FILES["piet-image"]["name"] . '!!!</span>');
          return [$randomid, basename($_FILES["piet-image"]["name"])];
        } else {
          print("<span class=\"error\">Failed To Move File To Storage Directory!!!</span><br>");
          return -3;
        }
      }

      return 0;
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 13 - Term Project";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
?>