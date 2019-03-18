window.onload = function() {
  var form = document.getElementById('message-form');
  var messageField = document.getElementById('message');

  // Create a new WebSocket.
  var socket = new WebSocket('wss://web.senorcontento.com/game/');

  // Show a connected message when the WebSocket is opened.
  socket.onopen = function(event) {
    //alert('Connected to: ' + event.currentTarget.url);
    console.log('Connected to: ' + event.currentTarget.url);
    //socketStatus.className = 'open';
  };

  // Handle any errors that occur.
  socket.onerror = function(error) {
    console.log('WebSocket Error: ' + error);
  };

  socket.onmessage = function(event) {
    var message = event.data;
    received.innerHTML += processMessage(message);
  };

  // Send a message when the form is submitted.
  form.onsubmit = function(e) {
    e.preventDefault();

    // Retrieve the message from the textarea.
    var message = messageField.value;

    // Send the message through the WebSocket.
    socket.send(message);

    // Clear out the message field.
    messageField.value = '';

    return false;
  };
}

function processMessage(message) {
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

  //var patt = /\u001b[31m/i;
  //message = str.match(patt);

  return message + "\n";
}