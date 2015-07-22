<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_MailList_Xiayi extends Action_ActionBase{
	public function _init(){
		$this->_userType = array(1=>'玩家ID',2=>'玩家账号',3=>'玩家昵称');
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id'] || $_GET['user']==''){
			return array();
		}
		
		if($_GET['userType']==3){
			$postData['user'] = base64_encode(trim($_GET['user']));
		} else {
			$postData['user'] = $_GET['user'];
		}
		$postData['userType'] = $_GET['userType'];
		$postData['is_send'] = $_GET['is_send'];
		$postData['Page'] = max(1,intval($_GET['page']));
		
		$data = $this->_gameObject->result($this->_getUrl(),$postData,$UrlAppend);
		$data = json_decode($data,true);
		if($data['status'] == 1){
			$this->_assign['dataList'] = $data['email_list'];
		} else {
			$errorInfo = $data['error'];
			$this->jump($errorInfo,-1);
		}
		
		$this->_assign['GET']=$_GET;
		$this->_assign['SendMailUrl']=$this->_SendMailUrl();
		return $this->_assign;
	}

	private function _SendMailUrl(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SendMail',$query);
	}
}