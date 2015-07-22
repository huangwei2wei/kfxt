<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_NoticeAdd_chunqiu extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isPost()){
			
			$postData = array(
				'begin_time'	=>intval(strtotime($_POST['begin_time'])),
				'end_time'		=>intval(strtotime($_POST['end_time'])),
				'interval_time'	=>intval($_POST['interval_time']),
				'content'=>base64_encode(trim($_POST['content'])),
				"cmd"=>$UrlAppend,
				"url"=>$this->_getUrl(),
			);
			$data = $this->_gameObject->addNotice($postData);
			if($data){
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
		return Tools::url(CONTROL,'Notice',$query);
	}

	//"$data" = Array [3]
	//	data = (boolean) true
	//	status = (int) 1
	//	info = null


}