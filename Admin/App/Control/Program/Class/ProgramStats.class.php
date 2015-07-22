<?php
/**
 * 朱磊
 * @author PHP-朱磊
 *
 */
class Control_ProgramStats extends Program {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_ProgramProject
	 * @var Model_ProgramProject
	 */
	private $_modelProgramProject;
	
	/**
	 * Model_ProgramTask
	 * @var Model_ProgramTask
	 */
	private $_modelProgramTask;
	
	/**
	 * Model_ProgramStatsMonth
	 * @var Model_ProgramStatsMonth
	 */
	private $_modelProgramStatsMonth;
	
	/**
	 * Model_ProgramStatsQuarter
	 * @var Model_ProgramStatsQuarter
	 */
	private $_modelProgramStatsQuarter;
	
	public function __construct(){	
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['ProgramStats_MonthAssignMarks']=Tools::url(CONTROL,'MonthAssignMarks',array('zp'=>'Program'));
		$this->_url['Program_EditMonthMarks']=Tools::url(CONTROL,'EditMonthMarks',array('zp'=>'Program'));
		$this->_url['ProgramStats_QuarterAssignMarks']=Tools::url(CONTROL,'QuarterAssignMarks',array('zp'=>'Program'));
		$this->_url['Program_EditQuarterMarks']=Tools::url(CONTROL,'EditQuarterMarks',array('zp'=>'Program'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 月统计
	 */
	public function actionMonth(){
		$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
		$projects=$this->_modelProgramProject->findAllProjectUser();
		if ($this->_isPost()){//统计
			$this->_view->assign('display',true);
			$selected=array();
			if ($_POST['time'])$selected['time']=$_POST['time'];
			if ($_POST['user_id'])$selected['user_id']=$_POST['user_id'];
			$this->_view->assign('selected',$selected);
			$this->_modelProgramTask=$this->_getGlobalData('Model_ProgramTask','object');
			$userStatsData=$this->_modelProgramTask->statsMonth(strtotime($_POST['time']),$_POST['user_id']);
			$this->_view->assign('userStatsData',$userStatsData);
			$this->_view->assign('efficiencyList',$this->_modelProgramTask->getEfficiencyLevel());
			$this->_view->assign('finishSpeedList',$this->_modelProgramTask->getFinishSpeed());
			
			$this->_modelProgramStatsMonth=$this->_getGlobalData('Model_ProgramStatsMonth','object');
			$statsHistory=$this->_modelProgramStatsMonth->getUserHistory($_POST['user_id']);
			foreach ($statsHistory as &$list){
				$list['time']=date('Y-m',$list['time']);
				if ($list['time']==$_POST['time'])$this->_view->assign('isStats',true);
				
			}
			$this->_view->assign('history',$statsHistory);
		}
		$this->_view->assign('projects',$projects);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 月评分
	 */
	public function actionMonthAssignMarks(){
		$this->_modelProgramStatsMonth=$this->_getGlobalData('Model_ProgramStatsMonth','object');
		$info=$this->_modelProgramStatsMonth->add($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	/**
	 * 修改月统计评分
	 */
	public function actionEditMonthMarks(){
		$this->_modelProgramStatsMonth=$this->_getGlobalData('Model_ProgramStatsMonth','object');
		$info=$this->_modelProgramStatsMonth->edit($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}

	/**
	 * 季度统计
	 */
	public function actionQuarter(){
		$this->_modelProgramProject=$this->_getGlobalData('Model_ProgramProject','object');
		$projects=$this->_modelProgramProject->findAllProjectUser();
		$this->_modelProgramStatsQuarter=$this->_getGlobalData('Model_ProgramStatsQuarter','object');
		if ($this->_isPost()){//统计
			$this->_view->assign('display',true);
			$selected=array();
			if ($_POST['year'])$selected['year']=$_POST['year'];
			if ($_POST['user_id'])$selected['user_id']=$_POST['user_id'];
			$this->_view->assign('selected',$selected);
			$userStatsData=$this->_modelProgramStatsQuarter->stats($_POST['user_id'],$_POST['year'],$_POST['quarter']);
			$this->_view->assign('userStatsData',$userStatsData);
			$history=$this->_modelProgramStatsQuarter->findByUserId($_POST['user_id']);
			if (count($history)){
				foreach ($history as $list){
					if ($list['year']==$_POST['year'] && $list['quarter']==$_POST['quarter'])$this->_view->assign('isStats',true);
				}
			}
			$this->_view->assign('history',$history);
		}
		$this->_view->assign('quarterList',$this->_modelProgramStatsQuarter->getQuarter());
		$this->_view->assign('projects',$projects);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 季度评分
	 */
	public function actionQuarterAssignMarks(){
		$this->_modelProgramStatsQuarter=$this->_getGlobalData('Model_ProgramStatsQuarter','object');
		$info=$this->_modelProgramStatsQuarter->add($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
	
	/**
	 * 修改季度统计评分
	 */
	public function actionEditQuarterMarks(){
		$this->_modelProgramStatsQuarter=$this->_getGlobalData('Model_ProgramStatsQuarter','object');
		$info=$this->_modelProgramStatsQuarter->edit($_POST);
		$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
	}
}