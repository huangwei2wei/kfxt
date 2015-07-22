<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeDel_WangZhe extends Action_ActionBase{
 
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$_REQUEST['id'] = intval($_REQUEST['id']);
		if(!$_REQUEST['id']){
			$this->jump('id error',-1);
		}
		$postData = array(
			'id'=>$_REQUEST['id'],
		);
		$getData = $this->_gameObject->getGetData($get);
		$getData = array_merge($getData,$postData);
		$data = $this->getResult($UrlAppend,$getData,null);
		
		if($data['status'] == 1){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
		
	}
	
	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Notice',$query);
	}
}