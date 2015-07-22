<?php
/**
 * 用户模块
 * @author php-朱磊
 */
class Control_User extends Control {
	
	/**
	 * 角色表
	 * @var Model_Roles
	 */
	private $_modelRoles;
	
	/**
	 * 公司表
	 * @var Model_Company
	 */
	private $_modelCompany;
	/**
	 * 部门表
	 * @var Model_Department
	 */
	private $_modelDepartment;
	/**
	 * 用户表
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
	 * Model_UserMail
	 * @var Model_UserMail
	 */
	private $_modelUserMail;
	
	/**
	 * Model_UserProiorityOperator
	 * @var Model_UserProiorityOperator
	 */
	private $_modelUserProiorityOperator;
	
	/**
	 * Model_GameOperator
	 * @var Model_GameOperator
	 */
	private $_modelGameOperator;
	
	/**
	 * Model_Org
	 * @var Model_Org
	 */
	private $_modelOrg;
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelUser = $this->_getGlobalData('Model_User','object');
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
		$this->_modelRoles = $this->_getGlobalData('Model_Roles','object');
		$this->_modelDepartment =$this->_getGlobalData('Model_Department','object');
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelUserProiorityOperator = $this->_getGlobalData ( 'Model_UserProiorityOperator', 'object' );
	}
	/**
	 * 初始化url
	 */
	private function _createUrl() {
		$this->_url ['User_RolesAdd'] = Tools::url ( CONTROL, 'Roles',array('doaction'=>'add') );
		$this->_url ['User_DepartmentAdd'] = Tools::url ( CONTROL, 'Department',array('doaction'=>'add') );
		$this->_url ['User_Add'] = Tools::url ( CONTROL, 'User',array('doaction'=>'add') );
		$this->_url ['User_DepartmentCreateCache'] = Tools::url ( CONTROL, 'Department',array('doaction'=>'cache') );
		$this->_url ['User_UserAddOperator'] = Tools::url ( CONTROL, 'User',array('doaction'=>'addOperator') );
		$this->_url ['User_SortUserOperator'] = Tools::url ( CONTROL, 'User',array('doaction'=>'sortOperator') );
		$this->_url ['User_Edit'] = Tools::url ( CONTROL, 'User',array('doaction'=>'edit') );
		$this->_url ['User_OrgAdd'] = Tools::url ( CONTROL, 'Org',array('doaction'=>'add') );
		$this->_url ['User_OrgEdit']=Tools::url(CONTROL,'Org',array('doaction'=>'edit'));
		$this->_url ['User_OrgCreateCache']=Tools::url(CONTROL,'Org',array('doaction'=>'cache'));
		$this->_url ['User_CreateCache']=Tools::url(CONTROL,'User',array('doaction'=>'cache'));
		$this->_url ['User_RolesEdit']=Tools::url(CONTROL,'Roles',array('doaction'=>'edit'));
		$this->_url ['User_DepartmentEdit']=Tools::url(CONTROL,'Department',array('doaction'=>'edit'));
		$this->_url ['User_MailDel']=Tools::url(CONTROL,'Mail',array('doaction'=>'del'));
		$this->_view->assign ( 'url', $this->_url );
	}
	
	#------------------------------------------------用户管理------------------------------------------------#
	
	/**
	 * 用户管理方法(重构)
	 */
	public function actionUser(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_userAdd();
				return ;
			}
			case 'edit' :{
				$this->_userEdit();
				return ;
			}
			case 'del' :{
				$this->_userDel();
				return ;
			}
			case 'cache' :{//创建缓存
				$this->_userCache();
				return ;
			}
			case 'initialize' :{//初始化
				$this->_userInitialize();
				return ;
			}
			case 'managerOperator' :{//用户管理 
				$this->_userManagerOperator();
				return ;
			}
			case 'addOperator' :{//增加运营商
				$this->_userAddOperator();
				return ;
			}
			case 'sortOperator' :{//排序
				$this->_userSortOperator();
				return ;
			}
			case 'delOperator' :{
				$this->_userDelOperator();
				return ;
			}
			default:{
				$this->_userIndex();
				return ;
			}
		}
	}
	
	private function _userAdd(){
		if ($this->_isPost ()) {
			if ($_POST ['password'] != $_POST ['pwd_confirm'])
				$this->_utilMsg->showMsg ( '密码不一致', - 1 );
			$rolesList = implode ( ',', $_POST ['roles'] );
			$addArr = array ('service_id'=>$_POST['service_id'],'department_id' => $_POST ['department_id'], 'roles' => $rolesList, 'user_name' => $_POST ['user_name'], 'password' => md5 ( $_POST ['password'] ), 'nick_name' => $_POST ['nick_name'], 'date_created' => CURRENT_TIME, 'date_updated' => CURRENT_TIME );
			if ($this->_utilRbac->createUser ( $addArr )) {
				$this->_utilMsg->showMsg ( '添加用户成功', 1 ,Tools::url(CONTROL,ACTION));
			} else {
				$this->_utilMsg->showMsg ( '添加用户失败', - 2 );
			}
		} else {
			$departmentList = $this->_modelDepartment->findAll ();
			$departmentList = $this->_modelDepartment->getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
			$rolesList = $this->_modelRoles->findAll ();
			$rolesList = $this->_modelRoles->getTtwoArrConvertOneArr ( $rolesList, 'role_value', 'role_name' );
			$this->_view->assign ( 'rolesList', $rolesList );
			$this->_view->assign ( 'departmentList', $departmentList );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/UserAdd.html'));
			$this->_view->display ();
		}
	}
	
	private function _userEdit(){
		if ($this->_isPost ()) {
			$updateArr = array (
					'service_id'=>$_POST['service_id'],
					'department_id' => $_POST ['department_id'], 
					'roles' => implode ( ',', $_POST ['roles'] ), 
					'nick_name' => $_POST ['nick_name'], 
					'date_updated' => CURRENT_TIME,
					'order_vip_level'=>count($_POST['order_vip_level'])?implode(',',$_POST['order_vip_level']):'' );
			if ($_POST['password']){//更改密码
				if ($_POST['password']!=$_POST['pwd_confirm'])$this->_utilMsg->showMsg('两次密码输入不一致',-1,2);
				$updateArr['password']=md5($_POST['password']);
			}
			if ($this->_modelUser->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_modelUser->createCache();
				$this->_utilMsg->showMsg ( '修改用户资料成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '修改用户资料失败', 1 );
			}
		} else {
			$departmentList = $this->_modelDepartment->findAll ();
			$departmentList = $this->_modelDepartment->getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
			$rolesList = $this->_modelRoles->findAll ();
			$rolesList = $this->_modelRoles->getTtwoArrConvertOneArr ( $rolesList, 'role_value', 'role_name' );
			$dataList = $this->_modelUser->findById ( $_GET ['Id'] );
			$dataList ['roles'] = explode ( ',', $dataList ['roles'] );
			$this->_view->assign('selectedVipLevel',explode(',',$dataList['order_vip_level']));
			$this->_view->assign('vipLevel',array(0,1,2,3,4,5,6));
			$this->_view->assign ( 'dataList', $dataList );
			$this->_view->assign ( 'rolesList', $rolesList );
			$this->_view->assign ( 'departmentList', $departmentList );
			$this->_view->set_tpl(array('body'=>'User/UserEdit.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	
	private function _userDel(){
		if ($this->_modelUser->delById ( $_GET ['Id'] )) {
			$this->_modelUserProiorityOperator = $this->_getGlobalData ( 'Model_UserProiorityOperator', 'object' );
			$this->_modelUserProiorityOperator->delByUserId ( $_GET ['Id'] );
			$filePath = USERS_DIR . "/{$_GET['user_name']}.serialize.php";
			if (file_exists($filePath))unlink ( $filePath );	//如果文件存在就删除文件
			$this->_utilMsg->showMsg ( "删除用户：{$_GET['user_name']}成功!", 1 );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}
	
	/**
	 * 创建缓存
	 */
	private function _userCache(){
		if ($this->_modelUser->createCache()){
			$this->_utilMsg->showMsg('生成缓存成功',1);
		}else {
			$this->_utilMsg->showMsg('生成缓存失败',-2);			
		}
	}
	
	/**
	 * 用户初始化
	 */
	private function _userInitialize(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass($_GET['user_name']);
		$userMail=$userClass->getUserMail();
		$userMail['_notReadCount']=0;
		$userMail['_total']=0;
		$userClass['_orderNum']=array();
		$userClass['_replyNum']=array();
		$userClass->setUpdateInfo(3);
		$this->_utilMsg->showMsg(false);
	}
	
	private function _userManagerOperator(){
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameTypeList = $this->_getGlobalData ( 'game_type' );
		$gameTypeList = Model::getTtwoArrConvertOneArr ( $gameTypeList, 'Id', 'name' );
		
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findAll ();
		foreach ( $gameOperatorIndex as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
		}
		
		$userOperatorList = $this->_modelUserProiorityOperator->findByUserId ( $_GET ['Id'] );
		foreach ( $userOperatorList as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
			$value ['word_game_type_id'] = $gameTypeList [$value ['game_type_id']];
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('operator_id' => $value ['operator_id'], 'user_id' => $_GET ['Id'], 'game_type_id' => $value ['game_type_id'],'doaction'=>'delOperator' ) );
		}
		$this->_view->assign ( 'userOperatorList', $userOperatorList );
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_view->assign ( 'userId', $_GET ['Id'] );
		$this->_view->set_tpl(array('body'=>'User/UserManagerOperator.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	private function _userAddOperator(){
		if (count ( $_POST ['operator_id'] ) && $_POST ['game_type_id']) {
			foreach ( $_POST ['operator_id'] as $value ) {
				$this->_modelUserProiorityOperator->addUserOperator ( array ('user_id' => $_POST ['user_id'], 'game_type_id' => $_POST ['game_type_id'], 'operator_id' => $value ) );
			}
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( '添加运营商失败,请正确选择游戏类型和营商', - 2 );
		}
	}
	
	private function _userSortOperator(){
		$this->_modelUserProiorityOperator->updateSort ( $_POST ['priority_level'], $_POST ['user_id'] );
		$this->_utilMsg->showMsg ( false );
	}
	
	private function _userDelOperator(){
		if ($this->_modelUserProiorityOperator->delByOperatorId ( $_GET ['game_type_id'], $_GET ['operator_id'], $_GET ['user_id'] )) {
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}
	
	/**
	 * 用户主界面
	 */
	private function _userIndex(){
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelUser->tName());
		if ($_GET['department_id']){
			$helpSqlSearch->set_conditions("department_id={$_GET['department_id']}");
			$this->_view->assign('selectedDepartmentId',$_GET['department_id']);
		}
		
		if ($_GET['org_id']){
			$helpSqlSearch->set_conditions("org_id={$_GET['org_id']}");
			$this->_view->assign('selectedOrgId',$_GET['org_id']);
		}

		if ($_GET['user_name']){
			$helpSqlSearch->set_conditions("user_name='{$_GET['user_name']}'");
			$this->_view->assign('selectedUserName',$_GET['user_name']);
		}
		
		if ($_GET['nick_name']){
			$nickName=urldecode($_GET['nick_name']);
			$helpSqlSearch->set_conditions("nick_name like '%{$nickName}%'");
			$this->_view->assign('selectedNickName',$nickName);
		}
		
		$helpSqlSearch->set_orderBy('date_updated desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelUser->select($sql);
		$helpPage=new Help_Page(array('total'=>$this->_modelUser->findCount($conditions),'perpage'=>PAGE_SIZE));
		
		$departmentList = $this->_getGlobalData('department');
		$departmentList = Model::getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
		$orgList=$this->_getGlobalData('org');
		$orgList=Model::getTtwoArrConvertOneArr($orgList,'Id','name');
		$rolesList = $this->_modelRoles->findAll ();
		$rolesList = $this->_modelRoles->getTtwoArrConvertOneArr ( $rolesList, 'role_value', 'role_name' );
		
		if ($dataList) {
			foreach ( $dataList as &$value ) {
				$value ['word_department'] = $departmentList [$value ['department_id']];
				$value ['word_org_id'] = $value['org_id']?$orgList[$value['org_id']]:'暂无组别';
				//转换角色为中文显示
				if ($value ['roles']) {
					$value ['roles'] = explode ( ',', $value ['roles'] );
					$value ['word_roles'] = array ();
					foreach ( $value ['roles'] as $tmpRolesList ) {
						array_push ( $value ['word_roles'], $rolesList [$tmpRolesList] );
					}
					$value ['word_roles'] = implode ( ',', $value ['word_roles'] );
				} else {
					$value ['word_roles'] = '暂无角色';
				}
				$value ['date_created'] = date ( 'Y-m-d H:i', $value ['date_created'] );
				$value ['date_updated'] = date ( 'Y-m-d H:i', $value ['date_updated'] );
				$value ['url_operator_manage'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'managerOperator' ) );
				$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'edit' ) );
				$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'], 'user_name' => $value ['user_name'],'doaction'=>'del' ) );
				$value ['url_Initialize']=Tools::url(CONTROL,ACTION,array('user_name'=>$value['user_name'],'doaction'=>'initialize'));
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$orgList['0']='未分组';
		$orgList['']='所有';
		$this->_view->assign('selectOrgList',$orgList);
		$departmentList['']='所有';
		$this->_view->assign('selectDepartmentList',$departmentList);
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->set_tpl(array('body'=>'User/UserIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 邮件功能
	 */
	public function actionMail(){
		switch ($_GET['doaction']){
			case 'del' :{
				$this->_mailDel();
				return ;
			}
			case 'read' :{
				$this->_mailRead();
			}
			default:{
				$this->_mailIndex();
				return ;
			}
		}
	}
	
	/**
	 * 用户读取邮件 
	 */
	private function _mailRead(){
		if (!$this->_isAjax())return ;
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$userClass->readMail ( $_GET ['Id'] );
		$userClass->setUpdateInfo ( 2 ); //2表示只更新邮件
	}

	
	/**
	 * 用户邮件
	 */
	private function _mailIndex(){
		#------初始化------#
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_modelUserMail=$this->_getGlobalData('Model_UserMail','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$mailType=$this->_getGlobalData('mail_type');
		#------初始化------#
		
		$userClass=$this->_utilRbac->getUserClass();
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelUserMail->tName());
		$helpSqlSearch->set_conditions("user_id={$userClass['_id']}");
		
		if ($_GET['mail_type']!=''){
			$helpSqlSearch->set_conditions("type={$_GET['mail_type']}");
			$this->_view->assign('selectedMailType',$_GET['mail_type']);
		}
		
		if ($_GET['read']!=''){
			$helpSqlSearch->set_conditions("is_read='{$_GET['read']}'");
			$this->_view->assign('selectedRead',$_GET['read']);
		}
		
		if ($_GET['is_read']!=''){
			$helpSqlSearch->set_orderBy('is_read asc,create_time desc');
		}else {
			$helpSqlSearch->set_orderBy('create_time desc');
		}
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelUserMail->select($sql);
		
		if ($dataList){
			Tools::import('Util_FontColor');
			foreach ($dataList as &$list){
				$list['word_is_read']=Util_FontColor::getMailRead($list['is_read']);
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['word_type']=Util_FontColor::getMailType($list['type'],$mailType[$list['type']]);
			}
			$conditions=$helpSqlSearch->get_conditions();
			$helpPage=new Help_Page(array('total'=>$this->_modelUserMail->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
			$this->_view->assign('dataList',$dataList);
		}

		$mailType['']='所有';
		$this->_view->assign('selectMailType',$mailType);
		$this->_view->assign('selectRead',array('1'=>'已读','0'=>'未读',''=>'所有'));
		$this->_view->set_tpl(array('body'=>'User/MailIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 用户删除邮件
	 */
	private function _mailDel(){
		if ($this->_isPost()){
			$this->_modelUserMail=$this->_getGlobalData('Model_UserMail','object');
			$data=$this->_modelUserMail->batchDel($_POST['ids']);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}
	}
	
	#------------------------------------------------用户管理------------------------------------------------#
	
	/**
	 * 角色管理
	 */
	public function actionRoles(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_rolesAdd();
				return ;
			}
			case 'edit' :{
				$this->_rolesEdit();
				return ;
			}
			case 'del' :{
				$this->_rolesDel();
				return ;
			}
			default:{
				$this->_rolesIndex();	
				return ;
			}
		}
	}

	/**
	 * 用户角色
	 */
	private function _rolesIndex() {
		$dataList = $this->_modelRoles->findAll ();
		foreach ( $dataList as &$value ) {
			$value ['date_created'] = date ( 'Y-m-d H:i', $value ['date_created'] );
			$value ['date_updated'] = date ( 'Y-m-d H:i', $value ['date_updated'] );
			$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'] ,'doaction'=>'edit' ) );
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'] ,'doaction'=>'del' ) );
			$value ['url_edit_perm'] = Tools::url ( 'Prem', 'EditPrem', array ('role_value' => $value ['role_value'] ) );
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_view->set_tpl(array('body'=>'User/RolesIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	private function _rolesAdd() {
		if ($this->_isPost ()) {
			$addArr = array ('role_value' => $_POST ['role_value'], 'role_name' => $_POST ['role_name'], 'description' => $_POST ['description'], 'date_created' => CURRENT_TIME, 'date_updated' => CURRENT_TIME );
			if ($this->_modelRoles->add ( $addArr )) {
				$this->_utilMsg->showMsg ( '角色增加成功', 1,Tools::url(CONTROL,ACTION) );
			} else {
				$this->_utilMsg->showMsg ( '角色增加失败', - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/RolesAdd.html'));
			$this->_view->display ();
		}
	}
	
	private function _rolesEdit(){
		if ($this->_isPost()){
			$udpateArr=array(
					'role_value' => $_POST ['role_value'], 
					'role_name' => $_POST ['role_name'], 
					'description' => $_POST ['description'], 
					'date_updated' => CURRENT_TIME );
			if ($this->_modelRoles->update($udpateArr,"Id={$_POST['Id']}")){
				$this->_utilMsg->showMsg('更新角色成功',1,Tools::url(CONTROL,ACTION));
			}else {
				$this->_utilMsg->showMsg('更新角色失败',-2);
			}
		}else {
			$data=$this->_modelRoles->findById($_GET['Id']);
			$this->_view->assign('data',$data);
			$this->_view->set_tpl(array('body'=>'User/RolesEdit.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	private function _rolesDel(){
		if ($this->_modelRoles->delById($_GET['Id'])){
			$this->_utilMsg->showMsg('删除角色成功',1);
		}else {
			$this->_utilMsg->showMsg('删除角色失败',-2);
		}
	}
	
	
	/**
	 * 组别管理
	 */
	public function actionOrg(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_orgAdd();
				return ;
			}
			case 'edit' :{
				$this->_orgEdit();
				return ;
			}
			case 'del' :{
				$this->_orgDel();
				return ;
			}
			case 'cache' :{
				$this->_orgCreateCache();	
				return ;
			}
			default:{
				$this->_orgIndex();	
				return ;
			}
		}
	}
	
	/**
	 * 用户组别
	 */
	private function _orgIndex() {
		$this->_modelOrg = $this->_getGlobalData ( 'Model_Org', 'object' );
		$dataList = $this->_modelOrg->findAll ();
		if ($dataList){	
			foreach ($dataList as &$value){
				$value['total_num']=$this->_modelUser->findCount("org_id={$value['Id']}");
				$value['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'edit'));
				$value['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'del'));
			}
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_view->set_tpl(array('body'=>'User/OrgIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	private function _orgAdd() {
		if ($this->_isPost ()) {
			$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
			if ($this->_modelOrg->add ( array('name'=>$_POST['name']) )) {
				if ($_POST['users']){
					$orgId=$this->_modelOrg->returnLastInsertId();	//获取插入的id
					$userIds=implode(',',$_POST['users']);
					$this->_modelUser->update(array('org_id'=>$orgId),"Id in ({$userIds})");
					$this->_modelUser->createCache();	
				}	
				$this->_modelOrg->createCache();
				$this->_utilMsg->showMsg ( '组别增加成功', 1 );
			} else {
				$this->_utilMsg->showMsg ( '组别增加失败', - 2 );
			}
		} else {
			$users = $this->_modelUser->findOrgByUser ();
			$departmentList=$this->_getGlobalData('department');
			$departmentList=Model::getTtwoArrConvertOneArr($departmentList,'Id','name');
			if ($users){
				foreach ($users as &$value){
					$value['Detail']="{$value['nick_name']}[{$departmentList[$value['department_id']]}]";
				}
				$users = Model::getTtwoArrConvertOneArr ( $users, 'Id', 'Detail' );
			}
			$this->_view->assign ( 'users', $users );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/OrgAdd.html'));
			$this->_view->display ();
		}
	}
	
	private function _orgEdit(){
		$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
		if ($this->_isPost()){
			if ($this->_modelOrg->update(array('name'=>$_POST['name']),"Id={$_POST['Id']}")){
				$this->_modelUser->update(array('org_id'=>'0'),"org_id={$_POST['Id']}");	//首先将所有此组的用户更新为0
				if ($_POST['users']){
					$userIds=implode(',',$_POST['users']);
					$this->_modelUser->update(array('org_id'=>$_POST['Id']),"Id in ({$userIds})");
					$this->_modelUser->createCache();	
				}
				$this->_modelOrg->createCache();
				$this->_utilMsg->showMsg('编辑组别成功',1,Tools::url(CONTROL,'Org'));
			}else {
				$this->_utilMsg->showMsg('编辑组别失败',-2);
			}
		}else {
			$departmentList=$this->_getGlobalData('department');
			$departmentList=Model::getTtwoArrConvertOneArr($departmentList,'Id','name');
			$org=$this->_modelOrg->findById($_GET['Id']);
			$users=$this->_modelUser->findOrgByUser($_GET['Id']);
			if ($users){
				$selected=array();
				foreach ($users as &$value){
					if ($value['org_id']==$org['Id'])array_push($selected,$value['Id']);
					$value['Detail']="{$value['nick_name']}[{$departmentList[$value['department_id']]}]";
				}
				$users=Model::getTtwoArrConvertOneArr($users,'Id','Detail');	
			}
			$this->_view->assign('selected',$selected);
			$this->_view->assign('org',$org);
			$this->_view->assign('users',$users);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/OrgEdit.html'));
			$this->_view->display();
		}
	}
	
	private function _orgDel(){
		$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
		if ($this->_modelOrg->delById($_GET['Id'])){
			$this->_modelUser->update(array('org_id'=>'0'),"org_id={$_GET['Id']}");
			$this->_modelUser->createCache();			
			$this->_modelOrg->createCache();
			$this->_utilMsg->showMsg('删除组别成功',1);
		}else {
			$this->_utilMsg->showMsg('删除组别失败',-2);
		}
	}
	
	private function _orgCreateCache() {
		$this->_modelOrg=$this->_getGlobalData('Model_Org','object');
		if ($this->_modelOrg->createCache ()) {
			$this->_utilMsg->showMsg ( '更新缓存成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '更新缓存失败', - 2 );
		}
	}
	
	
	
	
	/**
	 * 部门管理
	 */
	public function actionDepartment(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_departmentAdd();
				return ;
			}
			case 'edit' :{
				$this->_departmentEdit();
				return ;
			}
			case 'del' :{
				$this->_departmentDel();
				return ;
			}
			case 'cache' :{
				$this->_departmentCreateCache();	
				return ;
			}
			default:{
				$this->_departmentIndex();	
				return ;
			}
		}
	}

	
	/**
	 * 部门
	 */
	private function _departmentIndex() {
		$dataList = $this->_modelDepartment->findAll ();
		foreach ( $dataList as &$value ) {
			$value ['date_created'] = date ( 'Y-m-d H:i', $value ['date_created'] );
			$value ['date_updated'] = date ( 'Y-m-d H:i', $value ['date_updated'] );
			$value ['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'edit'));
			$value ['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'del'));
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'User/DepartmentIndex.html'));
		$this->_view->display ();
	}
	
	private function _departmentAdd() {
		if ($this->_isPost ()) {
			$addArr = array ('name' => $_POST ['name'], 'description' => $_POST ['description'], 'date_created' => CURRENT_TIME, 'date_updated' => CURRENT_TIME );
			if ($this->_modelDepartment->add ( $addArr )) {
				$this->_modelDepartment->createCache();
				$this->_utilMsg->showMsg ( '部门增加成功', 1 ,Tools::url(CONTROL,'Department'));
			} else {
				$this->_utilMsg->showMsg ( '部门增加失败', - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/DepartmentAdd.html'));
			$this->_view->display ();
		}
	}
	
	/**
	 * 编辑部门
	 */
	private function _departmentEdit(){
		if ($this->_isPost ()) {
			$updateArr = array ('name' => $_POST ['name'], 'description' => $_POST ['description'], 'date_updated' => CURRENT_TIME );
			if ($this->_modelDepartment->update ( $updateArr,"Id={$_POST['Id']}" )) {
				$this->_modelDepartment->createCache();
				$this->_utilMsg->showMsg ( '部门更新成功', 1 ,Tools::url(CONTROL,'Department'));
			} else {
				$this->_utilMsg->showMsg ( '部门更新失败', - 2 );
			}
		} else {
			$data=$this->_modelDepartment->findById($_GET['Id']);
			$this->_view->assign('data',$data);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'User/DepartmentEdit.html'));
			$this->_view->display ();
		}
	}
	
	/**
	 * 删除部门
	 */
	private function _departmentDel(){
		if ($this->_modelDepartment->delById($_GET['Id'])){
			$this->_modelUser->update(array('department_id'=>'0'),"department_id={$_GET['Id']}");	//将这个部门的所有员工都更新为0,无部门
			$this->_modelDepartment->createCache();
			$this->_modelUser->createCache();
			$this->_utilMsg->showMsg('删除部门成功');
		}else {
			$this->_utilMsg->showMsg('删除部门失败',-2);
		}
	}
	
	private function _departmentCreateCache() {
		if ($this->_modelDepartment->createCache ()) {
			$this->_utilMsg->showMsg ( '更新缓存成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '更新缓存失败', - 1 );
		}
	}
	

}