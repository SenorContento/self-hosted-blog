//$("select").css("max-width", $("#highlight").width() + "px");

function resizeCommand() {
  //document.getElementById("command").style.width = "816" + "px";
  //alert("Received Width: " + document.getElementById("received").offsetWidth);
  document.getElementById("command").style.width = (document.getElementById("received").offsetWidth-7) + "px";
}

window.onresize = function() {
  resizeCommand();
}