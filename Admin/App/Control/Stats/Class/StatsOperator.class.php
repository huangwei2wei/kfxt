<?php
/**
 * 运营商统计统计
 * @author php-朱磊
 *
 */
class Control_StatsOperator extends Stats {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_url['StatsOperator_Day']=Tools::url(CONTROL,'Day',array('zp'=>'Stats'));
		$this->_url['StatsOperator_DayDetail']=Tools::url(CONTROL,'DayDetail',array('zp'=>'Stats'));
		$this->_url['StatsOperator_HourDetail']=Tools::url(CONTROL,'HourDetail',array('zp'=>'Stats'));
		$this->_url['StatsOperator_Hour']=Tools::url(CONTROL,'Hour',array('zp'=>'Stats'));
		$this->_view->assign('url',$this->_url);
	}
	
	
	/**
	 * 按天统计
	 */
	public function actionDay(){
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
			if (empty($_POST['start_date']) || empty($_POST['end_date']))$this->_utilMsg->showMsg('统计日期不能为空',-2);
			if (empty($_POST['game_type_id']) || !count($_POST['operator_id']) )$this->_utilMsg->showMsg('请选译游戏,和运营商',-2);
			
			$selectedTime=array('start_date'=>$_POST['start_date'],'end_date'=>$_POST['end_date'],);
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
			
			
			$date['start']=strtotime($_POST['start_date']);
			$date['end']=strtotime($_POST['end_date']);	
			
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$this->_modelReplyQulity=$this->_getGlobalData('Model_ReplyQulity','object');
			$this->_modelVerify=$this->_getGlobalData('Model_Verify','object');
			$workOrderTotal=$this->_modelWorkOrder->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$workOrderQaTotal=$this->_modelWorkOrderQa->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$replyQulityTotal=$this->_modelReplyQulity->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$bugListTotal=$this->_modelVerify->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$total=Tools::getdateArr($_POST['start_date'],$_POST['end_date']);
			$total['total']=true;
			foreach ($total as $key=>$value){
				if (!is_array($workOrderTotal[$key]))$workOrderTotal[$key]=array();
				if (!is_array($workOrderQaTotal[$key]))$workOrderQaTotal[$key]=array();
				if (!is_array($replyQulityTotal[$key]))$replyQulityTotal[$key]=array();
				if (!is_array($bugListTotal[$key]))$bugListTotal[$key]=array();
				$total[$key]=array_merge_recursive($workOrderTotal[$key],$workOrderQaTotal[$key],$replyQulityTotal[$key],$bugListTotal[$key]);
				$total[$key]['url_detail']=Tools::url(CONTROL,ACTION,array('doaction'=>'detail','key'=>$key,'start_time'=>$_POST['start_date'],'end_time'=>$_POST['end_date']));
			}
			krsort($total);
			$this->_view->assign('total',$total);
			$this->_view->assign('displayTrue',true);
		}

		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 按天统计_ajax
	 */
	public function actionDayDetail(){
		Tools::import('Util_Stats');
		$utilStats=new Util_Stats(Util_Stats::STATS_OPERATOR);
		$key=$_POST['detail_key'];
		$time=array();
		if ($key=='total'){
			$time['start']=strtotime($_POST['start_date']);
			$time['end']=strtotime($_POST['end_date']);
		}else {
			$time['start']=strtotime($_POST['detail_key']);
			$time['end']=$time['start']+(24*60*60-1);
		}
		$utilStats->setStatsTime($time);
		$utilStats->setOperator(array('game_type_id'=>$_POST['game_type_id'],'operator'=>$_POST['operator_id']));
		$dataList=$utilStats->stats();
		$this->_view->assign('dataList',$dataList);
		$tableTitle=$utilStats->getTableTitle($_POST['game_type_id']);
		$tableTitle['time']=$time;
		$this->_view->assign('tableTitle',$tableTitle);
		$this->_view->display('Stats/StatsOperator/OperatorDetail');		
	}
	
	/**
	 * 按小时统计 
	 */
	public function actionHour(){
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
			if (empty($_POST['start_date']) || empty($_POST['end_date']))$this->_utilMsg->showMsg('统计日期不能为空',-2);
			if (empty($_POST['game_type_id']) || !count($_POST['operator_id']) )$this->_utilMsg->showMsg('请选译游戏,和运营商',-2);
			
			$curDate=$_POST['date'];
			$this->_view->assign('selectedGameTypeId',$_POST['game_type_id']);
			$selectedOperatorList=array();
			foreach ($_POST['operator_id'] as $operatorId){
				if (array_key_exists($operatorId,$operatorList)){
					$selectedOperatorList[$operatorId]=$operatorList[$operatorId];
				}
			}
			$this->_view->assign('selectedOperatorIds',$_POST['operator_id']);
			$this->_view->assign('selectedOperatorList',$selectedOperatorList);
			$this->_view->assign('selectedTime',array('start_date'=>$_POST['start_date'],'end_date'=>$_POST['end_date']));
			$date['start']=strtotime($_POST['start_date']);
			$date['end']=strtotime($_POST['end_date']);
			
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$this->_modelReplyQulity=$this->_getGlobalData('Model_ReplyQulity','object');
			$this->_modelVerify=$this->_getGlobalData('Model_Verify','object');
			$workOrderTotal=$this->_modelWorkOrder->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$workOrderQaTotal=$this->_modelWorkOrderQa->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$replyQulityTotal=$this->_modelReplyQulity->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$bugListTotal=$this->_modelVerify->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$total=array();
			for ($i=0;$i<=23;$i++){
				if (!$workOrderQaTotal[$i])$workOrderQaTotal[$i]=array();
				if (!$replyQulityTotal[$i])$replyQulityTotal[$i]=array();
				if (!$workOrderTotal[$i])$workOrderTotal[$i]=array();
				if (!$bugListTotal[$i])$bugListTotal[$i]=array();
				$total[$i]=array_merge($workOrderQaTotal[$i],$replyQulityTotal[$i],$workOrderTotal[$i],$bugListTotal[$i]);
			}
			if (!$workOrderQaTotal['total'])$workOrderQaTotal['total']=array();
			if (!$replyQulityTotal['total'])$replyQulityTotal['total']=array();
			if (!$workOrderTotal['total'])$workOrderTotal['total']=array();
			if (!$bugListTotal['total'])$bugListTotal['total']=array();
			$total['total']=array_merge($workOrderQaTotal['total'],$replyQulityTotal['total'],$workOrderTotal['total'],$bugListTotal['total']);
			ksort($total);
			$this->_view->assign('total',$total);
			$this->_view->assign('displayTrue',true);
		}
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 按小时统计详细_ajax
	 */
	public function actionHourDetail(){
		Tools::import('Util_Stats');
		$utilStats=new Util_Stats(Util_Stats::STATS_OPERATOR);
		$key=$_POST['detail_key'];
		$time=array();
		$time['start']=strtotime($_POST['start_date']);
		$time['end']=strtotime($_POST['end_date']);
		
		$utilStats->setStatsTime($time);
		$utilStats->setOperator(array('game_type_id'=>$_POST['game_type_id'],'operator'=>$_POST['operator_id']));
		$utilStats->setHour($key);
		$dataList=$utilStats->stats();
		$this->_view->assign('dataList',$dataList);

		$tableTitle=$utilStats->getTableTitle($_POST['game_type_id']);
		$tableTitle['time']=$time;
		$this->_view->assign('tableTitle',$tableTitle);
		$this->_view->display('Stats/StatsOperator/OperatorDetail');
	}
}