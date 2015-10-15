<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_member`;");
E_C("CREATE TABLE `xiaozu_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `address` varchar(250) NOT NULL,
  `creattime` int(11) NOT NULL,
  `logintime` int(11) NOT NULL,
  `usertype` int(1) NOT NULL COMMENT '0.普通会员，1开店商家',
  `score` int(5) NOT NULL DEFAULT '0' COMMENT '积分',
  `cost` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '账号余额',
  `loginip` varchar(30) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '账号是否可用',
  `safepwd` varchar(100) DEFAULT NULL COMMENT '支付密码',
  `group` int(2) DEFAULT '5',
  `is_bang` int(1) NOT NULL DEFAULT '0',
  `parent_id` int(20) DEFAULT '0',
  `total` int(6) DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_member` values('1','abf3f486b0332cb496ae44af6b5c238a','waimairen@qq.com','waimairen','18538129330','','1411405010','1411405010','0','40','0.00','115.60.10.99','','0',NULL,'3','0','0','0');");
E_D("replace into `xiaozu_member` values('2','e10adc3949ba59abbe56e057f20f883e','mx800com@126.com','mx800com','15625254525','','1411642948','1411646125','0','40','0.00','127.0.0.1','','0',NULL,'3','0','0','0');");
E_D("replace into `xiaozu_member` values('3','7036317bfa74b871c8687f0d6bf412f8','','nihaonihaon','15652565256','','1411716383','1411716383','0','40','0.00','127.0.0.1','','0',NULL,'5','0','0','0');");

require("../../inc/footer.php");
?>