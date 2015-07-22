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
		$_GET['Id']=abs(intval($_GET['Id']));
	}

	public function runGlobal(){
		$curAction=CONTROL.'_'.ACTION;
		switch ($this->_utilRbac->checkAct($curAction)){
			case 1 :{//已经登录,通过
				return true;
			}
			case -1 :{//没有权限
				$this->_utilMsg->showMsg('您没有权限',-1);
				break;
			}
			case -2 :{//未登录
				$this->_utilMsg->showMsg('您还未登录',-2,Tools::url('Index','Login'));
			}
		}

	}


}