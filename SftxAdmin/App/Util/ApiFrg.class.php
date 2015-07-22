<?php
Tools::import('Util_Curl');
/**
 * 富人国新api接口,用于解决并发问题
 * @author php-朱磊
 *
 */
class Util_ApiFrg extends Util_Curl {

	const ENCRYPT_KEY='kdfndsfasdrn@#$b';	//加密密钥
	const USER_NAME='client';				//账号
	const PASSWORD='KeFu*Sw$_@kfe87s6';		//密码
	const SUPER_LOGIN_KEY='uY*$&n-87N';			//超级密码

	const ENCODE_RPCSERI='RpcSeri';			//传输方式 序列化
	const ENCODE_AJAX='AjaxTran';			//传输方式 json
	const ENCODE_HTML='HtmlTemplate';		//返回html页面
	

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 增加一个http请求
	 * @param int/string $server 服务器流水号/url地址
	 * @param array $get	get值
	 * @param array $post   post值
	 */
	public function addHttp($server,$get=NULL,$post=NULL){
		if (is_numeric($server)){
			$key=$server;
			$gameServerList=$this->_getGlobalData('gameser_list');
			$url=$gameServerList[$server]['server_url'].'php/interface.php?m=Admin&__hj_dt='.self::ENCODE_RPCSERI.'&__sk='.Tools::passport_encrypt(self::USER_NAME.'|'.md5(self::PASSWORD),self::SUPER_LOGIN_KEY);
			parent::_addHttp($key,$url,$get,$post);
		}else {
			static $autoKey=0;
			$autoKey++;
			$url=$server;
			parent::_addHttp($autoKey,$url,$get,$post);
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
	public function getResult($key=null,$getType='RpcSeri'){
		if (!count($this->_result))return false;
		if ($key){
			$retResult=$this->_result[$key];
		}else {
			$retResult=array_shift($this->_result);
		}
		switch ($getType){
			case self::ENCODE_HTML :{
				break;
			}
			default:{//默认序列表
				$retResult=unserialize(Tools::passport_decrypt($retResult,self::ENCRYPT_KEY));
			}
		}
		return $retResult;
	}
	

	/**
	 * 获取所有结果集
	 * @param string $getType 默认序列化
	 */
	public function getResults($getType='RpcSeri'){
		foreach ($this->_result as $key=>&$value){
			$value=unserialize(Tools::passport_decrypt($value,self::ENCRYPT_KEY));
		}
		return $this->_result;
	}



}