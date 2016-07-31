# 资讯表
DROP TABLE IF EXISTS  `YL_news`;
CREATE TABLE `YL_news` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` SMALLINT(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(15000) NOT NULL DEFAULT '' COMMENT '正文内容',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '作者',
  `thumbnail`varchar(80) NOT NULL DEFAULT '' COMMENT '缩略图',
  `banner` VARCHAR(80) NOT NULL DEFAULT '' COMMENT 'BANNER图',
  `tag` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '标签',
  `postPos` tinyint(3) NOT NULL DEFAULT '0' COMMENT '发布位置0：全部，1：用户端，2：医生端',
  `isRecmd` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐0：否，1：是',
  `isRecmdIndex` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐到首页0：否，1：是',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0未发布，1发布',
  PRIMARY KEY (`nid`),
  KEY `state` (`state`) USING BTREE,
  KEY `postPos` (`postPos`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯表';