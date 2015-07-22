<?php
/**
 * php-rpc
 * @author PHP-朱磊
 *
 */
class Util_Rpc extends Base {
	
	/**
	 * PHPRPC_Client
	 * @var PHPRPC_Client
	 */
	private $_phpRpc=null;

	public function __construct(){
		$this->_init();
	}
	

	/**
	 * 初始化
	 */
	private function _init(){
		$phprpcPath=LIB_PATH . '/phprpc/phprpc_client.php';
		if (!file_exists($phprpcPath))throw new Error('phprpc libs not exist');
		include $phprpcPath;
		$this->_phpRpc=new PHPRPC_Client();
		$this->_phpRpc->setProxy(null);	//设置代理
		$this->_phpRpc->setEncryptMode(0);
		$this->_phpRpc->setCharset('UTF-8');
		$this->_phpRpc->setTimeout(10);
	}
	
	public function setPrivateKey($Key){
		$this->_phpRpc->setPrivateKey($Key);
	}
	
	/**
	 * 设置URL,如果有参数就加上$params
	 * @param int $server
	 * @param string $params
	 */
	public function setUrl($server,$params=null,$username = NULL, $password = NULL){
		if (is_numeric($server)){
			$serverList=$this->_getGlobalData('gameser_list');
			$server=$serverList[$server]['server_url'];
		}
		if (!is_null($params))$server.=$params;
		echo $server;
		$this->_phpRpc->useService($server,$username = NULL, $password = NULL);
	}
	

	public function __call($methond,$params){
		return call_user_func_array(array($this->_phpRpc,$methond),$params);
	}
	
	
	
}