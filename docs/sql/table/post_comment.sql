# 交流圈（帖子）评论表

DROP TABLE IF EXISTS  `YL_post_comment`;
CREATE TABLE `YL_post_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子id',
  `recmdUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户uid',
  `recmdNickname` VARCHAR(50)  NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `recmdContent` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
  `recmdTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postId` (`postId`) USING BTREE,
  KEY `recmdUid` (`recmdUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈（帖子）评论表';