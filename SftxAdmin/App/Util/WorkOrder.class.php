<?php
class Util_WorkOrder extends Control {
	
	public function __construct(){
		Tools::import('Object_OrderManage');
	}
	
	/**
	 * 返回管理工单对象
	 * @return Object_OrderManage
	 */
	public function getOrderManage(){
		$filePath = WORKORDER_DIR . "/workOrder.serialize.php";
		if (!file_exists($filePath)){
			return new Object_OrderManage();
		}
		return unserialize($this->_includeFile($filePath));
	}
}