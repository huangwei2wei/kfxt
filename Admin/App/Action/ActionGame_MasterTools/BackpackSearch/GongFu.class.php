<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_BackpackSearch_GongFu extends Action_ActionBase{
	public function _init(){
		$this->_assign['URL_itemsDel'] = $this->_urlItemsDel();
		$this->_assign['itemTypes'] = Tools::gameConfig('itemTypes',$this->_gameObject->_gameId);
	}

	public function getPostData($post=null){
		$player = array(
		1=>trim($_GET['playerId']),
		2=>trim($_GET['playerAccount']),
		3=>trim($_GET['playerNickname']),
		);
		$player = array_filter($player);
		if(!$player){
			return false;
		}
		$playerType = key($player);
		$playerValue = current($player);
		$this->_assign['playerSelect'] = array($playerType,$playerValue);
		return array(
			'type'=>$playerType,
			'value'=>$playerValue,
		);
	}

	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		$postData = $this->getPostData($post);

		if($postData){
			$getData = $this->_gameObject->getGetData($get);
				
			$data = $this->getResult($UrlAppend,$getData,$postData);
			/**
			 $applyInfo ='
			 <div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>申请原因：<br>'.$cause.'</div>
				</div>
				<div style="padding:3px; margin:3px; border:1px dashed #000">
				</div>
				applyInfo;';
				$serverId = intval($_REQUEST['server_id']);
				$applyData = array(
				'type'=>$this->_gameObject->getApplyId('ItemCardApply'),	//从Game拿id
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
				return $modelApply->AddApply($applyData);
				**/
			//0-类型，1-位置，2-数量,3-id，4-名字
			if($data['status'] == 1){
				$this->_assign['dataList'] = $data['data'];
			}else{
				$this->jump($data['info'],-1);
			}
		}
		return $this->_assign;
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