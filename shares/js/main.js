/*
 * CodeWriter SumaRoder(2023.9.28)
 * Copyright 2023 SumaRoder
 * Licensed under MIT
 */

var development_name = "SumaRoder";

var ua = window.navigator.userAgent;
var chrome = ua.match(/Chrome\/([\d.]+)/) || ua.match(/CriOS\/([\d.]+)/);

if (chrome) {
    var chromeVersion = parseInt(chrome[1].split('.')[0], 10);
    
    if (chromeVersion < 80) {
        alert("您正在使用的浏览器版本过低，为了您的最佳体验，请先升级浏览器。");
        window.location.href="http://support.dmeng.net/upgrade-your-browser.html?referrer="+encodeURIComponent(window.location.href);
    }
}

var msie = ua.indexOf('MSIE ');
var trident = ua.indexOf('Trident/');

if (msie > 0 || trident > 0) {
    var ieVersion = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    
    if (ieVersion < 11) {
        alert("您正在使用的浏览器版本过低，为了您的最佳体验，请先升级浏览器。");
        window.location.href="http://support.dmeng.net/upgrade-your-browser.html?referrer="+encodeURIComponent(window.location.href);
    }
}

document.getElementById("openBtn").addEventListener("click", function() {
  var openBtn = document.getElementById("openBtn");
  var popupa = document.getElementById("popup");
  var popupaContent = document.querySelector(".popup-content");

  // 计算按钮位置
  var btnRect = openBtn.getBoundingClientRect();
  var btnTop = btnRect.top + btnRect.height;
  var btnLeft = btnRect.left;
  
  // 设置弹出窗口位置
  popupaContent.style.top = btnTop + "px";
  popupaContent.style.left = btnLeft + "px";
  
  popupa.style.display = "block";
});

/*var menuWindow = document.querySelector(".bg");
menuWindow.style.display = "none";

var menuContent = document.querySelector(".menuWindow-content");
menuContent.style.display = "none";*/

/*document.getElementById("menu").addEventListener("click", function() {
  var popup = document.querySelector(".bg");
  popup.style.display = "block";
});*/

document.addEventListener("click", function(event) {
  var popupa = document.getElementById("popup");
  var popupaContent = document.getElementById("popup .popup-content");
  var openBtn = document.getElementById("openBtn");
  
  if (event.target == popupa && event.target !== popupaContent) {
    popupa.style.display = "none";
  }
  
  /*var popup = document.querySelector(".bg");
  var popup_content = document.getElementById("menuWindow .menuWindow-content");
  var menu = document.getElementById("menu");
  
  if (event.target == popup && event.target !== popup_content) {
    popup.style.display = "none";
  }*/
});

document.getElementById("lightModeBtn").addEventListener("click", function() {
  document.body.classList.remove("dark-mode");
  document.body.classList.add("light-mode");
  $.cookie("theme", "light-mode");
});

document.getElementById("darkModeBtn").addEventListener("click", function() {
  document.body.classList.remove("light-mode");
  document.body.classList.add("dark-mode");
  $.cookie("theme", "dark-mode");
});
function IsPhone() {
    //获取浏览器navigator对象的userAgent属性（浏览器用于HTTP请求的用户代理头的值）
    var info = navigator.userAgent;
    //通过正则表达式的test方法判断是否包含“Mobile”字符串
    var isPhone = /mobile/i.test(info);
    //如果包含“Mobile”（是手机设备）则返回true
    return isPhone;
}
function showindexyyes(){
    var window_bg = document.getElementsByClassName("window_bg")[0];
	window_bg.style.display = "none";
}
//点击弹窗外部可隐藏弹窗
window.onclick = function(event){
    let window_bg = document.getElementsByClassName("window_bg")[0];
	if(event.target == window_bg){
		event.target.style.display = "none";
	}
}
// 点击按钮显示弹窗
function showindex(){
    var window_bg = document.getElementsByClassName("window_bg")[0];
	window_bg.style.display = "block";
}
//["一个人，要走多远的距离，才能在时光的尽头，追回最初的自己。"];
function IndexInput(classname,arr)
{
    const text = document.querySelector('.'+classname);
    const txt = arr;
    var index = 0;
    var xiaBiao = 0;
    var huan = true;
    setInterval(function(){
        if(huan){      
            text.innerHTML = txt[xiaBiao].slice(0,++index);
            console.log(index);
        }
        else{
            text.innerHTML = txt[xiaBiao].slice(0,index--);
           console.log(index);
        }
        if(index==txt[xiaBiao].length+0.5)
        {
            huan = false;
        }
        else if(index<0)
        {
            index = 0;
            huan = true;
            xiaBiao++;
            if(xiaBiao>=txt.length)
            {
                xiaBiao=0; 
            }
        }
    },200)
}
