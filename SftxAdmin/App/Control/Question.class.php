<?php
/**
 * 提问处理模板
 * @author php-朱磊
 */
class Control_Question extends Control {

	/**
	 * Model_QuestionType
	 * @var Model_QuestionType
	 */
	private $_modelQuestionType;

	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

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
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		Tools::import ( 'Model_Sysconfig' );
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
		$this->_modelQuestionType = $this->_getGlobalData('Model_QuestionType','object');
		$this->_modelSysconfig = new Model_SysConfig ();
	}

	private function _createUrl() {
		$this->_url ['Question_Index'] = Tools::url ( CONTROL, 'Index' );
		$this->_url ['Question_Ask'] = Tools::url ( CONTROL, 'Ask' );
		$this->_url ['Question_Add'] = Tools::url ( CONTROL, 'Add' );
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 手动提问主显示页面
	 */
	public function actionIndex() {
		$gameTypeList = Model::getTtwoArrConvertOneArr($this->_modelSysconfig->getValueToCache ( 'game_type' ),'Id','name');;
		$questionList = $this->_modelQuestionType->findAll ();
		foreach ( $questionList as &$value ) {
			unset ( $value ['form_table'] );
		}
		$this->_view->assign ( 'questionList', json_encode ( $questionList ) );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 选择了游戏,提交了问题名称之后进入的页面
	 */
	public function actionAsk() {
		if ($this->_isPost ()) {
			$gameTypeList = Model::getTtwoArrConvertOneArr($this->_modelSysconfig->getValueToCache ( 'game_type' ),'Id','name');;
			$question = $this->_modelQuestionType->findById ( $_POST ['question_type'] ); //查找ID对应的问题
			Tools::import ( 'Util_QuickForm' );
			$quickForm = new Util_QuickForm ();
			$quickForm->set_selectedGameType ( $_POST ['game_type'] ); //设置用户提交过来的游戏类型,方便调用服务器列表
			$quickForm->addManyElementArray ( $question ['form_table'] );
			$this->_view->assign ( 'questionHtml', $quickForm->get_formHtml () );
			$this->_view->assign ( 'questionTitle', $question ['title'] ); //获取问题标题
			$this->_view->assign ( 'gameType', $_POST ['game_type'] );
			$this->_view->assign ( 'questionType', $_POST ['question_type'] );
			$this->_view->assign ( 'word_gameType', $gameTypeList [$_POST ['game_type']] );
			$this->_view->assign ( 'title', $_POST ['title'] );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	/**
	 * 添加问题
	 */
	public function actionAdd() {
		if ($this->_isPost ()) {
			$this->_modelWorkOrderDetail=$this->_getGlobalData('Model_WorkOrderDetail','object');
			$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');

			#------获取问题类型额外信息------#
			$questionType=$this->_modelQuestionType->findById($_POST['question_type']);
			$questionFormTable=$questionType['form_table'];
			$questionFormTableKey=$this->_modelQuestionType->getTtwoArrConvertOneArr($questionFormTable,'name','name');
			$formDetail=array();
			if (!empty($questionFormTableKey)){
				foreach ($questionFormTableKey as $value){
					if ($value==null)continue;
					$formDetail[$value]=$_POST[$value];
				}
			}
			#------获取问题类型额外信息------#

			#------生成工单work_order------#
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();		//获取用户资料,
			$userName=$userClass['_nickName'];
			$workOrder=array(
				'game_type'=>$_POST['game_type'],
				'question_type'=>$_POST['question_type'],
				'source'=>5,
				'question_num'=>1,
				'create_time'=>CURRENT_TIME,
				'title'=>$_POST['title'],
			);
			if ($_POST['game_server_id']){
				#------获取服务器ID和运营商ID------#
				list($workOrder['operator_id'],$workOrder['game_server_id'])=explode(',',$_POST['game_server_id']);
				unset($formDetail['game_server_id']);		//删除服务器列表数组
				#------获取服务器ID和运营商ID------#
			}
			$this->_modelWorkOrder->add($workOrder);
			$workOrderId=$this->_modelWorkOrder->returnLastInsertId();	//获取工单id
			#------生成工单work_order------#

			#------生成工单详细信息------#
			$userDetail=array('nick_name'=>$userName);
			$detail=array('user_data'=>$userDetail,'form_detail'=>$formDetail);
			$addWorkOrderDetailArr=array(
				'work_order_id'=>$workOrderId,
				'content'=>serialize($detail),
			);
			$this->_modelWorkOrderDetail->add($addWorkOrderDetailArr);
			#------生成工单详细信息------#

			#------生成工单提问信息------#
			$addWorkOrderQaArr=array(
				'work_order_id'=>$workOrderId,
				'content'=>$_POST['description'],
				'create_time'=>CURRENT_TIME,
			);
			$this->_modelWorkOrderQa->add($addWorkOrderQaArr);
			#------生成工单提问回复信息------#

			#------增加问题队列到队列------#
			$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
			$orderManage=$this->_utilWorkOrder->getOrderManage();
			$workOrder['Id']=$workOrderId;
			$orderManage->addOrder($workOrder);
			$orderManage->setUpdateInfo(1);
			#------增加问题队列到队列------#

			$this->_utilMsg->showMsg('工单提问成功',1,Tools::url(CONTROL,'Index',1));
		}
	}
}