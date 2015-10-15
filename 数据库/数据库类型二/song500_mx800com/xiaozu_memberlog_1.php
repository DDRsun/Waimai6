<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_memberlog`;");
E_C("CREATE TABLE `xiaozu_memberlog` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `userid` int(20) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '1积分/2资金',
  `addtype` int(1) NOT NULL DEFAULT '0' COMMENT '1增加2减少',
  `result` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '变动积分/金额数量',
  `addtime` int(12) NOT NULL DEFAULT '0',
  `content` varchar(255) DEFAULT NULL COMMENT '描述',
  `title` varchar(255) DEFAULT NULL,
  `acount` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '账户总金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_memberlog` values('1','1','1','1','40.00','1411405010','注册送积分40','注册送积分','40.00');");
E_D("replace into `xiaozu_memberlog` values('2','2','1','1','40.00','1411642948','注册送积分40','注册送积分','40.00');");
E_D("replace into `xiaozu_memberlog` values('3','3','1','1','40.00','1411716383','注册送积分40','注册送积分','40.00');");

require("../../inc/footer.php");
?>