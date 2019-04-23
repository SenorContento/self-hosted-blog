<?php
  # https://stackoverflow.com/a/21429652/6828099
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

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
  $mainPage->setVars();
  $mainPage->checkTor();
  $mainPage->printTutorial();
  $loadPage->loadFooter();

  class mainPage {
    function setVars() {
      //$this->torapi = "https://" . $_SERVER['SERVER_NAME'] . "/api/tor/index.php"; // This doesn't work on Tor Hidden Service because the Hidden Service is the Domain
      if(getenv('alex.server.type') === "production") {
        # The below variables are for the production server
        //$this->exec_ent_path = "/home/web/programs/ent";
        $this->torapi = getenv('alex.server.host') . "/api/tor/index.php";
      } else if(getenv('alex.server.type') === "development") {
        # The below variables are for testing on localhost
        //$this->exec_ent_path = "/Users/senor/Documents/.Programs/ent";
        $this->torapi = "https://web.senorcontento.com/api/tor/index.php";
      }
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>');
      //print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . "/assignments/Term_Project" . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a><br>'); // For Term Project
    }

    public function checkTor() {
      // https://check.torproject.org/exit-addresses
      // https://check.torproject.org/cgi-bin/TorBulkExitList.py?ip=1.1.1.1
      $ip = $_SERVER['REMOTE_ADDR'];
      $hiddenservice = isset($_SERVER['tor']) ? $_SERVER['tor'] : 'false';

      $exitnode = $this->exitNode($ip);

      print("<span class='tor-exit-node'>Coming From Tor Exit Node: $exitnode</span><br>");
      print("<span class='hidden-service'>Accessing Via Hidden Service: $hiddenservice</span>");
    }

    public function exitNode($ip) {
      $data = array('ip' => "$ip");

      // https://stackoverflow.com/a/6609181/6828099
      $options = array(
        'ssl' => array(
          // I cannot specify a self-signed cert to PHP, so I have to disable verification - https://serverfault.com/a/815795/379269
          'verify_peer' => filter_var(getenv("alex.server.host.verifycert"), FILTER_VALIDATE_BOOLEAN), // Set to false to disable checking certificate
          'verify_peer_name' => filter_var(getenv("alex.server.host.verifycert"), FILTER_VALIDATE_BOOLEAN)
          //'cafile' => '/usr/local/etc/nginx/certs/localhost'
        ),
        'http' => array(
          'header'  => "Content-Type: application/x-www-form-urlencoded\r\n" . "Accept: application/json\r\n",
          'user_agent'  => getenv('alex.server.user_agent'),
          'method'  => 'POST',
          'content' => http_build_query($data)
        )
      );

      //var_dump($data);
      //var_dump(http_build_query($data));

      $context = stream_context_create($options);
      $result = file_get_contents($this->torapi, false, $context); // http://php.net/manual/en/function.file-get-contents.php - string $filename, bool $use_include_path = FALSE, resource $context, ...
      //$result = false;
      //var_dump($result);

      if ($result === FALSE) {
        throw new Exception("Result Returned FALSE!!!");
      }

      if($this->isJson($result)) {
        $json = json_decode($result, true);

        if(isset($json["exitnode"])) {
          return $json["exitnode"];
        }

        return "Exit Node Not Found!!!";
      }

      return "Not JSON!!!";
    }

    // https://stackoverflow.com/a/6041773/6828099
    private function isJson($string) {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    }

    public function printTutorial() {
      // web.senorcontento.com - http://phbqryhvxheioups.onion
      // term.senorcontento.com - http://3whx4oukqxh7yf22.onion

      // Custom Cert - https://tor.stackexchange.com/a/13460/14086
      print("<div class='center'><p class='left'>These are the Hidden Services that are Hosted on Tor!!!<br>In order to access the encrypted site, you will want to add my Root CA to your Tor Browser Bundle!!!</p></div>");

      print("<h3>Adding Root CA's - Tor Browser Bundle</h3>");
      print("<ol>
            <li>Download this <a href='CubeTechLLC.pem'>certificate</a>.</li>
            <li>In your Tor Browser, go to <a href='about:config'>about:config</a>. *</li>
            <li>Then search for security.nocertdb and double click it to turn it off.</li>
            <li>Restart Tor Browser Bundle Completely.</li>
            <li>Then go to <a href='about:preferences'>about:preferences</a> and search for View Certificates. *</li>
            <li>Click on the Authorities Tab.</li>
            <li>Click Import and then Click on the Downloaded Certificate.</li>
            <li>Click 'Trust this CA to identify websites.' and then Click OK.</li>
            <li>Restart Tor Browser Bundle Again!!!</li>
            <li>You are Finished!!!</li>
            </ol>");

      print("<br>");

      print("<h3>Adding Root CA's - Orfox</h3>");
      print("<ol>
            <li>In your Tor Browser, go to <a href='about:config'>about:config</a>. *</li>
            <li>Then search for security.nocertdb and double click it to turn it off.</li>
            <li>Restart Orfox Completely (Force Close).</li>
            <li>Download this <a href='CubeTechLLC.pem'>certificate</a>.</li>
            <li>Click 'Trust this CA to identify websites.' and then Click OK.</li>
            <li>Restart Orfox Again!!!</li>
            <li>You are Finished!!!</li>
            </ol>");

      print("<br>");

      print("<div class='center'><p class='left'>* Your browser most likely will not just let you click the link, so you will have to type it in manually in a new tab.
             </p></div>");

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
      $PageTitle="Tor Hidden Services!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>