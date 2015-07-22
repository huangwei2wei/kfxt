<?php
/**
 * webservice发送类
 * @author php-朱磊
 *
 */
class WebService {
	
	/**
	 * Httpdown
	 * @var Httpdown
	 */
	private $_httpDown;
	
	/**
	 * 发送url地址
	 * @var string
	 */
	private $_sendUrl;
	
	public function __construct(){
		$this->_initialize();
	}
	
	/**
	 * 初始化引入httpdown类
	 */
	private function _initialize(){
		import('@.Util.Httpdown');
		$this->_httpDown=new Httpdown();
	}
	
	/**
	 * 设置url
	 * @param string $url
	 */
	public function setUrl($url){
		$this->_sendUrl=$url;
	}
	
	/**
	 * 传入get值 
	 * @param array $getArr
	 */
	public function setGet($getArr){
		if (!$this->_sendUrl)return false;
		foreach ($getArr as $key=>$value){
			if (strpos($this->_sendUrl,'?')){
				$this->_sendUrl.="&{$key}={$value}";
			}else {
				$this->_sendUrl.="?{$key}={$value}";
			}
		}
	}
	
	/**
	 * 传入post值
	 * @param array $postArr
	 */
	public function setPost($postArr){
		foreach ($postArr as $key=>$value){
			$this->_httpDown->AddForm($key,$value);
		}
	}
	
	/**
	 * 发送数据
	 */
	public function sendData(){
		if (!$this->_sendUrl)return false;
		$this->_httpDown->OpenUrl($this->_sendUrl);
		return true;
	}
	
	/**
	 * 返回数据
	 */
	public function getRaw(){
		return $this->_httpDown->GetRaw();
		if ($this->_httpDown->IsGetOK()){
			return $this->_httpDown->GetRaw();
		}
		return false;
	}
	
	
}