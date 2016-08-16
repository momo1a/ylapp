 # 提现表
DROP TABLE IF EXISTS  `YL_take_cash`;
CREATE TABLE `YL_take_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '提现人id',
  `bank` varchar(125) not null default '' comment '开户银行',
  `address` varchar(125) not null default '' comment '开户地区',
  `cardNum` varchar(50) not null default '' comment '银行卡号',
  `realName` varchar(18) not null default '' comment '真实姓名',
  `identity` varchar(30) not null default '' comment '身份证',
  `amount` DECIMAL(9,2) unsigned not null default '0' comment '提现金额',
  `userType` tinyint(3) unsigned not null default '0' comment '用户类型1用户端，2医生端',
  `dateline` int(11) unsigned not null default '0' comment '提现时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态：0.待处理，1已确认，2驳回',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `userType` (`userType`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现表';