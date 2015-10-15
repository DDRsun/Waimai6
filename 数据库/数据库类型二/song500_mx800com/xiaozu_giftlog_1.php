<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_giftlog`;");
E_C("CREATE TABLE `xiaozu_giftlog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `giftid` int(20) NOT NULL,
  `uid` int(20) NOT NULL,
  `score` int(6) NOT NULL,
  `addtime` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `address` varchar(150) DEFAULT NULL,
  `telphone` varchar(15) DEFAULT NULL,
  `contactman` varchar(150) DEFAULT NULL,
  `count` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>