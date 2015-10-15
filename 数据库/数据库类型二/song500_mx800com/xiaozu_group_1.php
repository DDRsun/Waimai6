<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_group`;");
E_C("CREATE TABLE `xiaozu_group` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '用户组名称',
  `type` varchar(100) NOT NULL COMMENT '前台或者后台',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_group` values('1','超级管理员','admin');");
E_D("replace into `xiaozu_group` values('2','配送员','font');");
E_D("replace into `xiaozu_group` values('3','商家会员','font');");
E_D("replace into `xiaozu_group` values('4','普通管理员','admin');");
E_D("replace into `xiaozu_group` values('5','普通会员','font');");

require("../../inc/footer.php");
?>