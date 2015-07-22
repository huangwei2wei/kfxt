<?php
/**
 * php-rpc
 * @author PHP-兴源
 *
 */
class Rpc{
	
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
		$phprpcPath=LIB_PATH . 'PhpRpc/phprpc_client.php';		
		if (!file_exists($phprpcPath))throw new Error('phprpc libs not exist');
		include $phprpcPath;
		$this->_phpRpc=new PHPRPC_Client();
		$this->_phpRpc->setProxy(null);	//设置代理
		$this->_phpRpc->setEncryptMode(0);
		$this->_phpRpc->setCharset('UTF-8');
		$this->_phpRpc->setTimeout(10);
	}
	
	/**
	 * 设置URL,如果有参数就加上$params
	 * @param int $server
	 * @param string $params
	 */
	public function setUrl($server,$params=null){
		if (!is_null($params))$server.=$params;
		$this->_phpRpc->useService($server);
	}
	
	public function __call($methond,$params){
		return call_user_func_array(array($this->_phpRpc,$methond),$params);
	}
	
}