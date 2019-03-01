// https://raw.githubusercontent.com/bgbrandongomez/blog/master/sitedata/javascripts/github/ajax-api.js

$(document).ready(function() {
  //$("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
  $("select").css("max-width", $("#ajax-table").width() + "px");
});

// https://stackoverflow.com/a/15009561
// https://stackoverflow.com/a/10198447
$(document).ready(function() {
  $('#build-api-request').click(function(e) {
    //var json = $("#option-request-type").serializeArray();
    //alert("JSON: " + JSON.stringify(json));
    //delete(json);
    var json = {};

    if(!$('#bytes').is('[disabled]')) {
      json["bytes"] = parseInt($('#bytes').val()); // int
      //alert(json["bytes"]);
    }

    if(!$('#rowID').is('[disabled]')) {
      json["id"] = parseInt($('#rowID').val()); // int
      //alert(json["id"]);
    }

    $("#option-request-type option").each(function() {
      if(!$(this).is('[disabled]') && $(this).val() !== "bytes") {
        //alert($(this).val());
        json[$(this).val()] = true; // bool
        //alert(json[$(this).val()]);
      }
    });

    $("#option-generator-type option").each(function() {
      if(!$(this).is('[disabled]') && !$("#option-generator-type").is('[disabled]') && $(this).is(':selected')) {
        //alert($(this).val());
        json["generator"] = $(this).val(); // string
        //alert(json[$(this).val()]);
      }
    });

    $("#option-format-type option").each(function() {
      if(!$(this).is('[disabled]') && !$("#option-format-type").is('[disabled]') && $(this).is(':selected')) {
        //alert($(this).val());
        json["format"] = $(this).val(); // string
        //alert(json[$(this).val()]);
      }
    });

    // {"id":"1","retrieve":true,"generator":"pseudorandom","format":"csv","download":"base64"}
    $("#option-download-type option").each(function() {
      if(!$(this).is('[disabled]') && !$("#option-download-type").is('[disabled]') && $(this).is(':selected')) {
        //alert($(this).val());
        json["download"] = $(this).val(); // string
        //alert(json[$(this).val()]);
      }
    });

    $("#option-count option").each(function() {
      if(!$(this).is('[disabled]') && !$("#option-count").is('[disabled]') && $(this).is(':selected')) {
        //alert($(this).val());
        json["count"] = ($(this).val() === 'true'); // string
        //alert(json[$(this).val()]);
      }
    });

    //alert("JSON: " + JSON.stringify(json));
    $("#data").val(JSON.stringify(json));
    json = {};
  });
});

function populateForm(option) {
  var hotbits_url = "/api/hotbits"; // $("#url").val(hotbits_url);
  var cryptography_url = "/api/cryptography"; // $("#url").val(cryptography_url);
  // This is to read the user controls and populate the form based on the controls

  /* API Methods (POST) - Hotbits and Cryptography
   *
   * 1 - bytes(int) and generator(string)
   * 2 - retrieve(bool) and id(int) and [Cryptography Only] download(string)
   * 3 - analyze(bool) and id(int) and [Hotbits Only] format(csv)
   * 4 - analyze(bool) and id(int) and count(bool) and [Hotbits Only] format(csv)
   */

   /* Responses - Hotbits
    *
    * 1 - (JSON) New Data Straight from Hotbits (Do Both Random and Pseudorandom)
    * 2 - (JSON) Old Data Already In MySQL Database (Do Both Random and Pseudorandom)
    * 3 - (JSON) Analyze Data From MySQL Database - format (CSV)
    * 4 - (JSON) Analyze Data From MySQL Database (and Provide Byte Counts) - format (CSV or JSON)
    */

    /* Responses - Cryptography
     *
     * 1 - (JSON) New Data - Encrypt and Decrypt Test File
     * 2 - (JSON) Old Data - Encrypt and Decrypt Test File - download (base64 and zip)
     * 3 - (HTML) Analyze Data From MySQL Database
     * 4 - (HTML) Analyze Data From MySQL Database (and Provide Byte Counts)
     */

  //option-user-controls, url, and data

  /* user-controls - option-user-controls
  <option value="grab-new-data">(JSON) Retrieve New Data (Bytes and Generator)</option>
  <option value="retrieve-existing-data">(JSON) Retrieve Existing Data (ID)</option>
  <option value="analyze-count-html">(HTML) Analyze Existing Data With Byte Count (ID)</option>
  <option value="encryption-new-data">(ZIP Archive) New Encrypt/Decrypt Test File (Bytes and Generator)</option>
  <option value="encryption-existing-data">(ZIP Archive) Existing Encrypt/Decrypt Test File (ID)</option>
  <option value="free-form">Free Form (Anything Goes)</option>
  */

  // grab-new-data, retrieve-existing-data, analyze-count-html, encryption-new-data, encryption-existing-data
  //alert("Option: " + option);

  //IDEA: It's not necessary to do these if statements
  if(option !== "free-form") {
    $("#option-download-type option[value='zip']").prop('disabled', 'disabled');
    $("#option-download-type option[value='base64']").prop('selected', 'true');
  }

  if(option !== "analyze-count-html" && option !== "free-form") {
    $("#option-format-type").prop('disabled', 'disabled');
    $("#option-count").prop('disabled', 'disabled');
  }

  if(option !== "encryption-new-data" && option !== "encryption-existing-data" && option !== "free-form") {
    $("#option-download-type").prop('disabled', 'disabled');
  }

  if(option === "grab-new-data") {
    $("#url").val(hotbits_url);
    //alert("1");
    // https://stackoverflow.com/a/1888946 - Use Prop on New JQuery
    $("#option-request-type option").each(function() {
      if($(this).text() === "Bytes") {
        //this.removeAttribute("disabled");
        $(this).removeAttr("disabled");
        $(this).prop('selected', 'selected');
      }
      if($(this).text() === "Retrieve" || $(this).text() === "Analyze") {
        $(this).prop('disabled', 'disabled'); //disabled="true"
      }
    });

    $("#bytes").removeAttr("disabled");
    $("#rowID").prop('disabled', 'disabled');
    $("#option-generator-type").removeAttr("disabled");
  } else if(option === "retrieve-existing-data") {
    $("#url").val(hotbits_url);
    //alert("2");
    $("#option-request-type option").each(function() {
      if($(this).text() === "Retrieve") {
        $(this).removeAttr("disabled");
        $(this).prop('selected', 'selected');
      }
      if($(this).text() === "Bytes" || $(this).text() === "Analyze") {
        $(this).prop('disabled', 'disabled'); //disabled="true"
      }
    });

    $("#rowID").removeAttr("disabled");
    $("#bytes").prop('disabled', 'disabled');
    $("#option-generator-type").prop('disabled', 'disabled');
  } else if(option === "analyze-count-html") {
    $("#url").val(hotbits_url);
    //alert("3");
    $("#option-format-type").removeAttr("disabled");
    $("#option-count").removeAttr("disabled");

    $("#option-format-type option").each(function() {
      if($(this).text() === "HTML") {
        $(this).prop('selected', 'selected');
      }
    });

    $("#option-count option").each(function() {
      if($(this).val() === "true") {
        $(this).prop('selected', 'selected');
      }
    });

    $("#option-request-type option").each(function() {
      if($(this).text() === "Analyze") {
        $(this).removeAttr("disabled");
        $(this).prop('selected', 'selected');
      }
      if($(this).text() === "Bytes" || $(this).text() === "Retrieve") {
        $(this).prop('disabled', 'disabled'); //disabled="true"
      }
    });

    $("#rowID").removeAttr("disabled");
    $("#bytes").prop('disabled', 'disabled');
    $("#option-generator-type").prop('disabled', 'disabled');
  } else if(option === "encryption-new-data") {
    $("#url").val(cryptography_url);
    //alert("4");
    $("#option-download-type").removeAttr("disabled");

    $("#option-request-type option").each(function() {
      if($(this).text() === "Bytes") {
        //this.removeAttribute("disabled");
        $(this).removeAttr("disabled");
        $(this).prop('selected', 'selected');
      }
      if($(this).text() === "Retrieve" || $(this).text() === "Analyze") {
        $(this).prop('disabled', 'disabled'); //disabled="true"
      }
    });

    $("#bytes").removeAttr("disabled");
    $("#rowID").prop('disabled', 'disabled');
    $("#option-generator-type").removeAttr("disabled");
  } else if(option === "encryption-existing-data") {
    $("#url").val(cryptography_url);
    //alert("5");
    $("#option-download-type").removeAttr("disabled");

    $("#option-request-type option").each(function() {
      if($(this).text() === "Retrieve") {
        $(this).removeAttr("disabled");
        $(this).prop('selected', 'selected');
      }
      if($(this).text() === "Bytes" || $(this).text() === "Analyze") {
        $(this).prop('disabled', 'disabled'); //disabled="true"
      }
    });

    $("#rowID").removeAttr("disabled");
    $("#bytes").prop('disabled', 'disabled');
    $("#option-generator-type").prop('disabled', 'disabled');
  } else if(option === "free-form") {
    //alert("6");
    //$("#option-download-type option[value='zip']").prop('disabled', 'disabled');
    $("#option-request-type option").each(function() {
        $(this).removeAttr("disabled");
    });
    $("#rowID").removeAttr("disabled");
    $("#bytes").removeAttr("disabled");
    $("#option-count").removeAttr("disabled");
    $("#option-format-type").removeAttr("disabled");
    $("#option-download-type").removeAttr("disabled");
    $("#option-download-type option[value='zip']").removeAttr("disabled");
    $("#option-generator-type").removeAttr("disabled");
  }
}

/*
<label for="option-request-type">Request Type: </label>
<select id="option-request-type" name="request-type">
  <option value="bytes">Bytes</option>
  <option value="retrieve">Retrieve</option>
  <option value="analyze">Analyze</option>
</select>

<br>

<label for="option-generator-type">Generator: </label>
<select id="option-generator-type" name="request-type">
  <option value="random">Random</option>
  <option value="pseudorandom">Pseudorandom</option>
</select>

<label for="bytes">Bytes (2048 Max): </label><input type="text" id="bytes" value="2048">
<label for="rowID">Row ID: </label><input type="text" id="rowID" value="1">

<br>

<label for="option-format-type">Format (Hotbits Only): </label>
<select id="option-format-type" name="request-type">
  <option value="random">Random</option>
  <option value="pseudorandom">Pseudorandom</option>
</select>

<label for="option-download-type">Download (Cryptography Only): </label>
<select id="option-download-type" name="request-type">
  <option value="base64">Base64</option>
  <option value="zip">Zip (Choose Base64) - I Only Work With Direct Download</option>
</select>
*/

$(document).ready(function() {
  $("#option-user-controls").change(function() {
    populateForm($(this).val());
    // grab-new-data, retrieve-existing-data, analyze-count-html, encryption-new-data, encryption-existing-data
  });
});

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
  $('#submit').click(function(e) {
    // https://stackoverflow.com/a/17387382/6828099
    window.URL.revokeObjectURL(this.href); // This removes the blob from memory
    this.removeAttribute("href"); // this.href sets link to have href attribute. This resets it.

    var rawData = lookup();
    if(rawData[1] === "json") {
      $("#response-table").show();
      raw(syntaxHighlight(prettyPrintArray(rawData[0])));
      table($.parseJSON(rawData[0]));

      /*var blob = new Blob([rawData[0]], {type: 'application/json'});
      var url = window.URL.createObjectURL(blob);

      this.href = url;
      this.target = '_blank';
      this.download = 'response.json';*/
    } else if(rawData[1] === "html") {
      $("#response-table").hide();
      raw(rawData[0]);

      /*var blob = new Blob([rawData[0]], {type: 'text/html'});
      var url = window.URL.createObjectURL(blob);

      this.href = url;
      this.target = '_blank';
      this.download = 'manpage.html';*/
    } else if(rawData[1] === "csv") {
      $("#response-table").hide();
      raw(rawData[0]);

      /*var blob = new Blob([rawData[0]], {type: 'text/csv'});
      var url = window.URL.createObjectURL(blob);

      this.href = url;
      this.target = '_blank';
      this.download = 'analyze.csv';*/
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

      // HREF:  TARGET:  DOWNLOAD:
      //alert("HREF: " + this.href + " TARGET: " + this.target + " DOWNLOAD: " + this.download);

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
    $("select").css("max-width", $("#ajax-table").width() + "px");
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