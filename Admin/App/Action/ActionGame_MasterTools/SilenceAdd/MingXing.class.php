<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SilenceAdd_MingXing extends Action_ActionBase{
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
		$plarerType=array(1=>'nickNameList',2=>'openIdList');
		
		$this->_serverId = intval($_REQUEST['server_id']);
		$this->_players = $this->_getPlayers();
		$this->_playerType = intval($_POST['playerType']);
		$this->_endTime = trim($_POST['endTime']);
		$this->reason = trim($_POST['cause']);
		
		
		$validate = array(
			$plarerType[$this->_playerType]=>array('trim','玩家不能为空'),
			'endTime'=>array('time','时间格式错误'),
			'reason'=>array('trim','原因不能为空'),
		);
		$postData =  array(
			$plarerType[$this->_playerType]=>$this->_players,
			'endTime' => strtotime($this->_endTime),
			'reason' => $this->reason,
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
		if($this->_isAjax()){
				$postData = $this->getPostData($post);
				$getData = $this->_gameObject->getGetData($get);
				$getData = (array)array_merge($getData, $postData);
				$data = $this->getResult($UrlAppend,$getData,null);
				if($data['status'] == 1 && is_array($data['data'])){
					foreach($data['data'] as $sub){
						$this->_playersData[]=array(
								'playerId'=>$sub['userId'],
								'playerAccount'=>$sub['userName'],
								'playerNickname'=>$sub['nickName'],
						);
					}
					$this->ajaxReturn(array('status'=>1,'info'=>'操作成功','data'=>null));
				}else{
					$this->ajaxReturn(array('status'=>0,'info'=>'操作失败','data'=>null));
				}
			exit;
		}else{
			if($_REQUEST['sbm']){
				$postData = $this->getPostData($post);
				$getData = $this->_gameObject->getGetData($get);
				$getData = (array)array_merge($getData, $postData);
				$data = $this->getResult($UrlAppend,$getData,null);
				if($data['status'] == 1 && is_array($data['data'])){
					foreach($data['data'] as $sub){
						$this->_playersData[]=array(
								'playerId'=>$sub['userId'],
								'playerAccount'=>$sub['userName'],
								'playerNickname'=>$sub['nickName'],
						);
					}
					$jumpUrl = $this->_urlSilence();
					$this->jump('操作成功',1,$jumpUrl,3);
				}else{
					$this->jump('操作失败:'.$data['info'],-1);
				}
			}
			$this->_assign['players'] = $this->_players;
			return $this->_assign;
		}
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
		$players = trim($_POST['players']);
		$players = str_replace(array("\r\n","\n"), array(',',','), $players);
		return $players;
	}
}