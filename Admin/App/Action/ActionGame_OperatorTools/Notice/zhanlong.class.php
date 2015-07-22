<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_Notice_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($_GET["WorldID"]==""&&$_POST["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
			$_POST["WorldID"] = $this->_getServerID();
		}
		$_GET["WorldID"] = $_POST["WorldID"];
		$this->_assign["_GET"] = $_GET;
		if($_GET["WorldID"]){
			$getData = $this->_gameObject->getGetData($get);
			$getData["WorldID"]		=	$_POST["WorldID"];
			$this->_assign['GET']	=	$_POST;
			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result'] == '0'){
				foreach ($data['Broadcast'] as $key=>&$sub){
					$sub["URL_del"]	=	$this->_urlNoticeDel($sub["OrderID"],$sub["WorldID"]);
					$sub["URL_edit"]	=	$this->_urlNoticeEdit();
				}
				$this->_assign['dataList']=$data['Broadcast'];
				$this->_loadCore('Help_Page');
				$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}
		return $this->_assign;
	}

	private function _urlNoticeDel($id,$WorldID){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'OrderID'=>$id,
			'WorldID'=>$WorldID,
		);
		return Tools::url(CONTROL,'NoticeDel',$query);
	}

	private function _urlNoticeEdit(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
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