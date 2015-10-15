<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:22:20
         compiled from "E:\phpnow\htdocs\module\member\template\edituser.html" */ ?>
<?php /*%%SmartyHeaderCode:114295425061cc509e8-07358679%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '24456805324d7fe6e95a536d97a5fbc068cd3005' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\member\\template\\edituser.html',
      1 => 1407570948,
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
  'nocache_hash' => '114295425061cc509e8-07358679',
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
  'unifunc' => 'content_5425061d14d749_24931554',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5425061d14d749_24931554')) {function content_5425061d14d749_24931554($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
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
							<span class="title_name"><b>基本资料</b></span></p>
						</div><!--title end-->

						<div class="mr-middle content"><!--mr-middle-->
							<div class="mr-middle-left"><!--mr-middle-left-->
								<div class="field mgt50">
									<label>ID号</label>
									<input type="text" id="nickname" name="nickname" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['member']->value['uid'];?>
">
									<label class="tip"></label>
								</div>

								<div class="field">
									<label>邮箱</label>
									<input type="text" id="email" name="email" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['member']->value['email'];?>
" data="<?php echo $_smarty_tpl->tpl_vars['member']->value['email'];?>
"> <span id="emailaction"> <?php if (empty($_smarty_tpl->tpl_vars['member']->value['email'])){?><<a href="javascript:void(0);" class="apanel" onclick="mody('email')">修改</a><?php }?></span>
									<label class="tip" id="emaildiv" style="width:100px;"></label>
								</div>

								<div class="field">
									<label>手机</label>
									<input type="text" id="mobile" name="mobile" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['member']->value['phone'];?>
"> <span id="mobileaction"> <?php if (empty($_smarty_tpl->tpl_vars['member']->value['phone'])){?><a href="javascript:void(0);" id="mdphone" onclick="mody('mobile')" class="apanel">绑定手机</a><?php }?></span>
									<label class="tip" id="mobilediv" style="width:100px;"></label> 	
								</div>


								<div class="field">
									<label>昵称</label>
									<input type="text" id="username" name="username" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
" data="<?php echo $_smarty_tpl->tpl_vars['member']->value['username'];?>
">	<span id="usernameaction"> <a href="javascript:void(0);" id="usernamea" class="apanel" onclick="mody('username')">修改</a></span>						
									<label class="tip" id="usernamediv" style="width:100px;"></label>
								</div> 
								<div class="field">
									<p style="text-indent:165px;">
										<a href="javascript:void(0)" id="pwdmx" onclick="showLayer('updatepwd');" class="apanel">修改密码</a>
									</p>
								</div>
							</div><!--mr-middle-left end-->

							<div class="mr-middle-right"><!--mr-middle-right-->
								<div class="img" id="member-img"><img id="headpic" src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['member']->value['logo'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['userlogo']->value : $tmp);?>
" onclick="showLayer('div-headpicUpload')" onmouseover="this.style.cursor='hand'"></div>
								<p>&nbsp;</p>
								<p><a href="javascript:showLayer('div-headpicUpload');" class="apanel" id="channgeheadimg">修改头像</a></p>

								<div id="div-headpicUpload" style="display:none;margin-left:-175px"><!--div-headpicUpload-->
		               <form id="form1" name="form1" method="post" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/other/userupload/type/userlogo"),$_smarty_tpl);?>
" enctype="multipart/form-data" target="ifr-headpic-upload" onsubmit="return checkImagetype('1');">    
		               	<div class=""> 
		               		<input name="head" type="file" id="imgFile" style="margin-left:15px;" name="imgFile" onchange="$('#input1').val($(this).val())">
		               		<input id="submitImg" type="button" value="上传" class="ss_sc">   
		               	</div> 
		               	<div class="ok">
		               		<p>仅支持<font color="red">GIF,JPG,PNG</font>格式
		               		<a href="javascript:void(0)" id="close" onclick="closeLayer('div-headpicUpload')">关闭</a></p><p>
		               	</p></div>
		               </form> 
									<br>
								</div><!--div-headpicUpload end-->

							</div><!--mr-middle-right end-->
							<div class="clear"></div>

							<div class="modifymobile" id="updatemobile" style="display:none"><!--modifymobile-->
								<div class="regtitle">
									<h1>绑定手机</h1> <span></span>
								</div>

								<div class="field">
									<label>手机</label>
									<input type="text" id="phone" onblur="verifyPhone(6)">
									<input type="button" class="btn_mobileCode" value="获取手机验证码">
									<label class="tip" id="phonediv" style="margin-left:160px;"></label>
								</div>

								<div class="field">
									<label>手机验证码</label>
									<input type="text" name="mobileCode" id="mobileCode">
									<label class="tip" id="mobileCodediv" style="margin-left:160px;"></label>
								</div>

								<div class="field" id="hebin" style="display:none">
									<label></label>
									<label class="tip" id="isBinddiv" style="margin-left:0;"></label><br>
									<label></label>
									<input type="radio" name="isHebin" class="check" value="1"> 合并
									<input type="radio" name="isHebin" class="check" value="0"> 不合并，另一账户解除绑定<br>
									<label class="tip" id="isHebindiv" style="margin-left:160px;"></label>
								</div>

								<div class="field">
									<p style="text-indent:163px;">&nbsp;<input type="button" class="weight" id="postBindMobile" value="提交"></p>
								</div>
							</div><!--modifymobile end-->

							<div class="modifypwd" id="updatepwd" style="display:none"><!--modifypwd-->
								<div class="regtitle">
									<h1>修改密码</h1> <span></span>
								</div>

								<div class="field">
									<label>原密码</label>
									<input type="password" id="oldpwd">
									<label class="tip"></label>
								</div>

								<div class="field">
									<label>新密码</label>
									<input onblur="blurPro(2)" onfocus="focusPro(2)" type="password" name="pwdinput" id="pwdinput">
									<label class="tip" id="pwddiv"></label>
								</div>

								<div class="field">
									<label>确认密码</label>
									<input onblur="blurPro(3)" onfocus="focusPro(3)" type="password" name="pwd2input" id="pwd2input">
									<label class="tip" id="pwd2div"></label>
								</div>

								<div class="field">
									<p style="text-indent:163px;">&nbsp;<input type="button" class="weight" onclick="postBaseInfo()" value="提交"></p>
								</div>
							</div><!--modifypwd end-->
						</div><!--mr-middle end-->
					</div><!--mc-right end--> 
				</div>
			</div>
<script type="text/javascript">
	$('.hc_login_btn_div').click(function(){
		subform('<?php echo FUNC_function(array('type'=>'url','link'=>"/member/loginout"),$_smarty_tpl);?>
',$('#loginForm'));
	})
	$('#submitImg').click(function(){
		ajaxFileUpload();
	});
</script>
<script type="text/javascript">
	var showaction = '<?php echo $_smarty_tpl->tpl_vars['showaction']->value;?>
';
	$(document).ready(function(){ 
		if($('#'+showaction).html() != undefined){
			$('#'+showaction).trigger('click');
		}
	});
	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:'<?php echo FUNC_function(array('type'=>'url','link'=>"/other/userupload/type/userlogo/datatype/json"),$_smarty_tpl);?>
',
				secureuri:false,
				fileElementId:'imgFile',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error == false)
						{ 
							$('#headpic').attr('src',data.msg); 
							$('#div-headpicUpload').hide();
						}else
						{
							alert(data.msg);
						}
					}else{
					  alert(data);
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}
	function closeLayer(obj){
	$('#'+obj).hide();
	}
	function showLayer(obj){
		$('#'+obj).show();
	}
	function mody(obj){
		$('#'+obj).attr('disabled',false);
		$('#'+obj+'action').html('<input type="button" class="weight" onclick="update(\''+obj+'\')" value="保存">');
	 
	}
	function update(obj){
		$('#'+obj).attr('data');
		$.ajax({
       type: 'POST',
       async:false,
       url: '<?php echo FUNC_function(array('type'=>'url','link'=>"/member/saveuser/datatype/json"),$_smarty_tpl);?>
',
       data: {'controlname':obj,obj:$('#'+obj).val()},
      dataType: 'json',success: function(content) { 
      	if(content.error == false)
      	{
      		$('#'+obj).attr('disabled',true);
		      $('#'+obj+'action').html('<a href="javascript:void(0);" class="apanel" onclick="mody(\''+obj+'\')">修改</a>');
      	}else{
      	   alert(content.msg);
      	} 
		  },
      error: function(content) { 
      	alert(content);
	    }
    });   
		
	}
	function postBaseInfo(){
		var info = {'controlname':'pwd','pwd':$('#oldpwd').val(),'newpwd':$('#pwdinput').val(),'newpwd2':$('#pwd2input').val()};
		var url = '<?php echo FUNC_function(array('type'=>'url','link'=>"/member/saveuser/random/@random@/datatype/json"),$_smarty_tpl);?>
';
		var message =  ajaxback(url,info);
		if(message.flag== false){
			location.reload();  
		}else{
		  alert(message.content);
		}
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