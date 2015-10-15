<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_adv`;");
E_C("CREATE TABLE `xiaozu_adv` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '广告标题',
  `advtype` varchar(10) NOT NULL COMMENT '广告类型code',
  `img` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `linkurl` varchar(255) DEFAULT NULL COMMENT '超链接',
  `module` varchar(50) DEFAULT 'newgreen',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_adv` values('37','首页幻灯B','lunbo','/upload/goods/20140923005254192.png','#','newgreen');");
E_D("replace into `xiaozu_adv` values('25','1','lunbo','/upload/goods/20140814174845428.jpg','#','gaodashang');");
E_D("replace into `xiaozu_adv` values('26','l2','lunbo','/upload/goods/20140814174901672.jpg','#','gaodashang');");
E_D("replace into `xiaozu_adv` values('27','1','lunbo','/upload/goods/20140814175650365.jpg','#','default');");
E_D("replace into `xiaozu_adv` values('28','2','lunbo','/upload/goods/20140814175705576.jpg','#','default');");
E_D("replace into `xiaozu_adv` values('29','2','lunbo','/upload/goods/20140814175720577.jpg','#','default');");
E_D("replace into `xiaozu_adv` values('30','1','other1','/upload/goods/20140814175915161.png','#','default');");
E_D("replace into `xiaozu_adv` values('31','2','other1','/upload/goods/20140814175930418.png','#','default');");
E_D("replace into `xiaozu_adv` values('32','3','other1','/upload/goods/20140814175947522.png','#','default');");
E_D("replace into `xiaozu_adv` values('33','4','other1','/upload/goods/20140814180006265.png','#','default');");
E_D("replace into `xiaozu_adv` values('34','5','other1','/upload/goods/20140814180021710.png','#','default');");
E_D("replace into `xiaozu_adv` values('36','首页幻灯A','lunbo','/upload/goods/20140923005153756.png','http://www.waimairen.com','newgreen');");

require("../../inc/footer.php");
?>