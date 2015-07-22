<?php
Tools::import('Control_Interface');
/**
 * 后台工单工单webservice接口
 * @author php-朱磊
 */
class Control_InterfaceWorkOrder extends ApiInterface {

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

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
	 * Model_QuestionType
	 * @var Model_QuestionType
	 */
	private $_modelQuestionType;

	/**
	 * 玩家资料获取类
	 * @var Util_GameUserManage
	 */
	private $_utilGameUserManage;

	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;

	/**
	 * Model_OrderLog
	 * @var Model_OrderLog
	 */
	private $_modelOrderLog;


	public function __construct() {
		parent::__construct();
	}

	/**
	 * 文件上传
	 */
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



	/**
	 * 官网提问
	 */
	private function _saveSource1(){
		$orderArr = array ();
		#------获取用户信息------#
		$orderArr['user_nickname']=strip_tags($_POST['user_nickname']);
		$orderArr['money']=$_POST['money_total'];
		#------获取用户信息------#

		$orderArr ['game_type'] = $_POST ['game_id'];
		$orderArr ['user_account'] = $_POST ['user_account']?strip_tags($_POST ['user_account']):0;
		$orderArr ['source'] = $_POST ['source']; //工单来源
		$orderArr ['title'] = strip_tags($_POST['title']);
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		$orderArr ['question_type'] = $_POST ['question_type'];
		$orderArr ['vip_level'] = 0;
		$orderArr ['operator_id'] = $_POST ['operator_id'];
		$orderArr ['game_server_id'] = $_POST ['game_server_id'];
		$orderArr ['game_user_id']=trim($_POST['user_id']);
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
		$orderArr ['is_verify']=0;

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
						'user_id'=>0,
						'game_type_id' => $_POST ['game_id'], 
						'operator_id' => $orderArr ['operator_id'], 
						'work_order_id' => $workOrderId, 
						'qa' => '0', 
						'content' => $content, 
						'create_time' => CURRENT_TIME );

		$this->_modelWorkOrderQa->add ( $orderDialog );
		#-----插入对话表------#
		eaccelerator_rm('question_'.$orderArr['user_account']);
		eaccelerator_put('question_'.$orderArr['user_account'], md5($orderArr['user_account'].$orderArr['title']),60);
		$this->_returnAjaxJson ( array ('status' => 1, 'info' => null, 'data' => array('order_id'=>$workOrderId,'room_id'=>$orderArr ['room_id']) ) );
	}
	
	private function _saveSource4(){
		$orderArr = array ();
		$orderArr ['game_type'] = intval($_REQUEST ['game_id']);
		$orderArr ['is_vip'] = intval($_REQUEST ['is_vip']);
		$orderArr ['user_account'] = $_REQUEST ['user_account']?$_POST ['user_account']:0;
		$orderArr ['user_nickname'] = trim($_REQUEST ['user_nickname']);
		$orderArr ['money'] = intval($_REQUEST ['money_total']);
		$orderArr ['source'] = intval($_REQUEST ['source']); //工单来源
		$orderArr ['title'] = strip_tags($_REQUEST['title']);
		$orderArr ['question_type'] = intval($_REQUEST ['question_type']);
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		$orderArr ['game_user_id']= trim($_REQUEST['user_id']);

		if ($_FILES['image'] ){
			$updateInfo=$this->_upload();		//如果有上传图片就上传文件 
		}
		$serverMarking = trim($_POST ['server_marking']);
		if ($_POST['partner']){
			$serverMarking=$_POST['partner'].'|'.$serverMarking;
		}
		$gameServerList = $this->_modelGameSerList->findByMarking ( $_REQUEST['game_id'],$serverMarking,$_POST['server_name'] );
		if (! $gameServerList) { //未找到服务器
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}

		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $_REQUEST ['game_id'], $gameServerList ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引 
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server or operator non-existent', 'data' => null ) );
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
		$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_QuestionSave', 'data' => $retArr ) );
	}

	/**
	 * 游戏内提问
	 */
	private function _saveSource2(){
		$orderArr = array ();
		$orderArr ['game_type'] = intval($_REQUEST ['game_id']);
		$orderArr ['is_vip'] = intval($_REQUEST ['is_vip']);
		$orderArr ['user_account'] = $_REQUEST ['user_account']?strip_tags($_POST ['user_account']):0;
		$orderArr ['user_nickname'] = strip_tags(trim($_REQUEST ['user_nickname']));
		$orderArr ['money'] = intval($_REQUEST ['money_total']);
		$orderArr ['source'] = intval($_REQUEST ['source']); //工单来源
		$orderArr ['title'] = strip_tags($_REQUEST['title']);
		$orderArr ['question_type'] = intval($_REQUEST ['question_type']);
		$orderArr ['question_num'] = 1;
		$orderArr ['create_time'] = CURRENT_TIME;
		$orderArr ['game_user_id']= trim($_REQUEST['user_id']);

		if ($_FILES['image'] ){
			$updateInfo=$this->_upload();		//如果有上传图片就上传文件 
		}
		$serverMarking = trim($_POST ['server_marking']);
		if ($_POST['partner']){
			$serverMarking=$_POST['partner'].'|'.$serverMarking;
		}
		$gameServerList = $this->_modelGameSerList->findByMarking ( $_REQUEST['game_id'],$serverMarking,$_POST['server_name'] );
		if (! $gameServerList) { //未找到服务器
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server non-existent', 'data' => null ) );
		}

		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $_REQUEST ['game_id'], $gameServerList ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引 
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'game server or operator non-existent', 'data' => null ) );
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
		$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_QuestionSave', 'data' => $retArr ) );
	}

	/**
	 * 自动回复
	 */
	private function _autoreply($msgarr,$url){
		$room	=	$this->_getGlobalData("rooms");
		if($room[$msgarr["room_id"]]["autoreply"]["status"]==1){
			$start	=	strtotime($room[$msgarr["room_id"]]["autoreply"]["start_time"]);
			$end	=	strtotime($room[$msgarr["room_id"]]["autoreply"]["end_time"]);
			if($start>$end){
				$end += 86400;	//60*60*24
			}
				
			if($start<CURRENT_TIME && CURRENT_TIME<$end){
				$game=$this->_getGlobalData($msgarr["game_type"],'game');
				if($game && is_callable(array($game,'sendOrderReplay'))){
					$sendData = array(
						'title'		=>	$room[$msgarr["room_id"]]["autoreply"]["title"],
						'content'	=>	$room[$msgarr["room_id"]]["autoreply"]["content"],
						'player'	=>	$msgarr["game_user_id"],
						'send_url'	=>	$url,
						'server_msg'=>	$msgarr
					);
					$game->autoReplay($sendData);
				}
			}
		}
	}

	/**
	 * 保存工单
	 */
	public function actionQuestionSave() {
		foreach ($_POST as &$value){
			if (is_array($value))continue;
			$value=rawurldecode(urldecode($value));
		}
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		//		//日志测试		
		//		$logs= '$_POST = '.var_export($_POST,true);
		//		$logs .=";\n\r";
		//		$logs .= '$_FILES = '.var_export($_FILES,true);
		//		$logs .=";\n\r";
		//		$logs .= '$_GET = ' .var_export($_GET,true);
		//		$logs .=";\n\r\n\r";
		//		error_log($logs, 3, RUNTIME_DIR.'/Logs/workorder_logs_'.date('Y_m_d_H',time()).".log");
		//		eaccelerator_put('question_'.$orderArr['user_account'], md5($orderArr['user_account'].$orderArr['title']),1);

		$repeat_question_check	=	md5($_POST['user_account'].strip_tags($_POST['title']));
		if($repeat_question_check==eaccelerator_get('question_'.$_POST['user_account'])){
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'A short time submitted repeated', 'data' => null ) );
		}
		switch ($_REQUEST['source']){
			case '1' ://官网提问
				$this->_saveSource1();
				break;
			case '4' ://VIP提问
				$this->_saveSource4();
				break;
			case '2' :
			case '3' ://游戏内提问
				$this->_saveSource2();
				break;
			default:
				$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'source error', 'data' => null ) );
		}



	}

	/**
	 * 用户追问
	 */
	public function actionReply() {

		$_POST['id'] = intval($_POST['id']);

		if(!$_POST['id']){
			$this->_returnAjaxJson ( array ('status' => 0, 'info' =>'error id', 'data' => NULL ) );
		}
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$orderList=$this->_modelWorkOrder->findById($_POST['id']);	//工单详细
		if(!$orderList){
			$this->_returnAjaxJson ( array ('status' => 0, 'info' =>'work order non-existent', 'data' => NULL ) );
		}
		$orderList['status'] = intval($orderList['status']);
		if($orderList['status']<1 || $orderList['status']>3){	//satus是1、2、3才允许回复
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'status forbidden', 'data' => null ) );
		}
		if($orderList['evaluation_status'] != '0'){	//已评价的工单不让追问
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'already evaluation', 'data' => null ) );
		}

		$sql = "SELECT qa,create_time from cndw_work_order_qa where work_order_id = ".$_POST['id']." order by id desc";
		$pevQaInfo = $this->_modelWorkOrder->select($sql,1);
		if($pevQaInfo){
			if($pevQaInfo['qa']==0){
				$pevQusertionTime = $pevQaInfo['create_time'];
				if(time() - $pevQusertionTime < 60 ){// 一分钟内的追问过于频繁
					$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'Too often asked, a minute later to continue', 'data' => null ) );
				}
			}elseif ($pevQaInfo['qa']==1){
				$pevQusertionTime = $pevQaInfo['create_time'];
				if(time() - $pevQusertionTime > 3*24*60*60 ){//回复超过3天 后就不能进行追问
					$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'Questioning the validity of 3 days', 'data' => null ) );
				}
			}
		}

		foreach ($_POST as &$value){
			$value=rawurldecode(urldecode($value));
		}
		if ($_FILES['image'] ) $updateInfo=$this->_upload ();		//如果有上传图片就上传文件 

		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$content=$_POST['content'];
		$content = str_replace(array('\r\n','\r'),chr(10),$content);

		$addArr = array ('work_order_id' => $_POST ['id'], 'content' =>$content, 'qa' => 0, 'user_id' => 0 , 'create_time' => CURRENT_TIME );
		if ($updateInfo && is_array($updateInfo)){
			$retArr = array();
			$_imgArr	=	array();
			$i = '';
			foreach ($updateInfo as $img){
				$retArr["image_path{$i}"]=$img['web_path'];
				$i++;
				$_imgArr[] = str_replace(__ROOT__,'',$img["web_path"]);
			}
			$addArr['image'] = json_encode($_imgArr);
		}
		$updateArr = array ('create_time' => CURRENT_TIME, 'status' => 1, 'question_num' => 'question_num+1' );

		$this->_modelWorkOrder->update ( $updateArr, "Id={$_POST['id']}" );
		$this->_modelWorkOrderQa->add ( $addArr );

		#------检测如果当前负责工单的用户下线了,将重新载入工单------#
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$orderClass=$this->_utilWorkOrder->getOrderManage();
		$userIsOnline=$this->_utilOnline->isUserOnline($orderList['owner_user_id']);	//是否在线
		if (!$userIsOnline){//不在线
			$orderList['game_type_id']=$orderList['game_type'];//处理兼容
			$orderClass->addOrder($orderList);//增加到order队列 
		}
		#------检测如果当前负责工单的用户下线了,将重新载入工单------#

		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('Id'=>$_POST['id']),Model_OrderLog::BACK_ASK);
		#------追加日志------#


		$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_Reply', 'data' => $retArr ) );
	}

	/**
	 * 工单评价
	 */
	public function actionEvaluate() {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$workOrderId = intval($_REQUEST ['id']);
		$ev = intval($_REQUEST ['ev']);
		//不在范围的评价状态，默认好评
		if($ev<1 || $ev>3){
			$ev = 1;
		}
		$workOrder = $this->_modelWorkOrder->findById ( $workOrderId );
		if (! $workOrder) { //未找到工单
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'work order non-existent', 'data' => null ) );
		}
		if($workOrder ['status'] != '3'){	//尚未已处理
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'status forbidden', 'data' => null ) );
		}
		if ($workOrder ['evaluation_status'] != '0') { //工单已经评价
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'already evaluation', 'data' => null ) );
		}
		$updateArr=array('evaluation_status'=>$ev);
		if ($ev==3){//如果等于3就表示不满意,将更新字段
			$des=intval($_REQUEST['des']);
			$updateArr['evaluation_desc']=$des?$des:6;	//默认其他
		}
		$this->_modelWorkOrder->update ( $updateArr, "Id={$workOrderId}" );
		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('Id'=>$workOrderId),Model_OrderLog::EV);
		#------追加日志------#
		$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_Evaluate', 'data' => null ) );
	}


	/**
	 * 删除工单
	 */
	public function actionDel() {
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$workOrderId = $_REQUEST ['id'];
		if(is_string($workOrderId)){
			$workOrderId = explode(',',$workOrderId);
		}elseif(!is_array($workOrderId)){
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'id empty', 'data' => null ) );
		}
		$workOrderId = array_map('intval', $workOrderId);	//所有元素取整
		$workOrderId = array_filter($workOrderId);	//过滤无效元素
		$isOk = false;
		if($workOrderId){
			//增加每个工单的删除日志
			foreach ($workOrderId as $id){
				$this->_modelOrderLog->addLog(array('Id'=>$id),Model_OrderLog::DEL);
			}
			//更改工单删除状态
			if(count($workOrderId)==1){
				$workOrderId = array_shift($workOrderId);
				$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id={$workOrderId}");
			}else{
				$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id in (".implode ( ',', $workOrderId ).")" );
			}
		}
		if ($isOk) { //是否删除成功 
			$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
			$orderClass=$this->_utilWorkOrder->getOrderManage();
			$orderClass->delOrder($workOrderId);
			$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_Del', 'data' => null ) );
		} else {
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'error id', 'data' => null ) );
		}
	}

	/**
	 * 删除某玩家的所有工单
	 */
	public function actionDelAll(){
		$userInfo =array(
			'user_account'=>trim($_REQUEST['user_account']),
			'game_user_id'=>trim($_REQUEST['user_id']),//不排除有些游戏id不能解析成int(例如double)，因此用trim
			'user_nickname'=>trim(urldecode($_REQUEST['user_nickname'])),
		);
		$userInfo = array_filter($userInfo);
		//检查用户信息是否都为空
		if(!$userInfo){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'player empty','data' =>null));
		}
		$gameId = intval($_REQUEST['game_id']);
		if(!$gameId){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'error game_id','data' =>null));
		}
		$serverMarking = trim($_REQUEST['server_marking']);
		if(empty($serverMarking)){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'server_marking empty','data' =>null));
		}
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$gameServerList = $this->_modelGameSerList->findByMarking ($gameId,$serverMarking);
		if (! $gameServerList) { //未找到服务器
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game server non-existent','data'=>null));
		}
		$status = intval($_REQUEST['status']);

		$this->_loadCore('Help_SqlSearch');//载入sql工具		
		$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelWorkOrder->tName());
		$helpSqlSearch->set_field('Id');
		//指定玩家
		$helpSqlSearch->set_conditions(key($userInfo)."='".current($userInfo)."'");
		//来自游戏的工单
		$helpSqlSearch->set_conditions('source=2');
		//来自哪个游戏
		$helpSqlSearch->set_conditions("game_type={$gameId}");
		//确定某个服务器
		$helpSqlSearch->set_conditions("game_server_id={$gameServerList ['Id']}");
		//处理状态，0：待受理（包括：1未受理，2受理中），3：已答复
		if($status != -1){
			if($status == 3){
				$helpSqlSearch->set_conditions('status=3');
			}else{
				$helpSqlSearch->set_conditions('status>=1');
				$helpSqlSearch->set_conditions('status<=2');
			}
		}
		$sql=$helpSqlSearch->createSql();
		$workOrderId = $this->_modelWorkOrder->select($sql);
		$isOk = false;
		if($workOrderId){
			$workOrderId = Model::getTtwoArrConvertOneArr($workOrderId,'Id','Id');
			//增加每个工单的删除日志
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			foreach ($workOrderId as $id){
				$this->_modelOrderLog->addLog(array('Id'=>$id),Model_OrderLog::DEL);
			}
			//更改工单删除状态
			if(count($workOrderId)==1){
				$workOrderId = array_shift($workOrderId);
				$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id={$workOrderId}");
			}else{
				$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id in (".implode ( ',', $workOrderId ).")" );
			}
		}
		if ($isOk) { //是否删除成功
			$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
			$orderClass=$this->_utilWorkOrder->getOrderManage();
			$orderClass->delOrder($workOrderId);
			$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_DelAll', 'data' => null ) );
		}elseif(empty($workOrderId)) {
			$this->_returnAjaxJson ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_DelAll', 'data' => null ) );
		}else{
			$this->_returnAjaxJson ( array ('status' => 0, 'info' => 'unknow error', 'data' => null ) );
		}
	}

	/**
	 * 内部队列分配 工单 
	 */
	public function actionDistributionOrder() {
		#------自动发送工单队列------#
		$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
		$orderManage = $this->_utilWorkOrder->getOrderManage();

		$orderManage->sendOrder();
		$orderManage->setUpdateInfo(1);
		#------自动发送工单队列------#
		$cacheAutoCount=$this->_getGlobalData('Cache_AutoCount','object');
		$cacheAutoCount->vipCount();
		$cacheAutoCount->roomCount();
	}

	private function _writeLog(){
		$msg=array();
		foreach ($_GET as $key=>$value){
			array_push($msg,"GET {$key} ： {$value}");
		}
		foreach ($_POST as $key=>$value){
			array_push($msg,"POST {$key} ： {$value}");
		}
		Tools::addLog(Tools::formatLog($msg),true);
	}

	public function actionWorkOrderList(){
		$userInfo =array(
			'user_account'=>trim($_REQUEST['user_account']),
			'game_user_id'=>trim($_REQUEST['user_id']),//不排除有些游戏id不能解析成int(例如double)，因此用trim
			'user_nickname'=>trim(urldecode($_REQUEST['user_nickname'])),
		);
		$userInfo = array_filter($userInfo);
		//检查用户信息是否都为空
		if(!$userInfo){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'player empty','data' =>null));
		}
		$gameId = intval($_REQUEST['game_id']);
		if(!$gameId){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'error game_id','data' =>null));
		}
		$serverMarking = trim($_REQUEST['server_marking']);
		if(empty($serverMarking)){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'server_marking empty','data' =>null));
		}
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		$gameServerList = $this->_modelGameSerList->findByMarking ($gameId,$serverMarking);
		if (! $gameServerList) { //未找到服务器
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game server non-existent','data'=>null));
		}
		$status = intval($_REQUEST['status']);
		$ev = trim($_REQUEST['ev']);
		$page = max(1,intval($_REQUEST['page']));
		$pageSize = intval($_REQUEST['page_size']);
		if($pageSize<1 || $pageSize>100){
			$pageSize = 10;
		}
		$this->_loadCore('Help_SqlSearch');//载入sql工具		
		$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelWorkOrder->tName());
		$helpSqlSearch->set_field('Id as id,question_type as type,title,create_time as update_time,evaluation_status as ev,status,evaluation_desc as ev_desc,is_read');
		//指定玩家
		$helpSqlSearch->set_conditions(key($userInfo)."='".current($userInfo)."'");
		//来自游戏的工单
		$helpSqlSearch->set_conditions('source=2');
		//来自哪个游戏
		$helpSqlSearch->set_conditions("is_vip='0'");
		$helpSqlSearch->set_conditions("game_type={$gameId}");
		//确定某个服务器
		$helpSqlSearch->set_conditions("game_server_id={$gameServerList ['Id']}");
		//处理状态，0：待受理（包括：1未受理，2受理中），3：已答复 ,4：删除的 ，-1： 表示全部
		if($status != -1){
			if($status == 0){
				$helpSqlSearch->set_conditions('status>=1');
				$helpSqlSearch->set_conditions('status<=2');
			}else{
				$helpSqlSearch->set_conditions('status=3');
			}
		}else {
			$helpSqlSearch->set_conditions('status<>4');
		}
			
		//评价状态
		if($ev=='0'){
			$helpSqlSearch->set_conditions('evaluation_status=0');
		}elseif($ev){
			$helpSqlSearch->set_conditions('evaluation_status='.intval($ev));
		}
		//分页
		$helpSqlSearch->setPageLimit($page,$pageSize);
		//排序
		$helpSqlSearch->set_orderBy('is_read,Id desc');
		$sql=$helpSqlSearch->createSql();
		$conditions=$helpSqlSearch->get_conditions();
		$dataList = $this->_modelWorkOrder->select($sql);
		$total = $this->_modelWorkOrder->findCount($conditions);
		$returnData = array(
			'list' =>$dataList,
			'query_status'=>$status,
			'total' =>$total,
			'page'=>$page,
		);
		$this->_returnAjaxJson(array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$returnData));
	}

	public function actionWorkOrderDetail(){
		$userInfo =array(
			'user_account'=>trim($_REQUEST['user_account']),
			'game_user_id'=>trim($_REQUEST['user_id']),//不排除有些游戏id不能解析成int(例如double)，因此用trim
			'user_nickname'=>trim(urldecode($_REQUEST['user_nickname'])),
		);
		$userInfo = array_filter($userInfo);
		//检查玩家
		if($userInfo){
			$workOrder_condition = key($userInfo)."='".current($userInfo)."'";
		}else{
			$this->_returnAjaxJson(array('status'=>0,'info'=>'player empty','data' =>null));
		}
		$workOrderId = intval($_REQUEST['work_order_id']);
		if(!$workOrderId){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'Work Order Id Empty','data' =>null));
		}
		$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
		$sql_CheckWorkOrder = "select Id,question_type as type,title,create_time as update_time,evaluation_status as ev,evaluation_desc as ev_desc,status,is_read from {$this->_modelWorkOrder->tName()} where Id={$workOrderId} and {$workOrder_condition}";
		$workOrderData = $this->_modelWorkOrder->select($sql_CheckWorkOrder,1);
		if(!$workOrderData){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'the player has not this order','data' =>null));
		}
		if($workOrderData['is_read'] == 0){
			$this->_modelWorkOrder->update(array('is_read'=>1),"Id={$workOrderId}");//更改已读状态
		}
		$this->_loadCore('Help_SqlSearch');//载入sql工具		
		$this->_modelWorkOrderQa = $this->_getGlobalData('Model_WorkOrderQa','object');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_field('Id as id,work_order_id,qa,service_id,content,create_time,image');
		$helpSqlSearch->set_tableName($this->_modelWorkOrderQa->tName());
		$helpSqlSearch->set_conditions('work_order_id='.$workOrderId);
		$helpSqlSearch->set_conditions('not_sendmsg=0');
		$helpSqlSearch->set_orderBy('create_time');
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->_modelWorkOrderQa->select($sql);
		$this->_returnAjaxJson(array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$dataList,'ext'=>$workOrderData));
	}

	public function actionWorkOrderStatus(){
		$workOrderId = intval($_REQUEST['work_order_id']);
		if(!$workOrderId){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'Work Order Id Empty','data' =>null));
		}
		$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
		$gameId = intval($_REQUEST['game_id']);
		$sql_CheckWorkOrder = "select * from {$this->_modelWorkOrder->tName()} where Id={$workOrderId} and game_type={$gameId}";
		$workOrderData = $this->_modelWorkOrder->select($sql_CheckWorkOrder,1);
		if(!$workOrderData){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'workorder non-exist','data' =>null));
		}
		//if($workOrderData['is_read'] == 0){
		//	$this->_modelWorkOrder->update(array('is_read'=>1),"Id={$workOrderId}");//更改已读状态
		//}
		$workOrderData['status'] = min(3,$workOrderData['status']);
		$this->_modelWorkOrderQa = $this->_getGlobalData('Model_WorkOrderQa','object');
		$sql = "select work_order_id,content,create_time from ".$this->_modelWorkOrderQa->tName()." where work_order_id={$workOrderId} order by create_time ";
		$dataList = $this->_modelWorkOrderQa->select($sql,1);
		$dataList['status'] = $workOrderData['status'];
		$this->_returnAjaxJson(array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$dataList));
	}
	/**
	 * 获得玩家的详细信息
	 */
	public function actionGetPlayerDataByAccount(){
		$gameId = intval($_REQUEST['game_id_from_uwan']);
		$playerAccount = urldecode(trim($_REQUEST['player_account']));
		$_REQUEST['server_id'] = trim($_REQUEST['server_id']);
		if(empty($gameId) || empty($playerAccount) || empty($_REQUEST['server_id'])){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'parameter empty','data'=>null));
		}
		$gameObject = $this->_getGlobalData($gameId,'game');
		if(!$gameObject){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game_id Error','data'=>null));
		}
		if(!is_callable(array($gameObject,'getPlayerDataByAccount')) ){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'Method Error','data'=>null));
		}
		$playerInfo = $gameObject->getPlayerDataByAccount($playerAccount,$_REQUEST['server_id']);
		if(!$playerInfo){
			$this->_returnAjaxJson(array('status'=>0,'info'=>'player not found','data'=>null));
		}
		$this->_returnAjaxJson(array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$playerInfo));
	}
}