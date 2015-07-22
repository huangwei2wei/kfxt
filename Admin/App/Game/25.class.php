<?php
Tools::import('Game_GameBase');
class Game_25 extends Game_GameBase{

	public $_key	=	"0147de556c0877293bbfbac834585";

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 25;		//游戏Id
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
		json_encode();
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
		return array(
			'timestamp'=>CURRENT_TIME,
			'sign'=>md5(CURRENT_TIME.$this->_key),
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


	public function getServerMarking($serverId=0){
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$marking = $serverList[$serverId]['marking'];
		}
		return $marking;
	}
	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
		return array(
			'PlayerLookup'=>array(
				'action'=>'Default_1_1',
				'UrlAppend'=>'getPlayerList.php',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
		),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'XiYou',
				'UrlAppend'=>'query/getLogList',
				'get'=>array(),
				'body'=>'',
		),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'XiYou',
				'UrlAppend'=>'query/getLogType',
				'get'=>array(),
				'body'=>'',
		),
			'SendMail'=>array(	//发邮件
				'action'=>'Default_1_1',
				'UrlAppend'=>'sendEmail.php',
				'get'=>array(),
				'body'=>'',
		),
		// 			'RechargeRecord'=>array('action'=>'Action10',),
			'GameLogin'=>array(	//游戏登录
				'action'=>'Default_1_1',
				'UrlAppend'=>'loginGame.php',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_GameLogin',
		),
			
			'TitleOrGag'=>array(	//封号禁言
				'action'=>'Default_1_1',
				'UrlAppend'=>'getPlayerRestictList.php',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
		),
			'AddTitleOrGag'=>array(	//添加封号禁言
				'action'=>'Default_1_1',
				'UrlAppend'=>'addPlayerRestict.php',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
				'notify'=>'Log_TitleOrGag',
		),
			'DelTitleOrGag'=>array(	//删除封号禁言
				'action'=>'Default_1_1',
				'UrlAppend'=>'delPlayerRestict.php',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
				'notify'=>'Log_TitleOrGag',
		),
			
			
			'Notice'=>array(	//公告列表
				'action'=>'Default_1_1',
				'UrlAppend'=>'getNoticeList.php',
				'get'=>array(),
				'body'=>'',
		),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'Default_1_1',
				'UrlAppend'=>'addNotice.php',
				'get'=>array(),
				'body'=>'',
		),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'Default_1_1',
				'UrlAppend'=>'delNotice.php',
				'get'=>array(),
				'body'=>'',
		),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'Default_1_1',
				'UrlAppend'=>'updateNotice.php',
				'get'=>array(),
				'body'=>'',
		),

			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'Default_1_1',
				'UrlAppend'=>'getBackpackGoodsList.php',
				'get'=>array(),
				'body'=>'',
			),
			'BackpackUpdate'=>array(	//用户背包更新
				'action'=>'Default_1_1',
				'UrlAppend'=>'updateBackpackGoods.php',
				'get'=>array(),
				'body'=>'',
			),
			
			'Item'=>array(	//道具更新|获得道具列表
				'action'=>'Default_1_1',
				'UrlAppend'=>'getGoodsList.php',
				'get'=>array(),
				'body'=>'',
			),
			'ItemDel'=>array(	//道具删除
				'action'=>'XiYou',
				'UrlAppend'=>'player/updateBackpackGoods',
				'get'=>array(),
				'notify'=>'Log_Silence',
		),
			
			
			'ItemCard'=>array(	//礼包
				'action'=>'Default_1_1',
				'UrlAppend'=>'getCardList.php',
				'get'=>array(),
		),
			'ItemCardApply'=>array(	//礼包申请
				'action'=>'Default_1_1',
				'UrlAppend'=>'sendGoods.php',
				'get'=>array(),
		),
				'ItemCardMotion'=>array(	//礼包运营用
					'action'=>'Default_1_1',
					'UrlAppend'=>'getCardList.php',
					'get'=>array(),
				),
				'ItemCardApplyMotion'=>array(	//礼包申请运营用
					'action'=>'Default_1_1',
					'UrlAppend'=>'sendGoods.php',
					'get'=>array(),
				),
		//			'FunOnOrOff'=>array(	//玩家登陆情况
		//					'action'=>'XiYou',
		//					'UrlAppend'=>'server/getFunction',
		//					'get'=>array(),
		//			),
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
			
		);
	}
}