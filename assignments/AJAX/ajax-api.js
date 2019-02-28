// https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/javascripts/github/ajax-api.js

function table(json) {
  $(document).ready(function() {
    //$('.index-table').remove();
    //$('.item-table').remove();
    $('.ajax-table-tr').remove();
    jQuery.each(json, function(index, item) {
        if(item instanceof Object) {
          recurseTable(index, item);
        } else {
          $('#ajax-table-body').append("<tr class=\"ajax-table-tr\"><td class=\"index-table\" style=\"text-align: left\">" + index + "</td>" +
          "<td class=\"item-table\" style=\"text-align: left\">" + item + "</td></tr>");
        }
    });
  });
}

$(document).ready(function() {
  $('#submit').click(function() {
    var rawData = lookup();
    if(rawData[1] === "json") {
      $("#response-table").show();
      raw(syntaxHighlight(prettyPrintArray(rawData[0])));
      table($.parseJSON(rawData[0]));
    } else if(rawData[1] === "html") {
      $("#response-table").hide();
      raw(rawData[0]);
    } else if(rawData[1] === "csv") {
      $("#response-table").hide();
      raw(rawData[0]);
    } else if(rawData[1] === "zip") {
      //raw(rawData[0]);

      //alert("Bytes: " + rawData[0].length);
      //alert(typeof(rawData[0])); // object

      // AJAX cannot download binary data (without corrupting it), so I have to encode it to base64 on the server first
      var decoded = atob(rawData[0]); // https://stackoverflow.com/a/2820329/6828099

      array = new Uint8Array(decoded.length);
      for (var i = 0; i < decoded.length; i++){
        array[i] = decoded.charCodeAt(i);
      }

      var blob = new Blob([array], {type: 'application/zip'});
      var url = window.URL.createObjectURL(blob);

      //alert("AJAX URL: " + url);

      this.href = url;
      this.target = '_blank';
      this.download = 'cryptography.zip';
      //window.URL.revokeObjectURL(url);
    } else {
      $("#response-table").hide();
      raw(rawData[0]);
    }

    rawData = undefined;
    delete(rawData);
  });
});

// https://stackoverflow.com/a/641874/6828099
window.onresize = function(event) {
  $(document).ready(function() {
    //$("#ajax-output-debug").css("max-width",(window.innerWidth - 70) + "px");
    // The width detection code doesn't always correctly detect the window size,
    // but a table with width: 100% that always works can be used as a replacement.
    $("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
  });
};

function raw(string) {
  $(document).ready(function() {
    $("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
    $("#ajax-output-debug").html(string); //.text(json);
    //$("#ajax-output-debug").css("max-width",(window - 70) + "px");
  });
}

// https://stackoverflow.com/a/18452766/6828099
// https://stackoverflow.com/a/42381024/6828099
// https://stackoverflow.com/a/54931396/6828099 - My Own Answer
function prettyPrintArray(json) {
  if (typeof json === 'string') {
    json = JSON.parse(json);
  }
  output = JSON.stringify(json, function(k,v) {
    if(v instanceof Array)
      return JSON.stringify(v);
    return v;
  }, 2).replace(/\"\[/g, '[')//.replace(/\\/g, '') // I intentionally removed this so the JSON is still valid in the debugger.
        .replace(/\]\"/g,']')
        .replace(/\"\{/g, '{')
        .replace(/\}\"/g,'}');

  return output;
}

function recurseTable(key, value) {
  $(document).ready(function() {
    if(value instanceof Object) {
      $.each(value, function(k, v) {
        if(v instanceof Object) {
          recurseTable(k, v);
        } else {
          $('#ajax-table-body').append("<tr class=\"ajax-table-tr\"><td class=\"index-table\" style=\"text-align: left\">" + key + " --> " + k + "</td>" +
          "<td class=\"item-table\" style=\"text-align: left\">" + v + "</td></tr>");
        }
      });
    }
  });
}

function lookup() {
  var url = $("#url").val(); // https://localhost/assignments/AJAX/hotbits.php
  var json = JSON.parse($("#data").val());

  //var url = "/api/cryptography";
  //var json = {"download": "base64", "retrieve": true, "id": 99};

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

// https://stackoverflow.com/a/7220510/6828099
function syntaxHighlight(json) {
    if (typeof json != 'string') {
         json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}