<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_wxback`;");
E_C("CREATE TABLE `xiaozu_wxback` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `msgtype` int(1) NOT NULL DEFAULT '1',
  `values` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_wxback` values('3','a','3','a:1:{i:0;a:4:{s:6:\"biaoti\";s:23:\"4月1号愚人节快乐\";s:7:\"miaoshu\";s:339:\"             本服务协议双方分别为深圳市网生活信息技术有限公司旗下网站“外卖人”（以下简称“外卖人”）与“外卖人”网站用户，本服务协议具有合同效力。用户必须为具备完全民事行为能力的自然人，或者是具有合法经营资格的实体组织。\r\n             \";s:6:\"tupian\";s:58:\"http://m2.waimairen.com/upload/goods/20140328195812444.jpg\";s:7:\"lianjie\";s:13:\"www.baidu.com\";}}');");
E_D("replace into `xiaozu_wxback` values('4','x','1','a:2:{s:8:\"lj_title\";s:12:\"我要下单\";s:7:\"lj_link\";s:64:\"http://m2.waimairen.com/index.php?controller=wxsite&action=index\";}');");
E_D("replace into `xiaozu_wxback` values('5','3','2','我想有个更长的名字测试你好啊啊');");
E_D("replace into `xiaozu_wxback` values('6','subscribe','2','感谢您关注外卖人官方微信\r\n菜单装口袋  店铺随身带\r\n快速订外卖  方便有实在\r\n您可以点击在线订餐选择您所在的地区订餐\r\n也可以查看附近店铺 请您发送您的地理位置\r\n点击左下角的小键盘\r\n点击+\r\n点击位置并发送\r\n1绑定帐号回复:帐号##密码  \r\n2查看最近订单回复:c\r\n3.查看积分回复:j');");
E_D("replace into `xiaozu_wxback` values('7','d','1','a:2:{s:8:\"lj_title\";s:2:\"dd\";s:7:\"lj_link\";s:3:\"ddd\";}');");

require("../../inc/footer.php");
?>