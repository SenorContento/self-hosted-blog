$(document).ready(function() {
  //$("#ajax-output-debug").css("max-width", ($("#ajax-table").width() - 20) + "px");
  $("select").css("max-width", $("#highlight").width() + "px");
});

function lookup() {
  var json = JSON.parse($("#data").val());

  var url = "/api/antivirus";
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