<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_drawbacklog`;");
E_C("CREATE TABLE `xiaozu_drawbacklog` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `orderid` int(20) NOT NULL,
  `content` text NOT NULL,
  `phone` varchar(50) NOT NULL,
  `contactname` varchar(100) NOT NULL,
  `status` int(1) DEFAULT '0',
  `addtime` int(12) DEFAULT NULL,
  `cost` decimal(6,2) DEFAULT '0.00',
  `bkcontent` varchar(255) DEFAULT NULL COMMENT '回复说明',
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>