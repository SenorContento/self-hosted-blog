// https://developers.google.com/chart/interactive/docs/quick_start
// https://jsfiddle.net/csabatoth/v3h9ycd4/2/
// https://stackoverflow.com/a/22021224/6828099

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

//width = (window.innerWidth/1.5);
//height = (window.innerWidth/2.5);

window.onresize = function(event) {
  $(document).ready(function() {
    drawChart();
  });
};

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
                 //'width': 100,
                 //'height': 200,
                 //colors: ['green', '#0f0f0f', 'red', 'blue', 'purple'],
                 colors: ['green'],
                 is3D: true,
                 backgroundColor: {
                   fill: 'black',
                   fillOpacity: 1
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
                 },

                 vAxis: {
                  textStyle: {
                    color: 'red',
                    fontName: 'Source-Code',
                    fontSize: '14',

                  },
                 },

                 hAxis: {
                  textStyle: {
                    color: 'cyan',
                    fontName: 'Source-Code',
                    fontSize: '14'
                  },
                  gridlines: {
                    color: "purple"
                  },
                  baselineColor: 'red',
                  direction: 1
                 },
               };

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}