<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountDel_zhanlong extends Action_ActionBase{

	private $_utilMsg;
	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime = '';
	protected $_cause;
	protected $_playersData=array();
	protected $_silenceDel = true;
	public function _init(){
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = trim($_GET['AccountName']);
		$this->_playerType = 1;
		$this->_cause = '';
		$this->_playersData[]=array(
							'playerAccount'=>trim($_GET['AccountName']),
							"playerId"=>0,
		);
		$getData["AccountName"]	=	$_GET['AccountName'];
		$getData["Remove"]	=	intval(1);
		$data = $this->getResult($UrlAppend,$getData);

		if($data["Result"]===0){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
}