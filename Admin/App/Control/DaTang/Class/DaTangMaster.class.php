<?php
/**
 * 游戏用户管理 
 * @author PHP-兴源
 *
 */
class Control_DaTangMaster extends DaTang {
	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;

	private $_modelGamePlayerLogTpl;

	//	private $_newServers = array(978,967);

	Const ITEMS_CACHE_NAME = 'da_tang_items_list';

	public function __construct(){
		$_GET['page']=$_GET['page']?$_GET['page']:1;
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['OperationLog_FindPage'] = Tools::url(CONTROL,'OperationLog',array('zp'=>self::PACKAGE,'doaction'=>'findpage'));
		$this->_url['OperationLog_TplSearch'] = Tools::url(CONTROL,'OperationLog',array('zp'=>self::PACKAGE,'doaction'=>'tplsearch'));

		$this->_url['OperationLog_LockIp'] = Tools::url(CONTROL,'LockIp',array('zp'=>self::PACKAGE));
		$this->_url['OperationLog_LockUser'] = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE));
		$this->_url['OperationLog_LockUserDel'] = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'del'));

		$this->_url['OperationLog_ForbiddenChatDel'] = Tools::url(CONTROL,'ForbiddenChat',array('zp'=>self::PACKAGE,'doaction'=>'del'));


		$this->_view->assign('url',$this->_url);
	}




	/*
	 *	玩家数值修改 
	 * */
	public function actionModifyInfo(){

		$this->_checkOperatorAct();
		$this->_createServerList();
		switch ($_REQUEST["doaction"]){
			case "seachuser":
				$this->_seachuser();
				break;
			default:
				break;
		}
		$ajaxurl	=	Tools::url(CONTROL,ACTION,array("zp"=>"DaTang","doaction"=>"seachuser","time"=>CURRENT_TIME,"server_id"=>$_REQUEST["server_id"]));
		$this->_view->assign('ajaxurl',$ajaxurl);
		$this->_view->display();
	}

	private function _seachuser(){
		$attribute	=	array(
							"id"		=>	"玩家ID"	,
							"name"		=>	"玩家名称",
							"ingot"		=>	"元宝",
							"Campid"	=>	"军团ID",
							"coins"		=>	"银两",
							"level"		=>	"等级",
							"account"	=> 	"账号"
							);

							switch($_POST["seachuser"]){
								case "0":
									$post["playerName"]	=	$_POST["username"];
									break;
								case "1";
								$post["playerId"]	=	$_POST["username"];
								break;
							}
							$get = array();
							$dataList = $this->getResult($_REQUEST['server_id'],'user/find',$get,$post);
							$value	=	$dataList["plist"]["0"];
							foreach($value as $key=>$_value){
								if(isset($attribute[$key])){
									$thevalue[$key]=$attribute[$key].":".$_value;
								}
									
							}
							if(empty($value)){
								$returnDate = array('status'=>0,'info'=>'返回为空','data'=>NULL);
							}else{
								$returnDate = array('status'=>1,'info'=>NULL,'data'=>$thevalue);
							}
							$this->_returnAjaxJson($returnDate);
							//print_r($dataList["plist"]["0"]);
	}


	/**
	 * 用户查询
	 */
	public function actionUserQuery(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] ){
			$get = array();
			$post['pageIndex'] = max(1,intval($_GET['page']));
			$post['pageSize'] = PAGE_SIZE;
			$post['playerId'] = intval($_GET['playerId']);
			$post['playerAccount'] = trim($_GET['playerAccount']);
			$post['playerName'] = trim($_GET['playerName']);
			$post['registerTimeStart'] = $_GET['registerTimeStart'];
			$post['registerTimeEnd'] = $_GET['registerTimeEnd'];
			$post['loginTimeStart'] = $_GET['loginTimeStart'];
			$post['loginTimeEnd'] = $_GET['loginTimeEnd'];
			$serverList = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
			$dataList = $this->getResult($_REQUEST['server_id'],'user/find',$get,$post);
			if(is_array($dataList['plist'])){
				$this->_loadCore('Help_Page');//载入分页工具
				foreach($dataList['plist'] as $key =>&$val){
					$val['id'] = Tools::d2s($val['id']);
					$val['URL_Deposit'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'playerId'=>$val['id']));
					$bugParam = array(
						'game_type_id'=>self::GAME_ID,
						'operator_id'=>$serverList[$_REQUEST['server_id']]['operator_id'],
						'game_server_id'=>$_REQUEST['server_id'],
						'game_user_id'=>$val['id'],
						'user_account'=>$val['account'],
						'user_nickname'=>$val['name'],
					);
					$val['URL_Bug'] = Tools::url('Verify','OrderVerify',$bugParam);
				}
				$this->_view->assign('dataList',$dataList['plist']);

				$helpPage=new Help_Page(array('total'=>$dataList['playerCount'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}
			$url['LockUserAdd'] = Tools::url(CONTROL,'LockUser',array('doaction'=>'add','zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
			$url['ForbiddenChatAdd'] = Tools::url(CONTROL,'ForbiddenChat',array('doaction'=>'add','zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
			$url['SendMail']= Tools::url(CONTROL,'SendMail',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('ShortcutUrl',$url);
		}
		$this->_view->assign('selected',$_GET);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 玩家封号
	 */
	public function actionLockUser(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_lockUserAdd();
				return ;
			}
			case 'del':{
				$this->_lockUserDel();
				return;
			}
			case 'time_end':{
				$this->_lockUserTimeEnd();
				return;
			}
			case 'detail':{
				$this->_operateDetail(1);
				return;
			}
			default :{
				$this->_lockUserIndex();
				return ;
			}
		}
	}


	/**
	 * 禁言,多服封号
	 */
	public function actionAlllock(){
		if ($this->_isAjax()){
			$_POST['cause'] = trim($_POST['cause']);
			$_POST['users'] = trim($_POST['users']);
			$post['accounts'] = $_POST['users'];
			if(!strtotime($_POST['EndTime'])){
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'时间错误'));
			}
			if($_POST['locktype']=='1'){
				$forbidtype	=	'封号';
				$inface		=	'account/addforbid';
				$locktype	=	1;
				$post['forbidlogin']	=	$_POST['EndTime'];
			}else{
				$forbidtype	=	'禁言';
				$inface		=	'speak/addforbid';
				$locktype	=	2;
				$post['forbidSpeak']	=	$_POST['EndTime'];
			}
			$dataList = $this->getResult($_REQUEST['server_id'],$inface,array(),$post);
			if(is_array($dataList) && count($dataList)){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
				array('操作','<font style="color:#F00">'.$forbidtype.'</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array($forbidtype.'结束时间',$_POST['EndTime']),
				array('原因',$_POST['cause']),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				foreach($dataList as $sub){
					$userInfo =$sub;
					$userInfo['UserId'] = $sub['id'];
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,$locktype,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
				}
				#------->>>记录游戏后台新操作日志-------#
				$this->_returnAjaxJson(array('status'=>1,'msg'=>$forbidtype.'成功'));
			}elseif(is_array($dataList)){
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'提交用户不存在'));
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'操作失败'));
			}
		}else {
			$this->_checkOperatorAct();	//检测服务器
			$this->_createMultiServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->set_tpl(array('body'=>'DaTang/DaTangMaster/alllock.html'));
			$this->_view->display();
		}

	}


	/**
	 * 封号列表
	 */
	private function _lockUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$post['playerId'] = trim($_GET['playerId']);
		$post['page'] = max(1,intval($_GET['page']));
		if ($_REQUEST['server_id']){
			$get = array();
			$post['pageIndex'] = max(1,intval($_GET['page']));
			$post['pageSize'] = PAGE_SIZE;
			if($post['playerId']){
				$get["playerid"]	=	$post['playerId'];
				$dataList["plist"][0] = $this->getResult($_REQUEST['server_id'],'account/findbidbyid',$get,$post);

			}else{
				$dataList = $this->getResult($_REQUEST['server_id'],'account/listforbid',$get,$post);

			}

			//返回结构
			//"$dataList"	Array [2]
			//	playerCount	(int) 2
			//	plist	Array [2]
			//		0	Array [10]
			//			account	(string:2) N姐	
			//			coins	(int) 1892
			//			createDate	(double) 1.310625855E+012
			//			ingots	(int) 1000
			//			lastLogin	(double) 1.31298007341E+012
			//			id	(int) 56524
			//			level	(int) 8
			//			name	(string:2) N姐	
			//			vip	(int) 0
			//			forbidlogin	(string:19) 2011-08-11 21:39:03
			//		1	Array [10]


			if($dataList['plist'] && is_array($dataList['plist']) ){
				$status = array(0=>'强制解封',1=>'封号中',2=>'自动解封');
				foreach($dataList['plist'] as &$sub){

					$sub['status'] = isset($status[$sub['status']])? $status[$sub['status']]:$sub['status'];
					$sub['URL_Detail'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'game_user_id'=>$sub['id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
					$sub['URL_TimeEnd'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'time_end','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList['plist']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$dataList['playerCount'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}
		}
		$UrlLockUserAdd = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		//$this->_view->assign('alllock',Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'alllock','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('UrlLockUserAdd',$UrlLockUserAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 强制解封
	 */
	private function _lockUserTimeEnd(){
		if ($_REQUEST['server_id']){
			$post['playerIds'] = intval($_GET['id']);
			if(!$post['playerIds']){
				$this->_utilMsg->showMsg('操作失败',-1);
			}
			$post['forbidlogin'] = 'reLogin';
			$dataList = $this->getResult($_REQUEST['server_id'],'account/addforbid',array(),$post);
			if(is_array($dataList) && count($dataList)){
				foreach($dataList as $sub){
					//todo();记录日志
				}
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}elseif(is_array($dataList)){
				$this->_utilMsg->showMsg('提交用户不存在',-1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
	}


	/**
	 * 添加封号
	 */
	private function _lockUserAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if($_POST['fromPlayerList'] && $_POST['playerIds'] && is_array($_POST['playerIds'])){	//从用户列表选择用户封号
				$playerIds = implode(',',$_POST['playerIds']);
				$this->_view->assign('users',$playerIds);
			}else{
				$_POST['cause'] = trim($_POST['cause']);
				$_POST['users'] = trim($_POST['users']);
				$_POST['ReceiveType'] = intval($_POST['ReceiveType']);
				$post['forbidlogin'] = $_POST['EndTime'];
				$post['accounts'] = '';
				$post['playerIds'] = '';
				switch ($_POST[ReceiveType]){
					case "1":
						$post['playerIds'] = $_POST['users'];
						break;
					case "2":
						$post['playerNames'] = $_POST['users'];
						break;
					case "3":
						$post['accounts'] = $_POST['users'];
						break;
				}
				$dataList = $this->getResult($_REQUEST['server_id'],'account/addforbid',array(),$post);
				//dataList格式
				//"$dataList"	Array [2]
				//	0	Array [9]
				//		account	(string:2) N姐	
				//		coins	(int) 1892
				//		createDate	(double) 1.310625855E+012
				//		ingots	(int) 1000
				//		lastLogin	(double) 1.31298007341E+012
				//		id	(int) 56524
				//		level	(int) 8
				//		name	(string:2) N姐	
				//		vip	(int) 0
				//	1	Array [9]

				if(is_array($dataList) && count($dataList)){
					#-------<<<记录游戏后台新操作日志-------#
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
					array('操作','<font style="color:#F00">封号</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('封号结束时间',$_POST['EndTime']),
					array('原因',$_POST['cause']),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					foreach($dataList as $sub){
						$userInfo =$sub;
						$userInfo['UserId'] = $sub['id'];
						$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,1,$_REQUEST['server_id'],$AddLog);
						if(false !== $GameOperateLog){
							$this->_modelGameOperateLog->add($GameOperateLog);
						}
					}
					#------->>>记录游戏后台新操作日志-------#
					$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
				}elseif(is_array($dataList)){
					$this->_utilMsg->showMsg('提交用户不存在',-1);
				}else{
					$this->_utilMsg->showMsg('操作失败',-1);
				}
			}
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangMaster/LockUserAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 删除封号 
	 */
	private function _lockUserDel(){
		$playerId = (array)$_POST['playerId'];
		if($playerId){
			$post['playerIds'] = implode(',',$playerId);
			$post['forbidlogin'] = 'reLogin';
			$dataList = $this->getResult($_REQUEST['server_id'],'account/addforbid',array(),$post);
			//			$dataList = $this->getResult($_REQUEST['server_id'],'account/removeforbid',array(),$post);
			//返回数据				
			//"$dataList"	Array [2]
			//	0	Array [9]
			//		account	(string:6) 001001
			//		coins	(int) 550
			//		createDate	(double) 1.309245171E+012
			//		ingots	(int) 1000
			//		lastLogin	(double) 1.31062280369E+012
			//		id	(int) 1001
			//		level	(int) 1
			//		name	(string:6) 001001
			//		vip	(int) 0
			//	1	Array [9]
			if($dataList && count($dataList)){
				$this->_utilMsg->showMsg('操作成功',1,1,1);
			}elseif(is_array($dataList)){
				$this->_utilMsg->showMsg('提交用户不存在',-1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$this->_utilMsg->showMsg('没有选择',-1);
		}

	}

	/**
	 * 玩家禁言
	 */
	public function actionForbiddenChat(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_forbiddenChatAdd();
				return ;
			}
			case 'del':{
				$this->_forbiddenChatDel();
				return;
			}
			case 'time_end':{
				$this->_forbiddenChatTimeEnd();
				return;
			}
			case 'detail':{
				$this->_operateDetail(2);
				return ;
			}
			default :{
				$this->_forbiddenChatIndex();
				return ;
			}
		}
	}

	/**
	 * 禁言列表 
	 */
	private function _forbiddenChatIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$_GET['playerId'] = trim($_GET['playerId']);
		$_GET['username'] = trim($_GET['username']);
		$_GET['nickname'] = trim($_GET['nickname']);
		$_GET['end_time'] = trim($_GET['end_time']);
		$_GET['page'] = max(1,intval($_GET['page']));
		$_GET['pageSize'] = PAGE_SIZE;
		if ($_REQUEST['server_id'] ){
			$UrlForbiddenChatAdd = Tools::url(CONTROL,'ForbiddenChat',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$UrlForbiddenChatList = Tools::url(CONTROL,'ForbiddenChat',array('zp'=>self::PACKAGE));
			$get = array();
			$post['playerId'] = $_GET['playerId'];
			$post['playerAccount'] =$_GET['username'];
			$post['playerName'] =$_GET['nickname'];
			$post['forbidSpeak'] =$_GET['end_time'];
			$post['pageIndex'] = $_GET['page'];
			$post['pageSize'] = $_GET['pageSize'];
			//print_r($post);
			$this->_view->assign('UrlForbiddenChatList',$UrlForbiddenChatList);
			$dataList = $this->getResult($_REQUEST['server_id'],'speak/forbid',$get,$post);
			if($dataList['plist']){
				$status = array(0=>'强制解禁',1=>'禁言中',2=>'自动解禁');
				foreach($dataList['plist'] as &$sub){
					$sub['status'] = isset($status[$sub['status']])? $status[$sub['status']]:$sub['status'];
					$sub['URL_Detail'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'game_user_id'=>$sub['id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
					$sub['URL_TimeEnd'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'time_end','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList['plist']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$dataList['playerCount'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}
		}


		$this->_view->assign('UrlForbiddenChatAdd',$UrlForbiddenChatAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 禁言强制解禁
	 */

	private function _forbiddenChatTimeEnd(){
		if ($_REQUEST['server_id']){
			$post['playerIds'] = intval($_GET['id']);
			if(!$post['playerIds']){
				$this->_utilMsg->showMsg('操作失败',-1);
			}
			$post['forbidSpeak'] = 'reSpeak';
			$dataList = $this->getResult($_REQUEST['server_id'],'speak/addforbid',array(),$post);
			if(is_array($dataList) && count($dataList)){
				//				foreach($dataList as $sub){
				//					//todo();记录日志
				//				}
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}elseif(is_array($dataList)){
				$this->_utilMsg->showMsg('提交用户不存在',-1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}

	}

	/**
	 * 添加禁言
	 */
	private function _forbiddenChatAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if($_POST['fromPlayerList'] && $_POST['playerIds'] && is_array($_POST['playerIds'])){	//从用户列表选择用户封号
				$playerIds = implode(',',$_POST['playerIds']);
				$this->_view->assign('users',$playerIds);
			}else{
				if(!strtotime($_POST['EndTime'])){
					$this->_utilMsg->showMsg('时间错误',-1);
				}
				$_POST['cause'] = trim($_POST['cause']);
				$_POST['users'] = trim($_POST['users']);
				//			$_POST['ReceiveType'] = intval($_POST['ReceiveType']);


				switch ($_POST[ReceiveType]){
					case "1":
						$post['playerIds'] = $_POST['users'];
						break;
					case "2":
						$post['playerNames'] = $_POST['users'];
						break;
					case "3":
						$post['accounts'] = $_POST['users'];
						break;
				}
				$post['forbidSpeak'] = $_POST['EndTime'];
				$dataList=$this->getResult($_REQUEST['server_id'],'speak/addforbid',array(),$post);
				if($dataList){
					//dataList格式
					//					"$dataList"	Array [3]
					//					0	Array [2]
					//						id	(string:3) 501
					//						playerName	(string:4) 楚喬紅顏	
					//					1	Array [2]
					//						id	(string:3) 555
					//						playerName	(string:5) sdfsd
					//					2	Array [2]
					//						id	(string:3) 590
					//						playerName	(string:4) ouuu
					if(is_array($dataList) && $dataList){

						#-------<<<记录游戏后台新操作日志-------#
						$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
						$AddLog = array(
						array('操作','<font style="color:#F00">禁言</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
						array('禁言结束时间',$_POST['EndTime']),
						array('操作原因',$_POST['cause']),
						);
						$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
						foreach($dataList as $sub){
							$userInfo =$sub;
							$userInfo['UserId'] = $sub['id'];
							$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,2,$_REQUEST['server_id'],$AddLog);
							if(false !== $GameOperateLog){
								$this->_modelGameOperateLog->add($GameOperateLog);
							}
						}
						#------->>>记录游戏后台新操作日志-------#
						$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'ForbiddenChat',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
					}elseif(is_array($dataList)){
						$this->_utilMsg->showMsg('提交用户不存在',-1);
					}
				}
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangMaster/ForbiddenChatAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 解除禁言
	 */
	private function _forbiddenChatDel(){
		$playerId = (array)$_POST['playerId'];
		if($playerId){
			$post['playerIds'] = implode(',',$playerId);
			$post['forbidSpeak'] = 'reSpeak';
			$dataList = $this->getResult($_REQUEST['server_id'],'speak/addforbid',array(),$post);
			//			$dataList=$this->getResult($_REQUEST['server_id'],'speak/removeforbid',array(),$post);
			//	返回数据	
			//	"$dataList"	Array [2]
			//		0	Array [2]
			//			id	(string:4) 1111
			//			playerName	(string:5) 啊啊啊啊啊	
			//		1	Array [2]
			//			id	(string:4) 1112
			//			playerName	(string:2) 陷阱	

			if(is_array($dataList) && count($dataList)){
				//foreach($dataList as $sub){
				//todo();记录日志
				//}
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'ForbiddenChat',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}elseif(is_array($dataList)){
				$this->_utilMsg->showMsg('提交用户不存在',-1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}

		}else{
			$this->_utilMsg->showMsg('没有选择',-1);
		}
	}

	/**
	 *
	 * 操作日志获取
	 * @param unknown_type $operateType
	 */
	private function _operateDetail($operateType=0){
		if ($_REQUEST['server_id']){
			$gameUserId = $_GET['game_user_id'];
			$operateType = intval($operateType);
			$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
			$dataList = $this->_modelGameOperateLog->getDetail($_REQUEST['server_id'],$gameUserId,$operateType);
			$jsonData = array('status'=>1,'info'=>NULL,'data'=>$dataList);
		}else{
			$jsonData = array('status'=>0,'info'=>'server id error','data'=>NULL);
		}
		$this->_returnAjaxJson($jsonData);
	}

	/**
	 * IP封锁管理
	 */
	public function actionLockIp(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$post['forbidIps'] = $_POST['ips'];
			$dataList=$this->getResult($_REQUEST['server_id'],'ipforbid/update',array(),$post);
			//			"$dataList"	Array [2]
			//				status	(int) 1	//0失败
			//				info	(string:0)
			if(is_array($dataList) && $dataList['status']==1){
				//todo();记录日志
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'LockIp',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}elseif($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'ipforbid/list',array(),array());
			$this->_view->assign('dataList',$dataList);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 群发邮件
	 */
	public function actionSendMail(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if($_POST['fromPlayerList'] && $_POST['playerIds'] && is_array($_POST['playerIds'])){	//从用户列表选择用户封号
				$playerIds = implode(',',$_POST['playerIds']);
				$this->_view->assign('users',$playerIds);
			}else{
				$_POST['title'] = trim($_POST['title']);
				if($_POST['title'] == '' || trim($_POST['content']) == ''){
					$this->_utilMsg->showMsg('标题和内容不能为空',-1);
				}
				if(intval($_POST['receiveType'])){
					$post['playerNames'] = trim($_POST['users']);
				}else{
					$post['playerIds'] = trim($_POST['users']);
				}
				$post['title']=$_POST['title'];
				$post['context']=$_POST['content'];
				$dataList=$this->getResult($_REQUEST['server_id'],'user/mail',array(),$post);
				//返回数据		
				//"$dataList"	Array [2]
				//	0	Array [9]
				//		account	(string:2) N姐	
				//		coins	(int) 1892
				//		createDate	(double) 1.310625855E+012
				//		ingots	(int) 1000
				//		lastLogin	(double) 1.31070972607E+012
				//		id	(int) 56524
				//		level	(int) 8
				//		name	(string:2) N姐	
				//		vip	(int) 0
				//	1	Array [9]


				if(is_array($dataList) && count($dataList)){
					//$modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');

					//foreach($dataList as $sub){
					//todo();记录日志
					//}
					$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'SendMail',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
				}elseif(is_array($dataList)){
					$this->_utilMsg->showMsg('提交用户不存在',-1);
				}else{
					$this->_utilMsg->showMsg('操作失败',-1);
				}
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 玩家操作日志
	 */
	public function actionOperationLog(){
		switch($_GET['doaction']){
			case 'findpage':{
				$this->_operationLogFindPage();
				return ;
			}
			case 'tplsearch':{
				$this->_operationLogTplSearch();
				return;
			}
			case 'logtype':{
				$this->_operationLogType(-1);
				$this->_utilMsg->showMsg('更新完成',1);
				return;
			}
			default :{
				$this->_operationLogIndex();
				return ;
			}
		}
	}

	private function _operationLogType($effectiveTime = 86400){
		if($_REQUEST['server_id']){

			$ServerArr = $this->_f('datang_log_type','',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($ServerArr){
				return $ServerArr;
			}
			$LogRoot = array();
			$LogType = array();
			$data=$this->getResult($_REQUEST['server_id'],'user/typemap',array(),array());
			if($data[0] && is_array($data[0])){
				foreach($data[0] as $rootId => $rootName){
					$LogRoot[$rootId] = $rootName;
				}
			}

			if($data[1] && is_array($data[1])){
				foreach($data[1] as $typeId => $typeName){
					$rId = substr(strval($typeId),0,-4);
					$LogType[$rId]['subTypeList'][$typeId] = $typeName;
				}
			}
			if($LogType){
				foreach($LogType as $key => $sub){
					$LogType[$key]['rootTypeName'] = isset($LogRoot[$key])?$LogRoot[$key]:$key;
				}
			}
			ksort($LogType);
			if ($LogType){
				$this->_f('datang_log_type',$LogType);	//缓存数据数据
				return $LogType;
			}else{
				return false;
			}

			//"$data"	Array [2]
			//	0	Array [3]
			//		1	(string:2) 装备	
			//		2	(string:3) 游戏点	
			//		8	(string:2) 战斗	
			//	1	Array [3]
			//		80001	(string:4) 战斗小类	
			//		20001	(string:5) 游戏点小类	
			//		10001	(string:4) 装备小类	



		}else{
			return false;
		}
	}

	/**
	 * ajax返回模板搜索表单
	 */
	private function _operationLogTplSearch(){
		$Rootid = intval($_GET['RootId']);
		$TypeId = intval($_GET['TypeId']);
		if($_GET['TypeId'] <=0){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'模板Id错误!','data'=>NULL));
		}
		$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.self::GAME_ID.'_'.$Rootid);
		if(!isset($LogTpl[$TypeId])){
			$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.self::GAME_ID);
			if(!isset($LogTpl[$TypeId])){
				$this->_returnAjaxJson(array('status'=>0,'info'=>'模板不存在!','data'=>NULL));
			}
		}
		$VarCount = intval($LogTpl[$TypeId]['var_count']);
		$tpl = $LogTpl[$TypeId]['tpl'];
		for($i = 1;$i<=$VarCount;$i++){
			$tpl = str_replace('{x'.$i.'}',' <input name="x'.$i.'" class="text" style="width:50px;" value="'.$_GET['x'.$i].'" /> ',$tpl);
		}
		$ajacReturn = array(
			'status'=>1,
			'info'=>NULL,
			'data'=>$tpl,
		);
		$this->_returnAjaxJson($ajacReturn);
	}

	/**
	 * ajax返回指定条件的日志id所在页数
	 */
	private function _operationLogFindPage(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$_GET['rootid'] = intval($_GET['rootid']);
			$_GET['typeid'] = intval($_GET['typeid']);
			$_GET['playerId'] = intval($_GET['playerId']);
			$_GET['name'] = trim(strval($_GET['name']));
			//			$_GET['account'] = trim(strval($_GET['account']));
			$_GET['LogId'] = intval($_GET['LogId']);

			if($_GET['LogId'] <=0){
				$this->_returnAjaxJson(array('status'=>0,'info'=>'选择Id错误!','data'=>NULL));
			}

			$table='user_log';
			$this->_loadCore('Help_SqlSearch');
			$helpSqlSearch = new Help_SqlSearch();
			$helpSqlSearch->set_tableName($table);
			$helpSqlSearch->set_conditions("id >='{$_GET['LogId']}'");
			//连贯操作锁定分类是否作为条件
			if(intval($_GET['sequenceLock'])){
				if($_GET['rootid']){
					$helpSqlSearch->set_conditions("rootid={$_GET['rootid']}");
				}
				if($_GET['typeid']){
					$helpSqlSearch->set_conditions("typeid={$_GET['typeid']}");
				}
			}
			if($_GET['playerId']){
				$helpSqlSearch->set_conditions("playerId={$_GET['playerId']}");
			}
			if($_GET['name']!=''){
				$helpSqlSearch->set_conditions("name='{$_GET['name']}'");
			}
			//			if($_GET['account']!=''){
			//				$helpSqlSearch->set_conditions("account='{$_GET['account']}'");
			//			}

			$conditions=$helpSqlSearch->get_conditions();
			$totle = $this->CountXianHun($table,$conditions);
			if($totle === false){
				$this->_returnAjaxJson(array('status'=>0,'info'=>'数据库查询错误!','data'=>NULL));
			}
			$page = max(1,ceil($totle/PAGE_SIZE));

			$ajacReturn = array(
				'status'=>1,
				'info'=>NULL,
				'data'=>$page,
			);
			$this->_returnAjaxJson($ajacReturn);
		}else{
			$this->_returnAjaxJson(array('status'=>0,'info'=>'请选择服务器!','data'=>NULL));
		}
	}

	private function _operationLogIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();

		$RootSlt=array();
		$TypeSlt=array();
		$OperLogType = $this->_operationLogType();
		if($OperLogType){
			foreach($OperLogType as $key => $root){
				$RootSlt[$key] = $root['rootTypeName'];
				if($root['subTypeList']){
					foreach($root['subTypeList'] as $k => $type){
						$TypeSlt[$key][$k] =$type;
					}
				}
			}
		}
		$this->_view->assign('RootSlt',$RootSlt);
		$this->_view->assign('TypeSlt',json_encode($TypeSlt));

		if ($_REQUEST['server_id'] && $_REQUEST['submit']){
			$post['parentType'] = intval($_GET['rootid']);
			$post['sonType'] =  intval($_GET['typeid']);

			$post['startTime'] = strval($_GET['StartTime']);
			$post['endTime'] = strval($_GET['EndTime']);
			$post['playerId'] = intval($_GET['playerId']);
			$post['playerName'] = trim(strval($_GET['name']));
			$post['account'] = trim(strval($_GET['account']));
			$post['keywords'] = strval($_GET['keywords']);

			$post['pageIndex'] = intval(max(1,$_GET['page']));
			$post['pageSize'] = PAGE_SIZE;


			//$post['KeyWordSearchType'] = intval($_GET['KeyWordSearchType']);

			//			$LogRoot = $this->_getGlobalData( 'game_player_log_root_'.self::GAME_ID );
			//			if(!$LogRoot){
			//				$LogRoot = array();
			//			}
			//			$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.self::GAME_ID );
			//			if(!$LogTpl){
			//				$LogTpl = array();
			//			}
			//			foreach($LogRoot as $sub){
			//				$RootSlt[$sub['rootid']] = $sub['rootname'];
			//			}
			//			foreach($LogTpl as $sub){
			//				$TypeSlt[$sub['rootid']][$sub['typeid']] = $sub['typename'];
			//			}
			//			$this->_operationLogType();
			//			$this->_view->assign('RootSlt',$RootSlt);
			//			$this->_view->assign('TypeSlt',json_encode($TypeSlt));




			$dataList=$this->getResult($_REQUEST['server_id'],'user/operate',array(),$post);
			//"$dataList" = Array [2]
			//	playerCount = (int) 331
			//	plist = Array [20]
			//		0 = Array [16]
			//			id = (int) 12169
			//			playerId = (int) 728
			//			playerName = (string:4) 满脸胡须	
			//			sonType = (int) 50003
			//			parentType = (int) 5
			//			copper = (int) 526300
			//			gold = (int) 439361
			//			ip = (string:14) 192.168.14.112
			//			level = (int) 44
			//			power = (int) 100
			//			yueli = (int) 3567
			//			leginName = (string:8) dsfsdfsd
			//			leginLevel = (int) 44
			//			playerAccount = (string:4) 满脸胡须	
			//			parms = (string:65) 科技捐献 捐献科技【科技名称=议事厅】（科技ID=301），捐献铜币44，元宝5，当前捐献值上升为0，今日铜币捐献总额290557	
			//			actiontime = (string:19) 2011-08-10 17:10:42
			//		1 = Array [16]
			//		2 = Array [16]
			//		3 = Array [16]
			//		4 = Array [16]
			//		5 = Array [16]
			//		6 = Array [16]
			//		7 = Array [16]
			//		8 = Array [16]
			//		9 = Array [16]
			//		10 = Array [16]
			//		11 = Array [16]
			//		12 = Array [16]
			//		13 = Array [16]
			//		14 = Array [16]
			//		15 = Array [16]
			//		16 = Array [16]
			//		17 = Array [16]
			//		18 = Array [16]
			//		19 = Array [16]
			if(is_array($dataList['plist'])){
				foreach($dataList['plist'] as $key => &$val){
					$val['playerId'] 	= 	Tools::d2s($val['playerId']);
					$val['sonType']		=	$TypeSlt[$val['parentType']][$val['sonType']];
					$val['parentType']	=	$RootSlt[$val['parentType']];

					//echo $val['parentType']."<br>";
				}
				$this->_view->assign('dataList',$dataList['plist']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($dataList['playerCount']),'perpage'=>PAGE_SIZE));

				$this->_view->assign('pageBox',$helpPage->show());
			}
			$this->_view->assign('selected',$_GET);
		}
		$URL_LogType = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'logtype'));
		$this->_view->assign('URL_LogType',$URL_LogType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 从服务器更新道具
	 */
	public function actionItemsUpdate(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->list();
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$dataList = Model::getTtwoArrConvertOneArr($dataList,'id','primaryName');
				if($this->_addCache ( $dataList, CACHE_DIR . '/'.self::ITEMS_CACHE_NAME.'.cache.php' )){
					$this->_utilMsg->showMsg('操作成功',1,1,1);
				}
			}
			$this->_utilMsg->showMsg('操作失败',-1);	//如果到达这个阶段，就报错
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 邮件查询
	 */
	public function actionMailQuery(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$_GET['type'] = intval($_GET['type']);
			$_GET['readed'] = intval($_GET['readed']);
			$_GET['received'] = intval($_GET['received']);
			$_GET['returned'] = intval($_GET['returned']);
			$_GET['deleted'] = intval($_GET['deleted']);
			$_GET['receiverId'] = trim($_GET['receiverId']);
			$_GET['receiverName'] = trim($_GET['receiverName']);

			$sqlexp['main'] = 'select * from player_mail  where 1 and type<>4 ';
			$sqlexp['conditions'] = '';

			if($_GET['type']){
				$sqlexp['conditions'] .= ' and type='.$_GET['type'];
			}
			if($_GET['readed'] == 1){
				$sqlexp['conditions'] .= ' and readed=1';
			}elseif($_GET['readed'] == 2){
				$sqlexp['conditions'] .= ' and readed=0';
			}
			if($_GET['received'] == 1){
				$sqlexp['conditions'] .= ' and received=1';
			}elseif($_GET['received'] == 2){
				$sqlexp['conditions'] .= ' and received=0';
			}
			if($_GET['returned'] == 1){
				$sqlexp['conditions'] .= ' and returned=1';
			}elseif($_GET['returned'] == 2){
				$sqlexp['conditions'] .= ' and returned=0';
			}
			if($_GET['deleted'] == 1){
				$sqlexp['conditions'] .= ' and deleted=1';
			}elseif($_GET['deleted'] == 2){
				$sqlexp['conditions'] .= ' and deleted=0';
			}
			if($_GET['receiverId'] != ''){
				$sqlexp['conditions'] .= ' and receiverId= '.intval($_GET['receiverId']);
			}
			if($_GET['receiverName'] != ''){
				$sqlexp['conditions'] .= " and receiverName= '{$_GET['receiverName']}' ";
			}

			$sqlexp['limit'] = ' limit '.PAGE_SIZE*($_GET['page']-1).','.PAGE_SIZE;

			$sqlexp['order'] = ' order by id desc';

			$sql = $this->_makeSql($sqlexp);
			$dataList = $this->SelectXianHun($sql);

			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('selected',$_GET);

			$TotalSql = 'select count(1) from player_mail  where 1 and type<>4 '.$sqlexp['conditions'];

			$total = $this->CountXianHunBySql($TotalSql);
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());

			$this->_view->assign('selected',$_GET);
			$this->_view->assign('type',array(0=>'全部',1=>'玩家邮件',2=>'系统邮件'));
			$IsOrNot = array(0=>'全部',1=>'是',2=>'否');
			$this->_view->assign('readed',$IsOrNot);
			$this->_view->assign('received',$IsOrNot);
			$this->_view->assign('returned',$IsOrNot);
			$this->_view->assign('deleted',$IsOrNot);

			//echo $TotalSql;

		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏登录
	 */
	public function actionGameLogin(){
		$this->_checkOperatorAct();
		$serverList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
		if ($this->_isPost()){
			$_uname = trim($_POST['user_name']);
			if(empty($_uname)){
				echo '账号不能为空';
			}else{
				$post['playerId'] = '';
				$post['playerName']  = '';
				$post['account']  = $_uname;
				if(isset($serverList[$_POST['server_id']]['ordinal'])){
					$post['serverId'] = 'S'.intval($serverList[$_POST['server_id']]['ordinal']);
				}else{
					$serverName = strtoupper($serverList[$_POST['server_id']]['server_name']);

					$post['serverId'] = 'S'.intval(trim($serverName,'S'));
				}
				//[operator_id] => 31
				//印尼版
				if($serverList[$_POST['server_id']]["operator_id"]==113){
					$sendurl = 'game/GMlogin';
				}else{
					$sendurl = 'game/login';
				}
				$dataList = $this->getResult($_REQUEST['server_id'],'game/login',array(),$post);
				$serverUrl = $dataList['gameUrl'];
				$debug = intval($_GET['debug']);	//用于调试
				if($debug == 1){
					echo "返回的数据结构:<br>";
					echo '<pre>';
					print_r($dataList);
					echo '<pre>';
					echo "<br>登录地址:<a href='{$serverUrl}'>{$serverUrl}</a>";	
				}else{
					header('Location: '.$serverUrl);
				}
			}
			exit();
		}else {
			if ($_GET['operator_id']){
				foreach ($serverList as $key=>&$value){
					if ($value['operator_id']!=$_GET['operator_id'])unset($serverList[$key]);
				}
				$this->_view->assign('dataList',$serverList);
			}
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	public function actionApplyItemCard2(){
		$this->_view->assign('allowRegive',true);
		//道具卡用途说明

		$this->ItemCardUsage = array(
		2=>'补偿卡',
		3=>'内部测试卡',
		4=>'客服扣除卡',
		5=>'活动礼品卡',
		1=>'正常充值卡',
		);
		$this->_view->assign('usage',$this->ItemCardUsage);
		$this->_applyItemCard2();
	}

	private $ItemCardUsage;

	/**
	 * 礼包卡管理
	 * Enter description here ...
	 */
	public function actionItemCard(){
		//道具卡用途说明
		$this->ItemCardUsage = array(
		2=>'补偿卡',
		3=>'内部测试卡',
		4=>'客服扣除卡',
		5=>'活动礼品卡',
		1=>'正常充值卡',
		);
		switch ($_GET['doaction']){
			case 'add':
				//目前屏蔽直接添加
				//				$this->_utilMsg->showMsg('目前屏蔽直接添加',-1);
				//				return;
				$this->_addItemCard();
				return;
			case 'apply':{
				//客服申请单人礼包
				$_POST['regive'] = 0;
				$this->_applyItemCard();
				return;
			}
			case'del':{
				$this->_utilMsg->showMsg('被屏蔽',-1);
				$this->_deletecard();
				return;
			}
			case 'reCache':
				//$this->_getAllServerIds(-1);
				$this->_itemsReCache(-1);
				$this->_utilMsg->showMsg('操作成功',1);
				return;
				//			case 'serialnumber':{
				//				$this->_getCardSerialNumber();
				//				break;
				//			}
			case 'applyinfo':
				$this->_giftCardApplyInfo();
				return;
				//			case 'invalidate':
				//				$this->_itemcardInvalidate();
				//				return ;
			default:
				$this->_ItemCardList();
				return;
		}
	}

	/**
	 * 查看审核情况
	 */
	private function _giftCardApplyInfo(){
		$mark = trim($_GET['mark']);
		if($_REQUEST['server_id']){
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$dataList = $_modelApply->getByMark($mark,self::GAME_ID,$_REQUEST['server_id'],1);
		}
		if($dataList){
			$users=$this->_getGlobalData('user');
			$dataList['apply_user_id'] = $users[$dataList['apply_user_id']]['full_name'];
			$dataList['audit_user_id'] = $users[$dataList['audit_user_id']]['full_name'];
			$dataList['create_time'] = date('Y-m-d H:i:s',$dataList['create_time']);
			$dataList['send_time'] = date('Y-m-d H:i:s',$dataList['send_time']);
		}else{
			$noRecode = '<font color="#999999">无记录</font>';		
			$dataList['apply_user_id'] = $noRecode;
			$dataList['audit_user_id'] = $noRecode;
			$dataList['create_time'] = $noRecode;
			$dataList['send_time'] = $noRecode;
		}
		$return = array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$dataList);
		$this->_returnAjaxJson($return);
	}

	/**
	 * 礼品卡申请
	 * Enter description here ...
	 */
	private function _applyItemCard(){
		//临时解决只有3个服务器更新的问题
		//		if(!in_array($_REQUEST['server_id'],array(978))){
		//			$this->_utilMsg->showMsg('服务器限制');
		//		}
		$this->_checkOperatorAct();
		$this->_createServerList();
		$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameser_list',$gameser_list);
		$this->_view->assign('usage',$this->ItemCardUsage);
		if($_REQUEST['server_id']){
			if($this->_isPost()){
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($_POST['pointVal'][0]>20000){
						$this->_utilMsg->showMsg('不能过20000');
					}
				}
				$sendData = array();
				$_POST['usage'] = intval($_POST['usage']);
				if(!array_key_exists($_POST['usage'],$this->ItemCardUsage)){
					$this->_utilMsg->showMsg('使用类型错误');
				}
				$sendData['usage'] = $_POST['usage'];
				$userClass=$this->_utilRbac->getUserClass();
				$sendData['applyName'] = $userClass['_nickName'].','.$userClass['_id'];	//申请人姓名				
				$sendData['iteminfo'] = '0,0,0';	//‘6002:1,6003:2’，或者 ‘12，23，23’		
				$sendData['validTime']	=	$_POST["validTime"];
				$_POST['point'] = intval($_POST['point']);
				switch($_POST['point']){
					case 0:	//送装备			
						$tmp = array();
						if($_POST['goods'] && is_array($_POST['goods'])){
							foreach($_POST['goods'] as $key =>$val){
								$k = intval($key);
								$tmp[$k] = $k.':'.$val;
							}
						}
						if($tmp){
							$sendData['iteminfo']=implode(',',$tmp);
						}else{//如果没有道具的话，改为送点
							$_POST['point'] = 1;
						}
						unset($tmp);
						break;
					case 1:	//送点
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						break;
					case 4:
						$sendData['iteminfo'] = intval($_POST['closedTestValue']);
						break;
					case 2:	//通送(元宝)
					case 3:	//通送(礼券)
					default:
						$_POST['point'] = 3;	//屏蔽送元宝
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						if($_POST['goods'] && is_array($_POST['goods'])){
							foreach($_POST['goods'] as $key =>$val){
								$k = intval($key);
								$tmp[$k] = $k.':'.intval($val);
							}
						}
						if($tmp){
							$sendData['iteminfo'].='|'.implode(',',$tmp);
						}else{//如果没有道具的话，绑定死一个道具id（由于接口不兼容point=3的只发点）
							$sendData['iteminfo'].='|6:0';
						}
						unset($tmp);
				}
				$sendData['name'] 		= trim($_POST['name']);
				$sendData['detail'] 	= trim($_POST['detail']);
				$sendData['point'] 		= intval($_POST['point']);
				$sendData['regive'] 	= intval($_POST['regive']);
				$sendData['isSendMail'] = intval($_POST['isSendMail']);	//是否发送邮件
				//如果是绑定单人卡，必须含有玩家
				if($sendData['regive'] == 0||$sendData['regive'] == 1){
					$_POST['playerId'] = trim($_POST['playerId']);
					if($_POST['playerId']){
						$sendData['bindid'] = $_POST['playerId'];
					}else{
						$this->_utilMsg->showMsg('你选择了绑定单人卡,但玩家Id为空');
					}
					$regive = '单人领取一次';
					$sendData['count'] = 1;

					$sendData['server']	=	$gameser_list[$_REQUEST['server_id']]['ordinal'];
				}elseif($sendData['regive'] == -1){	//批量生成，不绑定，单人卡
					$sendData['bindid'] = 'unbind';
					$regive = '批量生成';
					$sendData['count'] = max(1,intval($_POST['count']));
					$sendData['isSendMail'] = 0;
					$sendData['regive'] = 0;	//标记回单人卡
					//					print_r($_POST['server']);
					//server_name
					if($_POST['server']===''){
						$sendData['server']	=	'全服生效';
					}else{
						//						print_r($gameser_list[$_POST['server']]["server_name"]);
						$server_name	=		trim($gameser_list[$_POST['server']]["server_name"]);
						$server_name_m	=	substr(trim($gameser_list[$_POST['server']]["server_name"]),0,1);
						if(strtolower($server_name_m)=="s"){
							$sarr	=	explode(" ", $server_name);
							$sendData['server']	=	substr($sarr["0"],1);
						}else{
							$sendData['server']	=	0;
						}
						//						substr ("abcdef", 1, 3);

					}
				}elseif($sendData['regive'] == 3){
					$sendData['bindid'] = 'unbind';
					$regive = '批量生成';
					$sendData['count'] = max(1,intval($_POST['count']));
					$sendData['isSendMail'] = intval($_POST['isSendMail']);
					$sendData['regive'] = 3;	//标记回单人卡
					if($_POST['server']===''){
						$sendData['server']	=	'全服生效';
					}else{
						$sendData["server"]	=	$gameser_list[$_POST['server']]["ordinal"];
					}
				}else{
					$sendData['bindid'] = '';
					$regive = '可多人重复领取';
					$sendData['count'] = 1;
					$sendData['server']	=	$gameser_list[$_REQUEST['server_id']]['ordinal'];
				}


				$_Money = array('元宝','铜币','阅历');
				if($_POST['point'] == 3){
					$_Money = array('礼券','铜币','阅历');
				}
				$content	=	'';
				if(is_array($_POST["pointVal"])){
					foreach($_POST["pointVal"] as $key=>$_mom){
						$content.=$_Money[$key].'(<font color="#FF0000">'.$_mom.'</font>)、';
					}
				}
				if($_POST["goods"]){
					foreach($_POST["goods"] as $key=>$_goods){
						$content .= substr(strstr($key,'_'),1).'(<font color="#FF0000">'.intval($_goods).'</font>)、';
					}
				}

				$apply_info ='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<申请原因##
				$apply_info.="<div>申请原因：<br>{$_POST['cause']}</div>";
				##>>申请原因##
				$apply_info.='</div>';

				$apply_info.='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<内容##
				$apply_info.= "<div>卡片名称：{$_POST['name']}</div>";
				$apply_info.= "<div>卡片描述：{$_POST['detail']}</div>";
				$apply_info.= "<div>领取方式：{$regive}</div>";
				$apply_info.= "<div>生成数量：{$_POST['count']}</div>";
				if($sendData['isSendMail']){

					//发送邮件的内容
					$sendData['title']		=	trim($_POST['title']);
					$sendData['content']	=	$_POST['content'];
					$sendData['playerId']	=	trim($_POST['playerId']);
					if($_POST['point'] != 4){
						$sendData['content'] .='<br>'.$content;
					}

					$apply_info .= "<div>邮件通知：是</div>";
					$apply_info .= "<div>收件者ID：{$_POST['playerId']}</div>";
					$apply_info .= "<div>邮件标题：{$_POST['title']}</div>";
					$apply_info .= "<div>邮件内容：{$_POST['content']}</div>";
				}else{
					$apply_info .= "<div>邮件通知：否</div>";
				}
				$apply_info.= "<div>卡内物品：{$content}</div>";
				##>>内容##
				$apply_info.='</div>';

				//"$dataList" = Array [1]
				//	cardNum = (string:36) 7d40e599-c64a-4b7a-954d-841eed079b11
				$serverId = intval($_REQUEST['server_id']);
				//				$dataList = $this->getResult($_REQUEST['server_id'],'game/makecard',array(),$sendData);


				$applyData = array(
					'type'=>9,
					'server_id'=>$serverId,
					'operator_id'=>$gameser_list[$serverId]['operator_id'],
					'game_type'=>$gameser_list[$serverId]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'game/makecardEx',
						'post_data'=>$sendData,
						'get_data'=>array(),
						'call'=>array(
							'cal_local_object'=>'Game_'.self::GAME_ID,
							'cal_local_method'=>'AddItemCard',
				)
				),
					'receiver_object'=>array($serverId=>''),
					'player_type'=>empty($_POST['playerId'])?0:1,
					'player_info'=>$_POST['playerId'],
				);

				//				print_r($applyData);
				//				die();
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->_utilMsg->showMsg($showMsg,1,1,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
				}
			}else{
				$Goods = $this->_itemsReCache();
				//				print_r($Goods);
				$this->_view->assign('Goods',$Goods);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/DaTangMaster/ApplyItemCard.html'));
		$this->_view->display();
	}
	/**
	 * 礼品卡申请2
	 * Enter description here ...
	 */
	private function _applyItemCard2(){
		$this->_checkOperatorAct();
		$this->_createMultiServerList();
		$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameser_list',$gameser_list);
		$this->_view->assign('usage',$this->ItemCardUsage);
		if($_REQUEST['server_id']){
			if($this->_isAjax()){
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($_POST['pointVal'][0]>20000){
						echo json_encode(array('status'=>0,'info'=>'不能过20000'));
						exit;
					}
				}
				$sendData = array();
				$_POST['usage'] = intval($_POST['usage']);
				if(!array_key_exists($_POST['usage'],$this->ItemCardUsage)){
					echo json_encode(array('status'=>0,'info'=>'使用类型错误'));
					exit;
				}
				$sendData['usage'] = $_POST['usage'];
				$userClass=$this->_utilRbac->getUserClass();
				$sendData['applyName'] = $userClass['_nickName'].','.$userClass['_id'];	//申请人姓名
				$sendData['iteminfo'] = '0,0,0';	//‘6002:1,6003:2’，或者 ‘12，23，23’
				$sendData['validTime']	=	$_POST["validTime"];
				$_POST['point'] = intval($_POST['point']);
				switch($_POST['point']){
					case 0:	//送装备
						$tmp = array();
						if($_POST['goods'] && is_array($_POST['goods'])){
							foreach($_POST['goods'] as $key =>$val){
								$k = intval($key);
								$tmp[$k] = $k.':'.$val;
							}
						}
						if($tmp){
							$sendData['iteminfo']=implode(',',$tmp);
						}else{//如果没有道具的话，改为送点
							$_POST['point'] = 1;
						}
						unset($tmp);
						break;
					case 1:	//送点
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						break;
					case 4:
						$sendData['iteminfo'] = intval($_POST['closedTestValue']);
						break;
					case 2:	//通送(元宝)
					case 3:	//通送(礼券)
					default:
						$_POST['point'] = 3;	//屏蔽送元宝
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						if($_POST['goods'] && is_array($_POST['goods'])){
							foreach($_POST['goods'] as $key =>$val){
								$k = intval($key);
								$tmp[$k] = $k.':'.intval($val);
							}
						}
						if($tmp){
							$sendData['iteminfo'].='|'.implode(',',$tmp);
						}else{//如果没有道具的话，绑定死一个道具id（由于接口不兼容point=3的只发点）
							$sendData['iteminfo'].='|6:0';
						}
						unset($tmp);
				}
				$sendData['name'] 		= trim($_POST['name']);
				$sendData['detail'] 	= trim($_POST['detail']);
				$sendData['point'] 		= intval($_POST['point']);
				$sendData['regive'] 	= intval($_POST['regive']);
				$sendData['isSendMail'] = intval($_POST['isSendMail']);	//是否发送邮件
				//如果是绑定单人卡，必须含有玩家
				if($sendData['regive'] == 0||$sendData['regive'] == 1){
					$_POST['playerId'] = trim($_POST['playerId']);
					if($_POST['playerId']){
						$sendData['bindid'] = $_POST['playerId'];
					}else{
						echo json_encode(array('status'=>0,'info'=>'你选择了绑定单人卡,但玩家Id为空'));
						exit;
					}
					$regive = '单人领取一次';
					$sendData['count'] = 1;

					$sendData['server']	=	$gameser_list[$_REQUEST['server_id']]['ordinal'];
				}elseif($sendData['regive'] == -1){	//批量生成，不绑定，单人卡
					$sendData['bindid'] = 'unbind';
					$regive = '批量生成';
					$sendData['count'] = max(1,intval($_POST['count']));
					$sendData['isSendMail'] = 0;
					$sendData['regive'] = 0;	//标记回单人卡
					//					print_r($_POST['server']);
					//server_name
					if($_POST['server']===''){
						$sendData['server']	= '全服生效';
					}else{
						//						print_r($gameser_list[$_POST['server']]["server_name"]);
						$server_name	=		trim($gameser_list[$_POST['server']]["server_name"]);
						$server_name_m	=	substr(trim($gameser_list[$_POST['server']]["server_name"]),0,1);
						if(strtolower($server_name_m)=="s"){
							$sarr	=	explode(" ", $server_name);
							$sendData['server']	=	substr($sarr["0"],1);
						}else{
							$sendData['server']	=	0;
						}
						//						substr ("abcdef", 1, 3);

					}
				}elseif($sendData['regive'] == 3){
					$sendData['bindid'] = 'unbind';
					$regive = '批量生成';
					$sendData['count'] = max(1,intval($_POST['count']));
					$sendData['isSendMail'] = intval($_POST['isSendMail']);
					$sendData['regive'] = 3;	//标记回单人卡
					if($_POST['server']===''){
						$sendData['server']	=	'全服生效';
					}else{
						$sendData["server"]	=	$gameser_list[$_POST['server']]["ordinal"];
					}
				}else{
					$sendData['bindid'] = '';
					$regive = '可多人重复领取';
					$sendData['count'] = 1;
					$sendData['server']	=	$gameser_list[$_REQUEST['server_id']]['ordinal'];
				}
				$_Money = array('元宝','铜币','阅历');
				if($_POST['point'] == 3){
					$_Money = array('礼券','铜币','阅历');
				}
				$content	=	'';
				if(is_array($_POST["pointVal"])){
					foreach($_POST["pointVal"] as $key=>$_mom){
						$content.=$_Money[$key].'(<font color="#FF0000">'.$_mom.'</font>)、';
					}
				}
				if($_POST["goods"]){
					foreach($_POST["goods"] as $key=>$_goods){
						$content .= substr(strstr($key,'_'),1).'(<font color="#FF0000">'.intval($_goods).'</font>)、';
					}
				}

				$apply_info ='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<申请原因##
				$apply_info.="<div>申请原因：<br>{$_POST['cause']}</div>";
				##>>申请原因##
				$apply_info.='</div>';

				$apply_info.='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<内容##
				$apply_info.= "<div>卡片名称：{$_POST['name']}</div>";
				$apply_info.= "<div>卡片描述：{$_POST['detail']}</div>";
				$apply_info.= "<div>领取方式：{$regive}</div>";
				$apply_info.= "<div>生成数量：{$_POST['count']}</div>";
				if($sendData['isSendMail']){

					//发送邮件的内容
					$sendData['title']		=	trim($_POST['title']);
					$sendData['content']	=	$_POST['content'];
					$sendData['playerId']	=	trim($_POST['playerId']);
					if($_POST['point'] != 4){
						$sendData['content'] .='<br>'.$content;
					}

					$apply_info .= "<div>邮件通知：是</div>";
					$apply_info .= "<div>收件者ID：{$_POST['playerId']}</div>";
					$apply_info .= "<div>邮件标题：{$_POST['title']}</div>";
					$apply_info .= "<div>邮件内容：{$_POST['content']}</div>";
				}else{
					$apply_info .= "<div>邮件通知：否</div>";
				}
				$apply_info.= "<div>卡内物品：{$content}</div>";
				##>>内容##
				$apply_info.='</div>';
				$serverId = intval($_REQUEST['server_id']);
				$applyData = array(
						'type'=>9,
						'server_id'=>$serverId,
						'operator_id'=>$gameser_list[$serverId]['operator_id'],
						'game_type'=>$gameser_list[$serverId]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,
						'send_type'=>2,	//2	html
						'send_data'=>array(
								'url_append'=>'game/makecardEx',
								'post_data'=>$sendData,
								'get_data'=>array(),
								'call'=>array(
										'cal_local_object'=>'Game_'.self::GAME_ID,
										'cal_local_method'=>'AddItemCard',
				)
				),
						'receiver_object'=>array($serverId=>''),
						'player_type'=>empty($_POST['playerId'])?0:1,
						'player_info'=>$_POST['playerId'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					echo json_encode(array('status'=>1,'info'=>'申请成功'));
				}else{
					echo json_encode(array('status'=>0,'info'=>'申请失败'));
				}
				exit;
			}else{
				$Goods = $this->_itemsReCache();
				$this->_view->assign('Goods',$Goods);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/DaTangMaster/ApplyItemCard2.html'));
		$this->_view->display();
	}
	//	private function _itemcardInvalidate(){
	//		$returnData = array(
	//			'status'=>0,
	//			'info'=>'server id error',
	//			'data'=>NULL,
	//		);
	//		if ($_REQUEST['server_id']){
	//			$post = array(
	//				'id'=>$_GET['id'],
	//			);
	//			$dataList = $this->getResult($_REQUEST['server_id'],'game/validcard ',array(),$post);
	//			if($dataList){	//拿状态判断
	//				$returnData = array(
	//					'status'=>1,
	//					'info'=>'',
	//					'data'=>NULL,
	//				);
	//			}
	//		}
	//		$this->_returnAjaxJson($returnData);
	//	}
	/**
	 * 注销
	 * Enter description here ...
	 */
	public function actionInvalidate(){
		$returnData = array(
			'status'=>0,
			'info'=>'server id error',
			'data'=>NULL,
		);
		if ($_REQUEST['server_id']){
			$post = array(
				'id'=>$_GET['id'],
			);
			$dataList = $this->getResult($_REQUEST['server_id'],'game/validcard ',array(),$post);
			if($dataList){	//拿状态判断
				$returnData = array(
					'status'=>1,
					'info'=>'',
					'data'=>NULL,
				);
			}
		}
		$this->_returnAjaxJson($returnData);
	}

	/**
	 * 删除礼包卡
	 * Enter description here ...
	 */
	private function _deletecard(){
		if ($_REQUEST['server_id']){

			$post = array();
			if($_POST['ids']){
				$post['ids'] = implode(',',$_POST['ids']);
			}elseif($_GET['uuid']){
				$post['uuid'] = trim($_GET['uuid']);
			}else{
				$this->_utilMsg->showMsg('参数错误',-1);
			}
			$dataList = $this->getResult($_REQUEST['server_id'],'game/deletecard',array(),$post);
			$this->_utilMsg->showMsg('删除完成',1);
		}
	}

	/**
	 * 道具卡列表
	 * Enter description here ...
	 */
	private function _ItemCardList(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$post['pageIndex'] = intval(max(1,intval($_GET['page'])));
			$post['pageSize'] = PAGE_SIZE;
			$dataList = $this->getResult($_REQUEST['server_id'],'game/listcard',array(),$post);

			//"$dataList" = Array [2]
			//	cardConut = (int) 2
			//	cardList = Array [2]
			//		0 = Array [12]
			//			detail = (string:8) 23432432
			//			id = (int) 45
			//			name = (string:11) 32432432432
			//			point = (int) 3
			//			type = (int) 0
			//			uuid = (string:36) 96fd7563-3ae0-48b0-b3e5-7058872cfe3c
			//			valid = (int) 0
			//			applyName = (string:4) demo
			//			verifyName = (string:4) demo
			//			createTime = (string:19) 2011-10-24 22:10:02
			//			usage = (int) 4
			//			awardInfo = (string:57) 礼劵 5 铜币 8 阅历 9 白虎堂小礼包 x 4 白虎堂二礼包 x 3 白虎堂三礼包 x 6 侠客拳套 x 6 	
			//		1 = Array [12]

			if($dataList['cardList'] && is_array($dataList['cardList'])){
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$dataList['cardConut'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
				$ApplyAudit = $this->_utilRbac->checkAct('Apply_Audit');	//审核权限
				$userClass =$this->_utilRbac->getUserClass();
				$userNickName = $userClass['_nickName'].','.$userClass['_id'];
				foreach($dataList['cardList'] as &$list){
					if($userNickName != $list['applyName']){
						$list['uuid']='没权查看';
					}
					$list['applyName'] = current(explode(',',$list['applyName']));
					$list['verifyName'] = current(explode(',',$list['verifyName']));
					$list['URL_SerialNumber'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'serialnumber','server_id'=>$_REQUEST['server_id'],'giftId'=>$list['Id']));
					$list['URL_del'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'del','server_id'=>$_REQUEST['server_id'],'uuid'=>$list['uuid']));
					$list['URL_ApplyInfo'] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'applyinfo','server_id'=>$_REQUEST['server_id'],'mark'=>$list['id']));
					$list['URL_invalidate'] = Tools::url(CONTROL,"Invalidate",array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'id'=>$list['id']));
					$list['usage'] = isset($this->ItemCardUsage[$list['usage']])?$this->ItemCardUsage[$list['usage']]:'0';
					//					$list->goodIdList = trim($list->goodIdList,',');
					//					$list->serverId = trim($list->serverId,',');
					//					$list->ServerArr = explode(',',$list->serverId);
					//					$tmp = array();
					//					if($list->goodIdList){
					//						$goodIdList = explode(',',$list->goodIdList);
					//						$goodnameList = explode(',',trim($list->goodnameList,','));
					//						$goodsNumList = explode(',',trim($list->goodsNumList,','));
					//						foreach ($goodIdList as $key => $val){
					//							$tmp[$key]=array($goodnameList[$key],$goodsNumList[$key]);
					//						}
					//					}
					//					$list->goods = $tmp;
				}
				$this->_view->assign('dataList',$dataList['cardList']);
			}

			$URL_del = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('URL_del',$URL_del);

			//"$dataList" = Object of: com_cndw_gm_util_Page
			//	totalCount = (double) 75
			//	start = (double) 0
			//	data = Array [5]
			//		0 = Object of: com_cndw_gm_pojo_GiftContentDto
			//			goodsNumList = (string:4) 2,3,
			//			copper = (double) 10
			//			goodIdList = (string:6) 51,62,
			//			goodId = null
			//			contentName = (string:0)
			//			equips = null
			//			forces = (double) 5	//血量
			//			exploit = (double) 0	//修为
			//			id = (double) 76	//礼包内容的id
			//			prestige = (double) 0	//声望
			//			token = (double) 0	//精力
			//			goodsnum = null	//没用
			//			name = (string:8) kakabian //礼包名	
			//			giftId = (double) 75	//礼包id
			//			goodnameList = (string:8) 琵琶石,精布鞋,	
			//			gold = (double) 0	//金币数
			//			serverId = (string:4) 3,1,	//服务器
			//		1 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		2 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		3 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		4 = Object of: com_cndw_gm_pojo_GiftContentDto
			//	pageSize = (int) 15
		}
		//$this->_view->assign('offcardurl',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'apply','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_ApplyItemCard',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'apply','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_ApplyItemCard2',Tools::url(CONTROL,'ApplyItemCard2',array('zp'=>self::PACKAGE,'doaction'=>'apply','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_AddGiftCard',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangMaster/ItemCard.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 添加礼品卡
	 * Enter description here ...
	 */
	private function _addItemCard(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$cardTypeInfo = array(
		1=>array('name'=>'标准礼物卡','bindPlayer'=>true),
		2=>array('name'=>'24字礼物卡','bindPlayer'=>false),
		4=>array('name'=>'四字礼物卡','bindPlayer'=>false),
		);
		$cardType = array();
		foreach($cardTypeInfo as $key =>$val){
			$cardType[$key] = $val['name'];
		}

		if($_REQUEST['server_id']){
			if($this->_isPost()){
				$sendData = array();
				$sendData['iteminfo'] = '0,0,0';	//‘6002:1,6003:2’，或者 ‘12，23，23’				
				switch(intval($_POST['point'])){
					case 0:
						$tmp = array();
						foreach($_POST['goods'] as $key =>$val){
							$k = intval($key);
							$tmp[$k] = $k.':'.$val;
						}
						if($tmp){
							$sendData['iteminfo']=implode(',',$tmp);
						}
						unset($tmp);
						break;
					case 1:
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						break;
					case 2:
					default:
						foreach($_POST['pointVal'] as &$sub){
							$sub  = intval($sub);
						}
						$sendData['iteminfo']=implode(',',$_POST['pointVal']);
						foreach($_POST['goods'] as $key =>$val){
							$k = intval($key);
							$tmp[$k] = $k.':'.$val;
						}
						if($tmp){
							$sendData['iteminfo'].='|'.implode(',',$tmp);
						}
						unset($tmp);
				}
				$sendData['name'] = trim($_POST['name']);
				$sendData['detail'] = trim($_POST['detail']);
				$sendData['point'] = intval($_POST['point']);
				$sendData['regive'] = intval($_POST['regive'])?1:0;
				$apply_info = "<div>申请原因:</div><div style='padding-left:10px;'>{$_POST['cause']}</div>";
				$dataList = $this->getResult($_REQUEST['server_id'],'game/makecard',array(),$sendData);
				//"$dataList" = Array [1]
				//	cardNum = (string:36) 7d40e599-c64a-4b7a-954d-841eed079b11

				if($dataList['cardNum'] && $_POST['isSendMail']){
					$giftUuid=$dataList['cardNum'];
					$title=trim($_POST['title']);
					$context=$_POST['content'];
					$playerIds=trim($_POST['playerId']);
					$checkSendMail = $this->_sendMailForItemCard($giftUuid,$title,$context,$playerIds);
				}
				$this->_utilMsg->showMsg('添加完成',1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}else{
				$Goods = $this->_itemsReCache();
				$this->_view->assign('Goods',$Goods);
			}
		}
		$this->_view->assign('cardTypeInfo',json_encode($cardTypeInfo));
		$this->_view->assign('cardType',$cardType);
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/DaTangMaster/AddGiftCard.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 为礼品卡发邮件
	 * Enter description here ...
	 * @param string $giftUuid		礼包卡
	 * @param string $title			邮件标题
	 * @param string $context		邮件内容
	 * @param string $playerIds		玩家id,逗号隔开，优先
	 * @param string $playerNames	玩家昵称,逗号隔开
	 */
	private function _sendMailForItemCard($giftUuid='',$title='',$context='',$playerIds='',$playerNames=''){
		$sendData = array(
			'giftUuid'=>$giftUuid,
			'title'=>$title,
			'context'=>$context,
			'playerIds'=>$playerIds,
			'playerNames'=>$playerNames,
		);
		return $this->getResult($_REQUEST['server_id'],'user/giftmail',array(),$sendData);
	}

	private function _itemsReCache($effectiveTime=86400){
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$items = $this->_f(self::PACKAGE.'_Items','',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($items){
				return $items;
			}
			//			if(true || in_array($_REQUEST['server_id'],$this->_newServers) ){	//已经更新的服
			$dataList = $this->getResult($_REQUEST['server_id'],'game/itemlist',array(),array());
			//			die();
			$items = array(
			1=>array('Name'=>'装备'),
			2=>array('Name'=>'道具'),
			3=>array('Name'=>'宝石'),
			);
			//			print_r($dataList);
			//			die();
			if($dataList && is_array($dataList)){
				foreach($dataList as $k => $val){
					$goodTypeId = $val['itemType'];
					$items[$goodTypeId]['Item'][$val['itemId']] = $val['itemName'];
				}
			}
			//			}else{	//使用旧接口的服
			//				$dataList = $this->getResult($_REQUEST['server_id'],'game/itemmap',array(),array());
			//				$items = array();
			//				if($dataList && is_array($dataList)){
			//					$items[0]['Name'] = '全部道具';
			//					foreach($dataList as $k => $val){
			//						$items[0]['Item'][$k] = $val;
			//					}
			//				}
			//			}
			$this->_f(self::PACKAGE.'_Items',$items);	//缓存数据数据
			return $items;
		}else{
			return false;
		}
	}

	/**
	 * 玩家充值查询
	 */
	public function actionDepositList(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$exchangeType = array(
		0=>'正常充值',
		1=>'正常充值(官网)',
		);
		if ($_REQUEST['server_id']){
			if(strtotime($_GET['startTime'])){
				$post['startTime'] = $_GET['startTime'];
			}
			if(strtotime($_GET['endTime'])){
				$post['endTime'] = $_GET['endTime'];
			}
			$_GET['playerId'] = trim($_GET['playerId']);
			if($_GET['playerId']){
				$post['playerId'] = $_GET['playerId'];
			}
			$_GET['playerName'] = trim($_GET['playerName']);
			if($_GET['playerName']){
				$post['playerName'] = $_GET['playerName'];
			}
			$_GET['userAccount'] = trim($_GET['userAccount']);
			if($_GET['userAccount']){
				$post['account'] = $_GET['userAccount'];
			}
			$_GET['transactionId'] = trim($_GET['transactionId']);
			if($_GET['transactionId']){
				$post['transactionId'] = $_GET['transactionId'];
			}
			$_GET['exchangeType'] = intval($_GET['exchangeType']);
			if($_GET['exchangeType']){
				$post['exchangeType'] = $_GET['exchangeType'];
			}else{
				$post['exchangeType'] = key($exchangeType);
			}
			$_GET['pageSize'] = intval($_GET['pageSize']);
			$post['pageSize'] = PAGE_SIZE;
			if($_GET['pageSize']>0 && $_GET['pageSize']<100 ){
				$post['pageSize'] = $_GET['pageSize'];
			}
			$post['pageIndex'] = max(1,intval($_GET['page']));
			$dataList = $this->getResult($_REQUEST['server_id'],'user/pay',array(),$post);
			//"$dataList" = Array [2]
			//	count = (int) 4
			//	payData = Array [4]
			//		0 = Array [10]
			//			id = (int) 1
			//			userName = (string:4) ouuu
			//			playerId = (int) 590
			//			account = (string:4) ouuu
			//			transactionId = (string:6) 123321
			//			money = (int) 1
			//			createTime = (int) 1316750400
			//			depay = (int) 1
			//			userGold = (int) 100
			//			exchangeType = (int) 1
			//		1 = Array [10]
			//		2 = Array [10]
			//		3 = Array [10]

			$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
			if($gameser_list[$_REQUEST['server_id']]["operator_id"]=="37"){
				$int_money = 10;
			}else{
				$int_money = 100;
			}
			if($dataList['payData'] && is_array($dataList['payData'])){
				foreach ($dataList['payData'] as &$list){
					$list['money']	=	round($list['money']/$int_money,2);
					$pageMoneyTotal += round($list['money'],2);
					$list['playerId'] = Tools::d2s($list['playerId']);
					$list['createTime'] = date('Y-m-d H:i:s',$list['createTime']);
					$list['exchangeType'] = $exchangeType[$list['exchangeType']];
				}
				$this->_view->assign('dataList',$dataList['payData']);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['count'],'perpage'=>$post['pageSize']));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('pageMoneyTotal',$pageMoneyTotal);
		$this->_view->assign('exchangeType',$exchangeType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	public function actionPhenixModify(){
		$this->_checkOperatorAct();
		$this->_createServerList();

		if ($_REQUEST['server_id']){
			if($this->_isPost()){
				$sendstr['playerId']	=	$_POST['playerId'];
				unset($_POST['playerId']);

				foreach($_POST as $k=>$msg){
					if(!empty($msg)){
						$sendstr['phenixInfo']	.=	$k.":".$msg.",";
					}
				}
				$data = $this->getResult($_REQUEST['server_id'],'data/updatephenixlist',array(),$sendstr);
				if($data['status'] == 1){
					$this->_utilMsg->showMsg('成功');
				}else{
					$this->_utilMsg->showMsg('失败');
				}
				$apply_info = "申请原因<br>{$_POST['cause']}<br>";
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>16,
					'server_id'=>$_REQUEST["server_id"],
					'operator_id'=>$gameser_list[$_REQUEST["server_id"]]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST["server_id"]]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>$itfSet,
						'post_data'=>$sendData,
						'get_data'=>array(),
						'call'=>array(
							'cal_local_object'=>'Game_'.self::GAME_ID,
							'cal_local_method'=>'PlayerDataModify',
				)
				),
					'receiver_object'=>array($_REQUEST["server_id"]=>''),
					'player_type'=>empty($_POST['playerId'])?0:1,
					'player_info'=>$_POST['playerId'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->_utilMsg->showMsg($showMsg,1,1,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
				}
			}elseif($_GET['submit']){
				$sendData = $this->_intFormData(0);
				$data = $this->getResult($_REQUEST['server_id'],'data/getphenixlist',array(),$sendData);

				$datalist	=	array(
					'1'	=> array(
						'name'	=>	'内功',
						'data'	=>	array(),
				),
					'2'	=> array(
						'name'	=>	'阵法',
						'data'	=>	array(),
				),
				);
				if($data['data']){
					foreach($data['data'] as $_msg){
						$datalist[$_msg['type']]['data'][]	=	$_msg;
					}
				}

				if($data['status'] == 1){
					$this->_view->assign('dataList',$datalist);
					$this->_view->assign('player',$data['player']);
				}
			}
		}
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/DaTangMaster/PhenixModify.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionPlayerDataModify(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$lang	=	$this->_getDataModifyFields();
		$doaction = array(
			'money'=>array('data/getgold','data/updategold'),
			'legion'=>array('data/getlegionfighttimes','data/updatelegionfighttimes'),
			
		);
		if(isset($doaction[$_REQUEST['doaction']])){
			$itfGet = $doaction[$_REQUEST['doaction']][0];	//获取数据接口
			$itfSet = $doaction[$_REQUEST['doaction']][1];	//修改数据接口
		}
		foreach($doaction as $key => $sub){
			$URL_doaction[$key] = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>$key,'server_id'=>intval($_REQUEST['server_id'])));
		}
		if ($_REQUEST['server_id']){
			if($this->_isPost()){
				if(empty($itfSet)){
					$this->_utilMsg->showMsg('doaction参数为空');
				}
				$cause	=	$_POST['cause'];
				unset($_POST['cause']);
				$sendData =$this->_intFormData(1);
				$apply_info = "申请原因<br>{$cause}<br>";
				$apply_info	.=$lang[$_REQUEST['doaction']]."修改:<br>";
				foreach($sendData as $k=>$_msg){
					$apply_info	.= "修改".$lang[$k]."为".$_msg."<br>";
				}
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>16,
					'server_id'=>$_REQUEST["server_id"],
					'operator_id'=>$gameser_list[$_REQUEST["server_id"]]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST["server_id"]]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>$itfSet,
						'post_data'=>$sendData,
						'get_data'=>array(),
						'call'=>array(
							'cal_local_object'=>'Game_'.self::GAME_ID,
							'cal_local_method'=>'PlayerDataModify',
				)
				),
					'receiver_object'=>array($_REQUEST["server_id"]=>''),
					'player_type'=>empty($_POST['playerId'])?0:1,
					'player_info'=>$_POST['playerId'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->_utilMsg->showMsg($showMsg,1,1,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
				}
			}elseif($_GET['submit']){
				if(empty($itfGet)){
					$this->_utilMsg->showMsg('doaction参数为空');
				}
				$sendData = $this->_intFormData(0);
				$data = $this->getResult($_REQUEST['server_id'],$itfGet,array(),$sendData);
				if($data['status'] == 1){
					$this->_view->assign('dataList',$data['data']);
					$this->_view->assign('player',$data['player']);
				}
			}
		}
		$this->_view->assign('doaction',$_REQUEST['doaction']);
		$this->_view->assign('lang',$lang);
		$this->_view->assign('URL_doaction',$URL_doaction);
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/DaTangMaster/PlayerDataModify.html'));
		$this->_view->assign('legend','测试玩家数值调整');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 获得表单的值，0/1 GET/POST
	 * @param int(0/1) $method
	 */
	private function _intFormData($method = 1){
		if($method == 1){
			$intArr = $_POST;
		}else{
			$intArr = $_GET;
			unset($intArr['zp'],$intArr['c'],$intArr['a'],$intArr['server_id'],$intArr['page']);
		}
		if(is_array($intArr)){
			foreach($intArr as $key =>$val){
				$intArr[$key] = intval($val);
			}
			return $intArr;
		}
		return array();
	}

	/**
	 * 暂时把字段当成语言写在此方法中
	 */
	private function _getDataModifyFields(){
		return array(
			'money'=>'金钱',
			'coin'=>'铜币',
			'gift'=>'礼券',
			'ingot'=>'元宝',
			'playerId'=>'玩家ID',
			'legion'=>'帮会',
			'times'=>'今日剩余挑战次数',
		);
	}

	public function actionUpdateUserInfo(){
		$this->_checkOperatorAct();
		$this->_createServerList();

		if($_GET['UserId']){
			$dataList=$this->getResult($_REQUEST['server_id'],'data/getUserInfo',array(),array('userId'=>$_GET['UserId']));
			unset($dataList['playerId']);
			if($this->_isPost()){
				$sendData['userId']	=	$_POST['userId'];
				$apply_info	=	"<div>修改玩家的属性</div>";
				$apply_info	.=	"<div>玩家ID：".$_GET['UserId']."</div>";
				$apply_info	.=	"<div>原因：".$_POST['cause']."</div>";
				foreach($dataList as $key=>$item){
					if($key=="skills"){
						foreach($item as $keyson=>$msg){
							if($_POST[$key][$keyson]){
								$apply_info	.=	"<div>".$msg["value"]."修改:".$_POST[$key][$keyson]."</div>";
								$jineng	.=	$msg["value"].":".$_POST[$key][$keyson].",";
							}
						}
						$sendData[$key]	=	$jineng;
					}else{
						$sendData[$key]	=	$_POST[$key];
						$apply_info	.=	"<div>".$item['name']."添加：$_POST[$key]</div>";	
					}

				}
				//$apply_info	.=	"<div>体力添加:".$_POST['power'].";活力点添加:".$_POST['score']."</div>";
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>24,
					'server_id'=>$_REQUEST["server_id"],
					'operator_id'=>$gameser_list[$_REQUEST["server_id"]]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST["server_id"]]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'data/updateUserInfo',
						'post_data'=>$sendData,
						'get_data'=>array(),
						'call'=>array(
							'cal_local_object'=>'Game_'.self::GAME_ID,
							'cal_local_method'=>'PlayerDataModify',
				)
				),
					'receiver_object'=>array($_REQUEST["server_id"]=>''),
					'player_type'=>empty($_POST['playerId'])?0:1,
					'player_info'=>$_POST['playerId'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->_utilMsg->showMsg($showMsg,1,1,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
				}
			}
			$this->_view->assign('item',$dataList);
			$this->_view->assign('UserId',$_GET['UserId']);
			$this->_view->assign('userinfo_display',true);
		}else{
			$this->_view->assign('userinfo_display',false);
		}

		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	public function actionUserPartners(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_forbiddenChatAdd();
				return ;
			}
			case 'del':{
				$this->_forbiddenChatDel();
				return;
			}
			case 'time_end':{
				$this->_forbiddenChatTimeEnd();
				return;
			}
			case 'detail':{
				$this->_operateDetail(2);
				return ;
			}
			default :{
				$this->_indexUserPartners();
				return ;
			}
		}
	}

	private function _indexUserPartners(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if(!empty($_GET['UserId'])){
			$user_type	=	array(
				"0"	=>	"userId",
				"1"	=>	"playerName",
				"2"	=>	"account",
			);

			$dataList=$this->getResult($_REQUEST['server_id'],'data/getUserPartnersInfo',array(),array($user_type["0"]=>$_GET['UserId']));
			if($dataList["status"]===0){
				print_r($dataList);
			}elseif(is_array($dataList)){
				foreach($dataList as $key=>&$item){

					$item["apartnerId"]["tag"]	=	1;
					$item["aname"]["tag"]	=	1;
				}

				$this->_view->assign('item',$dataList);
			}
			if($this->_isPost()){
				$sendData['userId']	=	$_POST['userId'];
				$apply_info	=	"<div>修改玩家武将的属性</div>";
				$apply_info	.=	"<div>原因：".$_POST['cause']."</div>";
				foreach($dataList[0] as $key=>$item){
					$sendData[$key]	=	$_POST[$key];
					if($item["tag"]==1){
						$apply_info	.=	"<div>".$item['name'].":".$_POST[$key]."</div>";
					}else{
						$apply_info	.=	"<div>".$item['name']."设置为：$_POST[$key]</div>";
					}

				}
				unset($sendData["aname"]);
				//$apply_info	.=	"<div>体力添加:".$_POST['power'].";活力点添加:".$_POST['score']."</div>";
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>28,
					'server_id'=>$_REQUEST["server_id"],
					'operator_id'=>$gameser_list[$_REQUEST["server_id"]]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST["server_id"]]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'data/updateUserPartner',
						'post_data'=>$sendData,
						'get_data'=>array(),
						'call'=>array(
							'cal_local_object'=>'Game_'.self::GAME_ID,
							'cal_local_method'=>'PlayerDataModify',
				)
				),
					'receiver_object'=>array($_REQUEST["server_id"]=>''),
					'player_type'=>empty($_POST['playerId'])?0:1,
					'player_info'=>$_POST['playerId'],
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$URL_CsAll = Tools::url('Apply','CsAll');
					$showMsg = '申请成功,等待审核...<br>';
					$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
					$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
					$this->_utilMsg->showMsg($showMsg,1,1,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1,1,false);
				}
			}
			$this->_view->assign('actionurl',Tools::url(CONTROL,'UserPartners',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST["server_id"],'UserId'=>$_GET['UserId'])));
			$this->_view->assign('userinfo_display',true);
			$this->_view->assign('UserId',$_GET['UserId']);
		}else{
			$this->_view->assign('userinfo_display',false);
		}

		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 在线修改玩家培养属性
	 */
	public function actionUpdateUserPartner(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($this->_isPost() && $_REQUEST['server_id']){
			$post = array(
				'userId'=>trim($_POST['userId']),
				'apartnerId'=>trim($_POST['apartnerId']),
				'growAttack'=>intval($_POST['growAttack']),
				'growDefend'=>intval($_POST['growDefend']),
				'growSpeed'=>intval($_POST['growSpeed']),
			);
			$post = array_filter($post);
			$dataList=$this->getResult($_REQUEST['server_id'],'data/updateUserPartner',$post,array());
			if(is_array($dataList) && $dataList['status'] == 1){
				$this->_utilMsg->showMsg('操作成功',1);
			}
			$this->_utilMsg->showMsg('操作失败',-1);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 *  玩家行为(元宝/礼券消费)日志查询功能
	 */
	public function actionOperategoldflow(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($_REQUEST['server_id']){
			if($_REQUEST['sbm']){
				$post = array(
					'costType'=>trim($_GET['costType']),
					'startTime'=>trim($_GET['startTime']),
					'endTime'=>trim($_GET['endTime']),
					'playerId'=>trim($_GET['playerId']),
					'playerName'=>trim($_GET['playerName']),
					'playerAccount'=>trim($_GET['playerAccount']),
					'pageIndex'=>max(1,intval($_GET['page'])),
					'pageSize'=>PAGE_SIZE,
				);
				$playerTmp = $post['playerId'].$post['playerName'].$post['playerAccount'];
				if(empty($playerTmp)){
					$this->_utilMsg->showMsg('玩家不能为空',-1);
				}
				//$post = array_filter($post);
				$dataList=$this->getResult($_REQUEST['server_id'],'user/operategoldflow',array(),$post);
				if(is_array($dataList)){
					$this->_view->assign('dataList',$dataList['plist']);
					$this->_loadCore('Help_Page');
					$this->_helpPage=new Help_Page(array('total'=>$dataList['logCount'],'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$this->_helpPage->show());
				}else{
					$this->_view->assign('errorInfo',$dataList);
				}
			}
		}
		$this->_view->assign('costType',array('-1'=>'所有','0'=>'礼券','1'=>'元宝'));

		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
}