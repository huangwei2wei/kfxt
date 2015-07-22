<?php
class Util_Online extends Control {

	/**
	 * Model_OnlineUser
	 * @var Model_OnlineUser
	 */
	private $_modelOnlineUser;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	public function __construct() {
		$this->_modelOnlineUser = $this->_getGlobalData ( 'Model_OnlineUser', 'object' );
	}

	/**
	 * 获取在线所有用户
	 * @param string $value online表字段,user_id/user_name(default)
	 * @return array
	 */
	public function getOnlineUser($value='user_name') {
		$users = $this->_modelOnlineUser->findAll ();
		return Model::getTtwoArrConvertOneArr ( $users,null, $value );
	}


	/**
	 * 检测某一用户是否在线
	 * @param string $userName 用户名
	 */
	public function isUserOnline($userName) {
		$user = $this->_modelOnlineUser->findByUserName ( $userName );
		if ($user) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 清除不在线用户
	 */
	public function cleanOffOnlineUser() {
		$this->_modelOnlineUser->deleteFromUser ();
	}

	/**
	 * 设置在线用户
	 */
	public function setOnlineUser($userName=NULL) {
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ($userName);
		$addArr = array (
			'user_id'	=> $userClass ['_id'],
			'user_name' => $userClass ['_userName'],
			'last_time' => CURRENT_TIME
		);
		$this->_modelOnlineUser->replace($addArr);
	}
}