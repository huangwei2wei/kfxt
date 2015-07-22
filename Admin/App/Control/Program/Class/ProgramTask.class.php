<?php
/**
 * 技术管理绩效
 * @author php-朱磊
 *
 */
class Control_ProgramTask extends Program {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * 技术管理绩效表
	 * @var Model_ProgramTask
	 */
	private $_modelProgramTask;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function __construct(){	
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['ProgramTask_Add']=Tools::url(CONTROL,'Add',array('zp'=>'Program'));
		$this->_url['ProgramTask_Finish']=Tools::url(CONTROL,'Finish',array('zp'=>'Program'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 显示页面
	 */
	public function actionIndex(){
		$selected=array();
		$users=$this->getItUsers();
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		$efficiencyLevel=$this->_modelProgramTask->getEfficiencyLevel();
		$finishSpeed=$this->_modelProgramTask->getFinishSpeed();
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelProgramTask->tName());
		$helpSqlSearch->setPageLimit($_GET['page']);
		$helpSqlSearch->set_orderBy('Id desc');
		if ($_REQUEST['accept_user_id']){
			$helpSqlSearch->set_conditions("accept_user_id = {$_REQUEST['accept_user_id']}");
			$selected['accept_user_id']=$_REQUEST['accept_user_id'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$helpSqlSearch->set_conditions("assign_time between ".strtotime($_REQUEST['start_time'])." and ".strtotime($_REQUEST['end_time']));
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		if ($_REQUEST['efficiency_level']){
			$helpSqlSearch->set_conditions("efficiency_level = {$_REQUEST['efficiency_level']}");
			$selected['efficiency_level']=$_REQUEST['efficiency_level'];
		}
		if ($_REQUEST['difficulty']){
			$helpSqlSearch->set_conditions("difficulty = {$_REQUEST['difficulty']}");
			$selected['difficulty']=$_REQUEST['difficulty'];
		}
		if ($_REQUEST['finish_speed']){
			$helpSqlSearch->set_conditions("finish_speed = {$_REQUEST['finish_speed']}");
			$selected['finish_speed']=$_REQUEST['finish_speed'];
		}
		if ($_REQUEST['Id']){
			$helpSqlSearch->set_conditions("Id = '{$_REQUEST['Id']}'");
			$selected['Id']=$_REQUEST['Id'];
		}
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelProgramTask->select($sql);
		if ($dataList){
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelProgramTask->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
			foreach ($dataList as &$list){
				$list['word_efficiency_level']=$efficiencyLevel[$list['efficiency_level']];
				$list['word_assign_user_id']=$users[$list['assign_user_id']];
				$list['word_accept_user_id']=$users[$list['accept_user_id']];
				$list['assign_time']=date('Y-m-d H:i:s',$list['assign_time']);
				$list['start_time']=$list['start_time']?date('Y-m-d H:i:s',$list['start_time']):'';
				$list['end_time']=$list['end_time']?date('Y-m-d H:i:s',$list['end_time']):'';
				$list['url_edit']=Tools::url(CONTROL,'Edit',array('Id'=>$list['Id'],'zp'=>'Program'));
				$list['finish_speed']=$finishSpeed[$list['finish_speed']];
				if (!$list['start_time']){
					$list['cur_status']='未接收任务';
					$list['url_accept']=Tools::url(CONTROL,'Accept',array('Id'=>$list['Id'],'zp'=>'Program'));
				}else {
					$list['cur_status']='已经接收任务';
					$list['url_finish']=Tools::url(CONTROL,'Finish',array('Id'=>$list['Id'],'zp'=>'Program'));
				}
				if ($list['end_time']){
					$list['cur_status']='任务已完成';
					$list['url_audit']=Tools::url(CONTROL,'Audit',array('Id'=>$list['Id'],'zp'=>'Program'));
					unset($list['url_finish']);
				}
				if ($list['efficiency_scorce']){
					unset($list['url_audit']);
					$list['cur_status']='已评分';
				}
			}
			$this->_view->assign('dataList',$dataList);
		}
		$this->_view->assign('finishSpeed',$finishSpeed);
		$this->_view->assign('selected',$selected);
		$this->_view->assign('efficiencyLevel',$efficiencyLevel);
		$this->_view->assign('users',$users);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 增加任务
	 */
	public function actionAdd(){
		if ($this->_isPost()){
			$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
			$info=$this->_modelProgramTask->newTask($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$this->_view->assign('users',$this->getItUsers());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 接收任务
	 */
	public function actionAccept(){
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		$info=$this->_modelProgramTask->acceptTask($_GET['Id']);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	/**
	 * 编辑任务
	 */
	public function actionEdit(){
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		if ($this->_isPost()){
			$info=$this->_modelProgramTask->editTask($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else{
			$dataList=$this->_modelProgramTask->findById($_GET['Id']);
			$dataList['start_time']=date('Y-m-d H:i:s',$dataList['start_time']);
			$dataList['end_time']=date('Y-m-d H:i:s',$dataList['end_time']);
			$this->_view->assign('efficiencyLevel',$this->_modelProgramTask->getEfficiencyLevel());
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('users',$this->getItUsers());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 延时原因
	 */
	public function actionTimeoutCause(){
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		$dataList=$this->_modelProgramTask->findById($_GET['Id']);
		$users=$this->_getGlobalData('user_index_id');
		$dataList['word_assign_user_id']=$users[$dataList['assign_user_id']];
		$dataList['word_accept_user_id']=$users[$dataList['accept_user_id']];
		$dataList['actual_hour']=$this->_modelProgramTask->getWorkHour($dataList['start_time'],CURRENT_TIME);
		$dataList['start_time']=date('Y-m-d H:i:s',$dataList['start_time']);
		$dataList['end_time']=date('Y-m-d H:i:s',CURRENT_TIME);
		$this->_view->assign('dataList',$dataList);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	
	/**
	 * 完成任务
	 */
	public function actionFinish(){
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		$info=$this->_modelProgramTask->finishTask($_REQUEST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	public function actionAudit(){
		$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
		if ($this->_isPost()){
			$info=$this->_modelProgramTask->auditTask($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else{
			$users=$this->_getGlobalData('user_index_id');
			$dataList=$this->_modelProgramTask->findById($_GET['Id']);
			$dataList['start_time']=date('Y-m-d H:i:s',$dataList['start_time']);
			$dataList['end_time']=date('Y-m-d H:i:s',$dataList['end_time']);
			$dataList['word_assign_user_id']=$users[$dataList['assign_user_id']];
			$dataList['word_accept_user_id']=$users[$dataList['accept_user_id']];
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('users',$this->getItUsers());
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	

}