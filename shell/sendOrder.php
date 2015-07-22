<?php
$key='cndw_kefu';
$rand=time();
$url='http://service.uwan.com/admin.php?c=InterfaceWorkOrder&a=DistributionOrder';
$_sign=md5($key.$rand);
$url.="&_sign={$_sign}&_verifycode={$rand}";
file_get_contents($url);