<?php
/**
 * 工单发送管理类
 * @author PHP-朱磊
 *
 */
class Object_OrderManage extends Object implements Serializable {
	
	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline = null;
	
	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms=null;
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder = null;
	
	/**
	 * Model_UserProiorityOperator
	 * @var Model_UserProiorityOperator
	 */
	private $_modelUserProiorityOperator = null;
	
	/**
	 * 是否更新的标识
	 * @var int
	 */
	private $_isUpdate = 0;
	
	/**
	 * 未分配的工单
	 * @var array Object_WorkOrder
	 */
	protected $_workOrder = array ();
	
	public function __construct() {
	
	}
	

	public function sendOrder() {
		$num=count($this->_workOrder);
		for ($i=0;$i<$num;$i++){
				$curWorkOrder=array_shift($this->_workOrder);
				$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
				$roomClass=$this->_utilRooms->getRoom($curWorkOrder['room_id']);	//获取此工单所属的房间
				$userClasses=$roomClass->findOnlineUser();	//获取房间内在线用户
				if (!$userClasses)break;	//如果没有用户将退出			
				$minUserClass=null;	//最少工单的那个用户对象 
				$minOrderNum=null;		//最少工单数
				foreach ($userClasses as $userClass){
					if ($userClass->checkCurOperLv(array($curWorkOrder['game_type_id'],$curWorkOrder['operator_id'],$curWorkOrder['vip_level']))){//查看用户是否有足够的权力处理此工单
						if ($minOrderNum===null){//如果是第一次
							$minOrderNum=$userClass->getOrderNum();	//获取此用户的工单数
							$minUserClass=$userClass;//记住这个最少数的用户对象.
						}else {
							if ($userClass->getOrderNum() < $minOrderNum){//如果当前对象的工单数小于之前最小的工单数,那么就将最小对象给他
								
								$minOrderNum=$userClass->getOrderNum();
								$minUserClass=$userClass;
							}
						}
					}
				}
				if ($minUserClass===null){//如果未找合适的对象将
					array_push($this->_workOrder,$curWorkOrder);	//将此工单压入到最后
				}else {//如果找到此对象的话.
					$this->_updateWorkOrderOwner($minUserClass,$curWorkOrder);
					$this->_isUpdate=1;
				}
		}

		
		
		/*
		/**
		 * 发送工单,逻辑如下:
		 * 首先找到正在处理工单的房间.然后循环未分配的工单.
		 * 先找到符合这个工单的用户,然后判断此用户的优先级别是否为1,如果不为1的话,那就么就找到为1级优先的最少的那个用户
		 * 查看这个优先级为1级的和工单数最少的这个用户的工单数量是大于5,如果工单数最少的那个人比1级优先级的那个用户还要少5个.
		 * 就要把工单分配给最少的那个用户,不然就还是分配给1级优先级的那个用户
		
		#------查找目前处于工作状态的房间------#
		$this->_utilRomeoms=$this->_getGlobalData('Util_Rooms','object');
		$roomClasses=$this->_utilRooms->findAllRooms();
		$curWorkClass=null;
		foreach ($roomClasses as $roomClass){
			list($startTime,$endTime)=$roomClass->getRoomTime();
			if (CURRENT_TIME>$startTi && CURRENT_TIME<$endTime){//如果此房间处于正在工作时间内
				$curWorkClass=$roomClass;
				break;
			}
		}
		#------查找目前处于工作状态的房间------#
		$i=1;
		#------循环分配工单------#
		$this->_isUpdate=1;
		$retryNum=0;	//重试次数
		while (count($this->_workOrder)){
			$curWorkOrder=array_shift($this->_workOrder);		//推出一个工单
			$orderGameType=$curWorkOrder['game_type'];
			$orderOperatorId=$curWorkOrder['operator_id'];
			
			$userClass=$curWorkClass->findMinOrderNumToUser(array($orderGameType,$orderOperatorId));	//返回当前可分配工单最少数的那个用户
			if (!is_object($userClass)){//如果找不到最少工单那个用户就continue;
				array_push($this->_workOrder,$curWorkOrder);//将这个找不到的工单排到最后
				if ($retryNum>count($this->_workOrder)){//如果重试次数大于总的未分配工单数的话就表示退出,表示这些工单没有一个能发出去的.
					return false;
				}
				$retryNum++;
				continue;
			}
			$retryNum=0;//如果找到了就将重试次数变为0
			
			if (!$userClass->checkCurOperLv(array($orderGameType,$orderOperatorId,$i))){//如果这个工单数最少的这个用户不是属于当前级别的
				$minNum=$userClass->getOrderNum();	//获取这个用户当天的工单量
				
				while (true){
					$firstLvUserClass=$curWorkClass->findOperMinOrderUser(array($orderGameType,$orderOperatorId,$i));
					if (is_object($firstLvUserClass)){
						break;
					}
					if (!is_object($firstLvUserClass) || $i>3){//如果找到了,或是等级大于3了就退出了
						$i++;
						continue;	
					}
					
				}
				if (is_object($firstLvUserClass)){	//如果找到了这个用户的话
					$firstLvNum=$firstLvUserClass->getOrderNum();
					if ($firstLvNum-$minNum>5){	//如果这个运营商等级为1级的用户的工单数量减去最少用户的工单数还大于5的话就表示相差5个工单了,那么就要把这个工单分配给工单最少的那个用户
						$this->_updateWorkOrderOwner($userClass,$curWorkOrder);
						continue;
					}else {
						$this->_updateWorkOrderOwner($firstLvUserClass,$curWorkOrder);
						continue;
					}
				}
			}else {
				$this->_updateWorkOrderOwner($userClass,$curWorkOrder);
				continue;
			}
			
		}
		
		#------循环分配工单------#
		*/

	}
	
	/**
	 * 更新拥有者
	 */
	private function _updateWorkOrderOwner(Object_UserInfo $userClass,$workOrder) {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrder->update ( array ('owner_user_id' => $userClass['_id'] ), "Id={$workOrder['Id']}" );
		$userClass->addToOrderNum(1);	//增加自身的工单数量
		$userClass->setUpdateInfo(1);		
	}
	
	/**
	 * 获取一条工单记录
	 * @return array
	 */
	public function getWorkOrder() {
		return array_shift ( $this->_workOrder );
	}
	
	/**
	 * 增加工单
	 * @return void
	 */
	public function addOrder($order) {
		array_push ( $this->_workOrder, $order );
	}
	
	/**
	 * 序列化对象
	 */
	public function serialize() {
		$data = array ();
		$data ['work_order'] = $this->_workOrder;
		return serialize ( $data );
	}
	
	/**
	 * @param array $serialized 反序列化对象
	 */
	public function unserialize($data) {
		$data = unserialize ( $data );
		$this->_workOrder = $data ['work_order'];
	}
	
	public function __destruct() {
		if ($this->_isUpdate == 1) {
			$this->_saveInfo ();
		}
	}
	
	private function _saveInfo() {
		$savePath = WORKORDER_DIR . "/workOrder.serialize.php";
		$userInfo = serialize ( $this );
		$string = "<?php \r\n";
		$string .= "return '{$userInfo}'; ";
		$this->_writeData ( $savePath, $string );
	}
	
	/**
	 * 设置是否保存对象
	 * @param int $value
	 */
	public function setUpdateInfo($value = 0) {
		$value = abs ( intval ( $value ) );
		$this->_isUpdate = $value;
	}

}