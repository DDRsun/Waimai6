<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_comment`;");
E_C("CREATE TABLE `xiaozu_comment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `orderid` int(20) NOT NULL,
  `orderdetid` int(20) NOT NULL,
  `shopid` int(20) NOT NULL,
  `goodsid` int(20) NOT NULL,
  `uid` int(20) NOT NULL DEFAULT '0',
  `content` varchar(250) DEFAULT NULL,
  `addtime` int(12) NOT NULL DEFAULT '0',
  `replycontent` varchar(250) DEFAULT NULL,
  `replytime` int(11) NOT NULL DEFAULT '0',
  `point` int(1) NOT NULL COMMENT '评分',
  `is_show` int(1) NOT NULL DEFAULT '0' COMMENT '0展示，1不展示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>