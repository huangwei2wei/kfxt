<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountAdd_ZhiDouXing extends Action_ActionBase{
	const SEPARATOR = "\n";
	
	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime;
	protected $_cause;
	protected $_playersData=array();
	public function _init(){
		
	}
	public function getPostData($post=null){
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = trim($_POST['account']);
		$this->_endTime = strtotime(trim($_POST['endTime']));
		$this->_cause = trim($_POST['remark']);
		$postData =  array(
			'e'	=>'add',
			'account'=>$this->_players,
			'startTime'=>time(),
			'endTime'=>$this->_endTime,
			'remark'=>$this->_cause,
		);
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$this->_isPost()){
			return $this->_assign;
		}
		
		if($_REQUEST['sbm']){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$jumpUrl = $this->_urlLockAccount();
				
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$this->jump('操作失败:'.$data['info'],-1);
			}
		}
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(self::SEPARATOR,$_POST['playerIds']);
		}
		$this->_assign['players'] = $playerIds;
		return $this->_assign;
	}
	
//"$data" = Array [3]	
//	data = Array [2]	
//		0 = Array [4]	
//			endTime = (int) 1330411559	
//			playerId = (int) 1	
//			playerName = (string:4) 花容失色	
//			accountName = (string:3) 111	
//		1 = Array [4]	
//	status = (int) 1	
//	info = null	
	
	
	private function _urlLockAccount(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
	
	private function _getPlayers(){
		$_POST['players'] = trim($_POST['players']);
		if(isset($_POST['separator']) && $_POST['separator'] && $_POST['separator']!=self::SEPARATOR){
			$_POST['players'] = str_replace($_POST['separator'],self::SEPARATOR,$_POST['players']);
		}
		return $_POST['players'];
	}
	
}