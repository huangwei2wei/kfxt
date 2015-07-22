<?php
class Util_Rbac extends Control {

	/**
	 * act文件缓存目录
	 * @var string 文件路径
	 */
	private $_cacheFile;

	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;

	public function __construct() {
		$this->_cacheFile = CACHE_DIR . '/act.cache.php';
	}

	/**
	 * 生成act文件
	 */
	public function createAct() {
		Tools::import ( 'Model_Act' );
		$modelAct = new Model_Act ();
		$dataList = $modelAct->findAll ();
		$actArr = array ();
		foreach ( $dataList as $value ) {
			$actArr [$value ['value']] = $value ['allow'];
		}
		return $this->_addCache ( $actArr, $this->_cacheFile );
	}

	/**
	 * 获取act缓存文件
	 */
	public function getAct() {
		return $this->_getGlobalData ( 'act' );
	}

	/**
	 * 检测是否有权限
	 * @param string $getAction 动作/控制器
	 * @return int 1:有权限 -1没有权限 -2未登录
	 */
	public function checkAct($getAction) {
		$actArr = $this->getAct ();
		if ($actArr [$getAction] == RBAC_EVERYONE)
			return 1; //如果为所有用户,马上就返回1
		$userInfo = $this->getUserClass ();
		if (is_object ( $userInfo )) {
			if (in_array ( $userInfo ['_userName'], array ('zlsky' ) ))
				return 1; //无敌账号判断
			$userRoles = $userInfo ['_roles']; //返回用户角色
		} else {
			return - 2;
		}
		if (empty ( $actArr [$getAction] )) { //如果为空就定为空数组
			$actionRoles = array ();
		} else { //否则就切割
			$actionRoles = explode ( ',', $actArr [$getAction] );
		}
		$result = array_intersect ( $actionRoles, $userRoles ); //是否有交集
		if (count ( $result )) {
			return 1;
		} else {
			return - 1;
		}
	}

	/**
	 * 返回用户实例,默认获取session的
	 * @return Object_UserInfo
	 */
	public function getUserClass($userName = null) {
		if ($userName == null && $this->isLogin ()) {
			$userName=Tools::dencrypt($_COOKIE[SESSION_USER_KEY],false);
			$userName=explode('|',$userName);
			$userName=$userName[0];	//0:用户名,1:ip
		}
		if ($userName == null)
			return false;
		return $this->_getGlobalData ( $userName, 'user' );
	}

	/**
	 * 通过用户的id获取用户的对象
	 * @param int $userId
	 */
	public function getUserClassById($userId){
		if (!$userId)return false;
		$users=$this->_getGlobalData('user');
		$userName=$users[$userId]['user_name'];
		return $this->getUserClass($userName);
	}

	/**
	 * 退出登录
	 * @return void
	 */
	public function loginOut() {
		setcookie(SESSION_USER_KEY,'',0,'/');
	}

	/**
	 * 是否登录
	 * @return boolean
	 */
	public function isLogin() {
		if ($_COOKIE [SESSION_USER_KEY]) {
			$userName=Tools::dencrypt($_COOKIE[SESSION_USER_KEY],false);
			$userName=explode('|',$userName);
			$userName=$userName[0];	//0:用户名,1:ip
			$user=$this->_getGlobalData('user');
			foreach ($user as $list){
				if ($userName==$list['user_name'])return true;
			}
			return false;
		} else {
			return false;
		}
	}

	/**
	 * 用户登录
	 * @param string $userName 用户名
	 * @return void
	 */
	public function setLogin($userName) {
		$decodePass=$userName.'|'.Tools::getClientIP();
//		Tools::setHeadP3P();
		$decodePass=Tools::dencrypt($decodePass);
		setcookie(SESSION_USER_KEY,$decodePass,CURRENT_TIME+60*60*6);
		$userClass=$this->getUserClass($userName);
		$userClass->setInfo();
		$userClass->setUpdateInfo(1);
	}

	/**
	 * 创建用户
	 * @param array $userInfo 用户信息
	 * @return boolean
	 */
	public function createUser($userInfo) {
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$this->_modelUser->add ( $userInfo );
		$userInfo ['Id'] = $this->_modelUser->returnLastInsertId ();
		$userClass = new Object_UserInfo ();
		$userClass->registerUserInfo ( $userInfo );
		$userClass->setUpdateInfo ( 1 );
		$userClass=null;
		$this->_modelUser->createCache();
		return true;
	}

	/**
	 * @return the $_cacheFile
	 */
	public function get_cacheFile() {
		return $this->_cacheFile;
	}

	/**
	 * @param $_cacheFile the $_cacheFile to set
	 */
	public function set_cacheFile($_cacheFile) {
		$this->_cacheFile = $_cacheFile;
	}

}