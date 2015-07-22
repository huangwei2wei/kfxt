<?php
/**
 * 工单表
 * @author PHP-朱磊
 *
 */
class Model_WorkOrder extends Model {
	protected $_tableName = 'work_order';	//表名

	public function __construct() {

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
		$sql="select main.*,qa.Id as qa_id,qa.qa,qa.is_timeout,qa.content,qa.reply_name,qa.create_time as qa_time,detail.content as detail ".
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
	 * 统计功能
	 * @param int $gameTypeId
	 * @param array $operatorId
	 * @param array $date
	 */
	public function getStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select * from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['first']} and {$date['last']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr['total']++;
				$returnArr['ev'][$value['evaluation_status']]++;	//评价
			}
			return $returnArr;
		}
		return false;
	}
	
	public function getOperatorStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select * from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$returnArr[date('Ymd',$value['create_time'])]['total']++;
				$returnArr[date('Ymd',$value['create_time'])]['ev'][$value['evaluation_status']]++;	//评价
				$returnArr['total']['total']++;
				$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
			}
			return $returnArr;
		}
		return false;
	}
	
	public function getOperatorDayStatsNum($gameTypeId,$operatorIds,$date){
		$operatorIds=implode(',',$operatorIds);
		$dataList=$this->select("select * from {$this->tName()} where game_type={$gameTypeId} and operator_id in ({$operatorIds}) and create_time between {$date['start']} and {$date['end']}");
		if ($dataList){
			$returnArr=array();
			foreach ($dataList as $value){
				$curKey=intval(date('H',$value['create_time']));
				$returnArr[$curKey]['total']++;
				$returnArr[$curKey]['ev'][$value['evaluation_status']]++;	//评价
				$returnArr['total']['total']++;
				$returnArr['total']['ev'][$value['evaluation_status']]++;	//评价
			}
			return $returnArr;
		}
		return false;
	}
	
	
	
}