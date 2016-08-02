# 用户病历表
DROP TABLE IF EXISTS  `YL_user_illness_history`;
CREATE TABLE `YL_user_illness_history` (
  `illId` int(11) unsigned AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '' comment '用户id',
  `username` varchar(30) not null default '0' comment '用户名',
  `illName` varchar(30) not null default '' comment '病历名称',
  `sex` tinyint(3) unsigned not null default '' comment '性别1男 2女',
  `allergyHistory` varchar(50) not null default '' comment '过敏史',
  `result` varchar(25) not null default '' comment '诊断结果',
  `stages` tinyint(3) not null default '0' comment '分期',
  `situation` VARCHAR(1000) not null default '' comment '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`illId`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户病历表';