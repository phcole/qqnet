document.write('\
	<style type="text/css">\
	#imgview{transition:all .2s linear;position:fixed;Z-INDEX:9999;top:0;right:0;bottom:0;left:0;opacity:0;display:none;background-color:rgba(0,0,0,0.6);}\
	#imgview img{position:absolute;top:50%;left:50%;}\
	</style>\
	<div id="imgview"><img id="imgview_img" src=""></div>\
');

function bindEvent(target, event, handler){
	if (typeof target.addEventListener != "undefined"){
		target.addEventListener(event, handler);
	} else {
		target.attachEvent("on"+event, handler);
	}
};

function unbindEvent(target, event, handler){
	if (typeof target.removeEventListener != "undefined"){
		target.removeEventListener(event, handler);
	} else {
		target.detachEvent("on"+event, handler);
	}
};

function resizeBgImg(s,t){
	r_w = s.naturalWidth / (window.innerWidth - 20);
	r_h = s.naturalHeight / (window.innerHeight - 20);
	if (r_w >= 1 && r_h >= 1){ //长款同时出界
		if (r_w >= r_h){
			i_w = window.innerWidth - 20;
			i_h = s.naturalHeight / r_w;
		} else {
			i_w = s.naturalWidth / r_h;
			i_h = window.innerHeight - 20;
		}
	} else if (r_w >= 1){ //仅长出界
		i_w = window.innerWidth - 20;
		i_h = s.naturalHeight / r_w;
	} else if (r_h >= 1){ //仅宽出界
		i_w = s.naturalWidth / r_h;
		i_h = window.innerHeight - 20;
	} else { //完全未出界
		i_w = s.naturalWidth;
		i_h = s.naturalHeight;
	}
	t.style.marginLeft = "-" + i_w / 2 + "px";
	t.style.width = i_w + "px";
	t.style.marginTop = "-" + i_h / 2 + "px";
	t.style.height = i_h + "px";
}

function bgOnScroll(e){
	if (typeof e.stopPropergation != "undefined"){
		e.stopPropergation();
	} else {
		cancelBubbles = false;
	}
	if (typeof e.preventDefault != "undefined"){
		e.preventDefault();
	} else {
		e.returnValue = false;
	}
}

function imgOnClick(e){
	if (e.target.tagName != "IMG" || e.target.id == "imgview_img") return;
	bg = document.getElementById("imgview");
	bg.style.opacity="1";
	bg.style.display="block";
	img = document.getElementById("imgview_img");
	img.setAttribute("src", e.target.getAttribute("src"));
	resizeBgImg(e.target, img);
	bindEvent($(document), "mousewheel", bgOnScroll);
	bindEvent($(document), "DOMMouseScroll", bgOnScroll);
};

function windowOnResize(e){
	if (document.getElementById("imgview").style.display=="") return;
	img = document.getElementById("imgview_img");
	resizeBgImg(img, img);	
}

function bgOnClick(e){
	bg = document.getElementById("imgview");
	unbindEvent(bg, "mousewheel", bgOnScroll);
	unbindEvent(bg, "DOMMouseScroll", bgOnScroll);
	bg.style.opacity="";
	setTimeout(function(e){
		bg.style.display="";
	}, 200);
}

pics = document.getElementsByTagName("img");
for (i = 0;i < pics.length; ++i)
	bindEvent(pics[i],"click",imgOnClick);
bindEvent(document.getElementById("imgview"), "click", bgOnClick);
bindEvent(window, "resize", windowOnResize);
