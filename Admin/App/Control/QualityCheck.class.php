<?php
/**
 * 质检模块
 * @author php-朱磊
 */
class Control_QualityCheck extends Control {
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;

	/**
	 * Model_Org
	 * @var Model_Org
	 */
	private $_modelOrg;

	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;

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
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Model_Quality
	 * @var Model_Quality
	 */
	private $_modelQuality;

	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

	/**
	 * Model_OperatorList
	 * @var Model_OperatorList
	 */
	private $_modelOperatorList;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * 质检状态
	 * @var array
	 */
	private $_statusOptions;

	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;
	
	/**
	 * Model_QualityDocument
	 * @var Model_QualityDocument
	 */
	private $_modelQualityDocument;
	
	/**
	 * Model_OrderLog
	 * @var Model_OrderLog
	 */
	private $_modelOrderLog;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_statusOptions = $this->_getGlobalData ( 'quality_status' );
	}

	private function _createUrl() {
		$this->_url ['QualityCheck_Dialog'] = Tools::url ( CONTROL, 'Dialog' );
		$this->_url ['QualityCheck_Index'] = Tools::url ( CONTROL, 'Index' );
		$this->_url ['QualityCheck_ShowQuality'] = Tools::url ( CONTROL, 'ShowQuality' );
		$this->_url ['QualityCheck_MyQualityTask'] = Tools::url ( CONTROL, 'MyQualityTask' );
		$this->_url ['QualityCheck_QualityDetail'] = Tools::url ( CONTROL, 'QualityDetail' );
		$this->_url	['QualityCheck_Again']=Tools::url(CONTROL,'Again');
		$this->_url ['QualityCheck_Quality']=Tools::url(CONTROL,'Quality');
		$this->_url ['updateQualityImgUrl']=Tools::url('Default','ImgUpload',array('type'=>'Quality'));
		$this->_url['QualityCheck_Document_add']=Tools::url(CONTROL,'Document',array('doaction'=>'add'));
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 项目质检管理页面
	 */
	public function actionIndex() {
		#------初始化------#
		//print_r($_POST);
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$users = $this->_modelUser->findSetOrgByUser ();
		$gamelist	=	$this->_getGlobalData("game_type");
		$operators = $this->_utilRbac->getOperatorActList();	//个人授权可操作的运营商
		if($_POST["gamelist"]){
			foreach($gamelist as &$gamemsg){
				if(in_array($gamemsg["Id"], $_POST["gamelist"])){
					$gamemsg["tab"]	=	1;
				}
			}
		}
		$this->_view->assign ('gamelist',$gamelist);
		$this->_view->assign ('operators',$operators);
		$this->_view->assign ('users',json_encode($users));
		$orgs = $this->_getGlobalData ( 'org' );
		$orgs = Model::getTtwoArrConvertOneArr ( $orgs, 'Id', 'name' );
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$this->_loadCore('Help_Page');
		$userClass=$this->_utilRbac->getUserClass();//获取用户class
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		#------初始化------#


		#------跟据搜索条件获取工单给用户------#
		if ($this->_isPost ()) {	//增加工单
			if (!abs(intval($_POST ['num'])))$this->_utilMsg->showMsg(Tools::getLang('INDEX_ERROR1',__CLASS__),-1);
			if (!count($_POST ['users']))$this->_utilMsg->showMsg(Tools::getLang('INDEX_ERROR2',__CLASS__),-1);
			if (!count($_POST ['operator_id']))$this->_utilMsg->showMsg('请选择运营商',-1);
			$selectedUsers=$this->_modelUser->findByUsersToCache($_POST['users']);
			$selectedUsers=Model::getTtwoArrConvertOneArr($selectedUsers,'Id','full_name');
			$this->_view->assign('selectedUsersOption',$_POST['users']);
			$this->_view->assign('selectedUsers',$selectedUsers);
			$users = implode ( ',', $_POST ['users'] );
			

			$startDate = strtotime ( $_POST ['start_date'] );
			$endDate = strtotime ( $_POST ['end_date'] );
			$redatalist	=	array();
			foreach($_POST ['users'] as $_user){
				$helpSqlSearch->clearAll();
				$helpSqlSearch->set_field('Id');
				$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );
				if ($_POST ['start_date'] && $_POST ['end_date']){
					$helpSqlSearch->set_conditions ( "create_time between {$startDate} and {$endDate}" );
					$this->_view->assign('selectedTime',array('start'=>$_POST['start_date'],'end'=>$_POST['end_date']));
				}
				if($_POST["gamelist"]){
					$games	=	implode ( ',', $_POST ['gamelist'] );
					$helpSqlSearch->set_conditions ( "game_type in ({$games})" );
				}
				if ($_POST ['users']){
					$helpSqlSearch->set_conditions ( "owner_user_id = {$_user}" );
					$this->_view->assign('selectedOrg',$_POST['org']);
				}
				if($_POST['operator_id']){
					$helpSqlSearch->set_conditions ( "operator_id in (".implode(',', $_POST['operator_id']).")");
				}
				if ($_POST ['num']){
					$helpSqlSearch->setPageLimit ( 1, abs ( intval ( $_POST ['num'] ) ) );
					$this->_view->assign('selectedNum',$_POST['num']);
				}
				$helpSqlSearch->set_conditions ( 'quality_id=0' );			//未被质检过
				$helpSqlSearch->set_conditions('owner_user_id is not null');	//已经被回复或处理过
				$helpSqlSearch->set_conditions('(status=3 or status=4)'); //已经处理完成的
				$helpSqlSearch->set_conditions('answer_num!=0');	//必须要有一次回复
				$helpSqlSearch->set_orderBy ( "rand()" );
				$sql = $helpSqlSearch->createSql ();
				$dataList = $this->_modelWorkOrder->select ( $sql );
				$redatalist	=	array_merge($redatalist,$dataList);
			}
			
			
			if ($redatalist){
				$ids=array();
				foreach ($redatalist as $value){
					array_push($ids,$value['Id']);
				}
				$this->_modelWorkOrder->addOrderToQualityUser($ids,$userClass['_id']);	//增加工单到当前用户
			}
			if (is_array($ids)){
				$addNum=count($ids);
				$addIds=implode(',',$ids);
			}else {
				$addNum=0;
				$addIds='';
			}
			$this->_view->assign('addOrderDeatil',array('num'=>$addNum,'addIds'=>$addIds));
		}
		#------跟据搜索条件获取工单给用户------#

		#------搜索用户所获取的质检工单------#
		$helpSqlSearch->clearAll();
		$helpSqlSearch->set_tableName($this->_modelWorkOrder->tName());
		$helpSqlSearch->set_field('*');
		$helpSqlSearch->set_conditions("quality_id=-{$userClass['_id']}");
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);

		#------分页box------#
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrder->findCount($conditions),'perpage'=>PAGE_SIZE));
		#------分页box------#

		$sql=$helpSqlSearch->createSql();
	
		$dataList=$this->_modelWorkOrder->select($sql);
		#------搜索用户所获取的质检工单------#

		#------载入缓存------#
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$userList = Model::getTtwoArrConvertOneArr ( $this->_getGlobalData ( 'user' ), 'Id', 'nick_name' );
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');
		#------载入缓存------#

		if ($dataList){
			Tools::import('Util_FontColor');
			$users=$this->_getGlobalData('user');
			$users=Model::getTtwoArrConvertOneArr($users,'Id','full_name');
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?'(查)':'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$questionArr = $this->_modelQuestionType->findById ( $list ['question_type'] );
				$list ['word_question_type'] = $questionArr ['title'] ? $questionArr ['title'] : ' ';
				$list ['url_detail'] = Tools::url ( CONTROL, 'Dialog', array ('Id' => $list ['Id'],'game_type_id'=>$list['game_type'],'operator_id'=>$list['operator_id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']];
				$list['word_game_server_id']=$serverList[$list['game_server_id']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
				if ($list['evaluation_status']==3)$list['word_ev_desc']=$badEvArr[$list['evaluation_desc']];
				if ($list['word_owner_user_id'])
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign ( 'orgs', $orgs );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 质检人员察看工单客服与员工对话
	 */
	public function actionOrderDialog(){
		if ($this->_isAjax()){
			if (!$_GET['Id'])$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('REQUEST_ERROR',__CLASS__)));
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
			$dialogList=$this->_modelWorkOrderQa->findByWorkOrderId($_GET['Id']);
			$TimeDifference = intval($_GET['TimeDifference'])*3600; //时间差
			if ($dialogList){
				$users=$this->_getGlobalData('user_all');
				foreach ($dialogList as &$list){
					if ($list['qa']==1)$list['word_reply_name']=$users[$list['user_id']]['full_name'];
					if($TimeDifference){
						$list['create_time']=date('Y-m-d H:i:s',$list['create_time'] + $TimeDifference);
						$list['create_time'] .= '('.Tools::getLang('GAME_SERVER_TIME','Common').')';
					}else{
						$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
					}					
					$list['content']=Tools::convertHtml($list['content']);
					$list['image'] = json_decode($list['image'],true);
					if($list['image'] && is_array($list['image'])){
						foreach ($list['image'] as $img){
							$list['content'] .= "<br/><img src='{$img}'/>";
						}
					}
				}
				$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('REQUEST_SUCCESS',__CLASS__),'data'=>$dialogList));
			}
			$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('REQUEST_ERROR',__CLASS__)));
		}
	}
	
	/**
	 * 质检动作
	 */
	public function actionQuality(){
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		if ($this->_isPost()) {
			$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
			$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$userClass = $this->_utilRbac->getUserClass ();
			$addArr = array (
						'reply_user_id'=>$_POST['user_id'],
						'game_type_id'=>$_POST['game_type_id'],
						'operator_id'=>$_POST['operator_id'],
						'work_order_id' => $_POST ['work_order_id'],
						'quality_user_id' => $userClass ['_id'],
						'qa_id' => $_POST ['qa_id'],
						'option_id' => $_POST ['option_id'],
						'quality_content' => $_POST ['quality_content'],
						'quality_time' => CURRENT_TIME,
						'scores' => $_POST ['scores'] );
			if ($_POST['option_id']>0 && $_POST['scores']>=0)$addArr['status']=5;		//如果质检都是好的话并且所扣分数是大于零,将会变成同意质检,不需要申诉
			if ($this->_modelQuality->add ( $addArr )) {
				$this->_modelWorkOrderQa->update ( array ('is_quality' => $userClass ['_id'] ), "Id={$_POST['qa_id']}" ); //更新回复,表示已质检
				#------添加日志------#
				$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
				$this->_modelOrderLog->addLog(array('Id'=>$_POST['work_order_id']),Model_OrderLog::QUALITY);
				#------添加日志------#
				$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
				$this->_modelWorkOrder->update ( array ('quality_id' => $userClass ['_id'] ), "Id={$_POST['work_order_id']}" );
				$this->_utilMsg->showMsg(false);
			} else {
				$this->_utilMsg->showMsg(Tools::getLang('QUALITY_ERROR',__CLASS__),-2);
			}
		}
	}

	/**
	 * 显示某一个工单的对话
	 */
	public function actionDialog() {
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		/*if ($this->_isPost()) {
			$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
			$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$userClass = $this->_utilRbac->getUserClass ();
			$addArr = array (
						'reply_user_id'=>$_POST['user_id'],
						'game_type_id'=>$_POST['game_type_id'],
						'operator_id'=>$_POST['operator_id'],
						'work_order_id' => $_POST ['work_order_id'],
						'quality_user_id' => $userClass ['_id'],
						'qa_id' => $_POST ['qa_id'],
						'option_id' => $_POST ['option_id'],
						'quality_content' => $_POST ['quality_content'],
						'quality_time' => CURRENT_TIME,
						'scores' => $_POST ['scores'] );
			if ($_POST['option_id']>0 && $_POST['scores']>=0)$addArr['status']=5;		//如果质检都是好的话并且所扣分数是大于零,将会变成同意质检,不需要申诉
			if ($this->_modelQuality->add ( $addArr )) {
				$this->_modelWorkOrderQa->update ( array ('is_quality' => $userClass ['_id'] ), "Id={$_POST['qa_id']}" ); //更新回复,表示已质检
				$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
				$this->_modelWorkOrder->update ( array ('quality_id' => $userClass ['_id'] ), "Id={$_POST['work_order_id']}" );
				$this->_utilMsg->showMsg(false);
			} else {
				$this->_utilMsg->showMsg(Tools::getLang('QUALITY_ERROR',__CLASS__),-2);
			}
		} else {*/
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$data=$this->_modelWorkOrder->findByIdToDetail($_GET['Id']);

			#------载入缓存------#
			$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_getGlobalData('game_type'), 'Id', 'name' ); //游戏类型
			$workOrderSourceArr = $this->_getGlobalData('workorder_source'); //工单来源
			$workOrderStatusArr = $this->_getGlobalData('workorder_status'); //工单状态
			$gameServerList = $this->_getGlobalData ( 'gameser_list' );
			$gameServerList = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
			$operatorList = $this->_getGlobalData ( 'operator_list' );
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$user = $this->_getGlobalData ( 'user' );
			$departmentList = $this->_getGlobalData ( 'department' );
			$departmentList = Model::getTtwoArrConvertOneArr ( $departmentList, 'Id', 'name' );
			#------载入缓存------#

			#------跟椐ID转换文字显示------#
			$data ['word_game_type'] = $gameTypeArr [$data ['game_type']];
			$data ['word_source'] = $workOrderSourceArr [$data ['source']];
			$data ['word_game_server_id'] = $gameServerList [$data ['game_server_id']];
			$data ['word_operator_id'] = $operatorList [$data ['operator_id']];
			$data ['create_time'] = date ( 'Y-m-d H:i', $data ['create_time'] );
			$data ['word_quality_id'] = ($data ['quality_id'] < 0) ? $user [$data ['quality_id']] ['nick_name'] . "[{$departmentList[$user[$data['quality_id']]['department_id']]}]" : '未质检';
			$data['word_quality_id']=$user[$data['quality_id']]['full_name'];
			#------跟椐ID转换文字显示------#

			$workOrderDetailArr = unserialize ( $data ['content'] ); //获取工单的详细信息
			$userData = $workOrderDetailArr ['user_data']; //获取提交工单用户的详细信息
			$userData ['register_date'] =$userData ['register_date']? date ( 'Y-m-d H:i:s', $userData ['register_date'] ):'';

			if ($data ['evaluation_status'] != 0) { //如果已经评价
				$playerEvaluation = $this->_getGlobalData ( 'player_evaluation' );
				$evaluation = $playerEvaluation [$data ['evaluation_status']];
				$evaluation = $evaluation ['title'];
				if ($data ['evaluation_status'] == 3)
					$evaluation .= '：&nbsp;' . $workOrderDetailArr ['other'] ['ev'];
				$this->_view->assign ( 'evaluation', $evaluation );
			}
			
			$userQuestionDetail = $workOrderDetailArr ['form_detail']; //获取提问类型工单的值
			$this->_modelQuestionType=$this->_getGlobalData('Model_QuestionType','object');
			$questionDetail = $this->_modelQuestionType->findById ( $data ['question_type'] ); //查找问题类型
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
			$badEvArr=$this->_getGlobalData('player_evaluation');
			$badEvArr=$badEvArr[3]['Description'];	//差评数组
			if ($data['evaluation_status']==3)$data['word_ev_desc']=$badEvArr[$data['evaluation_desc']];
			$dataList = $this->_modelWorkOrderQa->findByWorkOrderId ( $_GET ['Id'] );
			foreach ( $dataList as &$value ) {
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value ['word_reply_name']=$user[$value['user_id']]['full_name'];
				$value["image"]	=	json_decode($value["image"],true);
			}
			#------日志------#
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			$this->_view->assign('log',$this->_modelOrderLog->getLog($_GET['Id']));
			#------日志------#
			$this->_view->assign ( 'userData', $userData );
			$this->_view->assign ( 'data', $data ); //表单详细信息
			$qualityList = $this->_getGlobalData ( 'quality_options' );
			$this->_view->assign ( 'workOrderId', $_GET ['Id'] );
			$this->_view->assign ( 'qualityOptions', $qualityList );
			$this->_view->assign ( 'dataList', $dataList );
			$this->_view->assign('gameTypeId',$_GET['game_type_id']);
			$this->_view->assign('operatorId',$_GET['operator_id']);
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
//		}
	}

	/**
	 * ajax察看一个回复的质检
	 * @return json
	 */
	public function actionShowQuality() {
		if ($this->_isAjax()) {
			$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
			$qualityOptions = $this->_getGlobalData ( 'quality_options' ); //评价
			$users = $this->_getGlobalData ( 'user' );
			$qualityStatus=$this->_getGlobalData('quality_status');
			$qualityList = $this->_modelQuality->findByQaId ( $_GET ['qa_id'] );
			if (!$qualityList)$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('NOT_QUALITY_FIND',__CLASS__)));
			$qualityList ['word_status']=$qualityStatus[$qualityList['status']];
			$qualityList ['word_quality_user_id'] = $users [$qualityList ['quality_user_id']]['full_name'];
			$qualityList ['complain_content'] = $qualityList ['complain_content'] ? $qualityList ['complain_content'] : '';
			$qualityList ['reply_content'] = $qualityList ['reply_content'] ? $qualityList ['reply_content'] : '';
			$qualityList ['quality_content'] = $qualityList ['quality_content'] ? $qualityList ['quality_content'] : '';
			$qualityList ['again_content'] = $qualityList ['again_content'] ? $qualityList ['again_content'] : '';
			$qualityList ['word_option_id'] = $qualityOptions [$qualityList ['option_id']];
			$qualityList ['word_again_user_id']=$qualityList['again_user_id']?$users[$qualityList['again_user_id']]['full_name']:'';
			$qualityList ['reply_time']=$qualityList ['reply_time']?date('Y-m-d H:i:s',$qualityList ['reply_time']):'';
			$qualityList ['quality_time']=$qualityList ['quality_time']?date('Y-m-d H:i:s',$qualityList ['quality_time']):'';
			$qualityList ['complain_time']=$qualityList ['complain_time']?date('Y-m-d H:i:s',$qualityList ['complain_time']):'';
			$qualityList ['again_time']=$qualityList ['again_time']?date('Y-m-d H:i:s',$qualityList ['again_time']):'';
			$qualityList ['url_doc']=Tools::url(CONTROL,'Document',array('doaction'=>'add','work_order_id'=>$qualityList['work_order_id'],'qa_id'=>$qualityList['qa_id'],'Id'=>$qualityList['Id']));
			$this->_returnAjaxJson(array ('status' => 1, 'data' => $qualityList ));
		}
	}

	/**
	 * 所有被质检的工单
	 */
	public function actionAll(){
		$users=$this->_getGlobalData('user');
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$orgList=$this->_getGlobalData('org');
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','full_name');
				$value['user']=$curOrgUser;
			}
		}
		$selected=array();

		$this->_loadCore('Help_Page');
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );

		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
			
			#------提问类型------#
			$questionTypes=$this->_getGlobalData('question_types');
			$qTypes=array();
			foreach ($questionTypes as $key=>$list){
				if ($list['game_type_id']==$_GET['game_type_id'])$qTypes[$key]=$list['title'];
			}
			unset($questionTypes);
			$qTypes['']='所有';
			$this->_view->assign('qType',$qTypes);
			if ($_GET['question_type']){
				$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");
				$this->_view->assign('selectedQtype',$_GET['question_type']);
			}
			#------提问类型------#
		}
		
		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);	
		}
		
		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}
		
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
		
		if ($_REQUEST['quality_user_id']){
			$helpSqlSearch->set_conditions("quality_id={$_REQUEST['quality_user_id']}");
			$selected['quality_user_id']=$_REQUEST['quality_user_id'];
		}
		
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("create_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		
		if ($_GET['service_ids']){//如果选择了客服
			$this->_view->assign('selectedServiceIds',$_GET['service_ids']);
			$serviceIds=implode(',',$_GET['service_ids']);
			$helpSqlSearch->set_conditions("owner_user_id in ({$serviceIds})");
		}

		$helpSqlSearch->set_conditions ( "quality_id>0" );
		
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		
		$helpSqlSearch->set_orderBy ( "create_time desc" );

		#------分页box------#
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrder->findCount($conditions),'perpage'=>PAGE_SIZE));
		#------分页box------#

		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelWorkOrder->select ( $sql );

		#------载入缓存------#
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		#------载入缓存------#

		if ($dataList){
			Tools::import('Util_FontColor');
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?'(查)':'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$questionArr = $this->_modelQuestionType->findById ( $list ['question_type'] );
				$list ['word_question_type'] = $questionArr ['title'] ? $questionArr ['title'] : ' ';
				$list ['url_detail'] = Tools::url ( CONTROL, 'Dialog', array ('Id' => $list ['Id'],'game_type_id'=>$list['game_type'],'operator_id'=>$list['operator_id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']]['nick_name'];
				$list['word_quality_id']=$users[$list['quality_id']]['nick_name'];
				$list['word_game_server_id']=$serverList[$list['game_server_id']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
				if ($list['word_owner_user_id'])
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		
		$this->_view->assign('selected',$selected);
		$this->_view->assign('users',$users);
		$gameTypeArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypeList',$gameTypeArr);
		$operatorList['']=Tools::getLang('ALL','Common');
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 我质检过的工单
	 */
	public function actionMyTask() {
		#------初始化------#
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$onlineUsers=$this->_utilOnline->getOnlineUser('user_id');	//在线用户,一维数组,value值为user_id
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$serverList=$this->_getGlobalData('gameser_list');
		$serverList=Model::getTtwoArrConvertOneArr($serverList,'Id','server_name');
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_loadCore('Help_Page');
		$this->_loadCore ( 'Help_SqlSearch' );
		$orgList=$this->_getGlobalData('org');
		$evArr=$this->_modelWorkOrder->getEvArr();	//获取评价数组
		$badEvArr=$this->_getGlobalData('player_evaluation');
		$badEvArr=$badEvArr[3]['Description'];	//差评数组
		foreach ($orgList as &$value){
			$curOrgUser=$this->_modelUser->findByOrgId($value['Id']);
			if ($curOrgUser){
				$curOrgUser=Model::getTtwoArrConvertOneArr($curOrgUser,'Id','full_name');
				$value['user']=$curOrgUser;
			}
		}
		#------初始化------#
		
		
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrder->tName () );
		
		if ($_GET['game_type_id']!=''){
			$helpSqlSearch->set_conditions("game_type={$_GET['game_type_id']}");
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
			
			
			#------提问类型------#
			$questionTypes=$this->_getGlobalData('question_types');
			$qTypes=array();
			foreach ($questionTypes as $key=>$list){
				if ($list['game_type_id']==$_GET['game_type_id'])$qTypes[$key]=$list['title'];
			}
			unset($questionTypes);
			$qTypes['']=Tools::getLang('ALL','Common');
			$this->_view->assign('qType',$qTypes);
			if ($_GET['question_type']){
				$helpSqlSearch->set_conditions("question_type={$_GET['question_type']}");
				$this->_view->assign('selectedQtype',$_GET['question_type']);
			}
			#------提问类型------#
		}
		
		if ($_GET['operator_id']!=''){
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);	
		}
		
		if ($_GET['vip_level']!=''){	//如果设置了vip等级,将显示等级
			$helpSqlSearch->set_conditions("vip_level={$_GET['vip_level']}");
			$this->_view->assign('selectedVipLevel',$_GET['vip_level']);
		}
		
		
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
		$helpSqlSearch->set_conditions ( "quality_id={$userClass['_id']}" );
		
		$helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		
		$helpSqlSearch->set_orderBy('create_time desc');

		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);


		#------分页box------#
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelWorkOrder->findCount($conditions),'perpage'=>PAGE_SIZE));
		#------分页box------#

		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelWorkOrder->select ( $sql );

		#------载入缓存------#
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
		$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		$vipLevel=Tools::getLang('VIP_LEVEL','Common');
		$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' );
		$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' );
		$userList = Model::getTtwoArrConvertOneArr ( $this->_getGlobalData ( 'user' ), 'Id', 'nick_name' );
		#------载入缓存------#

		if ($dataList){
			Tools::import('Util_FontColor');
			$users=$this->_getGlobalData('user');
			$users=Model::getTtwoArrConvertOneArr($users,'Id','full_name');
			foreach ( $dataList as &$list ) {
				$list ['url_dialog'] =Tools::url(CONTROL,'OrderDialog',array('Id'=>$list['Id']));
				$list ['word_game_type'] = Util_FontColor::getGameTypeColor ( $list ['game_type'], $gameTypeArr [$list ['game_type']] );
				$list ['word_source'] = Util_FontColor::getWorkOrderSource ( $list ['source'], $workOrderSourceArr [$list ['source']] );
				$list ['word_status'] = Util_FontColor::getWorkOrderStatus ( $list ['status'], $workOrderStatusArr [$list ['status']] );
				$list ['word_status'].=$list['is_verify']?'(查)':'';
				$list ['word_operator_id'] = $operatorList [$list ['operator_id']];
				$questionArr = $this->_modelQuestionType->findById ( $list ['question_type'] );
				$list ['word_question_type'] = $questionArr ['title'] ? $questionArr ['title'] : ' ';
				$list ['url_detail'] = Tools::url ( CONTROL, 'Dialog', array ('Id' => $list ['Id'],'game_type_id'=>$list['game_type'],'operator_id'=>$list['operator_id'] ) );
				$list['word_owner_user_id']=$users[$list['owner_user_id']];
				$list['word_game_server_id']=$serverList[$list['game_server_id']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list ['word_ev']=Util_FontColor::getPlayerEvaluation($list['evaluation_status'],$evArr[$list['evaluation_status']]);	//提问类型
				if ($list['evaluation_status']==3)$list['word_ev_desc']=$badEvArr[$list['evaluation_desc']];
				if ($list['word_owner_user_id'])
					$list['word_owner_user_id'].=(in_array($list['owner_user_id'],$onlineUsers))?Util_FontColor::getOnline(1):Util_FontColor::getOnline(0);
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$gameTypeArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypeList',$gameTypeArr);
		$operatorList['']=Tools::getLang('ALL','Common');
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('vipLevel',$vipLevel);
		$workOrderStatusArr['']=Tools::getLang('ALL','Common');
		$this->_view->assign('workOrderStatusArr',$workOrderStatusArr);
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 申诉处理列表
	 */
	public function actionMyQualityTask() {
		$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' ); //评价
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$this->_loadCore('Help_Page');
		$users=$this->_getGlobalData('user_index_id');
		#------生成sql------#
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelQuality->tName () );
		$helpSqlSearch->set_conditions ( "quality_user_id={$userClass['_id']}" );
		if ($_REQUEST ['quality_option']) {
			$helpSqlSearch->set_conditions ( "option_id={$_REQUEST['quality_option']}" );
			$this->_view->assign ( 'selectedQualityOption', $_REQUEST ['quality_option'] );
		}
		if ($_REQUEST ['status_option']) {
			$helpSqlSearch->set_conditions ( "status={$_REQUEST['status_option']}" );
			$this->_view->assign ( 'selectedStatusOption', $_REQUEST ['status_option'] );
		}
		$conditions=$helpSqlSearch->get_conditions();
		$helpSqlSearch->set_orderBy ( "Id desc" );
		$helpPage=new Help_Page(array('total'=>$this->_modelQuality->findCount($conditions),'perpage'=>PAGE_SIZE));
		$sql = $helpSqlSearch->createSql ();
		#------生成sql------#


		$dataList = $this->_modelQuality->select ( $sql );
		if ($dataList) {
			Tools::import('Util_FontColor');
			foreach ( $dataList as &$value ) {
				$value ['quality_time'] = date ( 'Y-m-d H:i:s', $value ['quality_time'] );
				$value ['complain_time'] = $value ['complain_time'] ? date ( 'Y-m-d H:i:s', $value ['complain_time'] ) : '';
				$value ['word_option_id'] = $qualityOptions [$value ['option_id']];
				$value ['word_status'] = Util_FontColor::getQualityStatus($value['status'],$this->_statusOptions [$value ['status']]);
				$value ['url_detail'] = Tools::url ( CONTROL, 'QualityDetail', array ('work_order_id' => $value ['work_order_id'], 'qa_id' => $value ['qa_id'] ) );
				$value ['reply_time'] = $value ['reply_time'] ? date ( 'Y-m-d H:i:s', $value ['reply_time'] ) : '';
				$value ['word_reply_user_id']=$users[$value['reply_user_id']];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}

		$this->_statusOptions['']=Tools::getLang('ALL','Common');
		$qualityOptions['']=Tools::getLang('ALL','Common');
		$this->_view->assign ( 'qualityOptions', $qualityOptions );
		$this->_view->assign ( 'statusOptions', $this->_statusOptions );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 所有被质检的回复
	 */
	public function actionAllReply(){
		$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' ); //评价
		$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
		$users=$this->_getGlobalData('user_index_id');
		$this->_loadCore('Help_Page');
		#------生成sql------#
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ( $this->_modelQuality->tName () );
		$selected=array();
		if ($_REQUEST ['quality_option']) {
			$helpSqlSearch->set_conditions ( "option_id={$_REQUEST['quality_option']}" );
			$this->_view->assign ( 'selectedQualityOption', $_REQUEST ['quality_option'] );
		}
		if ($_REQUEST ['status_option']) {
			$helpSqlSearch->set_conditions ( "status={$_REQUEST['status_option']}" );
			$this->_view->assign ( 'selectedStatusOption', $_REQUEST ['status_option'] );
		}
		if ($_REQUEST['complain_user_id']){
			$helpSqlSearch->set_conditions("reply_user_id={$_REQUEST['complain_user_id']}");
			$selected['complain_user_id']=$_REQUEST['complain_user_id'];
		}
		if ($_REQUEST['quality_user_id']){
			$helpSqlSearch->set_conditions("quality_user_id={$_REQUEST['quality_user_id']}");
			$selected['quality_user_id']=$_REQUEST['quality_user_id'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("quality_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$conditions=$helpSqlSearch->get_conditions();
		$helpSqlSearch->set_orderBy ( "Id desc" );
		$helpSqlSearch->setPageLimit($_GET['page']);
		$helpPage=new Help_Page(array('total'=>$this->_modelQuality->findCount($conditions),'perpage'=>PAGE_SIZE));
		$sql = $helpSqlSearch->createSql ();
		#------生成sql------#


		$dataList = $this->_modelQuality->select ( $sql );
		if ($dataList) {
			
			Tools::import('Util_FontColor');
			foreach ( $dataList as &$value ) {
				$value ['quality_time'] = date ( 'Y-m-d H:i:s', $value ['quality_time'] );
				$value ['complain_time'] = $value ['complain_time'] ? date ( 'Y-m-d H:i:s', $value ['complain_time'] ) : '';
				$value ['word_option_id'] = $qualityOptions [$value ['option_id']];
				$value ['word_status'] = Util_FontColor::getQualityStatus($value['status'],$this->_statusOptions [$value ['status']]);
				$value ['url_detail'] = Tools::url ( CONTROL, 'QualityDetail', array ('work_order_id' => $value ['work_order_id'], 'qa_id' => $value ['qa_id'] ) );
				$value ['reply_time'] = $value ['reply_time'] ? date ( 'Y-m-d H:i:s', $value ['reply_time'] ) : '';
				$value ['word_quality_user_id']=$users[$value['quality_user_id']];
				$value ['word_reply_user_id']=$users[$value['reply_user_id']];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		
		$this->_view->assign('selected',$selected);
		$this->_view->assign('user',$users);
		$this->_statusOptions['']=Tools::getLang('ALL','Common');
		$qualityOptions['']=Tools::getLang('ALL','Common');
		$this->_view->assign ( 'qualityOptions', $qualityOptions );
		$this->_view->assign ( 'statusOptions', $this->_statusOptions );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 察看详细
	 */
	public function actionQualityDetail() {
		#------初始化------#
		$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
		#------初始化------#
		if ($this->_isPost ()) {
			switch ($_POST ['submit_type']) {
				case '0' :
					{ //质检
						$this->_utilMsg->showMsg ( false );
						break;
					}
				case '1' :
					{ //申诉
						if ($this->_modelQuality->update ( array (
																'status' => $_POST['status'], 
																'complain_content' => $_POST ['complain_content'], 
																'complain_time' => CURRENT_TIME ), "Id={$_POST['Id']}" )) {
							#------添加日志------#
							$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
							$this->_modelOrderLog->addLog(array('Id'=>$_POST['work_order_id']),Model_OrderLog::AGAIN);
							#------添加日志------#
							$this->_utilMsg->showMsg ( false );
						} else {
							$this->_utilMsg->showMsg ( Tools::getLang('APPEAL_ERROR',__CLASS__), - 2 );
						}
						break;
					}
				default :
					{ //默认回复申诉
						$updateArr=array ('status' => $_POST ['status'], 'reply_content' => $_POST ['reply_content'], 'reply_time' => CURRENT_TIME );
						if ($_POST['status']==3)$updateArr['scores']='0';	//如果申诉为同意申诉,将把扣分设置为0,不扣分;
						if ($this->_modelQuality->update ( $updateArr, "Id={$_POST['Id']}" )) {
							$this->_utilMsg->showMsg ( false );
						} else {
							$this->_utilMsg->showMsg ( Tools::getLang('REPLY_APPEAL_ERROR',__CLASS__), - 2 );
						}
						break;
					}
			}
		} else {
			#------初始化------#
			$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
			$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
			$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
			$this->_modelOperatorList = $this->_getGlobalData ( 'Model_OperatorList', 'object' );
			$this->_modelQuestionType = $this->_getGlobalData ( 'Model_QuestionType', 'object' );
			$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$user=$this->_getGlobalData('user');
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			#------初始化------#

			#------质检详细------#
			$qualityOptions = $this->_getGlobalData ( 'quality_options' );
			$qualityList = $this->_modelQuality->findByQaId ( $_GET ['qa_id'] );
			$qualityList ['word_option_id'] = $qualityOptions [$qualityList ['option_id']];
			$qualityList ['word_status'] = $this->_statusOptions [$qualityList ['status']];
			$qualityList ['word_quality_user_id']=$user[$qualityList['quality_user_id']]['full_name'];
			$qualityList ['word_again_user_id']=$user[$qualityList['again_user_id']]['full_name'];
			$qualityList ['reply_time']=$qualityList ['reply_time']?date('Y-m-d H:i:s',$qualityList ['reply_time']):'';
			$qualityList ['quality_time']=$qualityList ['quality_time']?date('Y-m-d H:i:s',$qualityList ['quality_time']):'';
			$qualityList ['complain_time']=$qualityList ['complain_time']?date('Y-m-d H:i:s',$qualityList ['complain_time']):'';
			$qualityList ['again_time']=$qualityList ['again_time']?date('Y-m-d H:i:s',$qualityList ['again_time']):'';
			#------质检详细------#

			$dataList = $this->_modelWorkOrder->findByIdDetail ( $_GET ['work_order_id'] ); //获取工单整个数组,包括关联表
			if (! $dataList)
				$this->_utilMsg->showMsg ( Tools::getLang('CONTENT_ERROR',__CLASS__), - 2 ); //防止错误


			$dialogArr = $dataList; //客服与用户的对话数组
			$dataList = $dataList [0]; //列表详细

			#------载入缓存------#
			$gameTypeArr = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' ); //游戏类型
			$workOrderSourceArr = $this->_modelSysconfig->getValueToCache ( 'workorder_source' ); //工单来源
			$workOrderStatusArr = $this->_modelSysconfig->getValueToCache ( 'workorder_status' ); //工单状态
			$gameSerList = $this->_modelGameSerList->findById ( $dataList ['game_server_id'] );
			$operatorList = $this->_modelOperatorList->findById ( $dataList ['operator_id'] );
			#------载入缓存------#

			foreach ($dialogArr as &$list){
				$list['qa_time']=date('Y-m-d H:i:s',$list['qa_time']);
				$list ['word_reply_name'] = $user[$list['user_id']]['nick_name'];
			}

			$workOrderDetailArr = unserialize ( $dataList ['detail'] ); //获取工单的详细信息
			$userData = $workOrderDetailArr ['user_data']; //获取提交工单用户的详细信息
			$userData['register_date']=date('Y-m-d H:i:s',$userData['register_date']);

			$userQuestionDetail = $workOrderDetailArr ['form_detail']; //获取提问类型工单的值
			$questionDetail = $this->_modelQuestionType->findById ( $dataList ['question_type'] ); //查找问题类型

			#------跟椐ID转换文字显示------#
			$dataList ['word_question_type'] = $questionDetail ['title']; //获取问题类型的中文名称以方便显示
			$dataList ['word_game_type'] = $gameTypeArr [$dataList ['game_type']];
			$dataList ['word_source'] = $workOrderSourceArr [$dataList ['source']];
			$dataList ['word_status'] = $workOrderStatusArr [$dataList ['status']];
			$dataList ['word_game_server_id'] = $gameSerList ['server_name'];
			$dataList ['word_operator_id'] = $operatorList ['operator_name'];
			$dataList ['create_time'] = date ( 'Y-m-d H:i', $dataList ['create_time'] );
			$dataList ['word_quality_id']=$user[$dataList['quality_id']]['full_name'];
			#------跟椐ID转换文字显示------#

			
			$questionDetail = $questionDetail ['form_table']; //获取问题类型的表单配置值
			$userQuestionDetailArr = array (); //初始化显示提交问题类型数组
			if (count ( $questionDetail )) {
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

			#------日志------#
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			$this->_view->assign('log',$this->_modelOrderLog->getLog($_GET['work_order_id']));
			#------日志------#
			$this->_view->assign('scorce',$this->_modelQuality->getScorce());
			$this->_view->assign('qualityOptions',$qualityOptions);
			$this->_view->assign ( 'qualityList', $qualityList );
			$this->_view->assign ( 'workOrderStatusArr', $workOrderStatusArr );
			$this->_view->assign ( 'userData', $userData );
			$this->_view->assign ( 'userQuestionDetailArr', $userQuestionDetailArr ); //问题类型显示
			$this->_view->assign ( 'dialogArr', $dialogArr ); //对话详细
			$this->_view->assign ( 'data', $dataList ); //表单详细信息
			$this->_view->assign ( 'userClass', $this->_utilRbac->getUserClass () );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}
	
	/**
	 * 超时回复列表
	 */
	public function actionTimeoutReply(){
		#------初始化------#
		$this->_loadCore ( 'Help_Page' );
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$qualityStatus = $this->_getGlobalData ( 'quality_status' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' );
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		#------初始化------#
		$selected=array();	//模板默认值 
		$users = $this->_getGlobalData ( 'user' );
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrderQa->tName () );
		$helpSqlSearch->set_conditions('is_timeout=1');
		$helpSqlSearch->set_conditions('qa=1');
		if ($_REQUEST['game_type_id']){
			$helpSqlSearch->set_conditions("game_type_id={$_REQUEST['game_type_id']}");
			$selected['game_type_id']=$_REQUEST['game_type_id'];
		}
		if ($_REQUEST['reply_user_id']!=''){
			$helpSqlSearch->set_conditions("user_id={$_REQUEST['reply_user_id']}");
			$selected['reply_user_id']=$_REQUEST['reply_user_id'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("create_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$helpSqlSearch->set_orderBy ( 'create_time desc' );
		$helpSqlSearch->setPageLimit ( $_GET ['page'], 20 );
		
		$allConditions = $helpSqlSearch->get_conditions (); //返回所有条件
		$helpPage = new Help_Page ( array ('total' => $this->_modelWorkOrderQa->findCount ( $allConditions ), 'perpage' => 20 ) );
		
		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelWorkOrderQa->select ( $sql );
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$value ) {
				$value ['word_game_type_id']=$gameTypes[$value['game_type_id']];
				$value ['word_is_quality']=$value['is_quality']?$users[$value['is_quality']]['nick_name']:'<font color="#666666">未质检</font>';
				$value ['word_reply_name']=$users[$value['user_id']]['nick_name'];
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value['last_reply_time']=date('Y-m-d H:i:s',$value['last_reply_time']);
				$value ['url_detail'] = Tools::url ( 'QualityCheck', 'Dialog', array (
																					'Id' => $value ['work_order_id'],
																					'game_type_id'=>$value['game_type_id'],
																					'operator_id'=>$value['operator_id'] ) );
				$value ['content'] = strip_tags ( $value ['content'] );
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		
		$gameTypes['']='所有';
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('users',$users);
		$this->_view->assign('selected',$selected);
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 未发送致服务器的回复
	 * @todo 新增动作
	 */
	public function actionNotSendReply(){
		#------初始化------#
		$this->_loadCore ( 'Help_Page' );
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$qualityStatus = $this->_getGlobalData ( 'quality_status' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' );
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		#------初始化------#
		$selected=array();	//模板默认值 
		$users = $this->_getGlobalData ( 'user' );
		$helpSqlSearch->set_tableName ( $this->_modelWorkOrderQa->tName () );
		$helpSqlSearch->set_conditions('qa=1');
		if ($_REQUEST['game_type_id']){
			$helpSqlSearch->set_conditions("game_type_id={$_REQUEST['game_type_id']}");
			$selected['game_type_id']=$_REQUEST['game_type_id'];
		}
		if ($_REQUEST['reply_user_id']!=''){
			$helpSqlSearch->set_conditions("user_id={$_REQUEST['reply_user_id']}");
			$selected['reply_user_id']=$_REQUEST['reply_user_id'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("create_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$helpSqlSearch->set_conditions('content like \'<em>%\' ');
		$helpSqlSearch->set_orderBy ( 'create_time desc' );
		$helpSqlSearch->setPageLimit ( $_GET ['page'] );
		
		$allConditions = $helpSqlSearch->get_conditions (); //返回所有条件
		$helpPage = new Help_Page ( array ('total' => $this->_modelWorkOrderQa->findCount ( $allConditions ), 'perpage' => PAGE_SIZE ) );
		
		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelWorkOrderQa->select ( $sql );
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$value ) {
				$value ['word_game_type_id']=$gameTypes[$value['game_type_id']];
				$value ['word_is_quality']=$value['is_quality']?$users[$value['is_quality']]['nick_name']:'<font color="#666666">未质检</font>';
				$value ['word_reply_name']=$users[$value['user_id']]['nick_name'];
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value['last_reply_time']=date('Y-m-d H:i:s',$value['last_reply_time']);
				$value ['url_detail'] = Tools::url ( 'QualityCheck', 'Dialog', array (
																					'Id' => $value ['work_order_id'],
																					'game_type_id'=>$value['game_type_id'],
																					'operator_id'=>$value['operator_id'] ) );
				$value ['content'] = strip_tags ( $value ['content'] );
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		
		$gameTypes['']='所有';
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('users',$users);
		$this->_view->assign('selected',$selected);
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	/**
	 * 复检列表
	 */
	public function actionAgainList(){
		#------初始化------#
		$this->_loadCore ( 'Help_Page' );
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch = new Help_SqlSearch ();
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$this->_modelQuality = $this->_getGlobalData ( 'Model_Quality', 'object' );
		$qualityStatus = $this->_getGlobalData ( 'quality_status' );
		$qualityOptions = $this->_getGlobalData ( 'quality_options' );
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operatorList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('operator_list'),'Id','operator_name');
		$againStatus=array('0'=>'未通过','1'=>'通过');
		#------初始化------#
		$selected=array();	//模板默认值 
		$users = $this->_getGlobalData ( 'user' );
		$helpSqlSearch->set_tableName ( $this->_modelQuality->tName () );
		$helpSqlSearch->set_conditions('again_user_id is not null');
		if ($_REQUEST['quality_user_id']!=''){	//质检
			$helpSqlSearch->set_conditions("quality_user_id={$_REQUEST['quality_user_id']}");
			$selected['quality_user_id']=$_REQUEST['quality_user_id'];
		}
		if ($_REQUEST['again_user_id']!=''){	//复检 
			$helpSqlSearch->set_conditions("again_user_id={$_REQUEST['again_user_id']}");
			$selected['again_user_id']=$_REQUEST['again_user_id'];
		}
		if ($_REQUEST['again_status']!=''){		//复检状态
			$helpSqlSearch->set_conditions("again_status={$_REQUEST['again_status']}");
			$selected['again_status']=$_REQUEST['again_status'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("again_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$helpSqlSearch->set_orderBy ( 'again_time desc' );
		$helpSqlSearch->setPageLimit ( $_GET ['page'] );
		
		$allConditions = $helpSqlSearch->get_conditions (); //返回所有条件
		$helpPage = new Help_Page ( array ('total' => $this->_modelQuality->findCount ( $allConditions ), 'perpage' => PAGE_SIZE ) );
		
		$sql = $helpSqlSearch->createSql ();
		$dataList = $this->_modelQuality->select ( $sql );
		if ($dataList) {
			Tools::import ( 'Util_FontColor' );
			foreach ( $dataList as &$value ) {
				$value ['word_game_type_id']=$gameTypes[$value['game_type_id']];
				$value ['word_operator_id']=$operatorList[$value['operator_id']];
				$value ['again_time']=date('Y-m-d H:i:s',$value['again_time']);
				$value ['quality_time'] = date ( 'Y-m-d H:i:s', $value ['quality_time'] );
				$value ['complain_time'] = $value ['complain_time'] ? date ( 'Y-m-d H:i:s', $value ['complain_time'] ) : '';
				$value ['word_option_id'] = $qualityOptions [$value ['option_id']];
				$value ['word_status'] = Util_FontColor::getQualityStatus($value['status'],$this->_statusOptions [$value ['status']]);
				$value ['url_detail'] = Tools::url ( CONTROL, 'QualityDetail', array ('work_order_id' => $value ['work_order_id'], 'qa_id' => $value ['qa_id'] ) );
				$value ['url_document']=Tools::url(CONTROL,'Document',array('doaction'=>'add','Id'=>$value['Id'],'qa_id' => $value ['qa_id'],'work_order_id'=>$value['work_order_id']));
				$value ['word_quality_user_id']=$users[$value['quality_user_id']]['nick_name'];
				$value ['word_again_user_id']=$users[$value['again_user_id']]['nick_name'];
				$value ['word_reply_user_id']=$users[$value['reply_user_id']]['nick_name'];
				$value ['reply_content']=strip_tags($value['reply_content']);
				$value ['quality_content']=strip_tags($value['quality_content']);
				$value ['complain_content']=strip_tags($value['complain_content']);
				$value ['again_content']=strip_tags($value['again_content']);
				$value ['word_again_status']=$againStatus[$value['again_status']];
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		
		$againStatus['']='所有';
		$gameTypes['']='所有';
		$this->_view->assign('againStatus',$againStatus);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('users',$users);
		$this->_view->assign('selected',$selected);
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}
	
	
	/**
	 * 复检动作
	 */
	public function actionAgain(){
		$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
		if ($this->_isPost()){
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
			$updateArr=array(	
				'again_status'=>$_POST['again_status'],
				'again_content'=>$_POST['again_content'],
				'again_time'=>CURRENT_TIME,
				'again_user_id'=>$userClass['_id'],
			);
			#------添加日志------#
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			$this->_modelOrderLog->addLog(array('Id'=>$_POST['work_order_id']),Model_OrderLog::AGAIN);
			#------添加日志------#
			if ($this->_modelQuality->update($updateArr,"Id={$_POST['Id']}")){
				$this->_utilMsg->showMsg(false);
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('AGAIN_ERROR',__CLASS__),-2);
			}
		}
		if ($_GET['doaction']=='option'){//更改评价
			$id=Tools::coerceInt($_GET['Id']);
			$option=intval($_GET['option']);
			$this->_modelQuality->update(array('option_id'=>$option),"Id={$id}");
			$this->_returnAjaxJson(array('status'=>1,'msg'=>'更改评价成功'));
		}
		if ($_GET['doaction']=='scorces'){//更改分数
			$id=Tools::coerceInt($_GET['Id']);
			$scores=intval($_GET['scores']);
			$this->_modelQuality->update(array('scores'=>$scores),"Id={$id}");
			$this->_returnAjaxJson(array('status'=>1,'msg'=>'更改分类成功'));
		}
		
	}
	
	/**
	 * 更改分数ajax
	 */
	public function actionChanageScores(){
		if ($this->_isAjax()){
			$scores=$_GET['scores'];
			$qaId=Tools::coerceInt($_GET['qa_id']);
			$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
			if ($this->_modelQuality->update(array('scores'=>$scores),"qa_id={$qaId}")){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('UPDATE_SOURCE_SUCCESS',__CLASS__)));
			}else {
				$this->_returnAjaxJson(array('status'=>0,'msg'=>Tools::getLang('UPDATE_SOURCE_ERROR',__CLASS__)));
			}
		}
	}
	
	/**
	 * 质检归档编辑功能
	 * @todo 新增方法
	 */
	public function actionDocument(){
		switch ($_GET['doaction']){
			case 'add':{
				$this->_documentAdd();
				return ;
			}
			case 'edit' :{
				$this->_documentEdit();
				return ;
			}
			case 'del' :{
				$this->_documentDel();
				return ;
			}
			default:{
				$this->actionDocumentIndex();//转向document显示页面
				return ;
			}
		}
	}
	
	private function _documentAdd(){
		if ($this->_isPost()){
			$this->_modelQualityDocument=$this->_getGlobalData('Model_QualityDocument','object');
			$data=$this->_modelQualityDocument->add($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			$orgList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('org'),'Id','name');//组列表
			$users=Model::getTtwoArrConvertOneArr($this->_getGlobalData('user'),'Id','nick_name');//用户列表
			$source=$this->_getGlobalData('verify_source');	//来源
			$socres=Tools::getLang('QUALITY_SOURCE','Common');//分数
			$feedBack=array('1'=>'是','0'=>'否');
			$verifyStatus=$this->_getGlobalData('verify_status');
			
			if ($_GET['Id']){
				$workOrderId=Tools::coerceInt($_GET['work_order_id']);
				$qaId=Tools::coerceInt($_GET['qa_id']);
				$id=Tools::coerceInt($_GET['Id']);
				$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
				$qualityDetail=$this->_modelQuality->findById($id);
				$userClass=$this->_utilRbac->getUserClassById($qualityDetail['reply_user_id']);
				$workOrderDetail=$this->_modelWorkOrder->findById($workOrderId);
				
				$selected=array();
				$selected['title']=$workOrderDetail['title'];
				$selected['source']=$workOrderDetail['source'];
				$selected['org_id']=$userClass['_orgId'];
				$selected['reply_user_id']=$qualityDetail['reply_user_id'];
				$selected['quality_user_id']=$qualityDetail['quality_user_id'];
				$selected['option_id']=$qualityDetail['option_id'];
				$selected['scores']=$qualityDetail['scores'];
				$this->_view->assign('selected',$selected);
			}
			
			$this->_view->assign('verifyStatus',$verifyStatus);
			$this->_view->assign('orgList',$orgList);
			$this->_view->assign('users',$users);
			$this->_view->assign('source',$source);
			$this->_view->assign('socres',$socres);
			$this->_view->assign('feedBack',$feedBack);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'QualityCheck/DocumentAdd.html'));
			$this->_view->display();
		}
	}
	
	private function _documentEdit(){
		$this->_modelQualityDocument=$this->_getGlobalData('Model_QualityDocument','object');
		if ($this->_isPost()){
			$data=$this->_modelQualityDocument->edit($_POST);
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
		}else {
			$orgList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('org'),'Id','name');//组列表
			$users=Model::getTtwoArrConvertOneArr($this->_getGlobalData('user'),'Id','nick_name');//用户列表
			$source=$this->_getGlobalData('verify_source');	//来源
			$socres=Tools::getLang('QUALITY_SOURCE','Common');//分数
			$feedBack=array('1'=>'是','0'=>'否');
			$verifyStatus=$this->_getGlobalData('verify_status');
			
			$id=Tools::coerceInt($_GET['Id']);
			$data=$this->_modelQualityDocument->findById($id);
			$this->_view->assign('selected',$data);
			$this->_view->assign('verifyStatus',$verifyStatus);
			$this->_view->assign('orgList',$orgList);
			$this->_view->assign('users',$users);
			$this->_view->assign('source',$source);
			$this->_view->assign('socres',$socres);
			$this->_view->assign('feedBack',$feedBack);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'QualityCheck/DocumentEdit.html'));
			$this->_view->display();
		}
	}
	
	private function _documentDel(){
		$this->_modelQualityDocument=$this->_getGlobalData('Model_QualityDocument','object');
		$id=Tools::coerceInt($_GET['Id']);
		if ($this->_modelQualityDocument->delById($id)){
			$this->_utilMsg->showMsg('删除成功');
		}else{
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}
	
	/**
	 * 质检归档显示页面
	 * @todo 新增方法
	 */
	public function actionDocumentIndex(){
		$orgList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('org'),'Id','name');//组列表
		$users=Model::getTtwoArrConvertOneArr($this->_getGlobalData('user'),'Id','nick_name');//用户列表
		$source=$this->_getGlobalData('verify_source');	//来源
		$socres=Tools::getLang('QUALITY_SOURCE','Common');//分数
		$feedBack=array('1'=>'是','0'=>'否');
		$verifyStatus=$this->_getGlobalData('verify_status');
		$this->_modelQualityDocument=$this->_getGlobalData('Model_QualityDocument','object');
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$selected=array();
		$helpSqlSearch->set_tableName($this->_modelQualityDocument->tName());
		$helpSqlSearch->setPageLimit($_GET['page']);
		if ($_GET['org_id']!=''){
			$helpSqlSearch->set_conditions("org_id={$_GET['org_id']}");
			$selected['org_id']=$_GET['org_id'];
		}
		if ($_GET['reply_user_id']!=''){
			$helpSqlSearch->set_conditions("reply_user_id={$_GET['reply_user_id']}");
			$selected['reply_user_id']=$_GET['reply_user_id'];
		}
		if ($_GET['source']!=''){
			$helpSqlSearch->set_conditions("source={$_GET['source']}");
			$selected['source']=$_GET['source'];
		}
		if ($_GET['quality_status']!=''){
			$helpSqlSearch->set_conditions("quality_status={$_GET['quality_status']}");
			$selected['quality_status']=$_GET['quality_status'];
		}
		if ($_GET['quality_user_id']!=''){
			$helpSqlSearch->set_conditions("quality_user_id={$_GET['quality_user_id']}");
			$selected['quality_user_id']=$_GET['quality_user_id'];
		}
		if ($_GET['create_user_id']!=''){
			$helpSqlSearch->set_conditions("create_user_id={$_GET['create_user_id']}");
			$selected['create_user_id']=$_GET['create_user_id'];
		}
		if ($_GET['socres']!=''){
			$helpSqlSearch->set_conditions("scores={$_GET['socres']}");
			$selected['socres']=$_GET['socres'];
		}
		if ($_GET['feedback']!=''){
			$helpSqlSearch->set_conditions("feedback={$_GET['feedback']}");
			$selected['feedback']=$_GET['feedback'];
		}
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("create_time between {$startTime} and {$endTime}");
			$selected['start_time']=$_REQUEST['start_time'];
			$selected['end_time']=$_REQUEST['end_time'];
		}
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelQualityDocument->select($sql);
		if ($dataList){
			foreach ($dataList as &$list){
				$list['word_org_id']=$orgList[$list['org_id']];
				$list['word_reply_user_id']=$users[$list['reply_user_id']];
				$list['word_quality_user_id']=$users[$list['quality_user_id']];
				$list['word_source']=$source[$list['source']];
				$list['word_quality_status']=$verifyStatus[$list['quality_status']];
				$list['word_feedback']=$feedBack[$list['feedback']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['word_create_user_id']=$users[$list['create_user_id']];
				$list ['url_dialog'] =Tools::url('QualityCheck','OrderDialog',array('Id'=>$list['work_order_id']));
				$list['url_edit']=Tools::url(CONTROL,'Document',array('doaction'=>'edit','Id'=>$list['Id']));
				$list['url_del']=Tools::url(CONTROL,'Document',array('doaction'=>'del','Id'=>$list['Id']));
			}
			$this->_view->assign('dataList',$dataList);
			$this->_loadCore('Help_Page');
			$helpPage=new Help_Page(array('total'=>$this->_modelQualityDocument->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_view->assign('selected',$selected);
		$verifyStatus['']='所有';
		$this->_view->assign('verifyStatus',$verifyStatus);
		$orgList['']='所有';
		$this->_view->assign('orgList',$orgList);
		$this->_view->assign('users',$users);
		$source['']='所有';
		$this->_view->assign('source',$source);
		$socres['']='所有';
		$this->_view->assign('socres',$socres);
		$feedBack['']='所有';
		$this->_view->assign('feedBack',$feedBack);
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'QualityCheck/Document.html'));
		$this->_view->display();
	}
	
	public function actionQualityCount(){
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$Quality	=	$this->_getGlobalData('Model_Quality','object');
		$this->_modelUser = $this->_getGlobalData ( 'Model_User', 'object' );
		$users = $this->_modelUser->findSetOrgByUser ();
		if($users){
			$userarr	=	array();
			foreach($users as $u){
				if($u['org_id']==13){
					if($_POST['userid']){
						if(in_array($u['Id'],$_POST['userid'])){
							$u['is_post']	=	1;
						}
					}
					$userarr[]	=	$u;
				}
			}
		}
		if($this->_isPost()){
			$type	=	array(
				'count'	=>'count',
				'1'=>'true',//		=>	'对',
				'2'=>'Points',//		=>	'加分',
				'-1'=>'Typo',//	=>	'错字',
				'-2'=>'incomplete',//	=>	'内容不完整',
				'-3'=>'Contents_error',//	=>	'内容错误',
				'-4'=>'optimization',//	=>	'建议优化',
				'-5'=>'Out',//	=>	'超时',
				'-6'=>'Other',//	=>	'其它',
			);
			$select	=	array(
				'userid'			=>	$_POST['userid'],
				'start_date'		=>	strtotime($_POST['start_date']),
				'end_date'			=>	strtotime($_POST['end_date']),
			);
			if(count($select['userid']>0)){
				$data	=	$Quality->getCountSelect($select);
				foreach($data as $k=>$_msg){
					$data[$type[$k]]	=	$_msg;
				}
				$this->_view->assign ( 'dataList',$data);
			}
			$this->_view->assign('selectedTime',array('start'=>$_POST['start_date'],'end'=>$_POST['end_date']));
		}
		$this->_view->assign ( 'user',$userarr);
		$this->_view->assign ( 'js',CONTROL.'/Index.js.html' );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

}