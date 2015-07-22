<?php
/**
 * 技术管理模块.每日工作管理
 * @author PHP-朱磊. 
 *
 */
class Control_ProgramDateWork extends Program {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * 技术管理后台每日工作计划表
	 * @var Model_ProgramDatework
	 */
	private $_modelProgramDatework;
	
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
		$this->_url['ProgramDateWork_Index_add']=Tools::url(CONTROL,'Index',array('doaction'=>'add','zp'=>'Program'));
		$this->_view->assign('url',$this->_url);
	}
	
	
	public function actionIndex(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_add();
				break;
			}
			case 'finish' : {	//完成
				$this->_finish();
			}
			case 'edit' :{
				$this->_edit();
				break;
			}
			default:{
				$this->_index();
				break;
			}
		}
	}
	
	private function _index(){
		$users=$this->getItUsers();
		$selected=array();
		$progromGroup=$this->_getGlobalData('program/project');
		$progromGroup=Model::getTtwoArrConvertOneArr($progromGroup,'Id','name');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$this->_loadCore('Help_SqlSearch');
		$this->_modelProgramDatework=$this->_getGlobalData('Model_ProgramDatework','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelProgramDatework->tName());
		$helpSqlSearch->set_orderBy('start_time desc');
		if ($_REQUEST['user_id']){
			$helpSqlSearch->set_conditions("user_id={$_REQUEST['user_id']}");
			$selected['user_id']=$_REQUEST['user_id'];
		}
		if ($_REQUEST['group_id']){
			$helpSqlSearch->set_conditions("group_id={$_REQUEST['group_id']}");
			$selected['group_id']=$_REQUEST['group_id'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$helpSqlSearch->set_conditions('start_time between ' . strtotime($_REQUEST['start_tme'] . ' and '.strtotime($_REQUEST['end_time'])));
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$conditions=$helpSqlSearch->get_conditions();
		$helpSqlSearch->setPageLimit($_POST['page']);
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelProgramDatework->select($sql);
		if ($dataList){
			
			foreach ($dataList as &$list){
				$list['word_user_id']=$users[$list['user_id']];
				$list['is_over']=$list['actual_time']?true:false;
				$list['word_is_over']=$list['actual_time']?'完成':'未完成';
				$list['start_time']=date('Y-m-d H:i:s',$list['start_time']);
				$list['end_time']=date('Y-m-d H:i:s',$list['end_time']);
				$list['actual_time']=$list['actual_time']?date('Y-m-d H:i:s',$list['actual_time']):'';
				$list['word_group_id']=$progromGroup[$list['group_id']];
				$list['url_finish']=Tools::url(CONTROL,'Index',array('doaction'=>'finish','Id'=>$list['Id'],'zp'=>'Program'));
				$list['url_edit']=Tools::url(CONTROL,'Index',array('doaction'=>'edit','Id'=>$list['Id'],'zp'=>'Program'));
			}
			$this->_view->assign('dataList',$dataList);
			
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelProgramDatework->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}	
		$this->_view->assign('selected',$selected);
		$this->_view->assign('users',$users);
		$this->_view->assign('progromGroup',$progromGroup);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _add(){
		$time=array();
		$time['start_time']=date('Y-m-d 09:45:00',CURRENT_TIME);
		$startTimeCuo=strtotime($time['start_time']);
		$time['end_time']=date('Y-m-d H:i:s',strtotime('+8 hour +15 minute',$startTimeCuo));
		if ($this->_isPost()){
			$postArr=$time;
			$postArr['group_id']=$_POST['group_id'];
			$postArr['content']=$_POST['content'];
			$this->_modelProgramDatework=$this->_getGlobalData('Model_ProgramDatework','object');
			$info=$this->_modelProgramDatework->add($postArr);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$programGroup=$this->_getGlobalData('program/project');
			$programGroup=Model::getTtwoArrConvertOneArr($programGroup,'Id','name');
			$this->_view->assign('time',$time);
			$this->_view->assign('programGroup',$programGroup);
			$this->_utilMsg->createPackageNavBar();
			$this->_view->set_tpl(array('body'=>'ProgramDateWork/Add.html'));
			$this->_view->display();
		}
	}
	
	private function _finish(){
		$this->_modelProgramDatework=$this->_getGlobalData('Model_ProgramDatework','object');
		$info=$this->_modelProgramDatework->finish($_GET['Id']);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	private function _edit(){
		$this->_modelProgramDatework=$this->_getGlobalData('Model_ProgramDatework','object');
		if ($this->_isPost()){
			$info=$this->_modelProgramDatework->edit($_POST);
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}else {
			$dataList=$this->_modelProgramDatework->findById($_GET['Id']);
			$this->_view->assign('dataList',$dataList);
			$this->_utilMsg->createPackageNavBar();
			$this->_view->set_tpl(array('body'=>'ProgramDateWork/Edit.html'));
			$this->_view->display();
		}
	}
	
	
}