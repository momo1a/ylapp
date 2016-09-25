/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.103_3306
Source Server Version : 50536
Source Host           : 192.168.1.103:3306
Source Database       : ylapp

Target Server Type    : MYSQL
Target Server Version : 50536
File Encoding         : 65001

Date: 2016-09-25 22:03:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for YL_menu
-- ----------------------------
DROP TABLE IF EXISTS `YL_menu`;
CREATE TABLE `YL_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '导航名称',
  `ctrl` varchar(20) NOT NULL DEFAULT '' COMMENT '控制器',
  `method` varchar(20) NOT NULL DEFAULT '' COMMENT '方法',
  `p_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '导航父id',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '状态(1:正常,0:停用)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of YL_menu
-- ----------------------------
INSERT INTO `YL_menu` VALUES ('1', '用户管理', 'User', 'index', '0', '13', '1');
INSERT INTO `YL_menu` VALUES ('2', '医生管理', 'Doctor', 'index', '0', '12', '1');
INSERT INTO `YL_menu` VALUES ('3', '首页管理', 'ClientIndex', 'index', '0', '10', '1');
INSERT INTO `YL_menu` VALUES ('4', '资讯管理', 'News', 'index', '0', '9', '1');
INSERT INTO `YL_menu` VALUES ('5', '交流圈', 'Post', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('6', '留言问答', 'LeavMsg', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('7', '电话问诊', 'TelOnline', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('8', '预约挂号', 'RegNum', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('9', '基因检测', 'Gene', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('10', '疫苗接种', 'Vaccine', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('11', '订单管理', 'Order', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('12', '提现管理', 'Cash', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('13', '客服', 'Customer', 'index', '0', '0', '1');
INSERT INTO `YL_menu` VALUES ('14', '账户管理', 'Auth', 'index', '0', '14', '1');
INSERT INTO `YL_menu` VALUES ('15', '医院管理', 'Hospital', 'index', '0', '11', '1');
