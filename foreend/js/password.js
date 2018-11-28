/*头部*/
$("#page_header").load("../stencil/page_header.html");

/*尾部*/
$("#page_footer").load("../stencil/page_footer.html");

/*找回密码one*/
var kg1, kg2, kg3;
//手机格式验证
jQuery.onPhone = function() {
	if($('.phone').val() == '') {
		$('.phone_yz').html("❶ 手机不能为空");
	} else if(!$('.phone').val().match(/^[0-9]{11}$/g)) {
		$('.phone_yz').html("❶ 手机格式错误")
		kg1 = false
	} else if($('.phone').val().match(/^[0-9]{11}$/g)) {
		$('.phone_yz').html(" ")
		kg1 = true
	}
};
$(".phone").blur(function() {
	$.onPhone();
});
//手机验证码验证
jQuery.onPhone_code = function() {
	if($('.phone_code').val() == '') {
		$('.phone_code_yz').html("手机验证码不能为空");
	} else if(!$('.phone_code').val().match(/^[0-9]{6}$/)) {
		$('.phone_code_yz').html("手机验证码错误");
		kg2 = false;
	} else if($('.phone_code').val().match(/^[0-9]{6}$/)) {
		$('.phone_code_yz').html(" ");
		kg2 = true;
	}
};
$(".phone_code").blur(function() {
	$.onPhone_code();
});
//图片验证码验证
jQuery.onImg_code = function() {
	if($('.img_code').val() == '') {
		$('.img_code_yz').html("图片验证码不能为空")
	} else if(!$('.img_code').val().match(/^[a-zA-Z0-9]{4}$/)) {
		$('.img_code_yz').html("图片验证码错误")
		kg3 = false
	} else if($('.img_code').val().match(/^[a-zA-Z0-9]{4}$/)) {
		$('.img_code_yz').html(" ")
		kg3 = true
	}
};
$(".img_code").blur(function() {
	$.onImg_code();
});

//提交
jQuery.next_one = function() {
	$.onPhone();
	$.onPhone_code();
	$.onImg_code();
	
	if(kg1 && kg2 && kg3 == true) {
		location.href = 'password_two.html'
	}
};
//键盘按下
document.onkeydown = function(e) {
	var e = document.all ? window.event : e;
	if(e.keyCode == 13) {
		$('.phone').blur();
		$('.phone_code').blur();
		$('.img_code').blur();
		$.next_one();
		return false;
	}
};
//鼠标按下
$('.next_one').click(function() {
	$.next_one();
	return false;
});

/*找回密码two*/
var kg4, kg5;
//新密码验证
jQuery.onPassword_one = function() {
	if($('.password_one').val() == '') {
		$('.password_one_yz').html("密码不能为空")
	} else if(!$('.password_one').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_one_yz').html("密码格式错误")
		kg4 = false
	} else if($('.password_one').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_one_yz').html(" ")
		kg4 = true
	}
};
$(".password_one").blur(function() {
	$.onPassword_one();
});
//确认密码验证码
jQuery.onPassword_two = function() {
	if($('.password_two').val() == '') {
		$('.password_two_yz').html("密码不能为空")
	} else if(!$('.password_two').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_two_yz').html("密码格式错误")
		kg5 = false
	} else if($('.password_two').val() != $('.password_one').val()) {
		$('.password_two_yz').html("两次密码输入不一致")
		zc5 = false
	} else if($('.password_two').val().match(/^[a-z,A-Z,0-9]{6,}$/g)) {
		$('.password_two_yz').html(" ")
		kg5 = true
	}
};
$(".password_two").blur(function() {
	$.onPassword_two();
});
//提交
jQuery.next_two = function() {
	$.onPassword_one();
	$.onPassword_two();

	if(kg4 && kg5 == true) {
		location.href = 'password_end.html'
	}
};
//键盘按下
document.onkeydown = function(e) {
	var e = document.all ? window.event : e;
	if(e.keyCode == 13) {
		$('.password_one').blur();
		$('.password_two').blur();
		$.next_two();
		return false;
	}
};
//鼠标按下
$('.next_two').click(function() {
	$.next_two();
	return false;
});