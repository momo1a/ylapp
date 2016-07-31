# 用户留言表
DROP TABLE IF EXISTS  `YL_user_leaving_msg`;
CREATE TABLE `YL_user_leaving_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askUid` int(11) unsigned not null default '0' COMMENT '问诊人uid',
  `askNickname` VARCHAR(50) not null default '' comment '问诊人昵称',
  `askPone` VARCHAR(20) not null default '' comment '问诊人电话',
  `askContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容',
  `price` DECIMAL(9,2) NOT NULL DEFAULT '0' COMMENT '价钱',
  `docId` int(11) unsigned not null default '0' COMMENT '指定医生id',
  `docName` varchar(20) not null default '' comment '医生名称',
  `askTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0通过，1未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户留言表';