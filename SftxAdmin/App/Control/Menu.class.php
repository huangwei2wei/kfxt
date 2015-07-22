<?php
/**
 * 菜单管理
 * @author php-朱磊
 */
class Control_Menu extends Control {
	
	/**
	 * Model_Menu
	 * @var Model_Menu
	 */
	private $_modelMenu;
	
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
	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_modelMenu = $this->_getGlobalData('Model_Menu','object');
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_helpSqlSearch = new Help_SqlSearch ();
	}
	
	private function _createUrl() {
		$this->_url ['Menu_AddMain'] = Tools::url ( CONTROL, 'Menu',array('doaction'=>'addMain') );
		$this->_url ['Menu_AddChild'] = Tools::url ( CONTROL, 'Menu',array('doaction'=>'addChild') );
		$this->_url ['Menu_CreateCache'] = Tools::url ( CONTROL, 'Menu',array('doaction'=>'cache') );
		$this->_url ['Menu_EditMain'] = Tools::url ( CONTROL, 'Menu',array('doaction'=>'editMain') );
		$this->_url ['Menu_EditChild'] = Tools::url ( CONTROL, 'Menu' ,array('doaction'=>'editChild'));
		$this->_url ['Menu_UpdateChild'] = Tools::url(CONTROL,'Menu',array('doaction'=>'update'));
		$this->_view->assign ( 'url', $this->_url );
	}
	
	
	/**
	 * 菜单管理
	 */
	public function actionMenu(){
		switch ($_GET['doaction']){
			case 'update' :{
				$this->_update();
				return ;
			}
			case 'addMain' :{
				$this->_addMain();
				return ;
			}
			case 'editMain' :{
				$this->_editMain();
				return ;
			}
			case 'delMain' :{
				$this->_delMain();
				return ;
			}
			case 'cache' :{
				$this->_createCache();
				return ;
			}
			case 'addChild' :{
				$this->_addChild();
				return ;
			}
			case 'editChild' :{
				$this->_editChild();
				return ;
			}
			case 'delChild' :{
				$this->_delChild();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}
	
	private function _index() {
		$dataList = $this->_modelMenu->findByMainList ();
		foreach ( $dataList as &$value ) {
			$value ['actions'] = $this->_modelMenu->findByParentIdToChildList ( $value ['Id'] );
			foreach ( $value ['actions'] as &$childValue ) {
				$childValue ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $childValue ['Id'] ,'doaction'=>'delChild' ) );
				$childValue ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $childValue ['Id'], 'parent_id' => $childValue ['parent_id'],'doaction'=>'editChild' ) );
				$childValue ['word_status'] = $childValue ['status'] ? '显示' : '不显示';
			}
			$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'editMain' ) );
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'delMain' ) );
			$value ['word_status'] = $value ['status'] ? '显示' : '不显示';
		}
		$this->_view->assign ( 'dataList', $dataList );
		$this->_view->set_tpl(array('body'=>'Menu/Index.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 更新菜单子项
	 */
	private function _update(){
		if ($this->_isPost()){
			if ($_POST['Id']){
				$updateArr=array(
					'sort'=>Tools::coerceInt($_POST['sort']),
					'super_action'=>trim($_POST['super_action']));
				$id=Tools::coerceInt($_POST['Id']);
				if ($this->_modelMenu->update($updateArr,"Id={$id}")){
					$this->_utilMsg->showMsg(false);
				}else {
					$this->_utilMsg->showMsg('更新失败,请联系管理员',-2);
				}
			}else{
				$this->_utilMsg->showMsg('请选择您要更新的菜单',-1);
			}
		}
	}
	
	private function _addMain() {
		if ($this->_isPost ()) {
			$_POST ['status'] = $_POST ['status'] ? 1 : 0;
			if ($this->_modelMenu->add ( $_POST )) {
				$this->_utilMsg->showMsg (false);
			} else {
				$this->_utilMsg->showMsg ( '添加失败,可能已经有相同的标识', - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Menu/AddMain.html'));
			$this->_view->display ();
		}
	}
	
	private function _createCache() {
		if ($this->_modelMenu->createCache ()) {
			$this->_utilMsg->showMsg ( '缓存生成成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '缓存生成失败' - 2 );
		}
	}
	
	private function _addChild() {
		if ($this->_isPost ()) {
			$_POST ['status'] = $_POST ['status'] ? 1 : 0;
			if ($this->_modelMenu->add ( $_POST )) {
				$this->_utilMsg->showMsg ( false );
			} else {
				$this->_utilMsg->showMsg ( '添加失败,可能已经有相同的标识', - 2 );
			}
		} else {
			$this->_helpSqlSearch->set_tableName ( $this->_modelMenu->tName () );
			$this->_helpSqlSearch->set_conditions ( 'parent_id=0' );
			$sql = $this->_helpSqlSearch->createSql ();
			$mainList = $this->_modelMenu->select ( $sql );
			$mainList = $this->_modelMenu->getTtwoArrConvertOneArr ( $mainList, 'Id', 'name' );
			$this->_view->assign ( 'mainList', $mainList );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Menu/AddChild.html'));
			$this->_view->display ();
		}
	}
	
	private function _editChild() {
		if ($this->_isPost ()) {
			$_POST ['status'] = $_POST ['status'] ? 1 : 0;
			if ($this->_modelMenu->update ( array ('parent_id' => $_POST ['parent_id'], 'value' => $_POST ['value'], 'name' => $_POST ['name'], 'status' => $_POST ['status'] ), "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '更改成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '更改失败,可能已经有相同的标识', - 2 );
			}
		} else {
			$mainList = $this->_modelMenu->findByMainList ();
			$mainList = $this->_modelMenu->getTtwoArrConvertOneArr ( $mainList, 'Id', 'value' );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_view->assign ( 'defaultParentId', $_GET ['parent_id'] );
			$this->_view->assign ( 'mainList', $mainList );
			$this->_view->assign ( 'data', $this->_modelMenu->findById ( $_GET ['Id'] ) );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Menu/EditChild.html'));
			$this->_view->display ();
		}
	}
	
	private function _editMain() {
		if ($this->_isPost ()) {
			$_POST ['status'] = $_POST ['status'] ? 1 : 0;
			if ($this->_modelMenu->update ( array ('value' => $_POST ['value'], 'name' => $_POST ['name'], 'status' => $_POST ['status'] ), "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( '更改成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '更改失败,可能已经有相同的标识', - 2 );
			}
		} else {
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_view->assign ( 'data', $this->_modelMenu->findById ( $_GET ['Id'] ) );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Menu/EditMain.html'));
			$this->_view->display ();
		}
	}
	
	private function _delChild() {
		if ($this->_modelMenu->execute ( "delete from {$this->_modelMenu->tName()} where Id={$_GET['Id']}" )) {
			$this->_utilMsg->showMsg ( '删除成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}
	
	private function _delMain() {
		$this->_modelMenu->execute ( "delete from {$this->_modelMenu->tName()} where Id={$_GET['Id']}" );
		$this->_modelMenu->execute ( "delete from {$this->_modelMenu->tName()} where parent_id={$_GET['Id']}" );
		$this->_utilMsg->showMsg ( '删除成功', 1 );
	}
	
}