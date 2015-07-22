<?php
Tools::import('Game_GameBase');
class Game_99 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 99;		//游戏Id
		$this->_zp = 'Aveyond';	//控制器扩展包
		$this->_key = '@#DFGFG5652$FG';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
		return $this->commonChk($this->_key);
	}
	
	public function sendOrderReplay($data=NULL){
		return true;
	}
	
	public function autoReplay($data=NULL){
		return false;
	}
	
	public function operatorExtParam(){
		return array();
	}
	
	public function serverExtParam(){
		return array();
	}
	
}