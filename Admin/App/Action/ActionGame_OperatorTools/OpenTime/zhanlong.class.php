<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_OpenTime_zhanlong extends Action_ActionBase{

	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新

	public function _init(){}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]	=	intval($this->_getServerID());
			$getData["BeginTime"]	=	intval(strtotime($_POST["BeginTime"]));
			$data = $this->getResult("UpdateSystem/WorldBeginTime",$getData);
			if($data['Result']===0){
				$this->jump("操作成功",1);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['Result'],-1);
			}
		}else{
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]	=	intval($this->_getServerID());
			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result']===0){
				$this->_assign["opentime"]=$data['BeginTime'];
			}else{
				$errorInfo = '操作失败:';
				$this->_assign["opentime"]=$errorInfo."返回码".$data['Result'];
			}
		}
		return $this->_assign;
	}
}