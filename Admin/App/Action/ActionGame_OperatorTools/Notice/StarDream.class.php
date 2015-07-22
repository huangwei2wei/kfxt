<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_StarDream extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		$getData = $this->_gameObject->getGetData($get);
		$postData=array();
		
		if(isset($_POST['page'])){
			$postData['page'] = intval($_POST['page']);
			$postData['pageSize'] = 1;
		} else {
			$postData['page'] = 1;
			$postData['pageSize'] = 1;
		}
		$postData['id'] = 0;//默认传全部
		
		$data = $this->_gameObject->result('Notice',$getData,$postData);
		if($data['status']=='1'){
			
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data']['total'],'perpage'=>PAGE_SIZE));
			foreach ($data['data']['list'] as &$one){
				$one['content'] = $one['content'];
				$one['URL_edit'] = $this->_urlNoticeEdit($one['id']);
				$one['URL_del'] = $this->_urlNoticeDel($one['id']);
			}
			$this->_assign['dataList'] = $data['data']['list'];
			$this->_assign['pageBox'] = $helpPage->show();
	
		} else {
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		
		$this->assing['URL_noticeAdd'] = $this->_urlNoticeAdd();
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