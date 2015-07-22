<?php
/**
 * 仙魂游戏用户管理 
 * @author PHP-兴源
 *
 */
class Control_XianHunUser extends XianHun {
	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;
	
	private $_modelGamePlayerLogTpl;

	public function __construct(){
		parent::__construct();
		$_GET['page']=$_GET['page']?$_GET['page']:1;
	}
	
	protected function _createUrl(){
		$this->_url['OperationLog_FindPage'] = Tools::url(CONTROL,'OperationLog',array('zp'=>'XianHun','doaction'=>'findpage'));
		$this->_url['OperationLog_TplSearch'] = Tools::url(CONTROL,'OperationLog',array('zp'=>'XianHun','doaction'=>'tplsearch'));
		$this->_url['OperationLog_LockIp'] = Tools::url(CONTROL,'LockIp',array('zp'=>'XianHun'));
		$this->_url['OperationLog_LockUser'] = Tools::url(CONTROL,'LockUser',array('zp'=>'XianHun'));
		$this->_url['OperationLog_LockUserDel'] = Tools::url(CONTROL,'LockUser',array('zp'=>'XianHun','doaction'=>'del'));
		$this->_url['OperationLog_ForbiddenChatDel'] = Tools::url(CONTROL,'ForbiddenChat',array('zp'=>'XianHun','doaction'=>'del'));
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 仙魂玩家操作日志
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
			default :{
				$this->_operationLogIndex();
				return ;
			}
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
		$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.$this->game_id.'_'.$Rootid);
		if(!isset($LogTpl[$TypeId])){
			$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.$this->game_id);
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
			if($_GET['account']!=''){
				$helpSqlSearch->set_conditions("username='{$_GET['account']}'");
			}
			
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
		if ($_REQUEST['server_id']){
			$table='user_log';
			$_GET['rootid'] = intval($_GET['rootid']);
			$_GET['typeid'] = intval($_GET['typeid']);
			$_GET['playerId'] = intval($_GET['playerId']);
			$_GET['name'] = trim(strval($_GET['name']));
			$_GET['page'] = intval(max(1,$_GET['page']));			
			$_GET['KeyWordSearchType'] = intval($_GET['KeyWordSearchType']);
			
			$LogRoot = $this->_getGlobalData( 'game_player_log_root_'.$this->game_id );
			if(!$LogRoot){
				$LogRoot = array();
			}
			$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.$this->game_id );
			if(!$LogTpl){
				$LogTpl = array();
			}
			$RootSlt[0] = Tools::getLang('ALL','Common');
			$TypeSlt[0] = Tools::getLang('ALL','Common');
			foreach($LogRoot as $sub){
				$RootSlt[$sub['rootid']] = $sub['rootname'];
			}
			foreach($LogTpl as $sub){					
				$TypeSlt[$sub['rootid']][$sub['typeid']] = $sub['typename'];
			}
			$this->_view->assign('RootSlt',$RootSlt);
			$this->_view->assign('TypeSlt',json_encode($TypeSlt));	
			if($_GET['submitselect']){
				$this->_loadCore('Help_SqlSearch');
				$helpSqlSearch = new Help_SqlSearch();
				$helpSqlSearch->set_tableName('user_log as a,level_up as b');
				$helpSqlSearch->set_field('a.exp as playerExp,b.exp as needExp,a.*,b.*');
				$helpSqlSearch->set_conditions("a.level= b.level");
				if($_GET['rootid']){
					$helpSqlSearch->set_conditions("rootid={$_GET['rootid']}");
				}
				if($_GET['typeid']){
					$helpSqlSearch->set_conditions("typeid={$_GET['typeid']}");
					if(isset($LogTpl[$_GET['typeid']])){					
						$VarCount = intval($LogTpl[$_GET['typeid']]['var_count']);
						$tpl = $LogTpl[$_GET['typeid']]['tpl'];
						switch($_GET['KeyWordSearchType']){
							case '1':{
								$symbol = " > '<REPLACEMENT>'";
								break;
							}
							case '2':{
								$symbol = " < '<REPLACEMENT>'";
								break;
							}
							case '3':{
								$symbol = " like '%<REPLACEMENT>%'";
								break;
							}
							case '0':
							default :{
							$symbol = " = '<REPLACEMENT>'";							
							}						
						}
					
						for($i = 1;$i<=$VarCount;$i++){
							$tpl = str_replace('{x'.$i.'}',' <input name="x'.$i.'" class="text" style="width:50px;" value="'.$_GET['x'.$i].'" /> ',$tpl);
							if(trim($_GET['x'.$i]) != ''){
								$xCondition = str_replace('<REPLACEMENT>',$_GET['x'.$i],$symbol);
								$helpSqlSearch->set_conditions("x{$i} {$xCondition}");	
							}
						}
						$this->_view->assign('TplSearch',$tpl);
					}				
				}
				if($_GET['playerId']){
					$helpSqlSearch->set_conditions("a.playerId={$_GET['playerId']}");
				}
				if($_GET['name']!=''){
					$helpSqlSearch->set_conditions("a.name='{$_GET['name']}'");
				}
				if($_GET['account']!=''){
					$helpSqlSearch->set_conditions("a.username='{$_GET['account']}'");
				}			
				if(strtotime($_GET['StartTime'])){
					$helpSqlSearch->set_conditions("a.timestamp>='{$_GET['StartTime']}'");
				}
				if(strtotime($_GET['EndTime'])){
					$helpSqlSearch->set_conditions("a.timestamp<='{$_GET['EndTime']}'");
				}
				$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
				$helpSqlSearch->set_orderBy('a.timestamp desc');
				$sql=$helpSqlSearch->createSql();
				$dataList = $this->SelectXianHun($sql);
				//print_r($dataList);
				if($dataList){
					foreach($dataList as &$ALog){
					//如果存在模板类型，匹配模板的内容
						$ALog['timestamp'] = $this->getXianHunTime($ALog['timestamp']);
						if(isset($LogTpl[$ALog['typeid']])){						
							$VarCount = intval($LogTpl[$ALog['typeid']]['var_count']);
							$ALog['content'] = $LogTpl[$ALog['typeid']]['tpl'];
							for($i = 1;$i<=$VarCount;$i++){
								$keyContent = is_array($ALog['x'.$i])?implode(',',$ALog['x'.$i]):$ALog['x'.$i];
								$ALog['content'] = str_replace('{x'.$i.'}',"<font color=red>{$keyContent}</font>",$ALog['content']);
							}
						}else{
							for($i = 1;$i<=10;$i++){
								if($ALog['x'.$i]!='')$ALog['content'] .= "x{$i}:<font color=red>{$ALog['x'.$i]}</font>,";
							}
							$ALog['content'] = "<font color='#999999'>日志模板未配置({$ALog['typeid']})</font><br>{$ALog['content']}";
						}
						$ALog['rootname'] = isset($LogRoot[$ALog['rootid']]['rootname'])?$LogRoot[$ALog['rootid']]['rootname']:$ALog['rootid'];
						$ALog['typename'] = isset($LogTpl[$ALog['typeid']]['typename'])?$LogTpl[$ALog['typeid']]['typename']:$ALog['typeid'];
					}
					$this->_view->assign('dataList',$dataList);	
				}elseif($dataList === false){
					$this->_utilMsg-> showMsg('DB ERROR',-1);
				}
				$this->_loadCore('Help_Page');//载入分页工具
				$conditions=$helpSqlSearch->get_conditions();
				$totle = $this->CountXianHun('user_log as a,level_up as b',$conditions);
				$helpPage=new Help_Page(array('total'=>$totle,'perpage'=>PAGE_SIZE));
				$this->_view->assign('selected',$_GET);
				$this->_view->assign('pageBox',$helpPage->show());
			}
			
		}		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	
	/**
	 * 在线用户列表
	 */
	public function actionIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->list();
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	

	
	/**
	 * 用户详细查询
	 */
	public function actionUserDetail(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		
		$QueryField = false;
		$userType = 0;
		$_GET['userId'] = intval($_GET['userId']);
		$_GET['username'] = trim(strval($_GET['username']));
		$_GET['nickname'] = trim(strval($_GET['nickname']));
		
		if($_GET['userId'] > 0){
			$QueryField = $_GET['userId'];
		}elseif('' != $_GET['username']){
			$QueryField = $_GET['username'];
			$userType = 2;
		}elseif('' != $_GET['nickname']){
			$QueryField = $_GET['nickname'];
			$userType = 1;
		}		
		$select = array(
			'userId'=>$_GET['userId'],
			'username'=>$_GET['username'],
			'nickname'=>$_GET['nickname'],
		);
		
		if ($_REQUEST['server_id'] && $QueryField){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->detail($QueryField,$userType);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('select',$select);
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 扣除背包装备
	 */
	public function actionKnapSack(){
		switch ($_REQUEST['doaciton']){
			case 'del':
				$this->_delItems('delItemFromKnapsack','背包');
				break;
			default:
				$this->_knapSack();
				break;
		}
	}
	
	/**
	 * 背包装备列表
	 * Enter description here ...
	 */
	private function _knapSack(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$QueryField = false;
		$userType = 0;
		$_GET['userId'] = intval($_GET['userId']);
		$_GET['username'] = trim(strval($_GET['username']));
		$_GET['nickname'] = trim(strval($_GET['nickname']));
		if($_GET['userId'] > 0){
			$QueryField = $_GET['userId'];
		}elseif('' != $_GET['username']){
			$QueryField = $_GET['username'];
			$userType = 2;
		}elseif('' != $_GET['nickname']){
			$QueryField = $_GET['nickname'];
			$userType = 1;
		}
		$select = array(
			'userId'=>$_GET['userId'],
			'username'=>$_GET['username'],
			'nickname'=>$_GET['nickname'],
		);
		if ($_REQUEST['server_id'] && $QueryField){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->knapsack($QueryField,$userType);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('select',$select);
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_view->assign('legend','背包装备');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/Dress.html'));
		$this->_view->display();
	}
	
	/**
	 * 扣除身穿装备
	 */
	public function actionDress(){
		switch ($_REQUEST['doaciton']){
			case 'del':
				$this->_delItems('delItemFromDress','身穿');
				break;
			default:
				$this->_dress();
				break;
		}
	}
	
	private function _dress(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$QueryField = false;
		$userType = 0;
		$_GET['userId'] = intval($_GET['userId']);
		$_GET['username'] = trim($_GET['username']);
		$_GET['nickname'] = trim($_GET['nickname']);
		if($_GET['userId'] > 0){
			$QueryField = $_GET['userId'];
		}elseif('' != $_GET['username']){
			$QueryField = $_GET['username'];
			$userType = 2;
		}elseif('' != $_GET['nickname']){
			$QueryField = $_GET['nickname'];
			$userType = 1;
		}
		$select = array(
			'userId'=>$_GET['userId'],
			'username'=>$_GET['username'],
			'nickname'=>$_GET['nickname'],
		);
		if ($_REQUEST['server_id'] && $QueryField){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->dress($QueryField,$userType);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('select',$select);
			$this->_view->assign('searchType',$this->_searchUserType);
			$this->_view->assign('legend','身穿装备');
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
	}
	
	private function _delItems($method='',$desc=''){
		if(empty($method)){
			$this->_utilMsg->showMsg('method为空,请联系管理员',-1);
		}
		$_POST['userId'] = intval($_POST['userId']);
		$_POST['username'] = trim(strval($_POST['username']));
		$_POST['nickname'] = trim(strval($_POST['nickname']));
		if($_POST['userId'] > 0){
			$user = $_POST['userId'];
			$userType = 0;
		}elseif('' != $_POST['username']){
			$user = $_POST['username'];
			$userType = 2;
		}elseif('' != $_POST['nickname']){
			$user = $_POST['nickname'];
			$userType = 1;
		}else{
			$this->_utilMsg->showMsg('user error',-1);
		}
		$DelData = array();
		$itemNames = array();
		if($_POST['delItems']){
			foreach($_POST['delItems'] as $keyItemId =>$delValue){
				$delValue = intval($delValue);
				if($delValue>0){
					$itemId = strstr($keyItemId,'_');
					$itemId = ltrim($itemId,'_');
					if(isset($DelData[$itemId])){
						$DelData[$itemId] += $delValue;
					}else{
						$DelData[$itemId] = $delValue;
						$itemNames[$itemId] = $_POST['itemsName'][$keyItemId];//道具名
					}
				}
			}	
		}
//"$DelData" = Array [3]	
//	31012101 = (int) 3	
//	31015101 = (int) 1	
//	31025101 = (int) 2	
//"$itemNames" = Array [3]	
//	31012101 = (string:4) 青溥戒指	
//	31015101 = (string:4) 刑天戒指	
//	31025101 = (string:4) 阎梦戒指
		if($DelData){
			foreach($DelData as $key =>&$val){
				$itemNames[$key] .='(<font color="#FF0000">'.$val.'</font>)';
				$val = $key.':'.$val;
			}
			$DelData = implode(',',$DelData);
			$playerInfo = array(0=>'玩家ID',1=>'昵称',2=>'账号');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>'.$playerInfo[$userType].'：</div><div style="padding-left:10px;">'.$user.'</div>';
			$apply_info.="<div>扣除道具({$desc})：</div><div style='padding-left:10px;'>".implode(' , ',$itemNames).'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
				'type'=>7,	//扣除道具申请
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/item',
					'phprpc_method'=>$method,
					'phprpc_params'=>array($user,$DelData,$userType),
					'phprpc_key'=>self::RPC_KEY,
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);	
			
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}			
//			$rpc = $this->getApi();
//			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
//			$rpc->setPrivateKey(self::RPC_KEY);
//			$dataResult=$rpc->invoke($method,array($user,$DelData,$userType));
//			if($dataResult instanceof PHPRPC_Error ){
//				$this->_utilMsg->showMsg($dataResult->Message,-1,1,false);
//			}elseif($dataResult){
//				$jumpUrl = Tools::url(CONTROL,ACTION,array('userId'=>$_POST['userId']));
//				$this->_utilMsg->showMsg($dataResult,$jumpUrl);
//			}
		}
		else{
			$this->_utilMsg->showMsg('没有选择道具',-1);
		}
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
				return ;
			}
			case 'detail':{
				$this->_operateDetail(1);
				return ;
			}
			default :{
				$this->_lockUserIndex();
				return ;
			}
		}
	}
	
	/**
	 * 封号列表
	 */
	private function _lockUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();		
		$_GET['playerId'] = trim($_GET['playerId']);
		$_GET['username'] = trim($_GET['username']);
		$_GET['nickname'] = trim($_GET['nickname']);
		$_GET['page'] = max(1,intval($_GET['page']));
		$_GET['pageSize'] = PAGE_SIZE;		
		if ($_REQUEST['server_id']){			
			$UrlLockUserAdd = Tools::url(CONTROL,'LockUser',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getForbiddenIdPlayers($_GET['playerId'],$_GET['username'],$_GET['nickname'],$_GET['page'],$_GET['pageSize']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				
				foreach($dataList[results] as &$sub){
					//print_r($sub);
					$sub['URL_TimeEnd'] = Tools::url(CONTROL,ACTION,array('zp'=>'XianHun','doaction'=>'detail','id'=>$sub['playerId'],'server_id'=>$_REQUEST['server_id']));
				}
				
				
				$_GET['page'] = intval($dataList['pageIndex']);	//使用服务器纠正当前页数
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($dataList['total']),'perpage'=>PAGE_SIZE));
				$this->_view->assign('selected',$_GET);
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('dataList',$dataList['results']);
			}
		}
		$this->_view->assign('UrlLockUserAdd',$UrlLockUserAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 添加封号
	 */
	private function _lockUserAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();	
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(!strtotime($_POST['StartTime']) || !strtotime($_POST['EndTime'])){
				$this->_utilMsg->showMsg('时间错误',-1);
			}
			$_POST['cause'] = trim($_POST['cause']);
			$_POST['users'] = trim($_POST['users']);
			$_POST['ReceiveType'] = intval($_POST['ReceiveType']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->forbiddenPlayer($_POST['users'],$_POST['StartTime'],$_POST['EndTime'],$_POST['ReceiveType']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				if($dataList['msgno'] == 1){
					$Exist = $dataList['backparams']['Exist'];					
					//warren 操作日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
							array('操作','<font style="color:#F00">封号</font>'),
							array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
							array('操作人','<b>{UserName}</b>'),
							array('封号结束时间',$_POST['EndTime']),
							array('原因',$_POST['cause']),
					);	
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					foreach($Exist as $sub){
						//todo();记录日志
						$userInfo =$sub;
						$userInfo['UserId'] = $sub['userId'];
						$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,1,$_REQUEST['server_id'],$AddLog);
						if(false !== $GameOperateLog){
							$this->_modelGameOperateLog->add($GameOperateLog);
						}
						
					}
					//================
					$noExist = $dataList['backparams']['noExist'];			
					
					if(is_array($noExist) && count($noExist)>0){
						$dataList['message'] .= '<br>不存在:'.implode(',',$noExist);
					}

					$this->_utilMsg->showMsg($dataList['message'],1,Tools::url(CONTROL,'LockUser',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id'])),NULL);
				}else{
					
					$this->_utilMsg->showMsg($dataList['message'],-1);
				}
			}
			return ;
		}
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/LockUserAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();	
	}
	
	/**
	 * 删除封号 
	 */
	private function _lockUserDel(){
//		3)删除封号用户
//		接口：user
//		方法：delForbiddenIdPlayers
//		参数：	
//			String 		playerIds	用户id串 （“,”分隔）
//		返回 是否成功
		
		$playerId = (array)$_POST['playerId'];
		if($playerId){
			$playerId = implode(',',$playerId);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->delForbiddenIdPlayers($playerId);
			
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1);
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
		$_GET['page'] = max(1,intval($_GET['page']));
		$_GET['pageSize'] = PAGE_SIZE;		
		if ($_REQUEST['server_id']){			
			$UrlForbiddenChatAdd = Tools::url(CONTROL,'ForbiddenChat',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getForbiddenChatPlayers($_GET['playerId'],$_GET['username'],$_GET['nickname'],$_GET['page'],$_GET['pageSize']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				
				foreach($dataList[results] as &$sub){
					//print_r($sub);
					$sub['URL_TimeEnd'] = Tools::url(CONTROL,ACTION,array('zp'=>'XianHun','doaction'=>'detail','id'=>$sub['playerId'],'server_id'=>$_REQUEST['server_id']));
				}
				
				$_GET['page'] = intval($dataList['pageIndex']);	//使用服务器纠正当前页数
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>intval($dataList['total']),'perpage'=>PAGE_SIZE));
				$this->_view->assign('selected',$_GET);
				$this->_view->assign('pageBox',$helpPage->show());
				foreach($dataList['results'] as &$list){
					$endTime = strtotime($list['banChatEndTime']);
					if($endTime && $endTime < CURRENT_TIME){
						$list['banChatStatus'] = 3;
					}
				}
				$this->_view->assign('dataList',$dataList['results']);
			}
		}
		$this->_view->assign('UrlForbiddenChatAdd',$UrlForbiddenChatAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();	
	}
	
	/**
	 * 添加禁言
	 */
	private function _forbiddenChatAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();	
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(!strtotime($_POST['StartTime']) || !strtotime($_POST['EndTime'])){
				$this->_utilMsg->showMsg('时间错误',-1);
			}
			$_POST['cause'] = trim($_POST['cause']);
			$_POST['users'] = trim($_POST['users']);
			$_POST['ReceiveType'] = intval($_POST['ReceiveType']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->forbiddenChat($_POST['users'],$_POST['StartTime'],$_POST['EndTime'],$_POST['ReceiveType']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				if($dataList['msgno'] == 1){
					$Exist = $dataList['backparams']['Exist'];					
					//warren 操作日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
							array('操作','<font style="color:#F00">封言</font>'),
							array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
							array('操作人','<b>{UserName}</b>'),
							array('封言结束时间',$_POST['EndTime']),
							array('原因',$_POST['cause']),
					);	
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					foreach($Exist as $sub){
						//todo();记录日志
						$userInfo =$sub;
						$userInfo['UserId'] = $sub['userId'];
						$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,2,$_REQUEST['server_id'],$AddLog);
						if(false !== $GameOperateLog){
							$this->_modelGameOperateLog->add($GameOperateLog);
						}
						
					}
					//================
					$noExist = $dataList['backparams']['noExist'];			
					
					if(is_array($noExist) && count($noExist)>0){
						$dataList['message'] .= '<br>不存在:'.implode(',',$noExist);
					}

					$this->_utilMsg->showMsg($dataList['message'],1,Tools::url(CONTROL,'ForbiddenChat',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id'])),NULL);
				}else{
					
					$this->_utilMsg->showMsg($dataList['message'],-1);
				}
			}
			return ;
		}
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/ForbiddenChatAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();	
	}
	
	/**
	 * 解除禁言
	 */
	private function _forbiddenChatDel(){
		$playerId = (array)$_POST['playerId'];
		if($playerId){
			$playerId = implode(',',$playerId);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->delForbiddenChatPlayers($playerId);
			
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
			
		}else{
			$this->_utilMsg->showMsg('没有选择',-1);
		}	
	}
	
	/**
	 * 封IP
	 */
	public function actionLockIp(){
//		1)获取ip锁定列表
//		接口：server
//		方法：getBlackIps
//		参数：无，  
//		返回：array[string]
//		
//		2)添加ip锁定
//		接口：server
//		方法：addBlackIps
//		参数：String ips	","分隔的ip串
//		返回：是否成功
//		 
//		3)删除ip锁定 
//		接口：server
//		方法：delBlackIps
//		参数：String ips	","分隔的ip串
//		返回：是否成功
		
		switch($_GET['doaction']){
			case 'mng':{
				$this->_lockIpMng();
				return;
			}
			default :{
				$this->_lockIpIndex();
				return ;
			}
		}
	}
	
	/**
	 * 被封IP列表
	 */
	private function _lockIpIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getBlackIps();
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList && is_array($dataList)){
				$this->_view->assign('dataList',implode(',',$dataList));
			}
			$UrlIpMng = Tools::url(CONTROL,'LockIp',array('zp'=>'XianHun','doaction'=>'mng','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('UrlIpMng',$UrlIpMng);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * IP封锁管理
	 */
	private function _lockIpMng(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['ips']=trim($_POST['ips']);
			$_POST['OperateType'] = intval($_POST['OperateType']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			if($_POST['OperateType'] == 1){
				$ReturnResult=$rpc->delBlackIps($_POST['ips']);				
			}else{
				$ReturnResult=$rpc->addBlackIps($_POST['ips']);
			}
			$UrlLockIp = Tools::url(CONTROL,'LockIp',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
			if($ReturnResult instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($ReturnResult){
				$this->_utilMsg->showMsg('操作成功',1,$UrlLockIp,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/LockIpMng.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 群发邮件
	 */
	public function actionSendMail(){
//		1)用户群发短信邮件
//		接口：server
//		方法：sendMails
//		参数：	
//			String	users			用户id串，或昵称串（“,”分隔）
//			int			receiveType 	0用户id，1角色昵称
//			String	title			标题
//			String	content		内容
//			String	itemAndNums	物品id和数量:	100001:1,100002:1
//			int			gold			游戏币（银两）
//			int			cash			金币（人民币）
//		返回：
//			是否成功
//			
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){			
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}		
			$_POST['users'] = trim($_POST['users']);	
			if($_POST['users'] ==''){
				$this->_utilMsg->showMsg('用户不能为空',-1);	
			}			
			$_POST['receiveType'] = intval($_POST['receiveType']);		
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->sendMails($_POST['users'],$_POST['receiveType'],$_POST['title'],$_POST['content'],'',0,0);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
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
				if($this->_addCache ( $dataList, CACHE_DIR . '/'.self::XIANHUN_ITEMS_CACHE_NAME.'.cache.php' )){
					$this->_utilMsg->showMsg('操作成功',1,1,1);
				}
			}
			$this->_utilMsg->showMsg('操作失败',-1);	//如果到达这个阶段，就报错
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 道具发放至邮箱
	 */
	public function actionSendItems(){
		$this->_utilMsg->showMsg('被屏蔽');
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['cause']) == ''){
				$this->_utilMsg->showMsg('操作原因不能为空',-1);
			}
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}
			$_POST['users']=trim($_POST['users']);
			if($_POST['users'] ==''){
				$this->_utilMsg->showMsg('用户不能为空',-1);	
			}
			
			$_POST['receiveType'] = intval($_POST['receiveType']);	
			$_POST['gold'] = intval($_POST['gold']);
			$_POST['cash'] = intval($_POST['cash']);
			
			$ItemAndNums = array();
			if(is_array($_POST['ItemId']) && is_array($_POST['ItemNum'])){
				foreach($_POST['ItemId'] as $key => $sub){
					$ItemAndNums['ItemId'.$_POST['ItemId'][$key]] = $_POST['ItemId'][$key].':'.$_POST['ItemNum'][$key];
				}
			}
			unset($ItemAndNums['ItemId']);	//删除页面上没有传输id的道具
			$ItemAndNums = implode(',',$ItemAndNums);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
			$rpc->setPrivateKey(self::RPC_KEY);
			switch (intval($_POST['receiveType'])){
				case 1:
					$dataList=$rpc->giveToMailBoxByNames($_POST['users'],$_POST['title'],$_POST['content'],$ItemAndNums,$_POST['gold'],$_POST['cash']);
					break;
				case 2:
					$dataList=$rpc->giveToMailBoxByUsernames($_POST['users'],$_POST['title'],$_POST['content'],$ItemAndNums,$_POST['gold'],$_POST['cash']);
					break;
				case 0:
				default:
					$dataList=$rpc->giveToMailBoxByIds($_POST['users'],$_POST['title'],$_POST['content'],$ItemAndNums,$_POST['gold'],$_POST['cash']);
					break;
			}
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg($dataList,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		//创建更新道具的RUL
		$URL_ItemUpdate = Tools::url(CONTROL,'ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();		
	}
	
	public function actionItemsApply(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['cause']) == ''){
				$this->_utilMsg->showMsg('操作原因不能为空',-1);
			}
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}
			$_POST['users']=trim($_POST['users']);
			if($_POST['users'] ==''){
				$this->_utilMsg->showMsg('用户不能为空',-1);	
			}
			
			$_POST['receiveType'] = intval($_POST['receiveType']);	
			$_POST['gold'] = intval($_POST['gold']);
			$_POST['cash'] = intval($_POST['cash']);
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
				if($_POST['cash'] >20000){
					$this->_utilMsg->showMsg('金币不能过20000',-1);
				}
			}
			$ItemAndNums = array();
			$ItemInfo = array();
			if(is_array($_POST['ItemId']) && is_array($_POST['ItemNum'])){
				foreach($_POST['ItemId'] as $key => $sub){
					$ItemAndNums['ItemId'.$_POST['ItemId'][$key]] = $_POST['ItemId'][$key].':'.$_POST['ItemNum'][$key];
					$ItemInfo[] = $_POST['ItemName'][$key].'(<font style="color:red">'.$_POST['ItemNum'][$key].'</font>)';
				}
			}
			$userType=array(0=>'玩家ID',1=>'昵称',2=>'账号');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>'.$userType[$_POST['receiveType']].'：</div><div style="padding-left:10px;">'.$_POST['users'].'</div>';
			$apply_info.='<div>邮件标题：</div><div style="padding-left:10px;">'.$_POST['title'].'</div>';
			$apply_info.='<div>邮件内容：</div><div style="padding-left:10px;">'.$_POST['content'].'</div>';
			$apply_info.='<div>点数：</div><div style="padding-left:10px;">游戏币(<font style="color:red">'.$_POST['gold'].'</font>) , 金币(<font style="color:red">'.$_POST['cash'].'</font>)</div>';
			$apply_info.='<div>道具：</div><div style="padding-left:10px;">'.implode(' , ',$ItemInfo).'</div>';
			unset($ItemAndNums['ItemId']);	//删除页面上没有传输id的道具
			$ItemAndNums = implode(',',$ItemAndNums);
//			$rpc = $this->getApi();
//			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
//			$rpc->setPrivateKey(self::RPC_KEY);
			switch (intval($_POST['receiveType'])){
				case 1:
					$phprpcMethod = 'giveToMailBoxByNames';
					$player_type = 2;
					break;
				case 2:
					$phprpcMethod = 'giveToMailBoxByUsernames';
					$player_type = 3;
					break;
				case 0:
				default:
					$phprpcMethod = 'giveToMailBoxByIds';
					$player_type = 1;
					break;
			}
			$gameser_list = $this->_getGlobalData('server/server_list_'.$this->game_id);
			$applyData = array(
					'type'=>1,	//道具发放申请
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>3,	//3	phprpc
					'send_data'=>array (
						'url_append'=>'rpc/item',
						'phprpc_method'=>$phprpcMethod,
						'phprpc_params'=>array(
								$_POST['users'],
								$_POST['title'],
								$_POST['content'],
								$ItemAndNums,
								$_POST['gold'],
								$_POST['cash'],
								),
						'phprpc_key'=>self::RPC_KEY,
					),
					'receiver_object'=>array($_REQUEST['server_id']=>''),
					'player_type'=>$player_type,
					'player_info'=>$_POST['users'],
				);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$this->_utilMsg->showMsg('申请成功,等待审核...',1,Tools::url('Apply','CsIndex'),1);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}
		}
		//创建更新道具的RUL
		$URL_ItemUpdate = Tools::url(CONTROL,'ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();			
	}


	/**
	 * 发放道具至背包
	 */
	public function actionSendItemsToKnapsack(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['cause']) == ''){
				$this->_utilMsg->showMsg('操作原因不能为空',-1);
			}
			$_POST['users']=trim($_POST['users']);
			if($_POST['users'] ==''){
				$this->_utilMsg->showMsg('用户不能为空',-1);	
			}
			$_POST['receiveType'] = intval($_POST['receiveType']);			
			$ItemAndNums = array();
			if(is_array($_POST['ItemId']) && is_array($_POST['ItemNum'])){
				foreach($_POST['ItemId'] as $key => $sub){
					$ItemAndNums['ItemId'.$_POST['ItemId'][$key]] = $_POST['ItemId'][$key].':'.$_POST['ItemNum'][$key];
				}
			}
			unset($ItemAndNums['ItemId']);	//删除页面上没有传输id的道具
			$ItemAndNums = implode(',',$ItemAndNums);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
			$rpc->setPrivateKey(self::RPC_KEY);
			if($_POST['receiveType'] == 1){
				$dataList=$rpc->giveToKnapsackByNames($_POST['users'],$ItemAndNums);
			}else{
				$dataList=$rpc->giveToKnapsackByIds($_POST['users'],$ItemAndNums);
			}			
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg($dataList,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		//创建更新道具的RUL
		$URL_ItemUpdate = Tools::url(CONTROL,'ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	
	/**
	 * 道具发放申请
	 */
	public function actionItemsApply1(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
		
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}
			$_POST['users']=trim($_POST['users']);
			if($_POST['users'] ==''){
				$this->_utilMsg->showMsg('用户不能为空',-1);	
			}			
			$_POST['receiveType'] = intval($_POST['receiveType']);	
			
			$_POST['receiveType'] = intval($_POST['receiveType']);
			$_POST['title']=trim($_POST['title']);
			$_POST['content']=trim($_POST['content']);
			$_POST['items'] = (array)$_POST['items'];
			$_POST['ItemNums'] = (array)$_POST['ItemNums'];
			$_POST['gold'] = intval($_POST['gold']);
			$_POST['cash'] = intval($_POST['cash']);

		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();	
	}
	
	/**
	 * 用户查询
	 */
	public function actionUserQuery(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		
		$QueryField = false;
		$_GET['userId'] = intval($_GET['userId']);
		$_GET['username'] = trim(strval($_GET['username']));
		$_GET['nickname'] = trim(strval($_GET['nickname']));
		if($_GET['userId'] > 0){
			$QueryField = $_GET['userId'];
		}elseif('' != $_GET['username']){
			$QueryField = $_GET['username'];
		}elseif('' != $_GET['nickname']){
			$QueryField = $_GET['nickname'];
		}
		
		$select = array(
			'userId'=>$_GET['userId'],
			'username'=>$_GET['username'],
			'nickname'=>$_GET['nickname'],
		);
		
		if ($_REQUEST['server_id'] && $QueryField){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->findByNickname($QueryField);			
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$dataList['UserDetailUrl'] = Tools::url(CONTROL,'UserDetail',array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'userId'=>$dataList['userId']));
				$this->_view->assign('dataList',$dataList);
			}		
			
		}
		$this->_view->assign('select',$select);
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionUserQueryDb(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			
			$_GET['playId'] = intval($_GET['playId']);
			$_GET['nickname'] = trim($_GET['nickname']);			
			$_GET['username'] = trim($_GET['username']);
			$_GET['regIp'] = trim($_GET['regIp']);
			
			$_GET['cashFrom'] = intval($_GET['cashFrom']);
			$_GET['cashTo'] = intval($_GET['cashTo']);
			$_GET['coinFrom'] = intval($_GET['coinFrom']);
			$_GET['coinTo'] = intval($_GET['coinTo']);
			$_GET['levelFrom'] = intval($_GET['levelFrom']);
			$_GET['levelTo'] = intval($_GET['levelTo']);
			
			$_GET['loginTimeFrom'] = trim($_GET['loginTimeFrom']);
			$_GET['loginTimeTo'] = trim($_GET['loginTimeTo']);
						
			$_GET['isBanChat'] = intval($_GET['isBanChat']);
			$_GET['isBanId'] = intval($_GET['isBanId']);
			
			$_GET['page'] = max(1,intval($_GET['page']));
		
			
			$sqlexp['main'] = 'select A.id,A.username,A.nickname,A.loginTime,A.offlineTime,A.createTime,A.regIp,A.level,A.exp,A.vocationId,B.cash,B.coin,C.id as assId,C.name as assName from player A left join player_money B on A.id=B.playerId left join player_association C on A.associationId = C.id where 1 ';
			$sqlexp['conditions'] = '';
			
			
			if($_GET['playId']>0){			
				$sqlexp['conditions'] .=" and A.id={$_GET['playId']}";
			}	
			if($_GET['nickname']!=''){	
				$sqlexp['conditions'] .=" and A.nickname like '%{$_GET['nickname']}%'";
			}
			if($_GET['username']!=''){
				$sqlexp['conditions'] .=" and A.username='{$_GET['username']}'";
			}
			if($_GET['regIp']!=''){
				$sqlexp['conditions'] .=" and A.regIp = '{$_GET['regIp']}'";
			}
			if($_GET['cashFrom']>0){
				$sqlexp['conditions'] .=" and B.cash >= {$_GET['cashFrom']}";
			}
			if($_GET['cashTo']>0){
				$sqlexp['conditions'] .=" and B.cash <= {$_GET['cashTo']}";
			}
			if($_GET['coinFrom']>0){
				$sqlexp['conditions'] .=" and B.coin >= {$_GET['coinFrom']}";
			}
			if($_GET['coinTo']>0){
				$sqlexp['conditions'] .=" and B.coin <= {$_GET['coinTo']}";
			}
			if(strtotime($_GET['loginTimeFrom'])){
				$sqlexp['conditions'] .=" and A.loginTime >= '{$_GET['loginTime']}'";
			}
			if(strtotime($_GET['loginTimeTo'])){
				$sqlexp['conditions'] .=" and A.loginTime <= '{$_GET['loginTime']}'";
			}

			if(strtotime($_GET['regTimeFrom'])){
				$sqlexp['conditions'] .=" and A.createTime >= '{$_GET['regTimeFrom']}'";
			}
			if(strtotime($_GET['regTimeTo'])){
				$sqlexp['conditions'] .=" and A.createTime <= '{$_GET['regTimeTo']}'";
			}
			
			$nowTimeStr = date('Y-m-d H:i:s',CURRENT_TIME);
			if(intval($_GET['isBanChat'])){
				$sqlexp['conditions'] .=" and A.banChatEndTime >= '{$nowTimeStr}'";
			}
			if(intval($_GET['isBanId'])){
				$sqlexp['conditions'] .=" and A.banIdEndTime >= '{$nowTimeStr}'";
			}			
			$sqlexp['limit'] = ' limit '.PAGE_SIZE*($_GET['page']-1).','.PAGE_SIZE;
			
			$sqlexp['order'] = ' order by 1';			
			
			$sql = $this->_makeSql($sqlexp);
			$dataList = $this->SelectXianHun($sql);
			if($dataList){
				$serverList = $this->_getGlobalData('server/server_list_'.$this->game_id);
				foreach($dataList as &$list){
					$list['loginTime'] = $this->getXianHunTime($list['loginTime']);
					$list['offlineTime'] = $this->getXianHunTime($list['offlineTime']);
					$list['regTime'] = $this->getXianHunTime($list['createTime']);
					$bugParam = array(
						'game_type_id'=>$this->game_id,
						'operator_id'=>$serverList[$_REQUEST['server_id']]['operator_id'],
						'game_server_id'=>$_REQUEST['server_id'],
						'game_user_id'=>$list['id'],
						'user_account'=>$list['username'],
						'user_nickname'=>$list['nickname'],
					);
					$list['URL_Bug'] = Tools::url('Verify','OrderVerify',$bugParam);
					$list['UserDetailUrl'] = Tools::url(CONTROL,'UserDetail',array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'userId'=>$list['id']));
				}
				$this->_view->assign('dataList',$dataList);
				$TotalSql = 'select count(1) from player A left join player_money B on A.id=B.playerId left join player_association C on A.associationId = C.id where 1 '.$sqlexp['conditions'];
				//echo $TotalSql;
				$total = $this->CountXianHunBySql($TotalSql);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}
			$this->_view->assign('selected',$_GET);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionMailQuery(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $_REQUEST['submit']){
			$_GET['type'] = intval($_GET['type']);
			$_GET['readed'] = intval($_GET['readed']);
			$_GET['received'] = intval($_GET['received']);
			$_GET['returned'] = intval($_GET['returned']);
			$_GET['deleted'] = intval($_GET['deleted']);
			$_GET['receiverId'] = trim($_GET['receiverId']);
			$_GET['receiverName'] = trim($_GET['receiverName']);
			$_GET['receiverUsername'] = trim($_GET['receiverUsername']);
			
			$sqlexp['main'] = 'SELECT * FROM player_mail a, player b WHERE a.receiverId=b.id';
			$sqlexp['conditions'] = '';
			
			if($_GET['type']){
				$sqlexp['conditions'] .= ' and a.type='.$_GET['type'];
			}
			if($_GET['readed'] == 1){
				$sqlexp['conditions'] .= ' and a.readed=1';
			}elseif($_GET['readed'] == 2){
				$sqlexp['conditions'] .= ' and a.readed=0';
			}
			if($_GET['received'] == 1){
				$sqlexp['conditions'] .= ' and a.received=1';
			}elseif($_GET['received'] == 2){
				$sqlexp['conditions'] .= ' and a.received=0';
			}
			if($_GET['returned'] == 1){
				$sqlexp['conditions'] .= ' and a.returned=1';
			}elseif($_GET['returned'] == 2){
				$sqlexp['conditions'] .= ' and a.returned=0';
			}
			if($_GET['deleted'] == 1){
				$sqlexp['conditions'] .= ' and a.deleted=1';
			}elseif($_GET['deleted'] == 2){
				$sqlexp['conditions'] .= ' and a.deleted=0';
			}
			if($_GET['receiverId'] != ''){
				$sqlexp['conditions'] .= ' and a.receiverId= '.intval($_GET['receiverId']);
			}
			if($_GET['receiverName'] != ''){
				$sqlexp['conditions'] .= " and b.nickname= '{$_GET['receiverName']}' ";
			}
			
			if($_GET['receiverUsername'] != ''){
				$sqlexp['conditions'] .= " and b.username= '{$_GET['receiverUsername']}' ";
			}
			
			$sqlexp['limit'] = ' limit '.PAGE_SIZE*($_GET['page']-1).','.PAGE_SIZE;
			
			$sqlexp['order'] = ' order by a.id desc';			
			
			$sql = $this->_makeSql($sqlexp);

			$dataList = $this->SelectXianHun($sql);

//"$dataList"	Array [20]	
//	0	Array [21]	
//	1	Array [21]	
//	2	Array [21]	
//	3	Array [21]	
//	4	Array [21]	
//	5	Array [21]	
//	6	Array [21]	
//	7	Array [21]	
//	8	Array [21]	
//	9	Array [21]	
//	10	Array [21]	
//	11	Array [21]	
//	12	Array [21]	
//	13	Array [21]	
//		id	(string:5) 19261	
//		receiverId	(string:5) 15231	
//		receiverName	(string:0) 	
//		title	(string:1) d	
//		content	(string:1) d	
//		attachments	(string:274) [{"itemId":66903993483265,"name":"鎏金翅·地煞","num":1,"ownerId":15231,"received":true,"type":1},{"itemId":66903993548801,"name":"鎏金翅·天罡","num":1,"ownerId":15231,"received":true,"type":1},{"itemId":66903993614337,"name":"鎏金翅·天罡","num":1,"ownerId":15231,"received":true,"type":1}]	
//		price	(string:1) 0	
//		senderId	(string:1) 0	
//		senderName	(string:2) 系统	
//		sendTime	(string:19) 2011-09-20 17:36:19	
//		type	(string:1) 2	
//		saved	(string:1) ...
			if($dataList){
				foreach($dataList as &$list){
					$list['readTime'] = $this->getXianHunTime($list['readTime']);
					$list['receiveTime'] = $this->getXianHunTime($list['receiveTime']);
					$list['returnTime'] = $this->getXianHunTime($list['returnTime']);
					$list['deleteTime'] = $this->getXianHunTime($list['deleteTime']);
					$list['sendTime'] = $this->getXianHunTime($list['sendTime']);
					if(is_string($list['attachments'])){
						$list['attachments'] = json_decode($list['attachments'],true);
					}
					if(is_array($list['attachments'])){
						$tmp = array();
						foreach($list['attachments'] as $sub){
							if($sub['type']==1){
								$tmp[] = "<font color='#FF0000'>{$sub['name']}</font>(<font color='#FF0000'>{$sub['num']}</font>).{$sub['itemId']}.{$sub['ownerId']}";
							}elseif($sub['type']==2){
								$tmp[] = "金币(<font color='#FF0000'>{$sub['num']}</font>)";
							}
						}
						$list['attachments'] = implode(' , ',$tmp);
					}else{
						$list['attachments'] = '';
					}
				}
			}
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('selected',$_GET);
			
			$TotalSql = 'select count(*) from player_mail a,player b  where a.receiverId=b.id and a.type<>4 '.$sqlexp['conditions'];

			$total = $this->CountXianHunBySql($TotalSql);
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_view->assign('selected',$_GET);
		$this->_view->assign('type',array(0=>'全部',1=>'玩家邮件',2=>'系统邮件'));
		$IsOrNot = array(0=>'全部',1=>'是',2=>'否');
		$this->_view->assign('readed',$IsOrNot);
		$this->_view->assign('received',$IsOrNot);
		$this->_view->assign('returned',$IsOrNot);
		$this->_view->assign('deleted',$IsOrNot);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 游戏登录（登录玩家的游戏）
	 */
	public function actionGameLogin(){
		$this->_checkOperatorsAct();
		$serverList=$this->_getGlobalData('server/server_list_'.$this->game_id);
		if ($this->_isPost()){
			$_uname = trim($_POST['user_name']);
			if(empty($_uname)){
				echo '账号不能为空';
			}else{
				$_verifycode = CURRENT_TIME;
				$_sign = md5(self::RPC_KEY.$_verifycode);
				$serverUrl=$serverList[$_POST['server_id']]['server_url'];
				$serverUrl .= "gmlogin?_verifycode={$_verifycode}&_sign={$_sign}&_uname={$_uname}";
				header('Location: '.$serverUrl);
			}
			exit();
		}else {
			if ($_GET['operator_id']){
				
				foreach ($serverList as $key=>&$value){
					if (empty($value['server_url']))unset($serverList[$key]);
					if ($value['operator_id']!=$_GET['operator_id'])unset($serverList[$key]);
				}
				$this->_view->assign('dataList',$serverList);
			}

			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 玩家充值查询
	 */
	public function actionDepositList(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$exchangeType = array(
			''=>'全部',
			2=>'Q点',
			1=>'金币卡',
			0=>'钱',
		);
		$status = array(
			''=>'全部',
			0=>'接收订单',
			1=>'支付成功',
			-1=>'支付失败',
		);
		
		if ($_REQUEST['server_id']){
//			$table = 'player_money_log';
			$table = 'player_deposit';
			$sqlexp['main'] = "select * from {$table} where 1";
			$sqlexp['conditions'] = '';

			$_GET['playerId'] = trim($_GET['playerId']);
			if($_GET['playerId']){
				$sqlexp['conditions'] .= " and playerId ='{$_GET['playerId']}'";
			}
//			$_GET['playerName'] = trim($_GET['playerName']);
//			if($_GET['playerName']){
//				$post['playerName'] = $_GET['playerName'];
//			}
			$_GET['userAccount'] = trim($_GET['userAccount']);
			if($_GET['userAccount']){
				$sqlexp['conditions'] .= " and UserName ='{$_GET['userAccount']}'";
			}
			$_GET['transactionId'] = trim($_GET['transactionId']);
			if($_GET['transactionId']){
				$sqlexp['conditions'] .= " and TransactionId ='{$_GET['transactionId']}'";
			}
			
			if($_GET['exchangeType']!==''){
				$sqlexp['conditions'] .= " and ExchangeType ='{$_GET['exchangeType'] }'";
			}
			
			if($_GET['status']!==''){
				$sqlexp['conditions'] .= " and status ='{$_GET['status'] }'";
			}
			
			$startTime = strtotime($_GET['startTime']);
			if(strtotime($_GET['startTime'])){
				$sqlexp['conditions'] .= " and IncomeTime >='{$startTime}'";
			}
			$endTime = strtotime($_GET['endTime']);
	 		if($endTime){
	 			$sqlexp['conditions'] .= " and IncomeTime <='{$endTime}'";
	 		}	 		
	 		$_GET['pageSize'] = intval($_GET['pageSize']);
	 		if($_GET['pageSize']>0 && $_GET['pageSize']<=100){
	 			$pageSize = $_GET['pageSize'];
	 		}else{
	 			$pageSize = PAGE_SIZE;
	 		}
			$sqlexp['limit'] = ' limit '.$pageSize*($_GET['page']-1).','.$pageSize;			
			$sqlexp['order'] = ' order by id desc';	 		
	 		$sql = $this->_makeSql($sqlexp);
	 		$dataList = $this->SelectXianHun($sql);			
			//"$dataList" = Array [14]	
			//	0 = Array [16]	
			//		Id = (string:2) 14	
			//		UserId = (string:1) 0	
			//		UserName = (string:7) test111	
			//		playerId = (string:1) 0	
			//		TransactionId = (string:36) 1111099209990201109071559350YBKS3050	
			//		Cash = (string:6) 999.00	
			//		IncomeTime = (string:10) 1316421012	
			//		Depay = (string:4) 0.00	
			//		GDepay = (string:4) 0.00	
			//		GetGold = (string:4) 9990	
			//		RecentGold = (string:1) 0	
			//		AddCoin = (string:1) 0	
			//		GoldCard = null	
			//		ExchangeType = (string:1) 0	
			//		PayIp = (string:14) /192.168.12.87	
			//		status = (string:7) success	
			//	1 = Array [16]	
			//	2 = Array [16]	
			$count = $this->CountXianHun($table,$sqlexp['conditions']);			
			$pageMoneyTotal = 0;
			if($dataList){
				foreach ($dataList as &$list){
					$pageMoneyTotal += round($list['Cash'],2);
					$list['IncomeTime'] = date('Y-m-d H:i:s',$list['IncomeTime']);
					$list['ExchangeType'] = $exchangeType[$list['ExchangeType']];
					$list['status'] = $status[$list['status']];
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$count,'perpage'=>$pageSize));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('pageMoneyTotal',$pageMoneyTotal);
		$this->_view->assign('exchangeType',$exchangeType);
		$this->_view->assign('status',$status);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionTest(){
		
		$serverList=$this->_getGlobalData('gameser_list');
		$server=$serverList[$_REQUEST['server_id']]['server_url'].'rpc/user';
		$phprpcPath=LIB_PATH . '/phprpc/phprpc_client.php';
		if (!file_exists($phprpcPath))throw new Error('phprpc libs not exist');
		include_once $phprpcPath;
		$phpRpc=new PHPRPC_Client();
		$phpRpc->useService($server);
		$phpRpc->setProxy(null);	//设置代理
		$phpRpc->setEncryptMode(0);
		$phpRpc->setCharset('UTF-8');
		$phpRpc->setTimeout(10);
		$phpRpc->setPrivateKey('test');
		$dataList=$phpRpc->getInfo('wcj');
		print_r($dataList);
		
	}
	
	/**
	 * 
	 * 操作日志获取
	 * @param unknown_type $operateType
	 */
	private function _operateDetail($operateType=0){		
		if ($_REQUEST['server_id']){
			$gameUserId = $_GET['id'];
			$operateType = intval($operateType);
			$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
			$dataList = $this->_modelGameOperateLog->getDetail($_REQUEST['server_id'],$gameUserId,$operateType);

			//print_r($dataList);
//			echo $dataList["0"]["info"];
			$jsonData = array('status'=>1,'info'=>NULL,'data'=>$dataList);
		}else{
//			echo "暂时没有记录";
			$jsonData = array('status'=>0,'info'=>'server id error','data'=>NULL);
		}
		//echo $jsonData;
		$this->_returnAjaxJson($jsonData);
	}
	
	/**
	 * 礼包卡号管理
	 */
	public function actionItemsPackage(){
		switch (($_GET['doaction'])){
			case 'add':
				$this->_itemsPackageAdd();
				return;
			case 'edit':{
				$this->_itemsPackageEdit();
				return;
			}
			case 'cardAdd':
				$this->_itemsPackageCardAdd();
				return;
			case 'cardView':
				$this->_itemsPackagecardView();
				return;
			default:
				$this->_itemsPackageIndex();
				break;
		}
	}
	
	private function _itemsPackageCardAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($_REQUEST['server_id']){
			$type = intval($_GET['type']);
			if($type>0){
				if($this->_isPost()){
					$rpc = $this->getApi();
					$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
					$rpc->setPrivateKey(self::RPC_KEY);
					$num = intval($_POST['num']);
					$day = intval($_POST['day']);
					if($num<=0 || $day<0){
						$this->_utilMsg->showMsg('num <= 0 || day <0',-1);
					}
					$dataList=$rpc->genCard($type,$num,$day);	//第一个参数从getCardReward接口拿到，对礼包创建卡
					if($dataList instanceof PHPRPC_Error ){
						$this->_utilMsg->showMsg($dataList->Message,-1);
					}elseif($dataList){
						$jumpUrl = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id']));
						$this->_utilMsg->showMsg('操作成功',1,$jumpUrl,1);			
					}else{
						$this->_utilMsg->showMsg('操作失败',-1);
					}
				}
			}else{
				$this->_utilMsg->showMsg('type id error',-1);
			}
			$this->_view->assign('type',$type);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/ItemsPackageCardAdd.html'));
		$this->_view->display();
	}
	
	private function _itemsPackagecardView(){
		$this->_checkOperatorAct();
		$this->_createServerList();

		if($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$type = $_GET['type'];
			if($_REQUEST['submit']){

				$startDate = trim($_GET['startDate']);
				$endDate = trim($_GET['endDate']);
				if($type && strtotime($startDate) && strtotime($endDate)){
					$dataList = $rpc->getCard($type,$startDate,$endDate);
					if($dataList instanceof PHPRPC_Error){
						$this->_view->assign('ConnectErrorInfo',$dataList->Message);
					}elseif($dataList){
						$this->_view->assign('dataList',$dataList);
					}
				}
			}
			$typeList = $rpc->getCardReward();	//card类型列表（道具打包列表）
			$typeList = Model::getTtwoArrConvertOneArr($typeList,'type','des');
			$this->_view->assign('typeList',$typeList);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/ItemsPackageCardView.html'));
		$this->_view->display();
	}
	
	private function _itemsPackageIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		
		if ($_REQUEST['server_id']){
			$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList = $rpc->getCardReward();	//card类型列表（道具打包列表）
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif(is_array($dataList)){
				foreach($dataList as &$subType){
					//$subType['type'];
					//$subType['des'];
					$subType['items'] = explode(',',$subType['items']);
					$itemInfo = '';
					if($subType['items']){
						foreach($subType['items'] as $val){
							list($itemId,$num) = explode('=',$val,2);
							$itemInfo .="$itemsData[$itemId](<font color='#FF0000'>$num</font>),";
						}	
					}
					$subType['items'] = $itemInfo;
					$subType['URL_edit'] = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit','type'=>$subType['type']));
					$subType['URL_cardAdd'] = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'doaction'=>'cardAdd','type'=>$subType['type']));
					$subType['URL_cardView'] = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'doaction'=>'cardView','type'=>$subType['type']));
				}
				$this->_view->assign('dataList',$dataList);
			}
		}
		$URL_itemsPackageAdd = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_view->assign('URL_itemsPackageAdd',$URL_itemsPackageAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _itemsPackageAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		if ($this->_isPost() && $_REQUEST['server_id']){
			$this->_itemsPackageUpdate(true);
		}
		$URL_ItemUpdate = Tools::url(CONTROL,'ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/ItemsPackageAdd.html'));
		$this->_view->display();
	}
	
	private function _itemsPackageEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		if ($this->_isPost() && $_REQUEST['server_id']){
			$this->_itemsPackageUpdate();
		}else{
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$type = intval($_GET['type']);
			$dataList = $rpc->getCardReward($type);
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$items = array();
				$i = 1;
				$dataList['items'] = explode(',',$dataList['items']);
				if($dataList['items']){
					foreach ($dataList['items'] as $val){
						list($itemId,$num) = explode('=',$val,2);
						$items[$i++] = array('id'=>$itemId,'name'=>$itemsData[$itemId],'num'=>intval($num));
					}
				}
				$this->_view->assign('itemsNow',$items);
				$this->_view->assign('itemInputNum',$i);
				$this->_view->assign('list',$dataList);
			}
		}
		$URL_ItemUpdate = Tools::url(CONTROL,'ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/ItemsPackageAdd.html'));
		$this->_view->display();
	}
	
	private function _itemsPackageUpdate($add = false){
		if(!$_REQUEST['server_id']){
			return ;
		}
		$typeId = intval($_POST['typeId']);
		if(!$typeId){
			$this->_utilMsg->showMsg('type id error',-1);
		}
		$rpc = $this->getApi();
		$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
		$rpc->setPrivateKey(self::RPC_KEY);
		if($add && $rpc->existCardReward($typeId)){
			$this->_utilMsg->showMsg('礼包类型ID已存在',-1);
		}
		$itemInfo = array();
		foreach($_POST['ItemId'] as $key => $itemId){
			$itemInfo[] = $_POST['ItemId'][$key].'='.$_POST['ItemNum'][$key];
		}
		$itemInfo = implode(',',$itemInfo);
		$describe = $_POST['describe'];
		$img = trim($_POST['img']);
		$dataList = $rpc->updateCardReward($typeId,$describe,$itemInfo,$img);
		if($dataList instanceof PHPRPC_Error ){
			$this->_utilMsg->showMsg($dataList->Message,-1);
		}elseif($dataList){
			$jumpUrl = Tools::url(CONTROL,ACTION,array('zp'=>$this->package,'server_id'=>$_REQUEST['server_id']));
			$this->_utilMsg->showMsg('操作成功',1,$jumpUrl,1);				
		}else{
			$this->_utilMsg->showMsg('操作失败',-1);
		}
	}
	
	public function actionWarehouse(){
		
		switch ($_REQUEST['doaciton']){
			case 'del':
				$this->_delWarehouse('delItemFromWarehouse','仓库');
				break;
			default:
				$this->_Warehouse();
				break;
		}
	}
	
	private function _Warehouse(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$QueryField = false;
		$userType = 0;
		$_GET['userId'] = intval($_GET['userId']);
		$_GET['username'] = trim(strval($_GET['username']));
		$_GET['nickname'] = trim(strval($_GET['nickname']));
		if($_GET['userId'] > 0){
			$QueryField = $_GET['userId'];
		}elseif('' != $_GET['username']){
			$QueryField = $_GET['username'];
			$userType = 2;
		}elseif('' != $_GET['nickname']){
			$QueryField = $_GET['nickname'];
			$userType = 1;
		}
		$select = array(
			'userId'=>$_GET['userId'],
			'username'=>$_GET['username'],
			'nickname'=>$_GET['nickname'],
		);
		if ($_REQUEST['server_id'] && $QueryField){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/user');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->wareHouse($QueryField,$userType);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('select',$select);
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_view->assign('legend','背包装备');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunUser/Dress.html'));
		$this->_view->display();
	}
	
	private function _delWarehouse($method='',$desc=''){
		if(empty($method)){
			$this->_utilMsg->showMsg('method为空,请联系管理员',-1);
		}
		$_POST['userId'] = intval($_POST['userId']);
		$_POST['username'] = trim(strval($_POST['username']));
		$_POST['nickname'] = trim(strval($_POST['nickname']));
		if($_POST['userId'] > 0){
			$user = $_POST['userId'];
			$userType = 0;
		}elseif('' != $_POST['username']){
			$user = $_POST['username'];
			$userType = 2;
		}elseif('' != $_POST['nickname']){
			$user = $_POST['nickname'];
			$userType = 1;
		}else{
			$this->_utilMsg->showMsg('user error',-1);
		}
		$DelData = array();
		$itemNames = array();
		if($_POST['delItems']){
			foreach($_POST['delItems'] as $keyItemId =>$delValue){
				$delValue = intval($delValue);
				if($delValue>0){
					$itemId = strstr($keyItemId,'_');
					$itemId = ltrim($itemId,'_');
					if(isset($DelData[$itemId])){
						$DelData[$itemId] += $delValue;
					}else{
						$DelData[$itemId] = $delValue;
						$itemNames[$itemId] = $_POST['itemsName'][$keyItemId];//道具名
					}
				}
			}	
		}
//"$DelData" = Array [3]	
//	31012101 = (int) 3	
//	31015101 = (int) 1	
//	31025101 = (int) 2	
//"$itemNames" = Array [3]	
//	31012101 = (string:4) 青溥戒指	
//	31015101 = (string:4) 刑天戒指	
//	31025101 = (string:4) 阎梦戒指
		if($DelData){
			foreach($DelData as $key =>&$val){
				$itemNames[$key] .='(<font color="#FF0000">'.$val.'</font>)';
				$val = $key.':'.$val;
			}
			$DelData = implode(',',$DelData);
			$playerInfo = array(0=>'玩家ID',1=>'昵称',2=>'账号');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>'.$playerInfo[$userType].'：</div><div style="padding-left:10px;">'.$user.'</div>';
			$apply_info.="<div>扣除道具({$desc})：</div><div style='padding-left:10px;'>".implode(' , ',$itemNames).'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
				'type'=>7,	//扣除道具申请
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/item',
					'phprpc_method'=>$method,
					'phprpc_params'=>array($user,$DelData,$userType),
					'phprpc_key'=>self::RPC_KEY,
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);	
			
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}			
//			$rpc = $this->getApi();
//			$rpc->setUrl($_REQUEST['server_id'],'rpc/item');
//			$rpc->setPrivateKey(self::RPC_KEY);
//			$dataResult=$rpc->invoke($method,array($user,$DelData,$userType));
//			if($dataResult instanceof PHPRPC_Error ){
//				$this->_utilMsg->showMsg($dataResult->Message,-1,1,false);
//			}elseif($dataResult){
//				$jumpUrl = Tools::url(CONTROL,ACTION,array('userId'=>$_POST['userId']));
//				$this->_utilMsg->showMsg($dataResult,$jumpUrl);
//			}
		}
		else{
			$this->_utilMsg->showMsg('没有选择道具',-1);
		}
	}
	
	public function actionModification(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($_GET["playerId"]){
			$playerId	=	$_GET["playerId"];
			$this->_view->assign("playerId",$playerId);
			$this->_view->assign("modifdisplay",true);
		}
		if($this->_isPost()){
			switch ($_POST['actionfunction']){
				case "addSingleInstanceCount":
					$method	=	"addSingleInstanceCount";
					$parameter	=	array($_POST["addcount"]);
					$playerId	=	"发送至全部玩家";
					$content	=	"增加所有人每天单人副本次数:".$_POST["addcount"];
					break;
				case "addMultiInstanceCount":
					$method	=	"addMultiInstanceCount";
					$parameter	=	array($_POST["addcount"]);
					$playerId	=	"发送至全部玩家";
					$content	=	"增加所有人每天多人副本次数:".$_POST["addcount"];
					break;
				case "setSingleInstanceCount":
					$method	=	"setSingleInstanceCount";
					$parameter	=	array($playerId,$_POST["addcount"]);
					$content	=	"增加某人每天单人副本次数:".$_POST["addcount"];
					break;
				case "setMultiInstanceCount":
					$method	=	"setMultiInstanceCount";
					$parameter	=	array($playerId,$_POST["addcount"],);
					$content	=	"增加某人每天多人副本次数:".$_POST["addcount"];
					break;
				case "setInstanceCount":
					$method	=	"setInstanceCount";
					$parameter	=	array($_POST['fbid'],$_POST["addcount"]);
					$content	=	"设置某个副本每天次数:次数".$_POST["addcount"]."；副本ID".$_POST['fbid'];
					break;
				case "setInstanceProgress":
					//setInstanceProgress($playerId,$post["instanceId"],$post['progressNum']);
					$method	=	"setInstanceProgress";
					$parameter	=	array($playerId,$_POST["instanceId"],$_POST['progressNum']);
					$content	=	"设置某个用户某个副本的进度， 天神阁就是层数:副本id:".$_POST["instanceId"]."；层数:".$_POST['progressNum'];
					break;
				case "setForgeLevel":
					$method	=	"setForgeLevel";
					//setInstanceProgress($playerId,$post["itemId"],$post['level'])
					$parameter	=	array($playerId,$_POST["itemId"],$_POST['level']);
					$content	=	"设置某人某道具的强化等级:道具id:".$_POST["itemId"]."；层数:".$_POST['level'];
					break;
				
			}
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>玩家ID:'.$playerId.'</div>';
			$apply_info.='<div style="padding-left:10px;">'.$content.'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
				'type'=>20,	//扣除道具申请
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/role',
					'phprpc_method'=>$method,
					'phprpc_params'=>$parameter,
					'phprpc_key'=>self::RPC_KEY,
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);	
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}	
		}
		
		$this->_view->display();
	}
	
	public function actionSoulModification(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($_GET["playerId"]){
			$playerId	=	$_GET["playerId"];
			$this->_view->assign("playerId",$playerId);
			$this->_view->assign("modifdisplay",true);
		}
		
		if($_GET["soul_playerId"]){
			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/role');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList = $rpc->getPlayerSoul($_GET["soul_playerId"]);
			if(is_array($dataList)){
				$this->_view->assign("soul_playerId_display",true);
			}
			$this->_view->assign("soul_playerId_array",$dataList);
			$this->_view->assign("soul_playerId",$_GET["soul_playerId"]);	
		}

		
		
		if($this->_isPost()){
			switch ($_POST['actionfunction']){
				case "setPlayerSoul":
					//setPlayerSoulEx($playerId,$post["powerEx"],$post['agileEx'],$post['witEx'],$post['enduranceEx']);
					$method		=	"setPlayerSoul";
					$parameter	=	array($playerId,$_POST["level"],$_POST['exp'],$_POST['growth'],$_POST['color']);
					$content	=	"设置宠物等级 经验 成长 品质:宠物等级{".$_POST["level"]."};宠物经验{".$_POST["exp"]."};宠物成长{".$_POST["growth"]."};宠物品质{".$_POST["color"]."}";
					break;
				case "setPlayerSoulEx":
					$method		=	"setPlayerSoulEx";
					$parameter	=	array($playerId,$_POST["powerEx"],$_POST['agileEx'],$_POST['witEx'],$_POST['enduranceEx']);
					$content	=	"设置天赋力敏智耐值 :力{".$_POST["powerEx"]."};敏{".$_POST["agileEx"]."};智{".$_POST["witEx"]."};耐{".$_POST["enduranceEx"]."}";
					break;
				case "setPlayerSoulAddition":
					$method		=	"setPlayerSoulAddition";
					$parameter	=	array($playerId,$_POST["powerAddition"],$_POST['agileAddition'],$_POST['witAddition'],$_POST['enduranceAddition']);
					$content	=	"设置洗髓力敏智耐值:宠物等级{".$post["powerAddition"]."};宠物经验{".$post["agileAddition"]."};宠物成长{".$post["witAddition"]."};宠物品质{".$post["enduranceAddition"]."}";
					break;
			}
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>玩家ID:'.$playerId.'</div>';
			$apply_info.='<div style="padding-left:10px;">'.$content.'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
				'type'=>20,	//扣除道具申请
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/role',
					'phprpc_method'=>$method,
					'phprpc_params'=>$parameter,
					'phprpc_key'=>self::RPC_KEY,
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);	
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}	
			
		}
		$this->_view->display();
	}
	
	private function _setPlayerSoul($post,$id){
		$rpc = $this->getApi();
		$rpc->setUrl($_REQUEST['server_id'],'rpc/role');
		$rpc->setPrivateKey(self::RPC_KEY);
		
		$data=$rpc->setPlayerSoul($id,$post["level"],$post['exp'],$post['growth'],$post['color']);
		
		if($data){
			$this->_utilMsg->showMsg($data,1);
		}else{
			$this->_utilMsg->showMsg("操作失败",-1);
		}
	}
	
	private function _setPlayerSoulEx($post,$id){
		$rpc = $this->getApi();
		$rpc->setUrl($_REQUEST['server_id'],'rpc/role');
		$rpc->setPrivateKey(self::RPC_KEY);
		$data=$rpc->setPlayerSoulEx($id,$post["powerEx"],$post['agileEx'],$post['witEx'],$post['enduranceEx']);
		if($data){
			$this->_utilMsg->showMsg($data,1);
		}else{
			$this->_utilMsg->showMsg("操作失败",-1);
		}
	}
	
	private function _setPlayerSoulAddition($post,$id){
		$rpc = $this->getApi();
		$rpc->setUrl($_REQUEST['server_id'],'rpc/role');
		$rpc->setPrivateKey(self::RPC_KEY);
		$data=$rpc->setPlayerSoulAddition($id,$post["powerAddition"],$post['agileAddition'],$post['witAddition'],$post['enduranceAddition']);
		if($data){
			$this->_utilMsg->showMsg($data,1);
		}else{
			$this->_utilMsg->showMsg("操作失败",-1);
		}
	}

	public function actionPlayerMoneyLog(){
		switch ($_REQUEST['doaction']){
			case 'update':
				$this->_playerMoneyType();
				$this->_utilMsg->showMsg("操作完成",1);
				return ;
			case 'index':
			default:
				$this->_playerMoneyLogIndex();
		}
		
	}
	
	private function _playerMoneyLogIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($_REQUEST['server_id'] && $_REQUEST['sbm']){
			$table = 'player_money_log';
			$this->_loadCore('Help_SqlSearch');
			$helpSqlSearch = new Help_SqlSearch();
			$helpSqlSearch->set_tableName($table);
			//隐藏特性,可以输入0来查询全部
			$playerId= trim($_GET['playerId']);
			if(strlen(trim($_GET['playerId']))==0){
				$this->_utilMsg->showMsg('玩家Id必须',-1);
			}
			$playerId = intval($playerId);
			if($playerId){
				$helpSqlSearch->set_conditions("playerId='{$playerId}'");
			}
			if(0 !=($type = intval($_GET['type']))){
				$helpSqlSearch->set_conditions("type='{$type}'");
			}
			if(false !== ($StartTime = strtotime($_GET['StartTime']))){
				$helpSqlSearch->set_conditions("timestamp>='{$StartTime}'");
			}
			if(false !== ($EndTime = strtotime($_GET['EndTime']))){
				$helpSqlSearch->set_conditions("timestamp<='{$EndTime}'");
			}
			$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
			$helpSqlSearch->set_orderBy('id desc');
			$sql=$helpSqlSearch->createSql();
			$dataList = $this->SelectXianHun($sql);
			if($dataList){
				$this->_view->assign('dataList',$dataList);
				$conditions=$helpSqlSearch->get_conditions();
				$count = $this->CountXianHun($table,$conditions);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$count,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('type',$this->_playerMoneyType());
		$this->_view->assign('URL_typeUpdate',Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'update','is_update'=>'1')));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _playerMoneyType($isUpdate = false){
		$fileName = self::GAME_ID.'_'.PACKAGE.'_'.CONTROL.'_'.ACTION.'_'.__FUNCTION__;
		if(!$isUpdate){
			$isUpdate = isset($_REQUEST['is_update'])?(boolean)$_REQUEST['is_update']:false;
		}
		if(!$isUpdate){
			$data = $this->_f($fileName);
			if($data !== false){
				return $data;
			}
		}
		$type = $this->getResult('moneytype');
		$data = array();
		if(is_array($type)){
			foreach ($type as $value){
				$data[$value[0]] = $value[5];
			}
		}
		$this->_f($fileName,$data);
		return $data;
	}
	
	public function actionPlayerValueUpdate(){
		$this->_checkOperatorAct();
		$this->_createServerList();
	
		if($this->_isPost()){
			if($_POST['sub1']){
				$method		=	"updateTianshenCount";
				$parameter	=	array($_POST["sub1_playerId"],$_POST["sub1_count"]);
				$content	=	"修改天神阁次数 玩家Id:{$_POST['sub1_playerId']},次数:{$_POST['sub1_count']}";
			} else if($_POST['sub2']){
				$method		=	"giveZhenWuBuff";
				$parameter	=	array($_POST["sub2_playerId"]);
				$content	=	"奖励真武观buff 玩家Id:{$_POST['sub2_playerId']}";
			} else if($_POST['sub3']){
				$method		=	"changeLevel";
				$parameter	=	array($_POST["sub3_playerId"],$_POST["sub3_level"],$_POST["sub3_exp"]);
				$content	=	"修改人物等级经验值 玩家Id:{$_POST['sub3_playerId']} 玩家等级:{$_POST['sub3_level']} 经验:{$_POST['sub3_exp']}";
			} else if($_POST['sub4']){
				$method		=	"updateTianGuanCount";
				$parameter	=	array($_POST["sub4_playerId"],$_POST["sub4_count"]);
				$content	=	"修改闯天关次数 玩家Id:{$_POST['sub4_playerId']} 次数:{$_POST['sub4_count']}";
			}
	
			$apply_info = $content;
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
				'type'=>65,	//申请
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/user',
					'phprpc_method'=>$method,
					'phprpc_params'=>$parameter,
					'phprpc_key'=>self::RPC_KEY,
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);	
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}	
			
		}
		$this->_view->display();
	}
	
}