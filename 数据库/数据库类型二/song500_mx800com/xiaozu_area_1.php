<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_area`;");
E_C("CREATE TABLE `xiaozu_area` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `pin` varchar(5) DEFAULT NULL COMMENT '首字母拼音',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '上级区域ID',
  `orderid` int(20) DEFAULT NULL COMMENT '排序ID',
  `imgurl` varchar(255) DEFAULT NULL COMMENT '一级地址图片地址',
  `lng` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `lat` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `show` int(1) DEFAULT '0',
  `is_com` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_area` values('1','金水区','j','0','1','/upload/goods/20140923005016918.png','120.551893','31.868813','1','0');");
E_D("replace into `xiaozu_area` values('2','顶级源码论坛区','djyml','0','2','/upload/goods/20140925191038742.jpg','0.000000','0.000000','1','1');");

require("../../inc/footer.php");
?>