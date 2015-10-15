<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shopfast`;");
E_C("CREATE TABLE `xiaozu_shopfast` (
  `shopid` int(20) NOT NULL,
  `is_orderbefore` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不支持提前预定，1支持',
  `delaytime` int(5) NOT NULL DEFAULT '0' COMMENT '营业时间和下单时间补差',
  `limitcost` int(3) NOT NULL DEFAULT '0' COMMENT '起送价格',
  `limitstro` varchar(255) DEFAULT NULL COMMENT '起送说明',
  `befortime` int(1) NOT NULL DEFAULT '0' COMMENT '起送提前天数',
  `personcost` int(5) NOT NULL DEFAULT '0' COMMENT '人均消费价格',
  `sendtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0网站配送，1自行配送',
  `is_hot` int(1) NOT NULL DEFAULT '0' COMMENT '热卖',
  `is_com` int(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `is_new` int(1) NOT NULL COMMENT '新店',
  `sendset` text,
  `maketime` int(5) DEFAULT '0',
  `psvalue3` int(1) NOT NULL DEFAULT '1',
  `pradius` int(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `shopid` (`shopid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_shopfast` values('1','1','0','0','','1','0','1','0','0','0','a:4:{s:6:\"pstype\";i:1;s:8:\"psvalue1\";i:0;s:8:\"psvalue2\";i:0;s:8:\"psvalue3\";i:0;}','0','1','1');");
E_D("replace into `xiaozu_shopfast` values('2','1','0','12','','22','12','0','0','0','0','a:4:{s:6:\"pstype\";i:0;s:8:\"psvalue1\";i:0;s:8:\"psvalue2\";i:0;s:8:\"psvalue3\";i:0;}','2','1','1');");

require("../../inc/footer.php");
?>