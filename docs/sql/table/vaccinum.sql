# 疫苗接种表
DROP TABLE IF EXISTS  `YL_vaccinum`;
CREATE TABLE `YL_vaccinum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned not null default '0' comment '类型,1儿童类型，2成人类型',
  `name` varchar(125) not null default '' comment '套餐名称',
  `detail` varchar(10000) not null default '' comment '套餐详情',
  `dateline` int(11) unsigned not null default '0' comment '发布时间',
  `price` DECIMAL(9,2) unsigned not null default '0' comment '价钱(定金)',
  `remainAmount` DECIMAL(9,2) unsigned not null default '0' comment '剩余款项',
  `thumbnail` varchar(125)  not null default '' comment '缩略图',
  `status` tinyint(3) unsigned not null default '0' comment '状态：1上架，2下架',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='疫苗接种表';