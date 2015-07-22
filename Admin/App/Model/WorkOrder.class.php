<?php
/**
 * 工单表
 * @author PHP-朱磊
 *
 */
class Model_WorkOrder extends Model {
	protected $_tableName = 'work_order';	//表名

	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;
	
	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;
	
	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;
	
	/**
	 * Model_Quality
	 * @var Model_Quality
	 */
	private $_modelQuality;
	
	/**
	 * Model_Verify
	 * @var Model_Verify
	 */
	private $_modelVerify;
	
	public function getVerify(){
		return array(0=>'无需查证',1=>'需查证',''=>'所有');
	}
	
	/**
	 * 增加工单来质检用户
	 * @param array $ids
	 * @param int $userId
	 */
	public function addOrderToQualityUser($ids,$userId){
		$ids=implode(',',$ids);
		return $this->execute("update {$this->tName()} set quality_id=-{$userId} where Id in ({$ids})");
	}

	/**
	 * 通过ID查找工单的详细信息
	 * @param int $id 工单ID号
	 */
	public function findByIdDetail($id){
		$sql="select main.*,qa.Id as qa_id,qa.qa,qa.is_timeout,qa.content,qa.user_id,qa.create_time as qa_time,detail.content as detail ".
			"from {$this->tName()} main,{$this->tName('work_order_detail')} detail,{$this->tName('work_order_qa')} qa ".
			"where main.Id={$id} and qa.work_order_id=main.Id and detail.work_order_id=main.Id order by qa.Id asc";
		return $this->select($sql);
	}
	
	/**
	 * 通过id查找工单的详细信息
	 * @param int $workOrderId
	 */
	public function findByIdToDetail($workOrderId){
		$sql="select main.*,detail.content from {$this->tName()} main,{$this->tName('work_order_detail')} detail ".
			"where main.Id={$workOrderId} and detail.work_order_id=main.Id";
		return $this->select($sql,1);
	}
	
	
	/**
	 * 清空质检任务
	 * @param int $userId
	 */
	public function clearTask($userId){
		if ($this->execute("update {$this->tName()} set quality_id=0 where quality_id=-{$userId}")){
			return array('msg'=>Tools::getLang('CLEAR_TASK_SUCCESS',__CLASS__),'status'=>1,'href'=>1);
		}else {
			return array('msg'=>Tools::getLang('CLEAR_TASK_ERROR',__CLASS__),'status'=>-2,'href'=>1);
		}
	}
	
	/**
	 * 清除房间内的用户工单
	 * @param int $roomId
	 */
	public function clearRoomOrder($roomId){
		$dataList=$this->select("select * from {$this->tName()} where room_id={$roomId} and status in (1,2)");
		if (!$dataList)return array('msg'=>Tools::getLang('CLEARROOM_ERROR',__CLASS__),'status'=>-1,'href'=>1);
		$ids=array();
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$objectOrder=$this->_utilWorkOrder->getOrderManage();	//获取自动分配工单对象
		
		foreach ($dataList as $list){
			$list['game_type_id']=$list['game_type'];	//处理兼容问题
			$objectOrder->addOrder($list);//将工单全部加入到队列
			array_push($ids,$list['Id']);	//工单ID
		}
		$this->execute("update {$this->tName()} set owner_user_id=null where Id in (".implode(',',$ids).")");//更新工单的所有者,让系统重新分配
		return array('msg'=>Tools::getLang('CLEARROOM_SUCCESS',__CLASS__),'status'=>1,'href'=>1);
	}
	
	/**
	 * 清空用户工单队列
	 * @param int $userId
	 */
	public function clearOrder($userId){
		$dataList=$this->select("select Id,vip_level,room_id,game_type,operator_id from {$this->tName()} where owner_user_id={$userId} and status in (1,2)");	//找到当前用户工单状态为待处理,处理中的工单ID
		if (!$dataList)return array('msg'=>Tools::getLang('CLEARORDER_ERROR',__CLASS__),'status'=>-2,'href'=>1);
		$ids=array();
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$objectOrder=$this->_utilWorkOrder->getOrderManage();	//获取自动分配工单对象
		foreach ($dataList as $list){
			$list['game_type_id']=$list['game_type'];	//处理兼容问题
			$objectOrder->addOrder($list);//将工单全部加入到队列
			array_push($ids,$list['Id']);	//工单ID
		}
		$objectOrder->setUpdateInfo(1);	//更新.
		$this->execute("update {$this->tName()} set owner_user_id=null where Id in (".implode(',',$ids).")");//更新工单的所有者,让系统重新分配
		return array('msg'=>Tools::getLang('CLEARORDER_ERROR',__CLASS__),'status'=>1,'href'=>1);
	}
	

	/**
	 * 统计功能环比API
	 * @param int $gameTypeId
	 * @param array $operatorId
	 * @param array $date
	 */
	public function getStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);
		$dataList=$this->select("select create_time,evaluation_status,is_verify,evaluation_desc from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['first']} and {$date['last']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr['total']++;
				$returnArr['ev'][$value['evaluation_status']]++;	//评价
				if($value['evaluation_status'] == 3){
					//$returnArr[date('Y-m-d',$value['create_time'])]['ev'][$evaluationDesc[$value['evaluation_desc']]]++;	//评价
					$returnArr['cp'][$evaluationDesc[$value['evaluation_desc']]]++;	//评价
				}
			}
			return $returnArr;
		}
		return false;
	}
	
	/**
	 * 统计运营商,天数为单位API
	 * @param int $gameTypeId
	 * @param int $operatorIds
	 * @param array $date
	 */
	public function getOperatorStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select create_time,evaluation_status,evaluation_desc,is_verify,status from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			//1游戏BUG
			//2游戏设置不满意
			//3回复不清晰
			//4回复错误
			//5客服服务态度恶劣
			//6其他	
			$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr[date('Y-m-d',$value['create_time'])]['total']++;
//				$returnArr[date('Y-m-d',$value['create_time'])]['ev'][$value['evaluation_status']]++;	//评价
//				$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
				if($value['evaluation_status'] == 3){
					$returnArr[date('Y-m-d',$value['create_time'])]['ev'][$evaluationDesc[$value['evaluation_desc']]]++;	//评价
					$returnArr['total']['ev'][$evaluationDesc[$value['evaluation_desc']]]++;	//评价
				}else{
					$returnArr[date('Y-m-d',$value['create_time'])]['ev'][$value['evaluation_status']]++;	//评价
					$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
				}
				$returnArr['total']['total']++;				
				if ($value['is_verify'] && in_array($value['status'],array(3,4))){
					$returnArr[date('Y-m-d',$value['create_time'])]['verify']++;
					$returnArr['total']['verify']++;
				}
				
			}
			$allDay=Tools::getdateArr($date['start'],$date['end']);
			foreach ($allDay as $key=>$value){
				if (!isset($returnArr[$key]))$returnArr[$key]=array();
			}
			krsort($returnArr);
			return $returnArr;
		}
		return array();
	}
	
	/**
	 * 统计运营商,小时为单位API
	 * @param int $gameTypeId
	 * @param int $operatorIds
	 * @param array $date
	 */
	public function getOperatorDayStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select create_time,evaluation_status,evaluation_desc,is_verify,status from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			//1游戏BUG
			//2游戏设置不满意
			//3回复不清晰
			//4回复错误
			//5客服服务态度恶劣
			//6其他	
			$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
			$returnArr=array();
			foreach ($dataList as $value){
				$curKey=intval(date('H',$value['create_time']));
				$returnArr[$curKey]['total']++;
//				$returnArr[$curKey]['ev'][$value['evaluation_status']]++;	//评价
				$returnArr['total']['total']++;
//				$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
				if($value['evaluation_status'] == 3){
					$returnArr[$curKey]['ev'][$evaluationDesc[$value['evaluation_desc']] ]++;	//评价
					$returnArr['total']['ev'][$evaluationDesc[$value['evaluation_desc']] ]++;	//评价
				}else{
					$returnArr[$curKey]['ev'][$value['evaluation_status']]++;	//评价
					$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
				}
				
				if ($value['is_verify'] && in_array($value['status'],array(3,4))){
					$returnArr[date('H',$value['create_time'])]['verify']++;
					$returnArr['total']['verify']++;
				}
			}
			return $returnArr;
		}
		return false;
	}
	
	/**
	 * 获取评价状态
	 * @return array
	 */
	public function getEvArr(){
		static $evArr=null;
		if (!is_array($evArr)){
			$evArr=$this->_getGlobalData('player_evaluation');
			foreach ($evArr as &$list){
				$list=$list['title'];
			}
			$evArr['0']=Tools::getLang('NOT_EV',__CLASS__);
		}
		return $evArr;
	}
	
	/**
	 * 详细统计
	 * @param array  $date
	 * @param array $users
	 */
	public function statsDetail($date,$users,$gameIds = NULL,$operators = null ){
		#------获取工单总数统计------#
		$sql="select evaluation_status,evaluation_desc,create_time,owner_user_id,is_verify,status from {$this->tName()} where create_time between {$date['start']} and {$date['end']} and owner_user_id in (".implode(',',$users).")";
		if($gameIds){
			if(is_array($gameIds)){
				$gameIds = implode(',',$gameIds);
			}
			$sql.= " and game_type in ({$gameIds})";
		}
		if($operators){
			if(is_array($operators)){
				$operators = implode(',',$operators);
			}
			$sql.= " and operator_id in ({$operators})";
		}
// 		echo "<p>".$sql;
		$dataList=$this->select($sql);
		if ($dataList){
			$evArr=array();
			//1游戏BUG
			//2游戏设置不满意
			//3回复不清晰
			//4回复错误
			//5客服服务态度恶劣
			//6其他	
			$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
			
			$workOrderList=array();
			foreach ($dataList as $list){
				$workOrderList['total']['total']['total']++;
//				$workOrderList['total']['total']['ev_'.$list['evaluation_status']]++;
				$workOrderList['total'][date('Y-m-d',$list['create_time'])]['total']++;
//				$workOrderList['total'][date('Y-m-d',$list['create_time'])]['ev_'.$list['evaluation_status']]++;
				
				if ($list['is_verify'] && in_array($list['status'],array(3,4))){
					$workOrderList['total']['total']['verify']++;
					$workOrderList['total'][date('Y-m-d',$list['create_time'])]['verify']++;
					$workOrderList[$list['owner_user_id']]['total']['verify']++;
					$workOrderList[$list['owner_user_id']][date('Y-m-d',$list['create_time'])]['verify']++;
				}
				
				
				$workOrderList[$list['owner_user_id']]['total']['total']++;
//				$workOrderList[$list['owner_user_id']]['total']['ev_'.$list['evaluation_status']]++;
				$workOrderList[$list['owner_user_id']][date('Y-m-d',$list['create_time'])]['total']++;
//				$workOrderList[$list['owner_user_id']][date('Y-m-d',$list['create_time'])]['ev_'.$list['evaluation_status']]++;
				
				//对差评进行扩展
				if($list['evaluation_status'] == 3){
					$tmpEv = $evaluationDesc[$list['evaluation_desc']];
					$workOrderList['total']['total']['ev_'.$tmpEv]++;
					$workOrderList[$list['owner_user_id']]['total']['ev_'.$tmpEv]++;
					$workOrderList['total'][date('Y-m-d',$list['create_time'])]['ev_'.$tmpEv]++;
					$workOrderList[$list['owner_user_id']][date('Y-m-d',$list['create_time'])]['ev_'.$tmpEv]++;					
				}else{
					$workOrderList['total']['total']['ev_'.$list['evaluation_status']]++;
					$workOrderList[$list['owner_user_id']]['total']['ev_'.$list['evaluation_status']]++;
					$workOrderList['total'][date('Y-m-d',$list['create_time'])]['ev_'.$list['evaluation_status']]++;
					$workOrderList[$list['owner_user_id']][date('Y-m-d',$list['create_time'])]['ev_'.$list['evaluation_status']]++;
				}
			}
		}
		$allDay=Tools::getdateArr($date['start'],$date['end']);
		foreach ($users as $user){
			foreach ($allDay as $key=>$value){
				if (!isset($workOrderList['total'][$key]))$workOrderList['total'][$key]=array();
				if (!isset($workOrderList[$user][$key]))$workOrderList[$user][$key]=array();
			}
			krsort($workOrderList[$user]);
		}
		krsort($workOrderList['total']);
		
		#------获取工单总数统计------#
		
		#------获取回复总记录数------#
		$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
		$sql = "select is_timeout,user_id,create_time from {$this->_modelWorkOrderQa->tName()} where create_time between {$date['start']} and {$date['end']} and qa=1 and user_id in (".implode(',',$users).")";
		if($gameIds){
			$sql .= " and game_type_id in ({$gameIds})";
		}
		if($operators){
			if(is_array($operators)){
				$operators = implode(',',$operators);
			}
			$sql.= " and operator_id in ({$operators})";
		}
// 		echo "<p>".$sql;
		$dataList=$this->_modelWorkOrderQa->select($sql);
		if ($dataList){
			$qaList=array();
			foreach ($dataList as $list){
				$qaList['total']['total']['total_reply']++;
				$qaList['total']['total']['timeout_'.$list['is_timeout']]++;
				$qaList['total'][date('Y-m-d',$list['create_time'])]['total_reply']++;
				$qaList['total'][date('Y-m-d',$list['create_time'])]['timeout_'.$list['is_timeout']]++;
				
				$qaList[$list['user_id']]['total']['total_reply']++;
				$qaList[$list['user_id']]['total']['timeout_'.$list['is_timeout']]++;
				$qaList[$list['user_id']][date('Y-m-d',$list['create_time'])]['total_reply']++;
				$qaList[$list['user_id']][date('Y-m-d',$list['create_time'])]['timeout_'.$list['is_timeout']]++;
			}
		}
		$allDay=Tools::getdateArr($date['start'],$date['end']);
		foreach ($users as $user){
			foreach ($allDay as $key=>$value){
				if (!isset($qaList['total'][$key]))$qaList['total'][$key]=array();
				if (!isset($qaList[$user][$key]))$qaList[$user][$key]=array();
			}
			krsort($qaList[$user]);
		}
		krsort($qaList['total']);
		#------获取回复总记录数------#
		
		#------质检统计------#
		$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
		$sql = 	"select quality_user_id,reply_user_id,scores,quality_time from {$this->_modelQuality->tName()} where quality_time between {$date['start']} and {$date['end']} and (quality_user_id in (".implode(',',$users).") or reply_user_id in (".implode(',',$users).")) ";
		if($gameIds){
			$sql .= " and game_type_id in ({$gameIds})";
		}
		if($operators){
			$sql.= " and operator_id in ({$operators})";
		}
// 		echo "<p>".$sql;
		$dataList=$this->_modelQuality->select($sql);
		if ($dataList){
			$qualityList=array();
			foreach ($dataList as $list){
				$qualityList['total']['total']['total_quality']++;
				$qualityList['total'][date('Y-m-d',$list['quality_time'])]['total_quality']++;
				
				$qualityList['total']['total']['quality_num']++;
				$qualityList['total']['total']['reply_num']++;
				
				$qualityList['total'][date('Y-m-d',$list['quality_time'])]['quality_num']++;
				$qualityList['total'][date('Y-m-d',$list['quality_time'])]['reply_num']++;
				
				$qualityList[$list['quality_user_id']]['total']['quality_num']++;											//质检回复量
				$qualityList[$list['quality_user_id']][date('Y-m-d',$list['quality_time'])]['quality_num']++;
				
				$qualityList[$list['reply_user_id']]['total']['reply_num']++;											//被质检回复量
				$qualityList[$list['reply_user_id']][date('Y-m-d',$list['quality_time'])]['reply_num']++;
				
				if ($list['scores']>0){
					$qualityList['total']['total']['bonus_num']++;
					$qualityList['total']['total']['bonus']+=$list['scores'];
					$qualityList['total'][date('Y-m-d',$list['quality_time'])]['bonus_num']++;
					$qualityList['total'][date('Y-m-d',$list['quality_time'])]['bonus']+=$list['scores'];
					
					$qualityList[$list['reply_user_id']]['total']['bonus_num']++;
					$qualityList[$list['reply_user_id']]['total']['bonus']+=$list['scores'];
					$qualityList[$list['reply_user_id']][date('Y-m-d',$list['quality_time'])]['bonus_num']++;
					$qualityList[$list['reply_user_id']][date('Y-m-d',$list['quality_time'])]['bonus']+=$list['scores'];

				}elseif ($list['scores']<0){
					$qualityList['total']['total']['deduction_num']++;
					$qualityList['total']['total']['deduction']+=$list['scores'];
					$qualityList['total'][date('Y-m-d',$list['quality_time'])]['deduction_num']++;
					$qualityList['total'][date('Y-m-d',$list['quality_time'])]['deduction']+=$list['scores'];
					
					$qualityList[$list['reply_user_id']]['total']['deduction_num']++;
					$qualityList[$list['reply_user_id']]['total']['deduction']+=$list['scores'];
					$qualityList[$list['reply_user_id']][date('Y-m-d',$list['quality_time'])]['deduction_num']++;
					$qualityList[$list['reply_user_id']][date('Y-m-d',$list['quality_time'])]['deduction']+=$list['scores'];
					
				}
			}
		}
		$allDay=Tools::getdateArr($date['start'],$date['end']);
		foreach ($users as $user){
			foreach ($allDay as $key=>$value){
				if (!isset($qualityList['total'][$key]))$qualityList['total'][$key]=array();
				if (!isset($qualityList[$user][$key]))$qualityList[$user][$key]=array();
			}
			krsort($qualityList[$user]);
		}
		krsort($qualityList['total']);
		#------质检统计------#
		
		
		#------BUGLIST------#
		$this->_modelVerify=$this->_getGlobalData('Model_Verify','object');
		$sql = "select user_id,finish_user_id,create_time from {$this->_modelVerify->tName()} where create_time between {$date['start']} and {$date['end']} and (user_id in (".implode(',',$users).") or finish_user_id in (".implode(',',$users)."))";
		if($gameIds){
			$sql .= " and game_type_id in ({$gameIds})";
		}
		if($operators){
			$sql.= " and operator_id in ({$operators})";
		}
// 		echo "<p>".$sql;
		$dataList=$this->_modelVerify->select($sql);
		if ($dataList){
			$bugList=array();
			foreach ($dataList as $list){
				$curTime=date('Y-m-d',$list['create_time']);
				$bugList['total']['total']['submit']++;
				$bugList['total'][$curTime]['submit']++;
				$bugList[$list['user_id']]['total']['submit']++;
				$bugList[$list['user_id']][$curTime]['submit']++;
				
				if ($list['finish_user_id']){
					$bugList['total']['total']['finish']++;
					$bugList['total'][$curTime]['finish']++;
					$bugList[$list['finish_user_id']]['total']['finish']++;
					$bugList[$list['finish_user_id']][$curTime]['finish']++;
				}
			}
		}
		$allDay=Tools::getdateArr($date['start'],$date['end']);
		foreach ($users as $user){
			foreach ($allDay as $key=>$value){
				if (!isset($bugList['total'][$key]))$bugList['total'][$key]=array();
				if (!isset($bugList[$user][$key]))$bugList[$user][$key]=array();
			}
			krsort($bugList[$user]);
		}
		krsort($bugList['total']);
		#------BUGLIST------#

		$arrayList=array();
		$arrayList['total']=array_merge_recursive($workOrderList['total'],$qaList['total'],$qualityList['total'],$bugList['total']);
		foreach ($users as $user){
			$arrayList[$user]=array_merge_recursive($workOrderList[$user],$qaList[$user],$qualityList[$user],$bugList[$user]);
		}
		
		return $arrayList;
	}
	
	/**
	 * 统计周受理量
	 * @param array $times
	 * @param array $gameTypeId
	 * @param array $operatorId
	 */
	public function statsAccept($times,$gameTypeId=NULL,$operatorId=NULL,$roomList=NULL){
		$sql="select room_id,game_type,operator_id,question_num,answer_num,create_time,status,vip_level from {$this->tName()} where 1";
		if (count($times))$sql.=" and create_time between {$times['start_time']} and {$times['end_time']} ";
		if (count($gameTypeId))$sql.=" and game_type in (".implode(',',$gameTypeId).")";
		if (count($operatorId))$sql.=" and operator_id in (".implode(',',$operatorId).")";
		if (count($roomList))$sql.=" and room_id in (".implode(',',$roomList).")";
		$dataList=$this->select($sql);
		if (!$dataList)return array();
		$orderNum=array();	//总工单
		$executeNum=array();	//已处理工单
		$vipOrderNum=array();	//VIP工单
		$vipExecuteNum=array();	//已经处理VIP工单
		$secOrderNum=array();	//分钟计算工单量
		$secExecuteNum=array();	//分钟计算已处理工单量
		$timeNum=array();
		
		$executeArr=array(3,4);
		foreach ($dataList as $list){
			$curWeek=date('w',$list['create_time']);
			$orderNum['total']['total']++;
			$orderNum['total'][$curWeek]++;
			$orderNum[$list['game_type']]['total']++;
			$orderNum[$list['game_type']][$curWeek]++;
			
			if (in_array($list['status'],$executeArr)){//必须是已经处理或是用户删除了的.
				$executeNum['total']['total']++;
				$executeNum['total'][$curWeek]++;
				$executeNum[$list['game_type']]['total']++;
				$executeNum[$list['game_type']][$curWeek]++;
				
				$vipExecuteNum['total']['total']++;
				$vipExecuteNum[$list['vip_level']]['total']++;
				$vipExecuteNum['total'][$curWeek]++;
				$vipExecuteNum[$list['vip_level']][$curWeek]++;
			}
			
			$vipOrderNum['total']['total']++;
			$vipOrderNum[$list['vip_level']]['total']++;
			$vipOrderNum['total'][$curWeek]++;
			$vipOrderNum[$list['vip_level']][$curWeek]++;
			
			$timeNum['total']['total']++;
			$timeNum['total'][date('H',$list['create_time'])]++;
			$timeNum[$list['game_type']]['total']++;
			$timeNum[$list['game_type']][date('H',$list['create_time'])]++;
		}
		ksort($orderNum);
		ksort($executeNum);
		ksort($vipOrderNum);
		ksort($vipExecuteNum);
		ksort($secOrderNum);
		ksort($secExecuteNum);
		ksort($timeNum);
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=array_keys($gameTypes);
		foreach ($gameTypes as $id){
			if (is_array($orderNum[$id]))ksort($orderNum[$id]);
			if (is_array($executeNum[$id]))ksort($executeNum[$id]);
			if (is_array($vipOrderNum[$id]))ksort($vipOrderNum[$id]);
			if (is_array($vipExecuteNum[$id]))ksort($vipExecuteNum[$id]);
			if (is_array($secOrderNum[$id]))ksort($secOrderNum[$id]);
			if (is_array($secExecuteNum[$id]))ksort($secExecuteNum[$id]);
			if (is_array($timeNum[$id]))ksort($timeNum[$id]);
		}
		if (is_array($orderNum['total']))ksort($orderNum['total']);
		if (is_array($executeNum['total']))ksort($executeNum['total']);
		if (is_array($vipOrderNum['total']))ksort($vipOrderNum['total']);
		if (is_array($vipExecuteNum['total']))ksort($vipExecuteNum['total']);
		if (is_array($secOrderNum['total']))ksort($secOrderNum['total']);
		if (is_array($secExecuteNum['total']))ksort($secExecuteNum['total']);
		if (is_array($timeNum['total']))ksort($timeNum['total']);
		return array('order_num'=>$orderNum,'execute_num'=>$executeNum,'vip_order_num'=>$vipOrderNum,'vip_execute_num'=>$vipExecuteNum,'time_num'=>$timeNum);
	}
	
	/**
	 * 满意度调查
	 * @param array $times	时间
	 * @param array $gameTypeId	游戏ID
	 * @param array $operatorId	运营商ID
	 * @param array $vipLevel	VIP等级
	 */
	public function statsSatisfaction($times,$gameTypeId=NULL,$operatorId=NULL,$vipLevel=NULL,$roomList=NULL){
		$sql="select room_id,game_type,operator_id,evaluation_status,evaluation_desc from {$this->tName()} where 1";
		if (count($times))$sql.=" and create_time between {$times['start_time']} and {$times['end_time']} ";
		if (count($gameTypeId))$sql.=" and game_type in (".implode(',',$gameTypeId).")";
		if (count($operatorId))$sql.=" and operator_id in (".implode(',',$operatorId).")";
		if (count($vipLevel))$sql.=" and vip_level in (".implode(',',$vipLevel).")";
//		$roomList = intval($roomList);
//		if ($roomList>0)$sql.=" and room_id = {$roomList}";
		if (count($roomList))$sql.=" and room_id in (".implode(',',$roomList).")";
		$dataList=$this->select($sql);
		if (!$dataList)return array();
		$evArr=array();
		//1游戏BUG
		//2游戏设置不满意
		//3回复不清晰
		//4回复错误
		//5客服服务态度恶劣
		//6其他	
		$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
		foreach ($dataList as $list){
			$evArr['total']['total']++;
			$evArr[$list['game_type']]['total']++;
			if ($list['evaluation_status']){ //已经评价
				$evArr['total']['is_ev']++;
				$evArr[$list['game_type']]['is_ev']++;
			}else {//未评价
				$evArr['total']['not_ev']++;
				$evArr[$list['game_type']]['not_ev']++;
			}
			
			if($list['evaluation_status'] == 3){
				$tmpVal = $evaluationDesc[$list['evaluation_desc']];
				$evArr['total'][$tmpVal]++;
				$evArr[$list['game_type']][$tmpVal]++;
			}else{
				$evArr['total'][$list['evaluation_status']]++;
				$evArr[$list['game_type']][$list['evaluation_status']]++;	
			}
		}
		ksort($evArr);
		return $evArr;
	}
	
	public function ChangeStatus($id,$status){
		if($this->update(array('status'=>$status),'id='.$id)){
			return true;
		}else{
			return false;
		}
	}
	
}