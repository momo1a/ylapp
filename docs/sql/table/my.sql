# 后台账户表
DROP TABLE IF EXISTS  `YL_background_account`;
CREATE TABLE `YL_background_account` (
  `id` int(6) unsigned AUTO_INCREMENT
  `name` varchar(20)  not null default '' comment '姓名',
  `telephone` varchar(20) not null default '' comment '手机号码',
  `pwd` varchar(80) not null default '' comment '密码'
  `degree` tinyint(3) unsigned not null default '0' comment '身份1管理员，2客服，N预留',
  `privileges` varchar(250) not null DEFAULT '' comment '权限',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台账户表';# banner图片表
DROP TABLE IF EXISTS  `YL_banner`;
CREATE TABLE `YL_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '类型1：用户端，2：医生端',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='banner图片表';# 医生费用设置表
DROP TABLE IF EXISTS  `YL_doctor_fee_seting`;
CREATE TABLE `YL_doctor_fee_seting` (
  `docId` int(11) unsigned NOT NULL comment '医生id',
  `docNicname` varchar(50) not null default '' comment '医生昵称',
  `leavMsgFee` DECIMAL(9,2) unsigned not null default '0' comment '留言费用',
  `leavMsgPer` DECIMAL(5,2) unsigned not null default '0' comment '留言费用平台分成',
  `regNumFee` DECIMAL(9,2) unsigned not null default '0' comment '挂号费用',
  `regNumPer` DECIMAL(5,2) unsigned not null default '0' comment '挂号费用平台分成',
  `phoneTimeLenFrist` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长一 单位：分',
  `phoneFeeFrist` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊一费用',
  `phonePerFrist` DECIMAL(5,2) unsigned not null default '0' comment '电话问诊一平台分成',
  `phoneTimeLenSecond` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长二 单位：分',
  `phoneFeeSecond` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊二费用',
  `phonePerSecond` DECIMAL(5,2) unsigned not null default '0' comment '电话问诊二平台分成',
  `phoneTimeLenThird` SMALLINT(5)  unsigned not null default '0' comment '电话问诊时长三 单位：分',
  `phoneFeeThird` DECIMAL(9,2) unsigned not null default '0' comment '电话问诊三费用',
  `dateline` int(11) unsigned not null default '0' comment '时间线',
  PRIMARY KEY (`docId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生费用设置表';# 医生信息表
DROP TABLE IF EXISTS  `YL_doctor_info`;
CREATE TABLE `YL_doctor_info` (
  `uid` int(11) unsigned NOT NULL,
  `hid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属医院id',
  `offices` VARCHAR(25) NOT NULL DEFAULT '' COMMENT '科室',
  `degree` varchar(30) not null default '' comment '学位',
  `sex` tinyint(3) unsigned not null default '0' comment '性别',
  `phoneSec` varchar(15) NOT NULL DEFAULT '' COMMENT '电话二',
  `summary` VARCHAR(1000) not null default '' comment '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态:0未通过,1通过',
  PRIMARY KEY (`uid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生信息表';# 医生电话问诊备注表
DROP TABLE IF EXISTS  `YL_doctor_phone_diagnosis_remarks`;
CREATE TABLE `YL_doctor_phone_diagnosis_remarks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned not null default '0' comment '类型1:电话问诊，2：挂号',
  `DiagId` int(11) unsigned not null default '0' comment '问诊记录id',
  `remarkContent` varchar(300)  not null default '' comment '医生备注',
  `img` varchar(500) not null default '' comment '图片',
  `remarkTime` int(11) unsigned not null default '0' comment '备注时间',
  PRIMARY KEY (`id`),
  KEY `DiagId` (`DiagId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生电话问诊备注表';# 医生回复表

DROP TABLE IF EXISTS  `YL_doctor_reply`;
CREATE TABLE `YL_doctor_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `themeId` int(11) unsigned not null default '0' comment '主题id',
  `type` tinyint(3) unsigned not null default '0' comment '主题类型1：留言,n预留',
  `replyContent` VARCHAR(500) not null default '' comment '回复内容',
  `replyId` int(11) not null default '0' comment '回复者id',
  `replyNicname` varchar(50) not null default '' comment '回复者昵称',
  `replyTime` int(11) unsigned not null default '0' comment '回复时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待处理，1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `themeId` (`themeId`) USING BTREE,
  KEY `replyTime` (`state`) USING BTREE,
  KEY `replyId` (`replyId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生回复表';# 反馈表
DROP TABLE IF EXISTS  `YL_feedback`;
CREATE TABLE `YL_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '反馈用户id',
  `username` varchar(30) not null default '' comment '反馈用户名',
  `userType` tinyint(3) unsigned  not null default  '0' comment '反馈用户类型1用户，2医生',
  `dateline` int(11) unsigned not null default '0' comment '反馈时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈表';# 基因检测表
DROP TABLE IF EXISTS  `YL_gene_check`;
CREATE TABLE `YL_gene_check` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(125) not null default '' comment '套餐名称',
  `detail` varchar(10000) not null default '' comment '套餐详情',
  `dateline` int(11) unsigned not null default '0' comment '发布时间',
  `price` DECIMAL(9,2) unsigned not null default '0' comment '价钱',
  `thumbnail` varchar(125)  not null default '' comment '缩略图',
  `status` tinyint(3) unsigned not null default '0' comment '状态：1上架，2下架',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基因检测表';# 医院表
DROP TABLE IF EXISTS  `YL_hospital`;
CREATE TABLE `YL_hospital` (
  `hid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '医院名称',
  `address` varchar(60) NOT NULL DEFAULT '' COMMENT '医院地址',
  `img` varchar(80) NOT NULL DEFAULT '' COMMENT '图片',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '新建时间',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0正常，N预留',
  PRIMARY KEY (`hid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医院表';#资讯分类表
DROP TABLE IF EXISTS  `YL_news_category`;
CREATE TABLE `YL_news_category` (
  `cid` SMALLINT(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` SMALLINT(5) unsigned NOT NULL DEFAULT '0' COMMENT '父类id',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态0正常, N预留',
  PRIMARY KEY (`cid`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯分类表';#资讯评论表
DROP TABLE IF EXISTS  `YL_news_comment`;
CREATE TABLE `YL_news_comment` (
  `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '资讯id',
  `uid` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论人id',
  `nickname` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `content` VARCHAR(125) NOT NULL DEFAULT '' COMMENT '评论内容',
  `dateline` INT(11) NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) NOT NULL DEFAULT '0' COMMENT '评论状态0：未审核，1：通过，2：不通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯分类表';# 资讯表
DROP TABLE IF EXISTS  `YL_news`;
CREATE TABLE `YL_news` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` SMALLINT(5) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(15000) NOT NULL DEFAULT '' COMMENT '正文内容',
  `author` varchar(20) NOT NULL DEFAULT '' COMMENT '作者',
  `thumbnail`varchar(80) NOT NULL DEFAULT '' COMMENT '缩略图',
  `banner` VARCHAR(80) NOT NULL DEFAULT '' COMMENT 'BANNER图',
  `tag` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '标签',
  `postPos` tinyint(3) NOT NULL DEFAULT '0' COMMENT '发布位置0：全部，1：用户端，2：医生端',
  `isRecmd` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐0：否，1：是',
  `isRecmdIndex` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐到首页0：否，1：是',
  `updateTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `createTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0未发布，1发布',
  PRIMARY KEY (`nid`),
  KEY `state` (`state`) USING BTREE,
  KEY `postPos` (`postPos`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯表'; # 订单表
DROP TABLE IF EXISTS  `YL_order`;
CREATE TABLE `YL_order` (
  `oid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyerId` int(11) unsigned not null default '0' comment '购买人id',
  `buyerName` varchar(25) not null default '' comment '购买人名称',
  `buyerSex` tinyint(3) unsigned not null default '0' comment '购买人性别1男 2女',
  `buyerTel` varchar(20) not null default '' comment '购买人电话',
  `buyerBrithday` int(11) unsigned not null default '0' comment '购买人出生日期',
  `packageId` int(11) unsigned not null default '0' comment '购买套餐id',
  `packageTitle` varchar(255) not null default '' comment '购买套餐名称',
  `price` decimal(9,2) unsigned not null default '0' comment '套餐价格',
  `type` tinyint(3) unsigned not null default '0' comment '类型：1疫苗接种，2基因检测',
  `dateline` int(11) unsigned not null default '0' comment '下单时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态：1.状态有待处理，2已支付，3未支付，4已通知，5完成',
  PRIMARY KEY (`oid`),
  KEY `status` (`status`) USING BTREE,
  KEY `packageId` (`packageId`) USING BTREE,
  KEY `buyerId` (`buyerId`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';# 交流圈（帖子）评论表

DROP TABLE IF EXISTS  `YL_post_comment`;
CREATE TABLE `YL_post_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子id',
  `recmdUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户uid',
  `recmdNickname` VARCHAR(50)  NOT NULL DEFAULT '' COMMENT '评论人昵称',
  `recmdContent` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
  `recmdTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postId` (`postId`) USING BTREE,
  KEY `recmdUid` (`recmdUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈（帖子）评论表';# 交流圈帖子表
DROP TABLE IF EXISTS  `YL_post`;
CREATE TABLE `YL_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `postUid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发帖人id',
  `postickname` VARCHAR(50)  NOT NULL DEFAULT '' COMMENT '发帖人昵称',
  `postTitle` varchar(25) not null default '' comment '帖子标题',
  `postContent` varchar(300) NOT NULL DEFAULT '' COMMENT '内容',
  `postTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `isAnonymous` tinyint(3) unsigned not null default '0' comment '是否匿名发表0否,1是',
  `clickLike` int(11) unsigned not null default '0' comment '点赞',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核：1通过，2未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `postUid` (`postUid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交流圈帖子表';# 权限表
DROP TABLE IF EXISTS  `YL_role_privileges`;
CREATE TABLE `YL_role_privileges` (
  `id` int(11) unsigned AUTO_INCREMENT
   ####
  PRIMARY KEY (`id`),
  KEY `illId` (`illId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表'; # 提现表
DROP TABLE IF EXISTS  `YL_take_cash`;
CREATE TABLE `YL_take_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '提现人id',
  `name` varchar(25) not null default '' comment '提现人昵称',
  `bank` varchar(125) not null default '' comment '开户银行',
  `address` varchar(125) not null default '' comment '开户地区',
  `cardNum` varchar(50) not null default '' comment '银行卡号',
  `realName` varchar(18) not null default '' comment '真实姓名',
  `identity` varchar(30) not null default '' comment '身份证',
  `amount` DECIMAL(9,2) unsigned not null default '0' comment '提现金额',
  `userType` tinyint(3) unsigned not null default '0' comment '用户类型1用户端，2医生端',
  `dateline` int(11) unsigned not null default '0' comment '提现时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态：0.待处理，1已确认，2驳回',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `userType` (`userType`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现表';#########################################################################
# File Name: tatal.sh
# Author: Moshiyou
# mail: momo1a@qq.com
#Created Time:Tue 02 Aug 2016 05:43:36 PM CST
#########################################################################
#!/bin/bash
TATAL=$(ls -l)
for i in `ls`
do
 cat $i >> my.sql
done
# 交易记录表
DROP TABLE IF EXISTS  `YL_trade_log`;
CREATE TABLE `YL_trade_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned not null default '0' comment '',
  `username` varchar(30) not null default '' comment '反馈用户名',
  `userType` tinyint(3) unsigned  not null default  '0' comment '用户类型1用户，2医生',
  `tradeVolume` DECIMAL(9,2) unsigned not null default '0' comment '交易额',
  `tradeDesc`  varchar(30) not null default '' comment '交易描述',
  `tradeChannel` tinyint(3) unsigned not null default '0' comment '交易渠道,0：本系统，1:支付宝，2：微信',
  `dateline` int(11) unsigned not null default '0' comment '交易时间',
  `status` tinyint(3) unsigned not null default '0' comment '状态预留',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易记录表';# 用户病历记录表
DROP TABLE IF EXISTS  `YL_user_illness_history_remarks`;
CREATE TABLE `YL_user_illness_history_remarks` (
  `id` int(11) unsigned AUTO_INCREMENT
  `illId` int(11) unsigned not null default '0' comment '病历id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' comment '用户id',
  `visitDate` int(11) unsigned not null default '0' comment '就诊日期',
  `stage` varchar(10) not null default '' comment '分期：初诊。。。',
  `content` varchar(600) not null default '' comment '病情记录',
  `img` varchar(300) not null default '' comment '图片',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`id`),
  KEY `illId` (`illId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户病历记录表';# 用户病历表
DROP TABLE IF EXISTS  `YL_user_illness_history`;
CREATE TABLE `YL_user_illness_history` (
  `illId` int(11) unsigned AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '' comment '用户id',
  `username` varchar(30) not null default '0' comment '用户名',
  `illName` varchar(30) not null default '' comment '病历名称',
  `sex` tinyint(3) unsigned not null default '' comment '性别1男 2女',
  `allergyHistory` varchar(50) not null default '' comment '过敏史',
  `result` varchar(25) not null default '' comment '诊断结果',
  `stages` tinyint(3) not null default '0' comment '分期',
  `situation` VARCHAR(1000) not null default '' comment '简介',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态:预留',
  PRIMARY KEY (`illId`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户病历表';# 用户留言表
DROP TABLE IF EXISTS  `YL_user_leaving_msg`;
CREATE TABLE `YL_user_leaving_msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askerUid` int(11) unsigned not null default '0' COMMENT '问诊人uid',
  `askerNickname` VARCHAR(50) not null default '' comment '问诊人昵称',
  `askerPone` VARCHAR(20) not null default '' comment '问诊人电话',
  `askerContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容',
  `price` DECIMAL(9,2) NOT NULL DEFAULT '0' COMMENT '价钱',
  `docId` int(11) unsigned not null default '0' COMMENT '指定医生id',
  `docName` varchar(20) not null default '' comment '医生名称',
  `img` VARCHAR(300) not null default '' comment '图片',
  `askTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0通过，1未通过',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户留言表';# 用户电话问诊表
DROP TABLE IF EXISTS  `YL_user_phone_diagnosis`;
CREATE TABLE `YL_user_phone_diagnosis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `askUid` int(11) unsigned not null default '0' COMMENT '问诊人uid',
  `illnessId` int(11) unsigned not null default '0' comment '病历id',
  `askNickname` VARCHAR(50) not null default '' comment '问诊人昵称',
  `age` tinyint(3) unsigned not null default '0' comment '年龄',
  `askTelephone` VARCHAR(20) not null default '' comment '问诊人电话',
  `ask_sex` tinyint(3) unsigned not null default '0' comment '问诊人性别1男 2女',
  `askContent` varchar(500) NOT NULL DEFAULT '' COMMENT '问诊内容简述',
  `otherIllness` VARCHAR(200) not null default '' comment '其他病史内容',
  `phoneTimeLen` SMALLINT(5) unsigned not null default '0' comment '通话时长',
  `hopeCalldate` int(11) unsigned not null default '0' comment '期望通话的日期',
  `price` DECIMAL(9,2) NOT NULL DEFAULT '0' COMMENT '价钱',
  `docId` int(11) unsigned not null default '0' COMMENT '医生id',
  `docName` varchar(20) not null default '' comment '医生名称',
  `docTelephone` varchar(20) not null default '' comment '医生电话',
  `askTime` INT(11) unsigned NOT NULL DEFAULT '0' COMMENT '问诊时间',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0待处理，1已确认沟通时间，2已支付，3未支付，4完成，5失败',
  PRIMARY KEY (`id`),
  KEY `state` (`state`) USING BTREE,
  KEY `askUid` (`askUid`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户电话问诊表';# 用户挂号表
DROP TABLE IF EXISTS  `YL_user_reg_num`;
CREATE TABLE `YL_user_reg_num` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned not null default '0' comment '挂号用户id',
  `userName` varchar(20)  not null default '' comment '挂号用户名',
  `docId` int(11) unsigned not null default '0' comment '指定医生id',
  `docName` varchar(30)  not null default '' comment '指定医生的昵称',
  `docTel` varchar(20)  not null default '' comment '医生电话',
  `price` DECIMAL(9,2) unsigned not null default '0' comment '价钱',
  `hosAddr` varchar(255) not null default '' comment '医院地址',
  `contacts` varchar(60) not null default '' comment '联系人',
  `appointTime` int(11) unsigned not null default '0' comment '预约时间',
  `sex` tinyint(3) unsigned not null default '0' comment '性别:1男；2女',
  `appointBrithday` int(11) unsigned not null default '0' comment '预约人的生日',
  `appointTel` varchar(25) not null default '' comment '预约人的电话',
  `illnessId` int(11) not null default '0' comment '病历id',
  `userRemark` varchar(200) not null default '' comment '用户备注',
  `dateline` int(11) unsigned not null default '0' comment '记录时间',
  `status` tinyint(3) not null default '0' comment '状态 1.状态有待处理，2预约成功，3预约失败，4已支付，5未支付，6完成',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  KEY `docId` (`docId`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户挂号表';# 用户表
DROP TABLE IF EXISTS  `YL_user`;
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
  `avatar` varchar(50) not null default '' comment '头像',
  `isBlack` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否黑名单:0否,1是',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户状态：0正常，N保留',
  PRIMARY KEY (`uid`),
  KEY `uname` (`nickname`) USING BTREE,
  KEY `mobile` (`phone`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

# 疫苗接种表
DROP TABLE IF EXISTS  `YL_vaccinum`;
CREATE TABLE `YL_vaccinum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned not null default '0' comment '类型,1儿童类型，2成人类型',
  `name` varchar(125) not null default '' comment '套餐名称',
  `detail` varchar(10000) not null default '' comment '套餐详情',
  `dateline` int(11) unsigned not null default '0' comment '发布时间',
  `price` DECIMAL(9,2) unsigned not null default '0' comment '价钱',
  `thumbnail` varchar(125)  not null default '' comment '缩略图',
  `status` tinyint(3) unsigned not null default '0' comment '状态：1上架，2下架',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='疫苗接种表';