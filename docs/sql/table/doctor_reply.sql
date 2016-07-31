# 医生回复表

DROP TABLE IF EXISTS  `YL_doctor_reply`;
CREATE TABLE `YL_doctor_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `themeId` int(11) unsigned not null default '0' comment '主题id',
  `type` tinyint(3) unsigned not null default '0' comment '主题类型1：留言,n预留',
  `replyContent` VARCHAR(500) not null default '' comment '回复内容',
  `replyId` int(11) not null default '0' comment '回复者id',
  `replyNicname` varchar(50) not null default '' comment '回复者昵称',
  `replyTime` int(11) unsigned not null default '0' comment '回复时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待处理，1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `themeId` (`themeId`) USING BTREE,
  KEY `replyTime` (`state`) USING BTREE,
  KEY `replyId` (`replyId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生回复表';