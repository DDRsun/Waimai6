<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_order`;");
E_C("CREATE TABLE `xiaozu_order` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `dno` varchar(100) NOT NULL COMMENT '订单编号',
  `shopuid` int(20) NOT NULL COMMENT '店铺UID',
  `shopid` int(20) NOT NULL COMMENT '店铺ID',
  `shopname` varchar(100) NOT NULL COMMENT '店铺名称',
  `shopphone` varchar(20) NOT NULL COMMENT '店铺电话',
  `shopaddress` varchar(150) NOT NULL COMMENT '店铺地址',
  `buyeruid` int(20) NOT NULL COMMENT '购买用户ID，0未注册用户',
  `buyername` varchar(100) NOT NULL COMMENT '购买热名称',
  `buyeraddress` varchar(150) NOT NULL COMMENT '联系地址',
  `buyerphone` varchar(20) NOT NULL COMMENT '联系电话',
  `status` int(1) NOT NULL COMMENT '状态',
  `paytype` varchar(20) NOT NULL COMMENT '支付类型outpay货到支付，open_acout账户余额支付，other调用paylist',
  `paystatus` int(1) NOT NULL COMMENT '支付状态1已支付',
  `content` varchar(255) NOT NULL COMMENT '订单备注',
  `ordertype` int(1) NOT NULL DEFAULT '0' COMMENT '订餐方式1网站，2电话，3微信，4App',
  `daycode` int(10) NOT NULL DEFAULT '0' COMMENT '当天订单序号',
  `area1` varchar(255) DEFAULT NULL COMMENT '二级地址名称',
  `area2` varchar(255) DEFAULT NULL COMMENT '三级地址名称',
  `area3` varchar(255) DEFAULT NULL,
  `cxids` varchar(100) DEFAULT NULL COMMENT '促销规则ID集',
  `cxcost` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '店铺促销优惠金额',
  `yhjcost` int(5) NOT NULL DEFAULT '0' COMMENT '优惠劵优惠金额',
  `yhjids` varchar(255) DEFAULT NULL COMMENT '使用优惠劵ID集',
  `ipaddress` varchar(255) DEFAULT NULL,
  `scoredown` int(5) NOT NULL DEFAULT '0' COMMENT '积分抵扣数',
  `is_ping` int(11) NOT NULL DEFAULT '0' COMMENT '是否评价字段 1已评完 0未评',
  `isbefore` int(1) NOT NULL DEFAULT '0' COMMENT '0:普通订单，1订台订单',
  `marketcost` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '超市商品总价',
  `marketps` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '超市配送配送',
  `shopcost` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '店铺商品总价',
  `shopps` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '店铺配送费',
  `pstype` int(1) NOT NULL DEFAULT '0' COMMENT '配送方式 0：平台1：个人',
  `bagcost` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '打包费',
  `addtime` int(12) NOT NULL DEFAULT '0' COMMENT '制造时间',
  `posttime` int(12) NOT NULL DEFAULT '0' COMMENT '配送时间',
  `passtime` int(12) NOT NULL DEFAULT '0' COMMENT '审核时间',
  `sendtime` int(12) NOT NULL DEFAULT '0' COMMENT '发货时间',
  `suretime` int(12) NOT NULL DEFAULT '0' COMMENT '完成时间',
  `allcost` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '订单实收费',
  `buycode` varchar(50) DEFAULT NULL COMMENT '订台码',
  `othertext` text COMMENT '其他说明',
  `is_print` int(1) NOT NULL DEFAULT '0',
  `wxstatus` int(1) NOT NULL DEFAULT '0' COMMENT '1商家确认，2商家取消',
  `shoptype` int(1) DEFAULT '0',
  `is_make` int(1) NOT NULL DEFAULT '0',
  `is_reback` smallint(1) DEFAULT '0',
  `areaids` char(255) DEFAULT NULL,
  `psuid` int(20) DEFAULT NULL,
  `psusername` varchar(100) DEFAULT NULL,
  `psemail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>