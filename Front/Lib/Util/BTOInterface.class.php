<?php
/**
 * 商业大亨接口
 * @author php-朱磊
 *
 */
class BTOInterface {
	/**
	 * Util_Httpdown
	 * @var Util_Httpdown
	 */
	private $_httpdown;
	
	private $_key='kelvin818';//key
	
	private $_sendUrl=null;
	
	private $_sendPage='api_interface.php';//发送页面
	
	public function __construct(){
		import('@.Util.Httpdown');
		import('@.Util.ServerSelect');
		import('@.Util.Decrypt');
		$this->_httpDown=new Httpdown();
	}
	
	/**
	 * 设置URL
	 * @param int $serverId 服务器ID
	 */
	public function setServerUrl($serverId){
		$server=new ServerSelect();
		$this->_sendUrl=$server->getServerApiUrl($serverId);
		$this->_sendUrl=$this->_sendUrl['server_url'];
		$this->_sendUrl.=$this->_sendPage;
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
			$getArr['tt']=C('CURRENT_TIME');
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
				$this->_httpDown->AddForm($key,$value);
			}
		}
	}
	
	public function callInterface(){
		if (!$this->_sendUrl)return false;	//如果没有url,将退出
		$this->_httpDown->OpenUrl($this->_sendUrl);
		if ($this->_httpDown->IsGetOK()){
			$data=$this->_httpDown->GetRaw();
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