<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_DaoJian extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}

		$getData = $this->_gameObject->getGetData($get);
		$postData=array(
				'pageSize'=>PAGE_SIZE,
				'pageNo'=>max(1,intval($_GET['page']))
				);
		if($post){
			$postData = array_merge($post,$postData);
		}
	
		$data = $this->getResult($UrlAppend,$getData,$postData);	//如果游戏有自定义接口,可以使用
		//var_dump($data);exit;
		if($data['result'] == 0 && $data['data']){
			$noticeType = array(0=>'跑马灯', 1=>'游戏信息公告', 2=>'喇叭', 3=> '跑马灯+聊天(紧急公告)',4=>' 聊天公告');
			$status = array(0=>'未审核',1=>'已审核');

			foreach ($data['data'] as &$sub){
				$sub['noticeType'] = $noticeType[$sub['noticeType']];
				$sub['status'] = $status[$sub['status']];
				$sub['URL_del'] 	= $this->_urlNoticeDel($sub['id']);
				$sub['URL_edit'] 	= $this->_urlNoticeEdit($sub['id']);
			}
			$this->_assign['dataList']=$data['data'];
		}
		$total = $data['totals'];
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