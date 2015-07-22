<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ActivitiesConfiguration_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
		$this->_assign['type']	=	$type;
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
		$getData = $this->_gameObject->getGetData($get);
		$getData["Page"]		=	max(0,intval($_GET['page'])-1);
		$getData["WorldID"]		=	max(0,intval($_POST["WorldID"]));
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$this->_assign['dataList']=$data["ClassTable"];
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
		return Tools::url(CONTROL,'ActivitiesConfigurationAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ActivitiesConfigurationDel',$query);
	}

}