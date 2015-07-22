<?php
/**
 * 工单发送管理类
 * @author PHP-朱磊
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
	 * Model_StatsUser
	 * @var Model_StatsUser
	 */
	private $_modelStatsUser;
	
	/**
	 * 自动队列工单表
	 * @var Model_AutoOrderQueue
	 */
	private $_modelAutoOrderQueue;
	
	/**
	 * 未分配的工单
	 * @var array Object_WorkOrder
	 */
	private $_workOrder = array ();
	
	/**
	 * 统计时间
	 * @var array
	 */
	protected $_statsDate=array();
	
	/**
	 * 未完成的工单数
	 * @var array
	 */
	protected $_orderNum=array();
	
	/**
	 * 查找工单最少的那个用户
	 * @param array $userClasses
	 * @param array $workOrder
	 * @return Object_UserInfo
	 */
	private function _findMinUser($userClasses,$workOrder){
		if (count($userClasses)==1){//如果只有一个人在线,那么就直接取此对象
			$userClass=reset($userClasses);		
			if ($userClass->checkCurOperLv(array($workOrder['game_type_id'],$workOrder['operator_id'],$workOrder['vip_level']))){
				return $userClass;
			}else {
				return false;
			}
		}		
		$minUserClass=null;	//最少工单的那个用户对象
		$minOrderNum=null;		//最少工单数
		foreach ($userClasses as $userClass){
			if ($userClass->checkCurOperLv(array($workOrder['game_type_id'],$workOrder['operator_id'],$workOrder['vip_level']))){//查看用户是否有足够的权力处理此工单
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
		if ($minUserClass===null)return false;	//如果找不到对象就返回false
		return $minUserClass;
	}
	
	/**
	 * 清除已经发送的工单
	 * @param array $ids 
	 */
	private function _clearSendOrder($ids){
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->delById($ids);
	}
	
	public function sendOrder() {
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_workOrder=$this->_modelAutoOrderQueue->findAll();
		$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
		$sendOrder=array();
		$updateUser=array();
		foreach ($this->_workOrder as $workOrder){
			$roomClass=$this->_utilRooms->getRoom($workOrder['room_id']);
			$userClasses=$roomClass->findOnlineUser();	//查找房间内所有在线的用户
			if (!$userClasses)continue;//如果没有用户对象,就跳过
			$userClass=$this->_findMinUser($userClasses,$workOrder);//找到工单数最少的那个用户
			if ($userClass===false)continue;	//如果没有找到对象就跳过		
			array_push($sendOrder,$workOrder['Id']);	//压入数组.表示此ID的工单已经发送出去了.
			$this->updateWorkOrderOwner($userClass,$workOrder);
		}
		if (count($sendOrder))$this->_clearSendOrder($sendOrder);	//如果有工单发出去,就删除这个工单
	}
	
	/**
	 * 更新拥有者
	 */
	public function updateWorkOrderOwner(Object_UserInfo $userClass,$workOrder) {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrder->update ( array ('owner_user_id' => $userClass['_id'] ), "Id={$workOrder['work_order_id']}" );
		$userClass->addToOrderNum(1);	//增加自身的工单数量
		$userClass->setUpdateInfo(1);		
	}
	
	/**
	 * 增加工单
	 * @return void
	 */
	public function addOrder($order) {
//		$this->_orderNum[$order['vip_level']]++;//vip几级的工单数+1
		if (!$order['room_id'])return false;	//如果没有房间ID,就跳过.
		$orderArr=array();//防止有过多的字段被存进对象,减小对象文件的大小
		$orderArr['game_type_id']=$order['game_type_id'];
		$orderArr['work_order_id']=$order['Id'];
		$orderArr['vip_level']=$order['vip_level'];
		$orderArr['room_id']=$order['room_id'];
		$orderArr['operator_id']=$order['operator_id'];
		$orderArr['create_time']=CURRENT_TIME;
		
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->add($orderArr);
	}
	
	/**
	 * 删除工单
	 * @param array $ids
	 */
	public function delOrder($ids){
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->delById($ids);
	}
	
	/**
	 * 减少/增加工单数
	 * @param int $vipLevel
	 * @param int $num 默认-1
	 */
	public function setOrderNum($vipLevel,$num=-1){
		if (!is_numeric($vipLevel))return false;//如果不是数字就退出
		if ($this->_orderNum[$vipLevel]>0)$this->_orderNum[$vipLevel]+=$num;//防止为负数
	}
	
	/**
	 * 设置未处理工单数目
	 * @param array $numArr
	 */
	public function setupOrderNum($numArr){
		$this->_orderNum=$numArr;
	}
	
	/**
	 * 序列化对象
	 */
	public function serialize() {
		$data = array ();
		$data ['order_num']=$this->_orderNum;
		return serialize ( $data );
	}
	
	/**
	 * @param array $serialized 反序列化对象
	 */
	public function unserialize($data) {
		$data = unserialize ( $data );
		$this->_orderNum=$data['order_num'];
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