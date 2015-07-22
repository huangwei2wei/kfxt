<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_Wudaopotian extends Action_ActionBase{
	protected $_operatorId;	//运营商ID
	protected $_ordinal;		//服号
	protected $_serverId;		//服务器ID
	protected $_playerAccount;//玩家账号
	protected $_cause;		//操作原因

	public function _init(){
		//前端可选用玩家的什么信息登录游戏
		$this->_assign['loginIdentifier'] = array(
			'playerAccount'=>'玩家账号',
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
			'openid'=>$this->_playerAccount,
		);
		return $postData;
	}

	public function main($UrlAppend=null,$get=null,$post=null){

		if($this->_isPost()){
				
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->_gameObject->_gameId);
			$mark = $this->_getMark($this->_serverId);
			$url = $this->_getUrl($this->_serverId);
			$postData = $this->getPostData($post);
			$send = array_merge($getData,$postData);
			$send['serverid'] = $send['serverId'];
			// 			unset($send['serverId']);
			if(substr($mark,0,3)=="DnC"){
				$send["playerAccount"]	=	$send["openid"];
				unset($send["openid"]);
				$url .= "gmlogin".'?'.http_build_query($send);
			}else{
				$url .= $UrlAppend.'?'.http_build_query($send);
			}
			header('Location: '.$url);
			$this->stop();
		}
		$gameId = $this->_gameObject->_gameId;
		$this->_assign['serverlist'] = json_encode($this->_getGlobalData('server/server_list_'.$gameId));
		return $this->_assign;

	}

	/**private function _getServerId($gameId,$operatorId,$ordinal){
		$modelGameSerList = $this->_getGlobalData('Model_GameSerList','object');
		$sql = "select Id from {$modelGameSerList->tName()} where game_type_id={$gameId} and operator_id={$operatorId} and ordinal={$ordinal}";
		$server = $modelGameSerList->select($sql,1);
		if(!$server){
			$this->stop('服务器不存在',-1);
		}
		return $server['Id'];
	}**/
}