<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shop`;");
E_C("CREATE TABLE `xiaozu_shop` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NOT NULL COMMENT '用户ID',
  `shortname` varchar(10) DEFAULT NULL COMMENT '店铺短地址',
  `shopname` varchar(150) NOT NULL COMMENT '店铺名称',
  `shoplogo` varchar(150) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL COMMENT '联系电话',
  `address` varchar(150) DEFAULT NULL COMMENT '联系地址',
  `point` int(10) NOT NULL DEFAULT '5' COMMENT '评分',
  `cx_info` text COMMENT '促销信息',
  `intr_info` text COMMENT '介绍信息',
  `notice_info` text COMMENT '公告信息',
  `starttime` varchar(100) DEFAULT NULL COMMENT '营业时间',
  `is_open` int(1) NOT NULL DEFAULT '0' COMMENT '是否营业',
  `is_pass` int(1) NOT NULL DEFAULT '0' COMMENT '是否通过审核',
  `is_recom` int(1) NOT NULL DEFAULT '0' COMMENT '是否是推荐店铺',
  `maphone` varchar(12) DEFAULT NULL,
  `addtime` int(12) DEFAULT NULL,
  `pointcount` int(5) NOT NULL DEFAULT '0',
  `yjin` int(3) NOT NULL DEFAULT '0' COMMENT '佣金比例当为0时调用网站设置',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '地图左坐标',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000' COMMENT '地图右坐标',
  `shopbacklogo` varchar(255) DEFAULT NULL COMMENT '商家默认背景图片',
  `sort` int(20) NOT NULL DEFAULT '999' COMMENT '排序',
  `email` varchar(50) DEFAULT NULL,
  `areaid1` int(20) NOT NULL DEFAULT '0',
  `areaid2` int(20) NOT NULL DEFAULT '0',
  `areaid3` int(20) NOT NULL DEFAULT '0',
  `otherlink` varchar(255) DEFAULT NULL COMMENT '其他链接地址',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `IMEI` varchar(255) DEFAULT NULL,
  `shoptype` int(2) NOT NULL DEFAULT '0',
  `noticetype` varchar(100) DEFAULT NULL,
  `machine_code` varchar(255) DEFAULT NULL COMMENT '打印2机器码',
  `mKey` varchar(255) DEFAULT NULL COMMENT '打印2打印密匙',
  `pradiusa` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_shop` values('1','1','guanghe','外卖人旗舰店',NULL,'18538129330','郑州光合科技有限公司','5',NULL,NULL,NULL,'08:00-23:59','1','1','0','18538129330','1411405010','0','0','0.000000','0.000000',NULL,'999','waimairen@qq.com','0','0','0','','1442941010','','0',NULL,NULL,NULL,'1');");
E_D("replace into `xiaozu_shop` values('2','2','顶级源码论坛的商店','顶级源码论坛的商店','/upload/user/20140925190525595.jpg','15625452845','顶级源码论坛的商店','5',NULL,'<dl class=\"xld cl bbda\"> <dt><a class=\"xi2\" href=\"http://www.mx800.com/forum.php\" target=\"_blank\">顶级源码，拒绝抄袭雷同！</a></dt> <dd>你好会员，我们站主要提供顶级类的实用性站点门户源码，房产类，汽车类，装饰类，婚庆类，旅游类，吃喝类，我们的资源绝不类同于其他VIP论坛的源码，并对每个源码做持续版本的更新服务，凡是VIP都提供完善的技术支持和升级服务！<a href=\"http://www.mx800.com\">www.mx800.com</a></dd></dl>','<dl class=\"xld cl bbda\"> <dt><a class=\"xi2\" href=\"http://www.mx800.com/forum.php\" target=\"_blank\">顶级源码，拒绝抄袭雷同！</a></dt> <dd>你好会员，我们站主要提供顶级类的实用性站点门户源码，房产类，汽车类，装饰类，婚庆类，旅游类，吃喝类，我们的资源绝不类同于其他VIP论坛的源码，并对每个源码做持续版本的更新服务，凡是VIP都提供完善的技术支持和升级服务！<a href=\"http://www.mx800.com/\">www.mx800.com</a></dd></dl>','08:01-00:00|08:08-00:00','1','1','0','15656565452','1411642972','0','0','0.000000','0.000000',NULL,'999','youxiang@126.com','0','0','0','','0','','0',NULL,NULL,NULL,'1');");

require("../../inc/footer.php");
?>