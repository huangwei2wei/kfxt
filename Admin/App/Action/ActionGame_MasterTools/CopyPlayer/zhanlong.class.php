<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_CopyPlayer_zhanlong extends Action_ActionBase{

	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新

	public function _init(){}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			if(!in_array($this->_getServerID(),array(2,3))){
				$errorInfo = '操作失败:只能在体验服使用';
				$this->jump($errorInfo,-1);
			}
			$getData["WorldID"]	=	intval($_POST["WorldID"]);
			$getData["SourceID"]	=	intval($_POST["SourceID"]);
			$getData["TargetID"]	=	intval($_POST["TargetID"]);
			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result']===0){
				$this->jump("操作成功",1);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['Result'],-1);
			}
		}
		return $this->_assign;
	}
}