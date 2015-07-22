<?php
Tools::import('Control_BaseGm');
/**
 * 工单处理模块
 * @author php-朱磊
 */
class Control_WorkOrder extends BaseGm {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Model_OperatorList
	 * @var Model_OperatorList
	 */
	private $_modelOperatorList;

	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;

	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

	/**
	 * Model_QuestionType
	 * @var Model_QuestionType
	 */
	private $_modelQuestionType;

	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;

	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;

	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;

	/**
	 * Util_WebService
	 * @var Util_WebService
	 */
	private $_utilWebService;

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;

	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;

	/**
	 * Util_ApiSftx
	 * @var Util_ApiSftx
	 */
	private $_utilApiSftx;

	/**
	 * Util_ApiBto
	 * @var Util_ApiBto
	 */
	private $_utilApiBto;

	/**
	 * Model_AutoOrderQueue
	 * @var Model_AutoOrderQueue
	 */
	private $_modelAutoOrderQueue;

	/**
	 * Model_OrderLog
	 * @var Model_OrderLog
	 */
	private $_modelOrderLog;

	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc;

	/**
	 * Model_Rooms
	 * @var Model_Rooms
	 */
	private $_modelRooms;


	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$this->_utilRooms = $this->_getGlobalData ( 'Util_Rooms', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelOperatorList = $this->_getGlobalData ( 'Model_OperatorList', 'object' );
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );

	}

	private function _createUrl() {
		$this->_url ['QualityCheck_ShowQuality']=Tools::url('QualityCheck','ShowQuality');
		$this->_url ['WorkOrder_Reply'] = Tools::url ( CONTROL, 'Reply' );
		$this->_url ['WorkOrder_UpdateRemark'] = Tools::url ( CONTROL, 'UpdateRemark' );
		$this->_url ['WorkOrder_TestAdd'] = Tools::url ( CONTROL, 'TestAdd' );
		$this->_url ['Question_Index'] = Tools::url ( 'Question', 'Index' );
		$this->_view->assign ( 'url', $this->_url );
	}

	private function _getServers($gameId = null,$operatorId = null){
		if(empty($gameId)){
			$gameId = trim($_REQUEST['game_type_id']);
		}
		if(empty($operatorId)){
			$operatorId = trim($_REQUEST['operator_id']);
		}
		$returnData = array();
		if($gameId && $operatorId){
			$serverList = $this->_getGlobalData('server/server_list_'.$gameId);
			if(is_array($serverList)){
				foreach($serverList as $serverId => $sub){
					if($sub['operator_id'] == $operatorId){
						$returnData[$serverId] = $sub['server_name'];
					}
				}
			}
			//$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>$returnData));
		}
		return $returnData;
		//$this->_returnAjaxJson(array('status'=>0,'info'=>'param empty','data'=>null));
	}


	public function actionVIPWorkOrder(){
		$this->_utilOnline	=	$this->_getGlobalData('Util_Online','object');
		$workOrderStatusArr = 	$this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$verifyStatusArr = $this->_getGlobalData ( 'verify_status' );//bug 状态
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$orgList=$this->_getGlobalData('org');
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		$questionTypes=$this->_getGlobalData('question_types');
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','nick_name');
				$value['user']=$curOrgUser;
			}
		}
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$roomList=$this->_modelRooms->findAll();
		$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
		$roomList['']=Tools::getLang('All','Common');

		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$orderManage=$this->_utilWorkOrder->getOrderManage();
		$orderVipNum=$orderManage['_orderNum'];
		$orderVipNum['']=array_sum($orderVipNum);
		#------初始化------#

		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		#------分页生成sql------#
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);

			#------提问类型------#
			$qTypes=array();
			foreach ($questionTypes as $key=>$list){
				if ($list['game_type_id']==$_GET['game_type_id'])$qTypes[$key]=$list['title'];
			}
			$qTypes['']=Tools::getLang('ALL','Common');
			$this->_view->assign('qType',$qTypes);
			if ($_GET['question_type']){
				$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");
				$this->_view->assign('selectedQtype',$_GET['question_type']);
			}
			#------提问类型------#

			#------运营商------#			
			$operatorListTemp = array();
			$gameOperatorIndex = $this->_getGlobalData ( 'Model_GameOperator', 'object' )->findByGameTypeId ($_GET['game_type_id']);
			foreach ( $gameOperatorIndex as $value ) {
				if(array_key_exists($value['operator_id'],$operatorList)){
					$operatorListTemp[$value['operator_id']] = $operatorList[$value['operator_id']];
				}
			}
			$operatorList = $operatorListTemp;
			unset($operatorListTemp);
			#------运营商------#
		}

		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		//增加服务器选择列表
		if($_GET['game_type_id']!='' && $_GET['operator_id']!=''){
			$gameOptServerLists = $this->_getServers($_GET['game_type_id'],$_GET['operator_id']);
			if($_GET['server_id']!=''){
				$helpSqlSearch->set_conditions('game_server_id='.intval($_GET['server_id']));
			}
			$this->_view->assign('gameOptServerLists',$gameOptServerLists);
		}

		if ($_GET['Id']){
			$helpSqlSearch->set_conditions("Id={$_GET['Id']}");
			$this->_view->assign('selectedId',$_GET['Id']);
		}

		if ($_GET['room_id']){
			$helpSqlSearch->set_conditions("room_id={$_GET['room_id']}");
			$this->_view->assign('selectedRoomId',$_GET['room_id']);
		}

		if ($_GET['evaluation_status']!=''){
			$helpSqlSearch->set_conditions("evaluation_status={$_GET['evaluation_status']}");
			$this->_view->assign('selectedEv',$_GET['evaluation_status']);
			if ($_GET['evaluation_status']==3){//如果为差评
				$this->_view->assign('badev_display',true);
			}
		}
		if ($_GET['is_verify']!=''){
			$helpSqlSearch->set_conditions("is_verify={$_GET['is_verify']}");
			$this->_view->assign('selectedIsVerify',$_GET['is_verify']);
		}
		if ($_GET['evaluation_desc']!=''){
			$helpSqlSearch->set_conditions("evaluation_desc={$_GET['evaluation_desc']}");
			$this->_view->assign('selectedBadEv',$_GET['evaluation_desc']);
		}

		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}
		$helpSqlSearch->setPageLimit ( $_GET ['page'], 20 );

		if ($_GET['order_status']){
			$helpSqlSearch->set_conditions("status={$_GET['order_status']}");
			$this->_view->assign('selectedOrderStatus',$_GET['order_status']);
		}

		if ($_GET['user_nickname']){
			$helpSqlSearch->set_conditions("user_nickname='{$_GET['user_nickname']}'");
			$this->_view->assign('selectedUserNickname',$_GET['user_nickname']);
			$_GET['user_nickname']=urlencode($_GET['user_nickname']);
		}

		if ($_GET['user_account']){
			$helpSqlSearch->set_conditions("user_account='{$_GET['user_account']}'");
			$this->_view->assign('selectedUserAccount',$_GET['user_account']);
			$_GET['user_account']=urlencode($_GET['user_account']);
		}

		if ($_GET['title']){
			$helpSqlSearch->set_conditions("title like '%{$_GET['title']}%'");
			$this->_view->assign('selectedTitle',$_GET['title']);
			$_GET['title']=urlencode($_GET['title']);
		}

		if ($_GET['service_ids']){//如果选择了客服
			$this->_view->assign('selectedServiceIds',$_GET['service_ids']);
			$serviceIds=implode(',',$_GET['service_ids']);
			$helpSqlSearch->set_conditions("owner_user_id in ({$serviceIds})");
		}

		if ($_GET['start_time'] && $_GET['end_time']){
			$this->_view->assign('selectedStartTime',$_GET['start_time']);
			$this->_view->assign('selectedEndTime',$_GET['end_time']);
			$helpSqlSearch->set_conditions('create_time between '.strtotime($_GET['start_time']) . ' and '.strtotime($_GET['end_time']));
		}
		$helpSqlSearch->set_conditions("is_vip='1'");
		$helpSqlSearch->set_orderBy('status asc,create_time desc');


		$sql = $helpSqlSearch->createSql ();
		$conditions=$helpSqlSearch->get_conditions();

		$this->_loadCore ( 'Help_Page' );
		$helpPage = new Help_Page ( array ('total' => $this->_modelWorkOrder->findCount ( $conditions ), 'perpage' => 20 ) );
		$this->_view->assign ( 'pageBox', $helpPage->show () );


		#------分页生成sql------#
		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		foreach ($vipLevel as $key=>&$vipCount){
			$vipCount.=" [<font color='#ff0000'>".intval($orderVipNum[$key])."</font>]";
		}
		$users=$this->_getGlobalData('user_all');
		$serverList=$this->_getGlobalData('gameser_list');
		#------载入缓存------#

		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			$timeInterval=array();	//定时器计时
			foreach ( $dataList as &$list ) {
				$TimeDifference = intval($serverList[$list['game_server_id']]['time_zone']);	//时差
				$list ['url_dialog'] =Tools::url('QualityCheck','OrderDialog',array('Id'=>$list['Id'],'TimeDifference'=>$TimeDifference));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?Tools::getLang('VERIFY_STATUS','Common'):'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$list ['word_question_type'] = $questionTypes[$list['question_type']] ['title'] ? $questionTypes[$list['question_type']] ['title'] : Tools::getLang('NOT_QUESTION_TYPE','Common');
				$list ['url_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $list ['Id'] ) );
				$list ['url_question'] = "javascript:questionText('".$list ['Id']."','".$list ['game_type']."');";
				$list ['url_questionend'] = "javascript:questionEnd('".$list ['Id']."','".$list ['game_type']."');";
				$list ['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list ['word_game_server_id']=$serverList[$list['game_server_id']]['server_name'];
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
				$list ['url_reply_detail']=Tools::url(CONTROL,'ReplyIndex',array('user_id'=>$list['owner_user_id']));
				$list ['word_room_id']=$list['room_id']?$roomList[$list['room_id']]:'<font color="#666666">'.Tools::getLang('NOT','Common').'</font>';
				if ($list['evaluation_status']==3)$list['word_ev_desc']=$badEvArr[$list['evaluation_desc']];
				if ($list['status']==1){
					$isTimeout=Tools::isTimeOut($list['create_time'],$list['timeout']);
					if ($isTimeout===true){//已超时
						$list['time_out_true']=true;
					}else {//未超时
						$list['lost_time']=Tools::getTimeFormat($isTimeout);
						$timeInterval[$list['Id']]['div']=$list['Id'];
						$timeInterval[$list['Id']]['time']=$isTimeout;
					}
				}

				if ($TimeDifference!=0){
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']+($TimeDifference*3600));
					$list['create_time'].= '('.Tools::getLang('GAME_SERVER_TIME','Common').')';
				}else {
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				}

				if ($list['word_owner_user_id']){
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
				}
				$verifyInfo = $this->_modelWorkOrder->select ( 'select status from '.DB_PREFIX.'verify '.'where work_order_id ='.$list['Id'],1);
				$verifyStatus = $verifyInfo['status'];
				$list ['word_verify_status'] = $verifyStatusArr [$verifyStatus];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$evArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('statusurl',Tools::url(CONTROL,'ChangetheStatus'));
		$badEvArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('verify',$this->_modelWorkOrder->getVerify());
		$this->_view->assign('badEvArr',$badEvArr);
		$this->_view->assign('evArr',$evArr);
		$this->_view->assign('roomList',$roomList);
		$this->_view->assign('timeInterval',json_encode($timeInterval));
		$gameTypeArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypeList',$gameTypeArr);
		$operatorList['']=Tools::getLang('ALL','Common');
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		/**
		 * 区分客服
		 */
		$this->_utilRbac = $this->_getGlobalData ('Util_Rbac', 'object' );
		$userClass=$this->_utilRbac->getUserClass();
		if($userClass["_departmentId"]==2||$userClass["_departmentId"]==4){
			$this->_view->assign('is_service',1);
		}

		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	/**
	 * 工单显示列表
	 */
	public function actionIndex(){
		#------初始化------#
		$this->_utilOnline	=	$this->_getGlobalData('Util_Online','object');
		$workOrderStatusArr = 	$this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$verifyStatusArr = $this->_getGlobalData ( 'verify_status' );//bug 状态
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$orgList=$this->_getGlobalData('org');
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		$questionTypes=$this->_getGlobalData('question_types');
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','nick_name');
				$value['user']=$curOrgUser;
			}
		}
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$roomList=$this->_modelRooms->findAll();
		$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
		$roomList['']=Tools::getLang('All','Common');

		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$orderManage=$this->_utilWorkOrder->getOrderManage();
		$orderVipNum=$orderManage['_orderNum'];
		$orderVipNum['']=array_sum($orderVipNum);
		#------初始化------#

		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		#------分页生成sql------#
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);

			#------提问类型------#
			$qTypes=array();
			foreach ($questionTypes as $key=>$list){
				if ($list['game_type_id']==$_GET['game_type_id'])$qTypes[$key]=$list['title'];
			}
			$qTypes['']=Tools::getLang('ALL','Common');
			$this->_view->assign('qType',$qTypes);
			if ($_GET['question_type']){
				$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");
				$this->_view->assign('selectedQtype',$_GET['question_type']);
			}
			#------提问类型------#

			#------运营商------#			
			$operatorListTemp = array();
			$gameOperatorIndex = $this->_getGlobalData ( 'Model_GameOperator', 'object' )->findByGameTypeId ($_GET['game_type_id']);
			foreach ( $gameOperatorIndex as $value ) {
				if(array_key_exists($value['operator_id'],$operatorList)){
					$operatorListTemp[$value['operator_id']] = $operatorList[$value['operator_id']];
				}
			}
			$operatorList = $operatorListTemp;
			unset($operatorListTemp);
			#------运营商------#
		}

		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}
		//增加服务器选择列表
		if($_GET['game_type_id']!='' && $_GET['operator_id']!=''){
			$gameOptServerLists = $this->_getServers($_GET['game_type_id'],$_GET['operator_id']);
			if($_GET['server_id']!=''){
				$helpSqlSearch->set_conditions('game_server_id='.intval($_GET['server_id']));
			}
			$this->_view->assign('gameOptServerLists',$gameOptServerLists);
		}

		if ($_GET['Id']){
			$helpSqlSearch->set_conditions("Id={$_GET['Id']}");
			$this->_view->assign('selectedId',$_GET['Id']);
		}

		if ($_GET['room_id']){
			$helpSqlSearch->set_conditions("room_id={$_GET['room_id']}");
			$this->_view->assign('selectedRoomId',$_GET['room_id']);
		}

		if ($_GET['evaluation_status']!=''){
			$helpSqlSearch->set_conditions("evaluation_status={$_GET['evaluation_status']}");
			$this->_view->assign('selectedEv',$_GET['evaluation_status']);
			if ($_GET['evaluation_status']==3){//如果为差评
				$this->_view->assign('badev_display',true);
			}
		}
		if ($_GET['is_verify']!=''){
			$helpSqlSearch->set_conditions("is_verify={$_GET['is_verify']}");
			$this->_view->assign('selectedIsVerify',$_GET['is_verify']);
		}
		if ($_GET['evaluation_desc']!=''){
			$helpSqlSearch->set_conditions("evaluation_desc={$_GET['evaluation_desc']}");
			$this->_view->assign('selectedBadEv',$_GET['evaluation_desc']);
		}

		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}
		$helpSqlSearch->setPageLimit ( $_GET ['page'], 20 );

		if ($_GET['order_status']){
			$helpSqlSearch->set_conditions("status={$_GET['order_status']}");
			$this->_view->assign('selectedOrderStatus',$_GET['order_status']);
		}

		if ($_GET['user_nickname']){
			$helpSqlSearch->set_conditions("user_nickname='{$_GET['user_nickname']}'");
			$this->_view->assign('selectedUserNickname',$_GET['user_nickname']);
			$_GET['user_nickname']=urlencode($_GET['user_nickname']);
		}

		if ($_GET['user_account']){
			$helpSqlSearch->set_conditions("user_account='{$_GET['user_account']}'");
			$this->_view->assign('selectedUserAccount',$_GET['user_account']);
			$_GET['user_account']=urlencode($_GET['user_account']);
		}

		if ($_GET['title']){
			$helpSqlSearch->set_conditions("title like '%{$_GET['title']}%'");
			$this->_view->assign('selectedTitle',$_GET['title']);
			$_GET['title']=urlencode($_GET['title']);
		}

		if ($_GET['service_ids']){//如果选择了客服
			$this->_view->assign('selectedServiceIds',$_GET['service_ids']);
			$serviceIds=implode(',',$_GET['service_ids']);
			$helpSqlSearch->set_conditions("owner_user_id in ({$serviceIds})");
		}

		if ($_GET['start_time'] && $_GET['end_time']){
			$this->_view->assign('selectedStartTime',$_GET['start_time']);
			$this->_view->assign('selectedEndTime',$_GET['end_time']);
			$helpSqlSearch->set_conditions('create_time between '.strtotime($_GET['start_time']) . ' and '.strtotime($_GET['end_time']));
		}
		$helpSqlSearch->set_orderBy('status asc,create_time desc');


		$sql = $helpSqlSearch->createSql ();
		$conditions=$helpSqlSearch->get_conditions();

		$this->_loadCore ( 'Help_Page' );
		$helpPage = new Help_Page ( array ('total' => $this->_modelWorkOrder->findCount ( $conditions ), 'perpage' => 20 ) );
		$this->_view->assign ( 'pageBox', $helpPage->show () );


		#------分页生成sql------#
		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		foreach ($vipLevel as $key=>&$vipCount){
			$vipCount.=" [<font color='#ff0000'>".intval($orderVipNum[$key])."</font>]";
		}
		$users=$this->_getGlobalData('user_all');
		$serverList=$this->_getGlobalData('gameser_list');
		#------载入缓存------#

		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			$timeInterval=array();	//定时器计时
			foreach ( $dataList as &$list ) {
				$TimeDifference = intval($serverList[$list['game_server_id']]['time_zone']);	//时差
				$list ['url_dialog'] =Tools::url('QualityCheck','OrderDialog',array('Id'=>$list['Id'],'TimeDifference'=>$TimeDifference));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?Tools::getLang('VERIFY_STATUS','Common'):'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$list ['word_question_type'] = $questionTypes[$list['question_type']] ['title'] ? $questionTypes[$list['question_type']] ['title'] : Tools::getLang('NOT_QUESTION_TYPE','Common');
				$list ['url_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $list ['Id'] ) );
				$list ['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list ['word_game_server_id']=$serverList[$list['game_server_id']]['server_name'];
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
				$list ['url_reply_detail']=Tools::url(CONTROL,'ReplyIndex',array('user_id'=>$list['owner_user_id']));
				$list ['word_room_id']=$list['room_id']?$roomList[$list['room_id']]:'<font color="#666666">'.Tools::getLang('NOT','Common').'</font>';
				if ($list['evaluation_status']==3)$list['word_ev_desc']=$badEvArr[$list['evaluation_desc']];
				if ($list['status']==1){
					$isTimeout=Tools::isTimeOut($list['create_time'],$list['timeout']);
					if ($isTimeout===true){//已超时
						$list['time_out_true']=true;
					}else {//未超时
						$list['lost_time']=Tools::getTimeFormat($isTimeout);
						$timeInterval[$list['Id']]['div']=$list['Id'];
						$timeInterval[$list['Id']]['time']=$isTimeout;
					}
				}

				if ($TimeDifference!=0){
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']+($TimeDifference*3600));
					$list['create_time'].= '('.Tools::getLang('GAME_SERVER_TIME','Common').')';
				}else {
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				}

				if ($list['word_owner_user_id']){
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
				}
				$verifyInfo = $this->_modelWorkOrder->select ( 'select status from '.DB_PREFIX.'verify '.'where work_order_id ='.$list['Id'],1);
				$verifyStatus = $verifyInfo['status'];
				$list ['word_verify_status'] = $verifyStatusArr [$verifyStatus];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$evArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('statusurl',Tools::url(CONTROL,'ChangetheStatus'));
		$badEvArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('verify',$this->_modelWorkOrder->getVerify());
		$this->_view->assign('badEvArr',$badEvArr);
		$this->_view->assign('evArr',$evArr);
		$this->_view->assign('roomList',$roomList);
		$this->_view->assign('timeInterval',json_encode($timeInterval));
		$gameTypeArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypeList',$gameTypeArr);
		$operatorList['']=Tools::getLang('ALL','Common');
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 通过webservice发送消息
	 */
	private function _sendMsg($data) {
		switch ($data['game_type_id']){
			case '1' :{// 商业大亨
				$this->_utilApiBto=$this->_getGlobalData('Util_ApiBto','object');
				$sendUrl=$data['send_url'];
				$sendUrl.='api_interface.php?action=Faq&doaction=GetClientReply';
				unset($data['send_url']);
				$this->_utilApiBto->addHttp($sendUrl,null,$data);
				$this->_utilApiBto->send();
				$dataResult=$this->_utilApiBto->getResult();
				if ($dataResult['status']==1){
					return true;
				}else {
					return Tools::getLang('SEND_MSG',__CLASS__);
				}
				break;
			}
			case '2' :{//富人国
				$data['send_url'].='php/interface.php?m=clerk&c=UserQuiz&a=GetClientReply';
				$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				$this->_utilFRGInterface->set_sendUrl($data['send_url']);
				unset($data['send_url']);
				$data ['_sign'] = md5 ( TAKE_KEY . CURRENT_TIME );
				$data ['_verifycode'] = CURRENT_TIME;
				$this->_utilFRGInterface->setPost($data);
				$data=$this->_utilFRGInterface->callInterface();
				if ($data){
					if ($data['msgno']==1){
						return true;
					}else {
						return Tools::getLang('FRG_SEND_ERROR',__CLASS__,array('data[message]'=>$data['message']));
					}
				}else {
					return Tools::getLang('SEND_MSG',__CLASS__);
				}

				break;
			}
			case '3' :{//三分天下
				$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
				$sendUrl=$data['send_url'];
				$sendUrl.='question/answerQuestion';
				unset($data['send_url']);
				$data['content'] = urlencode($data['content']);//解决三分%号发不出去
				$this->_utilApiSftx->addHttp($sendUrl,null,$data);
				$this->_utilApiSftx->send();
				$dataResult=$this->_utilApiSftx->getResult();
				if ($dataResult['status']==1){
					return true;
				}else {
					return Tools::getLang('SEND_MSG',__CLASS__).'<br>'.serialize($dataResult);
				}
				break;
			}
			case '5' :{//寻侠
				$utilRpc=$this->_getGlobalData('Util_Rpc','object');
				$utilRpc->setUrl($data['send_url'].'question/answerQuestion');
				$dataResult=$utilRpc->answerQuestion($data['work_order_id'],$data['service_id'],$data['status'],$data['content']);
				if($dataResult ===0){
					return true;
				}
				return Tools::getLang('SEND_MSG',__CLASS__).'<br>'.serialize($dataResult);
				break;
			}
			case '6':{
				return true;
			}
			case '7':{//大唐 双龙诀
				$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
				$post['_verifycode'] = CURRENT_TIME;
				$post['_sign'] = md5('e23&^$)(&HJjkdwi^&%$'.CURRENT_TIME);
				$post['work_order_id'] = $data['work_order_id'];
				$post['service_id'] = $data['service_id'];
				$post['content'] = $data['content'];
				$post['status']	= $data['status'];
				$this->_utilHttpMInterface->addHttp($data['send_url'],'game/answer',array(),$post);
				$this->_utilHttpMInterface->send();
				$dataResult = $this->_utilHttpMInterface->getResults();
				$dataResult =  json_decode(array_shift($dataResult),true);
				if ($dataResult['status']==1){
					return true;
				}else {
					return Tools::getLang('SEND_MSG',__CLASS__).':'.$dataResult['info'];
				}
				break;
			}
		}


	}

	/**
	 * 回复用户信息
	 */
	public function actionReply() {

		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$timeout = intval ( strtotime(substr($_POST ['create_time'] , 0,19)) ) + intval ( $_POST ['timeout'] );
		// 		var_dump( strtotime(substr($_POST ['create_time'] , 0,19)) );
		// 		var_dump( $_POST ['timeout'] );
		// 		echo date('Y-m-d H:i:s',$timeout);
		$gameServerList = $this->_getGlobalData ( 'server/server_list_'.$_POST['game_type_id']);
		$TimeDifference = intval($gameServerList[$_POST['game_server_id']]['time_zone'])*3600; //时差
		// 		echo date('Y-m-d H:i:s', CURRENT_TIME+$TimeDifference);exit;

		if (CURRENT_TIME+$TimeDifference > $timeout && $_POST ['cur_status'] == 1) { //如果当前时间大于超时时间,并且当前工单为未处理状态,就说明回复时间已经过了,那么就超时
			$isTimeout = '1';
		} else {
			$isTimeout = '0';
		}
		$_POST['game_type_id'] = intval($_POST['game_type_id']);
		$replyContent=$_POST ['reply'];
		$not_sendmsg	=	0;
		if ($_POST['lock']){
			$replyContent=Tools::getLang('REPLY_NOT_SEND_SERVER',__CLASS__).$replyContent;	//如果为不发送消息将自动在前面加上
			$not_sendmsg	=	1;
		}

		$addQaArr = array (
				'is_timeout' 	=> 	$isTimeout,
				'work_order_id' => 	$_POST ['Id'],
				'content' 		=> 	$replyContent,
				'qa' 			=> 	1,
				'user_id'		=>	$userClass['_id'],
				'create_time' 	=> 	CURRENT_TIME,
				'game_type_id' 	=> 	$_POST ['game_type_id'],
				'operator_id' 	=> 	$_POST ['operator_id'],
				'service_id' 	=> 	$userClass ['_serviceId'] ? $userClass ['_serviceId'] : '001',
				'not_sendmsg'	=>	$not_sendmsg,

		);

		if ($isTimeout)$addQaArr['last_reply_time']=$timeout;	//如果超时的话,将记录最后超时的时间

		if (($_POST ['source'] == 2 || $_POST ['source'] == 3) && !$_POST['lock']) { //如果工单来源为2并且lock为不锁定,那么就要发送webservice
			$gameServerUrl = $gameServerList [$_POST ['game_server_id']] ['server_url']; //获取url地址
			if (!$gameServerUrl)$this->_utilMsg->showMsg(Tools::getLang('REPLY_SEND_ERROR',__CLASS__),-2);
			//			$isOk = $this->_sendMsg ( array (
			//									'work_order_id' => $_POST ['Id'],
			//									'service_id' => $userClass ['_serviceId'] ? $userClass ['_serviceId'] : '001',
			//									'content' => $replyContent,
			//									'send_url' => $gameServerUrl,
			//									'status'=>$_POST ['status'],
			//									'game_type_id'=>$_POST['game_type_id'] ) );
			$isOk = 'error';
			$gameObject = $this->_getGlobalData($_POST['game_type_id'],'game');
			$file_img = '';
			if($gameObject->_sendImage){
				if(!empty($_FILES["file_img"]["name"])){
					$file_img	=	$gameObject->ImgUpload();
					if($file_img	==	false){
						$errorInfo =  Tools::getLang('SEND_MSG','Control_WorkOrder').':请检查好图片的大小和格式';
						$this->_utilMsg->showMsg ( $errorInfo, - 2 ,1,false);
					}
					$webPath = pathinfo($file_img);
					$webPath = '/Upload/Service/'.date('Ymd',CURRENT_TIME).'/'.$webPath["basename"];
					$addQaArr['image'] = json_encode(array($webPath));
				}
			}
			if($gameObject && is_callable(array($gameObject,'sendOrderReplay'))){
				$sendData = array (
					'work_order_id' => 	$_POST ['Id'],
					'service_id' 	=> 	$userClass ['_serviceId'] ? $userClass ['_serviceId'] : '001',
					'content'		=> 	$replyContent,
					'send_url' 		=> 	$gameServerUrl,
					'status'		=>	$_POST ['status'],
					'game_type_id'	=>	$_POST['game_type_id'],
					'file_img'		=>	$file_img,
					'game_user_id'	=>	$_POST["game_user_id"],
					'server_id'		=>	$_POST ['game_server_id']
				);

				$isOk = $gameObject->sendOrderReplay($sendData);
			}
			if ($isOk !== true){
				$this->_utilMsg->showMsg ( $isOk, - 2 ,1,false);
			}
		}
		$this->_modelWorkOrderQa->add ( $addQaArr );

		#------改变房间工单,用户回复数------#
		$userClass->setUpdateInfo(1);
		$userClass->addToReplyNum(1);

		/*if ($_POST['owner_user_id']==$userClass['_id'] && $_POST ['cur_status']==1)$userClass->setIncompleteOrderNum(-1);//用户完成一个工单

		if ($_POST ['cur_status']!=3 && $_POST['status']==3){//如果为未回复将房间未完成工单数减1
		$roomClass=$this->_utilRooms->getRoom($_POST['room_id']);
		if (is_object($roomClass)){
		$roomClass->completeOrder(-1);	//完成一个工单数
		$roomClass->setUpdateInfo(1);
		}

		//vip等级的工单数减1,
		$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
		$objOrderManage=$this->_utilWorkOrder->getOrderManage();
		$objOrderManage->setOrderNum($_POST['vip_level']);
		$objOrderManage->setUpdateInfo(1);
		}*/
		#------改变房间工单,用户回复数------#

		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->delByWorkOrderId($_POST['Id']);//删除队列

		#------添加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('Id'=>$_POST ['Id']),$_POST ['status']);
		#------添加日志------#

		$workOrderUpdateArr = array (
			 'status' => $_POST ['status'],
			 'answer_num' => 'answer_num+1', 
			 'owner_user_id' => $userClass ['_id'],
			 'is_read'=>0,'remark'=>$_POST['remark'] 
		); //更改状态,提问数+1,修改备注
		if ($_POST['is_verify'])$workOrderUpdateArr['is_verify']=1;	//如果为需查证,将改为需查证.
		$this->_modelWorkOrder->update ( $workOrderUpdateArr, "Id={$_POST['Id']}" );
		$this->_utilMsg->showMsg ( false );
	}
	/**
	 * 修改备注
	 */
	public function actionUpdateRemark() {
		$workOrderUpdateArr = array (
				'remark'=>$_POST['remark']
		);
		$r = $this->_modelWorkOrder->update ( $workOrderUpdateArr, "Id={$_POST['Id']}" );
		if ($r){
			echo json_encode(array('code'=>1,'msg'=>'备注修改成功'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'备注修改失败'));
		}
	}
	/**
	 * 查看工单详细
	 */
	public function actionDetail() {
		$dataList = $this->_modelWorkOrder->findByIdToDetail ( $_GET ['Id'] );
		$_REQUEST['operator_id']=$dataList['operator_id'];
		$this->_checkOperatorAct(true);
		$dialogList = $this->_modelWorkOrderQa->findByWorkOrderId ( $_GET ['Id'] );

		#------载入缓存------#
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' ); //游戏类型
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' ); //工单来源
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' ); //工单状态
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );

		$TimeDifference = intval($gameServerList [$dataList ['game_server_id']]['time_zone'])*3600; //时差

		$gameServerList = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$user = $this->_getGlobalData ( 'user_all' );
		$departmentList = $this->_getGlobalData ( 'department' );
		$departmentList = Model::getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
		#------载入缓存------#



		#------跟椐ID转换文字显示------#
		$dataList['word_status']=$workOrderStatusArr[$dataList['status']];
		$dataList ['word_game_type'] = $gameTypeArr [$dataList ['game_type']];
		$dataList ['word_source'] = $workOrderSourceArr [$dataList ['source']];
		$dataList ['word_game_server_id'] = $gameServerList [$dataList ['game_server_id']];
		$dataList ['word_operator_id'] = $operatorList [$dataList ['operator_id']];

		$dataList ['create_time'] = date ( 'Y-m-d H:i:s', $dataList ['create_time'] + $TimeDifference );
		if($TimeDifference){
			$dataList ['create_time'] .='('.Tools::getLang('GAME_SERVER_TIME','Common').')';
		}
		$dataList ['word_quality_id'] = ($dataList ['quality_id'] < 0) ? $user [$dataList ['quality_id']] ['nick_name'] . "[{$departmentList[$user[$dataList['quality_id']]['department_id']]}]" : '未质检';
		$dataList ['url_nick_name_detail_list']=Tools::url('MyTask','Index',array('view_type'=>2,'user_nickname'=>$dataList['user_nickname']));
		$dataList ['url_change_verify']=Tools::url(CONTROL,'ChangeVerify',array('Id'=>$dataList['Id'],'verify'=>$dataList['is_verify']?0:1));
		#------跟椐ID转换文字显示------#
		foreach ( $dialogList as &$list ) {
			$list ['create_time'] = date ( 'Y-m-d H:i:s', $list ['create_time'] + $TimeDifference );
			if($TimeDifference){
				$list ['create_time'] .='('.Tools::getLang('GAME_SERVER_TIME','Common').')';
			}
			$list["image"]	=	json_decode($list["image"],true);
			$list ['word_reply_name'] = $user [$list ['user_id']]['full_name'];
		}
		$workOrderDetailArr = unserialize ( $dataList ['content'] ); //获取工单的详细信息
		$userData = $workOrderDetailArr ['user_data']; //获取提交工单用户的详细信息

		//		$userData ['register_date'] =$userData ['register_date']? date ( 'Y-m-d H:i:s', $userData ['register_date'] ):'';
		if($userData ['register_date']){
			$userData ['register_date']=date ( 'Y-m-d H:i:s', $userData ['register_date'] + $TimeDifference );
			if($TimeDifference){
				$userData ['register_date'].='('.Tools::getLang('GAME_SERVER_TIME','Common').')';
			}
		}else{
			$userData ['register_date'] = '';
		}

		if ($dataList ['evaluation_status'] != 0) { //如果已经评价
			$playerEvaluation = $this->_getGlobalData ( 'player_evaluation' );
			$evaluation = $playerEvaluation [$dataList ['evaluation_status']];
			$evaluation = $evaluation ['title'];
			if ($dataList ['evaluation_status'] == 3)
			$evaluation .= '：&nbsp;' . $playerEvaluation [3] ['Description'][$dataList['evaluation_desc']];
			$this->_view->assign ( 'evaluation', $evaluation );
		}

		$userQuestionDetail = $workOrderDetailArr ['form_detail']; //获取提问类型工单的值
		$questionDetail = $this->_modelQuestionType->findById ( $dataList ['question_type'] ); //查找问题类型
		$dataList ['word_question_type'] = $questionDetail ['title']; //获取问题类型的中文名称以方便显示
		if ($dataList ['source'] != 2) { //如果工单不是从游戏里面来的话
			$questionDetail = $questionDetail ['form_table']; //获取问题类型的表单配置值
			$userQuestionDetailArr = array (); //初始化显示提交问题类型数组
			if ($questionDetail) {
				foreach ( $questionDetail as $value ) { //将表单转换为key/value方式,方便显示
					if ($value ['type'] == 'game_server_list')
					continue; //如果为服务器列表将跳过,因为已经在工单上面有了.
					switch ($value ['type']) {
						case 'select' :
							{
								$userQuestionDetailArr [$value ['title']] = $value ['options'] [$userQuestionDetail [$value ['name']]];
								break;
							}
						default :
							{
								$userQuestionDetailArr [$value ['title']] = $userQuestionDetail [$value ['name']];
								break;
							}
					}
				}
			}
		}




		#------发送短消息URL------#
		$sendMsgUrl=array(
		1=>'',
		2=>Tools::url('MasterFRG','SendMail',array('UserId[1]'=>$userData['user_id'],'server_id'=>$dataList['game_server_id'],'lock'=>1)),
		3=>Tools::url('GmSftx','SendMsg',array('users[1]'=>$userData['user_id'],'server_id'=>$dataList['game_server_id'],'lock'=>1)),
		);
		$this->_view->assign('sendMsgUrl',$sendMsgUrl[$dataList['game_type']]);	//url
		#------发送短消息URL------#

		#------日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_view->assign('log',$this->_modelOrderLog->getLog($_GET['Id']));
		#------日志------#


		unset ( $workOrderStatusArr [1] );	//删除待处理状态
		unset ( $workOrderStatusArr [4] );	//删除被玩家删除状态
		//玩家附加的信息(腾讯数据)
		if(isset($workOrderDetailArr ['ext'])){
			$this->_view->assign ( 'userExt', $workOrderDetailArr ['ext'] );
		}
		$this->_view->assign ( 'workOrderStatusArr', $workOrderStatusArr );
		$this->_view->assign ( 'userData', $userData );
		$this->_view->assign ( 'userQuestionDetailArr', $userQuestionDetailArr ); //问题类型显示
		$this->_view->assign ( 'dialogArr', $dialogList ); //对话详细
		$this->_view->assign ( 'data', $dataList ); //表单详细信息
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_url ['Verify_OrderVerify'] = Tools::url ( 'Verify', 'OrderVerify', array (
																						'work_order_id' => $_GET ['Id'],
																						'game_type_id'=>$dataList['game_type'],
																						'operator_id'=>$dataList['operator_id'],
																						'game_server_id'=>$dataList['game_server_id'],
																						'game_user_id'=>$userData['user_id'],
																						'user_account'=>urlencode($userData['user_account']),
																						'user_nickname'=>urlencode($userData['user_nickname']) ) );
		$this->_view->assign ( 'url', $this->_url );

		//加载游戏配置
		$gameClass = $this->_getGlobalData($dataList['game_type'],'game');
		$isSendOrderReplay = true;
		$isSendImage = false;
		if($gameClass){
			$isSendOrderReplay = $gameClass->_isSendOrderReplay;
			$isSendImage = $gameClass->_sendImage;
		}
		$this->_view->assign('isSendOrderReplay',$isSendOrderReplay);
		$this->_view->assign('isSendImage',$isSendImage);
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 回复列表
	 */
	public function actionReplyIndex(){
		$selected=array();
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$timeOutArr=array(0=>Tools::getLang('NOT_TIMEOUT','Common'),1=>Tools::getLang('TIMEOUT','Common'),''=>Tools::getLang('ALL','Common'));
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes['']=Tools::getLang('ALL','Common');
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$operatorList['']=Tools::getLang('ALL','Common');
		$userIndex=$this->_getGlobalData('user_index_id');
		$helpSqlSearch->set_tableName($this->_modelWorkOrderQa->tName());
		if ($_GET['game_type_id']!=''){
			$_GET['game_type_id']=Tools::coerceInt($_GET['game_type_id']);
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$selected['game_type_id']=$_GET['game_type_id'];
		}
		if ($_GET['operator_id']!=''){
			$_GET['operator_id']=Tools::coerceInt($_GET['operator_id']);
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$selected['operator_id']=$_GET['operator_id'];
		}
		if ($_GET['user_id']){
			$_GET['user_id']=Tools::coerceInt($_GET['user_id']);
			$helpSqlSearch->set_conditions("user_id={$_GET['user_id']}");
			$selected['user_id']=$_GET['user_id'];
		}else {
			$helpSqlSearch->set_conditions("user_id!=0");
		}
		if ($_GET['start_time'] && $_GET['end_time']){
			$selected['start_time']=$_GET['start_time'];
			$selected['end_time']=$_GET['end_time'];
			$helpSqlSearch->set_conditions("create_time between ".strtotime($_GET['start_time'])." and ".strtotime($_GET['end_time']));
		}
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelWorkOrderQa->select($sql);
		if ($dataList){
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrderQa->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
			foreach ($dataList as &$list){
				$list['word_game_type_id']=$gameTypes[$list['game_type_id']];
				$list['word_operator_id']=$operatorList[$list['operator_id']];
				$list['word_time_out']=$timeOutArr[$list['is_timeout']];
				$list['word_user_id']=$userIndex[$list['user_id']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['url_detail']=Tools::url(CONTROL,'Detail',array('Id'=>$list['work_order_id']));
			}
			$this->_view->assign('dataList',$dataList);
		}
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('timeOutArr',$timeOutArr);
		$this->_view->assign('users',$userIndex);
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 更改需查证状态
	 */
	public function actionChangeVerify(){
		$verify=Tools::coerceInt($_GET['verify']);
		$workOrderId=Tools::coerceInt($_GET['Id']);
		$this->_modelWorkOrder->update(array('is_verify'=>$verify),"Id={$workOrderId}");
		$this->_utilMsg->showMsg(false);
	}


	/**
	 * 更改状态
	 */
	public function actionChangeStatus(){
		if ($this->_isAjax()){
			$id=Tools::coerceInt($_GET['Id']);
			$status=Tools::coerceInt($_GET['status']);
			if ($this->_modelWorkOrder->update(array('status'=>$status),"Id={$id}")){
				#------添加日志------#
				$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
				$this->_modelOrderLog->addLog(array('Id'=>$id),Model_OrderLog::CHANGE_STATUS);
				#------添加日志------#
				$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('CHANGESTATUS_SUCCESS',__CLASS__)));
			}else{
				$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('CHANGESTATUS_ERROR',__CLASS__)));
			}
		}
	}



	public function actionVIPManualOrder(){
		$game = array();
		$gameTypes=$this->_getGlobalData('game_type');
		foreach($gameTypes as $k=>$v){
			if(in_array($k,array(18))){
				$game[$k]=$v["name"];
			}
		}
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operators=$this->_getGlobalData('operator_list');

		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		$this->_modelQuestionType=$this->_getGlobalData('Model_QuestionType','Object');
		$questionType = $this->_modelQuestionType->getGameQuestionType();
		$this->_modelGameOperator=$this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$servers = $this->_modelGameOperator->getGmOptSev();
		//		print_r($questionType);
		//		print_r($operators);
		//		print_r($servers);
		if($this->_isPost()){
			$gameObject = $this->_getGlobalData($_POST['game_type_id'],'game');	//加载游戏对象
			$gameServerList = $this->_getGlobalData ( 'server/server_list_'. $_POST['game_type_id']);
			$gameServerMarking = $gameServerList[$_POST ['server_id']] ['marking'];

			$_POST['server_marking'] = $gameServerMarking;
			$_POST['question_type'] = $_POST['questionType'];
			$_GET['source'] = 4;
			$_POST['user_id'] = $_POST['UserId'];
			$_POST['user_account'] = $_POST['UserId'];
			$_POST['user_nickname'] = $_POST['UserName'];
			$_POST['title'] = $_POST['title'];
			$_POST['content'] = $_POST['content'];
			$_POST['game_id'] = $_POST['game_type_id'];
			/*验证*/
			$_GET["_time"]=CURRENT_TIME;
			$_GET["is_vip"]=1;

			$_GET["game_id"]	=	$_POST['game_type_id'];
			$_GET["_unique"]	=	$_POST['UserId'];
			$_GET["_sign"]	=	md5(CURRENT_TIME.$getData["game_id"].$getData["_unique"].$gameObject->_key);
			//			$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
			//			$data = $utilHttpInterface->result("http://127.0.0.1/",'admin.php?c=InterfaceWorkOrder&a=QuestionSave',$getData,$sendData);
			//			$data = json_decode($data,true);
			$this->_saveSource4();

		}
		else{
			$this->_view->assign('gameTypes',json_encode($game));
			$this->_view->assign('operators',json_encode($operators));
			$this->_view->assign('servers',json_encode($servers));
			$this->_view->assign('questionType',json_encode($questionType));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	private function _saveSource4(){
		$orderArr = array ();
		$orderArr ['game_type'] = intval($_POST['game_id']);
		$orderArr ['is_vip'] = intval($_GET['is_vip']);
		$orderArr ['user_account'] = $_POST ['user_account']?$_POST ['user_account']:0;
		$orderArr ['user_nickname'] = trim($_POST ['user_nickname']);
		$orderArr ['money'] = intval($_POST ['money_total']);
		$orderArr ['source'] = intval(4); //工单来源
		$orderArr ['title'] = strip_tags($_POST['title']);
		$orderArr ['question_type'] = intval($_POST ['question_type']);
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		$orderArr ['game_user_id']= trim($_POST['user_id']);

		if ($_FILES['image'] ){
			$updateInfo=$this->_upload();		//如果有上传图片就上传文件 
		}
		$serverMarking = trim($_POST ['server_marking']);
		$gameServerList = $this->_modelGameSerList->findByMarking ( $_POST['game_id'],$serverMarking,$_POST['server_name'] );
		if (! $gameServerList) { //未找到服务器
			$this->_utilMsg->showMsg("game server non-existent",1);
			//			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}

		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $_POST ['game_id'], $gameServerList ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引 
			$this->_utilMsg->showMsg("game server or operator non-existent",1);
			//			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server or operator non-existent', 'data' => null ) );
		}
		//vip等级，如果从游戏中有传$_POST['vip_level']过来，就使用$_POST['vip_level']，否则使用配置值
		$orderArr ['vip_level'] = isset($_REQUEST['vip'])?intval($_REQUEST['vip']):$this->_modelGameOperator->getVipLevel ( $gameOperatorIndex ['vip_setup'] ['vip_pay'], $_POST ['money_total'] );
		$orderArr ['vip_level'] = min(6,$orderArr ['vip_level']);
		$orderArr ['timeout'] = $this->_modelGameOperator->getTimeOut ( $gameOperatorIndex ['vip_setup'] ['vip_timeout'], $orderArr ['vip_level'] );
		$orderArr ['timeout'] *= 60; //换成秒
		$orderArr ['is_verify']=0;
		$orderArr ['operator_id'] = $gameServerList ['operator_id'];
		$orderArr ['game_server_id'] = $gameServerList ['Id'];
		$orderArr ['is_read'] = 0;
		if ($gameServerList ['room_id'])
		$orderArr ['room_id'] = $gameServerList ['room_id'];


		if (! $this->_modelWorkOrder->add ( $orderArr )) { //创建表单失败
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'Failure to create order', 'data' => null ) );
		}
		$workOrderId = $this->_modelWorkOrder->returnLastInsertId (); //获取工单id
		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('game_type_id'=>$orderArr['game_type'],'operator_id'=>$orderArr['operator_id'],'server_id'=>$orderArr['game_server_id'],'Id'=>$workOrderId),Model_OrderLog::ASK);
		#------追加日志------#

		#------增加新的工单到队列------#
		if ($orderArr ['room_id']) {
			$addOrder = array (
						'Id' => $workOrderId, 
						'vip_level' => $orderArr ['vip_level'], 
						'room_id' => $orderArr ['room_id'], 
						'game_type_id' => $orderArr ['game_type'], 
						'operator_id' => $orderArr ['operator_id'] );
			$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
			$orderManageClass = $this->_utilWorkOrder->getOrderManage ();
			$orderManageClass->addOrder ( $addOrder );
			$orderManageClass = null;
		}
		#------增加新的工单到队列------#
		$orderDetail = array ();
		$orderDetail ['form_detail'] = array ();

		$orderDetail ['user_data'] = array (
									'user_id' => $_POST ['user_id'], 
									'user_account' => $_POST ['user_account'], 
									'user_nickname' => $_POST ['user_nickname'], 
									'money_total' => isset($_POST ['money_total'])?$_POST ['money_total']:-1, 
									'money_month' => isset($_POST ['money_month'])?$_POST ['money_month']:-1, 
									'register_date' => (!$_POST ['register_date'] || $_POST ['register_date']=='null')?null:$_POST ['register_date'], 
									'ip' => $_POST ['ip'] );

		//钻类型
		if(isset($_POST ['flatType'])){
			$orderDetail ['ext']['flatType']['desc'] = '钻类型';
			switch($_POST ['flatType']){
				case '1':
					$orderDetail ['ext']['flatType']['value']='<font color="#FF9900">黄钻</font>';
					break;
				case '10':
					$orderDetail ['ext']['flatType']['value']='<font color="#0000FF">蓝钻</font>';
					break;
				default:
					$orderDetail ['ext']['flatType']['value']='<font color="#999999">无</font>';
			}

		}
		//钻等级
		if(isset($_POST ['diamondLevel'])){
			$orderDetail ['ext']['diamondLevel'] = array(
				'desc'=>'钻等级',
				'value'=>$_POST ['diamondLevel'],
			);
		}
		//是否年费黄钻
		if(isset($_POST['yearDiamond'])){
			$orderDetail ['ext']['yearDiamond'] = array(
				'desc'=>'是否年费',
				'value'=>$_POST ['yearDiamond']?'是':'否',
			);
		}
		//游戏VIP等级
		if(isset($_POST ['vip'])){
			$orderDetail ['ext']['vip'] = array(
				'desc'=>'游戏VIP等级',
				'value'=>$_POST ['vip'],
			);
		}

		$orderDetail = serialize ( $orderDetail );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$this->_modelWorkOrderDetail->add ( array ('work_order_id' => $workOrderId, 'content' => $orderDetail ) );


		#-----插入对话表------#
		$retArr=array('order_id'=>$workOrderId);
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$content=strip_tags($_POST ['content']);
		$content = str_replace(array('\r\n','\r'),chr(10),$content);
		$orderDialog = array ('user_id'=>0,'game_type_id' => $_POST ['game_id'], 'operator_id' => $orderArr ['operator_id'], 'work_order_id' => $workOrderId, 'qa' => '0','content' => $content,'create_time' => CURRENT_TIME );
		if ($updateInfo && is_array($updateInfo) ){
			$_imgArr	=	array();
			$i = '';
			foreach ($updateInfo as $img){
				$retArr["image_path{$i}"]=$img['web_path'];
				$i++;
				$_imgArr[] = str_replace(__ROOT__,'',$img["web_path"]);
			}
			$orderDialog['image'] = json_encode($_imgArr);
		}
		$this->_modelWorkOrderQa->add ( $orderDialog );
		//$orderArr["room_id"]
		if($orderArr['room_id'] && $gameServerList['server_url'] && $_POST ['user_id']){
			$this->_autoreply($orderArr,$gameServerList['server_url']);
		}
		#-----插入对话表------#
		eaccelerator_rm('question_'.$orderArr['user_account']);
		eaccelerator_put('question_'.$orderArr['user_account'], md5($orderArr['user_account'].$orderArr['title']),60);

		$this->_utilMsg->showMsg("提交成功",1);




	}
	/**
	 * 手动录入工单
	 */
	public function actionManualEntry(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');
		$this->_modelQuestionType=$this->_getGlobalData('Model_QuestionType','Object');
		$questionType = $this->_modelQuestionType->getGameQuestionType();
		if($this->_isPost()){
			$_POST['game_type_id'] = intval($_POST['game_type_id']);
			$sendData['question_type'] = intval($_POST['questionType']);
			$sendData['game_user_id'] = intval($_POST['UserId']);	//有些游戏可能要重新double($_POST['UserId'])
			$sendData['title'] = trim($_POST['title']);
			$sendData['content'] = $_POST['content'];
			if(!$sendData['question_type']){
				$this->_utilMsg->showMsg('标题类型错误',-1);
			}
			if(!$sendData['game_user_id']){
				$this->_utilMsg->showMsg('用户ID不能为空',-1);
			}
			if(!$sendData['title']){
				$this->_utilMsg->showMsg('标题不能为空',-1);
			}
			if(!trim($sendData['content'])){
				$this->_utilMsg->showMsg('内容不能为空',-1);
			}
			$sendOk = false;
			$errorInfo = Tools::getLang('SEND_MSG',__CLASS__);
			switch ($_POST['game_type_id']){
				case 1:
					$gameServerList = $this->_getGlobalData ( 'server/server_list_'. $_POST['game_type_id']);
					$gameServerUrl = $gameServerList [$_POST ['server_id']] ['server_url'];
					$this->_utilApiBto=$this->_getGlobalData('Util_ApiBto','object');
					$sendUrl=$gameServerUrl.'api_interface.php?action=FaqService&doaction=GetUserQuiz';

					$this->_utilApiBto->addHttp($sendUrl,null,$sendData);
					$this->_utilApiBto->send();
					$dataResult=$this->_utilApiBto->getResult();
					//"$dataResult" = Array [3]
					//	status = (int) 1
					//	info = (string:55) Submitted! Please wait for reply from CS with patience!
					//	data = Array [8]
					//		order_id = (int) 17
					//		user_id = (int) 1002
					//		user_account = (string:10) uwan188110
					//		user_nickname = (string:6) sdfsfs
					//		money_month = (int) 390299
					//		money_total = (int) 570299
					//		register_date = (string:10) 1282743121
					//		server_marking = (string:6) S10009
					if($dataResult['status'] == 1){
						$sendOk = true;
					}else{
						$errorInfo = $dataResult['info'];
					}
					break;
				case 2:
					$gameServerList = $this->_getGlobalData ( 'server/server_list_'. $_POST['game_type_id']);
					$sendUrl = $gameServerList [$_POST ['server_id']] ['server_url'];
					$sendUrl.='php/interface.php?m=clerk&c=UserQuiz&a=SubmitByCs';
					$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
					$this->_utilFRGInterface->set_sendUrl($sendUrl);
					$sendData ['_sign'] = md5 ( TAKE_KEY . CURRENT_TIME );
					$sendData ['_verifycode'] = CURRENT_TIME;
					$this->_utilFRGInterface->setPost($sendData);
					$dataResult=$this->_utilFRGInterface->callInterface();
					//"$dataResult" = Array [5]
					//	msgno = (int) 0
					//	message = (string:0)
					//	backurl = (string:0)
					//	data = Array [3]
					//		status = (int) 1
					//		info = (string:0)
					//		data = Array [1]
					//			order_id = (int) 64125
					//	backparams = (string:0)
					if ($dataResult['data']['status']==1){
						$sendOk=1;
					}else{
						$errorInfo = $dataResult['message'];
					}
					break;
				case 3:
					$gameServerList = $this->_getGlobalData ( 'server/server_list_'. $_POST['game_type_id']);
					$gameServerUrl = $gameServerList [$_POST ['server_id']] ['server_url'];
					$utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
					$sendData['userId'] = $sendData['game_user_id'];
					$sendData['questionType'] = $sendData['question_type'];
					unset($sendData['game_user_id'],$sendData['question_type']);
					$sendData['source'] = 3;
					$utilHttpMInterface->addHttp($gameServerUrl,'question/saveQuestion',array(),$sendData);
					$utilHttpMInterface->send();
					$data = $utilHttpMInterface->getResults();
					$data = json_decode(array_shift($data),true);
					if($data && $data['r']===0){
						$sendOk = true;
					}
					//"$data" = Array [2]
					//	r = (int) 0
					//	m = Array [1]
					//		order_id = (int) 64113
					break;
				case 4:
					$this->_utilMsg->showMsg('暂未开放',-1);
					break;
				case 5:
					$this->_utilMsg->showMsg('暂未开放',-1);
					break;
				case 6:
					$this->_utilMsg->showMsg('暂未开放',-1);
					break;
				case 7:
					$this->_utilMsg->showMsg('暂未开放',-1);
					break;
				default :
					$this->_utilMsg->showMsg('游戏选择错误',-1);
			}
			if ($sendOk){
				$this->_utilMsg->showMsg('操作成功',1);
			}else {
				$this->_utilMsg->showMsg($errorInfo,-1);
			}
		}
		else{
			$this->_modelGameOperator=$this->_getGlobalData ( 'Model_GameOperator', 'object' );
			$servers = $this->_modelGameOperator->getGmOptSev();
			$this->_view->assign('gameTypes',json_encode($gameTypes));
			$this->_view->assign('operators',json_encode($operators));
			$this->_view->assign('servers',json_encode($servers));
			$this->_view->assign('questionType',json_encode($questionType));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	public function actionVIPQuestion(){

		$getData["game_id"]	=	$_GET['Game_id'];
		$getData["_time"]=CURRENT_TIME;
		$getData["_unique"]	=	"1";
		$gameObject = $this->_getGlobalData($_GET['Game_id'],'game');
		$getData["_sign"]	=	md5(CURRENT_TIME.$getData["game_id"].$getData["_unique"].$gameObject->_key);
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$sendData["id"]=$_GET['ID'];
		$sendData["content"]=$_GET['content'];
		$data = $utilHttpInterface->result("http://127.0.0.1/",'admin.php?c=InterfaceWorkOrder&a=Reply',$getData,$sendData);

		$data = json_decode($data,true);

		if ($data["status"]==1){
			$this->_utilMsg->showMsg('操作成功',1);
		}else {
			$this->_utilMsg->showMsg($errorInfo,-1);
		}
	}

	public function actionChangetheStatus(){
		if($this->_isPost()){
			foreach($_POST['ids'] as $_msg){
				if(!$this->_modelWorkOrder->ChangeStatus($_msg,$_POST['status'])){
					$error	.=	"{id:".$_msg."}";
				}
			}
			if(empty($error)){
				$this->_utilMsg->showMsg($error,-1);
			}else{
				$this->_utilMsg->showMsg('操作成功',1);
			}

		}
	}

	public function actionVIPQuestionEnd(){
		$getData["game_id"]	=	$_GET['Game_id'];
		$getData["_time"]=CURRENT_TIME;
		$getData["_unique"]	=	"1";
		$gameObject = $this->_getGlobalData($_GET['Game_id'],'game');
		$getData["_sign"]	=	md5(CURRENT_TIME.$getData["game_id"].$getData["_unique"].$gameObject->_key);
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$sendData["id"]=$_GET['ID'];
		$sendData["ev"]=$_GET['Type'];
		$data = $utilHttpInterface->result("http://127.0.0.1/",'admin.php?c=InterfaceWorkOrder&a=Evaluate',$getData,$sendData);
		$data = json_decode($data,true);
		if ($data["status"]==1){
			$this->_utilMsg->showMsg('操作成功',1);
		}else {
			$this->_utilMsg->showMsg($data["info"],-1);
		}
	}

	private function _upload() {
		$this->_loadCore('Help_FileUpload');
		$uploadPath = UPDATE_DIR . '/Player/' . date ( 'Ymd', CURRENT_TIME );
		$helpFileUpload=new Help_FileUpload($_FILES['image'],$uploadPath);
		$helpFileUpload->setBaseUrl(__ROOT__.'/Upload/Player/'.date('Ymd',CURRENT_TIME));
		if (is_array($_FILES['image']['name'])){
			$helpFileUpload->upload();
			return $helpFileUpload->getSaveInfo();
		}else{
			$helpFileUpload->singleUpload();
			return array($helpFileUpload->getSaveInfo());
		}
	}
}