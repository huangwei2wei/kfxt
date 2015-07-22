<?php
Tools::import('Util_Curl');
/**
 * 三分天下API调用类
 * @author php-朱磊
 *
 */
class Util_ApiSftx extends Util_Curl {

	const ENCRYPT_KEY=TAKE_KEY;	//加密密钥
	const USER_NAME='kefu';				//账号

	public function __construct(){
		parent::__construct();

	}

	/**
	 * 增加一个http请求
	 * @param int/string $server 服务器流水号/url地址
	 * @param array $get	get值
	 * @param array $post   post值
	 */
	public function addHttp($server,$get=NULL,$post=NULL){
		$random=CURRENT_TIME.rand(100000,900000);
		$verifyCode=md5(self::ENCRYPT_KEY.$random);
		if (is_numeric($server)){
			$key=$server;
			$gameServerList=$this->_getGlobalData('gameser_list');
			$url=$gameServerList[$server]['server_url'].$get['ctl'].'/'.$get['act']."?_sign={$verifyCode}&_verifycode={$random}&operator=".self::USER_NAME;
			unset($get['ctl'],$get['act']);
			parent::_addHttp($key,$url,$get,$this->_createJavaPost($post));
		}else {
			static $autoKey=0;
			$autoKey++;
			$url=$server;
			$url.="?_sign={$verifyCode}&_verifycode={$random}";
			parent::_addHttp($autoKey,$url,$get,$this->_createJavaPost($post));
		}
	}

	/**
	 * 返回java所需要的post数据
	 * @param array $post post数据
	 * @return string
	 */
	private function _createJavaPost($post){
		if (!count($post))return false;
		$postStr='';
		foreach ($post as $key=>$value){
			if (!is_array($value)){
				$postStr.="{$key}={$value}&";
			}else{
				foreach ($value as $childKey=>$childValue){
					$postStr.="{$key}={$childValue}&";
				}
			}
		}
		$postStr=substr($postStr,0,-1);
		return $postStr;
	}

	/**
	 * 发送请求
	 */
	public function send(){
		parent::_send();
	}

	/**
	 * 获取单个结果
	 * @param int $key key值,如果没有自动弹出第一个
	 * @param string $getType 返回类型,默认序列化
	 */
	public function getResult($key=null){
		if (!count($this->_result))return false;
		if ($key){
			$retResult=$this->_result[$key];
		}else {
			$retResult=array_shift($this->_result);
		}
		return json_decode($retResult,true);
	}


	/**
	 * 获取所有结果集
	 * @param string $getType 默认序列化
	 */
	public function getResults(){
		foreach ($this->_result as $key=>&$value){
			$value=json_decode($value,true);
		}
		return $this->_result;
	}

	public static $logType=array (
	1001=> '建造队列',
	1002=> '征收队列',
	1003=> '科技队列',
	1004=> '军令队列',
	1006=> '突飞队列',
	1007=> '强化队列',
	1008=> '委派队列',
	1011=> '纺织队列',
	1012=> '收获队列',
	1016=> '淬炼队列',
	1017=> '阵灵升级队列',
	2010074=> '碧玉群狼兵符',
	2010075=> '碧玉猛虎兵符',
	2010076=> '碧玉鳳翔兵符',
	2010077=> '碧玉龍飛兵符',
	2010078=> '紅玉猛虎兵符',
	2010079=> '紅玉鳳翔兵符',
	2010080=> '紅玉龍飛兵符',
	2010081=> '紅玉麒麟兵符',
	2010082=> '墨玉鳳翔兵符',
	2010083=> '墨玉龍飛兵符',
	2010084=> '墨玉麒麟兵符',
	2010085=> '墨玉君王兵符',
	2010086=> '黃玉龍飛兵符',
	2010087=> '黃玉麒麟兵符',
	2010088=> '黃玉君王兵符',
	2010089=> '黃玉霸王兵符',
	2010090=> '白玉麒麟兵符',
	2010091=> '白玉君王兵符',
	2010092=> '白玉霸王兵符',
	2010093=> '白玉軍神兵符',
	2001=> '军令',
	2002=> '修改军团名',
	2003=> '训练位',
	2004=> '建筑位',
	2005=> '军徽',
	2006=> '仓库开格',
	2008=> '8小时免战',
	2009=> '全服发言',
	20111=> '购买鐵箱子',
	20112=> '购买銅箱子',
	20113=> '购买銀箱子',
	20114=> '购买金箱子',
	20117=> '购买感恩之箱',
	20121=> '购买周年宝箱',
	2012=> '开启武魂训练师',
	2013=> '开启纺织商人',
	2014=> '开启祝福宝箱',
	2015=> '英-军令礼包',
	2016=> '金币鼓舞',
	2017=> '更改名字',
	2018=> '刷新奖励',
	61003=> '跨服金币鼓舞',
	7004=> 'BOSS战金币鼓舞',
	5052=> '砸金蛋',
	5051=> '刷新礼包金币',
	3001=> '商队强制通商',
	3002=> '每日任务刷新',
	3003=> '每日任务立即完成',
	3004=> '强征',
	3005=> '购买纺织次数',
	3006=> '增加纺织经验',
	3007=> '加强委派',
	3008=> '购买招募位置',
	3009=> '强行分解',
	3010=> '强行淬炼',
	3011=> '淬炼魔力值100',
	3012=> '晶石减半',
	3013=> '分解白金模式',
	3014=> '分解至尊模式',
	3017=> '淬炼白金模式',
	3018=> '淬炼至尊模式',
	3016=> '投资城市',
	71001=> '叛国金币消费',
	7001=> '阵灵升级成功率刷新',
	7002=> '阵灵升级金币100%成功',
	7003=> '阵灵开格',
	61005=> '陣靈天賦幻化成功率100%',
	52020=> '黑市金币刷新',
	52021=> '用金币购买黑市物品',
	60802=> '图腾升级',
	40106=> '委派刷新',
	62004=> '建造外城设备花费金币',
	62006=> '挖井消费金币',
	62009=> '买钻头花费的金币',
	4001=> '银矿战侦查',
	4002=> '强攻精英部队',
	4003=> '战役减少CD',
	4004=> 'vip2+双倍攻打概率',
	4006=> '强攻军团部队',
	4009=> '军团切磋',
	64001=> '兵種升級中補足技能書數量所需金幣',
	63602=> '刷新科技金币',
	5001=> '选择训练时间',
	5002=> '改变训练模式',
	5003=> '结束训练',
	5004=> '金币突飞',
	5005=> '金币洗属性',
	5006=> '装备强化魔力值100',
	6006=> '部队转生',
	62001=> '使用金幣保護星魂',
	62002=> '開啟武將的將星系統中的格子',
	62003=> '購買星魂所需金幣',
	62013=> '開啟點將開　結交位所花金幣',
	62015=> '結交武將　所花費的銀幣　金幣',
	62012=> '刷将所花金币',
	6001=> '30没登陆奖励',
	6002=> '打开藏宝图',
	6003=> '封测期间登陆获取奖励',
	9001=> '罗盘大乐转次数',
	9002=> '竞技场次数',
	1 => '加速消费',
	2 => '购买功能',
	3 => '内政',
	4 => '军事',
	5 => '武将',
	);


}