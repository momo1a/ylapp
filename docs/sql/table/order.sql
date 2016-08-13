 # 订单表
DROP TABLE IF EXISTS  `YL_order`;
CREATE TABLE `YL_order` (
  `oid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyerId` int(11) unsigned not null default '0' comment '购买人id',
  `buyerName` varchar(25) not null default '' comment '购买人名称',
  `buyerSex` tinyint(3) unsigned not null default '0' comment '购买人性别1男 2女',
  `buyerTel` varchar(20) not null default '' comment '购买人电话',
  `buyerBrithday` int(11) unsigned not null default '0' comment '购买人出生日期',
  `packageId` int(11) unsigned not null default '0' comment '购买套餐id',
  `packageTitle` varchar(255) not null default '' comment '购买套餐名称',
  `price` decimal(9,2) unsigned not null default '0' comment '套餐价格',
  `type` tinyint(3) unsigned not null default '0' comment '类型：1疫苗接种，2基因检测',
  `dateline` int(11) unsigned not null default '0' comment '下单时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态：1.待支付，2已支付，3待处理，4已通知，5完成',
  PRIMARY KEY (`oid`),
  KEY `status` (`status`) USING BTREE,
  KEY `packageId` (`packageId`) USING BTREE,
  KEY `buyerId` (`buyerId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';