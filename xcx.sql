CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wu_id` int(11) DEFAULT NULL, 
  `openid` varchar(255) DEFAULT NULL,
  `svc_number` varchar(255) DEFAULT NULL,
  `is_yzf` tinyint(1) DEFAULT NULL, -- 是否开通翼支付
  `subscribed` tinyint(1) DEFAULT NULL, -- 是否绑定
  `city_id` int(11) DEFAULT NULL, -- cities.id
  `area_id` int(11) DEFAULT NULL, -- areas.id
  `member` int(11) DEFAULT NULL, 
  `bar_code_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `account_id` int(11) DEFAULT NULL, -- accounts.id
  `user_star` int(11) DEFAULT NULL, -- 用户星级
  `last_update` int(11) DEFAULT NULL, -- 最后更新时间戳
  `user_name` varchar(255) DEFAULT NULL, -- 用户实名
  `meal_level` int(11) DEFAULT NULL, -- 套餐等级
  `regist_time` datetime DEFAULT NULL, 
  `city_code` varchar(255) DEFAULT NULL, 
  `is_real_name` tinyint(1) DEFAULT NULL, -- 是否实名用户
  `is_bind_card` tinyint(1) DEFAULT NULL, -- 是否绑卡
  `ext_attrs` text, -- json 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `openid` varchar(28) CHARACTER SET utf8 DEFAULT NULL, -- 微信openid
  `fakeid` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL, -- 微信昵称
  `cname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `followtime` int(11) DEFAULT NULL,
  `subscribed` tinyint(4) DEFAULT '1', -- 判断是关注
  `gender` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `geo_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `city` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scale` int(11) DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT NULL,
  `last_scan_id` int(11) DEFAULT NULL,
  `uniqid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_system_id` varchar(10) COLLATE utf8_unicode_ci DEFAULT 'id',
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unionid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `tagid_list` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subscribe_scene` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qr_scene` int(11) DEFAULT NULL,
  `qr_scene_str` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_wus_on_openid` (`openid`),
  KEY `index_wus_on_unionid` (`unionid`),
  KEY `index_wus_on_sub` (`sub_system_id`,`key`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `yzf_binds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(64) DEFAULT NULL,
  `wu_id` int(11) DEFAULT NULL, -- wus.id
  `svc_number` varchar(16) DEFAULT NULL,  --手机号
  `sms_code` varchar(16) DEFAULT NULL, 
  `sms_at` datetime DEFAULT NULL,
  `sms_check_cnt` int(11) DEFAULT '0',
  `status` varchar(3) DEFAULT 'I0A', -- 是否绑定： B0A=>绑定， I0A=>未绑定， U0A=>取消关注
  `aasm_state` varchar(32) DEFAULT 'init',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `first_state` int(1) DEFAULT NULL COMMENT 'null、0：首次绑定，1：同步交易记录成功，2：3005',
  `is_yzf` int(1) DEFAULT '0' COMMENT '0:未判断，1是， 2：否',
  `ref_svc_number` varchar(16) DEFAULT '0',
  `ref_state` tinyint(1) DEFAULT '0',
  `ref_desc` varchar(255) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `unionid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_yzf_binds_on_openid` (`openid`),
  KEY `idx_wu_id` (`wu_id`),
  KEY `svc_number` (`svc_number`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- 小程序表
CREATE TABLE `app_accounts`(
`id` int(11) NOT NULL AUTO_INCREMENT, -- 不同小程序的id
`name` varchar(255) DEFAULT NULL, -- 小程序名称
`description` text,-- 小程序简介描述
`app_id` varchar(32) DEFAULT NULL, -- AppID(小程序ID)
`app_secret` varchar(64) DEFAULT NULL,-- AppSecret(小程序密钥)	
)