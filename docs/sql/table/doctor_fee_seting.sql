# 医生费用设置表
DROP TABLE IF EXISTS  `YL_doctor_fee_seting`;
CREATE TABLE `YL_doctor_fee_seting` (
  `docId` int(11) unsigned NOT NULL comment '医生id',
  `docNicname` varchar(50) not null default '' comment '医生昵称',
  `leavMsgFee` DECIMAL(9,2) unsigned not null default '0' comment '留言费用',
  `leavMsgPer` DECIMAL(5,2) unsigned not null default '0' comment '留言费用平台分成',
  `regNumFee` DECIMAL(9,2) unsigned not null default '0' comment '挂号费用',
  `regNumPer` DECIMAL(5,2) unsigned not null default '0' comment '挂号费用平台分成',
  `phoneTimeLenFrist` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长一 单位：分',
  `phoneFeeFrist` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊一费用',
  `phonePerFrist` DECIMAL(5,2) unsigned not null default '0' comment '电话问诊一平台分成',
  `phoneTimeLenSecond` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长二 单位：分',
  `phoneFeeSecond` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊二费用',
  `phonePerSecond` DECIMAL(5,2) unsigned not null default '0' comment '电话问诊二平台分成',
  `phoneTimeLenThird` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长三 单位：分',
  `phoneFeeThird` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊三费用',
  `phonePerThrid` DECIMAL(5,2) unsigned not null default '0' comment '电话问诊三平台分成',
  `dateline` int(11) unsigned not null default '0' comment '时间线',
  PRIMARY KEY (`docId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生费用设置表';