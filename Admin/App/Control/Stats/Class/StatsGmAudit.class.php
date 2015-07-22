<?php
/**
 * GM审核统计
 * @author PHP-朱磊
 *
 */
class Control_StatsGmAudit extends Stats {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_ApplyDataFrg
	 * @var Model_ApplyDataFrg
	 */
	private $_modelApplyDataFrg;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	public function actionIndex(){
		$users=$this->_getGlobalData('user_index_id');
		if ($this->_isPost()){
			$auditType=$this->_getGlobalData('frg_audit_type');
			
			array_unshift($auditType,'汇总');
			if (empty($_POST['start_date']) || empty($_POST['end_date']))$this->_utilMsg->showMsg('请设置开始时间与结束时间',-1,2);
			if ($_POST['audit_user_id'])$auditUserId=Tools::coerceInt($_POST['audit_user_id']);
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			$time=array('start'=>strtotime($_POST['start_date']),'end'=>strtotime($_POST['end_date']));
			$dataList=$this->_modelApplyDataFrg->stats($time,$auditUserId);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('displayTrue',true);
			$this->_view->assign('auditType',$auditType);
		}
		$this->_view->assign('users',$users);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	
	
}