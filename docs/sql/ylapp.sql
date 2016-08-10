/*
Navicat MySQL Data Transfer

Source Server         : 192.168.61.97
Source Server Version : 50542
Source Host           : 192.168.61.97:3306
Source Database       : ylapp

Target Server Type    : MYSQL
Target Server Version : 50542
File Encoding         : 65001

Date: 2016-08-10 17:57:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for YL_background_account
-- ----------------------------
DROP TABLE IF EXISTS `YL_background_account`;
CREATE TABLE `YL_background_account` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `pwd` varchar(80) NOT NULL DEFAULT '' COMMENT '密码',
  `degree` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '身份1管理员，2客服，N预留',
  `privileges` varchar(250) NOT NULL DEFAULT '' COMMENT '权限',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台账户表';

-- ----------------------------
-- Records of YL_background_account
-- ----------------------------

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
INSERT INTO `YL_banner` VALUES ('2', 'banner2', '1', 'banner2,jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('3', 'banner3', '1', 'banner3.jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('4', 'banner4', '1', 'banner4.jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('5', 'banner5', '2', 'banner4.jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('6', 'banner6', '2', 'banner6.jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('7', 'banner7', '2', 'banner7.jpg', '1470361991', '1470361991', '0');
INSERT INTO `YL_banner` VALUES ('8', 'banner8', '2', 'banner8.jpg', '1470361991', '1470361991', '0');

-- ----------------------------
-- Table structure for YL_doctor_evaluate
-- ----------------------------
DROP TABLE IF EXISTS `YL_doctor_evaluate`;
CREATE TABLE `YL_doctor_evaluate` (
  `vid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `docId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '医生id',
  `docNicname` varchar(50) NOT NULL DEFAULT '' COMMENT '医生昵称',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `content` varchar(50) NOT NULL DEFAULT '' COMMENT '评价内容',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评价时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0;待处理，1审核通过，2审核不通过',
  PRIMARY KEY (`vid`),
  KEY `docId` (`docId`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='医生评价表';

-- ----------------------------
-- Records of YL_doctor_evaluate
-- ----------------------------
INSERT INTO `YL_doctor_evaluate` VALUES ('1', '6', '赵医生', '1', '张三', '人帅医术高明', '1470820966', '1');
INSERT INTO `YL_doctor_evaluate` VALUES ('2', '6', '赵医生', '1', '张三', '人帅医术高明', '1470820922', '0');
INSERT INTO `YL_doctor_evaluate` VALUES ('3', '6', '赵医生', '1', '张三', '人帅医术高明', '1470820911', '1');
INSERT INTO `YL_doctor_evaluate` VALUES ('4', '6', '赵医生', '1', '张三', '人帅医术高明', '1470820888', '0');
INSERT INTO `YL_doctor_evaluate` VALUES ('5', '6', '赵医生', '1', '张三', '人帅医术高明', '1470820877', '1');

-- ----------------------------
-- Table structure for YL_doctor_fee_seting
-- ----------------------------
DROP TABLE IF EXISTS `YL_doctor_fee_seting`;
CREATE TABLE `YL_doctor_fee_seting` (
  `docId` int(11) unsigned NOT NULL COMMENT '医生id',
  `docNicname` varchar(50) NOT NULL DEFAULT '' COMMENT '医生昵称',
  `leavMsgFee` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '留言费用',
  `leavMsgPer` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '留言费用平台分成',
  `regNumFee` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '挂号费用',
  `regNumPer` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '挂号费用平台分成',
  `phoneTimeLenFirst` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '电话问诊时长一 单位：分',
  `phoneFeeFirst` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊一费用电话问诊时长一 单位：分',
  `phonePerFirst` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊一平台分成',
  `phoneTimeLenSecond` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '电话问诊时长二 单位：分',
  `phoneFeeSecond` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊二费用',
  `phonePerSecond` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊二平台分成',
  `phoneTimeLenThird` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '电话问诊时长三 单位：分',
  `phoneFeeThird` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊三费用',
  `phonePerThird` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '电话问诊三平台分成',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间线',
  PRIMARY KEY (`docId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生费用设置表';

-- ----------------------------
-- Records of YL_doctor_fee_seting
-- ----------------------------
INSERT INTO `YL_doctor_fee_seting` VALUES ('6', '赵医生', '20.00', '2.50', '30.00', '3.50', '15', '40.00', '4.50', '30', '50.00', '3.00', '45', '80.00', '2.22', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('7', '李医生', '12.00', '1.22', '20.00', '1.18', '20', '50.00', '0.88', '40', '60.00', '0.89', '50', '100.00', '1.18', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('8', '钱医生', '8.00', '2.12', '28.00', '1.12', '28', '50.00', '1.20', '60', '120.00', '0.66', '100', '80.00', '1.92', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('9', '孙医生', '22.00', '1.12', '25.00', '2.12', '25', '30.00', '1.50', '30', '40.00', '0.22', '80', '130.00', '1.11', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('10', '鲁医生', '12.00', '1.18', '28.00', '2.13', '32', '40.00', '2.18', '60', '70.00', '0.18', '90', '155.00', '1.19', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('11', '黄医生', '9.00', '1.16', '33.00', '1.92', '32', '45.00', '6.62', '70', '60.00', '0.99', '110', '166.00', '1.88', '1470794030');
INSERT INTO `YL_doctor_fee_seting` VALUES ('12', '梁医生', '6.00', '0.11', '18.00', '0.99', '55', '30.00', '0.88', '60', '40.00', '0.88', '120', '130.00', '1.99', '1470794030');

-- ----------------------------
-- Table structure for YL_doctor_info
-- ----------------------------
DROP TABLE IF EXISTS `YL_doctor_info`;
CREATE TABLE `YL_doctor_info` (
  `uid` int(11) unsigned NOT NULL,
  `hid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属医院id',
  `officeId` smallint(5) NOT NULL DEFAULT '0' COMMENT '科室',
  `degree` varchar(30) NOT NULL DEFAULT '' COMMENT '学位',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `phoneSec` varchar(15) NOT NULL DEFAULT '' COMMENT '电话二',
  `summary` varchar(1000) NOT NULL DEFAULT '' COMMENT '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态:0未通过,1通过',
  `goodAt` varchar(300) NOT NULL DEFAULT '' COMMENT '擅长',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid` (`uid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生信息表';

-- ----------------------------
-- Records of YL_doctor_info
-- ----------------------------
INSERT INTO `YL_doctor_info` VALUES ('6', '2', '3', '研究生', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('7', '3', '4', '博士', '2', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('8', '1', '2', '博士生导师', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('9', '2', '5', '博士生导师', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('10', '2', '4', '博士生导师', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('11', '1', '1', '博士生导师', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');
INSERT INTO `YL_doctor_info` VALUES ('12', '2', '2', '博士生导师', '1', '15888888888', '深圳医院业务院长，博士生导师，我省资深内科专家，首都医科大学宣武医院特约顾问，中国、美国、德国三国持照医师，多年从事三甲医院的管理工作，被聘为多家医学杂志常务编委，享受国务院特殊津贴。', '1', '医生在北京大学深圳医院肿瘤科作为副主任医师帮助了无数肿瘤患者。\r\n \r\n擅长乳腺、甲状腺、胃肠外科肿瘤疾病的诊治，以及腹腔微创技术。');

-- ----------------------------
-- Table structure for Yl_doctor_offices
-- ----------------------------
DROP TABLE IF EXISTS `Yl_doctor_offices`;
CREATE TABLE `Yl_doctor_offices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `officeName` varchar(20) NOT NULL DEFAULT '' COMMENT '科室名',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0正常,n预留',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='科室表';

-- ----------------------------
-- Records of Yl_doctor_offices
-- ----------------------------
INSERT INTO `Yl_doctor_offices` VALUES ('1', '肿瘤科', '0');
INSERT INTO `Yl_doctor_offices` VALUES ('2', '内科', '0');
INSERT INTO `Yl_doctor_offices` VALUES ('3', '心脏科', '0');
INSERT INTO `Yl_doctor_offices` VALUES ('4', '外科', '0');
INSERT INTO `Yl_doctor_offices` VALUES ('5', '儿科', '0');

-- ----------------------------
-- Table structure for YL_doctor_phone_diagnosis_remarks
-- ----------------------------
DROP TABLE IF EXISTS `YL_doctor_phone_diagnosis_remarks`;
CREATE TABLE `YL_doctor_phone_diagnosis_remarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型1:电话问诊，2：挂号',
  `DiagId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊记录id',
  `remarkContent` varchar(300) NOT NULL DEFAULT '' COMMENT '医生备注',
  `img` varchar(500) NOT NULL DEFAULT '' COMMENT '图片',
  `remarkTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '备注时间',
  PRIMARY KEY (`id`),
  KEY `DiagId` (`DiagId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生电话问诊备注表';

-- ----------------------------
-- Records of YL_doctor_phone_diagnosis_remarks
-- ----------------------------

-- ----------------------------
-- Table structure for YL_doctor_reply
-- ----------------------------
DROP TABLE IF EXISTS `YL_doctor_reply`;
CREATE TABLE `YL_doctor_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `themeId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主题id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '主题类型1：留言,n预留',
  `replyContent` varchar(500) NOT NULL DEFAULT '' COMMENT '回复内容',
  `replyId` int(11) NOT NULL DEFAULT '0' COMMENT '回复者id',
  `replyNicname` varchar(50) NOT NULL DEFAULT '' COMMENT '回复者昵称',
  `replyTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待处理，1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `themeId` (`themeId`) USING BTREE,
  KEY `replyTime` (`state`) USING BTREE,
  KEY `replyId` (`replyId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生回复表';

-- ----------------------------
-- Records of YL_doctor_reply
-- ----------------------------

-- ----------------------------
-- Table structure for YL_feedback
-- ----------------------------
DROP TABLE IF EXISTS `YL_feedback`;
CREATE TABLE `YL_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '反馈用户id',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '反馈用户名',
  `userType` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '反馈用户类型1用户，2医生',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '反馈时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈表';

-- ----------------------------
-- Records of YL_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for YL_gene_check
-- ----------------------------
DROP TABLE IF EXISTS `YL_gene_check`;
CREATE TABLE `YL_gene_check` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL DEFAULT '' COMMENT '套餐名称',
  `detail` varchar(10000) NOT NULL DEFAULT '' COMMENT '套餐详情',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价钱',
  `thumbnail` varchar(125) NOT NULL DEFAULT '' COMMENT '缩略图',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：1上架，2下架',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基因检测表';

-- ----------------------------
-- Records of YL_gene_check
-- ----------------------------

-- ----------------------------
-- Table structure for YL_hospital
-- ----------------------------
DROP TABLE IF EXISTS `YL_hospital`;
CREATE TABLE `YL_hospital` (
  `hid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '医院名称',
  `address` varchar(60) NOT NULL DEFAULT '' COMMENT '医院地址',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`hid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='医院表';

-- ----------------------------
-- Records of YL_hospital
-- ----------------------------
INSERT INTO `YL_hospital` VALUES ('1', '北京武警总队医院', '长安街5号', '/upload/img/test.sql', '1470361991', '1470361991', '0');
INSERT INTO `YL_hospital` VALUES ('2', '广东协和医院', '东莞长安街8号', '/upload/img/test.sql', '1470361991', '1470361991', '0');
INSERT INTO `YL_hospital` VALUES ('3', '深圳人民医院', '南山区XX街5号', '/upload/img/test.sql', '1470361991', '1470361991', '0');

-- ----------------------------
-- Table structure for YL_news
-- ----------------------------
DROP TABLE IF EXISTS `YL_news`;
CREATE TABLE `YL_news` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(15000) NOT NULL DEFAULT '' COMMENT '正文内容',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '作者',
  `thumbnail` varchar(80) NOT NULL DEFAULT '' COMMENT '缩略图',
  `banner` varchar(80) NOT NULL DEFAULT '' COMMENT 'BANNER图',
  `tag` varchar(30) NOT NULL DEFAULT '' COMMENT '标签',
  `postPos` tinyint(3) NOT NULL DEFAULT '0' COMMENT '发布位置0：全部，1：用户端，2：医生端',
  `isRecmd` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐0：否，1：是',
  `isRecmdIndex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐到首页0：否，1：是',
  `updateTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0未发布，1发布',
  PRIMARY KEY (`nid`),
  KEY `state` (`state`) USING BTREE,
  KEY `postPos` (`postPos`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='资讯表';

-- ----------------------------
-- Records of YL_news
-- ----------------------------
INSERT INTO `YL_news` VALUES ('1', '2', '香港可接种9价HPV疫苗', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '疫苗', '1', '1', '1', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('2', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '1', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('3', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '2', '1', '1', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('4', '2', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '2', '1', '1', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('25', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('26', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('27', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('28', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('29', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('30', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('31', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('32', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('33', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('34', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('35', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('36', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('37', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('38', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('39', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('40', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('41', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('42', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('43', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('44', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('45', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('46', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('47', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('48', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('49', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('50', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');
INSERT INTO `YL_news` VALUES ('51', '1', '香港xxxx基因资讯', '据美国食品药品监督管理局（FDA）网站消息，美国默克公司（北美以外地区叫默沙东公司）的九价HPV疫苗“佳达修9”（九价重组人乳头瘤病毒疫苗）已通过FDA核准，产品上市在即。\r\n \r\n“佳达修9”是“佳达修4”的升级产品。与预防6、11、16、18等四个HPV病毒亚型的“佳达修4”相比，“佳达修9”增加了31、33、45、52、58五种病毒亚型，预防HPV的病毒亚型多达9个，可有效预防宫颈癌、外阴癌、阴道癌和肛门癌，以及生殖器疣等疾病。', 'ben', 'thumb/test1.jpg', 'banner1,jpg', '基因', '1', '1', '0', '1470368111', '1470368111', '0');

-- ----------------------------
-- Table structure for YL_news_category
-- ----------------------------
DROP TABLE IF EXISTS `YL_news_category`;
CREATE TABLE `YL_news_category` (
  `cid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父类id',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0正常, N预留',
  PRIMARY KEY (`cid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='资讯分类表';

-- ----------------------------
-- Records of YL_news_category
-- ----------------------------
INSERT INTO `YL_news_category` VALUES ('1', '基因', '0', '0');
INSERT INTO `YL_news_category` VALUES ('2', '疫苗', '0', '0');

-- ----------------------------
-- Table structure for YL_news_comment
-- ----------------------------
DROP TABLE IF EXISTS `YL_news_comment`;
CREATE TABLE `YL_news_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论人id',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `content` varchar(125) NOT NULL DEFAULT '' COMMENT '评论内容',
  `dateline` int(11) NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '评论状态0：未审核，1：通过，2：不通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯分类表';

-- ----------------------------
-- Records of YL_news_comment
-- ----------------------------

-- ----------------------------
-- Table structure for YL_order
-- ----------------------------
DROP TABLE IF EXISTS `YL_order`;
CREATE TABLE `YL_order` (
  `oid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyerId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买人id',
  `buyerName` varchar(25) NOT NULL DEFAULT '' COMMENT '购买人名称',
  `buyerSex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '购买人性别1男 2女',
  `buyerTel` varchar(20) NOT NULL DEFAULT '' COMMENT '购买人电话',
  `buyerBrithday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买人出生日期',
  `packageId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买套餐id',
  `packageTitle` varchar(255) NOT NULL DEFAULT '' COMMENT '购买套餐名称',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '套餐价格',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型：1疫苗接种，2基因检测',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：1.状态有待处理，2已支付，3未支付，4已通知，5完成',
  PRIMARY KEY (`oid`),
  KEY `status` (`status`) USING BTREE,
  KEY `packageId` (`packageId`) USING BTREE,
  KEY `buyerId` (`buyerId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of YL_order
-- ----------------------------

-- ----------------------------
-- Table structure for YL_post
-- ----------------------------
DROP TABLE IF EXISTS `YL_post`;
CREATE TABLE `YL_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发帖人id',
  `postickname` varchar(50) NOT NULL DEFAULT '' COMMENT '发帖人昵称',
  `postTitle` varchar(25) NOT NULL DEFAULT '' COMMENT '帖子标题',
  `postContent` varchar(300) NOT NULL DEFAULT '' COMMENT '内容',
  `postTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `isAnonymous` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否匿名发表0否,1是',
  `clickLike` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postUid` (`postUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈帖子表';

-- ----------------------------
-- Records of YL_post
-- ----------------------------

-- ----------------------------
-- Table structure for YL_post_comment
-- ----------------------------
DROP TABLE IF EXISTS `YL_post_comment`;
CREATE TABLE `YL_post_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子id',
  `recmdUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户uid',
  `recmdNickname` varchar(50) NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `recmdContent` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
  `recmdTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postId` (`postId`) USING BTREE,
  KEY `recmdUid` (`recmdUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈（帖子）评论表';

-- ----------------------------
-- Records of YL_post_comment
-- ----------------------------

-- ----------------------------
-- Table structure for YL_role_privileges
-- ----------------------------
DROP TABLE IF EXISTS `YL_role_privileges`;
CREATE TABLE `YL_role_privileges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of YL_role_privileges
-- ----------------------------

-- ----------------------------
-- Table structure for YL_take_cash
-- ----------------------------
DROP TABLE IF EXISTS `YL_take_cash`;
CREATE TABLE `YL_take_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '提现人id',
  `name` varchar(25) NOT NULL DEFAULT '' COMMENT '提现人昵称',
  `bank` varchar(125) NOT NULL DEFAULT '' COMMENT '开户银行',
  `address` varchar(125) NOT NULL DEFAULT '' COMMENT '开户地区',
  `cardNum` varchar(50) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `realName` varchar(18) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `identity` varchar(30) NOT NULL DEFAULT '' COMMENT '身份证',
  `amount` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `userType` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型1用户端，2医生端',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '提现时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0.待处理，1已确认，2驳回',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `userType` (`userType`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现表';

-- ----------------------------
-- Records of YL_take_cash
-- ----------------------------

-- ----------------------------
-- Table structure for YL_trade_log
-- ----------------------------
DROP TABLE IF EXISTS `YL_trade_log`;
CREATE TABLE `YL_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '反馈用户名',
  `userType` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型1用户，2医生',
  `tradeVolume` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '交易额',
  `tradeDesc` varchar(30) NOT NULL DEFAULT '' COMMENT '交易描述',
  `tradeChannel` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '交易渠道,0：本系统，1:支付宝，2：微信',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '交易时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易记录表';

-- ----------------------------
-- Records of YL_trade_log
-- ----------------------------

-- ----------------------------
-- Table structure for YL_user
-- ----------------------------
DROP TABLE IF EXISTS `YL_user`;
CREATE TABLE `YL_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `userType` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型（1：用户，2：医生）',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `regIp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册IP',
  `lastLoginTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `lastLoginIp` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登录ip',
  `email` varchar(150) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `birthday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '出生日期',
  `avatar` varchar(50) NOT NULL DEFAULT '' COMMENT '头像',
  `isBlack` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否黑名单:0否,1是',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户状态：0正常，N保留',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `uname` (`nickname`) USING BTREE,
  KEY `mobile` (`phone`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of YL_user
-- ----------------------------
INSERT INTO `YL_user` VALUES ('1', '张三', '641d3ac5e969efd385fdbfe1c134bb75', '1', '1470359890', '3232251298', '1470548940', '3232235794', 'momo1a@qq.com', '15977675495', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('3', '李四', 'c78b6663d47cfbdb4d65ea51c104044e', '1', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675496', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('4', '王五', 'c78b6663d47cfbdb4d65ea51c104044e', '1', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675497', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('5', '赵六', 'c78b6663d47cfbdb4d65ea51c104044e', '1', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675498', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('6', '赵医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675499', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('7', '李医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675410', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('8', '钱医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675411', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('9', '孙医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675412', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('10', '鲁医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675413', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('11', '黄医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675414', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('12', '梁医生', 'c78b6663d47cfbdb4d65ea51c104044e', '2', '1470359890', '3232251298', '1470359944', '3232251298', 'momo1a@qq.com', '15977675415', '1470359944', '/upoad/a/test.jpg', '0', '0');
INSERT INTO `YL_user` VALUES ('13', '', '7c20ba8ef9eb3dff71201c1fb1f5c29c', '1', '1470546934', '3232235794', '0', '0', '', '13707818185', '0', '', '0', '0');

-- ----------------------------
-- Table structure for YL_user_doctor_log
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_doctor_log`;
CREATE TABLE `YL_user_doctor_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `doctorId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '医生id',
  `comType` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '问诊类型1,留言问诊，2电话问诊，3预约挂号',
  `comState` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '问诊状态值 1,4,6 对应三个类型的',
  `description` varchar(30) NOT NULL DEFAULT '' COMMENT '日志描述',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `doctorId` (`doctorId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户-医生-日志表';

-- ----------------------------
-- Records of YL_user_doctor_log
-- ----------------------------
INSERT INTO `YL_user_doctor_log` VALUES ('1', '1', '6', '1', '1', 'x医生回答了x用户的问题', '1470385635', '0');
INSERT INTO `YL_user_doctor_log` VALUES ('2', '3', '7', '2', '4', 'x医生根x用户电话问诊完成', '1470385633', '0');
INSERT INTO `YL_user_doctor_log` VALUES ('3', '4', '10', '2', '3', 'x医生根x用户电话问诊x状态', '1470385632', '0');
INSERT INTO `YL_user_doctor_log` VALUES ('4', '5', '9', '3', '6', 'x医生根x用户预约挂号完成', '1470385631', '0');

-- ----------------------------
-- Table structure for YL_user_illness_history
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_illness_history`;
CREATE TABLE `YL_user_illness_history` (
  `illId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(30) NOT NULL DEFAULT '0' COMMENT '用户名',
  `illName` varchar(30) NOT NULL DEFAULT '' COMMENT '病历名称',
  `realname` varchar(30) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `age` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别1男 2女',
  `allergyHistory` varchar(50) NOT NULL DEFAULT '' COMMENT '过敏史',
  `result` varchar(25) NOT NULL DEFAULT '' COMMENT '诊断结果',
  `stages` tinyint(3) NOT NULL DEFAULT '0' COMMENT '分期',
  `situation` varchar(1000) NOT NULL DEFAULT '' COMMENT '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`illId`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户病历表';

-- ----------------------------
-- Records of YL_user_illness_history
-- ----------------------------
INSERT INTO `YL_user_illness_history` VALUES ('1', '1', '张三', '我的病历五', '张翠山', '52', '1', '无', '气管炎', '5', '房间打开接口房价肯定是减肥', '0');
INSERT INTO `YL_user_illness_history` VALUES ('2', '1', '张三', '我的病历二', '李白', '25', '1', '无', '盆腔炎', '3', '撒犯得上发生的看法酒店开了房艰苦拉萨解放', '0');
INSERT INTO `YL_user_illness_history` VALUES ('3', '1', '张三', '我的病历三', '李清照', '32', '2', '青霉素过敏', '脑血栓', '3', '房间看电视jfk拉萨jfk理解啊绿色健康', '0');

-- ----------------------------
-- Table structure for YL_user_illness_history_remarks
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_illness_history_remarks`;
CREATE TABLE `YL_user_illness_history_remarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `illId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '病历id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `visitDate` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '就诊日期',
  `stage` varchar(10) NOT NULL DEFAULT '' COMMENT '分期：初诊。。。',
  `content` varchar(600) NOT NULL DEFAULT '' COMMENT '病情记录',
  `img` varchar(300) NOT NULL DEFAULT '' COMMENT '图片',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`),
  KEY `illId` (`illId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户病历记录表';

-- ----------------------------
-- Records of YL_user_illness_history_remarks
-- ----------------------------
INSERT INTO `YL_user_illness_history_remarks` VALUES ('1', '1', '1', '1438444800', '初诊', '纷纷扰扰', '{\"0\":\"illRemark\\/2016\\/08\\/08\\/2152418315.jpg\",\"2\":\"illRemark\\/2016\\/08\\/08\\/2152419167.jpg\"}', '0');
INSERT INTO `YL_user_illness_history_remarks` VALUES ('2', '1', '1', '1438531200', '复诊', '并无大碍', '{\"0\":\"illRemark\\/2016\\/08\\/08\\/2152417219.jpg\",\"2\":\"illRemark\\/2016\\/08\\/08\\/2152417983.jpg\"}', '0');

-- ----------------------------
-- Table structure for YL_user_leaving_msg
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_leaving_msg`;
CREATE TABLE `YL_user_leaving_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askerUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊人uid',
  `askerNickname` varchar(50) NOT NULL DEFAULT '' COMMENT '问诊人昵称',
  `askerPone` varchar(20) NOT NULL DEFAULT '' COMMENT '问诊人电话',
  `askerContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价钱',
  `docId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '指定医生id',
  `docName` varchar(20) NOT NULL DEFAULT '' COMMENT '医生名称',
  `img` varchar(300) NOT NULL DEFAULT '' COMMENT '图片',
  `askTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0通过，1未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户留言表';

-- ----------------------------
-- Records of YL_user_leaving_msg
-- ----------------------------

-- ----------------------------
-- Table structure for YL_user_phone_diagnosis
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_phone_diagnosis`;
CREATE TABLE `YL_user_phone_diagnosis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊人uid',
  `illnessId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '病历id',
  `askNickname` varchar(50) NOT NULL DEFAULT '' COMMENT '问诊人昵称',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '年龄',
  `askTelephone` varchar(20) NOT NULL DEFAULT '' COMMENT '问诊人电话',
  `ask_sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '问诊人性别1男 2女',
  `askContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容简述',
  `otherIllness` varchar(200) NOT NULL DEFAULT '' COMMENT '其他病史内容',
  `phoneTimeLen` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '通话时长',
  `hopeCalldate` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '期望通话的日期',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '价钱',
  `docId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '医生id',
  `docName` varchar(20) NOT NULL DEFAULT '' COMMENT '医生名称',
  `docTelephone` varchar(20) NOT NULL DEFAULT '' COMMENT '医生电话',
  `askTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待处理，1已确认沟通时间，2已支付，3未支付，4完成，5失败',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `askUid` (`askUid`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户电话问诊表';

-- ----------------------------
-- Records of YL_user_phone_diagnosis
-- ----------------------------

-- ----------------------------
-- Table structure for YL_user_reg_num
-- ----------------------------
DROP TABLE IF EXISTS `YL_user_reg_num`;
CREATE TABLE `YL_user_reg_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '挂号用户id',
  `userName` varchar(20) NOT NULL DEFAULT '' COMMENT '挂号用户名',
  `docId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '指定医生id',
  `docName` varchar(30) NOT NULL DEFAULT '' COMMENT '指定医生的昵称',
  `docTel` varchar(20) NOT NULL DEFAULT '' COMMENT '医生电话',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价钱',
  `hosAddr` varchar(255) NOT NULL DEFAULT '' COMMENT '医院地址',
  `contacts` varchar(60) NOT NULL DEFAULT '' COMMENT '联系人',
  `appointTime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预约时间',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别:1男；2女',
  `appointBrithday` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '预约人的生日',
  `appointTel` varchar(25) NOT NULL DEFAULT '' COMMENT '预约人的电话',
  `illnessId` int(11) NOT NULL DEFAULT '0' COMMENT '病历id',
  `userRemark` varchar(200) NOT NULL DEFAULT '' COMMENT '用户备注',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态 1.状态有待处理，2预约成功，3预约失败，4已支付，5未支付，6完成',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户挂号表';

-- ----------------------------
-- Records of YL_user_reg_num
-- ----------------------------

-- ----------------------------
-- Table structure for YL_vaccinum
-- ----------------------------
DROP TABLE IF EXISTS `YL_vaccinum`;
CREATE TABLE `YL_vaccinum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '类型,1儿童类型，2成人类型',
  `name` varchar(125) NOT NULL DEFAULT '' COMMENT '套餐名称',
  `detail` varchar(10000) NOT NULL DEFAULT '' COMMENT '套餐详情',
  `dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价钱',
  `thumbnail` varchar(125) NOT NULL DEFAULT '' COMMENT '缩略图',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：1上架，2下架',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='疫苗接种表';

-- ----------------------------
-- Records of YL_vaccinum
-- ----------------------------
