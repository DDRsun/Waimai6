<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_marketcate`;");
E_C("CREATE TABLE `xiaozu_marketcate` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '目录名称',
  `keywd` varchar(50) DEFAULT NULL COMMENT '关键字',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '上级ID  0为1级目录',
  `shopid` int(20) DEFAULT NULL,
  `orderid` int(5) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>