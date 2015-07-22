<?php
Tools::import('Game_GameBase');
class Game_33 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	private  $_faqKey = 'dotoe23&^$(&HJ%dwie^&%$';
	public function _init(){
		$this->_gameId = 33;		//游戏Id
		$this->_zp = 'nuFengZhanChui';	//控制器扩展包
		$this->_key = 'e33&^$)(&Hj%dwi^&%$';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
		return $this->clientTimeChk($this->_faqKey);
// 		if($_REQUEST['source']==1){
// 			return $this->commonChk();
// 		}
// 		return $this->clientTimeChk('c970ab23-abac-49d0-9976-03d1cd649d47');//特殊处理，周末更新之后换回来
	}
	
	public function sendOrderReplay($data=NULL){
		if($this->_isSendOrderReplay){
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
		}
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
	
	public function getSignArr($data = array()){
		$sendData = array(
			'timestamp'=>CURRENT_TIME,
			'sign'=>md5(CURRENT_TIME.$this->_key),
		);
		if(is_array($data)){
			$sendData = array_merge($sendData,$data);
		}
		return $sendData;
	}
	public function getResult($urlAppend,$sendData,$serverId = 0,$url = ''){
		$sendData = $this->getSendData($urlAppend,$sendData);
		if($url == ''){
			$url = $this->getServerProperty($serverId,'server_url');
		}
		$urlInfo = parse_url($url);
		$host = $urlInfo['host']?$urlInfo['host']:$urlInfo['path'];
		$port = $urlInfo['port'];
		$socketInterface = $this->_getGlobalData('Util_SocketInterface','object');
		$result = $socketInterface->result($host,$port,$sendData,null,null,20,12+2);
		$result = trim($result);
// 		echo $result;
		if(is_numeric($result) && $result<0){
			switch ($result){
				case -1:
					return array('status'=>0,'info'=>'链接游戏服务器失败,地址：'.$url);
					break;
				case -2:
					return array('status'=>0,'info'=>'向游戏服务器写数据失败,地址：'.$url);
					break;
				case -3:
					return array('status'=>0,'info'=>'读取游戏服务器数据超时,地址：'.$url);
					break;
				default:
					return $result;
			}
		}else{
			return json_decode($result,true);
		}
	}
	public function getSendData($urlAppend,$sendData){
		$jsondata =  json_encode($sendData);
		$string = pack('A*',$jsondata);
		$body = pack('S',strlen($string)).$string;

		$totleLen = 12+strlen($body);
		$header = pack('SSSSSS', $totleLen,$urlAppend,null,null,null,null);
		$msg = $header.$body;
		return $msg;
	}
	public function getServerProperty($serverId = 0,$property){
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$property = $serverList[$serverId][$property];
		}
		return $property;
	}
	
	/**
	 * 获得GET基本数据
	 * @param $get
	 */
	public function getGetData($get = array()){
		$gameInfo = $this->getIfConf();
		$getData = $gameInfo[ACTION]['get'];
		if($get){
			$getData = array_merge($getData,$get);
		}
		return $getData;
	}
	/**
	 * 获得POST基本数据
	 * @param $post
	 */
	public function getPostData($data=array(),$serverId=0){
		$postData = $this->getSignArr($data);
		$postData['domain'] = $this->getServerProperty($serverId,'marking');
// 		$gameInfo = $this->getIfConf();
// 		$post = $gameInfo[ACTION]['post'];
// 		if($post){
// 			$postData = array_merge($postData,$post);
// 		}
		return $postData;
	}
	
	public function applyEnd($data,$type = 'json'){
		$type = strtolower($type);
		switch ($type){
			case 'json':
			default:
				$data = json_decode($data,true);
		}
		//		print_r($data);exit;
		if(!is_array($data)){
			return "<font color='#FF0000'>审核失败:游戏接口错误</font>";
		}
		if($data['status'] == 1){
			return '<font color="#00FF00">审核成功</font>';
		}
		return '<font color="#FF0000">审核失败:'.$data['info'].'</font>';
	}
	
	public function applySend($urlAppend,$sendData,$serverId){
// 		echo $urlAppend;echo '----'.$serverId;
// 		echo json_encode($sendData);
		$result = $this->getResult($urlAppend,$sendData,$serverId);
// 		print_r($result);exit;
		if($result['status']==1){
			return '<font color="#00FF00">审核成功</font>';
		}else{
			return '<font color="#FF0000">审核失败,信息：'.$result['info'].'</font>';
		}
// 		return $result;
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
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
			'PlayerLookup'=>array(
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30501',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
			),
//			'RechargeRecord'=>array( //玩家充值记录
//						'action'=>'HuanJL',
//						'UrlAppend'=>'Entrancek.php',
//						'get'=>array('type'=>'rechargelist'),
//						'body'=>'',
//			),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30503',
				'get'=>array(),
				'body'=>'',
			),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30502',
				'get'=>array(),
				'body'=>'',
			),
			'SendMail'=>array(	//发邮件
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30508',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/SendMail/XiYou.html',
			),
// 			'RechargeRecord'=>array('action'=>'Action10',),
// 			'GameLogin'=>array(	//游戏登录
// 				'action'=>'XiYou',
// 				'UrlAppend'=>'player/GMLogin',
// 				'get'=>array(),
// 				'body'=>'',
// 				'notify'=>'Log_GameLogin',
// 			),
			
			'TitleOrGag'=>array(	//封号禁言
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30505',
				'get'=>array(),
				'post'=>array(),
				'body'=>'ActionGame_MasterTools/TitleOrGag/XiYou.html',
			),
			'AddTitleOrGag'=>array(	//添加封号禁言
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30506',
				'get'=>array(),
				'post'=>array(),
				'body'=>'ActionGame_MasterTools/AddTitleOrGag/XiYou.html',
				'notify'=>'Log_TitleOrGag',
			),
			'DelTitleOrGag'=>array(	//删除封号禁言
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30507',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
				'notify'=>'Log_TitleOrGag',
			),
			
// 			'Silence'=>array(	//禁言
// 				'action'=>'XiYou',
// 				'UrlAppend'=>'player/getPlayerRestictList',
// 				'get'=>array(),
// 				'post'=>array('type'=>2),
// 				'body'=>'',
// 			),
			
// 			'MultiLock'=>array(	//多服封号|禁言
// 				'action'=>'Default',
// 			),
/* 			'LockIP'=>array(	//封IP列出
				'action'=>'DaoJian',
				'UrlAppend'=>'listBlockIp',
				'get'=>array(),
				'body'=>'',
			),
			'LockIPDone'=>array(	//封IP提交
				'action'=>'DaoJian',
				'UrlAppend'=>'blockIp',
				'get'=>array(),
				'body'=>'',
			),
			'DelLockIP'=>array(	//删除Ip
					'action'=>'DaoJian',
					'UrlAppend'=>'unblockIp',
					'get'=>array(),
					'body'=>'',
			), */
// 			'GetOperatorServer'=>array(
// 				'action'=>'GetOperatorServer10',
// 				'UrlAppend'=>'server',
// 				'get'=>array('action'=>'get'),
// 				'body'=>'',
// 			),
			
			'Notice'=>array(	//公告列表
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30515',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30514',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30517',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30516',
				'get'=>array(),
				'body'=>'ActionGame_OperatorTools/NoticeEdit/XiYou.html',
			),

			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30512',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/BackpackSearch/XiYou.html',
			),
			'Item'=>array(	//道具更新|获得道具列表
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30510',
				'get'=>array(),
				'post'=>array(),
				'body'=>'',
			),
// 			'ApplyCard'=>array(	//道具申请
// 					'action'=>'DaoJian',
// 					'UrlAppend'=>'sendEmail',
// 					'get'=>array(),
// 					'body'=>'',
// 			),

			'ItemDel'=>array(	//调整背包
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30513',
				'get'=>array(),
			),
			
			
			'ItemCard'=>array(	//道具卡列表
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30509',
				'get'=>array(),
			),
			'ItemCardApply'=>array(	//礼包申请
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30511',
				'get'=>array(),
			),
// 			'ItemCardAppendApply'=>array(	//礼包追加卡密申请
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'insert'),
// 			),
// 			'ItemCardShowBatch'=>array(	//查某礼包的所有批次
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'gift',
// 				'get'=>array('action'=>'getCardBatch'),
// 			),
// 			'ItemCardList'=>array(	//卡号列表
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'gift',
// 				'get'=>array('action'=>'getCardList'),
// 			),
// 			'ItemCardDownLoad'=>array(	//卡号下载
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'gift',
// 				'get'=>array('action'=>'downloadCardList'),
// 			),
// 			'ItemCardQuery'=>array(	//道具卡查询
// 				'action'=>'HuanJL',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'listCard'),
// 			),
// 			'ItemPackageEdit'=>array(	//礼包编辑
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'gift',
// 				'get'=>array('action'=>'update'),
// 				'body'=>'ActionGame_MasterTools/ItemCardApply/GongFu.html',	//配置指定模板
// 			),
// 			'ItemReceiveCondition'=>array(	//礼包领取条件
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'gift',
// 				'get'=>array('action'=>'getCondition'),
// 			),
// 			'AllNotice'=>array(	//多服公告管理
// 				'action'=>'Default',
// 			),
			
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
			),
			
// 			'AllNoticeAdd'=>array(	//多服发送
// 				'action'=>'Default',
// 			),
			'RechargeRecord'=>array(	//玩家充值记录
				'action'=>'NuFengZhanChui',
				'UrlAppend'=>'30518',
				'get'=>array(),
			),
// 			'PointDel'=>array(	//点数扣除
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'user',
// 				'get'=>array('action'=>'deduction'),
// 				'notify'=>'Log_PointDel',
// 			),
// 			'SetVIP'=>array(	//玩家VIP设置
// 					'action'=>'HuanJL',
// 					'UrlAppend'=>'Entrancek.php',
// 					'get'=>array('type'=>'setVIP'),
// 			),
// 			'UserLoginLog'=>array(	//玩家登陆情况
// 					'action'=>'HuanJL',
// 					'UrlAppend'=>'Entrancek.php',
// 					'get'=>array('type'=>'userloginlist'),
// 			),
			
// 			'FunOnOrOff'=>array(	//玩家登陆情况
// 					'action'=>'XiYou',
// 					'UrlAppend'=>'server/getFunction',
// 					'get'=>array(),
// 			),
			
		);
	}
}