<?php
/*
*   method 方法  包含所有会员相关操作
    管理员/会员  添加/删除/编辑/用户登陆
    用户日志其他相关连的通过  memberclass关联
*/
class method   extends baseclass
{ 
	
	
	 function index(){ 
	  	$id =IFilter::act(IReq::get('id')); 
	  	if($id > 0){
	 	
	 	     $checkinfo =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."area where id=".$id."  "); 
	 	     
	 	     if(empty($checkinfo)){
	 	          	$link = IUrl::creatUrl('wxsite/index');
	    	            $this->message('',$link); 
	 	   
	 	     }
	 	     $checkinfo2 =  $this->mysql->select_one("select id,name,parent_id from ".Mysite::$app->config['tablepre']."area where parent_id=".$id."  "); 
	 	     if(empty($checkinfo2)){
	 	                ICookie::set('lng',$checkinfo['lng'],2592000);  
	                	ICookie::set('lat',$checkinfo['lat'],2592000);  
	    	            ICookie::set('mapname',$checkinfo['name'],2592000);  
	    	            ICookie::set('myaddress',$checkinfo['id'],2592000);  
	    	            $cookmalist = ICookie::get('cookmalist');
	    	            $cooklnglist = ICookie::get('cooklnglist');
	    	            $cooklatlist = ICookie::get('cooklatlist'); 
	    	            $link = IUrl::creatUrl('wxsite/shoplist');
	    	            $this->message('',$link); 
	       }
	 	 
	 	 
	    }  
	 	  
	 }
	 function shoplist(){
	     $nowID= intval(ICookie::get('myaddress'));
	     if(empty($nowID)){
	 	     $link = IUrl::creatUrl('wxsite/index'); 
	       $this->message('',$link); 
	     } 
	 	  $templist = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shoptype where  cattype = 0 and parent_id = 0 and is_search = 1 and type ='checkbox'  order by orderid asc limit 0,1000"); 
		  $data['caipin'] = array();
	    if(!empty($templist)){
		 	  $data['caipin']  = $this->mysql->getarr("select id,name from ".Mysite::$app->config['tablepre']."shoptype where parent_id='".$templist['id']."'  ");
		  }
		  $shopsearch = IFilter::act(IReq::get('search_input')); 
		  $data['search_input'] = $shopsearch;
		  
		  
		  
		  
		  Mysite::$app->setdata($data);  
	 }
	 function shoplistdata(){
	 	 $where = '';  
	   $shopsearch = IFilter::act(IReq::get('search_input')); 
	   $shopsearch = urldecode($shopsearch); 
	   if(!empty($shopsearch)) $where=" and b.shopname like '%".$shopsearch."%' "; 
	   $areaid=  ICookie::get('myaddress');  
	   $catid = intval(IReq::get('catid'));
	   $order = intval(IReq::get('order'));
	   $order = in_array($order,array(1,2))? $order:0; 
	   //构造菜品查询
	   $where2 = '';
	    
	   if($catid > 0) $where2 = 'where sh.second_id = '.$catid; 
	   $checkareaid = $areaid;
	   if($checkareaid > 0) $where2 = empty($where2) ?' where  ard.areaid = '.$checkareaid:$where2.' and  ard.areaid = '.$checkareaid;
	  
	   $where = empty($where2)? $where:$where." and b.id in(select ard.shopid from ".Mysite::$app->config['tablepre']."areashop as ard left join ".Mysite::$app->config['tablepre']."shopsearch  as sh  on ard.shopid = sh.shopid   ".$where2."  group by shopid  ) "; 
	   
      //计算距离远近的方法
	   //   where  2  公里
	   $psset = Mysite::$app->config['psset'];
	   $locationtype = 0; 
	   if(!empty($psset)){
	      	 $psinfo = unserialize($psset);
	      	 $locationtype = $psinfo['locationtype']; 
	   }
	    if($locationtype == 1){
	         $lng = 0;
	         $lat = 0;
	         if($checkareaid > 0){
	              $areainfo =    $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."area where id=".$checkareaid."  ");
	              if(!empty($areainfo)){
	                $lng = $areainfo['lng'];
	                $lat = $areainfo['lat']; 
	                
	              } 
	         }  
	           $bili = intval(Mysite::$app->config['servery']/1000);
		          $bili = $bili*0.009;
	         $where = empty($where)?'and   SQRT((`lat` -'.$lat.') * (`lat` -'.$lat.' ) + (`lng` -'.$lng.' ) * (`lng` -'.$lng.' )) < '.$bili.' ': $where.' and SQRT ((`lat` -'.$lat.') * (`lat` -'.$lat.' ) + (`lng` -'.$lng.' ) * (`lng` -'.$lng.' )) < '.$bili.'';
           //构造排序条件
           //echo $where;
          // exit;//where  SQRT ((`lat` -34.805885) * (`lat` -34.805885 ) + (`lng` -113.664233 ) * (`lng` -113.664233 )) < 0.045  
           $orderarray = array(
           '0'=>' (`lat` -'.$lat.') * (`lat` -'.$lat.' ) + (`lng` -'.$lng.' ) * (`lng` -'.$lng.' ) ASC  ',
           '1'=>'limitcost desc',
           '2'=>'limitcost asc'
           );
      }else{
      	$orderarray = array(
           '0'=>' b.sort asc ',
           '1'=>'limitcost desc',
           '2'=>'limitcost asc'
           ); 
      }
     
	     $templist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shoptype where  cattype = 1 and parent_id = 0 and is_main =1  order by orderid asc limit 0,1000"); 
			 $attra['input'] = 0;
			 $attra['img'] = 0;
			 $attra['checkbox'] = 0; 
			 foreach($templist as $key=>$value){
			 	  if($value['type'] == 'input'){
			 	   $attra['input'] =  $attra['input'] > 0?$attra['input']:$value['id'];
			 	  }elseif($value['type'] == 'img'){
			 	  	 $attra['img'] =  $attra['img'] > 0?$attra['img']:$value['id'];
			 	  }elseif($value['type'] == 'checkbox'){
			 	  	$attra['checkbox'] =  $attra['checkbox'] > 0?$attra['checkbox']:$value['id'];
			 	  }
			 } 
			 /*获取店铺*/
		  $pageinfo = new page();
		  $pageinfo->setpage(intval(IReq::get('page'))); 
	    $list = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shopfast as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id  where  b.is_pass = 1 ".$where."    order by ".$orderarray[$order]." limit ".$pageinfo->startnum().", ".$pageinfo->getsize()."");
	       
			$nowhour = date('H:i:s',time()); 
      $nowhour = strtotime($nowhour);
      $templist = array();
       $cxclass = new sellrule();  
      if(is_array($list)){
			    foreach($list as $keys=>$values){  
			     
			    		if($values['id'] > 0){
			    			$values['shoplogo'] = empty($values['shoplogo'])? Mysite::$app->config['siteurl'].Mysite::$app->config['shoplogo']:Mysite::$app->config['siteurl'].$values['shoplogo'];
			          $checkinfo = $this->shopIsopen($values['is_open'],$values['starttime'],$values['is_orderbefore'],$nowhour); 
			          $values['opentype'] = $checkinfo['opentype'];
			          $values['newstartime']  =  $checkinfo['newstartime'];  
			          $attrdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shopattr where  cattype = 1 and shopid = ".$values['id']."");
			          $cxclass->setdata($values['id'],1000,$values['shoptype']); 
			          $checkps = 	 $this->pscost($values,1); 
			          $values['pscost'] = $checkps['pscost'];
			          $cxinfo = $cxclass->getdata(); 
			          $values['gzdata']='';
			          if(isset($cxinfo['gzdata'])){
			          	foreach($cxinfo['gzdata'] as $km=>$vk){
			          	  $values['gzdata'] = $vk;
			          	}
			          } 
			          $values['attrdet'] = array();
			          foreach($attrdet as $k=>$v){
			          	   if($v['firstattr'] == $attra['input']){
			          	   	$values['attrdet']['input'] = $v['value'];
			          	   }elseif($v['firstattr'] == $attra['img']){
			          	   	$values['attrdet']['img'][] = $v['value'];
			          	   }elseif($v['firstattr'] == $attra['checkbox']){
			          	   	 	$values['attrdet']['checkbox'][] = $v['value'];
			          	   } 
			          }
			          $templist[] = $values;
			     }
	       } 
	    }
	    $data  = $templist;
	    $this->success($data);
	 } 
	 function shopshow(){//店铺详情
	 	 $id = intval(IReq::get('id')); 
	 	 $data['id'] = $id; 
	 	  
	 	 $shopinfo = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shop where id='".$id."' ");   //店铺基本信息
	 	 if(empty($shopinfo)){
	 	 	//需要进行跳转
	 	 	 $link = IUrl::creatUrl('wxsite/index'); 
	     $this->message('',$link); 
	 	 } 
	 	 $nowID= intval(ICookie::get('myaddress'));
	     if(empty($nowID)){
	 	     $link = IUrl::creatUrl('wxsite/index'); 
	       $this->message('',$link); 
	     }
	 	 $shopdet = array();
	 	 if(empty($shopinfo['shoptype'])){
	 	 	 $shopdet = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopfast where shopid='".$id."' "); 
	 	 }elseif($shopinfo['shoptype'] == 1){
	 	 	 $shopdet = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopmarket where shopid='".$id."' "); 
	 	 }
	 	 $nowhour = date('H:i:s',time()); 
	 	 $data['openinfo'] = $this->shopIsopen($shopinfo['is_open'],$shopinfo['starttime'],$shopdet['is_orderbefore'],$nowhour); 
	 	   
	 	  
	 	 $data['shopinfo'] = $shopinfo;
	 	 $data['shopdet'] = $shopdet;
	 	  $templist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."goodstype where shopid='".$id."' ");
	 	  $data['goodstype'] = array();
	 	 foreach($templist as $key=>$value){
	 	 	$value['det'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."goods where shopid='".$id."' and typeid =".$value['id']." ");
	 	  $data['goodstype'][]  = $value; 
	 	 }
	 	 $shopdet['id'] = $id; 
	 	 $shopdet['shoptype']=1;
	 	 $tempinfo =   $this->pscost($shopdet,1); 
                $backdata['pstype'] = $tempinfo['pstype'];
                $backdata['pscost'] = $tempinfo['pscost'];
     $data['psinfo'] = $backdata;
     $data['mainattr'] = array(); 
     $templist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shoptype where  cattype = ".$shopinfo['shoptype']." and parent_id = 0 and is_main =1  order by orderid asc limit 0,1000");
		 foreach($templist as $key=>$value){
	  	 $value['det'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shoptype where parent_id = ".$value['id']." order by orderid asc  limit 0,20");  
	  	 $data['mainattr'][] = $value;
	 	 } 
	 	 $data['shopattr'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."shopattr  where  cattype = ".$shopinfo['shoptype']." and shopid = '".$shopinfo['id']."'  order by firstattr asc limit 0,1000");
	 	 
		
	 	 Mysite::$app->setdata($data); 
   } 
   function shopgoodslist(){//点菜
   	 	$nowID= intval(ICookie::get('myaddress'));
	     if(empty($nowID)){
	 	     $link = IUrl::creatUrl('wxsite/index'); 
	       $this->message('',$link); 
	     }
	 	 $id = IFilter::act(IReq::get('id'));    
   	 $shopinfo = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shop where id='".$id."' ");
   	 $data['shopinfo'] = $shopinfo;
   	 if(empty($shopinfo)){
	 	 	//需要进行跳转
	 	 	 $link = IUrl::creatUrl('wxsite/index'); 
	     $this->message('',$link); 
	 	 } 
	 	 $data['goodstype'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."goodstype where shopid='".$id."' ");
	 	 $data['goodslist'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."goods where shopid='".$id."' ");
   	 Mysite::$app->setdata($data); 
   }
   function shopcart(){//购物车
   	$nowID= intval(ICookie::get('myaddress'));
	     if(empty($nowID)){
	 	     $link = IUrl::creatUrl('wxsite/index'); 
	       $this->message('',$link); 
	     }
   	 $id = IFilter::act(IReq::get('id'));    
   	$data['scoretocost'] = Mysite::$app->config['scoretocost']; 
   	//	id	card 优惠劵卡号	card_password 优惠劵密码	status 状态，0未使用，1已绑定，2已使用，3无效	creattime 制造时间	cost 优惠金额	limitcost 购物车限制金额下限	endtime 失效时间	uid 用户ID	username 用户名	usetime 使用时间	name
   	$data['juanlist'] =  $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."juan  where uid='".$this->member['uid']."' and endtime > ".time()." and status = 1   ");
    
   	
   	
   	
   	 $shopinfo =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopfast as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id  where shopid = ".$id."   ");  
		 if(empty($shopinfo)){
		 	 $shopinfo =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopmarket as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id  where shopid = ".$id."   ");  
		 } 
	   $nowtime = time(); 
		 $timelist = array();
		 $info = explode('|',$shopinfo['starttime']);
		 $info = is_array($info)?$info:array($info);
		 $data['is_open'] = 0;
		 $dototime = time()+$shopinfo['maketime']*60;
		 foreach($info as $key=>$value){
		   if(!empty($value)){
		     $temptime = explode('-',$value);
		     if(count($temptime) == 2){ //开始转换
		      
		     	   $btime = strtotime($temptime[0].':00');
		     	   $etime = strtotime($temptime[1].':00');
		     	   $whtime = $btime;
		     	  	while ($whtime < $etime){
		     	  		  if($whtime < $etime && $whtime >= $nowtime && $whtime > $dototime ){
                  $timelist[] = date('H:i',$whtime);  
                  $data['is_open'] = 1;
                  }
                  $whtime +=900; 
              }
		     	    
		     }
		   }
		 }
     if($shopinfo['is_open'] == 0  || $shopinfo['is_pass'] == 0){
		 	  $data['is_open'] = 0;
		 }
	   $data['timelist'] =$timelist;  
   	$data['deaddress'] =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."address  where userid = ".$this->member['uid']." and `default`=1   "); 
   	
   	 $checkareaid = $nowID;
	    $dataareaids = array();
	    $areadata = array();
	    while($checkareaid > 0){ 
	  	     $temp_check =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."area where id ='".$checkareaid."'   order by id desc limit 0,50");
	  	     if(empty($temp_check)){
	  	       break;
	  	     }
	  	     if(in_array($checkareaid,$dataareaids)){
	  	       break;
	  	     }
	  	     $dataareaids[] = $checkareaid;
	  	     $checkareaid = $temp_check['parent_id']; 
	  	     $areadata[] = $temp_check['name']; 
	    }
	    $areadata = array_reverse($areadata);
	    $data['areainfo'] = join('',$areadata); 
   	
   	
   	
   	 
   	Mysite::$app->setdata($data); 
   }
   function member(){//用户中心
   	 $data['juanshu'] = $this->mysql->counts("select *  from ".Mysite::$app->config['tablepre']."juan where uid='".$this->member['uid']."'  and status = 1 order by id asc limit 0,50");
   	Mysite::$app->setdata($data); 
   	
   }
   function address(){
   	$link = IUrl::creatUrl('wxsite/index'); 
	  if($this->member['uid'] == 0)  $this->message('',$link); 
	  $tarelist = $this->mysql->getarr("select id,phone,address,areaid1,areaid2,areaid3,contactname,`default`  from ".Mysite::$app->config['tablepre']."address where userid='".$this->member['uid']."'   order by id asc limit 0,50");
	  $arelist = array();
	  $areaid=array();
	  foreach($tarelist as $key=>$value){
	     $areaid[] = $value['areaid1'];
	     $areaid[] = $value['areaid3'];
	     $areaid[] = $value['areaid2'];  
	  }
	  if(count($areaid) > 0){ 
	     $areaarr = $this->mysql->getarr("select id,name from ".Mysite::$app->config['tablepre']."area  where id in(".join(',',$areaid).")  order by id asc limit 0,1000"); 
	     foreach($areaarr as $key=>$value){
	  	    $arelist[$value['id']] = $value['name'];
	     } 
	  }
	  $data['arealist'] = $tarelist;
	  $data['areaarr'] = $arelist;
	  Mysite::$app->setdata($data); 
   }
   function editaddress(){
    	$link = IUrl::creatUrl('wxsite/index'); 
	   if($this->member['uid'] == 0)  $this->message('',$link); 
   }
   function order(){
   		$link = IUrl::creatUrl('wxsite/index'); 
	    if($this->member['uid'] == 0)  $this->message('',$link); 
   }
   function userorder(){
			$link = IUrl::creatUrl('wxsite/index'); 
	 if($this->member['uid'] == 0)  $this->message('',$link); 
	  $pageinfo = new page();
	  $pageinfo->setpage(intval(IReq::get('page')),20);  
	  // 
		$datalist = $this->mysql->getarr("select id,shopname,allcost,addtime,status,is_ping,shoptype from ".Mysite::$app->config['tablepre']."order where buyeruid='".$this->member['uid']."' order by id desc limit ".$pageinfo->startnum().", ".$pageinfo->getsize()."");   
	  $temparray = array('0'=>'外卖','1'=>'超市','2'=>'其他');
	  $backdata = array();
	  foreach($datalist as $key=>$value){  
				$listdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."orderdet where order_id='".$value['id']."'");  
				$value['det'] = '';
				foreach($listdet as $k=>$v){
					$value['det'] .= $v['goodsname'].',';
				}
				$value['shoptype'] = $temparray[$value['shoptype']];
				$value['addtime'] = date('m-d H:i',$value['addtime']);
				$backdata[] =$value;
		}
		$this->success($backdata);
	}
	function ordershow(){
		 	$link = IUrl::creatUrl('wxsite/index'); 
	 if($this->member['uid'] == 0)  $this->message('未登陆',$link); 
	  $orderid = intval(IReq::get('orderid'));  
	  if(!empty($orderid)){
	  	 
	     	$order = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."order where buyeruid='".$this->member['uid']."' and id = ".$orderid."");   
	     
	     	if(empty($order)){
	     		$data['order'] = '';
	     		Mysite::$app->setdata($data);
	     	}else{
	     	     $order['ps'] = $order['shopps'];
	     	     // 超市商品总价	 超市配送配送	shopcost 店铺商品总价	shopps 店铺配送费	pstype 配送方式 0：平台1：个人	bagcost  
       	     $orderdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."orderdet where order_id='".$order['id']."'");  
	            $order['cp'] = count($orderdet); 
	            $buyerstatus= array(
	     	     '0'=>'等待处理',
	     	     '1'=>'订餐成功处理中',
	     	     '2'=>'订单已发货',
	     	     '3'=>'订单完成',
	     	     '4'=>'订单已取消',
	     	     '5'=>'订单已取消'
	     	     );
	     	     $paytypelist = array('outpay'=>'货到支付','open_acout'=>'账号余额支付');  
	     	     $paylist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."paylist   order by id asc limit 0,50");
	     	     if(is_array($paylist)){
	     	       foreach($paylist as $key=>$value){
	     	     	    $paytypelist[$value['loginname']] = $value['logindesc'];
	     	       }
	            }
	     	     $paytypearr = $paytypelist; 
	     	      $order['surestatus'] = $order['status'];
	     	      $order['basetype'] = $order['paytype'];
	     	      $order['basepaystatus'] =$order['paystatus'];
	     	     $order['status'] = $buyerstatus[$order['status']];
	     	     $order['paytype'] = $paytypearr[$order['paytype']];
	     	     $order['paystatus'] = $order['paystatus']==1?'已支付':'未支付';
	     	     $order['addtime'] = date('Y-m-d H:i:s',$order['addtime']);
	     	     $order['posttime'] = date('Y-m-d H:i:s',$order['posttime']);
	     	  
	     	     
	     	     $data['order'] = $order;
	           $data['orderdet'] = $orderdet;
	          
	           Mysite::$app->setdata($data);
	           
	       }
	  }else{
	  	$data['order'] = '';
	  	Mysite::$app->setdata($data);
	  }
	}
	/*评价订单*/
	function commentorder(){
	  	$link = IUrl::creatUrl('wxsite/index'); 
	    if($this->member['uid'] == 0)  $this->message('未登陆',$link); 
	    $link = IUrl::creatUrl('wxsite/order'); 
	    $orderid = intval(IReq::get('orderid'));  
	    if(!empty($orderid)){
	  	 
	     	 $order = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."order where buyeruid='".$this->member['uid']."' and id = ".$orderid."");   
	     
	     	if(empty($order)){
	     		$data['order'] = '';
	     		Mysite::$app->setdata($data);
	     	}else{
	     	     $data['order'] =$order;
       	     $orderdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."orderdet where order_id='".$order['id']."'");   
	           $data['orderdet'] = $orderdet;
	           $tempcoment = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."comment where orderid='".$order['id']."'");  
	           $data['comment'] = array();
	           foreach($tempcoment as $key=>$value){
	             $data['comment'][$value['orderdetid']] = $value;
	           } 
	           //  id		orderdetid	shopid	goodsid	uid	content	addtime	replycontent	replytime	 评分	is_show 0展示，1不展示
	           Mysite::$app->setdata($data);
	           
	       }
	    }else{
	  	  $data['order'] = '';
	  	  Mysite::$app->setdata($data);
	    } 
	   
	}
	//积分操作
	function gift(){
	  	$link = IUrl::creatUrl('wxsite/index'); 
	    if($this->member['uid'] == 0)  $this->message('未登陆',$link); 
	}
	//积分记录
	function giftlog(){
		$data['logstat'] = array('0'=>'待处理','1'=>'已处理，配送中','2'=>'已发货','3'=>'兑换成功','4'=>'已取消兑换'); 
		 Mysite::$app->setdata($data);
	}
	//兑换产品列表
	function giftlist(){ 
	}
	function juan(){ 
		$wjuan = array('shuliang'=>0,'list'=>array());
		$ujuan = array('shuliang'=>0,'list'=>array());
		$ojuan = array('shuliang'=>0,'list'=>array());
		$nowtime = time();
		$wjuan['shuliang'] =  $this->mysql->counts("select * from ".Mysite::$app->config['tablepre']."juan    where uid = ".$this->member['uid']." and endtime > ".$nowtime." and status = 1 ");
		$wjuan['list'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."juan where uid = ".$this->member['uid']." and endtime > ".$nowtime." and status = 1 ");   
		$ujuan['shuliang'] =  $this->mysql->counts("select * from ".Mysite::$app->config['tablepre']."juan    where uid = ".$this->member['uid']."  and status = 2 ");
		$ujuan['list'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."juan where uid = ".$this->member['uid']."   and status = 2 ");     
		
		$ojuan['shuliang'] =  $this->mysql->counts("select * from ".Mysite::$app->config['tablepre']."juan    where uid = ".$this->member['uid']." and endtime < ".$nowtime." and (status = 1 or status =3)");
		$ojuan['list'] = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."juan where uid = ".$this->member['uid']." and endtime < ".$nowtime." and (status = 1 or status =3)  ");  
		
		$data['wjuan'] = $wjuan;
		$data['ujuan'] = $ujuan;
		$data['ojuan'] = $ojuan; 
		Mysite::$app->setdata($data);
	}
	function cart(){  
		 $Cart = new smCart;   
     $carinfo = $Cart->getMyCart();  
     $shopid = intval(IReq::get('shopid'));  
     $backdata = array();
     if($shopid  > 0){
        if(isset($carinfo['list'][$shopid]['data'])){
        	      $backdata['list'] = $carinfo['list'][$shopid]['data'];
        	      $backdata['sumcount'] =$carinfo['list'][$shopid]['count'];
        	      $backdata['sum'] =$carinfo['list'][$shopid]['sum'];
        	      $backdata['bagcost'] =$carinfo['list'][$shopid]['bagcost'];
        	      //获取促销
        	        $cxclass = new sellrule();
        	      if($carinfo['list'][$shopid]['shopinfo']['shoptype'] ==1){
	   	                    $shopcheckinfo =   $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopmarket as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id where a.shopid = '".$shopid."'    ");  
	               }else{
	                    $shopcheckinfo =   $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopfast as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id where a.shopid = '".$shopid."'    ");  
	              }
                $cxclass->setdata($shopid,$backdata['sum'],$carinfo['list'][$shopid]['shopinfo']['shoptype']);   
        	      $cxinfo = $cxclass->getdata();
        	      $backdata['surecost'] = $cxinfo['surecost'];
        	      $backdata['downcost'] = $cxinfo['downcost'];
        	      $backdata['gzdata'] = isset($cxinfo['gzdata'])?$cxinfo['gzdata']:array(); 
        	      
        	 
        	      
                $tempinfo =   $this->pscost($shopcheckinfo,$backdata['sumcount']); 
                $backdata['pstype'] = $tempinfo['pstype'];
                $backdata['pscost'] = $cxinfo['nops'] == true?0:$tempinfo['pscost']; 
                $backdata['canps'] = $tempinfo['canps']; 
        }else{
        	$this->message(array());
        //  $this->hjson(array('error'=>true,'data'=>array()));
        }
        
     }else{
        $this->message(array());//$backdata = $carinfo;
     } 
     $this->success($backdata);
	}
	function makeorder(){
		 $info['shopid'] = intval(IReq::get('shopid'));//店铺ID
		 $info['remark'] = IFilter::act(IReq::get('remark'));//备注
		 $info['paytype'] =  'outpay';//支付方式IFilter::act(IReq::get('paytype'));//支付方式
		 $info['dikou'] =  intval(IReq::get('dikou'));//抵扣金额
	 	 $info['username'] = IFilter::act(IReq::get('username')); 
		 $info['mobile'] = IFilter::act(IReq::get('mobile'));
		 $info['addressdet'] = IFilter::act(IReq::get('addressdet'));
		 $info['senddate'] = date('Y-m-d',time());// IFilter::act(IReq::get('senddate'));
		 $info['minit'] = IFilter::act(IReq::get('minit')); 
		 $info['juanid']  =  intval(IReq::get('juanid'));//优惠劵ID
		 $info['ordertype'] = 5;//订单类型 
		 $peopleNum = IFilter::act(IReq::get('peopleNum'));  
		 $info['othercontent'] ='';//empty($peopleNum)?'':serialize(array('人数'=>$peopleNum)); 
		  
		 if(empty($info['shopid'])) $this->message('店铺ID错误');
		 $Cart = new smCart; 
	   $carinfo = $Cart->getMyCart();  
		 if(!isset($carinfo['list'][$info['shopid']]['data'])) $this->message('对应店铺购物车商品为空');
		 if($carinfo['list'][$info['shopid']]['shopinfo']['shoptype'] == 1){
		 	 $shopinfo=   $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopmarket as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id where a.shopid = '".$info['shopid']."'    ");   
	   }else{
	      $shopinfo=   $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."shopfast as a left join ".Mysite::$app->config['tablepre']."shop as b  on a.shopid = b.id where a.shopid = '".$info['shopid']."'    ");   
	   }
	   
	   
		 if(empty($shopinfo))   $this->message('店铺获取失败');
		 $checkps = 	 $this->pscost($shopinfo,$carinfo['list'][$info['shopid']]['count']); 
		 if($checkps['canps'] != 1) $this->message('该店铺不在配送范围内');  
		 $info['cattype'] = 0;//
		 if(empty($info['username'])) 		  $this->message('联系人不能为空'); 
	  	if(!IValidate::suremobi($info['mobile']))   $this->message('请输入正确的手机号'); 
		 if(empty($info['addressdet'])) $this->message('详细地址为空');
	 
	   $info['userid'] = !isset($this->member['score'])?'0':$this->member['uid'];
	    if(Mysite::$app->config['allowedguestbuy'] != 1){
	     if($info['userid']==0) $this->message('禁止游客下单');
	   }
	   $ip_l=new iplocation(); 
     $ipaddress=$ip_l->getaddress($ip_l->getIP());  
     if(isset($ipaddress["area1"])){
		   $info['ipaddress']  = $ipaddress['ip'].mb_convert_encoding($ipaddress["area1"],'UTF-8','GB2312');//('GB2312','ansi',);
	   } 
	  //area1 二级地址名称	area2 三级地址名称	area3
	   $nowID= intval(ICookie::get('myaddress'));
	  if(empty($nowID)) $this->message('未选择配送区域');
	  $checkareaid = $nowID;
	  $dataareaids = array();
	  while($checkareaid > 0){
	  	 
	  	 $temp_check =  $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."area where id ='".$checkareaid."'   order by id desc limit 0,50");
	  	 if(empty($temp_check)){
	  	   break;
	  	 }
	  	 if(in_array($checkareaid,$dataareaids)){
	  	   break;
	  	 }
	  	 $dataareaids[] = $checkareaid;
	  	 $checkareaid = $temp_check['parent_id']; 
	  	 
	  }
	  $data['areaids'] = join(',',$dataareaids);
	  /*
	  $checksend = Mysite::$app->config['ordercheckphone'];
    if($checksend == 1){
    	  if(empty($this->member['uid'])){
    	  	  $checkphone = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."mobile where phone ='".$info['mobile']."'   order by addtime desc limit 0,50");
    	  	  if(empty($checkphone)) $this->message('短信验证码不能为空');
    	  	  if(empty($checkphone['is_send'])){ 
    	  	    $mycode = IFilter::act(IReq::get('phonecode'));
    	  	    if($mycode == $checkphone['code']){
    	  	       $this->mysql->update(Mysite::$app->config['tablepre'].'mobile',array('is_send'=>1),"phone='".$info['mobile']."'");  
    	  	    }else{
    	  	       $this->message('验证码不一致');
    	  	    } 
    	  	  } 
    	  }
    }*/
	   
	   
	   
	   
	   
		 $data['shopcost'] = 0;//:店铺商品总价
		 $data['shopps'] = 0;//店铺配送费  
	 
		 $data['bagcost']=0;//:打包费 
		 //获取店铺商品总价  获取超市商品总价
		 $data['shopcost'] = $carinfo['list'][$info['shopid']]['sum'];
		 $data['shopps'] = $checkps['pscost']; 
		 $data['bagcost'] =  $carinfo['list'][$info['shopid']]['bagcost'];
		 //支付方式检测
		 $data['paytype'] = $info['paytype'];
		 $paytype = $info['paytype'];
		 if($paytype != 'outpay'){
			 if($paytype == 'open_acout'){
		     if(Mysite::$app->config['open_acout'] != 1 || $userid == 0){
		  	    $data['paytype'] = 'outpay';
		     }
	     }else{
	    	 $paylist = $this->mysql->counts("select * from ".Mysite::$app->config['tablepre']."paylist where loginname='".$paytype."'  order by id desc  "); 
	    	 if($paylist < 1){
	    		 $data['paytype'] = 'outpay';
	    	 }
	     }
		 } 
		//判断促销 
		$data['cxids'] = '';
		$data['cxcost'] = 0;
		$zpin = array();
		$cattype = $info['cattype'];
		if($data['shopcost'] > 0){
		    $sellrule =new sellrule();
		    $cxtypeid = $cattype+1;
		    $sellrule->setdata($info['shopid'],$data['shopcost'],$shopinfo['shoptype']);
		    $ruleinfo = $sellrule->getdata();  
	      $data['cxcost'] = $ruleinfo['downcost'];
	      $data['cxids'] = $ruleinfo['cxids'];  
	      $zpin = $ruleinfo['zid'];//赠品
	      $data['shopps'] = $ruleinfo['nops'] == true?0:$data['shopps'];
	  }
	  //判断优惠劵
	  $allcost = $data['shopcost'];
	  $data['yhjcost'] = 0;
		$data['yhjids'] = ''; 
		$juanid = $info['juanid'];
		$userid = $info['userid'];
	   if($juanid > 0 && $userid > 0){
	      $juaninfo = $this->mysql->select_one("select *  from ".Mysite::$app->config['tablepre']."juan  where id= '".$juanid."' and uid='".$userid."'  and status = 1 and endtime > ".time()." ");
	   	  if(!empty($juaninfo)){
	   	  	 if($allcost >= $juaninfo['limitcost']){ 
	   	  	 	$data['yhjcost'] =  $juaninfo['cost']; 
	   	  	 	$juandata['status'] = 2;
	   	  	 	$juandata['usetime'] =  time(); 
	   	  	 	 $this->mysql->update(Mysite::$app->config['tablepre'].'juan',$juandata,"id='".$juanid."'");
	   	  	 	$data['yhjids'] = $juanid;
	   	  	 } 
	   	  } 
	   } 
	  //积分抵扣
	  $allcost = $allcost - $data['cxcost'] - $data['yhjcost'];
	  $data['scoredown'] = 0;
	  $dikou = $info['dikou'];
	  if(!empty($userid) && $dikou > 0 && Mysite::$app->config['scoretocost'] > 0 && $allcost > $dikou){
	    	 $checkuser= $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."member where uid='".$userid."'  "); 
	    	 if(is_array($checkuser)){
	    	     $checkscore = $dikou*(intval(Mysite::$app->config['scoretocost']));
	    	    if($checkuser['score']  >= $checkscore){  
	    	   	  $data['scoredown'] =  $checkscore;
	    	 	    $this->mysql->update(Mysite::$app->config['tablepre'].'member','`score`=`score`-'.$checkscore,"uid ='".$userid."' ");	 
	    	    } 
	    	 }
	  }
	  $dikou = $data['scoredown'] > 0?$dikou:0;
	  $allcost = $allcost-$dikou;
	  $data['allcost'] = $allcost+$data['shopps']+$data['bagcost']; //订单应收费用
	  
	  $data['shopuid'] = 0;// 店铺UID	
		$data['shopid'] = 0; //店铺ID	
		$data['shopname'] = '商城'; //店铺名称	
		$data['shopphone'] =  Mysite::$app->config['marketphone']; //店铺电话
		$data['shopaddress'] = '';// 店铺地址	
		$data['pstype'] = $checkps['pstype'] ;
		$data['shoptype'] = $shopinfo['shoptype'];
		//检测店铺
		$senddate = $info['senddate'];
		$minit = $info['minit'];
	  $nowpost = strtotime($senddate.' '.$minit.':00');
	  
	  	    $settime = time() - (10*60); 
	  	    if($settime > $nowpost)  $this->message('提交配送时间和服务器时间相差超过10分钟下单失败'); 
	  	    $temp = strtotime($minit.':00'); 
	  	    $is_orderbefore = $shopinfo['is_orderbefore'] == 0?0:$shopinfo['befortime'];
	  	    $tempinfo = $this->checkshopopentime($is_orderbefore,$nowpost,$shopinfo['starttime']);
	  	    if(!$tempinfo) $this->message('配送时间不在有效配送时间范围'); 
	  	    if($shopinfo['is_open'] != 1) $this->message('店铺暂停营业'); 
	  	    if($shopinfo['limitcost'] >$allcost ) $this->message('商品总价低于最小起送价'.$shopinfo['limitcost']);  
	  	    $data['shopuid'] = $shopinfo['uid'];// 店铺UID	
		      $data['shopid'] = $shopinfo['id']; //店铺ID	
		      $data['shopname'] = $shopinfo['shopname']; //店铺名称	
		      $data['shopphone'] =  $shopinfo['phone']; //店铺电话
		      $data['shopaddress'] = $shopinfo['address'];// 店铺地址	
	  $data['buyeraddress'] = $info['addressdet'];
	  $data['ordertype'] = $info['ordertype'];//来源方式;
	  $data['buyeruid'] = $userid;// 购买用户ID，0未注册用户	
		$data['buyername'] =  $info['username'];//购买热名称
		$data['buyerphone'] = $info['mobile'];// 联系电话   
		$panduan = Mysite::$app->config['man_ispass'];
	  $data['status'] = $panduan == 1?'0':1;
	  $data['paystatus'] = 0;// 支付状态1已支付	
		$data['content'] = $info['remark'];// 订单备注	
	 	  $data['is_make'] = Mysite::$app->config['allowed_is_make'] == 1?0:1;
		//  daycode 当天订单序号
	  $data['ipaddress'] = $info['ipaddress'];	 
		$data['is_ping'] = 0;// 是否评价字段 1已评完 0未评	
		$data['addtime'] = time(); 	  
	  $data['posttime'] = $nowpost;//: 配送时间  
	  $data['othertext'] = $info['othercontent'];//其他说明 	  
	  //  :审核时间
	  $data['passtime'] = time();
	  if($data['status']  == 1){
	  	$data['passtime'] == 0;
	  } 
	  $data['buycode'] = substr(md5(time()),9,6);
	  $data['dno'] = time().rand(1000,9999);
	   $minitime = strtotime(date('Y-m-d',time()));
    $tj = $this->mysql->select_one("select count(id) as shuliang from ".Mysite::$app->config['tablepre']."order where shopid='".$info['shopid']."' and addtime > ".$minitime." limit 0,1000");
	  $data['daycode'] = $tj['shuliang']+1; 
	  $this->mysql->insert(Mysite::$app->config['tablepre'].'order',$data);  //写主订单 
	  $orderid = $this->mysql->insertid(); 
	  $this->orderid = $orderid; 
	  foreach($carinfo['list'][$info['shopid']]['data'] as $key=>$value){ 
	    $cmd['order_id'] = $orderid; 
	    $cmd['goodsid'] = $value['id'];
	    $cmd['goodsname'] = $value['name'];
	    $cmd['goodscost'] = $value['cost'];
	  	$cmd['goodscount'] = $value['count'];
	  	$cmd['shopid'] = $value['shopid'];
	  	$cmd['status'] = 0; 
	  	$cmd['is_send'] = 0;
	  	$this->mysql->insert(Mysite::$app->config['tablepre'].'orderdet',$cmd);
	    $this->mysql->update(Mysite::$app->config['tablepre'].'goods','`count`=`count`-'.$cmd['goodscount'].' ,`sellcount`=`sellcount`+'.$cmd['goodscount'],"id='".$cmd['goodsid']."'"); 
	  }
	  if(is_array($zpin)){
	     foreach($zpin as $key=>$value){
	  		  $datadet['order_id'] = $orderid;
	  	    $datadet['goodsid'] = $key;
	  	    $datadet['goodsname'] = $value['presenttitle'];
	  	    $datadet['goodscost'] = 0;
	  	    $datadet['goodscount'] = 1;
	  	    $datadet['shopid'] = $checkshopid;
	  	    $datadet['status'] = 0; 
	  	    $datadet['is_send'] = 1;
	  	    //更新促销规则中 此赠品的数量 
	  	    $this->mysql->insert(Mysite::$app->config['tablepre'].'orderdet',$datadet);
	  	  	$this->mysql->update(Mysite::$app->config['tablepre'].'rule','`controlcontent`=`controlcontent`-1',"id='".$key."'");
	    } 
	  }
	  $checkbuyer = Mysite::$app->config['allowedsendbuyer']; 
	 	if(Mysite::$app->config['man_ispass']  != 1)
	 	{ 
	 		  $orderCLs = new orderclass($this->mysql);
        $orderCLs->sendmess($orderid);
	  }
	  if($userid ==  0){ 
	  	   ICookie::set('orderid',$orderid,86400);
	  }else{ 
	      //保持地址数据
	    $checkinfo =   $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."address where userid='".$userid."'  ");   
	    if(empty($checkinfo)){
	        $addata['userid'] = $this->member['uid'];
	        $addata['username'] = $this->member['username'];
	        $addata['address'] = $data['buyeraddress'];
	        $addata['phone'] = $data['buyerphone'];
	        $addata['contactname'] = $data['buyername'];
	        $addata['default'] = 1; 
	         $this->mysql->insert(Mysite::$app->config['tablepre'].'address',$addata);
	    }
	      
	  }
	  
	  $Cart->delshop($info['shopid']);
		$this->success($orderid); 
	}
	public static function checkshopopentime($is_orderbefore,$posttime,$starttime){
  	$maxnowdaytime = strtotime(date('Y-m-d',time()));
  	$daynottime = 24*60*60 -1; 
  	$findpostime = false;
  	for($i=0;$i <= $is_orderbefore;$i++){
  		if($findpostime == false){
  		   $miniday = $maxnowdaytime+$daynottime*$i;
  		   $maxday = $miniday+$daynottime; 
  		   $tempinfo = explode('|',$starttime);
  		   foreach($tempinfo as $key=>$value){
  		   	  if(!empty($value)){
  		   	    $temp2 = explode('-',$value);
  		   	    if(count($temp2) > 1){
  		   	    	$minbijiaotime = date('Y-m-d',$miniday);
  		   	    	$minbijiaotime = strtotime($minbijiaotime.' '.$temp2[0].':00');
  		   	    	
  		   	    	$maxbijiaotime = date('Y-m-d',$maxday);
  		   	    	$maxbijiaotime = strtotime($maxbijiaotime.' '.$temp2[1].':00');
  		   	    	 
  		   	    	if($posttime > $minbijiaotime && $posttime < $maxbijiaotime){
  		   	    		$findpostime = true;
  		   	    		break;
  		   	    	}
  		   	    }
  		   	  }
  		   }
  		 
  	  }
  		
  	} 
    return $findpostime; 
   }
   function subshow(){
	  $orderid = intval(IReq::get('orderid'));  
		$userid = empty($this->member['uid'])?0:$this->member['uid']; 
		$orderid = intval(IReq::get('orderid')); 
		if(empty($orderid)) $this->message('订单获取失败');
		if($userid == 0){ 
			$neworderid = ICookie::get('orderid'); 
			if($orderid != $neworderid) $this->message('订单无查看权限1');
		} 
	  if($orderid < 1){ 
	  	 $this->message('订单获取失败');
	  }
		$order = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."order where buyeruid='".$this->member['uid']."' and id = ".$orderid."");   
		$order['ps'] = $order['shopps'];
		// 超市商品总价	 超市配送配送	shopcost 店铺商品总价	shopps 店铺配送费	pstype 配送方式 0：平台1：个人	bagc 
	  if(empty($order)){ 
	  	 $this->message('订单获取失败');
	  } 
  	$orderdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."orderdet where order_id='".$order['id']."'");  
	  $order['cp'] = count($orderdet); 
	  $buyerstatus= array(
		'0'=>'等待处理',
		'1'=>'订餐成功处理中',
		'2'=>'订单已发货',
		'3'=>'订单完成',
		'4'=>'订单已取消',
		'5'=>'订单已取消'
		);
		$paytypelist = array('outpay'=>'货到支付','open_acout'=>'账号余额支付');  
		$paylist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."paylist   order by id asc limit 0,50");
		if(is_array($paylist)){
		  foreach($paylist as $key=>$value){
			    $paytypelist[$value['loginname']] = $value['logindesc'];
		  }
	  }
		$paytypearr = $paytypelist; 
		$order['surestatus'] = $order['status'];
		$order['status'] = $buyerstatus[$order['status']];
		$order['paytype'] = $paytypearr[$order['paytype']];
		$order['paystatus'] = $order['paystatus']==1?'已支付':'未支付';
		$order['addtime'] = date('Y-m-d H:i:s',$order['addtime']);
		$order['posttime'] = date('Y-m-d H:i:s',$order['posttime']); 
		$data['order'] = $order;
	  $data['orderdet'] = $orderdet;
	  Mysite::$app->setdata($data); 
	}
	function shop(){
			$link = IUrl::creatUrl('wxsite/index'); 
	    if($this->member['uid'] == 0)  $this->message('未登陆',$link);  
	    	$nowdata = date('Y-m-d',time());
	  $mintime = strtotime($nowdata);
	  $maxtime = strtotime($nowdata.' 23:59:59');
	  $where = ' and  posttime > '.$mintime.' and posttime < '.$maxtime;//发货时间
	  
	   $tjlist = $this->mysql->getarr("select count(id) as shuliang,status from ".Mysite::$app->config['tablepre']."order where shopuid=".$this->member['uid']." ".$where."  group by status order by id asc limit 0,50");
	  $data['tj'] = array();
	  foreach($tjlist as $key=>$value){
	    $data['tj'][$value['status']] = $value['shuliang'];
	  }
	   Mysite::$app->setdata($data);
	}
	function shopordert(){
	   //shopuid
	    
	}
	function shopordertoday(){
		$nowdata = date('Y-m-d',time());
	  $mintime = strtotime($nowdata);
	  $maxtime = strtotime($nowdata.' 23:59:59');
	  $where = '  posttime > '.$mintime.' and posttime < '.$maxtime;//发货时间
	  $status  = intval(IFilter::act(IReq::get('status')));
	  $status  =  in_array($status,array(1,2,3))? $status:1; 
	  $where .=' and status ='.$status;
	  $where .=' and shopuid ='.$this->member['uid']; 
	  $buyerstatus= array(
		'0'=>'等待处理',
		'1'=>'等待发货',
		'2'=>'已发货，待完成',
		'3'=>'订单完成',
		'4'=>'订单已取消',
		'5'=>'订单已取消'
		);
		$data['buyerstatus'] = $buyerstatus;
		$data['where'] = $where;
		$arraystatus = array(
		'1'=>'今日待发货订单',
		'2'=>'今日已发货订单',
		'3'=>'今日完成订单'
		);
		$data['orderbt'] = $arraystatus[$status]; 
	  Mysite::$app->setdata($data); 
	}
	function shopshoworder(){
		$link = IUrl::creatUrl('wxsite/index'); 
	 if($this->member['uid'] == 0)  $this->message('未登陆',$link); 
	  $orderid = intval(IReq::get('id'));  
	  if(!empty($orderid)){
	  	 
	     	$order = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."order where shopuid='".$this->member['uid']."' and id = ".$orderid."");   
	     
	     	if(empty($order)){
	     		$data['order'] = '';
	     		Mysite::$app->setdata($data);
	     	}else{
	     	     $order['ps'] = $order['shopps'];
	     	     // 超市商品总价	 超市配送配送	shopcost 店铺商品总价	shopps 店铺配送费	pstype 配送方式 0：平台1：个人	bagcost  
       	     $orderdet = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."orderdet where order_id='".$order['id']."'");  
	            $order['cp'] = count($orderdet); 
	            $buyerstatus= array(
	     	     '0'=>'等待处理',
		'1'=>'等待发货',
		'2'=>'已发货，待完成',
		'3'=>'订单完成',
		'4'=>'订单已取消',
		'5'=>'订单已取消'
	     	     );
	     	     $paytypelist = array('outpay'=>'货到支付','open_acout'=>'账号余额支付');  
	     	     $paylist = $this->mysql->getarr("select * from ".Mysite::$app->config['tablepre']."paylist   order by id asc limit 0,50");
	     	     if(is_array($paylist)){
	     	       foreach($paylist as $key=>$value){
	     	     	    $paytypelist[$value['loginname']] = $value['logindesc'];
	     	       }
	            }
	     	     $paytypearr = $paytypelist; 
	     	      $order['surestatus'] = $order['status'];
	     	     $order['status'] = $buyerstatus[$order['status']];
	     	     $order['paytype'] = $paytypearr[$order['paytype']];
	     	     $order['paystatus'] = $order['paystatus']==1?'已支付':'未支付';
	     	     $order['addtime'] = date('Y-m-d H:i:s',$order['addtime']);
	     	     $order['posttime'] = date('Y-m-d H:i:s',$order['posttime']);
	     	  
	     	     
	     	     $data['order'] = $order;
	           $data['orderdet'] = $orderdet;
	          
	           Mysite::$app->setdata($data);
	           
	       }
	  }else{
	  	$data['order'] = '';
	  	Mysite::$app->setdata($data);
	  }
	}
	function shopcontrol(){
		$this->checkmemberlogin();
		$controlname =trim(IFilter::act(IReq::get('controlname')));
		$orderid = intval(IReq::get('orderid')); 
		$ordertempinfo = $this->mysql->select_one("select * from ".Mysite::$app->config['tablepre']."order where id = ".$orderid."");
		if($ordertempinfo['shopuid'] != $this->member['uid']) $this->message('您不能操作此订单');
		$shopid = $ordertempinfo['shopid']; 
		$shopinfo = $this->mysql->select_one("select uid from ".Mysite::$app->config['tablepre']."shop where id = ".$shopid."");
		switch($controlname)
		{
			case 'unorder': 
			 
	     $reason = trim(IFilter::act(IReq::get('reason')));
	     if(empty($reason)) $this->message('关闭理由不能为空');
	   	 $ordercontrol = new ordercontrol($orderid);
	   	 if($ordercontrol->sellerunorder($shopinfo['uid'],$reason))
	   	 {
				 $this->success('操作成功');
	     }else{
				  $this->message($ordercontrol->Error());
		   }  
			break;
			case 'sendorder': 
		  $ordercontrol = new ordercontrol($orderid);
		  if($ordercontrol->sendorder($shopinfo['uid']))
		  {
				$this->success('操作成功');
		  }else{
				 $this->message($ordercontrol->Error());
		  } 
			break;
			case 'shenhe': 
		  $ordercontrol = new ordercontrol($orderid);
		  if($ordercontrol->shenhe($shopinfo['uid']))
		  {
					$this->success('操作成功');
		  }else{
				 $this->message($ordercontrol->Error());
		  }
			break;
			case 'delorder':
			$ordercontrol = new ordercontrol($orderid);
		  if($ordercontrol->sellerdelorder($shopinfo['uid']))
		  {
				$this->success('操作成功');
		  }else{
			   $this->message($ordercontrol->Error());
		  } 
			break;
			case 'domake':
			if($ordertempinfo['status'] != 1){
			  $this->message('订单状态不可操作是否制作');
			} 		
			if(!empty($ordertempinfo['is_make'])){
				 $this->message('订单已设置过是否制作，如要取消 请联系网站客服');
			}
			$newdata['is_make'] = 1;
			$this->mysql->update(Mysite::$app->config['tablepre'].'order',$newdata,"id='".$orderid."'");
			$this->success('操作成功');
			break;
			case 'unmake':
			if($ordertempinfo['status'] != 1){
			  $this->message('订单状态不可操作是否制作');
			} 		
			if(!empty($ordertempinfo['is_make'])){
				 $this->message('订单已设置过是否制作，如要取消 请联系网站客服');
			}
			$newdata['is_make'] = 2;
			$this->mysql->update(Mysite::$app->config['tablepre'].'order',$newdata,"id='".$orderid."'");
			$this->success('操作成功');
			break;
			default:
			$this->message('未定义的操作');
			break;
		}
		
		
		
	}
  
}



?>