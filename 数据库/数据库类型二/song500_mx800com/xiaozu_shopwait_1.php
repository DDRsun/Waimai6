<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shopwait`;");
E_C("CREATE TABLE `xiaozu_shopwait` (
  `shopid` int(20) NOT NULL,
  `personcost` decimal(5,0) NOT NULL DEFAULT '0' COMMENT '人均消费',
  `befortime` int(2) NOT NULL DEFAULT '14' COMMENT '提前预定天数',
  `maxperson` int(3) NOT NULL DEFAULT '10' COMMENT '最大消费人数',
  `position` text COMMENT '使用,分割',
  `is_hot` int(1) NOT NULL DEFAULT '0' COMMENT '热卖',
  `is_com` int(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `is_new` int(1) NOT NULL COMMENT '新店',
  `timesplit` int(11) NOT NULL DEFAULT '30' COMMENT '时间间隔',
  `limitcost` int(5) DEFAULT '0' COMMENT '起订价',
  UNIQUE KEY `shopid` (`shopid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>