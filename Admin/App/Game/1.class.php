<?php
Tools::import('Game_GameBase');
class Game_1 extends Game_GameBase{
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 1;
		$this->_sendImage = true;
	}
	
	public function workOrderIfChk(){
		return $this->commonChk();
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		$_utilApiBto=$this->_getGlobalData('Util_ApiBto','object');
		$sendUrl=$data['send_url'];
		$sendUrl.='api_interface.php?action=Faq&doaction=GetClientReply';
		$sendUrl	=	trim($sendUrl);
		$fileData = $data['file_img'];
		$isFile = is_file($fileData);
		if($isFile){
			$webPath = pathinfo($fileData);
			$data['ReplyFile'] = 'http://'.$_SERVER['HTTP_HOST'].'/Upload/Service/'.date('Ymd',CURRENT_TIME).'/'.$webPath["basename"];
		}
		unset($data['send_url']);
		$_utilApiBto->addHttp($sendUrl,null,$data);
		$_utilApiBto->send();
		$dataResult=$_utilApiBto->getResult();
		if ($dataResult['status']==1){
			return true;
		}else {
			if($isFile){
				unlink($fileData);
			}
			return Tools::getLang('SEND_MSG','Control_WorkOrder');
		}
	}
	
	public function autoReplay($data=NULL){
		$data['game_user_id']	=	$data['player'];
		unset($data['player']);
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		$_utilApiBto=$this->_getGlobalData('Util_ApiBto','object');
		$sendUrl=$data['send_url'];
		$sendUrl.='api_interface.php?action=FaqService&doaction=ClientSendMsg';
		$sendUrl	=	trim($sendUrl);
		unset($data['send_url']);
		$_utilApiBto->addHttp($sendUrl,null,$data);
		$_utilApiBto->send();
		$dataResult=$_utilApiBto->getResult();
		if ($dataResult['status']==1){
			return true;
		}else {
			return false;
		}
	}
	
	public function operatorExtParam(){
		return array(
			array('syskey','系统登录密匙','password',''),	//字段，描述，表单类型，默认值
			array('co_action','合作方标识','text',''),
			array('GameId','游戏标识ID','text',''),
		);
	}
	
	public function serverExtParam(){
		return array();
	}
	
}