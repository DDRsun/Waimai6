<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_areatomar`;");
E_C("CREATE TABLE `xiaozu_areatomar` (
  `areaid` int(20) NOT NULL,
  `shopid` int(20) NOT NULL,
  `cost` decimal(3,1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>