

//商品收藏

//function change_pic() {
//  var imgObj = document.getElementById("onred");
//  var Flag = (imgObj.getAttribute("src", 2) == "../img/icon1.png")
//  imgObj.src = Flag ? "../img/icon2.png" : "../img/icon1.png";
//}
//商品数量'+-'
var ipt = document.getElementsByTagName("input")
ipt[0].onclick = function() {
    ipt[2].value--
    if(ipt[2].value < 0) {
        ipt[2].value = 0
    }
};
ipt[2].onclick = function() {
    ipt[2].value++
}
//商品放大镜
var ul = document.getElementsByTagName('ul')[0];
var allLi = document.getElementsByTagName('ul')[0].getElementsByTagName('li');
var needImg = document.getElementsByTagName('img')[0];
var windowImg = big.getElementsByTagName('img')[0];
var liArr = [];
for(var i = 0; i < allLi.length; i++) {
    liArr.push(allLi[i]);
}

small.onmouseover = function() {
    float1.style.display = 'block';
    big.style.display = 'block';
};
small.onmouseout = function() {
    float1.style.display = 'none';
    big.style.display = 'none';
};
small.onmousemove = function(e) {

    var e = e || event;
    var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
    var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
    var x = e.pageX || e.clientX + scrollX;
    var y = e.pageY || e.clientY + scrollY;
    var l = x - div1.offsetLeft;
    var t = y - div1.offsetTop;
    l = l < 50 ? 50 : l;
    t = t < 50 ? 50 : t;
    l = l > 328 ? 328 : l;
    t = t > 328 ? 328 : t;
//	alert(div1.offsetWidth);
    var biX = (l - float1.offsetWidth / 2) / ((mark.offsetWidth - 2) - float1.offsetWidth / 2);
    var biY = (t - float1.offsetWidth / 2) / 278;
    windowImg.style.left = (((big.offsetWidth - 2) - windowImg.offsetWidth) * 0.01) * (biX * 100) + 'px';
    windowImg.style.top = (((big.offsetWidth - 2) - windowImg.offsetWidth) * 0.01) * (biY * 100) + 'px';

    float1.style.left = l - 50 + 'px';

    float1.style.top = t - 50 + 'px';
};

function findImg(obj, size, nodeSrc) {
    var len = obj.children[0].src.lastIndexOf('s');
    var needSrc = obj.children[0].src.substring(0, len);
    var lastSrc = obj.children[0].src.substring(len + 1);
    var sagoSrc = needSrc + size + lastSrc;
    nodeSrc.src = sagoSrc;
}

left.onclick = function() {
    liArr.push(liArr.shift());
    ul.innerHTML = '';

    for(var i = 0; i < liArr.length; i++) {
        var li = document.createElement('li');
        li = liArr[i];
        ul.appendChild(li);
    };

    for(var i = 0; i < allLi.length; i++) {
        allLi[i].className = ''
    };

    findImg(allLi[0], 'm', needImg);
    findImg(allLi[0], 'b', big.getElementsByTagName('img')[0])
    allLi[0].className = 'active';
}

right.onclick = function() {
    liArr.unshift(liArr.pop());
    ul.innerHTML = '';

    for(var i = 0; i < liArr.length; i++) {
        var li = document.createElement('li');
        li = liArr[i];
        ul.appendChild(li);
    };

    for(var i = 0; i < allLi.length; i++) {
        allLi[i].className = ''
    };

    findImg(allLi[0], 'm', needImg);
    findImg(allLi[0], 'b', big.getElementsByTagName('img')[0]);
    allLi[0].className = 'active';

}

for(var i = 0; i < allLi.length; i++) {
    allLi[i].onmouseover = function() {
        findImg(this, 'm', needImg);
        findImg(this, 'b', big.getElementsByTagName('img')[0]);
        for(var j = 0; j < allLi.length; j++) {
            allLi[j].className = '';
        };
        this.className = 'active';
    }
};