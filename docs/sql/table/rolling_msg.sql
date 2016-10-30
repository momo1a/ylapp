# 滚动消息表
DROP TABLE IF EXISTS  `YL_rolling_msg`;
CREATE TABLE `YL_rolling_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` VARCHAR(50) not null default '' comment '手动添加内容',
  `uid` int(11) unsigned NOT NULL default '0' comment '操作账户uid',
  `dateline` int(11) unsigned NOT NULL default '0' comment '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='滚动消息表';