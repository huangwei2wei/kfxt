<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountDel_chunqiu extends Action_ActionBase{

	private $_utilMsg;

	public function _init(){
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		//		$getData = $this->_gameObject->getGetData($get);
		$getData["user"]	=	$_GET['user'];
		$getData["userType"]	=	intval(1);
		$data = $this->_gameObject->result($this->_getUrl(),$getData,$UrlAppend);
		$data = json_decode($data,true);
		if($data["status"]==1){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['error'],-1);
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
}