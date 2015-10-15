<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_rule`;");
E_C("CREATE TABLE `xiaozu_rule` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '规则名称',
  `type` int(1) NOT NULL COMMENT '默认1，购物车总价',
  `limittype` int(1) NOT NULL COMMENT '是否指定具体时间1否2指定星期3指定小时',
  `limittime` varchar(255) NOT NULL COMMENT '具体时间：周几，或者具体时间',
  `limitcontent` int(5) NOT NULL COMMENT '限制内容购物车总价',
  `controltype` int(1) NOT NULL COMMENT '规则类型：1赠，3折扣，2减费用',
  `controlcontent` int(20) DEFAULT NULL COMMENT '限制内容填写赠品ID，折扣率，费用等大于0',
  `starttime` int(11) NOT NULL COMMENT '开始时间',
  `endtime` int(11) NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '状态1有效 2无效',
  `shopid` int(20) NOT NULL COMMENT '店铺id',
  `presenttitle` varchar(255) DEFAULT NULL COMMENT '赠品标题',
  `signid` int(20) NOT NULL,
  `cattype` int(1) NOT NULL DEFAULT '1' COMMENT '1外卖 2订台',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>