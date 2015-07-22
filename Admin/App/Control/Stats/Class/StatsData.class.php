<?php
/**
 * 数据统计
 * @author php-朱磊
 *
 */
class Control_StatsData extends Stats {
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['Stats_DataDetail']=Tools::url(CONTROL,'DataDetail',array('zp'=>'Stats'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 环比统计
	 */
	public function actionLinkRelative(){
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameTypeList = $this->_getGlobalData ( 'game_type' );
		$gameTypeList = Model::getTtwoArrConvertOneArr ( $gameTypeList, 'Id', 'name' );
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findAll ();
		foreach ( $gameOperatorIndex as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
		}
		
		if ($this->_isPost()){
			$startDay=intval($_POST['start_day']);//开始时间的天数
			$endDay=intval($_POST['end_day']);	//结束时间的天数
			if ($startDay<0 || $endDay<0)$this->_utilMsg->showMsg('统计的天数不能小于零',-2);
			if (empty($_POST['start_date_first']) || empty($_POST['end_date_first']) || empty($_POST['start_date_last']) || empty($_POST['end_date_last']))$this->_utilMsg->showMsg('统计日期和环比日期不能为空',-2);
			if (empty($_POST['game_type_id']) || !count($_POST['operator_id']) )$this->_utilMsg->showMsg('请选译游戏,和运营商',-2);
			$selectedTime=array(
				'start_date_first'=>$_POST['start_date_first'],
				'start_date_last'=>$_POST['start_date_last'],
				'end_date_first'=>$_POST['end_date_first'],
				'end_date_last'=>$_POST['end_date_last'],
			);
			$this->_view->assign('selectedGameTypeId',$_POST['game_type_id']);
			$selectedOperatorList=array();
			foreach ($_POST['operator_id'] as $operatorId){
				if (array_key_exists($operatorId,$operatorList)){
					$selectedOperatorList[$operatorId]=$operatorList[$operatorId];
				}
			}
			$this->_view->assign('selectedOperatorIds',$_POST['operator_id']);
			$this->_view->assign('selectedOperatorList',$selectedOperatorList);
			$this->_view->assign('selectedTime',$selectedTime);
			
			
			$startDate['first']=strtotime($_POST['start_date_first']);
			$startDate['last']=strtotime($_POST['start_date_last']);
			$endDate['first']=strtotime($_POST['end_date_first']);		
			$endDate['last']=strtotime($_POST['end_date_last']);	
			
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$this->_modelReplyQulity=$this->_getGlobalData('Model_ReplyQulity','object');
			$workOrderStartTotal=$this->_modelWorkOrder->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$startDate);
			$workOrderEndTotal=$this->_modelWorkOrder->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$endDate);

			$workOrderQaStartTotal=$this->_modelWorkOrderQa->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$startDate);
			$workOrderQaEndTotal=$this->_modelWorkOrderQa->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$endDate);
			$replyQulityStartTotal=$this->_modelReplyQulity->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$startDate);
			$replyQulityEndTotal=$this->_modelReplyQulity->getStatsNum($_POST['game_type_id'],$_POST['operator_id'],$endDate);
			
			if (!$workOrderQaStartTotal)$workOrderQaStartTotal=array();
			if (!$replyQulityStartTotal)$replyQulityStartTotal=array();
			if (!$workOrderStartTotal)$workOrderStartTotal=array();
			$startTotal=array_merge($workOrderQaStartTotal,$replyQulityStartTotal,$workOrderStartTotal);
			
			if (is_array($workOrderQaEndTotal) && is_array($replyQulityEndTotal) && is_array($workOrderEndTotal));
			if (!$workOrderQaEndTotal)$workOrderQaEndTotal=array();
			if (!$replyQulityEndTotal)$replyQulityEndTotal=array();
			if (!$workOrderEndTotal)$workOrderEndTotal=array();
			$endTotal=array_merge($workOrderQaEndTotal,$replyQulityEndTotal,$workOrderEndTotal);
			
			$linkRelative=array();
			$linkRelative['total']=$startTotal['total']?($endTotal['total']/$startTotal['total']-1)*100:'∞';
			for ($i=0;$i<=3;$i++){
				$linkRelative['ev'][$i]=$startTotal['ev'][$i]?($endTotal['ev'][$i]/$startTotal['ev'][$i]-1)*100:'∞';
			}
			$linkRelative['cp']["3"]=$startTotal['cp']["3"]?($endTotal['cp']["3"]/$startTotal['cp']["3"]-1)*100:'∞';
			$linkRelative['cp']["32"]=$startTotal['cp']["32"]?($endTotal['cp']["32"]/$startTotal['cp']["32"]-1)*100:'∞';
			//print_r($startTotal);
			$linkRelative['reply_total']=$startTotal['reply_total']?($endTotal['reply_total']/$startTotal['reply_total']-1)*100:'∞';
			$linkRelative['timeout_num']=$startTotal['timeout_num']?($endTotal['timeout_num']/$startTotal['timeout_num']-1)*100:'∞';
			$linkRelative['no_timeout_num']=$startTotal['no_timeout_num']?($endTotal['no_timeout_num']/$startTotal['no_timeout_num']-1)*100:'∞';
			$linkRelative['quality_num']=$startTotal['quality_num']?($endTotal['quality_num']/$startTotal['quality_num']-1)*100:'∞';
			$linkRelative['no_quality_num']=$startTotal['no_quality_num']?$endTotal['no_quality_num']/$startTotal['no_quality_num']-1:'∞';
			for ($i=-6;$i<=2;$i++){
				if ($i==0)continue;
				$linkRelative['option_num'][$i]=$startTotal['option_num'][$i]?($endTotal['option_num'][$i]/$startTotal['option_num'][$i]-1)*100:'∞';
			}
			for ($i=1;$i<=5;$i++){
				$linkRelative['status_num'][$i]=$startTotal['status_num'][$i]?($endTotal['status_num'][$i]/$startTotal['status_num'][$i]-1)*100:'∞';
			}
			$linkRelative['deduction']=$endTotal['deduction']?($endTotal['deduction']/$startTotal['deduction']-1)*100:'∞';
			$linkRelative['bonus']=$endTotal['bonus']?($endTotal['bonus']/$startTotal['bonus']-1)*100:'∞';
			$this->_view->assign('linkRelative',$linkRelative);

			$this->_view->assign('startTotal',$startTotal);
			$this->_view->assign('endTotal',$endTotal);
			$this->_view->assign('displayTrue',true);
		}
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display ();
	}

	
	/**
	 * 数据统计 
	 */
	public function actionData(){
// 		ini_set("display_errors", "On");
// 		error_reporting(E_ALL | E_STRICT);
		
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$orgList=$this->_getGlobalData('org');
		$allOrgUser=$this->_modelUser->findSetOrgByUser();
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$this->_view->assign('game_type',$gameTypes);
		$utilRbac = $this->_getGlobalData('Util_Rbac','object');
		$operators = $utilRbac->getOperatorActList();	//个人授权可操作的运营商
		$this->_view->assign('operators',$operators);
		
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','nick_name');
				$value['user']=$curOrgUser;
			}
		}
		$this->_view->assign('orgList',$orgList);
		
		if ($this->_isPost()){
			@ini_set ('memory_limit', '512M');
			if (count($_POST['check_users']) && $_POST['start_date'] && $_POST['end_date']&&count($_POST['operator_id'])){//必须保证有搜索项而且还选择了用户才会开开始搜索
				$date=array();
				$this->_view->assign('selectedTime',array('start'=>$_POST['start_date'],'end'=>$_POST['end_date']));
				$this->_view->assign('selectedUsers',$_POST['check_users']);
				$date['start']=strtotime($_POST['start_date']);//开始时间
				$date['end']=strtotime($_POST['end_date']);//结束时间 
				if ($date['start']>$date['end'])$this->_utilMsg->showMsg('开始时间不能大于结束时间',-2);
				$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
				$statusList=$this->_modelWorkOrder->statsDetail(array('start'=>strtotime($_POST['start_date']),'end'=>strtotime($_POST['end_date'])),$_POST['check_users'],$_POST['game_type'],$_POST['operator_id']);
				$this->_view->assign('dataList',$statusList);
				$this->_view->assign('users',$this->_getGlobalData('user_index_id'));
			}else {
				$this->_utilMsg->showMsg('请选择时间,用户,运营商',-2);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		if ($_POST['xls']){
			Tools::import('Util_ExportExcel');
			$this->_utilExportExcel=new Util_ExportExcel('用户详细统计','Excel/StatsUser',$statusList);
			$this->_utilExportExcel->outPutExcel();
		}else {
			$this->_view->display();
		}
	}
	
	/**
	 * 数据统计详细 ajax
	 */
	public function actionDataDetail(){
		$date=array();
		ini_set ('memory_limit', '256M');
		if ($_POST['date']=='total'){
			$date['start']=strtotime($_POST['start_date']);
			$date['end']=strtotime($_POST['end_date']);
		}else {
			$date['start']=strtotime($_POST['date']);
			$date['end']=strtotime('+1 day',$date['start']);
		}
		if ($_POST['key']=='total'){
			$users=$_POST['check_users'];
		}else {
			$users=$_POST['key'];
		}
		Tools::import('Util_Stats');
		$utilStats=new Util_Stats(Util_Stats::STATS_USER);
		$utilStats->setStatsTime($date);
		$utilStats->setUser($users);
		$utilStats->setGameType($_POST['game_type']);
		$dataList=$utilStats->stats();
		$tableTitle=$utilStats->getTableTitle();
		$tableTitle['time']=$date;
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('tableTitle',$tableTitle);
		$this->_view->display();
	}
}