//注册3
function jump(count) {
	window.setTimeout(function() {
		count--;
		if(count > 0) {
			$('#num').html(count);
			jump(count);
		} else {
			location.href = "index.html";
		}
	}, 1000);
}
jump(5);