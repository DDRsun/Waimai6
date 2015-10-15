<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_goods`;");
E_C("CREATE TABLE `xiaozu_goods` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `typeid` int(10) NOT NULL COMMENT '商品类型',
  `name` varchar(100) NOT NULL COMMENT '商品名称',
  `count` int(5) NOT NULL COMMENT '商品数量',
  `cost` decimal(5,2) NOT NULL COMMENT '商品价格',
  `img` varchar(150) NOT NULL COMMENT '图片地址',
  `point` int(5) NOT NULL COMMENT '评分',
  `sellcount` int(5) NOT NULL COMMENT '销售数量',
  `shopid` int(1) NOT NULL COMMENT '店铺ID',
  `uid` int(20) NOT NULL,
  `signid` varchar(100) NOT NULL COMMENT '促销标签ID集',
  `pointcount` int(5) NOT NULL DEFAULT '0' COMMENT '评价次数',
  `instro` text COMMENT '简单说明',
  `daycount` int(5) NOT NULL DEFAULT '0' COMMENT '每日销售数量',
  `marketcost` decimal(6,2) NOT NULL COMMENT '超市价格',
  `is_com` varchar(1) NOT NULL DEFAULT '0' COMMENT '商城使用字段 1首页推荐',
  `show_com` int(1) NOT NULL DEFAULT '0' COMMENT '是否在店铺展示中显示',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热卖',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新品',
  `bagcost` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '打包费',
  `shoptype` int(1) NOT NULL COMMENT '0外卖1超市',
  PRIMARY KEY (`id`),
  KEY `shopid` (`shopid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_goods` values('1','1','外卖人网上订餐系统','100','40.00','','0','0','1','0','','0','','100','0.00','0','0','0','0','0.00','0');");
E_D("replace into `xiaozu_goods` values('2','2','黄瓜西红柿','33','12.00','','0','0','2','2','','0','','100','0.00','0','0','0','0','0.00','0');");
E_D("replace into `xiaozu_goods` values('3','2','白菜','11','11.00','','0','0','2','2','','0','','100','0.00','0','0','0','0','0.00','0');");

require("../../inc/footer.php");
?>