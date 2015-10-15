<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_module`;");
E_C("CREATE TABLE `xiaozu_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `cnname` varchar(100) NOT NULL,
  `install` int(1) NOT NULL DEFAULT '0' COMMENT '0表未安装1，表已安装',
  `parent_id` int(5) NOT NULL DEFAULT '0' COMMENT '上级模块',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_module` values('1','system','系统管理','1','0');");
E_D("replace into `xiaozu_module` values('2','member','用户管理','1','0');");
E_D("replace into `xiaozu_module` values('3','shop','店铺管理','1','0');");
E_D("replace into `xiaozu_module` values('18','analysis','数据分析模块','1','0');");
E_D("replace into `xiaozu_module` values('5','order','订单管理','1','0');");
E_D("replace into `xiaozu_module` values('7','news','内容管理','1','0');");
E_D("replace into `xiaozu_module` values('8','gift','礼品管理','1','0');");
E_D("replace into `xiaozu_module` values('17','other','其他管理','1','0');");
E_D("replace into `xiaozu_module` values('10','area','区域管理','1','3');");
E_D("replace into `xiaozu_module` values('11','ask','留言管理','1','7');");
E_D("replace into `xiaozu_module` values('12','single','单页','1','7');");
E_D("replace into `xiaozu_module` values('16','card','营销管理','1','0');");
E_D("replace into `xiaozu_module` values('14','adv','广告管理','1','7');");
E_D("replace into `xiaozu_module` values('20','html5','手机模块','1','16');");

require("../../inc/footer.php");
?>