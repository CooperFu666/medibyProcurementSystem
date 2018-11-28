/*头部*/
$("#header").load("../stencil/header_index.html");

/*尾部*/
$("#footer").load("../stencil/footer.html");
// 页面定时跳转
$(document).ready(function () {
    setInterval(time,1000)
    var time = parseInt($(".setTime").html())
    function time() {
        time--;
        $(".setTime").html(time)
        if(time==0){
            location.href="./index.html";
            window.clearInterval(time);
        }
    }
});
