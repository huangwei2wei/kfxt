<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_chunqiu extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		$getData = $this->_gameObject->getGetData($get);
		$postData=array();
		if($post){
			$postData = array_merge($post,$postData);
		}
		$postData = array_filter($postData);
		$data = $this->_gameObject->getNotice($postData);	//如果游戏有自定义接口,可以使用
		if($data['status'] == '1' && $data['announcement_list']){
//			print_r($data['announcement_list']);
			foreach($data['announcement_list'] as &$item){
				$item["URL_edit"]	=	$this->_urlNoticeEdit($item["id"]);
				$item["URL_del"]	=	$this->_urlNoticeDel($item["id"]);
			}
			$this->_assign['dataList']=$data['announcement_list'];
		}
		return $this->_assign;
	}
	
	private function _urlNoticeDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}
	
	private function _urlNoticeEdit($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeEdit',$query);
	}
	
	private function _urlNoticeAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeAdd',$query);
	}
	
//"$data" = Array [3]	
//	data = Array [6]	
//		0 = Array [6]	
//			endTime = (int) 0	
//			url = (string:0) 	
//			beginTime = (int) 0	
//			id = (int) 1	
//			title = (string:10) 欢迎访问游戏!!!!	
//			initialDelay = (int) 60	
//		1 = Array [6]	
//		2 = Array [6]	
//		3 = Array [6]	
//		4 = Array [6]	
//		5 = Array [6]	
//	status = (int) 1	
//	info = null
	
}