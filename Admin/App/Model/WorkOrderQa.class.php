<?php
class Model_WorkOrderQa extends Model {
	protected $_tableName='work_order_qa';
	
	/**
	 * 通过work_order_id询查此工单的对话
	 */
	public function findByWorkOrderId($workOrderId){
		return $this->select("select * from {$this->tName()} where work_order_id={$workOrderId} order by Id");
	}
	
	/**
	 * 通过user_name查找工作量
	 * @param string $userName
	 * @param array $date
	 * @return array(<br />
	 * 		'quality_num'=>$qualityNum,	被质检数<br />
	 *		'no_quality_num'=>$noQualityNum,未被质检数<br />
	 *		'timeout_num'=>$timeoutNum,超时<br />
	 *		'no_timeout_num'=>$noTimeoutNum,未超时<br />
	 *		'total'=>$qualityNum+$noQualityNum,总数<br />
	 * )
	 *
	public function findWorkLoadByUser($userName,$date){
		$sql="select is_quality,is_timeout,create_time from {$this->tName()} where qa=1 and reply_name='{$userName}' and create_time between {$date['start']} and {$date['end']}";
		$dataList=$this->select($sql);
		$returnArr=array();
		$qualityNum=0;//被质检过的数量
		$noQualityNum=0;//未被质检过的数量
		$timeoutNum=0;//超时的
		$noTimeoutNum=0;//未超时的
		foreach ($dataList as $value){
			if ($value['is_timeout']==1){//已经超时
				$returnArr[date('Ymd',$value['create_time'])]['timeout_num']++;
				$timeoutNum++;
			}else {//未超时
				$returnArr[date('Ymd',$value['create_time'])]['no_timeout_num']++;
				$noTimeoutNum++;
			}
			if ($value['is_quality']==1){//质检过
				$returnArr[date('Ymd',$value['create_time'])]['quality_num']++;
				$qualityNum++;
			}else {//未质检
				$returnArr[date('Ymd',$value['create_time'])]['no_quality_num']++;
				$noQualityNum++;
			}
			$returnArr[date('Ymd',$value['create_time'])]['total']++;
		}
		$returnArr['total']=array(
			'quality_num'=>$qualityNum,
			'no_quality_num'=>$noQualityNum,
			'timeout_num'=>$timeoutNum,
			'no_timeout_num'=>$noTimeoutNum,
			'total'=>$qualityNum+$noQualityNum,
		);
		return $returnArr;
	}*/
	
	/**
	 * 统计功能,环比统计API
	 * @param int $gameTypeId
	 * @param array $operatorId
	 * @param array $date
	 */
	public function getStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select is_quality,is_timeout from {$this->tName()} where qa=1 and game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['first']} and {$date['last']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				if ($value['is_timeout']==1){
					$returnArr['timeout_num']++;
				}else {
					$returnArr['no_timeout_num']++;
				}
				if ($value['is_quality']==1){
					$returnArr['quality_num']++;
				}else {
					$returnArr['no_quality_num']++;
				}
				$returnArr['reply_total']++;
			}
			return $returnArr;
		}
		return false;
	}
	
	/**
	 * 统计运营商,按天
	 * @param int $gameTypeId
	 * @param int $operatorIds
	 * @param array $date
	 */
	public function getOperatorStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select is_quality,is_timeout,create_time from {$this->tName()} where qa=1 and game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				if ($value['is_timeout']==1){
					$returnArr[date('Y-m-d',$value['create_time'])]['timeout_num']++;
					$returnArr['total']['timeout_num']++;
				}else {
					$returnArr[date('Y-m-d',$value['create_time'])]['no_timeout_num']++;
					$returnArr['total']['no_timeout_num']++;
				}
				if ($value['is_quality']==1){
					$returnArr[date('Y-m-d',$value['create_time'])]['quality_num']++;
					$returnArr['total']['quality_num']++;
				}else {
					$returnArr[date('Y-m-d',$value['create_time'])]['no_quality_num']++;
					$returnArr['total']['no_quality_num']++;
				}
				$returnArr[date('Y-m-d',$value['create_time'])]['reply_total']++;
				$returnArr['total']['reply_total']++;
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
	 * 统计运营商,按小时API
	 * @param int $gameTypeId
	 * @param int $operatorIds
	 * @param array $date
	 */
	public function getOperatorDayStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select is_quality,is_timeout,create_time from {$this->tName()} where qa=1 and game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$curKey=intval(date('H',$value['create_time']));
				if ($value['is_timeout']==1){
					$returnArr[$curKey]['timeout_num']++;
					$returnArr['total']['timeout_num']++;
				}else {
					$returnArr[$curKey]['no_timeout_num']++;
					$returnArr['total']['no_timeout_num']++;
				}
				if ($value['is_quality']==1){
					$returnArr[$curKey]['quality_num']++;
					$returnArr['total']['quality_num']++;
				}else {
					$returnArr[$curKey]['no_quality_num']++;
					$returnArr['total']['no_quality_num']++;
				}
				$returnArr[$curKey]['reply_total']++;
				$returnArr['total']['reply_total']++;
			}
			return $returnArr;
		}
		return false;
	}
}