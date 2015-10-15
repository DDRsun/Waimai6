<?php /* Smarty version Smarty-3.1.10, created on 2001-09-26 11:27:49
         compiled from "E:\phpnow\htdocs\module\system\adminpage\sitebk.html" */ ?>
<?php /*%%SmartyHeaderCode:302783bb14b3556b110-32266281%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a4b3a80e9819d7557c1541d38570880bed9ec540' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\module\\system\\adminpage\\sitebk.html',
      1 => 1408791764,
      2 => 'file',
    ),
    'a226ecdebbc91363df26407aef19d81c3b3bd604' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\adminpage\\public\\admin.html',
      1 => 1411697363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '302783bb14b3556b110-32266281',
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
  'unifunc' => 'content_3bb14b35738be6_95447352',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_3bb14b35738be6_95447352')) {function content_3bb14b35738be6_95447352($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.date_format.php';
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
   	        	 	   <div class="showtop_t" id="positionname">水印设置</div>
   	        	 </div>
   	        	 <div class="show_content_m_t2">
   	        	 	
   	        	 	 
	 <div style="width:auto;overflow-x:hidden;overflow-y:auto;">   
          <div id="tagscontent">
            <form method="post" name="form1" action="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/system/module/savesitebk/datatype/json"),$_smarty_tpl);?>
" onsubmit="return subform('',this);">
              <div>
                <table border="0" cellspacing="2" cellpadding="4" class="list" name="table" id="table" width="100%">
                  <tbody>
                    
                    
                     
                    <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">网站默认背景</td>
                      <td height=200px>
                      	<input type="hidden" name="userlogo" id="userlogo" value="<?php echo $_smarty_tpl->tpl_vars['sitebk']->value;?>
" class="skey" style="width:100px;">
                      	<img src="<?php echo $_smarty_tpl->tpl_vars['sitebk']->value;?>
" width=200px height=200px id="imgshow" <?php if (empty($_smarty_tpl->tpl_vars['sitebk']->value)){?> style="display:none;"<?php }?>> 
                      	<span onclick="uploads();">上传图片</span> 
                      </td>
                    </tr>
                     <tr onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">网站水印</td>
                      <td >
                      	<input type="checkbox" value="1" name="is_water" onchange="waterdet();" <?php if ($_smarty_tpl->tpl_vars['is_water']->value==1){?> checked<?php }?>>启用 
                      </td>
                    </tr>
                    <tr class="waterdet" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">水印位置：</td>
                      <td>
                      	<select name="water_pos">
                      		  <option value="0">随机位置</option>
                      		  <option value="1" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==1){?>selected<?php }?>>顶端居左</option>
                      		   <option value="2" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==2){?>selected<?php }?>>顶端居中</option>
                      		    <option value="3" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==3){?>selected<?php }?>>顶端居右</option>
                      		     <option value="4" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==4){?>selected<?php }?>>中部居左</option>
                      		      <option value="5" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==5){?>selected<?php }?>>中部居中</option>
                      		       <option value="6" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==6){?>selected<?php }?>>中部居右</option>
                      		        <option value="7" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==7){?>selected<?php }?>>底端居左</option>
                      		         <option value="8" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==8){?>selected<?php }?>>底端居中</option>
                      		          <option value="9" <?php if ($_smarty_tpl->tpl_vars['water_pos']->value==9){?>selected<?php }?>>底端居右</option> 
                        </select>
                       
                      </td>
                    </tr>
                     <tr class="waterdet" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left">水印图片</td>
                      <td>
                      	<input type="hidden" name="logo_water" id="logo_water" value="<?php echo $_smarty_tpl->tpl_vars['logo_water']->value;?>
" class="skey" style="width:100px;"> 
                      	<img src="<?php echo $_smarty_tpl->tpl_vars['logo_water']->value;?>
" width=50px height=50px id="imgshows" <?php if (empty($_smarty_tpl->tpl_vars['logo_water']->value)){?> style="display:none;"<?php }?>> 
                      	<span onclick="uploadm();">上传图片</span> 
                      </td>
                    </tr>
                    <tr class="waterdet" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">文字水印：</td>
                      <td> 
                      	<div><input name="text_water" value="<?php echo $_smarty_tpl->tpl_vars['text_water']->value;?>
">非中文字符</div> 
                      </td>
                    </tr>
                    <tr class="waterdet" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">水印文字大小：</td>
                      <td> 
                      	 <div><select name="size_water">
                      		<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['name'] = 'foo';
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] = (int)10;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] = is_array($_loop=20) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] = ((int)2) == 0 ? 1 : (int)2;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['foo']['total']);
?>
                      		<option value="<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['foo']['index'];?>
" <?php if ($_smarty_tpl->getVariable('smarty')->value['section']['foo']['index']==$_smarty_tpl->tpl_vars['size_water']->value){?>selected<?php }?>> <?php echo $_smarty_tpl->getVariable('smarty')->value['section']['foo']['index'];?>
</option>
                         <?php endfor; endif; ?>
                         </select>
                        </div> 
                      </td>
                    </tr>
                    <tr class="waterdet" onmouseover="this.bgColor='#F5F5F5';" onmouseout="this.bgColor='ffffff';" bgcolor="#ffffff">
                      <td class="left" valign="top">水印文字颜色：</td>
                      <td> 
                      	 <div><select name="color_water"> 
                      		<option value="#FF0000" <?php if ($_smarty_tpl->tpl_vars['color_water']->value=='#FF0000'){?>selected<?php }?>> 红色</option>
                          <option value="#FFFFFF" <?php if ($_smarty_tpl->tpl_vars['color_water']->value=='#FFFFFF'){?>selected<?php }?>> 白色</option>
                          <option value="#000000" <?php if ($_smarty_tpl->tpl_vars['color_water']->value=='#000000'){?>selected<?php }?>> 黑色</option>
                        </select>
                        </div> 
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
<script>
	$(function(){ 
		$("input[name='is_water']").trigger("change"); 
		 
	});
	var dialogs ;
 function uploads(){  
 	  dialogs = art.dialog.open('<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/other/module/adminupload/func/uploadsucess"),$_smarty_tpl);?>
');
 	  dialogs.title('上传图片'); 
 } 
 function uploadsucess(flag,obj,linkurl){
 	 if(flag == true){
 		alert(linkurl);
			dialogs.close();
		uploads();
 	 }else{ 
 		dialogs.close();
 	  $('#userlogo').val(linkurl);
 	  $('#imgshow').attr('src',linkurl);
 	  $('#imgshow').show(); 
   }
 }  
 function uploadm(){  
 	  dialogs = art.dialog.open('<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/other/module/adminupload/func/uploadsucessm"),$_smarty_tpl);?>
');
 	  dialogs.title('上传图片'); 
 }
 function uploadsucessm(flag,obj,linkurl){
 	 if(flag == true){
 		alert(linkurl);
			dialogs.close();
		uploadm();
 	 }else{ 
 		dialogs.close();
 	  $('#logo_water').val(linkurl);
 	  $('#imgshows').attr('src',linkurl);
 	  $('#imgshows').show(); 
   }
 }  
  
 
function waterdet(){
	var is_water = $("input[name='is_water']:checked").val();
	if(is_water == 1){
	   $('.waterdet').show();
	}else{
		$('.waterdet').hide();
	} 
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