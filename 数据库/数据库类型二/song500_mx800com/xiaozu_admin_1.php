<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_admin`;");
E_C("CREATE TABLE `xiaozu_admin` (
  `uid` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `logintime` int(11) NOT NULL,
  `loginip` varchar(30) DEFAULT NULL,
  `limit` text,
  `groupid` int(20) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_admin` values('1','admin','21232f297a57a5a743894a0e4a801fc3','0','1411716432','127.0.0.1','siteset,seo_setsave,limitset,savelimitset,footlink,savefootlink,toplink,savetoplink,jflimitset,manegelist,manegeadd,editmanege,manegesave,delmanege,memberlist,addmember,editmember,membersave,delmember,oauthlist,deloauth,shoplist,shoplistw,shoptype,addshoptype,editshoptype,saveshoptype,delshoptype,arealist,addarea,eidtarea,savearea,cxsign,addcxsign,editcxsign,orderlist,ordertotal,markettj,marketorder,orderyjin,commentlist,delcommlist,asklist,delask,askshoplist,singlelist,addsingle,savesingle,delsingle,adv,addadv,advtype,giftlist,addgift,gifttype,addgifttype,giftlog,emailset,smsset,sendtasklist,sendtask,cardlist,addcard,juanlist,addjuan,excomm,pmes,loginlist,paylist,othertpl,ordertodayw,ordertoday,ordersend,basedata,rebkdata,market,addmarket,editmarket,delmarket,addmarkettype,delmarkettype,listmarkettype,friendlink,shoptopatt,wxset,wxback,wxmenu,printlog','1');");
E_D("replace into `xiaozu_admin` values('20','px2010','eb1f3562dc1fd5f20b5e370189b85b00','1407393163','0','','','1');");
E_D("replace into `xiaozu_admin` values('26','mx800com','e10adc3949ba59abbe56e057f20f883e','1411643501','1411645356','127.0.0.1',NULL,'1');");

require("../../inc/footer.php");
?>