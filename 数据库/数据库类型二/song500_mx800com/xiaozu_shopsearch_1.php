<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shopsearch`;");
E_C("CREATE TABLE `xiaozu_shopsearch` (
  `shopid` int(20) NOT NULL,
  `parent_id` int(20) NOT NULL DEFAULT '0',
  `second_id` int(20) NOT NULL DEFAULT '0',
  `cattype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1外卖2订台'
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_shopsearch` values('2','51','78','0');");
E_D("replace into `xiaozu_shopsearch` values('2','51','75','0');");
E_D("replace into `xiaozu_shopsearch` values('2','51','74','0');");
E_D("replace into `xiaozu_shopsearch` values('2','51','72','0');");

require("../../inc/footer.php");
?>