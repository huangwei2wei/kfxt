<?php
Tools::import('Game_GameBase');
class Game_30 extends Game_GameBase{

	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 30;		//游戏Id
		//$this->_zp = 'StarDream';	//控制器扩展包
		$this->_key = 'dg48sdf-2sdf3-4sdf3b-9a-ads2423*&#^*5';	//游戏密匙(游戏前端发送请求时判断有用)
		$this->_isSendOrderReplay = true;//客服回复时是否回复邮件
	}

	public function workOrderIfChk(){
		return $this->clientTimeChk();
	}

	public function sendOrderReplay($data=NULL){
		return true;
		$PostData["content"]    =	base64_encode( "你的提问已回复，<a href='event:Customers_replay'><u>请查看。</u></a>" );
		$PostData["title"]		=	base64_encode( "您的提问已回复" );
		$PostData["userType"]   =	0;
		$PostData["user"]		=	$data["game_user_id"];
		$PostData["server_id"] =   $data["server_id"];
		$getData = $this->getGetData();
		$data = $this->result('SendMail',$getData,$PostData);
		if($data['status']==1){
			return true;
		} else {
			return $data['info'];
		}
		
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
			'sign'=>md5(CURRENT_TIME.$this->_key),
		);
	}
/*
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
*/
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
	
	public function result($ActionName='',$getData=null,$postData=null){
		//$data = $utilHttpInterface->result($sendUrl,$ifConf['PlayerLookup']['UrlAppend'],$getData,$postData);
		
		$getIfConf = $this->getIfConf();
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		
		if($_REQUEST['server_id']){
			$Id = 	$_REQUEST['server_id'];
		}else{
			$Id	=	isset($getData['server_id'])?$getData['server_id']:$postData['server_id'];
		}
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($Id && is_array($this->_serverList[$Id])){
			$sendUrl	= $this->_serverList[$Id]['server_url'];
		}
		if(!$sendUrl || !isset($getIfConf[$ActionName]) ) return array('status'=>0,'info'=>'gm后台配置有误');
		
		if( is_null( $getData) ){
			$getData = $this->getGetData();
		}
		
		$UrlAppend = $getIfConf[$ActionName]['UrlAppend'];
		$data = $utilHttpInterface->result($sendUrl,$UrlAppend,$getData,$postData);
		return json_decode($data,true);
	}
	
	public function ApplySend($data,$server_id,$UrlAppend){
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($server_id && is_array($this->_serverList[$server_id])){
			$sendUrl	= $this->_serverList[$server_id]['server_url'];
		}
		$getData = $this->getGetData();
		$result = $this->result($UrlAppend,$getData,$data);
		if($result["status"]==1){
				$str = "审核成功";
		}else{
			$str = "审核失败：".$result["info"];
		}
		
		return  $str;
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
/*
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

	public function ApplySend($data,$server_id,$UrlAppend){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$this->_serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
		if($server_id && is_array($this->_serverList[$server_id])){
			$sendUrl	= $this->_serverList[$server_id]['server_url'];
		}
		$getData = $this->getGetData();
		$result = $utilHttpInterface->result($sendUrl,$UrlAppend,$getData,$data);
		$result = json_decode($result,true);
		if($result["states"]==1){	
			if( isset($data['SendMail']) ){//该功能实现的同时还要发给玩家相应的邮件信息
				$result = $utilHttpInterface->result($sendUrl,$data['SendMail']['UrlAppend'],$data['SendMail']['getData'],$data['SendMail']['data']);
				$result = json_decode($result,true);
				if($result['states'] == 1){
					$str = "审核成功";
				} else {
					$str = "审核成功,但相应的邮件发送功能失败";
				}
			} else {
				$str = "审核成功";
			}
		}else{
			$str = "审核失败：";
		}
		return  $str;
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
*/
	/**
	 * 获取玩家的信息 
	 *//*
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
*/

	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
		return array(
			'PlayerLookup'=>array(//用户查询
				'action'=>'StarDream',
				'UrlAppend'=>'?action=getPlayerList',
				'body'=>'',
			),
			'PlayerLog'=>array(//玩家操作日志
				'action'=>'StarDream',
				'UrlAppend'=>'?action=getLogList',
			),
			'PlayerLogType'=>array(
				'UrlAppend'=>'?action=getLogType',
			),
			
			'Silence'=>array(//禁言管理
				'action'=>'StarDream',
				'UrlAppend'=>'?action=getPlayerRestictList',
			),
			'SilenceAdd'=>array(//禁言添加
				'action'=>'StarDream',
				'UrlAppend'=>'?action=addPlayerRestict',
				'notify'=>'Log_Silence',
			),
			'SilenceDel'=>array(//禁言删除
				'action'=>'StarDream',
				'UrlAppend'=>'?action=delPlayerRestict',
				'notify'=>'Log_Silence',
			),
			
			'LockAccount'=>array(//封号管理
				'action'=>'StarDream',
				'UrlAppend'=>'?action=getPlayerRestictList',
			),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'StarDream',
				'UrlAppend'=>'?action=addPlayerRestict',
				'notify'=>'Log_LockAccount',
			),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'StarDream',
				'UrlAppend'=>'?action=delPlayerRestict',
				'notify'=>'Log_LockAccount',
			),
		
			
			'SendMail'=>array(//邮件发送
				'action'=>'StarDream',
				'UrlAppend'=>'?action=sendEmail',
				'body'=>'',
			),
			
			/*
			'MailList'=>array(//邮件列表
				'action'=>'StarDream',
				'UrlAppend'=>'',
				'body'=>'',
			),
			'BackpackSearch'=>array(//背包物品查询/扣除
				'action'=>'StarDream',
				'UrlAppend'=>'',
				'body'=>'',
			),*/
			'PackageList'=>array(
				'UrlAppend'=>'?action=getBackpackGoodsList',
			),
			'SendUserPackage'=>array(//道具发放
				'action'=>'StarDream',
				'UrlAppend'=>'?action=sendGoods',
				'body'=>'',
			),
			'DelUserPackage'=>array(//道具删除
				'action'=>'StarDream',
				'UrlAppend'=>'?action=updateBackpackGoods',
				'body'=>'',
			),
			'UserPackageList'=>array(//道具列表
				'UrlAppend'=>'?action=getGoodsList',
				'body'=>'',
			),
			
			'GameLogin'=>array(//登录玩家账号
				'action'=>'StarDream',
				'UrlAppend'=>'?action=loginGame',
				'body'=>'',
			),
			'RechargeRecord'=>array(//充值查询
				'action'=>'StarDream',
				'UrlAppend'=>'?action=queryRecharge',
				'body'=>'',
			),
		
			'Notice'=>array(//公告
				'action'=>'StarDream',
				'UrlAppend'=>'?action=getNoticeList',
			),
			'NoticeEdit'=>array(//编辑公告
				'action'=>'StarDream',
				'UrlAppend'=>'?action=updateNotice',
			),
			'NoticeDel'=>array(//删除公告
				'action'=>'StarDream',
				'UrlAppend'=>'?action=delNotice',
			),
			'NoticeAdd'=>array(//单服公告发送
				'action'=>'StarDream',
				'UrlAppend'=>'?action=addNotice',
			),
			'AllNotice'=>array(//全服公告
				'action'=>'StarDream',
				'UrlAppend'=>'',
			),
			'AllNoticeAdd'=>array(//全服公告发送
				'action'=>'StarDream',
				'UrlAppend'=>'',
			),
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
			),
			'getGoodsList'=>array(	//服务器管理
				'UrlAppend'=>'?action=getGoodsList',
			),
			/*
			'Silence'=>array(	//禁言
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_ban_account_api.php?act=list',
				'body'=>'',

			),
			'SilenceAdd'=>array(	//添加禁言
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_ban_account_api.php?act=ban',
				'notify'=>'Log_Silence',
			),
			'SilenceDel'=>array(	//删除禁言
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_ban_account_api.php?act=unban',
				'body'=>'',
				'notify'=>'Log_Silence',
			),
			'PlayerLookup'=>array(
				'action'=>'Default_1',
				'UrlAppend'=>'FTplayerDate.php',
				'body'=>'',
			),
			'MailList'=>array(	//
				'action'=>'Default_1',
				'UrlAppend'=>'FTplayermail.php',
				'body'=>'',
			),
			'LockIP'=>array(	//封IP列出
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_ip_api.php?act=list',
				'body'=>'',
			),
			'LockIPDone'=>array(	//封IP提交
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_ip_api.php?act=forb',
				'body'=>'',
			),
			'LockIPDel'=>array(	//封IP提交
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_ip_api.php?act=unforb',
				'body'=>'',
			),
			'GameLogin'=>array(	//游戏登录
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_login_account_api.php',
		),

			'LockAccount'=>array(	//封号
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_account_api.php?act=list',
				'body'=>'',
		),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_account_api.php?act=forb',
				'notify'=>'Log_LockAccount',
		),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_forb_account_api.php?act=unforb',
				'body'=>'',
				'notify'=>'Log_LockAccount',
		),
			'SendMail'=>array(	//发送邮件
				'action'=>'Default_1',
				'UrlAppend'=>'FTsendmail.php',
				'body'=>'',
		),
			'Notice'=>array(	//公告列表
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_send_notice_api.php?act=list',
				'body'=>'',
		),
			'NoticeAdd'=>array(	//添加公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_send_notice_api.php?act=add',
				'body'=>'',
		),

			'NoticeDel'=>array(	//删除公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_send_notice_api.php?act=del',
				'body'=>'',
		),
			'NoticeEdit'=>array(	//删除公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_send_notice_api.php?act=edit',
				'body'=>'',
		),
			'SendUserPackage'=>array(	//删除公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_props_relate_api.php?act=edit',
				'body'=>'',
		),
			'Item'=>array(	//删除公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_props_relate_api.php?act=list',
				'body'=>'',
		),
			'BackpackSearch'=>array(	//删除公告
				'action'=>'Default_1',
				'UrlAppend'=>'Ft_props_relate_api.php?act=show',
				'body'=>'',
		),
			'SysLog'=>array(
				'action'=>'Default',
				'UrlAppend'=>'',
				'body'=>'',
		),
			'PlayerLog'=>array(
				'action'=>'Default_1',
				'UrlAppend'=>'player_action_log.php?act=getLogList',
				'body'=>'',
		),*/
		);
	}
}