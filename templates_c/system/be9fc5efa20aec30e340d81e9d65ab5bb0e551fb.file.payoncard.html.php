<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:22:13
         compiled from "E:\phpnow\htdocs\module\member\template\payoncard.html" */ ?>
<?php /*%%SmartyHeaderCode:14907542506153fe8e7-20370710%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be9fc5efa20aec30e340d81e9d65ab5bb0e551fb' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\member\\template\\payoncard.html',
      1 => 1406886220,
      2 => 'file',
    ),
    'd889bd91db1d236442f7f694eda764fb110b98cb' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\member\\member.html',
      1 => 1408019000,
      2 => 'file',
    ),
    '9237284e2d14756fd32e5ee5ead6f049d52f3b6e' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\public\\site.html',
      1 => 1407569152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14907542506153fe8e7-20370710',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tempdir' => 0,
    'sitename' => 0,
    'keywords' => 0,
    'description' => 0,
    'metadata' => 0,
    'siteurl' => 0,
    'is_static' => 0,
    'controlname' => 0,
    'member' => 0,
    'sitelogo' => 0,
    'footlink' => 0,
    'items' => 0,
    'sitebk' => 0,
    'mapname' => 0,
    'shopsearch' => 0,
    'toplink' => 0,
    'beian' => 0,
    'footerdata' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.10',
  'unifunc' => 'content_542506157e35c6_17160781',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_542506157e35c6_17160781')) {function content_542506157e35c6_17160781($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 
  会员中心
  -<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
</title>
<meta name="Keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<?php echo stripslashes($_smarty_tpl->tpl_vars['metadata']->value);?>

<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/images/favicon.ico" /> 
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/common.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/server.css"> 
 <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/memberCenter.css">
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/jquerynew.js" type="text/javascript" language="javascript"></script>
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/allj.js" type="text/javascript" language="javascript"></script>
 <script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/artdialog/artDialog.js?skin=blue"></script> 
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/template.min.js" type="text/javascript" language="javascript"></script>
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/jquery.lazyload.min.js" type="text/javascript" language="javascript"></script>
 <script>
 	$(function() { 
$("img").lazyload({ 
effect : "fadeIn" 
}); 
}); 

 	</script>


 <script> 
	var siteurl = "<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
";
	var is_static ="<?php echo $_smarty_tpl->tpl_vars['is_static']->value;?>
";
	var controllername= '<?php echo $_smarty_tpl->tpl_vars['controlname']->value;?>
';
</script>

<!--[if lte IE 6]>
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/DD_belatedPNG_0.0.8a.js" type="text/javascript"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('div, ul, img, li, input , a'); 
    </script>
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/ie6.js" type="text/javascript"></script>
<![endif]--> 
</head> 
<body> 
<div id="toTop" style="left: 1212.5px; display: none;"></div>




<div class="top">
	 <div class="top_a">
	 	  <div class="top_a_show">
	 	  	<div class="top_a_show_l">
	 	  		  <a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/site/index"),$_smarty_tpl);?>
" class=""><span class="topmcbck"></span> <div style="padding-left:25px;">欢迎光临<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
</div></a>
	 	  	</div>
	 	  	<div class="top_a_show_r">
	 	  		<?php if (empty($_smarty_tpl->tpl_vars['member']->value['uid'])){?>
	 	  		 <div class="top_a_show_span"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/regester"),$_smarty_tpl);?>
">注册</a></div>
	 	  		 <div class="top_a_show_span">|</div>
	 	  		 <div class="top_a_show_span"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/login"),$_smarty_tpl);?>
">登陆</a></div>
	 	  		 <div class="top_a_show_span">|</div>
	 	  		 	 <a href="#">  <div class="sina"></div> </a>
	 	  		 <a href="#">  <div class="qq"></div> </a>
	 	  		 <?php }else{ ?>
	 	  		  <div class="top_a_show_span"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/loginout"),$_smarty_tpl);?>
">退出登陆</a></div>
	 	  		 <div class="top_a_show_span">|</div>
	 	  		  <div class="top_a_show_span"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usersorder"),$_smarty_tpl);?>
">个人中心</a></div>
	 	  		 <div class="top_a_show_span">|</div>
	 	  		  <div class="top_a_show_span"><?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
</div> 
	 	  		 <?php }?>
	 	  	
	 	  		
	 	  		 
	 	  		 
	 	  	</div>
	 	  </div>
  </div>
	 <div class="top1">
	 	 <div class="top1_show">
	 	 	
	 	  <div class="top1_logo"><a href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['sitelogo']->value;?>
"></a></div>
	 	  <div class="top1_menu">
	 	     <ul>
	 	     	<?php if (!empty($_smarty_tpl->tpl_vars['footlink']->value)){?>
	 	     	<?php $_smarty_tpl->tpl_vars['footlink'] = new Smarty_variable(unserialize($_smarty_tpl->tpl_vars['footlink']->value), null, 0);?>
	 	     		<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['myid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['footlink']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['myid']->value = $_smarty_tpl->tpl_vars['items']->key;
?> 
			          <li class="active_li" data="<?php echo $_smarty_tpl->tpl_vars['items']->value['typeurl'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['items']->value['typeurl'];?>
"><?php echo $_smarty_tpl->tpl_vars['items']->value['typename'];?>
</a> </li>
			      <?php } ?>
	 	     <?php }?>
	 	     	</ul>	
	 	  	
	 	  </div>
	 	   
	 	   
	 	   
	 	 </div>
	 </div>
	
</div> 



 
 
 <div class="box950 pad_tb_20" id="main" >
	<div class="box150">
	  <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/base.css"> 
     <div class="title">
     		<span class="colortip"></span><span class="title_name"><b><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/base"),$_smarty_tpl);?>
">我的账户</a></b></span>
      </div>
     <div class="content"><!-- 左侧菜单 -->
     		<ul class="navLeft1">
       		 
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>资料管理</span><span class="arrow_bottom" id="cate-a1"></span></a>
     				  <div class="navLeft2" id="a1" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/base"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/base'){?> class="navLeft1_cur"<?php }?> >基本资料</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/edituser"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/edituser'){?> class="navLeft1_cur"<?php }?> >修改资料</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/area/useraddress"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='area/useraddress'){?> class="navLeft1_cur"<?php }?> >配送资料</p></a>
     				  </div>
     				</li>	 
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>订单管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     				  <div class="navLeft2" id="a2" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usersorder"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='order/usersorder'){?> class="navLeft1_cur"<?php }?> >餐厅订单</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usermorder"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='order/usermorder'){?> class="navLeft1_cur"<?php }?> >超市订单</p></a> 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/drawbacklog"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/drawbacklog'){?> class="navLeft1_cur"<?php }?> >我要退款</p></a> 
     				  </div>
     				</li>	 
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>积分管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     				  <div class="navLeft2" id="a2" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/jifenlog"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/jifenlog'){?> class="navLeft1_cur"<?php }?> >我的积分</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/gift/usergift"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='gift/usergift'){?> class="navLeft1_cur"<?php }?> >积分礼品</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/card/myjuan"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='card/myjuan'){?> class="navLeft1_cur"<?php }?> >我的优惠卷</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/share"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/share'){?> class="navLeft1_cur"<?php }?> >邀请好友</p></a>
     				  </div>
     				</li>	
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>留言管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     				  <div class="navLeft2" id="a2" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/waitpiont"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='order/waitpiont'){?> class="navLeft1_cur"<?php }?> >待点评订单</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/overpiont"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='order/overpiont'){?> class="navLeft1_cur"<?php }?> >我的点评</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/ask/pmessage"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='ask/pmessage'){?> class="navLeft1_cur"<?php }?> >我的私信</p></a>
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/ask/myask"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='ask/myask'){?> class="navLeft1_cur"<?php }?> >我的留言</p></a> 
     				  </div>
     				</li>	
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>收藏管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     				  <div class="navLeft2" id="a2" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/shop/collect"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='shop/collect'){?> class="navLeft1_cur"<?php }?> >我的收藏</p></a> 
     				  </div>
     				</li>	
     				<?php if ($_smarty_tpl->tpl_vars['open_acout']->value==1){?>
     				<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>充值管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     				  <div class="navLeft2" id="a2" > 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/paylog"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/paylog'){?> class="navLeft1_cur"<?php }?> >帐号资金记录</p></a> 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/payoncard"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/payoncard'){?> class="navLeft1_cur"<?php }?> >我要充值</p></a> 
     				   <!--    <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/onlinelog"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='member/onlinelog'){?> class="navLeft1_cur"<?php }?> >在线支付记录</p></a>   -->
     				  </div>
     				</li>	
     				<?php }?>
     					<li class="light">
     					<a class="supervise" href="javascript:void(0)"><span class="square"></span><span>店铺管理</span><span class="arrow_bottom" id="cate-a2"></span></a>
     					 <div class="navLeft2" id="a2" > 
     				<?php echo smarty_function_load_data(array('assign'=>"checkinfo",'table'=>"shop",'type'=>"one",'where'=>"uid='".((string)$_smarty_tpl->tpl_vars['member']->value['uid'])."' and is_pass='1' ",'fileds'=>"*"),$_smarty_tpl);?>

     				<?php if (!empty($_smarty_tpl->tpl_vars['checkinfo']->value)){?> 
     				      <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/shop/mangeshop/id/".((string)$_smarty_tpl->tpl_vars['checkinfo']->value['id'])),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='shop/openshop'){?> class="navLeft1_cur"<?php }?> target="_blank" >我的店铺</p></a> 
     				<?php }else{ ?> 
     				        <a class="gray_w" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/shop/openshop"),$_smarty_tpl);?>
" id="cateId1"><p <?php if ($_smarty_tpl->tpl_vars['urlshort']->value=='shop/openshop'){?> class="navLeft1_cur"<?php }?> >我要开店</p></a> 
     				<?php }?> 
     					  </div>
     				</li>	
     				
     			</ul>
     </div>
	</div>
 
	
	  
	<div class="mc-right box790"><!--mc-right-->
					<div class="manageBox margin_bottom"><!--manageBox-->
						<div class="title"><!--title-->
							<p class="yleft"><span class="colortip"></span>
							<span class="title_name"><b>我要充值</b></span></p>
						</div><!--title end-->



 <div style="background-color:#fff;border:1px solid #ccc;margin-bottom:10px;">
            	<p class="accinfo"> 
										   <span class="title">账户余额：</span><span id="acc_balance"><?php echo $_smarty_tpl->tpl_vars['member']->value['cost'];?>
</span>元 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										   <span class="title">充值直扣支付次数：</span><span id="chongzhiOrderCount" style="font-size:14px;color:#666;font-weight:bold">0</span> 次
										   &nbsp;&nbsp;&nbsp; 
										   
										</p>
            </div>





						<div class="mr-middle"><!--mr-middle-->
							<div id="account_balance" class="account_balance"><!--account_balance-->
								 
										

										<!--充值信息-->
										<div class="Recharge">
											<h2>填写充值金额</h2>
											<div class="RechargeMes">
												<p>充值账户：<span id="account-name"><?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
</span></p>
												<p>充值金额：<input type="text" id="Sum" value="">元&nbsp;&nbsp;<span class="error-msg" id="error-msg">只能填写大于等于10，小于等于50000的整数金额</span></p>
												<p class="RechargeNotice">请注意：支持国内主流银行储蓄卡充值，在线支付成功后，充值金额会在1分钟内到账</p>
												<a href="javascript:void(0);" class="RechargeNextBtn1" id="nextpay">下一步</a>
											</div>

											<!--温馨提示-->
											<div class="tips">
												<h3>温馨提示</h3>
												<ol class="tipsList">
													<li>1. 充值成功后，余额可能存在延迟现象，一般1到5分钟会内到账，如有问题，请咨询客服； </li>
													<li>2. 充值金额输入值必须是不小于10且不大于50000的正整数； </li>
													<li>3. 您只能用储蓄卡在线支付充值金额的钱，如遇到任何支付问题可以查看在线支付帮助； </li>
													<li>4. 充值完成后，您可以进入账户充值记录页面进行查看余额充值状态。  </li>
												</ol>
											</div> 
										</div>
										
								 
										
										
									</div>
								</div><!--account_current end-->
								
								
								<div class="mr-middle" style="margin-top:10px;"><!--mr-middle-->
							<div id="account_balance" class="account_balance"><!--account_balance-->
								
								
									<div class="Recharge"  >
											<h2>充值卡充值</h2>
											<div class="RechargeMes">
												<form id="adfrom" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/card/exchangcard/datatype/json"),$_smarty_tpl);?>
" style="border:none;">
												<p>充值账户：<span id="account-name"><?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
</span></p>
												<p>卡号：<input type="text" id="card" name="card" value=""></p>
												<p>卡密：<input type="password" id="password" name="password" value=""></p> 
												<a href="javascript:newAddr();" class="RechargeNextBtn1" >确认提交</a>
											</form>
											</div> 
										</div> 
										
										
										
									</div>
								</div><!--account_current end-->
								

						 
					</div><!--mc-right end-->
				</div>
	
<script>
	 
	function newAddr()
	{
		 
		subform('',$('#adfrom'));
	}
	 
	$('#nextpay').click(function(){
		var newlink = '<?php echo FUNC_function(array('type'=>'url','link'=>"/member/payonline/cost/@cost@"),$_smarty_tpl);?>
';
		var showlink = newlink.replace('@cost@',$('#Sum').val());
		//Sum
		window.open(showlink);  
	});
 
</script>
 


 <script>
$(function(){ 
 $('.show_page a').wrap('<li></li>');
 $('.show_page').find('.current').eq(0).parent().css({'background':'#f60'}); 
});
   
</script>
 </div>
 

<div id="footer">
	
<div class="hc_btm_div">
		<div class="hc_btm_contact">
		   <?php if (!empty($_smarty_tpl->tpl_vars['toplink']->value)){?>
	 	      <?php $_smarty_tpl->tpl_vars['toplink'] = new Smarty_variable(unserialize($_smarty_tpl->tpl_vars['toplink']->value), null, 0);?>
		  	  <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['myid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['toplink']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['myid']->value = $_smarty_tpl->tpl_vars['items']->key;
?> 
			         <a href="<?php echo $_smarty_tpl->tpl_vars['items']->value['typeurl'];?>
"><?php echo $_smarty_tpl->tpl_vars['items']->value['typename'];?>
</a> | 
			    <?php } ?>
			<?php }?>

		</div>
		<div class="hc_btm_info">@2008-2012 <?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['beian']->value;?>
<?php echo stripslashes($_smarty_tpl->tpl_vars['footerdata']->value);?>
</div>
	</div>	
	
	
 </div> 
<div style="position: absolute; top: -1970px; left: -1970px;"> 
</div>  
</body>
</html>





<?php }} ?>