<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_SanFen extends Action_ActionBase{
	protected $_operatorId;	//运营商ID
	protected $_ordinal;		//服号
	protected $_serverId;		//服务器ID
	protected $_playerAccount;//玩家账号
	protected $_cause;		//操作原因

	public function _init(){
		//前端可选用玩家的什么信息登录游戏
		$this->_assign['loginIdentifier'] = array(
			1=>'玩家id',
			2=>'玩家账号',
			3=>'玩家昵称',
		);
	}
	public function getPostData($post=null){
		$gameId = $this->_gameObject->_gameId;
		$this->_operatorId = intval($_POST['operator_id']);
		$this->_ordinal = intval($_POST['ordinal']);
		$this->_serverId = intval($_POST['server_id']);//$this->_getServerId($gameId,$this->_operatorId,$this->_ordinal);
		$this->_playerAccount = trim($_POST['playerAccount']);
		$this->_cause = $_POST['cause'];
		$postData =  array(
			'player'=>$this->_playerAccount,
			'playerType'=>trim($_POST['type']),
		);
		
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 			print_r($data);exit;
			if($data['status']==1){
				$url = $this->_getUrl($this->_serverId);
				$url = "http://".$data['data'];
// 				header('Location: '.$url);
// 				$this->stop();
				$this->_assign['url'] = $url;
				$this->_assign['_body']	= "ActionGame_MasterTools/GameLogin/Login.html";
				return $this->_assign;
			}else{
				echo $data['info'];exit;
			}
		}
		$gameId = $this->_gameObject->_gameId;
		$this->_assign['serverlist'] = json_encode($this->_getGlobalData('server/server_list_'.$gameId));
		return $this->_assign;
		
	}
	
//	private function _getServerId($gameId,$operatorId,$ordinal){
//		$modelGameSerList = $this->_getGlobalData('Model_GameSerList','object');
//		$sql = "select Id from {$modelGameSerList->tName()} where game_type_id={$gameId} and operator_id={$operatorId} and ordinal={$ordinal}";
//		$server = $modelGameSerList->select($sql,1);
//		if(!$server){
//			$this->stop('服务器不存在',-1);
//		}
//		return $server['Id'];
//	}
}