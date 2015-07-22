<?php
/**
 * 游戏用户信息查询管理类
 * @author php-朱磊
 *
 */
class Util_GameUserManage extends Control {
	
	/**
	 * 富人国游戏接口
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;
	
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
	private $_utilBTOInterface;
	
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
		$this->_utilBTOInterface=$this->_getGlobalData('Util_BTOInterface','object');
		$getArr=array('uName'=>$this->_userAccount,'query'=>'Charge','action'=>'getCharge');
		$this->_utilBTOInterface->setServerUrl($this->_serverId);
		$this->_utilBTOInterface->setGet($getArr);
		$userObj=$this->_utilBTOInterface->callInterface();
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
		$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
		$this->_utilFRGInterface->setServerUrl($this->_serverId);
		$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserQuery','doaction'=>'SearchVUserName'));
		$this->_utilFRGInterface->setPost(array('UserNameStr'=>$this->_userAccount));
		$data=$this->_utilFRGInterface->callInterface();
		Tools::dump($data);
		$this->_userDetail=array(
			'user_id'=>$data[0]['Id'],
			'user_account'=>$this->_userAccount,
			'user_nickname'=>$data[0]['VUserName'],
			'money_total'=>'',
			'money_month'=>'',
			'register_date'=>$data[0]['RegTime'],
			'ip'=>$data[0]['RegIp'],
		);
	}
	
}