<?php
Tools::import('Game_GameBase');
class Game_26 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 26;		//游戏Id
		$this->_zp = 'SanFen';	//控制器扩展包
		$this->_key = '97d22c7d-7455-48dc-950d-5786b64d40c0';	//游戏密匙
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
		return $this->clientTimeChk($this->_key);
// 		if($_REQUEST['source']==1){
// 			return $this->commonChk();
// 		}
// 		return $this->clientTimeChk('c970ab23-abac-49d0-9976-03d1cd649d47');//特殊处理，周末更新之后换回来
	}
	
	public function sendOrderReplay($data=NULL){//客服回复后自动提示玩家
// 		if($data['status']==3){
// 			$title		=	'您的提问已回复';
// 			$content	=	"你的提问已经答复<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
// 		}else{
// 			$title		=	'您的提问正在处理中';
// 			$content	=	"您的提问正在处理中！<a href='event:gotoGmWin?id={$data['work_order_id']}'><font color='#0fe404'><u>点击查看</u></font></a>";
// 		}
		$api	=	$this->_getGlobalData('Util_HttpInterface','object');
		$getData = $this->getGetData(array(),$data["server_id"]);
		$postData['playerId'] = $data['game_user_id'];
// 		$postData['userType'] = 1;//1:玩家id,2:表示用户帐号,3:表示角色名
// 		$postData['title'] = $title;
// 		$postData['content'] = $content;
		$dataReturn = $api->result($data["send_url"],'replyCue.jsp',$getData,$postData);
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
// 		$ifConf = $this->getIfConf();
// 		$actionType = $getData['type'];
		
	/* 	echo $getData['type'];
		var_dump($actionType);
		var_dump($actionType.'---'.CURRENT_TIME.'---'.$this->_key);
		 */
		$d = date("Y-m-d");
		return array(
			'd'=>$d,
			'sk'=>md5($this->_key.$d),
		);
	}
	public function applyEnd($data,$type = 'json'){
// 		var_dump($data);
		$type = strtolower($type);
		switch ($type){
			case 'json':
			default:
				$data = json_decode($data,true);
		}
		
		if(!is_array($data)){
			return "<font color='#FF0000'>审核失败:游戏返回数据格式错误</font>";
		}
		if($data['status'] == 1){
			if($data['data']=='true'){
				return '<font color="#00FF00">审核成功</font>';
			}else{
				return '<font color="#FF0000"> 审核失败</font>';
			}
		}
		$errorInfo = $data['info']?":{$data['info']}":'';
		return "<font color='#FF0000'>{$errorInfo}</font>";
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
	
	public function getDBurl($serverId = 0){
		$DBurl = '';
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList = $this->_getGlobalData('server/server_list_'.$this->_gameId);
			$DBurl = $serverList[$serverId]['data_url'];
		}
		return $DBurl;
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
		return $data;
	}
	public function getPostData(){
		$gameInfo = $this->getIfConf();
		$post = $gameInfo[ACTION]['post']; 
		$postData = array(
				'pageSize'=>PAGE_SIZE,
				'pageCount'=>max(1,intval($_GET['page'])),
		);
		if($post){
			$postData = array_merge($postData,$post);
		}
		$id = $_GET['id'];
		if($id){
			$postData['id'] = $id;
		}
		return $postData;
	}
	
	
	public function getApplyId($mark = ''){
		$mark = trim($mark);
		if(empty($mark)){
			return false;
		}
		$applyInfo = array(
			'ItemCardApply'=>38,
		);
		return $applyInfo[$mark];
	}
	

	
	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
			'PlayerLookup'=>array(
				'action'=>'SanFen',
				'UrlAppend'=>'getPlayer.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'RechargeRecord'=>array( //玩家充值记录
						'action'=>'SanFen',
						'UrlAppend'=>'getBuyResult.jsp',
						'get'=>array(),
						'body'=>'',
			),
			'PlayerLog'=>array(	//玩家日志
				'action'=>'SanFen',
				'UrlAppend'=>'getOperationLog.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'PlayerLogType'=>array(	//玩家日志类型更新
				'action'=>'SanFen',
				'UrlAppend'=>'getLogType.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'SendMail'=>array(	//发邮件
				'action'=>'Default',
				'UrlAppend'=>'sendEmail.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'MailList'=>array(	//玩家邮件列表
					'action'=>'SanFen',
					'UrlAppend'=>'getMailResult.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'SendEmailMultiserver'=>array(	//多服发送邮件
				'action'=>'SanFen',
				'UrlAppend'=>'sendEmail.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'GameLogin'=>array(	//游戏登录
				'action'=>'SanFen',
				'UrlAppend'=>'getGameUrl.jsp',
				'get'=>array(),
				'body'=>'ActionGame_MasterTools/GameLogin/HuanJL.html',
				'notify'=>'Log_GameLogin',
			),
			
			'LockAccount'=>array(	//封号
				'action'=>'SanFen',
				'UrlAppend'=>'getAccountForbidPlayer.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'SanFen',
				'UrlAppend'=>'addAccountForbid.jsp',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_LockAccount',
			),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'SanFen',
				'UrlAppend'=>'deleteAccountForbid.jsp',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_LockAccount',
			),
			
			
			'Silence'=>array(	//禁言
				'action'=>'SanFen',
				'UrlAppend'=>'getForbidPlayer.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'SilenceAdd'=>array(	//添加禁言
				'action'=>'SanFen',
				'UrlAppend'=>'addForbid.jsp',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_Silence',
			),
			'SilenceDel'=>array(	//删除禁言
				'action'=>'SanFen',
				'UrlAppend'=>'deleteForbid.jsp',
				'get'=>array(),
				'body'=>'',
				'notify'=>'Log_Silence',
			),
			'ServerOnOrOff'=>array(	//开服停服
					'action'=>'SanFen',
					'UrlAppend'=>'serverOn.jsp',
					'get'=>array(),
					'body'=>'',
			),
// 			'MultiLock'=>array(	//多服封号|禁言
// 				'action'=>'Default',
// 			),
// 			'LockIP'=>array(	//封IP列出
// 				'action'=>'HuanJL',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'iplist'),
// 				'body'=>'',
// 			),
// 			'LockIPDone'=>array(	//封IP提交
// 				'action'=>'HuanJL',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'ipinsert'),
// 				'body'=>'',
// 			),
// 			'DelLockIP'=>array(	//封IP提交
// 					'action'=>'HuanJL',
// 					'UrlAppend'=>'Entrancek.php',
// 					'get'=>array('type'=>'ipdel'),
// 					'body'=>'',
// 			),
// 			'GetOperatorServer'=>array(
// 				'action'=>'GetOperatorServer10',
// 				'UrlAppend'=>'server',
// 				'get'=>array('action'=>'get'),
// 				'body'=>'',
// 			),
			
			'Notice'=>array(	//公告列表
				'action'=>'SanFen',
				'UrlAppend'=>'getNotice.jsp',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'SanFen',
				'UrlAppend'=>'addNotice.jsp',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'HuanJL',
				'UrlAppend'=>'deleteNotice.jsp',
				'get'=>array(),
				'body'=>'',
			),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'SanFen',
				'UrlAppend'=>'updateNotice.jsp',
				'get'=>array(),
				'body'=>'',
			),

			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'SanFen',
				'UrlAppend'=>'getItemInfoIn.jsp',
				'get'=>array(),
				'body'=>'',
			),
			'ItemDel'=>array(	//道具删除
					'action'=>'SanFen',
					'UrlAppend'=>'updateItemInfo.jsp',
					'get'=>array(),
					'notify'=>'Log_Silence',
			
			),
			'Item'=>array(	//道具更新|获得道具列表
				'action'=>'SanFen',
				'UrlAppend'=>'getItemType.jsp',
				'get'=>array(),
				'body'=>'',
			),

			'ApplyCard'=>array(	//道具申请
					'action'=>'SanFen',
					'UrlAppend'=>'sendGiftEmail.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'ApplyIngot'=>array(	//金币任务属性修改
					'action'=>'SanFen',
					'UrlAppend'=>'modifyRoleByInfo.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'SendGoodsMultiserver'=>array(	//道具申请
					'action'=>'SanFen',
					'UrlAppend'=>'sendGiftEmail.jsp',
					'get'=>array(),
					'body'=>'',
			),

			'OnLine'=>array(	//在线用户
					'action'=>'SanFen',
					'UrlAppend'=>'getopc.jsp',
					'get'=>array(),
					'body'=>'',
			),
// 			'ItemCard'=>array(	//礼包
// 				'action'=>'HuanJL',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'list'),
// 			),
// 			'ItemCardApply'=>array(	//礼包申请
// 				'action'=>'HuanJL',
// 				'UrlAppend'=>'Entrancek.php',
// 				'get'=>array('type'=>'insert'),
// 			),
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
			
			'AllNoticeAdd'=>array(	//多服发送
				'action'=>'SanFen',
				'UrlAppend'=>'addNotice.jsp',
			),

			
// 			'RechargeRecord'=>array(	//玩家充值记录
// 				'action'=>'GongFu',
// 				'UrlAppend'=>'paySearch',
// 				'get'=>array('action'=>'search'),
// 			),
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
// 			'UserLoginLog'=>array(	//玩家VIP设置
// 					'action'=>'HuanJL',
// 					'UrlAppend'=>'Entrancek.php',
// 					'get'=>array('type'=>'userloginlist'),
// 			),
			'ActivitiesList'=>array(	//活动列表
					'action'=>'SanFen',
					'UrlAppend'=>'getActivity.jsp',
					'get'=>array('id'=>0),
					'body'=>'',
			),
			'ActivitiesEdit'=>array(	//活动修改
					'action'=>'SanFen',
					'UrlAppend'=>'updateActivity.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'ShopProduce'=>array(	//商品列表
					'action'=>'SanFen',
					'UrlAppend'=>'getGoods.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'ShopProduceEdit'=>array(	//商品列表
					'action'=>'SanFen',
					'UrlAppend'=>'updateGoods.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'AddPlayerAsGM'=>array(	//添加GM
					'action'=>'SanFen',
					'UrlAppend'=>'addPlayerAsGM.jsp',
					'get'=>array(),
					'body'=>'',
			),
			'GMList'=>array(	//添加GM
					'action'=>'SanFen',
					'UrlAppend'=>'getGMList.jsp',
					'get'=>array(),
					'body'=>'',
			),
// 			'GetIngotType'=>array(	//获取金币流向类型
// 					'action'=>'SanFen',
// 					'UrlAppend'=>'getIngotType.jsp',
// 					'get'=>array(),
// 					'body'=>'',
// 			),
// 			'GetIngot'=>array(	//金币流向
// 					'action'=>'SanFen',
// 					'UrlAppend'=>'',
// 					'get'=>array(),
// 					'body'=>'',
// 			),
			'AddPlayerPay'=>array(	//玩家登陆情况
					'action'=>'SanFen',
					'UrlAppend'=>'addPlayerPay.jsp',
					'get'=>array(),
			),
		);
	}
}