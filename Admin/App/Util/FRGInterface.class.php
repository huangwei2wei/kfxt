<?php
class Util_FRGInterface extends Base {
	
	/**
	 * Util_Httpdown
	 * @var Util_Httpdown
	 */
	private $_httpDown;
	
	private $_encryptKey='kdfndsfasdrn@#$b';	//加密密钥
	private $_userName='client';				//账号
	private $_passWord='KeFu*Sw$_@kfe87s6';			//密码
	private $_superLoginKey='uY*$&n-87N';		//超级密码
	private $_sendUrl=null;					//发送服务器url地址
	
	private $_defaultUrl;
	
	const ENCODE_RPCSERI='RpcSeri';	//传输方式 序列化
	const ENCODE_AJAX='AjaxTran';	//传输方式 json
	
	public function __construct(){
		$this->_httpDown=$this->_getGlobalData('Util_Httpdown','object');
		Tools::setHeadP3P();
	}
	
	/**
	 * @param $_sendUrl the $_sendUrl to set
	 */
	public function set_sendUrl($_sendUrl) {
		$this->_sendUrl = $_sendUrl;
	}
	
	/**
	 * 设置调用服务器url
	 * @param int $serverId 服务器id
	 */
	public function setServerUrl($serverId){
		if(is_numeric($serverId)){
			$gameServerList=$this->_getGlobalData('gameser_list');
			$this->_sendUrl=$gameServerList[$serverId]['server_url'];
		}else{
			$this->_sendUrl = $serverId;
		}
		$this->_sendUrl.='php/interface.php?m=Admin&__sk='.Tools::passport_encrypt($this->_userName.'|'.md5($this->_passWord),$this->_superLoginKey);
		$this->_defaultUrl=$this->_sendUrl;
	}
	
	/**
	 * 获得富人国账号通行证
	 */
	public function getFrgSk($userName='',$passWord='',$LoginKey=''){
		if(empty($userName)){
			$userName = $this->_userName;
		}
		if(empty($passWord)){
			$passWord = $this->_passWord;
		}
		if(empty($LoginKey)){
			$LoginKey = $this->_superLoginKey;
		}
		return Tools::passport_encrypt($userName.'|'.md5($passWord),$LoginKey);
	}
	
	public function getFrgDecrypt($data,$key=''){
		if(!$data){
			return false;
		}
		if(empty($key)){
			$key = $this->_encryptKey;
		}
		return unserialize(Tools::passport_decrypt($data,$key));
	}
	
	/**
	 * 清空get值 
	 */
	public function clearGet(){
		$this->_sendUrl=$this->_defaultUrl;
	}
	
	/**
	 * 传入get值 
	 * @param array $getArr
	 */
	public function setGet($getArr){
		if (!$this->_sendUrl)return false;
		if (is_array($getArr)){
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
	 * 传入post值
	 * @param array $postArr
	 */
	public function setPost($postArr){
		if (is_array($postArr)){
			foreach ($postArr as $key=>$value){
				$this->_httpDown->AddForm($key,$value);
			}
		}

	}
	
	/**
	 * 调用富人国游戏接口
	 * @param string $encode 返回编码方式self::ENCODE_RPCSERI(序列化[默认]) 和 self::ENCODE_AJAX(json方式)
	 * @return array
	 */
	public function callInterface($encode='RpcSeri'){
		if (!$this->_sendUrl)return false;	//如果没有url,将退出
		$this->_sendUrl.="&__hj_dt={$encode}";
		$this->_httpDown->OpenUrl($this->_sendUrl);
		if ($this->_httpDown->IsGetOK()){
			$data=$this->_httpDown->GetRaw();
			if ($encode==self::ENCODE_RPCSERI){
				return unserialize(Tools::passport_decrypt($data,$this->_encryptKey));
			}else{
				return $data;
			}
		}else {
			return false;
		}
	}
}