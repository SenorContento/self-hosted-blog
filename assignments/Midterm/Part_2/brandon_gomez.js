// JavaScript Document
$(document).ready(function(){
	$("#catcountcontainer").hide();
	$("#othercontainer").hide();
	$("table.output tbody tr:even").addClass("evenrow");
	$("table.output tbody tr:odd").addClass("oddrow");
	$("input[name=cats]").on("change", function(){
		$("#catcountcontainer").hide();
		if(this.value === 'yes')
		{
			$("#catcountcontainer").show();
		}
	});
	$('input#favOther').on("change", function(){
		$("#othercontainer").hide();
		if($(this).is(':checked'))
		{
			$("#othercontainer").show();
		}
	});
});