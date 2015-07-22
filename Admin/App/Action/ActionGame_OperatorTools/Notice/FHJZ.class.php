<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_FHJZ extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
 
		$postData=array(
				'pageSize'=>PAGE_SIZE,
				'page'=>max(1,intval($_GET['page']))
				);
		if($post){
			$postData = array_merge($post,$postData);
		}
		 
		$postData = $this->_gameObject->getPostData($postData);
		
		$data = $this->getResult($UrlAppend,$get,$postData);
//  print_r($postData);
		if($data['status'] == '1' && $data['data']['list']){
			foreach ($data['data']['list'] as &$sub){
				$sub['URL_del'] 	= $this->_urlNoticeDel($sub['id']);
				$sub['URL_edit'] 	= $this->_urlNoticeEdit($sub['id']);
			}
			$this->_assign['dataList'] = $data['data']['list'];
		}
		$total = $data['data']['total'];
		$this->_loadCore('Help_Page');//载入分页工具
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		$this->_assign['pageBox'] = $helpPage->show();
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
}