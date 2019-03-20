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
  $mainPage->checkUpload();
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

    public function checkUpload() {
      // https://www.w3schools.com/php/php_file_upload.asp
      if(isset($_FILES["piet-image"])) {
        $id = isset($_REQUEST["id"]) ? (int) $_REQUEST["id"] : NULL; // Single Line If Statement
        $target_dir = $this->piet_upload_path;
        $target_file = $target_dir . basename($_FILES["piet-image"]["name"]); // Change to Randomly Generated UUID
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Change to Mime Type

        if(file_exists($target_file)) {
          print("<span class=\"error\">File Already Exists!!!</span><br>");
          return -1;
        }

        ($_FILES["piet-image"]["size"] > 500000); // 500 Kb
        ($imageFileType != "png"); // If Not PNG

        print('<span id="uploaded">Uploaded: ' . $_FILES["piet-image"]["name"] . '!!!</span>');

        move_uploaded_file($_FILES["piet-image"]["tmp_name"], $target_file);
      }
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