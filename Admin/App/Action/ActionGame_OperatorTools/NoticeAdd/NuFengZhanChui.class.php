<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeAdd_NuFengZhanChui  extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isAjax()){
			$data = array(
				'message'=>trim($_POST['message']),
				'intervalTime'=>intval($_POST['intervalTime']),
				'startTime'=>strtotime(trim($_POST['startTime'])),
				'endTime'=>strtotime(trim($_POST['endTime'])),
			);
			$postData = $this->_gameObject->getPostData($post);
			$sendData = array_merge($data,$postData,$get);
			$data = $this->_gameObject->getResult($UrlAppend,$sendData);
// 			var_dump($data);
			
			if($data['status'] == 1){
				$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！','data'=>null));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>'发送失败！','data'=>null));
			}
		}
		$this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
		return $this->_assign;
	}
	
	private function _urlNotice(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id']
		);
		return Tools::url(CONTROL,'Notice',$query);
	}
	
}