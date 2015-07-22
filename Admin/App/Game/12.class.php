<?php
Tools::import('Game_GameBase');
class Game_12 extends Game_GameBase{

	public $_key	=	"0147de556c087729312bb404ab15493f";

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 12;		//游戏Id
		$this->_zp = 'MingXing';	//控制器扩展包
		//$this->_key = '0147de556c087729312bb404ab15493f';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}

	public function workOrderIfChk(){
		return $this->clientTimeChk($this->_key);
	}

	public function sendOrderReplay($data=NULL){
		$postData["title"]	=	"您的提问已回复";
		$postData["content"]	=	"您的提问已回复，请查看<a href='event:Customers_replay'></a>";
		$postData["openIdlist"]	=	$data["game_user_id"];
		$getData["action"]	=	"sendSystemEmail";
		$getData	=	$this->getGetData($getData);
		$_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		$sendUrl=$_serverList[$data["service_id"]]['server_url'];
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		//		$sendUrl = $this->_getUrl(false,$isOperatorId);
		$data = $utilHttpInterface->result($sendUrl,"",array_merge($getData,$postData));
		//		return json_decode($data,true);
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
		//$data['serverId'] = $this->getServerId($serverId);
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
			'PlayerLookup'=>array(
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getUserInfoBy'),
				'body'=>'',
		),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getLogByCategory'),
				'body'=>'',
		),
			'PersonalInformation'=>array(
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getUserAttr'),
				'body'=>'',
		),
			'PlayerPartner'=>array(	//玩家伙伴
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getActorList'),
				'body'=>'',
		),
			'PlayerPartnerInfo'=>array(	//玩家伙伴属性
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getActorAttr'),
				'body'=>'',
		),
			'BackpackSearch'=>array(	//用户道具查询
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getItemList'),
				'body'=>'',
		),
			'Equipment'=>array(	//玩家装备
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getEquipmentList'),
				'body'=>'',
		),
			'EquipmentUpgrade'=>array(	//装备升级申请
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'upgradeEquipment'),
				'body'=>'',
		),
			'EquipmentDel'=>array(	//装备删除申请
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'deleteEquipment'),
				'body'=>'',
		),
		//			'Item'=>array(	//道具更新|获得道具列表
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'goods',
		//				'get'=>array('action'=>'get'),
		//				'body'=>'',
		//			),
			'ItemDel'=>array(	//道具删除
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'deleteItem'),
		),
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
			'RechargeRecord'=>array(	//玩家充值记录
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getPayInfoBy'),
				'body'=>'',
		),
			'GameLogin'=>array(	//游戏登录
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getUserLoginUrl'),
				'body'=>'ActionGame_MasterTools/GameLogin/Default.html',
		//'notify'=>'Log_GameLogin',
		),
			'MailList'=>array(	//玩家邮件列表
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'getEmail'),
				'body'=>'',
		),
			'SendMail'=>array(	//发邮件
				'action'=>'MingXing',
				'UrlAppend'=>'',
				'get'=>array('action'=>'sendSystemEmail'),
		),
			
			'LockAccount'=>array(	//封号
					'action'=>'MingXing',
					'UrlAppend'=>'',
					'get'=>array('action'=>'lockLogin'),
					'body'=>'',
			),
			'LockAccountAdd'=>array(	//添加封号
					'action'=>'MingXing',
					//'UrlAppend'=>'',
					'get'=>array('action'=>'lockLogin'),
					'body'=>'',
					'notify'=>'Log_LockAccount',
			),
			'LockAccountDel'=>array(	//删除封号
					'action'=>'MingXing',
					'UrlAppend'=>'',
					'get'=>array('action'=>'unlockLogin'),
					'body'=>'',
					'notify'=>'Log_LockAccount',
			),
			'Silence'=>array(	//禁言
					'action'=>'MingXing',
					'UrlAppend'=>'',
					'get'=>array('action'=>'lockChat'),
					'body'=>'',
			),
			'SilenceAdd'=>array(	//添加禁言
					'action'=>'MingXing',
					'UrlAppend'=>'',
					'get'=>array('action'=>'lockChat'),
					'body'=>'',
					'notify'=>'Log_Silence',
			),
			'SilenceDel'=>array(	//删除禁言
					'action'=>'MingXing',
					'UrlAppend'=>'',
					'get'=>array('action'=>'unlockChat'),
					'body'=>'',
					'notify'=>'Log_Silence',
			),
			'MultiLock'=>array(	//多服封号|禁言
					'action'=>'MingXing',
					'UrlAppend'=>'',
			),
		);
	}
}