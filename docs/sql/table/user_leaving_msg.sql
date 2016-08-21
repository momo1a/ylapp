# 用户留言表
DROP TABLE IF EXISTS  `YL_user_leaving_msg`;
CREATE TABLE `YL_user_leaving_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askerUid` int(11) unsigned not null default '0' COMMENT '问诊人uid',
  `illid` int(11) unsigned not null DEFAULT '0' comment '病历id',
  `askerNickname` VARCHAR(50) not null default '' comment '问诊人昵称',
  `askerPone` VARCHAR(20) not null default '' comment '问诊人电话',
  `askerContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容',
  `price` DECIMAL(9,2) NOT NULL DEFAULT '0' COMMENT '价钱',
  `docId` int(11) unsigned not null default '0' COMMENT '指定医生id',
  `docName` varchar(20) not null default '' comment '医生名称',
  `img` VARCHAR(300) not null default '' comment '图片',
  `askTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待付款，1待处理(已付款)，2通过（显示给医生看），3不通过（失败），4完成，5，医生已回答（等待客服审核）',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户留言表';