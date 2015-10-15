<?php /* Smarty version Smarty-3.1.10, created on 1938-08-21 05:40:20
         compiled from "E:\phpnow\htdocs\module\card\adminpage\prensentjuan.html" */ ?>
<?php /*%%SmartyHeaderCode:26965c500bf446ea055-95350474%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab2d06302241b85961dabb66ab275e8379571d3a' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\card\\adminpage\\prensentjuan.html',
      1 => 1409541382,
      2 => 'file',
    ),
    'a226ecdebbc91363df26407aef19d81c3b3bd604' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\adminpage\\public\\admin.html',
      1 => 1411697363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '26965c500bf446ea055-95350474',
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
  'unifunc' => 'content_c500bf44895441_16377764',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_c500bf44895441_16377764')) {function content_c500bf44895441_16377764($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
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
<script type="text/javascript" language="javascript" src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/datepicker/WdatePicker.js"></script>
 
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
   	        	 	   <div class="showtop_t" id="positionname">优惠券赠送设置</div>
   	        	 </div>
   	        	 <div class="show_content_m_t2">
   	        	 	
   	        	 	

	   <div style="width:auto;overflow-x:hidden;overflow-y:auto">

          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/card/module/saveprensentjuan/datatype/json"),$_smarty_tpl);?>
" onsubmit="return subform('',this);">
              <div>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
                  <tbody>
                  	<tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">注册送优惠券</td>
                      <td><input type="checkbox" name="regester_juan" id="regester_juan" value="1"  <?php if (isset($_smarty_tpl->tpl_vars['regester_juan']->value)&&$_smarty_tpl->tpl_vars['regester_juan']->value==1){?>checked<?php }?>>勾选注册后赠送优惠券,最低消费金额<input type="text" name="regester_juanlimit" id="regester_juanlimit" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['regester_juanlimit']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;">抵扣金额<input type="text" name="regester_juancost" id="regester_juancost" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['regester_juancost']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;"> 优惠券有效天数量<input type="text" name="regester_juandatay" id="regester_juandatay" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['regester_juandatay']->value)===null||$tmp==='' ? 7 : $tmp);?>
"  style="width:50px;">天
                      	</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">关注微信送优惠券</td>
                      <td>
                      	<input type="checkbox" name="wx_juan" id="wx_juan" value="1"  <?php if (isset($_smarty_tpl->tpl_vars['wx_juan']->value)&&$_smarty_tpl->tpl_vars['wx_juan']->value==1){?>checked<?php }?>>勾选后关注微信赠送优惠券,最低消费金额<input type="text" name="wx_juanlimit" id="wx_juanlimit" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wx_juanlimit']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;">抵扣金额<input type="text" name="wx_juancost" id="wx_juancost" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wx_juancost']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;"> 优惠券有效天数量<input type="text" name="wx_juanday" id="wx_juanday" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['wx_juanday']->value)===null||$tmp==='' ? 7 : $tmp);?>
"  style="width:50px;">天
                      	</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">推广送优惠券</td>
                      <td>
                      	<input type="checkbox" name="tui_juan" id="tui_juan" value="1"  <?php if (isset($_smarty_tpl->tpl_vars['tui_juan']->value)&&$_smarty_tpl->tpl_vars['tui_juan']->value==1){?>checked<?php }?>>勾选后通过推广链接注册用户下单完成后，赠送推荐人优惠券最低消费金额<input type="text" name="tui_juanlimit" id="tui_juanlimit" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['tui_juanlimit']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;">抵扣金额<input type="text" name="tui_juancost" id="tui_juancost" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['tui_juancost']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;"> 优惠券有效天数量<input type="text" name="tui_juanday" id="tui_juanday" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['tui_juanday']->value)===null||$tmp==='' ? 7 : $tmp);?>
"  style="width:50px;">天
                      	</td>
                    </tr>
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">登陆送优惠券</td>
                      <td>
                      	<input type="checkbox" name="login_juan" id="login_juan" value="1"  <?php if (isset($_smarty_tpl->tpl_vars['login_juan']->value)&&$_smarty_tpl->tpl_vars['login_juan']->value==1){?>checked<?php }?>> 勾选后 , 日期必填,
                      	日期：<input type="text" name="login_data" id="login_data" value="<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['login_data']->value,"%Y-%m-%d");?>
"   class="Wdate datefmt" onFocus="WdatePicker({isShowClear:false,readOnly:true});"> 在此日期登陆送优惠券  ,最低消费金额<input type="text" name="login_juanlimit" id="login_juanlimit" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['login_juanlimit']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;">抵扣金额<input type="text" name="login_juancost" id="login_juancost" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['login_juancost']->value)===null||$tmp==='' ? 1 : $tmp);?>
"  style="width:50px;"> 优惠券有效天数量<input type="text" name="login_juanday" id="login_juanday" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['login_juanday']->value)===null||$tmp==='' ? 7 : $tmp);?>
"  style="width:50px;">
                      	</td>
                    </tr>


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

<!--newmain 结束-->
 
   	        	 	
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