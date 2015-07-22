<?php
class Model_WorkOrderDetail extends Model {
	protected $_tableName='work_order_detail';
	
	/**
	 * 跟据work_order_id查找记录
	 * @param int $workOrderId
	 */
	public function findByWorkOrderId($workOrderId){
		return $this->select("select Id,content from {$this->tName()} where work_order_id={$workOrderId}",1);	
	}
}