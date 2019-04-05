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

  // https://stackoverflow.com/a/2397010/6828099
  define('INCLUDED', 1);
  require_once 'weather.php';

  $loadPage = new loadPage();
  $weather = new weather();
  $mainPage = new mainPage();

  $loadPage->loadHeader();

  $weather->setURL('https://lumpkin.weatherstem.com/api');
  $weather->setAPIKey(getenv("alex.server.api.weatherstem"));
  $weather->setStations(array("dahlonega"));

  $json = $weather->retrieveJSON();
  $data = $weather->parseJSON($json);
  //$weather->debugJSON($json);

  $mainPage->printTemperature($weather, "dahlonega", 0);
  $mainPage->printImage($weather, "dahlonega", 0);
  $mainPage->printExternalLinks($weather, "dahlonega", 0);

  $loadPage->loadFooter();

  class mainPage {
    public function printTemperature($weather, $handle, $station) {
      $property = $weather->getRecordProperty($handle, $station);
      $name = $weather->getRecordName($handle, $station);
      $type = $weather->getRecordType($handle, $station);

      $tempmax = $weather->getTempMax($handle, $station);
      $tempmin = $weather->getTempMin($handle, $station);

      $timemax = $weather->getTempMaxTime($handle, $station);
      $timemin = $weather->getTempMinTime($handle, $station);

      $unit = $weather->getTempUnit($handle, $station); // Fahrenheit is misspelled as Farenheight!!!
      $symbol = $weather->getTempSymbol($handle, $station);

      print("<b>Temperature High is $tempmax $symbol at $timemax!!!<br>");
      print("Temperature Low is $tempmin $symbol at $timemin!!!</b>");
    }

    public function printImage($weather, $handle, $camera) {
      // For The Sake of Consistency (With The Other Functions) I Am Handling One Camera At A Time
      /*foreach($cameras as $camera) {
        print("<image width='1000px' src='" . $data[$handle]["camera"][$camera]["url"] . "'></img>");
      }*/

      print("<image width='1000px' src='" . $weather->getCameraURL($handle, $camera) . "'></img>");
    }

    public function printExternalLinks($weather, $handle, $station) {
      $wunderground = "https://www.wunderground.com/weather/";
      $twitter = "https://twitter.com/";
      $facebook = "https://www.facebook.com/";

      print("<a href='" . $wunderground . $weather->getStationWunderground($handle, $station) . "'>Wunderground</a>");
      print("<a href='" . $twitter . $weather->getStationTwitter($handle, $station) . "'>Twitter</a>");
      print("<a href='" . $facebook . $weather->getStationFacebook($handle, $station) . "'>Facebook</a>");
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