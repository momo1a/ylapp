/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.103_3306
Source Server Version : 50536
Source Host           : 192.168.1.103:3306
Source Database       : ylapp

Target Server Type    : MYSQL
Target Server Version : 50536
File Encoding         : 65001

Date: 2016-09-10 15:59:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for YL_banner
-- ----------------------------
DROP TABLE IF EXISTS `YL_banner`;
CREATE TABLE `YL_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型1：用户端，2：医生端',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='banner图片表';

-- ----------------------------
-- Records of YL_banner
-- ----------------------------
INSERT INTO `YL_banner` VALUES ('1', 'banner1', '1', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('2', 'banner2', '1', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('3', 'banner3', '1', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('4', 'banner4', '1', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('5', 'banner5', '2', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('6', 'banner6', '2', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('7', 'banner7', '2', 'banner1.png', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('8', 'banner8', '2', 'banner1.png', '1470361991', '1470361991', '0');
