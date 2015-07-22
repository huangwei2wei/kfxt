<?php
Tools::import('Util_Curl');
/**
 * http并发接口
 *
 */
class Util_HttpMInterface extends Util_Curl {
	private static $_autoKey=0;

	public function __construct(){
		parent::__construct();
	}



	/**
	 * 增加一个http请求
	 * @param int/string $server 服务器流水号/url地址
	 * @param array $get	get值
	 * @param array $post   post值
	 */
	public function addHttp($server,$UrlAppend=NULL,$get=NULL,$post=NULL,$serverId=NULL){
		if (is_numeric($server)){
			$key=$server;
			$gameServerList=$this->_getGlobalData('gameser_list');
			$url=$gameServerList[$server]['server_url'].$UrlAppend;
			//exit(Tools::htmlPage($url,$get,$post));
			echo $url;
			parent::_addHttp($key,$url,$get,$post);
		}else {
			$key = $serverId?$serverId:++self::$_autoKey;
			$url=$server.$UrlAppend;
			parent::_addHttp($key,$url,$get,$post);
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
		return $retResult;
	}


	/**
	 * 获取所有结果集
	 * @param string $getType 默认序列化
	 */
	public function getResults(){
		return $this->_result;
	}



}