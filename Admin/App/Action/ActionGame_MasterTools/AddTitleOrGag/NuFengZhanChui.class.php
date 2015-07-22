<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_AddTitleOrGag_NuFengZhanChui  extends Action_ActionBase{
	const SEPARATOR = "\n";
	protected $_serverId;
	protected $_players;
	protected $_playerType;
	protected $_endTime;
	protected $_cause;
	protected $_playersData=array();
	protected $_lockUserTypes = array(); //1 封号 2 禁言
	protected $_lockUserType = 1;
	public function _init(){
		$this->_lockUserTypes = Tools::gameConfig('lockUserType',$this->_gameObject->_gameId);
		$this->_assign['userType'] = Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_assign['lockUserType'] = $this->_lockUserTypes;
	}
	public function getPostData($post=null){
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = $this->_getPlayers();
		$this->_playerType = intval($_POST['userType']);
		$this->_endTime = trim($_POST['endTime']);
		$this->_cause = trim($_POST['cause']);
		$this->_lockUserType = intval(trim($_POST['lockUserType']));
		$validate = array(
			'user'=>array('trim','玩家不能为空'),
			'userType'=>array(array('in_array','###',array(0,1,2)),'玩家类型错误'),
		);
		$postData =  array(
			'user'=>$this->_players,
			'userType'=>$this->_playerType,
			'endTime'=>strtotime($this->_endTime),
			'type'=>$this->_lockUserType,
		);
		$check = Tools::arrValidate($postData,$validate);
		if ($check!==true){
			$this->jump($check,-1);
		}
		if($post && is_array($post)){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$this->_isPost()){
			return $this->_assign;
		}
		if($_REQUEST['sbm']){
			$postData = $this->getPostData($post);
			$postData = $this->_gameObject->getPostData($postData);
			$sendData = array_merge($get,$postData);
			$data = $this->_gameObject->getResult($UrlAppend,$sendData);
// 			echo json_encode($postData);print_r( $data);exit;
			if($data['status'] == 1 && is_array($data['data']['list'])){
				foreach($data['data']['list'] as $sub){
					$this->_playersData[]=array(
						'playerId'=>trim($sub['userID']),
						'playerAccount'=>trim($sub['userName']),
						'playerNickname'=>trim($sub['nickName']),
					);
				}
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
	
	private function _urlLockAccount(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'TitleOrGag',$query);
	}
	private function _getPlayers(){
		$_POST['players'] = trim($_POST['players']);
		$_POST['players'] = str_replace(array("\r\n", "\n", "\r",'，'),array(',',',',',',','),$_POST['players']);
		return $_POST['players'];
	}
	
}