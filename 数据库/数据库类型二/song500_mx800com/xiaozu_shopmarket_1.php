<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shopmarket`;");
E_C("CREATE TABLE `xiaozu_shopmarket` (
  `shopid` int(20) NOT NULL,
  `is_orderbefore` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不支持提前预定，1支持',
  `delaytime` int(5) NOT NULL DEFAULT '0' COMMENT '营业时间和下单时间补差',
  `limitcost` int(3) NOT NULL DEFAULT '0' COMMENT '起送价格',
  `limitstro` varchar(255) DEFAULT NULL COMMENT '起送说明',
  `befortime` int(1) NOT NULL DEFAULT '0' COMMENT '起送提前天数',
  `sendtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0网站配送，1自行配送',
  `is_hot` int(1) NOT NULL DEFAULT '0' COMMENT '热卖',
  `is_com` int(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `is_new` int(1) NOT NULL COMMENT '新店',
  `sendset` text COMMENT '配送设置序列化数组',
  `maketime` int(5) DEFAULT '0',
  `pradius` int(1) NOT NULL DEFAULT '1',
  `pradiusvalue` text,
  UNIQUE KEY `shopid` (`shopid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>