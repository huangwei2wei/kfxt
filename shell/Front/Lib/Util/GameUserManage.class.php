<?php
/**
 * 游戏用户信息查询管理类
 * @author php-朱磊
 *
 */
class GameUserManage {
	
	/**
	 * 富人国游戏接口
	 * @var Util_FRGInterface
	 */
	private $_FRGInterface;
	
	/**
	 * 用户账号
	 * @var string
	 */
	private $_userAccount;
	
	/**
	 * 服务器ID
	 * @var int 
	 */
	private $_serverId;
	
	/**
	 * 游戏id
	 * @var int
	 */
	private $_gameTypeId;
	
	/**
	 * 商业大亨游戏接口
	 * @var Util_BTOInterface
	 */
	private $_BTOInterface;
	
	/**
	 * 用户详细信息
	 * @var array
	 */
	private $_userDetail=null;
	
	public function __construct(){
		
	}
	
	/**
	 * 返回 用户详细信息
	 */
	public function getUserResult(){
		return $this->_userDetail;
	}
	
	/**
	 * 设置用户详细信息
	 * @param string $userAccount 用户账号
	 * @param int $gameTypeId 游戏ID 1:大亨.2:富人国
	 */
	public function setUserResult($userAccount,$serverId,$gameTypeId){
		if (empty($userAccount) || empty($serverId) || empty($gameTypeId))return ;
		$this->_userAccount=$userAccount;
		$this->_serverId=$serverId;
		$this->_gameTypeId=$gameTypeId;
		switch ($gameTypeId){
			case '1' :{//商业大亨
				$this->_getBtoUser();
				break;
			}
			case '2' :{//游戏ID
				$this->_getFrgUser();
				break;
			}
		}
	}
	
	/**
	 * 获取商业大亨人物信息,将信息保存至$_userDetail属性
	 */
	private function _getBtoUser(){
		import('@.Util.BTOInterface');
		$this->_BTOInterface=new BTOInterface();
		$getArr=array('uName'=>$this->_userAccount,'query'=>'Charge','action'=>'getCharge');
		$this->_BTOInterface->setServerUrl($this->_serverId);
		$this->_BTOInterface->setGet($getArr);
		$userObj=$this->_BTOInterface->callInterface();
		if (!$userObj)return ;	//如果解析不了,将出错.
		$userObj=$userObj->o;
		$this->_userDetail=array(
			'user_id'=>$userObj->UserId,
			'user_account'=>$this->_userAccount,
			'user_nickname'=>$userObj->VUserName,
			'money_total'=>$userObj->ChargeTotal,
			'money_month'=>$userObj->Charge30Days,
			'register_date'=>strtotime($userObj->RegTime),
			'ip'=>long2ip($userObj->LoginIp),
		);
		
	}
	
	/**
	 * 获取富人国游戏人物信息,,将信息保存至$_userDetail属性
	 */
	private function _getFrgUser(){
		import('@.Util.FRGInterface');
		$this->_FRGInterface=new FRGInterface();
		$this->_FRGInterface->setServerUrl($this->_serverId);
		$this->_FRGInterface->setGet(array('m'=>'Admin','c'=>'UserData','a'=>'UserQuery','doaction'=>'SearchUserName'));
		$this->_FRGInterface->setPost(array('UserNameStr'=>$this->_userAccount));
		$data=$this->_FRGInterface->callInterface();
		$data=array_shift($data['backparams']);
		$this->_userDetail=array(
			'user_id'=>$data['Id'],
			'user_account'=>$this->_userAccount,
			'user_nickname'=>$data['VUserName'],
			'money_total'=>$data['money_total'],
			'money_month'=>$data['money_month'],
			'register_date'=>$data['RegTime'],
			'ip'=>$data['RegIp'],
		);
	}
	
}