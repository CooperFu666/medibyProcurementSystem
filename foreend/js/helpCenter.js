/*头部*/
$("#header").load("../stencil/header.html");

/*尾部*/
$("#footer").load("../stencil/footer.html");

function openNav(obj) {
    var par = obj.parentNode;
    var childNum = $(par).find('li').length
    if(par.style.height==''||par.style.height=='48px') {
        par.style.height = 48+childNum*40+'px'
        $(obj).addClass('changePhoto')
        // $(obj).css({'background':'url("../img/blackdown.png") no-repeat 152px 18px'})
        $(obj).css({'color':'#333333'})
    }else {
        par.style.height = '48px'
        $(obj).removeClass('changePhoto')
        // $(obj).css({'background':'url("../img/left2.png") no-repeat 156px 18px'})
        $(obj).css({'color':'#b3b3b3'})
    }
}
// 模拟点击售后服务
$(document).ready(function () {
    var sale = document.getElementById('sale')
    openNav(sale)
})
