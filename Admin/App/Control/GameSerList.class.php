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
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;

	
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
			default:{
				$this->_index();
				return ;
			}
		}
	}
	
	/**
	 * 服务器列表查看
	 */
	private function _index(){
		Tools::import('Util_ApiFrg');
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ($this->_modelGameSerList->tName());
		$helpSqlSearch->set_conditions ( 'Id !=100 and Id !=200' );
		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
		}
		
		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		if($_GET['timer']!=''){
			$_GET['timer'] = intval($_GET['timer']);
			$helpSqlSearch->set_conditions('timer = '.$_GET['timer']);
			$this->_view->assign('selectedtimer',$_GET['timer']);
		}
		if($_GET['Id']){
			$helpSqlSearch->set_conditions('Id='.intval($_GET['Id']));
			$this->_view->assign('selectedId',$_GET['Id']);
		}
		if($_GET['marking']){
			$helpSqlSearch->set_conditions("marking like '%{$_GET['marking']}%'");
			$this->_view->assign('selectedMarking',$_GET['marking']);
		}
		if($_GET['server_url']){
			$helpSqlSearch->set_conditions("server_url like '%{$_GET['server_url']}%'");
			$this->_view->assign('selectedServerUrl',$_GET['server_url']);
		}
		if ($_GET['server_name']){
			$helpSqlSearch->set_conditions("server_name like '%{$_GET['server_name']}%'");
			$this->_view->assign('selectedServerName',$_GET['server_name']);
		}
		$helpSqlSearch->set_orderBy('operator_id,ordinal,Id');
		$helpSqlSearch->setPageLimit ( $_GET ['page'] );
		$conditions = $helpSqlSearch->get_conditions ();
		$sql = $helpSqlSearch->createSql ();
		
		$serverList = $this->_modelGameSerList->select ( $sql );
		
		$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		if($_GET['game_type_id'] !=''){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$_GET['game_type_id']);
		}else{
			$operatorList = $this->_modelOperatorList->findAll ( false );
		}
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
			$this->_view->assign ( 'pageBox', $helpPage->show() );
		}
		
		$timer = array('0'=>'关闭','1'=>'开启',''=>'全部');
		$this->_view->assign ( 'timer', $timer );
		$this->_view->assign ( 'dataList', $serverList );
		$gameTypeList['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypeList',$gameTypeList);
		$operatorList['']=Tools::getLang('ALL','Common');
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
			
			//处理需要数据库信息的服务器
			$ext= array(
				'db_host'=>trim($_POST ['db_host']),
				'db_name'=>trim($_POST['db_name']),
				'db_user'=>trim($_POST ['db_user']),
				'db_pwd'=>trim($_POST ['db_pwd']),
				'db_port'=>trim($_POST ['db_port']),
			);
			foreach($ext as $key => $value){
				if(!$value){
					unset($ext[$key]);
				}
			}
					
			$data = array ('game_type_id' => intval($_POST ['game_type']),
							'operator_id' => intval($_POST ['operator_id']),
							'ordinal'=>intval($_POST['ordinal']),
							'server_name' => trim($_POST ['server_name']),
							'marking' => trim($_POST ['marking']),
							'server_url' => trim($_POST ['server_url']),
							'time_zone' => trim($_POST ['time_zone']),
							'timezone' => trim($_POST ['timezone']),
							'timer'=>intval($_POST['timer']),
							'data_url' => trim($_POST ['data_url']), 
			);
			if($ext){
				$data['ext'] = serialize($ext);
			}
			if ($this->_modelGameSerList->update ( $data, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_ERROR','Common'), - 2 );
			}
		} else {
			$data = $this->_modelGameSerList->findById ( $_GET ['Id'], false );
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			
			$data['ext'] = unserialize($data['ext']);
			
			$this->_view->assign ( 'data', $data );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'gameTypeList', $gameTypeList );
			$timer = array('0'=>'关闭','1'=>'开启');
			$this->_view->assign ( 'timer', $timer );
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
			$this->_utilMsg->showMsg ( Tools::getLang('DEL_SUCCESS','Common'), 1, 1 );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('DEL_ERROR','Common'), - 2 );
		}
	}
	

	/**
	 * 添加服务器
	 */
	private function _add() {
		if ($this->_isPost ()) {
			if ($_POST['batch_add']){
				$start=Tools::coerceInt($_POST['start']);
				$end=Tools::coerceInt($_POST['end']);
				$addArrs=array();
				for ($i=$start;$i<=$end;$i++){
					$addArrs[$i]=array(
						'game_type_id'=>intval($_POST['game_type']),
						'operator_id'=>intval($_POST['operator_id']),
						'ordinal'=>$i,
						'server_name'=>str_replace('{$var}',$i,$_POST['server_name']),
						'marking'=>str_replace('{$var}',$i,$_POST['marking']),
						'server_url'=>str_replace('{$var}',$i,$_POST['server_url']),
						'timezone' => trim($_POST ['timezone']),
						'time_zone'=>trim($_POST['time_zone']),
						'timer'=>intval($_POST['timer']),
						'data_url'=>str_replace('{$var}',$i,$_POST['data_url']),
					);
				}
				$reuslt=$this->_modelGameSerList->adds($addArrs);
			}else {
				$addArr = array (
					'game_type_id' => intval($_POST ['game_type']), 
					'operator_id' => intval($_POST ['operator_id']), 
					'ordinal'=>intval($_POST['ordinal']),
					'server_name' => trim($_POST ['server_name']), 
					'marking' => trim($_POST ['marking']), 
					'server_url' => trim($_POST ['server_url']), 
					'timezone' => trim($_POST ['timezone']),
					'time_zone'=>trim($_POST['time_zone']),
					'timer'=>intval($_POST['timer']),
					'data_url' => trim($_POST ['data_url']), 
				);
				$reuslt=$this->_modelGameSerList->add($addArr);
			}
			if ($reuslt) {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_ERROR','Common'), - 2 );
			}
		} else {
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			;
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'gameTypeList', $gameTypeList );
			$timer = array('0'=>'关闭','1'=>'开启');
			$this->_view->assign ( 'timer', $timer );
			$this->_utilMsg->createNavBar ();
			$this->_view->set_tpl(array('body'=>'GameSerList/Add.html'));
			$this->_view->display ();
		}
	}
	
	private function _createCache() {
		if ($this->_modelGameSerList->createToCache ()) {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1 );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_ERROR','Common'), - 2 );
		}
	}

}