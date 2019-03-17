<?php
  function customPageHeader() {
    // The function has to be left outside the classes in order to get loaded by the header.php file.
    //print('<!--This is here to bookmark how to load a custom page header!!!-->');
  }

  $loadPage = new loadPage();
  $getServerVars = new apisIndex();

  $loadPage->loadHeader();
  $getServerVars->printAPIs();
  $loadPage->loadFooter();

  class loadPage {
    public function loadHeader() {
      $PageTitle="APIs Index";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server-data/footer.php");
    }
  }

  class apisIndex {
    public function printAPIs() {
      print('<h1>Databases and APIs</h1>');

      print("<ul>");

      print('<li><a href="https://www.reddit.com/r/webdev/comments/3wrswc/what_are_some_fun_apis_to_play_with/">Reddit</a> has a list of some fun APIs to play around with!</li>' .
            '<li><a href="https://www.fourmilab.ch/hotbits/secure_generate.html">Hotbits by Fourmilab</a> allows obtaining data from a geiger counter. You now need an API key to get truly random data, but you can test the API with psuedo-random data.</li>' .
            '<li><a href="https://deckofcardsapi.com/">Deck of Cards</a> allows generating and pulling from a deck of cards. It even allows for custom decks.</li>' .
            '<li><a href="https://commoncrawl.org/big-picture/">Common Crawl</a> is a project that collects petabytes of data for the public to analyse for free</li>' .
            '<!-- The Scientific/Research Papers link was intentionally left blank as I have to organize this list better to accommodate the large amount of sites available to find research from -->' .
            '<li><a href="#">Scientific/Research Papers</a> such as <a href="https://www.ee.columbia.edu/~dpwe/papers/Wang03-shazam.pdf">How Shazam\'s Algorithm Works</a> are really awesome to go through. An API can be written to find papers through parsing the papers.</li>' .
            '<li><a href="https://data.gov/">Data.gov</a> provides a ton of data for you to parse through. At the time of posting (January 18th, 2019), the site is down due to government shutdown.</li>' .
            '<li><a href="https://datahub.io/collections">DataHub.io</a> has tons of aggregated data. It has a freemium model, but I did find interesting data like the education budget and history plus a link to the original source inside the zip.</li>' .
            '<li><a href="http://okfnlabs.org/projects/">Open Knowledge Labs</a> provides different projects with various types of content. It also teachers how to properly organize and analyze data.</li>' .
            '<li><a href="https://www.ecstasydata.org/">Ecstatsy Data</a> has a database of various drugs from the street that they have tested.</li>' .
            '<li><a href="https://www.accessdata.fda.gov/scripts/cder/daf/index.cfm">FDA\'s Federal Database of Drugs and Patents for Drugs</a></li>' .
            '<li><a href="http://patft.uspto.gov/netahtml/PTO/search-bool.html">TPO Office - PATFT (Old)</a> and <a href="http://appft.uspto.gov/netahtml/PTO/search-bool.html">TPO Office - APPTFT (New)</a> for U.S. Patents and <a href="http://tmsearch.uspto.gov/bin/gate.exe?f=tess&state=4808:zp1z5d.1.1">TPO - TESS</a> for U.S. Trademarks</li>' .
            '<li><a href="https://www.drugs.com/search.php">Drugs.com Effects Database</a> and <a href="https://www.drugs.com/imprints.php">Drugs.com Pill Imprint Database</a>.' .
            '<li><a href="https://api.psychonautwiki.org/">API for Psychonaut.wiki</a>. There is no documentation that I found, but it is in JSON.</li>' .
            '<li><a href="https://cds.cern.ch/">Cern Document Server</a> and <a href="http://library.cern/resources/online_databases">Cern Library</a>.' .
            '<li><a href="https://www.discogs.com/about">Discogs</a> is a crowdsourced database of music. For legal reasons they only link to music, but the metadata itself is there.</li>' .
            '<li><a href="https://musicbrainz.org/doc/MusicBrainz_Database">MusicBrainz</a> is another crowdsourced, music metadata site.</li>');

      print('</ul>');

      print('<h3>Notice how most of these links don\'t point to APIs directly. That is because I haven\'t searched for the API for every one of these links yet.</h3>');

      print('<p>Some more research organizations and academia are <a href="https://www.elsevier.com/">Elsevier</a>, <a href="https://scholar.google.com/">Google Scholar</a>, <a href="https://www.sciencedirect.com/search/advanced">ScienceDirect</a>, <a href="https://www.ncbi.nlm.nih.gov/pubmed/">Pubmed</a>, <a href="https://ieeexplore.ieee.org/Xplore/guesthome.jsp">IEEE Xplore</a>, <a href="https://agricola.nal.usda.gov/">National Agricultural Library (AGRICOLA)</a>, and <a href="https://eric.ed.gov/">Education Resources Information Center (ERIC)</a>.</p>');

      print('<p>There are also published government codes at <a href="http://uscode.house.gov/">house.gov</a> and <a href="https://www.govinfo.gov/">GPO Office (U.S. Federal Codes)</a>. Chances are likely that your state has online codes. <a href="https://dps.georgia.gov/lexisnexis">Georgia</a> likes to keep theirs on lexisnexis. Also check your county and city too. Also, check out <a href="https://www.congress.gov/">Congress.gov</a> as they are constantly publishing new codes.</p>');
    }
  }
?>