# 交易记录表
DROP TABLE IF EXISTS  `YL_trade_log`;
CREATE TABLE `YL_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '',
  `username` varchar(30) not null default '' comment '反馈用户名',
  `userType` tinyint(3) unsigned  not null default  '0' comment '用户类型1用户，2医生',
  `tradeVolume` DECIMAL(9,2) unsigned not null default '0' comment '交易额',
  `tradeDesc`  varchar(30) not null default '' comment '交易描述',
  `tradeChannel` tinyint(3) unsigned not null default '0' comment '交易渠道,0：本系统，1:支付宝，2：微信',
  `dateline` int(11) unsigned not null default '0' comment '交易时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易记录表';