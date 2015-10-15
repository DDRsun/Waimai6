<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_mobile`;");
E_C("CREATE TABLE `xiaozu_mobile` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `phone` varchar(50) NOT NULL,
  `addtime` int(12) NOT NULL,
  `code` varchar(50) NOT NULL,
  `is_send` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>