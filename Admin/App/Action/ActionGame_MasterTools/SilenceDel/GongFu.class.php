<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceDel_GongFu extends Action_ActionBase{
	
	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime = '';
	protected $_cause;
	protected $_playersData=array();
	protected $_silenceDel = true;

	public function _init(){

		
	}
	public function getPostData($post=null){
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = trim($_GET['playerId']);
		$this->_playerType = 1;
		$this->_cause = '';
		$this->_playersData=array(
			array(
				'playerId'=>$this->_players,
				'playerAccount'=>trim($_GET['playerAccount']),
				'playerNickname'=>trim($_GET['playerNickname']),
			),
		);
		return array(
			'players'=>$this->_players,
			'playerType'=>$this->_playerType,
		);
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == 1){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}	
}