<?php
class Util_HttpInterface extends Base {

	/**
	 * Util_Httpdown
	 * @var Util_Httpdown
	 */
	private $_httpDown;


	private $_sendUrl=null;					//发送服务器url地址

	private $_defaultUrl;


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
	public function setServerUrl($server,$UrlAppend=NULL){
		if(is_numeric($server)){
			$gameServerList=$this->_getGlobalData('gameser_list');
			$this->_sendUrl=$gameServerList[$server]['server_url'].$UrlAppend;
		}else{
			$this->_sendUrl=$server.$UrlAppend;
		}
		$this->_defaultUrl=$this->_sendUrl;
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
	 * 调用接口
	 * @param string $encode 返回编码方式self::ENCODE_RPCSERI(序列化[默认]) 和 self::ENCODE_AJAX(json方式)
	 * @return array
	 */
	public function callInterface($isAjax=false){
		if(!$isAjax)echo $this->_sendUrl;
		$this->_httpDown->OpenUrl($this->_sendUrl);
		if ($this->_httpDown->IsGetOK()){
			$data=$this->_httpDown->GetRaw();
			$this->_httpDown->Close();
// 			var_dump($data);
			return $data;
		}else {
			return false;
		}
	}

	/**
	 * 增加一个http请求
	 * @param int/string $server 服务器流水号/url地址
	 * @param array $get	get值
	 * @param array $post   post值
	 */
	public function result($server,$UrlAppend=NULL,$get=NULL,$post=NULL,$isAjax=false){
		if(is_numeric($server)){
			$gameServerList=$this->_getGlobalData('gameser_list');
			$this->_sendUrl=$gameServerList[$server]['server_url'].$UrlAppend;
		}else{
			$this->_sendUrl=$server.$UrlAppend;
		}

		$this->_defaultUrl=$this->_sendUrl;
		if($get){
			$this->setGet($get);
		}
		if($post){
			$this->setPost($post);
		}
		return $this->callInterface($isAjax);
	}
}