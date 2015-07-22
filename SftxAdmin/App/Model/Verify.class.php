<?php
class Model_Verify extends Model {
	protected $_tableName='verify';
	
	public function __construct(){
		
	}
	
	/**
	 * 根据work_order_id来查找记录
	 * @param int $workOrderId
	 */
	public function findByWorkOrderId($workOrderId){
		return $this->select("select * from {$this->tName()} where work_order_id={$workOrderId}");
	}

}