function setTimer(time) {
  // Set the date we're counting down to
  //var countDownDate = new Date(time).getTime(); // "Jan 5, 2021 15:37:25"
  var countDownDate = new Date(time); // "Mar 13, 2019 14:30:18"

  // This is a hack to adjust the time to UTC without actually changing the timezone. JS wants to use local timezone. I provide UTC.
  countDownDate.setTime(countDownDate.getTime() - countDownDate.getTimezoneOffset()*60*1000); // https://stackoverflow.com/a/16048201/6828099
  //alert(countDownDate);

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Get todays date and time
    //var now = new Date().getTime();
    var now = Date.now();
    //document.getElementById("time-remaining").innerHTML = now;

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Output the result in an element with id="demo"
    document.getElementById("time-remaining").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";

    // If the count down is over, write some text
    if (distance < 0) {
      clearInterval(x);
      document.getElementById("time-remaining").innerHTML = "Now Unbanned!!!";
    }
  }, 1000);
}