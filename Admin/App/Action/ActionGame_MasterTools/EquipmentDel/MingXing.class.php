<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_EquipmentDel_MingXing extends Action_ActionBase{
	
	public function _init(){}
	
	public function getPostData($post=null){
		$postData = array();
		$postData['nickName'] = urlencode(trim($_POST['nickName']));
		$postData['openId'] = trim($_POST['openId']);
		$postData['equipmentId'] = trim($_POST['equipmentId']);
		$postData['equipmentName'] = trim($_POST['equipmentName']);
		$postData['cause'] = trim($_POST['cause']);
		if(!($postData['nickName'].$postData['openId'])){
			$this->jump('玩家不能为空',-1);
		}
		if(!$postData['equipmentId']){
			$this->jump('equipmentId Error',-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			$this->jump('server_id error',-1);
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData=$this->getPostData($post);
		$sendData = array_merge($getData,$postData);
//		$data = $this->getResult($UrlAppend,$sendData);
		if($this->_apply($UrlAppend,$sendData) ){
			$this->jump('申请成功',1);
		}else{
			$this->jump('申请失败',-1);
		}
	}
	
	private function _apply($UrlAppend ='',$getData=array(),$postData=array()){
		$cause = trim($getData['cause']);
		if(empty($cause)){
			$this->jump('操作原因缺少',-1);
		}
		$applyInfo="操作原因：{$cause}
			玩家昵称：{$getData['nickName']}
			openId：{$getData['openId']}
			装备名称：{$getData['equipmentName']}
		";
		
		$serverId = intval($_REQUEST['server_id']);
		$applyData = array(
			'type'=>33,
			'server_id'=>$serverId,
			'operator_id'=>$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>$applyInfo,
			'send_type'=>2,	//2	http
			'send_data'=>array(
				'url_append'=>$UrlAppend,
				'post_data'=>$postData,
				'get_data'=>$getData,
//				'call'=>array(
//					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
//					'cal_local_method'=>'SendcartFile',
//				),
				'end'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
					'cal_local_method'=>'applyEnd',	//使用本地方法
					'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
				),
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>$getData['openId']?2:3,
			'player_info'=>$getData['openId']?$getData['openId']:$getData['nickName'],
		);
		$modelApply = $this->_getGlobalData('Model_Apply','object');
		return $modelApply->AddApply($applyData);
	}
}