DROP TABLE IF EXISTS `YL_menu`;
CREATE TABLE IF NOT EXISTS `YL_menu` (
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(20) NOT NULL DEFAULT "" COMMENT '导航名称',
  `ctrl` VARCHAR(20) not null default ""  comment '控制器',
  `method` varchar(20) not null default "" comment '方法',
  `p_id` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '导航父id',
  `sort` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态(1:正常,0:停用)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='菜单表' ;

DROP TABLE IF EXISTS `YL_user_menu`;
CREATE TABLE IF NOT EXISTS `YL_user_menu` (
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` INT(11) unsigned not null comment '用户id',
  `mid` int(11) unsigned not null comment '菜单id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid` (`uid`,`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户菜单表' ;