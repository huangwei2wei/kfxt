<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_LockAccountAdd_SanGuo extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			$getData = $this->_gameObject->getGetData($get);
			$postData=array(
				'user_id'	=>	$_POST['user_id'],
				'status'	=>	"0",
			);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
							array('操作','<font style="color:#F00">封号</font>'),
							array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
							array('操作人','<b>{UserName}</b>'),
							array('封号结束时间',"无限期"),
							array('原因',$_POST['cause']),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore(array("UserId"=>$_POST['user_id']),1,$_REQUEST['server_id'],$AddLog);
				if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
				}
				$jumpUrl = $this->_urlLockuser();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
		$this->_assign['get']	=	$_GET;
		return $this->_assign;
	}
	
	private function _urlLockuser(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'LockAccount',$query);
	}
	
}