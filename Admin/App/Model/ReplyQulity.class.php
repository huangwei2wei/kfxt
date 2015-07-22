<?php
class Model_ReplyQulity extends Model {
	protected $_tableName='reply_qulity';

	
	/**
	 * 通过username查找质量量
	 * @param string $userName
	 * @param array $date
	 * @return array 统计出来的各种数值
	 */
	public function findQualityByUerName($userName,$date){
		$sql="select * from {$this->tName()} where reply_name='{$userName}' and create_time between {$date['start']} and {$date['end']}";
		$returnArr=array();
		$dateList=$this->select($sql);
		$qualityOptions=$this->_getGlobalData('quality_options');
		$optionKey=array_keys($qualityOptions);	//获取key值
		$min=min($optionKey);
		$optionNum=array_fill($min,count($optionKey),0);	//各种质检类型的数量
		$deduction=0;	//加分
		$bonus=0;		//减分
		$statusNum=array_fill(1,5,0);	//各种状态
		foreach ($dateList as $value){
			$returnArr[date('Ymd',$value['create_time'])]['option_num'][$value['option_id']]++;
			$optionNum[$value['option_id']]++;
			if ($value['scores']>0){
				$deduction+=$value['scores'];
				$returnArr[date('Ymd',$value['create_time'])]['deduction']+=$value['scores'];
			}
			if ($value['scores']<0){
				$bonus+=$value['scores'];
				$returnArr[date('Ymd',$value['create_time'])]['bonus']+=$value['scores'];
			}
			$statusNum[$value['status']]++;
			$returnArr[date('Ymd',$value['create_time'])]['status_num'][$value['status']]++;
		}
		$returnArr['total']=array(
			'option_num'=>$optionNum,
			'deduction'=>$deduction,
			'bonus'=>$bonus,
			'status_num'=>$statusNum,
		);
		return $returnArr;
	}
	
	
	/**
	 * 统计功能
	 * @param int $gameTypeId
	 * @param array $operatorId
	 * @param array $date
	 */
	public function getStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select create_time,option_id,status,scores from {$this->tName()} where game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['first']} and {$date['last']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr['option_num'][$value['option_id']]++;
				$returnArr['status_num'][$value['status']]++;
				if ($value['scores']>0){
					$returnArr['deduction']+=$value['scores'];
				}
				if ($value['scores']<0){
					$returnArr['bonus']+=$value['scores'];
				}
			}
			return $returnArr;
		}
		return false;
	}
	
	/**
	 * 获取运营商统计
	 * @param int $gameTypeId
	 * @param array $operatorIds
	 * @param array $date
	 */
	public function getOperatorStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select create_time,option_id,status,scores from {$this->tName()} where game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr[date('Ymd',$value['create_time'])]['option_num'][$value['option_id']]++;
				$returnArr[date('Ymd',$value['create_time'])]['status_num'][$value['status']]++;
				$returnArr['total']['option_num'][$value['option_id']]++;
				$returnArr['total']['status_num'][$value['status']]++;
				
				$returnArr['total']['qulity']++;
				$returnArr[date('Y-m-d',$value['create_time'])]['qulity']++;
				if ($value['scores']>0){
					$returnArr[date('Y-m-d',$value['create_time'])]['deduction']+=$value['scores'];
					$returnArr[date('Y-m-d',$value['create_time'])]['deduction_num']++;
					$returnArr['total']['deduction']+=$value['scores'];
					$returnArr['total']['deduction_num']++;
				}
				if ($value['scores']<0){
					$returnArr[date('Y-m-d',$value['create_time'])]['bonus']+=$value['scores'];
					$returnArr[date('Y-m-d',$value['create_time'])]['bonus_num']++;
					$returnArr['total']['bonus']+=$value['scores'];
					$returnArr['total']['bonus_num']++;
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
	 * 获取运营商天数统计
	 * @param int $gameTypeId
	 * @param array $operatorIds
	 * @param array $date
	 */
	public function getOperatorDayStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select create_time,option_id,status,scores from {$this->tName()} where game_type_id={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$curKey=intval(date('H',$value['create_time']));
				$returnArr[$curKey]['option_num'][$value['option_id']]++;
				$returnArr[$curKey]['status_num'][$value['status']]++;
				$returnArr['total']['option_num'][$value['option_id']]++;
				$returnArr['total']['status_num'][$value['status']]++;
				
				$returnArr['total']['qulity']++;
				$returnArr[date('H',$value['create_time'])]['qulity']++;
				if ($value['scores']>0){
					$returnArr[$curKey]['deduction']+=$value['scores'];
					$returnArr[$curKey]['deduction_num']++;
					$returnArr['total']['deduction']+=$value['scores'];
					$returnArr['total']['deduction_num']++;
				}
				if ($value['scores']<0){
					$returnArr[$curKey]['bonus']+=$value['scores'];
					$returnArr[$curKey]['bonus_num']++;
					$returnArr['total']['bonus']+=$value['scores'];
					$returnArr['total']['bonus_num']++;
				}
			}
			return $returnArr;
		}
		return false;
	}
}