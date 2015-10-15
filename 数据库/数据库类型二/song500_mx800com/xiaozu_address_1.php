<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_address`;");
E_C("CREATE TABLE `xiaozu_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otherphone` varchar(15) DEFAULT NULL,
  `contactname` varchar(50) DEFAULT NULL,
  `default` int(1) NOT NULL DEFAULT '1',
  `areaid1` int(20) NOT NULL DEFAULT '0' COMMENT '区域1ID',
  `areaid2` int(20) NOT NULL DEFAULT '0' COMMENT '区域2ID',
  `areaid3` int(20) NOT NULL DEFAULT '0' COMMENT '区域3ID',
  `sex` smallint(1) NOT NULL DEFAULT '0' COMMENT '0女性 1男性',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>