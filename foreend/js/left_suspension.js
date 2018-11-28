var nav = $(".mulu");
	var win = $(window);
	var sc = $(document);
	win.scroll(function() {
		if(sc.scrollTop() > 600) {
			nav.addClass("muludong");
			$(".mulu").fadeIn();
		} else {
			nav.removeClass("muludong");
		};
	});
	$("[id*='Btn']").stop(true).on('click', function(e) {
		e.preventDefault();
		$(this).scrolld();
	});
	$("#demoUl a:eq(0)").click(function() {
		$("#demoUl a").removeClass("bgcolor");
		$("#demoUl a:eq(0)").addClass("bgcolor");
	})
	$("#demoUl a:eq(1)").click(function() {
		$("#demoUl a").removeClass("bgcolor");
		$("#demoUl a:eq(1)").addClass("bgcolor");
	})
	$("#demoUl a:eq(2)").click(function() {
		$("#demoUl a").removeClass("bgcolor");
		$("#demoUl a:eq(2)").addClass("bgcolor");
	})
	$("#demoUl a:eq(3)").click(function() {
		$("#demoUl a").removeClass("bgcolor");
		$("#demoUl a:eq(3)").addClass("bgcolor");
	})
	$("#demoUl a:eq(4)").click(function() {
		$("#demoUl a").removeClass("bgcolor");
		$("#demoUl a:eq(4)").addClass("bgcolor");
	})
	$(".gotop").click(function() {
    	$("html,body").animate({scrollTop:0}, 500);
  });