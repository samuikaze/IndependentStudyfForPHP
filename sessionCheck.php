<?php
     require_once 'admin/config.ini.php';
     $connect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DBNAME, $DB_PORT);
     mysqli_query($connect, "SET NAMES 'utf8'");
     mysqli_query($connect, "SET CHARACTER_SET_CLIENT=utf8");
     mysqli_query($connect, "SET CHARACTER_SET_RESULTS=utf8"); 
     //$self = substr($_SERVER['PHP_SELF'], $webdirstrnum);
     //網站後台
     if (isset($type) == true && $type == "important"){
          switch (isset($_COOKIE['sid'])){
               case true:                                        //sid的cookie存在
                    $sid = $_COOKIE['sid'];
                    $sql = mysqli_query($connect, "SELECT * FROM sessions WHERE sessionID = '$sid'");
                    $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
                    if ($row['sessionID'] != $_COOKIE['sid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                         session_start();
                         session_unset();
                         session_destroy();
                         setcookie("user", "", time()-3600);
                         setcookie("sid", "", time()-3600);
                         setcookie("auth", "", time()-3600);
                         mysqli_close($connect);
                         $refurl = 'member.php?action=login&refer=' . $self . "?" . $_SERVER['QUERY_STRING'];
                         header("Location: $refurl");
                         exit;
                    }
                    if ($row['sessionID'] == $_COOKIE['sid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                         session_start();
                         $username = $row['userName'];
                         $newsid = session_id();
                         $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` = '$username'");
                         $datarow = mysqli_fetch_array($sql, MYSQLI_BOTH);
                         $_SESSION['auth'] = "true";
                         $_SESSION['user'] = $datarow['userNickname'];
                         $_SESSION['uid'] = $username;
                         $_SESSION['priv'] = $datarow['userPriviledge'];
                         $ltime = date("Y-m-d H:i:s");
                         $iprmtaddr = $_SERVER['REMOTE_ADDR'];
                         $ipXFwFor = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "";
                         $iphttpvia = (isset($_SERVER['HTTP_VIA'])) ? $_SERVER['HTTP_VIA'] : "";
                         mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid' WHERE `sessionID` = '$sid'");
                         if ($row['ipRmtAddr'] != $iprmtaddr || $row['ipXFwFor'] != $ipXFwFor || $row['ipHttpVia'] != $iphttpvia){ //IP不同就更新資料
                              $liprmtaddr = $row['ipRmtAddr'];
                              $lipxfwfor = $row['ipXFwFor'];
                              $liphttpvia = $row['ipHttpVia'];
                              mysqli_query($connect, "UPDATE `sessions` SET `ipRmtAddr` = '$iprmtaddr', `ipXFwFor` = '$ipXFwFor', `ipHttpVia` = '$iphttpvia', `lastipRmtAddr` = '$liprmtaddr', `lastipXFwFor` = '$lipxfwfor', `lastipHttpVia` = '$liphttpvia' WHERE `sessionID` = '$newsid'");                //修改這次IP和上次IP
                         }
                         setcookie("user", $username, time()+2592000);
                         setcookie("sid", $newsid, time()+2592000);
                         setcookie("auth", "true", time()+2592000);
                         mysqli_close($connect);
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
                    $refurl = 'member.php?action=login&refer=' . $self . "?" . $_SERVER['QUERY_STRING'];
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
                    $row = mysqli_fetch_array($sql, MYSQLI_BOTH);
                    if ($row['sessionID'] != $_COOKIE['sid'] || $_COOKIE['auth'] != "true"){  //資訊不正確
                         session_start();
                         session_unset();
                         session_destroy();
                         setcookie("user", "", time()-3600);
                         setcookie("sid", "", time()-3600);
                         setcookie("auth", "", time()-3600);
                         session_start();
                         mysqli_close($connect);
                    }
                    if ($row['sessionID'] == $_COOKIE['sid'] && $_COOKIE['auth'] == "true"){  //資訊正確
                         session_start();
                         $username = $row['userName'];
                         $newsid = session_id();
                         $sql = mysqli_query($connect, "SELECT * FROM `member` WHERE `userName` = '$username'");
                         $datarow = mysqli_fetch_array($sql, MYSQLI_BOTH);
                         $_SESSION['auth'] = "true";
                         $_SESSION['user'] = $datarow['userNickname'];
                         $_SESSION['uid'] = $username;
                         $_SESSION['priv'] = $datarow['userPriviledge'];
                         $ltime = date("Y-m-d H:i:s");
                         $iprmtaddr = $_SERVER['REMOTE_ADDR'];
                         $ipXFwFor = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "";
                         $iphttpvia = (isset($_SERVER['HTTP_VIA'])) ? $_SERVER['HTTP_VIA'] : "";
                         mysqli_query($connect, "UPDATE `sessions` SET `loginTime` = '$ltime', `sessionID` = '$newsid' WHERE `sessionID` = '$sid'");
                         if ($row['ipRmtAddr'] != $iprmtaddr || $row['ipXFwFor'] != $ipXFwFor || $row['ipHttpVia'] != $iphttpvia){ //IP不同就更新資料
                              $liprmtaddr = $row['ipRmtAddr'];
                              $lipxfwfor = $row['ipXFwFor'];
                              $liphttpvia = $row['ipHttpVia'];
                              mysqli_query($connect, "UPDATE `sessions` SET `ipRmtAddr` = '$iprmtaddr', `ipXFwFor` = '$ipXFwFor', `ipHttpVia` = '$iphttpvia', `lastipRmtAddr` = '$liprmtaddr', `lastipXFwFor` = '$lipxfwfor', `lastipHttpVia` = '$liphttpvia' WHERE `sessionID` = '$newsid'");                //修改這次IP和上次IP
                         }
                         setcookie("user", $username, time()+2592000); //設定cookie一個月後過期
                         setcookie("sid", $newsid, time()+2592000);
                         setcookie("auth", "true", time()+2592000);
                         mysqli_close($connect);
                    }
                    break;
               default:                                          //sid的cookie不存在
                    setcookie("user", "", time()-3600);
                    setcookie("auth", "", time()-3600);
                    session_start();
                    session_unset();
                    session_destroy();
                    session_start();
                    mysqli_close($connect);
                    break;
          }
     }
?>