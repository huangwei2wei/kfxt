<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeAdd_WangZhe extends Action_ActionBase{
	public function _init(){}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		if($this->_isPost() && !empty($_POST["subbutton"])){
			$postData = array(
				'content'=> base64_encode(trim($_POST['title'])),
				'interval'=>intval($_POST['IntervalTime']),
				'startTime'=>urlencode(trim($_POST['begin'])),
				'endTime'=>urlencode(trim($_POST['end'])),
			);
			if($post){
				$postData = array_merge($post,$postData);
			}
			$getData = $getData = $this->_gameObject->getGetData($get);
			$getData = array_merge($getData,$postData);
			$data = $this->getResult($UrlAppend,$getData,null);
			 
			if($data['status'] == 1){
				$jumpUrl = $this->_urlNotice();
				$this->jump('发送成功',0,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}
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