/*导航*/
$('.nav_nav li').hover(function() {
	$('span', this).stop().css('height', '2px');
	$('span', this).animate({
		left: '0',
		width: '100%',
		right: '0'
	}, 300);
}, function() {
	$('span', this).stop().animate({
		left: '50%',
		width: '0'
	}, 300);
});

/*商品分类*/
$(document).ready(function(){
	$(".navsub").css("display","none")
});

$(document).click(function(){
	$(".navsub").slideUp("");
});
$(".carindex").click(function(event){
	event.stopPropagation();
    $(".navsub").slideToggle("");
});

$("[mg]").hover(function() {
	$(this).addClass("hover");
	var a1 = $(this).attr("mg");
	$("[mg2=" + a1 + "]").stop().fadeIn(300);
}, function() {
	$(this).removeClass("hover");
	var a1 = $(this).attr("mg");
	$("[mg2=" + a1 + "]").stop().fadeOut(300);
});