<?php
Tools::import ( 'Game_GameBase' );
class Game_16 extends Game_GameBase {
	
	/**
	 * 初始化
	 */
	public function _init() {
		$this->_gameId = 16;
		$this->_sendImage = true;
		$this->_key = "5dcd73d391c90e8769618d42a916ea3c";
	}
	public function workOrderIfChk() {
		if (CONTROL == 'InterfaceFaq') {
			return true;
		}
		return $this->clientChk ( $this->_key );
	}
	public function clientChk($_key) {
		$gameId = intval ( $_REQUEST ['game_id'] );
		$uniquePlayer = trim ( $_REQUEST ['_unique'] );
		if (empty ( $_key )) {
			$key = $this->_key;
		} else {
			$key = $_key;
		}
		$sign = trim ( $_REQUEST ['_sign'] );
		
		if (md5 ( $gameId . $uniquePlayer . $key ) == $sign) {
			return true;
		}
		return false;
	}
	public function sendOrderReplay($data = NULL) {
		return true;
	}
	public function autoReplay($data = NULL) {
		return false;
	}
	public function operatorExtParam() {
		return array (
				array (
						'syskey',
						'系统登录密匙',
						'password',
						'' 
				), // 字段，描述，表单类型，默认值
				array (
						'co_action',
						'合作方标识',
						'text',
						'' 
				),
				array (
						'GameId',
						'游戏标识ID',
						'text',
						'' 
				) 
		);
	}
	public function getGetData($get = array(), $serverId = 0) {
		$data = $this->getSignArr ( $get );
		$data ['serverId'] = $this->getServerId ( $serverId );
		if ($get && is_array ( $get )) {
			$data = array_merge ( $get, $data );
		}
		return array_filter ( $data );
	}
	public function getSignArr($getData = array()) {
		$actionName = $getData ['a'];
		return array (
				'timestamp' => CURRENT_TIME,
				'sk' => md5 ( $this->_key . $actionName . CURRENT_TIME ) 
		);
	}
	public function getServerId($serverId = 0) {
		$returnServerId = 'S';
		if (! $serverId) {
			$serverId = $_REQUEST ['server_id'];
		}
		if ($serverId) {
			$serverList = $this->_getGlobalData ( 'server/server_list_' . $this->_gameId );
			$returnServerId .= intval ( $serverList [$serverId] ['ordinal'] );
		}
		return $returnServerId;
	}
	public function serverExtParam() {
		return array ();
	}
	public function getIfConf() {
		return array (
				'PlayerLookup' => array (
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "getPlayerInfo" 
						),
						'body' => '' 
				),
				'PlayerLog' => array ( // 玩家日志
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'getOperationLog' 
						),
						'body' => '' 
				),
				'LockAccount' => array ( // 封号
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'badPlayer',
								'e' => 'list' 
						),
						'body' => '' 
				),
				'SendMail' => array ( // 发邮件
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'sendMail' 
						),
						'body' => '' 
				),
				'LockAccountAdd' => array ( // 添加封号
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'badPlayer',
								'e' => 'add' 
						),
						'body' => '',
						'notify' => 'Log_LockAccount' 
				),
				'LockAccountDel' => array ( // 删除封号
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'badPlayer',
								'e' => 'delete'
						),
						'body' => '',
						'notify' => 'Log_LockAccount'
				),
				'Silence' => array ( // 禁言列表
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'forbidSpeech',
								'e' => 'list' 
						),
						'body' => ''
				),
				'SilenceAdd' => array ( // 添加禁言
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'forbidSpeech',
								'e' => 'add'
						),
						'body' => ''
				),
				'SilenceDel' => array ( // 删除禁言
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								'a' => 'forbidSpeech',
								'e' => 'delete'
						),
						'body' => ''
				),
				'LockIP' => array ( // 封IP列出
						'action' => 'Default',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "badIp",
								"e" => "list" 
						),
						'body' => '' 
				),
				'LockIP' => array ( // 封IP列出
						'action' => 'Default',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "badIp",
								"e" => "list" 
						),
						'body' => '' 
				),
				'LockIPDone' => array ( // 封IP提交
						'action' => 'Default',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "badIp",
								"e" => "add" 
						),
						'body' => '' 
				),
				'PropertySend' => array ( // 封IP提交
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "goods" 
						),
						'body' => '' 
				),
				'ServerManagement' => array ( // 服务器管理
						'action' => 'Default' 
				),
				'OrderDetail' => array ( // Q币消费查询
						'action' => 'ZhiDouXing',
						'UrlAppend' => 'client',
						'get' => array (
								"a" => "orderDetail" 
						),
						'body' => '' 
				),
				'GameLogin'=>array(	//游戏登录
					'action'=>'ZhiDouXing',
					'UrlAppend'=>'direct',
					'get'=>array('key'=>'5dcd73d391c90e8769618d42a916ea3m'),
					'body'=>'',
					'notify'=>'Log_GameLogin',
				),
		);
	}
}