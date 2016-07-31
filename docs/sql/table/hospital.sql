# 医院表
DROP TABLE IF EXISTS  `YL_hospital`;
CREATE TABLE `YL_hospital` (
  `hid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '医院名称',
  `address` varchar(60) NOT NULL DEFAULT '' COMMENT '医院地址',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`hid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医院表';