<?php
class SafetyAction extends BaseAction{
	function _initialize(){
		parent::_initialize();
		$this->checklogin();
	}
	
}