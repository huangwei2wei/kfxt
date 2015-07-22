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
	 * 适用于使用php_rpc接口的游戏
	 */
	private $_rpc;

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
	public function setUserResult($userAccount,$serverId,$GameId){
		if (empty($userAccount) || empty($serverId) || empty($GameId))return ;
		$this->_userAccount=$userAccount;
		$this->_serverId=$serverId;
		$this->_gameTypeId=$GameId;
		switch ($GameId){
			case '1' :{//商业大亨
				$this->_getBtoUser();
				break;
			}
			case '2' :{//游戏ID
				$this->_getFrgUser();
				break;
			}
			case '6':{
				$this->_getXianHunUser();
				break;
			}
			case '5' :{
				$this->_getXunXiaUser();
				break;
			}
			case '8':
			case '9':{
				$this->_getBto2User();
				break;
			}
			default :	//扩展到全部游戏
				$this->_getDefaultUser();
				break;
		}
	}
	/**
	 * 从后端的公共接口获得玩家的数据
	 */
	private function _getDefaultUser(){
		$sendUrl = C('SEND_ORDER_URL');
		$getData = array(
			'c'=>'InterfaceWorkOrder',
			'a'=>'GetPlayerDataByAccount',
			'game_id_from_uwan'=>$this->_gameTypeId,
			'player_account'=>urlencode($this->_userAccount),
			'server_id'=>$this->_serverId,
			'_verifycode'=>C('CURRENT_TIME'),
			'_sign'=>md5(C('SEND_KEY').C('CURRENT_TIME')),
		);
		if(!$sendUrl){
			return false;
		}
		if (strpos($sendUrl,'?')){
			$sendUrl .= '&'.http_build_query($getData);
		}else {
			$sendUrl .= '?'.http_build_query($getData);
		}
		import('@.Util.Httpdown');
		$httpDown=new Httpdown();
		$httpDown->OpenUrl($sendUrl);
		if (!$httpDown->IsGetOK()){
			return false;
		}
		$data=$httpDown->GetRaw();
		if($data && ($data = json_decode($data,true) ) && $data['status'] == 1 ){
			$playerInfo = $data['data'];
			$this->_userDetail=array(
				'user_id'=>$playerInfo['user_id'],
				'user_account'=>$this->_userAccount,
				'user_nickname'=>$playerInfo['user_nickname'],
				'money_total'=>$playerInfo['money_total'],
				'money_month'=>$playerInfo['money_month'],
				'register_date'=>$playerInfo['register_date'],
				'ip'=>$playerInfo['ip'],
			);
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
			'ip'=>$this->get_client_ip(),//long2ip($userObj->LoginIp),
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
			'ip'=>$this->get_client_ip(),//$data['RegIp'],
		);
	}
	
	private function _getXianHunUser(){
		import('@.Util.Rpc');
		import('@.Util.ServerSelect');
		$server=new ServerSelect();
		$sendUrl=$server->getServerApiUrl($this->_serverId);
		$sendUrl=$sendUrl['server_url'].'rpc/user';
		$this->_rpc = new Rpc();
		$this->_rpc->setUrl($sendUrl);
		$data = $this->_rpc->getInfo($this->_userAccount);
		if(!$data instanceof PHPRPC_Error && $data){
			$this->_userDetail=array(
				'user_id'=>$data['user_id'],
				'user_account'=>$this->_userAccount,
				'user_nickname'=>$data['user_nickname'],
				'money_total'=>$data['money_total'],
				'money_month'=>$data['money_month'],
				'register_date'=>strtotime($data['register_date']),
				'ip'=>$this->get_client_ip(),
			);
		}else{
			$this->_userDetail=array(
				'user_id'=>0,
				'user_account'=>$this->_userAccount,
				'user_nickname'=>'',
				'money_total'=>0,
				'money_month'=>0,
				'register_date'=>0,
				'ip'=>$this->get_client_ip(),
			);
		}
	}
	
	/**
	 * 获得寻侠游戏任务信息，将信息保存至$_userDetail属性
	 */
	private function _getXunXiaUser(){
		import('@.Util.Rpc');
		import('@.Util.ServerSelect');
		$server=new ServerSelect();
		$sendUrl=$server->getServerApiUrl($this->_serverId);
		$sendUrl=$sendUrl['server_url'].'question/answerQuestion';
		$this->_rpc = new Rpc();
		$this->_rpc->setUrl($sendUrl);
		$data = $this->_rpc->questionPlayerInfo($this->_userAccount);
		if(!$data instanceof PHPRPC_Error && $data){
			$this->_userDetail=array(
				'user_id'=>$data->playerId,
				'user_account'=>$this->_userAccount,
				'user_nickname'=>$data->playerName,
				'money_total'=>$data->moneyTotal,
				'money_month'=>0,
				'register_date'=>$data->registerDate/1000,
				'ip'=>$this->get_client_ip(),
			);
		}else{
			$this->_userDetail=array(
				'user_id'=>0,
				'user_account'=>$this->_userAccount,
				'user_nickname'=>'',
				'money_total'=>0,
				'money_month'=>0,
				'register_date'=>0,
				'ip'=>$this->get_client_ip(),
			);
		}

	}
	
	private function _getBto2User(){
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
			'ip'=>$this->get_client_ip(),//$data['RegIp'],
		);
	}
	
	// 获取客户端IP地址
	private function get_client_ip(){
	   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
	       $ip = getenv("HTTP_CLIENT_IP");
	   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
	       $ip = getenv("HTTP_X_FORWARDED_FOR");
	   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
	       $ip = getenv("REMOTE_ADDR");
	   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
	       $ip = $_SERVER['REMOTE_ADDR'];
	   else
	       $ip = "unknown";
	   return($ip);
	}
	
}