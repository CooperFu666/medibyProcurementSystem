//品牌
tag.onclick = function() {
	if(tag.innerHTML == "展开 ∨") {
		tag.innerHTML = "闭合 ∧";
		brand_list.style.cssText = "height: 180px;transition: .5s all ease;"
	} else {
		tag.innerHTML = "展开 ∨";
		brand_list.style.cssText = "height: 80px;transition: .5s all ease;"
	};
};