<?php
/**
 * 用户对象
 * @author PHP-朱磊
 */
class Object_UserInfo extends Object implements Serializable {
	protected $_id; //用户id
	protected $_userName; //用户名
	protected $_departmentId; //所属部门
	protected $_serviceId;	//客服id
	protected $_roles; //所拥有的角色
	protected $_nickName; //姓名
	protected $_dateCreated; //添加时间
	protected $_dateUpdated; //更新时间
	protected $_roomId;					//所在的房间名
	protected $_orgId=0;				//组id
	protected $_workOrderStatus=false;	//用户接收工单状态,默认false关闭.
	protected $_operatorIds = array (); //用户的运营商
	protected $_orderNum=array();		//工单数,以日期为key,数量为value
	protected $_incompleteOrderNum=0;	//未完成的工单数
	protected $_fullName;				//完整名称
	protected $_replyNum=array();		//回复数
	protected $_orderVipLevel=array();	//用户能处理的VIP等级
	/**
	 * Object_UserMail
	 * @var Object_UserMail
	 */
	protected $_mail;	//用户email邮箱

	private $_isUpdate = 0; //是否更新 0:更新 1:不更新


	public function __construct() {

	}

	/**
	 * 返回用户能管理的游戏id
	 */
	public function getUserGameTypeIds(){
		$arr=array();
		foreach ($this->_operatorIds as $value){
			array_push($arr,$value['game_type_id']);
		}
		return $arr;
	}

	/**
	 * 返回用户能管理的运营商id
	 */
	public function getUserOperatorIds(){
		$arr=array();
		foreach ($this->_operatorIds as $value){
			array_push($arr,$value['operator_id']);
		}
		return $arr;
	}

	/**
	 * 将自身的回复数加$num
	 */
	public function addToReplyNum($num){
		$key=date('Ymd',CURRENT_TIME);
		if (!$this->_replyNum[$key])$this->_replyNum[$key]=0;
		$this->_replyNum[$key]+=$num;
		$this->_replyNum['total']+=$num;
	}

	/**
	 * 获取当天回复数,默认总回复数.
	 */
	public function getReplyNum($key='current'){
		if ($key=='current')$key=date('Ymd',CURRENT_TIME);
		if ($this->_replyNum[$key]===null)$this->_replyNum[$key]=0;
		return $this->_replyNum[$key];	//每天的上班日期做为key值
	}


	/**
	 * 将自身的工单数加1
	 */
	public function addToOrderNum($num){
		$key=date('Ymd',CURRENT_TIME);
		if (!$this->_orderNum[$key])$this->_orderNum[$key]=0;
		if (!$this->_orderNum['total'])$this->_orderNum['total']=0;
		if (!$this->_incompleteOrderNum)$this->_incompleteOrderNum=0;
		$this->_orderNum['total']+=$num;
		$this->_orderNum[$key]+=$num;
		$this->_incompleteOrderNum+=$num;//未回复数加1
	}

	/**
	 * 完成$num个工单
	 * @param $num
	 */
	public function setIncompleteOrderNum($num){
		$this->_incompleteOrderNum+=$num;
		if ($this->_incompleteOrderNum<0)$this->_incompleteOrderNum=0;	//防止变成负数
	}

	/**
	 * 获取当天/所有工单数
	 */
	public function getOrderNum($key=NULL){
		if ($key===null)$key=date('Ymd',CURRENT_TIME);
		if ($this->_orderNum[$key]===null)$this->_orderNum[$key]=0;
		return $this->_orderNum[$key];	//每天的上班日期做为key值
	}


	/**
	 * 检测用户是否属于当前运营商级别
	 */
	public function checkCurOperLv($operatorArr){
		list($gameTypeId,$operatorId,$vipLevel)=$operatorArr;
		foreach ($this->_operatorIds as $value){
			if (!is_array($this->_orderVipLevel))return false;	//如果没有设置VIP等级那么将直接退出
			//如果游戏类型ID相同,并且运营商ID相同,并且VIP等级也在处理范围内,将通过
			if ($value['game_type_id']==$gameTypeId && $value['operator_id']==$operatorId && in_array($vipLevel,$this->_orderVipLevel))
				return true;
		}
		return false;
	}

	/**
	 * 注册用户对象
	 * @param array $userInfo 用户信息
	 * @return void
	 */
	public function registerUserInfo($userInfo) {
		$this->_id = $userInfo ['Id'];
		$this->_userName = $userInfo ['user_name'];
		$this->_departmentId = $userInfo ['department_id'];
		$this->_nickName = $userInfo ['nick_name'];
		$this->_dateCreated = $userInfo ['date_created'];
		$this->_dateUpdated = $userInfo ['date_updated'];
		$this->_roomId = 0;
		if ($userInfo['order_vip_level'])//设置VIP等级
			$this->_orderVipLevel=explode(',',$userInfo['order_vip_level']);
		if ($userInfo ['roles'])//设置角色
			$this->_roles = explode ( ',', $userInfo ['roles'] );
		#------创建邮箱------#
		Tools::import('Object_UserMail');
		$this->_mail=new Object_UserMail();
		$this->_mail->createMail($userInfo);
		$this->_mail->setUpdateInfo(1);
		#------创建邮箱------#
	}

	public function serialize() {
		$data = array ();
		$data ['Id'] = $this->_id;
		$data ['user_name'] = $this->_userName;
		$data ['department_id'] = $this->_departmentId;
		$data ['service_id'] = $this->_serviceId;
		$data ['roles'] = $this->_roles;
		$data ['nick_name'] = $this->_nickName;
		$data ['date_created'] = $this->_dateCreated;
		$data ['date_updated'] = $this->_dateUpdated;
		$data ['roomId'] = $this->_roomId;
		$data ['operator_ids'] = $this->_operatorIds;
		$data ['orderNum'] = $this->_orderNum;
		$data ['orgId']=$this->_orgId;
		$data ['full_name']=$this->_fullName;
		$data ['reply_num']=$this->_replyNum;
		$data ['vip_level']=$this->_orderVipLevel;
		$data ['incompleteOrderNum']=$this->_incompleteOrderNum;
		return serialize ( $data );
	}

	public function unserialize($data) {
		$data = unserialize ( $data );
		$this->_orderVipLevel=$data['vip_level'];
		$this->_id = $data ['Id'];
		$this->_userName = $data ['user_name'];
		$this->_departmentId = $data ['department_id'];
		$this->_serviceId=$data['service_id'];
		$this->_roles = $data ['roles'];
		$this->_nickName = $data ['nick_name'];
		$this->_dateCreated = $data ['date_created'];
		$this->_dateUpdated = $data ['date_updated'];
		$this->_orgId = $data['orgId'];
		$this->_roomId = $data ['roomId'];
		$this->_operatorIds = $data ['operator_ids'];
		$this->_orderNum = $data['orderNum'];
		$this->_fullName = $data['full_name'];
		$this->_replyNum = $data['reply_num'];
		$this->_incompleteOrderNum=$data['incompleteOrderNum'];
	}

	/**
	 * 从数据库更新信息到对象
	 */
	public function setInfo(){
		$this->_setBaseInfo();
		$this->_setOperator();
		$this->_isUpdate=1;
	}


	/**
	 * 更新用户基础信息
	 */
	private function _setBaseInfo(){
		$modelUser=$this->_getGlobalData('Model_User','object');
		$userInfoArr=$modelUser->findByUserName($this->_userName);
		$this->_roles=$userInfoArr['roles']?explode(',',$userInfoArr['roles']):'';
		#------保存完整姓名------#
		$this->_departmentId=$userInfoArr['department_id'];
		$departmentList=$this->_getGlobalData('department');
		$this->_orgId=$userInfoArr['org_id'];
		if ($this->_orgId){
			$orgList=$this->_getGlobalData('org');
			$this->_fullName="{$this->_nickName}({$orgList[$this->_orgId]['name']})[{$departmentList[$this->_departmentId]['name']}]";
		}else {
			$this->_fullName="{$this->_nickName}[{$departmentList[$this->_departmentId]['name']}]";
		}
		#------保存完整姓名------#
		$this->_serviceId=$userInfoArr['service_id'];
		$this->_nickName=$userInfoArr['nick_name'];
		$this->_id=$userInfoArr['Id'];
		$this->_userName=$userInfoArr['user_name'];
		if ($userInfoArr['order_vip_level'])
			$this->_orderVipLevel=explode(',',$userInfoArr['order_vip_level']);
		$this->_updateMail();
	}

	/**
	 * 更新邮件
	 */
	private function _updateMail(){
		$this->_mail=$this->getUserMail();
		$this->_mail['_id']=$this->_id;
		$this->_mail['_userName']=$this->_userName;
		$this->_mail['_nickName']=$this->_nickName;
		$this->_mail->setUpdateInfo(1);
	}


	/**
	 * 获取邮件
	 * @param array $limit array(1,5),等于sql的limit
	 * @param array $where 获取邮件类型条件,sql-where
	 */
	public function getMail($limit=array(1,5),$where){
		$this->getUserMail();
		if (!is_array($where))$where=null;
		return $this->_mail->getMail($limit,$where);
	}

	/**
	 * 对用户对象发送邮件
	 */
	public function addMail($mail){
		$this->getUserMail();
		$this->_mail->addMail($mail);
	}

	/**
	 * 返回用户邮件对象,单例模式
	 * @return Object_UserMail
	 */
	public function getUserMail(){
		if (!$this->_mail){
			Tools::import('Object_UserMail','object');
			$this->_mail=$this->_includeFile ( USERS_DIR . "/{$this->_userName}/Mail.serialize.php" );
			$this->_mail=unserialize($this->_mail);
		}
		return $this->_mail;
	}

	/**
	 * 用户读取指定的邮件
	 * @param int $id
	 */
	public function readMail($id){
		$this->getUserMail();
		$this->_mail->readMail($id);
	}

	/**
	 * 更新用户运营商优先级
	 */
	private function _setOperator(){
		$modelUserProiorityOperator=$this->_getGlobalData('Model_UserProiorityOperator','object');
		$operatList=$modelUserProiorityOperator->findByUserId($this->_id);
		$this->_operatorIds=array();
		foreach ($operatList as $value){
			$this->_operatorIds[]=array(
				'game_type_id'=>$value['game_type_id'],
				'operator_id'=>$value['operator_id'],
				'priority_level'=>$value['priority_level'],
			);
		}
	}

	/**
	 * 用户进入指定的房间
	 * @param int $roomId
	 */
	public function inRoom($roomId){
		$this->_roomId=$roomId;
		$this->_workOrderStatus=true;
	}

	/**
	 * 用户退出房间
	 */
	public function outRoom(){
		if ($this->_roomId!=0){//如果在房间
			$utilRooms=$this->_getGlobalData('Util_Rooms');
			$roomClass=$utilRooms->getRoom($this->_roomId);
			if(is_object($roomClass)){
				if ($roomClass['_exit']){//如果房间出口是开的
					$roomClass->kickUser($this);
				}else {
					return false;
				}
			}
		}
		$this->_roomId=0;
		$this->_workOrderStatus=false;
		return true;
	}

	/**
	 * 是否更新 0:不更新 1:更新userclass,2更新邮件,3更新所有
	 * @param int $value
	 */
	public function setUpdateInfo($value = 0) {
		$value = abs ( intval ( $value ) );
		$this->_isUpdate = $value;
	}

	public function __destruct() {
		switch ($this->_isUpdate){
			case 1 :{
				$this->_saveInfo ();
				break;
			}
			case 2 :{
				$this->getUserMail();
				$this->_mail->setUpdateInfo(1);
				break;
			}
			case 3 :{
				$this->_saveInfo();
				$this->getUserMail();
				$this->_mail->setUpdateInfo(1);
				break;
			}
		}
	}

	private function _saveInfo() {
		$filePath=USERS_DIR . "/{$this->_userName}";
		if (!file_exists($filePath))mkdir($filePath,0777);
		$savePath = "{$filePath}/Info.serialize.php";
		$userInfo = serialize ( $this );
		$string = "<?php \r\n";
		$string .= "return '{$userInfo}'; ";
		$this->_writeData ( $savePath, $string );
	}

}