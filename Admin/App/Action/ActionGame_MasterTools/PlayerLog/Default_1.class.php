<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_Default_1 extends Action_ActionBase{
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		$type = $this->getResult("player_action_log.php?act=getLogType",$getData);
		$this->_assign["playerLogTypes"] = json_encode($type["data"]);
		$getData["page"]			=	max(0,intval($_GET['page']));
		$getData["User"]			=	urlencode($_GET['user']);//max(0,intval($_GET['user']));
		$getData["userType"]		=	max(0,intval($_GET['userType']));
		$getData['objectId']          =   max(0,intval($_GET['rootid']));//日志大类
		$getData['eventId']          =   max(0,intval($_GET['typeid']));//日志分类
		
		$data = $this->getResult($UrlAppend,$getData);
		if($data["status"]==1){
			foreach($data["data"]["list"] as &$v){
				$v["addTime"] = date("Y-m-d H:m:s",$v["addTime"]);
			}
			$this->_assign["dataList"] = $data["data"]["list"];
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["data"]["total"],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}

		return $this->_assign;
	}

	private function _urlAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}

	private function _urlDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'SilenceDel',$query);
	}

	private function _urlInGame(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'doaction'=>self::INGAME,
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

	private function _urlLocalLog(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'Silence',$query);
	}

}