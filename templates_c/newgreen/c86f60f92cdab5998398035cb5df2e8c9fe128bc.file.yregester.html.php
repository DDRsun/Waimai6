<?php /* Smarty version Smarty-3.1.10, created on 2001-09-26 11:34:21
         compiled from "E:\phpnow\htdocs\templates\newgreen\member\yregester.html" */ ?>
<?php /*%%SmartyHeaderCode:273253bb14cbdc9a473-94517231%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c86f60f92cdab5998398035cb5df2e8c9fe128bc' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\member\\yregester.html',
      1 => 1409540270,
      2 => 'file',
    ),
    '9237284e2d14756fd32e5ee5ead6f049d52f3b6e' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\public\\site.html',
      1 => 1407569152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '273253bb14cbdc9a473-94517231',
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
  'unifunc' => 'content_3bb14cbe040217_52660658',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_3bb14cbe040217_52660658')) {function content_3bb14cbe040217_52660658($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 用户注册-<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
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
/public/css/memberlogin.css">
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
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/regestercode.js" type="text/javascript" language="javascript"></script> 

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

<div class="mmbg" style="background:url(../upload/images/regesterbk.jpg) no-repeat;"></div> 

 
 <div class="new_regester">
   <div class="regester_top">
   </div>
   <div class="regester_middle">
   	  <div class="rm_li">
   	     <ul>
   	     	    <li style="cursor: pointer;"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/regester"),$_smarty_tpl);?>
">手机号注册</a> 	</li>
   	     	     <li  class="hoveron" style="cursor: pointer;"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/yregester"),$_smarty_tpl);?>
">邮箱注册</a> 	</li> 
   	     	</ul>
   	    
   	     	
   	  </div>
   	  <div class="rm_rt">已有帐号？<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/login"),$_smarty_tpl);?>
">马上登陆</a>|<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/findpwd"),$_smarty_tpl);?>
">找回密码</a></div>
   </div>
   <div class="regester_bottomss">
   	  <div class="rbm_l_p">
   	  	<form id="regForm" method="post" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/saveregester/datatype/json"),$_smarty_tpl);?>
">
   	  	   <div class="mem_regester_inputdiv">
   	  	   	  <label><font color=red>*</font>邮箱：</label>
   	  	   	  <input type="text" name="email" id="email" style="width:200px;">
   	  	   	  <span id="email_tips"></span>
   	  	   </div>
   	  	   
   	  	    <div class="mem_regester_inputdiv"  id="showgetcode" style="display:none;">
   	  	   	  <label>&nbsp;</label>
   	  	   	  <input type=button value="发送注册验证码到邮箱" name="inputgetcheck" onclick="clickyanzheng();" class="inputgetcheck" id="dosendbtn" time="0"> 
   	  	   </div>
   	  	   
   	  	    <div class="mem_regester_inputdiv" id="showinputcode" style="display:none;">
   	  	   	  <label><font color=red>*</font>验证码：</label>
   	  	   	  <input type="text" name="phoneyan" id="phoneyan" style="width:100px;">
   	  	   	  <span id="phoneyan_tips"></span>
   	  	   </div>
   	  	   
   	  	   
   	  	   <div class="mem_regester_inputdiv">
   	  	   	  <label><font color=red>*</font>帐号：</label>
   	  	   	  <input type="text" name="tname" id="tname" style="width:200px;">
   	  	   	  <span id="tname_tips"></span>
   	  	   </div>
   	  	   
   	  	    <div class="mem_regester_inputdiv">
   	  	   	  <label><font color=red>*</font>登陆密码：</label>
   	  	   	  <input type="password" name="pwd" id="pwd" style="width:200px;">
   	  	   	  <span id="pwd_tips"></span>
   	  	   </div>
   	  	   
   	  	   
   	  	    <div class="mem_regester_inputdiv">
   	  	   	  <label><font color=red>*</font>确认密码：</label>
   	  	   	  <input type="password" name="pwd2" id="pwd2" style="width:200px;">
   	  	   	  <span id="pwd2_tips"></span>
   	  	   </div>
   	  	   <?php if ($_smarty_tpl->tpl_vars['allowedcode']->value==1){?>
   	  	    <div class="mem_regester_inputdiv" id="imgcode">
   	  	   	  <label><font color=red>*</font>验证码：</label>
   	  	   	  <input type="text"  name="Captcha" id="Captcha" style="width:100px;"><img src="<?php echo FUNC_function(array('type'=>'url','link'=>"/site/getCaptcha"),$_smarty_tpl);?>
" id="captchaimg" style="height:30px;"><a href="javascript:freshcode();" >刷新图片</a>
   	  	   	  <span id="Captcha_tips"></span>
   	  	   </div> 
   	  	   <?php }?>
   	  	   <div class="mem_regester_inputdiv">
   	  	   	  <label>&nbsp;</label>
   	  	      	<input name="reg[agree]" type="checkbox" checked="" disabled> 我接受<a target="_blank" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/site/single/show/xieyi"),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
服务条款和协议</a>
   	  	   </div>
   	  	   
   	  	    <div class="mem_regester_inputdiv">
   	  	   	  <label>&nbsp;</label>
   	  	      <div class="button_new_regester login-button login-button" style="cursor: pointer;">同意协议并注册</div>
   	  	   </div>
   	  	</form>
   	 
   	  	
   	  	
   	  	
   	  </div>
   	  
   	  
   </div>
    <div class="right_btm_re">
   	   	
   	   	  <div class="apilogin">  
   	   	  	  <?php echo smarty_function_load_data(array('assign'=>"apiloginlist",'table'=>"otherlogin",'fileds'=>"*",'limit'=>"10"),$_smarty_tpl);?>
 
					    <?php if (count($_smarty_tpl->tpl_vars['apiloginlist']->value['list'])>0){?>
				 
						   <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['apiloginlist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
						    <div  style="padding-top:20px;">    <a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/linktest/logintype/".((string)$_smarty_tpl->tpl_vars['items']->value['loginname'])),$_smarty_tpl);?>
" target="_blank"><img src="/upload/images/<?php echo $_smarty_tpl->tpl_vars['items']->value['loginname'];?>
.jpg"></a> </div> 
						   <?php } ?>
				 
					    <?php }?>
   	   	  	
   	     	</div>
   	     	<div class="apibottom">
   	     		已有帐户<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/login"),$_smarty_tpl);?>
">立即登陆</a>
   	     	</div>
   	  </div>
  
 </div>
 

 
 
 
 	 
 	<script>
   	     		 $('.rm_li li').hover(function(){
   	     		 	$(this).addClass('hover').siblings().removeClass('hover');
 	           });
   	     		</script>
<script type="text/javascript">
var regestercode = '<?php echo $_smarty_tpl->tpl_vars['regestercode']->value;?>
';
$('.login-button').click(function(){ 
	subform('<?php echo FUNC_function(array('type'=>'url','link'=>"/member/base"),$_smarty_tpl);?>
',$('#regForm'));
})
$(".hc_login_input").change(function(j){
		var v=$(this).val();
		var p=$(this).attr("name"); 
		switch(p){
		case "email":
			var patrn= /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!patrn.exec(v)){ 
				 alert('邮箱格式错误'); 
			}else{ 
				$.ajax({
				type: "POST",
				url: "<?php echo FUNC_function(array('type'=>'url','link'=>"/member/checkemail/datatype/json"),$_smarty_tpl);?>
",
				dataType: "json",
				data: "uname=" + v,
				success: function(msg){     
					if(msg.error ==true){ 
						$('[name="email"]').val('');
						alert(msg.msg);
					 } 
					}
				});			
			}
		break;
		case "tname":
		 	var patrn=/[u4e00-u9fa5]/;  
			if(v == '' ||v == null){
			  alert('用户名不能为空'); 
			}else{
				$.ajax({
				type: "POST",
				url: "<?php echo FUNC_function(array('type'=>'url','link'=>"/member/checkname/datatype/json"),$_smarty_tpl);?>
",
				dataType: "json",
				data: "uname=" + v,
				success: function(msg){ 
					if(msg.error==true){ 
						$('[name="tname"]').val('');
						alert(msg.msg);
					  //alert('此用户名已注册过'); 
					 }
					}
				});	
			}
		break; 
		case "pwd":
			var patrn=/(.){4,16}/;  
			if(!patrn.exec(v)){
				 alert('密码是4-16位的字母、数字,区分大小写');  
			 } 
		break;
		}
	});

</script> 
 

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