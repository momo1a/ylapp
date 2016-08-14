# 交流圈帖子点赞表

DROP TABLE IF EXISTS  `YL_post_click_like`;
CREATE TABLE `YL_post_click_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发帖人id',
  `postId` int(11) unsigned not null default '0' comment '帖子id',
  `clickTime` INT(11) unsigned not null default '0' comment '点赞时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_postId` (`uid`,`postId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈帖子表';