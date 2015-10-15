<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_ask`;");
E_C("CREATE TABLE `xiaozu_ask` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `uid` int(20) NOT NULL,
  `shopid` int(20) NOT NULL DEFAULT '0' COMMENT '当为网站留言时此值为0',
  `content` varchar(250) NOT NULL,
  `addtime` int(11) NOT NULL,
  `typeid` int(2) NOT NULL,
  `replycontent` varchar(250) DEFAULT NULL,
  `replytime` int(11) NOT NULL DEFAULT '0',
  `replyname` varchar(255) DEFAULT NULL COMMENT '回复者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_ask` values('1','0','0','edffdfd','1411645955','5',NULL,'0',NULL);");

require("../../inc/footer.php");
?>