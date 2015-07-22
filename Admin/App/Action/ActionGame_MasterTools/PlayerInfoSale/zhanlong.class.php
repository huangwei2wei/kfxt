<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerInfoSale_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_noticeAdd'] = $this->_urlNoticeAdd();
	}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData = $this->_gameObject->getGetData($get);
		//		$getData["Page"]		=	max(0,intval($_GET['page']-1));
		if($_GET['AccountID']){
			$getData["PlayerID"]		=	max(1,intval($_GET['AccountID']));
		}
		if($_GET['AccountName']){
			$getData["PlayerName"]		=	$_GET['AccountName'];
		}
		$getData["WorldID"] = $this->_getServerID();
		$data = $this->getResult($UrlAppend,$getData);
		if($data['Result'] == '0'){
			$LogType = $this->SaleType();
			$MoneyGame = array(0=>"铜钱",1=>"元宝",2=>"铜钱",);
			$MoneyCash = array(0=>"元宝",1=>"铜钱",2=>"元宝",);
			foreach($data["LogList"] as &$item){
				$item["MoneyGame"]=$MoneyGame[$item["LogType"]].$item["MoneyGame"];
				$item["MoneyCash"]=$MoneyCash[$item["LogType"]].$item["MoneyCash"];
				$item["LogType"] = $LogType[$item["LogType"]];
			}
			
			$this->_assign['data']=$data;
		}
		$this->_assign['GET']=$_GET;
		return $this->_assign;
	}

	public function SaleType(){
		return array(
		0=>'出售、买入金币',
		1=>'出售、买入元宝或银贯',
		2=>'出售、买入道具',
		);
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