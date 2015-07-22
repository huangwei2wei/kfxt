<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_Organize_zhanlong extends Action_ActionBase{
	const INGAME = 'inGame';
	const LOGTYPE = 2;
	public function _init(){
		$this->_assign['URL_silenceAdd'] = $this->_urlSilenceAdd();
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		$get['Page']=max(0,intval($_GET['page']-1));
		$getData =$this->_gameObject->getGetData($get);
		$getData["WorldID"] = max(0,intval($_GET["WorldID"]));
		if($_GET["otype"]==1){
			$UrlAppend = "QueryGame/FactionRegist";
		}
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result']===0){
			if($data['FactionList']){
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$data['Count'],'perpage'=>PAGE_SIZE));
				foreach($data['FactionList'] as &$item){
					$item["memberUrl"]	=	$this->_urlMember($item["FactionID"]);
				}
				$this->_assign['dataList'] = $data['FactionList'];
				$this->_assign['pageBox'] = $helpPage->show();
			}
		}

		$this->_assign['OrganizeUrl'] = Tools::url(CONTROL,'Organize',$query = array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'otype'=>0));
		$this->_assign['OrganizeUrl2'] = Tools::url(CONTROL,'Organize',$query = array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'otype'=>1));
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