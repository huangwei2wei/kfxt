<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SendUserPackage_chunqiu extends Action_ActionBase{
	public function _init(){}

	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$user = array(
		1=>"玩家id",
		2=>"玩家账号",
		3=>"玩家昵称",
		);
		$this->_assign["items"]	=	$this->_f($this->getFileCacheName());
		if($this->_isPost()){

			$sendData["userType"]	=	intval($_POST["userType"]);
			if($sendData["userType"]==3){
				$sendData["users"]		=	base64_encode($_POST["user_id"]);
			}else{
				$sendData["users"]		=	$_POST["user_id"];
			}
			$sendData["is_common"]		=	intval($_POST["is_common"]);
			
			$cause = "原因：".$_POST["cause"]."<br/>".$user[$sendData["userType"]]."：".$_POST["user_id"]."<br/>";
			$i = 1;
			$cause .= "是否通服：".$sendData["is_common"];
			$sendData["item_list"]=array();
			foreach($_POST["itemNum"] as $k=>$item){
				$arr["item_id"] = 	$k;
				$arr["number"]	=	$item;
				$arr["bind"]	=	$_POST["itembind"][$k];
				$sendData["item_list"][]	=	$arr;
				$cause	.="[".$_POST["itemName"][$k]."]数量：".$item."<br/>";
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
				'url_append'=>$UrlAppend,
				'get_data'=>$sendData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
					'cal_local_method'=>'ApplySend',
					'params'	=>		array('data'=>$sendData,"server_id"=>$_REQUEST['server_id'],"UrlAppend"=>$UrlAppend),
			),
			),
			'receiver_object'=>array($_REQUEST['server_id']=>''),
			'player_type'=>0,
			'player_info'=>$sendData['users'],
			);
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
		if($_GET["updatecache"]){
			$this->cacheItem();
		}

		$this->_assign["updatecache"] = Tools::url(CONTROL,'SendUserPackage',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'updatecache'=>1));
		$this->_assign["submitUrl"] = Tools::url(CONTROL,'SendUserPackage',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
		return $this->_assign;
	}

	public function cacheItem(){
		$data = $this->_gameObject->result($this->_getUrl(),"","60007");
		//		echo $data;
		$data = json_decode($data,true);
		$this->_f($this->getFileCacheName(),$data["props_list"]);
	}


}