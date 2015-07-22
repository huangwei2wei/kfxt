<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_NuFengZhanChui extends Action_ActionBase{
	private $_userType = array();
	public function _init(){
		$this->_userType =  Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_assign['userType'] = $this->_userType;
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isAjax()){
			$data = array(
				'userType'=>intval(trim($_POST['userType'])),
				'user'=>str_replace('，', ',', trim($_POST['user'])),
				'title'=>trim($_POST['title']),
				'content'=>trim($_POST['content']),
			);
			$postData = $this->_gameObject->getPostData($post);
			$sendData = array_merge($data,$postData,$get);
			$data = $this->_gameObject->getResult($UrlAppend,$sendData);
			
// 			echo json_encode($sendData);print_r($data);exit;
			
			if($data['status'] == 1){
				$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！','data'=>null));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>'发送失败！','data'=>null));
			}
		}
		
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['players'] = $playerIds;
		
		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
		return $this->_assign;
	}
	
	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id']
		);
		return Tools::url(CONTROL,'Notice',$query);
	}
	
}