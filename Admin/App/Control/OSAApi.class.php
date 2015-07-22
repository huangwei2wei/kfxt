<?php
class Control_OSAApi extends Control {
	
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	private $_curUnion;
	
	private $_curAction;
	
	private $_account = array ('360safe' => array (
									'class' => 'Util_UnionOperation_Safe360', 
									'sign' => '1316063548', 
									'action' => array (
												'getLibaoCard_2',		//富人国获取礼包卡号动作 
												) 
									)
								 
								);
	
	public function __construct() {
		$this->_curUnion = $_REQUEST ['union'];
		$this->_curAction = $_REQUEST ['action'];
		Tools::import ( 'Util_UnionOperation_UnionBase' );
		$this->_dispatch ();
		exit ();
	}
	
	private function _check() {
		if (! array_key_exists ( $this->_curUnion, $this->_account ))
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'error', 'data' => null ) );
		if (! in_array ( $this->_curAction, $this->_account [$this->_curUnion] ['action'] ))
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'error', 'data' => null ) );
		$verifycode = $_REQUEST ['verifycode'];
		$sign = $_REQUEST ['sign'];
		if (md5 ( $this->_account [$this->_curUnion] ['sign'] . $verifycode ) != $sign) {
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'error', 'data' => null ) );
		}
	}
	
	/**
	 * 事件纷发
	 */
	private function _dispatch() {
		Tools::import ( $this->_account [$this->_curUnion] ['class'] );
		$obj = new $this->_account [$this->_curUnion] ['class'] ();
		$action=$this->_curAction;
		$msg = $obj->$action ();
		$this->_returnAjaxJson ( $msg );
	}
}