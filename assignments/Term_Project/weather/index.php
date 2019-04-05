<?php
  function customPageHeader() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  function customPageFooter() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  function customMetadata() {
    //print("\n\t\t" . '<link rel="stylesheet" href="shell.css">');
    //print("\n\t\t" . '<script src="shell.js"></script>');
    //print("\n\t\t" . '<script src="stylize.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new mainPage();

  $loadPage->loadHeader();

  $mainPage->setURL('https://lumpkin.weatherstem.com/api');
  $mainPage->setAPIKey(getenv("alex.server.api.weatherstem"));
  $mainPage->setStations(array("dahlonega"));

  $mainPage->parseJSON($mainPage->retrieveJSON());

  $loadPage->loadFooter();

  class mainPage {
    public $weather_url;
    public $api_key;
    public $stations;

    public function setURL($weather_url) {
      $this->weather_url = $weather_url;
    }

    public function getURL() {
      return $this->weather_url;
    }

    public function setAPIKey($api_key) {
      $this->api_key = $api_key;
    }

    public function getAPIKey() {
      return $this->api_key;
    }

    public function setStations($stations) {
      $this->stations = $stations;
    }

    public function getStations() {
      return $this->stations;
    }

    public function parseJSON($json) {
      $response = json_decode($json);
      //print_r($response);

      foreach($response as $station) {
        // Read stdClass Object
        // https://stackoverflow.com/a/8510937/6828099

        //print_r($station . "<br>");

        // Station
        print("Name: " . $station->station->name . "<br>");
        print("Handle: " . $station->station->handle . "<br>");
        print("Wunderground: " . $station->station->wunderground . "<br>");
        print("Twitter: " . $station->station->twitter . "<br>");

        print("Latitude: " . $station->station->lat . "<br>");
        print("Longitude: " . $station->station->lon . "<br>");
        print("Facebook Page ID: " . $station->station->facebook . "<br>");

        print("Domain Handle: " . $station->station->domain->handle . "<br>");
        print("Domain Name: " . $station->station->domain->name . "<br>");

        foreach($station->station->cameras as $camera) {
          print("Camera Name: " . $camera->name . "<br>");
          print("Camera Image URL: " . $camera->image . "<br>");
        }

        // Record
        print("Retrieved: " . $station->record->now . "<br>");
        print("Derived: " . $station->record->derived . "<br>"); // What is this?
        print("ID: " . $station->record->id . "<br>");
        print("Last Rain Time: " . $station->record->last_rain_time . "<br>");
        print("Time: " . $station->record->time . "<br>");

        // Hilo (High Temp and Low Temp)
        print("Property: " . $station->record->hilo->property . "<br>");
        print("Name: " . $station->record->hilo->name . "<br>");
        print("Type: " . $station->record->hilo->type . "<br>");

        print("Maximum Temperature: " . $station->record->hilo->max . "<br>");
        print("Maximum Time: " . $station->record->hilo->max_time . "<br>"); // High Temp Time?

        print("Minimum Temperature: " . $station->record->hilo->min . "<br>");
        print("Minimum Time: " . $station->record->hilo->min_time . "<br>"); // Low Temp Time?

        print("Measurement Unit: " . $station->record->hilo->unit . "<br>");
        print("Unit Symbol: " . $station->record->hilo->symbol . "<br>");

        foreach($station->record->readings as $reading) {
          print("Reading Unit Symbol: " . $reading->unit_symbol . "<br>");
          print("Reading Sensor Type: " . $reading->sensor_type . "<br>");
          print("Reading ID: " . $reading->id . "<br>");
          print("Reading Sensor: " . $reading->sensor . "<br>");
          print("Reading Value: " . $reading->value . "<br>");
          print("Reading Transmitter: " . $reading->transmitter . "<br>");
          print("Reading Unit: " . $reading->unit . "<br>");
        }
      }
    }

    public function retrieveJSON() {
      $url = $this->getURL();
      $vars = array(
      	"stations" => $this->getStations(),
      	"api_key"  => $this->getAPIKey()
      );

      $options = array(
      	'http' => array(
      	           'method'   => 'POST',
      	           'content'  => json_encode($vars),
      	           'header'   => "Content-Type: application/json\r\n" . "Accept: application/json\r\n"
      		        )
      );

      $context  = stream_context_create($options);
      return file_get_contents($url, false, $context);
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Piet Search Engine!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>