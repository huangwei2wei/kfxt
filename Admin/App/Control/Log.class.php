<?php
class Control_Log extends Control {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_Log
	 * @var Model_Log
	 */
	private $_modelLog;
	
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	
	public function actionLog(){
		$users=$this->_getGlobalData('user');
		$users=Model::getTtwoArrConvertOneArr($users,'Id','nick_name');
		
		$this->_modelLog=$this->_getGlobalData('Model_Log','object');
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelLog->tName());
		$selected=array();
		if ($_GET['user_id']!=''){
			$helpSqlSearch->set_conditions("user_id={$_GET['user_id']}");
			$selected['user_id']=$_GET['user_id'];
		}
		
		if ($_GET['detail']!=''){
			if ($_GET['detail']==1){
				$helpSqlSearch->set_conditions("msg is not null");
				$selected['detail']=1;
			}else {
				$helpSqlSearch->set_conditions("msg is null");
				$selected['detail']=0;
			}
		}
		
		if ($_GET['start_time'] && $_GET['end_time']){
			$startTime=date('Ym',strtotime($_GET['start_time']));
			$endtime=date('Ym',strtotime($_GET['end_time']));
			if ($startTime!=$endtime)$this->_utilMsg->showMsg(Tools::getLang('DATE_ERROR',__CLASS__),-1,2);
			
			$this->_modelLog->setTableName("log_{$startTime}");
			$helpSqlSearch->set_tableName($this->_modelLog->tName());
			$startTime=strtotime($_GET['start_time']);
			$endtime=strtotime($_GET['end_time']);
			$helpSqlSearch->set_conditions("`time` between {$startTime} and {$endtime}");
			$selected['start_time']=$_GET['start_time'];
			$selected['end_time']=$_GET['end_time'];
		}
		if ($_GET['control']){
			$helpSqlSearch->set_conditions("control='".strtolower($_GET['control'])."'");
			$selected['control']=$_GET['control'];
		}
		if ($_GET['action']){
			$helpSqlSearch->set_conditions("action='".strtolower($_GET['action'])."'");
			$selected['action']=$_GET['action'];
		}
		if ($_GET['doaction']){
			$helpSqlSearch->set_conditions("doaction='".strtolower($_GET['doaction'])."'");
			$selected['doaction']=$_GET['doaction'];
		}
		$helpSqlSearch->setPageLimit($_GET['page']);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelLog->select($sql);
		if ($dataList){
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelLog->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
			foreach ($dataList as &$list){
				$list['word_user_id']=$users[$list['user_id']];
				$list['time']=date('Y-m-d H:i:s',$list['time']);
				$list['ip']=long2ip($list['ip']);
			}
			$this->_view->assign('dataList',$dataList);
		}
		
		$this->_view->assign('selectDetal',Tools::getLang('SELECT_DETAIL',__CLASS__));
		$this->_view->assign('users',$users);
		$this->_view->assign('selected',$selected);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
}