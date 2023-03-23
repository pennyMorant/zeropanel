//获取cookie
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
    }
    return "";
}

if (getCookie('language') != '') {
    var language_type = getCookie('language');
} else if (navigator.language != '') {
    var language_type = navigator.language;
} else {
    var language_type = "en-US";
}

//get left date
var showtime = function () {
    var nowtime = new Date(),  //获取当前时间
        endtime = new Date(user_class_expired_time);  //定义结束时间
    var lefttime = endtime.getTime() - nowtime.getTime(),  //距离结束时间的毫秒数
        leftd = Math.floor(lefttime/(1000*60*60*24)),  //计算天数
        lefth = Math.floor(lefttime/(1000*60*60)%24),  //计算小时数
        leftm = Math.floor(lefttime/(1000*60)%60),  //计算分钟数
        lefts = Math.floor(lefttime/1000%60);  //计算秒数
   
    return leftd + "天 " + lefth + "小时 " + leftm + "分 " + lefts + "秒";  //返回倒计时的字符串
}
$(document).ready(function(){
var div = document.getElementById("user_class_expired_time");
if (div != null) {
    setInterval (function () {
        div.innerHTML = showtime();
    }, 1000);  //反复执行函数本身
}
})

// change language
function change_language(lang_type) {
    document.cookie = "language=" + lang_type;
    location.reload();
}