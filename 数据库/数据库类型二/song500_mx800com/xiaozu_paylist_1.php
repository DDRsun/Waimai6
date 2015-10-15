<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_paylist`;");
E_C("CREATE TABLE `xiaozu_paylist` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `loginname` varchar(10) NOT NULL,
  `logindesc` varchar(100) NOT NULL,
  `logourl` varchar(255) NOT NULL,
  `addmeta` varchar(255) DEFAULT NULL COMMENT '附加meta内容',
  `temp` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>