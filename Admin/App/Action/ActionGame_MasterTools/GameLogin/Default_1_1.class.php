<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_Default_1_1 extends Action_ActionBase{

	private $userType;
	public function _init(){
		$this->userType = Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_assign['loginIdentifier'] = $this->userType;
		$this->_assign['loginIdentifier'] = array(0=>'玩家ID',1=>'玩家账号',2=>'玩家昵称');
	}
	public function getPostData($post=null){
		$getPostData =  array(
			'userType'=>intval($_POST['type']),
			'user'=>trim($_POST['playerAccount']),
		);
		if($post){
			$getPostData = array_merge($post,getPostData);
		}
		return $getPostData;
	}

	public function main($UrlAppend=null,$get=null,$post=null){

		if($this->_isPost()){
			$url = $this->_gameObject->getServerMarking($this->_serverId);
			$get  =  $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$get);
			if($data['status'] == 1){
				$url = 'http://'.$url.'/'.$data['data']['url'];
				// 				echo $url;exit;
				// 				header('Location: '.$url);
				// 				$this->stop();
				$this->_assign['url'] = $url;
				$this->_assign['_body']	= "ActionGame_MasterTools/GameLogin/Login.html";
				return $this->_assign;
			}else{
				echo '玩家不存在';exit;
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