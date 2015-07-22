<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_StarDream extends Action_ActionBase{
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
		$this->_players = trim($_POST['user']);
		$this->_playerType = intval($_POST['userType']);
		$this->_endTime = strtotime($_POST['endTime']);
		$this->_cause = trim($_POST['cause']);
		$validate = array(
			'user'=>array('trim','玩家不能为空'),
			'userType'=>array(array('in_array','###',array(0,1,2)),'玩家类型错误'),
		);
		$postData =  array(
			'user'=>$this->_players,
			'userType'=>$this->_playerType,
			'endTime'=>$this->_endTime,
			'type'=>2
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
			$postData["userType"]	=	intval($_POST["userType"]);
			if($postData["userType"]==3){
				$postData["user"]		=	base64_encode($postData["user"]);
			}else{
				$postData["user"]		=	$postData["user"];
			}
			$getGetData = $this->_gameObject->getGetData($get);
			$data = $this->_gameObject->result('SilenceAdd',$getGetData,$postData);
			
			if($data['status'] == 1){
				$jumpUrl = $this->_urlSilence();
				
				$arr = explode(",",$postData["user"]);
				$typelist = array('playerId','playerAccount','playerNickname');
				foreach($arr as $a){
					$b = array('playerId'=>0,'playerAccount'=>'','playerNickname'=>'');
					$b[ $typelist[ $postData['userType'] ] ] = trim($a);
					
					$this->_playersData[] = $b;
				}
				
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$this->jump('操作失败:'.$data['info'],-1);
			}
		}
		
		return $this->_assign;
	}
	
	private function _urlSilence(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

}