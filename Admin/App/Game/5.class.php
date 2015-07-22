<?php
Tools::import('Game_GameBase');
class Game_5 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	
	
	public function _init(){
		$this->_gameId = 5;		//游戏Id
		$this->_zp = 'XunXia';	//控制器扩展包
		$this->_key = 'Aqf2i(*jh8H#D@$RFT@#uw@!~@#fOf31';	//游戏密匙
		$this->_timer = true;	//是否使用定时器
		$this->_urlApdWO = array(	//使用定时器的工单请求地址附加字符
			'new'=>'question/questionsNotGivenSend',
			'newCbk'=>'question/questionsNotGivenReceive',
			'del'=>'question/questionsHadDelete',
			'delCbk'=>'question/afterSendDelQs',
			'ev'=>'question/questionsHadEvulate',
			'evCbk'=>'question/afterSendEvuQs',
		);
		$this->_contentSelf = true;
		$this->_sendImage = true;
	}
	
	public function workOrderIfChk(){
// 		return $this->commonChk();
		
		$r = $this->commonChk();
		if($r === true){
			return $r;
		}
		$r = $this->clientChk();
		if($r === true){
			return $r;
		}
		
		$r = $this->clientTimeChk();
		if(true === $r || $r == 'TimeOut'){
			return true === $r?true:'TimeOut';
		}
		return false;
	}
	
	public function sendOrderReplay($data=NULL){
		if(!$data || empty($data['content'])){
			return 'Can not send empty data';
		}
		if($data['file_img'] && is_file($data['file_img'])){
			$webPath = pathinfo($data['file_img']);
			$webPath = 'http://'.$_SERVER['HTTP_HOST'].'/Upload/Service/'.date('Ymd',CURRENT_TIME).'/'.$webPath["basename"];
			$data['content'] .= "<br/><img src=\"{$webPath}\"/>";
		}
		$utilRpc=$this->_getGlobalData('Util_Rpc','object');
		$utilRpc->setUrl($data['send_url'].'question/answerQuestion');
		$dataResult=$utilRpc->answerQuestion($data['work_order_id'],$data['service_id'],$data['status'],$data['content']);
		return true; // 直接返回 true  
		if($dataResult ===0){
			return true;
		}
		return Tools::getLang('SEND_MSG','Control_WorkOrder').'<br>'.serialize($dataResult);
	}
	
	public function autoReplay($data=NULL){
		$api	=	$this->_getGlobalData('Util_Rpc','object');
		$api->setUrl($data["server_msg"]['game_server_id'],'msg/msg');
		$re=$api->saveMsg(1,$data['title'],$data['content'],$data["server_msg"]['game_user_id']);
		//$re=json_decode($re,true);
		if (isset($data->code)){
			return true;
		}else {
			return false;
		}
	}
	
	public function operatorExtParam(){
		return array();
	}
	
	public function serverExtParam(){
		return array();
	}
	
}