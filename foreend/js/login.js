/*头部*/
$("#page_header").load("../stencil/page_header.html");

/*尾部*/
$("#page_footer").load("../stencil/page_footer.html");

/*登录*/
$(document).click(function() {
	$(".name_box").css("border", "1px solid #ddd");
	$(".password_box").css("border", "1px solid #ddd");
});
$(".name_box input").click(function(event) {
	event.stopPropagation();
	$(".name_box").css("border", "1px solid #21cf70");
	$(".password_box").css("border", "1px solid #ddd");
});
$(".password_box input").click(function(event) {
	event.stopPropagation();
	$(".name_box").css("border", "1px solid #ddd");
	$(".password_box").css("border", "1px solid #21cf70");
});

var kg1, kg2;
//用户名验证
jQuery.onName = function() {
	if($('.name').val() == '') {
		$('.name_yz').html("用户名/手机号不能为空");
	} else if(!$('.name').val().match(/^[0-9]{11}$/g)) {
		$('.name_yz').html("用户名/手机号格式错误")
		kg1 = false
	} else if($('.name').val().match(/^[0-9]{11}$/g)) {
		$('.name_yz').html(" ")
		kg1 = true
	}
};
$(".name").blur(function() {
	$.onName();
});
//密码验证
jQuery.onPassword = function() {
	if($('.password').val() == '') {
		$('.password_yz').html("密码不能为空")
	} else if(!$('.password').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_yz').html("密码格式错误")
		kg2 = false
	} else if($('.password').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_yz').html(" ")
		kg2 = true
	}
};
$(".password").blur(function() {
	$.onPassword();
});
//提交
jQuery.login = function() {
	$.onName();
	$.onPassword();
	
	if(kg1 && kg2 == true) {
		location.href = 'index.html'
	}
};
//键盘按下
document.onkeydown = function(e) {
	var e = document.all ? window.event : e;
	if(e.keyCode == 13) {
		$('.name').blur();
		$('.password').blur();
		$.login();
		return false;
	}
};
//鼠标按下
$('.login').click(function() {
	$.login();
	return false;
});

