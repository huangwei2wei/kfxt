<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_MailList_zhanlong extends Action_ActionBase{
	
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page'])-1);
		if($_GET["Receiver"]){
			$getData["PlayerID"]	=	intval($_GET['Receiver']);
		}
		if($_GET["SendTime"]){
			$getData["DateTime"]	=	trim($_GET['SendTime']);
		}
		if($_GET["mailtype"]==1){
			$data = $this->getResult("QueryPlayer/MailDeletedList",$getData);
		}else{
			$data = $this->getResult($UrlAppend,$getData);
		}
		if($data['Result'] == '0'){
			$this->_assign['dataList']=$data["MailList"];
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}

		$this->_assign['Add_Url']=$this->_urlAdd();
		$this->_assign['Del_Url']=$this->_urlDel();
		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SendMail',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'MailDel',$query);
	}

}