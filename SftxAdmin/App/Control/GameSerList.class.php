<?php
class Control_GameSerList extends Control {
	
	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_OperatorList
	 * @var Model_OperatorList
	 */
	private $_modelOperatorList;
	
	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;
	
	/**
	 * Util_ApiSftx
	 * @var Util_ApiSftx
	 */
	private $_utilApiSftx;

	
	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_modelOperatorList = $this->_getGlobalData ( 'Model_OperatorList', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
	}
	
	private function _createUrl() {
		$this->_url ['GameSerList_Add'] = Tools::url ( CONTROL, 'Server',array('doaction'=>'add') );
		$this->_url ['GameSerList_Edit'] = Tools::url ( CONTROL, 'Server',array('doaction'=>'edit') );
		$this->_url ['GameSerList_CreateCache'] = Tools::url ( CONTROL, 'Server',array('doaction'=>'cache') );
		$this->_view->assign ( 'url', $this->_url );
	}
	
	/**
	 * 服务器管理
	 */
	public function actionServer(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_add();	
				return ;
			}
			case 'edit' :{
				$this->_edit();
				return ;
			}
			case 'del' :{
				$this->_del();	
				return ;
			}
			case 'cache' :{
				$this->_createCache();
				return ;
			}
			case 'online' :{//在线人数
				$this->_online();
				return ;
			}
			case 'startServer' :{//开启服务器
				$this->_startServer();
				return ;
			}
			case 'stopServer' :{//停止服务器通知
				$this->_stopServer();
				return ;
			}
			case 'downline' :{//强制下线
				$this->_downline();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}
	
	/**
	 * 服务器列表查看
	 */
	private function _index() {
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelGameSerList->tName () );
		$helpSqlSearch->set_conditions ( 'Id !=100 and Id !=200' );
		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
		}
		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		if ($_GET['server_name']){
			$helpSqlSearch->set_conditions("server_name like '%{$_GET['server_name']}%'");
			$this->_view->assign('selectedServerName',$_GET['server_name']);
		}
		$helpSqlSearch->setPageLimit ( $_GET ['page'] );
		$conditions = $helpSqlSearch->get_conditions ();
		$sql = $helpSqlSearch->createSql ();
		$serverList = $this->_modelGameSerList->select ( $sql );
		$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_modelOperatorList->findAll ( false );
		$operatorList = $this->_modelOperatorList->getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		if ($serverList) {
			foreach ( $serverList as &$value ) {
				$value ['word_game_type'] = $gameTypeList [$value ['game_type_id']];
				$value ['word_operator_name'] = $operatorList [$value ['operator_id']];
				$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'edit' ) );
				$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'del' ) );
			}
			$this->_loadCore ( 'Help_Page' );
			$helpPage = new Help_Page ( array ('total' => $this->_modelGameSerList->findCount ( $conditions ), 'perpage' => PAGE_SIZE ) );
			$this->_view->assign ( 'pageBox', $helpPage->show () );
			
		}
		
		$this->_view->assign ( 'dataList', $serverList );
		$gameTypeList['']='所有';
		$this->_view->assign('gameTypeList',$gameTypeList);
		$operatorList['']='所有';
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->set_tpl(array('body'=>'GameSerList/Index.html'));
		$this->_utilMsg->createNavBar ();
		$this->_view->display ();
	}
	
	/**
	 * 编辑服务器
	 */
	private function _edit() {
		if ($this->_isPost ()) {
			$updateArr=array (
				'send_msg_url' => $_POST ['send_msg_url'], 
				'game_type_id' => $_POST ['game_type'], 
				'operator_id' => $_POST ['operator_id'], 
				'server_name' => $_POST ['server_name'], 
				'server_url' => $_POST ['server_url'] ,
			);
			
			if ($_POST['modify_mark']){
				$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
				$getArr=array('ctl'=>'serverIdentify','act'=>'save');
				$postArr=array('identify'=>$_POST['marking'],'url'=>$_POST['server_url']);
				$this->_utilApiSftx->addHttp($_POST['Id'],$getArr,$postArr);
				$this->_utilApiSftx->send();
				$result=$this->_utilApiSftx->getResult();
				if ($result['status']==1){
					$updateArr['marking']=$_POST['marking'];
				}else {
					$msg=$result['info'];
				}
			}
			
			if ($this->_modelGameSerList->update ( $updateArr,"Id={$_POST['Id']}")) {
				$this->_utilMsg->showMsg ( '成功.但'.$msg, 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '更新失败', - 2 );
			}
		} else {
			$data = $this->_modelGameSerList->findById ( $_GET ['Id'], false );
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$this->_view->assign ( 'data', $data );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'gameTypeList', $gameTypeList );
			$this->_utilMsg->createNavBar ();
			$this->_view->set_tpl(array('body'=>'GameSerList/Edit.html'));
			$this->_view->display ();
		}
	}
	
	/**
	 * 删除服务器
	 */
	private function _del() {
		if ($this->_modelGameSerList->delById ( $_GET ['Id'] )) {
			$this->_utilMsg->showMsg ( '删除成功', 1, Tools::url ( CONTROL, ACTION ) );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}
	
	/**
	 * 显示服务器在线人数
	 */
	private function _online(){
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'external','act'=>'onlines2');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		$this->_returnAjaxJson(array('status'=>$data['status'],'msg'=>null,'data'=>$data['data']));
	}
	
	/**
	 * 启动服务
	 */
	private function _startServer(){
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'external','act'=>'startServerNotice');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		$this->_returnAjaxJson(array('status'=>$data['status'],'msg'=>'启动服务成功','data'=>null));
	}
	
	/**
	 * 停止服务
	 */
	private function _stopServer(){
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'external','act'=>'stopServerNotice');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		$this->_returnAjaxJson(array('status'=>$data['status'],'msg'=>'停止服务成功','data'=>null));
	}
	
	/**
	 * 强制下线
	 */
	private function _downline(){
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'external','act'=>'startServerForSepecialIPNotice');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		$this->_returnAjaxJson(array('status'=>$data['status'],'msg'=>'强制下线成功','data'=>null));
	}
	
	

	/**
	 * 添加服务器
	 */
	private function _add() {
		if ($this->_isPost ()) {
			$addArr = array (
				'game_type_id' => $_POST ['game_type'], 
				'operator_id' => $_POST ['operator_id'], 
				'server_name' => $_POST ['server_name'], 
				'marking' => $_POST ['marking'], 
				'server_url' => $_POST ['server_url'], 
				'send_msg_url' => $_POST ['send_msg_url'] );
			if ($this->_modelGameSerList->add ( $addArr )) {
				$this->_utilMsg->showMsg ( '添加成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '添加失败', - 2 );
			}
		} else {
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'gameTypeList', $gameTypeList );
			$this->_utilMsg->createNavBar ();
			$this->_view->set_tpl(array('body'=>'GameSerList/Add.html'));
			$this->_view->display ();
		}
	}
	
	private function _createCache() {
		if ($this->_modelGameSerList->createToCache ()) {
			$this->_utilMsg->showMsg ( '缓存生成成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '缓存生成失败', - 2 );
		}
	}
	
}