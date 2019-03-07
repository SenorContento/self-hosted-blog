$(document).ready(function() {
  $("#sun-roof-no").click(function() {
    $(".own-sunroof").css('display', 'none');
  });

  $("#sun-roof-yes").click(function() {
    $(".own-sunroof").css('display', 'inline-block');
  });

  $("tbody tr:even").addClass("table-even");
  $("tbody tr:odd").addClass("table-odd");
});