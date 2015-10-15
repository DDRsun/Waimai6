<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_newstype`;");
E_C("CREATE TABLE `xiaozu_newstype` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '目录类型：1文章目录 2包含下级目录 ',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '上级目录ID，0： 顶级目录',
  `displaytype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：一个文章显示一个页面，2表示此目录文章先到到一个文章里',
  `orderid` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_newstype` values('3','新手入门','1','0','1','2');");
E_D("replace into `xiaozu_newstype` values('2','关于我们','1','0','1','1');");
E_D("replace into `xiaozu_newstype` values('4','物流及售后','1','0','1','3');");
E_D("replace into `xiaozu_newstype` values('5','支付方式1','1','0','1','4');");
E_D("replace into `xiaozu_newstype` values('11','用户指南','2','0','1','1');");
E_D("replace into `xiaozu_newstype` values('12','常见问题','1','11','1','3');");

require("../../inc/footer.php");
?>