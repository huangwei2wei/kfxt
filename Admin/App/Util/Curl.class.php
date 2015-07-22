<?php

/**
 * curl扩展
 * @author php-朱磊
 * @example
	$curl=new Util_Curl();
	$curl->addHttp(
		'1',
		'http://localhost/test/AsyncTest/Request.php',
		array('a'=>'Index','c'=>'login'),
		array('Fisk'=>'show me the money','iu'=>'are you','siw'=>array('skwfd'=>1,'fdjsklf'=>3333,'sdfw'=>array('sifj'=>'sjsiwj','show'=>array('me'=>'money'))),'show me the money'=>'jdjdjddj'));
	$curl->addHttp('2','http://localhost/test/AsyncTest/Request.php',array('a'=>'Faq','c'=>'Ediw'),array('qqq'=>'eeeney','bb'=>'asdfasw'));
	var_dump($curl->send());
 */
class Util_Curl extends Control {
	
	/**
	 * http请求记录集
	 * @var array
	 */
	private $_sendHttp = array ();
	
	/**
	 * curl并发句柄
	 * @var object
	 */
	private $_curlMultiObj;
	
	private $_connectTimeOut = 20;
	
	private $_timeOut = 20;
	
	/**
	 * 结果
	 * @var array
	 */
	protected $_result = array ();
	
	public function __construct() {
		$this->_curlMultiObj = curl_multi_init ();
	}
	
	/**
	 * 初始化（在运行阶段多次并发，每次并发前都需要初始化）
	 */
	public function curlInit(){
		$ResourceType = get_resource_type($this->_curlMultiObj);
		if($ResourceType !== 'curl'){
			$this->_curlMultiObj = curl_multi_init ();
		}
	}
	
	/**
	 * 设置连接超时时间(秒)
	 * @param int $sec
	 */
	public function setConnectTimeOut($sec){
		$sec = intval($sec);
		$this->_connectTimeOut = $sec;
	}
	
	/**
	 * 设置超时时间(秒)
	 * @param int $sec
	 */
	public function setTimeOut($sec){
		$sec = intval($sec);
		$this->_timeOut = $sec;
	}
	
	/**
	 * 增加一个http请求
	 * @param int $key    key
	 * @param string $url url地址
	 * @param array $get  一维数组
	 * @param array $post 多维数组
	 */
	protected function _addHttp($key, $url, $get = NULL, $post = NULL) {
		$options = array (//设置
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_HEADER => false, 
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', 
			CURLOPT_CONNECTTIMEOUT => $this->_connectTimeOut, 
			CURLOPT_TIMEOUT => $this->_timeOut );
		if ( $post ) { //如果有post信息将以post提交
			$options [CURLOPT_POST] = true;
			$options [CURLOPT_POSTFIELDS] =  (is_array($post))?http_build_query($post):$post;
		}
		
		if (is_array ( $get ))
			$url = $this->_createGet ( $get, $url ); //生成url+get信息
		
		$this->_sendHttp [$key] = curl_init ( $url ); //初始化,生成句柄
//				print_r($url);
		//		die();
		curl_setopt_array ( $this->_sendHttp [$key], $options ); //装载设置 
		curl_multi_add_handle ( $this->_curlMultiObj, $this->_sendHttp [$key] ); //增加句柄到并发句柄里面去. 
	}
	
	/**
	 * 执行并发
	 */
	protected function _send() {
		$this->_result=array();
		$active = null;
		do {
			$mrc = curl_multi_exec ( $this->_curlMultiObj, $active );
		} while ( $mrc == CURLM_CALL_MULTI_PERFORM );
		while ( $active && $mrc == CURLM_OK ) {
			if (curl_multi_select ( $this->_curlMultiObj ) != - 1) {
				do {
					$mrc = curl_multi_exec ( $this->_curlMultiObj, $active );
				} while ( $mrc == CURLM_CALL_MULTI_PERFORM );
			}
		}
		foreach ( $this->_sendHttp as $key => $result ) {
			$this->_result [$key] = curl_multi_getcontent ( $this->_sendHttp [$key] );
			curl_close ( $this->_sendHttp [$key] );
			curl_multi_remove_handle( $this->_curlMultiObj,$this->_sendHttp [$key]);
		}
		curl_multi_close ( $this->_curlMultiObj );
		$this->_sendHttp=array();
	}
	
	/**
	 * 将url加上get值
	 * @param array $get
	 * @param string $url
	 * @return string
	 */
	private function _createGet($get, $url) {
		foreach ( $get as $key => $value ) {
			if (strpos ( $url, '?' )) {
				$url .= "&{$key}={$value}";
			} else {
				$url .= "?{$key}={$value}";
			}
		}
		return $url;
	}
	

	
	/**
	 * 创建post数据,自动转换一维数组
	 * @param array $post
	 * @param string $superKey 父key
	 *
	private function _createPost($post, $superKey = NULL) {
		static $resultArray = array ();
		foreach ( $post as $key => $value ) {
			if (is_array ( $value )) {
				$key = $superKey ? "{$superKey}[{$key}]" : $key;
				$this->_createPost ( $value, $key );
			} else {
				$key = $superKey ? "{$superKey}[{$key}]" : $key;
				if (! array_key_exists ( $key, $resultArray )) {
					$resultArray [$key] = $value;
				}
			}
		}
		$retArr=$resultArray;
		unset($resultArray);
		$resultArray=null;
		return $retArr;
	}*/

}