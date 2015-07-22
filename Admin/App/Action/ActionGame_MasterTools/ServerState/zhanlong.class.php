<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ServerState_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_lockIPDone'] = $this->_lockIPDone();
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$getData =$this->_gameObject->getGetData($get);
		if($this->_isPost()){
			$SendData = $getData;
			if($_POST["updataType"]==1){
				$SendData["OnlineMax"]	= $_POST["OnlineMax"];
				$SendData["OnlineTimeout"]	= $_POST["OnlineTimeout"];
				$data = $this->getResult("UpdateSystem/OnlineConfig",$SendData);
			}
			if($_POST["updataType"]==2){
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
				$data = $this->getResult("UpdateSystem/GameConfig",$SendData);
			}
			if($_POST["updataType"]==3){
				$data = $this->getResult("UpdateSystem/LoadData",$SendData);
			}

			if($data["Result"]===0){
				$jumpUrl = $this->_urlstate();
				$this->jump('操作成功',1,$jumpUrl);
			}else{
				$errorInfo = '操作失败:';
				$this->jump($errorInfo.$data['info'],-1);
			}
		}elseif($this->_isAjax()){
			$SendData = $getData;
			$SendData['WorldID'] = intval($_GET['WorldID']);
			 
			switch($_GET['doaction']){
				case "queryAccount":
					if($SendData['WorldID']===0){
						$data = $this->getResult("QueryAccount/AccountCount",$SendData,null,false,true);
					}else{
						$data = $this->getResult("QueryPlayer/AccountCount",$SendData,null,false,true);
					}
					$ret = $data['AccountCount'];
					break;
				case "queryRole":
					$data = $this->getResult("QueryPlayer/PlayerCount",$SendData,null,false,true);
					$ret = $data['PlayerCount'];
					break;
				case "queryDelRole":
					$data = $this->getResult("QueryPlayer/PlayerDeleteCount",$SendData,null,false,true);
					$ret = $data['PlayerCount'];
					break;
				default:
					return $this->_assign;
			}
			$this->_returnAjaxJson(array('status'=>1,'data'=>$ret));
		}
		$data = $this->getResult($UrlAppend,$getData);
		$OnlineConfig = $this->getResult("QuerySystem/OnlineConfig",$getData);
		$GameConfig = $this->getResult("QuerySystem/GameConfig",$getData);
//		print_r($GameConfig);
		//$AllCount = $this->getResult("QuerySystem/AllCount",$getData);
		if($GameConfig["Result"]===0){
			//			foreach($GameConfig as &$item){
			//				if($item==0){
			//					$item="close";
			//				}else{
			//					$item="open";
			//				}
			//			}
			$this->_assign['GameConfig'] = $GameConfig;
		}else{
			$this->_assign['GameConfig'] = $GameConfig;
		}

		//		if($AllCount["Result"]===0){
		//			$this->_assign['AllCount'] = $AllCount;
		//		}else{
		//			$this->_assign['AllCount'] = $AllCount;
		//		}
		if($OnlineConfig["Result"]===0){
			$this->_assign['OnlineConfig'] = $OnlineConfig;
		}else{
			$this->_assign['OnlineConfig'] = $OnlineConfig;
		}
		if($data["Result"]===0){
			$this->_assign['state'] = $data["WorldList"];
		}else{
			$this->_assign['state'] = $data["WorldList"];
		}
		$this->_assign['ajaxUrl'] = $this->_urlstate();
		$this->_assign['ajaxMutilUrl'] = $this->_urlstate("Mutil");

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