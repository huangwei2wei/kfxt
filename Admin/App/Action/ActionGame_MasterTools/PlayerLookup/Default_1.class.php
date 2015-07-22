<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLookup_Default_1 extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(1,intval($_GET['page']));
		if(!empty($_GET["regBeginTime"]) && !empty($_GET["regEndTime"])){
			$getData["regBeginTime"]	=	strtotime($_GET["regBeginTime"]);
			$getData["regEndTime"]	=	strtotime($_GET["regEndTime"]);
		}
		if(!empty($_GET["loginBeginTime"]) && !empty($_GET["loginEndTime"])){
			$getData["loginBeginTime"]	=	strtotime($_GET["loginBeginTime"]);
			$getData["loginEndTime"]	=	strtotime($_GET["loginEndTime"]);
		}
		if(!empty($_GET["User"])){
			$getData["User"]	=	urlencode($_GET["User"]);
			$getData["userType"]	=	$_GET["userType"];
		}
		$data = $this->getResult($UrlAppend,$getData);
//		print_r($data);
		if($data['states'] == '1'){
			$Column = $data["Column"];
			$this->_assign['Column']=$Column;
			$this->_assign['dataList']=$data['List'];
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}
		$this->_assign['GET']=$_GET;
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesDel',$query);
	}
}