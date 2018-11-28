/*尾部*/
$("#footer").load("../stencil/footer.html");

/*尾部*/
$("#hots").load("../stencil/hots.html");

$('.balance_info li,.balance_pay li,.balance_red li,.balance_invoice li').click(function(){
	var $li = $(this);
	var $change = $li.siblings();
	if($li.hasClass('bg')) {
		$li.removeClass('bg').addClass('active');
	} else {
		$li.removeClass('active').addClass('bg');
	}
	$change.addClass('bg').removeClass('active');
})
	


$(".balance_add").click(function(){
	$(".alert").show();
})
$(".address_hide").click(function(){
	$(".alert").hide();
})
$(".operate_on").click(function(){
	$(".alert").hide();
})