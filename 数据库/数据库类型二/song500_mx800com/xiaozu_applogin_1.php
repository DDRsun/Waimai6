<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_applogin`;");
E_C("CREATE TABLE `xiaozu_applogin` (
  `uid` int(20) NOT NULL,
  `channelid` varchar(100) NOT NULL,
  `addtime` int(12) NOT NULL,
  `userid` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_applogin` values('464','4464484622184929951','1410679182','805831160254297242');");
E_D("replace into `xiaozu_applogin` values('30','3878755219370652781','1409408480','896248702304885526');");
E_D("replace into `xiaozu_applogin` values('96','4085920095957395924','1409388726','1063827103602767853');");
E_D("replace into `xiaozu_applogin` values('642','3482660456830030040','1409407818','593277220651345740');");
E_D("replace into `xiaozu_applogin` values('29','3942638910097427934','1410577403','1081876789384761731');");
E_D("replace into `xiaozu_applogin` values('13','3727960254561923527','1410837523','882517251033415142');");

require("../../inc/footer.php");
?>