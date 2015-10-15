<?php  
$alipay_config['partner']		= '2088901767028754'; 
$alipay_config['key']			= 'qq4hcijgedgp1jh2eui9vim6gmjan8cg'; 
$alipay_config['private_key_path']	= '/www/web/m2_waimairen_com/public_html/plug/pay/alipay/html5/key/rsa_private_key.pem';
$alipay_config['ali_public_key_path']= '/www/web/m2_waimairen_com/public_html/plug/pay/alipay/html5/key/alipay_public_key.pem';
$alipay_config['sign_type']    = '0001';
$alipay_config['input_charset']= 'utf-8'; 
$alipay_config['cacert']    = getcwd().'\\cacert.pem';
$alipay_config['transport']    = 'http';
$alipay_config['notify_url'] = 'http://m2.waimairen.com/plug/pay/alipay/html5/notify_url.php';
$alipay_config['return_url'] = 'http://m2.waimairen.com/plug/pay/alipay/html5/call_back_url.php';
$alipay_config['error_url'] = 'http://m2.waimairen.com/plug/pay/alipay/html5/error.php';
$alipay_config['seller_email'] = '375952873@qq.com';
?>