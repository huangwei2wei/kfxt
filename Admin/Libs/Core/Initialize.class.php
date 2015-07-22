<?php
/**
 * 初始化操作
 * 默认注册的全局对象有:Util_Msg,Util_Rbac,Object_UserInfo
 * 默认引入的页面有 : Util_Rbac,Util_Msg,Object_UserInfo
 * 自动格式化$_GET['Id']
 * @author PHP-朱磊
 *
 */
class Initialize extends Base {

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	public function __construct(){
		//全局载入的class文件
		Tools::import('Object_UserInfo');
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
	}

	public function runGlobal(){
		$curAction=CONTROL.'_'.ACTION;
		if (MAGIC_QUOTES){//如果开启了魔术引号就去掉那些"\"
			if (count($_POST))Tools::arrayMap($_POST,array('stripcslashes'));
//			Tools::arrayMap($_GET,array('stripcslashes'));
//			Tools::arrayMap($_COOKIE,array('stripcslashes'));
		}
		$package=defined('PACKAGE')?PACKAGE:null;
		switch ($this->_utilRbac->checkAct($curAction,$package)){
			case 1 :{//已经登录,通过
				return true;
			}
			case -1 :{//没有权限
				$errorInfo = '您没有权限';
				if(isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest'){
					$result = array('status'=>0,'info'=>$errorInfo,'data'=>NULL);
					exit(json_encode($result));
				}
				$this->_utilMsg->showMsg($errorInfo,-1);
				break;
			}
			case -2 ://未登录
			case -3 ://账号停用
			default:{
				$this->_utilMsg->showMsg('您还未登录,或账号被停用',-2,PASSPORT_URL);
			}
		}

	}


}