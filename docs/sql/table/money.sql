# 用户金额表
DROP TABLE IF EXISTS  `YL_money`;
CREATE TABLE `YL_money` (
  `uid` int(11) unsigned NOT NULL,
  `amount` DECIMAL(9,2) unsigned NOT null DEFAULT '0' comment '用户金额',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  UNIQUE KEY (`uid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户金额表';