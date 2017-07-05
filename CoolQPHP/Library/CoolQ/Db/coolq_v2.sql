-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 �?07 �?05 �?20:51
-- 服务器版本: 5.5.53
-- PHP 版本: 5.5.38

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `coolq_v2`
--

-- --------------------------------------------------------

--
-- 表的结构 `coolq_log`
--

CREATE TABLE IF NOT EXISTS `coolq_log` (
  `msg` text COLLATE utf8mb4_bin NOT NULL,
  `time` datetime NOT NULL,
  `source` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `robot_qq` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- 表的结构 `coolq_plugin_list`
--

CREATE TABLE IF NOT EXISTS `coolq_plugin_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priority` int(11) NOT NULL DEFAULT '3000',
  `name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `author_url` text COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `order` text COLLATE utf8mb4_bin NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `plugin_class` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `coolq_plugin_list`
--

INSERT INTO `coolq_plugin_list` (`id`, `priority`, `name`, `author`, `author_url`, `description`, `order`, `status`, `plugin_class`) VALUES
(4, 1000, '翼宝机器人', '', '', '', '', 1, 'YiBaoPlugin'),
(5, 3000, '音乐插件', '', '', '', '', 1, 'MusicPlugin'),
(7, 3000, 'OCR', '', '', '', '', 1, 'OcrPlugin'),
(8, 0, '图灵机器人', '', '', '', '', 1, 'TulingPlugin');

-- --------------------------------------------------------

--
-- 表的结构 `coolq_plugin_orders`
--

CREATE TABLE IF NOT EXISTS `coolq_plugin_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL,
  `order_name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `coolq_plugin_orders`
--

INSERT INTO `coolq_plugin_orders` (`id`, `plugin_id`, `order_name`) VALUES
(1, 4, '*'),
(2, 5, 'QQ音乐#'),
(3, 5, '网易音乐#'),
(4, 5, '虾米音乐#'),
(5, 6, '*'),
(6, 7, '*'),
(7, 8, '*');

-- --------------------------------------------------------

--
-- 表的结构 `coolq_robot`
--

CREATE TABLE IF NOT EXISTS `coolq_robot` (
  `qq` varchar(12) COLLATE utf8mb4_bin NOT NULL,
  `time` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `manager` text COLLATE utf8mb4_bin NOT NULL,
  `group_white_list` text COLLATE utf8mb4_bin NOT NULL,
  `group_black_list` text COLLATE utf8mb4_bin NOT NULL,
  `qq_white_list` text COLLATE utf8mb4_bin NOT NULL,
  `qq_black_list` text COLLATE utf8mb4_bin NOT NULL,
  `keyword` text COLLATE utf8mb4_bin NOT NULL,
  `is_group_black_list` int(11) NOT NULL DEFAULT '0',
  `is_group_white_list` int(11) NOT NULL DEFAULT '0',
  `is_qq_black_list` int(11) NOT NULL DEFAULT '0',
  `is_qq_white_list` int(11) NOT NULL DEFAULT '0',
  `is_at` int(11) NOT NULL DEFAULT '0',
  `is_keyword` int(11) NOT NULL DEFAULT '0',
  `is_follow` int(11) NOT NULL DEFAULT '0',
  `is_on_friend` int(11) NOT NULL DEFAULT '1',
  `is_on_group` int(11) NOT NULL DEFAULT '1',
  `is_on_discuss` int(11) NOT NULL DEFAULT '1',
  `is_agree_friend` int(11) NOT NULL DEFAULT '0' COMMENT '0: 忽略 1: 通过 2:拒绝',
  `is_on_plugin` int(11) NOT NULL DEFAULT '1',
  `is_agree_group` int(11) NOT NULL DEFAULT '0' COMMENT '0: 忽略 1: 通过 2:拒绝',
  `qq_refuse_reason` varchar(512) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `group_refuse_reason` varchar(512) COLLATE utf8mb4_bin NOT NULL,
  `is_reply_at` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- 转存表中的数据 `coolq_robot`
--

INSERT INTO `coolq_robot` (`qq`, `time`, `status`, `manager`, `group_white_list`, `group_black_list`, `qq_white_list`, `qq_black_list`, `keyword`, `is_group_black_list`, `is_group_white_list`, `is_qq_black_list`, `is_qq_white_list`, `is_at`, `is_keyword`, `is_follow`, `is_on_friend`, `is_on_group`, `is_on_discuss`, `is_agree_friend`, `is_on_plugin`, `is_agree_group`, `qq_refuse_reason`, `group_refuse_reason`, `is_reply_at`) VALUES
('2093208406', '2017-05-05 21:44:00', 1, '[1353693508]', '[194233857]', '[194233857]', '[1353693508]', '[1353693508]', '["测试","调试","BUG"]', 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 0, '', '', 1),
('1246002938', '2017-07-05 01:40:01', 1, '[1353693508]', '[438778749]', '[194233857]', '[943817585,1353693508]', '[]', '["测试","调试","BUG"]', 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 1, 0, '就是想拒绝你 不行啊', '就是想拒绝你 不行啊', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
