<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_chunqiu extends Action_ActionBase{
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
		$this->_players = $this->_getPlayers();
		$this->_playerType = intval($_POST['playerType']);
		$this->_endTime = strtotime($_POST['endTime']);
		$this->_cause = trim($_POST['cause']);
		$validate = array(
			'users'=>array('trim','玩家不能为空'),
			'playerType'=>array(array('in_array','###',array(1,2,3)),'玩家类型错误'),
		);
		$postData =  array(
			'users'=>$this->_players,
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
			$postData["userType"]	=	intval($_POST["playerType"]);
			if($postData["userType"]==3){
				$postData["users"]		=	base64_encode($postData["users"]);
			}else{
				$postData["users"]		=	$postData["users"];
			}
			$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
			$data = json_decode($data,true);
			if($data['status'] == 1){
				$jumpUrl = $this->_urlSilence();

				$arr = explode(",",$this->_players);
				$typelist = array(1=>'playerId',2=>'playerAccount',3=>'playerNickname');
				foreach($arr as $a){
					$b = array('playerId'=>0,'playerAccount'=>'','playerNickname'=>'');
					$b[ $typelist[ $postData['userType'] ] ] = trim($a);
					
					$this->_playersData[] = $b;
				}
				
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$this->jump('操作失败:'.$data['error'],-1);
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


	private function _urlSilence(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

	private function _getPlayers(){
		$_POST['users'] = trim($_POST['users']);
		if(isset($_POST['separator']) && $_POST['separator'] && $_POST['separator']!=self::SEPARATOR){
			$_POST['users'] = str_replace($_POST['separator'],self::SEPARATOR,$_POST['users']);
		}
		return $_POST['users'];
	}
}