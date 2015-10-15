<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_juan`;");
E_C("CREATE TABLE `xiaozu_juan` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `card` varchar(20) NOT NULL COMMENT '优惠劵卡号',
  `card_password` varchar(10) NOT NULL COMMENT '优惠劵密码',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态，0未使用，1已绑定，2已使用，3无效',
  `creattime` int(11) NOT NULL COMMENT '制造时间',
  `cost` int(5) NOT NULL COMMENT '优惠金额',
  `limitcost` int(5) NOT NULL COMMENT '购物车限制金额下限',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '失效时间',
  `uid` int(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(100) NOT NULL DEFAULT '0' COMMENT '用户名',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `name` varchar(50) NOT NULL DEFAULT '优惠劵',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_juan` values('1','1411405010260','b6c87','1','1411405010','1','1','1411405010','1','waimairen','0','注册账号赠送优惠券');");
E_D("replace into `xiaozu_juan` values('2','1411642948326','db624','1','1411642948','1','1','1411642948','2','mx800com','0','注册账号赠送优惠券');");
E_D("replace into `xiaozu_juan` values('3','1411716383180','16011','1','1411716383','1','1','1411716383','3','nihaonihaon','0','注册账号赠送优惠券');");

require("../../inc/footer.php");
?>