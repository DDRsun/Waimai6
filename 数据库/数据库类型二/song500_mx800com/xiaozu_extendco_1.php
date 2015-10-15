<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_extendco`;");
E_C("CREATE TABLE `xiaozu_extendco` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `userid` int(20) DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `userlog` varchar(255) NOT NULL,
  `comid` int(20) NOT NULL,
  `addtime` int(12) NOT NULL,
  `content` text,
  `parent_id` int(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>