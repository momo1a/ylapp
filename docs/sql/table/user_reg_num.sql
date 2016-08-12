# 用户挂号表
DROP TABLE IF EXISTS  `YL_user_reg_num`;
CREATE TABLE `YL_user_reg_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned not null default '0' comment '挂号用户id',
  `userName` varchar(20)  not null default '' comment '挂号用户名',
  `docId` int(11) unsigned not null default '0' comment '指定医生id',
  `docName` varchar(30)  not null default '' comment '指定医生的昵称',
  `docTel` varchar(20)  not null default '' comment '医生电话',
  `price` DECIMAL(9,2) unsigned not null default '0' comment '价钱',
  `hosAddr` varchar(255) not null default '' comment '医院地址',
  `contacts` varchar(60) not null default '' comment '联系人',
  `appointTime` int(11) unsigned not null default '0' comment '预约时间',
  `sex` tinyint(3) unsigned not null default '0' comment '性别:1男；2女',
  `appointBrithday` int(11) unsigned not null default '0' comment '预约人的生日',
  `appointTel` varchar(25) not null default '' comment '预约人的电话',
  `illnessId` int(11) not null default '0' comment '病历id',
  `userRemark` varchar(200) not null default '' comment '用户备注',
  `dateline` int(11) unsigned not null default '0' comment '记录时间',
  `status` tinyint(3) not null default '0' comment '状态 0.状态有待处理，1未支付 2已支付,3预约成功，4预约失败，5完成',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户挂号表';