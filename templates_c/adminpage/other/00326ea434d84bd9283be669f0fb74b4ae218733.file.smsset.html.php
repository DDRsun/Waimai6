<?php /* Smarty version Smarty-3.1.10, created on 2001-09-26 11:28:34
         compiled from "E:\phpnow\htdocs\module\other\adminpage\smsset.html" */ ?>
<?php /*%%SmartyHeaderCode:289313bb14b626d45a2-48402738%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00326ea434d84bd9283be669f0fb74b4ae218733' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\other\\adminpage\\smsset.html',
      1 => 1408849374,
      2 => 'file',
    ),
    'a226ecdebbc91363df26407aef19d81c3b3bd604' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\adminpage\\public\\admin.html',
      1 => 1411697363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '289313bb14b626d45a2-48402738',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tempdir' => 0,
    'siteurl' => 0,
    'is_static' => 0,
    'admininfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.10',
  'unifunc' => 'content_3bb14b6283dad7_32106448',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_3bb14b6283dad7_32106448')) {function content_3bb14b6283dad7_32106448($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
?> <html xmlns="http://www.w3.org/1999/xhtml"><head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<meta http-equiv="Content-Language" content="zh-CN"> 
<meta content="all" name="robots"> 
<meta name="description" content=""> 
<meta content="" name="keywords"> 
<title>后台管理中心 </title>  
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/admin1.css"> 
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/jquery.js" type="text/javascript" language="javascript"></script>
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/public.js" type="text/javascript" language="javascript"></script>
 <script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/artdialog/artDialog.js?skin=blue"></script>
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/template.min.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/artdialog/plugins/iframeTools.js"></script>
 
<script>
	var menu = null;
	var siteurl = "<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
";
	var is_static ="<?php echo $_smarty_tpl->tpl_vars['is_static']->value;?>
";
	if(screen.width > 1359){
		
		$('.newtop').css('width',screen.width);
		$('.newmain').css('width',screen.width);
		$('.newfoot').css('width',screen.width);
	}  
</script> 
</head> 
<body> 
<div id="cat_zhe" class="cart_zhe" style="display:none;"></div>
<div id="cat_tj" class="cart_out_cat" style="display:none;"> 数据提交中..</div>
<div class="newtop">
	  <div class="newadddiv">
	  <div class="newlogo">
	  	  <div class="imglogo"><a href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/images/admin/logo.png"> </a></div>
	  </div>
	  <div class="newtopnav">
	  	 <ul>
	  	  <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tempdir']->value)."/public/admin_module.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 
	  	 	<ul>
	  	
	  </div>
	</div>
</div> 
<div style="clear:both;"></div>
<div class="newmain">
	<!-- 提示导航-->
   <div class="top_nav">
	    <div class="nav2 datainfo">
	    	<?php echo smarty_modifier_date_format(time(),"%Y-%m-%d");?>
  
	    </div>
	    <div class="nav2 positioninfo" id="positionname2">
	    	网站首页
	    </div>
	    <div class="nav2 orderinfo">
	    	您有今天有0 个订单待审核
	    </div>
   </div>
   <!-- 主内容区-->
   <div class="newmain_all">
   	 <!-- 主内左区-->
   	 <div class="left_content">
   	 	   <!-- 主内左区用户信息-->
   	 	   <div class="userinfo">
   	 	   	   <div class="user_area">
   	 	   	   	      <div class="user_name">
   	 	   	   	      	<?php echo $_smarty_tpl->tpl_vars['admininfo']->value['username'];?>

   	 	   	   	      </div>
   	 	   	   	      <div class="content_url">
   	 	   	   	      	 <a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/adminloginout"),$_smarty_tpl);?>
" class="out">退出</a> 
   	 	   	   	      	 <a onClick="modifypwd();" href="#">修改密码</a>
   	 	   	   	      </div>
   	 	   	  </div>
   	 	   	  <div class="now_name" id="nowactioninfo">
   	 	   	    	网站首页
   	 	   	    </div>
   	 	   </div>
   	 	   <!-- 主内左区导航条-->
   	 	    <div class="left_nav">
   	 	    	  <ul> 
   	 	    	  	
   	 	    	  	
   	 	    	  	 <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tempdir']->value)."/public/admin_menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

   	 	    	 
   	 	    	     <li style="text-align:center;"><a href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
" style="color:#0076cf;" target="_blank">返回网站首页</a></li>
   	 	    	  </ul>
   	 	    </div>
   	 	    <div class="left_navend">
   	 	    </div>
   	 	    <!-- 主内左区天气预报-->
   	 	    <div class="weather" id="weatherinfo" style="display:none;">
   	 	    	 <div class="todayweacher">
   	 	    	      <div class="weacher_left">
   	 	    	      	 <div id="wtoday_img"> </div>
   	 	    	      	 <div><span id="wcity" style="padding-right:5px;"></span><span id="wqihou"></span></div>
   	 	    	      </div>
   	 	    	      <div class="weacher_right" id="wwendu">
   	 	    	      	 
   	 	    	      </div>	 
   	 	    	 </div>
   	 	    	 <div style="clear:both;">
   	 	    	 	   <div class="tom">
   	 	    	 	   	    <div id="tom_1" class="tom_img"></div>
   	 	    	 	   	    <div class="tom_wendu" id="tom_day"></div>
   	 	    	 	   	    <div class="tom_wendu" id="tom_wendu"></div>
   	 	    	 	   	
   	 	    	 	   	</div>
   	 	    	 	   <div class="tom">
   	 	    	 	   	    <div id="tom_tom" class="tom_img"></div>
   	 	    	 	   	    <div class="tom_wendu" id="tom_tday"></div>
   	 	    	 	   	    <div class="tom_wendu" id="tom_twendu"></div>
   	 	    	 	   	</div>
   	 	    	 </div>
   	 	    </div>
   	 	     
   	  </div>
   	  
 
 
  
 
 
 <div class="right_content">
	<div class="show_content_m">
   	        	 <div class="show_content_m_ti">
   	        	 	   <div class="showtop_t" id="positionname">短信设置</div>
   	        	 </div>
   	        	 <div class="show_content_m_t2">
   	        	 	
   	        	 	

       <div style="width:auto;overflow-x:hidden;overflow-y:auto;">

          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/other/module/smssetsave/datatype/json"),$_smarty_tpl);?>
" onsubmit="return subform('',this);">
              <div>
              	<table border="0" cellspacing="2"   class="list" name="table" id="table1" width="100%">
                  <tbody>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" width="20%">选择短信发送类型</td>
                      <td>
                      	<select name="smstype" id="smstype" onchange="doinfo();">
                      	    <option value="1">亿美短信接口 </option>
                      		   <option value="2"<?php if ($_smarty_tpl->tpl_vars['smstype']->value==2){?> selected<?php }?>>sms-10086.cn </option>
                       </select>
                       </td>
                    </tr>
                  </tbody>
                </table>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table3" width="100%">
                  <tbody>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">账号</td>
                      <td><input type="text" name="sms86ac" id="sms86ac" value="<?php echo $_smarty_tpl->tpl_vars['sms86ac']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">密码</td>
                      <td><input type="password" name="sms86pd" id="sms86pd" value="<?php echo $_smarty_tpl->tpl_vars['sms86pd']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>

                  </tbody>
                </table>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table2" width="100%">
                  <tbody>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">帐号</td>
                      <td><input type="text" name="smacount" id="smacount" value="<?php echo $_smarty_tpl->tpl_vars['smacount']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">密码</td>
                      <td><input type="password" name="smpassword" id="smpassword" value="<?php echo $_smarty_tpl->tpl_vars['smpassword']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">KEY</td>
                      <td><input type="password" name="smkey" id="smkey" value="<?php echo $_smarty_tpl->tpl_vars['smkey']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>
                    <?php if (!empty($_smarty_tpl->tpl_vars['smeName']->value)){?>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" ></td>
                      <td>&nbsp;<a  onclick="return remind(this);" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/admin/unmobile"),$_smarty_tpl);?>
">取消此卡</a></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" ><a href="#" onclick="getfee();">查看余额</a></td>
                      <td id="showacountdet">&nbsp;</td>
                    </tr>

                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">&nbsp;</td>
                      <td><a href="#" onclick="topay();">账号充值</a></td>
                    </tr>
                    <?php }?>
                    <?php if (empty($_smarty_tpl->tpl_vars['smeName']->value)){?>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">&nbsp;</td>
                      <td>以下信息首次注册需要填写</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">公司名称</td>
                      <td><input type="text" name="smeName" id="smkey" value="<?php echo $_smarty_tpl->tpl_vars['smeName']->value;?>
" class="skey" style="width:250px;">个人则填写个人名称</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">个人姓名</td>
                      <td><input type="text" name="smlinkMan" id="smlinkMan" value="<?php echo $_smarty_tpl->tpl_vars['smlinkMan']->value;?>
" class="skey" style="width:250px;"></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">联系电话</td>
                      <td><input type="text" name="smphoneNum" id="smphoneNum" value="<?php echo $_smarty_tpl->tpl_vars['smphoneNum']->value;?>
" class="skey" style="width:250px;">带区号010-1111111</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">手机号</td>
                      <td><input type="text" name="smmobile" id="smmobile" value="<?php echo $_smarty_tpl->tpl_vars['smmobile']->value;?>
" class="skey" style="width:250px;">135*********</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">电子邮件</td>
                      <td><input type="text" name="smemail" id="smemail" value="<?php echo $_smarty_tpl->tpl_vars['smemail']->value;?>
" class="skey" style="width:250px;">xx@yy.com</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">传真</td>
                      <td><input type="text" name="smfax" id="smfax" value="<?php echo $_smarty_tpl->tpl_vars['smfax']->value;?>
" class="skey" style="width:250px;">010-1111111</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">联系地址</td>
                      <td><input type="text" name="smaddress" id="smaddress" value="<?php echo $_smarty_tpl->tpl_vars['smaddress']->value;?>
" class="skey" style="width:250px;">xx路</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">邮政编码</td>
                      <td><input type="text" name="smpostcode" id="smpostcode" value="<?php echo $_smarty_tpl->tpl_vars['smpostcode']->value;?>
" class="skey" style="width:250px;">123456</td>
                    </tr>
                     <?php }?>

                  </tbody>
                </table>
              </div>
              <div class="blank20"></div>

              <input type="hidden" name="tijiao" id="tijiao" value="do" class="skey" style="width:250px;">
              <input type="hidden" name="saction" id="saction" value="siteset" class="skey" style="width:250px;">
               <input type="submit" value="确认提交" class="button">

            </form>
          </div>

         </div>
     </div>
     <div class="show_content_m_t2"  style="display:none;" id="shocart">
          <div style="width:auto;overflow-x:hidden;overflow-y:auto;">

          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/other/module/smtopay/datatype/json"),$_smarty_tpl);?>
" onsubmit="return subform('',this);">
              <div>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
                  <tbody>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">&nbsp;</td>
                      <td>以下是充值提交内容</td>
                    </tr>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">充值卡号：</td>
                      <td><input type="text" name="smcard" id="smcard" value="" class="skey" style="width:250px;"></td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">充值密码：</td>
                      <td><input type="password" name="smpwd" id="smpwd" value="" class="skey" style="width:250px;"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="blank20"></div>
              <input type="hidden" name="tijiao" id="tijiao" value="do" class="skey" style="width:250px;">
              <input type="hidden" name="saction" id="saction" value="siteset" class="skey" style="width:250px;">
               <input type="submit" value="提交充值" class="button">
            </form>
          </div>

         </div>
     </div>

<!--newmain 结束-->
<script>
	$(function(){
		$('#smstype').trigger('change');
	});
	function doinfo(){
		var findvalue = $('#smstype').find("option:selected").val();
		if(findvalue == 1){
			 $('#table2').show();
			 $('#table3').hide();
		}else{
			$('#table2').hide();
			 $('#table3').show();
		}
	}
      	function topay(){
      		$('#shocart').show();
      	}
      	function getfee(){
      		var url = '<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/other/module/smgetbalance"),$_smarty_tpl);?>
';
		     $.post(url, {},function (data, textStatus){
			              $('#showacountdet').text(data);
			     }, 'html');
      	}
</script>

   	        	 	
               <div class="show_content_m_t3">
   	        	 </div>
   	        	 
   	       </div>
   	       <!-- show_content_m结束-->


   	  </div>
   	  <!-- right_content 结束-->
   	  
   </div>
   <!-- newmain_all 结束-->
</div>	
	
<!--newmain 结束-->
















<div style="clear:both;"></div>
<div class="newfoot">
	
	  &#20851;&#27880;&#22909;&#36164;&#28304;&#20851;&#27880;&#25105;&#20204;&#65306;&#119;&#119;&#119;&#46;&#77;&#120;&#56;&#48;&#48;&#46;&#99;&#111;&#109;
</div>	
<script>
$(function(){ 
 $('.show_page a').wrap('<li></li>');
 $('.show_page').find('.current').eq(0).parent().css({'background':'#f60'}); 
});
   
</script>
</body>
</html>





<?php }} ?>