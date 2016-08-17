# 关于我们表
DROP TABLE IF EXISTS  `YL_about_us`;
CREATE TABLE `YL_about_us` (
  `id` int(6) unsigned AUTO_INCREMENT,
  `telephone` varchar(20) not null default '' comment '手机号码',
  `email` varchar(50) not null default '' comment '邮箱',
  `address` varchar(120) not null default '' comment '地址',
  `description` varchar(1000) not null DEFAULT '' comment '公司简介',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关于我们表';

INSERT INTO `YL_about_us` (`telephone`, `email`, `address`, `description`) VALUES ('0751-832501465', 'test@qq.com', '深圳南山区南山大道', '公司简介公司简介公司简介公司简介公司简介公司简介公司简介公司简介公司简介公司简介公司简介');
