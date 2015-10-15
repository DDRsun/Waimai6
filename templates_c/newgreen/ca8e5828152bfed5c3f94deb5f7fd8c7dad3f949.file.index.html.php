<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 15:26:49
         compiled from "E:\phpStudy\www\templates\newgreen\gift\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2036054251539353577-63329424%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca8e5828152bfed5c3f94deb5f7fd8c7dad3f949' => 
    array (
      0 => 'E:\\phpStudy\\www\\templates\\newgreen\\gift\\index.html',
      1 => 1399915610,
      2 => 'file',
    ),
    '04b4dee65e2642325c464bc29d4812ae173ef71d' => 
    array (
      0 => 'E:\\phpStudy\\www\\templates\\newgreen\\public\\site.html',
      1 => 1407569152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2036054251539353577-63329424',
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
  'unifunc' => 'content_54251539634028_68017777',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54251539634028_68017777')) {function content_54251539634028_68017777($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpStudy\\www\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 礼品街-<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
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
/public/css/gift.css">  
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/css/bootstrap.min.css"> 
 
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
/public/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
  

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

 
<div class="IntegralMall"> 
  <div class="IntegralMallIn">
        <div class="IMTop"> 
       </div>
        <div class="IMMiddle">
            <ul> 
            	 <?php echo smarty_function_load_data(array('assign'=>"list",'table'=>"gift",'fileds'=>"*",'limit'=>"100"),$_smarty_tpl);?>
 
            	    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
                  <li>
                    <div class="IMPic"><a href="javascript:;" onclick="Pop.show(9)"><img alt="<?php echo $_smarty_tpl->tpl_vars['value']->value['title'];?>
" src="<?php echo $_smarty_tpl->tpl_vars['value']->value['img'];?>
" style="height:210px;width:210px;"></a></div>
                    <div class="IMIntro"><?php echo $_smarty_tpl->tpl_vars['value']->value['title'];?>
</div>
                    <div class="IMOperate">
                        <div class="IMIntegral">积分 <span><?php echo $_smarty_tpl->tpl_vars['value']->value['score'];?>
</span> 点</div>
                       
                        <a href="javascript:;" onclick="duihuan('<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['value']->value['score'];?>
','<?php echo $_smarty_tpl->tpl_vars['value']->value['title'];?>
','<?php echo $_smarty_tpl->tpl_vars['value']->value['sell_count'];?>
','<?php echo $_smarty_tpl->tpl_vars['value']->value['stock'];?>
','<?php echo $_smarty_tpl->tpl_vars['value']->value['img'];?>
')" class="IMBtn">查看详情</a>
                        <div id="content_shoid_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" style="display:none;">
                        	<?php echo $_smarty_tpl->tpl_vars['value']->value['content'];?>

                        	</div>
                    </div>
                  </li>
                  <?php } ?> 
                <div style="clear:both;"></div>
            </ul>
        </div>
         
        <div id="shoinfo" style="display: none;">  
        </div>  
   </div> 
</div>    
    <div id="showexc"></div>
    <script type="text/javascript"> 
			function duihuan(gid,gscore,gtitle,gsell_count,gstock,gimg)
			{
				var gcontent = $('#content_shoid_'+gid).html();
				//gcontent
			   var cc = '<?php echo $_smarty_tpl->tpl_vars['member']->value['uid'];?>
';
			   if( cc < 1)
			   {
			   	  diaerror('登录后才可兑换');
			   	}else{
				var htmldata='<div class="IMDetail" id="PopInfo" style="top: -10px; display: block;"><div class="IMDTop">';
           	htmldata+='<div class="IMDTLeft"><img src="'+gimg+'" width="300" height="300" id="giftimg"><\/div>';
htmldata+='<div class="IMDTRight">';
htmldata+='<h3>'+gtitle+'<\/h3>';
htmldata+='<dl>';
htmldata+='<dd><label style="display:inline;">商品编码：<\/label><span id="goodsCode">'+gid+'<\/span><\/dd>';
htmldata+='<dd><label style="display:inline;">兑换数量：<\/label><span id="tradeCnt">1个<\/span><\/dd>';
htmldata+='<dd><label style="display:inline;">兑换价格：<\/label><span id="price"><em>'+gscore+'</em>积分<\/span><\/dd>';
htmldata+='<\/dl>';
htmldata+='<div class="IMDTBtn">';
htmldata+='<a href="#" onclick="exch('+gid+')" id="exbtn">立即兑换<\/a>';
htmldata+='<\/div>';
htmldata+='<\/div>';
htmldata+='<div style="clear:both;"><\/div>';
htmldata+='<\/div>';
htmldata+='<div class="IMDBottom"><h3>温馨提示<\/h3><p>'+gcontent+'<\/p><\/div>';
htmldata+='<a href="javascript:;" onclick="closedialog(\'shoduihuan\');" class="IMClose"><\/a><\/div>';
				 
				 art.dialog({ 
				 	    id:'shoduihuan',
                content: htmldata,
               lock: true
          }); 
          
         }
			}
			function closedialog(dialogname)
			{
				 art.dialog({id:dialogname}).close();
			}	 
			function exch(gid){ 
				var newurl = siteurl+'/index.php?controller=gift&action=exchanghtml&id='+gid;
				newurl = newurl.replace('@random@', 1+Math.round(Math.random()*1000)), 
     			$.get(newurl,function(data){
     				art.dialog({id:'shoduihuan'}).close();
        	$('#showexc').html(data);
        	$('#Modal_col').modal('show');
           },'html');  
			}	
			 
			   
		 
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