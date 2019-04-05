<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="weather.css">');
  }

  function customPageFooter() {
    print("\n\t\t" . '<script src="tabs.js"></script>');
  }

  function customMetadata() {
    //print("\n\t\t" . '<!--Custom Metadata-->');
    print("\n\t\t" . '<meta name="description" content="Live Weather from UNG\'s Weather Equipment!!!">');
    print("\n\t\t" . '<meta name="keywords" content="UNG,Weather,Station,Live">');
    //print("\n\t\t" . '<!--End Custom Metadata-->');
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

  $mainPage->printTabbedContent();

  $mainPage->printTemperature($weather, "dahlonega", 0);
  $mainPage->printImage($weather, "dahlonega", 0);
  $mainPage->printExternalLinks($weather, "dahlonega", 0);

  $loadPage->loadFooter();

  class mainPage {
    public function printTabbedContent() {
      // https://www.w3schools.com/howto/howto_js_tabs.asp
      print('<div class="tab">
        <button class="tablinks" onclick="openTab(event, \'temperature\')" id="defaultOpen">Temperature</button>
        <button class="tablinks" onclick="openTab(event, \'camera\')">Camera</button>
        <button class="tablinks" onclick="openTab(event, \'externallinks\')">Tokyo</button>
      </div>');
    }

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

      print("<div id='temperature' class='tabcontent'>");
      print("<div class='temperature' name='" . $handle . ":" . $station . "'>Temperature High is $tempmax $symbol at $timemax!!!<br>");
      print("Temperature Low is $tempmin $symbol at $timemin!!!</div></div>");
    }

    public function printImage($weather, $handle, $camera) {
      print("<div id='camera' class='tabcontent'>");
      print("<image name='" . $handle . ":" . $camera . "' class='camera' src='" . $weather->getCameraURL($handle, $camera) . "'><div name='" . $handle . ":" . $camera . "' data-info='For Shading The Image'></div></img>");
      print("</div>");
    }

    public function printExternalLinks($weather, $handle, $station) {
      $wunderground = "https://www.wunderground.com/weather/";
      $twitter = "https://twitter.com/";
      $facebook = "https://www.facebook.com/";

      print("<div id='externallinks' class='tabcontent'>");
      print("<div name='" . $handle . ":" . $station . "' class='links'>");
      print("<a class='wunderground button' href='" . $wunderground . $weather->getStationWunderground($handle, $station) . "'>Weather Underground Page</a>");
      print("<a class='twitter button' href='" . $twitter . $weather->getStationTwitter($handle, $station) . "'>Twitter Profile</a>");
      print("<a class='facebook button' href='" . $facebook . $weather->getStationFacebook($handle, $station) . "'>Facebook Page</a>");
      print("</div></div>");
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