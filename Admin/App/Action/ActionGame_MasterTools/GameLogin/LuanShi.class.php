<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_LuanShi extends Action_ActionBase{


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
			'playerValue'=>trim($_POST['playerAccount']),
		);
		if($post){
			$getPostData = array_merge($post,getPostData);
		}
		return $getPostData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		
		if($this->_isPost()){
			$postData = $this->getPostData($post);
// 			$url = $this->_getUrl($this->_serverId);
			$send['serverId'] = $send['serverId'];
			
			$getData = $this->_gameObject->getGetData($get,$this->_serverId);
			$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);exit;
			if($data['status'] == 1){
				$url = $data['data']['url'];
// 				header('Location: '.$url);
// 				$this->stop();
// echo $url;exit;
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
	
}