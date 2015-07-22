<?php
abstract class Log_LogBase extends Base implements SplObserver{
	private $_modelGameOperateLog;
	public function __construct(){
		$_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$this->_init();
	}
	public function _init(){}
}