<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_onlinelog`;");
E_C("CREATE TABLE `xiaozu_onlinelog` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `upid` int(20) NOT NULL DEFAULT '0',
  `cost` decimal(6,2) NOT NULL DEFAULT '0.00',
  `status` int(1) NOT NULL DEFAULT '0',
  `addtime` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>