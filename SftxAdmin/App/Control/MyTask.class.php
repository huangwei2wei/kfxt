<?php
class Control_MyTask extends Control {

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
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;

	/**
	 * Model_ReplyQulity(视图)
	 * @var Model_ReplyQulity
	 */
	private $_modelReplyQulity;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Help_SqlSearch
	 * @var Help_SqlSearch
	 */
	private $_helpSqlSearch;

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}

	private function _createUrl() {
		$this->_url['MyTask_OutRoom']=Tools::url('Group','Room',array('doaction'=>'outRoom'));
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 用户退出房间
	 */
	public function actionOutRoom(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		if ($userClass->outRoom()){
			$userClass->setUpdateInfo(1);
			$this->_utilMsg->showMsg('退出回复专区成功',1,Tools::url('Group','Index'));
		}else {
			$this->_utilMsg->showMsg('退出回复专区失败',-2,Tools::url(CONTROL,'Index'));
		}

	}

	public function actionIndex() {
		#------初始化------#
		$this->_loadCore('Help_Page');
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		#------初始化------#

		$userClass = $this->_utilRbac->getUserClass ();
		$roomId=$userClass['_roomId'];
		if ($roomId){
//			$this->_utilMsg->showMsg('请先加入房间',-1,Tools::url('Group','Index'));
			$roomClass=$this->_utilRooms->getRoom($roomId);
			$this->_view->assign('roomClass',$roomClass);
		}


		$userGameTypes=$userClass->getUserGameTypeIds();	//获取用户能控制的游戏ids
		$userOperatorIds=$userClass->getUserOperatorIds();	//获取用户能控制的运营商ids
		if (!count($userGameTypes) && !count($userOperatorIds))
			$this->_utilMsg->showMsg('请先设置用户运营商权限',-1,Tools::url('Group','Room'));	//如果用户没有权限将退出


		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id


		#------分页生成sql------#
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

//		$helpSqlSearch->set_conditions("room_id={$roomId}");	//只显示房间内的
	
		if ($_GET['Id']){
			$helpSqlSearch->set_conditions("Id={$_GET['Id']}");
			$this->_view->assign('selectedId',$_GET['Id']);
		}

		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}

		if ($_GET['order_status']){
			$helpSqlSearch->set_conditions("status={$_GET['order_status']}");
			$this->_view->assign('selectedOrderStatus',$_GET['order_status']);
			$helpSqlSearch->set_orderBy('create_time desc');
		}else {
			$helpSqlSearch->set_orderBy('status asc,create_time desc');
		}

		$helpSqlSearch->set_conditions ( "owner_user_id={$userClass['_id']}" );

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


		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql = $helpSqlSearch->createSql ();
		$conditions=$helpSqlSearch->get_conditions();

		$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrder->findCount($conditions),'perpage'=>PAGE_SIZE));

		#------分页生成sql------#

		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=array('0'=>'普通','1'=>'一级','2'=>'二级','3'=>'三级','4'=>'四级','5'=>'五级','6'=>'六级',''=>'所有');
		$users=$this->_getGlobalData('user');
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');
		#------载入缓存------#
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
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
			$this->_view->assign ( 'dataList', $dataList);
		}

		$userOperator = $userClass ['_operatorIds'];
		foreach ( $userOperator as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
			$value ['word_game_type_id'] = $gameTypeArr [$value ['game_type_id']];
		}
		$this->_view->assign('userClass',$userClass);
		$this->_view->assign ( 'userOperator', $userOperator );
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']='所有';
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 工单简单对话ajax
	 */
	public function actionOrderDialog(){
		if ($this->_isAjax()){
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$dialogList=$this->_modelWorkOrderQa->findByWorkOrderId($_GET['Id']);
			if ($dialogList){
				$users=$this->_getGlobalData('user');
				$users=Model::getTtwoArrConvertOneArr($users,'user_name','full_name');
				foreach ($dialogList as &$list){
					$list['word_reply_name']=$users[$list['reply_name']];
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
					$list['content']=Tools::convertHtml($list['content']);
				}
				$this->_returnAjaxJson(array('status'=>1,'msg'=>'请求成功','data'=>$dialogList));
			}
			$this->_returnAjaxJson(array('status'=>0,'msg'=>'请求错误'));
		}
	}

	/**
	 * 我被质检过的回复
	 */
	public function actionMyReplyQulity() {
		#------初始化------#
		$this->_loadCore ( 'Help_Page' );
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_helpSqlSearch = new Help_SqlSearch ();
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelReplyQulity = $this->_getGlobalData ( 'Model_ReplyQulity', 'object' );
		$qualityStatus = $this->_getGlobalData ( 'quality_status' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' );
		#------初始化------#


		$allUser = $this->_getGlobalData ( 'user' );
		$allUser = Model::getTtwoArrConvertOneArr ( $allUser, 'Id', 'nick_name' );
		$userClass = $this->_utilRbac->getUserClass ();
		$this->_helpSqlSearch->set_tableName ( $this->_modelReplyQulity->tName () );
		$this->_helpSqlSearch->set_conditions ( "reply_name='{$userClass['_userName']}'" );
		if ($_GET ['option']) {
			$this->_helpSqlSearch->set_conditions ( "option_id={$_GET['option']}" );
			$this->_view->assign ( 'selectedOption', $_GET ['option'] );
		}
		if ($_GET ['status']) {
			$this->_helpSqlSearch->set_conditions ( "status={$_GET['status']}" );
			$this->_view->assign ( 'selectedStatus', $_GET ['status'] );
		}

		if ($_GET['is_timeout']!=''){
			$this->_helpSqlSearch->set_conditions ( "is_timeout={$_GET['is_timeout']}" );
			$this->_view->assign ( 'selectedTimeout', $_GET ['is_timeout'] );
		}

		if ($_GET ['scores']) {
			$this->_view->assign ( 'selectedSource', $_GET ['scores'] );
			if ($_GET ['scores'] == 1) {
				$this->_helpSqlSearch->set_conditions ( "scores<0" );
			} else {
				$this->_helpSqlSearch->set_conditions ( "scores>=0" );
			}
		}
		$this->_helpSqlSearch->set_orderBy ( 'create_time desc' );
		$this->_helpSqlSearch->setPageLimit ( $_GET ['page'], 20 );

		$allConditions = $this->_helpSqlSearch->get_conditions (); //返回所有条件
		$helpPage = new Help_Page ( array ('total' => $this->_modelReplyQulity->findCount ( $allConditions ), 'perpage' => 20 ) );

		$sql = $this->_helpSqlSearch->createSql ();
		$dataList = $this->_modelReplyQulity->select ( $sql );
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$value ) {
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value ['url_detail'] = Tools::url ( 'QualityCheck', 'QualityDetail', array ('work_order_id' => $value ['work_order_id'], 'qa_id' => $value ['qa_id'] ) );
				$value ['word_quality_user_id'] = $allUser [$value ['quality_user_id']];
				$value ['word_status'] = Util_FontColor::getQualityStatus ( $value ['status'], $qualityStatus [$value ['status']] );
				$value ['content'] = strip_tags ( $value ['content'] );
				$value ['word_option_id'] = $qualityOptions [$value ['option_id']];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$qualityStatus['']='所有';
		$qualityOptions['']='所有';
		$this->_view->assign ( 'qualityStatus', $qualityStatus );
		$this->_view->assign ( 'qualityOptions', $qualityOptions );
		$this->_view->assign ( 'scores', array (1 => '扣分', 2 => '未扣分',''=>'所有' ) );
		$this->_view->assign('timeout',array('1'=>'超时','0'=>'未超时',''=>'所有'));
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}


}