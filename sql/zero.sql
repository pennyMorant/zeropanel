-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-10-17 07:50:36
-- 服务器版本： 10.9.3-MariaDB
-- PHP 版本： 8.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `my`
--

-- --------------------------------------------------------

--
-- 表的结构 `alive_ip`
--

CREATE TABLE `alive_ip` (
  `id` bigint(20) NOT NULL,
  `nodeid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `ip` varchar(182) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `announcement`
--

CREATE TABLE `announcement` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `markdown` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL COMMENT '主键',
  `item` text NOT NULL COMMENT '项',
  `value` text NOT NULL COMMENT '值',
  `class` varchar(128) NOT NULL DEFAULT 'default' COMMENT '配置分类',
  `is_public` int(11) NOT NULL DEFAULT 0 COMMENT '是否为公共参数',
  `type` text NOT NULL COMMENT '值类型',
  `default` text NOT NULL COMMENT '默认值',
  `mark` text NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `coupon`
--

CREATE TABLE `coupon` (
  `id` bigint(20) NOT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '优惠码',
  `per_use_count` int(11) NOT NULL COMMENT '每个用户使用次数',
  `expire` bigint(20) NOT NULL COMMENT '到期时间',
  `limited_product` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '限定产品使用',
  `discount` int(11) NOT NULL COMMENT '折扣比例',
  `total_use_count` int(11) NOT NULL COMMENT '总使用次数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `email_queue`
--

CREATE TABLE `email_queue` (
  `id` bigint(20) NOT NULL,
  `to_email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `array` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Email Queue 發件列表';

-- --------------------------------------------------------

--
-- 表的结构 `email_verify`
--

CREATE TABLE `email_verify` (
  `id` bigint(20) NOT NULL,
  `email` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(182) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expire_in` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `link`
--

CREATE TABLE `link` (
  `id` bigint(20) NOT NULL,
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid` bigint(20) NOT NULL,
  `filter` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '节点筛选'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `login_ip`
--

CREATE TABLE `login_ip` (
  `id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `ip` varchar(182) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` bigint(20) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `node`
--

CREATE TABLE `node` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `server` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `info` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `traffic_rate` float NOT NULL DEFAULT 1,
  `node_class` int(11) NOT NULL DEFAULT 0,
  `node_speedlimit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `node_sort` int(11) NOT NULL DEFAULT 0 COMMENT '节点排序',
  `node_connector` int(11) NOT NULL DEFAULT 0,
  `node_bandwidth` bigint(20) NOT NULL DEFAULT 0,
  `node_bandwidth_limit` bigint(20) NOT NULL DEFAULT 0,
  `bandwidthlimit_resetday` int(11) NOT NULL DEFAULT 0,
  `node_heartbeat` bigint(20) NOT NULL DEFAULT 0,
  `node_ip` varchar(182) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `node_group` int(11) NOT NULL DEFAULT 0,
  `online` tinyint(1) NOT NULL DEFAULT 1,
  `gfw_block` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `node_info`
--

CREATE TABLE `node_info` (
  `id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `uptime` float NOT NULL,
  `load` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `node_online_log`
--

CREATE TABLE `node_online_log` (
  `id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `online_user` int(11) NOT NULL,
  `log_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE `order` (
  `id` bigint(20) NOT NULL COMMENT 'AUTO_INCREMENT',
  `no` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `order_type` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单类型,purchase_product_order-购买产品,add_credit_order-充值',
  `user_id` int(11) DEFAULT NULL COMMENT '提交用户',
  `product_id` int(11) DEFAULT NULL COMMENT '订单商品',
  `product_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '商品名称',
  `product_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '商品内容',
  `product_price` decimal(12,2) DEFAULT NULL COMMENT '商品售价',
  `order_coupon` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单优惠码',
  `order_total` decimal(12,2) DEFAULT NULL COMMENT '订单金额',
  `credit_paid` int(11) DEFAULT NULL COMMENT '订单余额支付部分',
  `order_status` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单状态,pending-等待支付,paid-完成支付,invalid-订单失效',
  `created_time` int(11) DEFAULT NULL COMMENT '订单创建时间',
  `updated_time` int(11) DEFAULT NULL COMMENT '订单更新时间',
  `expired_time` int(11) DEFAULT NULL COMMENT '订单失效时间',
  `paid_time` int(11) DEFAULT NULL COMMENT '订单支付时间',
  `order_payment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单支付方式',
  `paid_action` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付后操作',
  `execute_status` int(11) DEFAULT NULL COMMENT '执行状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `payback`
--

CREATE TABLE `payback` (
  `id` bigint(20) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `ref_by` bigint(20) NOT NULL,
  `ref_get` decimal(12,2) NOT NULL,
  `datetime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `payback_take_log`
--

CREATE TABLE `payback_take_log` (
  `id` int(11) NOT NULL,
  `type` int(11) DEFAULT 0,
  `userid` int(11) DEFAULT 0,
  `total` decimal(10,2) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `datetime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL,
  `name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品名称',
  `price` decimal(12,2) NOT NULL COMMENT '产品价格',
  `traffic` bigint(20) DEFAULT NULL COMMENT '产品包含的流量',
  `account_validity_period` bigint(20) DEFAULT NULL COMMENT '增加账户有效期时间',
  `user_group` int(11) DEFAULT NULL COMMENT '用户群组',
  `class` int(11) DEFAULT NULL COMMENT '产品等级',
  `class_validity_period` bigint(20) DEFAULT NULL COMMENT '产品等级有效时间',
  `traffic_reset_period` int(11) DEFAULT NULL COMMENT '流量重置周期',
  `traffic_reset_validity_period` bigint(20) DEFAULT NULL COMMENT '流量重置有效时间',
  `traffic_reset_value` bigint(20) DEFAULT NULL COMMENT '流量周期重置的值',
  `speed_limit` bigint(20) DEFAULT NULL COMMENT '速度限制',
  `ip_limit` int(11) DEFAULT NULL COMMENT 'IP限制',
  `type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品类型, cycle-周期,traffic-按流量,other-其他商品',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '产品排序',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '产品状态',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT '库存',
  `sales` int(11) NOT NULL DEFAULT 0 COMMENT '销量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `telegram_session`
--

CREATE TABLE `telegram_session` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `session_content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `telegram_tasks`
--

CREATE TABLE `telegram_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL COMMENT '任务类型',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '任务状态',
  `chatid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Telegram Chat ID',
  `messageid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Telegram Message ID',
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '任务详细内容',
  `process` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '临时任务进度',
  `userid` int(11) NOT NULL DEFAULT 0 COMMENT '网站用户 ID',
  `tguserid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Telegram User ID',
  `executetime` bigint(20) NOT NULL COMMENT '任务执行时间',
  `datetime` bigint(20) NOT NULL COMMENT '任务产生时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Telegram 任务列表';

-- --------------------------------------------------------

--
-- 表的结构 `ticket`
--

CREATE TABLE `ticket` (
  `id` bigint(20) NOT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rootid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `datetime` bigint(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '注册邮箱',
  `password` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录密码',
  `passwd` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SS 密码',
  `uuid` varchar(146) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'VMESS/TROJAN UUID',
  `t` int(11) NOT NULL DEFAULT 0,
  `u` bigint(20) NOT NULL,
  `d` bigint(20) NOT NULL,
  `current_product_id` int(11) DEFAULT NULL COMMENT '用户当前产品ID',
  `transfer_enable` bigint(20) NOT NULL COMMENT '总流量',
  `enable` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否启用',
  `last_signin_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `reg_date` datetime NOT NULL COMMENT '注册日期',
  `money` decimal(12,2) NOT NULL COMMENT '金钱',
  `notify_type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '接收通知的的方式',
  `ref_by` int(11) NOT NULL DEFAULT 0,
  `expire_time` int(11) NOT NULL DEFAULT 0,
  `method` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aes-256-gcm' COMMENT '加密方式',
  `reg_ip` varchar(182) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '127.0.0.1',
  `node_speedlimit` decimal(12,2) NOT NULL DEFAULT 0.00,
  `node_connector` int(11) NOT NULL DEFAULT 0,
  `is_admin` int(11) NOT NULL DEFAULT 0,
  `last_day_t` bigint(20) NOT NULL DEFAULT 0,
  `class` int(11) NOT NULL DEFAULT 0,
  `class_expire` datetime NOT NULL DEFAULT '1989-06-04 00:05:00',
  `expire_in` datetime NOT NULL DEFAULT '2099-06-04 00:05:00',
  `theme` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `node_group` int(11) NOT NULL DEFAULT 0 COMMENT '分组',
  `protocol` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT 'origin',
  `protocol_param` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `obfs` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT 'plain',
  `obfs_param` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disconnect_ip` varchar(182) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegram_id` bigint(20) DEFAULT NULL,
  `expire_notified` tinyint(1) NOT NULL DEFAULT 0,
  `traffic_notified` tinyint(1) DEFAULT 0,
  `lang` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'zh-cn' COMMENT '用户的语言',
  `rebate` int(11) NOT NULL DEFAULT -1 COMMENT '返利百分比',
  `commission` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT '返利金额',
  `agent` int(11) NOT NULL DEFAULT 0 COMMENT '代理商',
  `withdraw_account_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withdraw_account` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户配置'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_hourly_usage`
--

CREATE TABLE `user_hourly_usage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `traffic` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hourly_usage` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_invite_code`
--

CREATE TABLE `user_invite_code` (
  `id` int(11) NOT NULL,
  `code` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '2016-06-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_password_reset`
--

CREATE TABLE `user_password_reset` (
  `id` int(11) NOT NULL,
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `init_time` int(11) NOT NULL,
  `expire_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_subscribe_log`
--

CREATE TABLE `user_subscribe_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `user_id` int(11) NOT NULL COMMENT '用户 ID',
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户邮箱',
  `subscribe_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '获取的订阅类型',
  `request_ip` varchar(182) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '请求 IP',
  `request_time` datetime NOT NULL COMMENT '请求时间',
  `request_user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '请求 UA 信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户订阅日志';

-- --------------------------------------------------------

--
-- 表的结构 `user_token`
--

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL,
  `token` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `expire_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_traffic_log`
--

CREATE TABLE `user_traffic_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `u` bigint(20) NOT NULL,
  `d` bigint(20) NOT NULL,
  `node_id` int(11) NOT NULL,
  `rate` float NOT NULL,
  `traffic` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` int(11) NOT NULL COMMENT '记录时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `alive_ip`
--
ALTER TABLE `alive_ip`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `email_verify`
--
ALTER TABLE `email_verify`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `login_ip`
--
ALTER TABLE `login_ip`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `node_info`
--
ALTER TABLE `node_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node_info_ibfk_2` (`node_id`);

--
-- 表的索引 `node_online_log`
--
ALTER TABLE `node_online_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node_online_log_ibfk_3` (`node_id`);

--
-- 表的索引 `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `payback`
--
ALTER TABLE `payback`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `payback_take_log`
--
ALTER TABLE `payback_take_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `telegram_session`
--
ALTER TABLE `telegram_session`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `telegram_tasks`
--
ALTER TABLE `telegram_tasks`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `uuid` (`uuid`) USING BTREE,
  ADD KEY `user_name` (`name`);

--
-- 表的索引 `user_hourly_usage`
--
ALTER TABLE `user_hourly_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `user_invite_code`
--
ALTER TABLE `user_invite_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `user_password_reset`
--
ALTER TABLE `user_password_reset`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user_subscribe_log`
--
ALTER TABLE `user_subscribe_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `user_traffic_log`
--
ALTER TABLE `user_traffic_log`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `alive_ip`
--
ALTER TABLE `alive_ip`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键';

--
-- 使用表AUTO_INCREMENT `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `email_verify`
--
ALTER TABLE `email_verify`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `link`
--
ALTER TABLE `link`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `login_ip`
--
ALTER TABLE `login_ip`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `node_info`
--
ALTER TABLE `node_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `node_online_log`
--
ALTER TABLE `node_online_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `order`
--
ALTER TABLE `order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'AUTO_INCREMENT';

--
-- 使用表AUTO_INCREMENT `payback`
--
ALTER TABLE `payback`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `payback_take_log`
--
ALTER TABLE `payback_take_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `telegram_session`
--
ALTER TABLE `telegram_session`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `telegram_tasks`
--
ALTER TABLE `telegram_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_hourly_usage`
--
ALTER TABLE `user_hourly_usage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_invite_code`
--
ALTER TABLE `user_invite_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_password_reset`
--
ALTER TABLE `user_password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_subscribe_log`
--
ALTER TABLE `user_subscribe_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_token`
--
ALTER TABLE `user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user_traffic_log`
--
ALTER TABLE `user_traffic_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 限制导出的表
--

--
-- 限制表 `node_info`
--
ALTER TABLE `node_info`
  ADD CONSTRAINT `node_info_ibfk_2` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `node_online_log`
--
ALTER TABLE `node_online_log`
  ADD CONSTRAINT `node_online_log_ibfk_3` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `user_invite_code`
--
ALTER TABLE `user_invite_code`
  ADD CONSTRAINT `user_invite_code_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
