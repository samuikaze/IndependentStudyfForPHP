$(document).scroll(function(){
    //取得 #home 的元素高度後減 200 像素
    var pageHeight = $('#home').height() - 200;
    if($(this).scrollTop() > pageHeight){   
        $('header').css({
           "background":"rgba(196, 134, 0, 0.75)",
           "borderBottom":"2px solid rgba(255, 255, 255, 0.75)"
        });
        $('ul.navbar-nav').find('a').css({
            "color":"white"
        });
        $('.main-w3ls-logo').find('a').css({
            "color":"white"
        });
    } else {
        $('header').css({
           "background":"transparent",
           "borderBottom":"transparent"
        });
        $('ul.navbar-nav').find('a').css({
            "color":"black"
        });
        $('.main-w3ls-logo').find('a').css({
            "color":"black"
        });
    }
});

//修改高度用
$(document).scroll(function(){
    var homeHeight = $('#headerForCalc').outerHeight();
    var _this = $('div.top-main-banner-item').find('img').height();
    $('div.top-main-banner-wrapper').css("paddingTop", ($('div#banner').height() + 10) + homeHeight);
    $('div.banner').css("height", homeHeight + _this);
});

jQuery(document).ready(function ($) {
    $(".scroll").click(function (event) {
        event.preventDefault();
        $('html,body').animate({
            scrollTop: $(this.hash).offset().top
        }, 1000);
    });
});

// 圖片輪播 slick.js
$(document).ready(function(){
    $('.top-main-banner').slick({
        draggable: true,
        infinite: true,
        speed: 600,
        autoplaySpeed: 2000,
        autoplay: true,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        variableWidth: true,
        prevArrow: $(".navi-allow.prev"),
        nextArrow: $(".navi-allow.next"),
    });
});

/*
 * LoadingAnimation
 * Require PreloadJS 1.0.0
**/

var manifest = new Array();
var preload;
var percent;
var filePercent;

function loadProgress(){
    // 初始化
    initCheck();
    var i = 0;
    
    //關閉其他 preload 線程
    if (preload != null){
        preload.close();
    }
     
    //資料收集 img → script → css
    //遍歷所有 img 標籤
    $('img').each(function(){
        manifest[i] = $(this).attr('src');
        i = i + 1;
    });
    if ($('body').css('backgroundImage') != null){              //最後從 CSS 把背景圖的 URL 加進去
        var bodyBGImg = $('body').css('backgroundImage');
        bodyBGImg = bodyBGImg.replace('url(','').replace(')','').replace(/\"/gi, "");
        manifest[manifest.length] = bodyBGImg;
    }
    
    //遍歷所有 script 標籤
    $('script').each(function(){
        if (scriptEach != true){           //第一次執行變數 i 宣告
            var i = manifest.length;
            var scriptEach = true;
        }
        if ($(this).attr('src') != null || $(this).attr('src') != "js/loadAni.js"){     // js 檔不包含本檔案
            manifest[i] = $(this).attr('src');
        }
        i = i + 1;
    });
    
    //遍歷所有 css 和 icon 檔案
    $('link').each(function(){
        if (linkStylesheetEach != true){   //第一次執行變數 i 宣告
            var i = manifest.length;
            var linkStylesheetEach = true;
        }
        if ($(this).attr('rel') == 'stylesheet' || $(this).attr('rel') == 'shortcut icon' && $(this).attr('href') != null){
            manifest[i] = $(this).attr('href');
        }
        i = i + 1;
    });
    
    
    // 正式開始載入
    // LoadQueue() 中的 true 表示優先使用 XHR 方法載入
    preload = new createjs.LoadQueue(true);
    
    loadEvent();
    
    preload.on("progress", progressEventListener, this);
    preload.on("complete", completeLoadingProcess, this);
    preload.setMaxConnections(5);
}

// 載入完成
function completeLoadingProcess(event){
    $('#loadScr').fadeOut('slow');
    $('#holePageCont').fadeIn('slow');
}