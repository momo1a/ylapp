# 科室表
DROP TABLE IF EXISTS  `YL_doctor_offices`;
CREATE TABLE `YL_doctor_offices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `officeName` varchar(20) not null default '' comment '科室名',
  `status` tinyint(3) unsigned not null default '0' comment '0正常,n预留',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='科室表';