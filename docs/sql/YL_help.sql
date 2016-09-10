/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.103_3306
Source Server Version : 50536
Source Host           : 192.168.1.103:3306
Source Database       : ylapp

Target Server Type    : MYSQL
Target Server Version : 50536
File Encoding         : 65001

Date: 2016-09-10 12:37:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for YL_help
-- ----------------------------
DROP TABLE IF EXISTS `YL_help`;
CREATE TABLE `YL_help` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '帮助标题',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1用户端 2医生端',
  `isShow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0不显示 1显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='帮助表';

-- ----------------------------
-- Records of YL_help
-- ----------------------------
INSERT INTO `YL_help` VALUES ('1', '我想预约医生挂号', '描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述描述', '1445454515', '1', '1');
INSERT INTO `YL_help` VALUES ('2', '我忘记密码怎么办', '描述描述描述描述描述描述描述描述描述描述描述', '1545451515', '1', '1');
INSERT INTO `YL_help` VALUES ('3', '为什么我的手机无法注册', '描述描述描述描述描述描述描述描述', '1541554541', '1', '1');
INSERT INTO `YL_help` VALUES ('4', '医生端帮助1', '费的方式的方式发的', '154545454', '2', '1');
INSERT INTO `YL_help` VALUES ('5', '医生端帮助2', '发动发动机风口浪尖了', '15454545', '2', '1');
