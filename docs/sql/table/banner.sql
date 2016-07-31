# banner图片表
DROP TABLE IF EXISTS  `YL_banner`;
CREATE TABLE `YL_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型1：用户端，2：医生端',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='banner图片表';