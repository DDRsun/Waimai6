<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_gift`;");
E_C("CREATE TABLE `xiaozu_gift` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `market_cost` decimal(5,2) NOT NULL,
  `score` int(7) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `content` text,
  `typeid` int(10) NOT NULL DEFAULT '0',
  `sell_count` int(5) NOT NULL DEFAULT '0' COMMENT '销售数量',
  `stock` int(6) NOT NULL DEFAULT '0' COMMENT '库存',
  `img` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>