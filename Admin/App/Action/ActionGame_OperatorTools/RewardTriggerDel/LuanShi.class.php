<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_RewardTriggerDel_LuanShi extends Action_ActionBase{
	
	private $_utilMsg;
	
	public function _init(){
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$doaction = $_GET['doaction'];
		switch ($doaction){
			case 'delByTitle':
				$this->_delByTitle($UrlAppend,$get,$post);
				break;
			case 'delById':
			default:
				$this->_delById($UrlAppend,$get,$post);
		}
		
	}
	private function _delById($UrlAppend=NULL,$get=NULL,$post=NULL){
		$_REQUEST['id'] = intval($_REQUEST['id']);
		if(!$_REQUEST['id']){
			$this->jump('id error',-1);
		}
		$postData = array(
			'id'=>$_REQUEST['id'],
			'doaction'=>'delById',
		);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == 1){
			$jumpUrl = $this->_urlNotice();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
	}
	private function _delByTitle($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_GET['title']){
			$this->jump('title error',-1);
		}
		$postData = array(
			'title'=>$_GET['title'],
			'doaction'=>'delByTitle',
		);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
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
		return Tools::url(CONTROL,'RewardTriggerList',$query);
	}
}