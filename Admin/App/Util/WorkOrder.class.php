<?php
class Util_WorkOrder extends Control {
	
	public function __construct(){
		Tools::import('Object_OrderManage');
	}
	
	/**
	 * Object_OrderManage
	 * @var Object_OrderManage
	 */
	private $_objectOrderManage=null;
	
	/**
	 * 返回管理工单对象
	 * @return Object_OrderManage
	 */
	public function getOrderManage(){
		if (is_null($this->_objectOrderManage)){
			$filePath = WORKORDER_DIR . "/workOrder.serialize.php";
			if (!file_exists($filePath)){
				return new Object_OrderManage();
			}
			$this->_objectOrderManage=unserialize($this->_includeFile($filePath));
		}
		return $this->_objectOrderManage;
	}
}