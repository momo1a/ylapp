/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.103_3306
Source Server Version : 50536
Source Host           : 192.168.1.103:3306
Source Database       : ylapp

Target Server Type    : MYSQL
Target Server Version : 50536
File Encoding         : 65001

Date: 2016-09-25 22:03:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for YL_user_menu
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_menu`;
CREATE TABLE `YL_user_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `mid` int(11) unsigned NOT NULL COMMENT '菜单id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_mid` (`uid`,`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COMMENT='用户菜单表';

-- ----------------------------
-- Records of YL_user_menu
-- ----------------------------
INSERT INTO `YL_user_menu` VALUES ('47', '1', '6');
INSERT INTO `YL_user_menu` VALUES ('134', '3', '2');
INSERT INTO `YL_user_menu` VALUES ('135', '3', '7');
INSERT INTO `YL_user_menu` VALUES ('137', '3', '8');
INSERT INTO `YL_user_menu` VALUES ('37', '4', '4');
INSERT INTO `YL_user_menu` VALUES ('38', '4', '8');
INSERT INTO `YL_user_menu` VALUES ('123', '5', '4');
INSERT INTO `YL_user_menu` VALUES ('118', '5', '5');
INSERT INTO `YL_user_menu` VALUES ('122', '5', '6');
INSERT INTO `YL_user_menu` VALUES ('116', '5', '7');
INSERT INTO `YL_user_menu` VALUES ('124', '5', '8');
INSERT INTO `YL_user_menu` VALUES ('121', '5', '12');
INSERT INTO `YL_user_menu` VALUES ('32', '6', '4');
INSERT INTO `YL_user_menu` VALUES ('31', '6', '10');
INSERT INTO `YL_user_menu` VALUES ('43', '7', '3');
INSERT INTO `YL_user_menu` VALUES ('46', '7', '7');
INSERT INTO `YL_user_menu` VALUES ('45', '7', '9');
INSERT INTO `YL_user_menu` VALUES ('44', '7', '13');
INSERT INTO `YL_user_menu` VALUES ('111', '8', '1');
INSERT INTO `YL_user_menu` VALUES ('112', '8', '3');
INSERT INTO `YL_user_menu` VALUES ('110', '8', '6');
INSERT INTO `YL_user_menu` VALUES ('114', '8', '9');
INSERT INTO `YL_user_menu` VALUES ('113', '8', '11');
INSERT INTO `YL_user_menu` VALUES ('125', '15', '1');
INSERT INTO `YL_user_menu` VALUES ('126', '15', '3');
INSERT INTO `YL_user_menu` VALUES ('128', '15', '10');
INSERT INTO `YL_user_menu` VALUES ('127', '15', '12');
INSERT INTO `YL_user_menu` VALUES ('133', '25', '4');
INSERT INTO `YL_user_menu` VALUES ('131', '25', '11');
INSERT INTO `YL_user_menu` VALUES ('130', '25', '13');
