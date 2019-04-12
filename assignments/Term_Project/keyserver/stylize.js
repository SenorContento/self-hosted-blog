//$("select").css("max-width", $("#highlight").width() + "px");

function resizeCommand() {
  //document.getElementById("command").style.width = "816" + "px";
  //alert("Received Width: " + document.getElementById("received").offsetWidth);
  //document.getElementById("command").style.width = (document.getElementById("received").offsetWidth-7) + "px";

  var arrayLength = document.querySelectorAll('[type="text"]').length;
  for (var i = 0; i < arrayLength; i++) {
    //document.getElementsByTagName("input")[i].style.width = (document.getElementById("received").offsetWidth-7) + "px";
    document.querySelectorAll('[type="text"]')[i].style.width = (document.getElementById("sizing-tag").offsetWidth-55) + "px";
    //document.getElementsByTagName("textarea")[i].style.width = (document.getElementById("sizing-tag").offsetWidth-7) + "px";
    //alert((document.getElementById("sizing-tag").offsetWidth-7) + "px");
  }
}

window.onresize = function() {
  resizeCommand();
}

resizeCommand();