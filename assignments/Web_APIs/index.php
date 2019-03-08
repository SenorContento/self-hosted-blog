<?php
  function customPageHeader() {
    print("\n\t\t" . '<link rel="stylesheet" href="assignment11.css">');
    print("\n\t\t" . '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>');
  }

  $loadPage = new loadPage();
  $mainPage = new homeworkAssignmentEleven();

  $loadPage->loadHeader();

  //$mainPage->printSourceCodeLink();
  //$mainPage->printArchiveLink();
  $mainPage->printWarning();

  $mainPage->drawTable();

  $loadPage->loadFooter();

  class homeworkAssignmentEleven {
    public function printArchiveLink() {
      print('<a href="archive" style="text-align: center;display: block">Go to Archived Homework Assignment 11</a>');
      //print('<br>');
    }

    public function printWarning() {
      print('<center><h1>Assignment 11 has not been created yet! Please come back later!</h1></center>');
    }

    public function printSourceCodeLink() {
      print('<a class="source-code-link" href="' . getenv('alex.github.project') . '/tree/' . getenv('alex.github.branch') . $_SERVER['SCRIPT_NAME'] . '">View Source Code</a>');
    }

    public function drawTable() {
      print("
      <script type=\"text/javascript\">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        // Set chart options
        var options = {'title':'How Much Pizza I Ate Last Night',
                       'width':600,
                       'height':500,
                       'colors': ['green', '#0f0f0f', 'red', 'blue', 'purple'],
                       'is3D': true,
                       'backgroundColor': {
                         'fill': 'black',
                         'fillOpacity': 1
                       },
                       fontName: 'OpenSans - Lighter',
                       fontSize: '14',
                       titleTextStyle: {
                         color: '#90EE90',
                         fontName: 'OpenSans - Lighter',
                         fontSize: '22'
                       },
                       legend: {
                         textStyle: {
                           color: 'red',
                           fontName: 'OpenSans - Lighter',
                           fontSize: '14'
                         }
                       }
                     };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id=\"chart_div\"></div>");
    }
    // https://developers.google.com/chart/interactive/docs/quick_start
    // https://jsfiddle.net/csabatoth/v3h9ycd4/2/
    // https://stackoverflow.com/a/22021224/6828099
  }

  class loadPage {
    public function loadHeader() {
      $PageTitle="Assignment 11 - Web APIs";
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/header.php");
    }

    public function loadFooter() {
      $root = isset($_SERVER['PWD']) ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT'];
      include_once($root . "/server_data/footer.php");
    }
  }
?>