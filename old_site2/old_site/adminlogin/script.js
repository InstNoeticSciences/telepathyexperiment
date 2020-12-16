$(document).ready(function(){

	$(".delete").click(function(){
		if(confirm("Are you sure you want to remove it?")) return true;
		else return false;
	});
	$("#select_all").click(function(){
		$(".chk").attr("checked","checked");
	});
	$("#deselect_all").click(function(){
		$(".chk").attr("checked","");
	});
});