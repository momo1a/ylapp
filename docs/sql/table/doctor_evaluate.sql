# 医生评价表
DROP TABLE IF EXISTS  `YL_doctor_evaluate`;
CREATE TABLE `YL_doctor_evaluate` (
  `vid` int(11) unsigned not null auto_increment,
  `docId` int(11) unsigned NOT NULL default '0' comment '医生id',
  `docNicname` varchar(50) not null default '' comment '医生昵称',
  `uid` int(11) unsigned not null default '0' comment '用户id',
  `username` varchar(50)  not null default '' comment '用户昵称',
  `content` VARCHAR(500) not null default '' comment '评价内容',
  `dateline` int(11) unsigned not null default '0' comment '评价时间',
  `state` tinyint(3) unsigned not null default '0' comment '0;待处理，1审核通过，2审核不通过',
  PRIMARY KEY (`vid`),
  KEY `docId` (`docId`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生评价表';