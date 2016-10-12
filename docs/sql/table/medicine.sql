# 药品表
DROP TABLE IF EXISTS  `YL_medicine`;
CREATE TABLE `YL_medicine` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` SMALLINT(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '品名',
  `outline` VARCHAR(300) NOT NULL DEFAULT '' comment '概述',
  `content` varchar(15000) NOT NULL DEFAULT '' COMMENT '正文内容',
  `thumbnail`varchar(80) NOT NULL DEFAULT '' COMMENT '缩略图',
  `banner`varchar(80) NOT NULL DEFAULT '' COMMENT '药品banner',
  `dateline` int(11) not null default '0' comment '添加时间',
  `editTime` int(11) not null default '0' comment '编辑时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，1预留',
  PRIMARY KEY (`id`),
  KEY `state` (`cid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='药品表';