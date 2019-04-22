<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="template.css">');
    //print("\n\t\t" . '<script src="template.js"></script>');
  }

  function customPageFooter() {
    print("\n\t\t" . '<link rel="stylesheet" href="tor.css">');
    //print("\n\t\t" . '<script src="delayed.js"></script>');
  }

  function customMetadata() {
    print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Access Hidden Service!!!">');
    print("\n\t\t" . '<meta name="keywords" content="Tor,Hidden Service">');
    print("\n\t\t" . '<meta name="robots" content="noindex, nofollow">');
    print("\n\t\t" . '<!--End Custom Metadata-->');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();
  //$mainPage->printSourceCodeLink();
  $mainPage->printTutorial();
  $loadPage->loadFooter();

  class mainPage {
    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function printTutorial() {
      // web.senorcontento.com - http://phbqryhvxheioups.onion
      // term.senorcontento.com - http://3whx4oukqxh7yf22.onion

      // Custom Cert - https://tor.stackexchange.com/a/13460/14086
      print("<div class='center'><p class='left'>These are the Hidden Services that are Hosted on Tor!!!<br>In order to access the encrypted site, you will want to add my Root CA to your Tor Browser Bundle!!!</p></div>");

      print("<h3>Adding Root CA's</h3>");
      print("<ol>
            <li>Download this <a href='CubeTechLLC.pem'>certificate</a>.</li>
            <li>In your Tor Browser, go to <a href='about:config'>about:config</a>.</li>
            <li>Then search for security.nocertdb and double click it to turn it off.</li>
            <li>Restart Tor Browser Bundle Completely.</li>
            <li>Then go to <a href='about:preferences'>about:preferences</a> and search for View Certificates.</li>
            <li>Click on the Authorities Tab.</li>
            <li>Click Import and then Click on the Downloaded Certificate.</li>
            <li>Click 'Trust this CA to identify websites.' and then Click OK.</li>
            <li>Restart Tor Browser Bundle Again!!!</li>
            <li>You are Finished!!!</li>
            </ol>");

      print("<br>");
      print("<div class='center'><p class='left'>If you are having trouble getting the certificate to work, refer to this <a href='https://tor.stackexchange.com/a/13460/14086'>StackExchange Answer</a>!!!
             <br>If you want to verify the certificate you downloaded hasn't been tampered with,<br> download the <a href='CubeTechLLC.pem.gpg'>GPG signature</a> signed with key <a href='http://pool.sks-keyservers.net/pks/lookup?search=0xC9007161B19BC1513B78F7EF4E1204E51D9AC3EB&op=vindex'>C9007161B19BC1513B78F7EF4E1204E51D9AC3EB</a>!!!
             </p></div>");

      print("<br>");
      print("<a class='button' href='http://phbqryhvxheioups.onion'>Main Site (Unencrypted)</a>");
      print("<a class='button' href='http://3whx4oukqxh7yf22.onion'>Term Project (Unencrypted)</a>");

      print("<br>");
      print("<a class='button' href='https://phbqryhvxheioups.onion'>Main Site (Encrypted)</a>");
      print("<a class='button' href='https://3whx4oukqxh7yf22.onion'>Term Project (Encrypted)</a>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Template PHP Page!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>