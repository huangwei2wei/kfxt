<?php
echo date('Y-m-d H:i:s');
var_dump(date('Y-m-d H:i:s') > '2012-06-11 10:59:18');exit;

echo time();exit;
$str='
{"status":1,"total":2,"data":[{"account":"171717_1","playerName":"相半�?,"sex":0,"online":false,"gold":12012,"diamond":2149,"oil":1600,"forage":1600,"energy":909,"consumeCount":0,"consumeMoney":0,"installAppDate":0,"loginCount":0,"lastLoginDate":0,"onlineTimeCount":0,"lastOnlineTime":0,"GoodsSet":[{"goodsID":20040006,"number":109,"name":"核晶稳定�?},{"goodsID":20040068,"number":3,"name":"基因研究中心"},{"goodsID":20040034,"number":2,"name":"恢复休息�?},{"goodsID":20040089,"number":4,"name":"生物强化系统"},{"goodsID":20040035,"number":2,"name":"营养�?},{"goodsID":20040090,"number":3,"name":"兵器配给系统"},{"goodsID":20040064,"number":2,"name":"矿产探测�?},{"goodsID":20040065,"number":2,"name":"碾压粉碎�?},{"goodsID":20030016,"number":3,"name":"5级新手礼�?},{"goodsID":20040111,"number":24,"name":"防空火箭�?},{"goodsID":20040069,"number":2,"name":"核晶栽培手册"},{"goodsID":20040005,"number":2,"name":"核晶加速器"}]},{"account":"212121_1","playerName":"丁问�?,"sex":0,"online":false,"gold":84094,"diamond":427,"oil":19064,"forage":97944,"energy":1000,"consumeCount":0,"consumeMoney":0,"installAppDate":0,"loginCount":0,"lastLoginDate":0,"onlineTimeCount":0,"lastOnlineTime":0,"GoodsSet":[{"goodsID":20040091,"number":2,"name":"军事教育课程"},{"goodsID":20040070,"number":2,"name":"温室"},{"goodsID":20040111,"number":6,"name":"防空火箭�?},{"goodsID":20030006,"number":2,"name":"小型金币�?},{"goodsID":20040069,"number":6,"name":"核晶栽培手册"},{"goodsID":20040101,"number":7,"name":"自动化生产线"},{"goodsID":20040010,"number":1,"name":"建筑修理图纸"},{"goodsID":20020001,"number":91,"name":"小加班卡"},{"goodsID":20040066,"number":1,"name":"矿石嗅探�?},{"goodsID":20040285,"number":10,"name":"小喇�?},{"goodsID":20040289,"number":10,"name":"黄金VIP�?},{"goodsID":20040096,"number":2,"name":"自动化电�?},{"goodsID":20040068,"number":6,"name":"基因研究中心"},{"goodsID":20040105,"number":1,"name":"效率生产�?},{"goodsID":20040288,"number":3,"name":"白银VIP�?},{"goodsID":20030016,"number":1,"name":"5级新手礼�?},{"goodsID":20040064,"number":2,"name":"矿产探测�?},{"goodsID":20040067,"number":3,"name":"矿石精炼�?},{"goodsID":20040095,"number":2,"name":"节能工程"},{"goodsID":20040100,"number":1,"name":"效率工程"},{"goodsID":20040013,"number":1,"name":"纳米机器�?},{"goodsID":20040065,"number":2,"name":"碾压粉碎�?}]}]}
';
$str2 = '{"status":1,"total":1,"data":[{"account":"171717_1","playerName":"相半�?,"sex":0,"online":false,"gold":12012,"diamond":2149,"oil":1600,"forage":1600,"energy":909,"consumeCount":0,"consumeMoney":0,"installAppDate":0,"loginCount":0,"lastLoginDate":0,"onlineTimeCount":0,"lastOnlineTime":0,"GoodsSet":[{"goodsID":20040005,"number":2,"name":"核晶加速器"},{"goodsID":20040064,"number":2,"name":"矿产探测�?},{"goodsID":20040006,"number":109,"name":"核晶稳定�?},{"goodsID":20030016,"number":3,"name":"5级新手礼�?},{"goodsID":20040089,"number":4,"name":"生物强化系统"},{"goodsID":20040111,"number":24,"name":"防空火箭�?},{"goodsID":20040034,"number":2,"name":"恢复休息�?},{"goodsID":20040035,"number":2,"name":"营养�?},{"goodsID":20040068,"number":3,"name":"基因研究中心"},{"goodsID":20040069,"number":2,"name":"核晶栽培手册"},{"goodsID":20040065,"number":2,"name":"碾压粉碎�?},{"goodsID":20040090,"number":3,"name":"兵器配给系统"}]}],"extDate":[{"goodsID":20040005,"number":2,"name":"核晶加速器"},{"goodsID":20040064,"number":2,"name":"矿产探测�?},{"goodsID":20040006,"number":109,"name":"核晶稳定�?},{"goodsID":20030016,"number":3,"name":"5级新手礼�?},{"goodsID":20040089,"number":4,"name":"生物强化系统"},{"goodsID":20040111,"number":24,"name":"防空火箭�?},{"goodsID":20040034,"number":2,"name":"恢复休息�?},{"goodsID":20040035,"number":2,"name":"营养�?},{"goodsID":20040068,"number":3,"name":"基因研究中心"},{"goodsID":20040069,"number":2,"name":"核晶栽培手册"},{"goodsID":20040065,"number":2,"name":"碾压粉碎�?},{"goodsID":20040090,"number":3,"name":"兵器配给系统"}]}';


print_r( json_decode($str));
echo '----------------------';
print_r( json_decode($str2));
exit;














//var_dump($return = "{$return} = ");



$arr = array(
0=>'a',
1=>'b',
2=>'c',
3=>array(0=>'a',1=>'b')

);
var_dump( GetArrLv($arr));

echo count($arr);

//phpinfo();


function GetArrLv($arr) {
	if (is_array($arr)) {

		function AWRSetNull(&$val) {
			$val = NULL;
		}

		#�ݹ齫����ֵ��NULL��Ŀ��1������鹹����array("array(\n  ()")��2��print_r ������ɵ�?
		array_walk_recursive($arr, 'AWRSetNull');

		$ma = array();
		#������ƥ��[�հ�]����һ�������ţ�Ҫʹ�ö��п���'m'
		preg_match_all("'^\(|^\s+\('m", print_r($arr, true), $ma);
		#�ص�ת�ַ���
		//$arr_size = array_map('strlen', current($ma));
		#ȡ����󳤶Ȳ�����������ռ�õ�һλ����?
		//$max_size = max($arr_size) - 1;
		#���������?8 ���ո��У�����Ҫ�� 1 ������Ϊ print_r ��ӡ�ĵ�һ��������������
		//return $max_size / 8 + 1;
		return (max(array_map('strlen', current($ma))) - 1) / 8 + 1;
	} else {
		return 0;
	}
}