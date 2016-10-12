# 支付表
DROP TABLE IF EXISTS  `YL_pay`;
CREATE TABLE `YL_pay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '用户id',
  `oid` int(11) unsigned not null default '0' comment '订单id',
  `userType` tinyint(3) unsigned  not null default  '0' comment '用户类型1用户，2医生',
  `tradeVolume` DECIMAL(9,2) unsigned not null default '0' comment '交易额',
  `tradeNo` VARCHAR(32) NOT NULL DEFAULT '' comment '交易号',
  `tradeDesc`  varchar(30) not null default '' comment '交易描述',
  `tradeChannel` tinyint(3) unsigned not null default '0' comment '交易渠道,0：本系统，1:支付宝，2：微信,3银联',
  `dateline` int(11) unsigned not null default '0' comment '交易时间',
  `tradeType` tinyint(3) unsigned not null default '0' comment '０提现，１充值，２疫苗费用，３基因费用，４电话问诊，５在线问答，６预约挂号',
  `status` tinyint(3) unsigned not null default '0' comment '0未付款，1已付款',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付表';