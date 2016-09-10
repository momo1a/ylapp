# 帮助表
DROP TABLE IF EXISTS  `YL_help`;
CREATE TABLE `YL_help` (
  `id` int(6) unsigned AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL DEFAULT '' comment '帮助标题',
  `description` VARCHAR(500) NOT NULL DEFAULT '' comment '描述',
  `dateline` int(11) unsigned not null DEFAULT '0' comment '时间',
  `type` tinyint(3) unsigned not null default '0' comment '1用户端 2医生端',
  `isShow` tinyint(3) unsigned not null default '0' comment '0不显示 1显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帮助表';


