<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="洛嬉遊戲 L.S. Games LSGames" />
<script type="application/x-javascript">
    addEventListener("load", function () {
        setTimeout(hideURLbar, 0);
    }, false);

    function hideURLbar() {
        window.scrollTo(0, 1);
    }
</script>
<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/PreloadJS.js"></script>   <!-- 載入動畫（修改中） -->
<script type="text/javascript" src="js/slick.min.js"></script>    <!-- 圖片輪播 -->
<script src="js/custom.js"></script>
<script src="js/main.js"></script>
<script src="js/bootstrap.js"></script>
<script src="ckeditor/ckeditor.js"></script>
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="css/style.min.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">
    /* Loading Screen */
    .loadscr {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100vw;
        height: 100vh;
        background-color: rgb(196, 134, 0);
        z-index: 999;
    }

    .loadscr .progress {
        width: 50%;
        margin: 15px auto;
    }

    .progress-bar {
        text-align: right !important;
    }

    .progress-bar > span {
        font-weight: bold;
        font-size: 1.2em;
        text-shadow: 0px 0px 5px #7fbbf1, 0px 0px 5px #7fbbf1, 0px 0px 5px #7fbbf1, 0px 0px 5px #7fbbf1, 0px 0px 5px #7fbbf1;
    }

    .loadscr .loadTitle {
        font-size: 3.5em;
        color: white;
        text-align: center;
        width: 100vw;
        padding-top: 30vh;
    }

    .loadscr .logo {
        width: 1.7em;
    }

    .loadscr .loadHint {
        width: 100%;
        text-align: center;
        font-size: 1.1em;
        color: white;
    }

    .pageWrap {
        display: none;
    }

    #progBar {
        width: 45%;
    }

    .sr-only {
        display: none;
    }

    @media(max-width:480px) {
        .loadscr .loadTitle {
            font-size: 3em;
            color: white;
            text-align: center;
            width: 100vw;
            padding-top: 30vh;
        }
        
        .loadscr .logo {
            width: 1.4em;
        }
        
        .loadscr .loadHint {
            width: 100%;
            text-align: center;
            font-size: 1em;
            color: white;
        }

        .loadscr .progress {
            width: 80%;
            margin: 15px auto;
        }
    }

    @media(max-width:568px) {
        .loadscr .loadTitle {
            font-size: 3em;
            color: white;
            text-align: center;
            width: 100vw;
            padding-top: 30vh;
        }
        
        .loadscr .logo {
            width: 1.4em;
        }
        
        .loadscr .loadHint {
            width: 100%;
            text-align: center;
            font-size: 1em;
            color: white;
        }

        .loadscr .progress {
            width: 90%;
            margin: 15px auto;
        }
    }

    @media(max-width:991px) {
        .loadscr .progress {
            width: 60%;
            margin: 15px auto;
        }
    }
</style>