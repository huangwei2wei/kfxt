<?php
class Model_Verify extends Model {
	protected $_tableName='verify';
	
	const FINISH_STATUS=8;	//已经回赠玩家
	
	public function __construct(){
		
	}
	
	/**
	 * 获取部门数组
	 */
	public function getDep(){
		return array(1=>'客服部',2=>'技术部',3=>'测试部');
	}
	
	/**
	 * 根据work_order_id来查找记录
	 * @param int $workOrderId
	 */
	public function findByWorkOrderId($workOrderId){
		return $this->select("select * from {$this->tName()} where work_order_id={$workOrderId}");
	}
	
	/**
	 * 返回工单对应的5条查证处理,如果不够5条就用此工单玩家的相关查证处理补满5条
	 * @param string $userAccount
	 * @param int $workOrderId
	 */
	public function findByUserAccountWorkId($userAccount,$workOrderId){
		$dataList=$this->findByWorkOrderId($workOrderId);
		$num=count($dataList);
		if ($num>5)return $dataList;
		$lastNum=5-$num;
		if ($num>0){
			$ids=array();
			foreach ($dataList as $list){
				array_push($ids,$list['Id']);
			}	
			$userAccountList=$this->select("select * from {$this->tName()} where game_user_account='{$userAccount}' and Id not in (".implode(',',$ids).") limit ".$lastNum);
		}else {
			$userAccountList=$this->select("select * from {$this->tName()} where game_user_account='{$userAccount}' limit ".$lastNum);
		}

		if ($userAccountList){
			foreach ($userAccountList as $list){
				array_push($dataList,$list);
			}
		}
		return $dataList;
	}

	public function getOperatorStatsNum($gameTypeId,$operatorIds,$date){
		$dataList=$this->select("select user_id,finish_user_id,create_time from {$this->tName()} where game_type_id={$gameTypeId} and operator_id in (".implode(',',$operatorIds).") and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$curTime=date('Y-m-d',$value['create_time']);
				$returnArr[$curTime]['submit']++;
				$returnArr['total']['submit']++;
				if ($value['finish_user_id']){
					$returnArr[$curTime]['finish']++;
					$returnArr['total']['finish']++;
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
	
	public function getOperatorDayStatsNum($gameTypeId,$operatorIds,$date){
		$dataList=$this->select("select user_id,finish_user_id,create_time from {$this->tName()} where game_type_id={$gameTypeId} and operator_id in (".implode(',',$operatorIds).") and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$curHour=date('H',$value['create_time']);
				$returnArr[$curHour]['submit']++;
				$returnArr['total']['submit']++;
				if ($value['finish_user_id']){
					$returnArr[$curHour]['finish']++;
					$returnArr['total']['finish']++;
				}
			}
			return $returnArr;
		}
		return array();
	}
}