<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_HuanJL extends Action_ActionBase{


	public function _init(){
		//前端可选用玩家的什么信息登录游戏
		$this->_assign['loginIdentifier'] = array(
			'1'=>'玩家id',
			'2'=>'玩家账号',
			'3'=>'玩家昵称'
		);
	}
	public function getPostData($post=null){
		
		$getPostData =  array(
			'userType'=>intval($_POST['type']),
			'user'=>trim($_POST['playerAccount']),
		);
		if($_POST['type'] == 1){
			if(!is_numeric($_POST['playerAccount'])){
				$this->jump("用户id应为数字",-1);
			}
		}
		if($post){
			$getPostData = array_merge($post,getPostData);
		}
		return $getPostData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$url = $this->_getUrl($this->_serverId);
			$send['serverId'] = $send['serverId'];
			
// 			echo $UrlAppend;
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$data = $this->getResult($UrlAppend,$getData,$postData);
		
			if($data['status'] == 1){
				$url .= $data['data'];
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