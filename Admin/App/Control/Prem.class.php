<?php
class Control_Prem extends Control {
	
	/**
	 * Model_Act
	 * @var Model_Act
	 */
	private $_modelAct;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Help_SqlSearch
	 * @var Help_SqlSearch
	 */
	private $_helpSqlSearch;
	
	/**
	 * Model_Menu
	 * @var Model_Menu
	 */
	private $_modelMenu;
	
	/**
	 * Model_Moudle
	 * @var Model_Moudle
	 */
	private $_modelMoudle;
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		Tools::import ( 'Model_Act' );
		$this->_modelAct = new Model_Act ();
		Tools::import ( 'Util_Msg' );
		$this->_utilMsg = new Util_Msg ();
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_helpSqlSearch = new Help_SqlSearch ();
	}
	
	private function _createUrl() {
		$this->_url ['Control_AddCtl'] = Tools::url ( CONTROL, 'AddCtl' );
		$this->_url ['Control_AddAct'] = Tools::url ( CONTROL, 'AddAct' );
		$this->_url ['Control_EditPrem'] = Tools::url ( CONTROL, 'EditPrem' );
		$this->_url ['Prem_EditCtl'] = Tools::url ( CONTROL, 'EditCtl' );
		$this->_url ['Prem_EditAct'] = Tools::url ( CONTROL, 'EditAct' );
		$this->_url ['Prem_Moudle_add']=Tools::url(CONTROL,'Moudle',array('doaction'=>'add'));
		$this->_url ['Prem_Moudle_cache']=Tools::url(CONTROL,'Moudle',array('doaction'=>'cache','value'=>$_GET['value']));
		$this->_url ['Prem_MoudleMain_cache']=Tools::url(CONTROL,'Moudle',array('doaction'=>'cache'));
		$this->_view->assign ( 'url', $this->_url );
	}
	
	/**
	 * 模块管理
	 */
	public function actionMoudle(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_moudleAdd();
				return ;
			}
			case 'edit' :{
				$this->_moudleEdit();
				return ;
			}
			case 'edit_role_moudle' :{	//编辑模块权限
				$this->_moudleEditRole();
				break;
			}
			case 'del' :{
				$this->_moudleDel();
				return ;
			}
			case 'act' :{
				$this->_moudleAct();
				return ;
			}
			case 'edit_role_act' :{
				$this->_moudleEditAct();
				return ;
			}
			case 'cache' :{
				$this->_moudleCache();
				return ;
			}
			default:{
				$this->_moudleIndex();
				return ;
			}
		}
	}
	
	/**
	 * 角色修改模块权限
	 */
	private function _moudleEditRole(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		if ($this->_isPost()){
			$info=$this->_modelMoudle->editRoleAct($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$roleValue=trim($_GET['role_value']);
			$act=$this->_modelMoudle->findAll();
			foreach ($act as &$list){
				if ($list['act']==RBAC_EVERYONE || strpos($list['act'],$roleValue)!==false){
					$list['selected']=true;
					$list['url_edit_act']=Tools::url(CONTROL,ACTION,array('doaction'=>'edit_role_act','role_value'=>$roleValue,'value'=>$list['value']));
				}
			}
			$this->_view->assign('dataList',$act);		
			$this->_view->set_tpl(array('body'=>'Prem/MoudleEditRole.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 修改角色ACT模块权限
	 */
	private function _moudleEditAct(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		if ($this->_isPost()){
			$info=$this->_modelMoudle->editMoudleAct($_POST);
//			print_r($info);
//			print_r($_POST);
//			die();
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$act=$this->_modelMoudle->getAct($_GET['value'],$_GET['role_value']);
			$this->_view->assign('dataList',$act);
			$this->_createUrl();
			$this->_view->set_tpl(array('body'=>'Prem/MoudleEditAct.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	private function _moudleCache(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$info=$this->_modelMoudle->createCache($_GET['value'],$_POST['act']);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _moudleIndex(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$dataList=$this->_modelMoudle->findAll();
		foreach ($dataList as &$list){
			$list['url_edit']=Tools::url(CONTROL,ACTION,array('doaction'=>'edit'));
			$list['url_del']=Tools::url(CONTROL,ACTION,array('doaction'=>'del','value'=>$list['value']));
			$list['url_act']=Tools::url(CONTROL,ACTION,array('doaction'=>'act','value'=>$list['value']));
		}
		$this->_view->assign('dataList',$dataList);
		$this->_view->set_tpl(array('body'=>'Prem/MoudleIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _moudleAdd(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$info=$this->_modelMoudle->add($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _moudleEdit(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$info=$this->_modelMoudle->edit($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _moudleDel(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$info=$this->_modelMoudle->del($_GET['value']);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _moudleAct(){
		$this->_modelMoudle=$this->_getGlobalData('Model_Moudle','object');
		$act=$this->_modelMoudle->getAct($_GET['value']);
		
		if ($act){
			foreach ($act as &$control){
				if (is_array($control['act']))$control['act']=implode(',',$control['act']);
				if ($control['class_methods']){
					foreach ($control['class_methods'] as &$method){
						if (is_array($method['act']))$method['act']=implode(',',$method['act']);
					}
				}
			}
		}
		$this->_view->assign('act',$act);
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Prem/MoudleAct.html'));
		$this->_createUrl();
		$this->_view->display();
	}
	
	/**
	 * 添加控制器
	 */
	public function actionAddCtl() {
		if ($this->_isPost ()) {
			$data=$this->_modelAct->addCtl($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		} else {
			$this->_helpSqlSearch->set_tableName ( $this->_modelAct->tName () );
			$this->_helpSqlSearch->set_conditions ( 'parent_id=0' );
			$sql = $this->_helpSqlSearch->createSql ();
			$dataList = $this->_modelAct->select ( $sql );
			foreach ( $dataList as &$value ) {
				$value ['url_edit'] = Tools::url ( CONTROL, 'EditCtl', array ('Id' => $value ['Id'] ) );
				$value ['url_del'] = Tools::url ( CONTROL, 'DelCtl', array ('Id' => $value ['Id'] ) );
				if ($value ['allow'] == RBAC_EVERYONE) {
					$value ['word_allow'] = '<font color="#ff0000">所有用户</font>';
				} else if (empty ( $value ['allow'] )) {
					$value ['word_allow'] = '暂无角色';
				} else {
					$value ['word_allow'] = $value ['allow'];
				}
			}
			$this->_view->assign ( 'dataList', $dataList );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	public function actionEditCtl() {
		if ($this->_isPost ()) {
			$actArr = $this->_modelAct->findByChild ( $_POST ['Id'] );
			$ctl = ucwords ( $_POST ['value'] );
			$ctlUpdate=array();
			$ctlUpdate['value']=$ctl;
			if ($_POST['rbac_everyone']){
				$ctlUpdate['allow']=RBAC_EVERYONE;
			}else {
				if (count($_POST['allow']))$ctlUpdate['allow']=implode(',',$_POST['allow']);
			}
			$this->_modelAct->update ( $ctlUpdate, "Id={$_POST['Id']}" );
			foreach ( $actArr as $value ) {
				list ( $ctlStr, $actStr ) = explode ( '_', $value ['value'] );
				$childValue = "{$ctl}_{$actStr}";
				$this->_modelAct->update ( array ('value' => $childValue ), "Id={$value['Id']}" );
			}
			$this->_utilMsg->showMsg ( '更新控制器成功', 1, Tools::url ( CONTROL, 'AddCtl' ) );
		} else {
			$data=$this->_modelAct->findById ( $_GET ['Id']);
			if ($data['allow']==RBAC_EVERYONE){
				$this->_view->assign('everyone',1);
			}
			$selectedRoles=explode(',',$data['allow']);
			$allRoles=$this->_modelAct->getAllRoles();
			$selectedUser=$this->_modelAct->getUserAct($data['value']);		
			$this->_view->assign('selectedRoles',$selectedRoles);
			$this->_view->assign('allRoles',$allRoles);
			$this->_view->assign('selectedUser',$selectedUser);
			$this->_view->assign ( 'data', $data );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	public function actionDelCtl() {
		$this->_modelAct->deleteCtlandChildAct ( $_GET ['Id'] );
		$this->_utilMsg->showMsg ( '删除控制器,并且删除下面所有方法成功', 1 );
	}
	
	/**
	 * 添加方法
	 */
	public function actionAddAct() {
		if ($this->_isPost ()) {
			$data=$this->_modelAct->addAct($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		} else {
			$dataList = $this->_modelAct->findAll ();
			$controlList = array ();
			$actionList = array ();
			foreach ( $dataList as $value ) {
				if ($value ['parent_id'] == 0) {
					array_push ( $controlList, $value );
				} else {
					array_push ( $actionList, $value );
				}
			}
			$controlList = $this->_modelAct->getTtwoArrConvertOneArr ( $controlList, 'Id', 'value' );
			foreach ( $actionList as &$value ) {
				if ($value ['allow'] == RBAC_EVERYONE) {
					$value ['word_allow'] = '<font color="#ff0000">所有用户</font>';
				} else if (empty ( $value ['allow'] )) {
					$value ['word_allow'] = '暂无角色';
				} else {
					$value ['word_allow'] = $value ['allow'];
				}
				$value ['url_edit'] = Tools::url ( CONTROL, 'EditAct', array ('Id' => $value ['Id'] ) );
				$value ['url_del'] = Tools::url ( CONTROL, 'DelAct', array ('Id' => $value ['Id'] ) );
				$value ['word_control'] = $controlList [$value ['parent_id']];
			}
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			$this->_view->assign ( 'actionList', $actionList );
			$this->_view->assign ( 'controlList', $controlList );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	/**
	 * 编辑方法
	 */
	public function actionEditAct() {
		if ($this->_isPost ()) {
			$updateArr=array();
			$updateArr['value']=$_POST ['value'];
			if ($_POST['rbac_everyone']){
				$updateArr['allow']=RBAC_EVERYONE;
			}else {
				if (count($_POST['allow']))$updateArr['allow']=implode(',',$_POST['allow']);
			}
			
			if ($this->_modelAct->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '编辑方法成功', 1, Tools::url ( CONTROL, 'AddAct' ) );
			} else {
				$this->_utilMsg->showMsg ( '编辑方法失败', - 2 );
			
			}
		} else {		
			$data=$this->_modelAct->findById ( $_GET ['Id']);
			if ($data['allow']==RBAC_EVERYONE){
				$this->_view->assign('everyone',1);
			}
			$selectedRoles=explode(',',$data['allow']);
			$allRoles=$this->_modelAct->getAllRoles();
			$selectedUser=$this->_modelAct->getUserAct($data['value']);		
			$this->_view->assign('selectedRoles',$selectedRoles);
			$this->_view->assign('allRoles',$allRoles);
			$this->_view->assign('selectedUser',$selectedUser);
			$this->_view->assign ( 'data', $data );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	/**
	 * 删除方法
	 */
	public function actionDelAct() {
		$this->_modelAct->deleteToId ( $_GET ['Id'] );
		$this->_utilMsg->showMsg ( '删除方法成功', 1 );
	}
	
	/**
	 * 建立缓存
	 */
	public function actionCreateAct() {
		$rbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		if ($rbac->createAct ()) {
			$this->_utilMsg->showMsg ( "缓存文件生成成功:{$rbac->get_cacheFile()}", 1, Tools::url ( CONTROL, 'AddCtl' ) );
		} else {
			$this->_utilMsg->showMsg ( "缓存文件生成失败:{$rbac->get_cacheFile()}", - 2, Tools::url ( CONTROL, 'AddCtl' ) );
		}
	}
	
	public function actionEditPrem() {
		if ($this->_isPost ()) {
			$roleValue = strtolower ( $_POST ['role_value'] );
			$selectIds = $_POST ['Id'];
			if (! $selectIds)
				$selectIds = array ();
			$dataList = $this->_modelAct->findAll ();
			foreach ( $dataList as &$value ) {
				if ($value ['allow'] == RBAC_EVERYONE)
					continue; //如果是所有用户将跳过不执行
				if (empty ( $value ['allow'] )) {
					$roles = array ();
				} else {
					$roles = explode ( ',', $value ['allow'] ); //获取当前模板的所有角色
				}
				$key = array_search ( $value ['Id'], $selectIds ); //搜索用户是否选中此模块
				if ($key === false) { //如果没有找到,就表示用户让角色对此模块没有权限,将更新此模块删除allow字段里这个角色
					$rolesKey = array_search ( $roleValue, $roles );
					if ($rolesKey !== false)
						unset ( $roles [$rolesKey] ); //如果有这个角色,将删除这个角色
					$roles = implode ( ',', $roles );
					$updateArr = array ('allow' => $roles );
				} else { //否则将加上这个角色,并且更新allow字段
					$rolesKey = array_search ( $roleValue, $roles );
					if ($rolesKey !== false)
						continue; //如果找到此值,就说明此模块已经有这个角色,不用做操作.
					array_push ( $roles, $roleValue );
					$roles = implode ( ',', $roles );
					$updateArr = array ('allow' => $roles );
				}
				$this->_modelAct->update ( $updateArr, "Id={$value['Id']}" );
			}
			$rbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$rbac->createAct ();
			$this->_utilMsg->showMsg ( '更新成功', 1 );
		} else {
			#------获得菜单项一维数组------#
			$this->_modelMenu = $this->_getGlobalData ( 'Model_Menu', 'object' );
			$menuList = $this->_modelMenu->findAll ();
			$menuArr = array ();
			foreach ( $menuList as $value ) {
				$menuArr [$value ['value']] = $value ['name'];
			}
			#------获得菜单项一维数组------#
			

			$roleValue = strtolower ( $_GET ['role_value'] );
			$dataList = $this->_modelAct->findAll ();
			$controlList = array ();
			$actionList = array ();
			$selectedList = array ();
			foreach ( $dataList as &$value ) {
				$value ['value'] = "{$menuArr[$value['value']]}.{$value['value']}";
				$roles = explode ( ',', $value ['allow'] );
				if (in_array ( $roleValue, $roles ) || $value ['allow'] == RBAC_EVERYONE)
					array_push ( $selectedList, $value ['Id'] );
				if ($value ['parent_id'] == 0) {
					array_push ( $controlList, $value );
				} else {
					array_push ( $actionList, $value );
				}
			}
			$controlList = $this->_modelAct->getTtwoArrConvertOneArr ( $controlList, 'Id', 'value' );
			
			$checkBox = '';
			foreach ( $controlList as $key => $value ) {
				$checkBox .= '<tr><td align="left">';
				$checked = in_array ( $key, $selectedList ) ? 'checked="checked"' : '';
				$checkBox .= "<input type='checkbox' value={$key} name='Id[]' {$checked} /><b>{$value}</b><hr />";
				foreach ( $actionList as $childValue ) {
					if ($childValue ['parent_id'] == $key) {
						$checked = in_array ( $childValue ['Id'], $selectedList ) ? 'checked="checked"' : '';
						$checkBox .= "&nbsp;&nbsp;&nbsp;→<input type='checkbox' value={$childValue['Id']} name='Id[]' {$checked} />{$childValue['value']}<br/>";
					}
				}
				$checkBox .= '</td></tr>';
			}
			$this->_view->assign ( 'checkBox', $checkBox );
			$this->_view->assign ( 'roleValue', $roleValue );
			$this->_view->assign ( 'css', $this->_view->get_curCss () );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		
		}
	
	}

}