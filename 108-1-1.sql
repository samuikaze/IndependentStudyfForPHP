-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2019 年 10 月 07 日 20:21
-- 伺服器版本： 10.3.16-MariaDB
-- PHP 版本： 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `id11139420_sksk108`
--

-- --------------------------------------------------------

--
-- 資料表結構 `bbsarticle`
--

CREATE TABLE `bbsarticle` (
  `articleID` int(255) NOT NULL COMMENT '回覆識別碼',
  `articleTitle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '回覆標題',
  `articleContent` varchar(5000) COLLATE utf8_unicode_ci NOT NULL COMMENT '回覆內容',
  `articleUserID` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '回覆者',
  `articleTime` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '回覆時間',
  `articleStatus` int(11) NOT NULL DEFAULT 0 COMMENT '回覆狀態',
  `articleEdittime` timestamp NULL DEFAULT NULL COMMENT '回文編輯時間',
  `articlePost` int(100) NOT NULL COMMENT '回覆隸屬貼文'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `bbsarticle`
--

INSERT INTO `bbsarticle` (`articleID`, `articleTitle`, `articleContent`, `articleUserID`, `articleTime`, `articleStatus`, `articleEdittime`, `articlePost`) VALUES
(1, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-08 18:22:48', 1, '2019-05-17 05:03:48', 1),
(2, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-08 18:22:48', 1, '2019-05-17 05:03:48', 1),
(3, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'user', '2019-05-08 18:22:48', 1, '2019-05-17 05:03:48', 1),
(9, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 12:56:04', 1, '2019-05-17 05:03:48', 1),
(10, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:53:30', 1, '2019-05-17 05:03:48', 1),
(11, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:53:39', 1, '2019-05-17 05:03:48', 1),
(12, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:53:56', 1, '2019-05-17 05:03:48', 1),
(13, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:54:05', 1, '2019-05-17 05:03:48', 1),
(14, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:54:15', 1, '2019-05-17 05:03:48', 1),
(15, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:54:24', 1, '2019-05-17 05:03:48', 1),
(16, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:54:37', 1, '2019-05-17 05:03:48', 1),
(17, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 13:56:40', 1, '2019-05-17 05:03:48', 1),
(18, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 14:17:20', 1, '2019-05-17 05:03:48', 13),
(20, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-13 14:45:55', 1, '2019-05-17 05:03:48', 15),
(21, '測試回覆', '<p>測試回覆...!!!~~~</p>\r\n', 'admin', '2019-05-13 15:00:07', 1, '2019-05-17 12:42:37', 16),
(22, '測試回覆', '<p>測試回覆...!!!</p>\r\n', 'admin', '2019-05-14 02:14:26', 1, '2019-05-17 05:03:48', 1),
(23, NULL, '123', 'admin', '2019-06-12 02:48:38', 0, NULL, 16);

-- --------------------------------------------------------

--
-- 資料表結構 `bbsboard`
--

CREATE TABLE `bbsboard` (
  `boardID` int(5) NOT NULL COMMENT '討論板 ID',
  `boardName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '討論板名稱',
  `boardImage` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.jpg' COMMENT '討論版圖片',
  `boardDescript` char(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '討論板描述',
  `boardCTime` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '討論板建立時間',
  `boardCreator` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '討論板建立者',
  `boardHide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '討論板是否隱藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `bbsboard`
--

INSERT INTO `bbsboard` (`boardID`, `boardName`, `boardImage`, `boardDescript`, `boardCTime`, `boardCreator`, `boardHide`) VALUES
(1, '戰國紛爭板', 'board-1.jpg', '作品一說明文字', '2019-05-06 05:02:55', '1', 0),
(2, '討論板二號', 'board-2.jpg', '作品二說明文字', '2019-05-06 05:02:55', '1', 0),
(3, '討論板三號', 'board-3.jpg', '作品三說明文字', '2019-05-06 05:03:23', '1', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `bbspost`
--

CREATE TABLE `bbspost` (
  `postID` int(255) NOT NULL COMMENT '貼文識別碼',
  `postTitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '貼文標題',
  `postType` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '貼文分類',
  `postContent` varchar(5000) COLLATE utf8_unicode_ci NOT NULL COMMENT '貼文內容',
  `postUserID` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '貼文者',
  `postTime` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '貼文時間',
  `lastUpdateUserID` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最後回覆者',
  `lastUpdateTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最後回文時間',
  `postStatus` int(11) NOT NULL DEFAULT 0 COMMENT '貼文狀態',
  `postEdittime` timestamp NULL DEFAULT NULL COMMENT '文章編輯時間',
  `postBoard` int(100) NOT NULL COMMENT '貼文所屬討論板'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `bbspost`
--

INSERT INTO `bbspost` (`postID`, `postTitle`, `postType`, `postContent`, `postUserID`, `postTime`, `lastUpdateUserID`, `lastUpdateTime`, `postStatus`, `postEdittime`, `postBoard`) VALUES
(1, '測試貼文', '板務公告', '<p>測試貼文<br />\r\n測試貼文<br />\r\n測試貼文</p>\r\n', 'admin', '2019-05-08 08:05:58', 'admin', '2019-05-17 05:03:48', 1, '2019-05-14 02:12:39', 1),
(3, '測試貼文2', '綜合討論', '測試貼文2<br />測試貼文2<br />測試貼文2<br />', 'user', '2019-05-09 01:11:00', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(5, '測試新文章', '綜合討論', '測試新文章1<br />測試新文章2<br />測試新文章3', 'admin', '2019-05-09 06:40:49', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(6, 'temp', '綜合討論', '123', 'admin', '2019-05-09 14:02:53', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(7, 'temp', '板務公告', '123', 'admin', '2019-05-09 14:03:08', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(8, 'temp', '同人創作', '123', 'admin', '2019-05-09 14:03:24', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(9, 'temp1', '板務公告', '123', 'admin', '2019-05-09 14:03:39', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(10, 'temp2', '綜合討論', 'qwe', 'admin', '2019-05-09 14:03:55', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(11, 'temop3', '綜合討論', 'qwer', 'admin', '2019-05-09 14:04:17', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(12, 'qwer', '綜合討論', 'asdf', 'admin', '2019-05-09 14:04:30', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(13, 'asdfasdf', '綜合討論', 'asdfasdfasdf', 'admin', '2019-05-09 14:04:44', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(15, '小測試', '攻略心得', '小測試', 'user', '2019-05-13 14:45:21', 'admin', '2019-05-17 05:03:48', 0, NULL, 1),
(16, '測回覆編輯...', '板務公告', '測回覆編輯<br />測回覆編輯<br />測回覆編輯', 'admin', '2019-05-13 14:58:52', 'admin', '2019-06-12 02:48:38', 1, '2019-05-13 14:59:22', 1),
(17, '123', '綜合討論', '<p><span style=\"color:#27ae60\"><span style=\"font-family:Verdana,Geneva,sans-serif\"><span style=\"font-size:48px\"><strong>安安安安安</strong></span></span></span></p>\r\n', 'admin', '2019-05-14 01:27:00', 'admin', '2019-05-17 05:03:48', 2, NULL, 1),
(18, '偶想粗芒果', '同人創作', '<p>芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ(・&forall;・ )ლ<br />\r\n芒果ლ', 'admin', '2019-05-14 02:57:04', 'admin', '2019-05-17 05:03:48', 0, NULL, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `checkout`
--

CREATE TABLE `checkout` (
  `itemID` int(255) NOT NULL COMMENT '項目編號',
  `pattern` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT '運送方式',
  `fee` int(11) NOT NULL COMMENT '運費',
  `type` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'freight' COMMENT '結帳 / 運送方式',
  `cashType` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '付款方式',
  `isRAddr` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '送貨地址是否為住址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `checkout`
--

INSERT INTO `checkout` (`itemID`, `pattern`, `fee`, `type`, `cashType`, `isRAddr`) VALUES
(1, '超商取貨付款', 60, 'freight', 'nocash', 'false'),
(2, '超商取貨', 60, 'freight', 'cash', 'false'),
(3, '郵局取貨', 70, 'freight', 'cash', 'false'),
(4, '貨送到府', 70, 'freight', 'cash', 'true'),
(5, '信用卡', 0, 'casher', '', ''),
(6, 'ATM 轉帳', 0, 'casher', '', ''),
(7, '超商代碼繳費', 0, 'casher', '', ''),
(8, '郵局無摺存款', 0, 'casher', '', '');

-- --------------------------------------------------------

--
-- 資料表結構 `faqlist`
--

CREATE TABLE `faqlist` (
  `faqOrder` int(11) NOT NULL,
  `faqQuestion` text NOT NULL,
  `faqAnswer` text NOT NULL,
  `faqPostDate` datetime NOT NULL,
  `faqPostUser` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `frontcarousel`
--

CREATE TABLE `frontcarousel` (
  `imgID` int(255) NOT NULL COMMENT '輪播流水號',
  `imgUrl` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '輪播圖',
  `imgDescript` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '輪播描述',
  `imgReferUrl` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '輪播指向位址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `frontcarousel`
--

INSERT INTO `frontcarousel` (`imgID`, `imgUrl`, `imgDescript`, `imgReferUrl`) VALUES
(1, 'carousel-1.jpg', '輪播一', ''),
(2, 'carousel-2.jpg', '輪播二', ''),
(3, 'carousel-3.jpg', '輪播三', '');

-- --------------------------------------------------------

--
-- 資料表結構 `goodslist`
--

CREATE TABLE `goodslist` (
  `goodsOrder` int(255) NOT NULL COMMENT '商品識別碼',
  `goodsName` varchar(50) NOT NULL COMMENT '商品名稱',
  `goodsImgUrl` varchar(50) NOT NULL DEFAULT 'default.jpg' COMMENT '商品圖片',
  `goodsDescript` varchar(500) NOT NULL COMMENT '商品描述',
  `goodsPrice` int(11) NOT NULL COMMENT '商品價格',
  `goodsQty` int(10) NOT NULL COMMENT '商品在庫量',
  `goodsPostDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '商品上架日期',
  `goodsUp` varchar(50) NOT NULL COMMENT '商品上架者'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `goodslist`
--

INSERT INTO `goodslist` (`goodsOrder`, `goodsName`, `goodsImgUrl`, `goodsDescript`, `goodsPrice`, `goodsQty`, `goodsPostDate`, `goodsUp`) VALUES
(1, '週邊一', 'goods-1.jpg', '<p>週邊一說明文字</p>\r\n', 100, 40, '2019-05-17 01:19:53', 'admin'),
(2, '週邊二', 'goods-2.jpg', '<p>週邊二說明文字</p>\r\n', 250, 43, '2019-05-17 01:18:19', 'admin'),
(3, '週邊三', 'goods-3.jpg', '<p>週邊三說明文字</p>\r\n', 50, 38, '2019-05-17 01:19:29', 'admin'),
(4, '週邊四', 'goods-4.jpg', '<p>週邊四說明文字</p>\r\n', 300, 50, '2019-05-17 01:19:30', 'admin'),
(5, '週邊五', 'goods-5.jpg', '<p>週邊五說明文字</p>\r\n', 600, 49, '2019-05-17 01:19:30', 'admin'),
(6, '週邊六', 'goods-6.jpg', '<p>週邊六說明文字</p>\r\n', 75, 50, '2019-05-17 01:19:30', 'admin'),
(7, '週邊七', 'default.jpg', '週邊七說明文字', 500, 50, '2019-05-17 01:19:30', 'admin'),
(8, '週邊八', 'default.jpg', '週邊八說明文字', 500, 50, '2019-05-17 01:19:30', 'admin'),
(9, '週邊九', 'default.jpg', '週邊九說明文字', 500, 50, '2019-05-17 01:19:30', 'admin');

-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

CREATE TABLE `member` (
  `uid` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `userPW` char(150) NOT NULL,
  `userNickname` varchar(50) NOT NULL,
  `userAvator` varchar(30) NOT NULL DEFAULT 'exampleAvator.jpg',
  `userEmail` varchar(50) NOT NULL,
  `userRegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `userPriviledge` int(11) NOT NULL,
  `userRealName` varchar(50) DEFAULT NULL COMMENT '會員真名',
  `userPhone` varchar(20) DEFAULT NULL COMMENT '會員電話',
  `userAddress` varchar(100) DEFAULT NULL COMMENT '會員住址'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `member`
--

INSERT INTO `member` (`uid`, `userName`, `userPW`, `userNickname`, `userAvator`, `userEmail`, `userRegDate`, `userPriviledge`, `userRealName`, `userPhone`, `userAddress`) VALUES
(1, 'admin', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', '超級管理員', 'exampleAvator.jpg', 'example@abc.com', '2019-05-01 02:02:06', 99, NULL, NULL, NULL),
(6, 'user', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', '一般使用者', 'exampleAvator.jpg', '123@gmail.com', '2019-05-01 18:17:08', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `mempriv`
--

CREATE TABLE `mempriv` (
  `privNum` int(255) NOT NULL COMMENT '權限編號',
  `privName` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '權限名稱',
  `privPreset` tinyint(1) NOT NULL COMMENT '權限可否刪除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `mempriv`
--

INSERT INTO `mempriv` (`privNum`, `privName`, `privPreset`) VALUES
(1, '一般會員', 1),
(99, '超級管理員', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `news`
--

CREATE TABLE `news` (
  `newsOrder` int(11) NOT NULL,
  `newsType` char(5) NOT NULL,
  `newsTitle` varchar(50) NOT NULL,
  `newsContent` varchar(300) NOT NULL,
  `postTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `postUser` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `news`
--

INSERT INTO `news` (`newsOrder`, `newsType`, `newsTitle`, `newsContent`, `postTime`, `postUser`) VALUES
(1, '一般', '網站正式上線', '洛嬉遊戲網站正式開張！歡迎大家多多利用！', '2019-04-13 13:40:32', 1),
(2, '資訊', '目前團隊狀況', '目前團隊正在開發一款新的遊戲，敬請期待！', '2019-04-24 11:32:14', 1),
(3, '一般', '團隊新血招募中', '洛嬉遊戲團隊目前正大力招募新血中，有志者歡迎一同來開發遊戲。', '2019-04-30 10:20:31', 1),
(23, '一般', '測試消息', '<p><span style=\"color:#ff0000\"><strong><span style=\"font-size:72px\">這是一則測試消息</span></strong></span><br />\r\n測試<br />\r\n第三行</p>\r\n', '2019-05-06 05:24:37', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `notifications`
--

CREATE TABLE `notifications` (
  `notifyID` int(255) NOT NULL COMMENT '通知識別碼',
  `notifyContent` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '通知內容',
  `notifyTitle` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '通知標題',
  `notifySource` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '通知來源',
  `notifyTarget` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '通知目標',
  `notifyURL` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '通知指向位置',
  `notifyStatus` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'u' COMMENT '通知狀態',
  `notifyTime` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '通知時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `orderID` int(255) NOT NULL COMMENT '訂單識別碼',
  `tradeID` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `orderMember` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購會員',
  `orderContent` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購內容',
  `orderRealName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購者姓名',
  `orderPhone` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購者電話',
  `orderAddress` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂購者地址',
  `orderPrice` int(100) NOT NULL COMMENT '應付金額',
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '訂單日期',
  `orderCasher` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '付款方式',
  `orderPattern` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '下訂日期',
  `orderFreight` int(100) NOT NULL COMMENT '運費',
  `orderStatus` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '訂單狀態',
  `removeApplied` int(5) NOT NULL DEFAULT 0 COMMENT '是否提出取消申請',
  `orderApplyStatus` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '訂單移除前狀態'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `orderTemp`
--

CREATE TABLE `orderTemp` (
  `tempID` int(11) NOT NULL,
  `tradeID` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `contents` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `productname`
--

CREATE TABLE `productname` (
  `prodOrder` int(11) NOT NULL COMMENT '作品編號',
  `prodTitle` varchar(50) NOT NULL COMMENT '作品名稱',
  `prodImgUrl` varchar(150) NOT NULL DEFAULT 'nowprint.jpg' COMMENT '作品視覺圖',
  `prodDescript` varchar(100) NOT NULL COMMENT '作品簡介',
  `prodPageUrl` varchar(150) NOT NULL COMMENT '作品頁面',
  `prodType` varchar(30) NOT NULL COMMENT '遊戲類型',
  `prodPlatform` varchar(50) NOT NULL COMMENT '遊戲平台',
  `prodRelDate` timestamp NULL DEFAULT NULL COMMENT '作品上架日期',
  `prodAddDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '資料新增日期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `productname`
--

INSERT INTO `productname` (`prodOrder`, `prodTitle`, `prodImgUrl`, `prodDescript`, `prodPageUrl`, `prodType`, `prodPlatform`, `prodRelDate`, `prodAddDate`) VALUES
(1, '作品一', 'nowprint.jpg', '作品一描述文字作品一描述文字作品一描述文字作品一描述文字作品一描述文字作品一描述文字作品一描述文字。', '#', '作品一類型', 'PC', '2019-06-12 16:00:00', '2019-06-13 04:00:34');

-- --------------------------------------------------------

--
-- 資料表結構 `removeorder`
--

CREATE TABLE `removeorder` (
  `removeID` int(255) NOT NULL COMMENT '移除申請識別碼',
  `targetOrder` int(255) NOT NULL COMMENT '移除目標訂單識別碼',
  `removeReason` varchar(100) NOT NULL COMMENT '申請移除原因',
  `removeDate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '申請移除日期',
  `removeStatus` varchar(20) NOT NULL DEFAULT 'appling' COMMENT '訂單移除狀態'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `sessions`
--

CREATE TABLE `sessions` (
  `sID` int(100) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `sessionID` varchar(60) NOT NULL,
  `useBrowser` varchar(20) NOT NULL DEFAULT '未知' COMMENT '使用之瀏覽器',
  `ipRmtAddr` varchar(39) DEFAULT NULL,
  `ipXFwFor` varchar(39) DEFAULT NULL,
  `ipHttpVia` varchar(39) DEFAULT NULL,
  `ipHTTPCIP` varchar(39) DEFAULT NULL,
  `lastipRmtAddr` varchar(39) DEFAULT NULL,
  `lastipXFwFor` varchar(39) DEFAULT NULL,
  `lastipHttpVia` varchar(39) DEFAULT NULL,
  `lastipHTTPCIP` varchar(39) DEFAULT NULL,
  `loginTime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `systemsetting`
--

CREATE TABLE `systemsetting` (
  `settingName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '設定對象',
  `settingValue` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '設定對象值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `systemsetting`
--

INSERT INTO `systemsetting` (`settingName`, `settingValue`) VALUES
('adminPriv', '99'),
('articlesNum', '10'),
('backendPriv', '99'),
('goodsNum', '10'),
('newsNum', '6'),
('postsNum', '9');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `bbsarticle`
--
ALTER TABLE `bbsarticle`
  ADD PRIMARY KEY (`articleID`);

--
-- 資料表索引 `bbsboard`
--
ALTER TABLE `bbsboard`
  ADD PRIMARY KEY (`boardID`);

--
-- 資料表索引 `bbspost`
--
ALTER TABLE `bbspost`
  ADD PRIMARY KEY (`postID`);

--
-- 資料表索引 `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`itemID`);

--
-- 資料表索引 `faqlist`
--
ALTER TABLE `faqlist`
  ADD PRIMARY KEY (`faqOrder`);

--
-- 資料表索引 `frontcarousel`
--
ALTER TABLE `frontcarousel`
  ADD PRIMARY KEY (`imgID`);

--
-- 資料表索引 `goodslist`
--
ALTER TABLE `goodslist`
  ADD UNIQUE KEY `goodsOrder` (`goodsOrder`);

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- 資料表索引 `mempriv`
--
ALTER TABLE `mempriv`
  ADD UNIQUE KEY `privNum` (`privNum`);

--
-- 資料表索引 `news`
--
ALTER TABLE `news`
  ADD UNIQUE KEY `newsOrder` (`newsOrder`);

--
-- 資料表索引 `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notifyID`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- 資料表索引 `orderTemp`
--
ALTER TABLE `orderTemp`
  ADD PRIMARY KEY (`tempID`);

--
-- 資料表索引 `productname`
--
ALTER TABLE `productname`
  ADD UNIQUE KEY `prodOrder` (`prodOrder`);

--
-- 資料表索引 `removeorder`
--
ALTER TABLE `removeorder`
  ADD PRIMARY KEY (`removeID`);

--
-- 資料表索引 `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`sID`);

--
-- 資料表索引 `systemsetting`
--
ALTER TABLE `systemsetting`
  ADD UNIQUE KEY `settingName` (`settingName`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `bbsarticle`
--
ALTER TABLE `bbsarticle`
  MODIFY `articleID` int(255) NOT NULL AUTO_INCREMENT COMMENT '回覆識別碼', AUTO_INCREMENT=24;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `bbsboard`
--
ALTER TABLE `bbsboard`
  MODIFY `boardID` int(5) NOT NULL AUTO_INCREMENT COMMENT '討論板 ID', AUTO_INCREMENT=4;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `bbspost`
--
ALTER TABLE `bbspost`
  MODIFY `postID` int(255) NOT NULL AUTO_INCREMENT COMMENT '貼文識別碼', AUTO_INCREMENT=19;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `checkout`
--
ALTER TABLE `checkout`
  MODIFY `itemID` int(255) NOT NULL AUTO_INCREMENT COMMENT '項目編號', AUTO_INCREMENT=9;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `frontcarousel`
--
ALTER TABLE `frontcarousel`
  MODIFY `imgID` int(255) NOT NULL AUTO_INCREMENT COMMENT '輪播流水號', AUTO_INCREMENT=4;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `goodslist`
--
ALTER TABLE `goodslist`
  MODIFY `goodsOrder` int(255) NOT NULL AUTO_INCREMENT COMMENT '商品識別碼', AUTO_INCREMENT=14;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `member`
--
ALTER TABLE `member`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `news`
--
ALTER TABLE `news`
  MODIFY `newsOrder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notifyID` int(255) NOT NULL AUTO_INCREMENT COMMENT '通知識別碼';

--
-- 使用資料表自動增長(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(255) NOT NULL AUTO_INCREMENT COMMENT '訂單識別碼';

--
-- 使用資料表自動增長(AUTO_INCREMENT) `orderTemp`
--
ALTER TABLE `orderTemp`
  MODIFY `tempID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `productname`
--
ALTER TABLE `productname`
  MODIFY `prodOrder` int(11) NOT NULL AUTO_INCREMENT COMMENT '作品編號', AUTO_INCREMENT=2;

--
-- 使用資料表自動增長(AUTO_INCREMENT) `removeorder`
--
ALTER TABLE `removeorder`
  MODIFY `removeID` int(255) NOT NULL AUTO_INCREMENT COMMENT '移除申請識別碼';

--
-- 使用資料表自動增長(AUTO_INCREMENT) `sessions`
--
ALTER TABLE `sessions`
  MODIFY `sID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
