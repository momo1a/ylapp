# 用户-医生-日志表
DROP TABLE IF EXISTS  `YL_user_doctor_log`;
CREATE TABLE `YL_user_doctor_log` (
  `id` int(11) unsigned AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL DEFAULT '0' comment '用户id',
  `doctorId` int(11) unsigned not null default '0' comment '医生id',
  `comType` tinyint(3) unsigned not null default '0' comment '问诊类型1,留言问诊，2电话问诊，3预约挂号',
  `comState`tinyint(3) unsigned not null default '0' comment '问诊状态值 4,3,5 对应三个类型的完成状态',
  `description` varchar(30) not null default '' comment '日志描述',
  `dateline` int(11) unsigned not null default '0' comment '记录时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `doctorId` (`doctorId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户-医生-日志表';