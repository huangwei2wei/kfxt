<?php
$key='cndw_kefu';
$rand=time();
$url='http://f.com/admin.php?c=InterfaceWorkOrder&a=DistributionOrder';
$_sign=md5($key.$rand);
$url.="&_sign={$_sign}&_verifycode={$rand}";

//ECHO DATe("Y-m-d H:i:s",1348890407);

echo $url;exit;

file_get_contents($url);