<?php
/**
 * 自动工单队列表
 * @author PHP-朱磊
 *
 */
class Model_AutoOrderQueue extends Model {
	protected $_tableName='auto_orderqueue';
	
	public function delByWorkOrderId($workOrderId){
		return $this->execute("delete from {$this->tName()} where work_order_id={$workOrderId}");
	}
}