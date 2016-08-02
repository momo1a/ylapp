# 反馈表
DROP TABLE IF EXISTS  `YL_feedback`;
CREATE TABLE `YL_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '反馈用户id',
  `username` varchar(30) not null default '' comment '反馈用户名',
  `userType` tinyint(3) unsigned  not null default  '0' comment '反馈用户类型1用户，2医生',
  `dateline` int(11) unsigned not null default '0' comment '反馈时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈表';