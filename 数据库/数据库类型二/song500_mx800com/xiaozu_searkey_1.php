<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_searkey`;");
E_C("CREATE TABLE `xiaozu_searkey` (
  `type` int(1) NOT NULL COMMENT '1外卖，2订台，3商城',
  `goid` int(20) NOT NULL,
  `keycontent` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  FULLTEXT KEY `keycontent` (`keycontent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>