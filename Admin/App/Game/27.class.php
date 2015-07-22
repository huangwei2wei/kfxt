<?php
Tools::import('Game_GameBase');
class Game_27 extends Game_GameBase{

	public $_key	=	"zqdh_087729312bb404ab15493f_cndw";
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 27;		//游戏Id
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
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
		),
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
		),
		);
	}
}