<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountDel_DaoJian extends Action_ActionBase{
	
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
// 		print_r($postData);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);
// 		print_r($this->_playersData);exit;
		if($data['status'] == 0){
			$this->jump('操作成功',1);
		}else{
			$this->jump('操作失败:'.$data['info'],-1);
		}
	}	
}