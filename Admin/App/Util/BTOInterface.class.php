<?php
/**
 * 商业大亨接口
 * @author php-朱磊
 *
 */
class Util_BtoInterface extends Control {
	/**
	 * Util_Httpdown
	 * @var Util_Httpdown
	 */
	private $_utilHttpdown;
	
	private $_key='kelvin818';//key
	
	private $_sendUrl=null;
	
	private $_sendPage='api_interface.php';//发送页面
	
	public function __construct(){
		$this->_utilHttpdown=$this->_getGlobalData('Util_Httpdown','object');
	}
	
	/**
	 * 设置URL
	 * @param int $serverId 服务器ID
	 */
	public function setServerUrl($serverId){
		$gameServerList=$this->_getGlobalData('gameser_list');
		$this->_sendUrl=$gameServerList[$serverId]['server_url'].$this->_sendPage;
		$this->_defaultUrl=$this->_sendUrl;
	}
	
	/**
	 * 设置get值
	 * @param array $getArr
	 */
	public function setGet($getArr){
		if (!$this->_sendUrl)return false;
		if (is_array($getArr)){
			$getArr['sign']=md5("action={$getArr['action']}&key={$this->_key}");
			$getArr['tt']=CURRENT_TIME;
			foreach ($getArr as $key=>$value){
				if (strpos($this->_sendUrl,'?')){
					$this->_sendUrl.="&{$key}={$value}";
				}else {
					$this->_sendUrl.="?{$key}={$value}";
				}
			}
			
		}
	}
	
	/**
	 * 清空get值 
	 */
	public function clearGet(){
		$this->_sendUrl=$this->_defaultUrl;
	}
	
	/**
	 * 设置POST值
	 * @param array $postArr
	 */
	public function setPost($postArr){
		if (is_array($postArr)){
			foreach ($postArr as $key=>$value){
				$this->_utilHttpdown->AddForm($key,$value);
			}
		}
	}
	
	public function callInterface(){
		if (!$this->_sendUrl)return false;	//如果没有url,将退出
		$this->_utilHttpdown->OpenUrl($this->_sendUrl);
		if ($this->_utilHttpdown->IsGetOK()){
			$data=$this->_utilHttpdown->GetRaw();
			if ($data){
				return json_decode($data);
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	/**
	 * @return the $_sendPage
	 */
	public function get_sendPage() {
		return $this->_sendPage;
	}

	/**
	 * @param $_sendPage the $_sendPage to set
	 */
	public function set_sendPage($_sendPage) {
		$this->_sendPage = $_sendPage;
	}


}