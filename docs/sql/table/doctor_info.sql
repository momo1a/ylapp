# 医生信息表
DROP TABLE IF EXISTS  `YL_doctor_info`;
CREATE TABLE `YL_doctor_info` (
  `uid` int(11) unsigned NOT NULL,
  `hid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属医院id',
  `offices` VARCHAR(25) NOT NULL DEFAULT '' COMMENT '科室',
  `degree` varchar(30) not null default '' comment '学位',
  `sex` tinyint(3) unsigned not null default '0' comment '性别',
  `phoneSec` varchar(15) NOT NULL DEFAULT '' COMMENT '电话二',
  `summary` VARCHAR(1000) not null default '' comment '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态:0未通过,1通过',
  PRIMARY KEY (`uid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生信息表';