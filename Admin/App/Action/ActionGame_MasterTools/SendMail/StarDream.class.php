<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendMail_StarDream extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$PostData["content"]		=	base64_encode( htmlspecialchars( trim($_POST['MsgContent']) ) );
			$PostData["title"]			=	base64_encode( htmlspecialchars( trim($_POST['MsgTitle']) ) );
			$PostData["userType"]		=	intval($_POST['userType']);
			$PostData["user"]			=	trim($_POST['Users']);
			
			$data = $this->_gameObject->result('SendMail',$getData,$PostData);
			
			if($data["status"]=="1"){
				$jumpUrl = Tools::url(CONTROL,'SendMail',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
	}


}