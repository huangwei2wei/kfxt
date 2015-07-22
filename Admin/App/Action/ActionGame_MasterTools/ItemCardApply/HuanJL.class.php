<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_ItemCardApply_HuanJL extends Action_ActionBase{
	
	const ITEM_TYPE = 'itemTypes';
	const BIND_TYPE = 'bindType';
	const GOLD_TYPE = 'goldType';
	const KEY_TYPE = 'keyType';
	const ITEM_PACKAGE_TYPE = 'itemPackageType';
	const EXCHANGE_TYPE = 'exchangeTypeApply';
	private $_itemsData = null;
	private $_itemsInfo = null;
	private $_conditionsData = null;
	private $_conditionsInfo = null;
	public function _init(){
		$this->_assign[self::ITEM_TYPE] = Tools::gameConfig(self::ITEM_TYPE,$this->_gameObject->_gameId);	//itemTypes
		$this->_assign[self::BIND_TYPE] = Tools::gameConfig(self::BIND_TYPE,$this->_gameObject->_gameId);	//bindType
		$this->_assign[self::GOLD_TYPE] = Tools::gameConfig(self::GOLD_TYPE,$this->_gameObject->_gameId);	//goldType
		$this->_assign[self::KEY_TYPE] = Tools::gameConfig(self::KEY_TYPE,$this->_gameObject->_gameId);	//keyType
		$this->_assign[self::ITEM_PACKAGE_TYPE] = Tools::gameConfig(self::ITEM_PACKAGE_TYPE,$this->_gameObject->_gameId);	//keyType
		$this->_assign[self::EXCHANGE_TYPE] = Tools::gameConfig(self::EXCHANGE_TYPE,$this->_gameObject->_gameId);	//keyType
		
		$this->_assign['itemReceiveConditions'] = $this->partner('ItemReceiveCondition');//礼包领取的条件
		$this->_assign['items'] = $this->partner('Item');
		$this->_assign['selected'][self::BIND_TYPE] = 2;//0：不绑定，1：绑定服，2：绑定玩家
		$this->_assign['selected'][self::GOLD_TYPE] = 2;//1：系统元宝,2：GM元宝
		$this->_assign['selected'][self::KEY_TYPE] = 10;//1：36位，2 10位
		$this->_assign['selected'][self::ITEM_PACKAGE_TYPE] = 1;//1：标准礼包，2: 共享礼包
		$this->_assign['selected'][self::EXCHANGE_TYPE] = 2;//1：补偿金币卡，2: 智能金币卡
		
		$this->_assign['URL_itemReceiveConditions'] = Tools::url(
			CONTROL,
			'ItemReceiveCondition',
			array('timeout'=>'1','zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_itemUpdate'] = Tools::url(
			CONTROL,
			'Item',
			array('timeout'=>'1','zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_itemCard'] = Tools::url(
			CONTROL,
			'ItemCard',
			array('submit'=>1,'zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_playerLookup'] = Tools::url(
			CONTROL,
			'PlayerLookup',
			array('sbm'=>1,'zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
		$this->_assign['URL_ItemPackageEdit'] = Tools::url(
			CONTROL,
			'ItemPackageEdit',
			array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId,'server_id'=>$_REQUEST['server_id'])
		);
	}
	
	public function getPostData($post=null){

		$type = intval($_POST['type']);	//100：标准礼包，200: 共享礼包
		$repeat = $_POST['repeat']?1:0;
		$keyType = intval($_POST['keyType']);//type	Int	否	礼包类型	1 36位 ，2 10位
		$name = strval($_POST['name']);//name	String	否	礼包的名字
		$bindType = intval($_POST['bindType']);//bindType	Int	否	绑定类型。0：不绑定，1：绑定服，2：绑定玩家
		$counts = $bindType===2?1:max(1,intval($_POST['counts']));//counts	Int	否	礼包卡密的数量
		$playerType = intval($_POST['playerType']); //玩家类型
		$playerId = $bindType===2?trim($_POST['playerId']):0;//playerId	Long	是	绑定玩家是玩家Id
		//$goldType = intval($_POST['goldType']);//goldType	Int	是	元宝类型, 1：系统元宝,2：GM元宝
		$exchangeType = intval($_POST['exchangeType']);
		$goldValue = max(0,intval($_POST['goldValue']));//goldValue	Int	是	元宝数量
		$goldTickeValue = max(0,intval($_POST['goldTickeValue']));//天心
		$prestige = max(0,intval($_POST['Prestige']));//声望
		$CashGift =  max(0,intval($_POST['CashGift']));//礼金
		$Energy =  max(0,intval($_POST['Energy']));//行动值
		$assetValue = max(0,intval($_POST['assetValue']));//int 银两
		$mailTitle = trim($_POST['mailTitle']);//mailTitle	String	是	邮件标题
		$mailContent = trim($_POST['mailContent']);//mailContent	String	是	邮件内容
		$expiration = strtotime(trim($_POST['expiration']))?trim($_POST['expiration']):'';//expiration	String	是	礼包过期时间，没限制可不填或者为""
		$goods = $this->_getItemsData('ItemsData');//goods	String	是	道具JSON格式[{goodsId:xx, num:xx, bind:1},{},{},{}]
		$conditions = $this->_getReceiveConditions('data');
		$desn =  $cause = trim($_POST['cause']); // 申请理由
		
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
			if($goldValue>20000){
				$this->jump('元宝不能超过20000',-1);
			}
		}
		
		$points = json_encode(
				array(
						'goldValue'=>$goldValue,
						'assetValue'=>$assetValue,
						'goldTickeValue'=>$goldTickeValue,
						'Prestige'=>$prestige,
						'CashGift' =>$CashGift,
						'Energy'=>$Energy,
						)
				);
		
		$postData = compact('type','repeat','keyType','name','counts',
				'bindType','playerType','playerId','points','exchangeType','goldTickeValue',
				'mailTitle','mailContent','expiration','goods','conditions','desn',
				'goldValue','assetValue','goldTickeValue','prestige','CashGift','Energy'
				);
//		if($bindType == 1){
//			$postData['serverId'] = $this->_gameObject->getServerId();//在getData中已经带上
//		}
		$postData['__fields__'] = array('batchId'=>'Id');	//batchId将要换成申请的id,作为批号
		//对$postData进行数据调整
		$this->_isSendMail($postData);
		$this->_goldExist($postData);
		$validate = array(
			'type'=>array(array('array_key_exists','###',$this->_assign[self::ITEM_PACKAGE_TYPE]),'礼包类型不合法'),
			'keyType'=>array(array('array_key_exists','###',$this->_assign[self::KEY_TYPE]),'卡密长度类型不合法'),
			'name'=>array('trim','名字不能为空'),
			'bindType'=>array(array('array_key_exists','###',$this->_assign[self::BIND_TYPE]),'绑定类型不合法'),
			'exchangeType'=>$postData['goldValue']?array(array('array_key_exists','###',$this->_assign[self::EXCHANGE_TYPE]),'元宝类型超出范围'):null,
			'playerId'=>$postData['bindType']==2?$validate['playerId'] = array('trim','绑定的玩家不能为空'):null,
// 			'expiration'=>$postData['expiration']!==0?array(array('max','###',0),'如果填写过期时间,则需大于当前时间'):null,
		);
// 		var_dump( $postData['expiration']);
		$check = Tools::arrValidate($postData,$validate);
		if($check !== true){
			$this->jump($check,-1);
		}
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id']){
			return $this->_assign;
		}
		if($this->_isPost()){
			$postData = $this->getPostData($post);
			$getData = array(
				'cal_local_object'=>'Game_'.$this->_gameObject->_gameId,	//使用本地对象，如果为空，则使用内置函数
				'cal_local_method'=>'getGetData',	//使用本地方法，‘cal_local_method’判断是否使用内部方法
				'params'=>array(	//如果有参数，就使用数组方式表示
					$get,
					intval($_REQUEST['server_id']),
				),
			);
			//$postData['batchId'] = intval($_POST['batchId']);//测试
//			$data = $this->getResult($UrlAppend,$getData,$postData);
			if($this->_apply($UrlAppend,$getData,$postData)){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->jump($showMsg,1,1,false);
			}
			$this->jump('申请失败',-1);
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