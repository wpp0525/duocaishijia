-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 04 月 16 日 05:39
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `yincart-basic`
--
CREATE DATABASE IF NOT EXISTS `yincart-basic` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `yincart-basic`;

-- --------------------------------------------------------

--
-- 表的结构 `ad`
--

CREATE TABLE IF NOT EXISTS `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `pic` varchar(100) NOT NULL,
  `url` varchar(50) NOT NULL,
  `theme` varchar(50) DEFAULT 'default',
  `content` text,
  `sort_order` int(11) NOT NULL DEFAULT '255',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `address_result`
--

CREATE TABLE IF NOT EXISTS `address_result` (
  `contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址库ID',
  `user_id` int(10) unsigned DEFAULT NULL,
  `contact_name` varchar(45) DEFAULT NULL COMMENT '联系人姓名',
  `country` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `district` varchar(45) DEFAULT NULL,
  `zipcode` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `mobile_phone` varchar(45) DEFAULT NULL,
  `memo` text COMMENT '备注',
  `is_default` tinyint(1) unsigned DEFAULT '0',
  `create_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `profile` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_user2`
--

CREATE TABLE IF NOT EXISTS `admin_user2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `password_strategy` varchar(50) DEFAULT NULL,
  `requires_new_password` tinyint(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login_attempts` int(11) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  `login_ip` varchar(32) DEFAULT NULL,
  `validation_key` varchar(255) DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_id` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `area`
--

CREATE TABLE IF NOT EXISTS `area` (
  `area_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(45) NOT NULL,
  `language` varchar(20) NOT NULL,
  PRIMARY KEY (`area_id`),
  KEY `fk_area_area1_idx` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=990101 ;

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `url` varchar(100) NOT NULL,
  `pic_url` varchar(150) NOT NULL,
  `from` varchar(200) NOT NULL,
  `summary` text NOT NULL,
  `content` longtext NOT NULL,
  `views` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `language` varchar(45) NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `authassignment`
--

CREATE TABLE IF NOT EXISTS `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `authitem`
--

CREATE TABLE IF NOT EXISTS `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `authitemchild`
--

CREATE TABLE IF NOT EXISTS `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
  `value_id` int(10) unsigned NOT NULL DEFAULT '0',
  `value_name` varchar(45) DEFAULT NULL COMMENT 'vid的值',
  `prop_id` int(10) unsigned NOT NULL,
  `prop_name` varchar(45) DEFAULT NULL COMMENT '品牌的属性名',
  PRIMARY KEY (`value_id`),
  KEY `fk_brand_prop_value1` (`value_id`),
  KEY `fk_brand_item_prop1` (`prop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `id` char(128) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `value` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `left` int(10) unsigned NOT NULL,
  `right` int(10) unsigned NOT NULL,
  `root` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '分类名',
  `label` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `url` varchar(200) NOT NULL COMMENT '分类的url显示',
  `pic` varchar(255) NOT NULL COMMENT '分类图片',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `author` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(8) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `sign` varchar(5) DEFAULT NULL,
  `rate` decimal(10,4) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `customer_service`
--

CREATE TABLE IF NOT EXISTS `customer_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL,
  `nick_name` varchar(50) NOT NULL,
  `account` varchar(100) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '255',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `eavAttr`
--

CREATE TABLE IF NOT EXISTS `eavAttr` (
  `entity` bigint(20) unsigned NOT NULL,
  `attribute` varchar(250) NOT NULL,
  `value` text NOT NULL,
  KEY `ikEntity` (`entity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `reply` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `friend_link`
--

CREATE TABLE IF NOT EXISTS `friend_link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `url` varchar(200) NOT NULL,
  `memo` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '255',
  `language` varchar(45) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon`
--

CREATE TABLE IF NOT EXISTS `groupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '长标题',
  `short_title` varchar(18) CHARACTER SET utf8 NOT NULL COMMENT '短信标题',
  `sms_title` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '手机短信标题',
  `image` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '主图',
  `cate_1_id` int(10) unsigned NOT NULL COMMENT '一级分类',
  `cate_2_id` int(10) unsigned NOT NULL COMMENT '二级分类',
  `cate_3_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三级分类',
  `biz_id` int(10) unsigned NOT NULL COMMENT '商家id',
  `contract_id` int(10) unsigned NOT NULL COMMENT '合同id',
  `price` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '团购价',
  `market_price` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `cost` double(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `begin_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `per_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每人限购',
  `once_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每单限购',
  `begin_number` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '起购数量',
  `now_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '目前销量',
  `pre_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '加水量',
  `max_number` int(11) NOT NULL DEFAULT '-1' COMMENT '库存',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示1显示0不显示',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '站内排序值',
  `is_copy` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为复制项目',
  `examine_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=草稿 2=提交审核3=销售审核通过等待编辑4=销售审核失败 5=编辑完成等待审核 6=编辑审核失败7=编辑审核成功等待上线',
  `examine_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核人id',
  `examine_reason` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '审核原因',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '项目创建者id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `cate_1_id` (`cate_1_id`,`cate_2_id`,`cate_3_id`,`biz_id`,`contract_id`,`price`,`begin_time`,`end_time`,`display`,`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购项目主表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_attach`
--

CREATE TABLE IF NOT EXISTS `groupon_attach` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `relation_id` int(10) unsigned NOT NULL COMMENT '关联表id',
  `relation_type` enum('contract','biz','groupon') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'contract' COMMENT '关联类型 默认合同',
  `file_name` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件名',
  `file_path` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '文件路径',
  `title` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片标题',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `relation_id` (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合同等附件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_attr`
--

CREATE TABLE IF NOT EXISTS `groupon_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupon_id` int(10) unsigned NOT NULL COMMENT '团购id',
  `note` text CHARACTER SET utf8 NOT NULL COMMENT '温馨提示',
  `detail` text CHARACTER SET utf8 NOT NULL COMMENT '商品详情（表格）',
  `info` text CHARACTER SET utf8 NOT NULL COMMENT '图文介绍',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupon_id` (`groupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购属性表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_biz`
--

CREATE TABLE IF NOT EXISTS `groupon_biz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `title` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '商家名称',
  `license_photo` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业执照',
  `contact` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(18) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobile` char(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `bank_user` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户名',
  `bank_name` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_child` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '支行信息',
  `bank_no` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '银行账号',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建者id',
  `examine_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1=草稿状态 2=审核提交 3=审核通过 4=审核驳回',
  `examine_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核人id',
  `examine_reason` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '审核原因',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示1显示0不显示',
  PRIMARY KEY (`id`),
  KEY `examine_status` (`examine_status`,`display`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购商家主表' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_biz_shop`
--

CREATE TABLE IF NOT EXISTS `groupon_biz_shop` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `biz_id` int(10) unsigned NOT NULL COMMENT '商家id',
  `name` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '分店名',
  `service_tel` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '预约电话',
  `address` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '分店地址',
  `travel_info` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '公交信息',
  `open_time` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '营业时间',
  `province_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省份id',
  `city_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '城市id',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区域id',
  `cbd_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商圈id',
  `lnglat` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '经纬度',
  `is_reservation` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要预约1需要0不需要',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `biz_id` (`biz_id`,`city_id`,`area_id`,`cbd_id`,`is_reservation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商家分店' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_cates`
--

CREATE TABLE IF NOT EXISTS `groupon_cates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '分类名',
  `ename` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '分类别名',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '等级',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '分类路径',
  `is_hot` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否热门0不热 1热',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ename` (`ename`),
  KEY `pid` (`pid`,`level`,`path`,`is_hot`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购分类表' AUTO_INCREMENT=181 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_contract`
--

CREATE TABLE IF NOT EXISTS `groupon_contract` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '合同名称',
  `biz_id` int(10) unsigned NOT NULL COMMENT '商家id',
  `sign_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '合同签订时间',
  `online_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预计上线时间',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团购结束时间',
  `create_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签约人',
  `if_billing` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发票 0=不开发票 1=需要开具发票',
  `examine_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '合同状态1=待提交2=待审核3=审核通过4=驳回5=废除',
  `examine_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '审核人id',
  `examine_reason` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '审核原因',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `biz_id` (`biz_id`,`examine_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购商家合同' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_coupon`
--

CREATE TABLE IF NOT EXISTS `groupon_coupon` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(16) CHARACTER SET utf8 NOT NULL COMMENT '券号',
  `pass` char(6) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '券密码',
  `groupon_id` int(10) unsigned NOT NULL COMMENT '团购id',
  `biz_id` int(10) unsigned NOT NULL COMMENT '商家id',
  `order_id` bigint(20) unsigned NOT NULL COMMENT '订单id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1未消费2已消费3已退款4已过期',
  `sms` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '短信发送次数',
  `consume_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '消费时间',
  `ref_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '退款时间',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `seller_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销售员id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `groupon_id` (`groupon_id`,`biz_id`,`order_id`,`pass`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购券表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `groupon_shop`
--

CREATE TABLE IF NOT EXISTS `groupon_shop` (
  `groupon_id` int(10) unsigned NOT NULL COMMENT '团购id',
  `shop_id` int(10) unsigned NOT NULL COMMENT '分店id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupon_id`,`shop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='团购项目和分店关系表';

-- --------------------------------------------------------

--
-- 表的结构 `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Item ID',
  `category_id` int(10) unsigned NOT NULL COMMENT 'Category ID',
  `outer_id` varchar(45) DEFAULT NULL,
  `title` varchar(255) NOT NULL COMMENT '名称',
  `stock` int(10) unsigned NOT NULL COMMENT '库存',
  `min_number` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最少订货量',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '价格',
  `currency` varchar(20) NOT NULL COMMENT '币种',
  `props` longtext NOT NULL COMMENT '商品属性 格式：pid:vid;pid:vid',
  `props_name` longtext NOT NULL COMMENT '商品属性名称。标识着props内容里面的pid和vid所对应的名称。格式为：pid1:vid1:pid_name1:vid_name1;pid2:vid2:pid_name2:vid_name2……(注：属性名称中的冒号":"被转换为："#cln#"; 分号";"被转换为："#scln#" )',
  `desc` longtext NOT NULL COMMENT '描述',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否促销',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否热销',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否精品',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `wish_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  `review_count` int(10) NOT NULL,
  `deal_count` int(10) NOT NULL,
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `language` varchar(45) NOT NULL,
  `country` int(10) unsigned NOT NULL,
  `state` int(10) unsigned NOT NULL,
  `city` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `fk_item_category1_idx` (`category_id`),
  KEY `fk_item_area1_idx` (`country`),
  KEY `fk_item_area2_idx` (`state`),
  KEY `fk_item_area3_idx` (`city`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- 表的结构 `item_img`
--

CREATE TABLE IF NOT EXISTS `item_img` (
  `item_img_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Item Img ID',
  `item_id` int(10) unsigned NOT NULL COMMENT 'Item ID',
  `pic` varchar(255) NOT NULL COMMENT '图片url',
  `position` tinyint(3) unsigned NOT NULL COMMENT '图片位置',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`item_img_id`),
  KEY `fk_item_img_item1_idx` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- 表的结构 `item_prop`
--

CREATE TABLE IF NOT EXISTS `item_prop` (
  `item_prop_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性 ID 例：品牌的PID=20000',
  `category_id` int(10) unsigned NOT NULL,
  `parent_prop_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级属性ID',
  `parent_value_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级属性值ID',
  `prop_name` varchar(100) NOT NULL,
  `prop_alias` varchar(100) DEFAULT NULL,
  `type` tinyint(1) unsigned NOT NULL COMMENT '属性值类型。可选值：input(输入)、optional（枚举）multiCheck （多选）',
  `is_key_prop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否关键属性。可选值:1(是),0(否),搜索属性',
  `is_sale_prop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否销售属性。可选值:1(是),0(否)',
  `is_color_prop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否颜色属性。可选值:1(是),0(否)',
  `must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发布产品或商品时是否为必选属性。可选值:1(是),0(否)',
  `multi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发布产品或商品时是否可以多选。可选值:1(是),0(否)',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态。可选值:normal(正常),deleted(删除)',
  `sort_order` tinyint(3) unsigned DEFAULT '255',
  `item_propcol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`item_prop_id`),
  KEY `fk_item_prop_category1_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `location_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(45) NOT NULL COMMENT '国家',
  `state` varchar(45) NOT NULL COMMENT '省份',
  `city` varchar(45) NOT NULL COMMENT '城市',
  `district` varchar(45) NOT NULL COMMENT '县、区',
  `address` varchar(255) NOT NULL COMMENT '详细地址',
  `zip` varchar(45) NOT NULL COMMENT '邮编',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `lookup`
--

CREATE TABLE IF NOT EXISTS `lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `code` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `if_show` tinyint(1) DEFAULT NULL,
  `memo` text,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL,
  `language` varchar(16) NOT NULL,
  `translation` text,
  PRIMARY KEY (`id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `newsletter_subscriber`
--

CREATE TABLE IF NOT EXISTS `newsletter_subscriber` (
  `subscriber_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `email` varchar(150) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `confirm_code` varchar(32) DEFAULT NULL,
  `change_status_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`subscriber_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '0',
  `pay_status` tinyint(1) unsigned DEFAULT '0',
  `ship_status` tinyint(1) unsigned DEFAULT '0',
  `refund_status` tinyint(1) unsigned DEFAULT '0',
  `comment_status` tinyint(1) unsigned DEFAULT '0',
  `total_fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品金额（商品价格乘以数量的总金额）。精确到2位小数;单位:元。如:200.07，表示:200元7分',
  `ship_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `pay_fee` decimal(10,2) unsigned DEFAULT '0.00',
  `payment_method_id` int(10) unsigned DEFAULT '0',
  `shipping_method_id` int(11) DEFAULT '0',
  `receiver_name` varchar(45) DEFAULT NULL COMMENT '收货人的姓名',
  `receiver_country` varchar(45) DEFAULT NULL,
  `receiver_state` varchar(45) DEFAULT NULL COMMENT '收货人的所在省份',
  `receiver_city` varchar(45) DEFAULT NULL COMMENT '收货人的所在城市',
  `receiver_district` varchar(45) DEFAULT NULL COMMENT '收货人的所在地区',
  `receiver_address` varchar(255) DEFAULT NULL COMMENT '收货人的详细地址',
  `receiver_zip` varchar(45) DEFAULT NULL COMMENT '收货人的邮编',
  `receiver_mobile` varchar(45) DEFAULT NULL COMMENT '收货人的手机号码',
  `receiver_phone` varchar(45) DEFAULT NULL COMMENT '收货人的电话号码',
  `memo` text COMMENT '备注',
  `pay_time` int(10) unsigned DEFAULT NULL,
  `ship_time` int(10) unsigned DEFAULT NULL COMMENT '发货时间',
  `create_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  `order_ship_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `fk_order_payment_method1_idx` (`payment_method_id`),
  KEY `fk_order_shipping_method1_idx` (`shipping_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20140212007841 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_item`
--

CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` longtext NOT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `props_name` longtext NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `total_price` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `fk_order_item_order1_idx` (`order_id`),
  KEY `fk_order_item_item1_idx` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_log`
--

CREATE TABLE IF NOT EXISTS `order_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL,
  `op_name` varchar(45) DEFAULT NULL,
  `log_text` longtext,
  `action_time` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `result` enum('success','failure') DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- 表的结构 `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `key` varchar(200) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `language` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- 表的结构 `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `payment_sn` varchar(45) NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL,
  `currency` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `account` varchar(45) NOT NULL,
  `bank` varchar(45) NOT NULL,
  `pay_account` varchar(45) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `fk_payment_order1_idx` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `payment_method`
--

CREATE TABLE IF NOT EXISTS `payment_method` (
  `payment_method_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `name` varchar(120) NOT NULL,
  `desc` text NOT NULL,
  `config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `identify` varchar(45) NOT NULL,
  `path` varchar(255) NOT NULL,
  `hooks` text NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`plugin_id`),
  UNIQUE KEY `identify_UNIQUE` (`identify`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `plugins_setting`
--

CREATE TABLE IF NOT EXISTS `plugins_setting` (
  `plugin` varchar(45) NOT NULL,
  `key` varchar(45) NOT NULL,
  `value` text,
  PRIMARY KEY (`plugin`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `summary` text,
  `content` text NOT NULL,
  `tags` text,
  `status` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `author` varchar(32) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `language` varchar(45) DEFAULT NULL,
  `pic_url` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post_author` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `truename` varchar(45) NOT NULL DEFAULT '',
  `nickname` varchar(45) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `cart` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `profiles_fields`
--

CREATE TABLE IF NOT EXISTS `profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `prop_category`
--

CREATE TABLE IF NOT EXISTS `prop_category` (
  `prop_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`prop_id`,`category_id`),
  KEY `fk_prop_category_item_prop1` (`prop_id`),
  KEY `fk_prop_category_category1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `prop_img`
--

CREATE TABLE IF NOT EXISTS `prop_img` (
  `prop_img_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Prop Img ID',
  `item_id` int(10) unsigned NOT NULL COMMENT 'Item ID',
  `item_prop_value` varchar(255) NOT NULL COMMENT '图片所对应的属性组合的字符串',
  `pic` varchar(255) NOT NULL COMMENT '图片url',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`prop_img_id`),
  KEY `fk_prop_img_item1_idx` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `prop_value`
--

CREATE TABLE IF NOT EXISTS `prop_value` (
  `prop_value_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '属性值ID',
  `item_prop_id` int(10) unsigned NOT NULL,
  `value_name` varchar(45) NOT NULL COMMENT '属性值',
  `value_alias` varchar(45) NOT NULL COMMENT '属性值别名',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态。可选值:normal(正常),deleted(删除)',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排列序号。取值范围:大于零的整数',
  PRIMARY KEY (`prop_value_id`),
  KEY `fk_prop_value_item_prop1_idx` (`item_prop_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- 表的结构 `refund`
--

CREATE TABLE IF NOT EXISTS `refund` (
  `refund_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `refund_sn` varchar(45) NOT NULL,
  `money` decimal(20,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `pay_method` varchar(45) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `account` varchar(45) NOT NULL,
  `bank` varchar(45) NOT NULL,
  `refund_account` varchar(45) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`refund_id`,`create_time`,`status`),
  KEY `fk_refund_order1_idx` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `review`
--

CREATE TABLE IF NOT EXISTS `review` (
  `review_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_at` int(10) DEFAULT NULL,
  `content` text,
  `customer_id` int(10) DEFAULT NULL,
  `entity_id` smallint(5) DEFAULT NULL,
  `entity_pk_value` int(10) DEFAULT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `photos_exit` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141 ;

-- --------------------------------------------------------

--
-- 表的结构 `review_entity`
--

CREATE TABLE IF NOT EXISTS `review_entity` (
  `entiy_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `entiy_code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`entiy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `review_photos`
--

CREATE TABLE IF NOT EXISTS `review_photos` (
  `image_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(45) DEFAULT NULL,
  `review_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`image_id`),
  KEY `fk_review_image_review1_idx` (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL DEFAULT 'system',
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_key` (`category`,`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- 表的结构 `shipping`
--

CREATE TABLE IF NOT EXISTS `shipping` (
  `ship_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `ship_sn` varchar(45) DEFAULT NULL,
  `type` enum('return','delivery') DEFAULT NULL,
  `ship_method` varchar(45) DEFAULT NULL,
  `ship_fee` decimal(10,2) DEFAULT NULL,
  `op_name` varchar(45) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `receiver_name` varchar(45) DEFAULT NULL,
  `receiver_phone` varchar(45) DEFAULT NULL,
  `receiver_mobile` varchar(45) DEFAULT NULL,
  `location` text,
  `create_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ship_id`),
  KEY `fk_shipping_order1_idx` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物流数据结构 ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shipping_item`
--

CREATE TABLE IF NOT EXISTS `shipping_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ship_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `item_sn` varchar(45) DEFAULT NULL,
  `item_title` varchar(255) DEFAULT NULL,
  `num` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_delivery_item_delivery1_idx` (`ship_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shipping_method`
--

CREATE TABLE IF NOT EXISTS `shipping_method` (
  `shipping_method_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `desc` text,
  `enabled` tinyint(1) unsigned DEFAULT '0',
  `is_cod` tinyint(1) unsigned DEFAULT '0',
  `sort_order` tinyint(3) unsigned DEFAULT '255',
  PRIMARY KEY (`shipping_method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `sku`
--

CREATE TABLE IF NOT EXISTS `sku` (
  `sku_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'SKU ID',
  `item_id` int(10) unsigned NOT NULL COMMENT 'Item ID',
  `props` longtext NOT NULL COMMENT 'sku的销售属性组合字符串（颜色，大小，等等，可通过类目API获取某类目下的销售属性）,格式是p1:v1;p2:v2',
  `props_name` longtext NOT NULL COMMENT 'sku所对应的销售属性的中文名字串，格式如：pid1:vid1:pid_name1:vid_name1;pid2:vid2:pid_name2:vid_name2……',
  `stock` int(10) unsigned NOT NULL COMMENT 'sku商品库存',
  `price` decimal(10,2) unsigned NOT NULL COMMENT 'sku的商品价格',
  `outer_id` varchar(45) NOT NULL COMMENT '商家设置的外部id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'sku状态。 normal:正常 ；delete:删除',
  PRIMARY KEY (`sku_id`),
  KEY `fk_sku_item1_idx` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- 表的结构 `source_message`
--

CREATE TABLE IF NOT EXISTS `source_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(32) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=202 ;

-- --------------------------------------------------------

--
-- 表的结构 `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺表',
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `domain` varchar(200) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `years` int(11) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `introduction` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`store_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `store_category`
--

CREATE TABLE IF NOT EXISTS `store_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `label` tinyint(1) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `if_show` tinyint(1) DEFAULT NULL,
  `memo` text,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `store_menu`
--

CREATE TABLE IF NOT EXISTS `store_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(10) unsigned NOT NULL,
  `root` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned NOT NULL,
  `rgt` int(10) unsigned NOT NULL,
  `level` smallint(5) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `if_show` tinyint(1) DEFAULT NULL,
  `memo` text,
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `store_product_category`
--

CREATE TABLE IF NOT EXISTS `store_product_category` (
  `con_store_product_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `con_store_product_category_productid` int(10) unsigned DEFAULT NULL COMMENT 'product id',
  `con_store_product_category_categoryid` int(10) unsigned DEFAULT NULL COMMENT 'category id',
  PRIMARY KEY (`con_store_product_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_migration`
--

CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(50) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `author` varchar(45) DEFAULT NULL,
  `site` varchar(100) DEFAULT NULL,
  `update_url` varchar(100) DEFAULT NULL,
  `desc` text,
  `config` longtext,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `wishlist`
--

CREATE TABLE IF NOT EXISTS `wishlist` (
  `wishlist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned DEFAULT NULL,
  `desc` text,
  `create_time` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`wishlist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 限制导出的表
--

--
-- 限制表 `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_area1` FOREIGN KEY (`country`) REFERENCES `area` (`area_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_area2` FOREIGN KEY (`state`) REFERENCES `area` (`area_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_area3` FOREIGN KEY (`city`) REFERENCES `area` (`area_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `item_img`
--
ALTER TABLE `item_img`
  ADD CONSTRAINT `fk_item_img_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `item_prop`
--
ALTER TABLE `item_prop`
  ADD CONSTRAINT `fk_item_prop_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_payment_method1` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`payment_method_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_shipping_method1` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_method` (`shipping_method_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `prop_img`
--
ALTER TABLE `prop_img`
  ADD CONSTRAINT `fk_prop_img_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `prop_value`
--
ALTER TABLE `prop_value`
  ADD CONSTRAINT `fk_prop_value_item_prop1` FOREIGN KEY (`item_prop_id`) REFERENCES `item_prop` (`item_prop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `refund`
--
ALTER TABLE `refund`
  ADD CONSTRAINT `fk_refund_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `fk_shipping_order1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `shipping_item`
--
ALTER TABLE `shipping_item`
  ADD CONSTRAINT `fk_delivery_item_delivery1` FOREIGN KEY (`ship_id`) REFERENCES `shipping` (`ship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- 限制表 `sku`
--
ALTER TABLE `sku`
  ADD CONSTRAINT `fk_sku_item1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
