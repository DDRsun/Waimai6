<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_top`;");
E_C("CREATE TABLE `xiaozu_top` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `typeid` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `instro` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>