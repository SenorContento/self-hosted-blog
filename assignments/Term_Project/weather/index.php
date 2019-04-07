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

  $mainPage->printHeader();
  $mainPage->printTabbedContent();

  $mainPage->printTemperature($weather, "dahlonega", 0);
  $mainPage->printImage($weather, "dahlonega", 0);
  $mainPage->printExternalLinks($weather, "dahlonega", 0);

  $loadPage->loadFooter();

  class mainPage {
    public function printHeader() {
      print('<div class="header header-hidden">
        <button id="menu" class="header-hidden fas fa-bars" onclick="openDrawer()"></button>
      </div>');
    }

    public function printTabbedContent() {
      // https://www.w3schools.com/howto/howto_js_tabs.asp
      print('<div id="tab" class="tab tab-hidden">
        <button class="tablinks tab-hidden" onclick="openTab(event, \'weather\'); setTimeout(closeDrawer, 100)" id="defaultOpen">Weather</button>
        <button class="tablinks tab-hidden" onclick="openTab(event, \'camera\'); setTimeout(closeDrawer, 100)">Camera</button>
        <button class="tablinks tab-hidden" onclick="openTab(event, \'externallinks\'); setTimeout(closeDrawer, 100)">Links</button>
      </div><div id="tab-shade" class="tab-shade shade-hidden" onclick="closeDrawer()"></div>');
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

      print("<div id='weather' class='tabcontent tabcontent-visible'>");
      print("<div class='temperature' name='" . $handle . ":" . $station . "'><div class='center'>Temperature High is $tempmax $symbol at $timemax!!!<br>");
      print("Temperature Low is $tempmin $symbol at $timemin!!!</div></div></div>");
    }

    public function printImage($weather, $handle, $camera) {
      print("<div id='camera' class='tabcontent tabcontent-visible'>");
      print("<div class='div-camera'>");
      print("<image name='" . $handle . ":" . $camera . "' class='camera shade' src='" . $weather->getCameraURL($handle, $camera) . "'></img>");
      print("</div></div>");
    }

    public function printExternalLinks($weather, $handle, $station) {
      $wunderground = "https://www.wunderground.com/weather/";
      $twitter = "https://twitter.com/";
      $facebook = "https://www.facebook.com/";

      print("<div id='externallinks' class='tabcontent tabcontent-visible'>");
      print("<div name='" . $handle . ":" . $station . "' class='links'>");
      print("<a class='wunderground button' href='" . $wunderground . $weather->getStationWunderground($handle, $station) . "'><span class='fas fa-cloud'></span> Weather Underground Page</a>");
      print("<a class='twitter button' href='" . $twitter . $weather->getStationTwitter($handle, $station) . "'><span class='fab fa-twitter-square'></span> Twitter Profile</a>");
      print("<a class='facebook button' href='" . $facebook . $weather->getStationFacebook($handle, $station) . "'><span class='fab fa-facebook-square'></span> Facebook Page</a>");
      print("</div></div>");
    }
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Live Weather UNG, Dahlonega!!!";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }
  ?>