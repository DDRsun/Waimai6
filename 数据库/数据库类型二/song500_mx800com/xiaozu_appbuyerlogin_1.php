<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_appbuyerlogin`;");
E_C("CREATE TABLE `xiaozu_appbuyerlogin` (
  `uid` int(20) NOT NULL,
  `channelid` varchar(100) NOT NULL,
  `addtime` int(12) NOT NULL,
  `userid` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_appbuyerlogin` values('7','3942638910097427934','1410574948','822893913548000525');");
E_D("replace into `xiaozu_appbuyerlogin` values('680','3931633651968332694','1410332679','1035557201210891460');");
E_D("replace into `xiaozu_appbuyerlogin` values('690','3947641076228700960','1410427720','1149112417074034587');");
E_D("replace into `xiaozu_appbuyerlogin` values('691','3779471354991027696','1410834214','719976474914442210');");
E_D("replace into `xiaozu_appbuyerlogin` values('13','4487385448788386630','1410506061','624844243449682469');");
E_D("replace into `xiaozu_appbuyerlogin` values('696','3998918151909252731','1410505847','965873996598383695');");
E_D("replace into `xiaozu_appbuyerlogin` values('533','3727960254561923527','1410837632','739062240901617485');");

require("../../inc/footer.php");
?>