<?php
/**
 * 遗忘密码
 * @author php-兴源
 *
 */
class LostPasswordAction extends BaseAction{
	
	public function index(){
		if(!is_null($this->uwanName)){
			redirect(U("Question/index"));
			return ;
		}
		$game_types = $this->getSysConfig("game_type");
		import('@.Util.ServerSelect');
		$util_server = new ServerSelect();		
		$server_list = array();
		foreach($game_types as $sub_game){
			$a_game_server_list = $util_server->getGameServers($sub_game['Id'],'server_name');
			$a_game_server_list = $this->idForKey($a_game_server_list,'server_name');
			$server_list[$sub_game['Id']] = $a_game_server_list;
		}
		unset($server_list[1][624]);	//去掉S0
		$this->assign("serverList",$server_list);
		$this->assign("game_type",$game_types);
		$this->display();
	}
	
//	public function save(){
//		$game_types = $this->getSysConfig("game_type");
//		if(!array_key_exists($_POST['game_type'],$game_types)){
//			$this->error('游戏选择有误！');
//		}
//		import('@.Util.ServerSelect');
//		$util_server = new ServerSelect();
//		$a_game_server_list = $util_server->getGameServers($_POST['game_type'],'server_name');
//		$a_game_server_list = $this->idForKey($a_game_server_list);
//		if(!array_key_exists($_POST['servers'],$a_game_server_list)){
//			$this->error('服务器选择有误！');
//		}
//		$add_data = array(
//			'game_type_id'=>$_POST['game_type'],	//游戏类型
//			'server_id'=>$_POST['servers'],		//服务器
//			'user_account'=>$_POST['account'],	//账号
//			'user_nickname'=>$_POST['game_name'],	//游戏昵称			
//			'account_area'=>$_POST['account_area'],		//帐号注册地区
//			'start_password'=>$_POST['start_password'],
//			'often_passsword'=>$_POST['often_passsword'],	//曾经Miami
//			'charge_type'=>$_POST['charge_type'],	//充值类型
//			'host'=>$_POST['host'],		//帐号是否身份绑定
//			'host_number'=>$_POST['host_number'],		//帐号绑定的有效证件号码/身份证
//			'host_username'=>$_POST['host_username'],	//身份持有人姓名
//			'email'=>$_POST['email'],
//			'telphone'=>$_POST['telphone'],
//			'status'=>1,
//			'create_time'=>time(),
//			'ip'=>$this->get_client_ip(),
//		);
//		M('LostPassword')->data($add_data)->add();
//		$this->assign("jumpUrl",U("Index/index"));
//		$this->success("提交成功！");
//	}
	
	private function idForKey($arr,$field="Id"){
		if(!is_array($arr))return array();
		$data = array();
		foreach($arr as $val){
			$data[$val['Id']] = $val[$field];
		}
		unset($arr);
		return ($data);
	}
	
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
	
	/**
	 * 返回用户详细资料
	 * @param string $userAccount
	 * @param int $serverId
	 * @param int $gameTypeId
	 */
	private function _getUserResult($userAccount,$serverId,$gameTypeId){
		if (!$gameTypeId || !$serverId)return array(			
			'user_id'=>0,
			'user_account'=>$userAccount,
			'user_nickname'=>'未建立角色',
			'money_total'=>'0',
			'money_month'=>'0',
			'register_date'=>C('CURRENT_TIME'),
			'ip'=>'',);

		$key='GameUserResult_'.$gameTypeId.'_'.$serverId.'_'.$userAccount;
		$userData=S($key);
		if (!$userData){
			import('@.Util.GameUserManage');
			$gameUserManage=new GameUserManage();
			$gameUserManage->setUserResult($userAccount,$serverId,$gameTypeId);
			$userData=$gameUserManage->getUserResult();
			if ($userData)S($key,$userData,60*60);	//缓存1小时
		}
		return $userData;
	}
	
	/**
	 * 提交密码遗失单
	 */
	public function send(){	
		//判断用户是否登录状态
		if(!is_null($this->uwanName)){
			$this->assign("jumpUrl",U("Index/index"));
			$this->success("恭喜！您已成功登录！");
			return ;
		}
		$needField = array(
			'game_type'=>'游戏产品',	
			'servers'=>'服务器',
			'account'=>'帐号',	
			'game_name'=>'游戏昵称',	
			'account_area'=>'帐号注册地区',	
			'often_passsword'=>'曾经使用密码',	
			'charge'=>'是否曾经充值',
			'host'=>'帐号是否身份绑定',	
			'ip'=>$this->get_client_ip(),
		);
		if($_POST['charge'] == 1)$needField['charge_type'] = '是否曾经充值';
		if($_POST['host'] == 1){
			$needField['host_number'] = '帐号绑定的有效证件号码';
			$needField['host_username'] = '身份持有人姓名';
		}
		else{
			$needField['host_number'] = '身份证号码';
			$needField['host_username'] = '真实姓名';
		}
		$checkReturn = $this->checkNullField($_POST,$needField);
		if($checkReturn !== true){
			$this->error($checkReturn.'不能为空！');
		}
		if(!$_POST['email'] && !$_POST['telphone']){
			$this->error('电子邮箱和电话至少填写一个！');
		}
		$sendArr=$this->_getUserResult($_POST['account'],$_POST['servers'],$_POST['game_type']);
		if($sendArr['user_nickname'] == ''){
			$this->error("所选服务器还没有建立角色！");
		}elseif($sendArr['user_nickname'] != $_POST['game_name']){
			$this->error("游戏昵称输入错误，请您重新提交正确的昵称。<br/>您还可以使用邮箱找回密码或来电咨询。");
		}
//		if(!isset($sendArr['user_id']))$sendArr['user_id'] = 0;
//		if(!isset($sendArr['user_account']))$sendArr['user_account']='-无-';
		$sendArr['user_account']='-';	//账号为空时,客服回复时，玩家是收不到的
		if(!isset($sendArr['user_nickname']))$sendArr['user_nickname'] = '-无-';
		if(!isset($sendArr['money_total']))$sendArr['money_total'] = 0;
		if(!isset($sendArr['money_month']))$sendArr['money_month'] = 0;
		if(!isset($sendArr['register_date']))$sendArr['register_date'] = 0;
		if(!isset($sendArr['ip']))$sendArr['ip'] = '0.0.0.0';
		$sendArr['question_type'] = 0;
		$sendArr['game_type'] = $_POST['game_type'];		
		$sendArr['game_server_id'] = $_POST['servers'];
		$sendArr['title'] = '密码遗失单';
		$sendArr['operator_id'] = C("WE_OPERATOR");
		$sendArr['game_id'] = $_POST['game_type'];
		$sendArr['source'] = 1;
		$curTime = C('CURRENT_TIME');
		$sendArr['_sign']=md5(C('SEND_KEY').$curTime);
		$sendArr['_verifycode']=$curTime;

		$changelink = "\n\r";
		$str = '--账号找回信息--';
		$str .= $changelink."账号：".$_POST['account'];
		$str .= $changelink.'游戏昵称：'.$_POST['game_name'];		
		$str .= $changelink.'帐号注册地区：'.$_POST['account_area'];
		$str .= $changelink.'注册初始密码：'.$_POST['start_password'];
		$str .= $changelink.'曾经使用密码：'.$_POST['often_passsword'];
		$str .= $changelink.'是否曾经充值：'.($_POST['charge']==1?'是':'否');
		$str .= $changelink.'充值类型：';		
		switch($_POST['charge_type']){
			case '1':{
				$str .='充值卡';
				break;
			}
			case '2':{
				$str .='网上银行';
				$str .= $changelink.'其中一次充值网银订单：'.$_POST['charge_order'];
				break;
			}
			default:{
				$str .='其他';
			}
		}
		$str .= $changelink.'帐号是否身份绑定：'.($_POST['host']==1?'是':'否');
		$str .= $changelink.'帐号绑定的有效证件号码：'.$_POST['host_number'];
		$str .= $changelink.'身份持有人姓名：'.$_POST['host_username'];
		$str .= $changelink.'电子邮箱：'.$_POST['email'];
		$str .= $changelink.'电话：'.$_POST['telphone'];
		if(!is_null($this->uwanName)){
			$str .= $changelink.$changelink."提问者：{$this->uwanName}";
		}
		else{
			$str .= $changelink.$changelink.'(注：该消息回复，不会有玩家收到！)';
		}
		$sendArr['content'] = $str;
		
		import('@.Util.WebService');
		$webService=new WebService();
		$webService->setUrl(C('SEND_ORDER_URL'));
		$webService->setGet(array('c'=>'InterfaceWorkOrder','a'=>'QuestionSave'));

		$webService->setPost($sendArr);
		$webService->sendData();
		
		$backParams=$webService->getRaw();
		$backParams=json_decode($backParams);
		if ($backParams->status==1){
			$this->assign("jumpUrl",U("Index/index"));
			$this->success("您的问题已受理!");
		}else {
			$this->error('提交失败!');
		}		
	}
	
	/**
	 * 检查需要的字段是否为空
	 * @param unknown_type $data
	 * @param unknown_type $needField
	 */
	private function checkNullField($data,$needField){
		$nullFields = array();
		foreach($data as $key => $val){
			if(trim($val) == '')$nullFields[$key] = $needField[$key];
		}
		$missingField = array_intersect_key($nullFields,$needField);
		if(count($missingField) == 0){
			return true;
		}
		else{
			return array_shift($missingField);
		}
	}
	
	
	
	
	
	

	
}
?>