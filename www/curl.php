<?php
$time_start1 = microtime_float();
$ac = new App_Curl();
for ($i = 0; $i < 50; $i++) {
	$ac ->_addHttp($i,"http://www.baidu.com");
}
$ac->_send();

$time_end1 = microtime_float();
$time = $time_end1 - $time_start1;

echo "��һ������ʱ��: " . $time . "<br>";

// var_dump ($ac->_result);

$time_start2 = microtime_float();
for ($i = 0; $i < 50; $i++) {
	$str[$i] = file_get_contents("http://www.baidu.com");
}
$time_end2 = microtime_float();
$time = $time_end2 - $time_start2;
echo "�ڶ�������ʱ��: " . $time;


function microtime_float(){
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}


class App_Curl {
	
	/**
	 * http�����¼��
	 * @var array
	 */
	private $_sendHttp = array ();
	
	/**
	 * curl�������
	 * @var object
	 */
	private $_curlMultiObj;
	
	private $_connectTimeOut = 20;
	
	private $_timeOut = 20;
	
	/**
	 * ���
	 * @var array
	 */
	public $_result = array ();
	
	public function __construct() {
		$this->_curlMultiObj = curl_multi_init ();
	}
	
	/**
	 * ��ʼ���������н׶ζ�β�����ÿ�β���ǰ����Ҫ��ʼ����
	 */
	public function curlInit(){
		$ResourceType = get_resource_type($this->_curlMultiObj);
		if($ResourceType !== 'curl'){
			$this->_curlMultiObj = curl_multi_init ();
		}
	}
	
	/**
	 * �������ӳ�ʱʱ��(��)
	 * @param int $sec
	 */
	public function setConnectTimeOut($sec){
		$sec = intval($sec);
		$this->_connectTimeOut = $sec;
	}
	
	/**
	 * ���ó�ʱʱ��(��)
	 * @param int $sec
	 */
	public function setTimeOut($sec){
		$sec = intval($sec);
		$this->_timeOut = $sec;
	}
	
	/**
	 * ����һ��http����
	 * @param int $key    key
	 * @param string $url url��ַ
	 * @param array $get  һά����
	 * @param array $post ��ά����
	 */
	public function _addHttp($key, $url, $get = NULL, $post = NULL) {
		$options = array (//����
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_HEADER => false, 
			CURLOPT_FOLLOWLOCATION => true, 
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', 
			CURLOPT_CONNECTTIMEOUT => $this->_connectTimeOut, 
			CURLOPT_TIMEOUT => $this->_timeOut );
		if ( $post ) { //�����post��Ϣ����post�ύ
			$options [CURLOPT_POST] = true;
			$options [CURLOPT_POSTFIELDS] =  (is_array($post))?http_build_query($post):$post;
		}
		
		if (is_array ( $get ))
			$url = $this->_createGet ( $get, $url ); //����url+get��Ϣ
		
		$this->_sendHttp [$key] = curl_init ( $url ); //��ʼ��,���ɾ��
		curl_setopt_array ( $this->_sendHttp [$key], $options ); //װ������ 
		curl_multi_add_handle ( $this->_curlMultiObj, $this->_sendHttp [$key] ); //���Ӿ���������������ȥ. 
	}
	
	/**
	 * ִ�в���
	 */
	public function _send() {
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
	 * ��url����getֵ
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
}


