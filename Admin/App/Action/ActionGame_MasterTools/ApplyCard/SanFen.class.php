<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ApplyCard_SanFen extends Action_ActionBase{
	private $playerType;
	private $goodsDis = '道具：<br>';
	public function _init(){
		$this->playerType = array(1=>'用户ID',2=>'用户账号',3=>'用户呢称 ');
		$this->_assign['playerType'] = $this->playerType;
		$this->_assign['items'] = $this->partner('Item');
		$this->_assign['URL_itemUpdate'] = Tools::url(
				CONTROL,
				'Item',
				array('timeout'=>'1','zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
	}
	
	public function getPostData($post=null){
		
		$playerType = $_POST['playerType'];
		$player = $_POST['player'];
		$mailTitle = $_POST['mailTitle'];
		$mailContent = $_POST['mailContent'];
		$goods = '';
		
		foreach ($_POST['itemNum'] as $k => $v){
			$goods .= $k.':'.$v.',';
			$this->goodsDis .= '名称：'.$_POST['itemName'][$k].'数量：'.$v .'  ';
		}
		$goods = substr($goods,0,strlen($goods)-1);
		
		
		$postData = array('playerType'=>$playerType,'player'=>$player,'title'=>$mailTitle,'content'=>$mailContent,'goods'=>$goods);
		if($post && is_array($post)){
			$postData = array_merge($postData,$post);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		$serverId = $_REQUEST['server_id'];
		if(!$serverId){
			return $this->_assign;
		}
		
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = $this->_gameObject->getGetData($get);
				$apply_info = "申请原因：<br>{$_POST['cause']}<p>";	//1的类型是奖励发送
				$apply_info .= '<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info .= $this->goodsDis;
				
				$apply_info .= '<br>用户类型：'.$this->playerType[$_POST['playerType']];
				$apply_info .= '<br>用户：'.$_POST['player'];
				$apply_info .= '</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.$_REQUEST['__game_id']);
				$applyData = array(
						'type'=>50,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>$UrlAppend,
								'post_data'=>$postData,
								'get_data'=>$getData,
// 								'call'=>array(
// 										'cal_local_object'=>'Util_ApplyInterface',
// 										'cal_local_method'=>'FrgSendReward',
// 								)
						),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>$_POST['playerType'],
						'player_info'=>$_POST['player'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
// 				print_r($applyData);exit;
// 				var_dump($applyInfo);exit;
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->jump($showMsg,1,1,false);
				}else{
					$this->jump($applyInfo['info'],-1);
				}
		}
		return $this->_assign;
	}
	/**
	 * 整理礼包领取的条件
	 */
	private function _getReceiveConditions($returnType='data'){
		if($this->_conditionsData ===null && $this->_conditionsInfo ===null){
			$this->_conditionsInfo = '';
			$this->_conditionsData = array();
			$relations = array(
				'1'=>'>',
				'2'=>'<',
				'0'=>'=',
			);
			if(is_array($_POST['conditions']) ){
				foreach($_POST['conditions'] as $sub){
					$field = $sub['field'];
					$name = $sub['name'];
					$relation = $relations[$sub['relation']];
					$value = $sub['value'];
					$isTime = intval($sub['isTime']);
					if($isTime){
						if(false !== ($timeStamp = strtotime($value)) ){
							$this->_conditionsInfo .= "{$name} {$relation} <font color='#FF0000'>{$value}</font>(t)、";
							$this->_conditionsData[$field][] = array($relation,$timeStamp);
						}
					}else{
						$this->_conditionsInfo .= "{$name} {$relation} <font color='#FF0000'>{$value}</font>、";
						$this->_conditionsData[$field][] = array($relation,$value);
					}
				}
			}
		}
		switch($returnType){
			case 'info':
				return $this->_conditionsInfo;
			case 'data':
			default:
				return json_encode($this->_conditionsData);
		}
	}
	
	/**
	 * 整理表单中的道具
	 */
	private function _getItemsData($returnType='ItemsData'){
		if($this->_itemsData ===null && $this->_itemsInfo ===null){
			$this->_itemsInfo = '';
			$this->_itemsData = array();
			//$itemsData = array();//goods	String	是	道具JSON格式[{goodsId:xx, num:xx, bind:1},{},{},{}]
			if(is_array($_POST['itemNum']) ){
				foreach($_POST['itemNum'] as $itemId =>$num){
					$this->_itemsInfo .= "{$_POST['itemName'][$itemId]}(<font color='#FF0000'>{$num}</font>)、";
					$this->_itemsData[] = array(
						'goodsId'=>$itemId,
						'num'=>max(0,intval($num)),
						'bind'=>1,
					);
				}
			}
		}
		switch($returnType){
			case 'ItemsInfo':
				return $this->_itemsInfo;
			case 'ItemsData':
			default:
				return json_encode($this->_itemsData);
		}
	}

	/**
	 * 不发邮件时间清空标题、内容
	 * @param array $data
	 */
	private function _isSendMail(&$data){
		if($_POST['isSendMail']=='0'){
			unset($data['mailTitle'],$data['mailContent']);
		}
	}
	/**
	 * 不发元宝时，元宝类型置0
	 * @param array $data
	 */
	private function _goldExist(&$data){
		if($data['goldValue'] <= 0){
			$data['goldType'] = 0;
		}
	}
	/**
	 * 获得过期时间
	 * @param unknown_type $dateFormat
	 */
	private function _expire($dateFormat = null){
		if(null === $dateFormat){
			$dateFormat = $_POST['expiration'];
		}
		$dateFormat = trim($dateFormat);
		if(empty($dateFormat)){
			return 0;
		}
		$dateFormat = strtotime($dateFormat);
		if(false === $dateFormat){
			return 0;
		}
		return $dateFormat-CURRENT_TIME;
	}
	
	private function _apply($UrlAppend ='',$getData=array(),$postData=array()){
		$cause = trim($_POST['cause']);
		$bindType = $this->_assign[self::BIND_TYPE];
		if(empty($cause)){
			$this->jump('操作原因缺少',-1);
		}
		$bindPlayerIdInfo = '';
		$sendMailInfo = '';
		if($postData['bindType']==2){
			$playerType = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称');
			$bindPlayerIdInfo = <<<bindPlayerIdInfo
				<div>账号类型：{$playerType[$postData['playerType']]}</div>
				<div>绑定玩家：{$postData['playerId']}</div>
bindPlayerIdInfo;
			if($postData['mailTitle'] && $postData['mailContent']){
				$sendMailInfo = <<<sendMailInfo
				<div>邮件标题：{$postData['mailTitle']}</div>
				<div>邮件内容：{$postData['mailContent']}</div>
sendMailInfo;
			}
		}
		//过期时间
		$expireTime = $postData['expiration']?date('Y-m-d H:i:s',CURRENT_TIME+$postData['expiration']):'不过期';
		$itemsInfo = '';
		if($postData['goldValue']>0){
			//记录元宝
			$itemsInfo .= "元宝(<font color='#FF0000'>{$postData['goldValue']}</font>)、";
		}
		if($postData['assetValue']>0){
			//记录银两
			$itemsInfo .= "银两(<font color='#FF0000'>{$postData['assetValue']}</font>)、";
		}
		if($postData['goldTickeValue']>0){
			//记录银票
			$itemsInfo .= "天心(<font color='#FF0000'>{$postData['goldTickeValue']}</font>)、";
		}
		if($postData['prestige']>0){
			//记录银票
			$itemsInfo .= "声望(<font color='#FF0000'>{$postData['prestige']}</font>)、";
		}
		if($postData['CashGift']>0){
			//记录银票
			$itemsInfo .= "礼金(<font color='#FF0000'>{$postData['CashGift']}</font>)、";
		}
		if($postData['Energy']>0){
			//记录银票
			$itemsInfo .= "行动值(<font color='#FF0000'>{$postData['Energy']}</font>)、";
		}
		
		
		$itemsInfo .= '道具：'.$this->_getItemsData('ItemsInfo');	//记录道具内容
		
		$repeatStr = $postData['repeat']?'是':'否';
		$applyInfo =<<<applyInfo
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>申请原因：<br>{$cause}</div>
			</div>
			<div style="padding:3px; margin:3px; border:1px dashed #000">
				<div>礼包类型：{$this->_assign[self::ITEM_PACKAGE_TYPE][$postData['type']]}</div>
				<div>重复领取：{$repeatStr}</div>
				<div>卡密长度：{$this->_assign[self::KEY_TYPE][$postData['keyType']]}</div>
				<div>礼包名称：{$postData['name']}</div>
				<div>绑定方式：{$bindType[$postData['bindType']]}</div>
				<div>生成数量：{$postData['counts']}</div>
				<div>过期时间：{$expireTime}</div>
				{$bindPlayerIdInfo}
				{$sendMailInfo}
				<div>内容：{$itemsInfo}</div>
			</div>
applyInfo;
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
// 				'end'=>array(
// 					'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象
// 					'cal_local_method'=>'applyEnd',	//使用本地方法
// 					'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
// 				),
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>empty($bindPlayerIdInfo)?0:1,
			'player_info'=>$postData['playerId'],
		);
		$modelApply = $this->_getGlobalData('Model_Apply','object');
		$result = $modelApply->AddApply($applyData);
		if($result==1){
			return true;
		}else{
			$this->jump($result['info'],-1);
		}
	}

}