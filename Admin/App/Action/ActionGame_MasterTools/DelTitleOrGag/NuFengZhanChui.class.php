<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_DelTitleOrGag_NuFengZhanChui  extends Action_ActionBase{
	
	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime = '';
	protected $_cause;
	protected $_playersData=array();
	protected $_idDel = true;
	protected $_lockUserTypes = array(); //1 封号 2 禁言
	protected $_lockUserType = 1;
	public function _init(){
		$this->_lockUserTypes = Tools::gameConfig('lockUserType',$this->_gameObject->_gameId);
	}
	public function getPostData($post=null){
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = intval(trim($_GET['playerId']));
		$this->_playerType = 0;
		$this->_lockUserType = trim($_GET['type']);
		$this->_cause = '';
		$this->_playersData=array(
			array(
				'playerId'=>$this->_players,
				'playerAccount'=>trim($_GET['playerAccount']),
				'playerNickname'=>trim($_GET['playerNickname']),
			),
		);
		$postData = array(
			'user'=>trim($this->_players),
			'userType'=>$this->_playerType,
			'type'=>intval($this->_lockUserType),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$postData = $this->_gameObject->getPostData($postData);
		$sendData = array_merge($postData,$get);
		$data = $this->_gameObject->getResult($UrlAppend,$sendData);
	
// 		print_r($data);print_r(json_encode($sendData));exit;
		if($data['status'] == 1){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}	
}