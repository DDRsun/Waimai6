<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_wxuser`;");
E_C("CREATE TABLE `xiaozu_wxuser` (
  `openid` varchar(255) NOT NULL,
  `uid` int(20) NOT NULL,
  `is_bang` int(1) NOT NULL DEFAULT '0',
  `wxlat` varchar(255) DEFAULT NULL,
  `wxlng` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>