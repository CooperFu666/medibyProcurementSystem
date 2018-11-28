/*头部*/
$("#header").load("../stencil/header.html");

/*尾部*/
$("#footer").load("../stencil/footer.html");

/*侧栏*/
$("#sidebar").load("../stencil/sidebar.html");

/*分页*/
$("#paging").load("../stencil/paging.html");


/*************************************************我的订单start**************************************************************/
// $(".on_order_details").click(function(){
// 	$(".myorder_main").hide();
// 	$(".order_details").show();
// });

/*************************************************个人中心start**************************************************************/

//个人中心-修改头像
$(".on_avatar").click(function(){
	$(".avatar").show();
});
//个人中心-修改头像-上传头像
function reads(fil){
	var reader = new FileReader();
	reader.readAsDataURL(fil);
	reader.onload = function(){
		$(".tar_img").attr('src',reader.result);
		$(".preview100").attr('src',reader.result);
		$(".preview60").attr('src',reader.result);
		$("#avatar").val(reader.result);
	};
};
$("#immp").change(function () {
	var fil = this.files;
	for (var i = 0; i < fil.length; i++) {
		reads(fil[i]);
	}
});
//个人中心-修改密码
$(".on_name").click(function(){
	$(".avapassword_two").show();
});
//提交
jQuery.next_two = function() {
    $(".avapassword_two").hide();
    $(".avapassword_end").show();
};

//绑定邮箱
$(".on_mailbox").click(function(){
	$(".mailbox").show();
});
var kg6, kg7;
//邮箱验证
jQuery.onEmail_add = function() {
	if($('.email_add').val() == '') {
		$('.email_add_yz').html("邮箱不能为空")
	} else if(!$('.email_add').val().match(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/g)) {
		$('.email_add_yz').html("邮箱格式错误")
		kg6 = false
	} else if($('.email_add').val().match(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/g)) {
		$('.email_add_yz').html(" ")
		kg6 = true
	}
};
//图片验证码验证two
jQuery.onImg_code_two = function() {
	if($('.img_code_two').val() == '') {
		$('.img_code_two_yz').html("图片验证码不能为空")
	} else if(!$('.img_code_two').val().match(/^[a-zA-Z0-9]{4}$/)) {
		$('.img_code_two_yz').html("图片验证码错误")
		kg7 = false
	} else if($('.img_code_two').val().match(/^[a-zA-Z0-9]{4}$/)) {
		$('.img_code_two_yz').html(" ")
		kg7 = true
	}
};
$(".img_code_two").blur(function() {
	$.onImg_code_two();
});
$(".email_add").blur(function() {
	$.onEmail_add();
});
//提交
jQuery.mailbox_next = function() {
	$.onEmail_add();
	$.onImg_code_two();

	if(kg6 && kg7 == true) {
		$(".mailbox").hide();
		$(".mailbox_end").show();
	}
};
//键盘按下
document.onkeydown = function(e) {
	var e = document.all ? window.event : e;
	if(e.keyCode == 13) {
		$('.email_add').blur();
		$('.img_code_two').blur();
		$.mailbox_next();
		return false;
	}
};
//鼠标按下
$('.mailbox_next').click(function() {
	$.mailbox_next();
	return false;
});

/*************************************************用户信息start**************************************************************/
//用户信息
$(".alter").click(function(){
	$(".userinfo_inp_box").show();
});

//企业类型
$(".select_box input").click(function() {
	var thisinput = $(this);
	var thisul = $(this).parent().find("ul");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
//				"overflow-y": "scroll"
			})
		};
		thisul.fadeIn("100");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("100");
		})
		thisul.find("li").click(function() {
			thisinput.val($(this).text());
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hoverbj");
		}, function() {
			$(this).removeClass("hoverbj");
		});
	} else {
		thisul.fadeOut("fast");
	}
});



/*************************************************我的收藏start**************************************************************/
//我的收藏
$(".mycollect_nav").click(function() {
	$(".mycollect_main").show().siblings().hide();
});
$(".new_address").click(function(){
	$(".alert").show();
})
$(".address_hide").click(function(){
	$(".alert").hide();
})
$(".operate_on").click(function(){
	$(".alert").hide();
})

/*************************************************我的足迹start****************************************************************/
//我的足迹
$(".mytracks_nav").click(function() {
	$(".mytracks_main").show().siblings().hide();
});
//收货地址
$(".address_nav").click(function() {
	$(".address_main").show().siblings().hide();
});

/*************************************************我的优惠券start*************************************************************/
//我的优惠券
$(".mycoupon_nav").click(function() {
	$(".mycoupon_main").show().siblings().hide();
});
$(".mycoupon_sub_yes").click(function(){
	$(".mycoupon_content").show();
	$(".mycoupon_content_no").hide();
})
$(".mycoupon_sub_no").click(function(){
	$(".mycoupon_content").hide();
	$(".mycoupon_content_no").show();
})

/*************************************************资质认证start***************************************************************/
//资质认证
$(".immp_nav").click(function() {
	$(".immp_main").show().siblings().hide();
});
//资质认证-上传营业执照
function licence(fils){
	var readers = new FileReader();
	readers.readAsDataURL(fils);
	readers.onload = function(){
		$(".licence_img").attr('src',readers.result);
	};
};
$("#immp_file").change(function () {
	var fils = this.files;
	for (var i = 0; i < fils.length; i++) {
		licence(fils[i]);
	}
});
//资质认证-上传医疗器械许可证
function medical(fn){
	var readers = new FileReader();
	readers.readAsDataURL(fn);
	readers.onload = function(){
		$(".medical_img").attr('src',readers.result);
	};
};
$("#medical_file").change(function () {
	var fn = this.files;
	for (var i = 0; i < fn.length; i++) {
		medical(fn[i]);
	}
});
//资质认证-切换
$(".immp_sub_yes").click(function(){
	$(".immp_content_yes").show();
	$(".immp_content_no").hide();
    $(".immp_sub_yes").attr('class', "immp_sub_yes active");
    $(".immp_sub_no").attr('class', "immp_sub_no");
})
$(".immp_sub_no").click(function(){
	$(".immp_content_yes").hide();
	$(".immp_content_no").show();
    $(".immp_sub_no").attr('class', "immp_sub_no active");
    $(".immp_sub_yes").attr('class', "immp_sub_yes ");
})

/*************************************************我的发票start**************************************************************/
//我的发票
$(".myinvoice_nav").click(function() {
	$(".myinvoice_main").show().siblings().hide();
});
$(".myinvoice_revise").click(function(){
	$(".myinvoice_add").show();
	$(".myinvoice_content").hide();
})
//$(".myinvoice_keep").click(function(){
//	$(".myinvoice_add").hide();
//	$(".myinvoice_content").show();
//})


/*************************************************联系客服start**************************************************************/
//联系客服
$(".contact_nav").click(function() {
	$(".contact_main").show().siblings().hide();
});
$(".call_back button").click(function(){
	$(".contact_yes").show();
	$(".contact_content").hide();
})
$(".contact_yes button").click(function(){
	$(".contact_yes").hide();
	$(".contact_content").show();
})
$(".contact_box input").click(function() {
	var thisinput = $(this);
	var thisul = $(this).parent().find("ul");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
//				"overflow-y": "scroll"
			})
		};
		thisul.fadeIn("100");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("100");
		})
		thisul.find("li").click(function() {
			thisinput.val($(this).text());
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hoverbj");
		}, function() {
			$(this).removeClass("hoverbj");
		});
	} else {
		thisul.fadeOut("fast");
	}
});
$(".time_year").click(function() {
	var thisinput = $(this);
	var thisul = $(this).parent().find(".select_year");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
//				"overflow-y": "scroll"
			})
		};
		thisul.fadeIn("100");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("100");
		})
		thisul.find("li").click(function() {
			thisinput.val($(this).text());
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hoverbj");
		}, function() {
			$(this).removeClass("hoverbj");
		});
	} else {
		thisul.fadeOut("fast");
	}
});
$(".time_shi").click(function() {
	var thisinput = $(this);
	var thisul = $(this).parent().find(".select_shi");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
//				"overflow-y": "scroll"
			})
		};
		thisul.fadeIn("100");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("100");
		})
		thisul.find("li").click(function() {
			thisinput.val($(this).text());
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hoverbj");
		}, function() {
			$(this).removeClass("hoverbj");
		});
	} else {
		thisul.fadeOut("fast");
	}
});
$(".time_fen").click(function() {
	var thisinput = $(this);
	var thisul = $(this).parent().find(".select_fen");
	if(thisul.css("display") == "none") {
		if(thisul.height() > 280) {
			thisul.css({
				height: "280" + "px"
//				"overflow-y": "scroll"
			})
		};
		thisul.fadeIn("100");
		thisul.hover(function() {}, function() {
			thisul.fadeOut("100");
		})
		thisul.find("li").click(function() {
			thisinput.val($(this).text());
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hoverbj");
		}, function() {
			$(this).removeClass("hoverbj");
		});
	} else {
		thisul.fadeOut("fast");
	}
});
$(".call_back span").click(function(){
	$(".contact_alert").show();
})
$(".contact_text h2 p").click(function(){
	$(".contact_alert").hide();
})
$(".operate_on").click(function(){
	$(".contact_alert").hide();
})

/*************************************************系统消息start****************************************************************/
//系统消息
$(".system_nav").click(function() {
	$(".system_main").show().siblings().hide();
});










