<?php
     require_once 'admin/config.ini.php';
     $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME);
     mysqli_query($connect, "SET NAMES 'utf8'");
     mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
     mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8"); 
     //$self = substr($_SERVER['PHP_SELF'], $webdirstrnum);
     //網站後台
     if (isset($type) == true && $type == "important"){
          switch (isset($_COOKIE['hsid'])){
               case true:                                        //hsid的cookie存在
                    $hsid = $_COOKIE['hsid'];
                    $sql = mysql_query("SELECT * FROM sessions WHERE hashSID = '$hsid'");
                    $row = @mysql_fetch_array($sql, MYSQL_BOTH);
                    if ($row['hashSID'] != $_COOKIE['hsid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                         session_start();
                         session_unset();
                         session_destroy();
                         $debugMde = 0;
                         setcookie("user", "", time()-3600);
                         setcookie("hsid", "", time()-3600);
                         setcookie("auth", "", time()-3600);
                         mysql_close($connect);
                         $refurl = 'member.php?action=login&refer=' . $self . "?" . $_SERVER['QUERY_STRING'];
                         header("Location: $refurl");
                         exit;
                    }
                    if ($row['hashSID'] == $_COOKIE['hsid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                         session_start();
                         $id = $row['userID'];
                         $dbm = mysql_query("SELECT debugMode FROM member WHERE id = '$id'");
                         $dbmData = mysql_fetch_assoc($dbm);
                         if($dbmData['debugMode'] != 0){
                              $debugMde = 1;
                         }else{
                              $debugMde = 0;
                         }
                         $sid = session_id();
                         $newhsid = hash("sha512", $sid);
                         $sql = mysql_query("SELECT * FROM member WHERE id = '$id'");
                         $datarow = @mysql_fetch_array($sql, MYSQL_BOTH);
                         $_SESSION['auth'] = "true";
                         $_SESSION['user'] = $datarow['nickname'];
                         $_SESSION['uid'] = $id;
                         date_default_timezone_set("Asia/Taipei");
                         $ltime = date("Y-m-d H:i:s");
                         $iprmtaddr = $_SERVER['REMOTE_ADDR'];
                         $ipXFwFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
                         $iphttpvia = $_SERVER['HTTP_VIA'];
                         mysql_query("UPDATE sessions SET loginTime = '$ltime', sessionID = '$sid', hashSID = '$newhsid' WHERE hashSID = '$hsid'");
                         if ($row['ipRmtAddr'] != $iprmtaddr || $row['ipXFwFor'] != $ipXFwFor || $row['ipHttpVia'] != $iphttpvia){ //IP不同就更新資料
                              $liprmtaddr = $row['ipRmtAddr'];
                              $lipxfwfor = $row['ipXFwFor'];
                              $liphttpvia = $row['ipHttpVia'];
                              mysql_query("UPDATE sessions SET ipRmtAddr = '$iprmtaddr', ipXFwFor = '$ipXFwFor', ipHttpVia = '$iphttpvia', lastipRmtAddr = '$liprmtaddr', lastipXFwFor = '$lipxfwfor', lastipHttpVia = '$liphttpvia' WHERE hashSID = '$newhsid'");                //修改這次IP和上次IP
                         }
                         setcookie("user", $id, time()+2592000);
                         setcookie("hsid", $newhsid, time()+2592000);
                         setcookie("auth", "true", time()+2592000);
                         mysql_close($connect);
                    }
                    break;
               default:                                        //hsid的cookie不存在
                    $debugMde = 0;
                    setcookie("user", "", time()-3600);
                    setcookie("auth", "", time()-3600);
                    session_start();
                    session_unset();
                    session_destroy();
                    session_start();
                    mysql_close($connect);
                    $refurl = 'member.php?action=login&refer=' . $self . "?" . $_SERVER['QUERY_STRING'];
                    header("Location: $refurl");
                    exit;
                    break;
          }
     }else{                                                                           //網站前台
          switch (isset($_COOKIE['hsid'])){
               case true:                                        //hsid的cookie存在
                    $hsid = $_COOKIE['hsid'];
                    $sql = mysql_query("SELECT * FROM sessions WHERE hashSID = '$hsid'");
                    $row = @mysql_fetch_array($sql, MYSQL_BOTH);
                    if ($row['hashSID'] != $_COOKIE['hsid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                         session_start();
                         session_unset();
                         session_destroy();
                         $debugMde = 0;
                         setcookie("user", "", time()-3600);
                         setcookie("hsid", "", time()-3600);
                         setcookie("auth", "", time()-3600);
                         session_start();
                         mysql_close($connect);
                    }
                    if ($row['hashSID'] == $_COOKIE['hsid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                         session_start();
                         $id = $row['userID'];
                         $dbm = mysql_query("SELECT debugMode FROM member WHERE id = '$id'");
                         $dbmData = mysql_fetch_assoc($dbm);
                         if($dbmData['debugMode'] != 0){
                              $debugMde = 1;
                         }else{
                              $debugMde = 0;
                         }
                         $sid = session_id();
                         $newhsid = hash("sha512", $sid);
                         $sql = mysql_query("SELECT * FROM member WHERE id = '$id'");
                         $datarow = @mysql_fetch_array($sql, MYSQL_BOTH);
                         $_SESSION['auth'] = "true";
                         $_SESSION['user'] = $datarow['nickname'];
                         $_SESSION['id'] = $id;
                         date_default_timezone_set("Asia/Taipei");
                         $ltime = date("Y-m-d H:i:s");
                         $iprmtaddr = $_SERVER['REMOTE_ADDR'];
                         $ipXFwFor = $_SERVER['HTTP_X_FORWARDED_FOR'];
                         $iphttpvia = $_SERVER['HTTP_VIA'];
                         mysql_query("UPDATE sessions SET loginTime = '$ltime', sessionID = '$sid', hashSID = '$newhsid' WHERE hashSID = '$hsid'");
                         if ($row['ipRmtAddr'] != $iprmtaddr || $row['ipXFwFor'] != $ipXFwFor || $row['ipHttpVia'] != $iphttpvia){ //IP不同就更新資料
                              $liprmtaddr = $row['ipRmtAddr'];
                              $lipxfwfor = $row['ipXFwFor'];
                              $liphttpvia = $row['ipHttpVia'];
                              mysql_query("UPDATE sessions SET ipRmtAddr = '$iprmtaddr', ipXFwFor = '$ipXFwFor', ipHttpVia = '$iphttpvia', lastipRmtAddr = '$liprmtaddr', lastipXFwFor = '$lipxfwfor', lastipHttpVia = '$liphttpvia' WHERE hashSID = '$newhsid'");                //修改這次IP和上次IP
                         }
                         setcookie("user", $id, time()+2592000); //設定cookie一個月後過期
                         setcookie("hsid", $newhsid, time()+2592000);
                         setcookie("auth", "true", time()+2592000);
                         mysql_close($connect);
                    }
                    break;
               default:                                          //hsid的cookie不存在
                    setcookie("user", "", time()-3600);
                    setcookie("auth", "", time()-3600);
                    session_start();
                    session_unset();
                    session_destroy();
                    session_start();
                    mysql_close($connect);
                    $debugMde = 0;
                    break;
          }
     }
?>