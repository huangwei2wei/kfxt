<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardApply_chunqiu extends Action_ActionBase{
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
		$type = array(
		0=>"补偿礼包",
		1=>"媒体礼包",
		);
		$this->_assign["items"]	=	$this->_f($this->getFileCacheName());
		if($this->_isPost()){
			
			//发送通服礼包
			if($_POST['sendComItemCard']){
				$this->sendComItemCard();
				return;
			}
			
			$sendData["type"]				=	intval($_POST["type"]);
			$sendData["reuse"]				=	intval($_POST["reuse"]);
			$sendData["bind"]				=	intval($_POST["bind"]);
			$sendData["card"]				=	intval($_POST["card"]);
			$sendData["is_mail"]			=	intval($_POST["is_mail"]);
			$sendData["card_count"]			=	intval($_POST["card_count"]);
			$sendData["max_reward_count"]	=	intval($_POST["max_reward_count"]);
			$sendData["gift_name"]			=	base64_encode(trim($_POST["gift_name"]));
			$sendData['is_common']			=   intval($_POST['is_common']);
			
			$cause 	= "申请礼包原因原因：".$_POST["cause"]."<br/>";
			$cause .= "礼包类型：".$type[$sendData["type"]]."<br/>";
			$cause .= "礼包有效時間：".$_POST["end_time"]."<br/>";
			$cause .= "描述：".$_POST["desc"]."<br/>";
			if($sendData["reuse"]==1){
				$cause .= "是否可以重复使用：是<br/>";
			}else{
				$cause .= "是否可以重复使用：否<br/>";
			}
			if($sendData["is_common"]==1){
				$cause .= "是否通服：是<br/>";
			}else{
				$cause .= "是否通服：否<br/>";
			}
			if($_POST["bind"]==2){
				$cause .= "是否绑定账号：是[".$_POST["char_id"]."]<br/>";
				$sendData["char_id"]		=	$_POST["char_id"];
			}elseif($_POST["bind"]==1){
				$cause .= "绑定本服";
			}else{
				$cause .= "是否绑定账号：否<br/>";
			}
			
			if($_POST["is_mail"]==1){
				$cause .= "是否發送郵件：是<br/>";
			}else{
				$cause .= "是否發送郵件：否<br/>";
			}
			
			
			$sendData["end_time"]	=	strtotime($_POST["end_time"]);
			$i = 1;
			$sendData["item_list"]=array();
			$sendData["desc"]			=	base64_encode(trim($_POST["desc"]));
			foreach($_POST["itemNum"] as $k=>$item){
				$arr["item_id"] = 	$k;
				$arr["number"]	=	$item;
				$arr["bind"]	=	$_POST["itembind"][$k];
				$sendData["item_list"][]	=	$arr;
				$cause	.="[".$_POST["itemName"][$k]."]数量：".$item."<br/>";
				$i++;
			}
			
			$applyData = array(
			'type'=>67,//41,	//从Game拿id
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
			'player_info'=>$sendData['user'],
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

		$this->_assign["updatecache"] = Tools::url(CONTROL,'ItemCardApply',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'],'updatecache'=>1));
		$this->_assign["submitUrl"] = Tools::url(CONTROL,'ItemCardApply',array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id']));
		return $this->_assign;
	}

	public function cacheItem(){
		$data = $this->_gameObject->result($this->_getUrl(),"","60007");
		//		echo $data;
		$data = json_decode($data,true);
		$this->_f($this->getFileCacheName(),$data["props_list"]);
	}

	public function sendComItemCard(){
		
		$case = '发送通服礼包：<br>';
		$case .= '礼包名：'.$_POST['send_ItemCarName'].'<br>';
		$case .= '礼包类型：'.$_POST['send_ItemCarType'].'<br>';
		$case .= '接受的服列表：<br>';
		
		$serverList=$this->_getGlobalData('gameser_list');
		$serverids = array_unique($_POST['send_serverlist']);
		foreach ($serverids as $serverid){
			$case .= '　　'.$serverList[$serverid]['full_name'].'<br>';
		}
		
		$UrlAppend = '60050';
		$postData = array(
			'card_type'=>intval($_POST['send_ItemCarType']),
			'serverids'=>$serverids,
		);
		$applyData = array(
				'type'=>66,//44,//审核id
				'serverId'=>$_REQUEST["server_id"],
				'operator_id'=>$this->_serverList[$_REQUEST["server_id"]]['operator_id'],
				'game_type'=>$this->_serverList[$_REQUEST["server_id"]]['game_type_id'],
				'cause'=>$case,
				'UrlAppend'=>$UrlAppend,
				'postData'=>$postData,
				'getData'=>$this->_gameObject->getGetData($get),
				'userType'=>2,//1为id，2为账号3为昵称
				'user'=>'',//值，
		);
			
		$re = $this->_gameObject->applyAction($applyData);
		if($re[0]==1){
			$this->jump($re[1],1,1,false);
		} else {
			$this->jump($re[1],-1);
		}
		return $this->_assign;
	}
}