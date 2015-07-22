<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_GameLogin_StarDream extends Action_ActionBase{
	
	public function _init(){
	}
	public function getPostData($post=null){
		$postData =  array(
			'user'=>trim($_POST['user']),
			'userType'=>intval($_POST['userType']),
			'server_id'=>intval($_POST['server_id']),
		);
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if($this->_isPost()){
			$getGetData = $this->_gameObject->getGetData($get);
			$postData = $this->getPostData($post);
			$data = $this->_gameObject->result('GameLogin',$getGetData,$postData);
			if($data['status'] == 1){
				
				$sd = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
				$goUrl = 'http://'.$sd[$postData['server_id']]['marking'].$data['data']['url'];
				
				if(headers_sent()){
					$this->stop("<meta http-equiv='Refresh' content='0;URL={$goUrl}'>");
				} else {
					header("Location: {$goUrl}");
					$this->stop();
				}
				
			} else {
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$gameId = $this->_gameObject->_gameId;
		$this->_assign['serverlist'] = json_encode($this->_getGlobalData('server/server_list_'.$gameId));
		return $this->_assign;
		
	}
}