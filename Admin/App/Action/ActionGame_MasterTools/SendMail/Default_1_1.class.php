<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_Default_1_1 extends Action_ActionBase{
	public function _init(){
		$this->_userType =  Tools::gameConfig('userType',$this->_gameObject->_gameId);
		$this->_assign['userType'] = array(0=>'玩家ID',1=>'玩家账号',2=>'玩家昵称');
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost()){
			$data = array(
				'userType'=>trim($_POST['userType']),
				'user'=>str_replace('，', ',', trim($_POST['users'])),
				'title'=>trim($_POST['title']),
				'content'=> htmlspecialchars( trim($_POST['content']) ),
			);
			$get  =  $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$get,$data);
			if($data['status'] == 1){
				//$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！','data'=>null));
				$this->jump('发送成功',1);
			}else{
				//$this->ajaxReturn(array('status'=>0,'info'=>'发送失败！','data'=>null));
				$this->jump('发送失败',-1);
			}
		}
	//	$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
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