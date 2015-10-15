<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_areatoadd`;");
E_C("CREATE TABLE `xiaozu_areatoadd` (
  `areaid` int(20) NOT NULL,
  `shopid` int(20) NOT NULL,
  `cost` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_areatoadd` values('1','0','0');");
E_D("replace into `xiaozu_areatoadd` values('1','1','0');");
E_D("replace into `xiaozu_areatoadd` values('1','2','0');");
E_D("replace into `xiaozu_areatoadd` values('2','0','0');");

require("../../inc/footer.php");
?>