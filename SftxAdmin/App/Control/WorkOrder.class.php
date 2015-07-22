<?php
/**
 * 工单处理模块
 * @author php-朱磊
 */
class Control_WorkOrder extends Control {
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
	 * Util_ApiSftx
	 * @var Util_ApiSftx
	 */
	private $_utilApiSftx;

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
		$this->_url ['WorkOrder_TestAdd'] = Tools::url ( CONTROL, 'TestAdd' );
		$this->_url ['Question_Index'] = Tools::url ( 'Question', 'Index' );
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 工单显示列表
	 */
	public function actionIndex() {
		#------初始化------#
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$orgList=$this->_getGlobalData('org');
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','nick_name');
				$value['user']=$curOrgUser;
			}
		}
		#------初始化------#

		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		#------分页生成sql------#
		$helpSqlSearch = $this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
		}

		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		}

		if ($_GET['Id']){
			$helpSqlSearch->set_conditions("Id={$_GET['Id']}");
			$this->_view->assign('selectedId',$_GET['Id']);
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

		$helpSqlSearch->set_orderBy('create_time desc');


		$sql = $helpSqlSearch->createSql ();
		$conditions=$helpSqlSearch->get_conditions();

		$this->_loadCore ( 'Help_Page' );
		$helpPage = new Help_Page ( array ('total' => $this->_modelWorkOrder->findCount ( $conditions ), 'perpage' => 20 ) );
		$this->_view->assign ( 'pageBox', $helpPage->show () );


		#------分页生成sql------#
		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=array('0'=>'普通','1'=>'一级','2'=>'二级','3'=>'三级','4'=>'四级','5'=>'五级','6'=>'六级',''=>'所有');
		$users=$this->_getGlobalData('user');
//		$users=Model::getTtwoArrConvertOneArr($users,'Id','full_name');
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');

		#------载入缓存------#

		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url('QualityCheck','OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$questionArr = $this->_modelQuestionType->findById ( $list ['question_type'] );
				$list ['word_question_type'] = $questionArr ['title'] ? $questionArr ['title'] : '游戏提问';
				$list ['url_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $list ['Id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list['word_game_server_id']=$serverList[$list['game_server_id']];
				if ($list['status']==1){
					$isTimeout=Tools::isTimeOut($list['create_time'],$list['timeout']);
					if ($isTimeout===true){//已超时
						$list['time_out_true']=true;
					}else {//未超时
						$list['lost_time']=Tools::getTimeFormat($isTimeout);
					}
				}

				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				if ($list['word_owner_user_id'])
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$gameTypeArr['']='所有';
		$this->_view->assign('gameTypeList',$gameTypeArr);
		$operatorList['']='所有';
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']='所有';
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 通过webservice发送消息
	 */
	private function _sendMsg($data) {
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$this->_utilApiSftx->addHttp($data['server_url'],null,$data);
		$this->_utilApiSftx->send();
		$dataResult=$this->_utilApiSftx->getResult();
		if ($dataResult['status']==1){
			return true;
		}else {
			return '向游戏发送消息失败';
		}
		/*
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
				return '游戏内部错误 ：'.$data['message'];
			}
		}else {
			return '向游戏发送消息失败';
		}*/
	}

	/**
	 * 回复用户信息
	 */
	public function actionReply() {
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$timeout = intval ( strtotime($_POST ['create_time']) ) + intval ( $_POST ['timeout'] );
		if (CURRENT_TIME > $timeout && $_POST ['cur_status'] == 1) { //如果当前时间大于超时时间,并且当前工单为未处理状态,就说明回复时间已经过了,那么就超时
			$isTimeout = '1';
		} else {
			$isTimeout = '0';
		}

		$replyContent=$_POST ['reply'];
		if ($_POST['lock'])$replyContent='<em>[该消息未发送到游戏服务器]</em> '.$replyContent;	//如果为不发送消息将自动在前面加上
		$addQaArr = array (
				'is_timeout' => $isTimeout,
				'work_order_id' => $_POST ['Id'],
				'content' => $replyContent,
				'qa' => 1,
				'reply_name' => $userClass ['_userName'],
				'create_time' => CURRENT_TIME,
				'game_type_id' => $_POST ['game_type_id'],
				'operator_id' => $_POST ['operator_id'] );

		if ($isTimeout)$addQaArr['last_reply_time']=$timeout;	//如果超时的话,将记录最后超时的时间

		if ($_POST ['source'] == 2 && !$_POST['lock']) { //如果工单来源为2并且lock为不锁定,那么就要发送webservice
			$gameServerList = $this->_getGlobalData ( 'gameser_list' );
			$gameServerUrl = $gameServerList [$_POST ['game_server_id']] ['send_msg_url']; //获取url地址
			if (!$gameServerUrl)$this->_utilMsg->showMsg('发送消息给游戏服务端失败',-2);
			$isOk = $this->_sendMsg ( array (
									'work_order_id' => $_POST ['Id'],
									'service_id' => $userClass ['_serviceId'] ? $userClass ['_serviceId'] : '001',
									'content' => $replyContent,
									'server_url' => $gameServerUrl,
									'status'=>$_POST ['status'] ) );
			if ($isOk !== true)
				$this->_utilMsg->showMsg ( $isOk, - 2 );
		}
		$this->_modelWorkOrderQa->add ( $addQaArr );

		#------改变房间工单,用户回复数------#
		$userClass->setUpdateInfo(1);
		$userClass->addToReplyNum(1);
		if ($_POST['owner_user_id']==$userClass['_id'] && $_POST ['cur_status']==1)$userClass->setIncompleteOrderNum(-1);//用户完成一个工单
		if ($_POST ['cur_status']==1){//如果为未回复将房间未完成工单数减1
			$roomClass=$this->_utilRooms->getRoom($_POST['room_id']);
			if (is_object($roomClass)){
				$roomClass->completeOrder(1);	//完成一个工单数
				$roomClass->setUpdateInfo(1);
			}
		}
		#------改变房间工单,用户回复数------#

		$workOrderUpdateArr = array ('status' => $_POST ['status'], 'answer_num' => 'answer_num+1', 'owner_user_id' => $userClass ['_id'] ); //变为待处理,提问数+1
		$this->_modelWorkOrder->update ( $workOrderUpdateArr, "Id={$_POST['Id']}" );
		$this->_utilMsg->showMsg ( false );
	}

	/**
	 * 查看工单详细
	 */
	public function actionDetail() {
		$dataList = $this->_modelWorkOrder->findByIdToDetail ( $_GET ['Id'] );
		$dialogList = $this->_modelWorkOrderQa->findByWorkOrderId ( $_GET ['Id'] );

		#------载入缓存------#
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' ); //游戏类型
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' ); //工单来源
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' ); //工单状态
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );
		$gameServerList = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$user = $this->_getGlobalData ( 'user' );
		$departmentList = $this->_getGlobalData ( 'department' );
		$departmentList = Model::getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
		#------载入缓存------#


		#------跟椐ID转换文字显示------#
		$dataList['word_status']=$workOrderStatusArr[$dataList['status']];
		$dataList ['word_game_type'] = $gameTypeArr [$dataList ['game_type']];
		$dataList ['word_source'] = $workOrderSourceArr [$dataList ['source']];
		$dataList ['word_game_server_id'] = $gameServerList [$dataList ['game_server_id']];
		$dataList ['word_operator_id'] = $operatorList [$dataList ['operator_id']];
		$dataList ['create_time'] = date ( 'Y-m-d H:i', $dataList ['create_time'] );
		$dataList ['word_quality_id'] = ($dataList ['quality_id'] < 0) ? $user [$dataList ['quality_id']] ['nick_name'] . "[{$departmentList[$user[$dataList['quality_id']]['department_id']]}]" : '未质检';
		#------跟椐ID转换文字显示------#


		$user = Model::getTtwoArrConvertOneArr ( $user, 'user_name', 'full_name' );
		foreach ( $dialogList as &$list ) {
			$list ['create_time'] = date ( 'Y-m-d H:i:s', $list ['create_time'] );
			$list ['word_reply_name'] = $user [$list ['reply_name']];
		}

		$workOrderDetailArr = unserialize ( $dataList ['content'] ); //获取工单的详细信息
		$userData = $workOrderDetailArr ['user_data']; //获取提交工单用户的详细信息
		$userData ['register_date'] =$userData ['register_date']? date ( 'Y-m-d H:i:s', $userData ['register_date'] ):'';

		if ($dataList ['evaluation_status'] != 0) { //如果已经评价
			$playerEvaluation = $this->_getGlobalData ( 'player_evaluation' );
			$evaluation = $playerEvaluation [$dataList ['evaluation_status']];
			$evaluation = $evaluation ['title'];
			if ($dataList ['evaluation_status'] == 3)
				$evaluation .= '：&nbsp;' . $workOrderDetailArr ['other'] ['ev'];
			$this->_view->assign ( 'evaluation', $evaluation );
		}

		if ($dataList ['source'] != 2) { //如果工单不是从游戏里面来的话
			$userQuestionDetail = $workOrderDetailArr ['form_detail']; //获取提问类型工单的值
			$questionDetail = $this->_modelQuestionType->findById ( $dataList ['question_type'] ); //查找问题类型
			$dataList ['word_question_type'] = $questionDetail ['title']; //获取问题类型的中文名称以方便显示
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

		unset ( $workOrderStatusArr [1] );	//删除待处理状态
		unset ( $workOrderStatusArr [4] );	//删除被玩家删除状态
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
		$this->_utilMsg->createNavBar();
		$this->_view->display ();

	/*
		#----------第一种方式----------#
		$dataList = $this->_modelWorkOrder->findByIdDetail ( $_GET ['Id'] ); //获取工单整个数组,包括关联表
		$dialogArr = $dataList; //列表详细
		$dataList = $dataList [0]; //客服与用户的对话数组
		#------载入缓存------#
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' ); //游戏类型
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' ); //工单来源
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' ); //工单状态
		$gameSerList = $this->_modelGameSerList->findById ( $dataList ['game_server_id'] );
		$operatorList = $this->_modelOperatorList->findById ( $dataList ['operator_id'] );
		#------载入缓存------#


		foreach ( $dialogArr as &$list ) {
			$list ['qa_time'] = date ( 'Y-m-d H:i:s', $list ['qa_time'] );
		}

		$workOrderDetailArr = unserialize ( $dataList ['detail'] ); //获取工单的详细信息
		$userData = $workOrderDetailArr ['user_data']; //获取提交工单用户的详细信息
		$userQuestionDetail = $workOrderDetailArr ['form_detail']; //获取提问类型工单的值
		$questionDetail = $this->_modelQuestionType->findById ( $dataList ['question_type'] ); //查找问题类型
		$user=$this->_getGlobalData('user');
		$departmentList=$this->_getGlobalData('department');
		$departmentList=Model::getTtwoArrConvertOneArr($departmentList,'Id','name');



		#------跟椐ID转换文字显示------#
		$dataList ['word_question_type'] = $questionDetail ['title']; //获取问题类型的中文名称以方便显示
		$dataList ['word_game_type'] = $gameTypeArr [$dataList ['game_type']];
		$dataList ['word_source'] = $workOrderSourceArr [$dataList ['source']];
		$dataList ['word_status'] = $workOrderStatusArr [$dataList ['status']];
		$dataList ['word_game_server_id'] = $gameSerList ['server_name'];
		$dataList ['word_operator_id'] = $operatorList ['operator_name'];
		$dataList ['create_time'] = date ( 'Y-m-d H:i', $dataList ['create_time'] );
		$dataList['word_quality_id']=($dataList['quality_id']<0)?$user[$dataList['quality_id']]['nick_name']."[{$departmentList[$user[$dataList['quality_id']]['department_id']]}]":'未质检';
		#------跟椐ID转换文字显示------#

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

		$this->_view->assign ( 'workOrderStatusArr', $workOrderStatusArr );
		$this->_view->assign ( 'userData', $userData );
		$this->_view->assign ( 'userQuestionDetailArr', $userQuestionDetailArr ); //问题类型显示
		$this->_view->assign ( 'dialogArr', $dialogArr ); //对话详细
		$this->_view->assign ( 'data', $dataList ); //表单详细信息
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_url ['Verify_OrderVerify']=Tools::url('Verify','OrderVerify',array('work_order_id'=>$_GET ['Id']));
		$this->_view->assign('url',$this->_url);
		$this->_view->display ();*/
	#----------第一种方式----------#

	}

	/**
	 * 更改状态
	 */
	public function actionChangeStatus(){
		if ($this->_isAjax()){
			$id=Tools::coerceInt($_GET['Id']);
			$status=Tools::coerceInt($_GET['status']);
			if ($this->_modelWorkOrder->update(array('status'=>$status),"Id={$id}")){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>'更改状态成功'));
			}else{
				$this->_returnAjaxJson(array('status'=>0,'msg'=>'更改状态失败'));
			}
		}
	}

	/**
	 * 测试设置页面
	 */
	public function actionDebug() {

		/*
		#------自动发送工单队列------#
		$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
		$orderManage = $this->_utilWorkOrder->getOrderManage ();
		//		$orderManage['_workOrder']=array();
		$orderManage->sendOrder ();
		$orderManage->setUpdateInfo ( 1 );
		Tools::dump ( $orderManage );
		#------自动发送工单队列------#
		*/

		#------发送测试工单------#
		set_time_limit ( 0 );
		$nameArr = range ( 'a', 'z' );
		Tools::import ( 'Util_WebService' );

		$askList = array ('魅力值如何提升？', '如何获得信赖度？', '座驾合成后，属性变差了？', '在哪里买车？ ', '使用了座驾，但做常务工作怎么没有效果的？ ', '租用的座驾有什么效果？ ', '我有座驾，但做看报纸的时间没有减短', '座驾合成一定能合成好东西吗？ ', '为什么我拥有机动车了，为什么不能去旅游？', '有没二手车行？ ', ' 租用的座驾有什么效果？ ', '我充值成功，没获得金币？ ', '官网充值失败 ', '拆除工厂后，售销经验还在吗？仓库等级会不会少？ ', '如何获得销售经验？', '原油原料 ', '创建工厂需要什么条件 ', '工厂如何采购原材料 ', '如何提高原料的获取速度 ', '工厂的销售经验有什么用 ', '天然气有什么用？ ', '特产货物', '如何提升社会地位 ', '豪宅仓库原料怎么运输 ', '如何获得信赖度 ', '人物经验怎么获得？ ', '如何加好友？', '战略合作有什么好处 ', '名片夹内的好友如何进行互动？ ', '怎么我今天进行出差没有双倍奖励？ ' );
		$this->_utilWebService = new Util_WebService ();
		$userName = array_rand ( $nameArr, 7 );
		$nickName = '';
		foreach ( $userName as $value ) {
			$nickName .= $nameArr [$value];
		}

		$this->_utilWebService->setUrl ( 'http://crm.uwan.com/sftxadmin.php?c=InterfaceWorkOrder&a=QuestionSave' );
		$sign = MD5 ( 'cndw_kefu' . CURRENT_TIME );
		$title = array_rand ( $askList );
		$title = $askList [$title];
		$content = array_rand ( $askList );
		$content = $askList [$content];
		$arr = array ('_verifycode' => CURRENT_TIME,
						'game_id' => 2,
						'_sign' => $sign,
						'user_id' => rand ( 0, 3000 ),
						'user_account' => 'test',
						'user_nickname' => $nickName,
						'money_total' => rand ( 0, 80000 ),
						'money_month' => rand ( 0, 2000 ),
						'server_marking' => 1,
						'title' => $title,
						'content' => $content,
						'source' => 2,
						'register_date' => CURRENT_TIME,
						'ip' => rand ( 127, 255 ) . '.' . rand ( 127, 255 ) . '.' . rand ( 127, 255 ) . '.' . rand ( 127, 255 ) );
		$this->_utilWebService->setPost ( $arr );
		$this->_utilWebService->sendData ();
		$data = $this->_utilWebService->getRaw ();
		$data = json_decode ( $data );
		unset ( $this->_utilWebService );
		$this->_utilWebService = null;
		#------发送测试工单------#
	}

	public function actionClearOrder() {
		Tools::import ( 'Util_WorkOrder' );
		$utilWorkOrder = new Util_WorkOrder ();
		$orderManage = $utilWorkOrder->getOrderManage ();
		$orderManage ['_workOrder'] = array ();
		$orderManage->setUpdateInfo ( 1 );
	}
}