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
			$('#UserDetailModel_corporate_type').val($(this).attr('data-id'))
			thisul.fadeOut("100");
		}).hover(function() {
			$(this).addClass("hover");
		}, function() {
			$(this).removeClass("hover");
		});
	} else {
		thisul.fadeOut("fast");
	}
});
/*注册2*/
var op1;
//公司名称验证
jQuery.onCorporate = function() {
	if($('.corporate').val() == '') {
		$('.corporate_yz').html("公司名称不能为空");
		op1 = false
	} else if(!$('.corporate').val() == '') {
		$('.corporate_yz').html(" ")
		op1 = true
	}
};
$(".corporate").blur(function() {
	$.onCorporate();
});
//企业类型验证
jQuery.onCompany = function() {
	if($(".select_box input:text").val() == '选择类型') {
		$('.company_yz').html("请选择企业类型");
		op2 = false
	} else if($(".select_box input:text").val() != '选择类型') {
		$('.company_yz').html(" ")
		op2 = true
	}
};
$(".company").blur(function() {
	$.onCompany();
});
//提交
jQuery.next_two = function() {
	$.onCorporate();
	$.onCompany();

	if(op1 && op2 == true) {
		$('#register-form').submit();
	}
};
//键盘按下
document.onkeydown = function(e) {
	var e = document.all ? window.event : e;
	if(e.keyCode == 13) {
		$('.corporate').blur();
		$.next_two();
		return false;
	}
};
//鼠标按下
$('.next_two').click(function() {
	$.next_two();
	return false;
	$("#myselect").val(111)
});
