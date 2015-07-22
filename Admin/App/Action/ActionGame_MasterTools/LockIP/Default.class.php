<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockIp_Default extends Action_ActionBase{

	public function _init(){
		$this->_assign['URL_lockIPDone'] = $this->_lockIPDone();
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1'){
			if(is_array($data['data'])){
				$this->_assign['param'] = implode("\n",$data['data']);
			}else{
				$this->_assign['param'] = $data['data'];
			}
		}
		return $this->_assign;
	}
	
	private function _lockIPDone(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockIPDone',$query);
	}
	
}