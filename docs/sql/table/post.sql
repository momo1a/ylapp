# 交流圈帖子表
DROP TABLE IF EXISTS  `YL_post`;
CREATE TABLE `YL_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发帖人id',
  `postNickname` VARCHAR(50)  NOT NULL DEFAULT '' COMMENT '发帖人昵称',
  `postTitle` varchar(25) not null default '' comment '帖子标题',
  `postContent` varchar(300) NOT NULL DEFAULT '' COMMENT '内容',
  `img` varchar(500) not null default '' comment '图片',
  `postTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `isAnonymous` tinyint(3) unsigned not null default '0' comment '是否匿名发表0否,1是',
  `clickLikeCount` INT(11) unsigned not null default '0' comment '点赞数量',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postUid` (`postUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈帖子表';