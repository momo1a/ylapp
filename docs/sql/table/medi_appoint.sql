# 药品表预约表
DROP TABLE IF EXISTS  `YL_medi_appoint`;
CREATE TABLE `YL_medi_appoint` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) not null default '' comment '预约人姓名',
  `telephone` VARCHAR(15) not null DEFAULT '' comment '预约人电话',
  `mediId` int(11) not null default '0' comment '药品id',
  `mediName` VARCHAR(30) not null DEFAULT '' comment '预约药品名',
  `content` VARCHAR(20000) not null default '' comment '内容',
  `regPhone` VARCHAR(15) not null default '' comment '用户注册手机号',
  `userId` INT(11) not null default '0' comment '用户uid',
  `guysId` INT(11) not null DEFAULT '0' comment '伙计id',
  `dateline` int(11) not null default '0' comment '记录时间',
  `appointTime` int(11) not null default '0' comment '预约时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0未分配，1分配',
  PRIMARY KEY (`id`),
  KEY `mediId` (`mediId`) USING BTREE,
  KEY `userId` (`userId`) USING BTREE,
  KEY `guysId` (`guysId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='药品表预约表';