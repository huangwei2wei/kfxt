<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_UpdatePersonalInformation_SanGuo extends Action_ActionBase{
	protected $_param;
	protected $_cause;
	
	public function _init(){}
	
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$this->_assign['server_id']	=	$_REQUEST['server_id'];
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$_POST);
			if($data['status'] == 1){
				$jumpurl	=	$this->_urlEdituser($_POST['user_id']);
				$this->_assign['data']	=	$data['data'];
				$this->jump('修改成功',1);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['msg'],-1);
			}
		}
		if($_GET["id"]){
			$senddata['user_id']	=	$_GET["id"];
			$getData = $this->_gameObject->getGetData($get);
			$getData['a']	=	'getUserData';
			$Playerdata	=	$this->getResult($UrlAppend,$getData,$senddata);
			$this->_assign['Playerdata']	=	$Playerdata['data'];
		}
		$this->_assign['get']	=	$_GET;
		$this->_assign['editurl']	=	$this->	_urlEdituser();
		return $this->_assign;
	}
	
	private function _urlEdituser($id=NULL){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'		=>$id,
		);
		return Tools::url(CONTROL,'UpdatePersonalInformation',$query);
	}
}