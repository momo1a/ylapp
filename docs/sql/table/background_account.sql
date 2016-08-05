# 后台账户表
DROP TABLE IF EXISTS  `YL_background_account`;
CREATE TABLE `YL_background_account` (
  `id` int(6) unsigned AUTO_INCREMENT,
  `name` varchar(20)  not null default '' comment '姓名',
  `telephone` varchar(20) not null default '' comment '手机号码',
  `pwd` varchar(80) not null default '' comment '密码',
  `degree` tinyint(3) unsigned not null default '0' comment '身份1管理员，2客服，N预留',
  `privileges` varchar(250) not null DEFAULT '' comment '权限',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台账户表';