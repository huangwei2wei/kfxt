<?php
Tools::import('Control_BaseGm');

class Control_GameOperateLog extends BaseGm {

	/**
	 * Util_Msg;
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * 游戏后台操作日志
	 * @var ModelGameOperateLog
	 */
	private $_modelGameOperateLog;

	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_checkOperatorAct();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}

	private function _createUrl(){

		$this->_view->assign('url',$this->_url);
	}
	
	public function actionIndex(){
		
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes[0] = "全部";
		ksort($gameTypes);
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		$GameServerList=$this->_getGlobalData('gameser_list');
		$GameServerList=Model::getTtwoArrConvertOneArr($GameServerList,'Id','full_name');
		
		$this->_modelGameOperator=$this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$servers = $this->_modelGameOperator->getGmOptSev();
		$user=$this->_getGlobalData('user');
		$user=Model::getTtwoArrConvertOneArr($user,'Id','full_name');	
		$user[0]="全部";
		ksort($user);
		$GameOperateType = $this->_getGlobalData ( 'game_operate_type' );
		$GameOperateType[0] = "全部";
		ksort($GameOperateType);
		
		$selected['game_type_id'] = $_GET['game_type_id'];
		$selected['operator_id'] = $_GET['operator_id'];
		$selected['server_id']	= $_GET['server_id'];
		$selected['user'] = $_GET['user'];
		$selected['UserId'] = $_GET['UserId'];
		$selected['UserId'] = $_GET['UserId'];
		$selected['GameOperateType'] = $_GET['GameOperateType'];
		$selected['start_time'] = $_GET['start_time'];
		$selected['end_time'] = $_GET['end_time'];
		
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',json_encode($gameTypes));
		$this->_view->assign('operators',json_encode($operators));
		$this->_view->assign('servers',json_encode($servers));
		
		$this->_view->assign('user',$user);
		
		$this->_view->assign('GameOperateType',$GameOperateType);
		
		$this->_utilMsg->createNavBar();
		
		#查数据部分#
		$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelGameOperateLog->tName());

		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		if($_GET['game_type_id']>0){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
		}
		$_GET['operator_id'] = intval($_GET['operator_id']);
		if($_GET['operator_id']>0){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
		}		
		$_GET['server_id'] = intval($_GET['server_id']);
		if($_GET['server_id']>0){
			$helpSqlSearch->set_conditions("game_server_id={$_GET['server_id']}");
		}
		$_GET['user'] = intval($_GET['user']);
		if($_GET['user']>0){
			$helpSqlSearch->set_conditions("user_id={$_GET['user']}");
		}
		$_GET['UserId'] = intval($_GET['UserId']);
		if($_GET['UserId']>0){
			$helpSqlSearch->set_conditions("game_user_id={$_GET['UserId']}");
		}
		$_GET['GameOperateType'] = intval($_GET['GameOperateType']);
		if($_GET['GameOperateType']>0){
			$helpSqlSearch->set_conditions("operate_type={$_GET['GameOperateType']}");
		}
		$StartTime = strtotime($_GET['start_time']);
		$EndTime = strtotime($_GET['end_time']);
		if($StartTime && $EndTime){			
			$helpSqlSearch->set_conditions("create_time>={$StartTime} and create_time<={$EndTime}");
		}
		$keywork = trim($_GET['keywork']);
		if($keywork){
			$helpSqlSearch->set_conditions("info like '%$keywork%'");
		}
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($_GET['page']);
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelGameOperateLog->select($sql);
		foreach($dataList as &$sub){
			$sub['user'] = $user[$sub['user_id']];
			$sub['game_operator_server'] = $GameServerList[$sub['game_server_id']];
			$sub['operate_type'] = $GameOperateType[$sub['operate_type']];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['info'] = unserialize($sub['info']);
			$sub['AddString'] = $sub['info']['AddString'];
		}
		
		
		$this->_view->assign('dataList',$dataList);
		#查数据部分#
		$this->_loadCore('Help_Page');
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelGameOperateLog->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		
		
		$this->_view->display();
		
	}
	
	public function actionGetInfo(){
		$_GET['game_server_id'] = intval($_GET['game_server_id']);
		$_GET['game_user_id'] = intval($_GET['game_user_id']);
		$_GET['operate_type'] = intval($_GET['operate_type']);
		$_GET['page'] = max(1,intval($_GET['page']));
		$_GET['page_size'] = intval($_GET['page_size']);
		if(!$_GET['page_size'])$_GET['page_size'] =50;
		$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$this->_loadCore('Help_SqlSearch');//载入sql工具
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_field('Id,info,create_time');
		$helpSqlSearch->set_tableName($this->_modelGameOperateLog->tName());
		if($_GET['game_server_id']){
			$helpSqlSearch->set_conditions("game_server_id={$_GET['game_server_id']}");
		}
		if($_GET['game_user_id']){
			$helpSqlSearch->set_conditions("game_user_id={$_GET['game_user_id']}");
		}
		if($_GET['operate_type']){
			$helpSqlSearch->set_conditions("operate_type={$_GET['operate_type']}");
		}
		$helpSqlSearch->set_orderBy('Id desc');
		$helpSqlSearch->setPageLimit($_GET['page'],$_GET['page_size']);
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->_modelGameOperateLog->select($sql);
		foreach($dataList as &$sub){
			$sub['info'] = unserialize($sub['info']);
			$sub['info'] = $sub['info']['AddString'];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
		}
		$this->_returnAjaxJson($dataList);
	}
	
//	public function actionDoLog(){
//		$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
//		$tableName = $this->_modelGameOperateLog->tName();
//		
//		$sql = "select * from {$tableName}";
//		$data = $this->_modelGameOperateLog->select($sql);
//		$gameser_list=$this->_getGlobalData('gameser_list');
//		foreach($data as $sub){
//			$Id = $sub['Id'];
//			
//			$game_type_id = $gameser_list[$sub['game_server_id']]['game_type_id'];
//			$operator_id= $gameser_list[$sub['game_server_id']]['operator_id'];
//			$this->_modelGameOperateLog->update(array('game_type'=>$game_type_id,'operator_id'=>$operator_id),"Id={$Id}");
//		}
//	}

}