/*尾部*/
$("#footer").load("../stencil/footer.html");

/*尾部*/
$("#hots").load("../stencil/hots.html");

// 数量减
$(".minus").click(function() {
	var t = $(this).parent().find('.num');
	t.text(parseInt(t.text()) - 1);
	if(t.text() <= 1) {
		t.text(1);
	}
	computeTotalPay()
});
// 数量加
$(".plus").click(function() {
	var t = $(this).parent().find('.num');
	t.text(parseInt(t.text()) + 1);
	if(t.text() <= 1) {
		t.text(1);
	}
	computeTotalPay()
});
// 删除所选
function delTr2() {
	delTr('ckb');
	computeTotalPay();
}

function delTr(ckb) {
	//获取选中的复选框，然后循环遍历删除
	var ckbs = $("input[name=" + ckb + "]:checked");
	if(ckbs.size() == 0) {
		alert("要删除指定行，需选中要删除的行！");
		return;
	}
	ckbs.each(function() {
		$(this).parent().parent().remove();
	});
}
//hx全选和反全选
$(".all").click(function() {

	$(".list :checkbox").prop("checked", this.checked);

	$(".all").prop("checked", this.checked);
	computeTotalPay();
});
//设置全选复选框
$(".list :checkbox").click(function() {
	allchk();
});

function allchk() {
	var chknum = $(".list :checkbox").length; //选项总个数
	var chk = 0;
	$(".list :checkbox").each(function() {
		if($(this).prop("checked") == true) {
			chk++;
		}
	});
	if(chknum == chk) { //全选
		$(".all").prop("checked", true);
	} else { //不全选
		$(".all").prop("checked", false);
	}
}

//    结算总价计算
// 监听checkbox选中事件
$('body').on('change', '.list input:checkbox', function() {
	computeTotalPay();
});

$('body').on('change', '.list.num', function() {
	computeTotalPay();
});

$(document).ready(function() {
	computeTotalPay();
});
if(!window.computeTotalPay) {
	window.computeTotalPay = function() {
		var goodsNum = 0; //数量
		var oprice = 0; //总价
		// 获取所有选中行
		$(".list input:checkbox:checked").each(function() {
			goodsNum += 1;
			// 获取所在行
			var num = parseInt($(this).parents(".list").find(".num").text());
			var price = parseFloat($(this).parents(".list").find(".price").text());
			var total = price * num;
			oprice += total;

			$(".chek").find(".ShopTotal").text(oprice.toFixed(2));
		});
		$(".ShopTotal").text(oprice.toFixed(2));
		// 设置结果
		$('#totalNum').text(goodsNum);
	}
}
