# 用户表
DROP TABLE IF EXISTS  `YL_user`;
CREATE TABLE `YL_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `userType` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型（1：用户，2：医生）',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `regIp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册IP',
  `lastLoginTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `lastLoginIp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录ip',
  `email` varchar(150) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `sex` tinyint(3) unsigned not null DEFAULT '1' comment '性别,1男，2女',
  `birthday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '出生日期',
  `avatar` varchar(50) not null default '' comment '头像',
  `isBlack` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否黑名单:0否,1是',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户状态：0正常，N保留',
  PRIMARY KEY (`uid`),
  KEY `uname` (`nickname`) USING BTREE,
  KEY `mobile` (`phone`) USING BTREE,
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

