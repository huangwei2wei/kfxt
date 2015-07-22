<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_DaoJian extends Action_ActionBase{

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
		$this->_players = $this->_getPlayers();
		$this->_playerType = intval($_POST['playerType']);
		$this->_endTime = trim($_POST['endTime']);
		$this->_cause = trim($_POST['cause']);
		$validate = array(
			'players'=>array('trim','玩家不能为空'),
			'playerType'=>array(array('in_array','###',array(1,2,3)),'玩家类型错误'),
			
		);
		$postData =  array(
			'players'=>$this->_players,
			'playerType'=>$this->_playerType,
			'endTime'=>$this->_endTime
		);
		$check = Tools::arrValidate($postData,$validate);
		if ($check!==true){
			$this->jump($check,-1);
		}
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
			
//			print_r($getData);
//			print_r($postData);
//			print_r($data);
// 			exit;
			
			if($data['status'] == 0){
				foreach($data['data'] as $sub){
					var_dump( $sub);
					$this->_playersData[]=array(
							'playerId' => $sub,
// 							'playerId'=>trim($sub['UserID']),
// 							'playerAccount'=>trim($sub['UserName']),
// 							'playerNickname'=>trim($sub['NickName']),
					);
				}
				$jumpUrl = $this->_urlSilence();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$this->jump('操作失败:'.$data['info'],-1);
			}	
		}
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
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
	
	
	private function _urlSilence(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}
	
	private function _getPlayers(){
		$_POST['players'] = trim($_POST['players']);
		$_POST['players'] = str_replace(array("\r\n", "\n", "\r"),array(',',',',','),$_POST['players']);
		return $_POST['players'];
	}
}