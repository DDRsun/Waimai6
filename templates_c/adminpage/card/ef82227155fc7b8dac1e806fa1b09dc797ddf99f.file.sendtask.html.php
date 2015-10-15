<?php /* Smarty version Smarty-3.1.10, created on 1938-08-21 05:40:22
         compiled from "E:\phpnow\htdocs\module\card\adminpage\sendtask.html" */ ?>
<?php /*%%SmartyHeaderCode:28173c500bf466ea057-91647423%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef82227155fc7b8dac1e806fa1b09dc797ddf99f' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\card\\adminpage\\sendtask.html',
      1 => 1409541412,
      2 => 'file',
    ),
    'a226ecdebbc91363df26407aef19d81c3b3bd604' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\adminpage\\public\\admin.html',
      1 => 1411697363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '28173c500bf466ea057-91647423',
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
  'unifunc' => 'content_c500bf467de298_64053085',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_c500bf467de298_64053085')) {function content_c500bf467de298_64053085($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
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
 
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/datepicker/WdatePicker.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/js/kindeditor/kindeditor.js" type="text/javascript" language="javascript"></script>
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
   	        	 	   <div class="showtop_t" id="positionname">发布任务</div>
   	        	 </div>
   	        	 <div class="show_content_m_t2">
   	        	 	
   	        	 	
        	 	 <?php echo smarty_function_load_data(array('assign'=>"info",'table'=>"task",'type'=>"one",'where'=>"id=0",'fileds'=>"*"),$_smarty_tpl);?>
 
     <div style="width:98%;overflow-y:auto;white-space:nowrap;">  
      	
          <div class="tags"> 
          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/card/module/savesendtask"),$_smarty_tpl);?>
" onsubmit="">
              <div>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
                  <tbody>
                  	 
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">任务标题</td>
                      <td><input type="text" name="taskname" id="taskname" value="<?php echo $_smarty_tpl->tpl_vars['info']->value['taskname'];?>
" class="skey" style="width:150px;" ></td>
                    </tr> 
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left" valign="top">任务类型</td>
                      <td>
                      	 <input type="radio" name="tasktype" value="1" checked >邮件群发 <input type="radio" name="tasktype" value="2" >短信群发
                      	</td>
                    </tr> 
                    <tr  onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">任务对象来源</td>
                      <td>
                      	 <input type="radio" name="taskusertype" value="1" checked onclick="show(1);">用户表获取 <input type="radio" name="taskusertype" value="2" onclick="show(2);">指定对象
                      	</td>
                    </tr> 
                     <tr class="show_1" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">用户类型</td>
                      <td><select name="usertype">
                      	<option value="0">不指定</option>
                      	<option value="1">普通会员</option>
                      	<option value="2">商加会员</option>
                      	<select></td>
                    </tr> 
                    <tr class="show_1" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">用户积分限制</td>
                      <td><input type="text" class="skey" style="width:150px;" name="userscore">不指定为空，例如填写 3 则条件是用户积分大于3</td>
                    </tr> 
                    <tr class="show_1" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">用户注册时间</td>
                      <td>从<input class="Wdate datefmt" type="text" name="creattime_starttime" id="creattime_starttime" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true});" />  
			   		 	            到<input class="Wdate datefmt" type="text" name="creattime_endtime" id="creattime_endtime" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true});" /> 为空不限制 </td>
                    </tr> 
                    <tr class="show_1" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">用户最近登陆时间</td>
                      <td>从<input class="Wdate datefmt" type="text" name="logintime_starttime" id="logintime_starttime" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true});" />  
			   		 	            到<input class="Wdate datefmt" type="text" name="logintime_endtime" id="logintime_endtime" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true});" /> 为空不限制 </td>
                    </tr>
                     <tr class="show_2" style="display:none;" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="ffffff">
                      <td class="left">对象ID集</td>
                      <td>
                      	 <textarea name="objcontent" style="width:550px;"></textarea>邮箱/手机号使用,分割  
                      	</td>
                    </tr> 
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff"> 
                      <td class="left">发送内容</td> 
                      <td>  
                      <div class='nop'><script>KE.show({id:'content',allowFileManager : true,imageUploadJson:'<?php echo FUNC_function(array('type'=>'url','link'=>"/other/saveupload"),$_smarty_tpl);?>
',fileManagerJson:'<?php echo FUNC_function(array('type'=>'url','link'=>"/other/saveupload"),$_smarty_tpl);?>
',items:['source','|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist', 'insertunorderedlist', '|', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold','italic', 'underline', 'removeformat', '|', 'image', 'advtable', 'hr','link', 'unlink']});</script><textarea name="content" id="content" style='width:550px; height:260px;'></textarea></div>           </td>

                    </tr>
                    <input type="hidden" name="uid" id="uid" value="<?php echo $_smarty_tpl->tpl_vars['info']->value['id'];?>
" class="skey" style="width:150px;">
                    
                  </tbody>
                </table>
              </div>
              <div class="blank20"></div> 
               <input type="submit" value="确认提交" class="button">  
            </form>
          </div>
        </div>
        <div class="blank20"></div> 
      
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
    
   
 
    
</div> 
<script>
 
   function show(a){
   	$('.show_1').hide();
   	$('.show_2').hide();
   	$('.show_'+a).show();
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