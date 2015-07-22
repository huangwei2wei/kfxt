<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_zhanlong extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_itemsDel'] = $this->_urlItemsDel();
		$this->_assign['itemTypes'] = Tools::gameConfig('itemTypes',$this->_gameObject->_gameId);
	}

	public function getPostData($post=null){
		//		$player = array(
		//		1=>trim($_GET['playerId']),
		//		2=>trim($_GET['playerAccount']),
		//		3=>trim($_GET['playerNickname']),
		//		);
		//		$player = array_filter($player);
		//		if(!$player){
		//			return false;
		//		}
		//		$playerType = key($player);
		//		$playerValue = current($player);
		//		$this->_assign['playerSelect'] = array($playerType,$playerValue);
		//		return array(
		//			'type'=>$playerType,
		//			'value'=>$playerValue,
		//		);
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($_GET["WorldID"]==""){
			$_GET["WorldID"] = $this->_getServerID();
		}
		$this->_assign["_GET"] = $_GET;
		if($_GET["user"]){

			//			if($_POST["apply"]==1){
			//				$this->_apply();
			//			}

			$getData = $this->_gameObject->getGetData($get);
			$getData["Page"]		=	max(0,intval($_GET['page']-1));
			if($_GET["userType"]==0){
				$getData["PlayerID"]		=	max(1,intval($_GET['user']));
			}else{
				$getData["PlayerName"]		=	urlencode(trim($_GET['user']));
			}
			$getData["WorldID"]	=	$_GET["WorldID"];
			$data = $this->getResult($UrlAppend,$getData);
			//0-类型，1-位置，2-数量,3-id，4-名字
			if($data['Result'] === 0){
				$this->_loadCore('Help_Page');
				$helpPage=new Help_Page(array('total'=>$data["Count"],'perpage'=>PAGE_SIZE));
				$this->_assign['pageBox'] = $helpPage->show();
				$this->_assign['dataList'] = $data['List'];
			}
		}
		$this->_assign["GET"]	=	$_REQUEST;
		return $this->_assign;
	}

	public function _apply(){

		$user = array(
		1=>"玩家id",
		2=>"玩家账号",
		3=>"玩家昵称",
		);
		$sendData["user"]		=	$_POST["user"];
		$sendData["userType"]	=	intval($_POST["userType"]);
		if($sendData["userType"]==3){
			$sendData["user"]		=	base64_encode($sendData["user"]);
		}
		$sendData["item_list"]=array();
		$i=1;
		$cause = "原因：".$_POST["cause"]."<br/>".$user[$sendData["userType"]]."：".$sendData["user"]."<br/>";
		foreach($_POST["itemNum"] as $k=>$item){
			$arr["item_id"] = 	$_POST["item_id"][$k];
			$arr["number"]	=	$item;
			$sendData["item_list"][]	=	$arr;
			$cause	.="[".$_POST["itemName"][$k]."]扣除数量：".$item."<br/>";
			$i++;
		}

		$applyData = array(
			'type'=>42,	//从Game拿id
			'server_id'=>$_REQUEST['server_id'],
			'operator_id'=>$this->_serverList[$_REQUEST['server_id']]['operator_id'],
			'game_type'=>$this->_serverList[$_REQUEST['server_id']]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>str_replace(array("\r\n","\n",),'',$cause),
			'send_type'=>1,	//2	http
			'send_data'=>array(
				'url_append'=>60013,
				'get_data'=>$sendData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
					'cal_local_method'=>'ApplySend',
					'params'	=>		array('data'=>$sendData,"server_id"=>$_REQUEST['server_id'],"UrlAppend"=>60013),
		),
		),
			'receiver_object'=>array($_REQUEST['server_id']=>''),
			'player_type'=>0,
			'player_info'=>$sendData['user'],
		);
		//		print_r($applyData);
		//		die();
		$modelApply = $this->_getGlobalData('Model_Apply','object');
		if($modelApply->AddApply($applyData)){
			$URL_CsIndex = Tools::url('Apply','CsIndex');
			$URL_CsAll = Tools::url('Apply','CsAll');
			$showMsg = '申请成功,等待审核...<br>';
			$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
			$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
			$this->jump($showMsg,1,1,false);
		}
		$this->jump('申请失败',-1);
	}

	private function _urlItemsDel(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
		);
		return Tools::url(CONTROL,'ItemDel',$query);
	}


}