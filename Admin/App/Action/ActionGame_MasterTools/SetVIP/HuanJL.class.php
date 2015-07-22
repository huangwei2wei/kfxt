<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_SetVIP_HuanJL extends Action_ActionBase{
	private $userType= array(
			'1'=>'玩家ID',
			'2'=>'玩家账号',
			'3'=>'玩家昵称',
		);
	public function _init(){
		$this->_assign['userType'] = $this->userType;
		
		$this->_assign['Level'] = array(
				'0'=>0,
				'1'=>1,
				'2'=>2,
				'3'=>3,
				'4'=>4,
				'5'=>5,
				'6'=>6,
				'7'=>7,
				'8'=>8,
				'9'=>9,
		);
		
	}
	public function getPostData($post=null){
		$postData = array(
			'userType'=>intval($_POST['userType']),
			'user'=>trim($_POST['user']),
			'Level'=>intval($_POST['Level']),
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		return $postData;
	}
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			
			/* $postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($data['status'] == 1){
				$this->jump('发送成功');
			}else{
				$this->jump($data['info'],-1);
			} */

			$cause = trim($_POST['cause']);
			if(empty($cause)){
				$this->jump('操作原因缺少',-1);
			}
			
			$user = trim($_POST['user']);
			if(empty($user)){
				$this->jump('玩家不能空',-1);
			}
			
			$info = '';
			
				$info = <<<bindPlayerIdInfo
				<div>账号类型：{$this->userType[$_POST['userType']]}</div>
				<div>玩家：{$user}</div>
				<div>Level：{$_POST['Level']}</div>
bindPlayerIdInfo;
			$repeatStr = $postData['repeat']?'是':'否';
			$applyInfo =<<<applyInfo
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>申请原因：<br>{$cause}</div>
			</div>
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				{$info}
			</div>
applyInfo;
							$serverId = intval($_REQUEST['server_id']);
							$applyData = array(
									'type'=>41,	//从Game拿id
									'server_id'=>$serverId,
									'operator_id'=>$this->_serverList[$serverId]['operator_id'],
									'game_type'=>$this->_serverList[$serverId]['game_type_id'],
									'list_type'=>1,
									'apply_info'=>str_replace(array("\r\n","\n",),'',$applyInfo),
									'send_type'=>2,	//2	http
									'send_data'=>array(
											'url_append'=>$UrlAppend,
											'post_data'=>$postData,
											'get_data'=>$getData,
											//				'call'=>array(
													//					'cal_local_object'=>'',
													//					'cal_local_method'=>'',
													//				)
									// 				'end'=>array(
											// 					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
											// 					'cal_local_method'=>'applyEnd',	//使用本地方法
											// 					'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
											// 				),
									),
									'receiver_object'=>array($serverId=>''),
									'player_type'=>$_POST['userType'],
									'player_info'=>$user,
							);
							$modelApply = $this->_getGlobalData('Model_Apply','object');
							$result = $modelApply->AddApply($applyData);
							if($result==1){
								$URL_CsIndex = Tools::url('Apply','CsIndex');
								$URL_CsAll = Tools::url('Apply','CsAll');
								$showMsg = '申请成功,等待审核...<br>';
								$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
								$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
								$this->jump($showMsg,1,1,false);
							}else{
								$this->jump($result['info'],-1);
							}
		}
		$playerIds = '';
		if($_POST['playerIds']){
			$playerIds = implode(',',$_POST['playerIds']);
		}
		$this->_assign['userTypeSelect'] = 1;
		$this->_assign['users'] = $playerIds;
		return $this->_assign;
	}
	
}