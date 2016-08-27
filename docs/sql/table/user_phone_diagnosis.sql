# 用户电话问诊表
DROP TABLE IF EXISTS  `YL_user_phone_diagnosis`;
CREATE TABLE `YL_user_phone_diagnosis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askUid` int(11) unsigned not null default '0' COMMENT '问诊人uid',
  `illnessId` int(11) unsigned not null default '0' comment '病历id',
  `askNickname` VARCHAR(50) not null default '' comment '问诊人昵称',
  `age` tinyint(3) unsigned not null default '0' comment '年龄',
  `askTelephone` VARCHAR(20) not null default '' comment '问诊人电话',
  `ask_sex` tinyint(3) unsigned not null default '0' comment '问诊人性别1男 2女',
  `askContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容简述',
  `otherIllness` VARCHAR(200) not null default '' comment '其他病史内容',
  `phoneTimeLen` SMALLINT(5) unsigned not null default '0' comment '通话时长',
  `hopeCalldate` int(11) unsigned not null default '0' comment '期望通话的日期',
  `price` DECIMAL(9,2) NOT NULL DEFAULT '0' COMMENT '价钱',
  `docId` int(11) unsigned not null default '0' COMMENT '医生id',
  `docName` varchar(20) not null default '' comment '医生名称',
  `docTelephone` varchar(20) not null default '' comment '医生电话',
  `askTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `cencelTime` INT(11) unsigned not null default '0' comment '取消时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待付款,1待审核(已付款 管理员协调双方),2已确认沟通时间，3完成，4失败，5用户取消',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `askUid` (`askUid`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户电话问诊表';