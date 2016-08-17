# 资讯收藏表
DROP TABLE IF EXISTS  `YL_news_collections`;
CREATE TABLE `YL_news_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` int(11) unsigned not null DEFAULT '0' comment '收藏者uid',
  `dateline` int(11) unsigned not null default '0' comment '收藏时间',
  PRIMARY KEY (`id`),
  UNIQUE  KEY `nid_uid` (`nid`,`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯收藏表';