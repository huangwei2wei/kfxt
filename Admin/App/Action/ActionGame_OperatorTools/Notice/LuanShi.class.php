<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_LuanShi extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$data = $this->getResult($UrlAppend,$getData,$postData);
		if($data['status'] == '1' && $data['data']['list']){
			foreach ($data['data']['list'] as &$sub){
				$sub['title']	=	strip_tags($sub['content']);
				$sub['URL_del'] 	= $this->_urlNoticeDel($sub['id']);
				$sub['URL_delByTitle'] 	= $this->_urlNoticeDelByTitle($sub['content']);
				$sub['URL_edit'] 	= $this->_urlNoticeEdit($sub['id']);
			}
			$this->_assign['dataList']=$data['data']['list'];
			
			$total = $data['data']['total'];
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}

		return $this->_assign;
	}
	
	private function _urlNoticeDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
			'do'=>'delByid',
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}
	
	private function _urlNoticeDelByTitle($title){
		$query = array(
				'zp'=>PACKAGE,
				'__game_id'=>$this->_gameObject->_gameId,
				'server_id'=>$_REQUEST['server_id'],
				'title'=>$title,
				'do'=>'delByTitle',
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