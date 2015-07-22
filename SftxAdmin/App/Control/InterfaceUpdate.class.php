<?php
/**
 * 系统更新表class接口
 * @author php-朱磊
 *
 */
class Control_InterfaceUpdate extends Control {
	
	/**
	 * key
	 * @var string
	 */
	private $_key = TAKE_KEY;
	
	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;
	
	public function __construct(){
		if (! $this->_initialize ())//如果不通过验证将退出返回出错数据
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'ERROR', 'data' => null ) );
	}
	
	/**
	 * 是否通过验证
	 */
	private function _initialize() {
		$sign = $_REQUEST ['_sign'];
		$verifyCode = $_REQUEST ['_verifycode'];
		if (isset ( $sign ) && isset ( $verifyCode )) {
			if (md5 ( $this->_key . $verifyCode ) == $sign) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function actionUpdateSql(){
		$this->_modelSysconfig=$this->_getGlobalData('Model_Sysconfig','object');
		$sql=array();
		foreach ($sql as $list){
			$this->_modelSysconfig->execute($list);
		}
	}

}