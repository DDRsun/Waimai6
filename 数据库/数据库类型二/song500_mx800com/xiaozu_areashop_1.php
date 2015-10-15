<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_areashop`;");
E_C("CREATE TABLE `xiaozu_areashop` (
  `areaid` int(20) DEFAULT NULL,
  `shopid` int(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_areashop` values('1','0');");
E_D("replace into `xiaozu_areashop` values('1','1');");
E_D("replace into `xiaozu_areashop` values('1','2');");
E_D("replace into `xiaozu_areashop` values('2','0');");

require("../../inc/footer.php");
?>