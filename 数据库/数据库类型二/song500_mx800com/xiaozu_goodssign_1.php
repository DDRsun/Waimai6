<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_goodssign`;");
E_C("CREATE TABLE `xiaozu_goodssign` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `imgurl` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL COMMENT 'goods商品,shop店铺,cx促销3类型',
  `instro` varchar(100) DEFAULT NULL COMMENT '说明',
  `typevalue` int(1) NOT NULL DEFAULT '0' COMMENT '0无,1新品，2热门，3推荐',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>