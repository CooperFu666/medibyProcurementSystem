/*头部*/
$("#header").load("../stencil/header_index.html");

/*尾部*/
$("#footer").load("../stencil/footer.html");

/*尾部*/
$("#left_suspension").load("../stencil/left_suspension.html");

/*轮播*/
var aa = new Swiper('#lunbo', {
	autoplay: 3000,
	speed: 1000,
	autoplayDisableOnInteraction: false,
	direction: 'horizontal',
	loop: true,
	pagination: '.swiper-pagination',
	paginationClickable: true,
	nextButton: '.swiper-button-next',
	prevButton: '.swiper-button-prev'
});

$(document).ready(function(){
	$(".navsub").css("display","block")
});