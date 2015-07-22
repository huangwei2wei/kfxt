<?php
/**
 * 后台工单工单webservice接口
 * @author php-朱磊
 */
class Control_InterfaceWorkOrder extends Control {

	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;

	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

	/**
	 * Model_WorkOrderDetail
	 * @var Model_WorkOrderDetail
	 */
	private $_modelWorkOrderDetail;

	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;

	/**
	 * Model_GameOperator
	 * @var Model_GameOperator
	 */
	private $_modelGameOperator;

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;

	/**
	 * key
	 * @var string
	 */
	private $_key = TAKE_KEY;

	/**
	 * Model_QuestionType
	 * @var Model_QuestionType
	 */
	private $_modelQuestionType;

	/**
	 * 玩家资料获取类
	 * @var Util_GameUserManage
	 */
	private $_utilGameUserManage;

	public function __construct() {
		if (! $this->_initialize ()) //如果不通过验证将退出返回出错数据
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'ERROR', 'data' => null ) );
	}

	/**
	 * 是否通过验证
	 */
	private function _initialize() {
		$sign = $_REQUEST ['_sign'];
		$verifyCode = $_REQUEST ['_verifycode'];
		if (isset ( $sign ) && isset ( $verifyCode )) {
			if (md5 ( $this->_key . $verifyCode ) == $sign) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 文件上传
	 */
	private function _upload() {
		$this->_loadCore('Help_FileUpload');
		$uploadPath = UPDATE_DIR . '/Player/' . date ( 'Ymd', CURRENT_TIME );
		$helpFileUpload=new Help_FileUpload($_FILES['image'],$uploadPath);
		$helpFileUpload->setBaseUrl(__ROOT__.'/Upload/Player/'.date('Ymd',CURRENT_TIME));
		$helpFileUpload->singleUpload();
//		Tools::dump($helpFileUpload->getSaveInfo());
		return $helpFileUpload->getSaveInfo();
	}

	/**
	 * 官网提问
	 */
	private function _saveSource1(){
		$orderArr = array ();
		#------获取用户信息------#
		$orderArr['user_nickname']=$_POST['user_nickname'];
		$orderArr['money']=$_POST['money_total'];
		#------获取用户信息------#

		$orderArr ['game_type'] = $_POST ['game_id'];
		$orderArr ['user_account'] = $_POST ['user_account'];
		$orderArr ['source'] = $_POST ['source']; //工单来源
		$orderArr ['title'] = strip_tags($_POST['title']);
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		$orderArr ['question_type'] = $_POST ['question_type'];
		$orderArr ['vip_level'] = 0;
		$orderArr ['timeout'] = FRONT_WORKORDER_TIMEOUT; //默认2个小时超时
		$orderArr ['operator_id'] = $_POST ['operator_id'];
		$orderArr ['game_server_id'] = $_POST ['game_server_id'];
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );
		$roomId = $gameServerList [$orderArr ['game_server_id']] ['room_id'];	//获取房间ID,这个服务器属于哪个工作区的
		if ($roomId)
			$orderArr ['room_id'] = $gameServerList [$orderArr ['game_server_id']] ['room_id'];

		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $_POST ['game_id'], $_POST ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}
		$orderArr ['vip_level'] = $this->_modelGameOperator->getVipLevel ( $gameOperatorIndex ['vip_setup'] ['vip_pay'], $_POST ['money_total'] );
		$orderArr ['timeout'] = $this->_modelGameOperator->getTimeOut ( $gameOperatorIndex ['vip_setup'] ['vip_timeout'], $orderArr ['vip_level'] );
		$orderArr ['timeout'] *= 60; //换成秒


		if (! $this->_modelWorkOrder->add ( $orderArr )) { //创建表单失败
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'Failure to create order', 'data' => null ) );
		}

		#------为房间工单数加1------#
		if ($orderArr ['room_id']) {
			$this->_utilRooms = $this->_getGlobalData ( 'Util_Rooms', 'object' );
			$roomCLass = $this->_utilRooms->getRoom ( $orderArr ['room_id'] );
			$roomCLass->addOrderNum ( 1 );
			$roomCLass->setUpdateInfo ( 1 );
			$roomCLass = null;
		}
		#------为房间工单数加1------#
		$workOrderId = $this->_modelWorkOrder->returnLastInsertId (); //获取工单id
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
			$orderManageClass->setUpdateInfo ( 1 );
			$orderManageClass = null;
		}
		#------增加新的工单到队列------#
		$orderDetail = array ();
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$orderDetail ['form_detail'] = array ();
		#------获取问题类型额外信息------#
		$questionType = $this->_modelQuestionType->findById ( $_POST ['question_type'] );
		$questionFormTable = $questionType ['form_table'];
		$questionFormTableKey = Model::getTtwoArrConvertOneArr ( $questionFormTable, 'name', 'name' );
		if (! empty ( $questionFormTableKey )) {
			foreach ( $questionFormTableKey as $value ) {
				if ($value == null)
					continue;
				$orderDetail ['form_detail'] [$value] = $_POST [$value];
			}
		}
		#------获取问题类型额外信息------#
		$orderDetail['user_data']=array(
			'user_id'=>$_POST['user_id'],
			'user_account'=>$_POST['user_account'],
			'user_nickname'=>$_POST['user_nickname'],
			'money_total'=>$_POST['money_total'],
			'money_month'=>$_POST['money_month'],
			'register_date'=>$_POST['register_date'],
			'ip'=>$_POST['ip'],
		);
		$orderDetail = serialize ( $orderDetail );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$this->_modelWorkOrderDetail->add ( array ('work_order_id' => $workOrderId, 'content' => $orderDetail ) );


		#-----插入对话表------#
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$content=strip_tags($_POST ['content']);
		if ($_POST['upload_img']){//如果有post专过来的图片路径
			$content.='<br>玩家截图：';
			foreach ($_POST['upload_img'] as $imgPath){
				$content.="<br><img src='{$imgPath}' /><hr size='1' /></br>";
			}
		}
		$orderDialog = array (
						'game_type_id' => $_POST ['game_id'],
						'operator_id' => $orderArr ['operator_id'],
						'work_order_id' => $workOrderId,
						'qa' => '0',
						'content' => $content,
						'create_time' => CURRENT_TIME );

		$this->_modelWorkOrderQa->add ( $orderDialog );
		#-----插入对话表------#
		$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => array('order_id'=>$workOrderId) ) );
	}

	/**
	 * 游戏内提问
	 */
	private function _saveSource2(){
		$orderArr = array ();
		$orderArr ['game_type'] = $_POST ['game_id'];
		$orderArr ['user_account'] = $_POST ['user_account'];
		$orderArr ['user_nickname'] = $_POST ['user_nickname'];
		$orderArr ['money'] = $_POST ['money_total'];
		$orderArr ['source'] = $_POST ['source']; //工单来源
		$orderArr ['title'] = $_POST['title'];
		$orderArr ['question_type'] = $_POST ['question_type'];
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		if ($_FILES['image'] ) $updateInfo=$this->_upload ();		//如果有上传图片就上传文件
		$serverMarking = $_POST ['server_marking'];
		$gameServerList = $this->_modelGameSerList->findByMarking ( $_POST['game_id'],$serverMarking );
		if (! $gameServerList) { //未找到服务器
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $_POST ['game_id'], $gameServerList ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}
		$orderArr ['vip_level'] = $this->_modelGameOperator->getVipLevel ( $gameOperatorIndex ['vip_setup'] ['vip_pay'], $_POST ['money_total'] );
		$orderArr ['timeout'] = $this->_modelGameOperator->getTimeOut ( $gameOperatorIndex ['vip_setup'] ['vip_timeout'], $orderArr ['vip_level'] );
		$orderArr ['timeout'] *= 60; //换成秒
		$orderArr ['operator_id'] = $gameServerList ['operator_id'];
		$orderArr ['game_server_id'] = $gameServerList ['Id'];
		if ($gameServerList ['room_id'])
			$orderArr ['room_id'] = $gameServerList ['room_id'];

		if (! $this->_modelWorkOrder->add ( $orderArr )) { //创建表单失败
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'Failure to create order', 'data' => null ) );
		}

		#------为房间工单数加1------#
		if ($orderArr ['room_id']) {
			$this->_utilRooms = $this->_getGlobalData ( 'Util_Rooms', 'object' );
			$roomCLass = $this->_utilRooms->getRoom ( $orderArr ['room_id'] );
			$roomCLass->addOrderNum ( 1 );
			$roomCLass->setUpdateInfo ( 1 );
			$roomCLass = null;
		}
		#------为房间工单数加1------#
		$workOrderId = $this->_modelWorkOrder->returnLastInsertId (); //获取工单id
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
			$orderManageClass->setUpdateInfo ( 1 );
			$orderManageClass = null;
		}
		#------增加新的工单到队列------#
		$orderDetail = array ();
		$orderDetail ['form_detail'] = array ();

		$orderDetail ['user_data'] = array (
									'user_id' => $_POST ['user_id'],
									'user_account' => $_POST ['user_account'],
									'user_nickname' => $_POST ['user_nickname'],
									'money_total' => $_POST ['money_total'],
									'money_month' => $_POST ['money_month'],
									'register_date' => $_POST ['register_date'],
									'ip' => $_POST ['ip'] );
		$orderDetail = serialize ( $orderDetail );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$this->_modelWorkOrderDetail->add ( array ('work_order_id' => $workOrderId, 'content' => $orderDetail ) );


		#-----插入对话表------#
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$content=$_POST ['content'];
		if ($updateInfo['web_path']){
			$content.="<br>玩家截图：<br> <img src='{$updateInfo['web_path']}' />";
		}
		$orderDialog = array (
						'game_type_id' => $_POST ['game_id'],
						'operator_id' => $orderArr ['operator_id'],
						'work_order_id' => $workOrderId,
						'qa' => '0',
						'content' => $content,
						'create_time' => CURRENT_TIME );

		$this->_modelWorkOrderQa->add ( $orderDialog );
//		if (!Tools::isUtf8($content))echo 'is not utf8';
		#-----插入对话表------#
		$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => array('order_id'=>$workOrderId,'image_path'=>$updateInfo['web_path']) ) );
	}

	/**
	 * 保存工单
	 */
	public function actionQuestionSave() {
//		foreach ($_POST as &$value){
//			if (is_array($value))continue;
//			$value=rawurldecode(urldecode($value));
//		}
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );

		switch ($_POST['source']){
			case '1' :{//官网提问
				$this->_saveSource1();
				break;
			}
			case '2' :{//游戏内提问
				$this->_saveSource2();
				break;
			}
		}
	}

	/**
	 * 用户追问
	 */
	public function actionReply() {
//		foreach ($_POST as &$value){
//			$value=rawurldecode(urldecode($value));
//		}
		if ($_FILES['image'] ) $updateInfo=$this->_upload ();		//如果有上传图片就上传文件

		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		if ($updateInfo['web_path'])$_POST ['content'].="<br>玩家截图：<br> <img src='{$updateInfo['web_path']}' />";
		$addArr = array ('work_order_id' => $_POST ['id'], 'content' => $_POST ['content'], 'qa' => 0, 'create_time' => CURRENT_TIME );
		$updateArr = array ('create_time' => CURRENT_TIME, 'status' => 1, 'question_num' => 'question_num+1' );
		$this->_modelWorkOrder->update ( $updateArr, "Id={$_POST['id']}" );
		$this->_modelWorkOrderQa->add ( $addArr );
		$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => array('image_path'=>$updateInfo['web_path']) ) );
	}

	/**
	 * 工单评价
	 */
	public function actionEvaluate() {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$workOrderId = $_POST ['id'];
		$ev = $_POST ['ev'];
		$workOrder = $this->_modelWorkOrder->findById ( $workOrderId );
		if (! $workOrder) { //未找到工单
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'work order non-existent', 'data' => null ) );
		}
		if ($workOrder ['evaluation_status'] != '0') { //工单已经评价
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'already evaluation', 'data' => null ) );
		}
		$this->_modelWorkOrder->update ( array ('evaluation_status' => $ev ), "Id={$workOrderId}" );
		if ($ev == 3 && ! empty ( $_POST ['des'] )) { //如果等于3,而且留言不为空.将获取用户填的资料
			$workOrderDetail = $this->_modelWorkOrderDetail->findByWorkOrderId ( $workOrderId );
			$detail = unserialize ( $workOrderDetail ['content'] );
			$detail ['other'] ['ev'] = $_POST ['des'];
			$detail = serialize ( $detail );
			$this->_modelWorkOrderDetail->update ( array ('content' => $detail ), "work_order_id={$workOrderId}" );
		}
		$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => null ) );
	}


	/**
	 * 删除工单
	 */
	public function actionDel() {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$workOrderId = $_REQUEST ['id'];
		if (is_array ( $workOrderId )) { //判断单个删除或是多个删除
			$workOrderId = implode ( ',', $workOrderId );
			$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id in ({$workOrderId})" );
		} else {
			$workOrderId = Tools::coerceInt ( $workOrderId );
			$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id={$workOrderId}" );
		}
		if ($isOk) { //是否删除成功
			$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => null ) );
		} else {
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => null, 'data' => null ) );
		}

	}

	/**
	 * 内部队列分配 工单
	 */
	public function actionDistributionOrder() {
		#------自动发送工单队列------#
		$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
		$orderManage = $this->_utilWorkOrder->getOrderManage ();
		//		$orderManage['_workOrder']=array();
		$orderManage->sendOrder ();
		$orderManage->setUpdateInfo ( 1 );
		Tools::dump ( $orderManage );
		#------自动发送工单队列------#
	}

}