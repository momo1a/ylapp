# 医生电话问诊备注表
DROP TABLE IF EXISTS  `YL_doctor_phone_diagnosis_remarks`;
CREATE TABLE `YL_doctor_phone_diagnosis_remarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned not null default '0' comment '类型1:电话问诊，2：挂号',
  `DiagId` int(11) unsigned not null default '0' comment '问诊记录id',
  `remarkContent` varchar(300)  not null default '' comment '医生备注',
  `img` varchar(500) not null default '' comment '图片',
  `remarkTime` int(11) unsigned not null default '0' comment '备注时间',
  PRIMARY KEY (`id`),
  KEY `DiagId` (`DiagId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生电话问诊备注表';