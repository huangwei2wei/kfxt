<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeAdd_FHJZ extends Action_ActionBase{
	public function _init(){

	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		 
		if($this->_isAjax()){
			$data = array(
				'title'=>trim($_POST['title']),
				'content'=>trim($_POST['message']),
				'intervalTime'=>intval($_POST['intervalTime']),
				'startTime'=>strtotime(trim($_POST['startTime'])),
				'endTime'=>strtotime(trim($_POST['endTime'])),
				'url'=>trim($_POST['url']),
				'isShow'=>(boolean)$_POST['isShow'],
			);
			$postData = $this->_gameObject->getPostData($post);
			$postData = array_merge($data,$postData);
			$data = $this->getResult($UrlAppend,$get,$postData);
// 			print_r($data);
			
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