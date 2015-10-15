<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:16:21
         compiled from "E:\phpnow\htdocs\templates\newgreen\ask\index.html" */ ?>
<?php /*%%SmartyHeaderCode:4020542504b512aa58-32000500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3805cbade0117e867bb9fadfb1604976a75793a5' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\ask\\index.html',
      1 => 1408866036,
      2 => 'file',
    ),
    '9237284e2d14756fd32e5ee5ead6f049d52f3b6e' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\public\\site.html',
      1 => 1407569152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4020542504b512aa58-32000500',
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
  'unifunc' => 'content_542504b5473265_84877600',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_542504b5473265_84877600')) {function content_542504b5473265_84877600($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
if (!is_callable('smarty_modifier_truncate')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 在线留言-<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
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
/public/css/ask.css"> 
 
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

<div class="mmbg" <?php if (!empty($_smarty_tpl->tpl_vars['sitebk']->value)){?>style="background:url(<?php echo $_smarty_tpl->tpl_vars['sitebk']->value;?>
) repeat;"<?php }?>></div> 


<div style="height:15px;"></div>
<div class="Announce hc_content"  ><?php echo $_smarty_tpl->tpl_vars['notice']->value;?>
</div> 
<div class="MsgFrm">
	<div class="MsgLeftFrm">
		 
		<div class="NewJoinFrm">
			<div class="SubTitle">最新加入</div>
			<div class="JoinList">
				<ul style="float:left;"> 
				 <?php echo smarty_function_load_data(array('assign'=>"userlist",'table'=>"member",'limit'=>"28",'orderby'=>"uid desc"),$_smarty_tpl);?>
 
					<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['userlist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
					<li>
						<a class="JoinHead" href="#"><img src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['items']->value['logo'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['userlogo']->value : $tmp);?>
"></a>
						<div class="JoinName" ><a href="#"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['items']->value['username'],5,"...",true);?>
</a></div>
					</li>
					<?php } ?>
				</ul>
				<div style="clear:both;"></div>
			</div>
		</div>
		 
	</div>
	<div class="MsgRightFrm">
		<div class="MsgTitle" style="background:url(<?php echo $_smarty_tpl->tpl_vars['shoplogo']->value;?>
) no-repeat 10px 10px #fff;">弹指间，美食现！ 给我们留下宝贵意见吧</div>
		<div class="MsgLeaveForm">
			<div class="MsgLeaveHead"><img src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['member']->value['logo'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['userlogo']->value : $tmp);?>
"   width="50" height="50"></div>
			<div class="MsgForm">
                <form action="" method="post" id="saveform" name="saveform">
				<textarea datatype="*1-200" nullmsg="请输入留言信息！" errormsg="留言信息最多200个字符！" class="MsgTxt" name="content" id="liuyan_textarea"></textarea>
				<a href="javascript:fabu();" class="BtnSubMsg"></a>
				<div style="clear:both;"></div>
                </form>
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- MsgList start -->
		<div class="MsgList">
			<ul id="MList" scrollpagination="enabled"> 
			 <?php echo smarty_function_load_data(array('assign'=>"list",'table'=>"ask",'showpage'=>"true",'where'=>"typeid = 5",'orderby'=>"id desc"),$_smarty_tpl);?>
 
			<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
			<?php echo smarty_function_load_data(array('assign'=>"userinfo",'table'=>"member",'type'=>"one",'where'=>"uid='".((string)$_smarty_tpl->tpl_vars['items']->value['uid'])."'",'fileds'=>"username"),$_smarty_tpl);?>
 
				<li id="M_599" u="690" rel="loaded">
					<div class="UserHead"><a href="#"><img src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['userinfo']->value['logo'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['userlogo']->value : $tmp);?>
"></a></div>
					<div class="MsgInfo">
						<div class="MITitle">
                        <a href="javascript:;" <a="" class="Name" id="N_599"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['userinfo']->value['username'])===null||$tmp==='' ? "游客" : $tmp);?>
</a> <span class="Levels l03" title="经验值：401点"></span> — <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['items']->value['addtime'],"%Y-%m-%d %H:%M:%S");?>

                        </div>
						<div class="MILeave" id="C_599"><?php echo $_smarty_tpl->tpl_vars['items']->value['content'];?>
</div>
                        <div id="MRList_599"> 
                        	<?php if (!empty($_smarty_tpl->tpl_vars['items']->value['replycontent'])){?>
                        <div id="MR_65" class="MsgReply">
                        	<a href="#" class="MsgCustomerService">
                              (<b style="color:Red;"><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
客服</b>)</a>发表于: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['items']->value['replytime'],"%Y-%m-%d %H:%M:%S");?>

                            <br>
                            <?php echo $_smarty_tpl->tpl_vars['items']->value['replycontent'];?>
<span class="MsgReplyIcon"></span>
                        </div>
                         <?php }?> 
                        </div>
					</div>
           <div style="clear:both;"></div>
				</li>
				<?php } ?> 
				
				 </ul>
		</div>
        <div class="MoreMsg">
        	<div class="show_page">
        		 <ul>
             <?php echo $_smarty_tpl->tpl_vars['list']->value['pagecontent'];?>

           </ul>
          </div>
        </div>
       <input type="hidden" name="pageshow" id="pageshow" value="1" data="1">
		<!-- MsgList end -->
	</div>
	<div style="clear:both;"></div>
</div>



<script>
	function fabu()
	{
		var shopid = $('#nowshopid').val();
		if(shopid == undefined)
		{
			shopid = 0; 
		}
		if($('#liuyan_textarea').val() == '')
		{
			diaerror('留言内容不能为空'); 
				 
		}else{
		var info = {'content':$('#liuyan_textarea').val(),'shopid':'0','Captcha':'','type':'5'};
		var backmes =   ajaxback('<?php echo FUNC_function(array('type'=>'url','link'=>"/ask/saveask/datatype/json"),$_smarty_tpl);?>
',info); 
		if(backmes.flag == false)
		{
			 $('#liuyan_textarea').val('');
		   location.reload();  
		}else{
			diaerror(backmes.content); 
			 
		} 
	 }
	}
  

    

 
</script>
 <script>
$(function(){ 
 $('.show_page a').wrap('<li></li>');
 $('.show_page').find('.current').eq(0).parent().css({'background':'#f60'}); 
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