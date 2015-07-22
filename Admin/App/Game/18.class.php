<?php
Tools::import('Game_GameBase');
class Game_18 extends Game_GameBase{

	public $_key	=	"0147de556c087729312bb404ab15493f";

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 18;		//游戏Id
		$this->_zp = 'MingXing';	//控制器扩展包
		//		$this->_key = 'zg4826d5-2332-473b-942a-bbfbac834585';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}

	public function workOrderIfChk(){
		return $this->clientTimeChk();
	}

	public function sendOrderReplay($data=NULL){
		//		$postData["title"]	=	"您的提问已回复";
		//		$postData["content"]	=	"您的提问已回复，请查看<a href='event:Customers_replay'></a>";
		//		$postData["openIdlist"]	=	$data["game_user_id"];
		//		$getData["action"]	=	"sendSystemEmail";
		//		$getData	=	$this->getGetData($getData);
		//		$_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		//		$sendUrl=$_serverList[$data["service_id"]]['server_url'];
		//		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		//		//		$sendUrl = $this->_getUrl(false,$isOperatorId);
		//		$data = $utilHttpInterface->result($sendUrl,"",array_merge($getData,$postData));
		//		//		return json_decode($data,true);
		return true;
	}

	public function autoReplay($data=NULL){
		return false;
	}

	public function operatorExtParam(){
		return array();
	}

	public function serverExtParam(){
		return array();
	}

	public function getSignArr($getData = array()){
		$actionName = $getData['action'];
		//		echo "time=".CURRENT_TIME."&key".$this->_key;
		return array(
			'time'=>CURRENT_TIME,
			'sign'=>md5("time=".CURRENT_TIME."&key=".$this->_key),
		);
	}

	public function getServerId($serverId=0){
		$returnServerId = 'S';
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$returnServerId .= intval($serverList[$serverId]['ordinal']);
		}
		return $returnServerId;
	}

	/**
	 * 获得GET基本数据
	 * @param $get
	 */
	public function getGetData($get = array(),$serverId=0){
		$data = $this->getSignArr($get);
		if($get && is_array($get)){
			$data = array_merge($get,$data);
		}
		return array_filter($data);
	}

	public function getApplyId($mark = ''){
		$mark = trim($mark);
		if(empty($mark)){
			return false;
		}
		$applyInfo = array(
			'ItemCardApply'=>18,
		);
		return $applyInfo[$mark];
	}

	public function ApplySend($data,$server_id,$UrlAppend){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($server_id && is_array($this->_serverList[$server_id])){
			$sendUrl	= $this->_serverList[$server_id]['server_url'];
		}
		$getData = $this->getGetData();
		$result = $utilHttpInterface->result($sendUrl,$UrlAppend,$getData,$data);
		$result = json_decode($result,true);
		if($result["Result"]===0){
			$str = "审核成功";
		}else{
			$str = "审核失败：".$result["Result"];
		}
		return  $str;
	}

	public function getNotice($data=array()){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if($_REQUEST['server_id']){
			$Id = 	$_REQUEST['server_id'];
		}else{
			$Id	=	$data['server_id'];
		}
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($Id && is_array($this->_serverList[$Id])){
			$sendUrl	= $this->_serverList[$Id]['server_url'];
		}
		if(!$sendUrl)return false;
		$data = $utilHttpInterface->result($sendUrl,"notice",$this->getGetData(array("action"=>"get")),NULL);
		return json_decode($data,true);
	}

	public function TransformNoticeData($data=array()){
		$datalist	=	$this->getNotice($data['post']);
		$addArrs=array();
		if($datalist['data']){
			foreach ($datalist['data'] as $value){
				$addArr=array();
				$addArr['content']		=$value['title'];
				$addArr['title']		=$value['title'];
				$addArr['start_time']	=$value['beginTime']?strtotime($value['beginTime']):0;
				$addArr['end_time']		=$value['endTime']?strtotime($value['endTime']):0;
				$addArr['interval_time']=$value['initialDelay'];
				$addArr['url']			=$value['url'];
				$addArr['create_time']	='0';
				$addArr['last_send_time']='0';
				$addArr['main_id']		=$value['id'];
				array_push($addArrs,$addArr);
			}
			return $addArrs;
		}
	}

	public function delNotice($data=array()){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if($_REQUEST['server_id']){
			$Id = 	$_REQUEST['server_id'];
		}else{
			$Id	=	$data['server_id'];
		}
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($Id && is_array($this->_serverList[$Id])){
			$sendUrl	= $this->_serverList[$Id]['server_url'];
		}
		if(!$sendUrl)return false;
		$data = $utilHttpInterface->result($sendUrl,"notice",$this->getGetData(array("action"=>"delete","id"=>$data['ids'])),NULL);
		return json_decode($data,true);
	}

	public function addNotice($data=array()){

		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if($_REQUEST['server_id']){
			$Id = 	$_REQUEST['server_id'];
		}else{
			$Id	=	$data['server_id'];
		}
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($Id && is_array($this->_serverList[$Id])){
			$sendUrl	= $this->_serverList[$Id]['server_url'];
		}
		$getSend	=	array(
			"action"	=>	"publish",
		);
		$postSend	=	array(
			"title"		=>	$data['title'],
			"url"		=>	$data['url'],
			"delay"		=>	$data['delay'],
			"begin"		=>	$data['begin'],
			"end"		=>	$data['end'],
		);
		$data 	= $utilHttpInterface->result($sendUrl,"notice",$this->getGetData($getSend,$Id),$postSend);
		$data	=	json_decode($data,true);
		if($data['status'] == 1){
			return true;
		}else{
			return false;
		}
	}

	public function SendcartFile($sendData=NULL,$receiver=NULL){
		$_utilHttpDown = $this->_getGlobalData('Util_Httpdown','object');
		$gameServerList=$this->_getGlobalData('gameser_list');
		$ServerId	=	 array_keys($receiver);
		$filePath = $sendData['post_data']['filePath'];
		$isImportFile = $sendData['post_data']['importFile'];
		unset($sendData['post_data']['filePath'],$sendData['post_data']['importFile']);
		//		foreach ($sendData['post_data'] as $k=>$v){
		//			$_utilHttpDown->AddForm($k,$v);
		//		}
		$url=$gameServerList[$ServerId[0]]['server_url'].$sendData['url_append']."?";
		$url.=http_build_query(array_merge($sendData['get_data'],$sendData['post_data']));
		if($isImportFile==1){
			$_utilHttpDown->AddFileContent('file',basename($filePath),file_get_contents($filePath));
		}
		$_utilHttpDown->OpenUrl($url);
		if($_utilHttpDown->IsGetOK()){
			$dataResult=$_utilHttpDown->GetRaw();
			$dataResult = json_decode($dataResult,ture);
			if($dataResult['status']!=1){
				return $dataResult;
			}
		}
	}

	/**
	 * 获取玩家的信息 
	 */
	public function getPlayerDataByAccount($playerAccount = '',$serverId=0){
		if(empty($playerAccount)){
			return array();
		}
		$ifConf = $this->getIfConf();
		$getData = $this->getGetData($ifConf['PlayerLookup']['get'],$serverId);
		$postData=array(
			'accountName'=>$playerAccount,
			'pageSize'=>1,
			'pageCount'=>1,
		);

		$server = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		$sendUrl = '';
		if($server[$serverId]){
			$sendUrl = $server[$serverId]['server_url'];
		}
		if(empty($sendUrl)){
			return array();
		}
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$data = $utilHttpInterface->result($sendUrl,$ifConf['PlayerLookup']['UrlAppend'],$getData,$postData);
		$returnData = array();
		if($data && ($data =  json_decode($data,true)) && is_array($data['data']['players']['0']) && ($playerInfo = $data['data']['players']['0']) ){
			$returnData = array(
				'user_id'=>Tools::d2s($playerInfo['playerId']),
				'user_account'=>$playerAccount,
				'user_nickname'=>$playerInfo['playerName'],
				'money_total'=>0,
				'money_month'=>0,
				'register_date'=>strtotime($playerInfo['regTime']),
				'ip'=>Tools::getClientIP(),
			);
		};
		return $returnData;
	}

	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
		return array(
					'ServerState'=>array(
					'action'=>'zhanlong',
					'UrlAppend'=>'QuerySystem/OnlineCount',
					'body'=>'',
		),

					'PlayerLookup'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QueryAccount/Account',
						'body'=>'',
		),
					'PlayerRoleList'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QueryPlayer/PlayerListInfo',
						'body'=>'',
		),
					'PlayerRoleDelList'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QueryPlayer/PlayerListDeleted',
						'body'=>'',
		),
					'LockIP'=>array(	//封IP列出
						'action'=>'zhanlong',
						'UrlAppend'=>'QueryAccount/FreezeIPList',
						'body'=>'',
		),
					'LockIPDone'=>array(	//封IP提交
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateAccount/FreezeIP',
						'body'=>'',
		),
					'LockIPDel'=>array(	//封IP提交
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateAccount/FreezeIP',
						'body'=>'',
		),
					'PlayerLog'=>array(	//玩家日志
						'action'=>'zhanlong',
						'UrlAppend'=>'index.php',
						'body'=>'',
		),
					'RechargeRecord'=>array(	//玩家日志
						'action'=>'zhanlong',
						'UrlAppend'=>'index.php',
						'body'=>'',
		),

					'Consumption'=>array(	//玩家日志
						'action'=>'zhanlong',
						'UrlAppend'=>'index.php',
						'body'=>'',
		),
		//			'PersonalInformation'=>array(
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'getUserAttr'),
		//				'body'=>'',
		//		),
		//			'PlayerPartner'=>array(	//玩家伙伴
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'getActorList'),
		//				'body'=>'',
		//		),
		//			'PlayerPartnerInfo'=>array(	//玩家伙伴属性
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'getActorAttr'),
		//				'body'=>'',
		//		),
		//			'BackpackSearch'=>array(	//用户道具查询
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'getItemList'),
		//				'body'=>'',
		//		),
		//			'Equipment'=>array(	//玩家装备
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'getEquipmentList'),
		//				'body'=>'',
		//		),
		//			'EquipmentUpgrade'=>array(	//装备升级申请
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'upgradeEquipment'),
		//				'body'=>'',
		//		),
		//			'EquipmentDel'=>array(	//装备删除申请
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'deleteEquipment'),
		//				'body'=>'',
		//		),
		//		//			'Item'=>array(	//道具更新|获得道具列表
		//		//				'action'=>'GongFu',
		//		//				'UrlAppend'=>'goods',
		//		//				'get'=>array('action'=>'get'),
		//		//				'body'=>'',
		//		//			),
		//			'ItemDel'=>array(	//道具删除
		//				'action'=>'MingXing',
		//				'UrlAppend'=>'',
		//				'get'=>array('action'=>'deleteItem'),
		//		),
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
			'Organize'=>array(	//帮会查询
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryGame/FactionList',
				'body'=>'',
		),
			'OrganizeMenber'=>array(	//帮会查询
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryGame/FactionMembers',
				'body'=>'',
		),
					'Notice'=>array(	//公告列表
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/Broadcast',
						'body'=>'',
		),

					'NoticeAdd'=>array(	//添加公告
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/Broadcast',
						'body'=>'',
		),

					'NoticeDel'=>array(	//删除公告
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/Broadcast',
						'body'=>'',
		),

					'NoticeEdit'=>array(	//编辑公告
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/Broadcast',
						'body'=>'',
		),
					'BadWord'=>array(	//编辑公告
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/BadWord',
						'body'=>'',
		),
					'ShopProduce'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/MallItemList',
						'body'=>'',
		),
					'AddShopProduce'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/MallItem',
						'body'=>'',
		),
					'DelShopProduce'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/MallItem',
						'body'=>'',
		),

		//					'QShopProduce'=>array(
		//						'action'=>'zhanlong',
		//						'UrlAppend'=>'QuerySystem/CashItemList',
		//						'body'=>'',
		//		),
		//					'AddQShopProduce'=>array(
		//						'action'=>'zhanlong',
		//						'UrlAppend'=>'UpdateSystem/CashItem',
		//						'body'=>'',
		//		),
		//					'DelQShopProduce'=>array(
		//						'action'=>'zhanlong',
		//						'UrlAppend'=>'UpdateSystem/CashItem',
		//						'body'=>'',
		//		),
					'ItemCard'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/ItemPackList',
						'body'=>'',
		),
					'ItemCardApply'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/ItemPack',
						'body'=>'',
		),
					'ItemDel'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/ItemPack',
						'body'=>'',
		),
					'ActivitiesList'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/ClassList',
						'body'=>'',
		),
					'ActivitiesAdd'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/Class',
						'body'=>'',
		),
					'ActivitiesDel'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/Class',
						'body'=>'',
		),
					'BackpackSearch'=>array(	//用户背包查询
						'action'=>'zhanlong',
						'UrlAppend'=>'QueryPlayer/PlayerInfoItem',
						'body'=>'',
		),
					'EventStoryList'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'QuerySystem/EventStoryList',
						'body'=>'',
		),
					'EventStoryAdd'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/EventStory',
						'body'=>'',
		),
					'EventStoryDel'=>array(
						'action'=>'zhanlong',
						'UrlAppend'=>'UpdateSystem/EventStory',
						'body'=>'',
		),
			'Silence'=>array(	//禁言
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryPlayer/GagPlayerList',
				'body'=>'',
		),
			'SilenceAdd'=>array(	//添加禁言
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/GagPlayer',
				'notify'=>'Log_Silence',
		),
			'SilenceDel'=>array(	//删除禁言
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/GagPlayer',
				'body'=>'',
				'notify'=>'Log_Silence',
		),

			'LockAccount'=>array(	//封号
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryAccount/FreezeAccountList',
				'body'=>'',
		),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateAccount/FreezeAccount',
				'notify'=>'Log_LockAccount',
		),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateAccount/FreezeAccount',
				'body'=>'',
				'notify'=>'Log_LockAccount',
		),
			'Item'=>array(	//删除封号
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ItemList',
				'body'=>'',
		),
			'MailList'=>array(	//删除封号
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryPlayer/MailList',
				'body'=>'',
		),
			'SendMail'=>array(	//删除封号
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/Mail',
				'body'=>'',
		),
			'SendEmailMultiserver'=>array(	//多服发送邮件
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/Mail',
				'body'=>'',
		),


			'MailDel'=>array(	//删除封号
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/RemoveMail',
				'body'=>'',
		),
			'Define'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/AllDefine',
				'body'=>'',
		),
		    'ItemCardSync'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ItemPackList',
				'body'=>'',
		),
		     'NoticeSync'=>array(
				'action'=>'zhanlong',
			    'UrlAppend'=>'QuerySystem/Broadcast',
				'body'=>'',
		),
		      'ActivitiesSync'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'PowerIP'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'ShopProduceSync'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/MallItemList',
				'body'=>'',
		),
			'ServicerMaintain'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'ServicerMaintainSync'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'WebList'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'WebAdd'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'DipList'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'DipAdd'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),

			'PayList'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),
			'PayAdd'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassList',
				'body'=>'',
		),

			'SysLog'=>array(
				'action'=>'Default',
				'UrlAppend'=>'',
				'body'=>'',
		),
			'PowerAccount'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'',
				'body'=>'',
		),
			'ActivitiesConfiguration'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassTable',
				'body'=>'',
		),

			'ActivitiesConfigurationDel'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateSystem/ClassTable',
				'body'=>'',
		),

			'ActivitiesConfigurationAdd'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateSystem/ClassTable',
				'body'=>'',
		),

			'ActivitiesConfigurationSync'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/ClassTable',
				'body'=>'',
		),
			'ItemLog'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'',
				'body'=>'',
		),
			'CopyPlayer'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdatePlayer/CopyPlayer',
				'body'=>'',
		),
			'OpenTime'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QuerySystem/WorldBeginTime',
				'body'=>'',
		),

			'PlayerRegName'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryPlayer/PlayerRegName',
				'body'=>'',
		),
			'Log'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryPlayer/PlayerRegName',
				'body'=>'',
		),
			'FunOnOrOff'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateSystem/GameConfig',
				'body'=>'',
		),
		//LogTypePlayerInfoSale
		'LogType'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'UpdateSystem/GameConfig',
				'body'=>'',
		),
		
		'PlayerInfoSale'=>array(
				'action'=>'zhanlong',
				'UrlAppend'=>'QueryPlayer/PlayerInfoSale',
				'body'=>'',
		),
			
		
		'ExcelImport'=>array(
				'action'=>'Default',
				'UrlAppend'=>'UpdateSystem/GameConfig',
				'body'=>'',
		),
		


		);
	}

	public function getiDoingObject(){
		return array(
		0=>array(
				"des"=>"无对像与事件匹配",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		1=>array(
				"des"=>"钱币",
				"iObjectID"=>$this->getMoney(),
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"is_item"=>true,
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		2=>array(
				"des"=>"声望",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		3=>array(
				"des"=>"经验",
				"iObjectID"=>"",
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		4=>array(
				"des"=>"等级",
				"iObjectID"=>"",
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		5=>array(
				"des"=>"元力",
				"iObjectID"=>"",
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		6=>array(
				"des"=>"道具",
				"iObjectID"=>$this->_f("zlsg_t_name_item"),
				"iLinkID"=>"商店ID：",
				"iFromValue"=>"数量",
				"iToValue"=>$this->getMoney(),
				"iChangeValue"=>"货币值",
		),
		7=>array(
				"des"=>"技能",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>$this->_f("zlsg_t_name_skill"),
				"iToValue"=>$this->_f("zlsg_t_name_skill"),
				"iChangeValue"=>$this->_f("zlsg_t_name_skill"),
		),
		8=>array(
				"des"=>"PK值",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		9=>array(
				"des"=>"成就",
				"iObjectID"=>"",
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		10=>array(
				"des"=>"帮会贡献",
				"iObjectID"=>$this->getLingPai(),
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		11=>array(
				"des"=>"帮会令牌",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		12=>array(
				"des"=>"邮件",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		13=>array(
				"des"=>"潜能",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>$this->_f("zlsg_t_name_potential"),
				"iToValue"=>$this->_f("zlsg_t_name_potential"),
				"iChangeValue"=>$this->_f("zlsg_t_name_potential"),
		),
		14=>array(
				"des"=>"宝石",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		15=>array(
				"des"=>"坐骑",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		16=>array(
				"des"=>"坐骑技能",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>$this->_f("zlsg_t_name_skill"),
				"iChangeValue"=>$this->_f("zlsg_t_name_skill"),
		),
		17=>array(
				"des"=>"坐骑技能激活次数",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
		18=>array(
				"des"=>"任务",
				"iObjectID"=>"",
				"iLinkID"=>"任务类型：",
				"iFromValue"=>$this->_f("zlsg_t_name_quest"),
				"iToValue"=>$this->_f("zlsg_t_name_quest"),
				"iChangeValue"=>$this->_f("zlsg_t_name_quest"),
		),
		19=>array(
				"des"=>"课程表",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"触发课程表ID:",
				"iChangeValue"=>"变化为",
		),
		20=>array(
				"des"=>"坐骑技能经验",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
			
		21=>array(
				"des"=>"副本",
				"iObjectID"=>"",
				"iLinkID"=>array(0=>"扫荡/领取奖励"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"增加可领奖数量:",
		),

		22=>array(
				"des"=>"转盘",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),

		23=>array(
				"des"=>"坐骑祝福值",
				"iObjectID"=>"",
				"iLinkID"=>$this->_f("zlsg_t_name_item"),
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"非系统事件为影响值",
		),
		24=>array(
				"des"=>"弓箭",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"",
				"iChangeValue"=>"",
		),

		25=>array(
				"des"=>"弓箭祝福值",
				"iObjectID"=>"",
				"iLinkID"=>"",
				"iFromValue"=>"",
				"iToValue"=>"变为",
				"iChangeValue"=>"变化为",
		),
			
		);
	}

	public function _getDataTable(){
		return array(
			"Doing"			=>"操作日志",
			"Doing_Other"	=>"其他操作",
			"Doing_Money"	=>"钱币操作",
			"Doing_Repute"	=>"声望操作",
			"Doing_Exp"		=>"经验操作",
			"Doing_Level"	=>"等级操作",
			"Doing_YuanLi"	=>"元力/韬略操作",
			"Doing_Item"	=>"道具操作",
			"Doing_Skill"	=>"技能操作",
			"Doing_PKValue"	=>"PK值操作",
			"Doing_Achieve"	=>"成就操作",
			"Doing_FactionContribution"=>"帮贡操作",
			"Doing_FactionToken"=>"帮会令牌操作",
			"Doing_Mail"		=>"邮件操作",
			"Doing_Potential"	=>"潜能操作",
			"Doing_Precious"	=>"宝石操作",
			"Doing_Mount"		=>"坐骑操作",
			"Doing_MountSkill"	=>"坐骑技能操作",
			"Doing_MountSkillActivation"=>"坐骑技能激活次数操作",
			"Doing_Quest"		=>"任务操作",
			"Doing_Class"		=>"活动操作",
			"Doing_MountSkillExp"=>"坐骑技能经验操作",
			"Doing_Copymap"		=>"副本操作",
			"Doing_RotaryTable"	=>"转盘操作",
			"Doing_MountBlessValue"=>"坐骑祝福值操作",
			"Doing_Copymap"=>"创建副本及完成副本",
			"Doing_General"=>"武将",
			"Doing_RidingWeapon"=>"骑战兵器",
			"Doing_RidingWeaponSchedule"=>"骑战兵器进度",
			"Doing_Bow"=>"弓箭",
			"Doing_BowBlessValue"=>"弓箭祝福值",
		);
	}

	public function getMoney(){
		return array(
		0=>"游戏币",
		1=>"元宝",
		2=>"绑定元宝",
		3=>"可领取元宝",
		);
	}

	public function getJob(){
		return array(
			"0"=>"猛将",
			"1"=>"术士男",
			"2"=>"术士女",
			"3"=>"天师",
		);
	}

	public function getLingPai(){
		return array(
		0=>"青龙令",
		1=>"朱雀令",
		2=>"白虎令",
		3=>"玄武令",
		);
	}
}