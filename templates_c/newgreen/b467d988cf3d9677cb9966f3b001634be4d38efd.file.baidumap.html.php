<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 14:38:06
         compiled from "E:\phpStudy\www\templates\newgreen\site\baidumap.html" */ ?>
<?php /*%%SmartyHeaderCode:9733542509ce13bf70-48775886%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b467d988cf3d9677cb9966f3b001634be4d38efd' => 
    array (
      0 => 'E:\\phpStudy\\www\\templates\\newgreen\\site\\baidumap.html',
      1 => 1411692769,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9733542509ce13bf70-48775886',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'keywords' => 0,
    'description' => 0,
    'metadata' => 0,
    'siteurl' => 0,
    'tempdir' => 0,
    'baidumapkey' => 0,
    'is_static' => 0,
    'controlname' => 0,
    'sitebk' => 0,
    'member' => 0,
    'cityinfo' => 0,
    'cityname' => 0,
    'arealist' => 0,
    'items' => 0,
    'cooklatlist' => 0,
    'cookmalist' => 0,
    'myid' => 0,
    'cooklnglist' => 0,
    'dlng' => 0,
    'dlat' => 0,
    'toplink' => 0,
    'beian' => 0,
    'footerdata' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.10',
  'unifunc' => 'content_542509ce3ca5d4_97797841',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_542509ce3ca5d4_97797841')) {function content_542509ce3ca5d4_97797841($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 百度地图-<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
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
/public/css/map.css" />
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
 <script src="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/templates/<?php echo $_smarty_tpl->tpl_vars['tempdir']->value;?>
/public/js/areamap.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo $_smarty_tpl->tpl_vars['baidumapkey']->value;?>
"></script>
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


 

 <div class="mmbg" <?php if (!empty($_smarty_tpl->tpl_vars['sitebk']->value)){?>style="background:url(<?php echo $_smarty_tpl->tpl_vars['sitebk']->value;?>
) no-repeat;"<?php }?>></div>  
   
 
 
 
  
 
<div class="middletu"> 
  <div class="headerAcMain">
                <span>
                    <em></em>
                    <i><a href="http://%77%77%77%2E%6D%78%38%30%30%2E%63%6F%6D/thread-54801-1-1.html"  >&#20851;&#27880;&#22909;&#36164;&#28304;&#28857;&#20987;&#36827;&#20837;</a></i>
                </span>
                <p><?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
</p>
				<ol>
					<li>
					 
					</li>
				</ol>
                <ul>
		            <li>
			    		    <?php if (!empty($_smarty_tpl->tpl_vars['member']->value['uid'])){?>
			    		    	<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/index"),$_smarty_tpl);?>
" title="会员中心">会员中心</a>
			    		    <?php }else{ ?>
				        	<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/login"),$_smarty_tpl);?>
" title="登录">登录</a>
				            <i>/</i>
				        	<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/member/regester"),$_smarty_tpl);?>
" title="注册">注册</a>
			        	  <?php }?>
			        	
                    </li>
                </ul>
  </div>
  <h2 class="run-mapSearchH2">
                <div class="run-mapSeachTxt">
                	 <div  class="citychoice" data="<?php echo $_smarty_tpl->tpl_vars['cityinfo']->value['id'];?>
" id="citychoice"> <?php echo $_smarty_tpl->tpl_vars['cityinfo']->value['name'];?>
 </div>
                	 <div style="width:494px;float:left;"> 
                	   	<input type="hidden" name="controller" value="site">
                	   	<input type="hidden" name="action" value="guide2"> 
                      <input value="请输入您要搜索的地址" type="text" tip="请输入您要搜索的地址" name="searchvalue" id="run-searchAddress" stype="地址" class="searchInput"   onkeydown="if(event.keyCode==13) dosearchmap(0);">
                      <i class="searchBtn" onclick="dosearchmap(0);"></i>
                     <input type="hidden" name="cityname" id="cityname" value="<?php echo $_smarty_tpl->tpl_vars['cityname']->value;?>
">
                     <input type="hidden"  name="surec" value="1"> 
                    <ul class="searchResult" style="display: none;">
                    </ul>
                   </div>
                  
                </div>
                  
 </h2>	
<div class="mapContent" >
	
	<!--- 地图左边信息--->
	<div class="mapSide" style="display:none;" >
	 
  </div>
  <!---地图左边结束--->
	<div id="SearchAddmap"> 
 	</div>
	
	
</div>
<div class="zhezhao"></div>
	
<div class="citylist">
	<div class="citylisttititle"> <div class="cityleft"> 选择您所在城市开始订餐 </div>  <div class="cityright"><font color=green>选城市</font> > 定位置 > 叫外卖</div> </div>
	<div class="citylistshow" style="clear:both;" id="citylistshow">
		<ul>
			 <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['arealist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
?>
		      <li data="<?php echo $_smarty_tpl->tpl_vars['items']->value['id'];?>
" showdata="<?php echo $_smarty_tpl->tpl_vars['items']->value['show'];?>
" lng="<?php echo $_smarty_tpl->tpl_vars['items']->value['lng'];?>
" lat="<?php echo $_smarty_tpl->tpl_vars['items']->value['lat'];?>
" ><?php echo $_smarty_tpl->tpl_vars['items']->value['name'];?>
</li> 
	     <?php } ?>
		</ul>
	</div>
	
</div>
<div class="citylist2"> 
	<div class="citylisttititle"> <div class="cityleft" id="citydet_tit_l"> 请选择区域 </div>  <div class="cityright" id="citydet_tit_r"></div> <div class="cityright" id="colse_det" style="display:none;">关闭</div> </div>
	<div class="citylistshow" style="clear:both;" id="citydet">
		<ul>
			 
		</ul>
	</div> 
</div>
<div class="search_result" id="search_result">
	 <div class="result-block" style="display:none;"><div class="marker ui_2"></div><p class="result-name">傻子烧鸡酱肉(团结湖店)</p><p class="result-address">团结湖公园东门团结湖路28号楼(近老思福面馆)</p><p class="result-nearby">附近有<span class="highlight">37</span>家餐厅</p></div>
</div>

<div class="searhistory">
	 <?php if (count($_smarty_tpl->tpl_vars['cooklatlist']->value)>0){?>
	   <div class="historytitle">历史地址</div>
	     <ul>
	     	<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['myid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cookmalist']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['myid']->value = $_smarty_tpl->tpl_vars['items']->key;
?>
	     	<?php if (!empty($_smarty_tpl->tpl_vars['items']->value)){?>
	     	 <?php if ($_smarty_tpl->tpl_vars['myid']->value<5){?>
	     	  <li> <a href="<?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['cooklnglist']->value[$_smarty_tpl->tpl_vars['myid']->value])===null||$tmp==='' ? '0' : $tmp);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['cooklatlist']->value[$_smarty_tpl->tpl_vars['myid']->value])===null||$tmp==='' ? '0' : $tmp);?>
<?php $_tmp2=ob_get_clean();?><?php echo FUNC_function(array('type'=>'url','link'=>"/site/setuserlng/lng/".$_tmp1."/lat/".$_tmp2."/mapname/".((string)$_smarty_tpl->tpl_vars['items']->value)),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['items']->value;?>
</a></li>     
	     	 <?php }?>
	     	 <?php }?>
	     	<?php } ?>
	     	
	    </ul>
	 
	 
	 
	 <?php }?>
</div>
<div class="myRichMarkerAlert info-window needPIE"  id="mysetdiv" style="display:none;">
	<i class="arrow"></i>
	<a class="close"></a>
	
</div>
 

<script type="text/javascript">

// 百度地图API功能
/*初始化地图*/  
var map = new BMap.Map("SearchAddmap");
var point = new BMap.Point(<?php echo $_smarty_tpl->tpl_vars['dlng']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['dlat']->value;?>
); 
var myIcon1 = new BMap.Icon(siteurl+"/upload/map/pepole.png", new BMap.Size(75,75),{offset: new BMap.Size(25,25),imageOffset: new BMap.Size(25,25)});  
//var marker = new BMap.Marker(point);
var marker = null;   
var infoWindow = new BMap.InfoWindow('');  // 创建信息窗口对象
var makenow = false;
var markerslist = null;
map.centerAndZoom(point, 18);
map.setDefaultCursor("url('<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/upload/shop/add-my-shubiao.cur'),default");
//map.setDefaultCursor("url('<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/upload/shop/add-my-shubiao.cur'),default");
map.setDraggingCursor("auto"); 
//加载缩放级别
map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
var savemapurl = '<?php echo FUNC_function(array('type'=>'url','link'=>"/site/setuserlng/mapname/@mapname@/lat/@lat@/lng/@lng@/random/@random@"),$_smarty_tpl);?>
';  
var marketurl = '<?php echo FUNC_function(array('type'=>'url','link'=>"/site/setuserlng/mapname/@mapname@/lat/@lat@/lng/@lng@/gototype/market/random/@random@"),$_smarty_tpl);?>
'
map.enableContinuousZoom();//可缩放

//map.openInfoWindow(infoWindow,point);                // 初始化地图,设置城市和地图级别。


/**当无查找数据时调用默认城市为图标**/ 

/*当存在查找数据*/
/*当有查找数据*/


/*当有查找数据*/




map.addEventListener("click",function(e){    
	if(e.overlay == null){  
	    // alert(e.pixel.x);
	    // e.pixel.y
	 //  if($("#mysetdiv").is(":hidden") == false){//$('.BMap_pop').html() != undefined){ 
	 if($.browser.msie) { 
	         	$('#mysetdiv').animate({ top: e.pixel.y-150,left: e.pixel.x-150}, 300); 
	        }else{
	        	 	$('#mysetdiv').animate({ top: e.pixel.y-110,left: e.pixel.x-140}, 300); 
	        }
	//    }
	    $.getScript('http://api.map.baidu.com/geocoder/v2/?ak=<?php echo $_smarty_tpl->tpl_vars['baidumapkey']->value;?>
&callback=renderReverse&location='+e.point.lat+','+e.point.lng+'&output=json&pois=1');   
	} 
});
map.addEventListener("dragend", function(evt){
	 if(e.overlay == null){  
	 	  makenow = false;
   	  var pointc = new BMap.Point(evt.point.lat, evt.point.lng); 
	    map.centerAndZoom(pointc, 18);
    	map.clearOverlays();
	}
 
//   var offsetPoint = new BMap.Pixel(evt.offsetX, evt.offsetY);   //记录鼠标当前点坐标<br>   alert(offsetPoint.x+","+offsetPointY);
});
function renderReverse(datas){
	 if(datas.status == 0){  
	 	  var  shuliang = 0;
	 	  var marketshu = 0;
	    var url = siteurl+'/index.php?contrller=site&action=ajaxlngtlat&lng='+datas.result.location.lng+'&lat='+datas.result.location.lat+'&random=@random@&datatype=json'; 
	    $.ajax({
         type: 'post',
         async:false, 
         url: url.replace('@random@', 1+Math.round(Math.random()*1000)), 
         dataType: 'json',success: function(content) {   
         	if(content.error == false){ 
             
              shuliang = content.msg.shopshu;
              //
              marketshu = content.msg.marketshu;
              
         	} 
	    	},
        error: function(content) {   alert('shiba'); }
       });  
 
	      var newpoint = new BMap.Point(datas.result.location.lng, datas.result.location.lat);  
	      //savemapurl  encodeURI
	      var temp_linksss = savemapurl.replace('@lat@',datas.result.location.lat).replace('@lng@', datas.result.location.lng).replace('@mapname@', encodeURI(datas.result.formatted_address));
	     
	       var contentc = '<i class="arrow"></i>	<a class="close"></a>	<p class="name">'+datas.result.formatted_address+'</p><p class="amount">附近有 <strong>'+shuliang+'</strong> 家餐厅</p>';
	       var temp_marketurl = marketurl.replace('@lat@',datas.result.location.lat).replace('@lng@', datas.result.location.lng).replace('@mapname@', encodeURI(datas.result.formatted_address));
	      if(shuliang > 0){
	           contentc += '<a class="btn" href="'+temp_linksss+'" >查看餐厅</a>';
	      }
	      if(marketshu > 0){
	      	contentc += '<a class="btn" href="'+temp_marketurl+'" >查看超市</a>';
	      }
	       $('#mysetdiv').html(contentc);
	      $('#mysetdiv').show();
	     // var NewinfoWindow = new BMap.InfoWindow(contentc);   
	      if(makenow ==  false){ 
	   	          marker = new BMap.Marker(newpoint,{icon:myIcon1});
        }else{
     	           marker.setPosition(newpoint);  
	      } 
	      
	    //    map.openInfoWindow(NewinfoWindow,newpoint);  
	      $('#cityname').val(datas.result.addressComponent.city); 
	      if(makenow ==  false){
	  	            makenow = true;
	               map.addOverlay(marker);
	      }
	 }else{  
	 } 
}
/*构造搜索*/
 var markerslist = null;
 	 
  //	$.getScript('http://api.map.baidu.com/geocoder/v2/?ak=<?php echo $_smarty_tpl->tpl_vars['baidumapkey']->value;?>
&callback=renderReverse&location=<?php echo $_smarty_tpl->tpl_vars['dlat']->value;?>
,<?php echo $_smarty_tpl->tpl_vars['dlng']->value;?>
&output=json&pois=1'); //不初始化小人
function miaodian(datas){
	markerslist=new Array();
	$('#search_result').html('');
	var  wordearray = ['A','B','C','D','E','F','G','H','I','J','K','L','M'];
	makenow = false;
	 $.each(datas, function(i,val){  
         		    	   if(i == 0){  
         		    	       var tempoint =   new BMap.Point(val.location.lng,val.location.lat);//imageOffset: new BMap.Size(-32,0 - i * 46)}
         		    	        map.centerAndZoom(tempoint,map.getZoom()); 
         		    	   } 
                     var icon = new BMap.Icon("/upload/map/mapIcon.png", new BMap.Size(90,44),{offset: new BMap.Size(45,46),imageOffset: new BMap.Size(-32,0 - i * 47)});//infoWindowAnchor:new BMap.Size(33,44),offset:new BMap.Size(0,0)}
         		         var mkr = new BMap.Marker(new BMap.Point(val.location.lng,val.location.lat), {icon: icon});  
         		        var temp_linksss = savemapurl.replace('@lat@',val.location.lat).replace('@lng@', val.location.lng).replace('@mapname@', encodeURI(val.name)); 
         		         var contentm = '<p class="infoTitle">'+val.name+'</p><p class="infoAddress">'+val.address+'</p><p>附近共有'+val.shuliang+'家餐厅</p>';
         		         if(val.shuliang > 0){
         		            contentm += '<a href="'+temp_linksss+'" class="mapBtns">查看餐厅</a>';
         		         }
         		       
         		    	   var infoWindow2 = new BMap.InfoWindow(contentm); 
                     markerslist.push(infoWindow2);
                     mkr.addEventListener("click", function(){this.openInfoWindow(infoWindow2);});
                     map.addOverlay(mkr);  
                    $('#search_result').append('<div class="result-block" lng="'+ val.location.lng+'" lat="'+ val.location.lat+'" index="'+i+'"><div class="marker ui_2">'+wordearray[i]+'</div><p class="result-name">'+val.name+'</p><p class="result-address">'+val.address+'</p><p class="result-nearby">附近有<span class="highlight">'+val.shuliang+'</span>家餐厅</p></div>');
                           
   });  
}
function makedivpage(shuliang,pagenum){
	var pagesize = 12;
	var pageall = shuliang/pagesize > parseInt(shuliang/pagesize) ?parseInt(shuliang/12)+1:parseInt(shuliang/12);
   var gotopage = 0;
   
	 $('#search_result').append('<div class="result-pager result_pagination light-theme simple-pagination"><ul></ul></div>'); 
	 if(pagenum != 0){
	 	    gotopage = Number(pagenum)-1;
	     $('.result-pager ul').append('<li class="active"><span class="current prev" onclick="dosearchmap('+gotopage+');">&lt;</span></li>');
	 } 
	var tempage = pageall > 5?5:pageall;
	 for(var i=0;i<tempage;i++){
	 	var c = i+1;
	 	   if(i == pagenum){
	 	   	   
	 	       $('.result-pager ul').append('<li class="active"><span class="current" onclick="dosearchmap('+i+');">'+c+'</span></li>');
	 	   }else{
	 	   	
	 	   	$('.result-pager ul').append('<li><a href="javascript:void(0);" class="page-link"  onclick="dosearchmap('+i+');">'+c+'</a></li>'); 
	 	   }
	 } 
	 if(pagenum != pageall){
	 	   gotopage = Number(pagenum)+1;
	    $('.result-pager ul').append('<li><a href="javascript:void(0);" class="page-link next" onclick="dosearchmap('+gotopage+');">&gt;</a></li>'); //每页12个  
	 }
}
$('#search_result  .result-block').live("click", function() {    
	$(this).addClass('ui_current').siblings().removeClass('ui_current');
	$('#mysetdiv').hide();
	var checkobj = $(this).attr('index');
	if(checkobj != undefined){
	     var tempoint =   new BMap.Point($(this).attr('lng'),$(this).attr('lat'));//imageOffset: new BMap.Size(-32,0 - i * 46)}
	      map.openInfoWindow(markerslist[checkobj],tempoint);  
        map.centerAndZoom(tempoint,map.getZoom()); 
	}
});

$('#run-searchAddress').click(function(){
	if($(this).val()  ==  $(this).attr('tip')){
	  $(this).val('');
	  $(this).css('color','rgb(0, 0, 0)');
	}
});
$('#run-searchAddress').blur(function(){
	if($(this).val()  == ''){
		$(this).val($(this).attr('tip'));
		$(this).css('color','#999');
	}
});
 

 
  
</script>
<div style="clear:both;"></div>
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


</div>
</body>
</html>
<?php }} ?>