<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_card`;");
E_C("CREATE TABLE `xiaozu_card` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `card` varchar(50) NOT NULL,
  `card_password` varchar(255) NOT NULL,
  `uid` int(20) NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `cost` int(4) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `creattime` int(12) NOT NULL DEFAULT '0',
  `usetime` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>