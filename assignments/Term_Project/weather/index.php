<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="weather.css">');
  }

  function customPageFooter() {
    print("\n\t\t" . '<script src="tabs.js"></script>');

    print("\n\t\t" . '<link rel="stylesheet" href="maps.css">');
    print("\n\t\t" . '<script src="/js/polymaps/polymaps.min.js"></script>');
    print("\n\t\t" . '<script src="maps.js"></script>');
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

  $mainPage->printWeather($weather, "dahlonega", 0);
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

    public function printWeather($weather, $handle, $station) {
      $property = htmlspecialchars($weather->getRecordProperty($handle, $station), ENT_QUOTES, 'UTF-8');
      $name = htmlspecialchars($weather->getRecordName($handle, $station), ENT_QUOTES, 'UTF-8');
      $type = htmlspecialchars($weather->getRecordType($handle, $station), ENT_QUOTES, 'UTF-8');

      $tempmax = htmlspecialchars($weather->getTempMax($handle, $station), ENT_QUOTES, 'UTF-8');
      $tempmin = htmlspecialchars($weather->getTempMin($handle, $station), ENT_QUOTES, 'UTF-8');

      $timemax = htmlspecialchars($weather->getTempMaxTime($handle, $station), ENT_QUOTES, 'UTF-8');
      $timemin = htmlspecialchars($weather->getTempMinTime($handle, $station), ENT_QUOTES, 'UTF-8');

      $unit = htmlspecialchars($weather->getTempUnit($handle, $station), ENT_QUOTES, 'UTF-8'); // Fahrenheit is misspelled as Farenheight!!!
      $symbol = preg_replace("/&amp;deg;/", "&deg;", htmlspecialchars($weather->getTempSymbol($handle, $station), ENT_QUOTES, 'UTF-8')); // I only want to convert &amp;deg; back to character &deg; // https://www.php.net/manual/en/function.preg-replace.php

      // Temperature
      print("<div id='weather' class='tabcontent tabcontent-visible'>");
      print("<div class='temperature' name='" . htmlspecialchars($handle . ":" . $station, ENT_QUOTES, 'UTF-8') . "'><div class='center'>Temperature High is $tempmax $symbol at $timemax!!!<br>");
      print("Temperature Low is $tempmin $symbol at $timemin!!!</div></div>");

      $latitude = htmlspecialchars($weather->getStationLatitude($handle, $station), ENT_QUOTES, 'UTF-8');
      $longitude = htmlspecialchars($weather->getStationLongitude($handle, $station), ENT_QUOTES, 'UTF-8');

      // Map
      print("<div class='map'><svg class='ung-map' id='ung-map'><p class='coordinates'>$latitude, $longitude</p></svg></div>");

      print("</div>");
    }

    public function printImage($weather, $handle, $camera) {
      print("<div id='camera' class='tabcontent tabcontent-visible'>");
      print("<div class='div-camera'>");
      print("<image name='" . htmlspecialchars($handle . ":" . $camera, ENT_QUOTES, 'UTF-8') . "' class='camera shade' src='" . htmlspecialchars($weather->getCameraURL($handle, $camera), ENT_QUOTES, 'UTF-8') . "'></img>");
      print("</div></div>");
    }

    public function printExternalLinks($weather, $handle, $station) {
      $wunderground = "https://www.wunderground.com/weather/";
      $twitter = "https://twitter.com/";
      $facebook = "https://www.facebook.com/";
      $weatherstem = "https://lumpkin.weatherstem.com/dahlonega";

      print("<div id='externallinks' class='tabcontent tabcontent-visible'>");
      print("<div name='" . htmlspecialchars($handle . ":" . $station, ENT_QUOTES, 'UTF-8') . "' class='links'>");
      print("<a class='weatherstem button' href='" . htmlspecialchars($weatherstem, ENT_QUOTES, 'UTF-8') . "'><span class='fas fa-thermometer-three-quarters'></span> WeatherStem Page</a>");
      print("<a class='wunderground button' href='" . htmlspecialchars($wunderground . $weather->getStationWunderground($handle, $station), ENT_QUOTES, 'UTF-8') . "'><span class='fas fa-cloud'></span> Weather Underground Page</a>");
      print("<a class='twitter button' href='" . htmlspecialchars($twitter . $weather->getStationTwitter($handle, $station), ENT_QUOTES, 'UTF-8') . "'><span class='fab fa-twitter-square'></span> Twitter Profile</a>");
      print("<a class='facebook button' href='" . htmlspecialchars($facebook . $weather->getStationFacebook($handle, $station), ENT_QUOTES, 'UTF-8') . "'><span class='fab fa-facebook-square'></span> Facebook Page</a>");
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