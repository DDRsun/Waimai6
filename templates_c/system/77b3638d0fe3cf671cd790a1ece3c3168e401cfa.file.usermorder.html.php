<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:21:42
         compiled from "E:\phpnow\htdocs\module\order\template\usermorder.html" */ ?>
<?php /*%%SmartyHeaderCode:15313542505f6480973-86610515%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '77b3638d0fe3cf671cd790a1ece3c3168e401cfa' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\order\\template\\usermorder.html',
      1 => 1221705958,
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
  'nocache_hash' => '15313542505f6480973-86610515',
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
  'unifunc' => 'content_542505f69cb3e1_74876021',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_542505f69cb3e1_74876021')) {function content_542505f69cb3e1_74876021($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
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

 <script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/ajaxfileupload.js"> </script>
 

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
							<span class="title_name"><b>订单管理</b></span></p>
						</div><!--title end-->


 <div class="searchTab">
                            <div class="tabTitleWarp clearfix" id="jifen-title">
                               <ul>
										<li class="c"><h3 <?php if ($_smarty_tpl->tpl_vars['actiondo']->value=='order'){?> class="addborder"<?php }?>><a  href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usersorder"),$_smarty_tpl);?>
">餐厅订单</a></h3></li>
										<li class="c"><h3 <?php if ($_smarty_tpl->tpl_vars['actiondo']->value=='ordermarket'){?> class="addborder"<?php }?>><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usermorder"),$_smarty_tpl);?>
" style="color:#fff;">超市订单</a></h3></li>
								 
									</ul>
                            </div>
                            <div class="clear"></div>
         </div>


<div style="background-color:#fff;border:1px solid #ccc;margin-bottom:10px;">
        	
        	<div class="m3-order-list-search">
										 
										<select class="searchstatus" id="orderdatediff" onchange="getOrders();">
											<option value="0" >当月订单</option>
											<option value="1" <?php if ($_smarty_tpl->tpl_vars['orderdatediff']->value==1){?>selected<?php }?>>历史订单</option>
										</select>

											<input type="hidden" id="ordertype" value="0">
										从 	<input type="text" class="searchtime" id="stime" value="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['stime']->value,"%Y-%m-%d");?>
"  onFocus="WdatePicker({isShowClear:false,readOnly:true});"> 至 
											<input type="text" id="etime" class="searchtime" value="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['etime']->value,"%Y-%m-%d");?>
"  onFocus="WdatePicker({isShowClear:false,readOnly:true});"> 
										
										<select class="searchstatus" id="orderstatus" onchange="getOrders();">
											<option value="0">订单状态</option>
											<option value="1" <?php if ($_smarty_tpl->tpl_vars['status']->value==1){?>selected<?php }?>>处理中</option>
											<option value="2" <?php if ($_smarty_tpl->tpl_vars['status']->value==2){?>selected<?php }?>>已完成</option>
										</select>
										
										
										
										
										<input type="image" src="/upload/shop/search.jpg" class="search" onclick="getOrders();">
									 
									</div>
        	
        	
        </div>




						<div class="mr-middle content"><!--mr-middle-->
							<div class="user-order m3-user-order"><!-- m3-user-order -->
								 
								 
 
								<div class="order-list m3-order-list"><!-- m3-order-list -->
									

									<div class="m3-order-list-view" id="m3-order-list"><table class="di_table">	<tbody>
										<tr>
											<th class="m3-olist1">订单号</th>  
											<th class="m3-olist2">订单商家</th>  
											<th class="m3-olist3">下单时间</th>		
											<th class="m3-olist4">订单总额</th>		
											<th class="m3-olist5">订单状态</th>	
											<th class="m3-olist6">完成时间</th>
											<th class="m3-olist7">操作</th>	
										</tr>	
										<?php if (count($_smarty_tpl->tpl_vars['list']->value)>0){?>
										<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
										<tr <?php if ($_smarty_tpl->tpl_vars['items']->value['status']<4&&$_smarty_tpl->tpl_vars['items']->value['posttime']>$_smarty_tpl->tpl_vars['nowtime']->value){?>  class="noborder" <?php }?>>		
											<td><a href="#orderview" onclick="getOrderDetail(<?php echo $_smarty_tpl->tpl_vars['items']->value['id'];?>
);"><?php echo $_smarty_tpl->tpl_vars['items']->value['dno'];?>
</a></td>		
											<td><a <?php if ($_smarty_tpl->tpl_vars['items']->value['shopid']==0){?>href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market"),$_smarty_tpl);?>
"<?php }else{ ?>href="<?php echo FUNC_function(array('type'=>'url','link'=>"/shop/index/id/".((string)$_smarty_tpl->tpl_vars['items']->value['shopid'])),$_smarty_tpl);?>
"<?php }?>  target="_blank" class="apanel"><?php echo $_smarty_tpl->tpl_vars['items']->value['shopname'];?>
</a></td>		
											<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['items']->value['addtime'],"%Y-%m-%d %H:%M");?>
</td>		
											<td><p>￥<?php echo $_smarty_tpl->tpl_vars['items']->value['allcost'];?>
</p><p><?php if ($_smarty_tpl->tpl_vars['items']->value['paytype']=='outpay'){?>就餐支付<?php }else{ ?>在线支付<?php if ($_smarty_tpl->tpl_vars['items']->value['paystatus']==1){?>(已付)<font color=red><?php echo $_smarty_tpl->tpl_vars['backarray']->value[$_smarty_tpl->tpl_vars['items']->value['is_reback']];?>
</font><?php }else{ ?>(<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/site/waitpay/orderid/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
">立即支付</a>)<?php }?><?php }?></p></td>	
											<td class="money"><?php echo $_smarty_tpl->tpl_vars['buyerstatus']->value[$_smarty_tpl->tpl_vars['items']->value['status']];?>
<p><?php if ($_smarty_tpl->tpl_vars['items']->value['is_make']==2){?>商家不制作<?php }?></p></td>		
											<td><?php if ($_smarty_tpl->tpl_vars['items']->value['status']==3){?> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['items']->value['suretime'],"%Y-%m-%d %H:%M");?>
<?php }else{ ?>---<?php }?></td>		
											<td><a href="#orderview" class="apanel" onclick="getOrderDetail(<?php echo $_smarty_tpl->tpl_vars['items']->value['id'];?>
);">查看</a></td>	
										</tr>
										<?php if ($_smarty_tpl->tpl_vars['items']->value['status']<4&&$_smarty_tpl->tpl_vars['items']->value['posttime']>$_smarty_tpl->tpl_vars['nowtime']->value){?>
										<tr class="proleng">
											<td colspan="7">
												<div class="progress" pro="1">
													<p class="bg"></p><p class="pro" style="width:0px;"></p>
											   <ul name="pro_loading">
												<li class="p1  b hover"><span>预定中</span></li>
												<li class="p2  b <?php if ($_smarty_tpl->tpl_vars['items']->value['status']>0){?>hover<?php }?>""><span>已预订</span></li>
												<li class="p3 s ">
													<a href="javascript:void(0)" style="cursor: default;"><span>取餐中</span></a>
													<a href="javascript:void(0)" style="cursor: default;"></a></li>
												<li class="p4 b <?php if ($_smarty_tpl->tpl_vars['items']->value['status']>1){?>hover<?php }?>"><span>配送中</span></li>
												<li class="p5 s"><a href="javascript:void(0)" style="cursor: default;"><span>送餐中</span></a></li>
												<li class="p6 b"><span>已到达</span></li>
											</ul>
										   </div>
										 </td>
										</tr>	
										<?php }?>
										<?php } ?>
										<?php }else{ ?>
										<tr>
											<td colspan="7" align="center">
												 暂无数据
											</td>
										</tr>
									  <?php }?>
										</tbody></table>
								 <div class="clear"></div>
								 	   <div class="show_page"> 
								 	   	  <ul>
								 	   	    <?php echo $_smarty_tpl->tpl_vars['pagecontent']->value;?>

								 	     	</ul>
								 	   </div>
								 		<div class="clear">	</div>
								</div>
								</div>
								
								
								
								
								
								
								
								
								
							</div>
						</div>
						
						
						 <div style="background-color:#fff;border:1px solid #ccc;margin-top:10px;display:none;" id="tempordershow">
		 	<a name="orderview" id="orderview"></a>
								<div class="m3-order-detail" id="m3-order-detail">
									
									
								</div>
		 </div>
						
						
						
						
						
					</div>
			</div>
<script id="trgoodlist" type="text/html"> 
<div class="m3-order-detail-left">	
										<ul>		
											<li><span>订单编号：</span><^%=order.dno%^></li>		
											<li><span>下单时间：</span><^%=order.addtime%^></li>		
											<li><span>要求时间：</span><^%=order.posttime%^></li>		
											<li><span>完成时间：</span><^%=order.suretime%^></li>		
											<li><span>配送方式：</span><^%if(order.pstype==1){%^>店铺自送<^%}else{%^>平台配送<^%}%^></li>		
											<li><span>支付方式：</span><^%if(order.paytype=='outpay'){%^>餐到付款<^%}else{%^>在线支付<^%if(order.paystatus=='1'){%^>(已付)<^%}else{%^> (未付) <^%}%^><^%}%^>
												<^%if(order.is_reback=='1'){%^>退款中..<^%}else{%^><^%if(order.is_reback=='2'){%^>退款成功<^%}else{%^><^%}%^><^%}%^>
											</li>		
											<li><span>客户姓名：</span><^%=order.buyername%^></li>		
											<li><span>手机号码：</span><^%=order.buyerphone%^></li>		
											<li><span>配送地址：</span><^%=order.buyeraddress%^></li>		
											<li><span>订单备注：</span><^%=order.excontent%^></li>	
										</ul>	
										<div class="clear"></div>
									</div>
									<div class="m3-order-detail-right">	
										<table>		
											<tbody>
												<tr>			
													<th class="m3-odetail1 ">订单商家：<^%=order.shopname%^></th>			
													<th class="m3-odetail2">订单状态：<^%=order.status%^></th>		
												</tr>	
											</tbody>
										</table>	
										<div class="menulist">	
										
											<table>		
												<tbody>
													<^%for(i = 0; i < orderdet.length; i ++) {%^>
													<tr>			
														<td class="m3-odetail1"><^%=orderdet[i].goodsname%^></td>			
														<td class="m3-odetail2">￥<^%=orderdet[i].goodscost%^>*<^%=orderdet[i].goodscount%^></td>			
														<td class="m3-odetail3"><span class="money">￥<^%=orderdet[i].sum%^></span></td>		
													</tr>		
												  <^%}%^> 
					  
												</tbody>
											</table>	
										</div>	
										<table>		
											<tbody>
												<tr>			
													<th class="m3-odetail1">菜单总价：￥<^%=order.goodscost%^></th>			
													<th class="m3-odetail2">配送费：￥<^%=order.pscost%^></th>			
													<th class="m3-odetail3">打包费：￥<^%=order.bagcost%^></th>		
												</tr>	
												<tr>			
													<th class="m3-odetail1">积分抵扣：<^%=order.scoredown%^>个</th>			
													<th class="m3-odetail2">优惠金：<^%=order.yhjcost%^>(券)+<^%=order.cxcost%^>(促销)</th>			
													<th class="m3-odetail3">订单应付：<span class="money">￥<^%=order.allcost%^></span></th>		
												</tr>	
											</tbody>
										</table>
									</div>
							<div class="clear"></div>
</script>
<script type="text/javascript">
	$(document).ready(function(){ 
		for(var i=0;i< $('.proleng').length;i++){
			var whatobj = $('.proleng').eq(i).find('.hover').length;
			whatobj = Number(whatobj)-1;
			var myobj =  $('.proleng').eq(i).find('.hover').eq(whatobj);
			//计算位置
			var cobj = $('.proleng').eq(i).find('.hover').eq(0);
				var menupos =$(myobj).offset();
        var leftend = menupos.left;
        var startpost = $(cobj).offset();
        var leftstart = startpost.left;
        var widths = Number(leftend)-Number(leftstart);
        $('.proleng').eq(i).find('.pro').eq(0).css('width',widths+'px'); 
		} 
	});
	function addAddr()
	{
		$('#newAddr_1').show();
	}
	function getOrderDetail(orderid)
	{  
	        var info = {'orderid':orderid};
	      	var backmes =   ajaxback('<?php echo FUNC_function(array('type'=>'url','link'=>"/order/userorderdet/datatype/json"),$_smarty_tpl);?>
',info); 
	      	if(backmes.flag == false)
	      	{
	      		// $backinfo['order'] = $orderinfo;
		        //$backinfo['orderdet'] = $orderdetinfo;
		        $('#tempordershow').show();
	      		 var htmls = template.render('trgoodlist', {order:backmes.content.order,orderdet:backmes.content.orderdet});
	      	   $('#m3-order-detail').html(htmls); 
	      	}else{
	      		diaerror(backmes.content); 
	      	}  
	}
	function getOrders(){
		//
		var mlinks = '<?php echo FUNC_function(array('type'=>'url','link'=>"/order/usermorder/status/@status@/stime/@stime@/etime/@etime@/orderdatediff/@orderdatediff@"),$_smarty_tpl);?>
';
		mlinks = mlinks.replace('@orderdatediff@', $("#orderdatediff").find("option:selected").val());
		mlinks = mlinks.replace('@status@', $('#orderstatus').find("option:selected").val());
		mlinks = mlinks.replace('@stime@', $('#stime').val());
		mlinks = mlinks.replace('@etime@', $('#etime').val());
	 window.location.href=mlinks;
	}
	function unorder(orderid)
	{
		if(confirm('确定取消订单吗？')){
		   myajax('<?php echo FUNC_function(array('type'=>'url','link'=>"/membercenter/unorder"),$_smarty_tpl);?>
',{'orderid':orderid});
		}
	}
	function delorder(orderid)
	{
		if(confirm('确定删除订单吗？')){
		   myajax('<?php echo FUNC_function(array('type'=>'url','link'=>"/membercenter/delorder"),$_smarty_tpl);?>
',{'orderid':orderid});
		}
	}
	function compelte(orderid)
	{
		window.location.href= siteurl+'/index.php?controller=membercenter&action=pingorder&orderid='+orderid;
	}  
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