<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `xiaozu_menu`;");
E_C("CREATE TABLE `xiaozu_menu` (
  `name` varchar(100) NOT NULL COMMENT '操作名称',
  `cnname` varchar(200) NOT NULL,
  `moduleid` int(20) NOT NULL,
  `group` int(20) NOT NULL,
  `id` int(5) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `xiaozu_menu` values('addarealist','后台添加购区域','10','1','1');");
E_D("replace into `xiaozu_menu` values('memberlist','普通会员列表','2','4','0');");
E_D("replace into `xiaozu_menu` values('adminarealist','后台区域列表','10','1','0');");
E_D("replace into `xiaozu_menu` values('errlog','错误日志','17','1','8');");
E_D("replace into `xiaozu_menu` values('cleartpl','清理缓存','17','1','7');");
E_D("replace into `xiaozu_menu` values('rebkdata','还原数据','17','1','6');");
E_D("replace into `xiaozu_menu` values('basedata','备份数据','17','1','5');");
E_D("replace into `xiaozu_menu` values('smsset','短信设置','17','1','4');");
E_D("replace into `xiaozu_menu` values('emailset','邮箱设置','17','1','3');");
E_D("replace into `xiaozu_menu` values('othertpl','短信/邮件模板设置','17','1','2');");
E_D("replace into `xiaozu_menu` values('loginlist','第三方登陆','17','1','1');");
E_D("replace into `xiaozu_menu` values('paylist','支付接口列表','17','1','0');");
E_D("replace into `xiaozu_menu` values('shop','店铺销量分析','18','6','0');");
E_D("replace into `xiaozu_menu` values('orderyjin','商家结算','5','6','0');");
E_D("replace into `xiaozu_menu` values('newslist','新闻列表','7','6','0');");
E_D("replace into `xiaozu_menu` values('giftlog','礼品兑换记录','8','1','2');");
E_D("replace into `xiaozu_menu` values('gifttype','礼品类型','8','1','1');");
E_D("replace into `xiaozu_menu` values('giftlist','礼品列表','8','1','0');");
E_D("replace into `xiaozu_menu` values('newstype','新闻分类','7','1','1');");
E_D("replace into `xiaozu_menu` values('newslist','新闻列表','7','7','0');");
E_D("replace into `xiaozu_menu` values('newstype','新闻分类','7','7','1');");
E_D("replace into `xiaozu_menu` values('newslist','新闻列表','7','1','0');");
E_D("replace into `xiaozu_menu` values('adminfastfoods','后台快速下单','5','1','7');");
E_D("replace into `xiaozu_menu` values('ordercomment','订单评价列表','5','1','6');");
E_D("replace into `xiaozu_menu` values('beizhulist','订单备注','5','1','5');");
E_D("replace into `xiaozu_menu` values('orderyjin','商家结算','5','1','4');");
E_D("replace into `xiaozu_menu` values('ordertotal','订单统计','5','1','3');");
E_D("replace into `xiaozu_menu` values('addmember','添加会员','2','4','1');");
E_D("replace into `xiaozu_menu` values('drawbacklog','退款申请处理','5','1','2');");
E_D("replace into `xiaozu_menu` values('ordertoday','当天订单处理','5','1','1');");
E_D("replace into `xiaozu_menu` values('orderlist','所有订单','5','1','0');");
E_D("replace into `xiaozu_menu` values('user','会员购买数据','18','1','3');");
E_D("replace into `xiaozu_menu` values('goods','菜品销量分析','18','1','2');");
E_D("replace into `xiaozu_menu` values('ordertoday','当天订单处理','5','12','0');");
E_D("replace into `xiaozu_menu` values('shop','店铺销量分析','18','1','1');");
E_D("replace into `xiaozu_menu` values('area','地区销量分析','18','1','0');");
E_D("replace into `xiaozu_menu` values('goodssign','促销商品标签','3','1','4');");
E_D("replace into `xiaozu_menu` values('shoptype','后台模型','3','1','3');");
E_D("replace into `xiaozu_menu` values('addshop','后台添加店铺','3','1','2');");
E_D("replace into `xiaozu_menu` values('adminshopwati','后台待审核商家','3','1','1');");
E_D("replace into `xiaozu_menu` values('adminshoplist','后台商家列表','3','1','0');");
E_D("replace into `xiaozu_menu` values('membergrade','会员成长等级','2','1','7');");
E_D("replace into `xiaozu_menu` values('grouplist','会员分组','2','1','6');");
E_D("replace into `xiaozu_menu` values('addgroup','添加会员分组','2','1','5');");
E_D("replace into `xiaozu_menu` values('addmember','添加会员','2','1','4');");
E_D("replace into `xiaozu_menu` values('memberlistshop','商家会员列表','2','1','3');");
E_D("replace into `xiaozu_menu` values('memberlist','普通会员列表','2','1','2');");
E_D("replace into `xiaozu_menu` values('addadmin','添加管理员','2','1','1');");
E_D("replace into `xiaozu_menu` values('adminlist','管理员列表','2','1','0');");
E_D("replace into `xiaozu_menu` values('limitset','后台菜单管理','1','1','8');");
E_D("replace into `xiaozu_menu` values('modullist','模块管理','1','1','7');");
E_D("replace into `xiaozu_menu` values('footinfo','网站底部','1','1','6');");
E_D("replace into `xiaozu_menu` values('toplink','网站导航','1','1','5');");
E_D("replace into `xiaozu_menu` values('sitebk','网站水印','1','1','4');");
E_D("replace into `xiaozu_menu` values('tempset','模板设置','1','1','3');");
E_D("replace into `xiaozu_menu` values('otherset','网站限制','1','1','2');");
E_D("replace into `xiaozu_menu` values('siteset','网站设置','1','1','1');");
E_D("replace into `xiaozu_menu` values('index','网站信息','1','1','0');");
E_D("replace into `xiaozu_menu` values('adminpsset','网站配送设置','10','1','2');");
E_D("replace into `xiaozu_menu` values('asklist','后台留言列表','11','1','0');");
E_D("replace into `xiaozu_menu` values('pmsglist','私信列表','11','1','1');");
E_D("replace into `xiaozu_menu` values('singlelist','单页列表','12','1','0');");
E_D("replace into `xiaozu_menu` values('cardlist','充值卡列表','16','1','0');");
E_D("replace into `xiaozu_menu` values('addcard','添加充值卡','16','1','1');");
E_D("replace into `xiaozu_menu` values('juanlist','优惠券列表','16','1','2');");
E_D("replace into `xiaozu_menu` values('addjuan','添加优惠券','16','1','3');");
E_D("replace into `xiaozu_menu` values('scoreset','积分设置','16','1','4');");
E_D("replace into `xiaozu_menu` values('prensentjuan','赠送卷设置','16','1','5');");
E_D("replace into `xiaozu_menu` values('sendtasklist','群发任务','16','1','6');");
E_D("replace into `xiaozu_menu` values('sendtask','发布群发任务','16','1','7');");
E_D("replace into `xiaozu_menu` values('advlist','广告列表','14','1','0');");
E_D("replace into `xiaozu_menu` values('advtype','广告类型','14','1','1');");

require("../../inc/footer.php");
?>