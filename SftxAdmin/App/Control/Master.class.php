<?php
/**
 * 管理员功能模块
 * @author php-朱磊
 *
 */
class Control_Master extends Control {
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();	
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
}