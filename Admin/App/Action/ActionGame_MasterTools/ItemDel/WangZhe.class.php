<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemDel_WangZhe extends Action_ActionBase{

	public function _init(){
	}
	private $cause = "";
	public function getPostData($post=null){
		$playerType = array(
				1=>'玩家id',
				2=>'玩家账号',
				3=>'玩家昵称',
				);
		$postData = array(
			'playerType' => intval($_POST['userType']),
			'playerId' => trim($_POST['user']),
			'bagInfo' => '',
			'unionInfo'=>'',
		);
		$this->cause ='<div style="padding:3px; margin:3px; border:1px dashed #000">
		 <div>申请原因：<br>'.$_POST['cause'].'</div>
		 </div>
		 <div style="padding:3px; margin:3px; border:1px dashed #000">';
// 		print_r($_POST);exit;
		$this->cause.= '账号类型：'.$playerType[intval($_POST['userType'])].'</br>';
		if(is_array($_POST['itemNum'])){
			foreach($_POST['itemNum'] as $k =>$v){
					$this->cause.="背包物品：".$_POST['goodsName'][$k]." 物品ID：".$k.";数量：".$v."</br>";
					$postData['bagInfo'] .= $k.','.$v.'|';
			}
		}
		if(is_array($_POST['unionNum'])){
			foreach($_POST['unionNum'] as $k =>$v){
				$this->cause.="仓库物品：".$_POST['unionName'][$k]." 物品ID：".$k.";数量：".$v."</br>";
				$postData['unionInfo'] .= $k.','.$v.'|';
			}
		}
		$this->cause.="</div>";
		return $postData;
	}
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);
		$getData = $this->_gameObject->getGetData($get);
		$getData = array_merge($getData,$postData);
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
		 'post_data'=>null,
		 'get_data'=>$getData,
// 		 'end'=>array(
// 				 'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
// 				 'cal_local_method'=>'applyEnd',	//使用本地方法
// 				 'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
// 			    ),
		),
		 'receiver_object'=>array($serverId=>''),
		 'player_type'=>intval($_POST['userType']),
		 'player_info'=>$postData['playerId'],
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