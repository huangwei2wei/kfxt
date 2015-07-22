<?php
class Object_WorkOrder extends Object implements Serializable {
	/**
	 * 游戏类型
	 * @var int
	 */
	protected $_gameType;
	/**
	 * 工单状态
	 * @var int
	 */
	protected $_status;
	/**
	 * 工单等级
	 * @var int
	 */
	protected $_level;
	/**
	 * 运营商id
	 * @var int
	 */
	protected $_operatorId;
	/**
	 * 工单由谁处理ID
	 * @var int
	 */
	protected $_ownerUserId;
	
	
	/**
	 * 序列化
	 */
	public function serialize() {
		
	}

	/**
	 * @param unknown_type $serialized
	 */
	public function unserialize($serialized) {
		
	}

	
}