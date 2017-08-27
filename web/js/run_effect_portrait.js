function changesize(){
        changeScale(0.37,"IFRAME","template2");
        changesizeTable(0.7,"TABLE");
        changesizeTable(0.6,"TBODY");
    }

function changesizetag(a,TagName){ 
//a là font chuẩn
//c là tên của ID chứa gía trị, vd: 'mon1'
var arr = document.getElementsByTagName(TagName);
var numbertag = arr.length;
for (var i = 0; i<numbertag; i++) {
var x = $(window).width();
if (x<1920) {
    var k = 1920/1920*a;
    var m = document.getElementsByTagName(TagName);
   m[i].style.fontSize = (k.toString())+"px";
    }
}   
}

function changeMarginTop(a,class_name){ 
var arr=document.getElementsByClassName(class_name);
var numberclass = $('.'+class_name).length;
for (var i = 0; i<numberclass; i++) {
var x = $(window).width();
if (x<1920) {
    var k = x/1920*a;
    var m = document.getElementsByClassName(class_name);
   m[i].style.marginTop = (k.toString())+"px";
    }
}   
}

function changeScale(a,TagName, class_name){ 
    //a là font chuẩn
    //c là tên của ID chứa gía trị, vd: 'mon1'
    var arr = document.getElementsByTagName(TagName);
    var numbertag = arr.length;
    for (var i = 0; i<numbertag; i++) {
        var x = $(window).width();
        if (x<1920) {
            var k = x/1920*a;
            var marginLeft = -90+465*a*(x/1920-1);
            var marginTop = -255+665*a*(x/1920-1);
            var m = document.getElementsByTagName(TagName);
            m[i].style.marginLeft = (marginLeft.toString())+"px";
            m[i].style.marginTop = (marginTop.toString())+"px";
            $('.'+class_name+">iframe").css({ transform: 'scale('+ k.toString()+')' });
        }
    }   
}
function changesizeTable(a,TagName){ 
    //a là font chuẩn
    //c là tên của ID chứa gía trị, vd: 'mon1'
    var arr = document.getElementsByTagName(TagName);
    var numbertag = arr.length;
    for (var i = 0; i<numbertag; i++) {
    var x = $(window).height();

    var k = x*a;
    var m = document.getElementsByTagName(TagName);
    m[i].style.height = (k.toString())+"px";
    }   
}