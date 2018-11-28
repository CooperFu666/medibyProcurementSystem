$(document).ready(function(){
	$(".navsub").css("display","block")
});

$(document).click(function(){
	$(".navsub").stop(true);
});
$(".carindex").click(function(event){
	event.stopPropagation();
    $(".navsub").stop(true);
});