<?php
  if(!defined('INCLUDED')) {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
    include($root . "/errors/404/index.php");
    die();
  }

  class Weather {
    public $weather_url;
    public $api_key;
    public $stations;

    public $stations_array = [];

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

    // Weather Data

    /*private function setStationName($handle, $station_count, $station) {
      // Should I Create A Setter???

      $this->stations_array[$handle]["station"][$station_count]["name"] = $station->station->name;
    }*/

    // Stations
    // Station (f.e. Dahlonega)

    public function getStationName($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["name"];
    }

    public function getStationHandle($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["handle"];
    }

    // Domain (Root, f.e. Lumpkin County)

    public function getStationDomainName($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["domain"]["name"];
    }

    public function getStationDomainHandle($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["domain"]["handle"];
    }

    // External Links

    public function getStationWunderground($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["wunderground"];
    }

    public function getStationTwitter($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["twitter"];
    }

    public function getStationFacebook($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["facebook"];
    }

    // End External Links

    public function getStationLatitude($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["latitude"];
    }

    public function getStationLongitude($handle, $station_count) {
      return $this->stations_array[$handle]["station"][$station_count]["longitude"];
    }

    // End Stations

    // Records

    public function getRecordNow($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["now"];
    }

    public function getRecordDerived($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["derived"];
    }

    public function getRecordID($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["id"];
    }

    public function getRecordRaintime($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["raintime"];
    }

    public function getRecordTime($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["time"];
    }

    // Hilo (High Low Temperatures)

    public function getRecordProperty($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["property"];
    }

    public function getRecordName($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["name"];
    }

    public function getRecordType($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["type"];
    }

    // Temperature

    public function getTempMax($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["max"];
    }

    public function getTempMaxTime($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["time"]["max"];
    }

    public function getTempMin($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["min"];
    }

    public function getTempMinTime($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["time"]["min"];
    }

    public function getTempUnit($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["unit"];
    }

    public function getTempSymbol($handle, $station_count) {
      return $this->stations_array[$handle]["record"][$station_count]["hilo"]["symbol"];
    }

    // End Temperature
    // End Hilo (High Low Temperatures)

    // Camera

    public function getCameraCount($handle) {
      $count = 0;
      foreach($this->stations_array[$handle]["camera"] as $camera) {
        $count = $count + 1;
      }

      return $count;
    }

    public function getCameraName($handle, $camera_count) {
      return $this->stations_array[$handle]["camera"][$camera_count]["name"];
    }

    public function getCameraURL($handle, $camera_count) {
      return $this->stations_array[$handle]["camera"][$camera_count]["url"];
    }

    // End Camera
    // Readings

    public function getReadingCount($handle) {
      $count = 0;
      foreach($this->stations_array[$handle]["reading"] as $record) {
        $count = $count + 1;
      }

      return $count;
    }

    public function getReadingSymbol($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["symbol"];
    }

    public function getReadingType($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["type"];
    }

    public function getReadingID($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["id"];
    }

    public function getReadingSensor($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["sensor"];
    }

    public function getReadingValue($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["value"];
    }

    public function getReadingTransmitter($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["transmitter"];
    }

    public function getReadingUnit($handle, $readings_count) {
      return $this->stations_array[$handle]["reading"][$readings_count]["unit"];
    }

    // End Readings

    // End Records
    // End Weather Data

    public function parseJSON($json) {
      $stations = json_decode($json);
      //$stations_array = [];

      $station_count = 0;
      foreach($stations as $station) {
        $handle = $station->station->handle;

        // Station
        $this->stations_array[$handle]["station"][$station_count]["name"] = $station->station->name;
        $this->stations_array[$handle]["station"][$station_count]["handle"] = $station->station->handle;
        $this->stations_array[$handle]["station"][$station_count]["domain"]["name"] = $station->station->domain->name;
        $this->stations_array[$handle]["station"][$station_count]["domain"]["handle"] = $station->station->domain->handle;

        $this->stations_array[$handle]["station"][$station_count]["wunderground"] = $station->station->wunderground; // https://www.wunderground.com/weather/KGADAHLO40
        $this->stations_array[$handle]["station"][$station_count]["twitter"] = $station->station->twitter; // https://twitter.com/UNGDWxSTEM
        $this->stations_array[$handle]["station"][$station_count]["facebook"] = $station->station->facebook; // https://www.facebook.com/1881141655466992

        $this->stations_array[$handle]["station"][$station_count]["latitude"] = $station->station->lat;
        $this->stations_array[$handle]["station"][$station_count]["longitude"] = $station->station->lon;

        // Record
        $this->stations_array[$handle]["record"][$station_count]["now"] = $station->record->now;
        $this->stations_array[$handle]["record"][$station_count]["derived"] = $station->record->derived;
        $this->stations_array[$handle]["record"][$station_count]["id"] = $station->record->id;

        $this->stations_array[$handle]["record"][$station_count]["raintime"] = $station->record->last_rain_time;
        $this->stations_array[$handle]["record"][$station_count]["time"] = $station->record->time;

        $this->stations_array[$handle]["record"][$station_count]["hilo"]["property"] = $station->record->hilo->property;
        $this->stations_array[$handle]["record"][$station_count]["hilo"]["name"] = $station->record->hilo->name;
        $this->stations_array[$handle]["record"][$station_count]["hilo"]["type"] = $station->record->hilo->type;

        $this->stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["max"] = $station->record->hilo->max;
        $this->stations_array[$handle]["record"][$station_count]["hilo"]["temp"]["min"] = $station->record->hilo->min;

        $this->stations_array[$handle]["record"][$station_count]["hilo"]["time"]["max"] = $station->record->hilo->max_time;
        $this->stations_array[$handle]["record"][$station_count]["hilo"]["time"]["min"] = $station->record->hilo->min_time;

        $this->stations_array[$handle]["record"][$station_count]["hilo"]["unit"] = $station->record->hilo->unit;
        $this->stations_array[$handle]["record"][$station_count]["hilo"]["symbol"] = $station->record->hilo->symbol;

        $camera_count = 0;
        foreach($station->station->cameras as $camera) {
          $this->stations_array[$handle]["camera"][$camera_count]["name"] = $camera->name;
          $this->stations_array[$handle]["camera"][$camera_count]["url"] = $camera->image;
          $camera_count = $camera_count + 1;
        }

        $readings_count = 0;
        foreach($station->record->readings as $reading) {
          $this->stations_array[$handle]["reading"][$readings_count]["symbol"] = $reading->unit_symbol;
          $this->stations_array[$handle]["reading"][$readings_count]["type"] = $reading->sensor_type;
          $this->stations_array[$handle]["reading"][$readings_count]["id"] = $reading->id;
          $this->stations_array[$handle]["reading"][$readings_count]["sensor"] = $reading->sensor;
          $this->stations_array[$handle]["reading"][$readings_count]["value"] = $reading->value;
          $this->stations_array[$handle]["reading"][$readings_count]["transmitter"] = $reading->transmitter;
          $this->stations_array[$handle]["reading"][$readings_count]["unit"] = $reading->unit;
          $readings_count = $readings_count + 1;
        }

        $station_count = $station_count + 1;
      }

      //print_r($this->stations_array);
      return $this->stations_array;
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
?>