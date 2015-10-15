<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_orderdet`;");
E_C("CREATE TABLE `xiaozu_orderdet` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `order_id` int(20) NOT NULL,
  `goodsid` int(20) NOT NULL,
  `goodsname` varchar(150) NOT NULL,
  `goodscost` decimal(5,2) NOT NULL,
  `goodscount` int(2) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `shopid` int(5) NOT NULL,
  `is_send` int(11) NOT NULL DEFAULT '0' COMMENT '是否是赠品 1赠品',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>