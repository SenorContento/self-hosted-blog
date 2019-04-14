// https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/javascripts/github/ajax-api.js

$(document).ready(function() {
  //$("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
  //$("select").css("max-width", $("#highlight").width() + "px");
  resizeCommand();
});

$(document).ready(function() {
  $('#submit').click(function(e) {
    var rawData = lookup();
    if(rawData[1] === "json") {
      $('#search-results').empty();
      //alert(rawData[0]);
      //$('#search-results').html("<div class='warning'>" + rawData[0] + "</div>");
      json = JSON.parse(rawData[0]);

      // https://stackoverflow.com/a/1675233/6828099
      if(typeof json["error"] !== typeof undefined) {
        //alert(json["error"]);
        $('#search-results').html("<div class='error'>" + json["error"] + "</div>");
        return -1;
      }

      json.forEach(processData);
    } else {
      alert("Unknown Format: \n" + rawData[0]);
    }
  });
});

// https://www.w3schools.com/jsref/jsref_foreach.asp
function processData(data, index) {
  // PGP - id, keyid, dateadded, fingerprint
  // Piet - id, programid, dateadded, programname, programabout, filename, checksum, allowed, banreason

  if(typeof data["keyid"] !== typeof undefined) {
    $('#search-results').append('<div class="search-items">Key ID: ' + data["keyid"] + '<br>');
    //$('#search-results').append('</div>');

    $('#search-results').append('</div>');
  } else {
    $('#search-results').append('<div class="search-items">Program ID: ' + data["programid"] + '<br>');
    //$('#search-results').append('</div>');

    $('#search-results').append('</div>');
  }
}

function resizeCommand() {
  //document.getElementById("command").style.width = "816" + "px";
  //alert("Received Width: " + document.getElementById("received").offsetWidth);
  //document.getElementById("command").style.width = (document.getElementById("received").offsetWidth-7) + "px";

  var arrayLength = document.querySelectorAll('[type="text"]').length;
  for (var i = 0; i < arrayLength; i++) {
    //document.getElementsByTagName("input")[i].style.width = (document.getElementById("received").offsetWidth-7) + "px";
    document.querySelectorAll('[type="text"]')[i].style.width = (document.getElementById("sizing-tag").offsetWidth-50) + "px";
    //alert((document.getElementById("search-parameters").offsetWidth-7) + "px");
  }

  //alert(document.getElementById("sizing-tag-search").offsetWidth);
  document.getElementById("search-parameters").style.width = (document.getElementById("sizing-tag-search").offsetWidth-20) + "px";
}

// https://stackoverflow.com/a/641874/6828099
window.onresize = function(event) {
  $(document).ready(function() {
    //$("#ajax-output-debug").css("max-width",(window.innerWidth - 70) + "px");
    // The width detection code doesn't always correctly detect the window size,
    // but a table with width: 100% that always works can be used as a replacement.
    //$("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
    //$("select").css("max-width", $("#highlight").width() + "px");
    //alert("Resize!!!");
    resizeCommand();
  });
};

function lookup() {
  var url = "/api/search";

  // https://www.w3schools.com/jsref/prop_radio_checked.asp
  //document.getElementById("red").checked = true;
  if(document.getElementById("radio-key-id").checked) {
    var json = {"keyid": $("#data").val()};//JSON.parse($("#data").val());
    //var json = {"download": "base64", "retrieve": true, "id": 99};
  } else {
    var json = {"programid": $("#data").val()};//JSON.parse($("#data").val());
    //var json = {"download": "base64", "retrieve": true, "id": 99};
  }

  var method = "POST";

  var returnvalue;
  /*$.ajaxSetup({
    beforeSend: function (jqXHR, settings) {
      //settings.xhr().responseType = 'arraybuffer';
      if (settings.dataType === 'binary') {
        settings.xhr().responseType = 'arraybuffer';
      }
    }
  });*/

  $.ajax({
    url: url,
    type: method,
    //dataType: 'binary', // No Conversion From Text to Binary
    async: false,
    data: json,
    beforeSend: function(xhr) {
      /* xhr means XMLHttpRequest */
      //xhr.setRequestHeader("Accept", "application/vnd.github.v3+json");
    }, error: function(data, status, thrown) {
      /* data is the exact same thing as data in complete, but with bad error codes
       * status throws out error, just like how status in complete throws out success
       * thrown tells what type of error it is */

      alert("Error Bytes: \"" + data.length + "\" Status: \"" + status + "\" Error: \"" + thrown + "\"");
      returnvalue = [JSON.stringify(data, null, 2), "json"];
    }, success: function(data, status, xhr) {
      // https://stackoverflow.com/a/3741604/6828099
      var ct = xhr.getResponseHeader("content-type") || "";
      if (ct.indexOf('html') > -1) {
        returnvalue = [data, "html"];
      } else if (ct.indexOf('json') > -1) {
        returnvalue = [JSON.stringify(data, null, 2), "json"];
      } else if (ct.indexOf('csv') > -1) {
        returnvalue = [data, "csv"];
      } else if(ct.indexOf('zip') > -1) {
        //this.href = "https://localhost" + url + "/api/cryptography?id=99&download=true&retrieve=true";
        //this.target = '_blank';
        //this.download = 'cryptography-temp.zip';

        //alert("Bytes: " + data.length); // {"download": true, "retrieve": true, "id": 99}
        returnvalue = [data, "zip"];
      } else {
        returnvalue = [data, "unknown"];
      }
    }, complete: function(data, status) {
      /* data is same as data in success, but with error codes and status messages thrown in with it
       * status is the status message without any other data. status is by default a string, not json */
      /* alert(JSON.stringify(data) + " | " + status); */
    }
  });
  return returnvalue;
}