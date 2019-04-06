function openTab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

document.getElementById("defaultOpen").click();

function closeDrawer() {
  //var drawer = document.getElementsByClassName("tab");
  var drawer = document.getElementById("tab");
  var buttons = document.getElementsByClassName("tablinks");
  var shade = document.getElementById("tab-shade");
  var tabcontents = document.getElementsByClassName("tabcontent");
  var menubutton = document.getElementById("menu");

  drawer.classList.add("tab-hidden");
  /*for(var x = 0; x < drawer.length; x++) {
    drawer[x].classList.add("tab-hidden");
    //drawer[x].classList.remove("tab");
  }*/

  for(var x = 0; x < buttons.length; x++) {
    buttons[x].classList.add("tab-hidden");
    buttons[x].classList.remove("tab-visible");
  }

  shade.classList.add("shade-hidden");

  for(var x = 0; x < tabcontents.length; x++) {
    tabcontents[x].classList.add("tabcontent-visible");
  }

  menubutton.classList.remove("nav-open-hidden");
}

function openDrawer() {
  //var drawer = document.getElementsByClassName("tab");
  var drawer = document.getElementById("tab");
  var buttons = document.getElementsByClassName("tablinks");
  var shade = document.getElementById("tab-shade");
  var tabcontents = document.getElementsByClassName("tabcontent");
  var menubutton = document.getElementById("menu");

  drawer.classList.remove("tab-hidden");
  /*for(var x = 0; x < drawer.length; x++) {
    drawer[x].classList.add("tab-hidden");
    //drawer[x].classList.remove("tab");
  }*/

  for(var x = 0; x < buttons.length; x++) {
    buttons[x].classList.remove("tab-hidden");
    buttons[x].classList.add("tab-visible");
  }

  shade.classList.remove("shade-hidden");

  for(var x = 0; x < tabcontents.length; x++) {
    tabcontents[x].classList.remove("tabcontent-visible");
  }

  menubutton.classList.add("nav-open-hidden");
}