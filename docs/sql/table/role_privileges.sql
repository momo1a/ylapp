# 权限表
DROP TABLE IF EXISTS  `YL_role_privileges`;
CREATE TABLE `YL_role_privileges` (
  `id` int(11) unsigned AUTO_INCREMENT
   ####
  PRIMARY KEY (`id`),
  KEY `illId` (`illId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';