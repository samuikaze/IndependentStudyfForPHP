<?php
    require_once 'admin/config.ini.php';
    require_once 'templates/functions.php';
    $usebrowser = get_browser_name($_SERVER['HTTP_USER_AGENT']);
    //$self = substr($_SERVER['PHP_SELF'], $webdirstrnum);
    //網站後台
    if (isset($type) == true && $type == "important"){
        switch (isset($_COOKIE['sid'])){
            case true:                                        //sid的cookie存在
                $sid = $_COOKIE['sid'];
                $sql = mysqli_query($connect, "SELECT * FROM sessions WHERE sessionID = '$sid'");
                $sessiondata = mysqli_fetch_array($sql, MYSQLI_BOTH);
                if ($sessiondata['sessionID'] != $_COOKIE['sid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                        session_start();
                        session_unset();
                        session_destroy();
                        setcookie("user", "", time()-3600);
                        setcookie("sid", "", time()-3600);
                        setcookie("auth", "", time()-3600);
                        // 網站的後台管理登入資訊有誤
                        mysqli_close($connect);
                        $refurl = "../member.php?action=login&loginErrType=4&refer=" . urlencode($self); 
                        header("Location: $refurl");
                        exit;
                }
                if ($sessiondata['sessionID'] == $_COOKIE['sid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                        session_start();
                        $username = $sessiondata['userName'];
                        $newsid = session_id();
                        $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` = '$username'");
                        $suserdata = mysqli_fetch_array($sql, MYSQLI_BOTH);
                        $_SESSION['auth'] = "true";
                        $_SESSION['user'] = $suserdata['userNickname'];
                        $_SESSION['uid'] = $username;
                        $_SESSION['priv'] = $suserdata['userPriviledge'];
                        $ltime = date("Y-m-d H:i:s");
                        $iprmtaddr = (empty($_SERVER['REMOTE_ADDR'])) ? "" : $_SERVER['REMOTE_ADDR'];
                        $ipXFwFor = (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? "" : $_SERVER['HTTP_X_FORWARDED_FOR'];
                        $iphttpvia = (empty($_SERVER['HTTP_VIA'])) ? "" : $_SERVER['HTTP_VIA'];
                        $iphttpcip = (empty($_SERVER["HTTP_CLIENT_IP"])) ? "" : $_SERVER["HTTP_CLIENT_IP"];
                        if ($sessiondata['ipRmtAddr'] != $iprmtaddr || $sessiondata['ipXFwFor'] != $ipXFwFor || $sessiondata['ipHttpVia'] != $iphttpvia || $sessiondata['ipHTTPCIP'] != $iphttpcip){ //IP不同就更新資料
                            $liprmtaddr = $sessiondata['ipRmtAddr'];
                            $lipxfwfor = $sessiondata['ipXFwFor'];
                            $liphttpvia = $sessiondata['ipHttpVia'];
                            $liphttpcip = $sessiondata['ipHTTPCIP'];
                            mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid', `useBrowser` = '$usebrowser', `ipRmtAddr` = '$iprmtaddr', `ipXFwFor` = '$ipXFwFor', `ipHttpVia` = '$iphttpvia', `ipHTTPCIP` = '$iphttpcip', `lastipRmtAddr` = '$liprmtaddr', `lastipXFwFor` = '$lipxfwfor', `lastipHttpVia` = '$liphttpvia', `lastipHTTPCIP`='$liphttpcip' WHERE `sessionID` = '$newsid'");                //修改這次IP和上次IP
                        }else{
                            mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid' WHERE `sessionID` = '$sid'");
                        }
                        setcookie("user", $username, time()+2592000);
                        setcookie("sid", $newsid, time()+2592000);
                        setcookie("auth", "true", time()+2592000);
                }
                break;
            default:                                        //sid的cookie不存在
                setcookie("user", "", time()-3600);
                setcookie("auth", "", time()-3600);
                session_start();
                session_unset();
                session_destroy();
                session_start();
                mysqli_close($connect);
                $refurl = "../member.php?action=relogin&loginErrType=5&refer=" . urlencode($self);
                header("Location: $refurl");
                exit;
                break;
        }
    }else{
        //網站前台
        switch (isset($_COOKIE['sid'])){
            case True:                                        //sid的cookie存在
                $sid = $_COOKIE['sid'];
                $sql = mysqli_query($connect, "SELECT * FROM `sessions` WHERE `sessionID` = '$sid'");
                $sessiondata = mysqli_fetch_array($sql, MYSQLI_BOTH);
                if ($sessiondata['sessionID'] != $_COOKIE['sid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                        session_start();
                        session_unset();
                        session_destroy();
                        setcookie("user", "", time()-3600);
                        setcookie("sid", "", time()-3600);
                        setcookie("auth", "", time()-3600);
                        session_start();
                }
                if ($sessiondata['sessionID'] == $_COOKIE['sid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                        session_start();
                        $username = $sessiondata['userName'];
                        $newsid = session_id();
                        $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` = '$username'");
                        $suserdata = mysqli_fetch_array($sql, MYSQLI_BOTH);
                        $_SESSION['auth'] = "true";
                        $_SESSION['user'] = $suserdata['userNickname'];
                        $_SESSION['uid'] = $username;
                        $_SESSION['priv'] = $suserdata['userPriviledge'];
                        // 取得通知
                        $notifySql = mysqli_query($connect, "SELECT * FROM `notifications` WHERE `notifyTarget` = '$username' ORDER BY `notifyTime` DESC");
                        $notifynums = mysqli_num_rows($notifySql);
                        $notifyunreadnums = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `notifications` WHERE `notifyTarget` = '$username' AND `notifyStatus` = 'u'"));
                        $ltime = date("Y-m-d H:i:s");
                        $iprmtaddr = (empty($_SERVER['REMOTE_ADDR'])) ? "" : $_SERVER['REMOTE_ADDR'];
                        $ipXFwFor = (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? "" : $_SERVER['HTTP_X_FORWARDED_FOR'];
                        $iphttpvia = (empty($_SERVER['HTTP_VIA'])) ? "" : $_SERVER['HTTP_VIA'];
                        $iphttpcip = (empty($_SERVER['HTTP_CLIENT_IP']))? "" : $_SERVER['HTTP_CLIENT_IP'];
                        if ($sessiondata['ipRmtAddr'] != $iprmtaddr || $sessiondata['ipXFwFor'] != $ipXFwFor || $sessiondata['ipHttpVia'] != $iphttpvia || $sessiondata['ipHTTPCIP'] != $iphttpcip){ //IP不同就更新資料
                            $liprmtaddr = $sessiondata['ipRmtAddr'];
                            $lipxfwfor = $sessiondata['ipXFwFor'];
                            $liphttpvia = $sessiondata['ipHttpVia'];
                            $liphttpcip = $sessiondata['ipHTTPCIP'];
                            mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid', `useBrowser` = '$usebrowser', `ipRmtAddr` = '$iprmtaddr', `ipXFwFor` = '$ipXFwFor', `ipHttpVia` = '$iphttpvia', `ipHTTPCIP` = '$iphttpcip', `lastipRmtAddr` = '$liprmtaddr', `lastipXFwFor` = '$lipxfwfor', `lastipHttpVia` = '$liphttpvia', `lastipHTTPCIP` = '$liphttpcip' WHERE `sessionID` = '$newsid'");                //修改這次IP和上次IP
                        }else{
                            mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid' WHERE `sessionID` = '$sid'");
                        }
                        setcookie("user", $username, time()+2592000); //設定cookie一個月後過期
                        setcookie("sid", $newsid, time()+2592000);
                        setcookie("auth", "true", time()+2592000);
                }
                break;
            default:                                          //sid的cookie不存在
                setcookie("user", "", time()-3600);
                setcookie("auth", "", time()-3600);
                session_start();
                session_unset();
                session_destroy();
                session_start();
                break;
        }
    }
?>