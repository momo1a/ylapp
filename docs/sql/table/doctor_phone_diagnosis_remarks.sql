# 医生电话问诊备注表
DROP TABLE IF EXISTS  `YL_doctor_phone_diagnosis_remarks`;
CREATE TABLE `YL_doctor_phone_diagnosis_remarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `TelDiagId` int(11) unsigned not null default '0' comment '电话问诊id',
  `remarkContent` varchar(300)  not null default '' comment '医生备注',
  `remarkTime` int(11) unsigned not null default '0' comment '备注时间',
  PRIMARY KEY (`id`),
  KEY `TelDiagId` (`TelDiagId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生电话问诊备注表';