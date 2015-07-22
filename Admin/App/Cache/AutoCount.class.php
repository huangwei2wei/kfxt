<?php
define('COUNT_VIP','count_vip_');
define('COUNT_ROOM','count_room_');
define('COUNT_USER','count_user_');
/**
 * 自动计数器
 * @author php-朱磊
 *
 */
class Cache_AutoCount extends Cache {
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;
	
	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;
	
	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * VIP数值计算
	 * @param $expire 默认180秒
	 */
	public function vipCount($expire=180){
		$vipCount=$this->get(COUNT_VIP);
		if (!$vipCount){
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$vipCount=$this->_modelWorkOrder->select("SELECT vip_level,COUNT(Id) AS vip_count FROM {$this->_modelWorkOrder->tName()} WHERE `status`=1 GROUP BY vip_level");
			$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
			$tempCount=array();
			foreach ($vipCount as $value){
				if ($value['vip_level']=='')continue;//防止空或是null
				$tempCount[$value['vip_level']]=$value['vip_count'];
			}
			$objectOrder=$this->_utilWorkOrder->getOrderManage();
			$objectOrder->setupOrderNum($tempCount);
			unset($objectOrder);
			$this->set(COUNT_VIP,$vipCount,$expire);
		}
	}
	
	/**
	 * 房间计数
	 * @param $expire 默认600秒
	 */
	public function roomCount($expire=600){
		$roomCount=$this->get(COUNT_ROOM);
		if (!$roomCount){
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$roomCount=$this->_modelWorkOrder->select("SELECT room_id,COUNT(Id) AS room_count FROM {$this->_modelWorkOrder->tName()} WHERE `status`=1 GROUP BY room_id");
			$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
			foreach ($roomCount as $value){
				$roomClass=$this->_utilRooms->getRoom($value['room_id']);
				if (!is_object($roomClass))continue;	//防止没有房间的
				$roomClass->setupIncompleteOrder($value['room_count']);
				$roomClass->setUpdateInfo(1);
				$roomClass=null;
				unset($roomClass);
			}
			$this->set(COUNT_ROOM,$roomCount,$expire);
		}
	}
	
	/**
	 * 用户计算器
	 * @param $expire 默认180秒
	 */
	public function userCount($expire=180){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		if (!is_object($userClass))return ;
		$isTimeOut=$this->get(COUNT_USER.$userClass['_id']);
		if (!$isTimeOut){
			$gameTypes=$userClass->getUserGameTypeIds();
			$operatorIds=$userClass->getUserOperatorIds();
			if (!count($gameTypes) || !count($operatorIds))return ;	//如果没有负责的游戏或是负责的运营商,就退出
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$count=$this->_modelWorkOrder->select("select count(Id) as count from {$this->_modelWorkOrder->tName()} where game_type in (".implode(',',$gameTypes).") and operator_id in (".implode(',',$operatorIds).") and owner_user_id = {$userClass['_id']} and status=1",1);
			$count=$count['count'];
			$this->set(COUNT_USER.$userClass['_id'],true,$expire);
			$userClass->setupIncomplete($count);
			$userClass->setUpdateInfo(1);
		}
		
		
	}
	
}