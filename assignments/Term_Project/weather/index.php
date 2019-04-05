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

  $json = $mainPage->retrieveJSON();
  $data = $mainPage->parseJSON($json);
  //$mainPage->debugJSON($json);

  $mainPage->printTemperature($data, "dahlonega", 0);
  //$mainPage->printImage($data, "dahlonega", 0);
  //$mainPage->printExternalLinks($data, "dahlonega", 0);

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

    public function printTemperature($data, $handle, $station) {
      $property = $data[$handle]["record"][$station]["hilo"]["property"];
      $name = $data[$handle]["record"][$station]["hilo"]["name"];
      $type = $data[$handle]["record"][$station]["hilo"]["type"];

      $tempmax = $data[$handle]["record"][$station]["hilo"]["temp"]["max"];
      $tempmin = $data[$handle]["record"][$station]["hilo"]["temp"]["min"];

      $timemax = $data[$handle]["record"][$station]["hilo"]["time"]["max"];
      $timemin = $data[$handle]["record"][$station]["hilo"]["time"]["min"];

      $unit = $data[$handle]["record"][$station]["hilo"]["unit"]; // Fahrenheit is misspelled as Farenheight!!!
      $symbol = $data[$handle]["record"][$station]["hilo"]["symbol"];

      print("<b>Temperature High is $tempmax $symbol at $timemax!!!<br>");
      print("Temperature Low is $tempmin $symbol at $timemin!!!</b>");
    }

    public function printImage($data, $handle, $camera) {
      // For The Sake of Consistency (With The Other Functions) I Am Handling One Camera At A Time
      /*foreach($cameras as $camera) {
        print("<image width='1000px' src='" . $data[$handle]["camera"][$camera]["url"] . "'></img>");
      }*/

      print("<image width='1000px' src='" . $data[$handle]["camera"][$camera]["url"] . "'></img>");
    }

    public function printExternalLinks($data, $handle, $station) {
      $wunderground = "https://www.wunderground.com/weather/";
      $twitter = "https://twitter.com/";
      $facebook = "https://www.facebook.com/";

      print("<a href='" . $wunderground . $data[$handle]["station"][$station]["wunderground"] . "'>Wunderground</a>");
      print("<a href='" . $twitter . $data[$handle]["station"][$station]["twitter"] . "'>Twitter</a>");
      print("<a href='" . $facebook . $data[$handle]["station"][$station]["facebook"] . "'>Facebook</a>");
    }

    public function parseJSON($json) {
      $stations = json_decode($json);
      $stations_array = [];

      $station_count = 0;
      foreach($stations as $station) {
        $handle = $station->station->handle;

        // Station
        $stations_array[$handle]["station"][$station_count]["name"] = $station->station->name;
        $stations_array[$handle]["station"][$station_count]["handle"] = $station->station->handle;
        $stations_array[$handle]["station"][$station_count]["domain"]["name"] = $station->station->domain->name;
        $stations_array[$handle]["station"][$station_count]["domain"]["handle"] = $station->station->domain->handle;

        $stations_array[$handle]["station"][$station_count]["wunderground"] = $station->station->wunderground; // https://www.wunderground.com/weather/KGADAHLO40
        $stations_array[$handle]["station"][$station_count]["twitter"] = $station->station->twitter; // https://twitter.com/UNGDWxSTEM
        $stations_array[$handle]["station"][$station_count]["facebook"] = $station->station->facebook; // https://www.facebook.com/1881141655466992

        $stations_array[$handle]["station"][$station_count]["latitude"] = $station->station->lat;
        $stations_array[$handle]["station"][$station_count]["longitude"] = $station->station->lon;

        // Record
        $stations_array[$handle]["record"][$station_count]["now"] = $station->record->now;
        $stations_array[$handle]["record"][$station_count]["derived"] = $station->record->derived;
        $stations_array[$handle]["record"][$station_count]["id"] = $station->record->id;

        $stations_array[$handle]["record"][$station_count]["raintime"] = $station->record->last_rain_time;
        $stations_array[$handle]["record"][$station_count]["time"] = $station->record->time;

        $stations_array[$handle]["record"][$station_count]["hilo"]["property"] = $station->record->hilo->property;
        $stations_array[$handle]["record"][$station_count]["hilo"]["name"] = $station->record->hilo->name;
        $stations_array[$handle]["record"][$station_count]["hilo"]["type"] = $station->record->hilo->type;

        $stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["max"] = $station->record->hilo->max;
        $stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["min"] = $station->record->hilo->min;

        $stations_array[$handle]["record"][$station_count]["hilo"]["time"]["max"] = $station->record->hilo->max_time;
        $stations_array[$handle]["record"][$station_count]["hilo"]["time"]["min"] = $station->record->hilo->min_time;

        $stations_array[$handle]["record"][$station_count]["hilo"]["unit"] = $station->record->hilo->unit;
        $stations_array[$handle]["record"][$station_count]["hilo"]["symbol"] = $station->record->hilo->symbol;

        $camera_count = 0;
        foreach($station->station->cameras as $camera) {
          $stations_array[$handle]["camera"][$camera_count]["name"] = $camera->name;
          $stations_array[$handle]["camera"][$camera_count]["url"] = $camera->image;
          $camera_count = $camera_count + 1;
        }

        $readings_count = 0;
        foreach($station->record->readings as $reading) {
          $stations_array[$handle]["reading"][$readings_count]["symbol"] = $reading->unit_symbol;
          $stations_array[$handle]["reading"][$readings_count]["type"] = $reading->sensor_type;
          $stations_array[$handle]["reading"][$readings_count]["id"] = $reading->id;
          $stations_array[$handle]["reading"][$readings_count]["sensor"] = $reading->sensor;
          $stations_array[$handle]["reading"][$readings_count]["value"] = $reading->value;
          $stations_array[$handle]["reading"][$readings_count]["transmitter"] = $reading->transmitter;
          $stations_array[$handle]["reading"][$readings_count]["unit"] = $reading->unit;
          $readings_count = $readings_count + 1;
        }

        $station_count = $station_count + 1;
      }

      //print_r($stations_array);
      return $stations_array;
    }

    public function debugJSON($json) {
      $stations = json_decode($json);
      //print_r($response);

      foreach($stations as $station) {
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
                   'user_agent' => getenv('alex.server.user_agent'),
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