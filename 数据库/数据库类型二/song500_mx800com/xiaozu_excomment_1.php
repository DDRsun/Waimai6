<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_excomment`;");
E_C("CREATE TABLE `xiaozu_excomment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `userid` int(20) NOT NULL COMMENT '用户ID',
  `username` varchar(255) NOT NULL,
  `userlog` varchar(255) NOT NULL,
  `addtime` int(12) NOT NULL COMMENT '评价时间',
  `score` int(1) NOT NULL DEFAULT '0' COMMENT '评分',
  `comtype` int(1) NOT NULL COMMENT '1网站 2订单   ',
  `scoreto` varchar(255) DEFAULT '0' COMMENT '评价对象',
  `shopid` int(20) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `content` text COMMENT '内容',
  `comacout` int(5) NOT NULL DEFAULT '0' COMMENT '被回复次数',
  `orderid` int(20) DEFAULT '0' COMMENT '订单ID',
  `imgurl` varchar(255) DEFAULT NULL,
  `orderctime` int(12) DEFAULT '0' COMMENT '订单消费时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>