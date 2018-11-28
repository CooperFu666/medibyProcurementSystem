/*尾部二维码*/
$("[ma]").hover(function() {
	$(this).addClass("hovertwo");
	var a2 = $(this).attr("ma");
	$("[ma2=" + a2 + "]").stop().fadeIn(300);
}, function() {
	$(this).removeClass("hovertwo");
	var a2 = $(this).attr("ma");
	$("[ma2=" + a2 + "]").stop().fadeOut(300);
});