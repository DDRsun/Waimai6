<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:16:12
         compiled from "E:\phpnow\htdocs\templates\newgreen\market\index.html" */ ?>
<?php /*%%SmartyHeaderCode:7664542504acec0880-71923403%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '829010838dff65d1a26fdeb47cc9ea5e4e21c9b5' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\market\\index.html',
      1 => 1400282454,
      2 => 'file',
    ),
    '9237284e2d14756fd32e5ee5ead6f049d52f3b6e' => 
    array (
      0 => 'E:\\phpnow\\htdocs\\templates\\newgreen\\public\\site.html',
      1 => 1407569152,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7664542504acec0880-71923403',
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
  'unifunc' => 'content_542504ad439b48_40980550',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_542504ad439b48_40980550')) {function content_542504ad439b48_40980550($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
if (!is_callable('smarty_modifier_truncate')) include 'E:\\phpnow\\htdocs\\lib\\Smarty\\libs\\plugins\\modifier.truncate.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 零食铺-<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
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
/public/css/market.css">  

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
/public/js/slides.jquery.js"> </script>
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/marketcart.js" type="text/javascript" language="javascript"></script>
<script>
		var shopid = "<?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['id'];?>
"; 
	$(function(){
			$('#bannerBox').slides({
				preload: true,
				preloadImage: '/upload/images/shop/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				generatePagination:false
			});
		}); 
		var findtype = '<?php echo $_smarty_tpl->tpl_vars['findtype']->value;?>
';
		
		if(findtype == 1){
			alert('你选择的区域范围内无小卖铺');
			window.location.href= '<?php echo FUNC_function(array('type'=>'url','link'=>"/site/guide"),$_smarty_tpl);?>
';
		}
		
		
		var locationfalse = false;
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

<div class="mmbg" style="background:url(../upload/shop/page_bg.png)"></div>

 
 <!--头部-->
  <div  class="market_index">
  	<!--绝对路径的分类-->
  	  <div class="cateList">
  	  	
      	  <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['catlist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
      	 <dl>
            <dt><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/cat/id/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
</a></dt>
            <dd>
            	<?php  $_smarty_tpl->tpl_vars['itv'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itv']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['items']->value['det']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itv']->key => $_smarty_tpl->tpl_vars['itv']->value){
$_smarty_tpl->tpl_vars['itv']->_loop = true;
?>
            	     <a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/cat/id/".((string)$_smarty_tpl->tpl_vars['itv']->value['id'])),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['itv']->value['name'];?>
</a> 
            	 <?php } ?>
            </dd>
         </dl>
        <?php } ?> 
    
     </div>
  		<!--绝对路径的分类结束-->
  		<!---轮播广告或者二级分类的产品列表或者产品详情页内容-->
   
  	<div class="homeCover">
  		  <div class="width700  fl">
  		     
  		  	   	<div id="bannerBox">
					    <div  class="slides_container" style="height:210px;">
					 	     <?php echo smarty_function_load_data(array('assign'=>"lunbo",'table'=>"adv",'fileds'=>"*",'limit'=>"5",'where'=>"advtype='market'"),$_smarty_tpl);?>
  
							   <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['lunbo']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
							  
			        	   <a href="<?php echo $_smarty_tpl->tpl_vars['items']->value['linkurl'];?>
" target="_blank"><img   src="<?php echo $_smarty_tpl->tpl_vars['items']->value['img'];?>
" style="width:700px;height:210px;" ></a> 
				          <?php } ?> 
				       </div>
					 
				      </div>
  		  	   <div class="topgoods">  
  		  	   	   	<ul>
  		  	   	   	 <?php echo smarty_function_load_data(array('assign'=>"toplist",'table'=>"goods",'where'=>"shopid='".((string)$_smarty_tpl->tpl_vars['shopinfo']->value['id'])."' and img !='' and is_com =1",'fileds'=>"*",'limit'=>"4"),$_smarty_tpl);?>
 
  		  	   	   		<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['toplist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
                         <li id="goods_113"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/showgoods/id/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
" target="_blank">
                               <div class="thumb"><img class="lazy"  src="../upload/shop/grey.gif" data-original="<?php echo $_smarty_tpl->tpl_vars['items']->value['img'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
"></div>
                               <div class="info">
                                  <h4 class="name"><?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
</h4>
                                  <div class="price">￥<?php echo $_smarty_tpl->tpl_vars['items']->value['cost'];?>
 </div>
                               </div>
                           </a></li>
                    <?php } ?> 
                </ul>
  		  	  </div>
  		  </div>
  		  <div class="width225 fl">
  		  	<div class="hcright">
  		  	   
  		   <div class="storeInfo">
            <i class="entity"></i>
            <h3><?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['shopname'];?>
</h3>
            <p>门店营业：<?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['starttime'];?>
</p>
            <p><?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['address'];?>
 </p> 
            <p>售后电话：<?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['phone'];?>
 </p>
        </div>

        <div id="homeNotice">
               <ul class="text_list_1">
                <h3>本店公告</h3>
                <?php echo htmlspecialchars_decode($_smarty_tpl->tpl_vars['shopinfo']->value['notice_info']);?>
 
              </ul>
        </div> 
  		  	 <div id="homeNewArticle">
              <ul class="text_list_1">
                 <h3><a href="#"><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
最新动态</a></h3>
                <?php echo smarty_function_load_data(array('assign'=>"newlist",'table'=>"news",'fileds'=>"*",'limit'=>"5",'orderby'=>"id desc"),$_smarty_tpl);?>
 
                <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['newlist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
                   <li><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/site/news/id/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['items']->value['title'];?>
" target="_blank"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['items']->value['title'],15,"...",true);?>
</a></li> 
                <?php } ?> 
              </ul>
           </div>     
  		  	   
  		   </div>
  		  </div>
  		
  	</div>
  		<!---轮播广告或者二级分类的产品列表或者产品详情页内容结束-->
  </div> 
 
  <!---头结束-->
    <div  class="market_index">
 <!---猪页独立结构---> 
  
   <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['catlist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
  	<?php if (!empty($_smarty_tpl->tpl_vars['items']->value['ids'])){?>     	  
   <div id="floor_<?php echo $_smarty_tpl->tpl_vars['items']->value['id'];?>
" class="home_block home_block_common" style="border-top-color:;"> 
       <h2><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/cat/id/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
" target="_blank" style="color:"><span class="floor"></span><?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
</a> <a class="more" href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/cat/id/".((string)$_smarty_tpl->tpl_vars['items']->value['id'])),$_smarty_tpl);?>
">[更多商品]</a></h2>
       <div class="goods_list_1">
           <ul>
        	 <?php echo smarty_function_load_data(array('assign'=>"listdet",'table'=>"goods",'where'=>"shopid = '".((string)$_smarty_tpl->tpl_vars['shopinfo']->value['id'])."' and typeid in(".((string)$_smarty_tpl->tpl_vars['items']->value['ids']).") and is_hot =1",'limit'=>"12"),$_smarty_tpl);?>
 
        	 <?php  $_smarty_tpl->tpl_vars['itk'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['itk']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listdet']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['itk']->key => $_smarty_tpl->tpl_vars['itk']->value){
$_smarty_tpl->tpl_vars['itk']->_loop = true;
?>
        	 
                  <li id="goods_<?php echo $_smarty_tpl->tpl_vars['itk']->value['id'];?>
">
                                <div class="box">
                    <div class="thumb">
                        <div class="thumb_box">
                                 <a onclick="addonedish('<?php echo $_smarty_tpl->tpl_vars['itk']->value['id'];?>
','<?php echo $_smarty_tpl->tpl_vars['itk']->value['shopid'];?>
',1,this);" ><img  class="lazy"  src="../upload/shop/grey.gif"  data-original="<?php echo $_smarty_tpl->tpl_vars['itk']->value['img'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['itk']->value['name'];?>
" style="display: inline;"><div class="addtocart"></div></a>
                         </div>
                    </div>
                    <h4 class="name"><a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/market/showgoods/id/".((string)$_smarty_tpl->tpl_vars['itk']->value['id'])),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['itk']->value['name'];?>
" target="_blank"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['itk']->value['name'],13,"...",true);?>
</a></h4>
                    <div class="price">
                                                ￥<?php echo $_smarty_tpl->tpl_vars['itk']->value['cost'];?>
                                         </div>
                </div>
             </li> 
             
           <?php } ?> 
            </ul>
       </div>
  </div>
  <?php }?>
  <?php } ?>
 
<!--- 循环结束-->
</div>
 
<!--购物车-->
 <div class="mini-container" id="smallcatinfo">
 	   <div class="mini-cart-count" style="width: 66px;">
 	   	<a class="mini-buy" href="#" target="_self" onclick="ajaxdoorder(<?php echo $_smarty_tpl->tpl_vars['shopinfo']->value['id'];?>
);"></a>
 	   	<div class="mini-cart-open" style="display: none;">
 	   		<div class="mini-cart-cover">
 	   			<div class="cart-tip ks-clear">
 	   			   <div class="cart-num"><em class="J_CartNum">0</em></div>
 	   		     <div class="cart-price"><em class="J_CartPrice">0</em></div>
 	   		   	 <div class="cart-weight"><em class="J_CartWeight">0</em></div>
 	   		  </div>
 	   		  <div class="warn-msg">
 	   				<span href="满69免运费" target="_blank" class="J_CartMsg" title=""></span>
 	   			</div>
 	   	  </div>
 	   	  <s class=""></s>
 	   	</div>
 	   </div>
 	   <div class="mini-cart-close" style="display:block;" onclick="hidedetas();"><div class="mini-cate J_CartNum">0</div></div> 
 	   <div class="mini-bd sc-non-empty" style="width: 722px;display:none;">
 	   	    <div class="mini-clear ks-clear">
 	   	    	<div class="mini-bd-lf">
 	   	    		<a href="javascript:hidedeta();" class="mini-tringer" target="_self"></a>
 	   	    		<div class="mini-bd-content">
 	   	    			<p class="sc-notice J_CartNotice">您推的是空车子，赶快去购物吧！</p>
 	   	    			<div class="mini-bor">
 	   	    				<div class="mini-prev"><a class="disable" target="_self" href="javascript:goupcart();">上一页</a></div>
 	   	    				<div class="mini-next"><a class="disable" target="_self" href="javascript:gonextcart();">下一页</a></div>
 	   	    				<div class="mini-switch-container">
 	   	    					<div class="mini-switch-content" style="left:0;" id="cartinfodet">
 	   	    					    
 	   	    				    	<!--产品循环--> 
 	   	    				  </div>
 	   	    				</div></div></div></div><s></s></div>
 	   </div>
 	
</div>
<!--购物车结束-->



  <div style="clear:both;"></div>
  <script>
  $('.box').hover(function(){
  	 $(this).css('background-color','rgb(241, 241, 241)');
  });
  $('.box').mouseleave(function(){
  	 $(this).css('background-color','');
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