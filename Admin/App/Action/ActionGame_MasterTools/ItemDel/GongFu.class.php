<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemDel_GongFu extends Action_ActionBase{

	public function _init(){

	}

	private $cause = "";

	public function getPostData($post=null){
		$postData = array(
			'type' => intval($_POST['playerType']),
			'value' => trim($_POST['player']),
			'goods' => array(),
		);
		$this->cause ='
		 <div style="padding:3px; margin:3px; border:1px dashed #000">
		 <div>申请原因：<br>'.$_POST['cause'].'</div>
		 </div>
		 <div style="padding:3px; margin:3px; border:1px dashed #000">';
		if(is_array($_POST['itemNum'])){
			foreach($_POST['itemNum'] as $ikey =>$num){
				$type = trim(intval($_POST['itemType'][$ikey]));
				$num = trim(abs($num)*-1);
				$position = trim($_POST['itemPosition'][$ikey]);
				$itemId = trim($_POST['itemId'][$ikey]);
				//$itemName = trim($_POST['itemName'][$where]);
				$this->cause.="扣减物品ID：".$itemId.";数量：".$num."</br>";
				$postData['goods'][] = array($type,$position,$num,$itemId);
			}
		}
		foreach($postData as $key => $val){
			if(empty($val)){
				$this->jump($key.' empty',-1);
			}
		}
		$postData['goods'] = json_encode($postData['goods']);
		$this->cause.="</div>";
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);

		$serverId = intval($_REQUEST['server_id']);
		$applyData = array(
		 'type'=>$this->_gameObject->getApplyId('ItemCardApply'),	//从Game拿id
		 'server_id'=>$serverId,
		 'operator_id'=>$this->_serverList[$serverId]['operator_id'],
		 'game_type'=>$this->_serverList[$serverId]['game_type_id'],
		 'list_type'=>1,
		 'apply_info'=>$this->cause,
		 'send_type'=>2,	//2	http
		 'send_data'=>array(
		 'url_append'=>$UrlAppend,
		 'post_data'=>$postData,
		 'get_data'=>$getData,
		 'end'=>array(
		 'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
		 'cal_local_method'=>'applyEnd',	//使用本地方法
		 'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
		),
		),
		 'receiver_object'=>array($serverId=>''),
		 'player_type'=>empty($bindPlayerIdInfo)?0:1,
		 'player_info'=>$postData['playerId'],
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

		return $this->_assign;
	}





}