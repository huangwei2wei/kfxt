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
			parent::_addHttp($key,$url,$get,$post);
		}else {
			static $autoKey=0;
			$autoKey++;
			$url=$server;
			$url.="?_sign={$verifyCode}&_verifycode={$random}";
			parent::_addHttp($autoKey,$url,$get,$post);
		}
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
	public function getResults($getType='RpcSeri'){
		foreach ($this->_result as $key=>&$value){
			$value=json_decode($value,true);
		}
		return $this->_result;
	}



}