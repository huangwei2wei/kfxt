<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_MultiLock_Default extends Action_ActionBase{
	const OPT_TYPE_LOCK_ACCOUNT = 1;	//封号(操作类型ID)
	const OPT_TYPE_SILENCE = 2;	//禁言(操作类型ID)
	const PLAYER_TYPE_ACCOUNT = 2;	//用户账号2，其中 1:玩家id,2:账号,3:昵称
	const SEPARATOR = ',';	//玩家之间的分隔符
	public function _init(){
		$this->_assign['typeMap'] = array(
			'lock_account' =>self::OPT_TYPE_LOCK_ACCOUNT,
			'silence' =>self::OPT_TYPE_SILENCE,
		);
		$this->_assign['url'] = array(
			self::OPT_TYPE_LOCK_ACCOUNT =>$this->_urlLockAccountAdd(),
			self::OPT_TYPE_SILENCE =>$this->_urlSilenceAdd(),
		);
		$this->_assign['optType'] = array(
			self::OPT_TYPE_LOCK_ACCOUNT =>'封号',
			self::OPT_TYPE_SILENCE =>'禁言',
		);
		$this->_assign['playerType'] = self::PLAYER_TYPE_ACCOUNT;
		$this->_assign['separator'] = self::SEPARATOR;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		return $this->_assign;
	}
	
	private function _urlLockAccountAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
		);
		return Tools::url(CONTROL,'LockAccountAdd',$query);
	}
	
	private function _urlSilenceAdd(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
		);
		return Tools::url(CONTROL,'SilenceAdd',$query);
	}
}