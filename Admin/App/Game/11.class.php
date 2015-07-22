<?php
Tools::import('Game_GameBase');
class Game_11 extends Game_GameBase{	
	
	/**
	 * 初始化
	 */
	public function _init(){
		$this->_gameId = 11;		//游戏Id
		$this->_zp = 'SanGuo';	//控制器扩展包
		$this->_key = 'IUEHFTH$%()$DKLGJB';	//游戏密匙
		$this->_ts	=	time();
		$this->_isSendOrderReplay = false;
	}
	
	public function workOrderIfChk(){
		return $this->clientTimeChk();
	}
	
	public function sendOrderReplay($data=NULL){
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
		$data['sig']	=	md5($this->_key.$this->_ts);
		$data['ts']		=	$this->_ts;
		if($get && is_array($get)){
			$data = array_merge($get,$data);
		}
		return array_filter($data);
	}
	/**
	 * 获得游戏接口配置
	 */
	public function getIfConf(){
//		return $this->_getGlobalData('game_if_conf/'.$this->_gameId);
		//迟点要优化至后台自动生成
		return array(
//			'PlayerLookup'=>array(
//				'action'=>'GongFu',
//				'UrlAppend'=>'user',
//				'get'=>array('action'=>'getPlayers'),
//				'body'=>'',
//			),
//			'PlayerLog'=>array(	//玩家日志
//				'action'=>'GongFu',
//				'UrlAppend'=>'log',
//				'get'=>array('action'=>'getLogs'),
//				'body'=>'',
//			),
//			'PlayerLogType'=>array(	//玩家日志类型更新
//				'action'=>'GongFu',
//				'UrlAppend'=>'log',
//				'get'=>array('action'=>'getTemplate'),
//				'body'=>'',
//			),
//			'SendMail'=>array(	//发邮件
//				'action'=>'Default',
//				'UrlAppend'=>'mail',
//				'get'=>array('action'=>'send'),
//				'body'=>'',
//			),
			'GameLogin'=>array(	//游戏登录
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'loginForUser'),
				'body'=>'ActionGame_MasterTools/GameLogin/Default.html',
				'notify'=>'Log_GameLogin',
			),
			
			'LockAccount'=>array(	//封号
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getFreezeUser'),
				'body'=>'',
			),
			'LockAccountAdd'=>array(	//添加封号
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'freezeUser'),
				'body'=>'',
			),
			'LockAccountDel'=>array(	//删除封号
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'freezeUser'),
				'body'=>'',
			),
//			'Silence'=>array(	//禁言
//				'action'=>'GongFu',
//				'UrlAppend'=>'restrict',
//				'get'=>array('action'=>'getChats'),
//				'body'=>'',
//			),
//			'SilenceAdd'=>array(	//添加禁言
//				'action'=>'GongFu',
//				'UrlAppend'=>'restrict',
//				'get'=>array('action'=>'setChat'),
//				'body'=>'',
//				'notify'=>'Log_Silence',
//			),
//			'SilenceDel'=>array(	//删除禁言
//				'action'=>'GongFu',
//				'UrlAppend'=>'restrict',
//				'get'=>array('action'=>'deleteChat'),
//				'body'=>'',
//			),
			
//			'LockIP'=>array(	//封IP列出
//				'action'=>'Default',
//				'UrlAppend'=>'restrict',
//				'get'=>array('action'=>'getFilterIp'),
//				'body'=>'',
//			),
//			'LockIPDone'=>array(	//封IP提交
//				'action'=>'Default',
//				'UrlAppend'=>'restrict',
//				'get'=>array('action'=>'filterIp'),
//				'body'=>'',
//			),
			'RechargeRecord'=>array(	//充值记录
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getUserRechargeLog'),
				'body'=>'',
			),
//			'ItemIssuance'=>array('action'=>'Action10',),
//			'ItemsDel'=>array('action'=>'Action10',),
//			'GetOperatorServer'=>array(
//				'action'=>'GetOperatorServer10',
//				'UrlAppend'=>'kungfucross/server',
//				'get'=>array('action'=>'get'),
//				'body'=>'',
//			),
			
			'Notice'=>array(	//公告列表
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getNotice'),
				'body'=>'',
			),
			
			'NoticeAdd'=>array(	//添加公告
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'addNotice'),
				'body'=>'',
			),
			
			'NoticeDel'=>array(	//删除公告
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'delNotice'),
				'body'=>'',
			),
			
			'NoticeEdit'=>array(	//编辑公告
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getNotice'),
				'body'=>'',
			),
			'NoticeEditDone'=>array(
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'updateNotice'),
				'body'=>'',
			),
			'PersonalInformation'=>array(	//獲取用戶信息
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getUserData'),
				'body'=>'',
			),
			
			'UpdatePersonalInformation'=>array(	//修改用戶資產
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'updateUserData'),
				'body'=>'',
			),
			'UserCurrencyLog'=>array(	//修改用戶資產
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getUserCurrencyLog'),
				'body'=>'',
			),
			'GameData'=>array(	//游戏数据
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getGameData'),
				'body'=>'',
			),
			'BackpackSearch'=>array(	//用户背包查询
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'getUserStorage'),
				'body'=>'',
			),
			
			'SendUserPackage'=>array(	//礼包发放
				'action'=>'SanGuo',
				'UrlAppend'=>'cndw_api/',
				'get'=>array('a'=>'sendUserPackage'),
				'body'=>'',
			),
			
			'ServerManagement'=>array(	//服务器管理
				'action'=>'Default',
			),
		);
	}
	
	public function applyEnd($data,$type = 'json'){
		$type = strtolower($type);
		switch ($type){
			case 'json':
			default:
				$data = json_decode($data,true);
		}
		if($data['status'] == 1){
			return '<font color="#00FF00">审核成功</font>';
		}
		return '<font color="#FF0000">审核失败</font>';
	}
}