<?php

 echo urlencode("88#@*^%o");
/***

$url = 'http://service.uwan.com/admin.php?c=InterfaceFaq&a=Type&game_id=8&_unique=10843165&_time=1347271410&_sign=a8cf93ee57ea33d2871659fdef753ce8&lang=1';

//$data = file_get_contents($url);
//print_r($data);

$ch = curl_init();
$timeout = 5;
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
curl_close($ch);

echo $file_contents;

**/