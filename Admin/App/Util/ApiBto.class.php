<?php
Tools::import('Util_Curl');
/**
 * 商业大亨API接口
 * @author Administrator
 *
 */
class Util_ApiBto extends Util_Curl {
	const ENCRYPT_KEY=TAKE_KEY;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addHttp($server,$get=NULL,$post=NULL){
		$random=CURRENT_TIME.rand(10000,90000);
		$verifyCode=md5(self::ENCRYPT_KEY.$random);
		if (!is_array($get))$get=array();
		$get['_sign']=$verifyCode;
		$get['_verifycode']=$random;
		if (is_numeric($server)){
			$key=$server;
			$gameServerList=$this->_getGlobalData('gameser_list');
			$url=$gameServerList[$server]['server_url'];
			parent::_addHttp($key,$url,$get,$post);
		}else {
			static $autoKey=0;
			$autoKey++;
			$url=$server;
			parent::_addHttp($autoKey,$url,$get,$post);
		}
	}
	
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
	
	public function getResults($getType='RpcSeri'){
		foreach ($this->_result as $key=>&$value){
			$value=json_decode($value,true);
		}
		return $this->_result;
	}
	
	
}