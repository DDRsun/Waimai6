<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_siteset`;");
E_C("CREATE TABLE `xiaozu_siteset` (
  `id` int(1) NOT NULL,
  `instro` text NOT NULL,
  `gonggao` varchar(250) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_siteset` values('1','fds','dfs','111');");

require("../../inc/footer.php");
?>