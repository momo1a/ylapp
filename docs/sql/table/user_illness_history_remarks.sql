# 用户病历记录表
DROP TABLE IF EXISTS  `YL_user_illness_history_remarks`;
CREATE TABLE `YL_user_illness_history_remarks` (
  `id` int(11) unsigned AUTO_INCREMENT
  `illId` int(11) unsigned not null default '0' comment '病历id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' comment '用户id',
  `visitDate` int(11) unsigned not null default '0' comment '就诊日期',
  `stage` varchar(10) not null default '' comment '分期：初诊。。。',
  `content` varchar(600) not null default '' comment '病情记录',
  `img` varchar(300) not null default '' comment '图片',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`),
  KEY `illId` (`illId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户病历记录表';