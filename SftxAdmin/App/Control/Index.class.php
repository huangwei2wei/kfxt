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

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}

	private function _createUrl() {
		$this->_url ['Index_Top'] = Tools::url ( CONTROL, 'Top' );
		$this->_url ['Index_Left'] = Tools::url ( CONTROL, 'Left' );
		$this->_url ['Index_Right'] = Tools::url ( CONTROL, 'Right' );
		$this->_url ['Index_Login'] = Tools::url ( CONTROL, 'Login' );
		$this->_url ['Index_LoginOut'] = Tools::url ( CONTROL, 'LoginOut' );
		$this->_url ['Index_SetOnline'] = Tools::url(CONTROL, 'SetOnline');
		$this->_url ['Default_VerifyCode'] = Tools::url('Default','VerifyCode');
		$this->_url ['Index_Right']=Tools::url(CONTROL,'Right');
		$this->_url ['NoReadUser_MailIndex']=Tools::url('User','Mail',array('is_read'=>'0'));
		$this->_url ['User_MailIndex']=Tools::url('User','Mail');
		$this->_url ['MyTask_Index']=Tools::url('MyTask','Index',array('order_status'=>1));	//未处理工单 
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
		$this->_view->assign('userClass',$userClass);
		$this->_view->display ( $this->_view->get_curPage () );

	}

	/**
	 * 后台左边菜单管理
	 */
	public function actionLeft() {
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
		if (count($userClass['_roles'])){
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
		}
		$this->_view->assign('userOeratorIds',$userOeratorIds);
		$this->_view->assign('userClass',$userClass);
		$this->_view->display ();
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
			if (!$helpImgCode->check($_POST['verify_code']))$this->_utilMsg->showMsg('您输入的验证码不正确',-1);
			#------验证码------#

			$userName=trim($_POST['user_name']);
			$userInfo = $this->_modelUser->findByUserName ( $userName );
			if ($userInfo ['password'] == md5 ( $_POST ['password'] )) {//如果密码正确
				$this->_utilRbac->setLogin($userName);
				$utilOnline=$this->_getGlobalData('Util_Online','object');
				$utilOnline->setOnlineUser($userName);		//设置在线用户
				$this->_utilMsg->showMsg ( false, 1 ,Tools::url ( CONTROL, 'Index' ) );
			} else {
				$this->_utilMsg->showMsg ( '您输入的密码不正确,或账号不存在' ,-2 );
			}
		} else {
			$this->_view->display ( $this->_view->get_curPage () );
		}
	}

	/**
	 * 退出登录
	 */
	public function actionLoginOut() {
		$this->_utilRbac->loginOut();
		$this->_utilMsg->showMsg ( false,1, Tools::url(CONTROL,'Login') );
	}

}