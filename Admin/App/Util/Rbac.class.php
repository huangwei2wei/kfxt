<?php
class Util_Rbac extends Control {

	const CHECK_OPERATOR=1;
	const CHECK_SERVER=2;

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

	/**
	 * Model_Moudle
	 * @var Model_Moudle
	 */
	private $_modelMoudle;


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
	 * 检测运营商权限
	 * @param int $serverId 服务器ID/运营商id
	 * @param int $type 检测类型,默认以服务器id
	 * @return boolean
	 */
	public function checkOperatorAct($serverId,$type=NULL){
		if (!$serverId)return true;
		if ($type===null)$type=self::CHECK_SERVER;
		$userClass=$this->getUserClass();
		if (in_array($userClass['_userName'],explode(',',MasterAccount)))return true;	//无敌账号检测
		$userOperator=$userClass->getUserOperatorIds();
		if (!count($userOperator))return false;
		if ($type==self::CHECK_SERVER){
			$modelGameserList=$this->_getGlobalData('Model_GameSerList','object');
			$serverDetail=$modelGameserList->findById($serverId);
			$operatorId=$serverDetail['operator_id'];
			return in_array($operatorId,$userOperator)?true:false;
		}else {
			return in_array($serverId,$userOperator)?true:false;
		}

	}
	
	/**
	 * 返回经过检测权限的游戏数组
	 * @return array
	 */
	public function getGameActList(){
		static $GameResult=null;//单例,防止计算第二次.
		if ($GameResult===null){
			$gameList=$this->_getGlobalData('game_type');
			$gameList=Model::getTtwoArrConvertOneArr($gameList,'Id','name');
			$userClass=$this->getUserClass();
			if (in_array($userClass['_userName'],explode(',',MasterAccount)))return $gameList;	//无敌账号检测
			$userGame=$userClass->getUserGameTypeIds();	//获取用户的所有运营商
			$GameResult = array();
			foreach($userGame as $val){
				if(array_key_exists($val,$gameList)){
					$GameResult[$val] = $gameList[$val];
				}
			}
		}
		return $GameResult;
	}

	/**
	 * 返回经过检测权限的运营商数组
	 * @return array
	 */
	public function getOperatorActList($gameId=0){
		static $result=null;//单例,防止计算第二次.
		if ($result===null){
			if($gameId){
				$operatorList=$this->_getGlobalData('operator/operator_list_'.$gameId);
			}else{
				$operatorList=$this->_getGlobalData('operator_list');
			}
			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$userClass=$this->getUserClass();
			if (in_array($userClass['_userName'],explode(',',MasterAccount)))return $operatorList;	//无敌账号检测
			$userOperator=$userClass->getUserOperatorIds($gameId);	//获取用户的所有运营商
			foreach ($operatorList as $key=>$value){
				if (!in_array($key,$userOperator))unset($operatorList[$key]);
			}
			$result=$operatorList;
		}
		return $result;

	}

	/**
	 * 获取act缓存文件
	 * @param string $package
	 */
	public function getAct($package) {
		if ($package!==null)return $this->_getGlobalData('moudle_act/'.ucwords($package));
		return $this->_getGlobalData ( 'act' );
	}

	/**
	 * 检测是否有权限
	 * @param string $getAction 动作/控制器
	 * @param string $package 包名
	 * @return int 1:有权限 -1没有权限 -2未登录 -3账号已停用
	 */
	public function checkAct($getAction,$package=NULL) {
		$actArr = $this->getAct ($package);
		if ($actArr [$getAction] == RBAC_EVERYONE)
			return 1; //如果为所有用户,马上就返回1
		$userInfo = $this->getUserClass ();
		if (is_object ( $userInfo )) {
			if (in_array ( $userInfo ['_userName'], explode(',',MasterAccount) ))
				return 1; //无敌账号判断
			if (!$userInfo['_status'])return -3;	//账号已经停用
			$userRoles = is_array($userInfo ['_roles'])?$userInfo ['_roles']:array(); //返回用户角色
		} else {
			return - 2;
		}
		if (empty ( $actArr [$getAction] )) { //如果为空就定为空数组
			$actionRoles = array ();
		} else { //否则就切割数组
			$actionRoles =(is_array($actArr [$getAction]))?$actArr [$getAction] : explode ( ',', $actArr [$getAction] );
		}
		$result = array_intersect ( $actionRoles, $userRoles ); //是否有交集
		if (count ( $result )) {
			return 1;
		} else {
			if($package){	//其他模块的个人额外权限检查
				if (is_array($userInfo['_moudleAct']) && is_array($userInfo['_moudleAct'][$package]) && in_array($getAction,$userInfo['_moudleAct'][$package]) ){
					return 1;
				}				
			}else{	//默认模块的个人额外权限检查
				if (is_array($userInfo['_act']) && in_array($getAction,$userInfo['_act'])){
					return 1;
				}
			}
		}
		return - 1;
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
	 * @return Object_UserInfo
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
		$userClass=$this->getUserClass();
		$utilOnline=$this->_getGlobalData('Util_Online','object');
		$utilOnline->cleanOffOnlineUser($userClass['_id']);	//清除在线状态
		setcookie(SESSION_USER_KEY,'',CURRENT_TIME-1,'/');
	}

	/**
	 * 是否登录
	 * @return boolean
	 */
	public function isLogin() {
		static $isLogin=null;//单例,只判断一次
		if ($isLogin===null){
			if ($_COOKIE [SESSION_USER_KEY]) {
				$userName=Tools::dencrypt($_COOKIE[SESSION_USER_KEY],false);
				$userName=explode('|',$userName);
				$userName=$userName[0];	//0:用户名,1:ip
				$user=$this->_getGlobalData('user_index');
				if (array_key_exists($userName,$user)){
					$isLogin=true;
				}else {
					$isLogin=false;
				}
			} else {
				$isLogin=false;
			}
		}
		return $isLogin;
	}

	/**
	 * 用户登录
	 * @param string $userName 用户名
	 * @return void
	 */
	public function setLogin($userName) {
		$decodePass=$userName.'|'.Tools::getClientIP();
		Tools::setHeadP3P();
		$decodePass=Tools::dencrypt($decodePass);
		setcookie(SESSION_USER_KEY,$decodePass,CURRENT_TIME+60*60*6,'/');
		
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
	 * 获取用户默认模块菜单
	 * @return array
	 */
	public function getUserMoudleMenu($moudleValue=NULL){
		if (is_null($moudleValue))return $this->_getMoudleMainMenu();	//获取模块总菜单
		return $this->_getMoudleChildMenu($moudleValue);		//获取子模块子菜单
	}

	private function _getMoudleMainMenu(){
		$userClass=$this->getUserClass();
		$moudleList=$this->_getGlobalData('moudle_act/moudle');
		if($this->isMaster() ){	//超管
			foreach ($moudleList as $key=>&$list){
				$list['url']=Tools::url('Index','Index',array('value'=>$list['value']));	//改成新开页面
				if($list['value'] == 'ActionGame'){	//公共GM功能模板不显示
					unset($moudleList[$key]);
					continue;
				}
			}
		}else{	//普通用户
			foreach ($moudleList as $key=>&$list){
				//$list['url']=Tools::url('Index','Left',array('value'=>$list['value']));
				$list['url']=Tools::url('Index','Index',array('value'=>$list['value']));	//改成新开页面
				if($list['value'] == 'ActionGame'){	//公共GM功能模板不显示
					unset($moudleList[$key]);
					continue;
				}
				if ($list['act']==RBAC_EVERYONE){
					continue;
				}
				$list['act']=is_array($list['act'])?$list['act']:array();
				$result=array_intersect($userClass['_roles'],$list['act']);
				if (!count($result)){
					unset($moudleList[$key]);
				}
			}
		}
		return $moudleList;
	}

	private function _getMoudleChildMenu($moudleValue){
		$moudleValue=ucwords($moudleValue);
		$userClass=$this->getUserClass();
		$menu=$this->_getGlobalData($moudleValue,'menu');
		if (count($menu) > 0 && is_array($menu) == true)
		{
			//加上__game_id的选择
			$_REQUEST['__game_id'] = intval($_REQUEST['__game_id']);
			if($_REQUEST['__game_id']){
				$queryArr = array('zp'=>$moudleValue,'__game_id'=>intval($_REQUEST['__game_id']) );
			}else{
				$queryArr = array('zp'=>$moudleValue);
			}
    		foreach ($menu as $key => &$value){
    			if ($value['display']==false)unset($menu[$key]);
    			if ($this->checkAct($key,$moudleValue)!=1)unset($menu[$key]);
    			if ($value['child']){
    				foreach ($value['child'] as $childKey=>&$childValue){
    					if($this->_checkActionMenu($childKey)==false)unset($value['child'][$childKey]);
     					if ($childValue['display']==false)unset($value['child'][$childKey]);
     					$c = isset($childValue['c'])?$childValue['c']:$key;
    					$a = isset($childValue['a'])?$childValue['a']:$childKey;
    					if ($this->checkAct($c.'_'.$a,$moudleValue)!=1)unset($value['child'][$childKey]);
    					$childValue['url']=Tools::url($c,$a,$queryArr);
    				}
    				if ($value['child'])$value['actions']=$value['child'];
    			}
    		}
		}
		return $menu;
	}
	
	private function _checkActionMenu($action){
		static $gameItf = array();
		if($_REQUEST['__game_id'] && $_REQUEST['value']=="ActionGame"){
			$gameId = intval($_REQUEST['__game_id']);
			if(!isset($gameItf[$gameId])){
				$gameClass = $this->_getGlobalData($gameId,'game');
				$gameItf[$gameId] = $gameClass->getIfConf();
				if(!is_array($gameItf[$gameId])){
					$gameItf[$gameId] = array();
				}
			}
			if(array_key_exists($action,$gameItf[$gameId])){
				return true;
			}
			return false;
		}
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
	/**
	 * 是否超级管理员
	 */
	public function isMaster(){
		static $isMaster = null;
		if($isMaster !==null){
			return $isMaster;
		}
		$userClass=$this->getUserClass();
		if (in_array($userClass['_userName'],explode(',',MasterAccount))){
			$isMaster =  true;
		}else{
			$isMaster =  false;
		}
		return $isMaster;
	}
	
//	private $_myGames;	
//	private $_myOperators;
//	public function getMyGames(){
//		if($this->_myGames){
//			return $this->_myGames;			
//		}
//		$this->createMyGamesNOperators();
//		return $this->_myGames;	
//	}	
//	public function getMyOperators(){
//		if($this->_myOperators){
//			return $this->_myOperators;			
//		}
//		$this->createMyGamesNOperators();
//		return $this->_myOperators;
//	}	
//	private function createMyGamesNOperators(){
//		$MyClass = $this->getUserClass();
//		$gameTypes=$this->_getGlobalData('game_type');
//		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
//		$operators=$this->_getGlobalData('operator_list');
//		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
//		if(in_array($MyClass['_userName'],explode(',',MasterAccount) )){
//			$this->_myGames=$gameTypes;
//			$this->_myOperators=$operators;
//		}else{
//			$this->_myGames=array();
//			$this->_myOperators=array();
//			foreach($MyClass['_operatorIds'] as $sub){
//				$gameId = $sub['game_type_id'];
//				$operatorId = $sub['operator_id'];
//				if(array_key_exists($gameId,$gameTypes)){
//					$this->_myGames[$gameId] = $gameTypes[$gameId];
//				}
//				if(array_key_exists($operatorId,$operators)){
//					$this->_myOperators[$operatorId] = $operators[$operatorId];
//				}
//			}
//		}
//	}

}