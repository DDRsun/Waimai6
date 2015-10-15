<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_friendlink`;");
E_C("CREATE TABLE `xiaozu_friendlink` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `type` smallint(1) NOT NULL DEFAULT '1',
  `typevalue` varchar(255) DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `orderid` int(10) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>