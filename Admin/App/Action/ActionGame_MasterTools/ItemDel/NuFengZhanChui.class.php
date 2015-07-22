<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemDel_NuFengZhanChui extends Action_ActionBase{
	private $_playerType;
	public function _init(){
		$this->_playerType = Tools::gameConfig('userType',$this->_gameObject->_gameId);
	}
	private $cause = "";

	public function getPostData($post=null){
		$postData = array(
			'userType' => intval($_POST['userType']),
			'user' => trim($_POST['user']),
			'goods' => '',
		);
		$this->cause ='<div style="padding:3px; margin:3px; border:1px dashed #000">
		 <div>申请原因：<br>'.$_POST['cause'].'</div>
		 </div>
		 <div style="padding:3px; margin:3px; border:1px dashed #000">';
		if(is_array($_POST['itemNum'])){
			$this->cause.= '账号类型：'.$this->_playerType[$_POST['userType']].'</br>';
			$this->cause.= '玩家账号：'.trim($_POST['user']).'</br>';
			
			foreach($_POST['itemNum'] as $ikey =>$num){
				if($num != '' ){
					$quantity = trim(intval($num));
					$name = trim($_POST['goodsName'][$ikey]);
					$itemId = trim($ikey);
					//$itemName = trim($_POST['itemName'][$where]);
					$this->cause.="物品名称：".$name." 物品ID：".end(explode('_', $itemId))." 格子ID:".reset(explode('_', $itemId))." 数量：".$quantity."</br>";
					$postData['goods'] .= $ikey.'_'.$quantity.'&';
				}
			}
			$postData['goods'] = substr($postData['goods'], 0,strlen($postData['goods'])-1);
		} 

		$this->cause.="</div>";
	
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		
		$postData = $this->getPostData($post);
		
		$postData = $this->_gameObject->getPostData($postData);

		$serverId = intval($_REQUEST['server_id']);
		$applyData = array(
		 'type'=>72,
		 'server_id'=>$serverId,
		 'operator_id'=>$this->_serverList[$serverId]['operator_id'],
		 'game_type'=>$this->_serverList[$serverId]['game_type_id'],
		 'list_type'=>1,
		 'apply_info'=>$this->cause,
		 'send_type'=>2,	//2	http
		 'send_data'=>array(
		 		'call'=>array(
		 				'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,
		 				'cal_local_method'=>'applySend',
		 				'params'=>array("UrlAppend"=>$UrlAppend,'data'=>array_merge($postData,$get),"server_id"=>$serverId,),
		 		)
// 		 'end'=>array(
// 				 'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
// 				 'cal_local_method'=>'applyEnd',	//使用本地方法
// 				 'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
// 			    ),
		),
		 'receiver_object'=>array($serverId=>''),
		 'player_type'=>$_POST['userType']+1,
		 'player_info'=>$_POST['user'],
		);
		$modelApply = $this->_getGlobalData('Model_Apply','object');
		if($modelApply->AddApply($applyData)){
// 			print_r($applyData);exit;
			$URL_CsIndex = Tools::url('Apply','CsIndex');
			$URL_CsAll = Tools::url('Apply','CsAll');
			$showMsg = '申请成功,等待审核...<br>';
			$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
			$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
			$this->jump($showMsg,1,100,false);
		}
		$this->jump('申请失败',-1);
		return $this->_assign;
	}





}