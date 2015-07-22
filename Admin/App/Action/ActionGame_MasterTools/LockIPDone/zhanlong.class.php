<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockIPDone_zhanlong extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$getData["IP"]			= 	$_POST['IP'];
			$getData["HowLong"]		=	intval($_POST['HowLong']);
			$getData["Remove"]		=	intval(0);
			$data = $this->getResult($UrlAppend,$getData);
			if($data["Result"]===0){
				$jumpUrl = $this->_urlNotice();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
	}

	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockIP',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}