<?php
Tools::import('Game_GameBase');
class Game_23 extends Game_GameBase{

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 23;		//游戏Id
		$this->_zp = 'GongFu';	//控制器扩展包
		$this->_key = 'SIb3DQEBAQUAA4GNADCBiQKB';	//游戏密匙
		//c970ab23-abac-49d0-9976-03d1cd649d47
		$this->_isSendOrderReplay = true;
		$this->_userTypeArr = array(1=>'玩家Id',2=>'玩家账号',3=>'玩家昵称');
	}

	public function workOrderIfChk(){
		if($_REQUEST['source']==1){
			return $this->commonChk();
		}
		return $this->clientTimeChk($this->_key);//特殊处理，周末更新之后换回来
	}

	public function sendOrderReplay($data=NULL){
		if($data['status']==3){
			$title		=	'您的提问已回复';
			$content	=	"您的提问已经答复<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
		}else{
			$title		=	'您的提问正在处理中';
			$content	=	"您的提问正在处理中！<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
		}
		/*$api	=	$this->_getGlobalData('Util_HttpInterface','object');
		$getData = $this->getGetData(array('action'=>'send'),$data["server_id"]);
		$postData['users'] = $data['game_user_id'];
		$postData['userType'] = 1;//1:玩家id,2:表示用户帐号,3:表示角色名
		$postData['title'] = $title;
		$postData['content'] = $content;
		$dataReturn = $api->result($data["send_url"],'mail',$getData,$postData);*/
		
		$postData['users'] = $data['game_user_id'];
		$postData['userType'] = 1;//1:玩家id,2:表示用户帐号,3:表示角色名
		$postData['title'] = base64_encode($title);
		$postData['content'] = base64_encode($content);
		$this->result($data["send_url"],$postData,"60006");
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

	public function getSign($cmd,$data){
		$len 	=	strlen($data)+8+32+5;
		if(strlen($len)<8){
			$i = 8-strlen($len);
			for($a=0;$i>$a;$a++){
				$len = "0".$len;
			}
		}
		$sign = $len.md5($len.$data.$this->_key).$cmd.$data;
		return $sign;
	}

	public function result($url,$data=array(),$cmd){
		$urlArr = explode(":",$url);
		$data 	=	json_encode($data);
		$tcp = $this->_getGlobalData('Util_TcpInterfack','object');
		$data	=	$this->getSign($cmd,$data);
		
		if( false !== strpos($url,'qqopenapp') ){
			//腾讯的服需要加这个头
			$data = "tgw_l7_forward\r\nHost: {$url}\r\n\r\n<policy-file-request/>".$data;
		}
		
//				echo $data;
//				die();
		return $tcp->result($urlArr[0],$urlArr[1],$data);
	}
	
	//result函数不改,这个函数只设置了一下超时时间
	public function SetTimeOutresult($url,$data=array(),$cmd, $timeout=15){
		$urlArr = explode(":",$url);
		$data 	=	json_encode($data);
		$tcp = $this->_getGlobalData('Util_TcpInterfack','object');
		$tcp->_timeout = $timeout;
		$data	=	$this->getSign($cmd,$data);
		if( false !== strpos($urlArr[0],'qqopenapp') ){
			//腾讯的服需要加这个头
			$data = "tgw_l7_forward\r\nHost: {$url}\r\n\r\n<policy-file-request/>".$data;
		}
		return $tcp->result($urlArr[0],$urlArr[1],$data);
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
		$data 	= $this->result($sendUrl,"","60017");
		$data	=	json_decode($data,true);
		foreach($data["announcement_list"] as &$item){
			if($item["is_delete"]==1){
				$item=null;

			}
		}
		return $data;
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
		$senddata["announcement_info"]	=	$data;
		$senddata["type"]				=	intval(3);
		$data 	= $this->result($sendUrl,$senddata,"60019");
		$data	=	json_decode($data,true);
		if($data['status']==1){
			return true;
		}else{
			return false;
		}
	}

	public function editNotice($data=array()){
		$url	=	$data["url"];
		$cmd	=	$data["cmd"];
		unset($data["url"]);
		unset($data["cmd"]);
		$senddata["announcement_info"]	=	$data;
		$senddata["type"]				=	intval(2);
		$data 	= $this->result($url,$senddata,"60019");
		$data	=	json_decode($data,true);
		if($data['status']==1){
			return true;
		}else{
			return false;
		}
	}

	public function addNotice($data=array()){
		$url	=	$data["url"];
		$cmd	=	$data["cmd"];
		unset($data["url"]);
		unset($data["cmd"]);
		$senddata["announcement_info"]	=	$data;
		$senddata["type"]				=	intval(1);
		$data 	= $this->result($url,$senddata,"60019");
		$data	=	json_decode($data,true);
		if($data['status']==1){
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

	public function ApplySend($data,$server_id,$UrlAppend){
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($server_id && is_array($this->_serverList[$server_id])){
			$sendUrl	= $this->_serverList[$server_id]['server_url'];
		}
		if($UrlAppend == '60050' && $this->_gameId== 23 ){
			//特殊处理
			$str = $this->sendComItemCard_Apply($data);
		} else {
			$result = $this->result($sendUrl,$data,$UrlAppend);
			$result = json_decode($result,true);
			if($result["status"]==1){
				$str = "审核成功";
			}else{
				$str = "审核失败：".$result["error"];
			}
		}
		
		return  $str;
	}

	public function sendComItemCard_Apply($data){
		
		set_time_limit(100);
		$serverids = $data['serverids'];
		$ItemCarType = intval($data['card_type']);
		
		$serverList=$this->_getGlobalData('gameser_list');

		$sendStatusMsgs = '';
		foreach ($serverids as $serverid){
			if(!isset($serverList[$serverid])){
				continue;
			}
			
			$data = $this->SetTimeOutresult($this->_serverList[$serverid]['server_url'],array("card_type"=>$ItemCarType),"60050",5);
			$data = json_decode($data,true);
			if( $data['status'] == 1 ){
				$message = Tools::getLang('OPERATION_SUCCESS','Common');
				$sendStatusMsgs .="<b>{$serverList[$serverid]['full_name']}</b>:<font color='#00CC00'>{$message}</font><br>";
			} else {
				$message = Tools::getLang('OPERATION_FAILURE','Common');
				$sendStatusMsgs .="<b>{$serverList[$serverid]['full_name']}</b>:<font color='##FF0000'>{$message}</font><br>";
			}
		}
		return "审核成功，操作结果<br>".$sendStatusMsgs;
		
	}
	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
		//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
					'PersonalInformation'=>array(//查询并修改个人信息
						'action'=>'Xiayi',
						'UrlAppend'=>'60005',
						'body'=>'',
					),
					'SavePersonalInformation'=>array(//修改个人信息,被调用
						'UrlAppend'=>'60015',
					),
					//'PlayerLookup'=>array(
					//	'action'=>'chunqiu',
					//	'UrlAppend'=>'60005',
					//	'body'=>'',
					//),
					'PlayerLog'=>array(	//玩家日志
						'action'=>'chunqiu',
						'UrlAppend'=>'60021',
						'body'=>'',
		),
		//			'PlayerLogType'=>array(	//玩家日志类型更新
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'log',
		//				'get'=>array('action'=>'getTemplate'),
		//				'body'=>'',
		//		),
					'MailList'=>array(	//邮件列表
						'action'=>'Xiayi',
						'UrlAppend'=>'60030',
					),
					
					'SendMail'=>array(	//发邮件
						'action'=>'chunqiu',
						'UrlAppend'=>'60006',
						'body'=>'',
					),
					'SendUserPackage'=>array(	//发邮件
						'action'=>'chunqiu',
						'UrlAppend'=>'60008',
						'body'=>'',
					),
					'Recharge'=>array(
						'action'=>'Xiayi',
						
					),
					'GameLogin'=>array(	//游戏登录
						'action'=>'chunqiu',
						'UrlAppend'=>'api/api.php',
						'body'=>'',
						'notify'=>'Log_GameLogin',
					),
		//
					'LockAccount'=>array(	//封号
						'action'=>'chunqiu',
						'UrlAppend'=>'60016',
						'body'=>'',
					),
					'LockAccountAdd'=>array(	//添加封号
						'action'=>'chunqiu',
						'UrlAppend'=>'60001',
						'body'=>'',
						'notify'=>'Log_LockAccount',
					),
					'LockAccountDel'=>array(	//删除封号
						'action'=>'chunqiu',
						'UrlAppend'=>'60002',
						'body'=>'',
						'notify'=>'Log_LockAccount',
					),


					'Silence'=>array(	//禁言
						'action'=>'chunqiu',
						'UrlAppend'=>'60011',
						'body'=>'',
					),
					'SilenceAdd'=>array(	//添加禁言
						'action'=>'chunqiu',
						'UrlAppend'=>'60009',
						'notify'=>'Log_Silence',
					),
					'SilenceDel'=>array(	//删除禁言
						'action'=>'chunqiu',
						'UrlAppend'=>'60010',
						'body'=>'',
						'notify'=>'Log_Silence',
					),

		
		//			'MultiLock'=>array(	//多服封号|禁言
		//				'action'=>'Default',
		//		),
		//			'LockIP'=>array(	//封IP列出
		//				'action'=>'Default',
		//				'UrlAppend'=>'restrict',
		//				'get'=>array('action'=>'getFilterIp'),
		//				'body'=>'',
		//		),
		//			'LockIPDone'=>array(	//封IP提交
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'restrict',
		//				'get'=>array('action'=>'filterIp'),
		//				'body'=>'',
		//		),
		//			'GetOperatorServer'=>array(
		//				'action'=>'GetOperatorServer10',
		//				'UrlAppend'=>'server',
		//				'get'=>array('action'=>'get'),
		//				'body'=>'',
		//		),
		//
					'Notice'=>array(	//公告列表
						'action'=>'chunqiu',
						'UrlAppend'=>'60017',
						'body'=>'',
					),

					'NoticeAdd'=>array(	//添加公告
						'action'=>'chunqiu',
						'UrlAppend'=>'60019',
						'body'=>'',
					),

					'NoticeDel'=>array(	//删除公告
						'action'=>'chunqiu',
						'UrlAppend'=>'60019',
						'body'=>'',
					),

					'NoticeEdit'=>array(	//编辑公告
						'action'=>'chunqiu',
						'UrlAppend'=>'60019',
						'body'=>'',
					),
					'BackpackSearch'=>array(	//用户背包查询
						'action'=>'chunqiu',
						'UrlAppend'=>'60014',
						'body'=>'',
					),
					'RechargeRecord'=>array(	//用户背包查询
						'action'=>'chunqiu',
						'UrlAppend'=>'60024',
						'body'=>'',
					),

					'Organize'=>array(	//帮会查询
						'action'=>'chunqiu',
						'UrlAppend'=>'60029',
						'body'=>'',
					),
					'EditOrganize'=>array(	//帮会修改
						'UrlAppend'=>'60049',
					),
					'TradeManage'=>array(	//交易管理-查询
						'action'=>'Xiayi',
						'UrlAppend'=>'60055',
						'body'=>'',
					),
					'EditTrade'=>array(
						'UrlAppend'=>'60054',
					),
		//			'Item'=>array(	//道具更新|获得道具列表
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'goods',
		//				'get'=>array('action'=>'get'),
		//				'body'=>'',
		//		),
		//			'ItemDel'=>array(	//道具删除
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'user',
		//				'get'=>array('action'=>'delGoods'),
		//		),
					'ItemCard'=>array(	//礼包
						'action'=>'chunqiu',
						'UrlAppend'=>'60026',
					),
					'ItemCardApply'=>array(	//礼包申请
						'action'=>'chunqiu',
						'UrlAppend'=>'60025',
					),
					'TaskManage'=>array(	//任务管理
						'action'=>'Xiayi',
						'UrlAppend'=>'60045',
					),
					'ClearCopyNum'=>array(	//清副本次数
						'action'=>'Xiayi',
						'UrlAppend'=>'60037',
					),
					'EditBoneLvel'=>array(	//调根骨等级
						'action'=>'Xiayi',
						'UrlAppend'=>'60036',
					),
					'EditMountAndPet'=>array(	//调整坐骑/宠物信息
						'action'=>'Xiayi',
						'UrlAppend'=>'60038',//(查询宠物的)
					),
					'EditSavePet'=>array(
						'UrlAppend'=>'60039',//修改宠物信息,没有单独的php文件，在EditMountAndPet中调用
					),
					'SearchMount'=>array(
						'UrlAppend'=>'60040',//查询坐骑信息,没有单独的php文件，在EditMountAndPet中调用
					),
					'EditSaveMount'=>array(
						'UrlAppend'=>'60041',//修改坐骑信息,没有单独的php文件，在EditMountAndPet中调用
					),
					'EditSaveMount'=>array(
						'UrlAppend'=>'60041',//修改坐骑信息,没有单独的php文件，在EditMountAndPet中调用
					),
			
					'Equipment'=>array(	//装备查询
						'action'=>'Xiayi',
						'UrlAppend'=>'60043',
					),
					'Equipment_SAVE'=>array(	//修改装备
						'UrlAppend'=>'60044',//没有单独的php文件，在EditMountAndPet中调用Equipment的修改中调用
					),
		//			'ItemCardAppendApply'=>array(	//礼包追加卡密申请
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'appendCard'),
		//		),
		//			'ItemCardShowBatch'=>array(	//查某礼包的所有批次
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'getCardBatch'),
		//		),
		//			'ItemCardList'=>array(	//卡号列表
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'getCardList'),
		//		),
		//			'ItemCardDownLoad'=>array(	//卡号下载
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'downloadCardList'),
		//		),
		//			'ItemCardQuery'=>array(	//道具卡查询
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'select'),
		//		),
		//			'ItemPackageEdit'=>array(	//礼包编辑
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'update'),
		//				'body'=>'ActionGame_MasterTools/ItemCardApply/GongFu.html',	//配置指定模板
		//		),
		//			'ItemReceiveCondition'=>array(	//礼包领取条件
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'gift',
		//				'get'=>array('action'=>'getCondition'),
		//		),
		//			'AllNotice'=>array(	//多服公告管理
		//				'action'=>'Default',
		//		),
			
						'ServerManagement'=>array(	//服务器管理
							'action'=>'Default',
					),
			
		//			'AllNoticeAdd'=>array(	//多服发送
		//				'action'=>'Default',
		//		),
		//			'RechargeRecord'=>array(	//玩家充值记录
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'paySearch',
		//				'get'=>array('action'=>'search'),
		//		),
		//			'PointDel'=>array(	//点数扣除
		//				'action'=>'GongFu',
		//				'UrlAppend'=>'user',
		//				'get'=>array('action'=>'deduction'),
		//				'notify'=>'Log_PointDel',
		//		),
		);
	}
	
	public function applyAction($applyData=array()){
		$applyData = array(
			'type'=>$applyData['type'],//55,	
			'server_id'=>$applyData['serverId'],//$serverId,
			'operator_id'=>$applyData['operator_id'],//$this->_serverList[$serverId]['operator_id'],
			'game_type'=>$applyData['game_type'],//$this->_serverList[$serverId]['game_type_id'],
			'list_type'=>1,
			'apply_info'=>str_replace(array("\r\n","\n",),'',$applyData['cause']),//str_replace(array("\r\n","\n",),'',$cause),
			'send_type'=>1,	//2	http
			'send_data'=>array(
				'url_append'=>$applyData['UrlAppend'],//$UrlAppend,
				'post_data'=>$applyData['postData'],//$postData,
				'get_data'=>$applyData['getData'],//$getData,
				'call'=>array(
					'cal_local_object'=>'Game_'.$this->_gameId,
					'cal_local_method'=>'ApplySend',
					//'params'	=>array('data'=>$postData,"server_id"=>$_REQUEST['server_id'],"UrlAppend"=>$UrlAppend),
					'params'	=>array('data'=>$applyData['postData'],"server_id"=>$applyData['serverId'],"UrlAppend"=>$applyData['UrlAppend']),
			),
			),
			'receiver_object'=>array($serverId=>''),
			'player_type'=>$applyData['userType'],//$postData["userType"],
			'player_info'=>$applyData['user'],//$postData["Users"],
			);

			$modelApply = $this->_getGlobalData('Model_Apply','object');
			if($modelApply->AddApply($applyData)){
				//				die();
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				
				return array(1,$showMsg);
			}
			return array(0,'申请失败');
			
	}
}