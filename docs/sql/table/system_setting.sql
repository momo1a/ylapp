# 系统设置表
DROP TABLE IF EXISTS  `YL_system_setting`;
CREATE TABLE `YL_system_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `settingKey` VARCHAR(25) NOT NULL DEFAULT '' comment '设置key',
  `settingValue` VARCHAR(60) NOT NULL DEFAULT '' comment '设置value',
  UNIQUE KEY (`settingKey`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统设置表';