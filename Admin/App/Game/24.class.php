<?php
Tools::import('Game_GameBase');
class Game_24 extends Game_GameBase{

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 24;		//游戏Id
		$this->_zp = 'WangZhe';	//控制器扩展包
		$this->_key = 'ec4826d5-2332-473b-942a-bbfbac291518452';	//游戏密匙
		//c970ab23-abac-49d0-9976-03d1cd649d47
		$this->_isSendOrderReplay = false;
	}

	public function workOrderIfChk(){
		if($_REQUEST['source']==1){
			return $this->commonChk();
		}
		return $this->clientTimeChk();//特殊处理，周末更新之后换回来
	}

	public function sendOrderReplay($data=NULL){
		if($data['status']==3){
			$title		=	'您的提问已回复';
			$content	=	"你的提问已经答复<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
		}else{
			$title		=	'您的提问正在处理中';
			$content	=	"您的提问正在处理中！<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
		}
		$api	=	$this->_getGlobalData('Util_HttpInterface','object');
		$getData = $this->getGetData(array('action'=>'send'),$data["server_id"]);
		$postData['users'] = $data['game_user_id'];
		$postData['userType'] = 1;//1:玩家id,2:表示用户帐号,3:表示角色名
		$postData['title'] = $title;
		$postData['content'] = $content;
		$dataReturn = $api->result($data["send_url"],'mail',$getData,$postData);
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
		if(isset($getData['UrlAppend'])){
			$actionName = $getData['UrlAppend'];
		}else{
			$ifConf = $this->getIfConf();
			$actionName = $ifConf[ACTION]['UrlAppend'];
		}
		return array(
			'timestamp'=>CURRENT_TIME,
			'sign'=>md5($actionName.CURRENT_TIME.$this->_key),
		);
	}

	public function getServerId($serverId=0){
		$returnServerId = 'S';
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$returnServerId = intval($serverList[$serverId]['ordinal']);
		}
		return $returnServerId;
	}

	/**
	 * 获得GET基本数据
	 * @param $get
	 */
	public function getGetData($get = array(),$serverId=0){
		$data = $this->getSignArr($get);
		$data['serviceId'] = $this->getServerId($serverId);
		if($get && is_array($get)){
			$data = array_merge($get,$data);
		}
		return $data;
	}

	public function getApplyId($mark = ''){
		$mark = trim($mark);
		if(empty($mark)){
			return false;
		}
		$applyInfo = array(
			'ItemCardApply'=>46,
		);
		return $applyInfo[$mark];
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
		$data = $utilHttpInterface->result($sendUrl,"noticeList",$this->getGetData(),NULL);
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
		if($data['server_id']!=""){
			$Id	=	$data['server_id'];
		}else{
			$Id = 	$_REQUEST['server_id'];
		}
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($Id && is_array($this->_serverList[$Id])){
			$sendUrl	= $this->_serverList[$Id]['server_url'];
			$serverId	=	"S".$this->_serverList[$Id]['ordinal'];
		}
		if(!$sendUrl)return false;
		$arr	=	$this->getGetData(array("action"=>"delete","id"=>$data['ids'],"serverId"=>$serverId));
		$arr["serverId"]=$serverId;
		$data = $utilHttpInterface->result($sendUrl,"notice",$arr,NULL);
		return json_decode($data,true);
	}

	public function addNotice($data=array()){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if($data['server_id']!=""){
			$Id	=	$data['server_id'];
		}else{
			$Id = 	$_REQUEST['server_id'];
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
		//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
			'PlayerLookup'=>array(
				'action'=>'WangZhe',
				'UrlAppend'=>'showPlayers',
				'get'=>array(),
				'body'=>'',
		),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'WangZhe',
				'UrlAppend'=>'showOperationLog',
				'get'=>array(),
				'body'=>'',
		),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'WangZhe',
				'UrlAppend'=>'getOperationTypes',
				'get'=>array(),
				'body'=>'',
		),
			'MailList'=>array(	//指定玩家获取邮件列表
				'action'=>'WangZhe',
				'UrlAppend'=>'showMails',
				'get'=>array(),
				'body'=>'',
		),
			'SendMail'=>array(	//发邮件
				'action'=>'WangZhe',
				'UrlAppend'=>'sendMails',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/SendMail/Default.html',
		),
			
			'GameLogin'=>array(	//游戏登录
				'action'=>'WangZhe',
				'UrlAppend'=>'landPlayer',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/GameLogin/HuanJL.html',
				'notify'=>'Log_GameLogin',
		),
			
			'LockAccount'=>array(	//封号
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidPlayerList',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/LockAccount/HuanJL.html',
		),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidPlayer',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/LockAccountAdd/HuanJL.html',
				'notify'=>'Log_LockAccount',
		),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidPlayer',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_LockAccount',
		),
		'ApplyCard'=>array(	//道具申请
				'action'=>'WangZhe',
				'UrlAppend'=>'sendProps',
				'get'=>array(),
				'body'=>'',
		),
		'ApplyIngot'=>array(	//元宝申请
				'action'=>'WangZhe',
				'UrlAppend'=>'sendIngot',
				'get'=>array(),
				'body'=>'',
		),
			'Silence'=>array(	//禁言
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidSpeakPlayerList',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/Silence/HuanJL.html',
		),
			'SilenceAdd'=>array(	//添加禁言
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidSpeakPlayer',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/SilenceAdd/HuanJL.html',
				'notify'=>'Log_Silence',
		),
			'SilenceDel'=>array(	//删除禁言
				'action'=>'WangZhe',
				'UrlAppend'=>'forbidSpeakPlayer',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_Silence',
		),
		'ServerState'=>array(	//服务器在线状态
				'action'=>'WangZhe',
				'UrlAppend'=>'dataCenter',
				'get'=>array(),
				'body'=>'',
		),
		'OnLine'=>array(	//在线用户
				'action'=>'WangZhe',
				'UrlAppend'=>'getOnlineSession',
				'get'=>array(),
				'body'=>'',
		),
		// 			'MultiLock'=>array(	//多服封号|禁言
		// 				'action'=>'Default',
		// 		),
		// 			'LockIP'=>array(	//封IP列出
		// 				'action'=>'Default',
		// 				'UrlAppend'=>'restrict',
		// 				'get'=>array('action'=>'getFilterIp'),
		// 				'body'=>'',
		// 		),
		// 			'LockIPDone'=>array(	//封IP提交
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'restrict',
		// 				'get'=>array('action'=>'filterIp'),
		// 				'body'=>'',
		// 		),
		// 			'GetOperatorServer'=>array(
		// 				'action'=>'GetOperatorServer10',
		// 				'UrlAppend'=>'server',
		// 				'get'=>array('action'=>'get'),
		// 				'body'=>'',
		// 		),
		'Notice'=>array(	//公告列表
				'action'=>'WangZhe',
				'UrlAppend'=>'noticeList',
				'get'=>array(),
				'body'=>'',
		),
		'NoticeAdd'=>array(	//添加公告
				'action'=>'WangZhe',
				'UrlAppend'=>'sendNotice',
				'get'=>array(),
				'body'=>'',
		),
		'NoticeDel'=>array(	//删除公告
				'action'=>'WangZhe',
				'UrlAppend'=>'cancelNotice',
				'get'=>array(),
				'body'=>'',
		),
			
		'NoticeEdit'=>array(	//编辑公告
				'action'=>'WangZhe',
				'UrlAppend'=>'sendNotice',
				'get'=>array(),
				'body'=>'',
		),
			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'WangZhe',
				'UrlAppend'=>'showPlayerPorps',
				'get'=>array(),
				'body'=>'',
		),
		// 			'Item'=>array(	//道具更新|获得道具列表
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'goods',
		// 				'get'=>array('action'=>'get'),
		// 				'body'=>'',
		// 		),
			'ItemDel'=>array(	//道具删除
				'action'=>'WangZhe',
				'UrlAppend'=>'modifyPlayerPorps',
				'get'=>array(),
		),
		// 			'ItemCard'=>array(	//礼包
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'getCardClassList'),
		// 		),
		// 			'ItemCardApply'=>array(	//礼包申请
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'create'),
		// 		),
		// 			'ItemCardAppendApply'=>array(	//礼包追加卡密申请
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'appendCard'),
		// 		),
		// 			'ItemCardShowBatch'=>array(	//查某礼包的所有批次
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'getCardBatch'),
		// 		),
		// 			'ItemCardList'=>array(	//卡号列表
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'getCardList'),
		// 		),
		// 			'ItemCardDownLoad'=>array(	//卡号下载
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'downloadCardList'),
		// 		),
		// 			'ItemCardQuery'=>array(	//道具卡查询
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'select'),
		// 		),
		// 			'ItemPackageEdit'=>array(	//礼包编辑
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'update'),
		// 				'body'=>'ActionGame_MasterTools/ItemCardApply/GongFu.html',	//配置指定模板
		// 		),
		// 			'ItemReceiveCondition'=>array(	//礼包领取条件
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'gift',
		// 				'get'=>array('action'=>'getCondition'),
		// 		),
		// 			'AllNotice'=>array(	//多服公告管理
		// 				'action'=>'Default',
		// 		),
			
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
		// 			'AllNoticeAdd'=>array(	//多服发送
		// 				'action'=>'Default',
		// 		),
		// 			'RechargeRecord'=>array(	//玩家充值记录
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'paySearch',
		// 				'get'=>array('action'=>'search'),
		// 		),
		// 			'PointDel'=>array(	//点数扣除
		// 				'action'=>'GongFu',
		// 				'UrlAppend'=>'user',
		// 				'get'=>array('action'=>'deduction'),
		// 				'notify'=>'Log_PointDel',
		// 		),
		
		);
	}
}