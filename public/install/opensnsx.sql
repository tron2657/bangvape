-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-01-13 11:14:23
-- 服务器版本： 5.6.49-log
-- PHP 版本： 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `2.7.1`
--

-- --------------------------------------------------------

--
-- 表的结构 `osx_action_log`
--

CREATE TABLE `osx_action_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `action` varchar(25) NOT NULL COMMENT '行为标识',
  `content` text NOT NULL,
  `model` varchar(25) NOT NULL DEFAULT '' COMMENT '关联表名',
  `row` int(11) NOT NULL DEFAULT '0' COMMENT '关联id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_all_agreement`
--

CREATE TABLE `osx_all_agreement` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL COMMENT '所属模块',
  `protocol` text NOT NULL COMMENT '协议说明',
  `show_where` text NOT NULL COMMENT '显示位置',
  `update_time` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_all_agreement`
--

INSERT INTO `osx_all_agreement` (`id`, `name`, `module`, `protocol`, `show_where`, `update_time`, `status`) VALUES
(1, '1212', '公共模块-注册登录', '用户协议：是约定软件所属公司与用户之间关于本款软件服务的权利义务等的文档', '【注册登录】，【设置-关于我们】页面', 1608001222, 1),
(2, '隐私协议', '公共模块-注册登录', '隐私协议：时告知用户网站或者移动端软件如何收集和使用用户信息和数据的文档', '【注册登录】，【设置-关于我们】页面', 1608002095, 1),
(3, '分销协议1', '商城，知识商城模块-分销', '分销申请协议：是约定平台，商家和全体承销商（一级二级分销用户）之前关于本款软件中分销服务的权利义务签约文档', '【申请开通分销权限】页面', 1608012313, 1),
(4, '分销收益规则说明', '商城，知识商城模块-分销', '分销收益规则说明：是告知用户关于“分销”功能的文档', '【我的-推广中心-我的收益】页面', 1608014029, 1),
(5, '分销提现规则说明', '商城，知识商城模块-分销', '分销提现规则说明：是告知用户提现规则说明的文档', '【我的-推广中心-立即提现】页面', 1608015278, 1),
(6, '积分规则说明', '公共模块-积分体系', '积分规则规则说明：是告知用户签到相关积分规则的文档', '', 1608021521, 1),
(7, '签到积分规则说', '公共模块-签到', '签到积分规则说明：是告知用户签到相关规则的文档', '', 1608021542, 1),
(8, '用户等级说明', '公共模块-用户等级', '用户等级说明：是告知用户相关等级规则的文档', '', 1608025643, 1),
(9, '积分奖励规则说明', '社区-版主奖励插件', '积分奖励规则说明：是告知版主前端奖励积分的规则的文档', '【版主奖励确认】页面', 1608024948, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_app_version`
--

CREATE TABLE `osx_app_version` (
  `id` int(11) NOT NULL COMMENT 'id',
  `version` text NOT NULL COMMENT '版本',
  `url` text NOT NULL COMMENT '链接地址',
  `status` int(11) NOT NULL COMMENT '状态',
  `remark` text NOT NULL COMMENT '备注',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `ios_url` text NOT NULL COMMENT 'iso链接'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_article`
--

CREATE TABLE `osx_article` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文章管理ID',
  `cid` varchar(255) DEFAULT '1' COMMENT '分类id',
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `author` varchar(255) DEFAULT NULL COMMENT '文章作者',
  `image_input` varchar(255) NOT NULL COMMENT '文章图片',
  `synopsis` varchar(255) DEFAULT NULL COMMENT '文章简介',
  `share_title` varchar(255) DEFAULT NULL COMMENT '文章分享标题',
  `share_synopsis` varchar(255) DEFAULT NULL COMMENT '文章分享简介',
  `visit` varchar(255) DEFAULT NULL COMMENT '浏览次数',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `url` varchar(255) DEFAULT NULL COMMENT '原文链接',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '状态',
  `add_time` varchar(255) NOT NULL COMMENT '添加时间',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员id',
  `mer_id` int(10) UNSIGNED DEFAULT '0' COMMENT '商户id',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否热门(小程序)',
  `is_banner` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否轮播图(小程序)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章管理表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_article_category`
--

CREATE TABLE `osx_article_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文章分类id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `title` varchar(255) NOT NULL COMMENT '文章分类标题',
  `intr` varchar(255) DEFAULT NULL COMMENT '文章分类简介',
  `image` varchar(255) NOT NULL COMMENT '文章分类图片',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '状态',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1删除0未删除',
  `add_time` varchar(255) NOT NULL COMMENT '添加时间',
  `hidden` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_article_content`
--

CREATE TABLE `osx_article_content` (
  `nid` int(10) UNSIGNED NOT NULL COMMENT '文章id',
  `content` text NOT NULL COMMENT '文章内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章内容表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_bind_forum_group`
--

CREATE TABLE `osx_bind_forum_group` (
  `id` int(11) NOT NULL COMMENT 'id',
  `bind_forum` int(11) NOT NULL COMMENT '绑定的分区或者板块',
  `type` text NOT NULL COMMENT '绑定的类型',
  `group` text NOT NULL COMMENT '绑定的用户组，用，拼接',
  `status` int(11) NOT NULL COMMENT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_bind_group_power`
--

CREATE TABLE `osx_bind_group_power` (
  `id` int(11) NOT NULL COMMENT 'id',
  `sign` text NOT NULL COMMENT 'power表id',
  `g_id` int(11) NOT NULL COMMENT 'group表id',
  `level` int(11) NOT NULL COMMENT '等级',
  `value` int(11) NOT NULL COMMENT '值',
  `update_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_bind_group_power`
--

INSERT INTO `osx_bind_group_power` (`id`, `sign`, `g_id`, `level`, `value`, `update_time`) VALUES
(1, 'visit', 2, 2, 1, 1587115458),
(2, 'send_thread', 2, 2, 1, 1587115458),
(3, 'send_news', 2, 2, 1, 1587115458),
(4, 'send_weibo', 2, 2, 1, 1587115458),
(5, 'send_video', 2, 2, 1, 1587115458),
(6, 'send_comment', 2, 2, 1, 1587115458),
(7, 'share_goods', 2, 2, 1, 1587115458),
(8, 'edit_my_thread', 2, 2, 1, 1587115458),
(9, 'send_thread_count', 2, 2, 9999, 1587115458),
(10, 'audit', 2, 2, 1, 1587115458),
(11, 'visit', 3, 2, 1, 1587115471),
(12, 'send_thread', 3, 2, 1, 1587115471),
(13, 'send_news', 3, 2, 1, 1587115471),
(14, 'send_weibo', 3, 2, 1, 1587115471),
(15, 'send_video', 3, 2, 1, 1587115471),
(16, 'send_comment', 3, 2, 1, 1587115471),
(17, 'share_goods', 3, 2, 1, 1587115471),
(18, 'edit_my_thread', 3, 2, 1, 1587115471),
(19, 'send_thread_count', 3, 2, 9999, 1587115471),
(20, 'audit', 3, 2, 1, 1587115471),
(21, 'visit', 4, 2, 1, 1587115483),
(22, 'send_thread', 4, 2, 1, 1587115483),
(23, 'send_news', 4, 2, 1, 1587115483),
(24, 'send_weibo', 4, 2, 1, 1587115483),
(25, 'send_video', 4, 2, 1, 1587115483),
(26, 'send_comment', 4, 2, 1, 1587115483),
(27, 'share_goods', 4, 2, 1, 1587115483),
(28, 'edit_my_thread', 4, 2, 1, 1587115483),
(29, 'send_thread_count', 4, 2, 9999, 1587115483),
(30, 'audit', 4, 2, 1, 1587115483),
(31, 'set_top', 2, 2, 3, 1587115538),
(32, 'add_digest', 2, 2, 1, 1587115538),
(33, 'recommend', 2, 2, 1, 1587115538),
(34, 'edit_thread', 2, 2, 1, 1587115538),
(35, 'delete_thread', 2, 2, 1, 1587115538),
(36, 'set_top_thread', 2, 2, 1, 1587115538),
(37, 'delete_comment', 2, 2, 1, 1587115538),
(38, 'set_top', 3, 2, 3, 1587115547),
(39, 'add_digest', 3, 2, 1, 1587115547),
(40, 'recommend', 3, 2, 1, 1587115547),
(41, 'edit_thread', 3, 2, 1, 1587115547),
(42, 'delete_thread', 3, 2, 1, 1587115547),
(43, 'set_top_thread', 3, 2, 1, 1587115547),
(44, 'delete_comment', 3, 2, 1, 1587115547),
(45, 'set_top', 4, 2, 2, 1587115555),
(46, 'add_digest', 4, 2, 1, 1587115555),
(47, 'recommend', 4, 2, 1, 1587115555),
(48, 'edit_thread', 4, 2, 1, 1587115555),
(49, 'delete_thread', 4, 2, 1, 1587115555),
(50, 'set_top_thread', 4, 2, 1, 1587115555),
(51, 'delete_comment', 4, 2, 1, 1587115555),
(52, 'visit', 7, 1, 1, 1587115591),
(53, 'send_thread', 7, 1, 0, 1587115591),
(54, 'send_news', 7, 1, 0, 1587115591),
(55, 'send_weibo', 7, 1, 0, 1587115591),
(56, 'send_video', 7, 1, 0, 1587115591),
(57, 'send_comment', 7, 1, 0, 1587115591),
(58, 'share_goods', 7, 1, 0, 1587115591),
(59, 'edit_my_thread', 7, 1, 0, 1587115591),
(60, 'send_thread_count', 7, 1, 0, 1587115591),
(61, 'audit', 7, 1, 0, 1587115591),
(62, 'visit', 8, 1, 1, 1587115614),
(63, 'send_thread', 8, 1, 1, 1587115614),
(64, 'send_news', 8, 1, 1, 1587115614),
(65, 'send_weibo', 8, 1, 1, 1587115614),
(66, 'send_video', 8, 1, 1, 1587115614),
(67, 'send_comment', 8, 1, 1, 1587115614),
(68, 'share_goods', 8, 1, 1, 1587115614),
(69, 'edit_my_thread', 8, 1, 1, 1587115614),
(70, 'send_thread_count', 8, 1, 9999, 1587115614),
(71, 'audit', 8, 1, 1, 1587115614),
(72, 'visit', 9, 4, 1, 1587115645),
(73, 'send_thread', 9, 4, 0, 1587115645),
(74, 'send_news', 9, 4, 0, 1587115645),
(75, 'send_weibo', 9, 4, 0, 1587115645),
(76, 'send_video', 9, 4, 0, 1587115645),
(77, 'send_comment', 9, 4, 0, 1587115645),
(78, 'share_goods', 9, 4, 0, 1587115645),
(79, 'edit_my_thread', 9, 4, 0, 1587115645),
(80, 'send_thread_count', 9, 4, 0, 1587115645),
(81, 'audit', 9, 4, 1, 1587115645),
(82, 'visit', 10, 5, 0, 1587115667),
(83, 'send_thread', 10, 5, 0, 1587115667),
(84, 'send_news', 10, 5, 0, 1587115667),
(85, 'send_weibo', 10, 5, 0, 1587115667),
(86, 'send_video', 10, 5, 0, 1587115667),
(87, 'send_comment', 10, 5, 0, 1587115667),
(88, 'share_goods', 10, 5, 0, 1587115667),
(89, 'edit_my_thread', 10, 5, 0, 1587115667),
(90, 'send_thread_count', 10, 5, 0, 1587115667),
(91, 'audit', 10, 5, 1, 1587115667),
(92, 'forum_prohibit', 4, 2, 1, 1588903971),
(93, 'audit_admin', 4, 2, 1, 1588903971),
(94, 'audit_content', 4, 2, 1, 1588903971),
(95, 'audit_visit', 4, 2, 1, 1588903971),
(96, 'group_manage', 4, 2, 1, 1588903971),
(97, 'forum_prohibit', 3, 3, 1, 1588903952),
(98, 'audit_admin', 3, 3, 1, 1588903952),
(99, 'audit_content', 3, 3, 1, 1588903952),
(100, 'audit_visit', 3, 3, 1, 1588903952),
(101, 'group_manage', 3, 3, 1, 1588903952);

-- --------------------------------------------------------

--
-- 表的结构 `osx_bind_group_uid`
--

CREATE TABLE `osx_bind_group_uid` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `g_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '3：等级用户组'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_bind_uid_cid`
--

CREATE TABLE `osx_bind_uid_cid` (
  `uid` int(11) NOT NULL COMMENT 'uid',
  `cid` text NOT NULL COMMENT '用户cid',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_bind_user_log`
--

CREATE TABLE `osx_bind_user_log` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT 'uid',
  `create_time` int(11) NOT NULL COMMENT 'create_time',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'status',
  `is_vest` int(11) NOT NULL DEFAULT '0' COMMENT '是否是马甲用户'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_blacklist`
--

CREATE TABLE `osx_blacklist` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `black_uid` int(11) NOT NULL COMMENT '拉黑的用户uid',
  `status` int(11) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '拉黑时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='黑名单表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_cache`
--

CREATE TABLE `osx_cache` (
  `key` varchar(32) NOT NULL,
  `result` text COMMENT '缓存数据',
  `add_time` int(10) DEFAULT NULL COMMENT '缓存时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信缓存表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_cache_flush`
--

CREATE TABLE `osx_cache_flush` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='缓存结构表';

--
-- 转存表中的数据 `osx_cache_flush`
--

INSERT INTO `osx_cache_flush` (`id`, `name`, `update_time`) VALUES
(1, 'cacheFlush_sign_signnum', 1601183643),
(2, 'cacheFlush_sign_signday', 1601183643),
(3, 'cacheFlush_sign_renwulist', 1601183643),
(4, 'cacheFlush_sign_qiandaodata', 1601183643),
(5, 'cacheFlush_sign_grade', 1601183643),
(6, 'cacheFlush_sign_newuserrw', 1601183656),
(7, 'cacheFlush_sign_tasktabbar', 1601183656),
(8, 'cacheFlush_forum_firstClassList', 1601183656),
(9, 'cacheFlush_forum_sendList', 1601183656),
(10, 'cacheFlush_forum_announce', 1601183656),
(11, 'cacheFlush_forum_bottomNavBar', 1601183656),
(12, 'cacheFlush_forum_term', 1601183656),
(13, 'cacheFlush_forum_adv1', 1601183656),
(14, 'cacheFlush_forum_adv2', 1601183656),
(15, 'cacheFlush_forum_adv3', 1601183656),
(16, 'cacheFlush_forum_adv4', 1601183656),
(17, 'cacheFlush_forum_adv5', 1601183656),
(18, 'cacheFlush_forum_adv6', 1601183656),
(19, 'cacheFlush_forum_adv7', 1601183656),
(20, 'cacheFlush_forum_adv8', 1601183656),
(21, 'cacheFlush_forum_adv9', 1601183656),
(22, 'cacheFlush_forum_adv10', 1601183656),
(23, 'cacheFlush_forum_adv11', 1601183656),
(24, 'cacheFlush_forum_adv', 1601183656),
(25, 'cacheFlush_mall', 1601183656);

-- --------------------------------------------------------

--
-- 表的结构 `osx_cash_out`
--

CREATE TABLE `osx_cash_out` (
  `id` int(11) NOT NULL,
  `order_num` varchar(50) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT '提现类型,支付宝alipay,微信weixin',
  `account` varchar(50) NOT NULL COMMENT '提现账户',
  `image` varchar(200) NOT NULL COMMENT '提现相关图片，微信、支付宝收款码或好友添加方式',
  `out_num` decimal(8,2) NOT NULL COMMENT '提现金额',
  `create_time` int(11) NOT NULL COMMENT '提现请求发起时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态，0：已驳回；1：发起请求(待审核、可驳回）；2：审核通过(待打款、可驳回、可备注）；3：线下打款成功（	可备注）',
  `finish_time` int(11) NOT NULL COMMENT '提现完成时间',
  `fail_reason` varchar(300) NOT NULL COMMENT '提现失败原因',
  `remark` varchar(300) NOT NULL COMMENT '备注',
  `weixin_name` varchar(20) NOT NULL DEFAULT '' COMMENT '微信真实姓名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_cate`
--

CREATE TABLE `osx_certification_cate` (
  `id` int(11) NOT NULL,
  `table_name` varchar(20) NOT NULL COMMENT '表名，不可重复',
  `name` varchar(50) NOT NULL COMMENT '认证名称',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `type_id` int(11) DEFAULT NULL COMMENT '认证类型',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '认证图标',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `image` varchar(255) NOT NULL COMMENT '类别图标'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证管理-认证类别';

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_cate_condition`
--

CREATE TABLE `osx_certification_cate_condition` (
  `id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL COMMENT '认证类别id',
  `condition_id` int(11) NOT NULL COMMENT '认证条件id',
  `condition_value` int(11) DEFAULT NULL COMMENT '条件值',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证管理-认证类别&允许条件';

--
-- 转存表中的数据 `osx_certification_cate_condition`
--

INSERT INTO `osx_certification_cate_condition` (`id`, `cate_id`, `condition_id`, `condition_value`, `create_time`, `update_time`) VALUES
(1, 1, 6, 11, 1574778156, 1574778197),
(2, 1, 2, 0, 1574778197, 1574778197);

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_cate_datum`
--

CREATE TABLE `osx_certification_cate_datum` (
  `id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL COMMENT '认证类别id',
  `datum_id` int(11) NOT NULL COMMENT '认证资料项id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证管理-认证类别&认证资料项';

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_cate_privilege`
--

CREATE TABLE `osx_certification_cate_privilege` (
  `id` int(11) NOT NULL,
  `cate_id` int(11) NOT NULL COMMENT '认证类别id',
  `privilege_id` int(11) NOT NULL COMMENT '认证特权id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `built_in` int(1) DEFAULT '0' COMMENT '是否内置（1是，0否）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证管理-认证类别&认证特权';

--
-- 转存表中的数据 `osx_certification_cate_privilege`
--

INSERT INTO `osx_certification_cate_privilege` (`id`, `cate_id`, `privilege_id`, `create_time`, `update_time`, `built_in`) VALUES
(1, 1, 4, 1574776288, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_condition`
--

CREATE TABLE `osx_certification_condition` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '标识',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '问题',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-认证条件';

--
-- 转存表中的数据 `osx_certification_condition`
--

INSERT INTO `osx_certification_condition` (`id`, `name`, `desc`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, 'rztj1', '清晰头像', 0, 1, NULL, NULL),
(2, 'rztj2', '绑定手机', 0, 1, NULL, NULL),
(3, 'rztj3', '关注数≥', 0, 1, NULL, NULL),
(4, 'rztj4', '粉丝数≥', 0, 1, NULL, NULL),
(5, 'rztj5', '评论数≥', 0, 1, NULL, NULL),
(6, 'rztj6', '近30天发帖数≥', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_datum`
--

CREATE TABLE `osx_certification_datum` (
  `id` int(11) NOT NULL,
  `field` varchar(50) NOT NULL COMMENT '字段名 标识',
  `name` varchar(50) NOT NULL COMMENT '标识名 名称',
  `input_tips` varchar(100) NOT NULL DEFAULT '' COMMENT '输入提示 备注说明',
  `form_type` varchar(20) NOT NULL COMMENT '字段类型 样式',
  `setting` text COMMENT '设置 参数',
  `type_id` varchar(11) NOT NULL DEFAULT '0' COMMENT '所属认证类型',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `default_value` varchar(255) DEFAULT '' COMMENT '默认值',
  `max_length` int(4) DEFAULT '0' COMMENT '字数上限',
  `is_unique` tinyint(1) DEFAULT '0' COMMENT '是否唯一（1是，0否）',
  `is_null` tinyint(1) DEFAULT '0' COMMENT '是否必填（1是，0否）',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `built_in` int(1) DEFAULT '0' COMMENT '是否内置（1是，0否）',
  `use_range` varchar(100) NOT NULL DEFAULT '1' COMMENT '使用范围'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-资料项';

--
-- 转存表中的数据 `osx_certification_datum`
--

INSERT INTO `osx_certification_datum` (`id`, `field`, `name`, `input_tips`, `form_type`, `setting`, `type_id`, `sort`, `status`, `default_value`, `max_length`, `is_unique`, `is_null`, `create_time`, `update_time`, `built_in`, `use_range`) VALUES
(1, 'zsxm', '真实姓名', '', 'text', '', '1,2', 100, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(2, 'sfzh', '身份证号', '', 'text', '', '1,2', 99, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(3, 'scsfzzm', '上传身份证正面图片', '注意反光，保证身份证内容清晰可见', 'file', '', '1,2', 98, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(4, 'scsfzfm', '上传身份证反面图片', '注意反光，保证身份证内容清晰可见', 'file', '', '1,2', 97, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(5, 'scscsfz', '上传本人手持身份证照片', '请确保身份证内容清晰可见', 'file', '', '1,2', 0, 1, '', 0, 1, 1, 1553788800, 1553788800, 1, '1'),
(6, 'jgmc', '机构名称', '', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(7, 'jgjc', '机构简称', '认证成功后将在前端显示', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(8, 'yyzzzch', '营业执照注册号', '', 'text', '', '3', 0, 1, '', 0, 1, 0, 1553788800, 1553788800, 1, '1'),
(9, 'frdbxm', '法人代表姓名', '', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(10, 'yyzxm', '运营者姓名', '', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(11, 'yyzsfzh', '运营者身份证号', '', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(12, 'scyyzz', '上传营业执照', '', 'file', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(13, 'scyyzsfz', '上传运营者身份证', '', 'file', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(14, 'scrzghzp', '上传认证公函照片', '', 'file', '', '3', 0, 1, '', 0, 1, 1, 1553788800, 1553788800, 1, '1'),
(15, 'wzbaxx', '网站备案信息', '', 'text', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(16, 'scsbzcz', '上传商标注册证', '', 'file', '', '3', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(17, 'xb', '性别', '', 'select', '男\n女\n保密', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(18, 'sr', '生日', '', 'date', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(19, 'xz', '星座', '', 'select', '白羊座\n金牛座\n双子座\n巨蟹座\n狮子座\n处女座\n天秤座\n天蝎座\n射手座\n摩羯座\n水瓶座\n双鱼座\n保密', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(20, 'gddh', '固定电话', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(21, 'sjh', '手机号', '', 'mobile', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(22, 'dz', '地址', '', 'address', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(23, 'csd', '出生地', '', 'text', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(24, 'jzd', '居住地', '', 'text', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(25, 'byxx', '毕业学校', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(26, 'xl', '学历', '', 'select', '文盲\n小学\n初中\n高中(职高、中专)\n大专(高职)\n本科\n硕士研究生\n博士研究生\n保密', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(27, 'gs', '公司', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(28, 'zy', '职业', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(29, 'zw', '职位', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(30, 'nsr', '年收入', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(31, 'qgzt', '情感状态', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(32, 'jymd', '交友目的', '', 'text', '', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(33, 'xx', '血型', '', 'select', 'A\nB\nO\nAB\n保密', '1', 0, 1, '', 0, 0, 0, 1553788800, 1553788800, 1, '1'),
(34, 'qq', 'QQ', '', 'text', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(35, 'zwjs', '自我介绍', '', 'textarea', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(36, 'xqah', '兴趣爱好', '', 'textarea', '', '1', 0, 1, '', 0, 0, 1, 1553788800, 1553788800, 1, '1'),
(37, 'rztx', '认证头衔', '认证成功用于前端显示，请谨慎填写', 'text', '', '1,3', 0, 1, '', 0, 0, 0, 1586920322, NULL, 1, '1'),
(38, 'nc', '昵称', '', 'text', '', '', 0, 1, '', 0, 0, 0, 1598247384, NULL, 0, '2');

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_entity`
--

CREATE TABLE `osx_certification_entity` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `avatar` varchar(200) NOT NULL COMMENT '头像',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `truename` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '电话',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '认证类别',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态（0未审核，1审核通过 -1 审核驳回）默认未审核',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `approve_time` int(11) DEFAULT NULL COMMENT '通过时间',
  `reject_time` int(11) DEFAULT NULL COMMENT '驳回时间',
  `datum_data` text COMMENT '资料信息',
  `reject_note` varchar(200) DEFAULT NULL COMMENT '驳回理由',
  `is_read` tinyint(1) DEFAULT '0' COMMENT '是否阅读 0:未阅读，1:已读'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证管理-认证实体';

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_faq`
--

CREATE TABLE `osx_certification_faq` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '问题',
  `desc` text NOT NULL COMMENT '问题说明',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-常见问题';

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_privilege`
--

CREATE TABLE `osx_certification_privilege` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '特权名称',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '图标',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `built_in` int(1) DEFAULT '0' COMMENT '是否内置（1是，0否）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-认证特权';

--
-- 转存表中的数据 `osx_certification_privilege`
--

INSERT INTO `osx_certification_privilege` (`id`, `name`, `desc`, `icon`, `sort`, `status`, `create_time`, `update_time`, `built_in`) VALUES
(1, '官方认证标识', '真实身份，易于辨识', '', 0, 1, NULL, NULL, 0),
(2, '官方推荐', '海量资源位，优先展示', '', 0, 1, NULL, NULL, 0),
(3, '专属特权', '新功能优先体验', '', 0, 1, NULL, NULL, 0),
(4, '专属客服', '专人对接，优先解决', '', 0, 1, NULL, NULL, 0),
(5, '点亮红名', '专享全站红名特权', '', 0, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_certification_type`
--

CREATE TABLE `osx_certification_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '认证类型名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-认证类型';

--
-- 转存表中的数据 `osx_certification_type`
--

INSERT INTO `osx_certification_type` (`id`, `name`) VALUES
(1, '个人认证'),
(2, '实名认证'),
(3, '机构认证');

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel`
--

CREATE TABLE `osx_channel` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `logo` varchar(250) NOT NULL COMMENT '封面，存放路径',
  `intor` varchar(250) NOT NULL COMMENT '频道说明',
  `type` tinyint(2) NOT NULL COMMENT '频道类型，1：系统频道；2：自定义频道',
  `status` tinyint(2) NOT NULL COMMENT '频道状态，启用，禁用，删除',
  `default_open_status` tinyint(2) NOT NULL COMMENT '默认开启状态，导航设置相关功能',
  `default_sort` tinyint(4) NOT NULL COMMENT '默认排序值，导航设置相关功能',
  `post_type` tinyint(2) NOT NULL COMMENT '0：不推送，1:自动推送，2：手动推送，3：自动推送+手动推送',
  `post_audit` tinyint(2) NOT NULL COMMENT '投稿是否需要审核，0：不需要，1：需要',
  `post_intor` varchar(250) NOT NULL COMMENT '投稿说明',
  `from_type` tinyint(2) NOT NULL COMMENT '来源类型。0：来自版块，1：来自用户，2：来自全站',
  `from_ids` varchar(500) NOT NULL COMMENT '关联id列表，多个用逗号拼接保存',
  `condition_post_hot_type` tinyint(2) NOT NULL COMMENT '帖子热度条件。1:同时满足三项，2：满足其中一项',
  `condition_post_hot_comment` int(11) NOT NULL COMMENT '评论数大于',
  `condition_post_hot_read` int(11) NOT NULL COMMENT '阅读数大于',
  `condition_post_hot_support` int(11) NOT NULL COMMENT '点赞数大于',
  `condition_post_type` tinyint(2) NOT NULL COMMENT '帖子类型。0：全部，1：只取精华帖，2：只取置顶帖',
  `condition_post_content` varchar(10) NOT NULL COMMENT '帖子内容,多选，多个逗号拼接。1：帖子，2：视频，3：资讯，4：动态',
  `condition_post_send_time` tinyint(2) NOT NULL COMMENT '发布时间范围，X以内。1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天',
  `condition_post_comment_time` tinyint(2) NOT NULL COMMENT '最后回复时间范围，X以内。1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天',
  `condition_post_update_time` tinyint(2) NOT NULL COMMENT '最后修改时间范围，X以内。1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天',
  `list_sort_type` tinyint(2) NOT NULL COMMENT '推送帖子，前台排序规则。1. 按点赞数目倒序；2. 按评论数目倒序；3. 按收藏数目倒序；4. 按发布时间倒序（默认）；5. 按回复时间倒序；6. 按修改时间倒序',
  `list_page_limit` int(11) NOT NULL COMMENT '单页数量。（个，用于分页）',
  `list_update_interval` int(11) NOT NULL COMMENT '刷新频率。（小时，小于24小时的整数值）',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `fixed` int(11) NOT NULL DEFAULT '0' COMMENT '是否固定0非固定1固定',
  `is_index` int(11) NOT NULL DEFAULT '0' COMMENT '是否是设置为首页,只能设置成一个',
  `list_update_interval_type` int(11) NOT NULL DEFAULT '0' COMMENT '0分1小时'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_channel`
--

INSERT INTO `osx_channel` (`id`, `title`, `logo`, `intor`, `type`, `status`, `default_open_status`, `default_sort`, `post_type`, `post_audit`, `post_intor`, `from_type`, `from_ids`, `condition_post_hot_type`, `condition_post_hot_comment`, `condition_post_hot_read`, `condition_post_hot_support`, `condition_post_type`, `condition_post_content`, `condition_post_send_time`, `condition_post_comment_time`, `condition_post_update_time`, `list_sort_type`, `list_page_limit`, `list_update_interval`, `create_time`, `update_time`, `fixed`, `is_index`, `list_update_interval_type`) VALUES
(1, '关注', '', '系统频道-关注', 1, 1, 1, 1, 0, 0, '', 0, '', 0, 0, 0, 0, 0, '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, '推荐', '', '系统频道-推荐（该id必须为2，跟代码绑定了，安装人员注意）', 1, 1, 1, 2, 3, 1, '投稿到推荐频道，需等待3~5天的审核……审核结果会站内消息通知您', 2, '', 1, 0, 0, 0, 0, '1', 1, 1, 1, 4, 10, 10, 0, 0, 1, 1, 0),
(3, '圈子', '', '系统频道-圈子', 1, 1, 1, 3, 0, 0, '', 0, '', 0, 0, 0, 0, 0, '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, '话题', '', '系统频道-话题', 1, 1, 1, 4, 0, 0, '', 0, '', 0, 0, 0, 0, 0, '0', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_admin`
--

CREATE TABLE `osx_channel_admin` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `do_uid` int(11) NOT NULL COMMENT '操作人uid',
  `status` tinyint(2) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道管理员关联表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_channel_admin`
--

INSERT INTO `osx_channel_admin` (`id`, `channel_id`, `uid`, `create_time`, `do_uid`, `status`) VALUES
(1, 2, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_count_content`
--

CREATE TABLE `osx_channel_count_content` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `post_num` int(11) NOT NULL COMMENT '帖子内容数',
  `create_time` int(11) NOT NULL COMMENT '统计日期，标记是哪天的统计。实际创建时间是次日凌晨1点',
  `post_type` tinyint(2) NOT NULL COMMENT '推荐类型，1：自动推荐统计，2：手动推荐统计'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道内容统计' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_count_open_rate`
--

CREATE TABLE `osx_channel_count_open_rate` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `rate` int(11) NOT NULL COMMENT '开启率，整数存储，实际开启率=(存储值/100) %',
  `create_time` int(11) NOT NULL COMMENT '创建时间，标记是哪天的统计'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道开启率' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_count_view`
--

CREATE TABLE `osx_channel_count_view` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `view_num` int(11) NOT NULL COMMENT '浏览数',
  `create_time` int(11) NOT NULL COMMENT '统计日期，标记是哪天的统计。实际创建时间是次日凌晨1点',
  `type` tinyint(2) NOT NULL COMMENT '统计类型，1：日统计，2：按周统计，3：按月统计，4按年统计'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='浏览数统计' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_count_view_log`
--

CREATE TABLE `osx_channel_count_view_log` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `ip` int(11) NOT NULL COMMENT 'ip地址',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `uid` int(11) NOT NULL COMMENT '用户uid，不一定有，默认0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='浏览记录，调用一次频道帖子数据列表接口算一次浏览。保留30日内浏览记录，每天统计时自动删除30天以前的记录' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_post`
--

CREATE TABLE `osx_channel_post` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `post_id` int(11) NOT NULL COMMENT '帖子id',
  `post_title` varchar(500) DEFAULT NULL,
  `post_author` varchar(100) NOT NULL COMMENT '内容作者,存放作者信息，后台搜索使用，有一定延时',
  `post_support_count` int(11) NOT NULL COMMENT '帖子点赞数，前台排序使用',
  `post_comment_count` int(11) NOT NULL COMMENT '帖子评论数，前台排序使用',
  `post_collect_count` int(11) NOT NULL COMMENT '帖子收藏数，前台排序使用',
  `post_create_time` int(11) NOT NULL COMMENT '帖子创建时间，前台排序使用',
  `post_comment_time` int(11) NOT NULL COMMENT '帖子最后评论时间，前台排序使用',
  `post_update_time` int(11) NOT NULL COMMENT '帖子最后编辑时间，前台排序使用',
  `recommend_uid` int(11) NOT NULL COMMENT '推荐用户，手动推送类型需要',
  `status` tinyint(2) NOT NULL COMMENT '投稿/关联状态，2 待审核，1 已推送/审核通过，0 审核失败',
  `audit_fail_reason` varchar(500) NOT NULL COMMENT '审核失败原因',
  `is_hide` tinyint(2) NOT NULL COMMENT '是否手动屏蔽，0：不屏蔽,1：屏蔽',
  `post_type` tinyint(2) NOT NULL COMMENT '投稿类型，1：自动推送,2：手动推送',
  `post_long` tinyint(4) NOT NULL COMMENT '推送时长设置。1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天',
  `deadline` int(11) NOT NULL DEFAULT '2145888000' COMMENT '推荐截止时间。根据推送时长和操作时间自动计算出来。为查询方便，无限制时存放2038年1月1日的时间戳',
  `sort_num` int(11) NOT NULL COMMENT '排序权重，0~100内数字',
  `image_show_type` tinyint(2) NOT NULL COMMENT '图片形式，1：单图，2：双图，3：三图，4：无图',
  `is_top` tinyint(2) NOT NULL COMMENT '是否置顶',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '同步帖子type，用于小程序查询时排除视频'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道帖子关联表。实际排序方式：is_top desc,sort_num desc,频道的排序方式' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_post_hide`
--

CREATE TABLE `osx_channel_post_hide` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `post_id` int(11) NOT NULL COMMENT '频道id',
  `uid` int(11) NOT NULL COMMENT '屏蔽操作人',
  `create_time` int(11) NOT NULL COMMENT '屏蔽操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子屏蔽表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_post_pool`
--

CREATE TABLE `osx_channel_post_pool` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL COMMENT '内容id',
  `post_title` varchar(500) DEFAULT NULL,
  `post_author` varchar(100) NOT NULL COMMENT '内容作者，后台搜索使用，有一定延时',
  `recommend_uid` int(11) NOT NULL COMMENT '备选人',
  `status` tinyint(2) NOT NULL COMMENT '备选状态，2：待加入备选（帖子等列表有个编辑推送按钮，这个时候可能还没加入备选，这时候编辑的数据也要保存到这边），1：正常，-1：删除',
  `post_long` tinyint(4) NOT NULL COMMENT '推荐有效期，从“立即推送”开始计算。1. 无限制（默认）；2. 24小时；3. 3天；4. 7天；5. 30天；6. 180天',
  `sort_num` int(11) NOT NULL COMMENT '排序权重，0~100内数字',
  `image_show_type` tinyint(2) NOT NULL COMMENT '图片形式，1：单图，2：双图，3：三图，0：无图',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='备选池' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_recommend_log`
--

CREATE TABLE `osx_channel_recommend_log` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id',
  `create_time` int(11) NOT NULL COMMENT '创建时间，标记是几点执行的自动推荐'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自动推荐执行记录表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_channel_user`
--

CREATE TABLE `osx_channel_user` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '频道id，非系统频道的id，系统频道的id不在这边记录',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  `sort_num` int(11) NOT NULL COMMENT '排序值',
  `status` tinyint(2) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户频道设置（喜欢、排序）' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_collect`
--

CREATE TABLE `osx_collect` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '主题id',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_author`
--

CREATE TABLE `osx_column_author` (
  `id` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL COMMENT '作者昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `label` varchar(255) NOT NULL COMMENT '标签，多个用英文,隔开',
  `summary` varchar(255) NOT NULL COMMENT '作者简介',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识付费作者表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_category`
--

CREATE TABLE `osx_column_category` (
  `id` int(11) NOT NULL COMMENT '商品分类表ID',
  `pid` int(11) NOT NULL COMMENT '父id',
  `cate_name` varchar(100) NOT NULL COMMENT '分类名称',
  `sort` mediumint(11) NOT NULL COMMENT '排序',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否推荐',
  `create_time` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_class`
--

CREATE TABLE `osx_column_class` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '栏目名称',
  `summary` varchar(255) NOT NULL COMMENT '描述',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：纵向单列，2：纵向双列',
  `num` int(11) NOT NULL COMMENT '前台显示行数',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识付费栏目表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_class_product`
--

CREATE TABLE `osx_column_class_product` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '商品id',
  `cid` int(11) NOT NULL COMMENT '栏目id',
  `sort` int(11) NOT NULL COMMENT '当前栏目内排序排序',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识付费栏目商品表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_collect`
--

CREATE TABLE `osx_column_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `pid` int(11) NOT NULL COMMENT '产品id',
  `is_column` tinyint(4) NOT NULL COMMENT '是否是专栏',
  `is_new` tinyint(4) DEFAULT '0' COMMENT '是否有更新',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识付费收藏表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_coupon`
--

CREATE TABLE `osx_column_coupon` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '优惠券表ID',
  `title` varchar(64) NOT NULL COMMENT '优惠券名称',
  `integral` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '兑换消耗积分值',
  `coupon_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '兑换的优惠券面值',
  `use_min_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最低消费多少金额可用优惠券',
  `coupon_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券有效期限（单位：天）',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态（0：关闭，1：开启）',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '兑换项目添加时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_coupon_issue`
--

CREATE TABLE `osx_column_coupon_issue` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(10) DEFAULT NULL COMMENT '优惠券ID',
  `start_time` int(10) DEFAULT NULL COMMENT '优惠券领取开启时间',
  `end_time` int(10) DEFAULT NULL COMMENT '优惠券领取结束时间',
  `total_count` int(10) DEFAULT NULL COMMENT '优惠券领取数量',
  `remain_count` int(10) DEFAULT NULL COMMENT '优惠券剩余领取数量',
  `is_permanent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否无限张数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 正常 0 未开启 -1 已无效',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `add_time` int(10) DEFAULT NULL COMMENT '优惠券添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券前台领取表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_coupon_issue_user`
--

CREATE TABLE `osx_column_coupon_issue_user` (
  `uid` int(10) DEFAULT NULL COMMENT '领取优惠券用户ID',
  `issue_coupon_id` int(10) DEFAULT NULL COMMENT '优惠券前台领取ID',
  `add_time` int(10) DEFAULT NULL COMMENT '领取时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券前台用户领取记录表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_coupon_user`
--

CREATE TABLE `osx_column_coupon_user` (
  `id` int(11) NOT NULL COMMENT '优惠券发放记录id',
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '兑换的项目id',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券所属用户',
  `coupon_title` varchar(32) NOT NULL COMMENT '优惠券名称',
  `coupon_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '优惠券的面值',
  `use_min_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最低消费多少金额可用优惠券',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '优惠券创建时间',
  `end_time` int(11) UNSIGNED NOT NULL COMMENT '优惠券结束时间',
  `use_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '使用时间',
  `type` varchar(32) NOT NULL DEFAULT 'send' COMMENT '获取方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态（0：未使用，1：已使用, 2:已过期）',
  `is_fail` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券发放记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_product_reply`
--

CREATE TABLE `osx_column_product_reply` (
  `id` int(11) NOT NULL COMMENT '评论ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `oid` int(11) NOT NULL COMMENT '订单ID',
  `unique` char(32) NOT NULL COMMENT '唯一id',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `reply_type` varchar(32) NOT NULL DEFAULT 'product' COMMENT '某种商品类型(普通商品、秒杀商品）',
  `product_score` tinyint(1) NOT NULL COMMENT '商品分数',
  `service_score` tinyint(1) NOT NULL COMMENT '服务分数',
  `comment` varchar(512) NOT NULL COMMENT '评论内容',
  `pics` text NOT NULL COMMENT '评论图片',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  `merchant_reply_content` varchar(300) DEFAULT NULL COMMENT '管理员回复内容',
  `merchant_reply_time` int(11) DEFAULT NULL COMMENT '管理员回复时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未删除1已删除',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未回复1已回复'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_reply`
--

CREATE TABLE `osx_column_reply` (
  `id` int(11) NOT NULL COMMENT '评论ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '期ID',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `comment` varchar(512) NOT NULL COMMENT '评论内容',
  `pics` text NOT NULL COMMENT '评论图片',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未回复1已回复',
  `is_zan` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数量',
  `score` tinyint(2) NOT NULL DEFAULT '5' COMMENT '星级评论',
  `merchant_reply_content` varchar(300) DEFAULT '' COMMENT '管理员回复内容',
  `merchant_reply_time` int(11) DEFAULT NULL COMMENT '管理员回复时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未删除1已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_text`
--

CREATE TABLE `osx_column_text` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `pid` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品id',
  `is_column` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:专栏，0：单品',
  `is_free` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:免费，0：付费',
  `category_id` int(11) NOT NULL COMMENT '分类id',
  `author_id` int(11) NOT NULL COMMENT '作者id',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '本章名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `images` text NOT NULL COMMENT '轮播图',
  `info` varchar(255) NOT NULL COMMENT '描述',
  `introduction` text NOT NULL COMMENT '简介',
  `content` text NOT NULL COMMENT '内容或者连接地址',
  `media_url` text NOT NULL COMMENT '媒体url',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1文章、2音频、3视频',
  `m_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为云播放内容:0否,1是',
  `price` decimal(8,2) NOT NULL COMMENT '售价',
  `cost_price` decimal(8,2) NOT NULL COMMENT '成本价',
  `ot_price` decimal(8,2) NOT NULL COMMENT '市场价',
  `score` int(11) NOT NULL COMMENT '赠送积分',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示：0不显示、1显示',
  `is_trial` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否试读：0不试读、1试读',
  `read_count` int(10) DEFAULT '0' COMMENT '阅读量',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序:每期排序',
  `sales` int(11) NOT NULL COMMENT '销量',
  `ficti_sales` int(11) NOT NULL COMMENT '虚拟销量',
  `strip_num` decimal(8,2) NOT NULL COMMENT '剥比',
  `recommend_sell` tinyint(4) NOT NULL COMMENT '是否推荐分销',
  `platform_get` decimal(8,2) NOT NULL COMMENT '平台抽成',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(4) NOT NULL COMMENT '启用、禁用、删除',
  `keyword` varchar(255) NOT NULL COMMENT '关键词，多个用英文,隔开'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专栏详情表【专栏下的每一期详情】' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_column_user_buy`
--

CREATE TABLE `osx_column_user_buy` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `pid` int(11) NOT NULL COMMENT '产品id',
  `is_free` tinyint(4) NOT NULL COMMENT '是否付费',
  `is_column` tinyint(4) NOT NULL COMMENT '是否专栏',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有更新',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `read_time` int(11) NOT NULL COMMENT '最近阅读时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识付费用户已购表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_comment_census`
--

CREATE TABLE `osx_comment_census` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `seven` int(11) NOT NULL,
  `thirty` int(11) NOT NULL,
  `ninety` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_comment_report`
--

CREATE TABLE `osx_comment_report` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '举报用户',
  `to_uid` int(11) NOT NULL COMMENT '被举报用户',
  `content` text NOT NULL COMMENT '举报内容',
  `create_time` int(11) NOT NULL COMMENT '投诉时间',
  `reason` int(11) NOT NULL COMMENT '投诉原因',
  `status` int(11) NOT NULL COMMENT '状态',
  `is_deal` int(11) NOT NULL DEFAULT '0' COMMENT '是否处理'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_comment_template`
--

CREATE TABLE `osx_comment_template` (
  `id` int(11) NOT NULL COMMENT 'id',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_community_count`
--

CREATE TABLE `osx_community_count` (
  `id` int(11) NOT NULL COMMENT 'id',
  `time` int(11) NOT NULL COMMENT '时间',
  `forum` int(11) NOT NULL COMMENT '发帖数量',
  `comment` int(11) NOT NULL COMMENT '评论数量',
  `support` int(11) NOT NULL COMMENT '点赞数量',
  `share` int(11) NOT NULL COMMENT '分享数量',
  `reward` int(11) NOT NULL COMMENT '打赏数量'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_community_count`
--

INSERT INTO `osx_community_count` (`id`, `time`, `forum`, `comment`, `support`, `share`, `reward`) VALUES
(1, 1587571200, 0, 0, 0, 0, 0),
(2, 1599580800, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_adv`
--

CREATE TABLE `osx_com_adv` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1- 首页顶部 2-首页 3-社区 4-帖子详情 5-个人中心',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `pic` varchar(255) NOT NULL DEFAULT '0' COMMENT '图片',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '显示顺序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 启用 0 禁用',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `jump_page` int(11) NOT NULL DEFAULT '1' COMMENT '跳转页面：1帖子详情；2商品详情；3公告详情；',
  `jump_id` int(11) NOT NULL DEFAULT '0' COMMENT '跳转页面id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_com_adv`
--

INSERT INTO `osx_com_adv` (`id`, `type`, `name`, `pic`, `url`, `sort`, `status`, `create_time`, `update_time`, `jump_page`, `jump_id`) VALUES
(1, 1, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb96073277f.png', '', 1, 1, 1572576867, 1572576867, 1, 1),
(2, 1, '2', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9607867ce.png', '', 2, 1, 1572576940, 1572576940, 1, 1),
(3, 2, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb960768617.png', '', 1, 1, 1572576963, 1572576963, 1, 1),
(4, 3, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb96075406e.png', '', 1, 1, 1572576982, 1572576982, 1, 1),
(5, 3, '2', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9607867ce.png', '', 2, 1, 1572577005, 1572577005, 1, 1),
(6, 4, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb961b47850.png', '', 1, 1, 1572577030, 1572577030, 1, 1),
(7, 6, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb95f1a210f.png', '', 1, 1, 1572577078, 1572577078, 2, 1),
(8, 6, '2', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb95f23974b.png', '', 2, 1, 1572577107, 1572577107, 2, 1),
(9, 8, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb95f28fedd.png', '', 1, 1, 1572577128, 1572577128, 2, 1),
(10, 7, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb95f181b7c.png', '', 1, 1, 1572577148, 1572577148, 2, 1),
(11, 7, '2', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb95f14eb37.png', '', 1, 1, 1572577160, 1572577160, 2, 1),
(12, 5, '1', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb961b5e9e8.png', '', 1, 1, 1572577178, 1572577178, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_adv_platform`
--

CREATE TABLE `osx_com_adv_platform` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `adv_id` int(11) NOT NULL DEFAULT '0' COMMENT '广告表ID',
  `platform` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '展现平台，1=微信小程序（iOS），2=微信小程序（Android），3=iOS App，4=Android App，5=H5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告平台表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_announce`
--

CREATE TABLE `osx_com_announce` (
  `id` int(11) NOT NULL COMMENT '公告id',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '主题id',
  `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '版块id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '公告标题',
  `content` text NOT NULL COMMENT '公告内容',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '发布人',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '阅读时间',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '公告开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '公告结束时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '显示顺序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公告表，添加一条公告，需要同时向announce、post、thread三个表添加一条记录' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_announce_user`
--

CREATE TABLE `osx_com_announce_user` (
  `id` int(11) NOT NULL COMMENT 'id',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '主题id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '阅读时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公告阅读记录-每个公告的tid唯一，对应主题id' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_draft`
--

CREATE TABLE `osx_com_draft` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级版块id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主题类型,1.普通版面,2.微博,3.朋友圈,4.资讯,5.活动,6.视频横版,7.视频竖版,8.公告',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件id',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `cover` varchar(255) NOT NULL DEFAULT '0' COMMENT '主题封面',
  `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '主题分类',
  `summary` varchar(255) NOT NULL COMMENT '帖子摘要',
  `image` text NOT NULL COMMENT '三张图地址',
  `product_id` text NOT NULL COMMENT '商品id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum`
--

CREATE TABLE `osx_com_forum` (
  `id` int(11) NOT NULL COMMENT '版块id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级版块id',
  `name` char(50) NOT NULL DEFAULT '' COMMENT '版块名称',
  `title` text NOT NULL COMMENT '版块标语',
  `content` text NOT NULL COMMENT '版块规则',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主题类型,1.普通版面,2.微博,3.朋友圈,4.资讯,5.活动,6.视频横版,7.视频竖版,8.公告',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态 0：禁用，1：启用，2：未审核，-1：删除',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `thread_count` int(11) NOT NULL DEFAULT '0' COMMENT '分类数量',
  `logo` varchar(500) NOT NULL COMMENT '版块logo',
  `background` varchar(500) NOT NULL COMMENT '版块背景图',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `allow_user_group` varchar(500) NOT NULL DEFAULT '' COMMENT '允许操作的用户组',
  `summary` text NOT NULL COMMENT '版块描述',
  `admin_uid` text NOT NULL COMMENT '版主uid',
  `last_post_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表时间',
  `level` smallint(3) NOT NULL DEFAULT '0' COMMENT '版块层级',
  `allow_edit_rules` tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许版主修改论坛规则',
  `allow_feed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许推送动态',
  `need_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发帖是否需要审核',
  `list_style` tinyint(1) NOT NULL DEFAULT '0' COMMENT '水平横排设置 0：竖排，1：横排',
  `allow_edit_post` tinyint(1) NOT NULL DEFAULT '0' COMMENT '允许编辑帖子',
  `allow_global_stick` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示全局置顶',
  `member_count` int(11) NOT NULL DEFAULT '0' COMMENT '版块成员数量',
  `share_count` int(11) NOT NULL DEFAULT '0' COMMENT '版块分享次数',
  `post_count` int(11) NOT NULL DEFAULT '0' COMMENT '帖子数量',
  `is_private` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否私密',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '版块点赞数',
  `is_hot` int(11) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `false_num` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟关注人数',
  `display` int(11) NOT NULL DEFAULT '1' COMMENT '1启用 0禁用  针对前台展示',
  `allow_post` int(11) NOT NULL DEFAULT '1' COMMENT '允许评论',
  `default_follow` int(11) DEFAULT '0' COMMENT '用户注册时是否默认关注',
  `is_audit` int(11) NOT NULL DEFAULT '1' COMMENT '是否自动审核1开启0关闭',
  `group` text NOT NULL COMMENT '用户组'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版块表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_com_forum`
--

INSERT INTO `osx_com_forum` (`id`, `pid`, `name`, `title`, `content`, `type`, `status`, `sort`, `thread_count`, `logo`, `background`, `create_time`, `update_time`, `allow_user_group`, `summary`, `admin_uid`, `last_post_time`, `level`, `allow_edit_rules`, `allow_feed`, `need_verify`, `list_style`, `allow_edit_post`, `allow_global_stick`, `member_count`, `share_count`, `post_count`, `is_private`, `support_count`, `is_hot`, `false_num`, `display`, `allow_post`, `default_follow`, `is_audit`, `group`) VALUES
(1, 0, '官方一区', '', '', 1, 1, 0, 0, '', '', 0, 0, '', '这里是官方一区', '1', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, ''),
(2, 0, '官方二区', '', '', 1, 1, 2, 0, '', '', 0, 1572571261, '', '这里是官方二区', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, ''),
(3, 1, '官方动态', '', '', 1, 1, 0, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8aa233318.png', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8a8dabacb.png', 1572573935, 1572573958, '', '这里是官方动态', '1', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 0, 1, ''),
(4, 2, '行业交流', '', '', 4, 1, 0, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8aa15c773.png', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8a8e068e6.png', 1572574017, 1572574049, '', '这里是行业交流', '1', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 1, 0, 1, ''),
(5, 2, '行业资讯', '', '', 4, 1, 0, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8aa212fd1.png', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb8a8e47408.png', 1572574040, 1572574045, '', '这里是行业资讯', '1', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum_admin`
--

CREATE TABLE `osx_com_forum_admin` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `level` int(11) NOT NULL COMMENT '权限级别：1，普通版主；2，超级版主；',
  `fid` int(11) NOT NULL COMMENT '管理版块',
  `admin` int(11) NOT NULL COMMENT '操作人',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `top` int(11) NOT NULL COMMENT '置顶数',
  `essence` int(11) NOT NULL COMMENT '加精数',
  `recommend` int(11) NOT NULL COMMENT '推荐数',
  `light` int(11) NOT NULL COMMENT '加粗数',
  `del` int(11) NOT NULL COMMENT '删除数',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版主表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum_admin_apply`
--

CREATE TABLE `osx_com_forum_admin_apply` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL COMMENT '版块id',
  `content` text NOT NULL COMMENT '申请理由',
  `create_time` int(11) NOT NULL COMMENT '申请时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `level` int(11) NOT NULL COMMENT '申请级别'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版主申请表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum_admin_score`
--

CREATE TABLE `osx_com_forum_admin_score` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1：版主；2：超级版主；3：前端管理员；',
  `info` text NOT NULL COMMENT '上限设置信息',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_del` tinyint(4) NOT NULL COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版主奖励设置表';

--
-- 转存表中的数据 `osx_com_forum_admin_score`
--

INSERT INTO `osx_com_forum_admin_score` (`id`, `type`, `info`, `status`, `is_del`) VALUES
(1, 1, '[{\"flag\":\"gong\",\"name\":\"\\u8d21\\u732e\\u503c\",\"num\":\"100\",\"score\":{\"id\":\"2\",\"name\":\"\\u8d21\\u732e\\u503c\",\"leixing\":\"1\",\"danwei\":\"\\u70b9\",\"explain\":\"\\u8d21\\u732e\\u503c\\u8bf4\\u660e\",\"flag\":\"gong\",\"status\":\"1\",\"is_del\":\"0\"}},{\"flag\":\"exp\",\"name\":\"\\u7ecf\\u9a8c\\u503c\",\"num\":\"50\",\"score\":{\"id\":\"1\",\"name\":\"\\u7ecf\\u9a8c\\u503c\",\"leixing\":\"1\",\"danwei\":\"\\u70b9\",\"explain\":\"\\u7ecf\\u9a8c\\u503c\\u8bf4\\u660e\",\"flag\":\"exp\",\"status\":\"1\",\"is_del\":\"0\"}},{\"flag\":\"one\",\"name\":\"\\u60f3\\u5929\\u70b9\",\"num\":\"50\"}]', 1, 0),
(2, 2, '[{\"flag\":\"gong\",\"name\":\"\\u8d21\\u732e\\u503c\",\"num\":\"100\",\"score\":{\"id\":\"2\",\"name\":\"\\u8d21\\u732e\\u503c\",\"leixing\":\"1\",\"danwei\":\"\\u70b9\",\"explain\":\"\\u8d21\\u732e\\u503c\\u8bf4\\u660e\",\"flag\":\"gong\",\"status\":\"1\",\"is_del\":\"0\"}},{\"flag\":\"exp\",\"name\":\"\\u7ecf\\u9a8c\\u503c\",\"num\":\"50\",\"score\":{\"id\":\"1\",\"name\":\"\\u7ecf\\u9a8c\\u503c\",\"leixing\":\"1\",\"danwei\":\"\\u70b9\",\"explain\":\"\\u7ecf\\u9a8c\\u503c\\u8bf4\\u660e\",\"flag\":\"exp\",\"status\":\"1\",\"is_del\":\"0\"}},{\"flag\":\"one\",\"name\":\"\\u60f3\\u5929\\u70b9\",\"num\":\"50\"}]', 1, 0),
(3, 3, '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum_admin_score_log`
--

CREATE TABLE `osx_com_forum_admin_score_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '被奖励人',
  `do_uid` int(11) NOT NULL COMMENT '奖励人',
  `from` varchar(100) NOT NULL COMMENT '来源',
  `fid` int(11) NOT NULL COMMENT '版块id',
  `tid` int(11) NOT NULL COMMENT '帖子id',
  `explain` varchar(500) NOT NULL COMMENT '奖励说明',
  `type` tinyint(4) NOT NULL COMMENT '奖励类型',
  `model` tinyint(4) NOT NULL COMMENT '1前台；2后台；',
  `exp` int(11) NOT NULL,
  `fly` int(11) NOT NULL,
  `buy` int(11) NOT NULL,
  `gong` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `two` int(11) NOT NULL,
  `three` int(11) NOT NULL,
  `four` int(11) NOT NULL,
  `five` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版主奖励记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_forum_member`
--

CREATE TABLE `osx_com_forum_member` (
  `id` int(11) NOT NULL COMMENT 'id',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '主题id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `audit_time` int(11) NOT NULL COMMENT '审核时间',
  `reason` text NOT NULL COMMENT '申请理由',
  `count` int(11) NOT NULL COMMENT '申请数量',
  `audit_uid` int(11) NOT NULL COMMENT '审核用户uid',
  `reject_resaon` int(11) NOT NULL COMMENT '拒绝理由',
  `is_admin` int(11) NOT NULL COMMENT '是否是后台管理员'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户加入版块记录表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_nav`
--

CREATE TABLE `osx_com_nav` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1- 底部 2-首页 3-商城',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `icon` varchar(255) NOT NULL DEFAULT '0' COMMENT '图标',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '显示顺序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 启用 0 禁用',
  `jump_page` int(11) NOT NULL DEFAULT '1' COMMENT '跳转页面：1首页；2社区；3版块主页；4商品首页；5商品分类；6限时秒杀；7超值拼团；8领券中心；9砍价；10我的；11签到；12任务中心；13会员中心',
  `jump_id` int(11) NOT NULL DEFAULT '0' COMMENT '跳转id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_com_nav`
--

INSERT INTO `osx_com_nav` (`id`, `type`, `name`, `icon`, `url`, `sort`, `status`, `jump_page`, `jump_id`) VALUES
(1, 2, '版块列表', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb98a3bf432.png', '', 1, 1, 2, 0),
(2, 2, '官方动态', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb98a3e5725.png', '', 2, 1, 3, 3),
(3, 2, '行业交流', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb98a3d431c.png', '', 3, 1, 3, 4),
(4, 2, '每日签到', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb98a40205d.png', '', 4, 1, 11, 0),
(5, 3, '商品分类', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbbed8136cb2.png', '', 1, 1, 5, 0),
(6, 3, '领券中心', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbbed8119a82.png', '', 2, 1, 8, 0),
(8, 3, '限时秒杀', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbbed81536bd.png', '', 3, 1, 6, 0),
(9, 3, '赚取积分', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbbed8145184.png', '', 4, 1, 12, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_post`
--

CREATE TABLE `osx_com_post` (
  `id` int(11) NOT NULL COMMENT '帖子id',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '版块id',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '主题id，如果是评论，这里记录回复帖子的id',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复楼中楼帖子id',
  `to_reply_uid` int(11) NOT NULL DEFAULT '0' COMMENT '回复帖子uid',
  `is_thread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是主题',
  `level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '层级,0表示主题，1表示一级评论，2表示楼中楼评论',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '楼层，一级评论才有，取当前主题楼层最大值加1',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '作者id',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '发表时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件id',
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否匿名',
  `content` text NOT NULL COMMENT '帖子内容',
  `is_top` tinyint(3) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '回复数量',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数量',
  `from` varchar(40) NOT NULL DEFAULT '' COMMENT '发布来源',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回复可见',
  `is_essence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `collect_count` int(11) NOT NULL DEFAULT '0' COMMENT '帖子收藏数',
  `image` text NOT NULL COMMENT '三张图地址',
  `del_time` int(11) NOT NULL COMMENT '删除时间',
  `del_user` varchar(50) NOT NULL COMMENT '删除人',
  `is_vest` int(11) NOT NULL DEFAULT '0' COMMENT '是否是马甲',
  `event_id` int(11) NOT NULL COMMENT '活动评论id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_com_post`
--

INSERT INTO `osx_com_post` (`id`, `fid`, `tid`, `to_reply_id`, `to_reply_uid`, `is_thread`, `level`, `position`, `author_uid`, `title`, `create_time`, `update_time`, `status`, `attachment_id`, `is_anonymous`, `content`, `is_top`, `comment_count`, `support_count`, `from`, `is_hide`, `is_essence`, `is_recommend`, `collect_count`, `image`, `del_time`, `del_user`, `is_vest`, `event_id`) VALUES
(1, 3, 1, 0, 0, 1, 0, 0, 1, '欢迎来到新的世界', 1572576407, 1572576407, 1, 0, 0, '<p>您好，欢迎使用由想天软件提供的OSX整合运营系统，在使用系统前请详细阅读《产品手册》，如有问题，欢迎随时联系客服哦！</p><p>联系电话：400-0573080</p><p><br/></p><p><img src=\"https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png\"/></p>', 0, 0, 0, 'HouTai', 0, 0, 1, 0, 'https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png\"', 0, '', 0, 0),
(2, 4, 2, 0, 0, 1, 0, 0, 1, '欢迎来到OSX的世界', 1572586622, 1572586622, 1, 0, 0, '<p style=\"white-space: normal;\">您好，欢迎使用由想天软件提供的OSX整合运营系统，在使用系统前请详细阅读《产品手册》，如有问题，欢迎随时联系客服哦！</p><p style=\"white-space: normal;\">联系电话：400-0573080</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal;\"><img src=\"https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png\"/></p>', 0, 0, 0, 'HouTai', 0, 0, 1, 0, 'https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png\"', 0, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_site`
--

CREATE TABLE `osx_com_site` (
  `id` int(11) NOT NULL,
  `forum_name` text NOT NULL,
  `user_name` text NOT NULL,
  `new_on` int(11) NOT NULL,
  `hot_on` int(11) NOT NULL,
  `threshold` int(11) NOT NULL,
  `read_census` int(11) NOT NULL DEFAULT '0' COMMENT '阅读量统计类型',
  `com_thread_name` text NOT NULL COMMENT '帖子名称',
  `weibo_name` text NOT NULL COMMENT '动态名称',
  `news_name` text NOT NULL COMMENT '资讯名称',
  `video_name` text NOT NULL COMMENT '视频名称',
  `hot_icon` int(11) NOT NULL COMMENT '热帖图标',
  `recommend_icon` int(11) NOT NULL COMMENT '推荐',
  `essence_icon` int(11) NOT NULL COMMENT '加精图标'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='社区设置表';

--
-- 转存表中的数据 `osx_com_site`
--

INSERT INTO `osx_com_site` (`id`, `forum_name`, `user_name`, `new_on`, `hot_on`, `threshold`, `read_census`, `com_thread_name`, `weibo_name`, `news_name`, `video_name`, `hot_icon`, `recommend_icon`, `essence_icon`) VALUES
(1, '版块', '版主', 1, 1, 200, 0, '帖子', '动态', '资讯', '视频', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_thread`
--

CREATE TABLE `osx_com_thread` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级版块id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主题类型,1.普通版面,2.微博,3.朋友圈,4.资讯,5.活动,6.视频横版,7.视频竖版,8.公告',
  `is_announce` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是公告，和type=8重复，为了后续构造查询条件方便',
  `post_id` int(11) NOT NULL DEFAULT '0' COMMENT '帖子表中的id',
  `read_perm` tinyint(3) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '发表时间',
  `last_post_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表时间',
  `last_post_uid` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表人id',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `reply_count` int(11) NOT NULL DEFAULT '0' COMMENT '回复次数',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `share_count` int(11) NOT NULL DEFAULT '0' COMMENT '分享次数',
  `collect_count` int(11) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `high_light` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否高亮',
  `is_essence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件id',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被管理员改动',
  `stick_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有回帖置顶',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `cover` varchar(255) NOT NULL DEFAULT '0' COMMENT '主题封面',
  `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '主题分类',
  `summary` varchar(255) NOT NULL COMMENT '帖子摘要',
  `image` text NOT NULL COMMENT '三张图地址',
  `from` varchar(40) NOT NULL DEFAULT '' COMMENT '发布来源',
  `pos` varchar(60) NOT NULL DEFAULT '' COMMENT '地点',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '帖子位置,暂时没用到',
  `product_id` text NOT NULL COMMENT '商品id',
  `is_massage` int(11) NOT NULL DEFAULT '0' COMMENT '是否是营销消息：1是；0不是',
  `video_id` varchar(100) NOT NULL DEFAULT '' COMMENT '视频腾讯云上的id',
  `video_url` text NOT NULL COMMENT '视频地址',
  `audio_id` text NOT NULL COMMENT '音频id',
  `audio_url` text NOT NULL COMMENT '音频地址',
  `audio_time` int(11) NOT NULL COMMENT '音频时长',
  `reject_reason` text NOT NULL COMMENT '驳回理由',
  `delete_reason` text NOT NULL COMMENT '删除理由',
  `is_recommend` int(11) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `recommend_time` int(11) NOT NULL COMMENT '推荐时间',
  `recommend_end_time` int(11) NOT NULL COMMENT '推荐到期时间',
  `recommend_uid` int(11) NOT NULL COMMENT '推荐人uid',
  `top_time` int(11) NOT NULL COMMENT '置顶时间',
  `top_end_time` int(11) NOT NULL COMMENT '置顶到期时间',
  `top_uid` int(11) NOT NULL COMMENT '置顶人uid',
  `index_top_time` int(11) NOT NULL COMMENT '首页置顶时间',
  `index_top_end_time` int(11) NOT NULL COMMENT '首页置顶到期时间',
  `index_top_uid` int(11) NOT NULL COMMENT '首页置顶人uid',
  `detail_top_time` int(11) NOT NULL COMMENT '详情置顶时间',
  `detail_top_end_time` int(11) NOT NULL COMMENT '详情置顶到期时间',
  `detail_top_uid` int(11) NOT NULL COMMENT '详情置顶人uid',
  `light_time` int(11) NOT NULL COMMENT '加粗时间',
  `light_end_time` int(11) NOT NULL COMMENT '加粗到期时间',
  `essence_uid` int(11) NOT NULL COMMENT '加精人uid',
  `essence_time` int(11) NOT NULL COMMENT '加精时间',
  `operation_uid` int(11) NOT NULL COMMENT '操作人uid',
  `operation_identity` int(11) NOT NULL COMMENT '操作人身份',
  `send_time` int(11) NOT NULL COMMENT '发布时间',
  `column_id` varchar(500) NOT NULL COMMENT '分享知识付费商品id',
  `video_cover` varchar(200) NOT NULL DEFAULT '' COMMENT '视频封面（可无）',
  `del_user` varchar(50) NOT NULL COMMENT '删帖人',
  `false_view` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟浏览量',
  `is_weibo` int(11) NOT NULL DEFAULT '0' COMMENT '是否是动态',
  `detail_top` int(11) NOT NULL DEFAULT '0' COMMENT '详情置顶',
  `index_top` int(11) NOT NULL DEFAULT '0' COMMENT '首页置顶',
  `is_new` int(11) DEFAULT '0' COMMENT '是否最新',
  `oid` text NOT NULL COMMENT '话题id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_com_thread`
--

INSERT INTO `osx_com_thread` (`id`, `fid`, `type`, `is_announce`, `post_id`, `read_perm`, `author_uid`, `title`, `content`, `create_time`, `last_post_time`, `last_post_uid`, `update_time`, `view_count`, `reply_count`, `support_count`, `share_count`, `collect_count`, `sort`, `high_light`, `is_essence`, `is_top`, `attachment_id`, `is_verify`, `stick_reply`, `status`, `cover`, `class_id`, `summary`, `image`, `from`, `pos`, `position`, `product_id`, `is_massage`, `video_id`, `video_url`, `audio_id`, `audio_url`, `audio_time`, `reject_reason`, `delete_reason`, `is_recommend`, `recommend_time`, `recommend_end_time`, `recommend_uid`, `top_time`, `top_end_time`, `top_uid`, `index_top_time`, `index_top_end_time`, `index_top_uid`, `detail_top_time`, `detail_top_end_time`, `detail_top_uid`, `light_time`, `light_end_time`, `essence_uid`, `essence_time`, `operation_uid`, `operation_identity`, `send_time`, `column_id`, `video_cover`, `del_user`, `false_view`, `is_weibo`, `detail_top`, `index_top`, `is_new`, `oid`) VALUES
(1, 3, 1, 0, 1, 0, 1, '欢迎来到OSX的世界', '\"<p>\\u60a8\\u597d\\uff0c\\u6b22\\u8fce\\u4f7f\\u7528\\u7531\\u60f3\\u5929\\u8f6f\\u4ef6\\u63d0\\u4f9b\\u7684OSX\\u6574\\u5408\\u8fd0\\u8425\\u7cfb\\u7edf\\uff0c\\u5728\\u4f7f\\u7528\\u7cfb\\u7edf\\u524d\\u8bf7\\u8be6\\u7ec6\\u9605\\u8bfb\\u300a\\u4ea7\\u54c1\\u624b\\u518c\\u300b\\uff0c\\u5982\\u6709\\u95ee\\u9898\\uff0c\\u6b22\\u8fce\\u968f\\u65f6\\u8054\\u7cfb\\u5ba2\\u670d\\u54e6\\uff01<\\/p><p>\\u8054\\u7cfb\\u7535\\u8bdd\\uff1a400-0573080<\\/p><p><br\\/><\\/p><p><img src=\\\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/editor\\/20191101\\/5dbb9c89ce28e.png\\\"\\/><\\/p>\"', 1572576407, 0, 0, 1572576449, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, '0', 1, '您好，欢迎使用由想天软件提供的OSX整合运营系统，在使用系统前请详细阅读《产品手', 'https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png', 'HouTai', '', 0, '', 0, '', '', '', '', 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1572576407, '', '', '', 0, 0, 0, 0, 0, '0'),
(2, 4, 4, 0, 2, 0, 1, '欢迎来到OSX的世界', '\"<p style=\\\"white-space: normal;\\\">\\u60a8\\u597d\\uff0c\\u6b22\\u8fce\\u4f7f\\u7528\\u7531\\u60f3\\u5929\\u8f6f\\u4ef6\\u63d0\\u4f9b\\u7684OSX\\u6574\\u5408\\u8fd0\\u8425\\u7cfb\\u7edf\\uff0c\\u5728\\u4f7f\\u7528\\u7cfb\\u7edf\\u524d\\u8bf7\\u8be6\\u7ec6\\u9605\\u8bfb\\u300a\\u4ea7\\u54c1\\u624b\\u518c\\u300b\\uff0c\\u5982\\u6709\\u95ee\\u9898\\uff0c\\u6b22\\u8fce\\u968f\\u65f6\\u8054\\u7cfb\\u5ba2\\u670d\\u54e6\\uff01<\\/p><p style=\\\"white-space: normal;\\\">\\u8054\\u7cfb\\u7535\\u8bdd\\uff1a400-0573080<\\/p><p style=\\\"white-space: normal;\\\"><br\\/><\\/p><p style=\\\"white-space: normal;\\\"><img src=\\\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/editor\\/20191101\\/5dbb9c89ce28e.png\\\"\\/><\\/p>\"', 1572586622, 0, 0, 1572586622, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9d57affec.jpg', 3, '您好，欢迎使用由想天软件提供的OSX整合运营系统，在使用系统前请详细阅读《产品手', 'https://newosx.demo.opensns.cn/public/uploads/editor/20191101/5dbb9c89ce28e.png\"', 'HouTai', '', 0, '', 0, '', '', '', '', 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1572586622, '', '', '', 0, 0, 0, 0, 0, '0');

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_thread_class`
--

CREATE TABLE `osx_com_thread_class` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '所属版块id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `sort` mediumint(9) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `icon` varchar(255) NOT NULL COMMENT '图标url',
  `moderators` tinyint(4) NOT NULL COMMENT '是否仅管理员可用',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '分类状态1.正常-1删除',
  `summary` varchar(255) NOT NULL COMMENT '分类描述',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子分类表';

--
-- 转存表中的数据 `osx_com_thread_class`
--

INSERT INTO `osx_com_thread_class` (`id`, `fid`, `name`, `sort`, `icon`, `moderators`, `status`, `summary`, `create_time`, `update_time`) VALUES
(1, 3, '分类1', 1, '', 0, 1, '', 2019, 2019),
(2, 3, '分类2', 0, '', 0, 1, '', 2019, 2019),
(3, 4, '分类3', 0, '', 0, 1, '', 2019, 2019),
(4, 4, '分类4', 0, '', 0, 1, '', 2019, 2019),
(5, 5, '分类5', 0, '', 0, 1, '', 2019, 2019),
(6, 5, '分类6', 0, '', 0, 1, '', 2019, 2019);

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_thread_draft`
--

CREATE TABLE `osx_com_thread_draft` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级版块id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主题类型,1.普通版面,2.微博,3.朋友圈,4.资讯,5.活动,6.视频横版,7.视频竖版,8.公告',
  `is_announce` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是公告，和type=8重复，为了后续构造查询条件方便',
  `post_id` int(11) NOT NULL DEFAULT '0' COMMENT '帖子表中的id',
  `read_perm` tinyint(3) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '发表时间',
  `last_post_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表时间',
  `last_post_uid` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表人id',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `reply_count` int(11) NOT NULL DEFAULT '0' COMMENT '回复次数',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `share_count` int(11) NOT NULL DEFAULT '0' COMMENT '分享次数',
  `collect_count` int(11) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `high_light` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否高亮',
  `is_essence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件id',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被管理员改动',
  `stick_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有回帖置顶',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `cover` varchar(255) NOT NULL DEFAULT '0' COMMENT '主题封面',
  `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '主题分类',
  `summary` varchar(255) NOT NULL COMMENT '帖子摘要',
  `image` text NOT NULL COMMENT '三张图地址',
  `from` varchar(40) NOT NULL DEFAULT '' COMMENT '发布来源',
  `pos` varchar(60) NOT NULL DEFAULT '' COMMENT '地点',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '帖子位置,暂时没用到',
  `product_id` text NOT NULL COMMENT '商品id',
  `is_massage` int(11) NOT NULL DEFAULT '0' COMMENT '是否是营销消息：1是；0不是',
  `video_id` varchar(100) NOT NULL DEFAULT '' COMMENT '视频腾讯云上的id',
  `video_url` text NOT NULL COMMENT '视频地址',
  `reject_reason` text NOT NULL COMMENT '驳回理由',
  `delete_reason` text NOT NULL COMMENT '删除理由',
  `video_cover` varchar(200) NOT NULL DEFAULT '' COMMENT '视频封面（可无）',
  `del_user` varchar(50) NOT NULL COMMENT '删帖人',
  `false_view` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟浏览量',
  `is_weibo` int(11) NOT NULL DEFAULT '0' COMMENT '是否是动态',
  `detail_top` int(11) NOT NULL DEFAULT '0' COMMENT '详情置顶',
  `index_top` int(11) NOT NULL DEFAULT '0' COMMENT '首页置顶',
  `is_new` int(11) DEFAULT '0' COMMENT '是否最新',
  `oid` text NOT NULL COMMENT '话题id',
  `send_time` int(11) NOT NULL COMMENT '发布时间',
  `keywords` text NOT NULL COMMENT '关键词'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_topic`
--

CREATE TABLE `osx_com_topic` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL COMMENT '标题',
  `summary` text NOT NULL COMMENT '简介',
  `image` text NOT NULL COMMENT '封面',
  `uid` int(11) NOT NULL COMMENT '发起人',
  `class_id` int(11) NOT NULL COMMENT '分类',
  `is_hot` int(11) NOT NULL DEFAULT '0' COMMENT '是否热门',
  `hot_time` int(11) NOT NULL COMMENT '热门设置时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '回帖时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读数',
  `follow_count` int(11) NOT NULL DEFAULT '0' COMMENT '关注数',
  `post_count` int(11) NOT NULL DEFAULT '0' COMMENT '讨论数',
  `seo_title` text NOT NULL COMMENT 'SEO标题',
  `seo_key` text NOT NULL COMMENT 'SEO关键词',
  `seo_summary` text NOT NULL COMMENT 'SEO描述',
  `hot_end_time` int(11) NOT NULL COMMENT '热门推荐到期时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='话题表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_topic_class`
--

CREATE TABLE `osx_com_topic_class` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '分类名称',
  `topic_count` int(11) NOT NULL DEFAULT '0' COMMENT '话题数',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='话题分类表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_com_topic_follow`
--

CREATE TABLE `osx_com_topic_follow` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL COMMENT '话题id',
  `uid` int(11) NOT NULL COMMENT '关注人',
  `status` int(11) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '关注时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='话题关注表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_event`
--

CREATE TABLE `osx_event` (
  `id` int(11) NOT NULL COMMENT 'id',
  `cate_id` int(11) NOT NULL COMMENT '分类id',
  `cate_pid` int(11) NOT NULL COMMENT '分类的父级id',
  `title` varchar(100) NOT NULL COMMENT '活动主题',
  `uid` int(11) NOT NULL COMMENT '发起人',
  `cover` text NOT NULL COMMENT '海报',
  `forum_id` int(11) NOT NULL COMMENT '所属板块',
  `type` tinyint(4) NOT NULL COMMENT '活动类型0线上活动1线下活动',
  `start_time` int(11) NOT NULL COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL COMMENT '活动结束时间',
  `address` varchar(255) NOT NULL COMMENT '活动地点',
  `detailed_address` varchar(255) NOT NULL COMMENT '详细地址',
  `enroll_start_time` int(11) NOT NULL COMMENT '报名开始时间',
  `enroll_end_time` int(11) NOT NULL COMMENT '报名结束时间',
  `enroll_count` int(11) NOT NULL COMMENT '报名人数',
  `enroll_range` tinyint(4) NOT NULL COMMENT '报名范围0代表全部用户1代表活动所在的版块2指定用户组',
  `price` decimal(10,2) NOT NULL COMMENT '支付价格',
  `price_type` tinyint(4) NOT NULL COMMENT '0免费 1积分 2现金',
  `is_need_check` tinyint(4) NOT NULL COMMENT '是否需要核销0不需要1需要',
  `is_recommend` tinyint(4) NOT NULL COMMENT '是否推荐0不推荐1推荐',
  `content` text NOT NULL COMMENT '活动介绍内容',
  `view` int(11) NOT NULL COMMENT '浏览量',
  `enroll_reality_count` int(11) NOT NULL COMMENT '实际报名量',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `check_count` int(11) NOT NULL DEFAULT '0' COMMENT '核销数',
  `cancel_reason` text NOT NULL COMMENT '取消理由',
  `delete_time` int(11) NOT NULL COMMENT '删除时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_bind_group`
--

CREATE TABLE `osx_event_bind_group` (
  `id` int(11) NOT NULL COMMENT 'id',
  `event_id` int(11) NOT NULL COMMENT '活动id',
  `group` varchar(255) NOT NULL COMMENT '用户组集合，以，分割',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='制定用户组绑定';

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_category`
--

CREATE TABLE `osx_event_category` (
  `id` int(11) NOT NULL COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `sort` int(11) NOT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_check`
--

CREATE TABLE `osx_event_check` (
  `id` int(11) NOT NULL COMMENT 'id',
  `event_id` int(11) NOT NULL COMMENT '活动id',
  `uid` int(11) NOT NULL COMMENT '核销员uid',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='核销员设置';

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_collect`
--

CREATE TABLE `osx_event_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `eid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '收藏时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_enroller`
--

CREATE TABLE `osx_event_enroller` (
  `id` int(11) NOT NULL COMMENT 'Id',
  `event_id` int(11) NOT NULL COMMENT '活动id',
  `uid` int(11) NOT NULL COMMENT '报名人',
  `status` tinyint(4) NOT NULL COMMENT '状态0报名1支付，等待等待核销2活动已经核销，-1活动取消且已经退还支付金额',
  `check_uid` int(11) NOT NULL COMMENT '核销员',
  `check_time` int(11) NOT NULL COMMENT '核销时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `code` varchar(100) NOT NULL COMMENT '核销码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_enroller_info`
--

CREATE TABLE `osx_event_enroller_info` (
  `id` int(11) NOT NULL COMMENT 'id',
  `event_id` int(11) NOT NULL COMMENT '活动id',
  `uid` int(11) NOT NULL COMMENT '报名人',
  `field` varchar(100) NOT NULL COMMENT '报名填写内容唯一标识',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_event_field`
--

CREATE TABLE `osx_event_field` (
  `id` int(11) NOT NULL COMMENT 'id',
  `event_id` int(11) NOT NULL COMMENT '活动id',
  `field` varchar(100) NOT NULL COMMENT '报填写内容唯一标识',
  `field_name` varchar(255) NOT NULL COMMENT '报填写内容名称',
  `is_need` tinytext NOT NULL COMMENT '是否是必填0非必填1必填',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='报名表单';

-- --------------------------------------------------------

--
-- 表的结构 `osx_express`
--

CREATE TABLE `osx_express` (
  `id` mediumint(11) UNSIGNED NOT NULL COMMENT '快递公司id',
  `code` varchar(50) NOT NULL COMMENT '快递公司简称',
  `name` varchar(50) NOT NULL COMMENT '快递公司全称',
  `sort` int(11) NOT NULL COMMENT '排序',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='快递公司表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_express`
--

INSERT INTO `osx_express` (`id`, `code`, `name`, `sort`, `is_show`) VALUES
(1, 'LIMINWL', '利民物流', 1, 0),
(2, 'XINTIAN', '鑫天顺物流', 1, 0),
(3, 'henglu', '恒路物流', 1, 0),
(4, 'klwl', '康力物流', 1, 0),
(5, 'meiguo', '美国快递', 1, 0),
(6, 'a2u', 'A2U速递', 1, 0),
(7, 'benteng', '奔腾物流', 1, 0),
(8, 'ahdf', '德方物流', 1, 0),
(9, 'timedg', '万家通', 1, 0),
(10, 'ztong', '智通物流', 1, 0),
(11, 'xindan', '新蛋物流', 1, 0),
(12, 'bgpyghx', '挂号信', 1, 0),
(13, 'XFHONG', '鑫飞鸿物流快递', 1, 0),
(14, 'ALP', '阿里物流', 1, 0),
(15, 'BFWL', '滨发物流', 1, 0),
(16, 'SJWL', '宋军物流', 1, 0),
(17, 'SHUNFAWL', '顺发物流', 1, 0),
(18, 'TIANHEWL', '天河物流', 1, 0),
(19, 'YBWL', '邮联物流', 1, 0),
(20, 'SWHY', '盛旺货运', 1, 0),
(21, 'TSWL', '汤氏物流', 1, 0),
(22, 'YUANYUANWL', '圆圆物流', 1, 0),
(23, 'BALIANGWL', '八梁物流', 1, 0),
(24, 'ZGWL', '振刚物流', 1, 0),
(25, 'JIAYU', '佳宇物流', 1, 0),
(26, 'SHHX', '昊昕物流', 1, 0),
(27, 'ande', '安得物流', 1, 0),
(28, 'ppbyb', '贝邮宝', 1, 0),
(29, 'dida', '递达快递', 1, 0),
(30, 'jppost', '日本邮政', 1, 0),
(31, 'intmail', '中国邮政', 96, 0),
(32, 'HENGCHENGWL', '恒诚物流', 1, 0),
(33, 'HENGFENGWL', '恒丰物流', 1, 0),
(34, 'gdems', '广东ems快递', 1, 0),
(35, 'xlyt', '祥龙运通', 1, 0),
(36, 'gjbg', '国际包裹', 1, 0),
(37, 'uex', 'UEX', 1, 0),
(38, 'singpost', '新加坡邮政', 1, 0),
(39, 'guangdongyouzhengwuliu', '广东邮政', 1, 0),
(40, 'bht', 'BHT', 1, 0),
(41, 'cces', 'CCES快递', 1, 0),
(42, 'cloudexpress', 'CE易欧通国际速递', 1, 0),
(43, 'dasu', '达速物流', 1, 0),
(44, 'pfcexpress', '皇家物流', 1, 0),
(45, 'hjs', '猴急送', 1, 0),
(46, 'huilian', '辉联物流', 1, 0),
(47, 'huanqiu', '环球速运', 1, 0),
(48, 'huada', '华达快运', 1, 0),
(49, 'htwd', '华通务达物流', 1, 0),
(50, 'hipito', '海派通', 1, 0),
(51, 'hqtd', '环球通达', 1, 0),
(52, 'airgtc', '航空快递', 1, 0),
(53, 'haoyoukuai', '好又快物流', 1, 0),
(54, 'hanrun', '韩润物流', 1, 0),
(55, 'ccd', '河南次晨达', 1, 0),
(56, 'hfwuxi', '和丰同城', 1, 0),
(57, 'Sky', '荷兰', 1, 0),
(58, 'hongxun', '鸿讯物流', 1, 0),
(59, 'hongjie', '宏捷国际物流', 1, 0),
(60, 'httx56', '汇通天下物流', 1, 0),
(61, 'lqht', '恒通快递', 1, 0),
(62, 'jinguangsudikuaijian', '京广速递快件', 1, 0),
(63, 'junfengguoji', '骏丰国际速递', 1, 0),
(64, 'jiajiatong56', '佳家通', 1, 0),
(65, 'jrypex', '吉日优派', 1, 0),
(66, 'jinchengwuliu', '锦程国际物流', 1, 0),
(67, 'jgwl', '景光物流', 1, 0),
(68, 'pzhjst', '急顺通', 1, 0),
(69, 'ruexp', '捷网俄全通', 1, 0),
(70, 'jmjss', '金马甲', 1, 0),
(71, 'lanhu', '蓝弧快递', 1, 0),
(72, 'ltexp', '乐天速递', 1, 0),
(73, 'lutong', '鲁通快运', 1, 0),
(74, 'ledii', '乐递供应链', 1, 0),
(75, 'lundao', '论道国际物流', 1, 0),
(76, 'mailikuaidi', '麦力快递', 1, 0),
(77, 'mchy', '木春货运', 1, 0),
(78, 'meiquick', '美快国际物流', 1, 0),
(79, 'valueway', '美通快递', 1, 0),
(80, 'nuoyaao', '偌亚奥国际', 1, 0),
(81, 'euasia', '欧亚专线', 1, 0),
(82, 'pca', '澳大利亚PCA快递', 1, 0),
(83, 'pingandatengfei', '平安达腾飞', 1, 0),
(84, 'pjbest', '品骏快递', 1, 0),
(85, 'qbexpress', '秦邦快运', 1, 0),
(86, 'quanxintong', '全信通快递', 1, 0),
(87, 'quansutong', '全速通国际快递', 1, 0),
(88, 'qinyuan', '秦远物流', 1, 0),
(89, 'qichen', '启辰国际物流', 1, 0),
(90, 'quansu', '全速快运', 1, 0),
(91, 'qzx56', '全之鑫物流', 1, 0),
(92, 'qskdyxgs', '千顺快递', 1, 0),
(93, 'runhengfeng', '全时速运', 1, 0),
(94, 'rytsd', '日益通速递', 1, 0),
(95, 'ruidaex', '瑞达国际速递', 1, 0),
(96, 'shiyun', '世运快递', 1, 0),
(97, 'sfift', '十方通物流', 1, 0),
(98, 'stkd', '顺通快递', 1, 0),
(99, 'bgn', '布谷鸟快递', 1, 0),
(100, 'jiahuier', '佳惠尔快递', 1, 0),
(101, 'pingyou', '小包', 1, 0),
(102, 'yumeijie', '誉美捷快递', 1, 0),
(103, 'meilong', '美龙快递', 1, 0),
(104, 'guangtong', '广通速递', 1, 0),
(105, 'STARS', '星晨急便', 1, 0),
(106, 'NANHANG', '中国南方航空股份有限公司', 1, 0),
(107, 'lanbiao', '蓝镖快递', 1, 0),
(109, 'baotongda', '宝通达物流', 1, 0),
(110, 'dashun', '大顺物流', 1, 0),
(111, 'dada', '大达物流', 1, 0),
(112, 'fangfangda', '方方达物流', 1, 0),
(113, 'hebeijianhua', '河北建华物流', 1, 0),
(114, 'haolaiyun', '好来运快递', 1, 0),
(115, 'jinyue', '晋越快递', 1, 0),
(116, 'kuaitao', '快淘快递', 1, 0),
(117, 'peixing', '陪行物流', 1, 0),
(118, 'hkpost', '香港邮政', 1, 0),
(119, 'ytfh', '一统飞鸿快递', 1, 0),
(120, 'zhongxinda', '中信达快递', 1, 0),
(121, 'zhongtian', '中天快运', 1, 0),
(122, 'zuochuan', '佐川急便', 1, 0),
(123, 'chengguang', '程光快递', 1, 0),
(124, 'cszx', '城市之星', 1, 0),
(125, 'chuanzhi', '传志快递', 1, 0),
(126, 'feibao', '飞豹快递', 1, 0),
(127, 'huiqiang', '汇强快递', 1, 0),
(128, 'lejiedi', '乐捷递', 1, 0),
(129, 'lijisong', '成都立即送快递', 1, 0),
(130, 'minbang', '民邦速递', 1, 0),
(131, 'ocs', 'OCS国际快递', 1, 0),
(132, 'santai', '三态速递', 1, 0),
(133, 'saiaodi', '赛澳递', 1, 0),
(134, 'jingdong', '京东快递', 1, 0),
(135, 'zengyi', '增益快递', 1, 0),
(136, 'fanyu', '凡宇速递', 1, 0),
(137, 'fengda', '丰达快递', 1, 0),
(138, 'coe', '东方快递', 1, 0),
(139, 'ees', '百福东方快递', 1, 0),
(140, 'disifang', '递四方速递', 1, 0),
(141, 'rufeng', '如风达快递', 1, 0),
(142, 'changtong', '长通物流', 1, 0),
(143, 'chengshi100', '城市100快递', 1, 0),
(144, 'feibang', '飞邦物流', 1, 0),
(145, 'haosheng', '昊盛物流', 1, 0),
(146, 'yinsu', '音速速运', 1, 0),
(147, 'kuanrong', '宽容物流', 1, 0),
(148, 'tongcheng', '通成物流', 1, 0),
(149, 'tonghe', '通和天下物流', 1, 0),
(150, 'zhima', '芝麻开门', 1, 0),
(151, 'ririshun', '日日顺物流', 1, 0),
(152, 'anxun', '安迅物流', 1, 0),
(153, 'baiqian', '百千诚国际物流', 1, 0),
(154, 'chukouyi', '出口易', 1, 0),
(155, 'diantong', '店通快递', 1, 0),
(156, 'dajin', '大金物流', 1, 0),
(157, 'feite', '飞特物流', 1, 0),
(159, 'gnxb', '国内小包', 1, 0),
(160, 'huacheng', '华诚物流', 1, 0),
(161, 'huahan', '华翰物流', 1, 0),
(162, 'hengyu', '恒宇运通', 1, 0),
(163, 'huahang', '华航快递', 1, 0),
(164, 'jiuyi', '久易快递', 1, 0),
(165, 'jiete', '捷特快递', 1, 0),
(166, 'jingshi', '京世物流', 1, 0),
(167, 'kuayue', '跨越快递', 1, 0),
(168, 'mengsu', '蒙速快递', 1, 0),
(169, 'nanbei', '南北快递', 1, 0),
(171, 'pinganda', '平安达快递', 1, 0),
(172, 'ruifeng', '瑞丰速递', 1, 0),
(173, 'rongqing', '荣庆物流', 1, 0),
(174, 'suijia', '穗佳物流', 1, 0),
(175, 'simai', '思迈快递', 1, 0),
(176, 'suteng', '速腾快递', 1, 0),
(177, 'shengbang', '晟邦物流', 1, 0),
(178, 'suchengzhaipei', '速呈宅配', 1, 0),
(179, 'wuhuan', '五环速递', 1, 0),
(180, 'xingchengzhaipei', '星程宅配', 1, 0),
(181, 'yinjie', '顺捷丰达', 1, 0),
(183, 'yanwen', '燕文物流', 1, 0),
(184, 'zongxing', '纵行物流', 1, 0),
(185, 'aae', 'AAE快递', 1, 0),
(186, 'dhl', 'DHL快递', 1, 0),
(187, 'feihu', '飞狐快递', 1, 0),
(188, 'shunfeng', '顺丰速运', 92, 1),
(189, 'spring', '春风物流', 1, 0),
(190, 'yidatong', '易达通快递', 1, 0),
(191, 'PEWKEE', '彪记快递', 1, 0),
(192, 'PHOENIXEXP', '凤凰快递', 1, 0),
(193, 'CNGLS', 'GLS快递', 1, 0),
(194, 'BHTEXP', '华慧快递', 1, 0),
(195, 'B2B', '卡行天下', 1, 0),
(196, 'PEISI', '配思货运', 1, 0),
(197, 'SUNDAPOST', '上大物流', 1, 0),
(198, 'SUYUE', '苏粤货运', 1, 0),
(199, 'F5XM', '伍圆速递', 1, 0),
(200, 'GZWENJIE', '文捷航空速递', 1, 0),
(201, 'yuancheng', '远成物流', 1, 0),
(202, 'dpex', 'DPEX快递', 1, 0),
(203, 'anjie', '安捷快递', 1, 0),
(204, 'jldt', '嘉里大通', 1, 0),
(205, 'yousu', '优速快递', 1, 0),
(206, 'wanbo', '万博快递', 1, 0),
(207, 'sure', '速尔物流', 1, 0),
(208, 'sutong', '速通物流', 1, 0),
(209, 'JUNCHUANWL', '骏川物流', 1, 0),
(210, 'guada', '冠达快递', 1, 0),
(211, 'dsu', 'D速快递', 1, 0),
(212, 'LONGSHENWL', '龙胜物流', 1, 0),
(213, 'abc', '爱彼西快递', 1, 0),
(214, 'eyoubao', 'E邮宝', 1, 0),
(215, 'aol', 'AOL快递', 1, 0),
(216, 'jixianda', '急先达物流', 1, 0),
(217, 'haihong', '山东海红快递', 1, 0),
(218, 'feiyang', '飞洋快递', 1, 0),
(219, 'rpx', 'RPX保时达', 1, 0),
(220, 'zhaijisong', '宅急送', 1, 0),
(221, 'tiantian', '天天快递', 99, 0),
(222, 'yunwuliu', '云物流', 1, 0),
(223, 'jiuye', '九曳供应链', 1, 0),
(224, 'bsky', '百世快运', 1, 0),
(225, 'higo', '黑狗物流', 1, 0),
(226, 'arke', '方舟速递', 1, 0),
(227, 'zwsy', '中外速运', 1, 0),
(228, 'jxy', '吉祥邮', 1, 0),
(229, 'aramex', 'Aramex', 1, 0),
(230, 'guotong', '国通快递', 1, 0),
(231, 'jiayi', '佳怡物流', 1, 0),
(232, 'longbang', '龙邦快运', 1, 0),
(233, 'minhang', '民航快递', 1, 0),
(234, 'quanyi', '全一快递', 1, 0),
(235, 'quanchen', '全晨快递', 1, 0),
(236, 'usps', 'USPS快递', 1, 0),
(237, 'xinbang', '新邦物流', 1, 0),
(238, 'yuanzhi', '元智捷诚快递', 1, 0),
(239, 'zhongyou', '中邮物流', 1, 0),
(240, 'yuxin', '宇鑫物流', 1, 0),
(241, 'cnpex', '中环快递', 1, 0),
(242, 'shengfeng', '盛丰物流', 1, 0),
(243, 'yuantong', '圆通速递', 97, 1),
(244, 'jiayunmei', '加运美物流', 1, 0),
(245, 'ywfex', '源伟丰快递', 1, 0),
(246, 'xinfeng', '信丰物流', 1, 0),
(247, 'wanxiang', '万象物流', 1, 0),
(248, 'menduimen', '门对门', 1, 0),
(249, 'mingliang', '明亮物流', 1, 0),
(250, 'fengxingtianxia', '风行天下', 1, 0),
(251, 'gongsuda', '共速达物流', 1, 0),
(252, 'zhongtong', '中通速递', 100, 1),
(253, 'quanritong', '全日通快递', 1, 0),
(254, 'ems', 'EMS', 1, 1),
(255, 'wanjia', '万家物流', 1, 0),
(256, 'yuntong', '运通快递', 1, 0),
(257, 'feikuaida', '飞快达物流', 1, 0),
(258, 'haimeng', '海盟速递', 1, 0),
(259, 'zhongsukuaidi', '中速快件', 1, 0),
(260, 'yuefeng', '越丰快递', 1, 0),
(261, 'shenghui', '盛辉物流', 1, 0),
(262, 'datian', '大田物流', 1, 0),
(263, 'quanjitong', '全际通快递', 1, 0),
(264, 'longlangkuaidi', '隆浪快递', 1, 0),
(265, 'neweggozzo', '新蛋奥硕物流', 1, 0),
(266, 'shentong', '申通快递', 95, 1),
(267, 'haiwaihuanqiu', '海外环球', 1, 0),
(268, 'yad', '源安达快递', 1, 0),
(269, 'jindawuliu', '金大物流', 1, 0),
(270, 'sevendays', '七天连锁', 1, 0),
(271, 'tnt', 'TNT快递', 1, 0),
(272, 'huayu', '天地华宇物流', 1, 0),
(273, 'lianhaotong', '联昊通快递', 1, 0),
(274, 'nengda', '港中能达快递', 1, 0),
(275, 'LBWL', '联邦物流', 1, 0),
(276, 'ontrac', 'onTrac', 1, 0),
(277, 'feihang', '原飞航快递', 1, 0),
(278, 'bangsongwuliu', '邦送物流', 1, 0),
(279, 'huaxialong', '华夏龙物流', 1, 0),
(280, 'ztwy', '中天万运快递', 1, 0),
(281, 'fkd', '飞康达物流', 1, 0),
(282, 'anxinda', '安信达快递', 1, 0),
(283, 'quanfeng', '全峰快递', 1, 0),
(284, 'shengan', '圣安物流', 1, 0),
(285, 'jiaji', '佳吉物流', 1, 0),
(286, 'yunda', '韵达快运', 94, 0),
(287, 'ups', 'UPS快递', 1, 0),
(288, 'debang', '德邦物流', 1, 0),
(289, 'yafeng', '亚风速递', 1, 0),
(290, 'kuaijie', '快捷速递', 98, 0),
(291, 'huitong', '百世快递', 93, 0),
(293, 'aolau', 'AOL澳通速递', 1, 0),
(294, 'anneng', '安能物流', 1, 0),
(295, 'auexpress', '澳邮中国快运', 1, 0),
(296, 'exfresh', '安鲜达', 1, 0),
(297, 'bcwelt', 'BCWELT', 1, 0),
(298, 'youzhengguonei', '挂号信', 1, 0),
(299, 'xiaohongmao', '北青小红帽', 1, 0),
(300, 'lbbk', '宝凯物流', 1, 0),
(301, 'byht', '博源恒通', 1, 0),
(302, 'idada', '百成大达物流', 1, 0),
(303, 'baitengwuliu', '百腾物流', 1, 0),
(304, 'birdex', '笨鸟海淘', 1, 0),
(305, 'bsht', '百事亨通', 1, 0),
(306, 'dayang', '大洋物流快递', 1, 0),
(307, 'dechuangwuliu', '德创物流', 1, 0),
(308, 'donghanwl', '东瀚物流', 1, 0),
(309, 'dfpost', '达方物流', 1, 0),
(310, 'dongjun', '东骏快捷物流', 1, 0),
(311, 'dindon', '叮咚澳洲转运', 1, 0),
(312, 'dazhong', '大众佐川急便', 1, 0),
(313, 'decnlh', '德中快递', 1, 0),
(314, 'dekuncn', '德坤供应链', 1, 0),
(315, 'eshunda', '俄顺达', 1, 0),
(316, 'ewe', 'EWE全球快递', 1, 0),
(317, 'fedexuk', 'FedEx英国', 1, 0),
(318, 'fox', 'FOX国际速递', 1, 0),
(319, 'rufengda', '凡客如风达', 1, 0),
(320, 'fandaguoji', '颿达国际快递', 1, 0),
(321, 'hnfy', '飞鹰物流', 1, 0),
(322, 'flysman', '飞力士物流', 1, 0),
(323, 'sccod', '丰程物流', 1, 0),
(324, 'farlogistis', '泛远国际物流', 1, 0),
(325, 'gsm', 'GSM', 1, 0),
(326, 'gaticn', 'GATI快递', 1, 0),
(327, 'gts', 'GTS快递', 1, 0),
(328, 'gangkuai', '港快速递', 1, 0),
(329, 'gtsd', '高铁速递', 1, 0),
(330, 'tiandihuayu', '华宇物流', 1, 0),
(331, 'huangmajia', '黄马甲快递', 1, 0),
(332, 'ucs', '合众速递', 1, 0),
(333, 'huoban', '伙伴物流', 1, 0),
(334, 'nedahm', '红马速递', 1, 0),
(335, 'huiwen', '汇文配送', 1, 0),
(336, 'nmhuahe', '华赫物流', 1, 0),
(337, 'hangyu', '航宇快递', 1, 0),
(338, 'minsheng', '闽盛物流', 1, 0),
(339, 'riyu', '日昱物流', 1, 0),
(340, 'sxhongmajia', '山西红马甲', 1, 0),
(341, 'syjiahuier', '沈阳佳惠尔', 1, 0),
(342, 'shlindao', '上海林道货运', 1, 0),
(343, 'shunjiefengda', '顺捷丰达', 1, 0),
(344, 'subida', '速必达物流', 1, 0),
(345, 'bphchina', '速方国际物流', 1, 0),
(346, 'sendtochina', '速递中国', 1, 0),
(347, 'suning', '苏宁快递', 1, 0),
(348, 'sihaiet', '四海快递', 1, 0),
(349, 'tianzong', '天纵物流', 1, 0),
(350, 'chinatzx', '同舟行物流', 1, 0),
(351, 'nntengda', '腾达速递', 1, 0),
(352, 'sd138', '泰国138', 1, 0),
(353, 'tongdaxing', '通达兴物流', 1, 0),
(354, 'tlky', '天联快运', 1, 0),
(355, 'youshuwuliu', 'UC优速快递', 1, 0),
(356, 'ueq', 'UEQ快递', 1, 0),
(357, 'weitepai', '微特派快递', 1, 0),
(358, 'wtdchina', '威时沛运', 1, 0),
(359, 'wzhaunyun', '微转运', 1, 0),
(360, 'gswtkd', '万通快递', 1, 0),
(361, 'wotu', '渥途国际速运', 1, 0),
(362, 'xiyoute', '希优特快递', 1, 0),
(363, 'xilaikd', '喜来快递', 1, 0),
(364, 'xsrd', '鑫世锐达', 1, 0),
(365, 'xtb', '鑫通宝物流', 1, 0),
(366, 'xintianjie', '信天捷快递', 1, 0),
(367, 'xaetc', '西安胜峰', 1, 0),
(368, 'xianfeng', '先锋快递', 1, 0),
(369, 'sunspeedy', '新速航', 1, 0),
(370, 'xipost', '西邮寄', 1, 0),
(371, 'sinatone', '信联通', 1, 0),
(372, 'sunjex', '新杰物流', 1, 0),
(373, 'yundaexus', '韵达美国件', 1, 0),
(374, 'yxwl', '宇鑫物流', 1, 0),
(375, 'yitongda', '易通达', 1, 0),
(376, 'yiqiguojiwuliu', '一柒物流', 1, 0),
(377, 'yilingsuyun', '亿领速运', 1, 0),
(378, 'yujiawuliu', '煜嘉物流', 1, 0),
(379, 'gml', '英脉物流', 1, 0),
(380, 'leopard', '云豹国际货运', 1, 0),
(381, 'czwlyn', '云南中诚', 1, 0),
(382, 'sdyoupei', '优配速运', 1, 0),
(383, 'yongchang', '永昌物流', 1, 0),
(384, 'yufeng', '御风速运', 1, 0),
(385, 'yamaxunwuliu', '亚马逊物流', 1, 0),
(386, 'yousutongda', '优速通达', 1, 0),
(387, 'yishunhang', '亿顺航', 1, 0),
(388, 'yongwangda', '永旺达快递', 1, 0),
(389, 'ecmscn', '易满客', 1, 0),
(390, 'yingchao', '英超物流', 1, 0),
(391, 'edlogistics', '益递物流', 1, 0),
(392, 'yyexpress', '远洋国际', 1, 0),
(393, 'onehcang', '一号仓', 1, 0),
(394, 'ycgky', '远成快运', 1, 0),
(395, 'lineone', '一号线', 1, 0),
(396, 'ypsd', '壹品速递', 1, 0),
(397, 'vipexpress', '鹰运国际速递', 1, 0),
(398, 'el56', '易联通达物流', 1, 0),
(399, 'yyqc56', '一运全成物流', 1, 0),
(400, 'zhongtie', '中铁快运', 1, 0),
(401, 'ZTKY', '中铁物流', 1, 0),
(402, 'zzjh', '郑州建华快递', 1, 0),
(403, 'zhongruisudi', '中睿速递', 1, 0),
(404, 'zhongwaiyun', '中外运速递', 1, 0),
(405, 'zengyisudi', '增益速递', 1, 0),
(406, 'sujievip', '郑州速捷', 1, 0),
(407, 'zhichengtongda', '至诚通达快递', 1, 0),
(408, 'zhdwl', '众辉达物流', 1, 0),
(409, 'kuachangwuliu', '直邮易', 1, 0),
(410, 'topspeedex', '中运全速', 1, 0),
(411, 'otobv', '中欧快运', 1, 0),
(412, 'zsky123', '准实快运', 1, 0),
(413, 'donghong', '东红物流', 1, 0),
(414, 'kuaiyouda', '快优达速递', 1, 0),
(415, 'balunzhi', '巴伦支快递', 1, 0),
(416, 'hutongwuliu', '户通物流', 1, 0),
(417, 'xianchenglian', '西安城联速递', 1, 0),
(418, 'youbijia', '邮必佳', 1, 0),
(419, 'feiyuan', '飞远物流', 1, 0),
(420, 'chengji', '城际速递', 1, 0),
(421, 'huaqi', '华企快运', 1, 0),
(422, 'yibang', '一邦快递', 1, 0),
(423, 'citylink', 'CityLink快递', 1, 0),
(424, 'meixi', '美西快递', 1, 0),
(425, 'acs', 'ACS', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_forum_census`
--

CREATE TABLE `osx_forum_census` (
  `id` int(11) NOT NULL COMMENT 'id',
  `fid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `one_comment` int(11) NOT NULL,
  `one_member` int(11) NOT NULL,
  `one_view` int(11) NOT NULL,
  `seven` int(11) NOT NULL,
  `seven_comment` int(11) NOT NULL,
  `seven_member` int(11) NOT NULL,
  `seven_view` int(11) NOT NULL,
  `thirty` int(11) NOT NULL,
  `thirty_comment` int(11) NOT NULL,
  `thirty_member` int(11) NOT NULL,
  `thirty_view` int(11) NOT NULL,
  `ninety` int(11) NOT NULL,
  `ninety_comment` int(11) NOT NULL,
  `ninety_member` int(11) NOT NULL,
  `ninety_view` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_forum_power`
--

CREATE TABLE `osx_forum_power` (
  `id` int(11) NOT NULL COMMENT 'id',
  `audit` int(11) NOT NULL COMMENT '审核权限',
  `visit` int(11) NOT NULL COMMENT '访问权限',
  `send_thread` int(11) NOT NULL COMMENT '发帖权限',
  `send_comment` int(11) NOT NULL COMMENT '发评论权限',
  `browse` int(11) NOT NULL COMMENT '浏览全选',
  `status` int(11) NOT NULL COMMENT '状态',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_forum_report`
--

CREATE TABLE `osx_forum_report` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '举报用户',
  `to_uid` int(11) NOT NULL COMMENT '被举报用户',
  `content` text NOT NULL COMMENT '举报内容',
  `plate` int(11) NOT NULL COMMENT '版块',
  `cate` int(11) NOT NULL COMMENT '分类',
  `create_time` int(11) NOT NULL COMMENT '投诉时间',
  `reason` int(11) NOT NULL COMMENT '投诉原因',
  `status` int(11) NOT NULL COMMENT '状态',
  `is_deal` int(11) NOT NULL DEFAULT '0' COMMENT '是否处理',
  `type` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_forum_visit_audit`
--

CREATE TABLE `osx_forum_visit_audit` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '申请人uid',
  `fid` int(11) NOT NULL COMMENT '板块id',
  `status` int(11) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `audit_time` int(11) NOT NULL COMMENT '审核时间',
  `reason` text NOT NULL COMMENT '申请理由',
  `count` int(11) NOT NULL COMMENT '申请数量',
  `audit_uid` int(11) NOT NULL COMMENT '审核用户uid',
  `reject_resaon` text NOT NULL COMMENT '拒绝理由',
  `is_admin` int(11) NOT NULL COMMENT '是否是后台审核'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户入版块审核表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_group`
--

CREATE TABLE `osx_group` (
  `id` int(11) NOT NULL COMMENT 'id',
  `name` text NOT NULL COMMENT '名称',
  `remark` text NOT NULL COMMENT '描述',
  `level` int(11) NOT NULL COMMENT '级别',
  `cate` text NOT NULL COMMENT '类型',
  `status` int(11) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '组类型',
  `bind_condition` int(11) NOT NULL COMMENT '相关的条件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_group`
--

INSERT INTO `osx_group` (`id`, `name`, `remark`, `level`, `cate`, `status`, `create_time`, `type`, `bind_condition`) VALUES
(2, '管理员', '系统管理员的前端账号,可在用户列表中设置，管理员为系统最大的权限用户,请务必谨慎设置', 2, '内置', 1, 1585192103, 1, 0),
(3, '超级版主', '即分区版主，可在【社区-版主管理】中设置', 2, '内置', 1, 1585192248, 1, 0),
(4, '版主', '即板块版主,可在【社区-版主管理】中设置', 2, '内置', 1, 1585192598, 1, 0),
(5, '实习版主', '非正式板块版主，享有部分版主权限，但不具备版主正式身份', 2, '自定义', 1, 1585192665, 1, 0),
(7, '游客', '所有打开应用,未注册的用户', 1, '内置', 1, 1585290545, 2, 0),
(8, '注册用户', '所有注册成功的用户', 1, '内置', 1, 1585290584, 2, 0),
(9, '禁言用户', '所有被设置禁言，且当前处于禁言中的用户', 4, '内置', 1, 1585290665, 2, 0),
(10, '禁用用户', '所有账号被禁用，且当前仍在禁用用户中的用户', 5, '内置', 1, 1585290728, 2, 0),
(50, '1级', '1级', 2, '内置', 1, 1599706318, 3, 1),
(51, '2级', '2级', 2, '内置', 1, 1599706318, 3, 2),
(52, '3级', '3级', 2, '内置', 1, 1599706318, 3, 3),
(53, '4级', '4级', 2, '内置', 1, 1599706318, 3, 4),
(54, '5级', '5级', 2, '内置', 1, 1599706318, 3, 5),
(55, '6级', '6级', 2, '内置', 1, 1599706318, 3, 6),
(56, '7级', '7级', 2, '内置', 1, 1599706318, 3, 7),
(57, '8级', '8级', 2, '内置', 1, 1599706318, 3, 8),
(58, '9级', '9级', 2, '内置', 1, 1599706318, 3, 9),
(59, '10级', '10级', 2, '内置', 1, 1599706318, 3, 10),
(60, '11级', '11级', 2, '内置', 1, 1599706318, 3, 11),
(61, '12级', '12级', 2, '内置', 1, 1599706318, 3, 12),
(62, '13级', '13级', 2, '内置', 1, 1599706318, 3, 13),
(63, '14级', '14级', 2, '内置', 1, 1599706318, 3, 14),
(64, '15级', '15级', 2, '内置', 1, 1599706318, 3, 15),
(65, '16级', '16级', 2, '内置', 1, 1599706318, 3, 16),
(66, '17级', '17级', 2, '内置', 1, 1599706318, 3, 17),
(67, '18级', '18级', 2, '内置', 1, 1599706318, 3, 18),
(68, '19级', '19级', 2, '内置', 1, 1599706318, 3, 19),
(69, '20级', '20级', 2, '内置', 1, 1599706318, 3, 20),
(70, '21级', '21级', 2, '内置', 1, 1599706318, 3, 21);

-- --------------------------------------------------------

--
-- 表的结构 `osx_head_login`
--

CREATE TABLE `osx_head_login` (
  `uid` int(11) NOT NULL COMMENT 'uid',
  `open_id` text NOT NULL COMMENT 'head的id',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_hot_census`
--

CREATE TABLE `osx_hot_census` (
  `id` int(11) NOT NULL COMMENT 'id',
  `tid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `seven` int(11) NOT NULL,
  `thirty` int(11) NOT NULL,
  `ninety` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_invite_code`
--

CREATE TABLE `osx_invite_code` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `code` text NOT NULL COMMENT '邀请码',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `invite_num` int(11) NOT NULL DEFAULT '0' COMMENT '邀请数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_invite_code`
--

INSERT INTO `osx_invite_code` (`id`, `uid`, `code`, `create_time`, `invite_num`) VALUES
(20, 2, 'sMpoF', 1572586075, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_invite_level`
--

CREATE TABLE `osx_invite_level` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '注册用户',
  `father1` int(11) NOT NULL COMMENT '邀请人1级  ',
  `father2` int(11) NOT NULL COMMENT '邀请人2级',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `child_num` int(11) NOT NULL DEFAULT '0' COMMENT '下一级人数',
  `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '下单数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_invite_log`
--

CREATE TABLE `osx_invite_log` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '注册人',
  `code` text NOT NULL COMMENT '邀请码',
  `father_uid` int(11) NOT NULL COMMENT '邀请人',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `reward` text NOT NULL COMMENT '邀请人奖励',
  `reward_type` text NOT NULL COMMENT '奖励类型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_invite_reward`
--

CREATE TABLE `osx_invite_reward` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL COMMENT '判断条件',
  `num` int(11) NOT NULL COMMENT '推荐人数',
  `reward_type` text NOT NULL COMMENT '奖励类型',
  `reward` text NOT NULL COMMENT '奖励内容',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `level` int(11) NOT NULL COMMENT '等级'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请奖励表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_invite_share`
--

CREATE TABLE `osx_invite_share` (
  `id` int(11) NOT NULL COMMENT '海报id',
  `title` varchar(25) NOT NULL COMMENT '海报标题',
  `url` text NOT NULL COMMENT '海报图片地址',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `colour` tinyint(4) NOT NULL DEFAULT '1' COMMENT '字体颜色：1，黑色；2，白色；'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分享海报配置' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_login_faq`
--

CREATE TABLE `osx_login_faq` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '问题',
  `desc` text NOT NULL COMMENT '问题说明',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态（1开启，0关闭）默认开启',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证配置-常见问题';

-- --------------------------------------------------------

--
-- 表的结构 `osx_message`
--

CREATE TABLE `osx_message` (
  `id` int(11) NOT NULL,
  `from_uid` int(11) NOT NULL COMMENT '系统消息，该值为0',
  `to_uid` text NOT NULL COMMENT '消息接收人',
  `type_id` int(11) NOT NULL COMMENT '消息类型id',
  `from_type` int(11) NOT NULL COMMENT '1:系统消息；2:评论消息；3:点赞消息；4:互动消息',
  `title` text NOT NULL COMMENT '消息标题',
  `content` varchar(500) NOT NULL COMMENT '消息内容',
  `image` varchar(255) NOT NULL COMMENT '消息图片url',
  `route` varchar(100) NOT NULL COMMENT '用于记录跳转位置，不能写url，要写标识，具体跳转位置在代码中根据标识解析确定',
  `link_id` text NOT NULL COMMENT '路由指针，标记跳转位置，如帖子的id、评论的id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(11) NOT NULL DEFAULT '1',
  `send_time` int(11) NOT NULL COMMENT '推送时间',
  `type_now` int(11) NOT NULL COMMENT '当前消息分类',
  `post_id` int(11) NOT NULL COMMENT '评论id，仅点赞和回复评论时需要',
  `own_post_id` int(11) NOT NULL COMMENT '产生的评论id，仅楼中楼回复用到',
  `post_uid` int(11) NOT NULL COMMENT '评论人uid',
  `to_type_uid` text NOT NULL COMMENT '接收用户组',
  `url` text NOT NULL COMMENT '连接选择器'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_census`
--

CREATE TABLE `osx_message_census` (
  `uid` int(11) NOT NULL COMMENT 'uid',
  `message` text NOT NULL COMMENT '系统消息type为1',
  `message_is_read` int(11) NOT NULL COMMENT '系统消息是否已读',
  `notice` text NOT NULL COMMENT '自定义消息type为7',
  `notice_is_read` int(11) NOT NULL COMMENT '自定义消息是否已读',
  `message_new` text NOT NULL COMMENT '运营消息type为6',
  `message_new_is_read` int(11) NOT NULL COMMENT '运营消息是否已读',
  `message_new_send` text NOT NULL COMMENT '新动态消息type为5',
  `message_new_send_is_read` int(11) NOT NULL COMMENT '新动态消息是否已读',
  `follow_count` int(11) NOT NULL COMMENT '点赞消息未读数量',
  `reply_count` int(11) NOT NULL COMMENT '评论消息未读数量',
  `support_count` int(11) NOT NULL COMMENT '点赞消息未读数量',
  `status` int(11) NOT NULL DEFAULT '1',
  `no_send_message` text NOT NULL COMMENT '未到时间的消息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_news`
--

CREATE TABLE `osx_message_news` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL COMMENT '标题',
  `from_uid` int(11) NOT NULL DEFAULT '0' COMMENT '推送人',
  `logo` varchar(255) NOT NULL COMMENT '封面',
  `content` text NOT NULL COMMENT '详情',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序，sort值较大的排在前面',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `send_time` int(11) NOT NULL COMMENT '推送时间',
  `to_uid` text NOT NULL COMMENT '接收人群',
  `to_type_uid` text NOT NULL COMMENT '接收用户组',
  `tid` int(11) NOT NULL COMMENT '主题帖id',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `summary` text NOT NULL COMMENT '摘要',
  `admin_uid` int(11) NOT NULL COMMENT '管理员id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_read`
--

CREATE TABLE `osx_message_read` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL COMMENT '消息表id',
  `uid` int(11) NOT NULL COMMENT '阅读人uid',
  `read_time` int(11) NOT NULL COMMENT '阅读时间',
  `is_read` int(11) NOT NULL DEFAULT '0' COMMENT '是否已读',
  `is_popup` int(11) NOT NULL DEFAULT '0' COMMENT '是否已经弹窗',
  `popup_time` int(11) NOT NULL COMMENT '弹窗时间',
  `type` int(11) NOT NULL COMMENT '消息类型',
  `is_sms` int(11) NOT NULL DEFAULT '0' COMMENT '是否发生短信',
  `sms_time` int(11) NOT NULL COMMENT '短信发送时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `is_share` int(11) NOT NULL DEFAULT '1' COMMENT '分销结算短信是否发送'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息阅读记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_register`
--

CREATE TABLE `osx_message_register` (
  `id` int(11) NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上级版块id',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主题类型,1.普通版面,2.微博,3.朋友圈,4.资讯,5.活动,6.视频横版,7.视频竖版,8.公告',
  `is_announce` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是公告，和type=8重复，为了后续构造查询条件方便',
  `post_id` int(11) NOT NULL DEFAULT '0' COMMENT '帖子表中的id',
  `read_perm` tinyint(3) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人id',
  `title` char(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '发表时间',
  `last_post_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表时间',
  `last_post_uid` int(11) NOT NULL DEFAULT '0' COMMENT '最后发表人id',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `reply_count` int(11) NOT NULL DEFAULT '0' COMMENT '回复次数',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `share_count` int(11) NOT NULL DEFAULT '0' COMMENT '分享次数',
  `collect_count` int(11) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `high_light` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否高亮',
  `is_essence` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件id',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否被管理员改动',
  `stick_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有回帖置顶',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0：禁用1：启用2：未审核-1：删除',
  `cover` varchar(255) NOT NULL DEFAULT '0' COMMENT '主题封面',
  `class_id` int(11) NOT NULL DEFAULT '0' COMMENT '主题分类',
  `summary` varchar(255) NOT NULL COMMENT '帖子摘要',
  `image` text NOT NULL COMMENT '三张图地址',
  `from` varchar(40) NOT NULL DEFAULT '' COMMENT '发布来源',
  `pos` varchar(60) NOT NULL DEFAULT '' COMMENT '地点',
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '帖子位置,暂时没用到',
  `product_id` text NOT NULL COMMENT '商品id',
  `is_massage` int(11) NOT NULL DEFAULT '0' COMMENT '是否是营销消息：1是；0不是',
  `video_id` varchar(100) NOT NULL DEFAULT '' COMMENT '视频腾讯云上的id',
  `video_cover` varchar(200) NOT NULL DEFAULT '' COMMENT '视频封面（可无）',
  `del_user` varchar(50) NOT NULL COMMENT '删帖人',
  `false_view` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟浏览量',
  `is_weibo` int(11) NOT NULL DEFAULT '0' COMMENT '是否是动态',
  `detail_top` int(11) NOT NULL DEFAULT '0' COMMENT '详情置顶',
  `index_top` int(11) NOT NULL DEFAULT '0' COMMENT '首页置顶',
  `is_new` int(11) DEFAULT '0' COMMENT '是否最新',
  `oid` text NOT NULL COMMENT '话题id',
  `keywords` varchar(200) NOT NULL DEFAULT '' COMMENT '关键词',
  `video_url` text NOT NULL COMMENT '视频地址',
  `audio_id` text NOT NULL COMMENT '音频id',
  `audio_url` text NOT NULL COMMENT '音频地址',
  `audio_time` int(11) NOT NULL COMMENT '音频时长',
  `reject_reason` text NOT NULL COMMENT '驳回理由',
  `delete_reason` text NOT NULL COMMENT '删除理由',
  `is_recommend` int(11) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `recommend_time` int(11) NOT NULL COMMENT '推荐时间',
  `recommend_end_time` int(11) NOT NULL COMMENT '推荐到期时间',
  `recommend_uid` int(11) NOT NULL COMMENT '推荐人uid',
  `top_time` int(11) NOT NULL COMMENT '置顶时间',
  `top_end_time` int(11) NOT NULL COMMENT '置顶到期时间',
  `top_uid` int(11) NOT NULL COMMENT '置顶人uid',
  `index_top_time` int(11) NOT NULL COMMENT '首页置顶时间',
  `index_top_end_time` int(11) NOT NULL COMMENT '首页置顶到期时间',
  `index_top_uid` int(11) NOT NULL COMMENT '首页置顶人uid',
  `detail_top_time` int(11) NOT NULL COMMENT '详情置顶时间',
  `detail_top_end_time` int(11) NOT NULL COMMENT '详情置顶到期时间',
  `detail_top_uid` int(11) NOT NULL COMMENT '详情置顶人uid',
  `light_time` int(11) NOT NULL COMMENT '加粗时间',
  `light_end_time` int(11) NOT NULL COMMENT '加粗到期时间',
  `essence_uid` int(11) NOT NULL COMMENT '加精人uid',
  `essence_time` int(11) NOT NULL COMMENT '加精时间',
  `operation_uid` int(11) NOT NULL COMMENT '操作人uid',
  `operation_identity` int(11) NOT NULL COMMENT '操作人身份',
  `send_time` int(11) NOT NULL COMMENT '发布时间',
  `is_open` int(11) NOT NULL COMMENT '是否开启',
  `open_time` int(11) NOT NULL COMMENT '多少天内显示新消息注册',
  `bind_thread` int(11) NOT NULL COMMENT '绑定帖子id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_message_register`
--

INSERT INTO `osx_message_register` (`id`, `fid`, `type`, `is_announce`, `post_id`, `read_perm`, `author_uid`, `title`, `content`, `create_time`, `last_post_time`, `last_post_uid`, `update_time`, `view_count`, `reply_count`, `support_count`, `share_count`, `collect_count`, `sort`, `high_light`, `is_essence`, `is_top`, `attachment_id`, `is_verify`, `stick_reply`, `status`, `cover`, `class_id`, `summary`, `image`, `from`, `pos`, `position`, `product_id`, `is_massage`, `video_id`, `video_cover`, `del_user`, `false_view`, `is_weibo`, `detail_top`, `index_top`, `is_new`, `oid`, `keywords`, `video_url`, `audio_id`, `audio_url`, `audio_time`, `reject_reason`, `delete_reason`, `is_recommend`, `recommend_time`, `recommend_end_time`, `recommend_uid`, `top_time`, `top_end_time`, `top_uid`, `index_top_time`, `index_top_end_time`, `index_top_uid`, `detail_top_time`, `detail_top_end_time`, `detail_top_uid`, `light_time`, `light_end_time`, `essence_uid`, `essence_time`, `operation_uid`, `operation_identity`, `send_time`, `is_open`, `open_time`, `bind_thread`) VALUES
(1, 1, 1, 0, 1, 0, 1, 'hi，欢迎您的加入！', '想天社区是一个超级有趣的社区，欢迎成为其中的一员！', 1594193527, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, '0', 0, '', '', 'HouTai', '', 0, '', 0, '', '', '', 0, 0, 0, 0, 0, '', '', '', '', '', 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1594193527, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_template`
--

CREATE TABLE `osx_message_template` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `forum` int(11) NOT NULL COMMENT '所属版块',
  `action` text NOT NULL COMMENT '触发条件',
  `template` text NOT NULL COMMENT '消息模版',
  `type` int(11) NOT NULL COMMENT '消息类型',
  `web` int(11) NOT NULL COMMENT '站内通知是否开启',
  `sms` int(11) NOT NULL COMMENT '短信通知是否开启',
  `popup` int(11) NOT NULL COMMENT '弹窗提醒是否开启',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息模版表';

--
-- 转存表中的数据 `osx_message_template`
--

INSERT INTO `osx_message_template` (`id`, `title`, `forum`, `action`, `template`, `type`, `web`, `sms`, `popup`, `status`) VALUES
(1, '被赞通知', 1, '发布的内容获赞即发送', '{用户昵称}给你点了一个赞!', 1, 1, 0, 1, 1),
(2, '被赞通知', 1, '小名片获赞即发送', '{用户昵称}给你的小名片点了一个赞!', 1, 1, 0, 1, 1),
(3, '评论通知', 1, '发布的帖子收到评论即发送', '{用户昵称}评论了你的帖子，快去看看吧!', 1, 1, 0, 1, 1),
(4, '评论通知', 1, '发布的资讯收到评论即发送', '{用户昵称}评论了你的资讯文章，快去看看吧!', 1, 1, 0, 1, 1),
(5, '评论通知', 1, '发布的视频收到评论即发送', '{用户昵称}评论了你的视频，快去看看吧!', 1, 1, 0, 1, 1),
(6, '评论通知', 1, '社区发布的评论被评论即发送', '{用户昵称}回复了您的评论，快去看看吧!', 1, 1, 0, 1, 1),
(7, '新动态通知', 1, '关注的用户发帖即发送', '你关注的@{用户昵称}有新动态，快去看看吧!', 1, 1, 0, 1, 1),
(8, '新动态通知', 1, '关注的版块版主发帖即发送', '你关注的{版块名称}版主{用户昵称}有新动态，快去看看吧!', 1, 1, 0, 1, 1),
(9, '删帖通知', 1, '版主确认删除版块内的帖子（发给被删帖人）成功即发送', '您{年月日时分}在“{版块名称}”内发布的帖子“{帖子标题}”已被删除', 1, 1, 1, 1, 1),
(10, '删评论通知', 1, '版主确认删除版块内的评论（发给被删评论人）成功即发送', '您{年月日时分}在“{帖子标题}”下发布的评论“{评论内容}”已被删除。', 1, 1, 1, 1, 1),
(11, '加精通知', 1, '版主加精操作成功即发送', '恭喜！您{年月日时分}在“{版块名称}”内发布的帖子“{帖子标题}”被加精了！', 1, 1, 1, 1, 1),
(12, '置顶通知', 1, '版主置顶操作成功即发送', '恭喜！您{年月日时分}在“{版块名称}”下发布的帖子“{帖子标题}”被置顶了！', 1, 1, 1, 1, 1),
(13, '付款提醒', 2, '买家下单XXX分钟未付款立即发送', '您还有订单尚未付款，请尽快完成支付!', 1, 1, 1, 1, 1),
(14, '发货提醒', 2, '管理后台发货成功时立即发送', '你的订单{订单编号}发货啦，请注意查收包裹！', 1, 1, 1, 1, 1),
(15, '退款通知', 2, '管理后台-订单管理-操作栏中点击“立即退款”成功即发送', '您提交的退款申请已审核通过，订单号：{订单编号}，请注意查收退还款项。', 1, 1, 1, 1, 1),
(16, '退款通知', 2, '管理后台-订单管理-操作栏中点击“拒绝退款”成功即发送', '您提出的退款申请审核未通过，订单号：{订单编号}，如有疑问，请及时联系客服。', 1, 1, 1, 1, 1),
(17, '订单取消通知', 2, '未申请退款订单卖家管理后台直接进行“立即退款”操作时立即发送', '您的订单{订单编号}已直接退款，请登录查看详情。', 1, 1, 1, 1, 1),
(18, '拼团通知', 2, '拼团成功即发送', '恭喜你，你参加的拼团{订单编号}已组团成功，我们将尽快打包发货，请注意查收！', 1, 1, 1, 1, 1),
(19, '拼团通知', 2, '拼团失败即发送', '你参加的拼团{订单编号}由于人数不足拼团失败，我们将尽快给你退款。', 1, 1, 1, 1, 1),
(20, '发货提醒', 4, '管理后台积分商城订单发货成功时即发送', '你的积分兑换订单发货啦，请注意查收包裹！', 1, 1, 1, 1, 1),
(21, '权限审核通知', 3, '管理后台推广权限审核通过即发送', '恭喜您成为【{应用名称}】的推广员，推广成功可赚佣金。推广越多，赚钱越快，赶紧去推广赚钱吧！！！', 1, 1, 1, 1, 1),
(22, '权限审核通知', 3, '管理后台推广权限审核驳回即发送', '您提交的推广权限开通申请，未通过审核。驳回理由：{驳回理由}', 1, 1, 1, 1, 1),
(23, '权限取消通知', 3, '管理后台推广权限取消即发送', '由于您的账号可能存在违规操作，已被限制推广权限，如有疑问请及时联系客服。', 1, 1, 1, 1, 1),
(24, '月结算通知', 3, '每月分销结算成功即发送', '您的上月推广订单已结算成功，请及时确认。', 1, 1, 1, 1, 1),
(25, '分销下单通知', 3, '下级下单时即发送给一级', '您的团队有人下单啦！', 1, 1, 0, 1, 1),
(26, '分销下单通知', 3, '下级下单时即发送给二级', '您的团队有人下单啦！', 1, 1, 0, 1, 1),
(27, '绑定成功通知', 3, '扫码、填写邀请码、分享链接注册等成功绑定上下级关系时即发送给上两级', '恭喜您，【{用户昵称}】已成为您的团队成员。ta购买推广商品后，您可以获得佣金。客户越多，赚钱越快，赶紧去推广赚钱吧！！！', 1, 1, 0, 1, 1),
(28, '提现通知', 3, '管理后台点击【人工打款成功】成功即发送', '您有一笔{金额}元的提现已打款，请注意查收！', 1, 1, 0, 1, 1),
(29, '提现通知', 3, '管理后台提现审核驳回成功即发送', '您有一笔{金额}元的提现申请被驳回，驳回理由：{驳回理由}。如有疑问请及时联系客服。', 1, 1, 0, 1, 1),
(30, '认证通知', 5, '管理后台认证审核通过成功即发送', '恭喜！{认证名称}认证成功！您提交的认证信息已通过审核。', 1, 1, 1, 1, 1),
(31, '认证通知', 5, '管理后台认证驳回时即发送', '很抱歉，您提交的认证信息审核未通过，具体原因：{驳回理由}。', 1, 1, 1, 1, 1),
(32, '认证通知', 5, '管理后台取消认证即发送', '通知！由于您的账号存在违规行为，已被取消认证，如有疑问请及时联系客服。', 1, 1, 1, 1, 1),
(33, '删帖通知', 6, '管理员后台删帖成功即发送', '您{年月日时分}在“{版块名称}”内发布的帖子“{帖子标题}”已被删除', 1, 1, 1, 1, 1),
(34, '删评论通知', 6, '管理员后台删评论成功即发送', '您{年月日时分}在“{帖子标题}”下发布的评论“{评论内容}”已被删除。', 1, 1, 1, 1, 1),
(35, '删商品评论通知', 6, '管理员后台删评论成功即发送', '您{年月日时分}对“{商品名称}”发布的评论“{评论内容}”已被删除。', 1, 1, 1, 1, 1),
(36, '加精通知', 6, '管理员加精操作成功即发送', '恭喜！您{年月日时分}在“{版块名称}”内发布的帖子“{帖子标题}”被加精了！', 1, 1, 1, 1, 1),
(37, '置顶通知', 6, '管理员置顶操作成功即发送', '恭喜！您{年月日时分}在“{版块名称}”下发布的帖子“{帖子标题}”被置顶了！', 1, 1, 1, 1, 1),
(38, '专栏更新通知', 7, '购买的专栏更新了新期刊', '您订阅的专栏：{专栏名称}更新了最新内容，立即阅读！', 1, 1, 0, 1, 1),
(39, '升级通知', 8, '用户系统等级升级及发送', '恭喜！您的用户等级已升级！', 1, 1, 0, 1, 1),
(40, '邀请有礼通知', 8, '用户邀请成功获得奖励时', '恭喜您邀请用户成功，获得奖励：{奖励内容}', 1, 1, 0, 1, 1),
(41, '系统通知', 8, '管理员操作积分即发送', '您在{年月日时分}被系统{加减分}{分值}原因：{改分理由}', 1, 1, 0, 1, 1),
(42, '删除通知', 1, '管理员删除内容已审核\\待审核列表帖子', '您{年月日时分}在【{版块名称}】内发布的帖子“{帖子标题}”已被系统管理员删除。删除原因：{删除原因}', 1, 1, 1, 1, 1),
(43, '内容驳回通知', 1, '管理员驳回内容待审核列表帖子', '您{年月日时分}在【{版块名称}】内发布的帖子“{帖子标题}”已被系统管理员审核驳回。驳回原因：{驳回原因}', 1, 1, 1, 1, 1),
(44, '内容审核通过通知', 1, '管理员审核通过内容待审核列表帖子', '您{年月日时分}在【{版块名称}】内发布的帖子“{帖子标题}”已被系统管理员审核通过。', 1, 1, 1, 1, 1),
(45, '举报反馈通知', 1, '有效举报处理成功（发给举报人）即发送', '您于{年月日时分}举报的问题管理员已处理，谢谢您的支持^_^', 1, 1, 1, 1, 1),
(46, '举报处理通知', 1, '有效用户举报处理（发给被举报人）成功即发送', '【警告】您的账号被多次举报，涉及违规操作，已被{XX}。', 1, 1, 1, 1, 1),
(47, '举报处理通知', 1, '有效内容举报处理（发给被举报人）成功即发送', '您{年月日时分}在【{版块名称}】内发布的帖子“{帖子标题}”涉嫌{删帖类型}被举报，已被删除。', 1, 1, 1, 1, 1),
(48, '举报处理通知', 1, '有效评论举报处理成功即发送（发给被举报人）', '您{年月日时分}在“{帖子标题}”下发布的评论“{评论标题}”涉嫌{删帖类型}被举报，已被删除。', 1, 1, 1, 1, 1),
(49, '版块访问审核通知', 1, '管理团队成员/系统审核通过用户的版块访问申请', '您加入【{版块名称}】的申请已被管理员审核通过，恭喜您正式成为其中一员！', 1, 1, 0, 1, 1),
(50, '版块访问驳回通知', 1, '管理团队成员/系统拒绝用户的版块访问申请', '您加入【{版块名称}】的申请已被管理员拒绝，驳回原因：{删除原因}', 1, 1, 0, 1, 1),
(51, '版块访问权限取消通知', 3, '管理团队成员/系统取消用户的版块访问权限', '您已被管理员移出【{版块名称}】，移出原因：{移出原因}', 1, 1, 0, 1, 1),
(52, '版主申请审核通知', 1, '管理团队成员/系统审核通过用户的申请成为版主申请', '恭喜您成为【{版块名称}】的版主，版块管理，任重道远！', 1, 1, 1, 1, 1),
(53, '版主申请驳回通知', 1, '管理团队成员/系统驳回用户的申请成为版主申请', '您的版主申请被驳回，驳回原因：{驳回原因}', 1, 1, 1, 1, 1),
(54, '用户申请发送版主', 1, '申请加入板块通知', '{用户}在{时间}发起了申请加入【{版块名称}】的请求！请及时进行处理！', 1, 1, 0, 1, 1),
(55, '用户发帖需审核通知', 1, '用户发帖需要审核', '您有新的待审核帖子,请及时审核！', 1, 1, 0, 1, 1),
(56, '报名成功通知', 1, '用户成功提交报名信息后出发', '你已成功提交报名信息！ 昵称：{用户名} 活动主题：{活动主题} 活动时间：{开始时间}-{结束时间} 费用：{支付费用}', 1, 1, 1, 1, 1),
(57, '活动提醒', 1, '当用户报名的活动时间倒计时24小时', '你报名的活动即将开始，请准时参加哦！ 昵称：{用户名} 活动主题：{活动主题} 活动时间：{开始时间}-{结束时间} 费用：{支付费用} ', 1, 1, 1, 1, 1),
(59, '活动取消', 1, '当用户报名的活动被取消即发出', '你报名的活动已取消，请知悉。 活动主题：{活动主题} 活动时间：{开始时间}-{结束时间} 费用：{支付费用} 取消原因：{取消原因} 如有任何问题，请尽快联系本次活动发起人{发起人昵称}。', 1, 1, 1, 1, 1),
(60, '内容审核通过消息', 9, '频道管理员后台点击【通过】操作后触发', '您于{推送时间}推送到{XX频道}的帖子{帖子标题}已经通过审核!', 1, 1, 0, 1, 1),
(61, '内容审核驳回消息', 9, '频道管理员后台点击【驳回】操作，填写驳回原因后触发', '您于{推送时间}推送到{XX频道}的帖子{帖子标题}未通过审核，原因：{XXX}!', 1, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_type`
--

CREATE TABLE `osx_message_type` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '名称',
  `icon` varchar(255) NOT NULL COMMENT '图标',
  `color` text NOT NULL COMMENT '图标背景色',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_message_type`
--

INSERT INTO `osx_message_type` (`id`, `name`, `icon`, `color`, `sort`, `status`) VALUES
(1, '通知', '', '', 0, 1),
(2, '评论', '', '', 0, 1),
(3, '被赞', '', '', 0, 1),
(4, '互动消息', '', '', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_message_user_popup`
--

CREATE TABLE `osx_message_user_popup` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户前台弹窗设置表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_os_token`
--

CREATE TABLE `osx_os_token` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `token` text NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_os_token`
--

INSERT INTO `osx_os_token` (`id`, `uid`, `token`, `create_time`) VALUES
(1, 2, '4bb116d3-d820-47ce-a04b-b9860f8792a2', 1572586041),
(2, 2, '17a53617-a818-4de5-b790-309a3da0123f', 1572586972),
(3, 2, 'e2931005-06d8-4bcc-bdbe-d47807deb231', 1572591375);

-- --------------------------------------------------------

--
-- 表的结构 `osx_payment_profit`
--

CREATE TABLE `osx_payment_profit` (
  `id` int(11) NOT NULL COMMENT 'id',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `info` varchar(255) NOT NULL COMMENT '内容',
  `amount` decimal(10,2) NOT NULL COMMENT '交易金额',
  `profit` decimal(10,2) NOT NULL COMMENT '抽成金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_pay_set`
--

CREATE TABLE `osx_pay_set` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付设置表';

--
-- 转存表中的数据 `osx_pay_set`
--

INSERT INTO `osx_pay_set` (`id`, `type`, `status`) VALUES
(1, 'weixin', 1),
(2, 'alipay', 0),
(3, 'yue', 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_pc_set`
--

CREATE TABLE `osx_pc_set` (
  `id` int(11) NOT NULL,
  `is_jump` int(11) NOT NULL DEFAULT '0' COMMENT '是否强制跳转到pc端',
  `jump_type` int(11) NOT NULL DEFAULT '1' COMMENT '强制跳转地址：1，框架页；2，pc端；',
  `frame_url` text NOT NULL COMMENT '框架页地址',
  `pc_url` text NOT NULL COMMENT 'pc端地址',
  `image` text NOT NULL COMMENT '二维码图片'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pc端跳转设置';

--
-- 转存表中的数据 `osx_pc_set`
--

INSERT INTO `osx_pc_set` (`id`, `is_jump`, `jump_type`, `frame_url`, `pc_url`, `image`) VALUES
(1, 0, 1, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `osx_picture`
--

CREATE TABLE `osx_picture` (
  `id` int(11) NOT NULL COMMENT '主键id自增',
  `type` varchar(50) NOT NULL,
  `path` text NOT NULL COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` varchar(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `format` tinyint(4) NOT NULL COMMENT '1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='上传图片' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_power`
--

CREATE TABLE `osx_power` (
  `id` int(11) NOT NULL COMMENT 'id',
  `name` text NOT NULL COMMENT '名称',
  `sign` text NOT NULL COMMENT '标志',
  `value` text NOT NULL COMMENT '标签选择内容 ',
  `input_type` text NOT NULL COMMENT '类型输入',
  `status` int(11) NOT NULL COMMENT '状态',
  `level` int(11) NOT NULL COMMENT '级别',
  `cate` text NOT NULL COMMENT '类型(内置/自定义)',
  `remark` text NOT NULL COMMENT '描述',
  `type` int(11) NOT NULL COMMENT '全站/社区/社区管理',
  `error_message` text NOT NULL COMMENT '错误的报错信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_power`
--

INSERT INTO `osx_power` (`id`, `name`, `sign`, `value`, `input_type`, `status`, `level`, `cate`, `remark`, `type`, `error_message`) VALUES
(1, '应用访问权限', 'visit', '1=>正常访问,0=>拒绝访问', 'radio', 1, 1, '内置', '', 1, '没有权限访问'),
(2, '允许发表常规帖子', 'send_thread', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限发表常规帖子'),
(3, '允许发表资讯', 'send_news', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限发表资讯'),
(4, '允许发表动态', 'send_weibo', '1=>是,0=>否', 'radio', 1, 2, '内置 ', '', 2, '没有权限发表动态'),
(5, '允许发表视频', 'send_video', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限发表视频'),
(6, '允许发评论', 'send_comment', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限发评论'),
(7, '允许社区分享商品', 'share_goods', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限分享商品'),
(8, '允许编辑常规帖子(自己发布的)', 'edit_my_thread', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, '没有权限编辑常规帖子（自己发布的）'),
(9, '允许直接发帖(无需审核)', 'audit', '1=>是,0=>否', 'radio', 1, 2, '内置', '', 2, ''),
(10, '24小时发帖数量上限', 'send_thread_count', '24', 'text', 1, 2, '内置', '', 2, '24小时发帖数量为{num},已达上限'),
(11, '允许置顶', 'set_top', '0=>不允许置顶,1=>允许板块标题置顶,2=>允许标题置顶+详情置顶,3=>允许板块内置顶+详情置顶+应用首页置顶', 'radio', 1, 2, '内置', '设置是否允许置顶管理范围内帖子的级别', 3, '没有权限置顶'),
(12, '允许加精', 'add_digest', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许精华管理范围内的帖子的级别', 3, '没有权限加精'),
(13, '允许高亮', 'height_line', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许高亮管理范围内的帖子', 3, '没有权限高亮'),
(14, '允许推荐', 'recommend', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许高亮管理范围内的帖子推荐到首页', 3, '没有权限推荐'),
(15, '允许编辑帖子', 'edit_thread', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许编辑管理范围内的帖子', 3, '没有权限编辑帖子'),
(16, '允许删除帖子', 'delete_thread', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许删除管理范围内的帖子', 3, '没有权限删除帖子'),
(17, '允许置顶评论', 'set_top_thread', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许置顶管理范围内的帖子', 3, '没有权限置顶评论'),
(18, '允许删除评论', 'delete_comment', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许删除管理范围内的帖子的评论', 3, '没有权限删除评论'),
(19, '允许版块内禁言', 'forum_prohibit', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许版主对用户进行版块内禁言', 3, '没有权限删除禁言用户'),
(20, '允许成员管理', 'group_manage', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许对管理版块成员管理', 3, '没有管理权限'),
(21, '允许版块用户访问申请审核', 'audit_visit', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许处理管理范围内的用户访问申请', 3, '没有审核用户访问申请权限'),
(22, '允许版块内容审核', 'audit_content', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许处理管理范围内的内容审核', 3, '没有内容审核权限'),
(23, '允许版主审核', 'audit_admin', '1=>是,0=>否', 'radio', 1, 2, '内置', '设置是否允许处理管理范围内的版主审核', 3, '没有版主审核权限');

-- --------------------------------------------------------

--
-- 表的结构 `osx_prohibit`
--

CREATE TABLE `osx_prohibit` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL COMMENT '版块id',
  `prohibit_time` int(11) NOT NULL COMMENT '禁言时间',
  `prohibit_reason` int(11) NOT NULL COMMENT '禁言理由',
  `other_reason` text NOT NULL COMMENT '其他理由',
  `create_time` int(11) NOT NULL COMMENT '禁言开始时间',
  `end_time` int(11) NOT NULL COMMENT '禁言结束时间',
  `operation_uid` int(11) NOT NULL COMMENT '操作人uid',
  `operation_identity` int(11) NOT NULL COMMENT '操作人身份',
  `status` int(11) NOT NULL COMMENT '状态',
  `relieve_uid` int(11) NOT NULL DEFAULT '0' COMMENT '解除禁言人uid',
  `relieve_identity` int(11) NOT NULL COMMENT '解除禁言人身份'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='禁言表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_prohibit_reason`
--

CREATE TABLE `osx_prohibit_reason` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '理由',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='禁言理由表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_qiandao`
--

CREATE TABLE `osx_qiandao` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `time` date DEFAULT NULL COMMENT '签到时间',
  `cishu` int(11) DEFAULT NULL COMMENT '签到次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_qiandao`
--

INSERT INTO `osx_qiandao` (`id`, `user_id`, `time`, `cishu`) VALUES
(9, 2, '2019-11-01', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank`
--

CREATE TABLE `osx_rank` (
  `id` int(11) NOT NULL,
  `title_one` text NOT NULL COMMENT '一级榜单名',
  `title_two` text NOT NULL COMMENT '二级榜单名，仅热评榜有效',
  `summary` text NOT NULL COMMENT '榜单说明',
  `frequency` int(11) NOT NULL COMMENT '更新频率，单位小时',
  `update_time` int(11) NOT NULL COMMENT '更新时间（建议是更新时间再减十分钟）',
  `image` text NOT NULL COMMENT '背景图',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排行榜表';

--
-- 转存表中的数据 `osx_rank`
--

INSERT INTO `osx_rank` (`id`, `title_one`, `title_two`, `summary`, `frequency`, `update_time`, `image`, `sort`, `status`) VALUES
(1, '热评榜', '帖子热评榜', '即全站所有公开的帖子在指定时间段内按评论数、点赞数综合指数由大到小排序的榜单', 24, 1585035833, '', 1, 1),
(2, '热评榜', '视频热评榜', '即全站所有公开的视频在指定时间段内按评论数、点赞数综合指数由大到小排序的榜单', 24, 1585035833, '', 2, 1),
(3, '热评榜', '动态热评榜', '即全站所有公开的视频在指定时间段内按评论数、点赞数综合指数由大到小排序的榜单', 24, 1585035833, '', 3, 1),
(4, '热评榜', '资讯热评榜', '即全站所有公开的视频在指定时间段内按评论数、点赞数综合指数由大到小排序的榜单', 24, 1585035833, '', 4, 1),
(5, '话题榜', '', '即话题在某一段时间段内按讨论、阅读综合指数由大到小排序的榜单', 24, 1585124215, '', 5, 1),
(6, '人气榜', '', '即用户按总粉丝数、7日新增粉丝数由大到小排序的榜单', 1, 1585035833, '', 6, 1),
(7, '热搜榜', '', '即某一段时间内用户搜索过的关键词按搜索量由多到少排序的榜单', 24, 1585035833, '', 7, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_del`
--

CREATE TABLE `osx_rank_del` (
  `id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL COMMENT 'thread：帖子；topic：话题；',
  `pid` int(11) NOT NULL COMMENT '帖子，话题，搜索对应表的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='下榜表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_search`
--

CREATE TABLE `osx_rank_search` (
  `id` int(11) NOT NULL,
  `keyword` text NOT NULL COMMENT '搜索词',
  `type` int(11) NOT NULL COMMENT '1：系统；2：自定义；',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '搜索量',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认搜索',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '是否下榜'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='热搜榜表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_thread`
--

CREATE TABLE `osx_rank_thread` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL COMMENT '帖子id',
  `type` int(11) NOT NULL COMMENT '榜单类型：1，帖子；2，视频；3，动态；4，资讯；',
  `time_type` int(11) NOT NULL COMMENT '榜单时间类型：1，24小时；2,7天；3,30天；4，总排行；',
  `sort` int(11) NOT NULL COMMENT '排序',
  `hot` int(11) NOT NULL COMMENT '热度值：点赞*0.5+评论*0.5',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='热评榜表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_thread_time`
--

CREATE TABLE `osx_rank_thread_time` (
  `id` int(11) NOT NULL,
  `time_type` int(11) NOT NULL COMMENT '榜单时间类型：1，24小时；2，7天；3，30天；4，总排行；',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='热评榜更新时间表';

--
-- 转存表中的数据 `osx_rank_thread_time`
--

INSERT INTO `osx_rank_thread_time` (`id`, `time_type`, `update_time`) VALUES
(1, 1, 1585123563),
(2, 2, 1585123563),
(3, 3, 1585123563),
(4, 4, 1585123563);

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_topic`
--

CREATE TABLE `osx_rank_topic` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL COMMENT '话题id',
  `sort` int(11) NOT NULL COMMENT '排序',
  `hot` int(11) NOT NULL COMMENT '热度值：帖子数*0.5+浏览量*0.5',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='话题榜表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_user`
--

CREATE TABLE `osx_rank_user` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `nickname` text NOT NULL COMMENT '昵称',
  `avatar` text NOT NULL COMMENT '头像',
  `fans` int(11) NOT NULL COMMENT '粉丝数',
  `last_fans` int(11) NOT NULL COMMENT '上周粉丝数',
  `rank` int(11) NOT NULL COMMENT '总排名',
  `new_fans` int(11) NOT NULL COMMENT '新增粉丝数',
  `week_rank` int(11) NOT NULL COMMENT '周排名',
  `signature` text NOT NULL COMMENT '签名',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人气榜表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_rank_user_time`
--

CREATE TABLE `osx_rank_user_time` (
  `id` int(11) NOT NULL,
  `page` int(11) NOT NULL COMMENT '当前执行页数',
  `page2` int(11) NOT NULL COMMENT '周人气榜页数',
  `type` text NOT NULL COMMENT '执行状态',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人气榜计划任务执行时间';

--
-- 转存表中的数据 `osx_rank_user_time`
--

INSERT INTO `osx_rank_user_time` (`id`, `page`, `page2`, `type`, `update_time`) VALUES
(1, 1, 1, 'end', 1515546180);

-- --------------------------------------------------------

--
-- 表的结构 `osx_renwu_jiafen_log`
--

CREATE TABLE `osx_renwu_jiafen_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `explain` varchar(100) DEFAULT NULL COMMENT '加分说明',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `exp` int(11) DEFAULT '0' COMMENT '经验值',
  `fly` int(11) DEFAULT '0' COMMENT '社区积分',
  `buy` int(11) DEFAULT '0' COMMENT '购物积分',
  `gong` int(11) DEFAULT '0' COMMENT '贡献值',
  `one` int(11) DEFAULT '0' COMMENT '自定义积分1',
  `two` int(11) DEFAULT '0' COMMENT '自定义积分2',
  `three` int(11) DEFAULT '0' COMMENT '自定义3',
  `four` int(11) DEFAULT '0' COMMENT '自定义4',
  `five` int(11) DEFAULT '0' COMMENT '自定义5',
  `zong` int(11) DEFAULT '0' COMMENT '行为总次数',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1：加分；0：减分；',
  `model` text NOT NULL COMMENT '触发模型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_renwu_nav`
--

CREATE TABLE `osx_renwu_nav` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '描述',
  `image` text NOT NULL COMMENT '图片',
  `link` text NOT NULL COMMENT '调整路径',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务导航表';

--
-- 转存表中的数据 `osx_renwu_nav`
--

INSERT INTO `osx_renwu_nav` (`id`, `title`, `content`, `image`, `link`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, '任务中心', '轻松赚积分', '', '任务中心-任务中心首页||/packageA/sign-in/task-center', 1, 1, 1580870359, 1580871219),
(2, '积分规则', '小积分大玩法', '', '任务中心-积分规则||/packageA/article/article?id=3', 1, 1, 1580870359, 1580871235);

-- --------------------------------------------------------

--
-- 表的结构 `osx_report`
--

CREATE TABLE `osx_report` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '举报用户',
  `to_uid` int(11) NOT NULL COMMENT '被举报用户',
  `content` text NOT NULL COMMENT '举报内容',
  `plate` int(11) NOT NULL COMMENT '版块',
  `cate` int(11) NOT NULL COMMENT '分类',
  `create_time` int(11) NOT NULL COMMENT '投诉时间',
  `reason` int(11) NOT NULL COMMENT '投诉原因',
  `status` int(11) NOT NULL COMMENT '状态',
  `is_deal` int(11) NOT NULL DEFAULT '0' COMMENT '是否处理',
  `type` int(11) NOT NULL DEFAULT '0',
  `deal_type` int(11) NOT NULL COMMENT '处理方式',
  `deal_time` int(11) NOT NULL COMMENT '处理时间',
  `prohibit` int(11) NOT NULL COMMENT '禁言类型',
  `prohibit_time` int(11) NOT NULL COMMENT '禁言到期时间',
  `other_reason` text NOT NULL COMMENT '其他原因',
  `operation_uid` int(11) NOT NULL COMMENT '操作人uid',
  `operation_identity` int(11) NOT NULL COMMENT '操作人身份'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_report_prohibit`
--

CREATE TABLE `osx_report_prohibit` (
  `id` int(11) NOT NULL COMMENT 'id',
  `num` int(11) NOT NULL COMMENT '数量',
  `time_type` int(11) NOT NULL COMMENT '类型0小时1天数',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_report_reason`
--

CREATE TABLE `osx_report_reason` (
  `id` int(11) NOT NULL COMMENT 'id',
  `name` text NOT NULL COMMENT '名称',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_report_reason`
--

INSERT INTO `osx_report_reason` (`id`, `name`, `sort`, `status`) VALUES
(1, '垃圾营销', 1, 1),
(2, '恶意广告', 2, 1),
(3, '谣言', 5, 1),
(4, '淫秽色情', 1, 1),
(5, '政治敏感', 4, 1),
(6, '欺骗诈骗', 1, 1),
(7, '不知道了', 0, 1),
(8, '其他原因', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_access_token`
--

CREATE TABLE `osx_routine_access_token` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '小程序access_token表ID',
  `access_token` varchar(256) NOT NULL COMMENT 'openid',
  `stop_time` int(11) UNSIGNED NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小程序access_token表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_routine_access_token`
--

INSERT INTO `osx_routine_access_token` (`id`, `access_token`, `stop_time`) VALUES
(1, '20_z3MAutcbznCSyQPqMVOQVRUktcvLYUXAAICpCMXkpu5rLoVnBB0u88rnJr1sWDJlwj-S6aVhmswmLdW86e9Bg2ugd3BOayE6ntY6FfckSXWgvW2y5N0bLkBxHpCjJH2bQpuvnmMIZr08G32hWSQfACAZVT', 1554809658);

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_ad`
--

CREATE TABLE `osx_routine_ad` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '广告名称',
  `ad_unit_id` varchar(32) NOT NULL DEFAULT '' COMMENT '广告ID',
  `ad_slot` varchar(32) NOT NULL DEFAULT '' COMMENT '广告类型',
  `ad_info` varchar(512) NOT NULL DEFAULT '' COMMENT '广告信息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态，0=禁用，1=开启',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示，0=隐藏，1=显示',
  `remark` varchar(128) NOT NULL DEFAULT '' COMMENT '备注',
  `renwu_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `trigger_gap` int(11) NOT NULL DEFAULT '0' COMMENT '触发时间间隔',
  `ad_theme` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '广告主题，1=白色，2=黑色',
  `grid_count` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '格子个数',
  `position` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '广告位置，1=信息流，2=悬浮格子',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,0=未删除,1=删除',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信小程序广告表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_ad_position`
--

CREATE TABLE `osx_routine_ad_position` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `routine_ad_id` int(11) NOT NULL DEFAULT '0' COMMENT '广告表ID',
  `ad_type` int(11) NOT NULL DEFAULT '0' COMMENT '固定广告位置类型',
  `trigger_scene` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '触发场景，1=商城付款成功，2=底部Tab栏切换，3=视频播放暂停'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信小程序广告位置表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_form_id`
--

CREATE TABLE `osx_routine_form_id` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '表单ID表ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户uid',
  `form_id` varchar(32) NOT NULL COMMENT '表单ID',
  `stop_time` int(11) UNSIGNED DEFAULT NULL COMMENT '表单ID失效时间',
  `status` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '状态1 未使用 2不能使用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表单id表记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_qrcode`
--

CREATE TABLE `osx_routine_qrcode` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '微信二维码ID',
  `third_type` varchar(32) NOT NULL COMMENT '二维码类型 spread(用户推广) product_spread(产品推广)',
  `third_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `status` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '状态 0不可用 1可用',
  `add_time` varchar(255) DEFAULT NULL COMMENT '添加时间',
  `page` varchar(255) DEFAULT NULL COMMENT '小程序页面路径带参数',
  `qrcode_url` varchar(255) DEFAULT NULL COMMENT '小程序二维码路径',
  `url_time` int(11) UNSIGNED DEFAULT NULL COMMENT '二维码添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小程序二维码管理表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_routine_template`
--

CREATE TABLE `osx_routine_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '模板id',
  `tempkey` char(50) NOT NULL COMMENT '模板编号',
  `name` char(100) NOT NULL COMMENT '模板名',
  `content` varchar(1000) NOT NULL COMMENT '回复内容',
  `tempid` char(100) DEFAULT NULL COMMENT '模板ID',
  `add_time` varchar(15) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信模板' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_routine_template`
--

INSERT INTO `osx_routine_template` (`id`, `tempkey`, `name`, `content`, `tempid`, `add_time`, `status`) VALUES
(13, 'AT0007', '订单发货提醒', '订单号{{keyword1.DATA}}\n快递公司{{keyword2.DATA}}\n快递单号{{keyword3.DATA}}\n发货时间{{keyword4.DATA}}\n备注{{keyword5.DATA}}', 'CyPxnxg-9eRgXoYUKhXY4IqXI4hecaUzgEQGX76OXng', '1534469928', 1),
(14, 'AT0787', '退款成功通知', '订单号{{keyword1.DATA}}\n退款时间{{keyword2.DATA}}\n退款金额{{keyword3.DATA}}\n退款方式{{keyword4.DATA}}\n备注{{keyword5.DATA}}', 'iLHSBbVVtg_ijs-hVABl5p-yaBV8JxpInqXIZy2c5To', '1534469993', 1),
(15, 'AT0009', '订单支付成功通知', '单号{{keyword1.DATA}}\n下单时间{{keyword2.DATA}}\n订单状态{{keyword3.DATA}}\n支付金额{{keyword4.DATA}}\n支付方式{{keyword5.DATA}}', 'bfXGbrwl70jdlvCnO_vZ7AeXVDsziYQW7oGT2KXiDz4', '1534470043', 1),
(16, 'AT1173', '砍价成功通知', '商品名称{{keyword1.DATA}}\n砍价金额{{keyword2.DATA}}\n底价{{keyword3.DATA}}\n砍掉价格{{keyword4.DATA}}\n支付金额{{keyword5.DATA}}\n备注{{keyword6.DATA}}', 'l3JCopf5cgNLXtmziLTxkVTHtImHPmDyHp5wBRZd3SI', '1534470085', 1),
(17, 'AT0036', '退款通知', '订单编号{{keyword1.DATA}}\n拒绝退款原因{{keyword2.DATA}}\n退款时间{{keyword3.DATA}}\n退款金额{{keyword4.DATA}}\n退款方式{{keyword5.DATA}}', '14pyhB_6xYAXfEk_iVBbtLWkph0rAqompGPbuDP3CWo', '1534470134', 1),
(19, 'AT2430', '拼团取消通知', '活动名称{{keyword1.DATA}}\n订单编号{{keyword2.DATA}}\n订单金额{{keyword3.DATA}}', 'KynVVBJcoYgaTbcnAKuy5N0YpAa82xZG1KCQIlb-Ws8', '1553910500', 1),
(20, 'AT0310', '拼团失败通知', '商品名称{{keyword1.DATA}}\n失败原因{{keyword2.DATA}}\n订单号{{keyword3.DATA}}\n开团时间{{keyword4.DATA}}\n退款金额{{keyword5.DATA}}', 'uOXsl0qRtuMWhExHGk57Hf_GLkYT6q9m16U0bg95uIQ', '1553910844', 1),
(21, 'AT0051', '拼团成功通知', '活动名称{{keyword1.DATA}}\n团长{{keyword2.DATA}}\n成团时间{{keyword3.DATA}}\n拼团价{{keyword4.DATA}}', '4uXFnRF1jnpG1R0F71JOj-NCnYbGwcI20xgihfx8Xzs', '1553911022', 1),
(22, 'AT0541', '开团成功提醒', '开团时间{{keyword1.DATA}}\n截至时间{{keyword2.DATA}}\n产品名称{{keyword3.DATA}}\n单号{{keyword4.DATA}}\n支付金额{{keyword5.DATA}}', '6rU48pJrJS7Y1MixVyGMfQmrXRtOIZZjJtc7q9kwo24', '1555133496', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_script`
--

CREATE TABLE `osx_script` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '名称',
  `type` int(11) NOT NULL COMMENT '类型',
  `create_time` int(11) NOT NULL COMMENT '执行时间',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计划任务表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_search`
--

CREATE TABLE `osx_search` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `keyword` text NOT NULL COMMENT '搜索词',
  `model` varchar(50) NOT NULL COMMENT '触发模块',
  `create_time` int(11) NOT NULL COMMENT '搜索时间',
  `source` varchar(50) NOT NULL COMMENT '来源'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='搜索表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_sell`
--

CREATE TABLE `osx_sell` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `child1_num` int(11) NOT NULL DEFAULT '0' COMMENT '一级下级数量',
  `child2_num` int(11) NOT NULL DEFAULT '0' COMMENT '二级下级数量',
  `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '团队订单数量（只算下级订单）',
  `order_money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '团队订单总额',
  `total_income` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '总收益，只统计已结算部分',
  `out_income` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '提现总收益',
  `out_num` int(11) NOT NULL DEFAULT '0' COMMENT '提现次数',
  `has_income` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '可提现收益',
  `father1` int(11) NOT NULL COMMENT '一级上级',
  `father2` int(11) NOT NULL COMMENT '二级上级',
  `create_time` int(11) NOT NULL COMMENT '申请时间',
  `audit_time` int(11) NOT NULL COMMENT '审核时间',
  `fail_reason` varchar(500) NOT NULL DEFAULT '' COMMENT '驳回理由',
  `need_tip` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否需要提醒',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态。未审核	2;已通过 1;已驳回	0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分销商信息表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_sell_order`
--

CREATE TABLE `osx_sell_order` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `order_id` varchar(32) NOT NULL DEFAULT '' COMMENT '订单号',
  `goods_info` varchar(500) NOT NULL DEFAULT '' COMMENT '每个商品[id|购买数量|sku]，多个组成二维数组后json_encode',
  `goods_title` text NOT NULL COMMENT '商品标题，多个‘，’号拼接，用于后台列表页筛选',
  `order_status` tinyint(4) NOT NULL COMMENT '订单状态（-1：已退款（申请的或者后台直接退款）；0：待发货（已支付,包括申请退款中）；1：待收货；2：已收货(订单完成,待评价,准备返利）；3：交易完成,已评价；4：待付款）',
  `give_back_time` int(11) NOT NULL COMMENT '最早结算日期，只结算上个月的返利到可提现中，订单完成时计算该时间，按后台配置的结算日X号确定该时间值为下个月的X号，计划任务执行时，只结算该值小于等于当前时间的部分，计划任务执行',
  `back_status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '返利状态，已结算1；未结算2；已关闭0（订单失败，取消、退款等）',
  `end_time` int(11) NOT NULL COMMENT '实际结算时间',
  `pay_money` decimal(8,2) NOT NULL COMMENT '订单支付金额',
  `back_money` decimal(8,2) NOT NULL COMMENT '订单返利金额',
  `father1` int(11) NOT NULL COMMENT '一级上级',
  `father1_back` decimal(8,2) NOT NULL COMMENT '一级上级返利金额',
  `father2` int(11) NOT NULL COMMENT '二级上级',
  `father2_back` decimal(8,2) NOT NULL COMMENT '二级上级返利金额',
  `create_time` int(11) NOT NULL COMMENT '下单时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推广订单（分销相关订单）' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_sensitive`
--

CREATE TABLE `osx_sensitive` (
  `id` int(11) NOT NULL,
  `sensitive` text NOT NULL COMMENT '敏感词',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `level` int(11) NOT NULL COMMENT '级别：1替换；2删除；3审核；',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='敏感词表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_sensitive_log`
--

CREATE TABLE `osx_sensitive_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '触发人',
  `sensitive` text NOT NULL COMMENT '触发敏感词',
  `content` text NOT NULL COMMENT '触发内容',
  `create_time` int(11) NOT NULL COMMENT '触发时间',
  `action` varchar(50) NOT NULL COMMENT '触发事件',
  `level` int(11) NOT NULL COMMENT '级别：1替换；2删除；3审核；',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='敏感词触发记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_shop_column`
--

CREATE TABLE `osx_shop_column` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '栏目名称',
  `type` int(11) NOT NULL COMMENT '属性',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分商城栏目设置';

--
-- 转存表中的数据 `osx_shop_column`
--

INSERT INTO `osx_shop_column` (`id`, `name`, `type`, `sort`, `status`) VALUES
(1, '每日热销', 1, 1, 1),
(2, '精品优选', 1, 2, 1),
(3, '限时兑换', 1, 3, 1),
(4, '0元福利', 1, 4, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_shop_order`
--

CREATE TABLE `osx_shop_order` (
  `id` int(11) NOT NULL,
  `order_id` varchar(32) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL,
  `real_name` text NOT NULL COMMENT '用户姓名',
  `user_phone` varchar(11) NOT NULL COMMENT '用户手机',
  `user_address` text NOT NULL COMMENT '用户地址',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  `total_num` int(11) NOT NULL COMMENT '商品数量',
  `pay_price` decimal(8,2) NOT NULL COMMENT '支付总金额',
  `pay_score` int(11) NOT NULL COMMENT '支付积分',
  `pay_postage` decimal(8,2) NOT NULL COMMENT '支付邮费',
  `pay_cash` decimal(8,2) NOT NULL COMMENT '支付现金',
  `paid` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `pay_type` text NOT NULL COMMENT '支付方式',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态',
  `delivery_name` text NOT NULL COMMENT '快递公司',
  `delivery_type` varchar(32) NOT NULL COMMENT '发货类型',
  `delivery_id` varchar(64) NOT NULL COMMENT '快递单号',
  `unique` int(11) NOT NULL COMMENT '唯一id',
  `no_delivery` int(11) NOT NULL COMMENT '是否需要物流',
  `remark` text NOT NULL COMMENT '管理员备注',
  `refund_price` decimal(8,2) NOT NULL COMMENT '退款金额',
  `refund_score` int(11) NOT NULL COMMENT '退款积分',
  `mark` text NOT NULL COMMENT '用户备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分商城订单表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_shop_order_status`
--

CREATE TABLE `osx_shop_order_status` (
  `oid` int(10) UNSIGNED NOT NULL COMMENT '订单id',
  `change_type` varchar(32) NOT NULL COMMENT '操作类型',
  `change_message` varchar(256) NOT NULL COMMENT '操作备注',
  `change_time` int(10) UNSIGNED NOT NULL COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单操作记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_shop_product`
--

CREATE TABLE `osx_shop_product` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL COMMENT '商品图片',
  `slider_image` text NOT NULL COMMENT '轮播图',
  `store_name` text NOT NULL COMMENT '商品名称',
  `store_info` text NOT NULL COMMENT '商品简介',
  `description` text NOT NULL COMMENT '商品详情',
  `score_price` int(11) NOT NULL COMMENT '积分价格',
  `cash_price` decimal(8,2) NOT NULL COMMENT '现金价格',
  `postage` decimal(8,2) NOT NULL COMMENT '邮费',
  `unit_name` varchar(50) NOT NULL DEFAULT '件' COMMENT '单位名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `sales` int(11) NOT NULL COMMENT '兑换数量',
  `ficti` int(11) NOT NULL COMMENT '虚拟兑换数量',
  `limit_num` int(11) NOT NULL COMMENT '限购数',
  `stock` int(11) NOT NULL COMMENT '库存',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `is_on` int(11) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `column_id` int(11) NOT NULL DEFAULT '1' COMMENT '栏目id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分商品表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_shop_score_type`
--

CREATE TABLE `osx_shop_score_type` (
  `id` int(11) NOT NULL,
  `flag` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分商城使用积分类型';

--
-- 转存表中的数据 `osx_shop_score_type`
--

INSERT INTO `osx_shop_score_type` (`id`, `flag`) VALUES
(1, 'buy');

-- --------------------------------------------------------

--
-- 表的结构 `osx_stat_reg_info`
--

CREATE TABLE `osx_stat_reg_info` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `platform` int(11) NOT NULL COMMENT '1.Android版  2.iOS版   3.H5版   4.微信小程序版  5.支付宝小程序版  6.百度小程序版  7.头条小程序版  8.H5  9.PC端',
  `reg_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_stat_reg_info`
--

INSERT INTO `osx_stat_reg_info` (`id`, `uid`, `platform`, `reg_time`) VALUES
(1, 2, 0, 1572586041);

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_bargain`
--

CREATE TABLE `osx_store_bargain` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '砍价产品ID',
  `product_id` int(11) UNSIGNED NOT NULL COMMENT '关联产品ID',
  `title` varchar(255) NOT NULL COMMENT '砍价活动名称',
  `image` varchar(150) NOT NULL COMMENT '砍价活动图片',
  `unit_name` varchar(16) DEFAULT NULL COMMENT '单位名称',
  `stock` int(11) UNSIGNED DEFAULT NULL COMMENT '库存',
  `sales` int(11) UNSIGNED DEFAULT NULL COMMENT '销量',
  `images` varchar(1000) NOT NULL COMMENT '砍价产品轮播图',
  `start_time` int(11) UNSIGNED NOT NULL COMMENT '砍价开启时间',
  `stop_time` int(11) UNSIGNED NOT NULL COMMENT '砍价结束时间',
  `store_name` varchar(255) DEFAULT NULL COMMENT '砍价产品名称',
  `price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '砍价金额',
  `min_price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '砍价商品最低价',
  `num` int(11) UNSIGNED DEFAULT NULL COMMENT '每次购买的砍价产品数量',
  `bargain_max_price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '用户每次砍价的最大金额',
  `bargain_min_price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '用户每次砍价的最小金额',
  `bargain_num` int(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '用户每次砍价的次数',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '砍价状态 0(到砍价时间不自动开启)  1(到砍价时间自动开启时间)',
  `description` text COMMENT '砍价详情',
  `give_integral` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '反多少积分',
  `info` varchar(255) DEFAULT NULL COMMENT '砍价活动简介',
  `cost` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '成本价',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否推荐0不推荐1推荐',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1删除',
  `add_time` int(11) UNSIGNED DEFAULT NULL COMMENT '添加时间',
  `is_postage` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否包邮 0不包邮 1包邮',
  `postage` decimal(10,2) UNSIGNED DEFAULT NULL COMMENT '邮费',
  `rule` text COMMENT '砍价规则',
  `look` int(11) UNSIGNED DEFAULT '0' COMMENT '砍价产品浏览量',
  `share` int(11) UNSIGNED DEFAULT '0' COMMENT '砍价产品分享量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_bargain_user`
--

CREATE TABLE `osx_store_bargain_user` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '用户参与砍价表ID',
  `uid` int(11) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `bargain_id` int(11) UNSIGNED DEFAULT NULL COMMENT '砍价产品id',
  `bargain_price_min` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '砍价的最低价',
  `bargain_price` decimal(8,2) DEFAULT NULL COMMENT '砍价金额',
  `price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '砍掉的价格',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态 1参与中 2 活动结束参与失败 3活动结束参与成功',
  `add_time` int(11) UNSIGNED DEFAULT NULL COMMENT '参与时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否取消'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户参与砍价表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_bargain_user_help`
--

CREATE TABLE `osx_store_bargain_user_help` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '砍价用户帮助表ID',
  `uid` int(11) UNSIGNED DEFAULT NULL COMMENT '帮助的用户id',
  `bargain_id` int(11) UNSIGNED DEFAULT NULL COMMENT '砍价产品ID',
  `bargain_user_id` int(11) UNSIGNED DEFAULT NULL COMMENT '用户参与砍价表id',
  `price` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '帮助砍价多少金额',
  `add_time` int(11) UNSIGNED DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价用户帮助表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_cart`
--

CREATE TABLE `osx_store_cart` (
  `id` bigint(8) UNSIGNED NOT NULL COMMENT '购物车表ID',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `type` varchar(32) NOT NULL COMMENT '类型',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `product_attr_unique` varchar(16) NOT NULL DEFAULT '' COMMENT '商品属性',
  `cart_num` smallint(5) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品数量',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `is_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = 未购买 1 = 已购买',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为立即购买',
  `combination_id` int(11) UNSIGNED DEFAULT '0' COMMENT '拼团id',
  `seckill_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '秒杀产品ID',
  `bargain_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '砍价id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_category`
--

CREATE TABLE `osx_store_category` (
  `id` mediumint(11) NOT NULL COMMENT '商品分类表ID',
  `pid` mediumint(11) NOT NULL COMMENT '父id',
  `cate_name` varchar(100) NOT NULL COMMENT '分类名称',
  `sort` mediumint(11) NOT NULL COMMENT '排序',
  `pic` varchar(128) NOT NULL DEFAULT '' COMMENT '图标',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否推荐',
  `add_time` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_store_category`
--

INSERT INTO `osx_store_category` (`id`, `pid`, `cate_name`, `sort`, `pic`, `is_show`, `add_time`) VALUES
(1, 0, '箱包手袋', 1, '', 1, 1572597574),
(2, 0, '手机数码', 2, '', 1, 1572597593),
(3, 0, '个体洗护', 3, '', 1, 1572597616),
(4, 0, '食品生鲜', 4, '', 1, 1572597636),
(5, 0, '服装鞋帽', 5, '', 1, 1572597665),
(6, 1, '手提包', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9734e167d.png', 1, 1572597734),
(7, 2, '电子手表', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb973580c3c.png', 1, 1572597759),
(8, 3, '宝宝洗浴', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb973545adc.png', 1, 1572597780),
(9, 4, '蔬菜速递', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9735729e2.png', 1, 1572597805),
(10, 5, '精品女装', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb973537793.png', 1, 1572597831),
(11, 6, '精品女包', 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9735729e2.png', 1, 1572597865);

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_category_column`
--

CREATE TABLE `osx_store_category_column` (
  `id` mediumint(11) NOT NULL COMMENT '商品分类表ID',
  `pid` mediumint(11) NOT NULL COMMENT '父id',
  `cate_name` varchar(100) NOT NULL COMMENT '分类名称',
  `sort` mediumint(11) NOT NULL COMMENT '排序',
  `pic` varchar(128) NOT NULL DEFAULT '' COMMENT '图标',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否推荐',
  `add_time` int(11) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_combination`
--

CREATE TABLE `osx_store_combination` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品id',
  `mer_id` int(10) UNSIGNED DEFAULT '0' COMMENT '商户id',
  `image` varchar(255) NOT NULL COMMENT '推荐图',
  `images` varchar(1000) NOT NULL COMMENT '轮播图',
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `attr` varchar(255) DEFAULT NULL COMMENT '活动属性',
  `people` int(2) UNSIGNED NOT NULL COMMENT '参团人数',
  `info` varchar(255) NOT NULL COMMENT '简介',
  `price` decimal(10,2) UNSIGNED NOT NULL COMMENT '价格',
  `sort` int(10) UNSIGNED NOT NULL COMMENT '排序',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `stock` int(10) UNSIGNED NOT NULL COMMENT '库存',
  `add_time` varchar(128) NOT NULL COMMENT '添加时间',
  `is_host` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '推荐',
  `is_show` tinyint(1) UNSIGNED NOT NULL COMMENT '产品状态',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `combination` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `mer_use` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '商户是否可用1可用0不可用',
  `is_postage` tinyint(1) UNSIGNED NOT NULL COMMENT '是否包邮1是0否',
  `postage` decimal(10,2) UNSIGNED NOT NULL COMMENT '邮费',
  `description` text NOT NULL COMMENT '拼团内容',
  `start_time` int(11) UNSIGNED NOT NULL COMMENT '拼团开始时间',
  `stop_time` int(11) UNSIGNED NOT NULL COMMENT '拼团结束时间',
  `cost` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '拼图产品成本',
  `browse` int(11) DEFAULT '0' COMMENT '浏览量',
  `unit_name` varchar(32) NOT NULL COMMENT '单位名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团产品表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_combination_attr`
--

CREATE TABLE `osx_store_combination_attr` (
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品ID',
  `attr_name` varchar(32) NOT NULL COMMENT '属性名',
  `attr_values` varchar(256) NOT NULL COMMENT '属性值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_combination_attr_result`
--

CREATE TABLE `osx_store_combination_attr_result` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `result` text NOT NULL COMMENT '商品属性参数',
  `change_time` int(10) UNSIGNED NOT NULL COMMENT '上次修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性详情表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_combination_attr_value`
--

CREATE TABLE `osx_store_combination_attr_value` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `suk` varchar(128) NOT NULL COMMENT '商品属性索引值 (attr_value|attr_value[|....])',
  `stock` int(10) UNSIGNED NOT NULL COMMENT '属性对应的库存',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `price` decimal(8,2) UNSIGNED NOT NULL COMMENT '属性金额',
  `image` varchar(128) DEFAULT NULL COMMENT '图片',
  `unique` char(8) NOT NULL DEFAULT '' COMMENT '唯一值',
  `cost` decimal(8,2) UNSIGNED NOT NULL COMMENT '成本价'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性值表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_coupon`
--

CREATE TABLE `osx_store_coupon` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '优惠券表ID',
  `title` varchar(64) NOT NULL COMMENT '优惠券名称',
  `integral` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '兑换消耗积分值',
  `coupon_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '兑换的优惠券面值',
  `use_min_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最低消费多少金额可用优惠券',
  `coupon_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券有效期限（单位：天）',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态（0：关闭，1：开启）',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '兑换项目添加时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_coupon_issue`
--

CREATE TABLE `osx_store_coupon_issue` (
  `id` int(10) UNSIGNED NOT NULL,
  `cid` int(10) DEFAULT NULL COMMENT '优惠券ID',
  `start_time` int(10) DEFAULT NULL COMMENT '优惠券领取开启时间',
  `end_time` int(10) DEFAULT NULL COMMENT '优惠券领取结束时间',
  `total_count` int(10) DEFAULT NULL COMMENT '优惠券领取数量',
  `remain_count` int(10) DEFAULT NULL COMMENT '优惠券剩余领取数量',
  `is_permanent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否无限张数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 正常 0 未开启 -1 已无效',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `add_time` int(10) DEFAULT NULL COMMENT '优惠券添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券前台领取表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_coupon_issue_user`
--

CREATE TABLE `osx_store_coupon_issue_user` (
  `uid` int(10) DEFAULT NULL COMMENT '领取优惠券用户ID',
  `issue_coupon_id` int(10) DEFAULT NULL COMMENT '优惠券前台领取ID',
  `add_time` int(10) DEFAULT NULL COMMENT '领取时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券前台用户领取记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_coupon_user`
--

CREATE TABLE `osx_store_coupon_user` (
  `id` int(11) NOT NULL COMMENT '优惠券发放记录id',
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '兑换的项目id',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券所属用户',
  `coupon_title` varchar(32) NOT NULL COMMENT '优惠券名称',
  `coupon_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '优惠券的面值',
  `use_min_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '最低消费多少金额可用优惠券',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '优惠券创建时间',
  `end_time` int(11) UNSIGNED NOT NULL COMMENT '优惠券结束时间',
  `use_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '使用时间',
  `type` varchar(32) NOT NULL DEFAULT 'send' COMMENT '获取方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态（0：未使用，1：已使用, 2:已过期）',
  `is_fail` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券发放记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_order`
--

CREATE TABLE `osx_store_order` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '订单ID',
  `order_id` varchar(32) NOT NULL COMMENT '订单号',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `real_name` varchar(32) NOT NULL COMMENT '用户姓名',
  `user_phone` varchar(18) NOT NULL COMMENT '用户电话',
  `user_address` varchar(100) NOT NULL COMMENT '详细地址',
  `cart_id` varchar(256) NOT NULL DEFAULT '[]' COMMENT '购物车id',
  `total_num` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单商品总数',
  `total_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '订单总价',
  `total_postage` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `pay_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
  `pay_postage` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '支付邮费',
  `deduction_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '抵扣金额',
  `coupon_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `coupon_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '优惠券金额',
  `paid` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付状态',
  `pay_time` int(11) UNSIGNED DEFAULT NULL COMMENT '支付时间',
  `pay_type` varchar(32) NOT NULL COMMENT '支付方式',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态（-1 : 申请退款 -2 : 退货成功 0：待发货；1：待收货；2：已收货；3：待评价；-1：已退款）',
  `refund_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 未退款 1 申请中 2 已退款',
  `refund_reason_wap_img` varchar(255) DEFAULT NULL COMMENT '退款图片',
  `refund_reason_wap_explain` varchar(255) DEFAULT NULL COMMENT '退款用户说明',
  `refund_reason_time` int(11) UNSIGNED DEFAULT NULL COMMENT '退款时间',
  `refund_reason_wap` varchar(255) DEFAULT NULL COMMENT '前台拒绝退款原因',
  `refund_reason` varchar(255) DEFAULT NULL COMMENT '拒绝退款的理由',
  `refund_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '退款金额',
  `delivery_name` varchar(64) DEFAULT NULL COMMENT '快递名称/送货人姓名',
  `delivery_type` varchar(32) DEFAULT NULL COMMENT '发货类型',
  `delivery_id` varchar(64) DEFAULT NULL COMMENT '快递单号/手机号',
  `gain_integral` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '消费赚取积分',
  `use_integral` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '使用积分',
  `back_integral` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '给用户退了多少积分',
  `mark` varchar(512) NOT NULL COMMENT '备注',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除',
  `unique` char(32) NOT NULL COMMENT '唯一id(md5加密)类似id',
  `remark` varchar(512) DEFAULT NULL COMMENT '管理员备注',
  `mer_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商户ID',
  `is_mer_check` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `combination_id` int(11) UNSIGNED DEFAULT '0' COMMENT '拼团产品id0一般产品',
  `pink_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '拼团id 0没有拼团',
  `cost` decimal(8,2) UNSIGNED NOT NULL COMMENT '成本价',
  `seckill_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '秒杀产品ID',
  `bargain_id` int(11) UNSIGNED DEFAULT '0' COMMENT '砍价id',
  `is_channel` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '支付渠道(0微信公众号1微信小程序)',
  `is_zg` int(11) NOT NULL DEFAULT '0' COMMENT '是否是智果订单：0，不是；1，是；',
  `is_column` tinyint(4) NOT NULL COMMENT '是否是专栏',
  `no_delivery` int(11) NOT NULL DEFAULT '0' COMMENT '是否需要物流',
  `delivery_time` int(11) NOT NULL COMMENT '发货时间',
  `receiving_time` int(11) NOT NULL COMMENT '收货时间',
  `score_num` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '购物积分使用数',
  `is_message` int(11) NOT NULL DEFAULT '0' COMMENT '是否已消息提醒'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_order_cart_info`
--

CREATE TABLE `osx_store_order_cart_info` (
  `oid` int(11) UNSIGNED NOT NULL COMMENT '订单id',
  `cart_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '购物车id',
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品ID',
  `cart_info` text NOT NULL COMMENT '购买东西的详细信息',
  `unique` char(32) NOT NULL COMMENT '唯一id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单购物详情表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_order_status`
--

CREATE TABLE `osx_store_order_status` (
  `oid` int(10) UNSIGNED NOT NULL COMMENT '订单id',
  `change_type` varchar(32) NOT NULL COMMENT '操作类型',
  `change_message` varchar(256) NOT NULL COMMENT '操作备注',
  `change_time` int(10) UNSIGNED NOT NULL COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单操作记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_pink`
--

CREATE TABLE `osx_store_pink` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `order_id` varchar(32) NOT NULL COMMENT '订单id 生成',
  `order_id_key` int(10) UNSIGNED NOT NULL COMMENT '订单id  数据库',
  `total_num` int(10) UNSIGNED NOT NULL COMMENT '购买商品个数',
  `total_price` decimal(10,2) UNSIGNED NOT NULL COMMENT '购买总金额',
  `cid` int(10) UNSIGNED NOT NULL COMMENT '拼团产品id',
  `pid` int(10) UNSIGNED NOT NULL COMMENT '产品id',
  `people` int(10) UNSIGNED NOT NULL COMMENT '拼图总人数',
  `price` decimal(10,2) UNSIGNED NOT NULL COMMENT '拼团产品单价',
  `add_time` varchar(24) NOT NULL COMMENT '开始时间',
  `stop_time` varchar(24) NOT NULL,
  `k_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '团长id 0为团长',
  `is_tpl` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否发送模板消息0未发送1已发送',
  `is_refund` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否退款 0未退款 1已退款',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态1进行中2已完成3未完成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼团表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product`
--

CREATE TABLE `osx_store_product` (
  `id` mediumint(11) NOT NULL COMMENT '商品id',
  `mer_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商户Id(0为总后台管理员创建,不为0的时候是商户后台创建)',
  `image` text NOT NULL COMMENT '商品图片',
  `slider_image` text NOT NULL COMMENT '轮播图',
  `store_name` varchar(128) NOT NULL COMMENT '商品名称',
  `store_info` varchar(256) NOT NULL COMMENT '商品简介',
  `keyword` varchar(256) NOT NULL COMMENT '关键字',
  `cate_id` varchar(64) NOT NULL COMMENT '分类id',
  `price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `vip_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '会员价格',
  `ot_price` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `postage` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `unit_name` varchar(32) NOT NULL COMMENT '单位名',
  `sort` smallint(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `sort_style` tinyint(1) NOT NULL DEFAULT '1' COMMENT '期刊显示顺序，1为顺序，2为倒序',
  `sales` mediumint(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `stock` mediumint(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '库存',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（0：未上架，1：上架）',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `is_benefit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否优惠',
  `is_best` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精品',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新品',
  `description` text NOT NULL COMMENT '产品描述',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '添加时间',
  `is_postage` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否包邮',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除',
  `mer_use` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商户是否代理 0不可代理1可代理',
  `give_integral` decimal(8,2) UNSIGNED NOT NULL COMMENT '获得积分',
  `cost` decimal(8,2) UNSIGNED NOT NULL COMMENT '成本价',
  `is_seckill` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '秒杀状态 0 未开启 1已开启',
  `is_bargain` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '砍价状态 0未开启 1开启',
  `ficti` mediumint(11) DEFAULT '100' COMMENT '虚拟销量',
  `browse` int(11) DEFAULT '0' COMMENT '浏览量',
  `ficti_browse` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟浏览量',
  `code_path` varchar(64) DEFAULT '' COMMENT '产品二维码地址(用户小程序海报)',
  `is_type` tinyint(1) DEFAULT '0' COMMENT '类型：0代表商品、1代表知识专栏',
  `is_column` tinyint(1) DEFAULT '0' COMMENT '专栏：0代表不是专栏、1代表是专栏',
  `recommend_sell` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否推荐分销',
  `buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '限购数',
  `platform_get` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '平台抽取',
  `strip_num` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '剥比',
  `services` varchar(64) NOT NULL DEFAULT '' COMMENT '商品服务保障ID集合'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_store_product`
--

INSERT INTO `osx_store_product` (`id`, `mer_id`, `image`, `slider_image`, `store_name`, `store_info`, `keyword`, `cate_id`, `price`, `vip_price`, `ot_price`, `postage`, `unit_name`, `sort`, `sort_style`, `sales`, `stock`, `is_show`, `is_hot`, `is_benefit`, `is_best`, `is_new`, `description`, `add_time`, `is_postage`, `is_del`, `mer_use`, `give_integral`, `cost`, `is_seckill`, `is_bargain`, `ficti`, `browse`, `ficti_browse`, `code_path`, `is_type`, `is_column`, `recommend_sell`, `buy_num`, `platform_get`, `strip_num`, `services`) VALUES
(48, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '[\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf96a4b44e8.jpg\",\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf96a443989.jpg\",\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\"]', '马克珍妮儿童装女童套装 天鹅绒婴儿宝宝秋装运动卫衣套装', '舒适天鹅绒面料，时尚烫金图案', '童装', '6', '299.00', '0.00', '199.00', '5.00', '件', 1, 1, 0, 999, 1, 1, 1, 0, 1, '', 1572844050, 1, 0, 0, '0.00', '99.00', 0, NULL, 100, 0, 0, '', 0, 0, 0, 0, '0.00', '0.00', '');

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_attr`
--

CREATE TABLE `osx_store_product_attr` (
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品ID',
  `attr_name` varchar(32) NOT NULL COMMENT '属性名',
  `attr_values` varchar(256) NOT NULL COMMENT '属性值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_store_product_attr`
--

INSERT INTO `osx_store_product_attr` (`product_id`, `attr_name`, `attr_values`) VALUES
(48, '颜色', '粉色,绿色,浅灰'),
(48, '尺码', 'S,M,L');

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_attr_result`
--

CREATE TABLE `osx_store_product_attr_result` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `result` text NOT NULL COMMENT '商品属性参数',
  `change_time` int(10) UNSIGNED NOT NULL COMMENT '上次修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性详情表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_store_product_attr_result`
--

INSERT INTO `osx_store_product_attr_result` (`product_id`, `result`, `change_time`) VALUES
(48, '{\"attr\":[{\"value\":\"\\u989c\\u8272\",\"detailValue\":\"\",\"attrHidden\":true,\"detail\":[\"\\u7c89\\u8272\",\"\\u7eff\\u8272\",\"\\u6d45\\u7070\"]},{\"value\":\"\\u5c3a\\u7801\",\"detailValue\":\"\",\"attrHidden\":true,\"detail\":[\"S\",\"M\",\"L\"]}],\"value\":[{\"detail\":{\"\\u989c\\u8272\":\"\\u7c89\\u8272\",\"\\u5c3a\\u7801\":\"S\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u7c89\\u8272\",\"\\u5c3a\\u7801\":\"M\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u7c89\\u8272\",\"\\u5c3a\\u7801\":\"L\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u7eff\\u8272\",\"\\u5c3a\\u7801\":\"S\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u7eff\\u8272\",\"\\u5c3a\\u7801\":\"M\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u7eff\\u8272\",\"\\u5c3a\\u7801\":\"L\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u6d45\\u7070\",\"\\u5c3a\\u7801\":\"S\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u6d45\\u7070\",\"\\u5c3a\\u7801\":\"M\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false},{\"detail\":{\"\\u989c\\u8272\":\"\\u6d45\\u7070\",\"\\u5c3a\\u7801\":\"L\"},\"cost\":\"99.00\",\"price\":\"299.00\",\"sales\":999,\"pic\":\"https:\\/\\/newosx.demo.opensns.cn\\/public\\/uploads\\/attach\\/2019\\/11\\/04\\/5dbf967a17f98.jpg\",\"check\":false}]}', 1572844157);

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_attr_value`
--

CREATE TABLE `osx_store_product_attr_value` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `suk` varchar(128) NOT NULL COMMENT '商品属性索引值 (attr_value|attr_value[|....])',
  `stock` int(10) UNSIGNED NOT NULL COMMENT '属性对应的库存',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `price` decimal(8,2) UNSIGNED NOT NULL COMMENT '属性金额',
  `image` text COMMENT '图片',
  `unique` char(8) NOT NULL DEFAULT '' COMMENT '唯一值',
  `cost` decimal(8,2) UNSIGNED NOT NULL COMMENT '成本价',
  `strip_num` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '剥比'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性值表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_store_product_attr_value`
--

INSERT INTO `osx_store_product_attr_value` (`product_id`, `suk`, `stock`, `sales`, `price`, `image`, `unique`, `cost`, `strip_num`) VALUES
(48, '浅灰,L', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '13fa8c33', '99.00', '0.00'),
(48, '绿色,L', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '157488af', '99.00', '0.00'),
(48, '浅灰,M', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '3dba2c7d', '99.00', '0.00'),
(48, '粉色,S', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '7ad86837', '99.00', '0.00'),
(48, '粉色,M', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '92eb6ec7', '99.00', '0.00'),
(48, '绿色,M', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', 'a1b5f3b4', '99.00', '0.00'),
(48, '浅灰,S', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', 'a70f8280', '99.00', '0.00'),
(48, '绿色,S', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', 'bb6a63cd', '99.00', '0.00'),
(48, '粉色,L', 999, 0, '299.00', 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', 'c1946a69', '99.00', '0.00');

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_relation`
--

CREATE TABLE `osx_store_product_relation` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `type` varchar(32) NOT NULL COMMENT '类型(收藏(collect）、点赞(like))',
  `category` varchar(32) NOT NULL COMMENT '某种类型的商品(普通商品、秒杀商品)',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `is_zg` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品点赞和收藏表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_reply`
--

CREATE TABLE `osx_store_product_reply` (
  `id` int(11) NOT NULL COMMENT '评论ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `oid` int(11) NOT NULL COMMENT '订单ID',
  `unique` char(32) NOT NULL COMMENT '唯一id',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `reply_type` varchar(32) NOT NULL DEFAULT 'product' COMMENT '某种商品类型(普通商品、秒杀商品）',
  `product_score` tinyint(1) NOT NULL COMMENT '商品分数',
  `service_score` tinyint(1) NOT NULL COMMENT '服务分数',
  `comment` varchar(512) NOT NULL COMMENT '评论内容',
  `pics` text NOT NULL COMMENT '评论图片',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  `merchant_reply_content` varchar(300) DEFAULT NULL COMMENT '管理员回复内容',
  `merchant_reply_time` int(11) DEFAULT NULL COMMENT '管理员回复时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未删除1已删除',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未回复1已回复',
  `del_time` int(11) NOT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_services`
--

CREATE TABLE `osx_store_product_services` (
  `id` int(11) NOT NULL COMMENT '服务id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '服务名称',
  `explain` varchar(250) NOT NULL DEFAULT '' COMMENT '服务说明',
  `icon` varchar(250) NOT NULL DEFAULT '' COMMENT '图标',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态，0=禁用，1=开启',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,0=未删除,1=删除',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品服务保障表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_product_text_reply`
--

CREATE TABLE `osx_store_product_text_reply` (
  `id` int(11) NOT NULL COMMENT '评论ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '期ID',
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `comment` varchar(512) NOT NULL COMMENT '评论内容',
  `pics` text NOT NULL COMMENT '评论图片',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未回复1已回复',
  `is_zan` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数量',
  `score` tinyint(2) NOT NULL DEFAULT '5' COMMENT '星级评论',
  `merchant_reply_content` varchar(300) DEFAULT '' COMMENT '管理员回复内容',
  `merchant_reply_time` int(11) DEFAULT NULL COMMENT '管理员回复时间',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0未删除1已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_seckill`
--

CREATE TABLE `osx_store_seckill` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '商品秒杀产品表id',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品id',
  `image` varchar(255) NOT NULL COMMENT '推荐图',
  `images` varchar(1000) NOT NULL COMMENT '轮播图',
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `info` varchar(255) NOT NULL COMMENT '简介',
  `price` decimal(10,2) UNSIGNED NOT NULL COMMENT '价格',
  `cost` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '成本',
  `ot_price` decimal(10,2) UNSIGNED NOT NULL COMMENT '原价',
  `give_integral` decimal(10,2) UNSIGNED NOT NULL COMMENT '返多少积分',
  `sort` int(10) UNSIGNED NOT NULL COMMENT '排序',
  `stock` int(10) UNSIGNED NOT NULL COMMENT '库存',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `unit_name` varchar(16) NOT NULL COMMENT '单位名',
  `postage` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '邮费',
  `description` text COMMENT '内容',
  `start_time` varchar(128) NOT NULL COMMENT '开始时间',
  `stop_time` varchar(128) NOT NULL COMMENT '结束时间',
  `add_time` varchar(128) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '产品状态',
  `is_postage` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否包邮',
  `is_hot` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '热门推荐',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除 0未删除1已删除',
  `num` int(11) UNSIGNED NOT NULL COMMENT '最多秒杀几个',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '显示',
  `time_type` text NOT NULL COMMENT '时间段'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品秒杀产品表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_seckill_attr`
--

CREATE TABLE `osx_store_seckill_attr` (
  `product_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品ID',
  `attr_name` varchar(32) NOT NULL COMMENT '属性名',
  `attr_values` varchar(256) NOT NULL COMMENT '属性值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀商品属性表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_seckill_attr_result`
--

CREATE TABLE `osx_store_seckill_attr_result` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `result` text NOT NULL COMMENT '商品属性参数',
  `change_time` int(10) UNSIGNED NOT NULL COMMENT '上次修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀商品属性详情表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_seckill_attr_value`
--

CREATE TABLE `osx_store_seckill_attr_value` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT '商品ID',
  `suk` varchar(128) NOT NULL COMMENT '商品属性索引值 (attr_value|attr_value[|....])',
  `stock` int(10) UNSIGNED NOT NULL COMMENT '属性对应的库存',
  `sales` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '销量',
  `price` decimal(8,2) UNSIGNED NOT NULL COMMENT '属性金额',
  `image` varchar(128) DEFAULT NULL COMMENT '图片',
  `unique` char(8) NOT NULL DEFAULT '' COMMENT '唯一值',
  `cost` decimal(8,2) UNSIGNED NOT NULL COMMENT '成本价'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀商品属性值表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_service`
--

CREATE TABLE `osx_store_service` (
  `id` int(11) NOT NULL COMMENT '客服id',
  `mer_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `uid` int(11) NOT NULL COMMENT '客服uid',
  `avatar` varchar(250) NOT NULL COMMENT '客服头像',
  `nickname` varchar(50) NOT NULL COMMENT '代理名称',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0隐藏1显示',
  `notify` int(2) DEFAULT '0' COMMENT '订单通知1开启0关闭'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客服表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_service_log`
--

CREATE TABLE `osx_store_service_log` (
  `id` int(11) NOT NULL COMMENT '客服用户对话记录表ID',
  `mer_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `msn` text NOT NULL COMMENT '消息内容',
  `uid` int(11) NOT NULL COMMENT '发送人uid',
  `to_uid` int(11) NOT NULL COMMENT '接收人uid',
  `add_time` int(11) NOT NULL COMMENT '发送时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读（0：否；1：是；）',
  `remind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提醒过'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客服用户对话记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_set`
--

CREATE TABLE `osx_store_set` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '简介',
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目设置表';

--
-- 转存表中的数据 `osx_store_set`
--

INSERT INTO `osx_store_set` (`id`, `title`, `content`, `status`) VALUES
(1, '商品分类', '多种商品任你选择', 1),
(2, '精品推荐', '商家精品推荐商品', 1),
(3, '新品首发', '多个商品最新上架', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_visit`
--

CREATE TABLE `osx_store_visit` (
  `id` int(10) NOT NULL,
  `product_id` int(11) DEFAULT NULL COMMENT '产品ID',
  `product_type` varchar(32) DEFAULT NULL COMMENT '产品类型',
  `cate_id` int(11) DEFAULT NULL COMMENT '产品分类ID',
  `type` char(50) DEFAULT NULL COMMENT '产品类型',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `count` int(11) DEFAULT NULL COMMENT '访问次数',
  `content` varchar(255) DEFAULT NULL COMMENT '备注描述',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品浏览分析表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_store_zan`
--

CREATE TABLE `osx_store_zan` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论点赞表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_support`
--

CREATE TABLE `osx_support` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `model` varchar(25) NOT NULL COMMENT '模块标识',
  `row` int(11) NOT NULL COMMENT '关联记录id',
  `status` tinyint(3) NOT NULL COMMENT '状态',
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户点赞记录表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_support`
--

INSERT INTO `osx_support` (`id`, `uid`, `model`, `row`, `status`, `create_time`) VALUES
(1, 2, 'thread', 1, 0, 1572588893);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_admin`
--

CREATE TABLE `osx_system_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '后台管理员表ID',
  `account` varchar(32) NOT NULL COMMENT '后台管理员账号',
  `pwd` char(32) NOT NULL COMMENT '后台管理员密码',
  `real_name` varchar(16) NOT NULL COMMENT '后台管理员姓名',
  `roles` varchar(128) NOT NULL COMMENT '后台管理员权限(menus_id)',
  `last_ip` varchar(16) DEFAULT NULL COMMENT '后台管理员最后一次登录ip',
  `last_time` int(10) UNSIGNED DEFAULT NULL COMMENT '后台管理员最后一次登录时间',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '后台管理员添加时间',
  `login_count` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '后台管理员级别',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '后台管理员状态 1有效0无效',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `is_password` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否修改过密码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理员表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_admin`
--

INSERT INTO `osx_system_admin` (`id`, `account`, `pwd`, `real_name`, `roles`, `last_ip`, `last_time`, `add_time`, `login_count`, `level`, `status`, `is_del`, `is_password`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '1', '127.0.0.1', 1599640698, 1599640642, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_attachment`
--

CREATE TABLE `osx_system_attachment` (
  `att_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '附件名称',
  `att_dir` varchar(200) NOT NULL COMMENT '附件路径',
  `satt_dir` varchar(200) DEFAULT NULL COMMENT '压缩图片路径',
  `att_size` char(30) NOT NULL COMMENT '附件大小',
  `att_type` char(30) NOT NULL COMMENT '附件类型',
  `pid` int(10) NOT NULL COMMENT '分类ID0编辑器,1产品图片,2拼团图片,3砍价图片,4秒杀图片,5文章图片,6组合数据图',
  `time` int(11) NOT NULL COMMENT '上传时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件管理表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_attachment`
--

INSERT INTO `osx_system_attachment` (`att_id`, `name`, `att_dir`, `satt_dir`, `att_size`, `att_type`, `pid`, `time`) VALUES
(54, '5c9ccca12638a.gif', '/public/uploads/attach/2019/03/28/5c9ccca12638a.gif', '/public/uploads/attach/2019/03/28/s_5c9ccca12638a.gif', '122854', 'image/gif', 5, 1553779873),
(55, '5c9ccca151e99.gif', '/public/uploads/attach/2019/03/28/5c9ccca151e99.gif', '/public/uploads/attach/2019/03/28/s_5c9ccca151e99.gif', '105770', 'image/gif', 5, 1553779873),
(56, '5c9ccca178a67.gif', '/public/uploads/attach/2019/03/28/5c9ccca178a67.gif', '/public/uploads/attach/2019/03/28/s_5c9ccca178a67.gif', '108109', 'image/gif', 5, 1553779873),
(57, '5c9ccca1a01b6.gif', '/public/uploads/attach/2019/03/28/5c9ccca1a01b6.gif', '/public/uploads/attach/2019/03/28/s_5c9ccca1a01b6.gif', '109454', 'image/gif', 5, 1553779873),
(58, '5c9ccca1c78cd.gif', '/public/uploads/attach/2019/03/28/5c9ccca1c78cd.gif', '/public/uploads/attach/2019/03/28/s_5c9ccca1c78cd.gif', '110373', 'image/gif', 5, 1553779873),
(97, '5dbb8a8dabacb.png', '/public/uploads/attach/2019/11/01/5dbb8a8dabacb.png', '/public/uploads/attach/2019/11/01/s_5dbb8a8dabacb.png', '784335', 'image/png', 9, 1572571789),
(98, '5dbb8a8e068e6.png', '/public/uploads/attach/2019/11/01/5dbb8a8e068e6.png', '/public/uploads/attach/2019/11/01/s_5dbb8a8e068e6.png', '307665', 'image/png', 9, 1572571790),
(99, '5dbb8a8e47408.png', '/public/uploads/attach/2019/11/01/5dbb8a8e47408.png', '/public/uploads/attach/2019/11/01/s_5dbb8a8e47408.png', '594720', 'image/png', 9, 1572571790),
(100, '5dbb8aa1444f3.png', '/public/uploads/attach/2019/11/01/5dbb8aa1444f3.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa1444f3.png', '9880', 'image/png', 9, 1572571809),
(101, '5dbb8aa15c773.png', '/public/uploads/attach/2019/11/01/5dbb8aa15c773.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa15c773.png', '10793', 'image/png', 9, 1572571809),
(102, '5dbb8aa17c3ca.png', '/public/uploads/attach/2019/11/01/5dbb8aa17c3ca.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa17c3ca.png', '10793', 'image/png', 9, 1572571809),
(103, '5dbb8aa1af961.png', '/public/uploads/attach/2019/11/01/5dbb8aa1af961.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa1af961.png', '10521', 'image/png', 9, 1572571809),
(104, '5dbb8aa1e8b16.png', '/public/uploads/attach/2019/11/01/5dbb8aa1e8b16.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa1e8b16.png', '10390', 'image/png', 9, 1572571809),
(105, '5dbb8aa212fd1.png', '/public/uploads/attach/2019/11/01/5dbb8aa212fd1.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa212fd1.png', '10566', 'image/png', 9, 1572571810),
(106, '5dbb8aa233318.png', '/public/uploads/attach/2019/11/01/5dbb8aa233318.png', '/public/uploads/attach/2019/11/01/s_5dbb8aa233318.png', '12870', 'image/png', 9, 1572571810),
(107, '5dbb95f14eb37.png', '/public/uploads/attach/2019/11/01/5dbb95f14eb37.png', '/public/uploads/attach/2019/11/01/s_5dbb95f14eb37.png', '146847', 'image/png', 13, 1572574705),
(108, '5dbb95f181b7c.png', '/public/uploads/attach/2019/11/01/5dbb95f181b7c.png', '/public/uploads/attach/2019/11/01/s_5dbb95f181b7c.png', '83387', 'image/png', 13, 1572574705),
(109, '5dbb95f1a210f.png', '/public/uploads/attach/2019/11/01/5dbb95f1a210f.png', '/public/uploads/attach/2019/11/01/s_5dbb95f1a210f.png', '204896', 'image/png', 13, 1572574705),
(110, '5dbb95f1dc147.png', '/public/uploads/attach/2019/11/01/5dbb95f1dc147.png', '/public/uploads/attach/2019/11/01/s_5dbb95f1dc147.png', '216933', 'image/png', 13, 1572574706),
(111, '5dbb95f221466.png', '/public/uploads/attach/2019/11/01/5dbb95f221466.png', '/public/uploads/attach/2019/11/01/s_5dbb95f221466.png', '140255', 'image/png', 13, 1572574706),
(112, '5dbb95f23974b.png', '/public/uploads/attach/2019/11/01/5dbb95f23974b.png', '/public/uploads/attach/2019/11/01/s_5dbb95f23974b.png', '108768', 'image/png', 13, 1572574706),
(113, '5dbb95f263a85.png', '/public/uploads/attach/2019/11/01/5dbb95f263a85.png', '/public/uploads/attach/2019/11/01/s_5dbb95f263a85.png', '127804', 'image/png', 13, 1572574706),
(114, '5dbb95f28fedd.png', '/public/uploads/attach/2019/11/01/5dbb95f28fedd.png', '/public/uploads/attach/2019/11/01/s_5dbb95f28fedd.png', '61867', 'image/png', 13, 1572574706),
(115, '5dbb96073277f.png', '/public/uploads/attach/2019/11/01/5dbb96073277f.png', '/public/uploads/attach/2019/11/01/s_5dbb96073277f.png', '40360', 'image/png', 13, 1572574727),
(116, '5dbb96075406e.png', '/public/uploads/attach/2019/11/01/5dbb96075406e.png', '/public/uploads/attach/2019/11/01/s_5dbb96075406e.png', '45311', 'image/png', 13, 1572574727),
(117, '5dbb960768617.png', '/public/uploads/attach/2019/11/01/5dbb960768617.png', '/public/uploads/attach/2019/11/01/s_5dbb960768617.png', '51833', 'image/png', 13, 1572574727),
(118, '5dbb9607867ce.png', '/public/uploads/attach/2019/11/01/5dbb9607867ce.png', '/public/uploads/attach/2019/11/01/s_5dbb9607867ce.png', '143993', 'image/png', 13, 1572574727),
(119, '5dbb9607b0ab1.png', '/public/uploads/attach/2019/11/01/5dbb9607b0ab1.png', '/public/uploads/attach/2019/11/01/s_5dbb9607b0ab1.png', '82102', 'image/png', 13, 1572574727),
(120, '5dbb961b47850.png', '/public/uploads/attach/2019/11/01/5dbb961b47850.png', '/public/uploads/attach/2019/11/01/s_5dbb961b47850.png', '52044', 'image/png', 13, 1572574747),
(121, '5dbb961b5e9e8.png', '/public/uploads/attach/2019/11/01/5dbb961b5e9e8.png', '/public/uploads/attach/2019/11/01/s_5dbb961b5e9e8.png', '53372', 'image/png', 13, 1572574747),
(122, '5dbb9639121cf.png', '/public/uploads/attach/2019/11/01/5dbb9639121cf.png', '/public/uploads/attach/2019/11/01/s_5dbb9639121cf.png', '6075', 'image/png', 12, 1572574777),
(123, '5dbb963920d47.png', '/public/uploads/attach/2019/11/01/5dbb963920d47.png', '/public/uploads/attach/2019/11/01/s_5dbb963920d47.png', '6476', 'image/png', 12, 1572574777),
(124, '5dbb963937837.png', '/public/uploads/attach/2019/11/01/5dbb963937837.png', '/public/uploads/attach/2019/11/01/s_5dbb963937837.png', '5236', 'image/png', 12, 1572574777),
(125, '5dbb96395c873.png', '/public/uploads/attach/2019/11/01/5dbb96395c873.png', '/public/uploads/attach/2019/11/01/s_5dbb96395c873.png', '6211', 'image/png', 12, 1572574777),
(126, '5dbb963970078.png', '/public/uploads/attach/2019/11/01/5dbb963970078.png', '/public/uploads/attach/2019/11/01/s_5dbb963970078.png', '7300', 'image/png', 12, 1572574777),
(127, '5dbb963981025.png', '/public/uploads/attach/2019/11/01/5dbb963981025.png', '/public/uploads/attach/2019/11/01/s_5dbb963981025.png', '5885', 'image/png', 12, 1572574777),
(128, '5dbb963998228.png', '/public/uploads/attach/2019/11/01/5dbb963998228.png', '/public/uploads/attach/2019/11/01/s_5dbb963998228.png', '7088', 'image/png', 12, 1572574777),
(129, '5dbb9639acb1f.png', '/public/uploads/attach/2019/11/01/5dbb9639acb1f.png', '/public/uploads/attach/2019/11/01/s_5dbb9639acb1f.png', '5521', 'image/png', 12, 1572574777),
(130, '5dbb9639c83f3.png', '/public/uploads/attach/2019/11/01/5dbb9639c83f3.png', '/public/uploads/attach/2019/11/01/s_5dbb9639c83f3.png', '7420', 'image/png', 12, 1572574777),
(131, '5dbb9639df807.png', '/public/uploads/attach/2019/11/01/5dbb9639df807.png', '/public/uploads/attach/2019/11/01/s_5dbb9639df807.png', '4973', 'image/png', 12, 1572574777),
(132, '5dbb963a017e3.png', '/public/uploads/attach/2019/11/01/5dbb963a017e3.png', '/public/uploads/attach/2019/11/01/s_5dbb963a017e3.png', '6688', 'image/png', 12, 1572574778),
(133, '5dbb963a0fd1b.png', '/public/uploads/attach/2019/11/01/5dbb963a0fd1b.png', '/public/uploads/attach/2019/11/01/s_5dbb963a0fd1b.png', '4660', 'image/png', 12, 1572574778),
(134, '5dbb963a22b83.png', '/public/uploads/attach/2019/11/01/5dbb963a22b83.png', '/public/uploads/attach/2019/11/01/s_5dbb963a22b83.png', '5598', 'image/png', 12, 1572574778),
(135, '5dbb963a4ff2c.png', '/public/uploads/attach/2019/11/01/5dbb963a4ff2c.png', '/public/uploads/attach/2019/11/01/s_5dbb963a4ff2c.png', '6388', 'image/png', 12, 1572574778),
(136, '5dbb963a706e1.png', '/public/uploads/attach/2019/11/01/5dbb963a706e1.png', '/public/uploads/attach/2019/11/01/s_5dbb963a706e1.png', '5116', 'image/png', 12, 1572574778),
(137, '5dbb963aa9ffc.png', '/public/uploads/attach/2019/11/01/5dbb963aa9ffc.png', '/public/uploads/attach/2019/11/01/s_5dbb963aa9ffc.png', '6471', 'image/png', 12, 1572574778),
(138, '5dbb9650aa968.png', '/public/uploads/attach/2019/11/01/5dbb9650aa968.png', '/public/uploads/attach/2019/11/01/s_5dbb9650aa968.png', '2900', 'image/png', 11, 1572574800),
(139, '5dbb9650ba00a.png', '/public/uploads/attach/2019/11/01/5dbb9650ba00a.png', '/public/uploads/attach/2019/11/01/s_5dbb9650ba00a.png', '3938', 'image/png', 11, 1572574800),
(140, '5dbb9650c986a.png', '/public/uploads/attach/2019/11/01/5dbb9650c986a.png', '/public/uploads/attach/2019/11/01/s_5dbb9650c986a.png', '5416', 'image/png', 11, 1572574800),
(141, '5dbb9650d8ab9.png', '/public/uploads/attach/2019/11/01/5dbb9650d8ab9.png', '/public/uploads/attach/2019/11/01/s_5dbb9650d8ab9.png', '5502', 'image/png', 11, 1572574800),
(142, '5dbb9650e7178.png', '/public/uploads/attach/2019/11/01/5dbb9650e7178.png', '/public/uploads/attach/2019/11/01/s_5dbb9650e7178.png', '5671', 'image/png', 11, 1572574800),
(143, '5dbb96510362a.png', '/public/uploads/attach/2019/11/01/5dbb96510362a.png', '/public/uploads/attach/2019/11/01/s_5dbb96510362a.png', '5236', 'image/png', 11, 1572574801),
(144, '5dbb965114b74.png', '/public/uploads/attach/2019/11/01/5dbb965114b74.png', '/public/uploads/attach/2019/11/01/s_5dbb965114b74.png', '5008', 'image/png', 11, 1572574801),
(145, '5dbb96512668b.png', '/public/uploads/attach/2019/11/01/5dbb96512668b.png', '/public/uploads/attach/2019/11/01/s_5dbb96512668b.png', '5561', 'image/png', 11, 1572574801),
(146, '5dbb965137c87.png', '/public/uploads/attach/2019/11/01/5dbb965137c87.png', '/public/uploads/attach/2019/11/01/s_5dbb965137c87.png', '5481', 'image/png', 11, 1572574801),
(147, '5dbb96514fe63.png', '/public/uploads/attach/2019/11/01/5dbb96514fe63.png', '/public/uploads/attach/2019/11/01/s_5dbb96514fe63.png', '5382', 'image/png', 11, 1572574801),
(148, '5dbb965167ea9.png', '/public/uploads/attach/2019/11/01/5dbb965167ea9.png', '/public/uploads/attach/2019/11/01/s_5dbb965167ea9.png', '6116', 'image/png', 11, 1572574801),
(149, '5dbb965177bbb.png', '/public/uploads/attach/2019/11/01/5dbb965177bbb.png', '/public/uploads/attach/2019/11/01/s_5dbb965177bbb.png', '6283', 'image/png', 11, 1572574801),
(150, '5dbb96518b2c4.png', '/public/uploads/attach/2019/11/01/5dbb96518b2c4.png', '/public/uploads/attach/2019/11/01/s_5dbb96518b2c4.png', '5534', 'image/png', 11, 1572574801),
(151, '5dbb96519bbfd.png', '/public/uploads/attach/2019/11/01/5dbb96519bbfd.png', '/public/uploads/attach/2019/11/01/s_5dbb96519bbfd.png', '6281', 'image/png', 11, 1572574801),
(152, '5dbb9651acfc7.png', '/public/uploads/attach/2019/11/01/5dbb9651acfc7.png', '/public/uploads/attach/2019/11/01/s_5dbb9651acfc7.png', '6213', 'image/png', 11, 1572574801),
(153, '5dbb9651d8073.png', '/public/uploads/attach/2019/11/01/5dbb9651d8073.png', '/public/uploads/attach/2019/11/01/s_5dbb9651d8073.png', '5710', 'image/png', 11, 1572574801),
(154, '5dbb96523cbae.png', '/public/uploads/attach/2019/11/01/5dbb96523cbae.png', '/public/uploads/attach/2019/11/01/s_5dbb96523cbae.png', '5729', 'image/png', 11, 1572574802),
(155, '5dbb9652721db.png', '/public/uploads/attach/2019/11/01/5dbb9652721db.png', '/public/uploads/attach/2019/11/01/s_5dbb9652721db.png', '5904', 'image/png', 11, 1572574802),
(156, '5dbb9652914e7.png', '/public/uploads/attach/2019/11/01/5dbb9652914e7.png', '/public/uploads/attach/2019/11/01/s_5dbb9652914e7.png', '6093', 'image/png', 11, 1572574802),
(157, '5dbb9652ddf31.png', '/public/uploads/attach/2019/11/01/5dbb9652ddf31.png', '/public/uploads/attach/2019/11/01/s_5dbb9652ddf31.png', '6039', 'image/png', 11, 1572574802),
(158, '5dbb9734e167d.png', '/public/uploads/attach/2019/11/01/5dbb9734e167d.png', '/public/uploads/attach/2019/11/01/s_5dbb9734e167d.png', '11635', 'image/png', 14, 1572575028),
(160, '5dbb97350ce5b.png', '/public/uploads/attach/2019/11/01/5dbb97350ce5b.png', '/public/uploads/attach/2019/11/01/s_5dbb97350ce5b.png', '10240', 'image/png', 14, 1572575029),
(161, '5dbb97351b229.png', '/public/uploads/attach/2019/11/01/5dbb97351b229.png', '/public/uploads/attach/2019/11/01/s_5dbb97351b229.png', '11368', 'image/png', 14, 1572575029),
(162, '5dbb973529514.png', '/public/uploads/attach/2019/11/01/5dbb973529514.png', '/public/uploads/attach/2019/11/01/s_5dbb973529514.png', '10421', 'image/png', 14, 1572575029),
(163, '5dbb973537793.png', '/public/uploads/attach/2019/11/01/5dbb973537793.png', '/public/uploads/attach/2019/11/01/s_5dbb973537793.png', '9713', 'image/png', 14, 1572575029),
(164, '5dbb973545adc.png', '/public/uploads/attach/2019/11/01/5dbb973545adc.png', '/public/uploads/attach/2019/11/01/s_5dbb973545adc.png', '13173', 'image/png', 14, 1572575029),
(165, '5dbb973556685.png', '/public/uploads/attach/2019/11/01/5dbb973556685.png', '/public/uploads/attach/2019/11/01/s_5dbb973556685.png', '8086', 'image/png', 14, 1572575029),
(166, '5dbb9735648e4.png', '/public/uploads/attach/2019/11/01/5dbb9735648e4.png', '/public/uploads/attach/2019/11/01/s_5dbb9735648e4.png', '15638', 'image/png', 14, 1572575029),
(167, '5dbb9735729e2.png', '/public/uploads/attach/2019/11/01/5dbb9735729e2.png', '/public/uploads/attach/2019/11/01/s_5dbb9735729e2.png', '12906', 'image/png', 14, 1572575029),
(168, '5dbb973580c3c.png', '/public/uploads/attach/2019/11/01/5dbb973580c3c.png', '/public/uploads/attach/2019/11/01/s_5dbb973580c3c.png', '10597', 'image/png', 14, 1572575029),
(169, '5dbb98a3bf432.png', '/public/uploads/attach/2019/11/01/5dbb98a3bf432.png', '/public/uploads/attach/2019/11/01/s_5dbb98a3bf432.png', '9979', 'image/png', 10, 1572575395),
(170, '5dbb98a3d431c.png', '/public/uploads/attach/2019/11/01/5dbb98a3d431c.png', '/public/uploads/attach/2019/11/01/s_5dbb98a3d431c.png', '10360', 'image/png', 10, 1572575395),
(171, '5dbb98a3e5725.png', '/public/uploads/attach/2019/11/01/5dbb98a3e5725.png', '/public/uploads/attach/2019/11/01/s_5dbb98a3e5725.png', '11824', 'image/png', 10, 1572575395),
(172, '5dbb98a40205d.png', '/public/uploads/attach/2019/11/01/5dbb98a40205d.png', '/public/uploads/attach/2019/11/01/s_5dbb98a40205d.png', '10309', 'image/png', 10, 1572575396),
(173, '5dbb9c89ce28e.png', '/public/uploads/editor/20191101/5dbb9c89ce28e.png', '/public/uploads/editor/20191101/s_5dbb9c89ce28e.png', '17307', 'image/png', 16, 1572576393),
(175, '5dbb9d57affec.jpg', '/public/uploads/attach/2019/11/01/5dbb9d57affec.jpg', '/public/uploads/attach/2019/11/01/s_5dbb9d57affec.jpg', '76215', 'image/jpeg', 16, 1572576599),
(176, '5dbba6f25e1d0.png', '/public/uploads/attach/2019/11/01/5dbba6f25e1d0.png', '/public/uploads/attach/2019/11/01/s_5dbba6f25e1d0.png', '4993', 'image/png', 12, 1572579058),
(177, '5dbba73e2908a.png', '/public/uploads/attach/2019/11/01/5dbba73e2908a.png', '/public/uploads/attach/2019/11/01/s_5dbba73e2908a.png', '5885', 'image/png', 12, 1572579134),
(178, '5dbbca845f41f.png', '/public/uploads/attach/2019/11/01/5dbbca845f41f.png', '/public/uploads/attach/2019/11/01/s_5dbbca845f41f.png', '3648', 'image/png', 11, 1572588164),
(179, '5dbbca846dee7.png', '/public/uploads/attach/2019/11/01/5dbbca846dee7.png', '/public/uploads/attach/2019/11/01/s_5dbbca846dee7.png', '3066', 'image/png', 11, 1572588164),
(180, '5dbbca847c60b.png', '/public/uploads/attach/2019/11/01/5dbbca847c60b.png', '/public/uploads/attach/2019/11/01/s_5dbbca847c60b.png', '3907', 'image/png', 11, 1572588164),
(181, '5dbbdab6b800e.png', '/public/uploads/attach/2019/11/01/5dbbdab6b800e.png', '/public/uploads/attach/2019/11/01/s_5dbbdab6b800e.png', '5095', 'image/png', 11, 1572592310),
(182, '5dbbed81095bf.png', '/public/uploads/attach/2019/11/01/5dbbed81095bf.png', '/public/uploads/attach/2019/11/01/s_5dbbed81095bf.png', '6114', 'image/png', 10, 1572597121),
(183, '5dbbed8119a82.png', '/public/uploads/attach/2019/11/01/5dbbed8119a82.png', '/public/uploads/attach/2019/11/01/s_5dbbed8119a82.png', '6674', 'image/png', 10, 1572597121),
(184, '5dbbed812887c.png', '/public/uploads/attach/2019/11/01/5dbbed812887c.png', '/public/uploads/attach/2019/11/01/s_5dbbed812887c.png', '6924', 'image/png', 10, 1572597121),
(185, '5dbbed8136cb2.png', '/public/uploads/attach/2019/11/01/5dbbed8136cb2.png', '/public/uploads/attach/2019/11/01/s_5dbbed8136cb2.png', '5969', 'image/png', 10, 1572597121),
(186, '5dbbed8145184.png', '/public/uploads/attach/2019/11/01/5dbbed8145184.png', '/public/uploads/attach/2019/11/01/s_5dbbed8145184.png', '6965', 'image/png', 10, 1572597121),
(187, '5dbbed81536bd.png', '/public/uploads/attach/2019/11/01/5dbbed81536bd.png', '/public/uploads/attach/2019/11/01/s_5dbbed81536bd.png', '7517', 'image/png', 10, 1572597121),
(188, '5dbbed81622b4.png', '/public/uploads/attach/2019/11/01/5dbbed81622b4.png', '/public/uploads/attach/2019/11/01/s_5dbbed81622b4.png', '5710', 'image/png', 10, 1572597121),
(189, '5dbbed817168c.png', '/public/uploads/attach/2019/11/01/5dbbed817168c.png', '/public/uploads/attach/2019/11/01/s_5dbbed817168c.png', '6590', 'image/png', 10, 1572597121),
(190, '5dbf967a17f98.jpg', '/public/uploads/attach/2019/11/04/5dbf967a17f98.jpg', '/public/uploads/attach/2019/11/04/s_5dbf967a17f98.jpg', '257433', 'image/jpeg', 16, 1572836986),
(191, '5dbf96a443989.jpg', '/public/uploads/attach/2019/11/04/5dbf96a443989.jpg', '/public/uploads/attach/2019/11/04/s_5dbf96a443989.jpg', '232033', 'image/jpeg', 16, 1572837028),
(192, '5dbf96a484c56.jpg', '/public/uploads/attach/2019/11/04/5dbf96a484c56.jpg', '/public/uploads/attach/2019/11/04/s_5dbf96a484c56.jpg', '275310', 'image/jpeg', 16, 1572837028),
(193, '5dbf96a4b44e8.jpg', '/public/uploads/attach/2019/11/04/5dbf96a4b44e8.jpg', '/public/uploads/attach/2019/11/04/s_5dbf96a4b44e8.jpg', '258906', 'image/jpeg', 16, 1572837028);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_attachment_category`
--

CREATE TABLE `osx_system_attachment_category` (
  `id` int(11) NOT NULL,
  `pid` int(11) DEFAULT '0' COMMENT '父级ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `enname` varchar(50) NOT NULL COMMENT '分类目录'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件分类表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_attachment_category`
--

INSERT INTO `osx_system_attachment_category` (`id`, `pid`, `name`, `enname`) VALUES
(5, 0, '订单详情', ''),
(9, 0, '版块管理', ''),
(10, 0, '导航素材', ''),
(11, 0, '等级素材', ''),
(12, 0, '任务素材', ''),
(13, 0, '广告素材', ''),
(14, 0, '商品分类', ''),
(16, 0, '其他', '');

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_config`
--

CREATE TABLE `osx_system_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置id',
  `menu_name` varchar(255) NOT NULL COMMENT '字段名称',
  `type` varchar(255) NOT NULL COMMENT '类型(文本框,单选按钮...)',
  `config_tab_id` int(10) UNSIGNED NOT NULL COMMENT '配置分类id',
  `parameter` varchar(255) DEFAULT NULL COMMENT '规则 单选框和多选框',
  `upload_type` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '上传文件格式1单图2多图3文件',
  `required` varchar(255) DEFAULT NULL COMMENT '规则',
  `width` int(10) UNSIGNED DEFAULT NULL COMMENT '多行文本框的宽度',
  `high` int(10) UNSIGNED DEFAULT NULL COMMENT '多行文框的高度',
  `value` varchar(5000) DEFAULT NULL COMMENT '默认值',
  `info` varchar(255) NOT NULL COMMENT '配置名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '配置简介',
  `sort` int(10) UNSIGNED NOT NULL COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL COMMENT '是否隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_config`
--

INSERT INTO `osx_system_config` (`id`, `menu_name`, `type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `sort`, `status`) VALUES
(1, 'site_name', 'text', 1, '', 0, 'required:true', 100, 0, '\"\\u60f3\\u5929\\u5546\\u57ce\"', '网站名称', '网站名称', 0, 1),
(2, 'site_url', 'text', 1, '', 0, 'required:true,url:true', 100, 0, '\"http:\\/\\/os.opensns.cn\"', '网站地址', '网站地址', 0, 1),
(3, 'site_logo', 'upload', 1, '', 1, '', 0, 0, '\"\"', '后台LOGO', '左上角logo,建议尺寸[170*50]', 0, 1),
(4, 'site_phone', 'text', 1, '', 0, '', 100, 0, '\"\"', '联系电话', '联系电话', 0, 1),
(5, 'seo_title', 'text', 23, '', 0, 'required:true', 100, 0, '\"\\u60f3\\u5929\\u8f6f\\u4ef6\"', 'SEO标题', 'SEO标题', 0, 1),
(6, 'site_email', 'text', 1, '', 0, 'email:true', 100, 0, '\"\"', '联系邮箱', '联系邮箱', 0, 1),
(7, 'site_qq', 'text', 1, '', 0, 'qq:true', 100, 0, '\"\"', '联系QQ', '联系QQ', 0, 1),
(8, 'site_close', 'radio', 1, '0=>开启\n1=>PC端关闭\n2=>WAP端关闭(含微信)\n3=>全部关闭', 0, '', 0, 0, '\"0\"', '网站关闭', '网站后台、商家中心不受影响。关闭网站也可访问', 0, 1),
(9, 'close_system', 'radio', 1, '0=>开启\n1=>关闭', 0, '', 0, 0, '\"0\"', '关闭后台', '关闭后台', 0, 2),
(10, 'wechat_name', 'text', 2, '', 0, 'required:true', 100, 0, '', '公众号名称', '公众号的名称', 0, 1),
(11, 'wechat_id', 'text', 2, '', 0, '', 100, 0, '', '微信号', '微信号', 0, 1),
(12, 'wechat_sourceid', 'text', 2, '', 0, 'required:true', 100, 0, '', '公众号原始id', '公众号原始id', 0, 1),
(13, 'wechat_appid', 'text', 2, '', 0, 'required:true', 100, 0, '', 'AppID', 'AppID', 0, 1),
(14, 'wechat_appsecret', 'text', 2, '', 0, 'required:true', 100, 0, '', 'AppSecret', 'AppSecret', 0, 1),
(15, 'wechat_token', 'text', 2, '', 0, 'required:true', 100, 0, '\"OpenSNSX\"', '微信验证TOKEN', '微信验证TOKEN', 0, 1),
(16, 'wechat_encode', 'radio', 2, '0=>明文模式\n1=>兼容模式\n2=>安全模式', 0, '', 0, 0, '\"0\"', '消息加解密方式', '如需使用安全模式请在管理中心修改，仅限服务号和认证订阅号', 0, 1),
(17, 'wechat_encodingaeskey', 'text', 2, '', 0, 'required:true', 100, 0, '\"OkF97Oo0MB7ubjjOOlvuXHKTHvbwaHZZpoACRFemO77\"', 'EncodingAESKey', '公众号消息加解密Key,在使用安全模式情况下要填写该值，请先在管理中心修改，然后填写该值，仅限服务号和认证订阅号', 0, 1),
(19, 'wechat_qrcode', 'upload', 2, '', 1, '', 0, 0, '\"\\/public\\/uploads\\/config\\/image\\/5c3d8f3405c48.jpg\"', '公众号二维码', '您的公众号二维码', 0, 1),
(20, 'wechat_type', 'radio', 2, '0=>服务号\n1=>订阅号', 0, '', 0, 0, '\"0\"', '公众号类型', '公众号的类型', 0, 1),
(23, 'pay_weixin_appid', 'text', 4, '', 0, '', 100, 0, '', 'Appid', '微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看。', 0, 1),
(24, 'pay_weixin_appsecret', 'text', 4, '', 0, '', 100, 0, '', 'Appsecret', 'JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看。', 0, 1),
(25, 'pay_weixin_mchid', 'text', 4, '', 0, '', 100, 0, '', 'Mchid', '受理商ID，身份标识', 0, 1),
(26, 'pay_weixin_client_cert', 'upload', 4, '', 3, '', 0, 0, '', '微信支付证书', '微信支付证书，在微信商家平台中可以下载！文件名一般为apiclient_cert.pem', 0, 1),
(27, 'pay_weixin_client_key', 'upload', 4, '', 3, '', 0, 0, '', '微信支付证书密钥', '微信支付证书密钥，在微信商家平台中可以下载！文件名一般为apiclient_key.pem', 0, 1),
(28, 'pay_weixin_key', 'text', 4, '', 0, '', 100, 0, '', 'Key', '商户支付密钥Key。审核通过后，在微信发送的邮件中查看。', 0, 1),
(29, 'pay_weixin_open', 'radio', 4, '1=>开启\n0=>关闭', 0, '', 0, 0, '\"1\"', '开启', '是否启用微信支付', 0, 1),
(31, 'store_postage', 'text', 31, NULL, NULL, 'number:true,min:0', 100, 0, '\"0\"', '邮费基础价', '商品邮费基础价格,最终金额为(基础价 + 商品1邮费 + 商品2邮费,积分商城配置无效)', 0, 1),
(32, 'store_free_postage', 'text', 5, '', 0, 'number:true,min:-1', 100, 0, '\"100\"', '满额包邮', '商城商品满多少金额即可包邮', 0, 1),
(33, 'offline_postage', 'radio', 31, '0=>不包邮\n1=>包邮', 0, '', 0, 0, '\"1\"', '线下支付是否包邮', '用户选择线下支付时是否包邮', 0, 1),
(34, 'integral_ratio', 'text', 11, '', 0, 'number:true', 100, 0, '\"0.01\"', '积分抵用比例', '积分抵用比例(1积分抵多少金额)', 0, 1),
(35, 'site_service_phone', 'text', 1, NULL, NULL, NULL, 100, 0, '\"400-0573080\"', '客服电话', '客服联系电话', 0, 1),
(44, 'store_user_min_recharge', 'text', 5, '', 0, 'required:true,number:true,min:0', 100, 0, '\"0.01\"', '用户最低充值金额', '用户单次最低充值金额', 0, 0),
(45, 'site_store_admin_uids', 'text', 5, '', 0, '', 100, 0, '\"4\"', '管理员用户ID', '管理员用户ID,用于接收商城订单提醒，到微信用户中查找编号，多个英文‘,’隔开', 0, 0),
(46, 'system_express_app_code', 'text', 10, '', 0, '', 100, 0, '\"e435be4a9bea44fa8a4862f8d0204da6\"', '快递查询密钥', '阿里云快递查询接口密钥购买地址：https://market.aliyun.com/products/56928004/cmapi021863.html', 0, 1),
(47, 'main_business', 'text', 2, '', 0, 'required:true', 100, 0, '\" IT\\u79d1\\u6280 \\u4e92\\u8054\\u7f51|\\u7535\\u5b50\\u5546\\u52a1\"', '微信模板消息_主营行业', '微信公众号模板消息中选择开通的主营行业', 0, 0),
(48, 'vice_business', 'text', 2, '', 0, 'required:true', 100, 0, '\"IT\\u79d1\\u6280 IT\\u8f6f\\u4ef6\\u4e0e\\u670d\\u52a1 \"', '微信模板消息_副营行业', '微信公众号模板消息中选择开通的副营行业', 0, 0),
(49, 'store_brokerage_ratio', 'text', 9, '', 0, 'required:true,min:0,max:100,number:true', 100, 0, '\"30\"', '一级返佣比例', '订单交易成功后给上级返佣的比例0 - 100,例:5 = 反订单金额的5%', 5, 1),
(50, 'wechat_first_sub_give_coupon', 'text', 12, '', 0, 'requred:true,digits:true,min:0', 100, 0, '\"1\"', '首次关注赠送优惠券ID', '首次关注赠送优惠券ID,0为不赠送', 0, 1),
(51, 'store_give_con_min_price', 'text', 12, '', 0, 'requred:true,digits:true,min:0', 100, 0, '\"0.01\"', '消费满多少赠送优惠券', '消费满多少赠送优惠券,0为不赠送', 0, 1),
(52, 'store_order_give_coupon', 'text', 12, '', 0, 'requred:true,digits:true,min:0', 100, 0, '\"\"', '消费赠送优惠劵ID', '消费赠送优惠劵ID,0为不赠送', 0, 1),
(53, 'user_extract_min_price', 'text', 9, '', 0, 'required:true,number:true,min:0', 100, 0, '\"1\"', '提现最低金额', '用户提现最低金额', 0, 1),
(54, 'sx_sign_min_int', 'text', 11, '', 0, 'required:true,number:true,min:0', 100, 0, '\"1\"', '签到奖励最低积分', '签到奖励最低积分', 0, 1),
(55, 'sx_sign_max_int', 'text', 11, '', 0, 'required:true,number:true,min:0', 100, 0, '\"5\"', '签到奖励最高积分', '签到奖励最高积分', 0, 1),
(57, 'about_us', 'upload', 1, '', 1, '', 0, 0, '\"\\/public\\/uploads\\/config\\/image\\/5c3d964265e9f.png\"', '关于我们', '系统的标识', 0, 1),
(58, 'replenishment_num', 'text', 5, '', 0, 'required:true,number:true,min:0', 100, 0, '\"20\"', '待补货数量', '产品待补货数量低于多少时，提示补货', 0, 1),
(59, 'routine_appId', 'text', 7, '', 0, '', 100, 0, '', 'appId', '小程序appID', 0, 1),
(60, 'routine_appsecret', 'text', 7, '', 0, '', 100, 0, '', 'AppSecret', '小程序AppSecret', 0, 1),
(61, 'api', 'text', 2, '', 0, '', 100, 0, '\"\\/wap\\/Wechat\\/serve\"', '接口地址', '微信接口例如：http://www.abc.com/wap/wechat/serve', 0, 1),
(62, 'paydir', 'textarea', 4, '', 0, '', 100, 5, '\"\\/wap\\/my\\/\\r\\n\\/wap\\/my\\/order\\/uni\\/\\r\\n\\/wap\\/store\\/confirm_order\\/cartId\\/\\r\\n\\/wap\\/store\\/combination_order\\/\"', '配置目录', '支付目录配置系统不调用提示作用', 0, 0),
(73, 'routine_logo', 'upload', 7, '', 1, '', 0, 0, '', '小程序logo', '小程序logo', 0, 0),
(74, 'routine_name', 'text', 7, '', 0, '', 100, 0, '', '小程序名称', '小程序名称', 0, 1),
(76, 'routine_style', 'text', 7, '', 0, '', 100, 0, '', '小程序风格', '小程序颜色', 0, 0),
(77, 'store_stock', 'text', 5, '', 0, '', 100, 0, '\"2\"', '警戒库存', '警戒库存提醒值', 0, 1),
(85, 'stor_reason', 'textarea', 5, '', 0, '', 100, 8, '\"\\u6536\\u8d27\\u5730\\u5740\\u586b\\u9519\\u4e86\\r\\n\\u4e0e\\u63cf\\u8ff0\\u4e0d\\u7b26\\r\\n\\u4fe1\\u606f\\u586b\\u9519\\u4e86\\uff0c\\u91cd\\u65b0\\u62cd\\r\\n\\u6536\\u5230\\u5546\\u54c1\\u635f\\u574f\\u4e86\\r\\n\\u672a\\u6309\\u9884\\u5b9a\\u65f6\\u95f4\\u53d1\\u8d27\\r\\n\\u5176\\u5b83\\u539f\\u56e0\"', '退货理由', '配置退货理由，一行一个理由', 0, 1),
(87, 'store_brokerage_two', 'text', 9, '', 0, 'required:true,min:0,max:100,number:true', 100, 0, '\"10\"', '二级返佣比例', '订单交易成功后给上级返佣的比例0 - 100,例:5 = 反订单金额的5%', 4, 1),
(88, 'store_brokerage_statu', 'radio', 9, '1=>指定分销\n2=>人人分销', 0, '', 0, 0, '\"2\"', '分销模式', '人人分销默认每个人都可以分销，制定人分销后台制定人开启分销', 10, 1),
(89, 'pay_routine_appid', 'text', 14, '', 0, 'required:true', 100, 0, '', 'Appid', '小程序Appid', 0, 1),
(90, 'pay_routine_appsecret', 'text', 14, '', 0, 'required:true', 100, 0, '', 'Appsecret', '小程序Appsecret', 0, 1),
(91, 'pay_routine_mchid', 'text', 14, '', 0, 'required:true', 100, 0, '', 'Mchid', '商户号', 0, 1),
(92, 'pay_routine_key', 'text', 14, '', 0, 'required:true', 100, 0, '', 'Key', '商户key', 0, 1),
(93, 'pay_routine_client_cert', 'upload', 14, '', 3, '', 0, 0, '', '小程序支付证书', '小程序支付证书', 0, 1),
(94, 'pay_routine_client_key', 'upload', 14, '', 3, '', 0, 0, '', '小程序支付证书密钥', '小程序支付证书密钥', 0, 1),
(98, 'wechat_avatar', 'upload', 2, '', 1, '', 0, 0, '', '公众号logo', '公众号logo', 0, 1),
(99, 'user_extract_bank', 'textarea', 9, '', 0, '', 100, 5, '\"\\u4e2d\\u56fd\\u519c\\u884c\\r\\n\\u4e2d\\u56fd\\u5efa\\u8bbe\\u94f6\\u884c\\r\\n\\u5de5\\u5546\\u94f6\\u884c\"', '提现银行卡', '提现银行卡，每个银行换行', 0, 1),
(100, 'fast_info', 'text', 16, NULL, NULL, '', 100, NULL, '\"\\u4e0a\\u767e\\u79cd\\u5546\\u54c1\\u5206\\u7c7b\\u4efb\\u60a8\\u9009\\u62e9\"', '快速选择简介', '小程序首页配置快速选择简介', 0, 1),
(101, 'bast_info', 'text', 16, NULL, NULL, '', 100, NULL, '\"\\u8001\\u674e\\u8bda\\u610f\\u63a8\\u8350\\u54c1\\u8d28\\u5546\\u54c1\"', '精品推荐简介', '小程序首页配置精品推荐简介', 0, 1),
(102, 'first_info', 'text', 16, NULL, NULL, '', 100, NULL, '\"\\u591a\\u4e2a\\u4f18\\u8d28\\u5546\\u54c1\\u6700\\u65b0\\u4e0a\\u67b6\"', '首发新品简介', '小程序首页配置首发新品简介', 0, 1),
(103, 'sales_info', 'text', 16, NULL, NULL, '', 100, NULL, '\"\\u5e93\\u5b58\\u5546\\u54c1\\u4f18\\u60e0\\u4fc3\\u9500\\u6d3b\\u52a8\"', '促销单品简介', '小程序首页配置促销单品简介', 0, 1),
(104, 'fast_number', 'text', 16, NULL, NULL, 'required:true,digits:true,min:1', 100, NULL, '\"10\"', '快速选择分类个数', '小程序首页配置快速选择分类个数', 0, 1),
(105, 'bast_number', 'text', 16, NULL, NULL, 'required:true,digits:true,min:1', 100, NULL, '\"10\"', '精品推荐个数', '小程序首页配置精品推荐个数', 0, 1),
(106, 'first_number', 'text', 16, NULL, NULL, 'required:true,digits:true,min:1', 100, NULL, '\"10\"', '首发新品个数', '小程序首页配置首发新品个数', 0, 1),
(107, 'routine_index_logo', 'upload', 7, NULL, 1, NULL, NULL, NULL, '', '小程序主页logo图标', '小程序主页logo图标', 0, 0),
(108, 'site_session', 'text', 23, NULL, NULL, '', 100, NULL, '\"2592000\"', '登录过期时间(秒)', '登录过期时间(秒)', 0, 1),
(110, 'sms_id', 'text', 20, NULL, NULL, '', 100, NULL, '', '短信平台账号', '短信平台账号', 0, 1),
(111, 'sms_password', 'text', 20, NULL, NULL, '', 100, NULL, '', '短信平台密码', '短信平台密码', 0, 1),
(112, 'sms_sign_id', 'text', 20, NULL, NULL, '', 100, NULL, '', '签名ID', '签名ID', 0, 1),
(113, 'sms_template_id', 'text', 20, NULL, NULL, '', 100, NULL, '', '注册模板ID', '注册模板ID', 0, 1),
(114, 'sms_back_password_template_id', 'text', 20, NULL, NULL, '', 100, NULL, '\"\"', '找回密码模板ID', '找回密码模板ID', 0, 1),
(115, 'sms_resend_time', 'text', 20, NULL, NULL, '', 100, NULL, '\"60\"', '重发时间', '重发时间', 0, 1),
(116, 'sms_content', 'textarea', 20, NULL, NULL, '', 100, NULL, '\"您的验证码为{$verify}验证码，账号为{$account}\"', '模板内容', '1、验证码变量：{$verify}；账号变量：{$account}\r\n\r\n2、两个变量不可删除，且顺序必须为验证码在前，账号在后', 0, 1),
(117, 'reg_switch', 'radio', 21, '0=>不开启\n1=>开启', NULL, NULL, NULL, NULL, '1', '开启微信登录', '开启微信登录', 0, 1),
(119, 'nickname_min_length', 'text', 22, NULL, NULL, '', 100, NULL, '2', '昵称最短长度', '昵称最短长度', 0, 1),
(120, 'nickname_max_length', 'text', 22, NULL, NULL, '', 100, NULL, '12', '昵称最大长度', '昵称最大长度', 0, 1),
(121, 'website_name', 'text', 23, NULL, NULL, '', 100, NULL, '\"OSX\"', '软件名称', '软件名称', 10, 1),
(122, 'website_logo', 'upload', 23, NULL, 1, NULL, NULL, NULL, '\"\\/public\\/uploads\\/config\\/image\\/5dbbc1c24e308.png\"', 'logo(280*280)', 'logo上传', 9, 1),
(123, 'website_introduce', 'textarea', 23, NULL, NULL, NULL, 100, 5, '\"OSX\\u6574\\u5408\\u8fd0\\u8425\\u7cfb\\u7edf\\u662f\\u4e00\\u6b3e\\u96c6\\u793e\\u533a\\u3001\\u7535\\u5546\\u3001\\u77e5\\u8bc6\\u4ed8\\u8d39\\u6574\\u5408\\u4e00\\u4f53\\u5316\\u7684\\u591a\\u573a\\u666f\\u3001\\u591a\\u6e20\\u9053\\u8fd0\\u8425\\u89e3\\u51b3\\u65b9\\u6848\\uff0c\\u53ef\\u4ee5\\u6709\\u6548\\u5e2e\\u52a9\\u7528\\u6237\\u5b9e\\u73b0\\u79c1\\u57df\\u6d41\\u91cf\\u9ad8\\u6548\\u8fd0\\u8425\\u53d8\\u73b0\"', '软件简介', '软件简介', 8, 1),
(124, 'service_code', 'upload', 23, NULL, 1, NULL, NULL, NULL, '\"\\/public\\/uploads\\/config\\/image\\/5dbbc2dfa445d.png\"', '客服微信二维码(550*550)', '客服微信二维码', 7, 1),
(125, 'share_title', 'text', 24, NULL, NULL, '', 100, NULL, '想天商城', '分享标题', '分享标题', 0, 1),
(126, 'share_picture', 'upload', 24, NULL, 1, NULL, NULL, NULL, NULL, '分享图片（250*250）', '分享图片', 0, 1),
(127, 'share_content', 'textarea', 24, NULL, NULL, NULL, 100, 5, '想天商城，邀您来购物！', '分享描述', '分享描述', 0, 1),
(128, 'default_avatar', 'upload', 23, NULL, 1, NULL, NULL, NULL, '\"\\/public\\/uploads\\/config\\/image\\/5dbbc25562e51.png\"', '默认头像设置（240*240）', '仅针对新注册用户，老用户无效', 0, 1),
(129, 'score_on', 'radio', 26, '0=>关闭\n1=>开启', NULL, NULL, NULL, NULL, '\"0\"', '开启状态', '开启状态', 0, 1),
(130, 'score_cash', 'text', 26, NULL, NULL, '', 100, NULL, '0', '积分抵现比例', '抵用比例（即，1积分抵多少金额）', 0, 1),
(131, 'score_limit', 'text', 26, NULL, NULL, '', 100, NULL, '0', '积分抵现上限', '抵现上限（即，每个订单可用积分抵现的金额上限）', 0, 1),
(132, 'shop_name', 'text', 5, NULL, NULL, '', 100, NULL, '想天商城', '商城名称', '商城名称', 100, 1),
(133, 'close_order_time', 'text', 5, NULL, NULL, '', 100, NULL, '0', '自动关闭未付款订单时间（单位：小时）', '订单下单未付款，N小时后自动关闭，空或0不自动关闭', 0, 1),
(134, 'receiving_goods_time', 'text', 5, NULL, NULL, '', 100, NULL, '0', '自动收货时间（单位：天）', '订单发货后，用户收货的天数，到时间以后自动确认收货，0或空不自动收货', 0, 1),
(135, 'sale_time', 'text', 5, NULL, NULL, '', 100, NULL, '30', '申请售后时间（单位：天）', '订单完成后，用户在X天内可以申请退款，空或0则不允许完成订单退款', 0, 1),
(136, 'forum_num_limit', 'text', 28, NULL, NULL, '', 100, NULL, '20', '帖子标题字数上限', '帖子标题字数上限', 0, 1),
(137, 'forum_content_limit', 'text', 28, NULL, NULL, '', 100, NULL, '2000', '帖子正文字数上限', '帖子正文字数上限', 0, 1),
(138, 'forum_product_limit', 'text', 28, NULL, NULL, '', 100, NULL, '6', '帖子内商品分享数量限制', '帖子内商品分享数量限制', 0, 1),
(139, 'news_title_limit', 'text', 29, NULL, NULL, '', 100, NULL, '20', '资讯标题字数限制', '资讯标题字数限制', 0, 1),
(140, 'news_content_limit', 'text', 29, NULL, NULL, '', 100, NULL, '5000', '资讯正文字数限制', '资讯正文字数限制', 0, 1),
(141, 'shop_phone', 'text', 23, NULL, NULL, '', 100, NULL, '\"400-0573080\"', '客服联系电话', '客服联系电话', 6, 1),
(142, 'ip_white', 'textarea', 30, NULL, NULL, NULL, 100, 5, '\"\"', '后台访问Ip白名单', '如设置了后台访问IP白名单，则只有白名单里的IP才能访问该管理后台，如通过其他IP访问，则系统会提示：页面不存在。如不设置则所有IP均能访问。', 0, 1),
(143, 'invite_code', 'radio', 21, '0=>不开启\n11=>开启', NULL, NULL, NULL, NULL, '\"0\"', '邀请码注册', '邀请码注册', 0, 1),
(144, 'invite_code_need', 'radio', 21, '0=>非必填\n11=>必填', NULL, NULL, NULL, NULL, '\"0\"', '设置邀请码为必填项', '设置邀请码为必填项', 0, 1),
(145, 'business_cooperation', 'text', 23, NULL, NULL, '', 100, NULL, '\"\"', '商务合作商务合作', '商务合作', 6, 1),
(146, 'feedback', 'text', 23, NULL, NULL, '', 100, NULL, '\"\"', '意见反馈', '意见反馈', 6, 1),
(147, 'pc_logo', 'upload', 32, NULL, 1, NULL, NULL, NULL, NULL, 'logo(227*50)', 'logo(227*50)', 1, 1),
(148, 'pc_icp', 'text', 32, NULL, NULL, '', 100, NULL, '', 'ICP备案信息', 'ICP备案信息', 0, 1),
(149, 'post_time_limit', 'text', 28, NULL, NULL, '', 100, NULL, '0', '24小时内发帖次数限制', '24小时内，最多发帖次数限制，0为无限制', 0, 1),
(150, 'kdn_id', 'text', 31, NULL, NULL, '', 100, NULL, '', '快递鸟ID', '快递鸟ID', 0, 1),
(151, 'kdn_my', 'text', 31, NULL, NULL, '', 100, NULL, '', '快递鸟秘钥', '快递鸟秘钥', 0, 1),
(152, 'score_full', 'text', 26, NULL, NULL, '', 100, NULL, '0', '满多少元可使用积分抵扣', '满多少可使用积分抵扣（0为都可使用）', 0, 1),
(153, 'agent_config', 'text', 33, NULL, NULL, '', 100, NULL, '{\"agent_way\":1}', '成为分销商方案', '成为分销商途径配置', 0, 2),
(154, 'agent_yongjin_config', 'text', 33, NULL, NULL, '', 100, NULL, '60', '佣金配置', '一级分销商获得返利比例，二级的=100-该值', 0, 2),
(155, 'agent_tixian_config_max', 'text', 33, NULL, NULL, '', 100, NULL, '', '提现额度上限', '提现额度上限', 0, 2),
(156, 'agent_tixian_config_min', 'text', 33, NULL, NULL, '', 100, NULL, '', '提现额度下限', '提现额度下限', 0, 2),
(157, 'agent_tixian_config_day', 'text', 33, NULL, NULL, '', 100, NULL, '', '每月*号结算', '每月*号结算上个月收益', 0, 2),
(158, 'agent_tixian_config_rules', 'textarea', 33, NULL, NULL, '', 100, NULL, '', '提现规则', '提现规则', 0, 2),
(159, 'agent_xieyi_config', 'textarea', 33, NULL, NULL, '', 100, NULL, '', '分销申请协议', '分销申请协议配置', 0, 2),
(160, 'agent_income_config', 'textarea', 33, NULL, NULL, '', 100, NULL, '', '收益说明', '收益说明配置', 0, 2),
(161, 'agent_share_config_logo', 'upload', 33, NULL, 1, '', 100, NULL, '', '分享海报配置-分享商品页面中的企业logo', '分享海报配置', 0, 2),
(162, 'agent_share_config_title', 'text', 33, NULL, NULL, '', 100, NULL, '', '分享海报配置-分享商品页面中的企业名称', '分享海报配置', 0, 2),
(163, 'video_size', 'text', 34, NULL, NULL, '', 100, NULL, '20', '视频大小上传限制', '单位:MB;0为不限制。', 20, 1),
(164, 'video_limit', 'text', 34, NULL, NULL, '', 100, NULL, '0', '24小时内视频上传数量限制', '24小时内视频上传数量限制', 19, 1),
(165, 'video_title', 'text', 34, NULL, NULL, '', 100, NULL, '20', '视频标题字数上限', '视频标题字数上限', 18, 1),
(166, 'video_product', 'text', 34, NULL, NULL, '', 100, NULL, '6', '视频商品数量限制', '视频商品数量限制', 14, 1),
(167, 'video_content', 'text', 34, NULL, NULL, '', 100, NULL, '2000', '视频内容字数上限', '视频内容字数上限', 16, 1),
(168, 'refund_reason', 'textarea', 5, '', 0, '', 100, 8, '\"\\u9519\\u62cd\\/\\u591a\\u62cd\\/\\u4e0d\\u60f3\\u8981\\r\\n\\u534f\\u5546\\u4e00\\u81f4\\u9000\\u6b3e\\r\\n\\u5176\\u4ed6\\u539f\\u56e0\"', '退款理由', '配置退款理由，一行一个理由', 0, 1),
(169, 'is_certification', 'radio', 37, '1=>是\n0=>否', NULL, '', 100, NULL, '0', '是否开启认证', '是否开启认证', 0, 1),
(170, 'is_certification_icon', 'radio', 37, '1=>是\n0=>否', NULL, '', 100, NULL, '1', '是否显示认证图标', '是否显示认证图标', 0, 1),
(171, 'tencent_video_is_open', 'radio', 34, '1=>开启\n0=>关闭', 0, '', 0, 0, '0', '是否开启腾讯云点播', '视频上传、播放', 13, 1),
(172, 'tencent_video_secret_id', 'text', 34, NULL, NULL, '', 100, NULL, '', '腾讯云点播secret_id', 'secret_id', 12, 1),
(173, 'tencent_video_secret_key', 'text', 34, NULL, NULL, '', 100, NULL, '', '腾讯云点播secret_key', 'secret_key', 11, 1),
(174, 'tencent_video_procedure', 'text', 34, NULL, NULL, '', 100, NULL, '', '腾讯云点播-任务流', '视频上传完成后执行的任务流，腾讯云中配置', 10, 1),
(175, 'tencent_video_app_id', 'text', 34, NULL, NULL, '', 100, NULL, '', '腾讯云点播app_id', '视频播放所需的app_id', 13, 1),
(176, 'cl_sms_id', 'text', 35, NULL, NULL, '', 100, NULL, '', '账号', '创蓝平台账号', 0, 1),
(177, 'cl_sms_password', 'text', 35, NULL, NULL, '', 100, NULL, '', '密码', '创蓝平台密码，不是登录密码', 0, 1),
(178, 'cl_sms_sign', 'text', 35, NULL, NULL, NULL, 100, NULL, '\"\"', '短信签名', '创蓝平台通知短信签名', 0, 1),
(179, 'cl_sms_template', 'textarea', 35, NULL, NULL, '', 100, NULL, '\"\\u60a8\\u7684\\u9a8c\\u8bc1\\u7801\\u662f\\uff1a{s6}\\uff0c15\\u5206\\u949f\\u5185\\u6709\\u6548\\u3002\\u5982\\u975e\\u672c\\u4eba\\u64cd\\u4f5c\\uff0c\\u8bf7\\u5ffd\\u7565\\u8be5\\u77ed\\u4fe1\\u3002\"', '短信模版', '创蓝平台通知短信模版', 0, 1),
(180, 'sms_type', 'radio', 36, 'cl=>创蓝\nfg=>飞鸽', NULL, NULL, NULL, NULL, '\"cl\"', '服务商选择', '短信服务商选择', 0, 1),
(181, 'pay_weixin_app_appid', 'text', 50, '', 0, '', 100, 0, '', 'Appid', '微信开放平台AppId', 0, 1),
(182, 'pay_weixin_app_mchid', 'text', 50, '', 0, '', 100, 0, '', 'Mchid', '微信商户平台账号-开通App支付，并与开放平台appid对应', 0, 1),
(183, 'pay_weixin_app_key', 'text', 50, '', 0, '', 100, 0, '', 'Key', '微信商户平台key-商户平台设置的密钥key', 0, 1),
(184, 'pay_weixin_app_open', 'radio', 50, '1=>开启\n0=>关闭', 0, '', 0, 0, '\"1\"', '开启', '是否启用APP微信支付', 0, 1),
(195, 'wx_nickname', 'radio', 2, '1=>开启\n0=>关闭', NULL, NULL, NULL, NULL, '\"1\"', '微信登录昵称是否排重', '如重复则系统在昵称后加上UID作区分', 0, 1),
(196, 'cart_limit', 'text', 5, NULL, NULL, '', 100, NULL, '100', '购物车加购上限', '购物车加购上限', 0, 1),
(198, 'website_logo_show', 'upload', 70, NULL, 1, NULL, NULL, NULL, '\"\\/public\\/uploads\\/config\\/image\\/5e23cad0947a4.png\"', '底部版权图片(225*90)', '免费版本不可修改', 0, 1),
(199, 'website_url', 'text', 70, NULL, NULL, '', 100, NULL, '\"https://preview-sjh.baidu.com/site/opensns.cn/1324db54-df6d-41d3-bc6f-be1a3d8a041b?time=1593767116912&showpageinpc=0\"', '底部版权链接', '免费版不可修改', 0, 1),
(200, 'client_local_storage_version', 'text', 55, '', 0, '', 100, 0, '\"2020-09-09-16-37\"', '客户端缓存版本号', '调整版本号后，客户端版本号不同的会清除所有缓存，默认格式当前时间：Y-m-d-H-i', 0, 1),
(202, 'weibo_content_limit', 'text', 38, NULL, NULL, NULL, 100, NULL, '\"200\"', '内容字数限制', '内容字数限制', 0, 1),
(203, 'weibo_store_limit', 'text', 38, NULL, NULL, '', 100, NULL, '6', '动态内商品分享数量限制', '动态内商品分享数量限制', 0, 1),
(300, 'forum_num_limit_down', 'text', 28, NULL, NULL, '', 100, NULL, '1', '帖子标题字数下限', '帖子标题字数下限', 0, 1),
(301, 'forum_content_limit_down', 'text', 28, NULL, NULL, '', 100, NULL, '10', '帖子正文字数下限', '帖子正文字数下限', 0, 1),
(302, 'video_title_down', 'text', 34, NULL, NULL, '', 100, NULL, '0', '视频标题字数下限', '视频标题字数下限', 17, 1),
(303, 'video_content_down', 'text', 34, NULL, NULL, '', 100, NULL, '0', '视频内容字数下限', '视频内容字数下限', 15, 1),
(304, 'code_site', 'radio', 40, '0=>不显示\n1=>显示', NULL, NULL, NULL, NULL, '\"1\"', '【我的】页面用户名下方是否显示邀请码', '我的页面邀请码是否显示', 8, 1),
(310, 'is_sns_verify', 'radio', 999, '0=>关闭\n1=>开启', 0, '', 0, 0, '\"0\"', '是否不验证短信', '是否不验证短信', 0, 0),
(311, 'forum_admin_one', 'upload', 39, NULL, 1, NULL, NULL, NULL, NULL, '版主图标(150*75)', '版主图标', 0, 1),
(312, 'forum_admin_two', 'upload', 39, NULL, 1, NULL, NULL, NULL, NULL, '超级版主图标(150*75)', '超级版主图标', 0, 1),
(313, 'picture_store_place', 'radio', 60, 'local=>本地存储\nTencent_COS=>腾讯云对象存储COS', 0, '', 0, 0, '\"local\"', '存储位置', '如选择“腾讯云对象储存”，请准确、完整填写下方相关信息。另，特殊格式图片（如webp）仅支持保存至本地，请知悉。', 7, 1),
(314, 'picture_store_tencent_secretId', 'text', 60, '', 0, '', 100, 0, '\"\"', 'secretId', '腾讯云对象存储secretId', 10, 1),
(315, 'picture_store_tencent_secretKey', 'text', 60, '', 0, '', 100, 0, '\"\"', 'secretKey', '腾讯云对象存储secretKey', 0, 1),
(316, 'picture_store_tencent_region', 'text', 60, '', 0, '', 100, 0, '\"\"', '所属地域', '腾讯云存储桶所属地域，如ap-chengdu', 0, 1),
(317, 'picture_store_tencent_bucket', 'text', 60, '', 0, '', 100, 0, '\"\"', '空间名称', '腾讯云存储空间，即存储桶，如jxxt-1257689580', 0, 1),
(318, 'invite_show', 'radio', 40, '0=>不显示\n1=>显示', NULL, NULL, NULL, NULL, '\"1\"', '邀请海报是否显示邀请码', '邀请海报是否显示邀请码', 0, 1),
(319, 'invite_reward', 'radio', 40, '0=>不开启\n1=>开启', NULL, NULL, NULL, NULL, '\"0\"', '邀请奖励是否开启', '邀请奖励是否开启', 0, 1),
(320, 'share_suffix', 'radio', 24, '1=>开启\n0=>关闭', NULL, NULL, NULL, NULL, '\"1\"', '应用名称后缀', '应用名称后缀', 0, 1),
(321, 'company_name', 'text', 23, NULL, NULL, NULL, 100, 5, '\"\"', '公司名称', '公司名称', 9, 1),
(322, 'related_information', 'upload', 23, NULL, 2, NULL, 100, 5, '\"\"', '相关证照', '相关证照', 7, 1),
(323, 'forum_audit_visit_limit', 'text', 61, NULL, NULL, NULL, 100, 5, '\"2\"', '版块用户访问申请有效期(天)', '开启访问审核的版块，用户申请提交后，如果在有效期内管理组未处理，系统则按默认方式自动处理', 4, 1),
(324, 'forum_audit_visit', 'radio', 61, '0=>驳回\n1=>通过', 0, '', 0, 0, '\"1\"', '有效期后系统默认处理方式', '有效期后系统默认处理方式', 3, 1),
(325, 'forum_audit_forum_limit', 'text', 61, NULL, NULL, NULL, 100, 5, '\"1\"', '版块帖子审核有效期(天)', '开启内容审核的版块，用户发帖后如果在有效期内管理组未处理，系统则按默认方式自动处理', 2, 1),
(326, 'forum_audit_forum', 'radio', 61, '0=>驳回\r\n1=>通过', 0, '', 0, 0, '\"1\"', '有效期后系统默认处理方式', '有效期后系统默认处理方式', 1, 1),
(339, 'tencent_video_save_key', 'text', 34, '', NULL, '', 100, NULL, '', '腾讯云点播防盗链Key', '视频播放所需的加密Key', 8, 1),
(340, 'picture_magnification', 'radio', 23, '1=>默认尺寸\n2=>2倍\n3=>3倍', NULL, NULL, NULL, NULL, '\"1\"', '应用内图片倍率', '即前台图片像素调整，倍率越高图片质量越高，清晰度越高，打开速度越慢', 0, 1),
(341, 'super_forum_admin_num', 'text', 39, NULL, NULL, NULL, 100, NULL, '\"1\"', '超级版主数量限制', '一个分区最多设置几个超级版主', 21, 1),
(342, 'forum_admin_num', 'text', 39, NULL, NULL, '', 100, NULL, '3', '版主数量限制', '一个版块最多设置几个版主', 20, 1),
(343, 'super_forum_admin_recommend', 'text', 39, NULL, NULL, '', 100, NULL, '10', '超级版主日推荐数量限制', '超级版主日推荐数量限制', 19, 0),
(344, 'super_forum_admin_top', 'text', 39, NULL, NULL, '', 100, NULL, '5', '超级版主日置顶数量限制', '超级版主日置顶数量限制', 18, 1),
(345, 'super_forum_admin_detail_top', 'text', 39, NULL, NULL, '', 100, NULL, '2', '超级版主日详情置顶数量限制', '超级版主日详情置顶数量限制', 17, 1),
(346, 'super_forum_admin_index_top', 'text', 39, NULL, NULL, '', 100, NULL, '1', '超级版主日首页置顶数量限制', '超级版主日首页置顶数量限制', 16, 1),
(347, 'super_forum_admin_essence', 'text', 39, NULL, NULL, '', 100, NULL, '10', '超级版主日加精数量限制', '超级版主日加精数量限制', 16, 1),
(348, 'forum_admin_recommend', 'text', 39, NULL, NULL, '', 100, NULL, '5', '版主日推荐数量限制', '版主日推荐数量限制', 15, 0),
(349, 'forum_admin_top', 'text', 39, NULL, NULL, '', 100, NULL, '1', '版主日置顶数量限制', '版主日置顶数量限制', 14, 1),
(350, 'forum_admin_detail_top', 'text', 39, NULL, NULL, '', 100, NULL, '1', '版主日详情置顶数量限制', '版主日详情置顶数量限制', 13, 1),
(351, 'forum_admin_index_top', 'text', 39, NULL, NULL, '', 100, NULL, '0', '版主日首页置顶数量限制', '版主日首页置顶数量限制', 12, 1),
(352, 'forum_admin_essence', 'text', 39, NULL, NULL, '', 100, NULL, '5', '版主日加精数量限制', '版主日加精数量限制', 11, 1),
(353, 'website_connect_open', 'radio', 90, '0=>不开启\n1=>开启', 0, '', 100, 0, '\"0\"', '是否开启第三方平台接入', '开启后，OSX默认作为内嵌应用，不再有独立的注册登录系统，完全采用主应用免登陆形式', 0, 1),
(354, 'website_connect_app_key', 'text', 90, '', 0, '', 100, 0, '\"osx_app_key\"', 'appKey', '开发人员尽量避免使用默认值，最好修改保存后使用', 0, 1),
(355, 'website_connect_app_secret', 'text', 90, '', 0, '', 100, 0, '\"osx_app_secret\"', 'appSecret', '开发人员尽量避免使用默认值，最好修改保存后使用', 0, 1),
(356, 'website_connect_userInfo_api', 'text', 90, '', 0, '', 100, 0, '\"\"', '用户信息获取接口', '参考OSX对接档中免登陆对接中的“用户信息获取接口”实现，可联系客户经理获取', 0, 1),
(357, 'website_connect_userActionNotify_api', 'text', 90, '', 0, '', 100, 0, '\"\"', '事件通知接口', '参考OSX对接档中用户行为事件通知中的“事件通知接口实现”实现，可联系客户经理获取', 0, 1),
(358, 'website_connect_login_page', 'text', 90, '', 0, '', 100, 0, '\"\"', '游客唤起登录页', '游客唤起登录时跳转的主应用登录页', 0, 1),
(359, 'xcx_video', 'radio', 7, '0=>关闭\n1=>开启', NULL, NULL, NULL, NULL, '\"0\"', '小程序是否开启视频', '小程序是否开启视频', 0, 1),
(360, 'invite_show_name', 'radio', 40, '0=>不显示\n1=>显示', NULL, NULL, NULL, NULL, '\"1\"', '邀请海报是否显示邀请人信息（头像、昵称）', '邀请海报是否显示用户昵称', 4, 1),
(361, 'invite_reward_remark', 'textarea', 91, '', NULL, NULL, NULL, NULL, '', '前端好友邀请活动说明页中的文案', '前端好友邀请活动说明页中的文案', 6, 1),
(362, 'domain_white', 'textarea', 30, NULL, NULL, NULL, 100, 5, '\"\"', '后台访问域名白名单', '如设置了后台访问域名白名单，则只有通过白名单里的域名才能访问到该管理后台，如通过其他域名访问，则系统会提示：页面不存在。如不设置则所有域名均能访问。', 0, 1),
(363, 'support_third_login', 'radio', 0, '0=>否\n1=>是', 0, '', 0, 0, '\"0\"', '是否支持第三方（微信）直接登录', '是否支持第三方（微信）直接登录', 1, 1),
(364, 'must_weixin_login', 'radio', 21, '0=>否\n1=>是', 0, '', 0, 0, '\"0\"', 'H5端微信环境强制登录', 'H5端微信环境强制登录', 1, 1),
(365, 'support_platform', 'checkbox', 92, '1=>h5\r\n2=>微信小程序\r\n3=>app-Android\r\n4=>app-ios', 0, '', 0, 0, '\"1\"', '支持平台', '', 1, 1),
(366, 'platform_h5_url', 'text', 92, '', 0, '', 100, 0, '', 'H5端前台访问地址', '填写时地址务必以/#/结尾，如https://www.baidu.com/#/', 1, 1),
(367, 'platform_xcx_url', 'upload', 92, '', 1, '', 0, 0, '', '小程序访问二维码', '', 1, 1),
(368, 'platform_android_url', 'text', 92, '', 0, '', 100, 0, '', 'app-Android下载地址', '', 1, 1),
(369, 'platform_ios_url', 'text', 92, '', 0, '', 100, 0, '', 'app-iOS下载地址', '', 1, 1),
(370, 'invite_font_color', 'radio', 40, '0=>白色\n1=>黑色', NULL, NULL, NULL, NULL, '\"0\"', '海报文字颜色选择', '即设置海报邀请人信息、邀请码等相关文字的颜色', 6, 1),
(371, 'app_message_key', 'text', 93, '', 0, '', 100, 0, '', '消息appkey ', '', 1, 1),
(372, 'app_message_id', 'text', 93, '', 0, '', 100, 0, '', '消息appid ', '', 1, 1),
(373, 'app_message_mastersecret', 'text', 93, '', 0, '', 100, 0, '', '消息MASTERSECRET ', '', 1, 1),
(374, 'index_goods_price', 'radio', 94, '0=>隐藏\n1=>显示', NULL, NULL, NULL, NULL, '1', '首页商品价格', '首页商品价格是否显示', 0, 1),
(375, 'index_goods_sale', 'radio', 94, '0=>隐藏\n1=>显示', NULL, NULL, NULL, NULL, '1', '首页商品销量', '商品的销量是否显示', 0, 1),
(376, 'index_goods_name', 'radio', 94, '0=>隐藏\n1=>显示', NULL, NULL, NULL, NULL, '\"0\"', '首页商品名称', '首页商品名称是否显示', 0, 1),
(377, 'pc_gaba', 'text', 32, NULL, NULL, '', 100, NULL, '', '公安备案号', '公安备案号', 0, 1),
(378, 'pc_gaba_link', 'text', 32, NULL, NULL, '', 100, NULL, '', '公安备案号链接', '公安备案号链接', 0, 1),
(379, 'pc_copyright', 'text', 32, NULL, NULL, '', 100, NULL, '', '版权信息', '版权信息', 0, 1),
(380, 'pc_h5_code', 'upload', 32, NULL, 1, NULL, NULL, NULL, NULL, '右侧二维码', '右侧二维码', 1, 1),
(381, 'shop_on', 'radio', 88, '0=>关闭\n1=>开启', NULL, NULL, NULL, NULL, '\"0\"', '积分商城是否开启', '积分商城是否开启', 0, 1),
(382, 'video_default_cover', 'upload', 34, '', 1, '', 100, NULL, '\"\"', '视频默认封面', '视频默认封面', 6, 1),
(383, 'picture_max', 'text', 23, '', NULL, NULL, NULL, NULL, '2', '社区图片大小上传限制', '社区图片大小上传限制(单位M)', 0, 1),
(384, 'url_link_white', 'textarea', 30, NULL, NULL, NULL, 100, 5, '\"\"', '后台发帖超链接白名单', '管理后台发帖添加的超链接，前台访问时提示“安全性未知”，通过添加超链接白名单的方式，已加入白名单的链接地址不再提示“安全性未知”，可直接点击进入。', 0, 1),
(385, 'channel_first_page_open', 'radio', 100, '1=>开启\n0=>关闭', 0, '', 100, 0, '\"1\"', '页面', '关闭后用户首次登陆将不显示频道引导选择，直接进入首页', 0, 1),
(386, 'channel_first_page_can_jump', 'radio', 100, '1=>开启\n0=>关闭', 0, '', 100, 0, '\"1\"', '跳过引导按钮', '关闭后，频道引导页面上不显示跳过按钮，用户必须选择频道后才能进入首页', 0, 1),
(387, 'channel_edit_page_open', 'radio', 100, '1=>开启\n0=>关闭', 0, '', 100, 0, '\"1\"', '页面', '关闭后，首页频道导航栏处不显示频道编辑按钮，即用户无法对导航栏进行编辑', 0, 1),
(388, 'event_on', 'radio', 0, '0=>关闭\n1=>开启', NULL, NULL, NULL, NULL, '\"0\"', '活动是否开启', '活动是否开启', 0, 1),
(389, 'event_type_pay', 'text', 0, '', NULL, NULL, NULL, NULL, '\"fly\"', '活动积分付款', '活动积分付款', 0, 1),
(390, 'message_name', 'text', 95, NULL, NULL, '', 100, 0, '\"\\u81ea\\u5b9a\\u4e49\\u6d88\\u606f\"', '自定义消息名称', '该消息在消息中心的名称', 0, 1),
(391, 'message_logo', 'upload', 95, NULL, 1, NULL, NULL, NULL, NULL, '自定义消息图标', '该消息在消息中心的图标', 0, 1),
(392, 'kdn_ff', 'radio', 31, '0=>免费\n1=>付费', 0, '', 0, 0, '\"0\"', '快递鸟是否付费', '快递鸟是否付费', 0, 1),
(393, 'is_force_login', 'radio', 21, '0=>否\n1=>是', 0, '', 0, 0, '\"0\"', '是否开启强制登录', '打开应用时是否强制登录，使用部分功能时一定要登录', 1, 1),
(394, 'other_login', 'radio', 21, '0=>否\n1=>是', 0, '', 0, 0, '\"0\"', '第三方注册绑定', '第三方注册绑定', 1, 1),
(395, 'other_login_must', 'radio', 21, '0=>否\n1=>是', 0, '', 0, 0, '\"0\"', '是否设置绑定为必填项', '是否设置绑定为必填项', 1, 1),
(396, 'email_title', 'text', 45, NULL, NULL, '', 100, NULL, '\"\\u90ae\\u4ef6\\u6ce8\\u518c\"', '邮件标题', '邮件标题', 10, 1),
(397, 'email_content', 'text', 45, NULL, NULL, '', 100, NULL, '\"\\u60a8\\u7684\\u9a8c\\u8bc1\\u7801\\u662f {\\u9a8c\\u8bc1\\u7801} \\uff0c15\\u5206\\u949f\\u6709\\u6548\\uff01\"', '邮件正文', '{验证码}为验证码位置请勿删除', 10, 1),
(398, 'email_host', 'text', 45, NULL, NULL, '', 100, NULL, '', 'smtp服务器', 'smtp服务器', 10, 1),
(399, 'email_from', 'text', 45, NULL, NULL, '', 100, NULL, '', '发送者的邮件地址', '发送者的邮件地址', 10, 1),
(400, 'email_name', 'text', 45, NULL, NULL, '', 100, NULL, '', '发送邮件的用户昵称', '发送邮件的用户昵称', 10, 1),
(401, 'email_user', 'text', 45, NULL, NULL, '', 100, NULL, '', '登录到邮箱的用户名', '登录到邮箱的用户名', 10, 1),
(402, 'email_password', 'text', 45, NULL, NULL, '', 100, NULL, '', '授权码', '第三方登录的授权码，在邮箱里面设置', 10, 1),
(406, 'im_open', 'radio', 56, '0=>关闭\n1=>开启', 0, '', 0, 0, '\"0\"', '开启私信', '开启私信', 0, 1),
(409, 'im_url', 'text', 56, NULL, NULL, NULL, 100, 0, '\"https:\\/\\/sixintp.opensns.cn\\/\"', '私信第三方地址', '第三方地址', 0, 1),
(410, 'im_workman_url', 'text', 56, NULL, NULL, NULL, 100, 0, '\"wss:\\/\\/sixin.opensns.cn\\/wss\"', 'workman地址', '实时请求地址', 0, 1),
(411, 'site_service_nickname', 'text', 23, NULL, NULL, NULL, 100, 0, '\"\\u5ba2\\u670d\\u5c0f\\u59d0\\u59d0\"', '在线客服昵称', '10个字以内', 0, 1),
(412, 'site_service_identity', 'text', 23, NULL, NULL, NULL, 100, 0, '\"\\u5728\\u7ebf\\u5ba2\\u670d\"', '客服身份', '10个字以内', 0, 1),
(413, 'site_service_introduce', 'textarea', 23, NULL, NULL, '', 100, NULL, '\"Hi~ \\u6211\\u662f\\u60a8\\u7684\\u4e13\\u5c5e\\u5ba2\\u670d\"', '客服自我介绍', '20个字以内', 0, 1),
(414, 'recommend_at', 'radio', 105, '0=>关闭\n1=>开启', 1, NULL, 100, NULL, '\"0\"', '首次登录推荐用户引导', '首次登录推荐用户引导', 0, 1),
(415, 'comment_photo', 'radio', 106, '0=>关闭\n1=>开启', 1, NULL, 100, NULL, '\"1\"', ' 社区评论自定义设置是否开启图片评论', ' 社区评论自定义设置是否开启图片评论', 0, 1),
(416, 'reward_points_rules', 'text', 33, NULL, NULL, NULL, 100, NULL, '\"<p>12121212<\\/p>\"', '积分奖励规则协议', '积分奖励规则协议', 0, 1),
(417, 'writing_center', 'text', 32, NULL, NULL, NULL, 100, NULL, '\"\\u5e16\\u5b50\\u9875\\u9762-[1]\\u8fce\\u6765\\u5230OSX\\u7684\\u4e16...||\\/packageA\\/post-page\\/post-page?id=1\"', '创作引导帖子链接', '创作引导帖子链接', 0, 1),
(418, 'platform_pc_url', 'text', 92, NULL, NULL, NULL, 100, NULL, NULL, 'pc端前台访问地址', NULL, 0, 1),
(419, 'pc_user_background', 'upload', 32, NULL, 1, NULL, 100, NULL, NULL, 'pc个人中心用户背景图', 'pc个人中心用户背景图', 0, 1),
(420, 'tencent_animated_template_id', 'text', 34, NULL, NULL, '', 100, NULL, '\"20000\"', '腾讯云视频转动图模板ID', '腾讯云视频转动图模板ID，在腾讯云控制台设置', 5, 1),
(421, 'video_cover_type', 'checkbox', 34, '0=>自定义静态封面\r\n1=>动图封面', 0, '', 0, 0, '[\"0\"]', '视频封面', '视频封面类型，选择自定义静态封面需设置视频默认封面，选择动图封面需设置腾讯云视频转动图模板ID，两者都选优先使用自定义静态封面', 7, 1),
(422, 'information_stream_ad_min', 'text', 110, NULL, NULL, 'required:true,min:5,number:true', 100, NULL, '\"5\"', '最小间隔', '信息流广告展现频次最小间隔', 8, 1),
(423, 'information_stream_ad_max', 'text', 110, NULL, NULL, 'required:true,max:100,number:true', 100, NULL, '\"10\"', '最大间隔', '信息流广告展现频次最大间隔', 4, 1),
(424, 'site_service_avatar', 'upload', 23, NULL, 1, NULL, NULL, NULL, '\"\"', '客服头像', '客服头像', 0, 1),
(425, 'weixin_method', 'text', 4, '', 0, '', 100, 0, '', '渠道id', '', 12, 1),
(426, 'weixin_name', 'text', 4, '', 0, '', 100, 0, '', '渠道名称', '', 11, 1),
(427, 'withdrawal_service_charge', 'text', 101, '', 0, '', 100, 0, '0', '提现手续费', '%,百分比', 20, 1),
(428, 'withdrawal_min_amount', 'text', 101, '', 0, '', 100, 0, '0', '单次提现最低限额', '', 19, 1),
(429, 'withdrawal_max_amount', 'text', 101, '', 0, '', 100, 0, '0', '单次提现最高限额', '', 18, 1),
(430, 'withdrawal_day_max_amount', 'text', 101, '', 0, '', 100, 0, '0', '单日提现最高限额', '', 17, 1),
(435, 'information_stream_open', 'radio', 110, '0=>关闭\n1=>开启', 1, NULL, 100, NULL, '\"0\"', '微信小程序流量主', '微信小程序流量主', 0, 1),
(436, 'wallet_open', 'radio', 101, '0=>关闭\n1=>开启', 0, '', 0, 0, '\"0\"', '钱包功能', '钱包功能', 22, 1),
(437, 'withdraw_to_wallet', 'radio', 101, '0=>关闭\n1=>开启', 0, '', 0, 0, '\"0\"', '分销收益提现到余额', '分销收益提现到余额', 21, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_config_tab`
--

CREATE TABLE `osx_system_config_tab` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置分类id',
  `title` varchar(255) NOT NULL COMMENT '配置分类名称',
  `eng_title` varchar(255) NOT NULL COMMENT '配置分类英文名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '配置分类状态',
  `info` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置分类是否显示',
  `icon` varchar(30) DEFAULT NULL COMMENT '图标',
  `type` int(2) DEFAULT '0' COMMENT '配置类型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置分类表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_config_tab`
--

INSERT INTO `osx_system_config_tab` (`id`, `title`, `eng_title`, `status`, `info`, `icon`, `type`) VALUES
(2, '公众号配置', 'wechat', 1, 0, 'weixin', 1),
(4, '公众号支付配置', 'pay', 1, 0, 'jpy', 1),
(5, '商城配置', 'store', 1, 0, 'shopping-cart', 4),
(7, '小程序配置', 'routine', 1, 0, 'weixin', 2),
(9, '分销配置', 'fenxiao', 1, 0, 'sitemap', 3),
(11, '积分配置', 'point', 1, 0, 'powerpoint-o', 3),
(12, '优惠券配置', 'coupon', 1, 0, 'heartbeat', 3),
(14, '小程序支付配置', 'routine_pay', 1, 0, 'jpy', 2),
(16, '小程序首页配置', 'routine_index_page', 1, 0, 'home', 2),
(20, '短信配置', 'sms', 1, 0, 'envelope', 0),
(21, '注册配置', 'reg_config', 1, 0, 'cog', 3),
(22, '用户配置', 'user_config', 1, 0, 'pencil', 3),
(23, '软件基本配置', 'os_basics', 1, 0, 'shirtsinbulk', 0),
(24, '分享设置', 'share_basics', 1, 0, 'street-view', 0),
(25, '其他设置', 'other_basics', 1, 0, 'bookmark-o', 0),
(26, '积分配置', 'score_set', 1, 0, 'tag', 4),
(27, '商城基本设置', 'shop_basics', 1, 0, 'cart-plus', 4),
(28, '帖子配置', 'forum_set', 1, 0, 'book', 0),
(29, '资讯配置', 'news_set', 1, 0, 'joomla', 0),
(30, '安全配置', 'safe', 1, 0, 'shield', 0),
(31, '物流配置', 'shop_wuliu', 1, 0, 'heartbeat', 4),
(32, 'pc端配置', 'pc_basics', 1, 0, '', 0),
(33, '分销配置', 'agent', 1, 0, 'sitemap', 3),
(34, '视频设置', 'video_set', 1, 0, '', 0),
(35, '创蓝短信配置', 'cl_sms', 1, 0, '', 3),
(36, '短信服务商', 'sms_who', 1, 0, '', 3),
(37, '认证基础配置', 'certification', 1, 0, '', 5),
(38, '动态设置', 'weibo_set', 1, 0, '', 3),
(39, '版主设置', 'forum_set_icon', 1, 0, '', 3),
(40, '邀请设置', 'invite_site', 1, 0, '', 6),
(45, '邮件配置', 'email_set', 1, 0, 'jpy', 1),
(50, 'APP微信支付配置', 'wechat_app_pay', 1, 0, 'jpy', 1),
(55, '刷新客户端缓存', 'client_local_storage', 1, 0, 'bookmark-o', 1),
(60, '图片存储配置', 'picture_store_place', 1, 0, 'jpy', 1),
(70, '版权标识', 'copyright', 1, 0, '', 6),
(90, '第三方平台接入配置', 'website_connect', 1, 0, 'jpy', 1),
(91, '邀请奖励说明', 'invite_reward_remark', 1, 0, 'jpy', 6),
(92, '平台设置', 'platform_set', 1, 0, 'jpy', 1),
(93, 'APP消息配置', 'app_message_set', 1, 0, 'jpy', 1),
(94, '知识付费配置', 'knowledge_config_set', 1, 0, 'jpy', 1),
(95, '自定义消息设置', 'message_site', 1, 0, '', 0),
(100, '频道设置', 'channel_setting', 1, 0, '', 1),
(101, '私信设置', 'im_set_config', 1, 0, 'jpy', 1),
(105, '首次登录推荐用户引导配置', 'recommend', 1, 0, NULL, 5),
(106, '评论配置', 'comment', 1, 0, NULL, 0),
(110, '小程序流量主广告配置', 'routine_ad', 1, 0, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_count_log_share`
--

CREATE TABLE `osx_system_count_log_share` (
  `id` int(11) NOT NULL,
  `place` varchar(50) NOT NULL COMMENT '平台，all：全部；android：安卓；ios：苹果；h5：手机网页；mini_program：微信小程序；alipay_mini_program：支付宝小程序；headline_mini_program：头条小程序',
  `create_time` int(11) NOT NULL COMMENT '记录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分享次数记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_count_log_to_show`
--

CREATE TABLE `osx_system_count_log_to_show` (
  `id` int(11) NOT NULL,
  `place` varchar(50) NOT NULL COMMENT '平台，all：全部；android：安卓；ios：苹果；h5：手机网页；mini_program：微信小程序；alipay_mini_program：支付宝小程序；headline_mini_program：头条小程序',
  `type` varchar(10) NOT NULL COMMENT '类型，day：当日；average：每日平均；max：历史峰值',
  `new_count` int(11) NOT NULL COMMENT '该记录访问次数',
  `active_count` int(11) NOT NULL COMMENT '该记录访问次数',
  `view_count` int(11) NOT NULL COMMENT '该记录访问次数',
  `total_count` int(11) NOT NULL COMMENT '该记录访问次数',
  `share_count` int(11) NOT NULL COMMENT '该记录访问次数',
  `day` int(11) NOT NULL COMMENT '日期-时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='计划任务统计数据记录表，用于后台展示';

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_count_log_user`
--

CREATE TABLE `osx_system_count_log_user` (
  `id` int(11) NOT NULL,
  `place` varchar(50) NOT NULL COMMENT '平台，all：全部；android：安卓；ios：苹果；h5：手机网页；mini_program：微信小程序；alipay_mini_program：支付宝小程序；headline_mini_program：头条小程序',
  `type` varchar(10) NOT NULL COMMENT '类型，new:新增用户；active:活跃用户',
  `create_time` int(11) NOT NULL COMMENT '记录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新增/活跃用户记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_count_log_view`
--

CREATE TABLE `osx_system_count_log_view` (
  `id` int(11) NOT NULL,
  `place` varchar(50) NOT NULL COMMENT '平台，all：全部；android：安卓；ios：苹果；h5：手机网页；mini_program：微信小程序；alipay_mini_program：支付宝小程序；headline_mini_program：头条小程序',
  `num` int(11) NOT NULL COMMENT '该记录访问次数',
  `create_time` int(11) NOT NULL COMMENT '记录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问次数记录表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_file`
--

CREATE TABLE `osx_system_file` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文件对比ID',
  `cthash` char(32) NOT NULL COMMENT '文件内容',
  `filename` varchar(255) NOT NULL COMMENT '文价名称',
  `atime` char(12) NOT NULL COMMENT '上次访问时间',
  `mtime` char(12) NOT NULL COMMENT '上次修改时间',
  `ctime` char(12) NOT NULL COMMENT '上次改变时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件对比表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_grade_desc`
--

CREATE TABLE `osx_system_grade_desc` (
  `id` int(11) NOT NULL,
  `description` text COMMENT '说明内容',
  `type` int(11) DEFAULT NULL COMMENT '1会员等级,2商城会员等级,3积分规则说明，4积分签到',
  `all_agreement_id` int(11) DEFAULT NULL COMMENT '关联协议集中管理表的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_grade_desc`
--

INSERT INTO `osx_system_grade_desc` (`id`, `description`, `type`, `all_agreement_id`) VALUES
(1, '商城会员等级<p></p>', 2, 6),
(2, '系统会员等级<p></p>', 1, 8),
(3, '积分规则说明<p></p>', 3, 7),
(4, '签到规则说明<p></p>', 4, NULL),
(5, '签到规则说明<p></p>', 4, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_group`
--

CREATE TABLE `osx_system_group` (
  `id` int(11) NOT NULL COMMENT '组合数据ID',
  `name` varchar(50) NOT NULL COMMENT '数据组名称',
  `info` varchar(256) NOT NULL COMMENT '数据提示',
  `config_name` varchar(50) NOT NULL COMMENT '数据字段',
  `fields` text COMMENT '数据组字段以及类型（json数据）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组合数据表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_group`
--

INSERT INTO `osx_system_group` (`id`, `name`, `info`, `config_name`, `fields`) VALUES
(32, '个人中心菜单', '【公众号】', 'my_index_menu', '[{\"name\":\"\\u540d\\u79f0\",\"title\":\"name\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u56fe\\u6807\",\"title\":\"icon\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"url\",\"type\":\"select\",\"param\":\"\\/wap\\/my\\/integral.html=>\\u6211\\u7684\\u79ef\\u5206\\n\\/wap\\/my\\/coupon.html=>\\u4f18\\u60e0\\u5238\\n\\/wap\\/my\\/collect.html=>\\u6536\\u85cf\\u5217\\u8868\\n\\/wap\\/my\\/address.html=>\\u5730\\u5740\\u7ba1\\u7406\\n\\/wap\\/my\\/balance.html=>\\u6211\\u7684\\u4f59\\u989d\\n\\/wap\\/service\\/service_new.html=>\\u804a\\u5929\\u8bb0\\u5f55\\n\\/wap\\/index\\/about.html=>\\u8054\\u7cfb\\u6211\\u4eec\\n\\/wap\\/my\\/user_pro.html=>\\u63a8\\u5e7f\\u4f63\\u91d1\"},{\"name\":\"\\u6d4b\\u8bd5\",\"title\":\"test\",\"type\":\"uploads\",\"param\":\"\"}]'),
(34, '商城首页banner', '【公众号】', 'store_home_banner', '[{\"name\":\"\\u6807\\u9898\",\"title\":\"title\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"url\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u56fe\\u7247\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"}]'),
(35, '首页分类按钮图标', '【公众号】', 'store_home_menus', '[{\"name\":\"\\u540d\\u79f0\",\"title\":\"name\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"url\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u56fe\\u6807\",\"title\":\"icon\",\"type\":\"upload\",\"param\":\"\"}]'),
(36, '首页滚动新闻', '【公众号】', 'store_home_roll_news', '[{\"name\":\"\\u6eda\\u52a8\\u6587\\u5b57\",\"title\":\"info\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u70b9\\u51fb\\u94fe\\u63a5\",\"title\":\"url\",\"type\":\"input\",\"param\":\"\"}]'),
(37, '拼团、秒杀、砍价顶部banner图', '小程序', 'routine_lovely', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"img\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u63cf\\u8ff0\",\"title\":\"comment\",\"type\":\"input\",\"param\":\"\"}]'),
(38, '砍价列表页左上小图标', '小程序', 'bargain_banner', '[{\"name\":\"banner\",\"title\":\"banner\",\"type\":\"upload\",\"param\":\"\"}]'),
(47, '首页分类图标', '小程序', 'routine_home_menus', '[{\"name\":\"\\u5206\\u7c7b\\u540d\\u79f0\",\"title\":\"name\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u5206\\u7c7b\\u56fe\\u6807\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u8df3\\u8f6c\\u8def\\u5f84\",\"title\":\"url\",\"type\":\"select\",\"param\":\"\\/pages\\/index\\/index=>\\u5546\\u57ce\\u9996\\u9875\\n\\/pages\\/user_spread_user\\/index=>\\u4e2a\\u4eba\\u63a8\\u5e7f\\n\\/pages\\/user_sgin\\/index=>\\u6211\\u8981\\u7b7e\\u5230\\n\\/pages\\/user_get_coupon\\/index=>\\u4f18\\u60e0\\u5238\\n\\/pages\\/user\\/user=>\\u4e2a\\u4eba\\u4e2d\\u5fc3\\n\\/pages\\/activity\\/goods_seckill\\/index=>\\u79d2\\u6740\\u5217\\u8868\\n\\/pages\\/activity\\/goods_combination\\/index=>\\u62fc\\u56e2\\u5217\\u8868\\u9875\\n\\/pages\\/activity\\/goods_bargain\\/index=>\\u780d\\u4ef7\\u5217\\u8868\\n\\/pages\\/goods_cate\\/goods_cate=>\\u5206\\u7c7b\\u9875\\u9762\\n\\/pages\\/user_address_list\\/index=>\\u5730\\u5740\\u5217\\u8868\\n\\/pages\\/cash\\/cash=>\\u63d0\\u73b0\\u9875\\u9762\\n\\/pages\\/extension\\/extension=>\\u63a8\\u5e7f\\u7edf\\u8ba1\\n\\/pages\\/main\\/main=>\\u8d26\\u6237\\u91d1\\u989d\\n\\/pages\\/user_goods_collection\\/index=>\\u6211\\u7684\\u6536\\u85cf\\n\\/pages\\/promotion-card\\/promotion-card=>\\u63a8\\u5e7f\\u4e8c\\u7ef4\\u7801\\u9875\\u9762\\n\\/pages\\/order_addcart\\/order_addcart=>\\u8d2d\\u7269\\u8f66\\u9875\\u9762\\n\\/pages\\/order_list\\/index=>\\u8ba2\\u5355\\u5217\\u8868\\u9875\\u9762\\n\\/pages\\/news_list\\/index=>\\u6587\\u7ae0\\u5217\\u8868\\u9875\"},{\"name\":\"\\u5e95\\u90e8\\u83dc\\u5355\",\"title\":\"show\",\"type\":\"radio\",\"param\":\"1=>\\u662f\\n2=>\\u5426\"}]'),
(48, '首页banner滚动图', '小程序', 'routine_home_banner', '[{\"name\":\"\\u6807\\u9898\",\"title\":\"name\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"url\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u56fe\\u7247\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"}]'),
(49, '秒杀时间段', '小程序', 'routine_seckill_time', '[{\"name\":\"\\u5f00\\u542f\\u65f6\\u95f4(\\u6574\\u6570\\u5c0f\\u65f6)\",\"title\":\"time\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u6301\\u7eed\\u65f6\\u95f4(\\u6574\\u6570\\u5c0f\\u65f6)\",\"title\":\"continued\",\"type\":\"input\",\"param\":\"\"}]'),
(50, '首页滚动新闻', '小程序', 'routine_home_roll_news', '[{\"name\":\"\\u6eda\\u52a8\\u6587\\u5b57\",\"title\":\"info\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u8df3\\u8f6c\\u8def\\u5f84\",\"title\":\"url\",\"type\":\"select\",\"param\":\"\\/pages\\/index\\/index=>\\u5546\\u57ce\\u9996\\u9875\\n\\/pages\\/spread\\/spread=>\\u4e2a\\u4eba\\u63a8\\u5e7f\\n\\/pages\\/coupon-status\\/coupon-status=>\\u4f18\\u60e0\\u5238\\n\\/pages\\/user\\/user=>\\u4e2a\\u4eba\\u4e2d\\u5fc3\\n\\/pages\\/miao-list\\/miao-list=>\\u79d2\\u6740\\u5217\\u8868\\n\\/pages\\/pink-list\\/index=>\\u62fc\\u56e2\\u5217\\u8868\\u9875\\n\\/pages\\/cut-list\\/cut-list?id=123=>\\u780d\\u4ef7\\u5217\\u8868\\n\\/pages\\/productSort\\/productSort=>\\u5206\\u7c7b\\u9875\\u9762\\n\\/pages\\/address\\/address=>\\u5730\\u5740\\u5217\\u8868\\n\\/pages\\/cash\\/cash=>\\u63d0\\u73b0\\u9875\\u9762\\n\\/pages\\/extension\\/extension=>\\u63a8\\u5e7f\\u7edf\\u8ba1\\n\\/pages\\/main\\/main=>\\u8d26\\u6237\\u91d1\\u989d\\n\\/pages\\/collect\\/collect=>\\u6211\\u7684\\u6536\\u85cf\\n\\/pages\\/promotion-card\\/promotion-card=>\\u63a8\\u5e7f\\u4e8c\\u7ef4\\u7801\\u9875\\u9762\\n\\/pages\\/buycar\\/buycar=>\\u8d2d\\u7269\\u8f66\\u9875\\u9762\\n\\/pages\\/news-list\\/news-list=>\\u6d88\\u606f\\u5217\\u8868\\u9875\\n\\/pages\\/orders-list\\/orders-list=>\\u8ba2\\u5355\\u5217\\u8868\\u9875\\u9762\\n\\/pages\\/new-list\\/new-list=>\\u6587\\u7ae0\\u5217\\u8868\\u9875\"},{\"name\":\"\\u5e95\\u90e8\\u83dc\\u5355\",\"title\":\"show\",\"type\":\"radio\",\"param\":\"1=>\\u662f\\n0=>\\u5426\"}]'),
(51, '首页活动区域图片', '小程序', 'routine_home_activity', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u6807\\u9898\",\"title\":\"title\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u7b80\\u4ecb\",\"title\":\"info\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"link\",\"type\":\"select\",\"param\":\"\\/pages\\/activity\\/goods_seckill\\/index=>\\u79d2\\u6740\\u5217\\u8868\\n\\/pages\\/activity\\/goods_bargain\\/index=>\\u780d\\u4ef7\\u5217\\u8868\\n\\/pages\\/activity\\/goods_combination\\/index=>\\u62fc\\u56e2\\u5217\\u8868\"}]'),
(52, '首页精品推荐benner图', '小程序', 'routine_home_bast_banner', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"img\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u63cf\\u8ff0\",\"title\":\"comment\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"link\",\"type\":\"input\",\"param\":\"\"}]'),
(53, '订单详情状态图', '订单详情状态图', 'order_details_images', '[{\"name\":\"\\u8ba2\\u5355\\u72b6\\u6001\",\"title\":\"order_status\",\"type\":\"select\",\"param\":\"0=>\\u672a\\u652f\\u4ed8\\n1=>\\u5f85\\u53d1\\u8d27\\n2=>\\u5f85\\u6536\\u8d27\\n3=>\\u5f85\\u8bc4\\u4ef7\\n4=>\\u5df2\\u5b8c\\u6210\"},{\"name\":\"\\u56fe\\u6807\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"}]'),
(54, '个人中心菜单', '个人中心菜单', 'routine_my_menus', '[{\"name\":\"\\u83dc\\u5355\\u540d\",\"title\":\"name\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u56fe\\u6807\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u8df3\\u8f6c\\u8def\\u5f84\",\"title\":\"url\",\"type\":\"select\",\"param\":\"\\/pages\\/user_address_list\\/index=>\\u5730\\u5740\\u7ba1\\u7406\\n\\/pages\\/user_vip\\/index=>\\u4f1a\\u5458\\u4e2d\\u5fc3\\n\\/pages\\/activity\\/user_goods_bargain_list\\/index=>\\u780d\\u4ef7\\u8bb0\\u5f55\\n\\/pages\\/user_spread_user\\/index=>\\u63a8\\u5e7f\\u4e2d\\u5fc3\\n\\/pages\\/user_money\\/index=>\\u6211\\u7684\\u4f59\\u989d\\n\\/pages\\/user_goods_collection\\/index=>\\u6211\\u7684\\u6536\\u85cf\\n\\/pages\\/user_coupon\\/index=>\\u4f18\\u60e0\\u5238\"}]'),
(55, '签到天数配置', '签到天数配置', 'sign_day_num', '[{\"name\":\"\\u7b2c\\u51e0\\u5929\",\"title\":\"day\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u83b7\\u53d6\\u79ef\\u5206\",\"title\":\"sign_num\",\"type\":\"input\",\"param\":\"\"}]'),
(56, '热门搜索', '小程序', 'routine_hot_search', '[{\"name\":\"\\u6807\\u7b7e\",\"title\":\"title\",\"type\":\"input\",\"param\":\"\"}]'),
(57, '热门榜单推荐图片', '小程序', 'routine_home_hot_banner', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"img\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u63cf\\u8ff0\",\"title\":\"comment\",\"type\":\"input\",\"param\":\"\"}]'),
(58, '首发新品推荐图片', '小程序', 'routine_home_new_banner', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"img\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u63cf\\u8ff0\",\"title\":\"comment\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"link\",\"type\":\"input\",\"param\":\"\"}]'),
(59, '促销单品推荐图片', '小程序', 'routine_home_benefit_banner', '[{\"name\":\"\\u56fe\\u7247\",\"title\":\"img\",\"type\":\"upload\",\"param\":\"\"},{\"name\":\"\\u63cf\\u8ff0\",\"title\":\"comment\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u94fe\\u63a5\",\"title\":\"link\",\"type\":\"input\",\"param\":\"\"}]'),
(60, '分享海报', '小程序', 'routine_spread_banner', '[{\"name\":\"\\u540d\\u79f0\",\"title\":\"title\",\"type\":\"input\",\"param\":\"\"},{\"name\":\"\\u80cc\\u666f\\u56fe\",\"title\":\"pic\",\"type\":\"upload\",\"param\":\"\"}]'),
(61, '全站搜索', '小程序', 'all_hot_search', '[{\"name\":\"\\u6807\\u7b7e\",\"title\":\"title\",\"type\":\"input\",\"param\":\"\"}]');

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_group_data`
--

CREATE TABLE `osx_system_group_data` (
  `id` int(11) NOT NULL COMMENT '组合数据详情ID',
  `gid` int(11) NOT NULL COMMENT '对应的数据组id',
  `value` text NOT NULL COMMENT '数据组对应的数据值（json数据）',
  `add_time` int(10) NOT NULL COMMENT '添加数据时间',
  `sort` int(11) NOT NULL COMMENT '数据排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态（1：开启；2：关闭；）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组合数据详情表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_group_data`
--

INSERT INTO `osx_system_group_data` (`id`, `gid`, `value`, `add_time`, `sort`, `status`) VALUES
(52, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u79ef\\u5206\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/integral.html\"}}', 1513846430, 1, 1),
(53, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u4f18\\u60e0\\u5238\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/coupon.html\"}}', 1513846448, 1, 1),
(56, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u5df2\\u6536\\u85cf\\u5546\\u54c1\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/collect.html\"}}', 1513846605, 1, 1),
(57, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u5730\\u5740\\u7ba1\\u7406\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/address.html\"}}', 1513846618, 1, 1),
(67, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u804a\\u5929\\u8bb0\\u5f55\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/service\\/service_new.html\"}}', 1515570261, 1, 1),
(72, 35, '{\"name\":{\"type\":\"input\",\"value\":\"\\u780d\\u4ef7\"},\"url\":{\"type\":\"input\",\"value\":\"\\/wap\\/store\\/cut_list.html\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"}}', 1515985426, 1, 1),
(73, 35, '{\"name\":{\"type\":\"input\",\"value\":\"\\u9886\\u5238\"},\"url\":{\"type\":\"input\",\"value\":\"\\/wap\\/store\\/issue_coupon.html\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"}}', 1515985435, 1, 1),
(74, 35, '{\"name\":{\"type\":\"input\",\"value\":\"\\u62fc\\u56e2\"},\"url\":{\"type\":\"input\",\"value\":\"\\/wap\\/store\\/combination.html\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"}}', 1515985441, 1, 1),
(80, 36, '{\"info\":{\"type\":\"input\",\"value\":\"opensnsx\\u7535\\u5546\\u7cfb\\u7edf V 2.6 \\u5373\\u5c06\\u4e0a\\u7ebf\\uff01\"},\"url\":{\"type\":\"input\",\"value\":\"#\"}}', 1515985907, 1, 1),
(81, 36, '{\"info\":{\"type\":\"input\",\"value\":\"opensnsx\\u5546\\u57ce V 2.6 \\u5c0f\\u7a0b\\u5e8f\\u516c\\u4f17\\u53f7\\u6570\\u636e\\u540c\\u6b65\\uff01\"},\"url\":{\"type\":\"input\",\"value\":\"#\"}}', 1515985918, 1, 1),
(84, 34, '{\"title\":{\"type\":\"input\",\"value\":\"banner1\"},\"url\":{\"type\":\"input\",\"value\":\"#\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1522135667, 2, 1),
(86, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u8054\\u7cfb\\u5ba2\\u670d\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/index\\/about.html\"}}', 1522310836, 1, 1),
(87, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u4f59\\u989d\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/balance.html\"}}', 1525330614, 1, 1),
(89, 38, '{\"banner\":{\"type\":\"upload\",\"value\":\"\"}}', 1527153599, 1, 1),
(91, 37, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"\\u79d2\\u6740\\u5217\\u8868\\u9876\\u90e8baaner\"}}', 1528688012, 1, 1),
(92, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u63a8\\u5e7f\\u4f63\\u91d1\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/user_pro.html\"}}', 1530688244, 1, 1),
(99, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u5546\\u54c1\\u5206\\u7c7b\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/goods_cate\\/goods_cate\"},\"show\":{\"type\":\"radio\",\"value\":\"1\"}}', 1533721963, 8, 1),
(100, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u9886\\u4f18\\u60e0\\u5238\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_get_coupon\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1533722009, 7, 1),
(101, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u884c\\u4e1a\\u8d44\\u8baf\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/news_list\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1533722037, 6, 1),
(102, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u8981\\u7b7e\\u5230\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_sgin\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1533722063, 5, 1),
(104, 48, '{\"name\":{\"type\":\"input\",\"value\":\"banenr2\"},\"url\":{\"type\":\"input\",\"value\":\"\\/pages\\/pink-list\\/index?id=2\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1533722286, 10, 1),
(105, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u6536\\u85cf\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_goods_collection\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1533797064, 5, 1),
(106, 32, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u780d\\u4ef7\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/wap\\/my\\/user_cut.html\"}}', 1533889033, 1, 1),
(108, 35, '{\"name\":{\"type\":\"input\",\"value\":\"\\u79d2\\u6740\"},\"url\":{\"type\":\"input\",\"value\":\"\\/wap\\/store\\/seckill_index.html\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"}}', 1541054595, 1, 1),
(109, 35, '{\"name\":{\"type\":\"input\",\"value\":\"\\u7b7e\\u5230\"},\"url\":{\"type\":\"input\",\"value\":\"\\/wap\\/my\\/sign_in.html\"},\"icon\":{\"type\":\"upload\",\"value\":\"\"}}', 1541054641, 1, 1),
(113, 49, '{\"time\":{\"type\":\"input\",\"value\":\"5\"},\"continued\":{\"type\":\"input\",\"value\":\"3\"}}', 1552443280, 1, 1),
(114, 49, '{\"time\":{\"type\":\"input\",\"value\":\"8\"},\"continued\":{\"type\":\"input\",\"value\":\"4\"}}', 1552443293, 1, 1),
(115, 49, '{\"time\":{\"type\":\"input\",\"value\":\"12\"},\"continued\":{\"type\":\"input\",\"value\":\"4\"}}', 1552443304, 1, 1),
(116, 49, '{\"time\":{\"type\":\"input\",\"value\":\"16\"},\"continued\":{\"type\":\"input\",\"value\":\"4\"}}', 1552481140, 1, 1),
(117, 49, '{\"time\":{\"type\":\"input\",\"value\":\"20\"},\"continued\":{\"type\":\"input\",\"value\":\"2\"}}', 1552481146, 1, 1),
(118, 49, '{\"time\":{\"type\":\"input\",\"value\":\"22\"},\"continued\":{\"type\":\"input\",\"value\":\"2\"}}', 1552481151, 1, 1),
(119, 49, '{\"time\":{\"type\":\"input\",\"value\":\"0\"},\"continued\":{\"type\":\"input\",\"value\":\"5\"}}', 1552481157, 1, 1),
(121, 50, '{\"info\":{\"type\":\"input\",\"value\":\"opensnsx\\u7535\\u5546\\u7cfb\\u7edf V 2.6 \\u5373\\u5c06\\u4e0a\\u7ebf\\uff01\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/index\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"\\u662f\"}}', 1552611989, 1, 1),
(122, 50, '{\"info\":{\"type\":\"input\",\"value\":\"opensnsx\\u7535\\u5546\\u7cfb\\u7edf V 2.6 \\u5373\\u5c06\\u4e0a\\u7ebf\\uff01\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/miao-list\\/miao-list\"},\"show\":{\"type\":\"radio\",\"value\":\"\\u5426\"}}', 1552612003, 1, 1),
(123, 50, '{\"info\":{\"type\":\"input\",\"value\":\"opensnsx\\u7535\\u5546\\u7cfb\\u7edf V 2.6 \\u5373\\u5c06\\u4e0a\\u7ebf\\uff01\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/index\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"\\u662f\"}}', 1552613047, 1, 1),
(124, 51, '{\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"title\":{\"type\":\"input\",\"value\":\"\\u4e00\\u8d77\\u6765\\u62fc\\u56e2\"},\"info\":{\"type\":\"input\",\"value\":\"\\u4f18\\u60e0\\u591a\\u591a\"},\"link\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_combination\\/index\"}}', 1552620002, 3, 1),
(125, 51, '{\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"title\":{\"type\":\"input\",\"value\":\"\\u79d2\\u6740\\u4e13\\u533a\"},\"info\":{\"type\":\"input\",\"value\":\"\\u65b0\\u80fd\\u6e90\\u6c7d\\u8f66\\u706b\\u70ed\\u9500\\u552e\"},\"link\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\"}}', 1552620022, 2, 1),
(126, 51, '{\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"title\":{\"type\":\"input\",\"value\":\"\\u780d\\u4ef7\\u6d3b\\u52a8\"},\"info\":{\"type\":\"input\",\"value\":\"\\u547c\\u670b\\u5524\\u53cb\\u6765\\u780d\\u4ef7~~~\"},\"link\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_bargain\\/index\"}}', 1552620041, 1, 1),
(127, 52, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"\\u7cbe\\u54c1\\u63a8\\u8350750*282\"},\"link\":{\"type\":\"input\",\"value\":\"\\/pages\\/first-new-product\\/index\"}}', 1552633893, 1, 1),
(128, 52, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"\\u7cbe\\u54c1\\u63a8\\u8350750*282\"},\"link\":{\"type\":\"input\",\"value\":\"\\/pages\\/first-new-product\\/index\"}}', 1552633912, 1, 1),
(135, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u4f1a\\u5458\\u4e2d\\u5fc3\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_vip\\/index\"}}', 1553779918, 1, 1),
(136, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u780d\\u4ef7\\u8bb0\\u5f55\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/user_goods_bargain_list\\/index\"}}', 1553779935, 1, 2),
(137, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u63a8\\u5e7f\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_spread_user\\/index\"}}', 1553779950, 1, 1),
(138, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u4f59\\u989d\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_money\\/index\"}}', 1553779973, 1, 1),
(139, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u5730\\u5740\\u4fe1\\u606f\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_address_list\\/index\"}}', 1553779988, 1, 1),
(140, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u6211\\u7684\\u6536\\u85cf\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_goods_collection\\/index\"}}', 1553780003, 1, 1),
(141, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u4f18\\u60e0\\u5238\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/user_coupon\\/index\"}}', 1553780017, 1, 1),
(142, 53, '{\"order_status\":{\"type\":\"select\",\"value\":\"0\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553780202, 1, 1),
(143, 53, '{\"order_status\":{\"type\":\"select\",\"value\":\"1\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553780210, 1, 1),
(144, 53, '{\"order_status\":{\"type\":\"select\",\"value\":\"2\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553780221, 1, 1),
(145, 53, '{\"order_status\":{\"type\":\"select\",\"value\":\"3\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553780230, 1, 1),
(146, 53, '{\"order_status\":{\"type\":\"select\",\"value\":\"4\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553780237, 1, 1),
(147, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u4e00\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"10\"}}', 1553780276, 100, 1),
(148, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u4e8c\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"20\"}}', 1553780292, 99, 1),
(149, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u4e09\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"30\"}}', 1553780303, 90, 1),
(150, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u56db\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"40\"}}', 1553780334, 60, 1),
(151, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u4e94\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"50\"}}', 1553780351, 50, 1),
(152, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u7b2c\\u516d\\u5929\"},\"sign_num\":{\"type\":\"input\",\"value\":\"60\"}}', 1553780364, 40, 1),
(153, 55, '{\"day\":{\"type\":\"input\",\"value\":\"\\u5956\\u52b1\"},\"sign_num\":{\"type\":\"input\",\"value\":\"110\"}}', 1553780389, 10, 1),
(154, 57, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"1\"}}', 1553780856, 1, 1),
(155, 58, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"1\"},\"link\":{\"type\":\"input\",\"value\":\"#\"}}', 1553780869, 1, 1),
(156, 59, '{\"img\":{\"type\":\"upload\",\"value\":\"\"},\"comment\":{\"type\":\"input\",\"value\":\"1\"},\"link\":{\"type\":\"input\",\"value\":\"#\"}}', 1553780883, 1, 1),
(157, 56, '{\"title\":{\"type\":\"input\",\"value\":\"\\u5438\\u5c18\\u5668\"}}', 1553782153, 1, 1),
(158, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u62fc\\u56e2\\u6d3b\\u52a8\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_combination\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1553849878, 3, 1),
(159, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u79d2\\u6740\\u6d3b\\u52a8\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_seckill\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1553849905, 2, 1),
(160, 47, '{\"name\":{\"type\":\"input\",\"value\":\"\\u780d\\u4ef7\\u6d3b\\u52a8\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/goods_bargain\\/index\"},\"show\":{\"type\":\"radio\",\"value\":\"2\"}}', 1553850093, 1, 1),
(161, 60, '{\"title\":{\"type\":\"input\",\"value\":\"\\u5206\\u4eab\\u6d77\\u62a5\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1553866489, 1, 1),
(162, 54, '{\"name\":{\"type\":\"input\",\"value\":\"\\u780d\\u4ef7\\u8bb0\\u5f55\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"},\"url\":{\"type\":\"select\",\"value\":\"\\/pages\\/activity\\/user_goods_bargain_list\\/index\"}}', 1553866805, 1, 1),
(163, 56, '{\"title\":{\"type\":\"input\",\"value\":\"\\u52a0\\u6e7f\\u5668\"}}', 1553869694, 1, 1),
(165, 56, '{\"title\":{\"type\":\"input\",\"value\":\"\\u70ed\\u6c34\\u5668\"}}', 1553869710, 1, 1),
(167, 60, '{\"title\":{\"type\":\"input\",\"value\":\"1\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1555063900, 1, 1),
(168, 60, '{\"title\":{\"type\":\"input\",\"value\":\"2\"},\"pic\":{\"type\":\"upload\",\"value\":\"\"}}', 1555067377, 1, 1),
(16900, 61, '{\"title\":{\"type\":\"input\",\"value\":\"\\u517b\\u751f\"}}', 1553782153, 1, 1),
(16901, 61, '{\"title\":{\"type\":\"input\",\"value\":\"\\u5065\\u5eb7\"}}', 1572585854, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_jifen`
--

CREATE TABLE `osx_system_jifen` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT '第几天',
  `expone` int(11) DEFAULT NULL,
  `expmax` int(11) DEFAULT NULL,
  `flyone` int(11) DEFAULT NULL,
  `flymax` int(11) DEFAULT NULL,
  `gongone` int(11) DEFAULT NULL,
  `gongmax` int(11) DEFAULT NULL,
  `buyone` int(11) DEFAULT NULL,
  `buymax` int(11) DEFAULT NULL,
  `firstone` int(11) DEFAULT NULL,
  `firstmax` int(11) DEFAULT NULL,
  `twoone` int(11) DEFAULT NULL,
  `twomax` int(11) DEFAULT NULL,
  `threeone` int(11) DEFAULT NULL,
  `is_del` int(11) DEFAULT '0',
  `threemax` int(11) DEFAULT NULL,
  `fourone` int(11) DEFAULT NULL,
  `fourmax` int(11) DEFAULT NULL,
  `fiveone` int(11) DEFAULT NULL,
  `fivemax` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_jifen`
--

INSERT INTO `osx_system_jifen` (`id`, `name`, `expone`, `expmax`, `flyone`, `flymax`, `gongone`, `gongmax`, `buyone`, `buymax`, `firstone`, `firstmax`, `twoone`, `twomax`, `threeone`, `is_del`, `threemax`, `fourone`, `fourmax`, `fiveone`, `fivemax`) VALUES
(1, '第1天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2),
(2, '第2天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 6, 2, 6, 2, 0, 2, 2, 2, 2, 2),
(3, '第3天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2),
(4, '第4天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2),
(5, '第5天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2),
(6, '第6天', 1, 1, 1, 1, 0, 0, 1, 1, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2),
(7, '第7天', 2, 2, 2, 2, 0, 0, 2, 2, 2, 2, 2, 2, 2, 0, 2, 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_log`
--

CREATE TABLE `osx_system_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '管理员操作记录ID',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '管理员id',
  `admin_name` varchar(64) NOT NULL COMMENT '管理员姓名',
  `path` varchar(128) NOT NULL COMMENT '链接',
  `page` varchar(64) NOT NULL COMMENT '行为',
  `method` varchar(12) NOT NULL COMMENT '访问类型',
  `ip` varchar(16) NOT NULL COMMENT '登录IP',
  `type` varchar(32) NOT NULL COMMENT '类型',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '操作时间',
  `merchant_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商户id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员操作记录表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_log`
--

INSERT INTO `osx_system_log` (`id`, `admin_id`, `admin_name`, `path`, `page`, `method`, `ip`, `type`, `add_time`, `merchant_id`) VALUES
(1, 1, 'admin', 'admin/community.index/get_census_message/', '未知', 'GET', '111.3.11.173', 'system', 1587611085, 0),
(2, 1, 'admin', 'admin/community.index/get_census/', '未知', 'POST', '111.3.11.173', 'system', 1587611085, 0),
(3, 1, 'admin', 'admin/community.index/census_rank/', '未知', 'POST', '111.3.11.173', 'system', 1587611086, 0),
(4, 1, 'admin', 'admin/com.comforum/get_platform_config/', '未知', 'GET', '127.0.0.1', 'system', 1599640707, 0),
(5, 1, 'admin', 'admin/community.index/census_rank/', '未知', 'POST', '127.0.0.1', 'system', 1599640707, 0),
(6, 1, 'admin', 'admin/community.index/get_census_message/', '未知', 'GET', '127.0.0.1', 'system', 1599640708, 0),
(7, 1, 'admin', 'admin/community.index/get_census/', '未知', 'POST', '127.0.0.1', 'system', 1599640709, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_mall_grade`
--

CREATE TABLE `osx_system_mall_grade` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL COMMENT '消费金额上限',
  `zhe` float(8,2) DEFAULT NULL COMMENT '享受折扣',
  `icon` varchar(125) DEFAULT NULL COMMENT '图标',
  `image` varchar(125) DEFAULT NULL COMMENT '大图标',
  `explain` varchar(125) DEFAULT NULL COMMENT '等级说明',
  `is_del` int(11) DEFAULT '0' COMMENT '0正常，1删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_mall_grade`
--

INSERT INTO `osx_system_mall_grade` (`id`, `name`, `experience`, `zhe`, `icon`, `image`, `explain`, `is_del`) VALUES
(1, '初级用户', 10000, 0.00, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77015dcc47.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/11/20/5dd4e3826cbd3.png', '高级用户', 0),
(2, '高级用户', 200000, 0.00, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7702371e6d.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/11/20/5dd4e3b7601b2.png', '高级用户', 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_menus`
--

CREATE TABLE `osx_system_menus` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '菜单ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级id',
  `icon` varchar(16) NOT NULL COMMENT '图标',
  `menu_name` varchar(32) NOT NULL DEFAULT '' COMMENT '按钮名',
  `module` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '模块名',
  `controller` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '控制器',
  `action` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '方法名',
  `params` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '[]' COMMENT '参数',
  `sort` tinyint(3) NOT NULL DEFAULT '1' COMMENT '排序',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示',
  `access` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '子管理员是否可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_menus`
--

INSERT INTO `osx_system_menus` (`id`, `pid`, `icon`, `menu_name`, `module`, `controller`, `action`, `params`, `sort`, `is_show`, `access`) VALUES
(1, 289, '', '系统设置', 'admin', 'setting.systemConfig', 'index', '[]', 101, 1, 1),
(2, 153, '', '权限规则', 'admin', 'setting.systemMenus', 'index', '[]', 7, 1, 1),
(4, 153, '', '管理员列表', 'admin', 'setting.systemAdmin', 'index', '[]', 9, 1, 1),
(7, 467, '', '配置分类', 'admin', 'setting.systemConfigTab', 'index', '[]', 1, 1, 1),
(8, 153, '', '身份管理', 'admin', 'setting.systemRole', 'index', '[]', 10, 1, 1),
(9, 467, '', '组合数据', 'admin', 'setting.systemGroup', 'index', '[]', 1, 1, 1),
(11, 600, '', '公众号', 'admin', 'wechat', 'index', '[]', 91, 1, 1),
(12, 354, '', '微信关注回复', 'admin', 'wechat.reply', 'index', '{\"key\":\"subscribe\",\"title\":\"\\u7f16\\u8f91\\u65e0\\u914d\\u7f6e\\u9ed8\\u8ba4\\u56de\\u590d\"}', 86, 1, 1),
(17, 11, '', '微信菜单', 'admin', 'wechat.menus', 'index', '[]', 99, 1, 1),
(19, 11, '', '图文管理', 'admin', 'wechat.wechatNewsCategory', 'index', '[]', 60, 0, 1),
(21, 0, 'iconwrench', '维护', 'admin', 'system', 'index', '[]', 0, 1, 1),
(23, 0, 'iconshop', '商城', 'admin', 'store', 'index', '[]', 99, 1, 1),
(24, 23, '', '商品管理', 'admin', 'store.storeProduct', 'index', '{\"type\":\"1\"}', 100, 1, 1),
(25, 23, '', '商品分类', 'admin', 'store.storeCategory', 'index', '[]', 99, 1, 1),
(26, 23, '', '订单管理', 'admin', 'order.storeOrder', 'index', '[]', 98, 1, 1),
(30, 354, '', '关键字回复', 'admin', 'wechat.reply', 'keyword', '[]', 85, 1, 1),
(31, 354, '', '无效关键词回复', 'admin', 'wechat.reply', 'index', '{\"key\":\"default\",\"title\":\"\\u7f16\\u8f91\\u65e0\\u6548\\u5173\\u952e\\u5b57\\u9ed8\\u8ba4\\u56de\\u590d\"}', 84, 1, 1),
(33, 284, '', '附加权限', 'admin', 'article.articleCategory', '', '[]', 0, 0, 1),
(34, 33, '', '添加文章分类', 'admin', 'article.articleCategory', 'create', '[]', 0, 0, 1),
(35, 33, '', '编辑文章分类', 'admin', 'article.articleCategory', 'edit', '[]', 0, 0, 1),
(36, 33, '', '删除文章分类', 'admin', 'article.articleCategory', 'delete', '[]', 0, 0, 1),
(37, 31, '', '附加权限', 'admin', 'wechat.reply', '', '[]', 0, 0, 1),
(38, 283, '', '附加权限', 'admin', 'article.article', '', '[]', 0, 0, 1),
(39, 38, '', '添加文章', 'admin', 'article. article', 'create', '[]', 0, 0, 1),
(40, 38, '', '编辑文章', 'admin', 'article. article', 'add_new', '[]', 0, 0, 1),
(41, 38, '', '删除文章', 'admin', 'article. article', 'delete', '[]', 0, 0, 1),
(42, 19, '', '附加权限', 'admin', 'wechat.wechatNewsCategory', '', '[]', 0, 0, 1),
(43, 42, '', '添加图文消息', 'admin', 'wechat.wechatNewsCategory', 'create', '[]', 0, 0, 1),
(44, 42, '', '编辑图文消息', 'admin', 'wechat.wechatNewsCategory', 'edit', '[]', 0, 0, 1),
(45, 42, '', '删除图文消息', 'admin', 'wechat.wechatNewsCategory', 'delete', '[]', 0, 0, 1),
(46, 7, '', '配置分类附加权限', 'admin', 'setting.systemConfigTab', '', '[]', 0, 0, 1),
(47, 46, '', '添加配置分类', 'admin', 'setting.systemConfigTab', 'create', '[]', 0, 0, 1),
(48, 117, '', '添加配置', 'admin', 'setting.systemConfig', 'create', '[]', 0, 0, 1),
(49, 46, '', '编辑配置分类', 'admin', 'setting.systemConfigTab', 'edit', '[]', 0, 0, 1),
(50, 46, '', '删除配置分类', 'admin', 'setting.systemConfigTab', 'delete', '[]', 0, 0, 1),
(51, 46, '', '查看子字段', 'admin', 'system.systemConfigTab', 'sonConfigTab', '[]', 0, 0, 1),
(52, 9, '', '组合数据附加权限', 'admin', 'setting.systemGroup', '', '[]', 0, 0, 1),
(53, 468, '', '添加数据', 'admin', 'setting.systemGroupData', 'create', '[]', 0, 0, 1),
(54, 468, '', '编辑数据', 'admin', 'setting.systemGroupData', 'edit', '[]', 0, 0, 1),
(55, 468, '', '删除数据', 'admin', 'setting.systemGroupData', 'delete', '[]', 0, 0, 1),
(56, 468, '', '数据列表', 'admin', 'setting.systemGroupData', 'index', '[]', 0, 0, 1),
(57, 52, '', '添加数据组', 'admin', 'setting.systemGroup', 'create', '[]', 0, 0, 1),
(58, 52, '', '删除数据组', 'admin', 'setting.systemGroup', 'delete', '[]', 0, 0, 1),
(59, 4, '', '管理员列表附加权限', 'admin', 'setting.systemAdmin', '', '[]', 0, 0, 1),
(60, 59, '', '添加管理员', 'admin', 'setting.systemAdmin', 'create', '[]', 0, 0, 1),
(61, 59, '', '编辑管理员', 'admin', 'setting.systemAdmin', 'edit', '[]', 0, 0, 1),
(62, 59, '', '删除管理员', 'admin', 'setting.systemAdmin', 'delete', '[]', 0, 0, 1),
(63, 8, '', '身份管理附加权限', 'admin', 'setting.systemRole', '', '[]', 0, 0, 1),
(64, 63, '', '添加身份', 'admin', 'setting.systemRole', 'create', '[]', 0, 0, 1),
(65, 63, '', '修改身份', 'admin', 'setting.systemRole', 'edit', '[]', 0, 0, 1),
(66, 63, '', '删除身份', 'admin', 'setting.systemRole', 'delete', '[]', 0, 0, 1),
(67, 8, '', '身份管理展示页', 'admin', 'setting.systemRole', 'index', '[]', 0, 0, 1),
(68, 4, '', '管理员列表展示页', 'admin', 'setting.systemAdmin', 'index', '[]', 0, 0, 1),
(69, 7, '', '配置分类展示页', 'admin', 'setting.systemConfigTab', 'index', '[]', 0, 0, 1),
(70, 9, '', '组合数据展示页', 'admin', 'setting.systemGroup', 'index', '[]', 0, 0, 1),
(71, 284, '', '文章分类管理展示页', 'admin', 'article.articleCategory', 'index', '[]', 0, 0, 1),
(72, 283, '', '文章管理展示页', 'admin', 'article.article', 'index', '[]', 0, 0, 1),
(73, 19, '', '图文消息展示页', 'admin', 'wechat.wechatNewsCategory', 'index', '[]', 0, 0, 1),
(74, 2, '', '菜单管理附加权限', 'admin', 'setting.systemMenus', '', '[]', 0, 0, 1),
(75, 74, '', '添加菜单', 'admin', 'setting.systemMenus', 'create', '[]', 0, 0, 1),
(76, 74, '', '编辑菜单', 'admin', 'setting.systemMenus', 'edit', '[]', 0, 0, 1),
(77, 74, '', '删除菜单', 'admin', 'setting.systemMenus', 'delete', '[]', 0, 0, 1),
(78, 2, '', '菜单管理展示页', 'admin', 'setting.systemMenus', 'index', '[]', 0, 0, 1),
(82, 11, '', '微信用户管理', 'admin', 'user', 'list', '[]', 5, 0, 1),
(84, 82, '', '用户标签', 'admin', 'wechat.wechatUser', 'tag', '[]', 0, 1, 1),
(89, 30, '', '关键字回复附加权限', 'admin', 'wechat.reply', '', '[]', 0, 0, 1),
(90, 89, '', '添加关键字', 'admin', 'wechat.reply', 'add_keyword', '[]', 0, 0, 1),
(91, 89, '', '修改关键字', 'admin', 'wechat.reply', 'info_keyword', '[]', 0, 0, 1),
(92, 89, '', '删除关键字', 'admin', 'wechat.reply', 'delete', '[]', 0, 0, 1),
(93, 30, '', '关键字回复展示页', 'admin', 'wechat.reply', 'keyword', '[]', 0, 0, 1),
(94, 31, '', '无效关键词回复展示页', 'admin', 'wechat.reply', 'index', '[]', 0, 0, 1),
(95, 31, '', '无效关键词回复附加权限', 'admin', 'wechat.reply', '', '[]', 0, 0, 1),
(96, 95, '', '无效关键词回复提交按钮', 'admin', 'wechat.reply', 'save', '{\"key\":\"default\",\"title\":\"编辑无效关键字默认回复\"}', 0, 0, 1),
(97, 12, '', '微信关注回复展示页', 'admin', 'wechat.reply', 'index', '[]', 0, 0, 1),
(98, 12, '', '微信关注回复附加权限', 'admin', 'wechat.reply', '', '[]', 0, 0, 1),
(99, 98, '', '微信关注回复提交按钮', 'admin', 'wechat.reply', 'save', '{\"key\":\"subscribe\",\"title\":\"编辑无配置默认回复\"}', 0, 0, 1),
(100, 74, '', '添加提交菜单', 'admin', 'setting.systemMenus', 'save', '[]', 0, 0, 1),
(101, 74, '', '编辑提交菜单', 'admin', 'setting.systemMenus', 'update', '[]', 0, 0, 1),
(102, 59, '', '提交添加管理员', 'admin', 'setting.systemAdmin', 'save', '[]', 0, 0, 1),
(103, 59, '', '提交修改管理员', 'admin', 'setting.systemAdmin', 'update', '[]', 0, 0, 1),
(104, 63, '', '提交添加身份', 'admin', 'setting.systemRole', 'save', '[]', 0, 0, 1),
(105, 63, '', '提交修改身份', 'admin', 'setting.systemRole', 'update', '[]', 0, 0, 1),
(106, 46, '', '提交添加配置分类', 'admin', 'setting.systemConfigTab', 'save', '[]', 0, 0, 1),
(107, 46, '', '提交修改配置分类', 'admin', 'setting.systemConfigTab', 'update', '[]', 0, 0, 1),
(108, 117, '', '提交添加配置列表', 'admin', 'setting.systemConfig', 'save', '[]', 0, 0, 1),
(109, 52, '', '提交添加数据组', 'admin', 'setting.systemGroup', 'save', '[]', 0, 0, 1),
(110, 52, '', '提交修改数据组', 'admin', 'setting.systemGroup', 'update', '[]', 0, 0, 1),
(111, 468, '', '提交添加数据', 'admin', 'setting.systemGroupData', 'save', '[]', 0, 0, 1),
(112, 468, '', '提交修改数据', 'admin', 'setting.systemGroupData', 'update', '[]', 0, 0, 1),
(113, 33, '', '提交添加文章分类', 'admin', 'article.articleCategory', 'save', '[]', 0, 0, 1),
(114, 33, '', '提交添加文章分类', 'admin', 'article.articleCategory', 'update', '[]', 0, 0, 1),
(115, 42, '', '提交添加图文消息', 'admin', 'wechat.wechatNewsCategory', 'save', '[]', 0, 0, 1),
(116, 42, '', '提交编辑图文消息', 'admin', 'wechat.wechatNewsCategory', 'update', '[]', 0, 0, 1),
(117, 1, '', '配置列表附加权限', 'admin', 'setting.systemConfig', '', '[]', 0, 0, 1),
(118, 1, '', '配置列表展示页', 'admin', 'setting.systemConfig', 'index', '[]', 0, 0, 1),
(119, 117, '', '提交保存配置列表', 'admin', 'setting.systemConfig', 'save_basics', '[]', 0, 0, 1),
(123, 89, '', '提交添加关键字', 'admin', 'wechat.reply', 'save_keyword', '{\"dis\":\"1\"}', 0, 0, 1),
(124, 89, '', '提交修改关键字', 'admin', 'wechat.reply', 'save_keyword', '{\"dis\":\"2\"}', 0, 0, 1),
(126, 17, '', '微信菜单展示页', 'admin', 'wechat.menus', 'index', '[]', 0, 0, 1),
(127, 17, '', '微信菜单附加权限', 'admin', 'wechat.menus', '', '[]', 0, 0, 1),
(128, 127, '', '提交微信菜单按钮', 'admin', 'wechat.menus', 'save', '{\"dis\":\"1\"}', 0, 0, 1),
(129, 82, '', '用户行为纪录', 'admin', 'wechat.wechatMessage', 'index', '[]', 0, 1, 1),
(130, 469, '', '系统日志', 'admin', 'system.systemLog', 'index', '[]', 5, 1, 1),
(131, 130, '', '管理员操作记录展示页', 'admin', 'system.systemLog', 'index', '[]', 0, 0, 1),
(132, 129, '', '微信用户行为纪录展示页', 'admin', 'wechat.wechatMessage', 'index', '[]', 0, 0, 1),
(133, 82, '', '微信用户', 'admin', 'wechat.wechatUser', 'index', '[]', 1, 1, 1),
(134, 133, '', '微信用户展示页', 'admin', 'wechat.wechatUser', 'index', '[]', 0, 0, 1),
(137, 135, '', '添加通知模板', 'admin', 'system.systemNotice', 'create', '[]', 0, 0, 1),
(138, 135, '', '编辑通知模板', 'admin', 'system.systemNotice', 'edit', '[]', 0, 0, 1),
(139, 135, '', '删除辑通知模板', 'admin', 'system.systemNotice', 'delete', '[]', 0, 0, 1),
(140, 135, '', '提交编辑辑通知模板', 'admin', 'system.systemNotice', 'update', '[]', 0, 0, 1),
(141, 135, '', '提交添加辑通知模板', 'admin', 'system.systemNotice', 'save', '[]', 0, 0, 1),
(142, 25, '', '产品分类展示页', 'admin', 'store.storeCategory', 'index', '[]', 0, 0, 1),
(143, 25, '', '产品分类附加权限', 'admin', 'store.storeCategory', '', '[]', 0, 0, 1),
(144, 117, '', '获取配置列表上传文件的名称', 'admin', 'setting.systemConfig', 'getimagename', '[]', 0, 0, 1),
(145, 117, '', '配置列表上传文件', 'admin', 'setting.systemConfig', 'view_upload', '[]', 0, 0, 1),
(146, 24, '', '产品管理展示页', 'admin', 'store.storeProduct', 'index', '[]', 0, 0, 1),
(147, 24, '', '产品管理附加权限', 'admin', 'store.storeProduct', '', '[]', 0, 0, 1),
(148, 23, '', '优惠券管理', '', '', '', '[]', 96, 1, 1),
(149, 148, '', '优惠券', 'admin', 'ump.storeCoupon', 'index', '[]', 5, 1, 1),
(150, 148, '', '领取记录', 'admin', 'ump.storeCouponUser', 'index', '[]', 1, 1, 1),
(151, 0, 'iconteam', '用户', 'admin', 'user', 'index', '[]', 97, 1, 1),
(153, 289, '', '管理权限', 'admin', 'setting.systemAdmin_gl', '', '[]', 97, 1, 1),
(155, 154, '', '商户产品展示页', 'admin', 'store.storeMerchant', 'index', '[]', 0, 0, 1),
(156, 154, '', '商户产品附加权限', 'admin', 'store.storeMerchant', '', '[]', 0, 0, 1),
(158, 157, '', '商户文章管理展示页', 'admin', 'wechat.wechatNews', 'merchantIndex', '[]', 0, 0, 1),
(159, 157, '', '商户文章管理附加权限', 'admin', 'wechat.wechatNews', '', '[]', 0, 0, 1),
(170, 23, '', '评论管理', 'admin', 'store.store_product_reply', 'index', '{\"status\":\"0\"}', 97, 1, 1),
(173, 469, '', '文件校验', 'admin', 'system.systemFile', 'index', '[]', 1, 1, 1),
(174, 11, '', '微信模板消息', 'admin', 'wechat.wechatTemplate', 'index', '[]', 98, 1, 1),
(175, 11, '', '客服管理', 'admin', 'wechat.storeService', 'index', '[]', 70, 0, 1),
(177, 151, '', '用户管理', 'admin', 'user.user', 'index', '[]', 10, 1, 1),
(179, 307, '', '充值记录', 'admin', 'finance.userRecharge', 'index', '[]', 1, 1, 1),
(190, 26, '', '订单管理展示页', 'admin', 'store.storeOrder', 'index', '[]', 0, 0, 1),
(191, 26, '', '订单管理附加权限', 'admin', 'store.storeOrder', '', '[]', 0, 0, 1),
(192, 191, '', '订单管理去发货', 'admin', 'store.storeOrder', 'deliver_goods', '[]', 0, 0, 1),
(193, 191, '', '订单管理备注', 'admin', 'store.storeOrder', 'remark', '[]', 0, 0, 1),
(194, 191, '', '订单管理去送货', 'admin', 'store.storeOrder', 'delivery', '[]', 0, 0, 1),
(195, 191, '', '订单管理已收货', 'admin', 'store.storeOrder', 'take_delivery', '[]', 0, 0, 1),
(196, 191, '', '订单管理退款', 'admin', 'store.storeOrder', 'refund_y', '[]', 0, 0, 1),
(197, 191, '', '订单管理修改订单', 'admin', 'store.storeOrder', 'edit', '[]', 0, 0, 1),
(198, 191, '', '订单管理修改订单提交', 'admin', 'store.storeOrder', 'update', '[]', 0, 0, 1),
(199, 191, '', '订单管理退积分', 'admin', 'store.storeOrder', 'integral_back', '[]', 0, 0, 1),
(200, 191, '', '订单管理退积分提交', 'admin', 'store.storeOrder', 'updateIntegralBack', '[]', 0, 0, 1),
(201, 191, '', '订单管理立即支付', 'admin', 'store.storeOrder', 'offline', '[]', 0, 0, 1),
(202, 191, '', '订单管理拒绝退款原因', 'admin', 'store.storeOrder', 'refund_n', '[]', 0, 0, 1),
(203, 191, '', '订单管理拒绝退款原因提交', 'admin', 'store.storeOrder', 'updateRefundN', '[]', 0, 0, 1),
(204, 191, '', '订单管理修改配送信息', 'admin', 'store.storeOrder', 'distribution', '[]', 0, 0, 1),
(205, 191, '', '订单管理修改配送信息提交', 'admin', 'store.storeOrder', 'updateDistribution', '[]', 0, 0, 1),
(206, 191, '', '订单管理退款提交', 'admin', 'store.storeOrder', 'updateRefundY', '[]', 0, 0, 1),
(207, 191, '', '订单管理去发货提交', 'admin', 'store.storeOrder', 'updateDeliveryGoods', '[]', 0, 0, 1),
(208, 191, '', '订单管理去送货提交', 'admin', 'store.storeOrder', 'updateDelivery', '[]', 0, 0, 1),
(209, 175, '', '客服管理展示页', 'admin', 'store.storeService', 'index', '[]', 0, 0, 1),
(210, 175, '', '客服管理附加权限', 'admin', 'store.storeService', '', '[]', 0, 0, 1),
(211, 210, '', '客服管理添加', 'admin', 'store.storeService', 'create', '[]', 0, 0, 1),
(212, 210, '', '客服管理添加提交', 'admin', 'store.storeService', 'save', '[]', 0, 0, 1),
(213, 210, '', '客服管理编辑', 'admin', 'store.storeService', 'edit', '[]', 0, 0, 1),
(214, 210, '', '客服管理编辑提交', 'admin', 'store.storeService', 'update', '[]', 0, 0, 1),
(215, 210, '', '客服管理删除', 'admin', 'store.storeService', 'delete', '[]', 0, 0, 1),
(216, 179, '', '用户充值记录展示页', 'admin', 'user.userRecharge', 'index', '[]', 0, 0, 1),
(217, 179, '', '用户充值记录附加权限', 'admin', 'user.userRecharge', '', '[]', 0, 0, 1),
(218, 217, '', '用户充值记录退款', 'admin', 'user.userRecharge', 'edit', '[]', 0, 0, 1),
(219, 217, '', '用户充值记录退款提交', 'admin', 'user.userRecharge', 'updaterefundy', '[]', 0, 0, 1),
(220, 180, '', '预售卡管理批量修改预售卡金额', 'admin', 'presell.presellCard', 'batch_price', '[]', 0, 0, 1),
(221, 180, '', '预售卡管理批量修改预售卡金额提交', 'admin', 'presell.presellCard', 'savebatch', '[]', 0, 0, 1),
(222, 210, '', '客服管理聊天记录查询', 'admin', 'store.storeService', 'chat_user', '[]', 0, 0, 1),
(223, 210, '', '客服管理聊天记录查询详情', 'admin', 'store.storeService', 'chat_list', '[]', 0, 0, 1),
(224, 170, '', '评论管理展示页', 'admin', 'store.storeProductReply', 'index', '[]', 0, 0, 1),
(225, 170, '', '评论管理附加权限', 'admin', 'store.storeProductReply', '', '[]', 0, 0, 1),
(226, 225, '', '评论管理回复评论', 'admin', 'store.storeProductReply', 'set_reply', '[]', 0, 0, 1),
(227, 225, '', '评论管理修改回复评论', 'admin', 'store.storeProductReply', 'edit_reply', '[]', 0, 0, 1),
(228, 225, '', '评论管理删除评论', 'admin', 'store.storeProductReply', 'delete', '[]', 0, 0, 1),
(229, 149, '', '优惠券管理展示页', 'admin', 'store.storeCoupon', 'index', '[]', 0, 0, 1),
(230, 149, '', '优惠券管理附加权限', 'admin', 'store.storeCoupon', '', '[]', 0, 0, 1),
(231, 230, '', '优惠券管理添加', 'admin', 'store.storeCoupon', 'create', '[]', 0, 0, 1),
(232, 230, '', '优惠券管理添加提交', 'admin', 'store.storeCoupon', 'save', '[]', 0, 0, 1),
(233, 230, '', '优惠券管理删除', 'admin', 'store.storeCoupon', 'delete', '[]', 0, 0, 1),
(234, 230, '', '优惠券管理立即失效', 'admin', 'store.storeCoupon', 'status', '[]', 0, 0, 1),
(235, 148, '', '发布管理', 'admin', 'ump.storeCouponIssue', 'index', '[]', 3, 1, 1),
(236, 82, '', '用户分组', 'admin', 'wechat.wechatUser', 'group', '[]', 0, 1, 1),
(237, 21, '', '刷新缓存', 'admin', 'system.clear', 'index', '[]', 0, 1, 1),
(238, 272, '', '拼团产品', 'admin', 'ump.storeCombination', 'index', '[]', 0, 1, 1),
(239, 306, '', '提现申请', 'admin', 'finance.user_extract', 'index', '[]', 0, 1, 1),
(241, 273, '', '限时秒杀', 'admin', 'ump.storeSeckill', 'index', '[]', 0, 1, 1),
(244, 294, '', '财务报表', 'admin', 'record.storeStatistics', 'index', '[]', 0, 1, 1),
(246, 295, '', '用户统计', 'admin', 'user.user', 'user_analysis', '[]', 0, 1, 1),
(247, 153, '', '个人资料', 'admin', 'setting.systemAdmin', 'admininfo', '[]', 0, 0, 1),
(248, 247, '', '个人资料附加权限', 'admin', 'setting.systemAdmin', '', '[]', 0, 0, 1),
(249, 248, '', '个人资料提交保存', 'admin', 'system.systemAdmin', 'setAdminInfo', '[]', 0, 0, 1),
(250, 247, '', '个人资料展示页', 'admin', 'setting.systemAdmin', 'admininfo', '[]', 0, 0, 1),
(254, 271, '', '砍价产品', 'admin', 'ump.storeBargain', 'index', '[]', 0, 1, 1),
(255, 289, '', '后台通知', 'admin', 'setting.systemNotice', 'index', '[]', 0, 0, 1),
(261, 147, '', '编辑产品', 'admin', 'store.storeproduct', 'edit', '[]', 0, 0, 1),
(262, 147, '', '添加产品', 'admin', 'store.storeproduct', 'create', '[]', 0, 0, 1),
(263, 147, '', '编辑产品详情', 'admin', 'store.storeproduct', 'edit_content', '[]', 0, 0, 1),
(264, 147, '', '开启秒杀', 'admin', 'store.storeproduct', 'seckill', '[]', 0, 0, 1),
(265, 147, '', '开启秒杀', 'admin', 'store.store_product', 'bargain', '[]', 0, 0, 1),
(266, 147, '', '产品编辑属性', 'admin', 'store.storeproduct', 'attr', '[]', 0, 0, 1),
(267, 11, '', '公众号配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"1\",\"tab_id\":\"2\"}', 100, 1, 1),
(270, 5400, '', '小程序配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"10\",\"tab_id\":\"7\"}', 10, 1, 1),
(272, 23, '', '拼团管理', 'admin', '', '', '[]', 95, 1, 1),
(273, 23, '', '秒杀管理', 'admin', '', '', '[]', 94, 1, 1),
(276, 469, '', '附件管理', 'admin', 'widget.images', 'index', '[]', 0, 0, 1),
(278, 469, '', '清除数据', 'admin', 'system.systemCleardata', 'index', '[]', 0, 1, 1),
(283, 80, '', '文章管理', 'admin', 'article.article', 'index', '[]', 0, 1, 1),
(284, 80, '', '文章分类', 'admin', 'article.article_category', 'index', '[]', 0, 1, 1),
(288, 0, 'iconlinechart', '数据', 'admin', 'record', 'index', '[]', 93, 1, 1),
(289, 0, 'iconsetting', '设置', 'admin', 'setting', 'index', '[]', 96, 1, 1),
(293, 288, '', '交易数据', 'admin', '', '', '[]', 100, 1, 1),
(295, 288, '', '会员数据', 'admin', '', '', '[]', 70, 1, 1),
(296, 288, '', '营销数据', 'admin', '', '', '[]', 90, 1, 1),
(300, 294, '', '提现统计', 'admin', 'record.record', 'chart_cash', '[]', 0, 1, 1),
(301, 294, '', '充值统计', 'admin', 'record.record', 'chart_recharge', '[]', 0, 1, 1),
(302, 294, '', '返佣统计', 'admin', 'record.record', 'chart_rebate', '[]', 0, 1, 1),
(303, 295, '', '会员增长', 'admin', 'record.record', 'user_chart', '[]', 0, 1, 1),
(304, 295, '', '会员业务', 'admin', 'record.record', 'user_business_chart', '[]', 0, 1, 1),
(306, 287, '', '财务操作', 'admin', '', '', '[]', 100, 1, 1),
(307, 287, '', '财务记录', 'admin', '', '', '[]', 50, 1, 1),
(308, 287, '', '佣金记录', 'admin', '', '', '[]', 0, 1, 1),
(312, 307, '', '资金监控', 'admin', 'finance.finance', 'bill', '[]', 0, 1, 1),
(313, 308, '', '佣金记录', 'admin', 'finance.finance', 'commission_list', '[]', 0, 1, 1),
(315, 296, '', '优惠券统计', 'admin', 'record.record', 'chart_coupon', '[]', 0, 1, 1),
(316, 296, '', '拼团统计', 'admin', 'record.record', 'chart_combination', '[]', 0, 1, 1),
(317, 296, '', '秒杀统计', 'admin', 'record.record', 'chart_seckill', '[]', 0, 1, 1),
(319, 297, '', '产品销售排行', 'admin', 'record.record', 'ranking_saleslists', '[]', 0, 1, 1),
(320, 297, '', '返佣排行', 'admin', 'record.record', 'ranking_commission', '[]', 0, 1, 1),
(321, 297, '', '积分排行', 'admin', 'record.record', 'ranking_point', '[]', 0, 1, 1),
(329, 23, '', '营销订单', 'admin', 'user', 'user', '[]', 0, 0, 1),
(333, 272, '', '拼团列表', 'admin', 'ump.storeCombination', 'combina_list', '[]', 0, 1, 1),
(334, 329, '', '秒杀订单', 'admin', 'user', '', '[]', 0, 0, 1),
(335, 329, '', '积分兑换', 'admin', 'user', '', '[]', 0, 0, 1),
(337, 0, 'iconapartment', '分销', 'admin', 'agent', 'index', '[]', 94, 1, 1),
(340, 293, '', '订单统计', 'admin', 'record.record', 'chart_order', '[]', 0, 1, 1),
(341, 293, '', '产品统计', 'admin', 'record.record', 'chart_product', '[]', 0, 1, 1),
(350, 349, '', '积分配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"3\",\"tab_id\":\"11\"}', 0, 1, 1),
(351, 349, '', '积分日志', 'admin', 'ump.userPoint', 'index', '[]', 0, 1, 1),
(354, 11, '', '自动回复', '', '', '', '[]', 80, 0, 1),
(356, 355, '', '个人中心菜单', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"32\"}', 0, 1, 1),
(361, 11, '', '公众号微信支付', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"1\",\"tab_id\":\"4\"}', 97, 1, 1),
(362, 276, '', '附加权限', 'admin', 'widget.images', '', '[]', 0, 1, 1),
(363, 362, '', '上传图片', 'admin', 'widget.images', 'upload', '[]', 0, 0, 1),
(364, 362, '', '删除图片', 'admin', 'widget.images', 'delete', '[]', 0, 0, 1),
(365, 362, '', '附件管理', 'admin', 'widget.images', 'index', '[]', 0, 0, 1),
(366, 254, '', '其它权限管理', '', '', '', '[]', 0, 0, 1),
(367, 366, '', '编辑砍价', 'admin', 'ump.storeBargain', 'edit', '[]', 0, 0, 1),
(368, 366, '', '砍价产品更新', 'admin', 'ump.storeBargain', 'update', '[]', 0, 1, 1),
(369, 143, '', '添加产品分类', 'admin', 'store.storeCategory', 'create', '[]', 0, 0, 1),
(370, 143, '', '编辑产品分类', 'admin', 'store.storeCategory', 'edit', '[]', 0, 0, 1),
(372, 462, '', '首页幻灯片', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"48\"}', 0, 1, 1),
(373, 462, '', '首页导航按钮', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"47\"}', 0, 1, 1),
(376, 269, '', '小程序模板消息', 'admin', 'routine.routineTemplate', 'index', '[]', 0, 1, 1),
(377, 469, '', '数据备份', 'admin', 'system.systemDatabackup', 'index', '[]', 0, 1, 1),
(378, 50069, '', '物流公司', 'admin', 'system.express', 'index', '[]', 8, 1, 1),
(379, 469, '', '文件管理', 'admin', 'system.systemFile', 'opendir', '[]', 0, 1, 1),
(380, 379, '', '权限规则', 'admin', 'system.systemFile', '', '[]', 0, 0, 1),
(381, 380, '', '打开文件', 'admin', 'system.systemFile', 'openfile', '[]', 0, 0, 1),
(382, 380, '', '编辑文件', 'admin', 'system.systemFile', 'savefile', '[]', 0, 0, 1),
(386, 362, '', '移动图片分类展示', 'admin', 'widget.images', 'moveimg', '[]', 0, 0, 1),
(387, 362, '', '编辑分类', 'admin', 'widget.images', 'updatecate', '[]', 0, 0, 1),
(388, 362, '', '添加分类', 'admin', 'widget.images', 'savecate', '[]', 0, 0, 1),
(389, 362, '', '移动图片分类', 'admin', 'widget.images', 'moveimgcecate', '[]', 0, 0, 1),
(390, 362, '', '编辑分类展示', 'admin', 'widget.images', 'editcate', '[]', 0, 0, 1),
(392, 362, '', '删除分类', 'admin', 'widget.images', 'deletecate', '[]', 0, 0, 1),
(393, 362, '', '添加分类展示', 'admin', 'widget.images', 'addcate', '[]', 0, 0, 1),
(394, 191, '', '订单获取列表', 'admin', 'store.storeOrder', 'order_list', '[]', 0, 0, 1),
(395, 82, '', '微信用户附加权限', 'admin', 'wechat.wechatUser', '', '[]', 0, 0, 1),
(396, 395, '', '推送消息', 'admin', 'wechat.wechat_news_category', 'push', '[]', 0, 0, 1),
(397, 395, '', '推送优惠券', 'admin', 'ump.storeCouponUser', 'grant', '[]', 0, 0, 1),
(398, 177, '', '会员列表页', 'admin', 'user.user', 'index', '[]', 0, 0, 1),
(399, 177, '', '会员附加权限', '', 'user.user', '', '[]', 0, 0, 1),
(400, 399, '', '修改用户状态', '', 'user.user', 'set_status', '[]', 0, 0, 1),
(401, 399, '', '编辑用户', 'admin', 'user.user', 'edit', '[]', 0, 0, 1),
(402, 399, '', '更新用户', 'admin', 'user.user', 'update', '[]', 0, 0, 1),
(403, 399, '', '查看用户', 'admin', 'user.user', 'see', '[]', 0, 0, 1),
(405, 399, '', '发优惠券', 'admin', 'ump.storeCouponUser', 'grant', '[]', 0, 0, 1),
(406, 399, '', '推送图文', 'admin', 'wechat.wechatNewsCategory', 'push', '[]', 0, 0, 1),
(407, 399, '', '发站内信', 'admin', 'user.userNotice', 'notice', '[]', 0, 0, 1),
(408, 176, '', '站内通知附加权限', 'admin', 'user.user_notice', '', '[]', 0, 0, 1),
(409, 408, '', '添加站内消息', 'admin', 'user.user_notice', 'save', '[]', 0, 0, 1),
(410, 408, '', '编辑站内消息', 'admin', 'user.user_notice', 'update', '[]', 0, 0, 1),
(411, 408, '', '发送站内消息', 'admin', 'user.user_notice', 'send', '[]', 0, 0, 1),
(412, 408, '', '删除站内消息', 'admin', 'user.user_notice', 'delete', '[]', 0, 0, 1),
(413, 408, '', '指定用户发送', 'admin', 'user.user_notice', 'send_user', '[]', 0, 0, 1),
(415, 371, '', '分销管理附加权限', 'admin', 'agent.agentManage', '', '[]', 0, 0, 1),
(416, 174, '', '微信模版消息附加权限', 'admin', 'wechat.wechatTemplate', '', '[]', 0, 0, 1),
(417, 416, '', '添加模版消息', 'admin', 'wechat.wechatTemplate', 'save', '[]', 0, 0, 1),
(418, 416, '', '添加模版消息展示', 'admin', 'wechat.wechatTemplate', 'create', '[]', 0, 0, 1),
(419, 416, '', '编辑模版消息展示', 'admin', 'wechat.wechatTemplate', 'edit', '[]', 0, 0, 1),
(420, 416, '', '更新模版消息展示', 'admin', 'wechat.wechatTemplate', 'update', '[]', 0, 0, 1),
(421, 416, '', '删除模版消息展示', 'admin', 'wechat.wechatTemplate', 'delete', '[]', 0, 0, 1),
(422, 376, '', '小程序模版消息附加权限', 'admin', 'routine.routineTemplate', '', '[]', 0, 0, 1),
(423, 422, '', '添加模版消息展示', 'admin', 'routine.routineTemplate', 'create', '[]', 0, 0, 1),
(424, 422, '', '添加模版消息', 'admin', 'routine.routineTemplate', 'save', '[]', 0, 0, 1),
(425, 422, '', '编辑模版消息展示', 'admin', 'routine.routineTemplate', 'edit', '[]', 0, 0, 1),
(426, 422, '', '编辑模版消息', 'admin', 'routine.routineTemplate', 'update', '[]', 0, 0, 1),
(427, 422, '', '删除模版消息', 'admin', 'routine.routineTemplate', 'delete', '[]', 0, 0, 1),
(439, 377, '', '数据库备份附加权限', 'admin', 'system.systemDatabackup', '', '[]', 0, 0, 1),
(440, 439, '', '查看表结构', 'admin', 'system.systemDatabackup', 'seetable', '[]', 0, 0, 1),
(441, 439, '', '优化表', 'admin', 'system.systemDatabackup', 'optimize', '[]', 0, 0, 1),
(442, 439, '', '修复表', 'admin', 'system.systemDatabackup', 'repair', '[]', 0, 0, 1),
(443, 439, '', '备份表', 'admin', 'system.systemDatabackup', 'backup', '[]', 0, 0, 1),
(444, 439, '', '删除备份', 'admin', 'system.systemDatabackup', 'delFile', '[]', 0, 0, 1),
(445, 439, '', '恢复备份', 'admin', 'system.systemDatabackup', 'import', '[]', 0, 0, 1),
(446, 439, '', '下载备份', 'admin', 'system.systemDatabackup', 'downloadFile', '[]', 0, 0, 1),
(447, 377, '', '数据备份展示页', 'admin', 'system.systemDatabackup', 'index', '[]', 0, 0, 1),
(448, 379, '', '文件管理展示页', 'admin', 'system.systemFile', 'index', '[]', 0, 0, 1),
(450, 371, '', '分销管理列表页', 'admin', 'agent.agentManage', 'index', '[]', 0, 0, 1),
(451, 376, '', '小程序模版消息列表页', 'admin', 'routine.routineTemplate', 'index', '[]', 0, 0, 1),
(452, 174, '', '微信模版消息列表页', 'admin', 'wechat.wechatTemplate', 'index', '[]', 0, 0, 1),
(453, 276, '', '附件管理展示页', 'admin', 'widget.images', 'index', '[]', 0, 0, 1),
(458, 462, '', '签到天数配置', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"55\"}', 0, 1, 1),
(459, 462, '', '订单详情动态图', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"53\"}', 0, 1, 1),
(460, 462, '', '个人中心菜单', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"54\"}', 0, 1, 1),
(461, 462, '', '小程序首页滚动新闻', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"50\"}', 0, 1, 1),
(462, 269, '', '模块数据配置', 'admin', '', '', '[]', 100, 1, 1),
(463, 462, '', '热门榜单推荐banner', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"57\"}', 0, 1, 1),
(464, 462, '', '首发新品推荐banner', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"58\"}', 0, 1, 1),
(465, 462, '', '促销单品推荐banner', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"59\"}', 0, 1, 1),
(466, 462, '', '个人中心分销海报', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"60\"}', 0, 1, 1),
(468, 1, '', '配置组合数据附加权限', 'admin', 'setting.systemGroupData', 'index', '[]', 0, 0, 1),
(470, 1, '', '配置组合数据展示页', 'admin', 'setting.systemGroup', 'index', '[]', 0, 0, 1),
(471, 0, 'iconcomment', '社区', 'admin', 'com', 'index', '[]', 100, 1, 1),
(472, 471, '', '版块管理', 'admin', 'com.comForum', 'index', '{\"status\":\"1\"}', 124, 1, 1),
(474, 471, '', '帖子管理', 'admin', 'com.thread', 'index', '[]', 121, 1, 1),
(475, 50006, '', '分类管理', 'admin', 'com.comThreadClass', 'index', '[]', 123, 1, 1),
(481, 5060, '', '飞鸽配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"20\"}', 0, 1, 1),
(482, 5310, '', '注册配置', 'admin', 'user.login', 'index_set', '', 97, 1, 1),
(485, 50005, '', '广告/菜单', 'admin', 'com.com_adv', 'index', '[]', 9, 1, 1),
(486, 485, '', '社区首页广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"2\"}', 50, 1, 1),
(487, 485, '', '首页顶部轮播图', 'admin', 'com.com_adv', 'index', '{\"\":\"type\"}', 10, 1, 1),
(488, 471, '', '资讯管理', 'admin', 'com.com_thread', 'index', '{\"type\":\"4\",\"status\":\"1\"}', 119, 1, 1),
(489, 471, '', '视频管理', 'admin', 'com.com_thread', 'index', '{\"type\":\"6\",\"status\":\"1\"}', 120, 1, 1),
(490, 489, '', '视频列表', 'admin', 'com.com_thread', 'index', '{\"type\":\"6\",\"status\":\"\"}', 5, 1, 1),
(492, 474, '', '帖子列表', 'admin', 'com.com_thread', 'index', '[]', 3, 1, 1),
(494, 488, '', '资讯列表', 'admin', 'com.com_thread', 'index', '{\"type\":\"4\",\"status\":\"\"}', 5, 1, 1),
(498, 485, '', '社区导航', 'admin', 'com.com_nav', 'index', '{\"type\":\"2\"}', 54, 1, 1),
(499, 485, '', '商城导航', 'admin', 'com.com_nav', 'index', '{\"type\":\"3\"}', 53, 1, 1),
(500, 485, '', '社区顶部轮播图', 'admin', 'com.com_adv', 'index', '{\"type\":\"3\"}', 8, 1, 1),
(501, 485, '', '帖子详情广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"4\"}', 49, 1, 1),
(502, 485, '', '个人中心广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"5\"}', 48, 1, 1),
(503, 485, '', '商城首页广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"8\"}', 47, 1, 1),
(504, 151, '', '用户等级', 'admin', 'level', 'index', '[]', 8, 1, 1),
(505, 504, '', '系统用户等级', 'admin', 'user.level', 'index', '{\"type\":\"1\"}', 0, 1, 1),
(506, 504, '', '商城会员等级', 'admin', 'user.MallLevel', 'index', '[]', 0, 0, 1),
(507, 50064, '', '积分管理', 'admin', 'user_jf', 'index', '[]', 95, 1, 1),
(508, 507, '', '积分类型', 'admin', 'user.rule', 'index', '[]', 10, 1, 1),
(509, 507, '', '积分编辑', 'admin', 'user.guize', 'index', '[]', 9, 1, 1),
(510, 507, '', '签到积分', 'admin', 'user.jifen', 'index', '[]', 8, 1, 1),
(513, 5031, '', '商城栏目设置', 'admin', 'store.storeSet', 'index', '{\"status\":\"1\"}', 11, 1, 1),
(515, 600, '', 'app配置', 'admin', 'wechat_xcx', 'index', '[]', 88, 1, 1),
(600, 0, 'iconappstoreadd', '应用', 'admin', 'application', 'index', '', 91, 1, 1),
(5000, 50061, '', '公告消息', 'admin', 'com.comAnnounce', 'index', '', 124, 1, 1),
(5001, 50061, '', '自定义消息', 'admin', 'com.comMessage', 'index', '{\"status\":\"1\"}', 126, 1, 1),
(5002, 485, '', '商城顶部轮播图', 'admin', 'com.com_adv', 'index', '{\"type\":\"6\"}', 6, 1, 1),
(5003, 485, '', '首页精品推荐', 'admin', 'com.com_adv', 'index', '{\"type\":\"7\"}', 4, 1, 1),
(5005, 5004, '', '帖子投诉列表', 'admin', 'com.comReport', 'index', '[]', 0, 1, 1),
(5006, 5004, '', '用户投诉列表', 'admin', 'com.comReport', 'user', '[]', 0, 1, 1),
(5007, 5004, '', '投诉原因列表', 'admin', 'com.comReport', 'reason', '[]', 0, 1, 1),
(5013, 50006, '', '专栏管理', 'admin', 'column', 'index', '[]', 99, 1, 1),
(5014, 50006, '', '栏目管理', 'admin', 'column.column_class', 'index', '[]', 0, 1, 1),
(5015, 5013, '', '专栏列表', 'admin', 'column.column_list', 'index', '{\"type\":\"\",\"is_column\":\"1\",\"is_show\":\"1\"}', 0, 1, 1),
(5016, 50006, '', '订单管理', 'admin', 'knowledge_order', 'index', '[]', 100, 1, 1),
(5017, 5016, '', '订单管理', 'admin', 'knowledge.knowledge_order', 'index', '[]', 0, 1, 1),
(5018, 5016, '', '评论管理', 'admin', 'knowledge.knowledge_comment', 'index', '[]', 0, 1, 1),
(5019, 50006, '', '知识付费数据', 'admin', 'knowledge_sj', 'index', '[]', 98, 1, 1),
(5020, 5019, '', '订单统计', 'admin', 'knowledge.order_summary', 'chart_order', '[]', 90, 1, 1),
(5021, 5019, '', '产品统计', 'admin', 'knowledge.order_summary', 'chart_product', '[]', 80, 1, 1),
(5022, 5019, '', '优惠券统计', 'admin', 'knowledge.order_summary', 'chart_coupon', '[]', 70, 1, 1),
(5024, 5019, '', '产品销售排行榜', 'admin', 'knowledge.order_summary', 'ranking_saleslists', '[]', 0, 0, 1),
(5025, 289, '', '热门搜索', 'admin', 'setting.systemAdmin_rm', '', '[]', 96, 1, 1),
(5026, 5025, '', '商城热搜', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"56\"}', 9, 1, 1),
(5027, 5025, '', '全站/社区热搜', 'admin', 'setting.system_group_data', 'index', '{\"gid\":\"61\"}', 10, 1, 1),
(5028, 1, '', '基本信息', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"23\"}', 100, 1, 1),
(5029, 1, '', '分享设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"24\"}', 99, 1, 1),
(5031, 23, '', '商城设置', 'admin', 'setting.systemAdmin_sotre', '', '[]', 93, 1, 1),
(5032, 5031, '', '积分设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"26\"}', 7, 1, 1),
(5033, 5031, '', '商城基本设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"5\"}', 10, 1, 1),
(5034, 474, '', '帖子配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"28\"}', 0, 1, 1),
(5035, 488, '', '资讯配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"29\"}', 0, 1, 1),
(5039, 21, '', '数据修正', 'admin', 'system.correct', 'index', '[]', 0, 1, 1),
(5040, 289, '', '安全配置', 'admin', 'setting_aq', 'index', '[]', 94, 1, 1),
(5041, 5040, '', '白名单', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"30\"}', 0, 1, 1),
(5042, 1, '', '用户协议', 'admin', 'user.user_agreement', 'index', '', 0, 1, 1),
(5043, 50069, '', '物流设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"31\"}', 9, 1, 1),
(5044, 507, '', '积分日志', 'admin', 'user.log', 'index', '[]', 7, 1, 1),
(5045, 50064, '', '积分商城', 'admin', '', '', '[]', 96, 1, 1),
(5046, 5045, '', '商品管理', 'admin', 'shop.shop_product', 'index', '{\"status\":\"1\"}', 10, 1, 1),
(5047, 5045, '', '订单管理', 'admin', 'shop.shop_order', 'index', '[]', 9, 1, 1),
(5048, 5045, '', '栏目管理', 'admin', 'shop.shop_column', 'index', '{\"status\":\"1\"}', 8, 1, 1),
(5051, 485, '', '知识付费导航', 'admin', 'com.com_nav', 'index', '{\"type\":\"5\"}', 52, 1, 1),
(5052, 485, '', '知识付费广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"11\"}', 44, 1, 1),
(5053, 485, '', '知识付费顶部轮播图', 'admin', 'com.com_adv', 'index', '{\"type\":\"12\"}', 0, 1, 1),
(5060, 289, '', '短信设置', 'admin', 'setting.systemConfig_sms', 'index', '[]', 98, 1, 1),
(5061, 5045, '', '基础设置', 'admin', 'shop.shop_type', 'index', '[]', 7, 1, 1),
(5101, 337, '', '分销订单', 'admin', 'agent.sell_order', 'index', '[]', 3, 1, 1),
(5102, 337, '', '分销提现', 'admin', 'agent.cash_out', 'index', '[]', 2, 1, 1),
(5109, 50080, '', '邀请海报', 'admin', 'share.index', 'hai_bao', '[]', 2, 1, 1),
(5301, 289, '', '资料项配置', 'admin', 'certification.datum', 'index', '[]', 95, 1, 1),
(5302, 50065, '', '认证特权', 'admin', 'certification.privilege', 'index', '[]', 8, 1, 1),
(5303, 50065, '', '认证条件', 'admin', 'certification.condition', 'index', '[]', 7, 1, 1),
(5304, 50065, '', '常见问题', 'admin', 'certification.faq', 'index', '[]', 5, 1, 1),
(5306, 50065, '', '认证列表', 'admin', 'certification.entity', 'index', '[]', 10, 1, 1),
(5307, 50065, '', '认证类别', 'admin', 'certification.cate', 'index', '[]', 9, 1, 1),
(5308, 50065, '', '认证配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"37\"}', 4, 1, 1),
(5310, 289, '', '注册登录', 'admin', 'setting.systemConfig', 'login', '', 99, 1, 1),
(5400, 600, '', '小程序', 'admin', 'wechat_xcx', 'index', '[]', 90, 1, 1),
(5500, 0, 'iconaccountbook', '财务管理', 'admin', 'payment.index', 'index', '[]', 92, 1, 1),
(50004, 50061, '', '营销消息', 'admin', 'com.comMessageNews', 'index', '', 127, 1, 1),
(50005, 0, 'iconReport', '运营', 'admin', 'adv', 'adv', '[]', 95, 1, 1),
(50006, 0, 'iconbulb', '知识付费', 'admin', 'knowledge', 'index', '[]', 98, 1, 1),
(50007, 50006, '', '作者管理', 'admin', 'column.column_author', 'index', '[]', 97, 1, 1),
(50008, 289, '', '图片上传中心', 'admin', 'widget.Images', 'index', '[]', 109, 0, 1),
(50009, 289, '', '上传功能', 'admin', 'widget.Images', 'upload', '[]', 109, 0, 1),
(50010, 90007, '', 'pc端配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"32\"}', 10, 1, 1),
(50011, 50060, '', '基本设置', 'admin', 'agent.agent_config', 'index', '[]', 10, 1, 1),
(50012, 50060, '', '佣金设置', 'admin', 'agent.agent_config', 'yong_jin', '[]', 9, 1, 1),
(50013, 50060, '', '提现设置', 'admin', 'agent.agent_config', 'ti_xian', '[]', 8, 1, 1),
(50014, 50060, '', '申请协议', 'admin', 'agent.agent_config', 'xie_yi', '[]', 7, 1, 1),
(50016, 337, '', '分销商', 'admin', 'agent.agent_manage', 'agent', '[]', 4, 1, 1),
(50017, 50060, '', '收益说明', 'admin', 'agent.agent_config', 'income_statement', '[]', 6, 1, 1),
(50018, 50060, '', '商品海报配置', 'admin', 'agent.agent_config', 'share_config', '[]', 5, 1, 1),
(50049, 485, '', '推广中心广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"9\"}', 46, 1, 1),
(50050, 489, '', '视频配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"34\"}', 3, 1, 1),
(50051, 485, '', '积分商城广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"10\"}', 45, 1, 1),
(50052, 5060, '', '创蓝配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"35\"}', 0, 1, 1),
(50053, 5060, '', '短信服务商', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"36\"}', 3, 1, 1),
(50054, 485, '', '认证首页广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"13\"}', 43, 1, 1),
(50060, 337, '', '分销配置', 'admin', 'com.com_fx', 'index', '[]', 1, 1, 1),
(50061, 50005, '', '消息管理', 'admin', 'com.com_message', 'index', '[]', 10, 1, 1),
(50064, 0, 'iconexpand', '扩展模块', 'admin', 'system', 'index', '[]', 92, 1, 1),
(50065, 50064, '', '认证', 'admin', 'com.com_renzheng', 'index', '[]', 100, 1, 1),
(50066, 151, '', '推荐关注', 'admin', 'user.user_recommend', 'index', '[]', 7, 1, 1),
(50068, 515, '', 'APP微信支付配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"1\",\"tab_id\":\"50\"}', 10, 1, 1),
(50069, 289, '', '物流配置', 'admin', 'com.wuliu', 'index', '', 92, 1, 1),
(50070, 50061, '', '系统提醒', 'admin', 'com.comMessage', 'message_reminder', '', 123, 1, 1),
(50073, 289, '', '敏感词', 'admin', '', '', '', 91, 1, 1),
(50074, 50073, '', '敏感词列表', 'admin', 'sensitive.sensitive', 'index', '', 10, 1, 1),
(50075, 50073, '', '触发记录', 'admin', 'sensitive.sensitiveLog', 'index', '', 9, 1, 1),
(50076, 21, '', '刷新客户端缓存', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"4\",\"tab_id\":\"55\"}', 0, 1, 1),
(50077, 471, '', '动态管理', 'admin', 'com.weibo', 'index', '[]', 120, 1, 1),
(50078, 50077, '', '动态列表', 'admin', 'com.com_thread', 'index', '{\"is_weibo\":\"1\",\"status\":\"\"}', 3, 1, 1),
(50079, 50077, '', '动态配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"38\"}', 0, 1, 1),
(50080, 50064, '', '用户邀请', 'admin', 'user.user_yq', 'index', '[]', 94, 1, 1),
(50081, 50080, '', '邀请码', 'admin', 'invite.invite_code', 'index', '[]', 10, 1, 1),
(50082, 50080, '', '邀请记录', 'admin', 'invite.invite_log', 'index', '[]', 10, 1, 1),
(50083, 507, '', '任务管理', 'admin', 'user.renwu', 'index', '[]', 0, 1, 1),
(50085, 471, '', '社区配置', 'admin', 'com.com_site', 'index', '[]', 114, 1, 1),
(50086, 289, '', '图片存储配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"5\",\"tab_id\":\"60\"}', 93, 1, 1),
(50087, 471, '', '版主管理', 'admin', 'com.com_forum_admin_gl', 'index', '[]', 122, 1, 1),
(50088, 50087, '', '版主列表', 'admin', 'com.com_forum_admin', 'index', '[]', 120, 1, 1),
(50089, 50087, '', '版主设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"3\",\"tab_id\":\"39\"}', 4, 1, 1),
(50090, 515, '', 'app版本', 'admin', 'system.system_version', 'index', '[]', 8, 1, 1),
(50091, 50080, '', '邀请奖励', 'admin', 'invite.invite_reward', 'index', '[]', 10, 1, 1),
(50092, 50080, '', '邀请设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"6\",\"tab_id\":\"40\"}', 4, 1, 1),
(50093, 5310, '', '登陆问题设置', 'admin', 'user.faq', 'index', '[]', 5, 1, 1),
(50096, 471, '', '举报管理', 'admin', 'com.com_report_gl', 'index', '[]', 117, 1, 1),
(50097, 50096, '', '举报列表', 'admin', 'com.com_report', 'index', '[]', 100, 1, 1),
(50099, 50096, '', '举报原因', 'admin', 'com.com_report', 'reason', '[]', 100, 1, 1),
(50100, 50224, '', '禁言时长', 'admin', 'com.com_report', 'prohibit', '[]', 100, 1, 1),
(50102, 50064, '', '话题', 'admin', 'com.topic', 'index', '[]', 99, 1, 1),
(50103, 50102, '', '话题列表', 'admin', 'com.com_topic', 'index', '[]', 101, 1, 1),
(50104, 50102, '', '话题分类', 'admin', 'com.com_topic_class', 'index', '[]', 101, 1, 1),
(50105, 50064, '', '马甲', 'admin', 'com.com_vest', 'index', '[]', 97, 1, 1),
(50106, 50105, '', '马甲列表', 'admin', 'com.com_vest', 'index', '[]', 0, 1, 1),
(50107, 50105, '', '评论模板', 'admin', 'com.com_template', 'index', '[]', 0, 1, 1),
(50108, 50105, '', '马甲评论', 'admin', 'com.com_post', 'index', '{\"is_vest\":\"1\"}', 2, 1, 1),
(50200, 50064, '', 'SEO配置', 'admin', 'seo.index', 'index', '', 93, 1, 1),
(50201, 50200, '', '页面配置', 'admin', 'seo.index', 'index', '', 0, 1, 1),
(50202, 50200, '', '基础配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"11\",\"tab_id\":\"65\"}', 0, 1, 1),
(50203, 50200, '', '静态文件列表', 'admin', 'seo.index', 'fileIndex', '', 0, 1, 1),
(50204, 5101, '', '知识付费订单', 'admin', 'knowledge.knowledge_sell_order', 'index', '[]', 0, 1, 1),
(50205, 50064, '', '榜单', 'admin', 'rank.rank', 'index', '[]', 98, 1, 1),
(50206, 471, '', '评论管理', 'admin', 'com.com_post', 'index', '[]', 118, 1, 1),
(50207, 151, '', '用户组', 'admin', 'group.group_power', 'index', '[]', 9, 1, 1),
(50208, 50207, '', '管理组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"1\"}', 10, 1, 1),
(50209, 50207, '', '系统用户组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"2\"}', 9, 1, 1),
(50210, 50207, '', '晋级用户组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"3\"}', 6, 1, 1),
(50211, 50207, '', '会员用户组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"4\"}', 7, 1, 1),
(50212, 50207, '', '认证用户组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"5\"}', 8, 1, 1),
(50213, 50207, '', '自定义用户组', 'admin', 'group.group_power', 'index', '{\"group_type\":\"6\"}', 5, 1, 1),
(50217, 5400, '', '小程序微信支付', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"10\",\"tab_id\":\"14\"}', 8, 1, 1),
(50218, 472, '', '版块列表', 'admin', 'com.comForum', 'index', '{\"status\":\"1\"}', 127, 1, 1),
(50219, 472, '', '访问审核', 'admin', 'group.visit_audit', 'index', '{\"status\":\"2\"}', 125, 1, 1),
(50220, 472, '', '版块设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"61\"}', 3, 1, 1),
(50221, 90007, '', 'PC端强制跳转', 'admin', 'pc.pc_set', 'index', '[]', 9, 1, 1),
(50222, 21, '', 'token值', 'admin', 'system.system_version', 'token_show', '[]', 0, 1, 1),
(50224, 471, '', '禁言管理', 'admin', 'rohibit.prohibit', 'index', '[]', 117, 1, 1),
(50225, 50224, '', '禁言原因', 'admin', 'prohibit.prohibit_reason', 'index', '[]', 117, 1, 1),
(50226, 50224, '', '禁言列表', 'admin', 'prohibit.prohibit', 'index', '{\"type\":\"1\"}', 118, 1, 1),
(51000, 50064, '', '第三方平台接入', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"8\",\"tab_id\":\"90\"}', 92, 1, 1),
(51001, 51000, '', '接入配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"8\",\"tab_id\":\"90\"}', 0, 1, 1),
(51002, 51000, '', '事件通知', 'admin', 'user.website', 'index', '', 0, 1, 1),
(51003, 50087, '', '版主审核', 'admin', 'com.com_forum_admin', 'apply', '[]', 120, 1, 1),
(51004, 50006, '', '优惠券管理', '', '', '', '[]', 96, 1, 1),
(51005, 51004, '', '优惠券', 'admin', 'ump.columnCoupon', 'index', '[]', 5, 1, 1),
(51006, 51004, '', '领取记录', 'admin', 'ump.columnCouponUser', 'index', '[]', 1, 1, 1),
(51007, 51004, '', '发布管理', 'admin', 'ump.columnCouponIssue', 'index', '[]', 3, 1, 1),
(51008, 1, '', '版权标识', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"6\",\"tab_id\":\"70\"}', 97, 1, 1),
(51009, 289, '', '移动图片分类显示', 'admin', 'widget.Images', 'moveimg', '[]', 109, 0, 1),
(51010, 289, '', '删除图片', 'admin', 'widget.Images', 'delete', '[]', 109, 0, 1),
(51011, 289, '', '添加图片分类', 'admin', 'widget.Images', 'addcate', '[]', 109, 0, 1),
(51012, 289, '', '编辑图片分类', 'admin', 'widget.Images', 'editcate', '[]', 109, 0, 1),
(51013, 289, '', '删除图片分类', 'admin', 'widget.Images', 'deletecate', '[]', 109, 0, 1),
(51015, 50067, '', '分销海报', 'admin', 'share.index', 'hai_bao', '[]', 2, 1, 1),
(51016, 1, '', '平台信息', 'admin', 'setting.system_config', 'index', '{\"type\":\"1\",\"tab_id\":\"92\"}', 98, 1, 1),
(51017, 515, '', 'APP消息配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"1\",\"tab_id\":\"93\"}', 9, 1, 1),
(51018, 50006, '', '知识付费配置', 'admin', 'setting.systemConfig', 'index', '{\"type\":\"1\",\"tab_id\":\"94\"}', 0, 1, 1),
(51019, 5025, '', '搜索日志', 'admin', 'system.search', 'index', '', 7, 1, 1),
(51020, 5400, '', '小程序订阅消息', 'admin', 'wechat.wechatRoutineTemplate', 'index', '[]', 9, 1, 1),
(51021, 50061, '', '自定义消息设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"95\"}', 125, 1, 1),
(51022, 50061, '', '新注册消息', 'admin', 'com.com_message_register', 'index', '', 120, 1, 1),
(51030, 50006, '', '单品管理', 'admin', 'column.product', 'index', '[]', 99, 1, 1),
(51121, 50064, '', '活动', 'admin', 'event.event_category', 'index', '[]', 98, 1, 1),
(51122, 51121, '', '活动分类', 'admin', 'event.event_category', 'index', '[]', 100, 1, 1),
(51123, 51121, '', '活动管理', 'admin', 'event.event', 'index', '[]', 100, 1, 1),
(51124, 51121, '', '活动配置', 'admin', 'event.event', 'set', '[]', 100, 1, 1),
(51125, 485, '', '拼团广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"14\"}', 42, 1, 1),
(51126, 485, '', '秒杀广告位', 'admin', 'com.com_adv', 'index', '{\"type\":\"15\"}', 41, 1, 1),
(51127, 5101, '', '商城订单', 'admin', 'agent.sell_order', 'index', '[]', 3, 1, 1),
(52000, 50064, '', '微信流量主广告', 'admin', '', '', '', 91, 1, 1),
(90000, 471, '', '频道管理', 'admin', 'channel.index', 'index', '', 124, 1, 1),
(90001, 90000, '', '数据统计', 'admin', 'channel.count', 'index', '', 6, 1, 1),
(90002, 90000, '', '频道设置', 'admin', 'channel.index', 'config', '', 5, 1, 1),
(90003, 90000, '', '系统频道', 'admin', 'channel.index', 'index', '', 4, 1, 1),
(90004, 90000, '', '自定义频道', 'admin', 'channel.index', 'other', '', 3, 1, 1),
(90005, 90000, '', '备选池管理', 'admin', 'channel.post_pool', 'index', '', 2, 1, 1),
(90006, 90000, '', '频道管理员', 'admin', 'channel.admin', 'index', '', 1, 1, 1),
(90007, 600, '', 'PC端', 'admin', 'pc', 'index', '', 88, 1, 1),
(90008, 50006, '', '分类管理', 'admin', 'column.column_category', 'index', '[]', 96, 1, 1),
(90009, 51030, '', '图文单品', 'admin', 'column.column_list', 'index', '{\"type\":\"1\",\"is_column\":\"0\",\"is_show\":\"1\"}', 99, 1, 1),
(90010, 51030, '', '音频单品', 'admin', 'column.column_list', 'index', '{\"type\":\"2\",\"is_column\":\"0\",\"is_show\":\"1\"}', 98, 1, 1),
(90011, 51030, '', '视频单品', 'admin', 'column.column_list', 'index', '{\"type\":\"3\",\"is_column\":\"0\",\"is_show\":\"1\"}', 97, 1, 1),
(90012, 5019, '', '付费商品排行榜', 'admin', 'knowledge.order_summary', 'ranking_saleslists', '{\"type\":\"0\"}', 60, 1, 1),
(90013, 5019, '', '免费商品排行榜', 'admin', 'knowledge.order_summary', 'ranking_saleslists', '{\"type\":\"1\"}', 50, 1, 1),
(90014, 50064, '', '私信设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"56\"}', 1, 1, 1),
(90015, 289, '', '登录设置', 'admin', 'user.login', 'index', '', 97, 1, 1),
(90016, 485, '', '任务导航', 'admin', 'user.renwu_nav', 'index', '', 54, 1, 1),
(90017, 50087, '', '积分奖励', 'admin', 'com.com_forum_admin', 'score_set', '[]', 120, 1, 1),
(90018, 50206, '', '评论管理', 'admin', 'com.com_post', 'index', '[]', 118, 1, 1),
(90019, 50206, '', '评论设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"106\"}', 118, 1, 1),
(90020, 1, '', '协议集中管理', 'admin', 'setting.system_config', 'all_agreement', '', 12, 1, 1),
(110000, 50005, '', 'PC广告管理', 'admin', 'com.com_adv', 'index', '[]', 3, 1, 1),
(110001, 110000, '', '发布页广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"99\"}', 5, 1, 1),
(110002, 110000, '', '话题详情广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"98\"}', 4, 1, 1),
(110003, 110000, '', '版块详情广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"97\"}', 3, 1, 1),
(110004, 110000, '', '内容详情页广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"96\"}', 2, 1, 1),
(110005, 110000, '', '搜索页广告', 'admin', 'com.com_adv', 'index', '{\"type\":\"95\"}', 1, 1, 1),
(110006, 50064, '', '创作配置', 'admin', 'setting.systemConfig', 'writing_center', '[]', 10, 1, 1),
(110007, 5031, '', '服务保障', 'admin', 'store.store_product', 'services', '', 5, 1, 1),
(110008, 52000, '', '广告管理', 'admin', 'wechat.routine_ad', 'index', '', 100, 1, 1),
(110009, 52000, '', '基础配置', 'admin', 'setting.system_config', 'index', '{\"type\":\"0\",\"tab_id\":\"110\"}', 0, 1, 1),
(110010, 5500, '', '全局设置', 'admin', 'payment.index', 'set_config', '[]', 1, 1, 1),
(110011, 5500, '', '交易记录', 'admin', 'payment.trade', 'index', '[]', 91, 1, 1),
(110012, 5500, '', '提现记录', 'admin', 'payment.index', 'withdraw_list', '[]', 90, 1, 1),
(110013, 5500, '', '财务统计', 'admin', 'payment.index', 'payment_profit', '[]', 90, 1, 1),
(110015, 50087, '', '奖励记录', 'admin', 'com.com_forum_admin_score_log', 'index', '[]', 100, 1, 1),
(110016, 289, '', '邮件设置', 'admin', 'setting.system_config', 'index', '{\"type\":\"3\",\"tab_id\":\"45\"}', 97, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_notice`
--

CREATE TABLE `osx_system_notice` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '通知模板id',
  `title` varchar(64) NOT NULL COMMENT '通知标题',
  `type` varchar(64) NOT NULL COMMENT '通知类型',
  `icon` varchar(16) NOT NULL COMMENT '图标',
  `url` varchar(64) NOT NULL COMMENT '链接',
  `table_title` varchar(256) NOT NULL COMMENT '通知数据',
  `template` varchar(64) NOT NULL COMMENT '通知模板',
  `push_admin` varchar(128) NOT NULL COMMENT '通知管理员id',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通知模板表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_notice_admin`
--

CREATE TABLE `osx_system_notice_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '通知记录ID',
  `notice_type` varchar(64) NOT NULL COMMENT '通知类型',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '通知的管理员',
  `link_id` int(10) UNSIGNED NOT NULL COMMENT '关联ID',
  `table_data` text NOT NULL COMMENT '通知的数据',
  `is_click` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点击次数',
  `is_visit` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '访问次数',
  `visit_time` int(11) NOT NULL COMMENT '访问时间',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '通知时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通知记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_renwu`
--

CREATE TABLE `osx_system_renwu` (
  `id` int(11) NOT NULL,
  `leixing` varchar(100) DEFAULT NULL COMMENT '1日常任务,2每周任务,3新手任务,4自定义任务',
  `jifenflag` varchar(100) DEFAULT NULL COMMENT '积分标志',
  `name` varchar(100) DEFAULT NULL COMMENT '任务名称',
  `explain` varchar(100) DEFAULT NULL COMMENT '任务描述',
  `require` varchar(100) DEFAULT NULL COMMENT '完成要求',
  `exp` int(11) DEFAULT NULL COMMENT '经验值积分奖励',
  `fly` int(11) DEFAULT NULL COMMENT '社区积分奖励',
  `gong` int(11) DEFAULT NULL COMMENT '贡献值积分奖励',
  `buy` int(11) DEFAULT NULL COMMENT '购物积分奖励',
  `one` int(11) DEFAULT NULL COMMENT '自定义积分1奖励',
  `position` varchar(100) DEFAULT NULL COMMENT '任务位置:1首页、2商城首页、3我的、4其他',
  `url` varchar(100) DEFAULT NULL COMMENT '打开的url',
  `type` int(11) DEFAULT '1' COMMENT '1系统任务，2自定义任务',
  `status` int(11) DEFAULT '1' COMMENT '1启用，2禁用',
  `is_del` int(11) DEFAULT '0' COMMENT '0正常，1删除',
  `icon` text,
  `two` int(11) DEFAULT NULL,
  `three` int(11) DEFAULT NULL,
  `four` int(11) DEFAULT NULL,
  `five` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_renwu`
--

INSERT INTO `osx_system_renwu` (`id`, `leixing`, `jifenflag`, `name`, `explain`, `require`, `exp`, `fly`, `gong`, `buy`, `one`, `position`, `url`, `type`, `status`, `is_del`, `icon`, `two`, `three`, `four`, `five`) VALUES
(1, '1', 'guanzhu', '关注', '每日关注5位用户', '5', 2, 2, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963998228.png', 0, 0, 0, 0),
(2, '1', 'fatie', '发帖', '每日发布5个帖子', '5', 8, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb96395c873.png', 0, 0, 0, 0),
(3, '1', 'dianzan', '点赞', '每日完成5次点赞', '5', 2, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963a22b83.png', 0, 0, 0, 0),
(4, '1', 'pinglun', '评论', '每日发布5条评论', '5', 5, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9639df807.png', 0, 0, 0, 0),
(5, '1', 'shoucang', '收藏', '每日完成5次收藏', '5', 2, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9639c83f3.png', 0, 0, 0, 0),
(6, '2', 'xiadanshu', '下单数', '一周内在商城消费两次', '2', 2, 0, 0, 2, 0, '', '/pages/mix-mall/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963a0fd1b.png', 0, 0, 0, 0),
(7, '2', 'gouwujine', '购物金额', '一周内商城消费满100元', '100', 2, 0, 0, 2, 0, '', '/pages/mix-mall/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9639acb1f.png', 0, 0, 0, 0),
(9, '3', 'genghuantouxiang', '更换头像', '完成即可获得经验值5', '1', 5, 0, 0, 0, 0, '', '/packageA/login-reg/perfect-userinfo', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963981025.png', 0, 0, 0, 0),
(10, '3', 'tianxieziliao', '填写资料', '完成即可获得经验值2', '1', 2, 0, 0, 0, 0, '', '/packageA/login-reg/perfect-userinfo', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbba6f25e1d0.png', 0, 0, 0, 0),
(11, '3', 'shoucifatie', '首次发帖', '完成即可获得经验值5', '1', 5, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963a706e1.png', 0, 0, 0, 0),
(12, '3', 'shoucipinglun', '首次评论', '完成即可获得经验值3', '1', 3, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb9639df807.png', 0, 0, 0, 0),
(13, '3', 'shoucidianzan', '首次点赞', '完成即可获得经验值2', '1', 2, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963a22b83.png', 0, 0, 0, 0),
(14, '3', 'shoucishoucang', '首次收藏', '完成即可获得经验值5', '10', 2, 0, 0, 0, 0, '', '/pages/index/index', 1, 2, 0, 'https://newosx.demo.opensns.cn/public/uploads/attach/2019/11/01/5dbb963a4ff2c.png', 0, 0, 0, 0),
(15, '4', 'flagone', '自定义任务15', '自定义任务15', '1', 2, 2, 2, 2, 2, '', '2', 2, 2, 1, 'https://osxbecs.demo.opensns.cn/public/uploads/attach/2019/03/28/5c9ccc99269d1.png', 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_role`
--

CREATE TABLE `osx_system_role` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '身份管理id',
  `role_name` varchar(32) NOT NULL COMMENT '身份管理名称',
  `rules` text NOT NULL COMMENT '身份管理权限(menus_id)',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='身份管理表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_system_role`
--

INSERT INTO `osx_system_role` (`id`, `role_name`, `rules`, `level`, `status`) VALUES
(1, '超级管理员', '23,24,147,266,265,264,263,262,261,146,25,142,143,369,370,285,26,191,394,208,207,206,205,204,203,202,201,200,199,198,197,196,195,194,193,192,190,329,334,335,290,170,225,228,227,226,224,151,177,399,402,403,405,406,407,401,400,398,176,408,413,412,411,409,410,449,337,353,371,415,450,286,148,149,230,234,233,232,231,229,235,150,352,271,254,366,367,368,272,238,333,273,241,349,351,350,287,306,239,307,179,216,217,218,219,312,308,313,288,293,340,341,296,318,317,316,315,314,294,302,244,301,300,295,303,304,305,246,374,297,321,320,319,269,372,270,373,375,376,422,423,424,425,426,427,451,11,360,267,17,127,128,126,174,416,417,418,419,420,421,452,361,355,359,358,356,357,354,12,97,98,99,30,93,89,92,91,90,124,123,31,95,96,94,37,175,210,215,214,213,212,211,223,222,209,19,73,42,116,115,45,44,43,82,133,134,395,396,397,84,236,129,132,289,378,153,8,67,63,105,104,66,65,64,4,68,59,103,102,62,61,60,2,78,74,77,76,75,101,100,247,250,248,249,1,6,118,117,119,145,144,7,46,108,107,106,50,49,48,47,51,69,9,70,52,112,111,110,109,58,57,56,55,54,53,255,80,283,72,38,41,40,39,284,71,33,114,113,36,35,34,21,173,252,237,278,130,131,377,447,439,445,446,444,443,440,441,442,276,362,364,363,365,390,393,392,389,388,386,387,453,379,448,380,381,382,0', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_rule`
--

CREATE TABLE `osx_system_rule` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `leixing` varchar(50) DEFAULT NULL COMMENT '类型',
  `danwei` varchar(30) DEFAULT NULL COMMENT '单位',
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  `flag` varchar(20) DEFAULT NULL COMMENT '标识',
  `status` int(11) DEFAULT '1' COMMENT '1启用，2禁用',
  `is_del` int(11) DEFAULT '0' COMMENT '0正常，1删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_rule`
--

INSERT INTO `osx_system_rule` (`id`, `name`, `leixing`, `danwei`, `explain`, `flag`, `status`, `is_del`) VALUES
(1, '经验值', '1', '点', '经验值说明', 'exp', 1, 0),
(2, '社区积分', '1', '点', '社区积分', 'fly', 1, 0),
(3, '购物积分', '1', '点', '购物积分', 'buy', 1, 0),
(4, '贡献值', '1', '点', '贡献值说明', 'gong', 1, 0),
(5, '自定义1', '2', '点', '自定义1', 'one', 2, 0),
(6, '自定义2', '2', '点', '自定义2', 'two', 2, 0),
(7, '自定义3', '2', '点', '自定义3', 'three', 2, 0),
(8, '自定义4', '2', '点', '自定义4', 'four', 2, 0),
(9, '自定义5', '2', '点', '自定义5', 'five', 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_rule_action`
--

CREATE TABLE `osx_system_rule_action` (
  `id` int(11) NOT NULL,
  `module` varchar(100) DEFAULT NULL COMMENT '所属模块',
  `actionflag` varchar(100) DEFAULT NULL COMMENT '行为标识',
  `actiontype` varchar(100) DEFAULT NULL COMMENT '行为类型',
  `actionname` varchar(100) DEFAULT NULL COMMENT '行为名称',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '每日奖励上限',
  `expone` int(11) DEFAULT NULL COMMENT '经验值单次变动',
  `expmax` int(11) DEFAULT NULL,
  `flyone` int(11) DEFAULT NULL,
  `flymax` int(11) DEFAULT NULL,
  `gongone` int(11) DEFAULT NULL,
  `gongmax` int(11) DEFAULT NULL,
  `buyone` int(11) DEFAULT NULL,
  `buymax` int(11) DEFAULT NULL,
  `firstone` int(11) DEFAULT NULL,
  `firstmax` int(11) DEFAULT NULL,
  `twoone` int(11) DEFAULT NULL,
  `twomax` int(11) DEFAULT NULL,
  `threeone` int(11) DEFAULT NULL,
  `threemax` int(11) DEFAULT NULL,
  `fourone` int(11) DEFAULT NULL,
  `fourmax` int(11) DEFAULT NULL,
  `fiveone` int(11) DEFAULT NULL,
  `fivemax` int(11) DEFAULT NULL,
  `is_del` int(11) DEFAULT '0' COMMENT '0正常，1删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_rule_action`
--

INSERT INTO `osx_system_rule_action` (`id`, `module`, `actionflag`, `actiontype`, `actionname`, `num`, `expone`, `expmax`, `flyone`, `flymax`, `gongone`, `gongmax`, `buyone`, `buymax`, `firstone`, `firstmax`, `twoone`, `twomax`, `threeone`, `threemax`, `fourone`, `fourmax`, `fiveone`, `fivemax`, `is_del`) VALUES
(1, '社区', 'fatie', '日常行为', '发帖', 0, 2, 10, 2, 10, 0, 0, 0, 0, 0, 0, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(3, '社区', 'pinglun', '日常行为', '评论', 0, 2, 10, 2, 10, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(9, '社区', 'shoucangtiezi', '日常行为', '收藏帖子', 0, 1, 5, 1, 5, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(10, '社区', 'dianzan', '日常行为', '点赞', 0, 1, 5, 1, 5, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(15, '社区', 'guanzhu', '日常行为', '关注', 0, 1, 4, 2, 4, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(16, '社区', 'jiarubankuai', '日常行为', '关注版块', 0, 1, 2, 0, 0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(17, '系统', 'meiridenglu', '日常行为', '每日登录', 0, 1, 1, 0, 0, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(18, '系统', 'zhuce', '日常行为', '注册', 0, 1, 1, 2, 2, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 0),
(20, '社区', 'fadongtai', '日常行为', '发动态', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(21, '社区', 'fashipin', '日常行为', '发视频', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(22, '社区', 'beijinyan', '日常行为', '被禁言', 5, -2, -10, -2, -10, -2, -10, -2, -10, -2, -10, -2, -10, -2, -10, -2, -10, -2, -10, 0),
(23, '商城', 'goumaishangpin', '日常行为', '购买商品', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(24, '商城', 'fashangpinpingjia', '日常行为', '发商品/知识商品评价', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(25, '商城', 'shangpinshoucang', '日常行为', '商品收藏', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(26, '知识商城', 'goumaizhishishangpin', '日常行为', '购买知识商品', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0),
(27, '知识商城', 'fazhishishangpinpingjia', '日常行为', '发知识商品评价', 5, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 2, 10, 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_user_grade`
--

CREATE TABLE `osx_system_user_grade` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '等级名称',
  `experience` int(11) DEFAULT '0' COMMENT '等级经验值',
  `icon` varchar(100) DEFAULT '' COMMENT '图标',
  `image` varchar(100) DEFAULT '' COMMENT '等级图片',
  `explain` varchar(550) DEFAULT '' COMMENT '等级说明',
  `is_del` int(11) DEFAULT '0' COMMENT '0正常，1删除',
  `type` int(11) DEFAULT '1' COMMENT '1系统会员等级,2商城会员等级'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_system_user_grade`
--

INSERT INTO `osx_system_user_grade` (`id`, `name`, `experience`, `icon`, `image`, `explain`, `is_del`, `type`) VALUES
(1, '1级', 20, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77015dcc47.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77015dcc47.png', '1级', 0, 1),
(2, '2级', 50, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7702371e6d.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7702371e6d.png', '2级', 0, 1),
(3, '3级', 100, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de773df0f83f.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de773df0f83f.png', '3级', 0, 1),
(4, '4级', 200, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7743796e2a.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7743796e2a.png', '4级', 0, 1),
(5, '5级', 400, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7745266209.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7745266209.png', '5级', 0, 1),
(6, '6级', 700, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7748744d61.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7748744d61.png', '6级', 0, 1),
(7, '7级', 1000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774b4c9560.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774b4c9560.png', '7级', 0, 1),
(8, '8级', 1500, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774cc8d381.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774cc8d381.png', '8级', 0, 1),
(9, '9级', 2000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774e293014.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774e293014.png', '9级', 0, 1),
(10, '10级', 2500, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774fca9e45.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de774fca9e45.png', '10级', 0, 1),
(11, '11级', 3000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77513a17eb.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77513a17eb.png', '11级', 0, 1),
(12, '12级', 4000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775298a1f5.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775298a1f5.png', '12级', 0, 1),
(13, '13级', 5000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7754a6f554.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7754a6f554.png', '13级', 0, 1),
(14, '14级', 6500, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7756203ba4.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7756203ba4.png', '14级', 0, 1),
(15, '15级', 8000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7757ccc374.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7757ccc374.png', '15级', 0, 1),
(16, '16级', 10000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7759b2c901.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7759b2c901.png', '16级', 0, 1),
(17, '17级', 12000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775b46951f.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775b46951f.png', '17级', 0, 1),
(18, '18级', 15000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775cd5d56a.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775cd5d56a.png', '18级', 0, 1),
(19, '19级', 20000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775e9e2259.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de775e9e2259.png', '19级', 0, 1),
(20, '20级', 27000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7760845376.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7760845376.png', '20级', 0, 1),
(21, '21级', 37000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77647e5895.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de77647e5895.png', '21级', 0, 1),
(22, '22级', 40000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7767c0699b.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de7767c0699b.png', '22级', 0, 1),
(23, '23级', 52000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776a1a1acc.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776a1a1acc.png', '23级', 0, 1),
(24, '24级', 67000, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776c2c4d55.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776c2c4d55.png', '24级', 0, 1),
(25, '25级', 999999, 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776e30c3ab.png', 'https://osxshopht.demo.opensns.cn/public/uploads/attach/2019/12/04/5de776e30c3ab.png', '25级', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_user_level`
--

CREATE TABLE `osx_system_user_level` (
  `id` int(11) NOT NULL,
  `mer_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '会员名称',
  `money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '购买金额',
  `valid_date` int(11) NOT NULL DEFAULT '0' COMMENT '有效时间',
  `is_forever` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为永久会员',
  `is_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否购买,1=购买,0=不购买',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 1=显示,0=隐藏',
  `grade` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `discount` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '享受折扣',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡背景',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '会员图标',
  `explain` text NOT NULL COMMENT '说明',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除.1=删除,0=未删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设置用户等级表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_system_user_task`
--

CREATE TABLE `osx_system_user_task` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '任务名称',
  `real_name` varchar(255) NOT NULL DEFAULT '' COMMENT '配置原名',
  `task_type` varchar(50) NOT NULL DEFAULT '' COMMENT '任务类型',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '限定数',
  `level_id` int(11) NOT NULL DEFAULT '0' COMMENT '等级id',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `is_must` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否务必达成任务,1务必达成,0=满足其一',
  `illustrate` varchar(255) NOT NULL DEFAULT '' COMMENT '任务说明',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '新增时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='等级任务设置' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_talk`
--

CREATE TABLE `osx_talk` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '会话uid1',
  `to_uid` int(11) NOT NULL COMMENT '会话uid2',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(11) NOT NULL COMMENT '状态',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_talk_content`
--

CREATE TABLE `osx_talk_content` (
  `id` int(11) NOT NULL COMMENT 'id',
  `talk_id` int(11) NOT NULL COMMENT '会话id',
  `uid` int(11) NOT NULL COMMENT '发消息人',
  `content` text NOT NULL COMMENT '消息内容',
  `image` text NOT NULL COMMENT '图片',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_tencent_file`
--

CREATE TABLE `osx_tencent_file` (
  `id` int(11) NOT NULL,
  `file_id` varchar(50) NOT NULL COMMENT '文件id，腾讯云返回',
  `media_url` varchar(200) NOT NULL COMMENT '文件url，腾讯云返回',
  `cover_url` varchar(200) NOT NULL COMMENT '封面url，video类型可能有',
  `status` tinyint(2) NOT NULL COMMENT '状态',
  `type` varchar(15) NOT NULL COMMENT '类型，audio：音频，video：视频',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='腾讯云点播-文件管理表-音频、视频';

-- --------------------------------------------------------

--
-- 表的结构 `osx_text_user`
--

CREATE TABLE `osx_text_user` (
  `aid` int(11) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `level` varchar(255) DEFAULT NULL COMMENT '级别',
  `signature` varchar(255) DEFAULT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='知识专栏编辑者';

--
-- 转存表中的数据 `osx_text_user`
--

INSERT INTO `osx_text_user` (`aid`, `nickname`, `avatar`, `level`, `signature`) VALUES
(1, '想天', NULL, '1', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `osx_thread_census`
--

CREATE TABLE `osx_thread_census` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `one` int(11) NOT NULL,
  `seven` int(11) NOT NULL,
  `thirty` int(11) NOT NULL,
  `ninety` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_thread_user`
--

CREATE TABLE `osx_thread_user` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '数据用户信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_thread_user`
--

INSERT INTO `osx_thread_user` (`id`, `uid`, `content`) VALUES
(1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `osx_token`
--

CREATE TABLE `osx_token` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `rand_string` varchar(10) NOT NULL DEFAULT '' COMMENT '10位随机字符串',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='保存token随机字符串' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user`
--

CREATE TABLE `osx_user` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `account` varchar(32) NOT NULL COMMENT '用户账号',
  `pwd` varchar(32) NOT NULL COMMENT '用户密码',
  `nickname` varchar(16) NOT NULL COMMENT '用户昵称',
  `avatar` varchar(256) NOT NULL COMMENT '用户头像',
  `phone` char(15) DEFAULT NULL COMMENT '手机号码',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `add_time` int(11) UNSIGNED NOT NULL COMMENT '添加时间',
  `add_ip` varchar(16) NOT NULL COMMENT '添加ip',
  `last_time` int(11) UNSIGNED NOT NULL COMMENT '最后一次登录时间',
  `last_ip` varchar(16) NOT NULL COMMENT '最后一次登录ip',
  `now_money` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '用户余额',
  `integral` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '用户剩余积分',
  `sign_num` int(11) NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为正常，0为禁止，-1删除，-2注册失败',
  `level` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '等级',
  `spread_uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '推广元id',
  `spread_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '推广员关联时间',
  `user_type` varchar(32) NOT NULL COMMENT '用户类型',
  `is_promoter` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为推广员',
  `pay_count` int(11) UNSIGNED DEFAULT '0' COMMENT '用户购买次数',
  `spread_count` int(11) DEFAULT '0' COMMENT '下级人数',
  `fans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `follow` int(11) NOT NULL DEFAULT '0' COMMENT '关注数',
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别，0保密-未设置，1男，2女',
  `birthday` int(11) NOT NULL COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `signature` varchar(150) NOT NULL COMMENT '签名',
  `login_time` int(11) NOT NULL COMMENT '登录次数',
  `pos_province` int(11) NOT NULL,
  `pos_city` int(11) NOT NULL,
  `pos_district` int(11) NOT NULL,
  `pos_community` int(11) NOT NULL,
  `total_sign` int(11) NOT NULL DEFAULT '0' COMMENT '累签数',
  `support_count` int(11) NOT NULL DEFAULT '0' COMMENT '被点赞数',
  `introduction` text NOT NULL COMMENT '简介',
  `forum_count` int(11) NOT NULL DEFAULT '0' COMMENT '版块数',
  `post_count` int(11) NOT NULL DEFAULT '0' COMMENT '帖子数',
  `exp` int(11) NOT NULL DEFAULT '0' COMMENT '经验值',
  `fly` int(11) NOT NULL DEFAULT '0' COMMENT '社区积分',
  `gong` int(11) NOT NULL DEFAULT '0' COMMENT '贡献积分',
  `buy` int(11) NOT NULL DEFAULT '0' COMMENT '购物积分',
  `one` int(11) NOT NULL DEFAULT '0' COMMENT '自定义积分1',
  `two` int(11) NOT NULL DEFAULT '0' COMMENT '自定义积分2',
  `three` int(11) NOT NULL DEFAULT '0' COMMENT '自定义积分3',
  `four` int(11) NOT NULL DEFAULT '0' COMMENT '自定义积分4',
  `five` int(11) NOT NULL DEFAULT '0' COMMENT '自定义积分5',
  `is_collect` int(11) NOT NULL,
  `icon` varchar(200) DEFAULT NULL COMMENT '认证图标',
  `is_red` int(1) NOT NULL DEFAULT '0' COMMENT '是否红名',
  `mark` text NOT NULL COMMENT '管理员备注',
  `real_name` varchar(50) NOT NULL COMMENT '真实姓名',
  `collect` int(11) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `is_avatar` int(11) NOT NULL DEFAULT '0' COMMENT '是否修改过头像',
  `is_password` int(11) NOT NULL DEFAULT '0' COMMENT '是否可用密码登陆',
  `is_vest` int(11) NOT NULL COMMENT '是否是马甲',
  `cate_id` int(11) NOT NULL COMMENT '最新认证id',
  `bind_im_uid` int(11) NOT NULL COMMENT '绑定im的uid',
  `user_background` text COMMENT 'pc用户个人中心背景图'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_user`
--

INSERT INTO `osx_user` (`uid`, `account`, `pwd`, `nickname`, `avatar`, `phone`, `email`, `add_time`, `add_ip`, `last_time`, `last_ip`, `now_money`, `integral`, `sign_num`, `status`, `level`, `spread_uid`, `spread_time`, `user_type`, `is_promoter`, `pay_count`, `spread_count`, `fans`, `follow`, `sex`, `birthday`, `qq`, `signature`, `login_time`, `pos_province`, `pos_city`, `pos_district`, `pos_community`, `total_sign`, `support_count`, `introduction`, `forum_count`, `post_count`, `exp`, `fly`, `gong`, `buy`, `one`, `two`, `three`, `four`, `five`, `is_collect`, `icon`, `is_red`, `mark`, `real_name`, `collect`, `is_avatar`, `is_password`, `is_vest`, `cate_id`, `bind_im_uid`, `user_background`) VALUES
(1, 'rt11555153424', 'e10adc3949ba59abbe56e057f20f883e', '管理员', 'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqj70fHkbW9aJgp0KWMsp7cqOsgT16Syr8mWt9JkhngDWARibyNv5MBia3h8Y3BOkHBHdLiaX8Hq9J0w/132', NULL, '', 1555153423, '127.0.0.1', 1555153955, '127.0.0.1', '0.00', '0.00', 0, 1, 0, 0, 0, 'routine', 0, 0, 0, 1, 0, 0, 0, '', '', 0, 0, 0, 0, 0, 0, 0, '', 0, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '', '', 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_address`
--

CREATE TABLE `osx_user_address` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户地址id',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `real_name` varchar(32) NOT NULL COMMENT '收货人姓名',
  `phone` varchar(16) NOT NULL COMMENT '收货人电话',
  `province` varchar(64) NOT NULL COMMENT '收货人所在省',
  `city` varchar(64) NOT NULL COMMENT '收货人所在市',
  `district` varchar(64) NOT NULL COMMENT '收货人所在区',
  `detail` varchar(256) NOT NULL COMMENT '收货人详细地址',
  `post_code` int(10) UNSIGNED NOT NULL COMMENT '邮编',
  `longitude` varchar(16) NOT NULL DEFAULT '0' COMMENT '经度',
  `latitude` varchar(16) NOT NULL DEFAULT '0' COMMENT '纬度',
  `is_default` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否默认',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户地址表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_agreement`
--

CREATE TABLE `osx_user_agreement` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `name` text NOT NULL,
  `all_agreement_id` int(11) DEFAULT NULL COMMENT '关联协议集中管理表的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_user_agreement`
--

INSERT INTO `osx_user_agreement` (`id`, `content`, `status`, `name`, `all_agreement_id`) VALUES
(1, '这个是一个用户注册协议', 1, '', 1),
(2, '', 1, '隐私协议', 2);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_bill`
--

CREATE TABLE `osx_user_bill` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '用户账单id',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户uid',
  `link_id` varchar(32) NOT NULL DEFAULT '0' COMMENT '关联id',
  `pm` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = 支出 1 = 获得',
  `title` varchar(64) NOT NULL COMMENT '账单标题',
  `category` varchar(64) NOT NULL COMMENT '明细种类',
  `type` varchar(64) NOT NULL DEFAULT '' COMMENT '明细类型',
  `number` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '明细数字',
  `balance` decimal(8,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '剩余',
  `mark` varchar(512) NOT NULL COMMENT '备注',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = 带确定 1 = 有效 -1 = 无效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户账单表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_buy_product`
--

CREATE TABLE `osx_user_buy_product` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_enter`
--

CREATE TABLE `osx_user_enter` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '商户申请ID',
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `province` varchar(32) NOT NULL COMMENT '商户所在省',
  `city` varchar(32) NOT NULL COMMENT '商户所在市',
  `district` varchar(32) NOT NULL COMMENT '商户所在区',
  `address` varchar(256) NOT NULL COMMENT '商户详细地址',
  `merchant_name` varchar(256) NOT NULL COMMENT '商户名称',
  `link_user` varchar(32) NOT NULL,
  `link_tel` varchar(16) NOT NULL COMMENT '商户电话',
  `charter` varchar(512) NOT NULL COMMENT '商户证书',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间',
  `apply_time` int(10) UNSIGNED NOT NULL COMMENT '审核时间',
  `success_time` int(11) NOT NULL COMMENT '通过时间',
  `fail_message` varchar(256) NOT NULL COMMENT '未通过原因',
  `fail_time` int(10) UNSIGNED NOT NULL COMMENT '未通过时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1 审核未通过 0未审核 1审核通过',
  `is_lock` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = 开启 1= 关闭',
  `is_del` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户申请表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_extract`
--

CREATE TABLE `osx_user_extract` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED DEFAULT NULL,
  `real_name` varchar(64) DEFAULT NULL COMMENT '名称',
  `extract_type` varchar(32) DEFAULT 'bank' COMMENT 'bank = 银行卡 alipay = 支付宝wx=微信',
  `bank_code` varchar(32) DEFAULT '0' COMMENT '银行卡',
  `bank_address` varchar(256) DEFAULT '' COMMENT '开户地址',
  `alipay_code` varchar(64) DEFAULT '' COMMENT '支付宝账号',
  `extract_price` decimal(8,2) UNSIGNED DEFAULT '0.00' COMMENT '提现金额',
  `mark` varchar(512) DEFAULT NULL,
  `balance` decimal(8,2) UNSIGNED DEFAULT '0.00',
  `fail_msg` varchar(128) DEFAULT NULL COMMENT '无效原因',
  `fail_time` int(10) UNSIGNED DEFAULT NULL,
  `add_time` int(10) UNSIGNED DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(2) DEFAULT '0' COMMENT '-1 未通过 0 审核中 1 已提现',
  `wechat` varchar(15) DEFAULT NULL COMMENT '微信号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户提现表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_follow`
--

CREATE TABLE `osx_user_follow` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `follow_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被关注用户id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '关注时间',
  `alias` varchar(40) NOT NULL DEFAULT '' COMMENT '用户备注',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户关注表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_user_follow`
--

INSERT INTO `osx_user_follow` (`id`, `uid`, `follow_uid`, `create_time`, `alias`, `status`) VALUES
(1, 2, 1, 1572599065, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_friend`
--

CREATE TABLE `osx_user_friend` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `friend_uid` int(11) NOT NULL DEFAULT '0' COMMENT '好友用户id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '成为好友的时间',
  `alias` varchar(40) NOT NULL DEFAULT '' COMMENT '用户备注',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户好友表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_group`
--

CREATE TABLE `osx_user_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(64) DEFAULT NULL COMMENT '用户分组名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户分组表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_level`
--

CREATE TABLE `osx_user_level` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `level_id` int(11) NOT NULL DEFAULT '0' COMMENT '等级vip',
  `grade` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级',
  `valid_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `is_forever` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否永久',
  `mer_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:禁止,1:正常',
  `mark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `remind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已通知',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,0=未删除,1=删除',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `discount` int(11) NOT NULL DEFAULT '0' COMMENT '享受折扣'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户等级记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_login_log`
--

CREATE TABLE `osx_user_login_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_login_set`
--

CREATE TABLE `osx_user_login_set` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1：手机；2：邮箱；3：微信；',
  `status` int(11) NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录方式设置';

--
-- 转存表中的数据 `osx_user_login_set`
--

INSERT INTO `osx_user_login_set` (`id`, `type`, `status`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_notice`
--

CREATE TABLE `osx_user_notice` (
  `id` int(11) NOT NULL,
  `uid` text NOT NULL COMMENT '接收消息的用户id（类型：json数据）',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '消息通知类型（1：系统消息；2：用户通知）',
  `user` varchar(20) NOT NULL DEFAULT '' COMMENT '发送人',
  `title` varchar(20) NOT NULL COMMENT '通知消息的标题信息',
  `content` varchar(500) NOT NULL COMMENT '通知消息的内容',
  `add_time` int(11) NOT NULL COMMENT '通知消息发送的时间',
  `is_send` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发送（0：未发送；1：已发送）',
  `send_time` int(11) NOT NULL COMMENT '发送时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户通知表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_notice_see`
--

CREATE TABLE `osx_user_notice_see` (
  `id` int(11) NOT NULL,
  `nid` int(11) NOT NULL COMMENT '查看的通知id',
  `uid` int(11) NOT NULL COMMENT '查看通知的用户id',
  `add_time` int(11) NOT NULL COMMENT '查看通知的时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户通知发送记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_order`
--

CREATE TABLE `osx_user_order` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '订单创建者',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `unique` varchar(255) NOT NULL COMMENT '订单号加密,md5加密',
  `pay_type` varchar(100) NOT NULL COMMENT '支付类型',
  `info` varchar(255) NOT NULL COMMENT '支付内容',
  `amount` decimal(10,2) NOT NULL COMMENT '金额',
  `amount_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为支出1为获得',
  `status` tinyint(4) NOT NULL COMMENT '状态2创建交易中1交易成功0交易关闭 -1交易失败',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `bind_table` varchar(100) NOT NULL COMMENT '绑定的订单号详情的表格',
  `order_type` int(11) NOT NULL COMMENT '订单类型1社区消费2社区收入3商城消费4充值5提现6退款',
  `call_back_order_id` varchar(255) NOT NULL,
  `relation_order` text NOT NULL COMMENT '关联订单'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_order_callback_log`
--

CREATE TABLE `osx_user_order_callback_log` (
  `id` int(11) NOT NULL COMMENT 'id',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `content` text NOT NULL COMMENT 'Json格式内容，返回的所有数据',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_order_log`
--

CREATE TABLE `osx_user_order_log` (
  `id` int(11) NOT NULL COMMENT 'id',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `info` varchar(255) NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL COMMENT '变更时间',
  `uid` int(11) NOT NULL COMMENT '操作人0为系统',
  `uid_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为前端，1为后端'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单变更日志';

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_pay`
--

CREATE TABLE `osx_user_pay` (
  `id` int(11) NOT NULL COMMENT 'id',
  `method` varchar(20) NOT NULL COMMENT '渠道id',
  `name` varchar(11) NOT NULL COMMENT '渠道名称',
  `tab_id` int(11) NOT NULL COMMENT '配置内容的tab_id',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `bus` varchar(20) NOT NULL COMMENT '商户 id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_user_pay`
--

INSERT INTO `osx_user_pay` (`id`, `method`, `name`, `tab_id`, `status`, `bus`) VALUES
(1, 'weixin_method', 'weixin_name', 4, 1, 'pay_weixin_appid');

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_picture`
--

CREATE TABLE `osx_user_picture` (
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `picture` text NOT NULL COMMENT '照片',
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_rank`
--

CREATE TABLE `osx_user_rank` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '签到更新时间',
  `con_check` int(11) NOT NULL DEFAULT '0' COMMENT '连签次数',
  `total_check` int(11) NOT NULL DEFAULT '0' COMMENT '累签次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户签到表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_read`
--

CREATE TABLE `osx_user_read` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `pid` int(11) NOT NULL COMMENT '商品id',
  `rid` int(11) NOT NULL COMMENT '阅读id',
  `create_time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户阅读记录' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_recharge`
--

CREATE TABLE `osx_user_recharge` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) DEFAULT NULL COMMENT '充值用户UID',
  `order_id` varchar(32) DEFAULT NULL COMMENT '订单号',
  `price` decimal(8,2) DEFAULT NULL COMMENT '充值金额',
  `recharge_type` varchar(32) DEFAULT NULL COMMENT '充值类型',
  `paid` tinyint(1) DEFAULT NULL COMMENT '是否充值',
  `pay_time` int(10) DEFAULT NULL COMMENT '充值支付时间',
  `add_time` int(12) DEFAULT NULL COMMENT '充值时间',
  `refund_price` decimal(10,2) DEFAULT '0.00' COMMENT '退款金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户充值表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_recommend`
--

CREATE TABLE `osx_user_recommend` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `reason` text NOT NULL COMMENT '推荐原因',
  `create_time` int(11) NOT NULL COMMENT '推荐时间',
  `sort` int(11) NOT NULL COMMENT '排序',
  `status` int(11) NOT NULL COMMENT '状态：1推荐；0不推荐；',
  `attention` int(11) NOT NULL COMMENT '是否默认关注：0=>不关注1=>关注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户推荐表';

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_register`
--

CREATE TABLE `osx_user_register` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `from` varchar(50) NOT NULL DEFAULT '' COMMENT '注册终端',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '注册方式',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户注册记录' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_user_register`
--

INSERT INTO `osx_user_register` (`id`, `uid`, `from`, `type`, `status`) VALUES
(1, 2, 'osx', 'phone', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_report`
--

CREATE TABLE `osx_user_report` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '举报用户',
  `to_uid` int(11) NOT NULL COMMENT '被举报用户',
  `total_count` text NOT NULL COMMENT '累计被举报次数',
  `create_time` int(11) NOT NULL COMMENT '投诉时间',
  `reason` int(11) NOT NULL COMMENT '投诉原因',
  `status` int(11) NOT NULL COMMENT '状态',
  `is_deal` int(11) NOT NULL DEFAULT '0' COMMENT '是否处理'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_reward`
--

CREATE TABLE `osx_user_reward` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `author_uid` int(11) NOT NULL DEFAULT '0' COMMENT '作者uid',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '帖子id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` int(5) NOT NULL DEFAULT '0' COMMENT '打赏类型 1：积分 2：余额',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '打赏数额',
  `content` varchar(255) DEFAULT '' COMMENT '打赏时鼓励的话'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户打赏表' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_share`
--

CREATE TABLE `osx_user_share` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `sharetype` varchar(60) DEFAULT NULL COMMENT '分享类型',
  `create_time` int(11) DEFAULT NULL COMMENT '分享时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_sign`
--

CREATE TABLE `osx_user_sign` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '签到说明',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '获得积分',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '剩余积分',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_support_card`
--

CREATE TABLE `osx_user_support_card` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '点赞者',
  `to_uid` int(11) NOT NULL COMMENT '被点赞者',
  `create_time` int(11) NOT NULL COMMENT '点赞时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_sync_login`
--

CREATE TABLE `osx_user_sync_login` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type_uid` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `oauth_token` varchar(255) NOT NULL,
  `oauth_token_secret` varchar(255) NOT NULL,
  `is_sync` tinyint(4) NOT NULL,
  `open_id` varchar(255) NOT NULL,
  `mini_open_id` varchar(255) NOT NULL COMMENT '小程序openid',
  `is_update` int(11) NOT NULL DEFAULT '0',
  `app_open_id` text NOT NULL COMMENT 'app的open_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_task_day`
--

CREATE TABLE `osx_user_task_day` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `action` text NOT NULL COMMENT '行为标识',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '累计获得积分数值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户日常任务获得积分统计表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_task_finish`
--

CREATE TABLE `osx_user_task_finish` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有效',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户任务完成记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_task_new`
--

CREATE TABLE `osx_user_task_new` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `action` text NOT NULL COMMENT '行为标识',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '数值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户新手任务完成记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_verify`
--

CREATE TABLE `osx_user_verify` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `account` varchar(255) NOT NULL COMMENT '关键标识，如手机验证码，这里储存手机号',
  `type` varchar(20) NOT NULL COMMENT '类型标识，当前只有mobile',
  `verify` varchar(50) NOT NULL COMMENT '验证码',
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户验证码' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `osx_user_verify`
--

INSERT INTO `osx_user_verify` (`id`, `uid`, `account`, `type`, `verify`, `create_time`) VALUES
(24, 0, '15258086836', 'mobile', '816587', 1572602369);

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_visit`
--

CREATE TABLE `osx_user_visit` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) NOT NULL COMMENT '查看',
  `visit_uid` int(11) NOT NULL COMMENT '被查看的uid',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_wallet`
--

CREATE TABLE `osx_user_wallet` (
  `uid` int(11) NOT NULL COMMENT 'id',
  `password` varchar(100) NOT NULL COMMENT '支付密码',
  `all_money` decimal(10,2) NOT NULL COMMENT '全部钱',
  `enable_money` decimal(10,2) NOT NULL COMMENT '可用金额',
  `disable_money` decimal(10,2) NOT NULL COMMENT '冻结金额',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_user_wanshan`
--

CREATE TABLE `osx_user_wanshan` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '1手机号，2头像，3个人信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `osx_user_wanshan`
--

INSERT INTO `osx_user_wanshan` (`id`, `uid`, `status`) VALUES
(1, 2, 3),
(2, 2, 2);

-- --------------------------------------------------------

--
-- 表的结构 `osx_vest`
--

CREATE TABLE `osx_vest` (
  `id` int(11) NOT NULL COMMENT 'id',
  `avatar` text NOT NULL COMMENT '头像',
  `nickname` text NOT NULL COMMENT '昵称',
  `attribute` text NOT NULL COMMENT '属性',
  `phone` text NOT NULL COMMENT '手机',
  `sex` int(11) NOT NULL COMMENT '性别',
  `mark` text NOT NULL COMMENT '个人简介',
  `create_time` int(11) NOT NULL COMMENT '注入时间',
  `follow_count` int(11) NOT NULL COMMENT '关注数',
  `status` int(11) NOT NULL COMMENT '状态',
  `bind_uid` int(11) NOT NULL COMMENT '绑定的uid',
  `signature` text NOT NULL COMMENT '个人简介',
  `password` text NOT NULL COMMENT '密码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_website_connect_action_notify`
--

CREATE TABLE `osx_website_connect_action_notify` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '操作用户id',
  `to_id` int(11) NOT NULL COMMENT '操作对象id，如帖子id',
  `to_uid` int(11) NOT NULL COMMENT '操作对象所属uid',
  `action` varchar(100) NOT NULL COMMENT '行为标识',
  `action_token` varchar(100) NOT NULL COMMENT '单次行为唯一标识',
  `num` tinyint(2) NOT NULL COMMENT '第num次请求',
  `send_time` int(11) NOT NULL COMMENT '计划请求时间',
  `last_false_reason` varchar(200) NOT NULL COMMENT '上次请求失败原因',
  `false_reason` varchar(200) NOT NULL COMMENT '本次请求失败原因',
  `notify_status` tinyint(2) NOT NULL COMMENT '请求状态。2：待发起，1：发起并响应成功，0：发起失败，-1：请求数据异常，取消通知'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为通知第三方-支持请求重试' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_website_connect_token`
--

CREATE TABLE `osx_website_connect_token` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `user_token` varchar(50) NOT NULL COMMENT '用户第三方平台唯一标识',
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方平台用户唯一标识和OSX平台用户一对一绑定关系' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_media`
--

CREATE TABLE `osx_wechat_media` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '微信视频音频id',
  `type` varchar(16) NOT NULL COMMENT '回复类型',
  `path` varchar(128) NOT NULL COMMENT '文件路径',
  `media_id` varchar(64) NOT NULL COMMENT '微信服务器返回的id',
  `url` varchar(256) NOT NULL COMMENT '地址',
  `temporary` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否永久或者临时 0永久1临时',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信回复表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_message`
--

CREATE TABLE `osx_wechat_message` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '用户行为记录id',
  `openid` varchar(32) NOT NULL COMMENT '用户openid',
  `type` varchar(32) NOT NULL COMMENT '操作类型',
  `result` varchar(512) NOT NULL COMMENT '操作详细记录',
  `add_time` int(10) UNSIGNED NOT NULL COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为记录表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_news_category`
--

CREATE TABLE `osx_wechat_news_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '图文消息管理ID',
  `cate_name` varchar(255) NOT NULL COMMENT '图文名称',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态',
  `new_id` varchar(255) NOT NULL COMMENT '文章id',
  `add_time` varchar(255) NOT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图文消息管理表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_qrcode`
--

CREATE TABLE `osx_wechat_qrcode` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '微信二维码ID',
  `third_type` varchar(32) NOT NULL COMMENT '二维码类型',
  `third_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `ticket` varchar(255) NOT NULL COMMENT '二维码参数',
  `expire_seconds` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '二维码有效时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态',
  `add_time` varchar(255) NOT NULL COMMENT '添加时间',
  `url` varchar(255) NOT NULL COMMENT '微信访问url',
  `qrcode_url` varchar(255) NOT NULL COMMENT '微信二维码url',
  `scan` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '被扫的次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信二维码管理表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_wechat_qrcode`
--

INSERT INTO `osx_wechat_qrcode` (`id`, `third_type`, `third_id`, `ticket`, `expire_seconds`, `status`, `add_time`, `url`, `qrcode_url`, `scan`) VALUES
(1, 'spread', 1, 'gQFo8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAya0N2X29zVnJmcWoxMDAwMGcwMzQAAgRAErVdAwQAAAAA', 0, 1, '1572147776', 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQFo8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAya0N2X29zVnJmcWoxMDAwMGcwMzQAAgRAErVdAwQAAAAA', 'http://weixin.qq.com/q/02kCv_osVrfqj10000g034', 0);

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_reply`
--

CREATE TABLE `osx_wechat_reply` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '微信关键字回复id',
  `key` varchar(64) NOT NULL COMMENT '关键字',
  `type` varchar(32) NOT NULL COMMENT '回复类型',
  `data` text NOT NULL COMMENT '回复数据',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0=不可用  1 =可用',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信关键字回复表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_routine_template`
--

CREATE TABLE `osx_wechat_routine_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '模板id',
  `tempkey` char(50) NOT NULL COMMENT '模板编号',
  `name` char(100) NOT NULL COMMENT '模板名',
  `content` varchar(1000) NOT NULL,
  `tempid` char(100) DEFAULT NULL COMMENT '模板ID',
  `add_time` varchar(15) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信模板' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_wechat_routine_template`
--

INSERT INTO `osx_wechat_routine_template` (`id`, `tempkey`, `name`, `content`, `tempid`, `add_time`, `status`) VALUES
(1, '569', '签到提醒', '活动名称：{{thing3.DATA}}\n签到时间：{{time2.DATA}}\n连续签到：{{thing5.DATA}}}', '', '1594793418', 1),
(2, '3574', '拼团成功通知', '订单状态：{{phrase5.DATA}}\n项目名称：{{thing2.DATA}}\n成团时间：{{time6.DATA}}}', '', '1594793418', 1),
(3, '3577', '拼团失败通知', '失败原因：{{thing4.DATA}}\n商品名称：{{thing2.DATA}}\n失败时间：{{date5.DATA}}}', '', '1594793608', 1),
(4, '7456', '订单取消通知', '订单编号：{{character_string4.DATA}}\n商品名称：{{thing8.DATA}}\n取消时间：{{time3.DATA}}\n订单状态：{{phrase6.DATA}}\n备注：{{thing7.DATA}}}', '', '1594793608', 1),
(5, '2027', '支付成功通知', '订单编号：{{character_string4.DATA}}\n支付金额：{{amount1.DATA}}\n商品名称：{{thing6.DATA}}\n支付时间：{{time7.DATA}}}', '', '1594793608', 1),
(6, '5592', '退款通知', '订单编号：{{character_string1.DATA}}\n退款金额：{{amount3.DATA}}\n退款状态：{{phrase4.DATA}}}', '', '1594793608', 1),
(7, '1885', '待付款提醒', '订单编号：{{character_string1.DATA}}\n商品名称：{{name2.DATA}}\n下单时间：{{date3.DATA}}\n下单金额：{{amount4.DATA}}\n温馨提示：{{thing5.DATA}}}', '', '1594793608', 1),
(8, '1417', '发货提醒', '订单编号：{{number1.DATA}}\n商品名称：{{thing3.DATA}}\n订单状态：{{phrase5.DATA}}\n快递公司：{{thing11.DATA}}\n快递单号：{{character_string12.DATA}}}', '', '1594793608', 1),
(9, '3576', '分销审核通知', '审核结果：{{phrase3.DATA}}\n申请名称：{{name1.DATA}}\n联系方式：{{phone_number2.DATA}}\n申请程序：{{thing4.DATA}}}', '', '1594793608', 1),
(10, '1883', '提现结果通知	', '状态：{{phrase2.DATA}}\n时间：{{time3.DATA}}\n备注：{{thing4.DATA}}}', '', '1594793608', 1),
(11, '5710', '邀请结果提醒', '邀请者：{{thing3.DATA}}\n被邀请者：{{thing4.DATA}}\n时间：{{time5.DATA}}}', '', '1594793608', 1),
(12, '3264', '认证审核通知', '审核结果：{{phrase1.DATA}}\n认证内容：{{thing2.DATA}}\n申请时间：{{date3.DATA}}\n备注：{{thing4.DATA}}}', '', '1594793608', 1),
(13, '5989', '被举报通知', '违规内容：{{thing1.DATA}}\n违规原因：{{thing2.DATA}}\n温馨提示：{{thing3.DATA}}}', '', '1594793608', 1),
(14, '7937', '举报结果通知', '举报结果：{{name1.DATA}}\n结果说明：{{thing2.DATA}}}', '', '1594793608', 1),
(15, '6897', '加圈拒绝通知', '申请人：{{name1.DATA}}\n申请时间：{{date2.DATA}}\n申请圈子：{{thing3.DATA}}\n拒绝理由：{{thing4.DATA}}}', '', '1594793608', 1),
(16, '1905', '加圈成功提醒', '加入时间：{{date1.DATA}}\n圈子名称：{{thing2.DATA}}\n备注：{{thing3.DATA}}}', '', '1594793608', 1),
(17, '9007', '评论点赞通知', '评论内容：{{thing1.DATA}}\n点赞用户：{{thing2.DATA}}\n点赞时间：{{time4.DATA}}\n备注：{{thing5.DATA}}}', '', '1594793608', 1),
(18, '3206', '评论回复通知', '回复者：{{thing3.DATA}}\n时间：{{time4.DATA}}\n回复内容：{{thing2.DATA}}\n备注：{{thing5.DATA}}}', '', '1594793608', 1),
(19, '484', '新的评论提醒', '评论内容：{{thing2.DATA}}\n评论时间：{{time3.DATA}}\n评论用户：{{thing5.DATA}}\n帖子内容：{{thing6.DATA}}}', '', '1594793608', 1),
(20, '11671', '评论删除提醒', '评论内容：{{thing1.DATA}}\n删除原因：{{thing2.DATA}}}', '', '1594793608', 1),
(21, '10173', '帖子被赞通知', '点赞人：{{keyword1.DATA}}\n点赞时间：{{keyword2.DATA}}\n帖子内容：{{keyword3.DATA}}}', '', '1594793608', 1),
(22, '3540', '帖子被收藏通知', '收藏用户：{{name1.DATA}}\n收藏时间：{{time2.DATA}}\n收藏帖子：{{thing3.DATA}}}', '', '1594793608', 1),
(23, '4190', '帖子被分享通知', '分享人：{{thing1.DATA}}\n分享时间：{{date2.DATA}}\n帖子内容：{{thing4.DATA}}}', '', '1594793608', 1),
(24, '8517', '帖子审核通知', '帖子内容：{{thing1.DATA}}\n审核状态：{{thing2.DATA}}\n审核时间：{{time4.DATA}}\n温馨提示：{{thing5.DATA}}}', '', '1594793608', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_template`
--

CREATE TABLE `osx_wechat_template` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '模板id',
  `tempkey` char(50) NOT NULL COMMENT '模板编号',
  `name` char(100) NOT NULL COMMENT '模板名',
  `content` varchar(1000) NOT NULL COMMENT '回复内容',
  `tempid` char(100) DEFAULT NULL COMMENT '模板ID',
  `add_time` varchar(15) NOT NULL COMMENT '添加时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信模板' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_wechat_template`
--

INSERT INTO `osx_wechat_template` (`id`, `tempkey`, `name`, `content`, `tempid`, `add_time`, `status`) VALUES
(1, 'OPENTM201490123', '订单取消通知', '{{first.DATA}}订单编号：{{keyword1.DATA}}\n商品详情：{{keyword2.DATA}}\n订单金额：{{keyword3.DATA}}\n{{remark.DATA}}', '', '1515052638', 1),
(2, 'OPENTM411665300', '退款通知', '{{first.DATA}}\n退款金额：{{keyword1.DATA}}\n到账时间：{{keyword2.DATA}}\n{{remark.DATA}}', '', '1528966701', 1),
(3, 'OPENTM410578602', '订单发货通知', '{{first.DATA}}收货人：{{keyword1.DATA}}\n收货人手机号：{{keyword2.DATA}}\n快递公司：{{keyword3.DATA}}\n快递单号：{{keyword4.DATA}}\n订单号：{{keyword5.DATA}}\n{{remark.DATA}}', '', '1515052638', 1),
(4, 'OPENTM412181252', '签到成功通知', '{{first.DATA}}\n签到人：{{keyword1.DATA}}\n签到时间：{{keyword2.DATA}}\n签到状态：{{keyword3.DATA}}\n{{remark.DATA}}', '', '1528966759', 1),
(5, 'OPENTM414889350', '退款成功通知', '{{first.DATA}}\n订单编号：{{keyword1.DATA}}\n退款金额：{{keyword2.DATA}}\n{{remark.DATA}}', '', '1528966701', 1),
(12, 'TM00181', '用户登录提醒', '{{first.DATA}}\n帐号登录时间：{{time.DATA}}\n帐号登录IP：{{ip.DATA}}\n{{remark.DATA}}', '', '1528966701', 1),
(13, 'OPENTM412865802', '下单成功通知', '{{first.DATA}}\n订单号：{{keyword1.DATA}}\n下单时间：{{keyword2.DATA}}\n收货人：{{keyword3.DATA}}\n收货地址：{{keyword4.DATA}}\n支付费用：{{keyword5.DATA}}\n{{remark.DATA}}', '', '1528966759', 1);

-- --------------------------------------------------------

--
-- 表的结构 `osx_wechat_user`
--

CREATE TABLE `osx_wechat_user` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '微信用户id',
  `unionid` varchar(30) DEFAULT NULL COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段',
  `openid` varchar(30) DEFAULT NULL COMMENT '用户的标识，对当前公众号唯一',
  `routine_openid` varchar(32) DEFAULT NULL COMMENT '小程序唯一身份ID',
  `nickname` varchar(64) NOT NULL COMMENT '用户的昵称',
  `headimgurl` varchar(256) NOT NULL COMMENT '用户头像',
  `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `city` varchar(64) NOT NULL COMMENT '用户所在城市',
  `language` varchar(64) NOT NULL COMMENT '用户的语言，简体中文为zh_CN',
  `province` varchar(64) NOT NULL COMMENT '用户所在省份',
  `country` varchar(64) NOT NULL COMMENT '用户所在国家',
  `remark` varchar(256) DEFAULT NULL COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `groupid` int(10) UNSIGNED DEFAULT '0' COMMENT '用户所在的分组ID（兼容旧的用户分组接口）',
  `tagid_list` varchar(256) DEFAULT NULL COMMENT '用户被打上的标签ID列表',
  `subscribe` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '用户是否订阅该公众号标识',
  `subscribe_time` int(10) UNSIGNED DEFAULT NULL COMMENT '关注公众号时间',
  `add_time` int(10) UNSIGNED DEFAULT NULL COMMENT '添加时间',
  `stair` int(11) UNSIGNED DEFAULT NULL COMMENT '一级推荐人',
  `second` int(11) UNSIGNED DEFAULT NULL COMMENT '二级推荐人',
  `order_stair` int(11) DEFAULT NULL COMMENT '一级推荐人订单',
  `order_second` int(11) UNSIGNED DEFAULT NULL COMMENT '二级推荐人订单',
  `now_money` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT '佣金',
  `session_key` varchar(32) DEFAULT NULL COMMENT '小程序用户会话密匙',
  `user_type` varchar(32) DEFAULT 'wechat' COMMENT '用户类型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `osx_wechat_user`
--

INSERT INTO `osx_wechat_user` (`uid`, `unionid`, `openid`, `routine_openid`, `nickname`, `headimgurl`, `sex`, `city`, `language`, `province`, `country`, `remark`, `groupid`, `tagid_list`, `subscribe`, `subscribe_time`, `add_time`, `stair`, `second`, `order_stair`, `order_second`, `now_money`, `session_key`, `user_type`) VALUES
(1, '', NULL, 'o9qvr4iV5qWtMWyIDbZ7K6N7UG8o', '想天软件', 'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqj70fHkbW9aJgp0KWMsp7cqOsgT16Syr8mWt9JkhngDWARibyNv5MBia3h8Y3BOkHBHdLiaX8Hq9J0w/132', 1, '安康', 'zh_CN', '陕西', '中国', NULL, 0, NULL, 1, NULL, 1555153423, NULL, NULL, NULL, NULL, NULL, 'BXSON6AH+vFXz7YnykLLzw==', 'routine');

-- --------------------------------------------------------

--
-- 表的结构 `osx_week_order_task`
--

CREATE TABLE `osx_week_order_task` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT '0' COMMENT '每周开始时间',
  `end` int(11) DEFAULT '0' COMMENT '每周结束时间',
  `total` decimal(8,2) DEFAULT NULL COMMENT '消费总金额',
  `nums` int(11) DEFAULT NULL COMMENT '消费次数',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_withdraw_order`
--

CREATE TABLE `osx_withdraw_order` (
  `id` int(11) NOT NULL COMMENT 'id',
  `order_id` varchar(255) NOT NULL COMMENT '订单id',
  `rate` float NOT NULL COMMENT '手续费利润',
  `reality_money` decimal(10,2) NOT NULL COMMENT '实际提现金额',
  `type` varchar(100) NOT NULL COMMENT '提现类型',
  `account` varchar(255) NOT NULL COMMENT '提现账号',
  `name` varchar(255) NOT NULL COMMENT '提现账号昵称',
  `code` text NOT NULL COMMENT '收款码',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `uid` int(11) NOT NULL COMMENT '报名用户',
  `money` decimal(10,2) NOT NULL COMMENT '提现金额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `osx_zidingyi_renwu`
--

CREATE TABLE `osx_zidingyi_renwu` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `jifenflag` varchar(50) DEFAULT NULL COMMENT '积分标识',
  `url` varchar(200) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `osx_action_log`
--
ALTER TABLE `osx_action_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_all_agreement`
--
ALTER TABLE `osx_all_agreement`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_app_version`
--
ALTER TABLE `osx_app_version`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_article`
--
ALTER TABLE `osx_article`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_article_category`
--
ALTER TABLE `osx_article_category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_article_content`
--
ALTER TABLE `osx_article_content`
  ADD UNIQUE KEY `nid` (`nid`) USING BTREE;

--
-- 表的索引 `osx_bind_forum_group`
--
ALTER TABLE `osx_bind_forum_group`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_bind_group_power`
--
ALTER TABLE `osx_bind_group_power`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `osx_bind_group_uid`
--
ALTER TABLE `osx_bind_group_uid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- 表的索引 `osx_bind_uid_cid`
--
ALTER TABLE `osx_bind_uid_cid`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `osx_bind_user_log`
--
ALTER TABLE `osx_bind_user_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_blacklist`
--
ALTER TABLE `osx_blacklist`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_cache`
--
ALTER TABLE `osx_cache`
  ADD PRIMARY KEY (`key`) USING BTREE;

--
-- 表的索引 `osx_cache_flush`
--
ALTER TABLE `osx_cache_flush`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_cash_out`
--
ALTER TABLE `osx_cash_out`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_cate`
--
ALTER TABLE `osx_certification_cate`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `table_name` (`table_name`);

--
-- 表的索引 `osx_certification_cate_condition`
--
ALTER TABLE `osx_certification_cate_condition`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_cate_datum`
--
ALTER TABLE `osx_certification_cate_datum`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_cate_privilege`
--
ALTER TABLE `osx_certification_cate_privilege`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_condition`
--
ALTER TABLE `osx_certification_condition`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_datum`
--
ALTER TABLE `osx_certification_datum`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `field` (`field`);

--
-- 表的索引 `osx_certification_entity`
--
ALTER TABLE `osx_certification_entity`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_faq`
--
ALTER TABLE `osx_certification_faq`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_privilege`
--
ALTER TABLE `osx_certification_privilege`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_certification_type`
--
ALTER TABLE `osx_certification_type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel`
--
ALTER TABLE `osx_channel`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_admin`
--
ALTER TABLE `osx_channel_admin`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_count_content`
--
ALTER TABLE `osx_channel_count_content`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_count_open_rate`
--
ALTER TABLE `osx_channel_count_open_rate`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_count_view`
--
ALTER TABLE `osx_channel_count_view`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_count_view_log`
--
ALTER TABLE `osx_channel_count_view_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_post`
--
ALTER TABLE `osx_channel_post`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_post_hide`
--
ALTER TABLE `osx_channel_post_hide`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_post_pool`
--
ALTER TABLE `osx_channel_post_pool`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_recommend_log`
--
ALTER TABLE `osx_channel_recommend_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_channel_user`
--
ALTER TABLE `osx_channel_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_collect`
--
ALTER TABLE `osx_collect`
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `tid` (`tid`) USING BTREE,
  ADD KEY `uid_tid` (`uid`,`tid`) USING BTREE;

--
-- 表的索引 `osx_column_author`
--
ALTER TABLE `osx_column_author`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_column_category`
--
ALTER TABLE `osx_column_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_base` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `add_time` (`create_time`) USING BTREE;

--
-- 表的索引 `osx_column_class`
--
ALTER TABLE `osx_column_class`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_column_class_product`
--
ALTER TABLE `osx_column_class_product`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_column_collect`
--
ALTER TABLE `osx_column_collect`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_column_coupon`
--
ALTER TABLE `osx_column_coupon`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `state` (`status`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `coupon_time` (`coupon_time`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE;

--
-- 表的索引 `osx_column_coupon_issue`
--
ALTER TABLE `osx_column_coupon_issue`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `start_time` (`start_time`,`end_time`) USING BTREE,
  ADD KEY `remain_count` (`remain_count`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE;

--
-- 表的索引 `osx_column_coupon_issue_user`
--
ALTER TABLE `osx_column_coupon_issue_user`
  ADD UNIQUE KEY `uid` (`uid`,`issue_coupon_id`) USING BTREE;

--
-- 表的索引 `osx_column_coupon_user`
--
ALTER TABLE `osx_column_coupon_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `end_time` (`end_time`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_fail` (`is_fail`) USING BTREE;

--
-- 表的索引 `osx_column_product_reply`
--
ALTER TABLE `osx_column_product_reply`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_id_2` (`oid`,`unique`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `parent_id` (`reply_type`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `product_score` (`product_score`) USING BTREE,
  ADD KEY `service_score` (`service_score`) USING BTREE;

--
-- 表的索引 `osx_column_reply`
--
ALTER TABLE `osx_column_reply`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `parent_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_column_text`
--
ALTER TABLE `osx_column_text`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE;

--
-- 表的索引 `osx_column_user_buy`
--
ALTER TABLE `osx_column_user_buy`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_comment_census`
--
ALTER TABLE `osx_comment_census`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_comment_report`
--
ALTER TABLE `osx_comment_report`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_comment_template`
--
ALTER TABLE `osx_comment_template`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_community_count`
--
ALTER TABLE `osx_community_count`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_adv`
--
ALTER TABLE `osx_com_adv`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_adv_platform`
--
ALTER TABLE `osx_com_adv_platform`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_announce`
--
ALTER TABLE `osx_com_announce`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_announce_user`
--
ALTER TABLE `osx_com_announce_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_draft`
--
ALTER TABLE `osx_com_draft`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_forum`
--
ALTER TABLE `osx_com_forum`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_forum_admin`
--
ALTER TABLE `osx_com_forum_admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_forum_admin_apply`
--
ALTER TABLE `osx_com_forum_admin_apply`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_forum_admin_score`
--
ALTER TABLE `osx_com_forum_admin_score`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_forum_admin_score_log`
--
ALTER TABLE `osx_com_forum_admin_score_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_forum_member`
--
ALTER TABLE `osx_com_forum_member`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_nav`
--
ALTER TABLE `osx_com_nav`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_post`
--
ALTER TABLE `osx_com_post`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_site`
--
ALTER TABLE `osx_com_site`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_thread`
--
ALTER TABLE `osx_com_thread`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_thread_class`
--
ALTER TABLE `osx_com_thread_class`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_thread_draft`
--
ALTER TABLE `osx_com_thread_draft`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_com_topic`
--
ALTER TABLE `osx_com_topic`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_topic_class`
--
ALTER TABLE `osx_com_topic_class`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_com_topic_follow`
--
ALTER TABLE `osx_com_topic_follow`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event`
--
ALTER TABLE `osx_event`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_bind_group`
--
ALTER TABLE `osx_event_bind_group`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_category`
--
ALTER TABLE `osx_event_category`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_check`
--
ALTER TABLE `osx_event_check`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_collect`
--
ALTER TABLE `osx_event_collect`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `eid` (`eid`) USING BTREE,
  ADD KEY `uid_eid` (`uid`,`eid`) USING BTREE;

--
-- 表的索引 `osx_event_enroller`
--
ALTER TABLE `osx_event_enroller`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_enroller_info`
--
ALTER TABLE `osx_event_enroller_info`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_event_field`
--
ALTER TABLE `osx_event_field`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_express`
--
ALTER TABLE `osx_express`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `code` (`code`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE;

--
-- 表的索引 `osx_forum_census`
--
ALTER TABLE `osx_forum_census`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_forum_power`
--
ALTER TABLE `osx_forum_power`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_forum_report`
--
ALTER TABLE `osx_forum_report`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_forum_visit_audit`
--
ALTER TABLE `osx_forum_visit_audit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_group`
--
ALTER TABLE `osx_group`
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `osx_head_login`
--
ALTER TABLE `osx_head_login`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `osx_hot_census`
--
ALTER TABLE `osx_hot_census`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_invite_code`
--
ALTER TABLE `osx_invite_code`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_invite_level`
--
ALTER TABLE `osx_invite_level`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_invite_log`
--
ALTER TABLE `osx_invite_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_invite_reward`
--
ALTER TABLE `osx_invite_reward`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_invite_share`
--
ALTER TABLE `osx_invite_share`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_login_faq`
--
ALTER TABLE `osx_login_faq`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_message`
--
ALTER TABLE `osx_message`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_message_news`
--
ALTER TABLE `osx_message_news`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_message_read`
--
ALTER TABLE `osx_message_read`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_message_register`
--
ALTER TABLE `osx_message_register`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_message_template`
--
ALTER TABLE `osx_message_template`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_message_type`
--
ALTER TABLE `osx_message_type`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_message_user_popup`
--
ALTER TABLE `osx_message_user_popup`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_os_token`
--
ALTER TABLE `osx_os_token`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_payment_profit`
--
ALTER TABLE `osx_payment_profit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_pay_set`
--
ALTER TABLE `osx_pay_set`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_pc_set`
--
ALTER TABLE `osx_pc_set`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_picture`
--
ALTER TABLE `osx_picture`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_power`
--
ALTER TABLE `osx_power`
  ADD UNIQUE KEY `id` (`id`);

--
-- 表的索引 `osx_prohibit`
--
ALTER TABLE `osx_prohibit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_prohibit_reason`
--
ALTER TABLE `osx_prohibit_reason`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_qiandao`
--
ALTER TABLE `osx_qiandao`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank`
--
ALTER TABLE `osx_rank`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_del`
--
ALTER TABLE `osx_rank_del`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_search`
--
ALTER TABLE `osx_rank_search`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_thread`
--
ALTER TABLE `osx_rank_thread`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_thread_time`
--
ALTER TABLE `osx_rank_thread_time`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_topic`
--
ALTER TABLE `osx_rank_topic`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_user`
--
ALTER TABLE `osx_rank_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_rank_user_time`
--
ALTER TABLE `osx_rank_user_time`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_renwu_jiafen_log`
--
ALTER TABLE `osx_renwu_jiafen_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_renwu_nav`
--
ALTER TABLE `osx_renwu_nav`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_report`
--
ALTER TABLE `osx_report`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_report_prohibit`
--
ALTER TABLE `osx_report_prohibit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_report_reason`
--
ALTER TABLE `osx_report_reason`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_routine_access_token`
--
ALTER TABLE `osx_routine_access_token`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_routine_ad`
--
ALTER TABLE `osx_routine_ad`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_routine_ad_position`
--
ALTER TABLE `osx_routine_ad_position`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_routine_form_id`
--
ALTER TABLE `osx_routine_form_id`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_routine_qrcode`
--
ALTER TABLE `osx_routine_qrcode`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_routine_template`
--
ALTER TABLE `osx_routine_template`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `tempkey` (`tempkey`) USING BTREE;

--
-- 表的索引 `osx_script`
--
ALTER TABLE `osx_script`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_search`
--
ALTER TABLE `osx_search`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_sell`
--
ALTER TABLE `osx_sell`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_sell_order`
--
ALTER TABLE `osx_sell_order`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_sensitive`
--
ALTER TABLE `osx_sensitive`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_sensitive_log`
--
ALTER TABLE `osx_sensitive_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_shop_column`
--
ALTER TABLE `osx_shop_column`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_shop_order`
--
ALTER TABLE `osx_shop_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_shop_order_status`
--
ALTER TABLE `osx_shop_order_status`
  ADD KEY `oid` (`oid`) USING BTREE,
  ADD KEY `change_type` (`change_type`) USING BTREE;

--
-- 表的索引 `osx_shop_product`
--
ALTER TABLE `osx_shop_product`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_shop_score_type`
--
ALTER TABLE `osx_shop_score_type`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_stat_reg_info`
--
ALTER TABLE `osx_stat_reg_info`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_store_bargain`
--
ALTER TABLE `osx_store_bargain`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_bargain_user`
--
ALTER TABLE `osx_store_bargain_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_bargain_user_help`
--
ALTER TABLE `osx_store_bargain_user_help`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_cart`
--
ALTER TABLE `osx_store_cart`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`uid`) USING BTREE,
  ADD KEY `goods_id` (`product_id`) USING BTREE,
  ADD KEY `uid` (`uid`,`is_pay`) USING BTREE,
  ADD KEY `uid_2` (`uid`,`is_del`) USING BTREE,
  ADD KEY `uid_3` (`uid`,`is_new`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `osx_store_category`
--
ALTER TABLE `osx_store_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_base` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `osx_store_category_column`
--
ALTER TABLE `osx_store_category_column`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_base` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `osx_store_combination`
--
ALTER TABLE `osx_store_combination`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_combination_attr`
--
ALTER TABLE `osx_store_combination_attr`
  ADD KEY `store_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_combination_attr_result`
--
ALTER TABLE `osx_store_combination_attr_result`
  ADD UNIQUE KEY `product_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_combination_attr_value`
--
ALTER TABLE `osx_store_combination_attr_value`
  ADD UNIQUE KEY `unique` (`unique`,`suk`) USING BTREE,
  ADD KEY `store_id` (`product_id`,`suk`) USING BTREE;

--
-- 表的索引 `osx_store_coupon`
--
ALTER TABLE `osx_store_coupon`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `state` (`status`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `coupon_time` (`coupon_time`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE;

--
-- 表的索引 `osx_store_coupon_issue`
--
ALTER TABLE `osx_store_coupon_issue`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `start_time` (`start_time`,`end_time`) USING BTREE,
  ADD KEY `remain_count` (`remain_count`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE;

--
-- 表的索引 `osx_store_coupon_issue_user`
--
ALTER TABLE `osx_store_coupon_issue_user`
  ADD UNIQUE KEY `uid` (`uid`,`issue_coupon_id`) USING BTREE;

--
-- 表的索引 `osx_store_coupon_user`
--
ALTER TABLE `osx_store_coupon_user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `end_time` (`end_time`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_fail` (`is_fail`) USING BTREE;

--
-- 表的索引 `osx_store_order`
--
ALTER TABLE `osx_store_order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_id_2` (`order_id`,`uid`) USING BTREE,
  ADD UNIQUE KEY `unique` (`unique`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `pay_price` (`pay_price`) USING BTREE,
  ADD KEY `paid` (`paid`) USING BTREE,
  ADD KEY `pay_time` (`pay_time`) USING BTREE,
  ADD KEY `pay_type` (`pay_type`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `coupon_id` (`coupon_id`) USING BTREE;

--
-- 表的索引 `osx_store_order_cart_info`
--
ALTER TABLE `osx_store_order_cart_info`
  ADD UNIQUE KEY `oid` (`oid`,`unique`) USING BTREE,
  ADD KEY `cart_id` (`cart_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_order_status`
--
ALTER TABLE `osx_store_order_status`
  ADD KEY `oid` (`oid`) USING BTREE,
  ADD KEY `change_type` (`change_type`) USING BTREE;

--
-- 表的索引 `osx_store_pink`
--
ALTER TABLE `osx_store_pink`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_product`
--
ALTER TABLE `osx_store_product`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cate_id` (`cate_id`) USING BTREE,
  ADD KEY `is_hot` (`is_hot`) USING BTREE,
  ADD KEY `is_benefit` (`is_benefit`) USING BTREE,
  ADD KEY `is_best` (`is_best`) USING BTREE,
  ADD KEY `is_new` (`is_new`) USING BTREE,
  ADD KEY `toggle_on_sale, is_del` (`is_del`) USING BTREE,
  ADD KEY `price` (`price`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `sales` (`sales`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `is_postage` (`is_postage`) USING BTREE;

--
-- 表的索引 `osx_store_product_attr`
--
ALTER TABLE `osx_store_product_attr`
  ADD KEY `store_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_product_attr_result`
--
ALTER TABLE `osx_store_product_attr_result`
  ADD UNIQUE KEY `product_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_product_attr_value`
--
ALTER TABLE `osx_store_product_attr_value`
  ADD UNIQUE KEY `unique` (`unique`,`suk`) USING BTREE,
  ADD KEY `store_id` (`product_id`,`suk`) USING BTREE;

--
-- 表的索引 `osx_store_product_relation`
--
ALTER TABLE `osx_store_product_relation`
  ADD UNIQUE KEY `uid` (`uid`,`product_id`,`type`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `category` (`category`) USING BTREE;

--
-- 表的索引 `osx_store_product_reply`
--
ALTER TABLE `osx_store_product_reply`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_id_2` (`oid`,`unique`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `parent_id` (`reply_type`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `product_score` (`product_score`) USING BTREE,
  ADD KEY `service_score` (`service_score`) USING BTREE;

--
-- 表的索引 `osx_store_product_services`
--
ALTER TABLE `osx_store_product_services`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_product_text_reply`
--
ALTER TABLE `osx_store_product_text_reply`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `parent_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_seckill`
--
ALTER TABLE `osx_store_seckill`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `product_id` (`product_id`) USING BTREE,
  ADD KEY `start_time` (`start_time`,`stop_time`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `is_hot` (`is_hot`) USING BTREE,
  ADD KEY `is_show` (`status`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `sort` (`sort`) USING BTREE,
  ADD KEY `is_postage` (`is_postage`) USING BTREE;

--
-- 表的索引 `osx_store_seckill_attr`
--
ALTER TABLE `osx_store_seckill_attr`
  ADD KEY `store_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_seckill_attr_result`
--
ALTER TABLE `osx_store_seckill_attr_result`
  ADD UNIQUE KEY `product_id` (`product_id`) USING BTREE;

--
-- 表的索引 `osx_store_seckill_attr_value`
--
ALTER TABLE `osx_store_seckill_attr_value`
  ADD UNIQUE KEY `unique` (`unique`,`suk`) USING BTREE,
  ADD KEY `store_id` (`product_id`,`suk`) USING BTREE;

--
-- 表的索引 `osx_store_service`
--
ALTER TABLE `osx_store_service`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_service_log`
--
ALTER TABLE `osx_store_service_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_store_set`
--
ALTER TABLE `osx_store_set`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_store_visit`
--
ALTER TABLE `osx_store_visit`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_support`
--
ALTER TABLE `osx_support`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_admin`
--
ALTER TABLE `osx_system_admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `account` (`account`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- 表的索引 `osx_system_attachment`
--
ALTER TABLE `osx_system_attachment`
  ADD PRIMARY KEY (`att_id`) USING BTREE;

--
-- 表的索引 `osx_system_attachment_category`
--
ALTER TABLE `osx_system_attachment_category`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- 表的索引 `osx_system_config`
--
ALTER TABLE `osx_system_config`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_config_tab`
--
ALTER TABLE `osx_system_config_tab`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_count_log_share`
--
ALTER TABLE `osx_system_count_log_share`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_count_log_to_show`
--
ALTER TABLE `osx_system_count_log_to_show`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_count_log_user`
--
ALTER TABLE `osx_system_count_log_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_count_log_view`
--
ALTER TABLE `osx_system_count_log_view`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_file`
--
ALTER TABLE `osx_system_file`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_grade_desc`
--
ALTER TABLE `osx_system_grade_desc`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_group`
--
ALTER TABLE `osx_system_group`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `config_name` (`config_name`) USING BTREE;

--
-- 表的索引 `osx_system_group_data`
--
ALTER TABLE `osx_system_group_data`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_jifen`
--
ALTER TABLE `osx_system_jifen`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_log`
--
ALTER TABLE `osx_system_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `admin_id` (`admin_id`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE;

--
-- 表的索引 `osx_system_mall_grade`
--
ALTER TABLE `osx_system_mall_grade`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_menus`
--
ALTER TABLE `osx_system_menus`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE,
  ADD KEY `is_show` (`is_show`) USING BTREE,
  ADD KEY `access` (`access`) USING BTREE;

--
-- 表的索引 `osx_system_notice`
--
ALTER TABLE `osx_system_notice`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `type` (`type`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- 表的索引 `osx_system_notice_admin`
--
ALTER TABLE `osx_system_notice_admin`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `admin_id` (`admin_id`,`notice_type`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `is_visit` (`is_visit`) USING BTREE,
  ADD KEY `is_click` (`is_click`) USING BTREE;

--
-- 表的索引 `osx_system_renwu`
--
ALTER TABLE `osx_system_renwu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_role`
--
ALTER TABLE `osx_system_role`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- 表的索引 `osx_system_rule`
--
ALTER TABLE `osx_system_rule`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_rule_action`
--
ALTER TABLE `osx_system_rule_action`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_user_grade`
--
ALTER TABLE `osx_system_user_grade`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_system_user_level`
--
ALTER TABLE `osx_system_user_level`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_system_user_task`
--
ALTER TABLE `osx_system_user_task`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_talk`
--
ALTER TABLE `osx_talk`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_talk_content`
--
ALTER TABLE `osx_talk_content`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_tencent_file`
--
ALTER TABLE `osx_tencent_file`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_text_user`
--
ALTER TABLE `osx_text_user`
  ADD PRIMARY KEY (`aid`);

--
-- 表的索引 `osx_thread_census`
--
ALTER TABLE `osx_thread_census`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_token`
--
ALTER TABLE `osx_token`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user`
--
ALTER TABLE `osx_user`
  ADD PRIMARY KEY (`uid`) USING BTREE,
  ADD KEY `account` (`account`) USING BTREE,
  ADD KEY `spreaduid` (`spread_uid`) USING BTREE,
  ADD KEY `level` (`level`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `is_promoter` (`is_promoter`) USING BTREE;

--
-- 表的索引 `osx_user_address`
--
ALTER TABLE `osx_user_address`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `is_default` (`is_default`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE;

--
-- 表的索引 `osx_user_agreement`
--
ALTER TABLE `osx_user_agreement`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_bill`
--
ALTER TABLE `osx_user_bill`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `openid` (`uid`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `pm` (`pm`) USING BTREE,
  ADD KEY `type` (`category`,`type`,`link_id`) USING BTREE;

--
-- 表的索引 `osx_user_buy_product`
--
ALTER TABLE `osx_user_buy_product`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_enter`
--
ALTER TABLE `osx_user_enter`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `uid` (`uid`) USING BTREE,
  ADD KEY `province` (`province`,`city`,`district`) USING BTREE,
  ADD KEY `is_lock` (`is_lock`) USING BTREE,
  ADD KEY `is_del` (`is_del`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- 表的索引 `osx_user_extract`
--
ALTER TABLE `osx_user_extract`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `extract_type` (`extract_type`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `openid` (`uid`) USING BTREE,
  ADD KEY `fail_time` (`fail_time`) USING BTREE;

--
-- 表的索引 `osx_user_follow`
--
ALTER TABLE `osx_user_follow`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_friend`
--
ALTER TABLE `osx_user_friend`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_group`
--
ALTER TABLE `osx_user_group`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_level`
--
ALTER TABLE `osx_user_level`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- 表的索引 `osx_user_login_log`
--
ALTER TABLE `osx_user_login_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_login_set`
--
ALTER TABLE `osx_user_login_set`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_notice`
--
ALTER TABLE `osx_user_notice`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_notice_see`
--
ALTER TABLE `osx_user_notice_see`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_order`
--
ALTER TABLE `osx_user_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_order_log`
--
ALTER TABLE `osx_user_order_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_picture`
--
ALTER TABLE `osx_user_picture`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `osx_user_rank`
--
ALTER TABLE `osx_user_rank`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `uid` (`uid`) USING BTREE;

--
-- 表的索引 `osx_user_read`
--
ALTER TABLE `osx_user_read`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_recharge`
--
ALTER TABLE `osx_user_recharge`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_id` (`order_id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `recharge_type` (`recharge_type`) USING BTREE,
  ADD KEY `paid` (`paid`) USING BTREE;

--
-- 表的索引 `osx_user_recommend`
--
ALTER TABLE `osx_user_recommend`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_register`
--
ALTER TABLE `osx_user_register`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_report`
--
ALTER TABLE `osx_user_report`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_reward`
--
ALTER TABLE `osx_user_reward`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `pid` (`pid`) USING BTREE;

--
-- 表的索引 `osx_user_share`
--
ALTER TABLE `osx_user_share`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_sign`
--
ALTER TABLE `osx_user_sign`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE;

--
-- 表的索引 `osx_user_support_card`
--
ALTER TABLE `osx_user_support_card`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_sync_login`
--
ALTER TABLE `osx_user_sync_login`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_task_day`
--
ALTER TABLE `osx_user_task_day`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_task_finish`
--
ALTER TABLE `osx_user_task_finish`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE;

--
-- 表的索引 `osx_user_task_new`
--
ALTER TABLE `osx_user_task_new`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_verify`
--
ALTER TABLE `osx_user_verify`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_user_visit`
--
ALTER TABLE `osx_user_visit`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_user_wallet`
--
ALTER TABLE `osx_user_wallet`
  ADD PRIMARY KEY (`uid`);

--
-- 表的索引 `osx_user_wanshan`
--
ALTER TABLE `osx_user_wanshan`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_vest`
--
ALTER TABLE `osx_vest`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_website_connect_action_notify`
--
ALTER TABLE `osx_website_connect_action_notify`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_website_connect_token`
--
ALTER TABLE `osx_website_connect_token`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_wechat_media`
--
ALTER TABLE `osx_wechat_media`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `type` (`type`,`media_id`) USING BTREE,
  ADD KEY `type_2` (`type`) USING BTREE;

--
-- 表的索引 `osx_wechat_message`
--
ALTER TABLE `osx_wechat_message`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `openid` (`openid`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE;

--
-- 表的索引 `osx_wechat_news_category`
--
ALTER TABLE `osx_wechat_news_category`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `osx_wechat_qrcode`
--
ALTER TABLE `osx_wechat_qrcode`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `third_type` (`third_type`,`third_id`) USING BTREE,
  ADD KEY `ticket` (`ticket`) USING BTREE;

--
-- 表的索引 `osx_wechat_reply`
--
ALTER TABLE `osx_wechat_reply`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `key` (`key`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `hide` (`hide`) USING BTREE;

--
-- 表的索引 `osx_wechat_routine_template`
--
ALTER TABLE `osx_wechat_routine_template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tempkey` (`tempkey`) USING BTREE;

--
-- 表的索引 `osx_wechat_template`
--
ALTER TABLE `osx_wechat_template`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `tempkey` (`tempkey`) USING BTREE;

--
-- 表的索引 `osx_wechat_user`
--
ALTER TABLE `osx_wechat_user`
  ADD PRIMARY KEY (`uid`) USING BTREE,
  ADD UNIQUE KEY `openid` (`openid`) USING BTREE,
  ADD KEY `groupid` (`groupid`) USING BTREE,
  ADD KEY `subscribe_time` (`subscribe_time`) USING BTREE,
  ADD KEY `add_time` (`add_time`) USING BTREE,
  ADD KEY `subscribe` (`subscribe`) USING BTREE,
  ADD KEY `unionid` (`unionid`) USING BTREE;

--
-- 表的索引 `osx_week_order_task`
--
ALTER TABLE `osx_week_order_task`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_withdraw_order`
--
ALTER TABLE `osx_withdraw_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `osx_zidingyi_renwu`
--
ALTER TABLE `osx_zidingyi_renwu`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `osx_action_log`
--
ALTER TABLE `osx_action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_all_agreement`
--
ALTER TABLE `osx_all_agreement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `osx_app_version`
--
ALTER TABLE `osx_app_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_article`
--
ALTER TABLE `osx_article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章管理ID';

--
-- 使用表AUTO_INCREMENT `osx_article_category`
--
ALTER TABLE `osx_article_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文章分类id';

--
-- 使用表AUTO_INCREMENT `osx_bind_forum_group`
--
ALTER TABLE `osx_bind_forum_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_bind_group_power`
--
ALTER TABLE `osx_bind_group_power`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=102;

--
-- 使用表AUTO_INCREMENT `osx_bind_group_uid`
--
ALTER TABLE `osx_bind_group_uid`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_bind_uid_cid`
--
ALTER TABLE `osx_bind_uid_cid`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'uid';

--
-- 使用表AUTO_INCREMENT `osx_bind_user_log`
--
ALTER TABLE `osx_bind_user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_blacklist`
--
ALTER TABLE `osx_blacklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_cache_flush`
--
ALTER TABLE `osx_cache_flush`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `osx_cash_out`
--
ALTER TABLE `osx_cash_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_certification_cate`
--
ALTER TABLE `osx_certification_cate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_certification_cate_condition`
--
ALTER TABLE `osx_certification_cate_condition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_certification_cate_datum`
--
ALTER TABLE `osx_certification_cate_datum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `osx_certification_cate_privilege`
--
ALTER TABLE `osx_certification_cate_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_certification_condition`
--
ALTER TABLE `osx_certification_condition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `osx_certification_datum`
--
ALTER TABLE `osx_certification_datum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- 使用表AUTO_INCREMENT `osx_certification_entity`
--
ALTER TABLE `osx_certification_entity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_certification_faq`
--
ALTER TABLE `osx_certification_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_certification_privilege`
--
ALTER TABLE `osx_certification_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `osx_certification_type`
--
ALTER TABLE `osx_certification_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `osx_channel`
--
ALTER TABLE `osx_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `osx_channel_admin`
--
ALTER TABLE `osx_channel_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_channel_count_content`
--
ALTER TABLE `osx_channel_count_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_count_open_rate`
--
ALTER TABLE `osx_channel_count_open_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_count_view`
--
ALTER TABLE `osx_channel_count_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_count_view_log`
--
ALTER TABLE `osx_channel_count_view_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_post`
--
ALTER TABLE `osx_channel_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_post_hide`
--
ALTER TABLE `osx_channel_post_hide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_post_pool`
--
ALTER TABLE `osx_channel_post_pool`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_recommend_log`
--
ALTER TABLE `osx_channel_recommend_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_channel_user`
--
ALTER TABLE `osx_channel_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_author`
--
ALTER TABLE `osx_column_author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_category`
--
ALTER TABLE `osx_column_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品分类表ID';

--
-- 使用表AUTO_INCREMENT `osx_column_class`
--
ALTER TABLE `osx_column_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_class_product`
--
ALTER TABLE `osx_column_class_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_collect`
--
ALTER TABLE `osx_column_collect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_coupon`
--
ALTER TABLE `osx_column_coupon`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '优惠券表ID';

--
-- 使用表AUTO_INCREMENT `osx_column_coupon_issue`
--
ALTER TABLE `osx_column_coupon_issue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_column_coupon_user`
--
ALTER TABLE `osx_column_coupon_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '优惠券发放记录id';

--
-- 使用表AUTO_INCREMENT `osx_column_product_reply`
--
ALTER TABLE `osx_column_product_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID';

--
-- 使用表AUTO_INCREMENT `osx_column_reply`
--
ALTER TABLE `osx_column_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID';

--
-- 使用表AUTO_INCREMENT `osx_column_text`
--
ALTER TABLE `osx_column_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id';

--
-- 使用表AUTO_INCREMENT `osx_column_user_buy`
--
ALTER TABLE `osx_column_user_buy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_comment_census`
--
ALTER TABLE `osx_comment_census`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_comment_report`
--
ALTER TABLE `osx_comment_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_comment_template`
--
ALTER TABLE `osx_comment_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_community_count`
--
ALTER TABLE `osx_community_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_com_adv`
--
ALTER TABLE `osx_com_adv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `osx_com_adv_platform`
--
ALTER TABLE `osx_com_adv_platform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `osx_com_announce`
--
ALTER TABLE `osx_com_announce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公告id';

--
-- 使用表AUTO_INCREMENT `osx_com_announce_user`
--
ALTER TABLE `osx_com_announce_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_com_draft`
--
ALTER TABLE `osx_com_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_forum`
--
ALTER TABLE `osx_com_forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '版块id', AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `osx_com_forum_admin`
--
ALTER TABLE `osx_com_forum_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_forum_admin_apply`
--
ALTER TABLE `osx_com_forum_admin_apply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_forum_admin_score`
--
ALTER TABLE `osx_com_forum_admin_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `osx_com_forum_admin_score_log`
--
ALTER TABLE `osx_com_forum_admin_score_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_forum_member`
--
ALTER TABLE `osx_com_forum_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_com_nav`
--
ALTER TABLE `osx_com_nav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `osx_com_post`
--
ALTER TABLE `osx_com_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_com_thread`
--
ALTER TABLE `osx_com_thread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_com_thread_class`
--
ALTER TABLE `osx_com_thread_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `osx_com_thread_draft`
--
ALTER TABLE `osx_com_thread_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_topic`
--
ALTER TABLE `osx_com_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_topic_class`
--
ALTER TABLE `osx_com_topic_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_com_topic_follow`
--
ALTER TABLE `osx_com_topic_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_event`
--
ALTER TABLE `osx_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_event_bind_group`
--
ALTER TABLE `osx_event_bind_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_event_category`
--
ALTER TABLE `osx_event_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_event_check`
--
ALTER TABLE `osx_event_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_event_collect`
--
ALTER TABLE `osx_event_collect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_event_enroller`
--
ALTER TABLE `osx_event_enroller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id';

--
-- 使用表AUTO_INCREMENT `osx_event_enroller_info`
--
ALTER TABLE `osx_event_enroller_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_event_field`
--
ALTER TABLE `osx_event_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_express`
--
ALTER TABLE `osx_express`
  MODIFY `id` mediumint(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '快递公司id', AUTO_INCREMENT=426;

--
-- 使用表AUTO_INCREMENT `osx_forum_census`
--
ALTER TABLE `osx_forum_census`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_forum_power`
--
ALTER TABLE `osx_forum_power`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_forum_report`
--
ALTER TABLE `osx_forum_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_forum_visit_audit`
--
ALTER TABLE `osx_forum_visit_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_group`
--
ALTER TABLE `osx_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=71;

--
-- 使用表AUTO_INCREMENT `osx_head_login`
--
ALTER TABLE `osx_head_login`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'uid';

--
-- 使用表AUTO_INCREMENT `osx_hot_census`
--
ALTER TABLE `osx_hot_census`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_invite_code`
--
ALTER TABLE `osx_invite_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `osx_invite_level`
--
ALTER TABLE `osx_invite_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_invite_log`
--
ALTER TABLE `osx_invite_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_invite_reward`
--
ALTER TABLE `osx_invite_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_invite_share`
--
ALTER TABLE `osx_invite_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '海报id';

--
-- 使用表AUTO_INCREMENT `osx_login_faq`
--
ALTER TABLE `osx_login_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_message`
--
ALTER TABLE `osx_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_message_news`
--
ALTER TABLE `osx_message_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_message_read`
--
ALTER TABLE `osx_message_read`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_message_register`
--
ALTER TABLE `osx_message_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_message_template`
--
ALTER TABLE `osx_message_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- 使用表AUTO_INCREMENT `osx_message_type`
--
ALTER TABLE `osx_message_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `osx_message_user_popup`
--
ALTER TABLE `osx_message_user_popup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_os_token`
--
ALTER TABLE `osx_os_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `osx_payment_profit`
--
ALTER TABLE `osx_payment_profit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_pay_set`
--
ALTER TABLE `osx_pay_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `osx_pc_set`
--
ALTER TABLE `osx_pc_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_picture`
--
ALTER TABLE `osx_picture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id自增';

--
-- 使用表AUTO_INCREMENT `osx_power`
--
ALTER TABLE `osx_power`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=24;

--
-- 使用表AUTO_INCREMENT `osx_prohibit`
--
ALTER TABLE `osx_prohibit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_prohibit_reason`
--
ALTER TABLE `osx_prohibit_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_qiandao`
--
ALTER TABLE `osx_qiandao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `osx_rank`
--
ALTER TABLE `osx_rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `osx_rank_del`
--
ALTER TABLE `osx_rank_del`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_rank_search`
--
ALTER TABLE `osx_rank_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_rank_thread`
--
ALTER TABLE `osx_rank_thread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_rank_thread_time`
--
ALTER TABLE `osx_rank_thread_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `osx_rank_topic`
--
ALTER TABLE `osx_rank_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_rank_user`
--
ALTER TABLE `osx_rank_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_rank_user_time`
--
ALTER TABLE `osx_rank_user_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_renwu_jiafen_log`
--
ALTER TABLE `osx_renwu_jiafen_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_renwu_nav`
--
ALTER TABLE `osx_renwu_nav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_report`
--
ALTER TABLE `osx_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_report_prohibit`
--
ALTER TABLE `osx_report_prohibit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_report_reason`
--
ALTER TABLE `osx_report_reason`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `osx_routine_access_token`
--
ALTER TABLE `osx_routine_access_token`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '小程序access_token表ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_routine_ad`
--
ALTER TABLE `osx_routine_ad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `osx_routine_ad_position`
--
ALTER TABLE `osx_routine_ad_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `osx_routine_form_id`
--
ALTER TABLE `osx_routine_form_id`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '表单ID表ID';

--
-- 使用表AUTO_INCREMENT `osx_routine_qrcode`
--
ALTER TABLE `osx_routine_qrcode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '微信二维码ID';

--
-- 使用表AUTO_INCREMENT `osx_routine_template`
--
ALTER TABLE `osx_routine_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板id', AUTO_INCREMENT=23;

--
-- 使用表AUTO_INCREMENT `osx_script`
--
ALTER TABLE `osx_script`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_search`
--
ALTER TABLE `osx_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_sell`
--
ALTER TABLE `osx_sell`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_sell_order`
--
ALTER TABLE `osx_sell_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_sensitive`
--
ALTER TABLE `osx_sensitive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_sensitive_log`
--
ALTER TABLE `osx_sensitive_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_shop_column`
--
ALTER TABLE `osx_shop_column`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `osx_shop_order`
--
ALTER TABLE `osx_shop_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_shop_product`
--
ALTER TABLE `osx_shop_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_shop_score_type`
--
ALTER TABLE `osx_shop_score_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_stat_reg_info`
--
ALTER TABLE `osx_stat_reg_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_store_bargain`
--
ALTER TABLE `osx_store_bargain`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '砍价产品ID';

--
-- 使用表AUTO_INCREMENT `osx_store_bargain_user`
--
ALTER TABLE `osx_store_bargain_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户参与砍价表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_bargain_user_help`
--
ALTER TABLE `osx_store_bargain_user_help`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '砍价用户帮助表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_cart`
--
ALTER TABLE `osx_store_cart`
  MODIFY `id` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '购物车表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_category`
--
ALTER TABLE `osx_store_category`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT COMMENT '商品分类表ID', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `osx_store_category_column`
--
ALTER TABLE `osx_store_category_column`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT COMMENT '商品分类表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_combination`
--
ALTER TABLE `osx_store_combination`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_store_coupon`
--
ALTER TABLE `osx_store_coupon`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '优惠券表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_coupon_issue`
--
ALTER TABLE `osx_store_coupon_issue`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_store_coupon_user`
--
ALTER TABLE `osx_store_coupon_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '优惠券发放记录id';

--
-- 使用表AUTO_INCREMENT `osx_store_order`
--
ALTER TABLE `osx_store_order`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单ID';

--
-- 使用表AUTO_INCREMENT `osx_store_pink`
--
ALTER TABLE `osx_store_pink`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_store_product`
--
ALTER TABLE `osx_store_product`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT COMMENT '商品id', AUTO_INCREMENT=49;

--
-- 使用表AUTO_INCREMENT `osx_store_product_reply`
--
ALTER TABLE `osx_store_product_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID';

--
-- 使用表AUTO_INCREMENT `osx_store_product_services`
--
ALTER TABLE `osx_store_product_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '服务id';

--
-- 使用表AUTO_INCREMENT `osx_store_product_text_reply`
--
ALTER TABLE `osx_store_product_text_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID';

--
-- 使用表AUTO_INCREMENT `osx_store_seckill`
--
ALTER TABLE `osx_store_seckill`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品秒杀产品表id';

--
-- 使用表AUTO_INCREMENT `osx_store_service`
--
ALTER TABLE `osx_store_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客服id';

--
-- 使用表AUTO_INCREMENT `osx_store_service_log`
--
ALTER TABLE `osx_store_service_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客服用户对话记录表ID';

--
-- 使用表AUTO_INCREMENT `osx_store_visit`
--
ALTER TABLE `osx_store_visit`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_support`
--
ALTER TABLE `osx_support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_system_admin`
--
ALTER TABLE `osx_system_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '后台管理员表ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_system_attachment`
--
ALTER TABLE `osx_system_attachment`
  MODIFY `att_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- 使用表AUTO_INCREMENT `osx_system_attachment_category`
--
ALTER TABLE `osx_system_attachment_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `osx_system_config`
--
ALTER TABLE `osx_system_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置id', AUTO_INCREMENT=438;

--
-- 使用表AUTO_INCREMENT `osx_system_config_tab`
--
ALTER TABLE `osx_system_config_tab`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置分类id', AUTO_INCREMENT=111;

--
-- 使用表AUTO_INCREMENT `osx_system_count_log_share`
--
ALTER TABLE `osx_system_count_log_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_system_count_log_to_show`
--
ALTER TABLE `osx_system_count_log_to_show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_system_count_log_user`
--
ALTER TABLE `osx_system_count_log_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_system_count_log_view`
--
ALTER TABLE `osx_system_count_log_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_system_file`
--
ALTER TABLE `osx_system_file`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件对比ID';

--
-- 使用表AUTO_INCREMENT `osx_system_grade_desc`
--
ALTER TABLE `osx_system_grade_desc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `osx_system_group`
--
ALTER TABLE `osx_system_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '组合数据ID', AUTO_INCREMENT=62;

--
-- 使用表AUTO_INCREMENT `osx_system_group_data`
--
ALTER TABLE `osx_system_group_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '组合数据详情ID', AUTO_INCREMENT=16902;

--
-- 使用表AUTO_INCREMENT `osx_system_jifen`
--
ALTER TABLE `osx_system_jifen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `osx_system_log`
--
ALTER TABLE `osx_system_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员操作记录ID', AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `osx_system_mall_grade`
--
ALTER TABLE `osx_system_mall_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_system_menus`
--
ALTER TABLE `osx_system_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单ID', AUTO_INCREMENT=110017;

--
-- 使用表AUTO_INCREMENT `osx_system_notice`
--
ALTER TABLE `osx_system_notice`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知模板id';

--
-- 使用表AUTO_INCREMENT `osx_system_notice_admin`
--
ALTER TABLE `osx_system_notice_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知记录ID';

--
-- 使用表AUTO_INCREMENT `osx_system_renwu`
--
ALTER TABLE `osx_system_renwu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用表AUTO_INCREMENT `osx_system_role`
--
ALTER TABLE `osx_system_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '身份管理id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_system_rule`
--
ALTER TABLE `osx_system_rule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `osx_system_rule_action`
--
ALTER TABLE `osx_system_rule_action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- 使用表AUTO_INCREMENT `osx_system_user_grade`
--
ALTER TABLE `osx_system_user_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `osx_system_user_level`
--
ALTER TABLE `osx_system_user_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_system_user_task`
--
ALTER TABLE `osx_system_user_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_talk`
--
ALTER TABLE `osx_talk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_talk_content`
--
ALTER TABLE `osx_talk_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_tencent_file`
--
ALTER TABLE `osx_tencent_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_text_user`
--
ALTER TABLE `osx_text_user`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_thread_census`
--
ALTER TABLE `osx_thread_census`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_token`
--
ALTER TABLE `osx_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user`
--
ALTER TABLE `osx_user`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_user_address`
--
ALTER TABLE `osx_user_address`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户地址id';

--
-- 使用表AUTO_INCREMENT `osx_user_agreement`
--
ALTER TABLE `osx_user_agreement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_user_bill`
--
ALTER TABLE `osx_user_bill`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户账单id';

--
-- 使用表AUTO_INCREMENT `osx_user_buy_product`
--
ALTER TABLE `osx_user_buy_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_enter`
--
ALTER TABLE `osx_user_enter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商户申请ID';

--
-- 使用表AUTO_INCREMENT `osx_user_extract`
--
ALTER TABLE `osx_user_extract`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_follow`
--
ALTER TABLE `osx_user_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_user_friend`
--
ALTER TABLE `osx_user_friend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_group`
--
ALTER TABLE `osx_user_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_level`
--
ALTER TABLE `osx_user_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_login_log`
--
ALTER TABLE `osx_user_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_login_set`
--
ALTER TABLE `osx_user_login_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `osx_user_notice`
--
ALTER TABLE `osx_user_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_notice_see`
--
ALTER TABLE `osx_user_notice_see`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_order`
--
ALTER TABLE `osx_user_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_order_log`
--
ALTER TABLE `osx_user_order_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_picture`
--
ALTER TABLE `osx_user_picture`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户uid';

--
-- 使用表AUTO_INCREMENT `osx_user_rank`
--
ALTER TABLE `osx_user_rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_read`
--
ALTER TABLE `osx_user_read`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_recharge`
--
ALTER TABLE `osx_user_recharge`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_recommend`
--
ALTER TABLE `osx_user_recommend`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_register`
--
ALTER TABLE `osx_user_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_user_report`
--
ALTER TABLE `osx_user_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_reward`
--
ALTER TABLE `osx_user_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_share`
--
ALTER TABLE `osx_user_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_sign`
--
ALTER TABLE `osx_user_sign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_support_card`
--
ALTER TABLE `osx_user_support_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_sync_login`
--
ALTER TABLE `osx_user_sync_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_task_day`
--
ALTER TABLE `osx_user_task_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_task_finish`
--
ALTER TABLE `osx_user_task_finish`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_task_new`
--
ALTER TABLE `osx_user_task_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_user_verify`
--
ALTER TABLE `osx_user_verify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `osx_user_visit`
--
ALTER TABLE `osx_user_visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_wallet`
--
ALTER TABLE `osx_user_wallet`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_user_wanshan`
--
ALTER TABLE `osx_user_wanshan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `osx_vest`
--
ALTER TABLE `osx_vest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_website_connect_action_notify`
--
ALTER TABLE `osx_website_connect_action_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_website_connect_token`
--
ALTER TABLE `osx_website_connect_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_wechat_media`
--
ALTER TABLE `osx_wechat_media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '微信视频音频id';

--
-- 使用表AUTO_INCREMENT `osx_wechat_message`
--
ALTER TABLE `osx_wechat_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户行为记录id';

--
-- 使用表AUTO_INCREMENT `osx_wechat_news_category`
--
ALTER TABLE `osx_wechat_news_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图文消息管理ID';

--
-- 使用表AUTO_INCREMENT `osx_wechat_qrcode`
--
ALTER TABLE `osx_wechat_qrcode`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '微信二维码ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_wechat_reply`
--
ALTER TABLE `osx_wechat_reply`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '微信关键字回复id';

--
-- 使用表AUTO_INCREMENT `osx_wechat_routine_template`
--
ALTER TABLE `osx_wechat_routine_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板id', AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `osx_wechat_template`
--
ALTER TABLE `osx_wechat_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板id', AUTO_INCREMENT=14;

--
-- 使用表AUTO_INCREMENT `osx_wechat_user`
--
ALTER TABLE `osx_wechat_user`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '微信用户id', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `osx_week_order_task`
--
ALTER TABLE `osx_week_order_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `osx_withdraw_order`
--
ALTER TABLE `osx_withdraw_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `osx_zidingyi_renwu`
--
ALTER TABLE `osx_zidingyi_renwu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
