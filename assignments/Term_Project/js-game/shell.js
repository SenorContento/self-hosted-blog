var playedOnce=false; // Helps determine if already ran through program at least once

window.onload = function() {
  resizeCommand(); // Helps Resize Command TextBox
  //alert("Received 2 Max-Width: " + document.getElementById("received").style.maxWidth);

  scroll=true; // Sets autoscrolling to active

  startSocket(); // Opens WebSocket
}

// https://stackoverflow.com/a/9837823/6828099
function pageScroll() {
  window.scrollBy(0,1);

  if(scroll) {
    scrolldelay = setTimeout(pageScroll,10);
  } else {
    clearTimeout(pageScroll);
  }
}

// https://stackoverflow.com/a/13207995/6828099
function stopScrollMouse() {
  //alert("Stop Scroll");
  document.removeEventListener('mousemove', stopScrollMouse, false);
  scroll=false;
};

window.addEventListener("scroll",function(){
    window.lastScrollTime = new Date().getTime();
});

function is_scrolling() {
  return window.lastScrollTime && new Date().getTime() < window.lastScrollTime + 500
}

// This gets set off by autoscroll, so a little extra work is needed
// https://stackoverflow.com/a/10605219/6828099
// https://stackoverflow.com/a/52414518/6828099
window.onscroll = function (e) {
  //setTimeout(stopScroll,10);
  stopScroll();
}

function stopScroll() {
  if(!is_scrolling() && scroll) {
    // TODO: Sometimes this function is called when it isn't supposed to be.
    // This can cause autoscroll to stop when it is supposed to scroll.
    // This issue doesn't seem to be caused by the is_scrolling timer.
    //alert("Stop Scroll");
    scroll=false;
    clearTimeout(stopScroll);
  } else {
    setTimeout(stopScroll,10);
  }
}

// This is so I can reopen the socket later.
function startSocket(command) {
  var form = document.getElementById('sendCommand');
  var messageField = document.getElementById('command');

  // Create a new WebSocket.
  var socket = new WebSocket('ws://localhost/');
  //var socket = new WebSocket('wss://web.senorcontento.com/game/'); // wss://term.web.senorcontento.com/piet-websocket/5c92cd6054ce1/
  //var socket = new WebSocket('wss://term.web.senorcontento.com/piet-websocket/'); //5c92cd6054ce1/

  // Show a connected message when the WebSocket is opened.
  socket.onopen = function(event) {
    //alert('Connected to: ' + event.currentTarget.url);
    console.log('Connected to: ' + event.currentTarget.url);
    //socketStatus.className = 'open';

    if(playedOnce) {
      // Allows extra spacer to separate out sessions of connections
      /*received.innerHTML += "<span class=\"newsession\">────────────────────────────────────────────────</span>";*/
      received.innerHTML += '<hr class=\"newsession\"></hr>';
      received.innerHTML += '<div class=\"newsession-copy\">────────────────────────────────────────────────</div>';
      //received.innerHTML += '\n';
    }

    // Allows for sending command from previously closed session!!!
    if(typeof(command) === "string") {
      socket.send(command);
    }
  };

  // Show User Connection Closed
  socket.onclose = function(event) {
    console.log('Connection Closed: ' + event.currentTarget.url);
    received.innerHTML += 'Connection Closed!!!' + '\n';
    playedOnce = true;
  };

  // Handle any errors that occur.
  socket.onerror = function(error) {
    /*let type = error.type;
    let name = error.name;
    console.log('WebSocket Error: ' + error);
    received.innerHTML += ('WebSocket Error: ' + error);*/
    received.innerHTML += 'WebSocket Error: Cannot Connect to ' + event.currentTarget.url + '\n';
  };

  socket.onmessage = function(event) {
    var message = event.data;
    received.innerHTML += processMessage(message);

    scroll=true;

    // I don't think this function is needed
    //document.addEventListener('mousemove', stopScrollMouse, false); // This allows stopping the autoscrolling.

    pageScroll(); // This allows autoscrolling on the page.
  };

  // Send a message when the form is submitted.
  form.onsubmit = function(e) {
    e.preventDefault();

    // Retrieve the message from the textarea.
    var message = messageField.value;

    if(socket.readyState === socket.OPEN) {
      // Send the message through the WebSocket.
      socket.send(message);
    } else {
      startSocket(message);
    }

    // Clear out the message field.
    messageField.value = '';

    return false;
  };
}

// Useful - https://stackoverflow.com/a/21648161
function processMessage(message) {
  var parsed = "";
  var black = /\x1b\[30m/g;
  var red = /\x1b\[31m/g;
  var green = /\x1b\[32m/g;
  var yellow = /\x1b\[33m/g;
  var blue = /\x1b\[34m/g;
  var magenta = /\x1b\[35m/g;
  var cyan = /\x1b\[36m/g;
  var white = /\x1b\[37m/g;
  var reset = /\x1b\[0m/g;
  var newcolor = /\x1b\[/g;
  /* Colors
   *
   * Black: \u001b[30m
   * Red: \u001b[31m
   * Green: \u001b[32m
   * Yellow: \u001b[33m
   * Blue: \u001b[34m
   * Magenta: \u001b[35m
   * Cyan: \u001b[36m
   * White: \u001b[37m
   * Reset: \u001b[0m
   */

   //console.log("Message: \"" + message + "\"");

   parsed = message;
   while ((match = black.exec(parsed)) != null) {
     console.log("Black Position: " + match.index);
     console.log("Black Parsed: " + parsed);
     parsed = "<span class=\"black\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = red.exec(parsed)) != null) {
     console.log("Red Position: " + match.index);
     console.log("Red Parsed: " + parsed);
     //alert("Message: \"" + parsed.substring(match.index+5,message.length) + "\"");
     parsed = "<span class=\"red\">" + parsed.substring(match.index+5,message.length)

     while ((match = newcolor.exec(parsed)) != null) {
       //alert("Message: \"" + parsed.substring(0,match.index) + "\"");
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = green.exec(parsed)) != null) {
     console.log("Green Position: " + match.index);
     console.log("Green Parsed: " + parsed);
     parsed = "<span class=\"green\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = yellow.exec(parsed)) != null) {
     console.log("Yellow Position: " + match.index);
     console.log("Yellow Parsed: " + parsed);
     parsed = "<span class=\"yellow\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = blue.exec(parsed)) != null) {
     console.log("Blue Position: " + match.index);
     console.log("Blue Parsed: " + parsed);
     parsed = "<span class=\"blue\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   // https://stackoverflow.com/a/2295681
   while ((match = magenta.exec(parsed)) != null) {
     console.log("Magenta Position: " + match.index);
     console.log("Magenta Parsed: " + parsed);
     parsed = "<span class=\"magenta\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = cyan.exec(parsed)) != null) {
     console.log("Cyan Position: " + match.index);
     console.log("Cyan Parsed: " + parsed);
     parsed = "<span class=\"cyan\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   while ((match = white.exec(parsed)) != null) {
     console.log("White Position: " + match.index);
     console.log("White Parsed: " + parsed);
     parsed = "<span class=\"white\">" + parsed.substring(match.index+5,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

   //alert("Message: \"" + message + "\"");
   while ((match = reset.exec(parsed)) != null) {
     console.log("Reset Position: " + match.index);
     console.log("Reset Parsed: " + parsed);

     parsed = "<span class=\"reset\">" + parsed.substring(match.index+4,message.length);

     while ((match = newcolor.exec(parsed)) != null) {
       parsed = parsed.substring(0,match.index) + "</span>";
     }
     //parsed = "";
   }

  //message = message.match(cyan);

  return parsed + "\n";
}