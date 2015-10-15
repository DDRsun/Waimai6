<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_pmes`;");
E_C("CREATE TABLE `xiaozu_pmes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uid` int(20) DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `usercontent` text,
  `userimg` varchar(255) DEFAULT NULL,
  `creattime` int(12) NOT NULL DEFAULT '0',
  `backuid` int(20) NOT NULL DEFAULT '0',
  `backcontent` text,
  `backimg` varchar(255) DEFAULT NULL,
  `backtime` int(12) NOT NULL DEFAULT '0',
  `backusername` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>