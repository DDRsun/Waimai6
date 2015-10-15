<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_shoptype`;");
E_C("CREATE TABLE `xiaozu_shoptype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL COMMENT '分类名',
  `type` varchar(100) DEFAULT NULL COMMENT 'checkbox多选，radio单选，img图片，input输入框',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '0表示导航明，1值',
  `cattype` int(1) NOT NULL DEFAULT '1' COMMENT '1外卖2订台3其他',
  `is_search` int(1) NOT NULL DEFAULT '0' COMMENT '0不是搜索关键字1搜索关键字',
  `is_main` int(1) NOT NULL DEFAULT '0' COMMENT '是否展示在店铺列表 0否1是',
  `is_admin` int(1) NOT NULL DEFAULT '0' COMMENT '设置类型0店铺1后台',
  `instro` varchar(255) DEFAULT NULL COMMENT '简单介绍',
  `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_shoptype` values('51','果品','checkbox','0','0','1','1','0','','1');");
E_D("replace into `xiaozu_shoptype` values('76','2','0','67','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('75','推荐','0','51','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('57','外买配送时间0','input','0','0','0','1','0','','2');");
E_D("replace into `xiaozu_shoptype` values('58','60分钟','0','57','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('59','店铺图标','img','0','0','0','1','0','','0');");
E_D("replace into `xiaozu_shoptype` values('60','/upload/goods/20140614190450621.png','0','59','0','0','0','0','限时配送','0');");
E_D("replace into `xiaozu_shoptype` values('61','/upload/goods/20140614190459687.png','0','59','0','0','0','0','满10元送可乐','0');");
E_D("replace into `xiaozu_shoptype` values('62','/upload/goods/20140614190507505.png','0','59','0','0','0','0','满10元减2元','0');");
E_D("replace into `xiaozu_shoptype` values('63','/upload/goods/20140614190515292.png','0','59','0','0','0','0','最新入驻商家','0');");
E_D("replace into `xiaozu_shoptype` values('68','1','0','67','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('74','川菜','0','51','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('73','炒饭','0','51','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('72','盖浇','0','51','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('78','冒菜','0','51','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('84','1','0','83','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('85','2','0','83','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('90','a','0','89','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('91','b','0','89','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('92','c','0','89','0','0','0','0','','0');");
E_D("replace into `xiaozu_shoptype` values('93','d','0','89','0','0','0','0','','0');");

require("../../inc/footer.php");
?>