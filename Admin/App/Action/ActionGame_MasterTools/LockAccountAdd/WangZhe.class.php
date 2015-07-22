<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountAdd_WangZhe extends Action_ActionBase{
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
		$this->_endTime = trim($_POST['endTime']);
		$this->_cause = trim($_POST['cause']);
		$validate = array(
			'playerIds'=>array('trim','玩家不能为空'),
			'playerType'=>array(array('in_array','###',array(1,2,3)),'玩家类型错误'),
			
		);
		$postData =  array(
			'playerIds'=>$this->_players,
			'playerType'=>$this->_playerType,
			'endTime'=>urlencode(trim($_POST['endTime']))
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
			$getData = array_merge($postData,$getData);
			$data = $this->getResult($UrlAppend,$getData,null);
// 			print_r($data);exit;
			if($data['status'] == 1 && is_array($data['data']['list'])){
				foreach($data['data']['list'] as $sub){
					$this->_playersData[]=array(
						'playerId'=>trim($sub['playerId']),
						'playerAccount'=>trim($sub['accountName']),
						'playerNickname'=>trim($sub['playerName']),
					);
				}
				$jumpUrl = $this->_urlLockAccount();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$this->jump('操作失败:'.$data['info'],-1);
			}
		}elseif ($this->_isAjax()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			var_dump($data);
			if($data['status'] == 1 ){
				if(is_array($data['data'])){
					foreach($data['data'] as $sub){
						$this->_playersData[]=array(
							'playerId'=>$sub['playerId'],
							'playerAccount'=>$sub['accountName'],
							'playerNickname'=>$sub['playerName'],
						);
					}
				}
				$data['info']='封号成功';
				$this->ajaxReturn($data);
			}else{
				$data['info']='封号失败';
				$this->ajaxReturn($data);
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
		$_POST['players'] = str_replace(array("\r\n", "\n", "\r"),array(',',',',','),$_POST['players']);
		return base64_encode($_POST['players']);
	}
	
}