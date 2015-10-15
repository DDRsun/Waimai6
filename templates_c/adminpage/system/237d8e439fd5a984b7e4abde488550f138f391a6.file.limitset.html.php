<?php /* Smarty version Smarty-3.1.10, created on 1938-08-21 05:39:59
         compiled from "E:\phpnow\htdocs\module\system\adminpage\limitset.html" */ ?>
<?php /*%%SmartyHeaderCode:4164c500bf2fa037a9-44955722%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '237d8e439fd5a984b7e4abde488550f138f391a6' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\system\\adminpage\\limitset.html',
      1 => 1408792556,
      2 => 'file',
    ),
    'a226ecdebbc91363df26407aef19d81c3b3bd604' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\adminpage\\public\\admin.html',
      1 => 1411697363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4164c500bf2fa037a9-44955722',
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
  'unifunc' => 'content_c500bf2fb71b02_83017737',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_c500bf2fb71b02_83017737')) {function content_c500bf2fb71b02_83017737($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
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
   	        	 	   <div class="showtop_t" id="positionname">后台菜单设置</div>
   	        	 </div>
   	        	 <div class="show_content_m_t2">
   	        	 	
   	        	 	
  <div style="width:auto;overflow-x:hidden;overflow-y:auto"> 
  	    <div class="search"> 
            <div class="search_content"> 
            	 <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/".((string)$_smarty_tpl->tpl_vars['Taction']->value)."/module/limitset"),$_smarty_tpl);?>
">
            	  
            	  <label>选择用户组：</label>
            	   <select name="usergrade">
            	   	 <?php echo smarty_function_load_data(array('assign'=>"grouplist",'table'=>"group",'fileds'=>"*",'where'=>"type='admin'",'limit'=>"6"),$_smarty_tpl);?>
  
            	   	<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['myid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['grouplist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['myid']->value = $_smarty_tpl->tpl_vars['items']->key;
?>  
                      	   <option value="<?php echo $_smarty_tpl->tpl_vars['items']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['groupid']->value==$_smarty_tpl->tpl_vars['items']->value['id']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
</option> 
                	<?php } ?>            
                 </select>
            	  
            	    <input type="submit" value="提交查询" class="button">  
            	 </form>
            </div>
        
      	</div>
  	
  	
  	  <?php if (!empty($_smarty_tpl->tpl_vars['groupinfo']->value)){?>
          <div class="tags">
       
          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/".((string)$_smarty_tpl->tpl_vars['Taction']->value)."/module/savelimit/datatype/json"),$_smarty_tpl);?>
" onsubmit="return subform('',this);">
              <div>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
                  <tbody>
                  	<?php if ($_smarty_tpl->tpl_vars['groupinfo']->value['type']=='admin'){?> 
                  	
                  	<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['modelist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
                  	
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left"><?php echo $_smarty_tpl->tpl_vars['items']->value['cnname'];?>
</td>
                      <td> 
                      	
                      <?php if (count($_smarty_tpl->tpl_vars['items']->value['det'])>0){?>
                        <?php  $_smarty_tpl->tpl_vars['itv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itv']->_loop = false;
 $_smarty_tpl->tpl_vars['mykey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['items']->value['det']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itv']->key => $_smarty_tpl->tpl_vars['itv']->value){
$_smarty_tpl->tpl_vars['itv']->_loop = true;
 $_smarty_tpl->tpl_vars['mykey']->value = $_smarty_tpl->tpl_vars['itv']->key;
?> 	
                             <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
[]" value="<?php echo $_smarty_tpl->tpl_vars['mykey']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['mykey']->value,$_smarty_tpl->tpl_vars['items']->value['menuarray'])){?>checked<?php }?>><?php echo $_smarty_tpl->tpl_vars['itv']->value;?>
 
                        <?php } ?> 
                       <?php }?>
                      	</td>
                      </tr> 
                      
                      
                     <?php } ?>
                      
                      
                    <?php }else{ ?>
                    <?php }?>
                     
                    <input type="hidden" name="groupid" id="groupid" value="<?php echo $_smarty_tpl->tpl_vars['groupid']->value;?>
" class="skey" style="width:150px;">
                   
                  </tbody>
                </table>
              </div>
              <div class="blank20"></div>
             
               <input type="submit" value="确认提交" class="button">  
            </form>
          </div>
        </div>
      <?php }?> 
        <div class="blank20"></div> 
      
      </div>
      <div class="clear"></div>
    </div>
   
   
    </div> 
<script>	 
function checkword(flag)
{ 
	 
	$("input[name='limit[]']").attr("checked",flag); 
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