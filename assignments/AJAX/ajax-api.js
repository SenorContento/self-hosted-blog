// https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/javascripts/github/ajax-api.js

function table(json) {
  $(document).ready(function() {
    $('.index-table').remove();
    $('.item-table').remove();
    jQuery.each(json, function(index, item) {
        if(item instanceof Object) {
          recurseTable(index, item);
        } else {
          $('#ajax-table-body').append("<tr><td class=\"index-table\" style=\"text-align: left\">" + index + "</td>" +
          "<td class=\"item-table\" style=\"text-align: left\">" + item + "</td></tr>");
        }
    });
  });
}

//Document Necessary Functions

$(document).ready(function() {
  $('#submit').click(function() {
    var rawJSON = lookup();
    raw(rawJSON);
    table($.parseJSON(rawJSON));
  });
});

function raw(json) {
  $(document).ready(function() {
    $("#ajax-output-debug").text(json);
  });
}

function recurseTable(key, value) {
  $(document).ready(function() {
    if(value instanceof Object) {
      $.each(value, function(k, v) {
        if(v instanceof Object) {
          recurseTable(k, v);
        } else {
          $('#ajax-table-body').append("<tr><td class=\"index-table\" style=\"text-align: left\">" + key + " --> " + k + "</td>" +
          "<td class=\"item-table\" style=\"text-align: left\">" + v + "</td></tr>");
        }
      });
    }
  });
}

function lookup() {
  //var url = "https://localhost/assignments/AJAX/hotbits.php";
  var data = {"retrieve": true, "id": 1}; //{retrieve: true, id: 1}; also works here, but not in JSON.parse(...);

  var url = $("#url").val(); // https://localhost/assignments/AJAX/hotbits.php
  var data = JSON.parse($("#data").val());

  var method = "POST";

  //alert("URL: \"" + url + "\" Data: \"" + data + "\"");

  var returnvalue;
  $.ajax({
    url: url,
    type: method,
    async: false,
    data: data,
    beforeSend: function(xhr) {
      /* xhr means XMLHttpRequest */
      //xhr.setRequestHeader("Accept", "application/vnd.github.v3+json");
    }, error: function(data, status, thrown) {
      /* data is the exact same thing as data in complete, but with bad error codes
       * status throws out error, just like how status in complete throws out success
       * thrown tells what type of error it is */
      returnvalue = JSON.stringify(data, null, '\\');
    }, success: function(data) {
      //alert("Success!"); //print("Success!");
      //alert(type(data));
      returnvalue = JSON.stringify(data);
    }, complete: function(data, status) {
      /* data is same as data in success, but with error codes and status messages thrown in with it
       * status is the status message without any other data. status is by default a string, not json */
      /* alert(JSON.stringify(data) + " | " + status); */
    }
  });
  return returnvalue;
}