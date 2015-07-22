<?php
Tools::import('Game_GameBase');
class Game_10 extends Game_GameBase{

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 10;		//游戏Id
		$this->_zp = 'GongFu';	//控制器扩展包
		$this->_key = 'ec4826d5-2332-473b-942a-bbfbac834585';	//游戏密匙
		//c970ab23-abac-49d0-9976-03d1cd649d47
		$this->_isSendOrderReplay = false;
	}

	public function workOrderIfChk(){
		if($_REQUEST['source']==1){
			return $this->commonChk();
		}
		return $this->clientTimeChk('c970ab23-abac-49d0-9976-03d1cd649d47');//特殊处理，周末更新之后换回来
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
		$actionName = $getData['action'];
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
		$data['serverId'] = $this->getServerId($serverId);
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
				'action'=>'GongFu',
				'UrlAppend'=>'user',
				'get'=>array('action'=>'getPlayers'),
				'body'=>'',
		),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'GongFu',
				'UrlAppend'=>'log',
				'get'=>array('action'=>'getLogs'),
				'body'=>'',
		),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'GongFu',
				'UrlAppend'=>'log',
				'get'=>array('action'=>'getTemplate'),
				'body'=>'',
		),
			'SendMail'=>array(	//发邮件
				'action'=>'Default',
				'UrlAppend'=>'mail',
				'get'=>array('action'=>'send'),
				'body'=>'',
		),
			'RechargeRecord'=>array('action'=>'Action10',),
			'GameLogin'=>array(	//游戏登录
				'action'=>'GongFu',
				'UrlAppend'=>'gmlogin',
				'get'=>array('action'=>'login'),
				'body'=>'',
				'notify'=>'Log_GameLogin',
		),
			
			'LockAccount'=>array(	//封号
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'getPlayers'),
				'body'=>'',
		),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'setPlayer'),
				'body'=>'',
				'notify'=>'Log_LockAccount',
		),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'deletePlayer'),
				'body'=>'',
				'notify'=>'Log_LockAccount',
		),
			
			
			'Silence'=>array(	//禁言
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'getChats'),
				'body'=>'',
		),
			'SilenceAdd'=>array(	//添加禁言
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'setChat'),
				'body'=>'',
				'notify'=>'Log_Silence',
		),
			'SilenceDel'=>array(	//删除禁言
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'deleteChat'),
				'body'=>'',
				'notify'=>'Log_Silence',
		),
			'MultiLock'=>array(	//多服封号|禁言
				'action'=>'Default',
		),
			'LockIP'=>array(	//封IP列出
				'action'=>'Default',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'getFilterIp'),
				'body'=>'',
		),
			'LockIPDone'=>array(	//封IP提交
				'action'=>'GongFu',
				'UrlAppend'=>'restrict',
				'get'=>array('action'=>'filterIp'),
				'body'=>'',
		),
			'GetOperatorServer'=>array(
				'action'=>'GetOperatorServer10',
				'UrlAppend'=>'server',
				'get'=>array('action'=>'get'),
				'body'=>'',
		),
			
			'Notice'=>array(	//公告列表
				'action'=>'GongFu',
				'UrlAppend'=>'notice',
				'get'=>array('action'=>'get'),
				'body'=>'',
		),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'GongFu',
				'UrlAppend'=>'notice',
				'get'=>array('action'=>'publish'),
				'body'=>'',
		),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'GongFu',
				'UrlAppend'=>'notice',
				'get'=>array('action'=>'delete'),
				'body'=>'',
		),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'GongFu',
				'UrlAppend'=>'notice',
				'get'=>array('action'=>'update'),
				'body'=>'',
		),
			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'GongFu',
				'UrlAppend'=>'user',
				'get'=>array('action'=>'getBox'),
				'body'=>'',
		),
			'Item'=>array(	//道具更新|获得道具列表
				'action'=>'GongFu',
				'UrlAppend'=>'goods',
				'get'=>array('action'=>'get'),
				'body'=>'',
		),
			'ItemDel'=>array(	//道具删除
				'action'=>'GongFu',
				'UrlAppend'=>'user',
				'get'=>array('action'=>'delGoods'),
		),
			'ItemCard'=>array(	//礼包
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'getCardClassList'),
		),
			'ItemCardApply'=>array(	//礼包申请
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'create'),
		),
			'ItemCardAppendApply'=>array(	//礼包追加卡密申请
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'appendCard'),
		),
			'ItemCardShowBatch'=>array(	//查某礼包的所有批次
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'getCardBatch'),
		),
			'ItemCardList'=>array(	//卡号列表
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'getCardList'),
		),
			'ItemCardDownLoad'=>array(	//卡号下载
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'downloadCardList'),
		),
			'ItemCardQuery'=>array(	//道具卡查询
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'select'),
		),
			'ItemPackageEdit'=>array(	//礼包编辑
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'update'),
				'body'=>'ActionGame_MasterTools/ItemCardApply/GongFu.html',	//配置指定模板
		),
			'ItemReceiveCondition'=>array(	//礼包领取条件
				'action'=>'GongFu',
				'UrlAppend'=>'gift',
				'get'=>array('action'=>'getCondition'),
		),
			'AllNotice'=>array(	//多服公告管理
				'action'=>'Default',
		),
			
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
			
			'AllNoticeAdd'=>array(	//多服发送
				'action'=>'Default',
		),
			'RechargeRecord'=>array(	//玩家充值记录
				'action'=>'GongFu',
				'UrlAppend'=>'paySearch',
				'get'=>array('action'=>'search'),
		),
			'PointDel'=>array(	//点数扣除
				'action'=>'GongFu',
				'UrlAppend'=>'user',
				'get'=>array('action'=>'deduction'),
				'notify'=>'Log_PointDel',
		),
		);
	}
}