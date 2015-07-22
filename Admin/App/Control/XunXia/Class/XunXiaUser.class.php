<?php
/**
 * 游戏用户管理 
 * @author PHP-朱磊
 *
 */
class Control_XunXiaUser extends XunXia {

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;

	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;

	private $_searchType=array(	1=>'用户ID',	2=>'用户名',	3=>'时间范围',);

	private $_searchUserType=array(1=>'用户ID',2=>'用户名',3=>'等级范围',4=>'金币范围',5=>'系统金币',6=>'银币范围');

	public function __construct(){
		$_GET['page']=$_GET['page']?$_GET['page']:1;
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['OperationLog_LockUserDel'] = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 用户查询
	 */
	public function actionIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'player/player');
			switch ($_GET['type']){
				case '1' :{//用户ID
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryPlayerById($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '2' :{//用户名
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryPlayerByName($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '3' :{//等级范围
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryPlayerByLevel($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '4' :{//金币
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryPlayerByGold($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '5' :{//系统金币
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryPlayerBySysGold($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '6' :{//银币
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryPlayerBySilver($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
			}
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				foreach ($dataList->page->data as $list){
					$list->playerId = $this->d2s($list->playerId);
				}
				$URL_Opt['sendMsg'] = Tools::url('XunXiaSysManage','SendMsg',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$URL_Opt['resUserAdd'] = Tools::url('XunXiaSysManage','ResUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$URL_Opt['talkUserAdd'] = Tools::url('XunXiaSysManage','TalkUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$this->_view->assign('URL_Opt',$URL_Opt);
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 游戏金币操作日志
	 * 金币改为“元宝”
	 */
	public function actionUserGoldLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'operationLog/operLog');
			switch ($_GET['type']){
				case '1' :{//用户ID
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperGoldLogById($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '2' :{//用户名
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperGoldLogByName($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '3' :{//时间范围
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryOperGoldLogByTime($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
			}
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				if (count($dataList->page->data)){
					foreach ($dataList->page->data as $list){
						$list->playerId = $this->d2s($list->playerId);
						$list->type = isset($this->_goldTypeID[$list->type])?$this->_goldTypeID[$list->type]:$list->type;
						$list->subType = isset($this->_goldSubTypeID[$list->subType])?$this->_goldSubTypeID[$list->subType]:$list->subType;
						$list->useValue=$list->oldValue-$list->newValue;
						//传输过来的double类型的毫秒
						$list->createAt=date('Y-m-d H:i:s',$list->createAt/1000);
					}
					$this->_view->assign('dataList',$dataList->page->data);
				}
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏银币操作日志
	 * “银币”改为”铜钱“
	 */
	public function actionUserSilverLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'operationLog/operLog');
			switch ($_GET['type']){
				case '1' :{//用户ID
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperSilverLogById($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '2' :{//用户名
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperSilverLogByName($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '3' :{//时间范围
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryOperSilverLogByTime($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
			}

			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				foreach ($dataList->page->data as $list){
					$list->playerId = $this->d2s($list->playerId);
					$list->newValue = intval($list->newValue);
					$list->oldValue = intval($list->oldValue);
					$list->diffValue = $list->oldValue-$list->newValue;
					$list->createAt = date('Y-m-d H:i:s',$list->createAt/1000);
					$list->type = isset($this->_SilverTypeID[$list->type])?$this->_SilverTypeID[$list->type]:$list->type;
					$list->subType = isset($this->_SilverSubTypeID[$list->subType])?$this->_SilverSubTypeID[$list->subType]:$list->subType;
				}
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏粮食操作日志
	 * 粮食 ： “血量”
	 */
	public function actionUserFoodLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'operationLog/operLog');
			switch ($_GET['type']){
				case '1' :{//用户ID
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperFoodLogById($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '2' :{//用户名
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperFoodLogByName($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '3' :{//时间范围
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryOperFoodLogByTime($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
			}
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				foreach ($dataList->page->data as $list){
					$list->playerId = $this->d2s($list->playerId);
					//$list->logTime="{$list->createAt->year}-{$list->createAt->month}-{$list->createAt->day} {$list->createAt->hour}:{$list->createAt->minute}:{$list->createAt->second}";
					$list->type = isset($this->_foodTypeID[$list->type])?$this->_foodTypeID[$list->type]:$list->type;
					$list->subType = isset($this->_foodSubTypeID[$list->subType])?$this->_foodSubTypeID[$list->subType]:$list->subType;
					//$list->type = isset()?:;
					$list->usedValue = $list->oldValue - $list->newValue;
					$list->createAt = date('Y-m-d H:i:s',$list->createAt/1000);
				}
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
			$this->_view->assign('selected',$selected);
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 用户操作日志
	 */
	public function actionOperLog(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$selected=array();
			$selected['dataMin']=$_GET['dataMin'];
			$selected['dataMax']=$_GET['dataMax'];
			$selected['type']=$_GET['type'];
			$this->getApi()->setUrl($_REQUEST['server_id'],'operationLog/operLog');
			switch ($_GET['type']){
				case '1' :{//用户ID
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperLogById($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '2' :{//用户名
					if ($_GET['dataMin']=='')break;
					$dataList=$this->getApi()->queryOperLogByName($_GET['dataMin'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '3' :{//时间范围
					if ($_GET['dataMin']=='' || $_GET['dataMax']=='')break;
					$dataList=$this->getApi()->queryOperLogByTime($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
					break;
				}
				case '4' :{
					//					$_GET['dataMin']实际上是用户Id
					//					$_GET['dataMax']实际上是关键字
					$dataList=$this->getApi()->queryOperLogByKeyWord($_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);

				}
			}
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				foreach ($dataList->page->data as $list){
					$list->playerId = $this->d2s($list->playerId);
					//$list->logTime="{$list->createAt->year}-{$list->createAt->month}-{$list->createAt->day} {$list->createAt->hour}:{$list->createAt->minute}:{$list->createAt->second}";
					$list->type = isset($this->_foodTypeID[$list->type])?$this->_foodTypeID[$list->type]:$list->type;
					$list->subType = isset($this->_foodSubTypeID[$list->subType])?$this->_foodSubTypeID[$list->subType]:$list->subType;
					//$list->type = isset()?:;
					$list->usedValue = $list->oldValue - $list->newValue;
					$list->actionTime = date('Y-m-d H:i:s',$list->actionTime/1000);
					$list->operateDesc = preg_replace('/<(.+)>/U','<font style="color:#F00">\\1</font>',$list->operateDesc);
				}
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
			$this->_view->assign('selected',$selected);
		}
		$searchType = $this->_searchType;
		$searchType[4] = '关键字搜索';
		$this->_view->assign('searchType',$searchType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionOperationLog(){
		switch($_REQUEST['doaction']){
			case 'ajax':
				$this->_OperationLogajax();
				break;
			case 'reCache':
				$this->_getOperLogType(-1);
				$this->_utilMsg->showMsg('操作成功',1);
				break;
			default:
				$this->_operationLogIndex();
		}
	}

	private function _OperationLogajax(){
		$rpc=$this->getApi();
		$rpc->setUrl($_GET['server_id'],'goldExpendLog/goldExpendLog');
		$data= $rpc->queryGoldExpendLogById($_GET['id'],$_GET['thetime']);
		if(!empty($data->page->data)){
			foreach($data->page->data as $_msg){
				echo "玩家ID：".$_msg->playerName."\n\r";
				echo "昵称：".$_msg->playerName."\n\r";
				echo "消费时间:".date('Y-m-d H:i:s',intval($_msg->createAt/1000))."\n\r";
				echo "消费前元宝数:".$_msg->oldValue."\n\r";
				echo "消费后元宝数:".$_msg->newValue."\n\r";
				echo "元宝变化原因:".$_msg->typeDesc."\n\r";
				echo "元宝变化类型号:".$_msg->subType."\n\r";
				echo "\n\r\n\r";
			}
		}else{
			echo "暂时没有相关信息！";
		}


	}

	private function _getOperLogType($effectiveTime=86400){
		if ($_REQUEST['server_id']){
			//"$dataList" = Array [9]
			//	0 = Object of: xn_domain_xunxia_OperLogTypeDto
			//		rootTypeId = (int) 1
			//		rootTypeName = (string:4) 装备系统	
			//		subTypeList = Array [8]
			//			0 = Object of: xn_domain_OperLogSubType
			//				subTypeName = (string:4) 碎片合成	
			//				subTypeId = (int) 10008
			//			1 = Object of: xn_domain_OperLogSubType
			//			2 = Object of: xn_domain_OperLogSubType
			//			3 = Object of: xn_domain_OperLogSubType
			//			4 = Object of: xn_domain_OperLogSubType
			//			5 = Object of: xn_domain_OperLogSubType
			//			6 = Object of: xn_domain_OperLogSubType
			//			7 = Object of: xn_domain_OperLogSubType
			//	1 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	2 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	3 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	4 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	5 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	6 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	7 = Object of: xn_domain_xunxia_OperLogTypeDto
			//	8 = Object of: xn_domain_xunxia_OperLogTypeDto
			$OperLogType = $this->_f('xunxia_oper_log_type','',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($OperLogType){
				return $OperLogType;
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'operateLogtype/getoperlogtype');
			$dataList = $this->getApi()->getOperateLogType();
			if ($dataList && !$dataList instanceof PHPRPC_Error){
				$OperLogType = array();
				if($dataList && is_array($dataList)){
					foreach($dataList as $root){
						if(is_object($root)){
							$OperLogType[$root->rootTypeId]['rootTypeName'] = $root->rootTypeName;
							if($root->subTypeList && is_array($root->subTypeList))
							foreach($root->subTypeList as $sub){
								$OperLogType[$root->rootTypeId]['subTypeList'][$sub->subTypeId] = $sub->subTypeName;
							}
						}elseif(is_array($root)){
							$OperLogType[$root['rootTypeId']]['rootTypeName'] = $root['rootTypeName'];
							if($root['subTypeList'] && is_array($root['subTypeList']))
							foreach($root['subTypeList'] as $sub){
								$OperLogType[$root['rootTypeId']]['subTypeList'][$sub['subTypeId']] = $sub['subTypeName'];
							}
						}
					}
				}
				$this->_f('xunxia_oper_log_type',$OperLogType);	//缓存数据数据
				return $OperLogType;
			}else{
				return false;
			}
		}else{
			$this->_utilMsg->showMsg('请选择服务器');
		}


	}

	/**
	 * 新用户操作日志列表
	 */
	private function _operationLogIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();


		if ($_REQUEST['server_id']){

			//			$LogRoot = $this->_getGlobalData( 'game_player_log_root_'.self::XUN_XIA_ID );
			//			if(!$LogRoot){
			//				$LogRoot = array();
			//			}
			//			$LogTpl = $this->_getGlobalData( 'game_player_log_tpl_'.self::XUN_XIA_ID );
			//			if(!$LogTpl){
			//				$LogTpl = array();
			//			}
			//			$RootSlt[0] = Tools::getLang('ALL','Common');
			//			$TypeSlt[0] = Tools::getLang('ALL','Common');
			//			foreach($LogRoot as $sub){
			//				$RootSlt[$sub['rootid']] = $sub['rootname'];
			//			}
			//			$this->_view->assign('RootSlt',$RootSlt);
			//			foreach($LogTpl as $sub){
			//				$TypeSlt[$sub['rootid']][$sub['typeid']] = $sub['typename'];
			//			}
			//			$this->_view->assign('TypeSlt',json_encode($TypeSlt));

			$RootSlt[0] = Tools::getLang('ALL','Common');
			$TypeSlt = array();
			$OperLogType = $this->_getOperLogType();
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

			$account = '';
			$name = trim($_GET['name']);
			$playerId= doubleval($_GET['playerId']);
			$StartTime = trim($_GET['StartTime']);
			$EndTime= trim($_GET['EndTime']);
			$keyword= trim($_GET['keyword']);
			$rootid= intval($_GET['rootid']);
			$typeid= intval($_GET['typeid']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'operationLog/operLog');
			if($_REQUEST['submit']){
				//				if(empty($playerId) && empty($name)){
				//					$this->_utilMsg->showMsg('need: name or playerId',-1);
				//				}
				$dataList = $rpc->queryOperLogBySomeKeyInfo($account,$name,$StartTime,$EndTime,$playerId,$keyword,$rootid,$typeid,$_GET['page'],PAGE_SIZE);
				if($dataList instanceof PHPRPC_Error){
					$this->_view->assign('ConnectErrorInfo',$dataList->Message);
				}elseif (is_object($dataList)){
					$this->_loadCore('Help_Page');
					foreach ($dataList->page->data as $list){
						$list->playerId = $this->d2s($list->playerId);
						$list->actionTime = date('Y-m-d H:i:s',$list->actionTime/1000);
						$list->operateDesc = preg_replace('/<(.+)>/U','<font style="color:#F00">\\1</font>',$list->operateDesc);
					}
					$this->_view->assign('dataList',$dataList->page->data);
					$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$this->_helpPage->show());
				}
			}

			$URL_ReCacheLogType = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'reCache','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('URL_ReCacheLogType',$URL_ReCacheLogType);
		}
		$this->_view->assign('URL_AJAX',Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'ajax','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('selected',$_GET);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 游戏登录
	 */
	public function actionGameLogin(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$serverList=$this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);

		if ($this->_isPost()){
			$rpc=$this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'playerLogin/playerlogin');
			$data= $rpc->loginToServer($_POST['user_name']);
			if($data->result==0){
				header('Location: '.$data->url."&platform=".$_POST["platform"]);
			}else{
				echo 'No User!';
			}
			exit();
		}else {
			if ($_GET['operator_id']){
				foreach ($serverList as $key=>&$value){
					if ($value['operator_id']!=$_GET['operator_id'])unset($serverList[$key]);
				}
				$this->_view->assign('dataList',$serverList);
			}
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}

	}

	/**
	 * 用户查询
	 */
	public function actionUserQuery(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$account = trim($_GET['account']);
			$playername = trim($_GET['playerName']);
			$registerTimeStart = trim($_GET['registerTimeStart']);
			$registerTimeEnd = trim($_GET['registerTimeEnd']);
			$loginTimeStart = trim($_GET['loginTimeStart']);
			$loginTimeEnd = trim($_GET['loginTimeEnd']);
			$playerId = trim($_GET['playerId']);
			$rpc=$this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'player/player');
			$dataList= $rpc->queryPlayerByUserInfo($account,$playername,$registerTimeStart,$registerTimeEnd,$loginTimeStart,$loginTimeEnd,$playerId,$_GET['page'],PAGE_SIZE);

			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif (is_object($dataList)){
				$this->_loadCore('Help_Page');
				$serverList = $this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
				foreach ($dataList->page->data as $list){
					$list->playerId = $this->d2s($list->playerId);
					$bugParam = array(
						'game_type_id'=>self::XUN_XIA_ID,
						'operator_id'=>$serverList[$_REQUEST['server_id']]['operator_id'],
						'game_server_id'=>$_REQUEST['server_id'],
						'game_user_id'=>$list->playerId,
						'user_account'=>$list->userAccount,
						'user_nickname'=>$list->playerName,
					);
					$list->URL_Bug = Tools::url('Verify','OrderVerify',$bugParam);
					//					$list->actionTime = date('Y-m-d H:i:s',$list->actionTime/1000);
				}
				$URL_Opt['sendMsg'] = Tools::url('XunXiaSysManage','SendMsg',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$URL_Opt['resUserAdd'] = Tools::url('XunXiaSysManage','ResUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$URL_Opt['talkUserAdd'] = Tools::url('XunXiaSysManage','TalkUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$URL_Opt['lockUser'] = Tools::url('XunXiaSysManage','LockUserByBatch',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
				$this->_view->assign('URL_Opt',$URL_Opt);
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 充值查询
	 */
	public function actionDepositList(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$inpourType = array(
		0=>'所有',
		1=>'系统赠送元宝',
		2=>'充值获得元宝',
		);
		if ($_REQUEST['server_id']){
			$_GET['playerId'] = trim($_GET['playerId']);
			$_GET['playerName'] = trim($_GET['playerName']);
			$_GET['userAccount'] = trim($_GET['userAccount']);
			$_GET['startTime'] = trim($_GET['startTime']);
			$_GET['endTime'] = trim($_GET['endTime']);
			$_GET['inpourType'] = intval($_GET['inpourType']);
			$_GET['transactionId'] = trim($_GET['transactionId']);
			$_GET['page'] = max(1,intval($_GET['page']));
			$_GET['pageSize'] = intval($_GET['pageSize']);
			if($_GET['pageSize']<=0){
				$_GET['pageSize'] = PAGE_SIZE;
			}
			$rpc=$this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'gmGold/gmGold');
			$dataList= $rpc->queryUserGoldChargeInfo($_GET['playerId'],$_GET['playerName'],$_GET['userAccount'],$_GET['startTime'],$_GET['endTime'],$_GET['transactionId'],$_GET['inpourType'],$_GET['page'],$_GET['pageSize']);
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif (is_object($dataList)){
				$pageMoneyTotal = 0;
				foreach($dataList->page->data as $list){
					$pageMoneyTotal += round($list->money,2);
					$list->uid = $this->d2s($list->uid);
					$list->inpourType = isset($inpourType[$list->inpourType])?$inpourType[$list->inpourType]:$list->inpourType;
				}
				$this->_view->assign('pageMoneyTotal',$pageMoneyTotal);
				$this->_loadCore('Help_Page');
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('inpourType',$inpourType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 充值查询
	 */
	public function actionDepositListQQ(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		$inpourType = array(
		0=>'所有',
		1=>'系统赠送元宝',
		2=>'充值获得元宝',
		);
		if ($_REQUEST['server_id']){
			$_GET['playerId'] = trim($_GET['playerId']);
			$_GET['playerName'] = trim($_GET['playerName']);
			$_GET['userAccount'] = trim($_GET['userAccount']);
			$_GET['startTime'] = trim($_GET['startTime']);
			$_GET['endTime'] = trim($_GET['endTime']);
			$_GET['inpourType'] = intval($_GET['inpourType']);
			$_GET['transactionId'] = trim($_GET['transactionId']);
			$_GET['page'] = max(1,intval($_GET['page']));
			$_GET['pageSize'] = intval($_GET['pageSize']);
			if($_GET['pageSize']<=0){
				$_GET['pageSize'] = PAGE_SIZE;
			}
			$rpc=$this->getApi();
			//			$rpc->setUrl($_REQUEST['server_id'],'gmGold/gmGold');
			//			$dataList= $rpc->queryUserGoldChargeInfo($_GET['playerId'],$_GET['playerName'],$_GET['userAccount'],$_GET['startTime'],$_GET['endTime'],$_GET['transactionId'],$_GET['inpourType'],$_GET['page'],$_GET['pageSize']);
			$rpc->setUrl($_REQUEST['server_id'],'buyLog/buyLogRecord');
			$dataList= $rpc->getUserBuyLog($_GET['playerId'],$_GET['playerName'],$_GET['startTime'],$_GET['endTime'],$_GET['transactionId'],$_GET['page'],$_GET['pageSize']);

			//"$dataList" = Object of: xn_util_QueryResultInfo
			//	page = Object of: xn_util_Page
			//		totalCount = (double) 2
			//		start = (double) 0
			//		data = Array [2]
			//			0 = Object of: xn_domain_xxgm_BuyLogVo
			//				playerName = (string:2) 曹笑	
			//				payitem = null
			//				status = null
			//				playerid = (double) 111
			//				id = null
			//				amt = null
			//				token = null
			//				rmb = (double) 0.1
			//				create_time = (double) 1.315207656E+012
			//				device = null
			//				openid = null
			//				server = null
			//				gold = (int) 11
			//				billno = (string:19) 2011-09-05sfsdfgdfg
			//			1 = Object of: xn_domain_xxgm_BuyLogVo
			//		pageSize = (int) 20
			//	code = (int) 0

			//getUserBuyLog(String playerId,String playerName,String startTime,String endTime,String transactionId,int pageNo, int pageSize);
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif (is_object($dataList)){
				$pageMoneyTotal = 0;
				foreach($dataList->page->data as $list){
					$pageMoneyTotal += round($list->rmb,2);
					$list->playerid = $this->d2s($list->playerid);
					$list->inpourType = isset($inpourType[$list->inpourType])?$inpourType[$list->inpourType]:$list->inpourType;
				}
				$this->_view->assign('pageMoneyTotal',$pageMoneyTotal);
				$this->_loadCore('Help_Page');
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('inpourType',$inpourType);
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
	 * 删除封号
	 */
	private function _lockUserDel(){
		$playerId = (array)$_REQUEST['playerId'];
		if($playerId){
			// 			print_r($playerId);
			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			$dataList=$this->getApi()->deleteForbidden($playerId);
			print_r($dataList);//exit;

			if($dataList == 0){
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

	public function _lockUserIndex(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			if(empty($_GET["page"])){$_GET["page"]=1;}

			$dataList=$this->getApi()->queryForbidden($_GET["page"],PAGE_SIZE);
			// 			print_r($dataList);

			// 			foreach($dataList->page->data as $value){
			// 				echo $value->succInsertIpList;
			// 				$value->succInsertIpList	=	str_replace(",","<br>",$value->succInsertIpList);
			// 			}
			foreach($dataList->page->data as &$sub){
				$id =  $sub->id;
				$sub->URL_Detail = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'playerId'=>$id,'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
				$sub->URL_TimeEnd = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'del','playerId'=>$id,'server_id'=>$_REQUEST['server_id']));
			}

			// 			print_r($dataList->page->data);
			// 			$this->_view->assign('dataList',$dataList['plist']);

			$this->_loadCore('Help_Page');
			$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
			$this->_view->assign('dataList',$dataList->page->data);
			$this->_view->assign('pageBox',$this->_helpPage->show());
		}
		$UrlLockUserAdd = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_view->assign('UrlLockUserAdd',$UrlLockUserAdd);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 添加封号
	 */
	private function _lockUserAdd(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();

		// 		print_r($_REQUEST);exit;
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['cause'] = trim($_POST['cause']);
			$_POST['users'] = trim($_POST['users']);
			$post['forbidlogin'] = $_POST['EndTime'];

			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			$dataList=$this->getApi()->saveForbidden($_POST['users'],$post['forbidlogin']);
			// 				print_r($dataList);exit;
			if($dataList == 0){
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
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}

		}
		$UrlLockUserDel = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_view->assign('UrlLockUserDel',$UrlLockUserDel);
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaUser/LockUserAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	//配置:元宝类型
	private $_goldTypeID = array(
		'1'=>'武将',
		'2'=>'内政',
		'3'=>'商城购买',
		'4'=>'军事',
		'5'=>'帮派',
		'6'=>'其他',
		'7'=>'元宝获得',
	);

	//配置:元宝子类型
	private $_goldSubTypeID = array(
		'10001'=>'修炼武将',
		'10002'=>'修炼星级刷新',
		'10003'=>'结束修炼',
		'10004'=>'金币激发',
		'10005'=>'元宝洗髓',
		'10006'=>'购买修炼位',
		'10007'=>'购买武魂修炼师',
		'10008'=>'购买招募位置',
		'10009'=>'激发潜能CD冷却',
		'20001'=>'加强探索',
		'20002'=>'探索CD冷却',
		'20003'=>'刷新每日任务',
		'20004'=>'任务立即完成',
		'20005'=>'当铺兑换',
		'20006'=>'摇钱CD冷却',
		'20007'=>'打造装备CD冷却',
		'20008'=>'100%打造成功',
		'20009'=>'购买运镖队列',
		'20010'=>'刷新镖队数量',
		'20011'=>'抢劫镖队次数',
		'20012'=>'镖车CD冷却',
		'20013'=>'刷新镖队',
		'20014'=>'出版经验提升',
		'20015'=>'购买出版次数',
		'20016'=>'购买书籍商人',
		'20017'=>'出版CD冷却',
		'20018'=>'天赋CD冷却',
		'20019'=>'购买天赋升级位',
		'20020'=>'设置纺织模型',
		'20021'=>'买纺织次数',
		'30074'=>'灵魂之靴',
		'30075'=>'圣天鞋',
		'30076'=>'降妖靴',
		'30077'=>'战魂之靴',
		'30078'=>'烈日战靴',
		'30079'=>'追魂鞋',
		'30080'=>'天魔护靴',
		'30081'=>'战神天靴',
		'30082'=>'伏魔鞋',
		'30083'=>'飞龙战靴',
		'30084'=>'烈焰之鞋',
		'30085'=>'天神战靴',
		'30086'=>'斩月靴',
		'30087'=>'金丝鞋',
		'30088'=>'无极战靴',
		'30089'=>'弑神战靴',
		'30090'=>'紫金长靴',
		'30091'=>'降龙战鞋',
		'30092'=>'武神战靴',
		'34001'=>'购买铁箱子',
		'34002'=>'购买铜箱子',
		'34003'=>'购买银箱子',
		'34004'=>'购买金箱子',
		'40001'=>'免战购买',
		'40002'=>'强攻精英部队',
		'40003'=>'组队强攻',
		'40004'=>'银矿战侦查',
		'40005'=>'元宝鼓舞',
		'40006'=>'精力购买',
		'40007'=>'精力CD冷却',
		'40008'=>'打开藏宝图',
		'40009'=>'战役CD冷却',
		'50001'=>'修改帮派名',
		'50002'=>'升级帮徽',
		'60001'=>'全服发言',
		'60002'=>'温泉CD冷却',
		'60003'=>'经脉CD冷却',
		'60004'=>'背包开格',
		'70001'=>'充值获得',
		'70002'=>'礼包获得',
		'70003'=>'摇钱获得',
		'70004'=>'其他来源',	
	);

	//配置:血量类型
	private $_foodTypeID = array(
		'1' =>'血量消耗',
		'2' =>'血量增加',
	);

	//配置:血量子类型
	private $_foodSubTypeID = array(
		'10001'=>'组队打npc减少血量',
		'10002'=>'玩家打团战减少血量',
		'10003'=>'攻打npc时',
		'20001'=>'购买血量',
		'20002'=>'银矿出征时补的血量',
		'20003'=>'炼丹炉得到的血量',
		'20004'=>'系统发卡',
		'20005'=>'免费补血',
		'20006'=>'温泉疗伤',
	);

	//配置：铜钱类型
	private $_SilverTypeID = array(
		'1'=>'获得铜钱',
		'2'=>'铜钱消耗',
	);

	//配置：铜钱子类型
	private $_SilverSubTypeID = array(
		'10001'=>'摇钱所得',
		'10002'=>'摇钱树收益',
		'10003'=>'任务奖励',
		'10004'=>'活动奖励',
		'10005'=>'声望奖励',
		'10006'=>'新手奖励',
		'10007'=>'低级别成长奖励',
		'10008'=>'系统奖励',
		'10009'=>'降级装备',
		'10010'=>'售卖装备',
		'10011'=>'徒弟上缴',
		'10012'=>'书籍出版',
		'10013'=>'海岛战斗',
		'10014'=>'战役奖励',
		'10015'=>'宝箱所得',
		'10016'=>'摧毁所得',
		'10017'=>'当铺所得',
		'10018'=>'打劫镖队',
		'10019'=>'随机国家奖励',
		'10020'=>'膘队获利',
		'20001'=>'打造装备',
		'20002'=>'购买装备',
		'20003'=>'捐赠',
		'20004'=>'帮派捐献',
		'20005'=>'家将修炼',
		'20006'=>'购买血量',
		'20007'=>'升级天赋',
		'20008'=>'招募武将',
		'20009'=>'探索',	
	);



}