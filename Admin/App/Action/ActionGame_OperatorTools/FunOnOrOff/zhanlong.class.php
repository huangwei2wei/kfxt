<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_FunOnOrOff_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_lockIPDone'] = $this->_lockIPDone();
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		if($this->_isAjax()){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
			$SendData = $this->_gameObject->getGetData($get);
			$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
			if($_POST["updataType"]==1){
				$r = $utilHttpInterface->result($serverList[$_REQUEST["server_id"]]['server_url'],"UpdateSystem/LoadData",$SendData);
				$r = json_decode($r,true);
			}else{
				$SendData["ClientGM"]	= max(0,intval($_POST['ClientGM']));
				$SendData["Adult"]	=  max(0,intval($_POST['Adult']));
				$SendData["Store"]	=  max(0,intval($_POST['Store']));
				$SendData["Team"]	=  max(0,intval($_POST['Team']));
				$SendData["Faction"]	=  max(0,intval($_POST['Faction']));
				$SendData["Trade"]	=  max(0,intval($_POST['Trade']));
				$SendData["Sale"]	=  max(0,intval($_POST['Sale']));
				$SendData["LifeTree"]	=  max(0,intval($_POST['LifeTree']));
				$SendData["Friend"]	=  max(0,intval($_POST['Friend']));
				$SendData["Mount"]	=  max(0,intval($_POST['Mount']));
				$SendData["Precious"]	=  max(0,intval($_POST['Precious']));
				$SendData["Improve"]	=  max(0,intval($_POST['Improve']));
				$SendData["Potential"]	=  max(0,intval($_POST['Potential']));
				$SendData["FactionShop"]=  max(0,intval($_POST['FactionShop']));
				$SendData["SpeededUPCheck"]	=  max(0,intval($_POST['SpeededUPCheck']));
				
				$SendData["General"]	=  max(0,intval($_POST['General']));
				$SendData["City"]	=  max(0,intval($_POST['City']));
				$SendData["Fighters"]	=  max(0,intval($_POST['Fighters']));
				$SendData["Bow"]=  max(0,intval($_POST['Bow']));
				$SendData["RidingWeapon"]	=  max(0,intval($_POST['RidingWeapon']));
				$r = $utilHttpInterface->result($serverList[$_REQUEST["server_id"]]['server_url'],"UpdateSystem/GameConfig",$SendData);
				$r = json_decode($r,true);
			}
			if($r["Result"]===0){
				$this->ajaxReturn(array('status'=>1,'msg'=>"succeed"));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>"failure:".$r["Result"]));
			}
			die();
		}

		return $this->_assign;
	}

	private function _urlstate($val=''){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		if($val=="Mutil"){
			$query['WorldID'] = 0;
		}else{
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameObject->_gameId);
			$query['WorldID'] = $serverList[$_REQUEST['server_id']]['ordinal'];
		}
		return Tools::url(CONTROL,'ServerState',$query);
	}
	protected function _returnAjaxJson($result){
		exit(json_encode($result));
	}
}