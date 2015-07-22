<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeEdit_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$postData	=	array('id'=>$_GET['id']);
		$getData = $getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1' && $data['data']){
			$this->_assign['data']	=	$data['data']['0'];
			$this->_assign['data']['post_time']	=	date("Y-m-d H:i:s",$this->_assign['data']['post_time']);
			$this->_assign['data']['exp']	=	date("Y-m-d H:i:s",$this->_assign['data']['exp']);
		}else{
			$this->jump($errorInfo,-1);
		}
		$this->_assign['get']					=	$_GET;
		$this->_assign['urlNoticeUpdate']		=	$this->_urlNoticeUpdate($_GET['id']);
		return $this->_assign;
	}
	
	private function _urlNoticeUpdate($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeEditDone',$query);
	}
	
}