<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesList_SanFen extends Action_ActionBase{
	public function _init(){
// 		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
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
		$data = $this->getResult($UrlAppend,$getData,$postData);
// 		print_r($data);
		if($data['status'] == '1'){
			$activation = array(1=>'是',0=>'否');
			foreach ($data['data'] as &$sub){
				$sub['beginTime'] = date("Y-m-d H:i:s",floor($sub['beginTime']/1000));
				$sub['endTime'] = date("Y-m-d H:i:s",floor($sub['endTime']/1000));
				$sub['URL_edit'] 	= $this->_urlEdit($sub['id']);
				$sub['activation'] = $activation[$sub['activation']];
// 				$sub['URL_del'] 	= $this->_urlDel($sub['id']);
			}
			$this->_assign['dataList']=$data['data'];
		}
// 		$total = $data['data'][1]['total'];
// 		$this->_loadCore('Help_Page');//载入分页工具
// 		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
// 		$this->_assign['pageBox'] = $helpPage->show();
		return $this->_assign;
	}
	
	private function _urlDel($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'id'=>$id,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}
	
	private function _urlEdit($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'getOneById'=>$id,
		);
		return Tools::url(CONTROL,'ActivitiesEdit',$query);
	}
	
	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'NoticeAdd',$query);
	}
}