<?php
/**
 * 房间类
 * @author php-朱磊
 *
 */
class Object_Room extends Object implements Serializable {
	/**
	 * 房间id
	 * @var int
	 */
	protected $_id = 0;

	/**
	 * 房间名称
	 * @var string
	 */
	protected $_roomName = '';

	/**
	 * 房间的入口
	 * @var boolean
	 */
	protected $_entrance = FALSE;

	/**
	 * 房间的出口
	 * @var boolean
	 */
	protected $_exit = FALSE;

	/**
	 * 房间内的员工
	 * @var array
	 */
	protected $_userClassList = array ();

	/**
	 * 房间的开启与结束时间
	 * @var array
	 */
	protected $_roomStartEndTime = array ();

	/**
	 * 0:不更新 1:更新
	 * @var int
	 */
	private $_isUpdate = 0;

	/**
	 * 房间内的工单
	 * @var array
	 */
	protected $_orderNum=array();

	public function __construct() {

	}

	/**
	 * 为房间增加一个工单数
	 * @param int $num
	 */
	public function addOrderNum($num){
		$this->_orderNum['total']+=$num;
		$this->_orderNum['incomplete']+=$num;
	}

	/**
	 * 创建新房间初始化
	 */
	public function initialize($roomInfo) {
		$this->_id = $roomInfo ['Id'];			//房间id
		$this->_roomName = $roomInfo ['name']; 	//房间名
	}

	/**
	 * 完成一个工单数
	 * @param int $num
	 */
	public function completeOrder($num){
		if ($this->_orderNum['incomplete']>0)
			$this->_orderNum['incomplete']-=$num;
	}

	/**
	 * 查找工单最少的那个用户
	 * @return Object_UserInfo
	 */
	public function findMinOrderNumToUser($operatorArr) {
		$users = $this->findOnlineUser (); 		//获取房间内在线用户
		if (!$users)return false;				//如果没有用户将退出
		$minUserClass=null;						//只会记住最小的那个用户对象
		$num=null;								//只会记住最小的工单数
		foreach($users as $userClass){			//排序法
			if (!$userClass->checkCurOperLv($operatorArr))continue;
			$curNum=$userClass->getOrderNum();
			if ($num===null){					//第一次循环
				$num=$curNum;
				$minUserClass=$userClass;
			}
			if ($curNum<$num){					//如果当前数比上一次的数还要少
				$num=$curNum;
				$minUserClass=$userClass;
			}
		}
		return $minUserClass;
	}

	/**
	 * 查找工单最少,并且运营商等级为$lv级的用户
	 * @return Object_UserInfo
	 */
	public function findOperMinOrderUser($operatorArr){
		$users=$this->findOnlineUser();
		if (!$users)return false;		//没有用户将退出
		$minUserClass=null;						//只会记住最小的那个用户对象
		$num=null;								//只会记住最小的工单数
		foreach($users as $userClass){			//排序法
			if (!$userClass->checkCurOperLv($operatorArr))continue;	//如果找不到这个等级的用户就跳过本次,继续下一次循环
			$curNum=$userClass->getOrderNum();
			if ($num===null){					//第一次循环
				$num=$curNum;
				$minUserClass=$userClass;
			}
			if ($curNum<$num){					//如果当前数比上一次的数还要少
				$num=$curNum;
				$minUserClass=$userClass;
			}
		}
		return $minUserClass;
	}

	/**
	 * 设置房间的开始时间与结束时间
	 * @return void
	 */
	public function setRoomTime($startTime,$endTime){
		$this->_roomStartEndTime['start']=$startTime;
		$this->_roomStartEndTime['end']=$endTime;
	}

	/**
	 * 获取房间内在线用户
	 * @return Object_UserInfo array
	 */
	public function findOnlineUser() {
		$utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$utilOnline = $this->_getGlobalData ( 'Util_Online', 'object' );
		$onlineUserArr = $utilOnline->getOnlineUser ('user_name'); //获取所有在线用户
		if (!count($onlineUserArr))return false;	//如果没有在线用户,退出
		$userClasses = array ();
		$roomOnlineUsers = array_intersect ( $this->_userClassList, $onlineUserArr ); //计算交集,获取在线的用户
		if (!count($roomOnlineUsers))return false;	//如果没有房间内的在线用户,退出
		foreach ( $roomOnlineUsers as $value ) {
			$userClasses [$value] = $utilRbac->getUserClass ( $value );
		}
		return $userClasses;
	}

	/**
	 * 获取房间内所有的员工
	 * @return Object_UserInfo array
	 */
	public function findAllUser() {
		$utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClasses = array ();
		foreach ( $this->_userClassList as $userName ) {
			$userClasses [$userName] = $utilRbac->getUserClass ( $userName );
		}
		return $userClasses;
	}

	/**
	 * 踢出所有员工
	 */
	public function kickAllUser() {
		$rbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		foreach ( $this->_userClassList as $userName ) {
			$userClass = $rbac->getUserClass ( $userName );
			$userClass->outRoom (); //用户退出房间
			$userClass->setUpdateInfo ( 1 );
			$userClass = null; //释放资源
		}
		$this->_userClassList = array ();
		$this->_isUpdate = 1;
	}

	/**
	 * 踢出一个用户
	 * @param Object_UserInfo $userClass 用户对象
	 * @return boolean
	 */
	public function kickUser(Object_UserInfo $userClass) {
		$key = array_search ( $userClass ['_userName'], $this->_userClassList );
		if ($key !== false) {
			unset ( $this->_userClassList [$key] ); //删除掉房间内用户的对象索引
			$userClass['_roomId']=0; //用户退出房间
			$userClass->setUpdateInfo ( 1 );
			$this->_isUpdate = 1;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 增加一个员工
	 * @param Object_UserInfo 用户
	 * @return int 返回-3,-2,-1,1<br />
	 * -3:此员工已经加入了别的房间<br />
	 * -2:出口未打开<br />
	 * -1:已经有此员工<br />
	 * 1:正常加入员工<br />
	 */
	public function addUser(Object_UserInfo $userClass) {
		if ($this->_entrance == false)
			return - 2;
		if ($userClass ['_roomId'] == $this->_id)
			return - 1;
		if ($userClass ['_roomId'] != 0)
			return - 3;
		if (array_search ( $userClass ['_userName'], $this->_userClassList )!==false)
			return - 1;

		array_push ( $this->_userClassList, $userClass ['_userName'] ); //记录此员工的userName
		$userClass->inRoom ( $this->_id );
		$userClass->setUpdateInfo ( 1 );
		$this->_isUpdate = 1;
		return 1;
	}

	public function __destruct() {
		if ($this->_isUpdate == 1) {
			$this->_saveInfo ();
		}
	}

	/**
	 * 存储信息
	 */
	private function _saveInfo() {
		$savePath = ROOMS_DIR . "/{$this->_id}.serialize.php";
		$roomInfo = serialize ( $this );
		$string = "<?php \r\n";
		$string .= "return '{$roomInfo}'; ";
		$this->_writeData ( $savePath, $string );
	}

	/**
	 * 是否更新 0:不更新 1:更新
	 * @param int $value
	 */
	public function setUpdateInfo($value = 0) {
		$value = abs ( intval ( $value ) );
		$this->_isUpdate = $value;
	}

	/**
	 * (对象序列化函数)
	 * @see Serializable::serialize()
	 */
	public function serialize() {
		$data = array ();
		$data ['_id'] = $this->_id;
		$data ['_roomName'] = $this->_roomName;
		$data ['_entrance'] = $this->_entrance;
		$data ['_exit'] = $this->_exit;
		$data ['_userClassList'] = $this->_userClassList;
		$data ['_roomStartEndTime'] = $this->_roomStartEndTime;
		$data ['_orderNum']=$this->_orderNum;
		return serialize ( $data );
	}

	/**
	 * (对象反序列化函数)
	 * @see Serializable::unserialize()
	 */
	public function unserialize($str) {
		$objectVars = unserialize ( $str );
		$this->_id = $objectVars ['_id'];
		$this->_roomName = $objectVars ['_roomName'];
		$this->_entrance = $objectVars ['_entrance'];
		$this->_exit = $objectVars ['_exit'];
		$this->_userClassList = $objectVars ['_userClassList'];
		$this->_roomStartEndTime = $objectVars ['_roomStartEndTime'];
		$this->_orderNum=$objectVars['_orderNum'];
	}

}