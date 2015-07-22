<?php
Tools::import('Control_BaseGm');

class Control_MasterFRG extends BaseGm {

	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;

	/**
	 * Util_Msg;
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * operator_id
	 * @var int
	 */
	private $_operatorId;

	/**
	 * Model_ApplyDataFrg
	 * @var Model_ApplyDataFrg
	 */
	private $_modelApplyDataFrg;

	/**
	 * Util_FRGTools
	 * @var Util_FRGTools
	 */
	private $_utilFRGTools;

	/**
	 * Model_PayCard
	 * @var Model_PayCard
	 */
	private $_modelPayCard;

	/**
	 * Model_FrgLog
	 * @var Model_FrgLog
	 */
	private $_modelFrgLog;

	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * 游戏后台操作日志
	 * @var ModelGameOperateLog
	 */
	private $_modelGameOperateLog;

	public function __construct(){
		$this->game_id=2;
		$this->_createView();
		$this->_createUrl();
		$this->_checkOperatorAct();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}

	private function _createUrl(){
		$this->_url['MasterFRG_UserInquire']=Tools::url(CONTROL,'UserInquire');
		$this->_url['MasterFRG_NoticeDel']=Tools::url(CONTROL,'Notice',array('doaction'=>'del'));
		$this->_url['MasterFRG_NoticeAdd']=Tools::url(CONTROL,'Notice',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['OperationFRG_Notice_serverSyn']=Tools::url('OperationFRG','Notice',array('doaction'=>'serverSyn'));
		$this->_url['MasterFRG_AddDonttalk']=Tools::url(CONTROL,'Donttalk',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_DelDonttalk']=Tools::url(CONTROL,'Donttalk',array('doaction'=>'del'));
		$this->_url['MasterFRG_ClearOuttalk']=Tools::url(CONTROL,'Donttalk',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'clear'));	//清除过时禁言
		$this->_url['MasterFRG_AddReward']=Tools::url(CONTROL,'Reward',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_DelReward']=Tools::url(CONTROL,'Reward',array('doaction'=>'del'));
		$this->_url['MasterFRG_EditReward']=Tools::url(CONTROL,'Reward',array('doaction'=>'edit'));
		$this->_url['OperationFRG_RewardServerSyn']=Tools::url('OperationFRG','Reward',array('doaction'=>'serversyn'));
		$this->_url['MasterFRG_AddLockUsers']=Tools::url(CONTROL,'LockUsers',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_DelLockUsers']=Tools::url(CONTROL,'LockUsers',array('doaction'=>'del'));
		$this->_url['MasterFRG_RewardBefore']=Tools::url(CONTROL,'RewardBefore');
		$this->_url['MasterFRG_KickUser']=Tools::url(CONTROL,'KickUser');
		$this->_url['MasterFRG_SendMail']=Tools::url(CONTROL,'SendMail');
		$this->_url['MasterFRG_AllSendMail']=Tools::url(CONTROL,'AllSendMail');

		$this->_url['MasterFRG_UserData']=Tools::url(CONTROL,'UserData',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserEmployeeTune']=Tools::url(CONTROL,'UserEmployeeTune',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserCarTune']=Tools::url(CONTROL,'UserCarTune',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserFactory']=Tools::url(CONTROL,'UserFactory',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserCofcTune']=Tools::url(CONTROL,'UserCofcTune',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_AddCard']=Tools::url(CONTROL,'AddCard',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_ImportCard']=Tools::url(CONTROL, 'ImportCard',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_DelInvalidCard']=Tools::url(CONTROL,'ImportCard',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'invalid_delete'));
		$this->_url['MasterFRG_CardRest']=Tools::url(CONTROL,'ImportCard',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'card_reset'));
		$this->_url['MasterFRG_CardInvalid']=Tools::url(CONTROL,'ImportCard',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'invalid_delete'));
		$this->_url['MasterFRG_PayLibao_add']=Tools::url(CONTROL,'PayLibao',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_PayLibao_edit']=Tools::url(CONTROL,'PayLibao',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
		$this->_url['MasterFRG_PayLibao_del']=Tools::url(CONTROL,'PayLibao',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_PayLibao_proportion']=Tools::url(CONTROL,'PayLibao',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'proportion'));
		$this->_url['OperationFRG_PayLibao_serverSyn']=Tools::url(CONTROL,'PayLibao',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'serverSyn'));
		$this->_url["MasterFRG_PayCard_add"]=Tools::url(CONTROL,'PayCard',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Drillmaster_Add']=Tools::url(CONTROL,'Drillmaster',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Drillmaster_Del']=Tools::url(CONTROL,'Drillmaster',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_Activity_Add']=Tools::url(CONTROL, 'Activity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Activity_Edit']=Tools::url(CONTROL, 'Activity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
		$this->_url['MasterFRG_Activity_Del']=Tools::url(CONTROL, 'Activity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_SpecialActivity_Add']=Tools::url(CONTROL,'SpecialActivity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_SpecialActivity_serverSyn']=Tools::url(CONTROL,'SpecialActivity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'serverSyn'));
		$this->_url['MasterFRG_SpecialActivity_del']=Tools::url(CONTROL,'SpecialActivity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));

		$this->_url['MasterFRG_Libao_del']=Tools::url(CONTROL,'Libao',array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_Libao_add']=Tools::url(CONTROL,'Libao',array('doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['OperationFRG_Libao_syncard']=Tools::url('OperationFRG','Libao',array('doaction'=>'syn_card'));
		$this->_url['OperationFRG_Libao_serverSyn']=Tools::url('OperationFRG','Libao',array('doaction'=>'serverSyn'));
		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 建立服务器选择列表
	 */
	private function _createServerList(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('server/server_list_'.$this->game_id);
		$this->timeZone($this->game_id);
		unset($gameServerList[100],$gameServerList[200]);
		foreach ($gameServerList as &$server){
			unset($server['game_type_id'],$server['room_id'],$server['marking'],$server['time_zone']);
		}
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','MasterFRG/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_view->assign('selectedServerUrl',$gameServerList[$_REQUEST['server_id']]['server_url']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}
	}

	/**
	 * 员工店铺列表
	 */
	public function actionEmpShopList(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'EmpShopList','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$this->_view->assign('data',$data['data']);
				$this->_loadCore('Help_Page');
				if ($data['data']['Query']['TypeItems']==1){//员工
					foreach ($data['data']['Data'] as &$list){
						$list['Sex']=$list['Sex']?Tools::getLang('WORWAN','Common'):Tools::getLang('MAN','Common');
						$list['Deleted']=$list['Deleted']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
					}
				}elseif ($data['data']['Query']['TypeItems']==2){//店铺
					foreach ($data['data']['Data'] as &$list){
						$list['ShopTypeId']=$data['data']['ShopTypes'][$list['IndustryId']][$list['ShopTypeId']]['Name'];
						$list['IndustryId']=$data['data']['Industry'][$list['IndustryId']];
					}

				}elseif ($data['data']['Query']['TypeItems']==3){//道具
					$wordGetType=Tools::getLang('EMPSHOPLIST_GETTYPE',__CLASS__);
					$wordUserStatus=array('0'=>Tools::getLang('NOT_USE','Common'),'1'=>Tools::getLang('USE','Common'));
					$wordDelete=array('0'=>Tools::getLang('NO','Common'),'1'=>Tools::getLang('YES','Common'));
					foreach ($data['data']['Data'] as &$list){
						$list['gettype']=$wordGetType[$list['gettype']];
						$list['UseMode']=$wordUserStatus[$list['UseMode']];
						$list['gettime']=date('Y-m-d H:i:s',$list['gettime']);
						$list['Deleted']=$wordDelete[$list['Deleted']];
					}
				}
				$this->_view->assign('selectDel',array('0'=>Tools::getLang('NO','Common'),'1'=>Tools::getLang('YES','Common'),'2'=>Tools::getLang('ALL','Common')));
				$this->_view->assign('dataList',$data['data']['Data']);
				$currUrl = Tools::url ( CONTROL, ACTION, $sendParams );
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl ) );
					$this->_view->assign('pageBox',$helpPage->show());
				}
			}
			$this->_view->assign('pageSize',Tools::getLang('PAGE_OPTION',__CLASS__));
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 用户查询
	 */
	public function actionUserInquire(){
		switch ($_GET['doaction']){
			case 'message' :{//消息
				$this->_userInquireMsg();
				return ;
			}
			default:{
				$this->_userInquireIndex();
				return ;
			}
		}

	}

	/**
	 * 用户消息搜索
	 */
	private function _userInquireMsg(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'MsgList','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$currUrl = Tools::url ( CONTROL, 'UserInquire', $sendParams );
				if ($data['data']['Data']){
					foreach ($data['data']['Data'] as &$value){
						$value['content']=strip_tags($value['content']);
						$value['sendtime']=date('Y-m-d H:i:s',$value['sendtime']);
						$value['flag']=$value['flag']?Tools::getLang('READ','Common'):Tools::getLang('NOT_READ','Common');
						$value['delr']=$value['delr']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
						$value['dels']=$value['delr']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
						$value['IsEvaluated']=$value['IsEvaluated']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
						$value['title']=stripcslashes($value['title']);
						$value['content']=stripcslashes($value['content']);
					}
				}
				$this->_loadCore('Help_Page');
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl ) );
					$this->_view->assign('pageBox',$helpPage->show());
				}

				$data['data']['Query']['PageSize']=$data['data']['PageSize'];
				$this->_view->assign('selectedArr',$data['data']['Query']);
				$selectPage=Tools::getLang('PAGE_OPTION',__CLASS__);
				$this->_view->assign('selectPage',$selectPage);
				$this->_view->assign('selectItems',$data['data']['Items']);
				$this->_view->assign('selectTypeItem',$data['data']['TypeItem']);
				$this->_view->assign('selectTypeItem2',$data['data']['TypeItem2']);
				$this->_view->assign('dataList',$data['data']['Data']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/UserInquireMsg.html'));
		$this->_view->display();
	}

	private function _userInquireIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserQuery','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$currUrl = Tools::url ( CONTROL, 'UserInquire', $sendParams );
				if ($data['data']['list']){
					foreach ($data['data']['list'] as &$value){
						$value['url_ask']=Tools::url('Verify','OrderVerify',array(
																			'game_type_id'=>2,
																			'operator_id'=>$this->_operatorId,
																			'game_server_id'=>$_REQUEST['server_id'],
																			'game_user_id'=>$value['UserId'],
																			'user_account'=>$value['UserName'],
																			'user_nickname'=>$value['VUserName'],));

						$value['url_emp']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>1,'PageSize'=>10));
						$value['url_shop']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>2,'PageSize'=>10));
						$value['url_tools']=Tools::url(CONTROL,'EmpShopList',array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>3,'PageSize'=>10));
						$value['url_msg']=Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'PageSize'=>10,'doaction'=>'message'));
						$value['url_send_msg']=Tools::url(CONTROL,'SendMail',array('server_id'=>$_REQUEST['server_id'],'UserId[]'=>$value['Id'],'lock'=>true,'user_name'=>$value['UserName'],'nick_name'=>$value['VUserName']));
						$value['url_event_list']=Tools::url(CONTROL,'EventList',array('server_id'=>$_REQUEST['server_id'],'UserId'=>$value['Id'],));
					}
				}
				$this->_loadCore('Help_Page');
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl ) );
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$data['data']['query']['Items']=$data['data']['query']['Items']?$data['data']['query']['Items']:'9';//默认角色名称
				$selectPage=Tools::getLang('PAGE_OPTION',__CLASS__);
				$this->_view->assign('selectPage',$selectPage);
				$this->_view->assign('select',$data['data']['Items']);
				$this->_view->assign('dataList',$data['data']['list']);
				$this->_view->assign('selectedQuery',$data['data']['query']);
				$this->_view->assign('selectedPageSize',$data['data']['PageSize']);
				$this->_view->assign('companyNum',$data['data']['CompanyNum']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}

		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 踢人
	 */
	public function actionKickUser(){
		if ($this->_isPost()){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'Kick'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data['msgno']==1){
				$this->_utilMsg->showMsg($data['message'],1);
			}else {
				$this->_utilMsg->showMsg($data['message'],-2);
			}
		}
	}
	
	public function actionEventList(){
		$this->_createServerList();		
		if ($_REQUEST['server_id']){
			strtotime($_POST['EndTime']);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('Page'));
			$this->_utilFRGInterface->setGet(array('c'=>'EventList','a'=>'ShowList','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
		
			$theData = $data['data'];
			if(isset($theData) ){
				
				$theData['QueryString']['server_id'] = $_REQUEST['server_id'];
				if($theData['QueryString']['StartTime'] != 0){
					$theData['QueryString']['StartTime'] = date('Y-m-d H:i:s',$theData['QueryString']['StartTime']);
				}
				if($theData['QueryString']['EndTime'] != 0){
					$theData['QueryString']['EndTime'] = date('Y-m-d H:i:s',$theData['QueryString']['EndTime']);
				}
				$currUrl = Tools::url ( CONTROL, 'EventList', $theData['QueryString'] );
				$this->_loadCore('Help_Page');
				$helpPage = new Help_Page ( array ('total' => $theData['TotalNum'], 'perpage' => ($theData['PageSize'] ), 'url' => $currUrl ) );
				$TableIndex=array('1'=>bto_eventlog_1,'2'=>bto_eventlog_2,'3'=>bto_eventlog_3,'4'=>bto_eventlog_4,'5'=>bto_eventlog_5,'6'=>bto_eventlog_6,'7'=>bto_eventlog_7,'8'=>bto_eventlog_8,'9'=>bto_eventlog_9,'10'=>bto_eventlog_10);
				$ReadStat=array('0'=>Tools::getLang('ALL','Common'),'1'=>Tools::getLang('NO','Common'),'2'=>Tools::getLang('YES','Common'),);
				$Deleted = array('-1'=>Tools::getLang('ALL','Common'),'0'=>Tools::getLang('NO','Common'),'1'=>Tools::getLang('YES','Common'));
				$this->_view->assign('TableIndex',$TableIndex);	
				$this->_view->assign('ReadStat',$ReadStat);
				$this->_view->assign('Deleted',$Deleted);
				$this->_view->assign('QueryString',$theData['QueryString']);
				$this->_view->assign('DataList',$theData['list']);
				$this->_view->assign('EventTypes',$theData['EventTypes']);
				$this->_view->assign('TotalPage',$theData['TotalPage']);
				$this->_view->assign('TotalNum',$theData['TotalNum']);
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('display',true);
			}
			else{
				exit('remote message:'.$data['message']);
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 全服发送短信
	 */
	public function actionAllSendMail(){
		$this->_createServerList();
		if ($this->_isPost()){
			set_time_limit(200);
			Tools::import('Util_ApiFrg');
			$this->_utilApiFrg=new Util_ApiFrg();
			$sendParams=Tools::getFilterRequestParam();
			$get=array('c'=>'Reward','a'=>'SendMail','doaction'=>'save');
			$_POST['IsAll']=1;//全服
			$_POST['IsApi']=1;//API接口
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],$get,$_POST);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
//			while (true){//循环发送
//				if (!$data['params']['url'])break;
//				unset($this->_utilApiFrg);
//				$this->_utilApiFrg=new Util_ApiFrg();
//				$this->_utilApiFrg->addUrl($data['params']['url'],null,$_POST);
//				$this->_utilApiFrg->send();
//				$data=$this->_utilApiFrg->getResult();
//			}
			if($data){
				if ($data['params']['url']){
					$this->_utilMsg->createNavBar();
					$this->_view->assign('sending',1);
					$this->_view->assign('message',$data['message']);
					$this->_view->assign('cause',$_POST['cause']);
					$this->_view->assign('MsgTitle',$_POST['MsgTitle']);
					$this->_view->assign('MsgContent',$_POST['MsgContent']);
					$this->_view->display();
				}else{
					$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
					$this->_modelFrgLog->add($_POST,7); //全服发送短信.
					$this->_utilMsg->showMsg(Tools::getLang('RETURN_MESSAGE','Common').':'.$data['message']);
				}				
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'));
			}
		}else {
			$this->_utilMsg->createNavBar();
			$this->_view->assign('sending',0);
			$this->_view->display();
		}
	}

	/**
	 * 群发短信
	 */
	public function actionSendMail(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost() && $_POST['submit']){//提交表单
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'SendMail','doaction'=>'save'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						$this->_modelFrgLog->add($_POST,4);
						if ($data['backparams'] && is_array($data['backparams'])){
							$data['message'].=Tools::getLang('SEND_ERRORMSG',__CLASS__).implode('，',$data['backparams']).'</b>';
						}
						$this->_utilMsg->showMsg($data['message'],1,1,null);
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,2);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		if ($_REQUEST['UserId'])$this->_view->assign('changeUsers',implode(',',$_REQUEST['UserId']));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 公告
	 */
	public function actionNotice(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_noticeAdd();
				return ;
			}
			case 'edit' :{
				$this->_noticeEdit();
				return ;
			}
			case 'del' :{
				$this->_noticeDel();
				return ;
			}
			default:{
				$this->_noticeIndex();
				return ;
			}
		}
	}

	private function _noticeEdit(){
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'Add','doaction'=>'saveadd'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
				}else {
					$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'doaction'=>'edit')));
				}
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'Add','Id'=>$_GET['Id']));
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('dataList',$data['data']['NoticeVal']);
			$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/NoticeEdit.html'));
		$this->_view->display();
	}

	private function _noticeAdd(){
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'Add','doaction'=>'saveadd'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
				}else {
					$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add')));
				}
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'Add'));
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/NoticeAdd.html'));
		$this->_view->display();
	}

	private function _noticeDel(){
		if ($_REQUEST['server_id']){
			if (!$_REQUEST['Id'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELNOTICE',__CLASS__),-2);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$sendParams['doaction']='delete';
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}

	private function _noticeIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$currUrl = (Tools::url ( CONTROL, 'UserInquire', $sendParams ));
				if ($data['data']['list']){
					foreach ($data['data']['list'] as &$list){
						$list['url_edit']=Tools::url(CONTROL,ACTION,array('doaction'=>'edit','Id'=>$list['Id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$data['data']['list']);
			}else{
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/NoticeList.html'));
		$this->_view->display();
	}


	/**
	 * 玩家禁言列表
	 */
	public function actionDonttalk(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_donttalkAdd();
				return ;
			}
			case 'del' :{
				$this->_donttalkDel();
				return ;
			}
			case 'clear' :{
				$this->_donttalkClear();
				return ;
			}
			case 'detail' :{
				$this->_donttalkDetail();
				return;
			}
			default:{
				$this->_donttalkIndex();
				return ;
			}
		}


	}

	private function _donttalkClear(){
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'ClearOutDateLock'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}

	private function _donttalkDel(){
		if ($_REQUEST['server_id']){
			if (!$_REQUEST['UserId'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELNOTTALK',__CLASS__),-2);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$sendParams['doaction']='delete';
			$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}

	private function _donttalkAdd(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			if ($this->_isPost() && $_POST['submit']){//提交表单
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'Add','doaction'=>'saveadd'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						$this->_modelFrgLog->add($_POST,1);

						//记录游戏后台新操作日志
						$AddLog = '操作:<font style="color:#F00">禁言</font>';
						$AddLog .= '<br>操作人:<b>{UserName}</b>';
						$AddLog .= '<br>禁言开始时间:'.$_POST['Data']['StartTime'];
						$AddLog .= '<br>禁言结束时间:'.$_POST['Data']['EndTime'];
						$AddLog .= '<br>原因:'.$_POST['cause'];
						$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
						$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($data['backparams']['Exist'],2,$_REQUEST['server_id'],$AddLog);
						if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
							
							foreach($GameOperateLog as $sub){
								$this->_modelGameOperateLog->add($sub);
							}
						}				
						//前端反馈不存在的玩家账号
						if(is_array($data['backparams']['NoExist']) && count($data['backparams']['NoExist'])>0){
							$data['message'].=Tools::getLang('SEND_ERRORMSG',__CLASS__).implode(',',$data['backparams']['NoExist']).'</b>';
						}
						
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])),1);
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add')));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/AddDonttalk.html'));
		$this->_view->display();
	}

	private function _donttalkIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($_POST);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['data']['UserData']){
					foreach ($data['data']['UserData'] as $key=>&$value){
						$value=array_merge($value,$data['data']['UserLockSay'][$key]);
						
						$value['detail_url'] = Tools::url(CONTROL,'Donttalk',array('doaction'=>'detail','game_server_id'=>$_REQUEST['server_id'],'game_user_id'=>$value['UserId']));
						
					}
					$this->_view->assign('dataList',$data['data']['UserData']);
				}
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _donttalkDetail(){
		$_GET['game_server_id'] = intval($_GET['game_server_id']);
		$_GET['game_user_id'] = intval($_GET['game_user_id']);
		$gameSerList = $this->_getGlobalData('gameser_list');
		if(!array_key_exists($_GET['game_server_id'],$gameSerList)){
			exit(Tools::getLang('NO_SERVER','Common'));
		}
		//游戏后台新操作日志
		$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$dataList = $this->_modelGameOperateLog->findGameUserLog($_GET['game_server_id'],$_GET['game_user_id'],2);
		foreach($dataList as &$sub){
			$sub['info'] = unserialize($sub['info']);
			$sub['info'] = $sub['info']['AddString'];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
		}
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('tplServerSelect','MasterFRG/GameOperateLogDetail.html');
		$this->_view->display();
	}

	public function actionReward(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_rewardAdd();
				return ;
			}
			case 'edit' :{
				$this->_rewardEdit();
				return ;
			}
			case 'del' :{
				$this->_rewardDel();
				return ;
			}
			default:{
				$this->_rewardIndex();
				return ;
			}
		}
	}

	/**
	 * 批量发放道具
	 */
	private function _rewardIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'ShowList'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				foreach ($data['data']['Result'] as &$value){
					$value['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
				}
				$this->_view->assign('dataList',$data['data']['Result']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/Reward.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 添加批量发放道具
	 */
	private function _rewardAdd(){
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){//如果选择了服务器将显示
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			$postArr=array();
			$postArr['server_id']=$_POST['server_id'];
			unset($_POST['server_id']);
			$postArr['type']=8;//奖励触发
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['send_action']=array('c'=>'Reward','a'=>'Add','Action'=>'Save');
			$postArr['post_data']=$_POST;
			$this->_modelApplyDataFrg->set_postArr($postArr);
			$data=$this->_modelApplyDataFrg->add();
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
		}else {//显示表单
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$this->_view->assign('objData',json_encode($data['data']['ObjData']));
				$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
				$effData=$data['data']['ObjProp'];
				$newEffData=array();
				foreach ($effData as $key=>$value){
					$newEffData[$key]=array();
					foreach ($value as $childValue){
						$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
						$newEffData[$key][$childValue]['Name']=$data['data']['ObjData'][$key][$childValue]['Name'];
					}
				}
				$this->_view->assign('effData',json_encode($newEffData));
				$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/AddReward.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 奖励详细
	 */
	private function _rewardEdit(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost()){//提交表单
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add','Action'=>'Save'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,'Reward',array('server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add','Id'=>$_GET['Id']));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					Tools::import('Util_FRGTools');
					$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
					$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
					$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
					$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
					$this->_view->assign('data',$data['data']['Reward']);
					$this->_utilFrgTools->setEditResult($data['data']['Reward']);
					$dataResult=$this->_utilFrgTools->getEditResult();
					$this->_view->assign('chageCond',$dataResult['chageCond']);
					$this->_view->assign('chageEffect',$dataResult['chageEffect']);
					$this->_view->assign('chageTool',$dataResult['chageTool']);
					$this->_view->assign('num',$this->_utilFrgTools->getEditNum());
					$everyDay=array('1'=>Tools::getLang('YES','Common'),'0'=>Tools::getLang('NO','Common'));
					$this->_view->assign('everyDayRadio',$everyDay);
					$this->_view->assign('readOnly',$data['data']['ReadOnly']);
					$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/EditReward.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 删除奖励
	 */
	private function _rewardDel(){
		if ($_REQUEST['server_id']){
			if (!$_REQUEST['Ids'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELREWARD',__CLASS__),-2);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'ShowList','Action'=>'Delete'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}

	public function actionLockUsers(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_lockUsersAdd();
				return ;
			}
			case 'del' :{
				$this->_lockUsersDel();
				return ;
			}
			case 'cause' :{
				$this->_lockUserCause();
				return;
			}
			case 'detail' :{
				$this->_lockUserDetail();
				return;
			}
			default:{
				$this->_lockUsersIndex();
				return ;
			}
		}
	}

	/**
	 * 封号管理页面
	 */
	private function _lockUsersIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'LockUser','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($_POST);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
//				
				foreach($data['data']['rv'] as &$sub){
					$sub['detail_url'] = Tools::url(CONTROL,'LockUsers',array('doaction'=>'detail','game_server_id'=>$_REQUEST['server_id'],'game_user_id'=>$sub['UserId']));
				}
				
				$this->_view->assign('dataList',$data['data']['rv']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->assign('URL_LockUserAdd',Tools::url('OperationFRG','LockUserAdd'));
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'MasterFRG/LockUsers.html'));
		$this->_view->display();
	}

	/**
	 * 增加封号用户
	 */
	private function _lockUsersAdd(){

		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			if ($this->_isPost() && $_POST['submit']){//提交表单
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'LockUser','a'=>'Add','doaction'=>'saveadd'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						//记录富人国操作日志
						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						$this->_modelFrgLog->add($_POST,3);
						
						//记录游戏后台新操作日志
						$AddLog = '操作:<font style="color:#F00">封号</font>';
						$AddLog .= '<br>操作人:<b>{UserName}</b>';
						$AddLog .= '<br>封号结束时间:'.$_POST['Data']['EndTime'];
						$AddLog .= '<br>原因:'.$_POST['cause'];
						$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
						$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($data['backparams']['Exist'],1,$_REQUEST['server_id'],$AddLog);
						
						if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
							
							foreach($GameOperateLog as $sub){
								$this->_modelGameOperateLog->add($sub);
							}
						}
						
						if(is_array($data['backparams']['NoExist']) && count($data['backparams']['NoExist'])>0){
							$data['message'].=Tools::getLang('SEND_ERRORMSG',__CLASS__).implode(',',$data['backparams']['NoExist']).'</b>';
						}
						
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,'LockUsers',array('server_id'=>$_REQUEST['server_id'])));
						
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/AddLockUsers.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}


	/**
	 * 删除封号用户
	 */
	private function _lockUsersDel(){
		if ($_REQUEST['server_id']){
			if (!$_REQUEST['UserId'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELUSER',__CLASS__),-2);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$sendParams['doaction']='delete';
			$this->_utilFRGInterface->setGet(array('c'=>'LockUser','a'=>'ShowList'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}
	
	
	/**
	 * 查看封号原因
	 */
	private function _lockUserDetail(){
		//print_r($_GET);
		$_GET['game_server_id'] = intval($_GET['game_server_id']);
		$_GET['game_user_id'] = intval($_GET['game_user_id']);
		$gameSerList = $this->_getGlobalData('gameser_list');

		if(!array_key_exists($_GET['game_server_id'],$gameSerList)){
			exit(Tools::getLang('NO_SERVER','Common'));
		}

		//游戏后台新操作日志
		$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
		$dataList = $this->_modelGameOperateLog->findGameUserLog($_GET['game_server_id'],$_GET['game_user_id'],1);
		foreach($dataList as &$sub){
			$sub['info'] = unserialize($sub['info']);
			$sub['info'] = $sub['info']['AddString'];
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
		}
		$this->_view->assign('dataList',$dataList);
//		$this->_view->assign('tplServerSelect','MasterFRG/GameOperateLogDetail.html');
		$this->_view->set_tpl(array('body'=>'MasterFRG/GameOperateLogDetail.html'));//设置使用自定义模板
		$this->_view->display();
		
	}

	/**
	 * 封锁IP管理
	 */
	public function actionLockIP(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost()){//提交表单
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'CommonVartype','doaction'=>'saveedit'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						$this->_modelFrgLog->add($_POST,2);
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'CommonVartype'));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					$this->_view->assign('data',$data['data']['VarCat']['ForbiddenIPList']);
				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 玩家数值控制
	 */
	public function actionUserData(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['edit_user']){//改变玩家信息
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=2;//玩家数值修改
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'UserData','a'=>'UserTune','doaction'=>'save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}elseif ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
					$auditData=$this->_modelApplyDataFrg->findById($_GET['audit_id']);
					$auditData['post_data']=unserialize($auditData['post_data']);
					$_POST['userflag']='txtids';
					$_POST['txtids']=$auditData['post_data']['userid'];
					$this->_view->assign('auditData',$auditData);
					$this->_view->assign('postData',$auditData['post_data']);
					$this->_view->assign('read',true);
				}
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserTune','doaction'=>'query'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['data']['ErrMsg'][0]){//如果没有记录
						$this->_view->assign('errMsg',$data['data']['ErrMsg'][0]);	//错误消息
					}else {//如果有
						$this->_view->assign('detailTrue',true);	//显示详细
						$this->_view->assign('uo',$data['data']['uo']);	//用户对象
						$this->_view->assign('co',$data['data']['co']);	//用户公司对象
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 用户工厂修改
	 */
	public function actionUserFactory(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=3;//工厂数值修改
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'UserData','a'=>'FactoryTune','doaction'=>'save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}elseif ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
					$auditData=$this->_modelApplyDataFrg->findById($_GET['audit_id']);
					$auditData['post_data']=unserialize($auditData['post_data']);
					$_POST['userflag']='txtids';
					$_POST['txtids']=$auditData['post_data']['userid'];
					$this->_view->assign('auditData',$auditData);
					$this->_view->assign('postData',$auditData['post_data']);

					$this->_view->assign('read',true);
				}
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'FactoryTune','doaction'=>'query'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['data']['ErrMsg'][0]){//如果没有记录
						$this->_view->assign('errMsg',$data['data']['ErrMsg'][0]);
					}else {//如果有
						$this->_view->assign('detailTrue',true);	//显示详细
						$this->_view->assign('uo',$data['data']['uo']);	//用户对象
						$this->_view->assign('fo',$data['data']['fo']);	//用户工厂对象
						$this->_view->assign('selectSysIndustries',$data['data']['SysIndustries']);	//行业
						$this->_view->assign('selectSysGoodsLevel',json_encode($data['data']['SysGoodsLevel']));	//行业等级
						$this->_view->assign('selectSysDeviceLevel',$data['data']['SysDeviceLevel']);	//工厂设备等级 select
						$this->_view->assign('selectSysStoreLevel',$data['data']['SysStoreLevel']);	//仓库等级select
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 商会修改
	 */
	public function actionUserCofcTune(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=6;//商会修改
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'UserData','a'=>'CofcTune','doaction'=>'save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}elseif ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
					$auditData=$this->_modelApplyDataFrg->findById($_GET['audit_id']);
					$auditData['post_data']=unserialize($auditData['post_data']);
					$_POST['userflag']='txtids';
					$_POST['txtids']=$auditData['post_data']['userid'];
					$this->_view->assign('auditData',$auditData);
					$this->_view->assign('postData',$auditData['post_data']);
					$this->_view->assign('read',true);
				}
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'CofcTune','doaction'=>'query'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['data']['ErrMsg'][0]){//如果没有记录
						$this->_view->assign('errMsg',$data['data']['ErrMsg'][0]);
					}else {//如果有
						$this->_view->assign('detailTrue',true);	//显示详细
						$this->_view->assign('uo',$data['data']['uo']);
						$this->_view->assign('co',$data['data']['co']);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}


	/**
	 * 员工数值修改
	 */
	public function actionUserEmployeeTune(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=4;//员工数值修改
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'UserData','a'=>'EmployeeTune','doaction'=>'save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}else if ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
					$auditData=$this->_modelApplyDataFrg->findById($_GET['audit_id']);
					$auditData['post_data']=unserialize($auditData['post_data']);
					$_POST['userflag']='txtids';
					$_POST['txtids']=$auditData['post_data']['userid'];
					$this->_view->assign('auditData',$auditData);
					$this->_view->assign('postData',$auditData['post_data']);
					$this->_view->assign('read',true);
				}
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'EmployeeTune','doaction'=>'query'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['data']['ErrMsg'][0]){//如果没有记录
						$this->_view->assign('errMsg',$data['data']['ErrMsg'][0]);
					}else {//如果有
						$this->_view->assign('detailTrue',true);	//显示详细
						$this->_view->assign('uo',$data['data']['uo']);
						$this->_view->assign('sysIndustries',$data['data']['SysIndustries']);		//行业选择
						$this->_view->assign('selectSysDowerState',$data['data']['SysDowerState']);		//天赋选择SELECT
						$this->_view->assign('selectSysDowerLevel',$data['data']['SysDowerLevel']);		//天赋级别SELECT
						$this->_view->assign('selectSysSkillLevel',$data['data']['SysSkillLevel']);		//行业技能
						$this->_view->assign('el',json_encode($data['data']['el']));	//用户员工
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 架座数值修改
	 */
	public function actionUserCarTune(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=5;//员工数值修改
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'UserData','a'=>'CarTune','doaction'=>'save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}elseif ($_POST['addtool']){//增加座驾道具
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'CarTune','doaction'=>'addtool'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_utilMsg->showMsg($data['message'],1,2);
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,2);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}elseif ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
					$auditData=$this->_modelApplyDataFrg->findById($_GET['audit_id']);
					$auditData['post_data']=unserialize($auditData['post_data']);
					$_POST['userflag']='txtids';
					$_POST['txtids']=$auditData['post_data']['userid'];
					$this->_view->assign('auditData',$auditData);
					$this->_view->assign('postData',$auditData['post_data']);
					$this->_view->assign('read',true);
				}
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'CarTune','doaction'=>'query'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['data']['ErrMsg'][0]){//如果没有记录
						$this->_view->assign('errMsg',$data['data']['ErrMsg'][0]);
					}else {//如果有
						$this->_view->assign('detailTrue',true);	//显示详细
						$this->_view->assign('uo',$data['data']['uo']);
						$this->_view->assign('car',$data['data']['Car']);
						$selectTools=$data['data']['Tools'];
						$selectTools=Model::getTtwoArrConvertOneArr($selectTools,'Id','toolsname');
						$this->_view->assign('selectTools',$selectTools);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏玩家用户操作日志
	 */
	public function actionUserLog(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_GET['RootId'] || $_GET['TypeId']){
				if ((!$_GET['UserId'] && !$_GET['UserName'] && !$_GET['VUserName']) && $_REQUEST['submit']){
					$this->_utilMsg->showMsg(Tools::getLang('USERLOG_ERROR1',__CLASS__),-1);
				}
			}
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			if($_GET['page']){				
				$sendParams['Page'] = $_GET['page'];
			}
			$this->_utilFRGInterface->setGet(array('c'=>'Log','a'=>'UserLog','dosubmit'=>'search'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				echo $data['User']->Properties->VUserName;
				if ($data['msgno']==2)$this->_utilMsg->showMsg($data['message'],-1);//用户名不存在
				$tableList=array_combine($data['data']['TableList'],$data['data']['TableList']);
				$this->_view->assign('selectTableList',$tableList);
				$this->_view->assign('selectBigOptions',$data['data']['BigOptions']);
				$this->_view->assign('selectSmallOptions',$data['data']['SmallOptions']);
				$this->_view->assign('dataList',$data['data']['Result']);
				if(isset ( $data ['data'] ['TotalNum'] )){
					$this->_loadCore('Help_Page');//载入分页工具
					$helpPage=new Help_Page(array('total'=>intval($data ['data'] ['TotalNum']),'perpage'=>20));
					$this->_view->assign('MultiPage',$helpPage->show());
				}elseif (isset ( $data ['data'] ['MultiPage'] )) {
					$url = Tools::url ( CONTROL, ACTION ,array('server_id'=>$_REQUEST['server_id']));
					$data ['data'] ['MultiPage'] = str_replace ( "?m=Admin&c=Log&a=UserLog", $url, $data ['data'] ['MultiPage'] );
					$this->_view->assign ( "MultiPage", $data ['data'] ['MultiPage'] );
				}
				$selectedArr=array(
					'Table'=>$data['data']['Table'],
					'StartTime'=>$data['data']['StartTime'],
					'EndTime'=>$data['data']['EndTime'],
					'UserId'=>$data['data']['UserId'],
					'UserName'=>$data['data']['UserName'],
					'VUserName'=>$data['data']['VUserName'],
					'RootId'=>$data['data']['RootId'],
					'TypeId'=>$data['data']['TypeId'],
					'LockTbl'=>$data['data']['LockTbl'],
					'SearchContent'=>$data['data']['SearchContent'],
				);
				$this->_view->assign('selectedArr',$selectedArr);
				$this->_view->assign('gameUserClass',$data['data']['User']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 单服对多个玩家发放道具
	 */
	public function actionRewardBefore(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost() && $_POST['submit']){//提交表单
//				exit(Tools::dump($_POST));
				unset($_POST['submit']);
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
				$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
				$postArr=array();
				$postArr['server_id']=$_POST['server_id'];
				unset($_POST['server_id']);
				$postArr['type']=1;//'奖励发放'
				$postArr['cause']=$_POST['cause'];
				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'Reward','a'=>'SendAward','Action'=>'Save');
				$postArr['post_data']=$_POST;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
			}else {//显示表单
				
				$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'SendAward'));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
//					Tools::dump($data);
					$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
					$effData=$data['data']['ObjProp'];
					$newEffData=array();
					foreach ($effData as $key=>$value){
						$newEffData[$key]=array();
						foreach ($value as $childValue){
							$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
							$newEffData[$key][$childValue]['Name']=$data['data']['ObjData'][$key][$childValue]['Name'];
						}
					}
					if ($_POST['UserId'])$this->_view->assign('changeUsers',implode(',',$_POST['UserId']));
					$this->_view->assign('outfitData',json_encode($data['data']['OutfitData']));
//					Tools::dump($data['data']['OutfitData']);
					$this->_view->assign('effData',json_encode($newEffData));
				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 礼包管理
	 */
	public function actionLibao(){
		switch ($_GET['doaction']) {
			case 'add' :{
				$this->_libaoAdd();
				return ;
			}
			case 'edit':{
				$this->_libaoEdit();
				return ;
			}
			case 'del' :{
				$this->_libaoDel();
				return ;
			}
			default:{
				$this->_libaoIndex();
				return ;
			}
		}
	}

	private function _libaoAdd(){

	}

	private function _libaoEdit(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost()){//提交表单
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Add','doaction'=>'Save'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_utilMsg->showMsg($data['message'],1,Tools::url('OperationFRG','Libao',array('server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Add','Id'=>$_GET['Id']));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					Tools::import('Util_FRGTools');
					$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
					$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
					$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
					$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
					$this->_view->assign('data',$data['data']['Reward']);
					$this->_utilFrgTools->setEditResult($data['data']['Reward']);
					$dataResult=$this->_utilFrgTools->getEditResult();
					$this->_view->assign('chageCond',$dataResult['chageCond']);
					$this->_view->assign('chageEffect',$dataResult['chageEffect']);
					$this->_view->assign('chageTool',$dataResult['chageTool']);
					$this->_view->assign('num',$this->_utilFrgTools->getEditNum());
					$this->_view->assign('readOnly',$data['data']['ReadOnly']);

				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/EditLibao.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _libaoDel(){
		if ($_REQUEST['server_id']){
			if (!$_REQUEST['Id'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELREWARD',__CLASS__),-2);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'TypeList','doaction'=>'delete'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg($data['message'],1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		}
	}

	private function _libaoIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'TypeList'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				foreach ($data['data']['Data'] as &$value){
					$value['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
					$value['word_active']=$value['Active']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
					$value['word_disrepeat']=$value['DisRepeat']?Tools::getLang('NOT_REPEAT','Common'):Tools::getLang('REPEAT','Common');
				}
				$this->_view->assign('dataList',$data['data']['Data']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/Libao.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 编辑礼包
	 */
	public function actionEditLibao(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果选择了服务器将显示
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			if ($this->_isPost()){//提交表单
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Add','doaction'=>'Save'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_utilMsg->showMsg($data['message'],1,Tools::url('OperationFRG','Libao',array('server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Add','Id'=>$_GET['Id']));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					Tools::import('Util_FRGTools');
					$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
					$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
					$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
					$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
					$this->_view->assign('data',$data['data']['Reward']);
					$this->_utilFrgTools->setEditResult($data['data']['Reward']);
					$dataResult=$this->_utilFrgTools->getEditResult();
					$this->_view->assign('chageCond',$dataResult['chageCond']);
					$this->_view->assign('chageEffect',$dataResult['chageEffect']);
					$this->_view->assign('chageTool',$dataResult['chageTool']);
					$this->_view->assign('num',$this->_utilFrgTools->getEditNum());
					$this->_view->assign('readOnly',$data['data']['ReadOnly']);

				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}


	/**
	 * 礼包卡号列表
	 */
	public function actionCardList(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'CardList','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$currUrl = Tools::url ( CONTROL, ACTION, $sendParams );
				$this->_loadCore('Help_Page');
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data['data']['Data']){
					Tools::import('Util_FontColor');
					foreach ($data['data']['Data'] as &$list){
						$list['word_State']=Util_FontColor::getFrgLibaoCardStatus($list['State']);
						$list['CardName']=$data['data']['TypeData'][$list['TypeId']]['CardName']?$data['data']['TypeData'][$list['TypeId']]['CardName']:Tools::getLang('LIBAO_ISDEL',__CLASS__);
					}
				}
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl ) );
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$selectPage=Tools::getLang('PAGE_OPTION',__CLASS__);
				$this->_view->assign('selectPage',$selectPage);
				$this->_view->assign('select',$data['data']['Items']);
				$this->_view->assign('dataList',$data['data']['Data']);
				$this->_view->assign('selectedQuery',$data['data']['query']);
				$this->_view->assign('selectedPageSize',$data['data']['PageSize']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}

		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 导入/导出 卡号列表
	 */
	public function actionImportCard(){
		switch ($_GET['doaction']){
			case 'card_reset' :{//重置为可用卡号
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'CardList','doaction'=>'CardReset'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg(Tools::getLang('REST_SUCCESS',__CLASS__),1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
				break;
			}
			case 'invalid_delete' :{//删除无效卡号
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'CardList','doaction'=>'delete'));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data['msgno']==1){
					$this->_utilMsg->showMsg(Tools::getLang('CLEAR_SUCCESS',__CLASS__),1);
				}else {
					$this->_utilMsg->showMsg($data['message'],-2);
				}
				break;
			}
			case 'export' :{//导出卡号列表
				set_time_limit(200);
				if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'));
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$startTime=intval(strtotime($_POST['start']));
				$endTime=intval(strtotime($_POST['end']));
				$this->_utilFRGInterface->setServerUrl($_GET['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'ImportCard','doaction'=>'export'));
				$this->_utilFRGInterface->setPost(array('type_id'=>$_GET['type_id'],'start'=>$startTime,'end'=>$endTime));
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){//输出excel
						Tools::import('Util_ExportExcel');
						$utilExportExcel=new Util_ExportExcel('card','Excel/LibaoCard',$data['params']);
						$utilExportExcel->outPutExcel();
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,1);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
				break;
			}
			default:{//导入卡号动作
				if ($this->_isPost()){
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'ImportCard'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['msgno']==1){
							if ($data['backparams']){
								$msg=Tools::getLang('NOT_IMPORT_CARD',__CLASS__);
								foreach ($data['backparams'] as $list){
									$msg.="<li>{$list}</li>";
								}
								$msg.='</ul>';
							}
							$this->_utilMsg->showMsg($msg,1,Tools::url(CONTROL,'CardList',array('server_id'=>$_REQUEST['server_id'])),null);
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])),null);
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {//导入卡号显示页面
					$this->_createServerList();
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Create'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data['data']['TypeData'])$this->_view->assign('cardList',json_encode($data['data']['TypeData']));//卡类型
					$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
					if ($_GET['Id'] && $_GET['card_name'])$this->_view->assign('selectedLibao',array('Id'=>$_GET['Id'],'card_name'=>$_GET['card_name']));
					$this->_utilMsg->createNavBar();
					$this->_view->display();
				}
			}
		}
	}

	/**
	 * 生成礼包卡号
	 */
	public function actionAddCard(){
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$sendParams=Tools::getFilterRequestParam();
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			$postArr=array();
			$postArr['server_id']=$_POST['server_id'];
			$postArr['type']=7;//礼包生成卡号
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['send_action']=array('c'=>'Card','a'=>'Create','doaction'=>'save');
			$postArr['post_data']=$_POST;
			$this->_modelApplyDataFrg->set_postArr($postArr);
			$data=$this->_modelApplyDataFrg->add();
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
		}else {
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Create'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data['data']['TypeData'])$this->_view->assign('cardList',json_encode($data['data']['TypeData']));//卡类型
			$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
			if ($_GET['Id'] && $_GET['card_name'])$this->_view->assign('selectedLibao',array('Id'=>$_GET['Id'],'card_name'=>$_GET['card_name']));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}


	/**
	 * 充值礼包管理
	 */
	public function actionPayLibao(){
		switch ($_GET['doaction']){
			case 'add' :{//增加
				if ($this->_isPost()){
					$_POST['ToolId']=$_POST['Tool'];
					$_POST['ToolIdName']=$_POST['ToolName'];
					$_POST['ToolIdImg']=$_POST['ToolImg'];
					unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageAdd','doaction'=>'Save'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_POST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,2);
						}
					}else {
						$this->_utilMsg->showMsg($data['message'],-2);
					}
				}else {
					$this->_createServerList();
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageAdd'));
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						Tools::import('Util_FRGTools');
						$this->_utilFRGTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
						$this->_view->assign('objData',json_encode($data['data']['ObjData']));
						$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
						$this->_view->assign('effData',json_encode($this->_utilFRGTools->get_effData()));
						$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
					}
					$this->_utilMsg->createNavBar();
					$this->_view->set_tpl(array('body'=>'MasterFRG/PayLibaoAdd.html'));
					$this->_view->display();
				}
				break;
			}
			case 'edit' :{//编辑
				if ($this->_isPost()){
					$_POST['ToolId']=$_POST['Tool'];
					$_POST['ToolIdName']=$_POST['ToolName'];
					$_POST['ToolIdImg']=$_POST['ToolImg'];
					unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageAdd','doaction'=>'Save'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_POST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,2);
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
					}
				}else {
					$this->_createServerList();
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageAdd','Id'=>$_GET['Id']));
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						Tools::import('Util_FRGTools');
						$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
						$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
						$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
						$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
						$this->_view->assign('data',$data['data']['Reward']);
						$this->_utilFrgTools->setEditResult($data['data']['Reward']);
						$dataResult=$this->_utilFrgTools->getEditResult();
						$this->_view->assign('chageCond',$dataResult['chageCond']);
						$this->_view->assign('chageEffect',$dataResult['chageEffect']);
						$this->_view->assign('chageTool',$dataResult['chageTool']);
						$this->_view->assign('num',$this->_utilFrgTools->getEditNum());
						$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
						$this->_view->assign('timesDetail',$data['data']['Reward']['TimesDetail']);
					}
					$this->_utilMsg->createNavBar();
					$this->_view->set_tpl(array('body'=>'MasterFRG/PayLibaoEdit.html'));
					$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
					$this->_view->display();
				}
				break;
			}
			case 'del' :{
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageList','doaction'=>'delete'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_POST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,1);
						}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
				}
				break;
			}
			case 'proportion' :{//比例设置
				if ($this->_isPost()){
					Tools::import('Util_FRGInterface');
					if ($_POST['subaction']=='set_count'){
						$this->_utilFRGInterface=new Util_FRGInterface();
						$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
						$sendParams=Tools::getFilterRequestParam();
						$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'vartype','doaction'=>'saveadd'));
						$this->_utilFRGInterface->setPost($sendParams);
						$data=$this->_utilFRGInterface->callInterface();
						if ($data){
							if ($data['msgno']==1){
									$this->_utilMsg->showMsg($data['message'],1);
								}else {
									$this->_utilMsg->showMsg($data['message'],-2,2);
								}
						}else {
							$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
						}
					}else {
						$this->_utilFRGInterface=new Util_FRGInterface();
						$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
						$sendParams=Tools::getFilterRequestParam();
						$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'vartype','doaction'=>'saveedit','CatId'=>1));
						$this->_utilFRGInterface->setPost($sendParams);
						$data=$this->_utilFRGInterface->callInterface();
						if ($data){
							if ($data['msgno']==1){
									$this->_utilMsg->showMsg($data['message'],1);
								}else {
									$this->_utilMsg->showMsg($data['message'],-2,2);
								}
						}else {
							$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
						}
					}
				}else {
					$this->_createServerList();
					Tools::import('Util_FRGInterface');
					$this->_utilFRGInterface=new Util_FRGInterface();
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'vartype','showaction'=>'edit','CatId'=>1));
					$data=$this->_utilFRGInterface->callInterface();
					$arrarCount=$data['data']['SystemVars']['247']['ArrayCount'];
					$var=$data['data']['SystemVars']['247']['VarValue'];
					$payProportion=array();
					for ($i=0;$i<$arrarCount;$i++){
						$key=$var[$i][0]?$var[$i][0]:$i;
						$payProportion[$key]=$var[$i][1]?$var[$i][1]:$i;
					}
					$this->_view->assign('payProportion',$payProportion);
					$this->_utilFRGInterface->clearGet();
					$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'vartype','showaction'=>'addvar','VarName'=>'DepositRatio'));
					$num=$this->_utilFRGInterface->callInterface();
					$this->_view->assign('num',$num['data']['ConfVar']);

					$this->_utilMsg->createNavBar();
					$this->_view->set_tpl(array('body'=>'MasterFRG/PayLibaoProportion.html'));
					$this->_view->display();
					break;
				}

			}
			case 'serverSyn' :{
				$this->_payLibaoServerSyn();
				return ;
			}
			default:{
				$this->_createServerList();
				if ($_REQUEST['server_id']){
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PackageList'));
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['data']['Data']){
							foreach ($data['data']['Data'] as &$list){
								$list['url_edit']=Tools::url(CONTROL,ACTION,array('doaction'=>'edit','Id'=>$list['Id'],'server_id'=>$_REQUEST['server_id']));
							}
							$this->_view->assign('dataList',$data['data']['Data']);
						}
					}else {
						$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
					}
				}
				$this->_utilMsg->createNavBar();
				$this->_view->display();
			}
		}
	}

	private function _payLibaoServerSyn(){
		if ($this->_isPost() && $_POST['submit']){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_SELECT_ERROR1',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_SELECT_ERROR2',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);
			$postArr=array('PackageArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,array('c'=>'Card','a'=>'PackageAdd','doaction'=>'receive'),$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			Tools::import('Control_OperationFRG');
			#------生成msg------#
			$serverList=$this->_getGlobalData('gameser_list');
			$sendStatusMsgs='';
			foreach ($getResult as $key=>$value){
				if ($value['msgno']==1){
					$value['message']=$value['message']?$value['message']:Tools::getLang('LIBAO_SEND_SUCCESS',__CLASS__);
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
				}else {
					$value['message']=$value['message']?$value['message']:Tools::getLang('LIBAO_SEND_ERROR',__CLASS__);
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
				}
			}
			$sendStatusMsgs;
			#------生成msg------#
			$this->_utilMsg->showMsg($sendStatusMsgs,1,Tools::url(CONTROL,ACTION));
		}else {
			#------多服务器选择列表------#
//			$operatorList=$this->_getGlobalData('operator_list');
//			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$gameServerList=$this->_getGlobalData('gameser_list');
			foreach ($gameServerList as $key=>&$value){
				if ($key==100 || $key==200){//100和200是特殊服务器,不显示
					unset($gameServerList[$key]);
					continue;
				}
				if ($value['game_type_id']!=2)unset($gameServerList[$key]);
			}
//			$this->_view->assign('operatorList',$operatorList);
			$this->_view->assign('gameServerList',json_encode($gameServerList));
			$this->_view->assign('tplServerSelect','OperationFRG/ServerSelect.html');
			#------多服务器选择列表------#

			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Id'])$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_ERROR1',__CLASS__),-1);
			$selectedIds=array_unique($_REQUEST['Id']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Card','a'=>'PackageList'));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if (!count($data['data']['Data']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_ERROR2',__CLASS__),-1);
			$synArr=array();
			foreach ($data['data']['Data'] as $list){
				if (in_array($list['Id'],$selectedIds)){
					array_push($synArr,$list);
				}
			}
			if (!count($synArr))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_ERROR2',__CLASS__),-1);

			$this->_view->assign('dataList',$synArr);
			$this->_view->assign('serverName',$serverName);
			$this->_view->set_tpl(array('body'=>'OperationFRG/PayLibaoServerSyn.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}


	/**
	 * 教官管理
	 */
	public function actionDrillmaster(){
		$this->_createServerList();
		switch ($_GET['doaction']){
			case 'add' :{//增加
				if ($this->_isPost() && $_POST['submit']){
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'InstructorsAdd','doaction'=>'saveadd'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['msgno']==1){
							$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
							$this->_modelFrgLog->add($_POST,5);
							if ($data['backparams'] && is_array($data['backparams'])){
								$data['message'].=Tools::getLang('SEND_ERRORMSG',__CLASS__).implode('，',$data['backparams']).'</b>';
							}
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],null)));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,2);
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {
					if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
					$this->_view->set_tpl(array('body'=>'MasterFRG/AddDrillmaster.html'));
					$this->_utilMsg->createNavBar();
					$this->_view->display();
				}
				break;
			}
			case 'del' :{//删除
				if ($_REQUEST['server_id']){
					if (!$_REQUEST['UserId'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_DELUSER',__CLASS__),-2);
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$sendParams=Tools::getFilterRequestParam();
					$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'InstructorsList','doaction'=>'delete'));
					$this->_utilFRGInterface->setPost($sendParams);
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1);
						}else {
							$this->_utilMsg->showMsg($data['message'],-2);
						}
					}else{
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
				}
				break;
			}
			default:{//显示
				if ($_REQUEST['server_id']){
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'UserLockSay','a'=>'InstructorsList'));
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						if (count($data['data']['Instructors'])){
							foreach ($data['data']['Instructors'] as $key=>&$value){
								$value=array_merge($value,$data['data']['UserData'][$key]);
							}
							$this->_view->assign('dataList',$data['data']['Instructors']);
						}
					}else {
						$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
					}
				}
				$this->_utilMsg->createNavBar();
				$this->_view->display();
			}
		}
	}

	/**
	 * 活动管理动作
	 */
	public function actionActivity(){
		switch ($_GET['doaction']){
			case 'add' :{
				if ($this->_isPost()){
					$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
					$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'EditActivity','doaction'=>'save'),$_POST);
					$this->_utilApiFrg->send();
					$data=$this->_utilApiFrg->getResult();
					if ($data){
						if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {
					$this->_createServerList();
					if ($_REQUEST['server_id']){
						$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
						$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
						$this->_utilFRGInterface->setGet(array('c'=>'Activity','a'=>'EditActivity'));
						$data=$this->_utilFRGInterface->callInterface();
						if ($data){
							$this->_view->assign('objData',json_encode($data['data']['ShowObjects']));
							$this->_view->assign('toolData',json_encode($data['data']['allTools']));
						}else {
							$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
						}
					}
					$this->_view->set_tpl(array('body'=>'MasterFRG/ActivityAdd.html'));
					$this->_utilMsg->createNavBar();
					$this->_view->display();
				}
				break;
			}
			case 'edit' :{
				if ($this->_isPost()){
					$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
					$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'EditActivity','doaction'=>'save'),$_POST);
					$this->_utilApiFrg->send();
					$data=$this->_utilApiFrg->getResult();
					if ($data){
						if ($data['msgno']==1){
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {
					$this->_createServerList();
					if ($_REQUEST['server_id']){
						$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
						$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
						$this->_utilFRGInterface->setGet(array('c'=>'Activity','a'=>'EditActivity','Id'=>$_GET['Id']));
						$data=$this->_utilFRGInterface->callInterface();
						if ($data){
							Tools::import('Util_FRGTools');
							$editHtml=Util_FRGTools::getActivityHtml($data['data']['EditActivity']['AcceptCond'], $data['data']['EditActivity']['AffectedProperties'], $data['data']['EditActivity']['AffectedTools']);
							$this->_view->assign('editHtml',$editHtml);
							$num=array(
								'cond'=>count($data['data']['EditActivity']['AcceptCond'])+1,
								'effect'=>count($data['data']['EditActivity']['AffectedProperties'])+1,
								'tool'=>count($data['data']['EditActivity']['AffectedTools'])+2,
							);

							$this->_view->assign('num',$num);
							$this->_view->assign('dataList',$data['data']['EditActivity']);
							$this->_view->assign('objData',json_encode($data['data']['ShowObjects']));
							$this->_view->assign('toolData',json_encode($data['data']['allTools']));
						}else {
							$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
						}
					}
					$this->_view->set_tpl(array('body'=>'MasterFRG/ActivityEdit.html'));
					$this->_utilMsg->createNavBar();
					$this->_view->display();
				}
				break;
			}
			case 'del' :{
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$sendParams=Tools::getFilterRequestParam();
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'Activity','a'=>'DeleteActivity'));
				$this->_utilFRGInterface->setPost($sendParams);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
				break;
			}
			default:{
				$this->_createServerList();
				if ($_REQUEST['server_id']){
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
					$this->_utilFRGInterface->setGet(array('c'=>'Activity','a'=>'ListActivity'));
					$data=$this->_utilFRGInterface->callInterface();
					if ($data){
						foreach($data['data']['Activities'] as &$list){
							$list['url_edit']=Tools::url(CONTROL, ACTION,array('server_id'=>$_REQUEST['server_id'],'Id'=>$list['Id'],'doaction'=>'edit'));
						}
						$this->_view->assign('dataList',$data['data']['Activities']);
					}else {
						$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
					}
				}
				$this->_utilMsg->createNavBar();
				$this->_view->display();
				break;
			}
		}
	}

	/**
	 * 特殊活动管理
	 */
	public function actionSpecialActivity(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_spActivityAdd();
				return ;
			}
			case 'edit' :{
				$this->_spActivityEdit();
				return;
			}
			case 'rest' :{
				$this->_spActivityRest();
				return ;
			}
			case 'del' :{
				$this->_spActivityDel();
				return ;
			}
			case 'onoff' :{
				$this->_spActivityOnOff();
				return ;
			}
			case 'serverSyn' :{
				$this->_spActivityServerSyn();
				return ;
			}
			case 'ajaxSyn':{
				$this->_ajaxSyn();
				return;
			}
			default:{
				$this->_spActivityIndex();
				return ;
			}
		}
	}
	/**
	 * 前端ajax请求，单服同步
	 */
	private function _ajaxSyn(){
		$data = array('status'=>0,'info'=>'操作失败','data'=>null);
//		$data = array('status'=>1,'info'=>'<font color="#00CC00">操作成功</font>','data'=>null);
//		$this->_returnAjaxJson($data);
//		return;
		if (!count($_POST['data'])){
			$data['info'] = '无同步数据';
			$this->_returnAjaxJson($data);
		}
		if(!$_POST['server_id']){
			$data['info'] = '服务器id错误';
			$this->_returnAjaxJson($data);
		}
		$postArr=array('ActivityArray'=>$_POST['data']);
		$postArr['UniIdentifier'] = intval($_POST['UniIdentifier']);
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$this->_utilApiFrg->addHttp($_POST['server_id'],array('c'=>'Activity','a'=>'AddSpecialActivity','doaction'=>'receive'),$postArr);
		$this->_utilApiFrg->send();
		$getResult=$this->_utilApiFrg->getResults();
		#------生成msg------#
		//$serverList=$this->_getGlobalData('server/server_list_'.$this->game_id);
		$sendStatusMsgs='';
		foreach ($getResult as $key=>$value){
			if ($value['msgno']==1){
				$data['status'] = 1;
				if($value['backparams']){
					$value['message'] = '<font color="#FF0000">同步失败活动有：</font>';
					foreach($value['backparams'] as $faileOne){
						$value['message'] .= $faileOne['Title'].'、';
					}
				}else{
					$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_SUCCESS','Common');
					$value['message'] = "<font color='#00CC00'>{$value['message']}</font>";
				}
				$sendStatusMsgs.="{$value['message']}";
			}else {
				$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_FAILURE','Common');
				$sendStatusMsgs.="<font color='#FF0000'>{$value['message']}</font>";
			}
		}
		if($sendStatusMsgs){
			$data['info'] = $sendStatusMsgs;
		}
		$this->_returnAjaxJson($data);
	}
	
	/**
	 * 运营商分组管理
	 */
	public function actionOperatorGroup(){
		switch($_REQUEST['doaction']){
			case 'add':
				$this->_operatorGroupAdd();
				break;
			case 'del':
				$this->_operatorGroupDel();
				break;
			default:
				$this->_operatorGroupIndex();
		}
	}
	private function _operatorGroupIndex(){
		$operatorGroup = $this->_getOperatorGroup();
		$this->_view->assign('operatorGroup',$operatorGroup);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _operatorGroupAdd(){
		$operatorGroup = $this->_getOperatorGroup();
		$_POST['operators'] = (array)$_POST['operators'];
		if($_POST['operators']){
			$tmp = array();
			foreach ($_POST['operators'] as $optId){
				$tmp[$optId] = $operatorGroup['no_group'][$optId];
			}
			$operatorGroup[] = $tmp;
		}
		unset($operatorGroup['no_group']);
		if($this->_getOperatorGroup($operatorGroup)){
			$this->_utilMsg->showMsg('操作成功',1);
		}
		$this->_utilMsg->showMsg('操作失败',-1);
	}
	
	private function _operatorGroupDel(){
		$operatorGroup = $this->_getOperatorGroup();
		$_REQUEST['operatorId'] = intval($_REQUEST['operatorId']);
		unset($operatorGroup['no_group'],$operatorGroup[$_REQUEST['operatorId']]);
		if(empty($operatorGroup)){
			$operatorGroup = null;
		}
		if($this->_getOperatorGroup($operatorGroup)){
			$this->_utilMsg->showMsg('操作成功',1);
		}
		$this->_utilMsg->showMsg('操作失败',-1);
	}
	
	/**
	 * 多服务器同步
	 */
	private function _spActivityServerSyn(){
		if (false && $this->_isPost() && $_POST['sbm']){	//屏蔽此流程
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_SELECT_ERROR1',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('SPECIAL_AVTIVE_SYN_SELECT_ERROR2',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);
			$postArr=array('ActivityArray'=>$_POST['data']);
			$postArr['UniIdentifier'] = intval($_POST['UniIdentifier']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,array('c'=>'Activity','a'=>'AddSpecialActivity','doaction'=>'receive'),$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			#------生成msg------#
			$serverList=$this->_getGlobalData('server/server_list_'.$this->game_id);
			$sendStatusMsgs='';
			foreach ($getResult as $key=>$value){
				if ($value['msgno']==1){
					if($value['backparams']){
						$value['message'] = '<font color="#FF0000">同步失败活动有：</font>';
						foreach($value['backparams'] as $faileOne){
							$value['message'] .= $faileOne['Title'].'、';
						}
					}else{
						$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_SUCCESS','Common');
						$value['message'] = "<font color='#00CC00'>{$value['message']}</font>";
					}
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:{$value['message']}<br>";
				}else {
					$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_FAILURE','Common');
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
				}
			}
			#------生成msg------#
			$this->_utilMsg->showMsg($sendStatusMsgs,1,Tools::url(CONTROL,ACTION),false);
		}else {
			#------多服务器选择列表------#
//			$gameServerList=$this->_getGlobalData('server/server_list_'.$this->game_id);
//			unset($gameServerList[200]);
//			$this->_view->assign('gameServerList',json_encode($gameServerList));
//			$this->_view->assign('tplServerSelect','OperationFRG/MultiServerSelect.html');
			$this->_multiOperatorSelect('_getOperatorGroup');//使用_getOperatorGroup获得分组
			#------多服务器选择列表------#

			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Ids'])$this->_utilMsg->showMsg('请选择需要同步的特殊活动',-1);
			$selectedIds=array_unique($_REQUEST['Ids']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'ListSpecialActivity'));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if (!count($data['data']['Activities']))$this->_utilMsg->showMsg('同步的特殊活动为空',-1);
			$synArr=array();
			foreach ($data['data']['Activities'] as $list){
				if (in_array($list['Id'],$selectedIds)){
					unset($list['Id'],$list['CreateTime']);
					array_push($synArr,$list);
				}
			}
			if (!count($synArr))$this->_utilMsg->showMsg('同步的特殊活动为空',-1);
			
			$this->_view->assign('dataList',$synArr);
			$this->_view->assign('serverName',$serverName);
			$this->_view->assign('URL_ajaxSyn',Tools::url(CONTROL,ACTION,array('doaction'=>'ajaxSyn')));
			$this->_view->set_tpl(array('body'=>'OperationFRG/SpecialActivityServerSyn.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}


	/**
	 * 开关
	 */
	private function _spActivityOnOff(){
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Activity','a'=>'ListSpecialActivity','action'=>'status','Id'=>$_GET['Id'],'IsOpen'=>$_GET['IsOpen']);
		$this->_utilApiFrg->addHttp($_GET['server_id'],$getArr);
		$this->_utilApiFrg->send();
		$dataResult=$this->_utilApiFrg->getResult();
		if ($dataResult['msgno']==2){
			$this->_utilMsg->showMsg($dataResult['message'],-2);
		}else {
			$this->_utilMsg->showMsg(false);
		}

	}

	private function _spActivityDel(){
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'ListSpecialActivity','action'=>'del'),$_POST);
		$this->_utilApiFrg->send();
		$this->_utilMsg->showMsg(false);
	}

	private function _spActivityAdd(){
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){//如果选择了服务器将显示
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'Activity','a'=>'AddSpecialActivity','action'=>'save');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiFrg->send();
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {//显示表单
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'AddSpecialActivity'));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if ($data){
				$this->_view->assign('types',$data['data']['ActivityTypes']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/SpecialActivityAdd.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _spActivityEdit(){
		set_time_limit(70);	//设置max_execution_time
		$this->_createServerList();
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$this->_utilApiFrg->setTimeOut(60);	//设置CURL超时时间
		if ($_REQUEST['server_id'] && $this->_isPost()){//如果选择了服务器将显示			
			$getArr=array('c'=>'Activity','a'=>'AddSpecialActivity','action'=>'save');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiFrg->send();
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {//显示表单
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'AddSpecialActivity','Id'=>$_GET['Id']));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if ($data){
				$data['data']['Activity']['word_Identifier']=$data['data']['ActivityTypes'][$data['data']['Activity']['Identifier']]['Description'];
				$this->_view->assign('types',$data['data']['ActivityTypes']);
				$this->_view->assign('rewardsList',$data['data']['AwardsForm']);
				$this->_view->assign('dataList',$data['data']['Activity']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/SpecialActivityEdit.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}


	private function _spActivityRest(){
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'ListSpecialActivity','action'=>'reset','Id'=>$_GET['Id']));
		$this->_utilApiFrg->send();
		$this->_utilMsg->showMsg(false);
	}

	private function _spActivityIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$Page = max(1,intval($_GET['page']));
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'ListSpecialActivity','Page'=>$Page));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
//			print_r($data);exit();
			if ($data){
				$open=array('0'=>Tools::getLang('CLOSE','Common'),'1'=>Tools::getLang('OPEN','Common'));
				$show = array('0'=>Tools::getLang('NOT_DISPLAY','Common'),'1'=>Tools::getLang('DISPLAY','Common'));
				$checkType=array('1'=>Tools::getLang('IN_PROGRESS','Common'),'3'=>Tools::getLang('ENDED','Common'));
				$type=$data['data']['ActivityTypes'];
				$this->_fSpActivityType($type);	//根据时间更新缓存
				if ($data['data']['Activities']){
					foreach ($data['data']['Activities'] as &$value){
						$value['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
						$value['url_rest']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'rest'));
						$value['word_type']=$type[$value['Identifier']]['Name'];
						$value['word_is_open']=$open[$value['IsOpen']];
						$value['word_is_show']=$show[$value['IsShow']];
						$value['url_onoff']=Tools::url(CONTROL,ACTION,array('doaction'=>'onoff','Id'=>$value['Id'],'IsOpen'=>$value['IsOpen']?0:1,'server_id'=>$_REQUEST['server_id']));
						
						if ($value['Status']>0){
							if ($value['Identifier']=='EmployeeTopScore'){
								$value['word_status']=date('Y-m-d H:i:s',$value['Status']).Tools::getLang('RESETED','Common');//RESETED
							}else {
								$value['word_status']=Tools::getLang('AWARDS_ISSUED',__CLASS__);
							}
						}else {
							if ($value['IsOpen']){
								if (CURRENT_TIME>$value['EndTime']){
									$value['word_status']=Tools::getLang('ENDED','Common');//
								}elseif (CURRENT_TIME>$value['StartTime']) {
									$value['word_status']=Tools::getLang('IN_PROGRESS','Common');//
								}elseif (CURRENT_TIME<$value['StartTime']){
									$value['word_status']=Tools::getLang('NOT_START','Common');//NOT_START
								}
							}else {
								$value['word_status']='-';
							}
						}
					}
				}
				$this->_view->assign('dataList',$data['data']['Activities']);
				if($data['data']['PageInfo']){
					$this->_loadCore('Help_Page');//载入分页工具
					$helpPage=new Help_Page(array('total'=>$data['data']['PageInfo']['total'],'perpage'=>20));
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$this->_view->assign('URL_refurbish',Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'],'timeout'=>'1')));
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/SpecialActivity.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 把特殊活动类型缓存在文件里
	 * @param array $typeData
	 * @param int $time
	 */
	private function _fSpActivityType($typeData=array(),$time=86400){
		$fileName = $this->game_id.self::FRG_SPACTIVITY_TYPE;
		//过时刷新
		if($_REQUEST['timeout']){
			$time = -1;
		}
		$data = $this->_f($fileName,'',CACHE_DIR,$time);
		if(false === $data){			
			if($typeData){			
				foreach($typeData as $key =>$val){
					$tmp[$key] = $val['Name'];
				}
			}
			if($tmp){
				$this->_f($fileName,$tmp);
			}
		}
	}


	/**
	 * 富人国日志功能
	 */
	public function actionLog(){
		switch ($_GET['doaction']){
			default:{//显示
				$frgLog=$this->_getGlobalData('frg_log');
				$users=$this->_getGlobalData('user');
				$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
				$this->_loadCore('Help_SqlSearch');
				$helpSqlSearch=new Help_SqlSearch();
				$helpSqlSearch->set_tableName($this->_modelFrgLog->tName());
				if($_GET['type']!=''){
					$helpSqlSearch->set_conditions("type={$_GET['type']}");
					$this->_view->assign('selectedType',$_GET['type']);
				}
				if ($_GET['user_id']) {
					$helpSqlSearch->set_conditions("user_id={$_GET['user_id']}");
					$this->_view->assign('selectedUserId',$_GET['user_id']);
				}
				$helpSqlSearch->set_orderBy('create_time desc');
				$helpSqlSearch->setPageLimit($_GET['page']);
				$sql=$helpSqlSearch->createSql();
				$conditions=$helpSqlSearch->get_conditions();
				$dataList=$this->_modelFrgLog->select($sql);
				if ($dataList) {
					foreach ($dataList as &$list){
						$list['word_type']=$frgLog[$list['type']];
						$list['word_user_id']=$users[$list['user_id']]['nick_name'];
						$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
						$list['ip']=$list['ip']?long2ip($list['ip']):'';
					}
					$this->_view->assign('dataList',$dataList);
					$this->_loadCore('Help_Page');
					$helpPage=new Help_Page(array('total'=>$this->_modelFrgLog->findCount($conditions),'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$frgLog['']=Tools::getLang('ALL','Common');
				$this->_view->assign('type',$frgLog);
				$this->_view->assign('users',Model::getTtwoArrConvertOneArr($users,'Id','nick_name'));
				$this->_utilMsg->createNavBar();
				$this->_view->display();
				break;
			}
		}
	}

	/**
	 * 玩家充值记录
	 */
	public function actionUserPayLog(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam(array('page'));
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'PayList','Page'=>$_GET['page']));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$currUrl = Tools::url ( CONTROL, 'UserPayLog', $sendParams );
				$this->_loadCore('Help_Page');
				if ($data['data']['TotalNum']=='')$data['data']['TotalNum']=0;
				if ($data ['data'] ['PageSize']){
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl ) );
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$goldType=array('1'=>Tools::getLang('NORMAL_RECHARGE',__CLASS__),'2'=>Tools::getLang('COMPENSATE_GOLD_CARD',__CLASS__),'3'=>Tools::getLang('INTELLOGENT_GOLD_CARD',__CLASS__),'4'=>Tools::getLang('INTERNAL_TESTING',__CLASS__),'5'=>Tools::getLang('CS_DEDUCT_GOLD',__CLASS__));
				if ($data['data']['Data']){
					$totalCash=0;
					foreach ($data['data']['Data'] as &$list){
						$list['ExchangeType']=$goldType[$list['ExchangeType']];
						$totalCash+=$list['Cash'];
					}
					$this->_view->assign('totalCash',$totalCash);
					$this->_view->assign('dataList',$data['data']['Data']);
				}

				$selectPage=Tools::getLang('PAGE_OPTION',__CLASS__);
				$this->_view->assign('selectPage',$selectPage);

				$this->_view->assign('goldType',$goldType);
				$this->_view->assign('items',$data['data']['Items']);
				$this->_view->assign('typeItem',$data['data']['TypeItem']);
				$data['data']['Query']['PageSize']=$data['data']['PageSize'];
				$this->_view->assign('selectedArr',$data['data']['Query']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 服务器统计页面
	 */
	public function actionServerStats() {
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'Index','a'=>'ServeSet','showaction'=>'UserLost'));
			$data=$this->_utilFRGInterface->callInterface();

			$this->_utilFRGInterface->clearGet();
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'Index','a'=>'ServeSet'));
			$onlineUser=$this->_utilFRGInterface->callInterface();
			$onlineUser=$onlineUser['data']['CheckList']['Online']['2'];
			$onlineUser=strip_tags($onlineUser);
			$this->_view->assign('onlineUserNum',$onlineUser);
			if ($data){
				ksort($data['data']['UserLostList']);
				$UserLostList=$data['data']['UserLostList'];

			  	$FirstData=$UserLostList[1];
			   	for($i=1;$i<=count($UserLostList);$i++){
				   	$Data=$UserLostList[$i];
				   	if($i==1&&$Data[1]==0)break;
				   	$PData=$i==1?$Data:$UserLostList[$i-1];

				   	$UserLostList[$i]['data_0']=$Data[2];
					$UserLostList[$i]['data_1']=$Data[1];
					$UserLostList[$i]['data_2']=($PData[1]>0?round(($PData[1]-$Data[1])/$PData[1]*100,2):0).'%';
					$UserLostList[$i]['data_3']=round(($FirstData[1]-$Data[1])/$FirstData[1]*100,2).'%' ;
					if ($i==1){
						$UserLostList[$i]['data_4']=0;
						$UserLostList[$i]['data_5']=0;
						$UserLostList[$i]['data_6']=0;
					}else {
						$UserLostList[$i]['data_4']=(!$data[3] && !$Data[1])?0:$Data[3]/$Data[1];
						$UserLostList[$i]['data_5']=$Data[4];
						$UserLostList[$i]['data_6']=$Data[5];
					}

			   	}
				$this->_view->assign('dataList',$UserLostList);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/ServerStats.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}


	/**
	 * 用户登录
	 */
	public function actionGameLogin(){
		if ($this->_isPost()){
			$serverList=$this->_getGlobalData('gameser_list');

			$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
			$addArr=array('cause'=>$_POST['cause'],'server_name'=>$serverList[$_POST['server_id']]['full_name'],'user_name'=>$_POST['user_name']);
			$this->_modelFrgLog->add($addArr,6);//6登陆游戏

			$serverUrl=$serverList[$_POST['server_id']]['server_url'];
			$serverUrl="{$serverUrl}php/interface.php?m=User&c=Login&a=login&__hj_dt=HtmlTemplate&job=1&";
			$operatorId=$serverList[$_POST['server_id']]['operator_id'];

			#获取key
//			$modelGoldCard=$this->_getGlobalData('Model_GoldCard','object');
//			$sysKey=$modelGoldCard->getKey($this->game_id);
//			$sysKey=$sysKey[$operatorId]['key'];
			$gameObject = $this->_getGlobalData($this->game_id,'game');
			$gameOperatorExt = $gameObject->getOptConf($operatorId);
			$sysKey = isset($gameOperatorExt['syskey'])?$gameOperatorExt['syskey']:'';
			
			$userName=trim(strval($_POST['user_name']));
			$time= time();
			$gameId='2';
			$serverid=1;
			$domainId='1';
			$al='1';
			$from='1';
			$siteurl='1';
			$apiUserId='1';
			$gmSign=md5("Uname={$userName}&userid={$apiUserId}&GameId={$gameId}&ServerId={$serverid}&Key={$sysKey}&Time={$time}&al={$al}&from={$from}&siteurl={$siteurl}");
// 			echo "Uname={$userName}&userid={$apiUserId}&GameId={$gameId}&ServerId={$serverid}&Key={$sysKey}&Time={$time}&al={$al}&from={$from}&siteurl={$siteurl}";exit;
			$serverUrl.="Sign={$gmSign}&Uname={$userName}&userid={$apiUserId}&GameId={$gameId}&ServerId={$serverid}&Time={$time}&al={$al}&from={$from}&siteurl={$siteurl}";
			header('Location: '.$serverUrl);
			exit();

		}else {
			if ($_GET['operator_id']){
				$serverList=$this->_getGlobalData('gameser_list');
				foreach ($serverList as $key=>&$value){
					if ($value['Id']==100 || $value['Id']==200)unset($serverList[$key]);
					if ($value['game_type_id']!=2)unset($serverList[$key]);
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
	 * 点亮座驾成就
	 */
	public function actionCarLightHonor(){
		$this->_createServerList();
		if($_REQUEST['server_id']){
			if ($this->_isPost()){
				if(!$_POST['cause']){
					$this->_utilMsg->showMsg('申请原因不能为空',-1);
				}
				if(!$_POST['UserId']){
					$this->_utilMsg->showMsg('玩家ID不能为空',-1);
				}
				$post = array(
					'UserId'=>trim($_POST['UserId']),
					'Honor1'=>intval($_POST['Honor1']),
					'Honor2'=>intval($_POST['Honor2']),
					'Honor3'=>intval($_POST['Honor3']),
					'Honor4'=>intval($_POST['Honor4']),
				);
				$apply_info="<div>申请原因：{$_POST['cause']}</div>";
				$apply_info.="<div>UerId：{$post['UserId']}</div>";
				$apply_info.="<div>越野成就：{$post['Honor1']}</div>";
				$apply_info.="<div>方程式成就：{$post['Honor2']}</div>";
				$apply_info.="<div>联赛成就：{$post['Honor3']}</div>";
				$apply_info.="<div>炼狱成就：{$post['Honor4']}</div>";
				$post = array_filter($post);
				$gameser_list = $this->_getGlobalData('server/server_list_'.$this->game_id);
				$applyData = array(
						'type'=>22,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>'php/interface.php',
							'post_data'=>$post,
							'get_data'=>array(
								'c'=>'UserData',
								'a'=>'CarTune',
								'm'=>'Admin',
								'doaction'=>'LightHonor',
								'__hj_dt'=>'RpcSeri',
								'__sk'=>array(
									'cal_local_object'=>'Util_FRGInterface',
									'cal_local_method'=>'getFrgSk',
									'params'=>NULL,
								),
							),
							'end'=>array(
								'cal_local_object'=>'Util_ApplyInterface',
								'cal_local_method'=>'PhpAuditEnd',
								'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
							),
						),
						'receiver_object'	=>array($_REQUEST['server_id']=>''),
						'player_type'		=>1,
						'player_info'		=>$post['UserId'],
				);	
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if(!$applyInfo){
					$this->_utilMsg->showMsg('申请失败',-1);
				}
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$URL_CsAll = Tools::url('Apply','CsAll');
				$showMsg = '申请成功,等待审核...<br>';
				$showMsg .="<a href='{$URL_CsIndex}'>客服审核列表</a><br>";
				$showMsg .="<a href='{$URL_CsAll}'>客服审核列表(全部)</a>";
				$this->_utilMsg->showMsg($showMsg,1,1,false);
//				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
//				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
//				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'CarTune','doaction'=>'LightHonor'));
//				$this->_utilFRGInterface->setPost($_POST);
//				$data=$this->_utilFRGInterface->callInterface();
//				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1);
			}
		}
		$this->_view->display();
	}
	
	/**
	 * 员工恢复
	 */
	public function actionEmployeeResume(){
		$this->_createServerList();		
		$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
		$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
		if ($this->_isPost()){
			$_GET['UserId'] = trim($_GET['UserId']);
			if(empty($_GET['UserId'])){
				$this->_utilMsg->showMsg('玩家ID不能为空',-1);
			}
			if('' == trim($_POST['cause'])){
				$this->_utilMsg->showMsg(Tools::getLang('TPL_REASON_CAN_NOT_BE_EMPTY',__CLASS__),-1,2);//'原因不能为空'
			}
			$ENames = '';
			foreach($_POST['EIds'] as $eid){
				$ENames .= $_POST['ENames_'.$eid].',';
			}
			$AddLog = '原因:'.$_POST['cause'];
			$AddLog .= '<br>玩家ID:'.$_GET['UserId'];
			$AddLog .= '<br>恢复的员工:'.$ENames;
			$PostData['EIds'] = $_POST['EIds'];
			$gameser_list = $this->_getGlobalData('server/server_list_'.$this->game_id);
			$applyData = array(
				'type'=>30,
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>1,
				'apply_info'=>$AddLog,//$apply_info
				'send_type'=>2,	//2	http
				'send_data'=>array(
					'url_append'=>'php/interface.php',
					'post_data'=>$PostData,
					'get_data'=>array(
						'c'=>'UserData',
						'a'=>'EmployeeResume',
						'm'=>'Admin',
						'doaction'=>$_POST['doaction'],
						'UserId' => $_GET['UserId'],
						'__hj_dt'=>'RpcSeri',
						'__sk'=>array(
							'cal_local_object'=>'Util_FRGInterface',
							'cal_local_method'=>'getFrgSk',
							'params'=>NULL,
						),
					),
					'end'=>array(	//结束后调用此方法对结果进行处理
						'cal_local_object'=>'Util_ApplyInterface',	//使用本地对象，如果为空，则使用内置函数
						'cal_local_method'=>'PhpAuditEnd',	//使用本地方法
						'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
					),
				),
				'receiver_object' =>array($_REQUEST['server_id']=>''),
				'player_type' =>1,
				'player_info' =>$_GET['UserId'],
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
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}
		}else{
			$_GET['DigStatus'] = intval($_GET['DigStatus']);
			
			$DigStatusMap = array('0'=>'2','1'=>'0','2'=>'1');
			$DigStatus = array('0'=>Tools::getLang('ALL','Common'),'1'=>Tools::getLang('NOT_POACHER',__CLASS__),'2'=>Tools::getLang('POACHER',__CLASS__));
			
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'EmployeeResume','UserId' => $_GET['UserId'],'DelDay' => $_GET['DelDay'],'DigStatus'=>$DigStatusMap[$_GET['DigStatus']]));
			$data=$this->_utilFRGInterface->callInterface();
			if($data){
				if($data['data']['ResumeDays']){
					foreach($data['data']['ResumeDays'] as $val){
						$ResumeDays[$val] = $val.Tools::getLang('DAY','Common');
					}
					$ResumeDays[0] = Tools::getLang('ALL','Common');
				}
				$this->_view->assign('DataTable',$data['data']['DataTable']);
				$this->_view->assign('DelDay',$ResumeDays);
				$this->_view->assign('DigStatus',$DigStatus);
				$this->_view->assign('dataList',$data['data']['Employees']);
			}
			$selected = array(
				'UserId'=>$_GET['UserId'],
				'DelDay'=>$_GET['DelDay'],
				'DigStatus'=>$_GET['DigStatus'],
			);
			$this->_view->assign('selected',$selected);
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	
	/**
	 * 用exlce文件中的用户账号，查询用户
	 */
	public function actionUserQueryByExcel(){
		$this->_createServerList();	
		if ($this->_isPost()){
			if(!strtotime($_POST['StartTime']) || !strtotime($_POST['EndTime'])){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_INSERT_CORRECT_TIME','Common'),-1);
			}			
			$file = $_FILES['upload'];
			$postData=array();
			if ($file['error'] == 0){
				$this->_loadCore('Help_FileUpload');
				$helpFileUpload=new Help_FileUpload($file,EXCEL_DIR.'/'.date('Ymd'),1024*1024*8,array('xls','xlsx'));
				$helpFileUpload->singleUpload();
				$fileInfo=$helpFileUpload->getSaveInfo();
				$this->_loadCore('Help_Excel');
				$helpExcel=new Help_Excel($fileInfo['path']);
				$excelData=$helpExcel->getData(0);
				array_shift($excelData);
				foreach($excelData as $sub){
					$postData[$sub[0]] = $sub[0];
				}
			}
			$postData = implode(',',$postData);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'ExportUser'));
			$this->_utilFRGInterface->setPost(array('StartTime'=>$_POST['StartTime'],'EndTime'=>$_POST['EndTime'],'UserNames'=>$postData));
			$data=$this->_utilFRGInterface->callInterface();
			if($data['msgno']!=0){
				$this->_utilMsg->showMsg($data['message'],-1);
			}
			Tools::import('Util_ExportExcel');
			$this->_utilExportExcel=new Util_ExportExcel($_GET['server_id'].'_'.CURRENT_TIME,'Excel/UserInfo',$data['data']);
			$this->_utilExportExcel->outPutExcel();
			return ;
		}
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$this->_view->assign('operatorList',$operatorList);
		$this->_utilMsg->createNavBar();
		$this->_view->display();

	}
	
	public function actionCollectionlist(){		
		$doactionArr = array(
			'opencollection'=>'OpenCollection',
			'carcache'=>'FreshCarCache',
			'peoplecache'=>'FreshEmployeeCache',
			'sendaward'=>'SendAward',
		);
		switch ($_GET['doaction']){
			case 'opencollection':
			case 'carcache':
			case 'peoplecache':
			case 'sendaward':
				$this->_collectionSend($doactionArr[$_GET['doaction']]);
				return;
			case 'del':
				$this->_collectionDel();
				return;
			default:
				$this->_collectionList();
				return;
		}
	}
	
	private function _collectionSend($doaction){
		if($_REQUEST['server_id'] ){
			$postData = array(
				'Cid'=>intval($_REQUEST['Cid']),
				'UserId'=>intval($_REQUEST['UserId']),
			);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserTune','doaction'=>$doaction));
			$this->_utilFRGInterface->setPost($postData);
			$data=$this->_utilFRGInterface->callInterface();
			$ajacReturn = array(
				'status'=>$data['msgno']===0?1:0,
				'info'=>$data['message'],
				'data'=>$postData['UserId'],
			);
			$this->_returnAjaxJson($ajacReturn);
		}
	}
	
	private function _collectionList(){
		$this->_createServerList();
		if($_REQUEST['server_id'] ){
			$URL_CollectionDel = Tools::url(CONTROL,ACTION,array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('URL_CollectionDel',$URL_CollectionDel);
			if($_REQUEST['toSubmit'] && $_GET['UserId']){
				$_GET['UserId']= intval($_GET['UserId']);
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserTune','doaction'=>'ShowList','UserId'=> $_GET['UserId']));
				$data=$this->_utilFRGInterface->callInterface();
				if($data['data']){
					$this->_view->assign('Lists',$data['data']['Lists']);
					$this->_view->assign('UserInfo',$data['data']['UserInfo']);
					$this->_view->assign('CollectionConfig',$data['data']['CollectionConfig']);
					$this->_view->assign('CacheFileData',$data['data']['CacheFileData']);
//					print_r($data['data']);exit();
				}
			}
		}
		
		$this->_view->assign('URL_Send',Tools::url(CONTROL,ACTION));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _collectionDel(){
		$this->_createServerList();
		if($_REQUEST['server_id']){
			if($this->_isPost()){
				$postData['UserId'] = $_POST['UserId'];
				$postData['Cid'] = $_POST['Cid'];
				$postData['Value'] = $_POST['Value'];
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'UserTune','doaction'=>'DelCollection'));
				$this->_utilFRGInterface->setPost($postData);
				$data=$this->_utilFRGInterface->callInterface();
				$this->_utilMsg->showMsg('操作完成',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}
		}
		$this->_view->set_tpl(array('body'=>'MasterFRG/CollectionDel.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	/**
	 * 收回炼狱值
	 */
	public function actionChangeCarPurVal(){
		$this->_createServerList();
		if($this->_isPost()){
			$postData = array(
				'UserId'=>intval($_POST['UserId']),	//玩家ID
				'CarState'=>trim($_POST['CarState']),	//操作(1为加,0为减,2为查询)
				'PurVal'=>intval($_POST['PurVal']),	//数值
				'TimePoint'=>trim($_POST['TimePoint']),	//时间点
				'EndTimePoint'=>trim($_POST['EndTimePoint']),	//结束时间点
				'index'=>intval($_POST['index']),	//事件表列号(1-5)
				'NotIds'=>trim($_POST['NotIds']),	//排除ID号(,隔开)
				'Page'=>max(1,intval($_POST['Page'])),	//页号
			);
			if($postData['CarState']!=='' && !in_array(intval($postData['CarState']),array(0,1,2))){
				$this->_utilMsg->showMsg('操作(1为加,0为减,2为查询)',-1);
			}
			if(!in_array($postData['index'],array(1,2,3,4,5))){
				$this->_utilMsg->showMsg('事件表列号(1-5)',-1);
			}
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			if($_POST['showPurVal']){
				$getData = array('c'=>'UserData','a'=>'UserTune','doaction'=>'SelPurVal');
			}else{
				$getData = array('c'=>'UserData','a'=>'UserTune','doaction'=>'ChangeCarPurVal');
			}
			$this->_utilFRGInterface->setGet($getData);
			$this->_utilFRGInterface->setPost($postData);
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('UserId',$data['data']['UserId']);
			$this->_view->assign('OldPurVal',$data['data']['OldPurVal']);
			$this->_view->assign('NewPurVal',$data['data']['NewPurVal']);
			$this->_view->assign('PurValLog',$data['data']['PurValLog']);
			$this->_view->assign('UserMore',$data['data']['UserMore']);

		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	/**
	 * 其他功能
	 */
	public function actionOthers(){
		
		$dataList=array(
			array('员工恢复',Tools::url(CONTROL,'EmployeeResume')),
			array('点亮座驾成就',Tools::url(CONTROL,'CarLightHonor')),
			array('玩家收藏列表',Tools::url(CONTROL,'Collectionlist')),
			array('收回炼狱值',Tools::url(CONTROL,'ChangeCarPurVal')),
		);
		$this->_view->assign('dataList',$dataList);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	

	
}