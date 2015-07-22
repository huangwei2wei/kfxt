<?php
class Control_Index extends Control {
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	/**
	 * Model_Roles
	 * @var Model_Roles
	 */
	private $_modelRoles;

	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;

	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;

	/**
	 * Cache_AutoCount
	 * @var Cache_AutoCount
	 */
	private $_cacheAutoCount;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}

	private function _createUrl() {
		$this->_url ['Index_Top'] = Tools::url ( CONTROL, 'Top' ,array('value'=>strval($_GET['value'])));
		$this->_url ['Index_Left'] = Tools::url ( CONTROL, 'Left' ,array('value'=>strval($_GET['value'])));
		$this->_url ['Index_Right'] = Tools::url ( CONTROL, 'Right' );
		$this->_url ['Index_Login'] = Tools::url ( CONTROL, 'Login' );
		$this->_url ['Index_LoginOut'] = Tools::url ( CONTROL, 'LoginOut' );
		$this->_url ['Index_SetOnline'] = Tools::url(CONTROL, 'SetOnline');
		$this->_url ['Default_VerifyCode'] = Tools::url('Default','VerifyCode');
		$this->_url ['Index_Right']=Tools::url(CONTROL,'Right');
		$this->_url ['NoReadUser_MailIndex']=Tools::url('User','Mail',array('is_read'=>'0'));
		$this->_url ['User_MailIndex']=Tools::url('User','Mail');
		$this->_url ['MyTask_Index']=Tools::url('MyTask','Index');	//未处理工单 
		//错误日志链接
		$this->_url['ErrorLogDel'] = Tools::url(CONTROL,'ErrorLog',array('doaction'=>'del'));
		$this->_url['ErrorLogDelAll'] = Tools::url(CONTROL,'ErrorLog',array('doaction'=>'delAll'));
		$this->_url['ErrorLogShow'] = Tools::url(CONTROL,'ErrorLog',array('doaction'=>'show'));

		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 主页面
	 */
	public function actionIndex() {
		$this->_view->display ( $this->_view->get_curPage () );
	}

	/**
	 * 顶部
	 */
	public function actionTop() {
		$userClass=$this->_utilRbac->getUserClass();
		$game	=	$this->_getGlobalData("game_type");
		$gamelist	=	array();
		$myGameIds = $userClass->getUserGameTypeIds();
		if($this->_utilRbac->isMaster() ){	//超管
			foreach($game as $gameId => $value){
				if($gameId>=10 && $gameId<99){
					$gamelist[$gameId] = array(
						'url'=>Tools::url('Index','Left',array('value'=>'ActionGame','__game_id'=>$gameId)),
						'name'=>$game[$gameId]['name'],
					);
				}
			}
		}else{
			foreach($myGameIds as $gameId){
				if($gameId>=10 && $gameId<99){
					$gamelist[$gameId] = array(
						'url'=>Tools::url('Index','Left',array('value'=>'ActionGame','__game_id'=>$gameId)),
						'name'=>$game[$gameId]['name'],
					);
				}
			}
		}
		$this->_view->assign('game',$gamelist);
		$this->_view->assign('moudelMenu',$this->_utilRbac->getUserMoudleMenu());
		$this->_view->assign('userClass',$userClass);
		$this->_view->display ( $this->_view->get_curPage () );

	}

	/**
	 * 后台左边菜单管理
	 */
	public function actionLeft() {
		if (!$_GET['value'] || ucwords($_GET['value'])=='Default'){
			$menu = $this->_getGlobalData ( 'menu' );
			foreach ( $menu as $key=>&$value ) {
				if (!$value['status'])unset($menu[$key]);//如果是不显示就删除这个选项
				if ($this->_utilRbac->checkAct ( $value ['value'] ) == 1) { //如果有权限的话
					foreach ( $value ['actions'] as $key=>&$childList ) {
						if (!$childList['status'])unset($value['actions'][$key]);//如果为不显示就跳过.
						if ($this->_utilRbac->checkAct ( $childList ['value'] ) == 1) { //如果有权限的话
							$urlParams = explode ( '_', $childList ['value'] );
							$childList ['url'] = Tools::url ( $urlParams [0], $urlParams [1] );
						}else {//否则删除此项
							unset($value ['actions'][$key]);
						}
					}
				}else {//否则删除此项
					unset($menu[$key]);
				}
			}
				
		}else {
			if($_GET["value"]=="ActionGame"){
				$this->_view->assign ( 'newlang',1);
			}
			$menu=$this->_utilRbac->getUserMoudleMenu($_GET['value']);
		}
		//print_r($menu);
		$this->_view->assign ( 'lang', $_COOKIE['kefu_lang'] );
		$this->_view->assign ( 'menu', $menu );
		$this->_view->display ( $this->_view->get_curPage () );
	}

	/**
	 * 后台右边主页面显示
	 */
	public function actionRight() {
		$this->_utilMsg->createNavBar();
		$gameTypeList=$this->_getGlobalData('game_type');
		$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
		$userClass=$this->_utilRbac->getUserClass();
		$orgList=$this->_getGlobalData('org');
		$orgList=Model::getTtwoArrConvertOneArr($orgList,'Id','name');
		$departmentList=$this->_getGlobalData('department');
		$departmentList=Model::getTtwoArrConvertOneArr($departmentList,'Id','name');
		$userClass['word_department']=$departmentList[$userClass['_departmentId']];
		$this->_modelRoles=$this->_getGlobalData('Model_Roles','object');
		if ($userClass['_roles']){
			$rolesArr=array();
			foreach ($userClass['_roles'] as $roles){
				$rolesArr[]=$this->_modelRoles->findByRoleToName($roles);
			}
			$userClass['word_roles']=implode(',',$rolesArr);
		}

		$userClass['word_vip']=implode(',',$userClass['_orderVipLevel']);
		$userClass['word_org']=$orgList[$userClass['_orgId']];
		$userOeratorIds=array();
		$userClass['bulletin_list']=$userClass->getMail(array(1,15),array('type'=>1));	//公告
		$userClass['bulletin_list']=$userClass['bulletin_list']['data'];
		$userClass['work_list']=$userClass->getMail(array(1,15),array('type'=>2));		//工作交接
		$userClass['work_list']=$userClass['work_list']['data'];
		foreach ($userClass['_operatorIds'] as $list){
			$str="{$operatorList[$list['operator_id']]}[{$gameTypeList[$list['game_type_id']]}]";
			array_push($userOeratorIds,$str);
		}
		if ($userClass['_roomId']){
			$roomClass=$this->_utilRooms->getRoom($userClass['_roomId']);
			$this->_view->assign('roomClass',$roomClass);
			$displaycontent	=	"已经登录房间[".$roomClass['_roomName']."] [<a href=".Tools::url('Group','Room',array('doaction'=>'outRoom'))."><font style='color:red;'>退出房间</font></a>] ";
		}else{
			$displaycontent	=	'<font color="#999999">您还未登录房间</font>[<a href="'.Tools::url('Group','Room').'">房间列表</a>]';
		}
		$displaycontent.=' [<a href="'.Tools::url('MyTask','Index').'">我的工单列表</a>]';
		$tmpLang=$this->_getGlobalData('lang');
		$lang=array();
		foreach ($tmpLang as $key=>$value){
			$lang[$key]['lang']=$value;
			$lang[$key]['url_lang']=Tools::url(CONTROL,'ChangeLang',array('lang'=>$key));
		}
		$this->_view->assign('curLangId',LANG_ID);
		$this->_view->assign('lang',$lang);
		$this->_view->assign('userOeratorIds',$userOeratorIds);
		$this->_view->assign('userClass',$userClass);

		//将错误日志显示给管理员
		$Permission_IndexErrorLog = $this->_utilRbac->checkAct('Index_ErrorLog');	//审核权限	
		//		if(in_array($userClass['_userName'],explode(',',MasterAccount) )){
		if($Permission_IndexErrorLog === 1){
			$this->_view->assign('showErrorLogFiles',true);
			$this->_view->assign('errorLogFiles',$this->_errorLogFiles());

		}else{
			$this->_view->assign('showErrorLogFiles',false);
		}
		$this->_view->assign('displaycontent',$displaycontent);
		$this->_view->display ();
	}

	/**
	 * 错误日志
	 */
	public function actionErrorLog(){
		switch($_REQUEST['doaction']){
			case 'del':
				$this->_errorLogDel();
				return;
			case 'delAll':
				$this->_errorLogDelAll();
				return;
			case 'show':
				$this->_errorLogShow();
				return;
			default:
				return;
		}
	}

	private function _errorLogDel(){
		$file = RUNTIME_DIR.'/Logs/'.trim($_GET['fileName']);
		if(is_file($file) && unlink($file)){
			$this->_returnAjaxJson(array('status'=>1,'info'=>NULL,'data'=>NULL));
		}else{
			$this->_returnAjaxJson(array('status'=>0,'info'=>'no such file','data'=>NULL));
		}
	}

	private function _errorLogDelAll(){
		$files = $this->_errorLogFiles();
		$DirName = RUNTIME_DIR.'/Logs/';
		$check = true;
		foreach ($files as $val){
			$check &= unlink($DirName.$val);
		}
		if($check){
			$this->_utilMsg->showMsg('operation success');
		}
		$this->_utilMsg->showMsg('operation failed');
	}

	private function _errorLogShow(){
		$filename = trim($_GET['fileName']);
		$file = RUNTIME_DIR.'/Logs/'.$filename;
		if(is_file($file)){
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($file));
			Header("Content-Disposition: attachment; filename=".$filename);
			echo file_get_contents($file);
		}else{
			echo '文件不存在';
		}
	}

	private function _errorLogFiles(){
		$DirName = RUNTIME_DIR.'/Logs/';
		$FileNameArr = array ();
		if (is_dir ( $DirName )) {
			$DirName = realpath ( $DirName );
			$handle = opendir ( $DirName );
			if ($handle) {
				while ( false !== ($file = readdir ( $handle )) ) {
					if ($file != "." && $file != "..") {
						$FileNameArr [] = $file;
					}
				}
				closedir ( $handle );
			}
		}
		return $FileNameArr;
	}

	/**
	 * 设置在线状态
	 */
	public function actionSetOnline(){
		$utilOnline=$this->_getGlobalData('Util_Online','object');
		$utilOnline->setOnlineUser();		//设置在线用户
		$utilOnline->cleanOffOnlineUser();	//清除在线用户
		$userClass=$this->_utilRbac->getUserClass();
		$mailTotalCount=$userClass->getUserMail()->get_total();
		$mailNotReadCount=$userClass->getUserMail()->get_notReadCount();
		$ajaxArr=array(
			'mail_total'=>$mailTotalCount,
			'mail_not_read'=>$mailNotReadCount,
			'incomplete_order_num'=>$userClass['_incompleteOrderNum'],
		);
		$this->_cacheAutoCount=$this->_getGlobalData('Cache_AutoCount','object');
		$this->_cacheAutoCount->userCount();
		$this->_returnAjaxJson(array('status'=>1,'data'=>$ajaxArr));
	}

	/**
	 * 登录
	 */
	public function actionLogin() {
		if ($this->_isPost ()) {
			#------验证码------#
			$this->_loadCore('Help_ImgCode');
			$helpImgCode=new Help_ImgCode();
			if (!$helpImgCode->check($_POST['verify_code']))$this->_utilMsg->showMsg(Tools::getLang('LOGIN_ERRORAUTH',__CLASS__),-1);
			#------验证码------#

			$userName=trim($_POST['user_name']);
			$userInfo = $this->_modelUser->findByUserName ( $userName );
			if ($userInfo && $userInfo ['password'] == md5 ( $_POST ['password'] )) {//如果密码正确
				$this->_utilRbac->setLogin($userName);
				$utilOnline=$this->_getGlobalData('Util_Online','object');
				$utilOnline->setOnlineUser($userName);		//设置在线用户
				$this->_utilMsg->showMsg ( false, 1 ,Tools::url ( CONTROL, 'Index' ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('Login_ERRORPWD',__CLASS__) ,-2 );
			}
		} else {
			$this->_view->assign('global',array('title'=>Tools::getLang('TITLE','Common')));
			$this->_view->display();
		}
	}
	/**
	 * 超管登录其他用户的账号
	 */
	public function actionLoginOthers(){
		$userClass=$this->_getGlobalData('Util_Rbac','object')->getUserClass();
		if(in_array($userClass['_userName'],explode(',',MasterAccount))){	//判断是否超管
			$userName=trim($_REQUEST['user_name']);
			$userInfo = $this->_modelUser->findByUserName ( $userName );
			if($userInfo){
				$this->_utilRbac->setLogin($userName);
				$utilOnline=$this->_getGlobalData('Util_Online','object');
				$utilOnline->setOnlineUser($userName);		//设置在线用户
				$this->_utilMsg->showMsg ( false, 1 ,Tools::url ( CONTROL, 'Index' ) );
			}
		}else{
			$this->_utilMsg->showMsg ( '不是超级管理员' ,-2 );
		}
	}

	/**
	 * 退出登录
	 */
	public function actionLoginOut() {
		$this->_utilRbac->loginOut();
		$this->_utilMsg->showMsg ( false,1, PASSPORT_URL );
	}

	/**
	 * 更改语言
	 */
	public function actionChangeLang(){
		$langId=Tools::coerceInt($_GET['lang']);
		if (!in_array($langId,array(1,2)))$langId=1;
		setcookie('kefu_lang',$langId,CURRENT_TIME+60*60*6);
		$this->_utilMsg->showMsg(false);
	}



}