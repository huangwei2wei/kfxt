<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PointDel_GongFu extends Action_ActionBase{
	
	protected $_serverId;
	protected $_playerType;
	protected $_players;
	protected $_asset;
	protected $_gold;
	protected $_goldTicket;
	
	public function _init(){
		$this->_assign['URL_playerQuery'] = Tools::url(
			'MasterTools',
			'PlayerLookup',
			array(
				'zp'=>'ActionGame',
				'server_id'=>$_REQUEST['server_id'],
				'__game_id'=>$this->_gameObject->_gameId,
				'sbm'=>'1',
			)
		);
	}
	
	public function getPostData($post=null){
		$this->_serverId = $_REQUEST['server_id'];
		$this->_playerType = intval($_POST['playerType']);
		$this->_players = trim($_POST['players']);
		$this->_asset = intval($_POST['asset']);
		$this->_gold = intval($_POST['gold']);
		$this->_goldTicket = intval($_POST['goldTicket']);
		$postData=array(
			'playerType'=>$this->_playerType,
			'playerValue'=>$this->_players,
			'asset'=>$this->_asset * -1,
			'gold'=>$this->_gold * -1,
			'goldTicket'=>$this->_goldTicket * -1,
		);
		if($post && is_array($post)){
			$postData = array_merge($post,$postData);
		}
		$validate = array(
			'playerValue'=>array('trim','玩家ID不能为空'),
			'asset'=>$postData['asset']?array(array('min',0,'###'),'银两范围错误'):'',
			'gold'=>$postData['gold']?array(array('min',0,'###'),'金币范围错误'):'',
			'goldTicket'=>$postData['goldTicket']?array(array('min',0,'###'),'银票范围错误'):'',
		);
		$check = Tools::arrValidate($postData,$validate);
		if($check !== true){
			$this->jump($check,-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || !$_REQUEST['sbm']){
			
			$playerIds = '';
			if($_POST['playerIds'] && is_array($_POST['playerIds']) ){
				$playerIds = trim(array_shift($_POST['playerIds']));
			}
			$this->_assign['players'] = $playerIds;
			
			return $this->_assign;
		}
		$getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if(is_array($data) && $data['status']==1){
			$this->jump('操作成功',1);
		}
		$this->jump('操作失败',-1);
	}
}