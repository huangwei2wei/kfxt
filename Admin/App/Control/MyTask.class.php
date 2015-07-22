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
		$this->_url['MyTask_Index_ref']=Tools::url(CONTROL,'Index',array('doaction'=>'ref'));
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
			$this->_utilMsg->showMsg(Tools::getLang('OUTROOM_SUCCESS',__CLASS__),1,Tools::url('Group','Index'));
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('OUTROOM_ERROR',__CLASS__),-2,Tools::url(CONTROL,'Index'));
		}

	}

	public function actionIndex() {
		switch ($_GET['doaction']){
			case 'ref' :{
				$this->_ref();
				break;	
			}
			default:{
				$this->_index();
				break;
			}
		}

	}
	
	private function _index(){
		#------初始化------#
		$this->_loadCore('Help_Page');
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$verifyStatusArr = $this->_getGlobalData ( 'verify_status' );//bug 状态
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		#------初始化------#

		$userClass = $this->_utilRbac->getUserClass ();
		$roomId=$userClass['_roomId'];
		if ($roomId){
			$roomClass=$this->_utilRooms->getRoom($roomId);
			$this->_view->assign('roomClass',$roomClass);
		}

		$userGameTypes=$userClass->getUserGameTypeIds();	//获取用户能控制的游戏ids
		$userOperatorIds=$userClass->getUserOperatorIds();	//获取用户能控制的运营商ids
		if (!count($userGameTypes) && !count($userOperatorIds))
			$this->_utilMsg->showMsg(Tools::getLang('SET_OPERATOR_ERROR',__CLASS__),-1,Tools::url('Group','Room'));	//如果用户没有权限将退出

		$questionTypes=$this->_getGlobalData('question_types');
		$questionSelect=array();
		foreach ($questionTypes as $question){
			if (in_array($question['game_type_id'],$userGameTypes)){
				$questionSelect[$question['Id']]='<b>['.$gameTypeArr[$question['game_type_id']].']</b>'.$question['title'];
			}
		}
		$questionSelect['']=Tools::getLang('ALL','Common');
		$this->_view->assign('questionSelect',$questionSelect);
		$questionTypes=Model::getTtwoArrConvertOneArr($questionTypes,'Id','title');
			
		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id

		#------分页生成sql------#
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if (!$_GET['view_type'])$_GET['view_type']=1;
		$this->_view->assign('selectedViewType',$_GET['view_type']);
		if ($_GET['view_type']==1){
			$helpSqlSearch->set_conditions ( "owner_user_id={$userClass['_id']}" );
		}else {
			if (count($userGameTypes)==1){
				$gameTypeId=reset($userGameTypes);
				$helpSqlSearch->set_conditions("game_type = {$gameTypeId}");	//只能处理的所有工单
			}else {
				$helpSqlSearch->set_conditions("game_type in (".implode(',',$userGameTypes).")");	//只能处理的所有工单
			}
			
			if (count($userOperatorIds)==1){
				$operatorId=reset($userOperatorIds);
				$helpSqlSearch->set_conditions("operator_id = {$operatorId}");
			}else {
				$helpSqlSearch->set_conditions("operator_id in (".implode(',',$userOperatorIds).")");
			}
			
		}		
		if($_GET['Id']){
			$helpSqlSearch->set_conditions('Id='.intval($_GET['Id']));
			$this->_view->assign('selectedId',$_GET['Id']);
		}
		if ($_GET['question_type']){
			$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");
			$this->_view->assign('selectedQuestion',$_GET['question_type']);
		}

		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}
		
		if ($_GET['evaluation_status']!=''){
			$helpSqlSearch->set_conditions("evaluation_status={$_GET['evaluation_status']}");
			$this->_view->assign('selectedEv',$_GET['evaluation_status']);
			if ($_GET['evaluation_status']==3){//如果为差评
				$this->_view->assign('badev_display',true);
			}
		}
		
		if ($_GET['evaluation_desc']!=''){
			$helpSqlSearch->set_conditions("evaluation_desc={$_GET['evaluation_desc']}");
			$this->_view->assign('selectedBadEv',$_GET['evaluation_desc']);
		}

		if ($_GET['is_verify']!=''){
			$helpSqlSearch->set_conditions("is_verify={$_GET['is_verify']}");
			$this->_view->assign('selectedIsVerify',$_GET['is_verify']);
		}
		
		if ($_GET['order_status']){
			$helpSqlSearch->set_conditions("status={$_GET['order_status']}");
			$this->_view->assign('selectedOrderStatus',$_GET['order_status']);
			$helpSqlSearch->set_orderBy('create_time desc');
		}else {
			$helpSqlSearch->set_orderBy('status asc,create_time desc');
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


		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql = $helpSqlSearch->createSql ();
		$conditions=$helpSqlSearch->get_conditions();

		$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrder->findCount($conditions),'perpage'=>PAGE_SIZE));

		#------分页生成sql------#
		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		$users=$this->_getGlobalData('user');
		$serverList=$this->_getGlobalData('gameser_list');
		#------载入缓存------#
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			$timeInterval=array();	//定时器计时
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?Tools::getLang('VERIFY_STATUS','Common'):'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$list ['word_question_type'] = $questionTypes [$list['question_type']] ? $questionTypes [$list['question_type']] : ' ';
				$list ['url_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $list ['Id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list['word_game_server_id']=$serverList[$list['game_server_id']]['server_name'];
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
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
				if ($serverList[$list['game_server_id']]['time_zone']!=0){
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']+($serverList[$list['game_server_id']]['time_zone']*3600));
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
			$this->_view->assign ( 'dataList', $dataList);
		}
		$evArr['']=Tools::getLang('ALL','Common');
		$badEvArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('verify',$this->_modelWorkOrder->getVerify());
		$this->_view->assign('badEvArr',$badEvArr);
		$this->_view->assign('evArr',$evArr);
		$this->_view->assign('timeInterval',json_encode($timeInterval));
		$userOperator = $userClass ['_operatorIds'];
		foreach ( $userOperator as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
			$value ['word_game_type_id'] = $gameTypeArr [$value ['game_type_id']];
		}
		$this->_view->assign('lastYmd',date('Ymd',strtotime('-1 day')));
		$this->_view->assign('viewType',Tools::getLang('VIEW_TYPE',__CLASS__));
		$this->_view->assign('userClass',$userClass);
		$this->_view->assign ( 'userOperator', $userOperator );
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	private function _ref(){
		#------初始化------#
		if (!$this->_isAjax())return ;
		parse_str($_POST['url'],$_GET);
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
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		
		#------初始化------#
		$userClass = $this->_utilRbac->getUserClass ();
		$userGameTypes=$userClass->getUserGameTypeIds();	//获取用户能控制的游戏ids
		$userOperatorIds=$userClass->getUserOperatorIds();	//获取用户能控制的运营商ids
		$questionTypes=$this->_getGlobalData('question_types');
		$questionTypes=Model::getTtwoArrConvertOneArr($questionTypes,'Id','title');
		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id

		#------分页生成sql------#
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if (!$_GET['view_type'])$_GET['view_type']=1;
		if ($_GET['view_type']==1){
			$helpSqlSearch->set_conditions ( "owner_user_id={$userClass['_id']}" );
		}else {
			if (count($userGameTypes)==1){
				$gameTypeId=reset($userGameTypes);
				$helpSqlSearch->set_conditions("game_type = {$gameTypeId}");	//只能处理的所有工单
			}else {
				$helpSqlSearch->set_conditions("game_type in (".implode(',',$userGameTypes).")");	//只能处理的所有工单
			}
			
			if (count($userOperatorIds)==1){
				$operatorId=reset($userOperatorIds);
				$helpSqlSearch->set_conditions("operator_id = {$operatorId}");
			}else {
				$helpSqlSearch->set_conditions("operator_id in (".implode(',',$userOperatorIds).")");
			}
		}

		if ($_GET['question_type'])$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");

		if ($_GET['vip_level']!='')$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
		
		if ($_GET['evaluation_status']!=''){
			$helpSqlSearch->set_conditions("evaluation_status={$_GET['evaluation_status']}");
			if ($_GET['evaluation_status']==3){//如果为差评
				$this->_view->assign('badev_display',true);
			}
		}
		
		if ($_GET['evaluation_desc']!='')$helpSqlSearch->set_conditions("evaluation_desc={$_GET['evaluation_desc']}");

		if ($_GET['order_status']){
			$helpSqlSearch->set_conditions("status={$_GET['order_status']}");
			$helpSqlSearch->set_orderBy('`create_time` desc');
		}else {
			$helpSqlSearch->set_orderBy('`status` asc,`create_time` desc');
		}
		if ($_GET['user_nickname'])$helpSqlSearch->set_conditions("user_nickname='{$_GET['user_nickname']}'");
		if ($_GET['user_account'])$helpSqlSearch->set_conditions("user_account='{$_GET['user_account']}'");
		if ($_GET['title'])$helpSqlSearch->set_conditions("title like '%{$_GET['title']}%'");
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelWorkOrder->select ( $sql );
		#------分页生成sql------#
		#------载入缓存------#
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		$users=$this->_getGlobalData('user');
		$serverList=$this->_getGlobalData('gameser_list');
		#------载入缓存------#
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			$timeInterval=array();	//定时器计时
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?Tools::getLang('VERIFY_STATUS','Common'):'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$list ['word_question_type'] = $questionTypes [$list['question_type']] ? $questionTypes [$list['question_type']] : ' ';
				$list ['url_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $list ['Id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list['word_game_server_id']=$serverList[$list['game_server_id']]['server_name'];
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
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
				if ($serverList[$list['game_server_id']]['time_zone']!=0){
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']+($serverList[$list['game_server_id']]['time_zone']*3600));
				}else {
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				}
				if ($list['word_owner_user_id'])
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
			}
		}
		$timeInterval=json_encode($timeInterval);
		$this->_view->assign('dataList',$dataList);
		$dataList=$this->_view->fetch('MyTask/Ref.html');
		
		$output=array('dataList'=>$dataList,'timer'=>$timeInterval);
		$this->_returnAjaxJson(array('status'=>1,'msg'=>null,'data'=>$output));
	}
	
	

	/**
	 * 工单简单对话ajax
	 */
	public function actionOrderDialog(){
		if ($this->_isAjax()){
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$dialogList=$this->_modelWorkOrderQa->findByWorkOrderId($_GET['Id']);
			if ($dialogList){
				$users=$this->_getGlobalData('user_all');
				foreach ($dialogList as &$list){
					$list['word_reply_name']=$users[$list['user_id']]['full_name'];
					
					$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
					$list['content']=Tools::convertHtml($list['content']);
				}
				$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('REQUEST_SUCCESS',__CLASS__),'data'=>$dialogList));
			}
			$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('REQUEST_ERROR',__CLASS__)));
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
		$userClass = $this->_utilRbac->getUserClass ();
		$this->_helpSqlSearch->set_tableName ( $this->_modelReplyQulity->tName () );
		$this->_helpSqlSearch->set_conditions ( "user_id='{$userClass['_id']}'" );
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
				$value ['word_quality_user_id'] = $allUser [$value ['quality_user_id']]['nick_name'];
				$value ['word_status'] = Util_FontColor::getQualityStatus ( $value ['status'], $qualityStatus [$value ['status']] );
				$value ['content'] = strip_tags ( $value ['content'] );
				$value ['word_option_id'] = $qualityOptions [$value ['option_id']];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$qualityStatus['']=Tools::getLang('ALL','Common');
		$qualityOptions['']=Tools::getLang('ALL','Common');
		$this->_view->assign ( 'qualityStatus', $qualityStatus );
		$this->_view->assign ( 'qualityOptions', $qualityOptions );
		$this->_view->assign ( 'scores', Tools::getLang('SOURCE',__CLASS__) );
		$this->_view->assign('timeout',Tools::getLang('TIMEOUT',__CLASS__));
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_view->assign('js',$this->_view->get_curJs());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}


}