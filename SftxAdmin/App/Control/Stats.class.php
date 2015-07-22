<?php
/**
 * 数据统计模块
 * @author PHP-朱磊
 */
class Control_Stats extends Control {
	
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;
	
	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;
	
	/**
	 * Model_ReplyQulity
	 * @var Model_ReplyQulity
	 */
	private $_modelReplyQulity;
	
	/**
	 * Model_GameOperator
	 * @var Model_GameOperator
	 */
	private $_modelGameOperator;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	private function _createUrl(){
		$this->_url['Stats_Detail']=Tools::url(CONTROL,'Detail');
		$this->_url['Stats_Index']=Tools::url(CONTROL,'Index');
		$this->_url['Stats_Operator']=Tools::url(CONTROL,'Operator');
		$this->_url['Stats_OperatorDay']=Tools::url(CONTROL,'OperatorDay');
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 环比统计
	 */
	public function actionIndex(){
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
		
		
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	
	
	/**
	 * 详细统计
	 */
	public function actionDetail(){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$orgList=$this->_getGlobalData('org');
		$allOrgUser=$this->_modelUser->findSetOrgByUser();
		$qualityOptions=$this->_getGlobalData('quality_options');	//质检选项
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','nick_name');
				$value['user']=$curOrgUser;
			}
		}
		if ($this->_isPost()){
			if (count($_POST['check_users']) && $_POST['start_date'] && $_POST['end_date']){//必须保证有搜索项而且还选择了用户才会开开始搜索
				$date=array();
				$this->_view->assign('selectedTime',array('start'=>$_POST['start_date'],'end'=>$_POST['end_date']));
				$this->_view->assign('selectedUsers',$_POST['check_users']);
				$date['start']=strtotime($_POST['start_date']);//开始时间
				$date['end']=strtotime($_POST['end_date']);//结束时间 
				if ($date['start']>$date['end'])$this->_utilMsg->showMsg('开始时间不能大于结束时间',-2);
				$statsUsers=array();	//所有的用户
				$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
				$this->_modelReplyQulity=$this->_getGlobalData('Model_ReplyQulity','object');
				$allUsers=$this->_getGlobalData('user');
				foreach ($_POST['check_users'] as $userId){//对用户进行逐个搜索
					$curUserDetail=$allUsers[$userId];
					$curUserDetail['workload']=$this->_modelWorkOrderQa->findWorkLoadByUser($curUserDetail['user_name'],$date);//回复量
					$curUserDetail['quality']=$this->_modelReplyQulity->findQualityByUerName($curUserDetail['user_name'],$date);//质检量
					array_push($statsUsers,$curUserDetail);
				}
				$startKey=date('Ymd',$date['start']);
				$endDate=date('Ymd',$date['end']);
				$baseTotal=array();			
				for ($i=$startKey;$i<=$endDate;$i++){
					foreach ($statsUsers as $childUser){
						$baseTotal['workload'][$i]['timeout_num']+=intval($childUser['workload'][$i]['timeout_num'])?intval($childUser['workload'][$i]['timeout_num']):0;
						$baseTotal['workload'][$i]['no_timeout_num']+=intval($childUser['workload'][$i]['no_timeout_num'])?intval($childUser['workload'][$i]['no_timeout_num']):0;
						$baseTotal['workload'][$i]['quality_num']+=intval($childUser['workload'][$i]['quality_num'])?intval($childUser['workload'][$i]['quality_num']):0;
						$baseTotal['workload'][$i]['no_quality_num']+=intval($childUser['workload'][$i]['no_quality_num'])?intval($childUser['workload'][$i]['no_quality_num']):0;
						$baseTotal['workload'][$i]['total']+=intval($childUser['workload'][$i]['total'])?intval($childUser['workload'][$i]['total']):0;

						
						$baseTotal['quality'][$i]['status_num'][1]+=intval($childUser['quality'][$i]['status_num'][1])?intval($childUser['quality'][$i]['status_num'][1]):0;
						$baseTotal['quality'][$i]['status_num'][2]+=intval($childUser['quality'][$i]['status_num'][2])?intval($childUser['quality'][$i]['status_num'][2]):0;
						$baseTotal['quality'][$i]['status_num'][3]+=intval($childUser['quality'][$i]['status_num'][3])?intval($childUser['quality'][$i]['status_num'][3]):0;
						$baseTotal['quality'][$i]['status_num'][4]+=intval($childUser['quality'][$i]['status_num'][4])?intval($childUser['quality'][$i]['status_num'][4]):0;
						$baseTotal['quality'][$i]['status_num'][5]+=intval($childUser['quality'][$i]['status_num'][5])?intval($childUser['quality'][$i]['status_num'][5]):0;
						$baseTotal['quality'][$i]['deduction']+=intval($childUser['quality'][$i]['deduction'])?intval($childUser['quality'][$i]['deduction']):0;
						$baseTotal['quality'][$i]['bonus']+=intval($childUser['quality'][$i]['bonus'])?intval($childUser['quality'][$i]['bonus']):0;
						$baseTotal['quality'][$i]['option_num'][1]+=intval($childUser['quality'][$i]['option_num'][1])?intval($childUser['quality'][$i]['option_num'][1]):0;
						$baseTotal['quality'][$i]['option_num'][2]+=intval($childUser['quality'][$i]['option_num'][2])?intval($childUser['quality'][$i]['option_num'][2]):0;
						$baseTotal['quality'][$i]['option_num'][3]+=intval($childUser['quality'][$i]['option_num'][-1])?intval($childUser['quality'][$i]['option_num'][-1]):0;
						$baseTotal['quality'][$i]['option_num'][4]+=intval($childUser['quality'][$i]['option_num'][-2])?intval($childUser['quality'][$i]['option_num'][-2]):0;
						$baseTotal['quality'][$i]['option_num'][5]+=intval($childUser['quality'][$i]['option_num'][-3])?intval($childUser['quality'][$i]['option_num'][-3]):0;
						$baseTotal['quality'][$i]['option_num'][6]+=intval($childUser['quality'][$i]['option_num'][-4])?intval($childUser['quality'][$i]['option_num'][-4]):0;
						$baseTotal['quality'][$i]['option_num'][7]+=intval($childUser['quality'][$i]['option_num'][-5])?intval($childUser['quality'][$i]['option_num'][-5]):0;
						$baseTotal['quality'][$i]['option_num'][8]+=intval($childUser['quality'][$i]['option_num'][-6])?intval($childUser['quality'][$i]['option_num'][-6]):0;


					}
				}
				foreach ($statsUsers as $childUser){
					$baseTotal['workload']['total']['timeout_num']+=intval($childUser['workload']['total']['timeout_num'])?intval($childUser['workload']['total']['timeout_num']):0;
					$baseTotal['workload']['total']['no_timeout_num']+=intval($childUser['workload']['total']['no_timeout_num'])?intval($childUser['workload']['total']['no_timeout_num']):0;
					$baseTotal['workload']['total']['quality_num']+=intval($childUser['workload']['total']['quality_num'])?intval($childUser['workload']['total']['quality_num']):0;
					$baseTotal['workload']['total']['no_quality_num']+=intval($childUser['workload']['total']['no_quality_num'])?intval($childUser['workload']['total']['no_quality_num']):0;
					$baseTotal['workload']['total']['total']+=intval($childUser['workload']['total']['total'])?intval($childUser['workload']['total']['total']):0;
					
					$baseTotal['quality']['total']['status_num'][1]+=intval($childUser['quality']['total']['status_num'][1])?intval($childUser['quality']['total']['status_num'][1]):0;
					$baseTotal['quality']['total']['status_num'][2]+=intval($childUser['quality']['total']['status_num'][2])?intval($childUser['quality']['total']['status_num'][2]):0;
					$baseTotal['quality']['total']['status_num'][3]+=intval($childUser['quality']['total']['status_num'][3])?intval($childUser['quality']['total']['status_num'][3]):0;
					$baseTotal['quality']['total']['status_num'][4]+=intval($childUser['quality']['total']['status_num'][4])?intval($childUser['quality']['total']['status_num'][4]):0;
					$baseTotal['quality']['total']['status_num'][5]+=intval($childUser['quality']['total']['status_num'][5])?intval($childUser['quality']['total']['status_num'][5]):0;
					$baseTotal['quality']['total']['deduction']+=intval($childUser['quality']['total']['deduction'])?intval($childUser['quality']['total']['deduction']):0;
					$baseTotal['quality']['total']['bonus']+=intval($childUser['quality']['total']['bonus'])?intval($childUser['quality']['total']['bonus']):0;
					$baseTotal['quality']['total']['option_num'][1]+=intval($childUser['quality']['total']['option_num'][1])?intval($childUser['quality']['total']['option_num'][1]):0;
					$baseTotal['quality']['total']['option_num'][2]+=intval($childUser['quality']['total']['option_num'][2])?intval($childUser['quality']['total']['option_num'][2]):0;
					$baseTotal['quality']['total']['option_num'][3]+=intval($childUser['quality']['total']['option_num'][-1])?intval($childUser['quality']['total']['option_num'][-1]):0;
					$baseTotal['quality']['total']['option_num'][4]+=intval($childUser['quality']['total']['option_num'][-2])?intval($childUser['quality']['total']['option_num'][-2]):0;
					$baseTotal['quality']['total']['option_num'][5]+=intval($childUser['quality']['total']['option_num'][-3])?intval($childUser['quality']['total']['option_num'][-3]):0;
					$baseTotal['quality']['total']['option_num'][6]+=intval($childUser['quality']['total']['option_num'][-4])?intval($childUser['quality']['total']['option_num'][-4]):0;
					$baseTotal['quality']['total']['option_num'][7]+=intval($childUser['quality']['total']['option_num'][-5])?intval($childUser['quality']['total']['option_num'][-5]):0;
					$baseTotal['quality']['total']['option_num'][8]+=intval($childUser['quality']['total']['option_num'][-6])?intval($childUser['quality']['total']['option_num'][-6]):0;
				}
				$this->_view->assign('statsUsers',$statsUsers);
				$this->_view->assign('baseTotal',$baseTotal);
				$this->_view->assign('displayTrue',true);
			}else {
				$this->_utilMsg->showMsg('请选择用户,时间',-2);
			}
		}
		$this->_view->assign('qualityOptions',$qualityOptions);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_view->assign('css',$this->_view->get_curCss());
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 运营商详细统计
	 */
	public function actionOperator(){
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
			$workOrderTotal=$this->_modelWorkOrder->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$workOrderQaTotal=$this->_modelWorkOrderQa->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$replyQulityTotal=$this->_modelReplyQulity->getOperatorStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);

			if (is_array($workOrderQaTotal) && is_array($replyQulityTotal) && is_array($workOrderTotal)){
				$total=array();
				for ($i=date('Ymd',$date['start']);$i<=date('Ymd',$date['end']);$i++){
					if (!$workOrderQaTotal[$i])$workOrderQaTotal[$i]=array();
					if (!$replyQulityTotal[$i])$replyQulityTotal[$i]=array();
					if (!$workOrderTotal[$i])$workOrderTotal[$i]=array();
					$total[$i]=array_merge($workOrderQaTotal[$i],$replyQulityTotal[$i],$workOrderTotal[$i]);
				}
				if (!$workOrderQaTotal['total'])$workOrderQaTotal['total']=array();
				if (!$replyQulityTotal['total'])$replyQulityTotal['total']=array();
				if (!$workOrderTotal['total'])$workOrderTotal['total']=array();
				$total['total']=array_merge($workOrderQaTotal['total'],$replyQulityTotal['total'],$workOrderTotal['total']);
			}
			$this->_view->assign('total',$total);
			$this->_view->assign('displayTrue',true);
		}
		
		
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 运营商按天数统计
	 */
	public function actionOperatorDay(){
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
			if (empty($_POST['date']))$this->_utilMsg->showMsg('统计日期不能为空',-2);
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
			$this->_view->assign('selectedDate',$curDate);
			$date['start']=strtotime($curDate);
			$date['end']=$date['start']+24*60*60-1;
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$this->_modelReplyQulity=$this->_getGlobalData('Model_ReplyQulity','object');
			$workOrderTotal=$this->_modelWorkOrder->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$workOrderQaTotal=$this->_modelWorkOrderQa->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			$replyQulityTotal=$this->_modelReplyQulity->getOperatorDayStatsNum($_POST['game_type_id'],$_POST['operator_id'],$date);
			if (is_array($workOrderQaTotal) && is_array($replyQulityTotal) && is_array($workOrderTotal)){
				$total=array();
				for ($i=0;$i<=23;$i++){
					if (!$workOrderQaTotal[$i])$workOrderQaTotal[$i]=array();
					if (!$replyQulityTotal[$i])$replyQulityTotal[$i]=array();
					if (!$workOrderTotal[$i])$workOrderTotal[$i]=array();
					$total[$i]=array_merge($workOrderQaTotal[$i],$replyQulityTotal[$i],$workOrderTotal[$i]);
				}
				if (!$workOrderQaTotal['total'])$workOrderQaTotal['total']=array();
				if (!$replyQulityTotal['total'])$replyQulityTotal['total']=array();
				if (!$workOrderTotal['total'])$workOrderTotal['total']=array();
				$total['total']=array_merge($workOrderQaTotal['total'],$replyQulityTotal['total'],$workOrderTotal['total']);
			}
			$this->_view->assign('total',$total);
			$this->_view->assign('displayTrue',true);
		}
		
		
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
}