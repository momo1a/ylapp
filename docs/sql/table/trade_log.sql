# 交易记录表
DROP TABLE IF EXISTS  `YL_trade_log`;
CREATE TABLE `YL_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '',
  `userType` tinyint(3) unsigned  not null default  '0' comment '用户类型1用户，2医生',
  `tradeVolume` DECIMAL(9,2) unsigned not null default '0' comment '交易额',
  `tradeDesc`  varchar(30) not null default '' comment '交易描述',
  `tradeChannel` tinyint(3) unsigned not null default '0' comment '交易渠道,0：本系统，1:支付宝，2：微信,3银联',
  `dateline` int(11) unsigned not null default '0' comment '交易时间',
  `tradeType` tinyint(3) unsigned not null default '0' comment '交易类型0提现，1充值，2疫苗费用，3基因费用，4电话问诊，5在线问答，6预约挂号，7电话问诊退款，预约挂号退款',
  `tradeNo` VARCHAR(32) NOT NULL DEFAULT '' comment '交易号',
  `status` tinyint(3) unsigned not null default '0' comment '0待处理，1已经确认，2失败',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `tradeNo` (`tradeNo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易记录表';