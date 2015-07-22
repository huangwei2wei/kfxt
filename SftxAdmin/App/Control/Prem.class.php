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
		$this->_view->assign ( 'url', $this->_url );
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
					$value ['word_allow'] = '所有用户';
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
			$ctlUpdate = array ('value' => $ctl, 'allow' => $_POST ['allow'] );
			$this->_modelAct->update ( $ctlUpdate, "Id={$_POST['Id']}" );
			foreach ( $actArr as $value ) {
				list ( $ctlStr, $actStr ) = explode ( '_', $value ['value'] );
				$childValue = "{$ctl}_{$actStr}";
				$this->_modelAct->update ( array ('value' => $childValue ), "Id={$value['Id']}" );
			}
			$this->_utilMsg->showMsg ( '更新控制器成功', 1, Tools::url ( CONTROL, 'AddCtl' ) );
		} else {
			$ctlArr = $this->_modelAct->findById ( $_GET ['Id'] );
			$this->_view->assign ( 'data', $ctlArr );
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
					$value ['word_allow'] = '所有用户';
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
			if ($this->_modelAct->update ( array ('value' => $_POST ['value'], 'allow' => $_POST ['allow'] ), "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '编辑方法成功', 1, Tools::url ( CONTROL, 'AddAct' ) );
			} else {
				$this->_utilMsg->showMsg ( '编辑方法失败', - 2 );
			
			}
		} else {
			$this->_view->assign ( 'data', $this->_modelAct->findById ( $_GET ['Id'] ) );
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