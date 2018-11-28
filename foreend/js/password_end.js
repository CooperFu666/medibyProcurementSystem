/*头部*/
$("#page_header").load("../stencil/page_header.html");

/*尾部*/
$("#page_footer").load("../stencil/page_footer.html");

//注册3
function jump(count) {
	window.setTimeout(function() {
		count--;
		if(count > 0) {
			$('#num').html(count);
			jump(count);
		} else {
			location.href = "login.html";
		}
	}, 1000);
}
jump(5);