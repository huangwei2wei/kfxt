<?php
class Control_HaiDaoMaster extends HaiDao {

	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;

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
		$this->_createView();
		$this->_createUrl();
		$this->_checkOperatorAct();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}

	private function _createUrl(){
		$this->_url['OperationFRG_BatchNotice']=Tools::url(CONTROL,'AllNotice',array('zp'=>self::PACKAGE));
		$this->_url['OperationFRG_SynNotice']=Tools::url(CONTROL,'AllNotice',array('doaction'=>'syn','zp'=>self::PACKAGE));


		$this->_url['Master_AddDonttalk']=Tools::url(CONTROL,'Donttalk',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['Master_ClearOuttalk']=Tools::url(CONTROL,'Donttalk',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'clear'));	//清除过时禁言

		//以上地址经过修改

		$this->_url['MasterFRG_UserInquire']=Tools::url(CONTROL,'UserInquire',array('zp'=>self::PACKAGE));
		$this->_url['MasterFRG_NoticeDel']=Tools::url(CONTROL,'Notice',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_url['MasterFRG_NoticeAdd']=Tools::url(CONTROL,'Notice',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['OperationFRG_Notice_serverSyn']=Tools::url('HaiDaoOperation','Notice',array('zp'=>self::PACKAGE,'doaction'=>'serverSyn'));
		$this->_url['MasterFRG_DelDonttalk']=Tools::url(CONTROL,'Donttalk',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_url['MasterFRG_AddReward']=Tools::url(CONTROL,'Reward',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_DelReward']=Tools::url(CONTROL,'Reward',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_url['MasterFRG_EditReward']=Tools::url(CONTROL,'Reward',array('zp'=>self::PACKAGE,'doaction'=>'edit'));
		$this->_url['OperationFRG_RewardServerSyn']=Tools::url('HaiDaoOperation','Reward',array('zp'=>self::PACKAGE,'doaction'=>'serversyn'));
		$this->_url['MasterFRG_AddLockUsers']=Tools::url(CONTROL,'LockUsers',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_DelLockUsers']=Tools::url(CONTROL,'LockUsers',array('zp'=>self::PACKAGE,'doaction'=>'del'));
		$this->_url['MasterFRG_RewardBefore']=Tools::url(CONTROL,'RewardBefore',array('zp'=>self::PACKAGE));
		$this->_url['MasterFRG_KickUser']=Tools::url(CONTROL,'KickUser',array('zp'=>self::PACKAGE));
		$this->_url['MasterFRG_SendMail']=Tools::url(CONTROL,'SendMail',array('zp'=>self::PACKAGE));
		$this->_url['MasterFRG_AllSendMail']=Tools::url(CONTROL,'AllSendMail',array('zp'=>self::PACKAGE));

		$this->_url['MasterFRG_UserData']=Tools::url(CONTROL,'UserData',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserEmployeeTune']=Tools::url(CONTROL,'UserEmployeeTune',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserCarTune']=Tools::url(CONTROL,'UserCarTune',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserFactory']=Tools::url(CONTROL,'UserFactory',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_UserCofcTune']=Tools::url(CONTROL,'UserCofcTune',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_AddCard']=Tools::url(CONTROL,'AddCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_ImportCard']=Tools::url(CONTROL, 'ImportCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_DelInvalidCard']=Tools::url(CONTROL,'ImportCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'invalid_delete'));
		$this->_url['MasterFRG_CardRest']=Tools::url(CONTROL,'ImportCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'card_reset'));
		$this->_url['MasterFRG_CardInvalid']=Tools::url(CONTROL,'ImportCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'invalid_delete'));
		$this->_url['MasterFRG_PayLibao_add']=Tools::url(CONTROL,'PayLibao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_PayLibao_edit']=Tools::url(CONTROL,'PayLibao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
		$this->_url['MasterFRG_PayLibao_del']=Tools::url(CONTROL,'PayLibao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_PayLibao_proportion']=Tools::url(CONTROL,'PayLibao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'proportion'));
		$this->_url['OperationFRG_PayLibao_serverSyn']=Tools::url(CONTROL,'PayLibao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'serverSyn'));
		$this->_url["MasterFRG_PayCard_add"]=Tools::url(CONTROL,'PayCard',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Drillmaster_Add']=Tools::url(CONTROL,'Drillmaster',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Drillmaster_Del']=Tools::url(CONTROL,'Drillmaster',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_Activity_Add']=Tools::url(CONTROL, 'Activity',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_Activity_Edit']=Tools::url(CONTROL, 'Activity',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
		$this->_url['MasterFRG_Activity_Del']=Tools::url(CONTROL, 'Activity',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));
		$this->_url['MasterFRG_SpecialActivity_Add']=Tools::url(CONTROL,'SpecialActivity',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		$this->_url['MasterFRG_SpecialActivity_serverSyn']=Tools::url(CONTROL,'SpecialActivity',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'serverSyn'));
		$this->_url['MasterFRG_SpecialActivity_del']=Tools::url(CONTROL,'SpecialActivity',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'del'));

		$this->_url['MasterFRG_Libao_del']=Tools::url(CONTROL,'Libao',array('zp'=>self::PACKAGE,'doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		$this->_url['MasterFRG_Libao_add']=Tools::url(CONTROL,'Libao',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['OperationFRG_Libao_syncard']=Tools::url('HaiDaoOperation','Libao',array('zp'=>self::PACKAGE,'doaction'=>'syn_card'));
		$this->_url['OperationFRG_Libao_serverSyn']=Tools::url('HaiDaoOperation','Libao',array('zp'=>self::PACKAGE,'doaction'=>'serverSyn'));
		$this->_view->assign('url',$this->_url);
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
			$sendParams['zp']	=	'HaiDao';
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
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']),) );
					$this->_view->assign('pageBox',$helpPage->show());
				}
			}
			$this->_view->assign('pageSize',Tools::getLang('PAGE_OPTION',__CLASS__));
		}
		$this->_utilMsg->createPackageNavBar();
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
			$sendParams['zp']	=	'HaiDao';
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
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']), 'url' => $currUrl,'zp'=>'HaiDao', ) );
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/UserInquireMsg.html'));
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
			$sendParams['zp']	=	'HaiDao';
			if ($data){
				$currUrl = Tools::url ( CONTROL, 'UserInquire', $sendParams );
				if ($data['data']['list']){
					foreach ($data['data']['list'] as &$value){
						$value['url_ask']=Tools::url('Verify','OrderVerify',array(
																			'game_type_id'=>self::GAME_ID,
																			'operator_id'=>$this->_operatorId,
																			'game_server_id'=>$_REQUEST['server_id'],
																			'game_user_id'=>$value['UserId'],
																			'user_account'=>$value['UserName'],
																			'user_nickname'=>$value['VUserName'],));

						$value['url_emp']=Tools::url(CONTROL,'EmpShopList',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>1,'PageSize'=>10));
						$value['url_shop']=Tools::url(CONTROL,'EmpShopList',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>2,'PageSize'=>10));
						$value['url_tools']=Tools::url(CONTROL,'EmpShopList',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'Query[TypeItems]'=>3,'PageSize'=>10));
						$value['url_msg']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'Query[Items]'=>3,'Query[start]'=>$value['Id'],'PageSize'=>10,'doaction'=>'message'));
						$value['url_send_msg']=Tools::url(CONTROL,'SendMail',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'UserId[]'=>$value['Id'],'lock'=>true,'user_name'=>$value['UserName'],'nick_name'=>$value['VUserName']));
						$value['url_event_list']=Tools::url(CONTROL,'EventList',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'UserId'=>$value['Id'],));
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
				//print_r($data['data']['list']);
				$this->_view->assign('dataList',$data['data']['list']);
				$this->_view->assign('selectedQuery',$data['data']['query']);
				$this->_view->assign('selectedPageSize',$data['data']['PageSize']);
				$this->_view->assign('companyNum',$data['data']['CompanyNum']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}

		}
		$this->_utilMsg->createPackageNavBar();
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
				$currUrl = Tools::url ( CONTROL, 'EventList', array_merge(array('zp'=>self::PACKAGE),$theData['QueryString']) );
				$this->_loadCore('Help_Page');
				$helpPage = new Help_Page ( array ('total' => $theData['TotalNum'], 'perpage' => ($theData['PageSize'] ), 'url' => $currUrl) );
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
		$this->_utilMsg->createPackageNavBar();
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
					$this->_utilMsg->createPackageNavBar();
					$this->_view->assign('sending',1);
					$this->_view->assign('message',$data['message']);
					$this->_view->assign('cause',$_POST['cause']);
					$this->_view->assign('MsgTitle',$_POST['MsgTitle']);
					$this->_view->assign('MsgContent',$_POST['MsgContent']);
					$this->_view->display();
				}else{
					//					$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
					//					$this->_modelFrgLog->add($_POST,7); //全服发送短信.
					$this->_utilMsg->showMsg(Tools::getLang('RETURN_MESSAGE','Common').':'.$data['message']);
				}
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'));
			}
		}else {
			$this->_utilMsg->createPackageNavBar();
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
						//						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						//						$this->_modelFrgLog->add($_POST,4);
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
		$this->_utilMsg->createPackageNavBar();
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
					$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
				}else {
					$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit')));
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/NoticeEdit.html'));
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
					$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
				}else {
					$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add')));
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/NoticeAdd.html'));
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
						$list['url_edit']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'edit','Id'=>$list['Id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$data['data']['list']);
			}else{
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/NoticeList.html'));
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
						//						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						//						$this->_modelFrgLog->add($_POST,1);

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

						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'add')));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/AddDonttalk.html'));
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

						$value['detail_url'] = Tools::url(CONTROL,'Donttalk',array('zp'=>self::PACKAGE,'doaction'=>'detail','game_server_id'=>$_REQUEST['server_id'],'game_user_id'=>$value['UserId']));

					}
					$this->_view->assign('dataList',$data['data']['UserData']);
				}
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createPackageNavBar();
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
		$this->_view->assign('tplServerSelect',self::PACKAGE.'/'.self::MASTER.'/GameOperateLogDetail.html');
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
					$value['url_edit']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
				}
				$this->_view->assign('dataList',$data['data']['Result']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/Reward.html'));
		$this->_utilMsg->createPackageNavBar();
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
			$postArr=array();
			$postArr['server_id']=$_POST['server_id'];
			unset($_POST['server_id']);
			$postArr['type']=8;//奖励触发
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['send_action']=array('c'=>'Reward','a'=>'Add','Action'=>'Save');
			$post	=	$_POST;
			$s	=	array(
				"1"	=>	'大于',
				"2"	=>	'小于',
				"3"	=>	'等于',
			);
			if(!empty($_POST["ToolIdName"])){
				foreach($_POST["ToolIdName"] as $k=>$_msg){
					$msg	.=	$_msg."(".$_POST["ToolNum"][$k].");";
				}
			}

			if(!empty($_POST["GetObjName"])){
				foreach($_POST["GetObjName"] as $k=>$_msg){
					$GetObjName	.=	$_msg.$s[$_POST["GetOpcode"][$k]].$_POST["GetValue"][$k].";";
				}
			}

			$s_2	=	array(
				'1'	=>	'增加',
				'2'	=>	'减少',
				'3'	=>	'改为',
				'4'	=>	'增加倍数',
			);
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			if(!empty($_POST["EffObjName"])){
				foreach($_POST["EffObjName"] as $k=>$_msg){
					$EffObjName	.=	$_msg.$s_2[$_POST["EffOpcode"][$k]].$_POST["EffValue"][$k].";";
					if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
						if($_POST["EffValue"][$k] >20000 && $_msg=='金币'){
							$this->_utilMsg->showMsg("不能过20000",-1);
						}
					}
				}
			}

			$playerInfo = array(0=>'玩家ID',1=>'昵称',2=>'账号');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$postArr['cause'].'</div>';
			$apply_info.='获得条件：'.$GetObjName."<br>";
			$apply_info.='申请道具：'.$msg.";".$EffObjName;
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
						'type'=>12,
						'server_id'=>$postArr['server_id'],
						'operator_id'=>$gameser_list[$postArr['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$postArr['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>'php/interface.php',
							'post_data'=>$post,
							'get_data'=>array(
								'm'=>'Admin',
								'c'=>'Reward',
								'a'=>'Add',
								'Action'=>'Save',
								'__hj_dt'=>'RpcSeri',
								'__sk'=>array(
									'cal_local_object'=>'Util_FRGInterface',
									'cal_local_method'=>'getFrgSk',
									'params'=>NULL,
			),
			),
							'call'=>array(
								'cal_local_object'=>'Util_ApplyInterface',
								'cal_local_method'=>'FrgSendReward',
			)
			),
						'receiver_object'=>array($_REQUEST['server_id']=>''),
						'player_type'=>0,
						'player_info'=>0,
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/AddReward.html'));
		$this->_utilMsg->createPackageNavBar();
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
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,'Reward',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/EditReward.html'));
		$this->_utilMsg->createPackageNavBar();
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
				foreach($data['data']['rv'] as &$sub){
					$sub['detail_url'] = Tools::url(CONTROL,'LockUsers',array('zp'=>self::PACKAGE,'doaction'=>'detail','game_server_id'=>$_REQUEST['server_id'],'game_user_id'=>$sub['UserId']));
				}
				$this->_view->assign('dataList',$data['data']['rv']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->assign('URL_LockUserAdd',Tools::url(self::OPT,'LockUserAdd',array('zp'=>self::PACKAGE)));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/LockUsers.html'));
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
						//						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						//						$this->_modelFrgLog->add($_POST,3);

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

						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,'LockUsers',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));

					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
			}
		}
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/AddLockUsers.html'));
		$this->_utilMsg->createPackageNavBar();
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/GameOperateLogDetail.html'));//设置使用自定义模板
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
					//warren 封Ip日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
					array('操作','<font style="color:#F00">封IP</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('原因',$_POST['cause']),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore(array("UserId"=>"-1"),5,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
					//================================

					if ($data['msgno']==1){
						//						$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
						//						$this->_modelFrgLog->add($_POST,2);
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}else {//显示表单
				$this->_utilFRGInterface->setGet(array('c'=>'Conf','a'=>'CommonVartype'));
				$data	=	$this->_utilFRGInterface->callInterface();
				//print_r($data['data']['VarCat']['ForbiddenIPList']);
				if ($data){
					$this->_view->assign('data',$data['data']['VarCat']['ForbiddenIPList']);
				}else {
					$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
				}
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 玩家数值控制
	 */
	public function actionUserData(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['edit_user']){//改变玩家信息
				$apply_info ='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<申请原因##
				$apply_info.="<div>申请原因：<br>{$_POST['cause']}</div>";
				##>>申请原因##
				$apply_info.='</div>';

				$apply_info.='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<内容##
				$apply_info.= "玩家ID(<font color='#FF0000'>{$_POST['userid']}</font>)<br>";
				$apply_info.= "岛屿等级(<font color='#FF0000'>{$_POST['company']['Level']}</font>)、";
				$apply_info.= "声望(魅力)(<font color='#FF0000'>{$_POST['user']['Fame']}</font>)、";
				$apply_info.= "建造点(<font color='#FF0000'>{$_POST['company']['ShopPoint']}</font>)、";
				$apply_info.= "功勋(<font color='#FF0000'>{$_POST['user']['Chop']}</font>)、";
				$apply_info.= "市场交易币(<font color='#FF0000'>{$_POST['user']['MarketCurrency']}</font>)、";
				$apply_info.= "声望值(<font color='#FF0000'>{$_POST['user']['UCofcPoints']}</font>)、";
				##>>内容##
				$apply_info.='</div>';
				$postData = $_POST;
				//$postData['userflag'] = 'txtids';
				unset($postData['server_id'],$postData['cause'],$postData['user_edit']);
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>25,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$postData,
						'get_data'=>array(
							'm'=>'Admin',
							'c'=>'UserData',
							'a'=>'UserTune',
							'doaction'=>'save',
							'__hj_dt'=>'RpcSeri',	//'__hj_dt'=>'_DP_JSON',		
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
				)
				),
					'receiver_object'=>array($_POST['server_id']=>''),
					'player_type'=>1,
					'player_info'=>$_POST['userid'],
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 用户工厂修改，能源基地数值修改
	 */
	public function actionUserFactory(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$postData = $_POST;
				//$postData['userflag'] = 'txtids';
				unset($postData['server_id'],$postData['cause'],$postData['user_edit']);

				$URL_detail=Tools::url(
				CONTROL,
				ACTION,
				array(
						'zp'=>self::PACKAGE,
						'server_id'=>$_REQUEST['server_id'],
						'request'=>'read',
						'auditData'=>base64_encode(serialize($postData))
				)
				);
				$apply_info ='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				$apply_info.="<div>申请原因：{$_POST['cause']}</div>";
				$apply_info.="<div><a href='{$URL_detail}'>详情</a></div>";
				$apply_info.='</div>';
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>27,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$postData,
						'get_data'=>array(
							'm'=>'Admin',
							'c'=>'UserData',
							'a'=>'FactoryTune',
							'doaction'=>'save',
							'__hj_dt'=>'RpcSeri',	//'__hj_dt'=>'_DP_JSON',		
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
				)
				),
					'receiver_object'=>array($_POST['server_id']=>''),
					'player_type'=>1,
					'player_info'=>$_POST['userid'],
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
			}elseif ($_POST['search_user'] || $_GET['request']=='read') {//搜索玩家信息
				if ($_GET['request']=='read'){//如果为只读消息的话,表示察 看
					if($_GET['auditData']){
						$_GET['auditData'] =  unserialize(base64_decode($_GET['auditData']));
					}
					if($_GET['auditData']){
						$_POST['userflag']='txtids';
						$_POST['txtids']=$_GET['auditData']['userid'];
						$this->_view->assign('postData',$_GET['auditData']);
						$this->_view->assign('read',true);
					}
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
						$this->_view->assign('selectSysDeviceLevel',$data['data']['SysDeviceLevel']);	//工厂设备等级 selec
						$this->_view->assign('selectSysStoreLevel',$data['data']['SysStoreLevel']);	//仓库等级select
					}
				}else {
					$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
				}
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 商会修改，联盟修改
	 */
	public function actionUserCofcTune(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			if ($_POST['user_edit']){//改变玩家信息
				$apply_info ='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<申请原因##
				$apply_info.="<div>申请原因：<br>{$_POST['cause']}</div>";
				##>>申请原因##
				$apply_info.='</div>';

				$apply_info.='<div style="padding:3px; margin:3px; border:1px dashed #000">';
				##<<内容##
				$apply_info.= "玩家ID(<font color='#FF0000'>{$_POST['userid']}</font>)<br>";
				$apply_info.= "联盟级别(<font color='#FF0000'>{$_POST['cofc']['Level']}</font>)、";
				$apply_info.= "联盟砖头(<font color='#FF0000'>{$_POST['cofc']['Stone']}</font>)、";
				$apply_info.= "联盟基金(<font color='#FF0000'>{$_POST['cofc']['Assets']}</font>)、";
				$apply_info.= "联盟发展值(<font color='#FF0000'>{$_POST['cofc']['Points']}</font>)、";
				$apply_info.= "联盟密码(<font color='#FF0000'>{$_POST['cofc']['PassWord']}</font>)、";
				$apply_info.= "联盟船坞等级加上(<font color='#FF0000'>{$_POST['StructureLevel'][5]}</font>)、";
				$apply_info.= "联盟祭坛等级加上(<font color='#FF0000'>{$_POST['StructureLevel'][6]}</font>)、";
				##>>内容##
				$apply_info.='</div>';
				$postData = $_POST;
				//$postData['userflag'] = 'txtids';
				unset($postData['server_id'],$postData['cause'],$postData['user_edit']);
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>26,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$postData,
						'get_data'=>array(
							'm'=>'Admin',
							'c'=>'UserData',
							'a'=>'CofcTune',
							'doaction'=>'save',
							'__hj_dt'=>'RpcSeri',	//'__hj_dt'=>'_DP_JSON',		
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
				)
				),
					'receiver_object'=>array($_POST['server_id']=>''),
					'player_type'=>1,
					'player_info'=>$_POST['userid'],
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
		$this->_utilMsg->createPackageNavBar();
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
		$this->_utilMsg->createPackageNavBar();
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
		$this->_utilMsg->createPackageNavBar();
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
					$url = Tools::url ( CONTROL, ACTION ,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
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
		$this->_utilMsg->createPackageNavBar();
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
				unset($_POST['submit']);
				$_POST['ToolId']=$_POST['Tool'];
				$_POST['ToolIdName']=$_POST['ToolName'];
				$_POST['ToolIdImg']=$_POST['ToolImg'];
				unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
				$serverId=$_POST['server_id'];
				unset($_POST['server_id']);
				$postData = $_POST;
				$gameClass = $this->_getGlobalData(self::GAME_ID,'game');
				$apply_info = "申请原因<br>{$_POST['cause']}<br>".$gameClass->AddAutoCause($postData,1);	//1的类型是奖励发送
				unset($_POST['cause']);
				$playerType = array(0=>'1',1=>'2',2=>'3');	//-1/0/1/2/3 混搭/无玩家/UserId/UserName/NickName
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
					'type'=>3,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>2,	//2	html
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$postData,
						'get_data'=>array(
							'm'=>'Admin',
							'c'=>'Reward',
							'a'=>'SendAward',
							'Action'=>'Save',
							'__hj_dt'=>'RpcSeri',	//'__hj_dt'=>'_DP_JSON',		
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
				),
				),
						'call'=>array(
							'cal_local_object'=>'Util_ApplyInterface',
							'cal_local_method'=>'FrgSendReward',
				)
				),
					'receiver_object'=>array($serverId=>''),
					'player_type'=>$playerType[$_POST['ReceiveType']],
					'player_info'=>$_POST['UserIds'],
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
		$this->_utilMsg->createPackageNavBar();
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
						$this->_utilMsg->showMsg($data['message'],1,Tools::url('HaiDaoOperation','Libao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/EditLibao.html'));
		$this->_utilMsg->createPackageNavBar();
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
					$value['url_edit']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
					$value['word_active']=$value['Active']?Tools::getLang('YES','Common'):Tools::getLang('NO','Common');
					$value['word_disrepeat']=$value['DisRepeat']?Tools::getLang('NOT_REPEAT','Common'):Tools::getLang('REPEAT','Common');
				}
				$this->_view->assign('dataList',$data['data']['Data']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/Libao.html'));
		$this->_utilMsg->createPackageNavBar();
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
						$this->_utilMsg->showMsg($data['message'],1,Tools::url('OperationFRG','Libao',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
		$this->_utilMsg->createPackageNavBar();
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
			unset($sendParams['submit']);
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
					$helpPage = new Help_Page ( array ('total' => $data ['data'] ['TotalNum'], 'perpage' => ($data ['data'] ['PageSize']) ) );
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 导入/导出 卡号列
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
							$this->_utilMsg->showMsg($msg,1,Tools::url(CONTROL,'CardList',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),null);
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),null);
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
					$this->_utilMsg->createPackageNavBar();
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
			$postArr=array();
			$postArr['server_id']=$_POST['server_id'];
			$postArr['type']=7;//礼包生成卡号
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['post_data']=$_POST;
			$gameser_list = $this->_getGlobalData('gameser_list');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$postArr['cause'].'</div>';
			$apply_info.="申请类型：".$_POST["TypeName"]."<br>";
			$apply_info.="申请数量：".$_POST["Number"]."<br>";
			$applyData = array(
					'type'=>13,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,//$apply_info
					'send_type'=>2,	//2	http
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$postArr['post_data'],
						'get_data'=>array(
							'c'=>'Card',
							'a'=>'Create',
							'doaction'=>'save',
							'm'=>'Admin',
							'__hj_dt'=>'RpcSeri',
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
			),
			),
						'call'=>array(
								'cal_local_object'=>'Util_ApplyInterface',
								'cal_local_method'=>'FrgSendReward',
			)
			),
					'receiver_object'=>array($_REQUEST['server_id']=>''),
					'player_type'=>0,
					'player_info'=>0,
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( $applyInfo===true ){
				$URL_CsIndex = Tools::url('Apply','CsIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg("申请失败",-1);
			}
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
			$this->_utilMsg->createPackageNavBar();
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
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_POST['server_id'])));
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
					$this->_utilMsg->createPackageNavBar();
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/PayLibaoAdd.html'));
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
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_POST['server_id'])));
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
					$this->_utilMsg->createPackageNavBar();
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/PayLibaoEdit.html'));
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
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_POST['server_id'])));
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

					$this->_utilMsg->createPackageNavBar();
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/PayLibaoProportion.html'));
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
								$list['url_edit']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'edit','Id'=>$list['Id'],'server_id'=>$_REQUEST['server_id']));
							}
							$this->_view->assign('dataList',$data['data']['Data']);
						}
					}else {
						$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
					}
				}
				$this->_utilMsg->createPackageNavBar();
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
				if ($value['game_type_id']!=self::GAME_ID)unset($gameServerList[$key]);
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
			$this->_utilMsg->createPackageNavBar();
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
							//							$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
							//							$this->_modelFrgLog->add($_POST,5);
							if ($data['backparams'] && is_array($data['backparams'])){
								$data['message'].=Tools::getLang('SEND_ERRORMSG',__CLASS__).implode('，',$data['backparams']).'</b>';
							}
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],null)));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,2);
						}
					}else {
						$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
					}
				}else {
					if ($_POST['UserId'])$this->_view->assign('users',implode(',',$_POST['UserId']));
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/AddDrillmaster.html'));
					$this->_utilMsg->createPackageNavBar();
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
				$this->_utilMsg->createPackageNavBar();
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
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/ActivityAdd.html'));
					$this->_utilMsg->createPackageNavBar();
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
							$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
						}else {
							$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
					$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/ActivityEdit.html'));
					$this->_utilMsg->createPackageNavBar();
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
						$this->_utilMsg->showMsg($data['message'],1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
					}else {
						$this->_utilMsg->showMsg($data['message'],-2,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
							$list['url_edit']=Tools::url(CONTROL, ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'],'Id'=>$list['Id'],'doaction'=>'edit'));
						}
						$this->_view->assign('dataList',$data['data']['Activities']);
					}else {
						$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
					}
				}
				$this->_utilMsg->createPackageNavBar();
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
			default:{
				$this->_spActivityIndex();
				return ;
			}
		}
	}

	/**
	 * 多服务器同步
	 */
	private function _spActivityServerSyn(){
//优化的代码------------------------
		$fileCacheName = get_class($this).'_spActivityServerSyn';
		if($_GET['syncnum']){
			$_POST = $this->_f($fileCacheName);
		}
//end------------------------------
		if ( ($this->_isPost() && $_POST['submit']) || $_GET['syncnum'] ){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_SELECT_ERROR1',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('SPECIAL_AVTIVE_SYN_SELECT_ERROR2',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);

			//去除没有值的参数（把富人国兼容至其他游戏）
			if(is_array($_POST['data'])){
				foreach ($_POST['data'] as &$sub){
					if(is_array($sub)){
						foreach($sub as $subkey =>$subFile){
							if(is_string($subFile) && strlen($subFile)==0){
								unset($sub[$subkey]);
							}
						}
					}
				}
			}
//优化代码---------------------------------------

			$postArr=array('ActivityArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');

			//1次最多处理5个地址
			$AddHttpNum=0;
			for ($i=0;$i<5;$i++){
				$serverId = array_shift($serverids);
				if($serverId){
					if( isset($_POST['failserverids'][$serverId]) && $_POST['failserverids'][$serverId]>5 ){
						//失败超过5次的，就不再尝试
						
					} else {
						$AddHttpNum += 1;
						$this->_utilApiFrg->addHttp($serverId,array('c'=>'Activity','a'=>'AddSpecialActivity','doaction'=>'receive'),$postArr);
					}
				}
			}
			
			$serverList=$this->_getGlobalData('gameser_list');
			if( $AddHttpNum ){
				$this->_utilApiFrg->send();
				$getResult=$this->_utilApiFrg->getResults();
			
				$sendStatusMsgs='';
				foreach ($getResult as $key=>$value){
					if ($value['msgno']==1){
						//成功
						$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_SUCCESS','Common');
						$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
						if( isset($_POST['failserverids'][$key]) ){
							unset($_POST['failserverids'][$key]);
						}
					}else {
						//失败
						array_unshift($serverids,$key);
						$_POST['failserverids'][$key] += 1;
					}
				}
			}

			if( count($serverids) ){
				$_POST['server_ids'] = $serverids;
				$this->_f($fileCacheName,$_POST);
				$syncnum = intval($_GET['syncnum'])+1;
				$sendStatusMsgs .= '跳转同步下一批机器';
				$this->_utilMsg->showMsg($sendStatusMsgs,1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'serverSyn','server_id'=>$_GET['server_id'],'syncnum'=>$syncnum)),1);
			} else {
				$sendStatusMsgs .= '全部同步完';
				if(is_file(CACHE_DIR.'/'.$fileCacheName.'.cache.php')){
					$this->_f($fileCacheName,null);
				}
				if( count($_POST['failserverids']) && is_array($_POST['failserverids']) ){
					$sendStatusMsgs .='<br>但以下机器同步失败超过5次,请单独检查<br>';
					foreach ($_POST['failserverids'] as $key=>$num){
						$sendStatusMsgs .="ID:<font color='#00CC00'>{$key}</font>;<b>{$serverList[$key]['full_name']}</b><br>";
					}
				} elseif ( $_POST['failserverids'] ){
					$sendStatusMsgs .='程序有误,出错数据为'.serialize($_POST['failserverids']).'<br>';
				}
				$this->_utilMsg->showMsg($sendStatusMsgs,1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE)),100);
			}
//end-------------------------------------------
/*原本的代码
			$postArr=array('ActivityArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,array('c'=>'Activity','a'=>'AddSpecialActivity','doaction'=>'receive'),$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			#------生成msg------#
			$serverList=$this->_getGlobalData('gameser_list');
			$sendStatusMsgs='';
			foreach ($getResult as $key=>$value){
				if ($value['msgno']==1){
					$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_SUCCESS','Common');
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
				}else {
					$value['message']=$value['message']?$value['message']:Tools::getLang('OPERATION_FAILURE','Common');
					$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
				}
			}
			#------生成msg------#
			$this->_utilMsg->showMsg($sendStatusMsgs,1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE)),100);
*/
		}else {
			#------多服务器选择列表------#
			$gameServerList=$this->_getGlobalData('gameser_list');
			foreach ($gameServerList as $key=>&$value){
				if ($key==100 || $key==200){//100和200是特殊服务器,不显示
					unset($gameServerList[$key]);
					continue;
				}
				if ($value['game_type_id']!=self::GAME_ID)unset($gameServerList[$key]);
			}
			$this->_view->assign('gameServerList',json_encode($gameServerList));
			$this->_view->assign('tplServerSelect','HaiDao/HaiDaoOperation/ServerSelect.html');
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
			$this->_view->set_tpl(array('body'=>'HaiDao/HaiDaoOperation/SpecialActivityServerSyn.html'));
			$this->_utilMsg->createPackageNavBar();
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
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/SpecialActivityAdd.html'));
		$this->_utilMsg->createPackageNavBar();
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
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])));
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/SpecialActivityEdit.html'));
		$this->_utilMsg->createPackageNavBar();
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
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Activity','a'=>'ListSpecialActivity',"PageSize"=>PAGE_SIZE,"Page"=>max(1,intval($_GET['page']))));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$data["data"]["TotalPage"]*PAGE_SIZE,'perpage'=>PAGE_SIZE));
			if ($data){
				$open=array('0'=>Tools::getLang('CLOSE','Common'),'1'=>Tools::getLang('OPEN','Common'));
				$show = array('0'=>Tools::getLang('NOT_DISPLAY','Common'),'1'=>Tools::getLang('DISPLAY','Common'));
				$checkType=array('1'=>Tools::getLang('IN_PROGRESS','Common'),'3'=>Tools::getLang('ENDED','Common'));
				$type=$data['data']['ActivityTypes'];
				if ($data['data']['Activities']){
					foreach ($data['data']['Activities'] as &$value){
						$value['url_edit']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit'));
						$value['url_rest']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'Id'=>$value['Id'],'server_id'=>$_REQUEST['server_id'],'doaction'=>'rest'));
						$value['word_type']=$type[$value['Identifier']]['Name'];
						$value['word_is_open']=$open[$value['IsOpen']];
						$value['word_is_show']=$show[$value['IsShow']];
						$value['url_onoff']=Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'onoff','Id'=>$value['Id'],'IsOpen'=>$value['IsOpen']?0:1,'server_id'=>$_REQUEST['server_id']));

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
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('dataList',$data['data']['Activities']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/SpecialActivity.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
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
				$this->_utilMsg->createPackageNavBar();
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
			$sendParams['zp']	=	self::PACKAGE;
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
		$this->_utilMsg->createPackageNavBar();
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
				if($data['data']['UserLostList']){
					ksort($data['data']['UserLostList']);
				}else{
					$data['data']['UserLostList'] = array();
				}
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
		$this->_view->set_tpl(array('body'=>self::PACKAGE.'/'.self::MASTER.'/ServerStats.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	/**
	 * 用户登录，游戏登录
	 */
	public function actionGameLogin(){
		if ($this->_isPost()){
			$serverList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);

			//			$this->_modelFrgLog=$this->_getGlobalData('Model_FrgLog','object');
			//			$addArr=array('cause'=>$_POST['cause'],'server_name'=>$serverList[$_POST['server_id']]['full_name'],'user_name'=>$_POST['user_name']);
			//			$this->_modelFrgLog->add($addArr,6);//6登陆游戏

			$serverUrl=$serverList[$_POST['server_id']]['server_url'];
			$serverUrl="{$serverUrl}php/interface.php?m=User&c=Login&a=login&__hj_dt=HtmlTemplate&job=1&";
			$operatorId=$serverList[$_POST['server_id']]['operator_id'];

			#获取key
			$gameObject = $this->_getGlobalData(self::GAME_ID,'game');
			$gameOperatorExt = $gameObject->getOptConf($operatorId);
			$sysKey = isset($gameOperatorExt['syskey'])?$gameOperatorExt['syskey']:'';

			if(empty($_POST['user_name']) && empty($_POST['user_username'])){
				$this->_utilMsg->showMsg("没有填写账号或角色名",-1);
			}
			if($_POST['user_name']){
				$userName="Uname=".trim(strval($_POST['user_name']));
			}else{
				$userName="VName=".trim(strval($_POST['user_username']));
			}

			$time='123456789000';
			$gameId='1';
			$serverid='1';
			$domainId='1';
			$al='1';
			$from='1';
			$siteurl='1';
			$apiUserId='1';
			$gmSign=md5("{$userName}&userid={$apiUserId}&GameId={$gameId}&ServerId={$serverid}&Key={$sysKey}&Time={$time}&al={$al}&from={$from}&siteurl={$siteurl}&job=1");
			$serverUrl.="Sign={$gmSign}&{$userName}&userid={$apiUserId}&GameId={$gameId}&ServerId={$serverid}&Time={$time}&al={$al}&from={$from}&siteurl={$siteurl}";
			header('Location: '.$serverUrl);
			exit();

		}else {
			if ($_GET['operator_id']){
				$serverList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
				foreach ($serverList as $key=>&$value){
					if ($value['operator_id']!=$_GET['operator_id'])unset($serverList[$key]);
				}
				$this->_view->assign('dataList',$serverList);
			}

			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
			$this->_utilMsg->createPackageNavBar();
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
					'Honor5'=>intval($_POST['Honor5']),
				    'Honor6'=>intval($_POST['Honor6']),
				);
				$apply_info="<div>申请原因：{$_POST['cause']}</div>";
				$apply_info.="<div>UerId：{$post['UserId']}</div>";
				$apply_info.="<div>海域战：{$post['Honor1']}</div>";
				$apply_info.="<div>讨伐：{$post['Honor2']}</div>";
				$apply_info.="<div>皇家航海赛：{$post['Honor3']}</div>";
				$apply_info.="<div>探险：{$post['Honor4']}</div>";
				$apply_info.="<div>征战成就：{$post['Honor5']}</div>";
				$apply_info.="<div>传奇成就：{$post['Honor6']}</div>";
				$post = array_filter($post);
				$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
				$applyData = array(
						'type'=>21,
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
			$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
			$applyData = array(
				'type'=>29,
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
			$this->_utilMsg->createPackageNavBar();
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();

	}

	public function actionHello(){

		$andy   = new StdClass;
		$andy->a = 1;
		$andy->b = 2;
		$andy->c = 3;
		$andy = (array)$andy;
		$this->_view->assign('var',$andy);
		$this->_view->display();

	}


	public function actionPropsModify(){
		switch ($_REQUEST['doaction']){
			case 'apply':
				$this->_itemsPropsApply();
				return;
			default:
				$this->_itemsPropsList();
				return;
		}
	}

	private function _itemsPropsList(){
		$this->_createServerList();
		if($_REQUEST['server_id']){
			$player = array(
				'txtids'	=>	trim($_GET['playerId']),	//玩家id:0
				'txtnames'	=>	trim($_GET['playerAccount']),	//玩家账号:1
				'txtvnames'	=>	trim($_GET['playerNickname']),	//玩家昵称:2
			);

			$ReceiveType	=	array(
				'0'	=>	trim($_GET['playerId']),	//玩家id:0
				'1'	=>	trim($_GET['playerAccount']),	//玩家账号:1
				'2'	=>	trim($_GET['playerNickname']),	//玩家昵称:2
			);

			$ReceiveType	=	array_filter($ReceiveType);
			$playerData = array(
					'ReceiveType'=>key($ReceiveType),
					'Name'=>current($ReceiveType),
			);
			$player 		= 	array_filter($player);
			if($player){


				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'ToolTune','doaction'=>'query'));
				$postData = array(
				key($player)=>current($player),
					'userflag'=>key($player),
				);
				$this->_utilFRGInterface->setPost($postData);
				$data=$this->_utilFRGInterface->callInterface();
				$this->_view->assign("player",$playerData);
				$this->_view->assign('datalist',$data["data"]["UserTools"]);
					
			}
		}
		$this->_view->assign("submiturl",Tools::url(CONTROL,ACTION,array('doaction'=>"apply","zp"=>"HaiDao","server_id"=>$_REQUEST["server_id"])));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	private function _itemsPropsApply(){
		if($this->_isPost()){
			$player 	= array(
				'0'=>trim($_POST['playerId']),	//玩家id:0
				'1'=>trim($_POST['playerAccount']),	//玩家账号:1
				'2'=>trim($_POST['playerNickname']),	//玩家昵称:2
			);
			$player 	= array_filter($player);
			$postData 	= array(
						'ReceiveType'=>key($player),
						'Name'=>current($player),
			);
			$toolimg	=	array();
			$toolname	=	array();
			$toolnameen	=	array();
			$buycount	=	array();
			$toolid		=	array();
			if($_POST["checkbox"]){
				foreach($_POST['checkbox'] as $_msg){
					$toolid[]		=	$_POST["checkbox"][$_msg];
					$toolimg[]		=	$_POST["toolimg"][$_msg];
					$toolname[]		=	$_POST["toolname"][$_msg];
					$toolnameen[]	=	$_POST["toolnameen"][$_msg];
					$buycount[]		=	$_POST["buycount"][$_msg];
				}
			}
			$post		=	array(
				'UserIds'		=>	$postData["Name"],
				'ReceiveType'	=>	$postData["ReceiveType"],
				'ToolId'		=>	$toolid,
				'ToolNum'		=>	$buycount,
				'ToolIdName'	=>	$toolname,
				'ToolIdEName'	=>	$toolnameen,
				'ToolIdImg'		=>	$toolimg,
			);
			if($toolname){
				foreach($toolname as $keyItemId =>$delValue){
					$itemNames .= $delValue."(".$buycount[$keyItemId].")&nbsp;&nbsp;";
				}
			}
			$playerInfo = array(0=>'玩家ID',1=>'昵称',2=>'账号');
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>'.$playerInfo[$postData["ReceiveType"]].'：</div><div style="padding-left:10px;">'.$postData["Name"].'</div>';
			$apply_info.="<div>修改道具：</div><div style='padding-left:10px;'>".$itemNames.'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			$applyData = array(
						'type'=>11,
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
								'a'=>'ToolDel',
								'm'=>'Admin',
								'__hj_dt'=>'RpcSeri',
								'__sk'=>array(
									'cal_local_object'=>'Util_FRGInterface',
									'cal_local_method'=>'getFrgSk',
									'params'=>NULL,
			),
			),
							'call'=>array(
								'cal_local_object'=>'Util_ApplyInterface',
								'cal_local_method'=>'FrgSendReward',
			)
			),
						'receiver_object'	=>array($_REQUEST['server_id']=>''),
						'player_type'		=>$postData["ReceiveType"],
						'player_info'		=>$postData["Name"],
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

	}


	public function actionToolRec(){
		switch ($_REQUEST['doaction']){
			case 'rec':
				$this->_itemsPropsRec();
				return;
			default:
				$this->_itemsToolRecList();
				return;
		}
	}

	private function _itemsToolRecList(){
		$this->_createServerList();
		if($_REQUEST['server_id']){
			$player = array(
				'txtids'	=>	trim($_GET['playerId']),	//玩家id:0
				'txtnames'	=>	trim($_GET['playerAccount']),	//玩家账号:1
				'txtvnames'	=>	trim($_GET['playerNickname']),	//玩家昵称:2
			);
			$player 		= 	array_filter($player);
			if($player){
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
				$this->_utilFRGInterface->setGet(array('c'=>'UserData','a'=>'ShowToolDeleted'));
				$postData = array(
				key($player)=>current($player),
					'userflag'=>key($player),
				);

				$this->_utilFRGInterface->setPost($postData);
				$data=$this->_utilFRGInterface->callInterface();
				$this->_view->assign('username',current($player));
				$this->_view->assign('datalist',$data["data"]["UserTools"]);
					
			}
			$this->_view->assign("serverid",$_REQUEST['server_id']);
		}
		$this->_view->assign("ajaxurl",Tools::url(CONTROL,'ToolRec',array("zp"=>"HaiDao","doaction"=>"rec",'server_id'=>$_REQUEST['server_id'])));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	private function _itemsPropsRec(){
		if(!empty($_POST['rec'])){
			$recstr	=	array("RecToolId"=>implode(',',$_POST['rec']));
			foreach($_POST['rec'] as $_msg){
				$cause	.=	$_POST['toolsname'][$_msg]."(".$_POST['buycount'][$_msg]."),";
			}
		}else{
			$this->_utilMsg->showMsg("你没有选择任何数据",-1);
		}
		$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
		$apply_info.='<div>用户：'.$_POST['username'].'</div>';
		$apply_info.="<div>回复道具：</div><div style='padding-left:10px;'>".$cause.'</div>';
		$gameser_list = $this->_getGlobalData('gameser_list');
		$applyData = array(
					'type'=>17,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,//$apply_info
					'send_type'=>2,	//2	http
					'send_data'=>array(
						'url_append'=>'php/interface.php',
						'post_data'=>$recstr,
						'get_data'=>array(
							'c'=>'UserData',
							'a'=>'ToolRec',
							'm'=>'Admin',
							'__hj_dt'=>'RpcSeri',
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
		),
		),
						'call'=>array(
							'cal_local_object'=>'Util_ApplyInterface',
							'cal_local_method'=>'FrgSendReward',
		)
		),
					'receiver_object'	=>array($_REQUEST['server_id']=>''),
					'player_type'		=>0,
					'player_info'		=>$_POST['username'],
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

	public function actionAddChallengeCount(){
		$this->_createServerList();

		if ($this->_isPost()){

			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>增加一次迷雾海域挑战次数</div>';
			$apply_info.='<div>用户：'.$_POST['UserId'].'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			unset($_POST["cause"]);
			$applyData = array(
						'type'=>23,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>'php/interface.php',
							'post_data'=>$_POST,
							'get_data'=>array(
								'c'=>'UserData',
								'a'=>'DataUpdate',
								'm'=>'Admin',
								'type'=>'33',
								'__hj_dt'=>'RpcSeri',
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
			),
			),
						'call'=>array(
							'cal_local_object'=>'Util_ApplyInterface',
							'cal_local_method'=>'FrgSendReward',
			)
			),
					'receiver_object'	=>array($_REQUEST['server_id']=>''),
					'player_type'		=>0,
					'player_info'		=>$_POST['UserId'],
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

		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 点亮成长树任务
	 */
	public function actionLightenTree(){
		$this->_createServerList();
		if($_REQUEST['server_id'] && $this->_isPost()){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$postData = array(
				'UserId'=>trim($_POST['UserId']),	//玩家Id
				'Floor'=>trim($_POST['Floor']),	//成长树层级
				'Order'=>trim($_POST['Order']),	//层级中的第order个任务
			);
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'UserData','a'=>'DataUpdate','type'=>42),$postData);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if($data && is_array($data)){
				$this->_utilMsg->showMsg($data['message']?$data['message']:'操作完成',1);
			}
			$this->_utilMsg->showMsg("操作失败",-1);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 点亮端午树
	 */
	public function actionLightenDWTree(){
		$this->_createServerList();
		if($_REQUEST['server_id'] && $this->_isPost()){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$postData = array(
					'UserId'=>trim($_POST['UserId']),	//玩家Id
					'Floor'=>trim($_POST['Floor']),	//成长树层级
					'Order'=>trim($_POST['Order']),	//层级中的第order个任务
			);
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'UserData','a'=>'DataUpdate','type'=>59),$postData);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
				
			if($data && is_array($data)){
				$this->_utilMsg->showMsg($data['message']?$data['message']:'操作完成',1);
			}
			$this->_utilMsg->showMsg("操作失败",-1);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 点亮活跃度任务
	 */
	public function actionLightenActivity(){
		$this->_createServerList();
		if ($this->_isPost()){

			// 			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>点亮活跃度任务</div>';
			$apply_info.='<div>用户：'.$_POST['UserId'].'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
				
			$applyData = array(
						'type'=>40,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>'php/interface.php',
							'post_data'=>$_POST,
							'get_data'=>array(
								'c'=>'UserData',
								'a'=>'DataUpdate',
								'm'=>'Admin',
								'type'=>'47',
								'__hj_dt'=>'RpcSeri',
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
			),
			),
						'call'=>array(
							'cal_local_object'=>'Util_ApplyInterface',
							'cal_local_method'=>'FrgSendReward',
			)
			),
					'receiver_object'	=>array($_REQUEST['server_id']=>''),
					'player_type'		=>0,
					'player_info'		=>$_POST['UserId'],
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	public function actionDataUpdate44(){
		$this->_createServerList();
		if ($this->_isPost()){

			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>修改玩家的海域站挑战次数</div>';
			$apply_info.='<div>用户：'.$_POST['UserId'].'</div>';
			$gameser_list = $this->_getGlobalData('gameser_list');
			unset($_POST["cause"]);
			$applyData = array(
						'type'=>23,
						'server_id'=>$_REQUEST['server_id'],
						'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
						'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
						'list_type'=>1,
						'apply_info'=>$apply_info,//$apply_info
						'send_type'=>2,	//2	http
						'send_data'=>array(
							'url_append'=>'php/interface.php',
							'post_data'=>$_POST,
							'get_data'=>array(
								'c'=>'UserData',
								'a'=>'DataUpdate',
								'm'=>'Admin',
								'type'=>'44',
								'__hj_dt'=>'RpcSeri',
							'__sk'=>array(
								'cal_local_object'=>'Util_FRGInterface',
								'cal_local_method'=>'getFrgSk',
								'params'=>NULL,
			),
			),
						'call'=>array(
							'cal_local_object'=>'Util_ApplyInterface',
							'cal_local_method'=>'FrgSendReward',
			)
			),
					'receiver_object'	=>array($_REQUEST['server_id']=>''),
					'player_type'		=>0,
					'player_info'		=>$_POST['UserId'],
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
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}


	public function actionImagetext(){
		$this->_createServerList();

		if(!empty($_GET["playerid"])){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'UserData','a'=>'DataUpdate','type'=>'19','UserId'=>$_GET["playerid"]);
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],$getArr,null);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			$this->_view->assign("Msg",$data["message"]);
		}
		$this->_view->assign("GET",$_GET);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/*
	 * 活动数据修复
	 **/
	public function actionSpecialActivityRepair(){
		$this->_createServerList();
		if($this->_isAjax()){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');

			$activeName = $_POST['activeName'];
			unset($_POST['activeName']);
			// 				print_r($_POST);
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'UserData','a'=>'SpecialActivityRepair','dosubmit'=>1,'Identifier'=>$activeName),$_POST);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult(null,'HtmlTemplate');
			echo $data;
			exit;
		}

		if($_REQUEST['server_id']){
			$activeName = $_POST['activeName'];
			$a= array('c'=>'UserData','a'=>'SpecialActivityRepair');
			if($activeName){
				$a['Identifier'] = $activeName;
			}
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],$a);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();

			$dataArray = array('null' => '请选择活动');
			if($data && is_array($data['data']['List'])){
				foreach ($data['data']['List'] as $k=>$v){
					$dataArray[$v['Identifier']]=$v['Title'];
					if($activeName && $v['Identifier'] == $activeName){
						$this->_view->assign('chooseActiveName',$v);
							
					}
				}
			}
			$adminRepairForm = $data['data']['Activity']['AdminRepairForm'];
			$this->_view->assign('adminRepairForm',$adminRepairForm);
			$this->_view->assign('dataArray',$dataArray);
			$url = Tools::url(CONTROL,'SpecialActivityRepair',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('url',$url);
			$this->_view->assign('activeName',$activeName);

			// 				print_r($_POST);exit;
		}


		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

}