/*
 * LoadingAnimation
 * Require PreloadJS 1.0.0
**/

var manifest = new Array();
var preload;
var percent;
var filePercent;
var loadText = ["頁面載入中，F5 連打不是好文明喔！",
                "最新消息可以掌握團隊想告知的訊息！",
                "覺得滿腔熱血想分享攻略？討論區是你的好選擇！",
                "對於製作遊戲有興趣嗎？現在立刻加入我們！"];
var temp = Math.random() * loadText.length;

function loadProgress(){
    // 初始化
    //initCheck();
    $('.loadHint').html(loadText[Math.floor(temp)]);
    
    //關閉其他 preload 線程
    if (preload != null){
        preload.close();
    }
     
    //資料收集 img → script → css
    //遍歷所有 img 標籤
    $('img').each(function(){
        manifest.push($(this).attr('src'));
    });
    if ($('.banner').css('backgroundImage') != null){           // 頭部 BANNER 圖片
        var bannerBGImg = $('.banner').css('backgroundImage');
        bannerBGImg = bannerBGImg.replace('url(','').replace(')','').replace(/\"/gi, "").replace("/css", "");
        manifest.push(bannerBGImg);
    }

    //遍歷所有 css 和 icon 檔案
    $('link').each(function(){
        if ($(this).attr('rel') == 'stylesheet' || $(this).attr('rel') == 'shortcut icon' && $(this).attr('href') != null){
            manifest.push($(this).attr('href'));
        }
    });
    
    // 正式開始載入
    // LoadQueue() 中的 true 表示優先使用 XHR 方法載入
    preload = new createjs.LoadQueue(true);
    
    loadEvent();
    
    preload.on("progress", progressEventListener, this);
    preload.on("complete", completeLoadingProcess, this);
    preload.setMaxConnections(5);
}

// 載入事件
function loadEvent(){
    preload.loadManifest(manifest);
}

// 整體進度事件
function progressEventListener(event){
    // 文字淡入淡出效果
    //setTimeout($('.loadHint').fadeTo(500, 0.6).fadeTo(500, 1.0) ,500);
    // 「event.progress」值為 0 ~ 1。
    percent = event.progress * 100 + '%';
    ariaValue = event.progress * 100;
    $('div.progress-bar').attr('aria-valuenow', ariaValue);
    $('div.progress-bar').css('width', percent);
    //adjustVerticalAlignMiddle();
}

// 載入完成
function completeLoadingProcess(event){
    $('.loadscr').delay(300).fadeOut('slow');
    $('.pageWrap').delay(300).fadeIn('slow');
    //console.log("載入完成！");
}


$(document).scroll(function(){
    //取得 #home 的元素高度後減 200 像素
    var pageHeight = $('#home').height() - 100;
    if($(this).scrollTop() > pageHeight){   
        $('header').css({
           "background":"rgba(196, 134, 0, 0.75)",
           "borderBottom":"2px solid rgba(255, 255, 255, 0.75)"
        });
        $('.colorTran').css({
            "color":"white"
        });
        $('.main-w3ls-logo').find('a').css({
            "color":"white"
        });
    } else {
        $('header').css({
           "background":"rgba(0, 0, 0, 0.35)",
           "borderBottom":"transparent"
        });
        /*$('.colorTran').css({
            "color":"black"
        });
        $('.main-w3ls-logo').find('a').css({
            "color":"black"
        });*/
    }
});

//登入／註冊 DIV 氣泡
$(document).ready(function(){
    var loginActionID = 0;
    var regActionID = 0;
    $('#loginForm').on("click", function(){
        if(loginActionID == 0){
            $('div.hp_login').fadeIn(200);
            loginActionID = 1;
            return false;
        }
    });

    $('#register').on("click", function(){
        if(regActionID == 0){
            $('div.hp_register').fadeIn(200);
            regActionID = 1;
            return false;
        }
    });
    
    $('body').on("click", function(e2) {
        if ($(e2.target).parents("#login").length == 0 && e2.target.id != "login" && loginActionID != 0 || regActionID != 0) {
            if(loginActionID != 0 && regActionID == 0){
                $('.hp_login').fadeOut(200);
                loginActionID = 0   
            }else if(loginActionID != 0 && regActionID != 0 && $(e2.target).parents("#reg").length == 0 && e2.target.id != "reg"){
                $('.hp_register').fadeOut(200);
                regActionID = 0;
            }
            if(e2.target.id != "reg-submit"){
                return false;
            }
        }
    });
});



//禁止 class=active 的連結有反應
$(document).ready(function(){
    $('a.active').on("click", function(){
        return false;
    });
});

//修改高度用
/*$(document).scroll(function(){
    var homeHeight = $('#headerForCalc').outerHeight();
    var _this = $('div.top-main-banner-item').find('img').height();
    $('div.top-main-banner-wrapper').css("paddingTop", ($('div#banner').height() + 10) + homeHeight);
    $('div.banner').css("height", homeHeight + _this);
});*/

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
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: false,
        variableWidth: false,
        prevArrow: $(".navi-allow.prev"),
        nextArrow: $(".navi-allow.next"),
    });
});

//捲到最上層
$(document).ready(function(){
    $('.toTop').on('click', function(){
        $('html,body').animate({ scrollTop: 0 }, 300);
    });
    $(window).on('scroll', function(){
        if ($(this).scrollTop() > 50){
            $('img.toTop').fadeIn(200);
        } else {
            $('img.toTop').stop().fadeOut(200);
        }
    });
});

// URL 變更
/*$(document).ready(function(){
    $('a.urlPush').on('click', function(){
        // 先判斷有沒有GET值，沒有就加問號，然後取得 URL
        // $(location).attr('search').substr(0, 1) != '?'
        var url = $(this).attr('href').replace('#', "?type=");

        // 推送 URL 至瀏覽器
        window.history.pushState(null, null, url);
    });
});*/