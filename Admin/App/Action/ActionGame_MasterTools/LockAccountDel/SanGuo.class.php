<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountDel_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		
		$getData = $this->_gameObject->getGetData($get);
		$postData=array(
			'user_id'	=>	$_GET['id'],
				'status'	=>	"1",
		);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == 1){
			$jumpUrl = $this->_urlLockuser();
			$this->jump('操作成功',1,$jumpUrl);
		}else{
			$errorInfo = '操作失败:';
			$this->jump($errorInfo.$data['info'],-1);
		}
		
		
	}
	
	private function _urlLockuser(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
	
//"$data" = Array [3]	
//	data = (boolean) true	
//	status = (int) 1	
//	info = null	
	
	
}