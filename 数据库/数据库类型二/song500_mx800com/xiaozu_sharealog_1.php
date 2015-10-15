<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_sharealog`;");
E_C("CREATE TABLE `xiaozu_sharealog` (
  `uid` int(20) NOT NULL,
  `childusername` varchar(100) DEFAULT NULL,
  `titile` varchar(255) DEFAULT NULL,
  `addtime` int(12) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>