#资讯评论表
DROP TABLE IF EXISTS  `YL_news_comment`;
CREATE TABLE `YL_news_comment` (
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论人id',
  `nickname` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `content` VARCHAR(125) NOT NULL DEFAULT '' COMMENT '评论内容',
  `dateline` INT(11) NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '评论状态0：未审核，1：通过，2：不通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯分类表';