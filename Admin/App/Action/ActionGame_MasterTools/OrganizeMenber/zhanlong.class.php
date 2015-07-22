<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_OrganizeMenber_zhanlong extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 2;
	public function _init(){
		$this->_assign['URL_silenceAdd'] = $this->_urlSilenceAdd();
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["FactionID"]){
			$get['Page']=max(0,intval($_GET['page']-1));
			$get["FactionID"]	=	$_GET["FactionID"];
			$getData =$this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData);
			if($data['Result']===0){
				if($data['FactionMembers']){
					$this->_loadCore('Help_Page');//载入分页工具
					$helpPage=new Help_Page(array('total'=>$data['Count'],'perpage'=>PAGE_SIZE));
					$this->_assign['dataList'] = $data['FactionMembers'];
					$this->_assign['pageBox'] = $helpPage->show();
				}
			}
		}
		return $this->_assign;
	}

	private function _urlMember($id){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'FactionID'=>$id
		);
		return Tools::url(CONTROL,'OrganizeMenber',$query);
	}

	private function _urlSilenceAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}
	private function _urlSilenceDel($account=null){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'account'=>$account
		);
		return Tools::url(CONTROL,'SilenceDel',$query);
	}
}