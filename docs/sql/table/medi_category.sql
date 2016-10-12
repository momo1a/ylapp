#药品分类表
DROP TABLE IF EXISTS  `YL_medi_category`;
CREATE TABLE `YL_medi_category` (
  `cid` SMALLINT(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` SMALLINT(5) unsigned NOT NULL DEFAULT '0' COMMENT '父类id',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0正常, N预留',
  PRIMARY KEY (`cid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='药品分类表';