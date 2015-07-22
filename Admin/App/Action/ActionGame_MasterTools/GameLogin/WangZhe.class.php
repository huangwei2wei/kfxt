<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_WangZhe extends Action_ActionBase{


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
			'playerType'=>intval($_POST['type']),
			'playerId'=>base64_encode(trim($_POST['playerAccount'])),
		);
		if($post){
			$getPostData = array_merge($post,getPostData);
		}
		return $getPostData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get,intval($_POST['server_id']));
			$url = $this->_getUrl(intval($_POST['server_id']));
			$send = array_merge($getData,$postData);
// 			unset($send['serverId']);
if(in_array($_POST['operator_id'], array(83))){
	$send['type'] = 1;
}else{
	$send['type'] = 0;
}
			$url .= $UrlAppend.'?'.http_build_query($send);
// 			echo $url;exit;
			header('Location: '.$url);
			$this->stop();
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