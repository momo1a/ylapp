# 首页广告词表
DROP TABLE IF EXISTS  `YL_index_ad_word`;
CREATE TABLE `YL_index_ad_word` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(125) not null default '' comment '广告词',
  `type` tinyint(3) unsigned not null default '0' comment '类型 1在线诊疗，2个人病历，3基因检测，4疫苗接种',
  `dateline` int(11) unsigned not null default '0' comment '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页广告词表';

INSERT INTO  `YL_index_ad_word` ( `word`, `type`, `dateline`) VALUES ('挑家好医院就诊', '1', '1470359891');
INSERT INTO  `YL_index_ad_word` ( `word`, `type`, `dateline`) VALUES ('自己的病历自己管', '2', '1470359892');
INSERT INTO  `YL_index_ad_word` ( `word`, `type`, `dateline`) VALUES ('提供最权威的基因检测', '3', '1470359893');
INSERT INTO  `YL_index_ad_word` ( `word`, `type`, `dateline`) VALUES ('最新最安全的疫苗', '4', '1470359894');



