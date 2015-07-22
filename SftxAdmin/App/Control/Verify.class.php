<?php
/**
 * 工单查证处理模块
 * @author php-朱磊
 */
class Control_Verify extends Control {

	/**
	 * Model_Verify
	 * @var Model_Verify
	 */
	private $_modelVerify;

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Help_SqlSearch
	 * @var Help_SqlSearch
	 */
	private $_helpSqlSearch;
	
	/**
	 * Util_UserMailManage
	 * @var Util_UserMailManage
	 */
	private $_utilUserMailManage;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_view->assign ( 'workOrderId', $_GET ['work_order_id'] );
	}

	private function _createUrl() {
		$this->_url ['Verify_OrderVerify'] = Tools::url ( CONTROL, 'OrderVerify' );
		$this->_url ['uploadImgUrl'] = Tools::url ( 'Default', 'ImgUpload', array ('type' => 'Verify' ) );
		$this->_url ['Verify_ReplyDialog'] = Tools::url ( CONTROL, 'ReplyDialog' );
		$this->_url ['Verify_ChangeStatus'] = Tools::url ( CONTROL, 'ChangeStatus' );
		$this->_url ['Verify_Index'] = Tools::url(CONTROL,'Index');
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 生成日志/留言对话
	 * @param array $data
	 * @return serialize string
	 * @example $this->_addLog(array('action'=>'add','status'=>5))
	 */
	private function _addLog($data) {
		$verifyStatus = $this->_getGlobalData ( 'verify_status' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
		$userClass = $this->_utilRbac->getUserClass ();
		$log = array ('time' => date ( 'Y-m-d H:i:s', CURRENT_TIME ), 'user' => $userClass['_fullName'] );
		switch ($data ['action']) {
			case 'add' :
				{
					$log ['type'] = '1';
					$log ['description'] = "添加此BUG.状态：{$verifyStatus[$data['status']]}";
					break;
				}
			case 'modify' :
				{
					$log ['type'] = '1';
					$log ['description'] = "修改状态：{$verifyStatus[$data['status']]}";
					break;
				}
			default :
				{
					$log ['type'] = '2';
					$log ['description'] = $data ['msg'];
					break;
				}
		}
		return $log;
	}

	/**
	 * 更改查证处理状态
	 */
	public function actionChangeStatus() {
		if ($this->_isAjax ()) {
			$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
			$newLog = $this->_addLog ( array ('action' => 'modify', 'status' => $_GET ['status'] ) );
			$dataList = $this->_modelVerify->findById ( $_GET ['Id'] );
			if ($dataList ['log'])
				$dataList ['log'] = unserialize ( $dataList ['log'] );
			if (! is_array ( $dataList ['log'] ))
				$dataList ['log'] = array ();
			array_push ( $dataList ['log'], $newLog );
			if ($this->_modelVerify->update ( array ('status' => $_GET ['status'], 'log' => serialize ( $dataList ['log'] ) ), "Id={$_GET['Id']}" )) {
				
				#------发送邮件------#
				$this->_utilUserMailManage=$this->_getGlobalData('Util_UserMailManage','object');
				$this->_utilUserMailManage->addUser($dataList['user_id']);
				$mail=array(
					'title'=>'Buglist改变',
					'href'=>Tools::url(CONTROL,'Detail',array('Id'=>$dataList['Id'],'work_order_id'=>$dataList['work_order_id'])),
					'type'=>3,
				);
				$this->_utilUserMailManage->addMail($mail);
				$this->_utilUserMailManage->send();
				#------发送邮件------#
				
				$this->_returnAjaxJson ( array ('status' => 1, 'msg' => '更改状态成功' ) );
			} else {
				$this->_returnAjaxJson ( array ('status' => 0, 'msg' => '更改状态失败' ) );
			}
		}
	}

	/**
	 * 查证处理列表
	 */
	public function actionIndex() {
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_loadCore ( 'Help_Page' );
		$this->_helpSqlSearch = new Help_SqlSearch ();
		$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
		$users=$this->_getGlobalData('user');
		$users=Model::getTtwoArrConvertOneArr($users,'Id','nick_name');
		$verifyStatus = $this->_getGlobalData ( 'verify_status' );
		$verifyType = $this->_getGlobalData ( 'verify_type' );
		$verifyLevel = $this->_getGlobalData ( 'verify_level' );
		$verifySource=$this->_getGlobalData('verify_source');
		$gameType = $this->_getGlobalData ( 'game_type' );
		$gameType = Model::getTtwoArrConvertOneArr ( $gameType, 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );
		$gameServerListOneArr = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
		$department = $this->_getGlobalData ( 'department' );
		$department = Model::getTtwoArrConvertOneArr ( $department, 'Id', 'name' );
		$this->_helpSqlSearch->set_tableName ( $this->_modelVerify->tName () );
		if ($_REQUEST['department_id']){
			$this->_view->assign('selectedDepartment',$_REQUEST['department_id']);
			$this->_helpSqlSearch->set_conditions("department_id={$_REQUEST['department_id']}");
		}
		if ($_REQUEST['game_type_id']){
			$this->_view->assign('selectedGameTypeId',$_REQUEST['game_type_id']);
			$this->_helpSqlSearch->set_conditions("game_type_id={$_REQUEST['game_type_id']}");
		}
		if ($_REQUEST['user_id']){
			$this->_view->assign('selectedUserId',$_REQUEST['user_id']);
			$this->_helpSqlSearch->set_conditions("user_id={$_REQUEST['user_id']}");
		}
		if ($_REQUEST['operator_id']){
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
			$this->_helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
		}
		if ($_REQUEST['game_server_id']){
			$this->_view->assign('selectedGameServerId',$_REQUEST['game_server_id']);
			$this->_helpSqlSearch->set_conditions("game_server_id={$_REQUEST['game_server_id']}");
		}
		if ($_REQUEST['status']){
			$this->_view->assign('selectedStatus',$_REQUEST['status']);
			$this->_helpSqlSearch->set_conditions("status={$_REQUEST['status']}");
		}
		if ($_REQUEST['type']){
			$this->_view->assign('selectedType',$_REQUEST['type']);
			$this->_helpSqlSearch->set_conditions("type={$_REQUEST['type']}");
		}
		if ($_REQUEST['source']){
			$this->_view->assign('selectedSource',$_REQUEST['source']);
			$this->_helpSqlSearch->set_conditions("source={$_REQUEST['source']}");
		}
		if ($_REQUEST['level']){
			$this->_view->assign('selectedLevel',$_REQUEST['level']);
			$this->_helpSqlSearch->set_conditions("level={$_REQUEST['level']}");
		}
		if ($_REQUEST['game_user_id']){
			$this->_view->assign('selectedGameUserId',$_REQUEST['game_user_id']);
			$this->_helpSqlSearch->set_conditions("game_user_id={$_REQUEST['game_user_id']}");
		}
		if ($_REQUEST['game_user_account']){
			$this->_view->assign('selectedGameUserAccount',$_REQUEST['game_user_account']);
			$this->_helpSqlSearch->set_conditions("game_user_account='{$_REQUEST['game_user_account']}'");
		}
		if ($_REQUEST['game_user_nickname']){
			$this->_view->assign('selectedGameUserNickName',$_REQUEST['game_user_nickname']);
			$this->_helpSqlSearch->set_conditions("game_user_nickname='{$_REQUEST['game_user_nickname']}'");
		}
		if ($_REQUEST['title']){
			$this->_view->assign('selectedTitle',$_REQUEST['title']);
			$this->_helpSqlSearch->set_conditions("title like '%{$_REQUEST['title']}%'");
		}
		if ($_REQUEST['source_detail']){
			$this->_view->assign('selectedSourceDetail',$_REQUEST['source_detail']);
			$this->_helpSqlSearch->set_conditions("source_detail like '%{$_REQUEST['source']}%'");
		}
		$this->_helpSqlSearch->set_orderBy ( "Id desc" );
		$this->_helpSqlSearch->setPageLimit ( $_GET ['page'], PAGE_SIZE );
		$helpPage = new Help_Page ( array ('total' => $this->_modelVerify->findCount ($this->_helpSqlSearch->get_conditions()), 'perpage' => PAGE_SIZE ) );

		$sql = $this->_helpSqlSearch->createSql ();
		$dataList = $this->_modelVerify->select ( $sql );
		if ($dataList) {
			Tools::import('Util_FontColor');
			foreach ( $dataList as &$value ) {
				$verifyTypeToGameType = $this->_getVerifyTypeByGameType ( $value ['game_type_id'] );
				$value ['word_game_type_id'] = $gameType [$value ['game_type_id']];
				$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
				$value ['word_game_server_id'] = $gameServerListOneArr [$value ['game_server_id']];
				$value ['word_status'] = $verifyStatus [$value ['status']];
				$value ['word_type'] = $verifyTypeToGameType [$value ['type']];
				$value ['word_level'] = Util_FontColor::getVerifyLevel($value['level'],$verifyLevel [$value ['level']]);
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value ['word_department_id'] = $department [$value ['department_id']];
				$value ['url_detail'] = Tools::url ( CONTROL, 'Detail', array ('Id' => $value ['Id'], 'work_order_id' => $value ['work_order_id'] ) );
				$value ['url_order_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $value ['work_order_id'] ) );
				$value ['work_user_id']=$users[$value['user_id']];
				$value ['word_source']=Util_FontColor::getVerifySource($value['source'],$verifySource[$value['source']]);
				$value ['content']=(strpos($value['content'],'\\'))?str_replace('\\','',$value['content']):$value['content'];
				if ($value ['log'])
					$value ['log'] = unserialize ( $value ['log'] );
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$this->_view->assign('users',$users);
		$this->_view->assign('verifySource',$verifySource);
		$this->_view->assign ( 'gameServerList', json_encode ( $gameServerList ) );
		$this->_view->assign ( 'department', $department );
		$this->_view->assign ( 'verifyStatus', $verifyStatus );
		$this->_view->assign ( 'verifyTypeJson', json_encode ( $verifyType ) );
		$this->_view->assign ( 'verifyLevel', $verifyLevel );
		$this->_view->assign ( 'gameType', $gameType );
		$this->_view->assign ( 'operatorList', $operatorList );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 根据gameTypeId查找问题类型
	 * @param int $gameTypeId
	 */
	private function _getVerifyTypeByGameType($gameTypeId) {
		if (! $gameTypeId)
			return false;
		$gameTypeId = abs ( intval ( $gameTypeId ) );
		$verifyType = $this->_getGlobalData ( 'verify_type' );
		return $verifyType [$gameTypeId];
	}

	/**
	 * 添加查证处理
	 */
	public function actionOrderVerify() {
		$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
		if ($this->_isPost ()) {
			if (! $_POST ['game_type_id'] || ! $_POST ['operator_id'] || !$_POST ['game_server_id']) {
				$this->_utilMsg->showMsg ( '请选择完整的信息', - 1 );
			} else {
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				$addArr = array (
							'user_id'=>$userClass['_id'],
							'department_id' => $_POST ['department_id'],
							'create_time' => CURRENT_TIME,
							'game_type_id' => $_POST ['game_type_id'],
							'operator_id' => $_POST ['operator_id'],
							'game_server_id' => $_POST ['game_server_id'],
							'status' => $_POST ['status'],
							'type' => $_POST ['type'],
							'level' => $_POST ['level'],
							'title' => $_POST ['title'],
							'content' => $_POST ['content'],
							'game_user_id'=>abs(intval($_POST['game_user_id'])),
							'game_user_account'=>$_POST['game_user_account'],
							'game_user_nickname'=>$_POST['game_user_nickname'],
							'source'=>$_POST['source'],
				);
				if ($_POST['source_detail'])$addArr['source_detail']=$_POST['source_detail'];
				if ($_POST['work_order_id'])$addArr['work_order_id']=$_POST ['work_order_id'];
				$addArr ['log'] = array ();
				array_push ( $addArr ['log'], $this->_addLog ( array ('action' => 'add', 'status' => $_POST ['status'] ) ) );
				$addArr ['log'] = serialize ( $addArr ['log'] );
				if ($this->_modelVerify->add ( $addArr )) {
					if ($_POST['work_order_id']){
						$this->_utilMsg->showMsg ( false );
					}else{
						$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,'Index'));
					}

				} else {
					$this->_utilMsg->showMsg ( '添加查询谁失败', - 2 );
				}
			}
		} else {
			$verifyStatus = $this->_getGlobalData ( 'verify_status' );
			$verifyType = $this->_getGlobalData ( 'verify_type' );
			$verifyLevel = $this->_getGlobalData ( 'verify_level' );
			$verifySource=$this->_getGlobalData('verify_source');
			$gameType = $this->_getGlobalData ( 'game_type' );
			$gameType = Model::getTtwoArrConvertOneArr ( $gameType, 'Id', 'name' );
			$operatorList = $this->_getGlobalData ( 'operator_list' );
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$gameServerList = $this->_getGlobalData ( 'gameser_list' );
			$gameServerListOneArr = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
			$department = $this->_getGlobalData ( 'department' );
			$department = Model::getTtwoArrConvertOneArr ( $department, 'Id', 'name' );

			if (isset($_GET['work_order_id'])){
				$dataList = $this->_modelVerify->findByWorkOrderId ( $_GET ['work_order_id'] );
				if ($dataList) {
					foreach ( $dataList as &$value ) {
						$verifyTypeToGameType = $this->_getVerifyTypeByGameType ( $value ['game_type_id'] );
						$value ['word_game_type_id'] = $gameType [$value ['game_type_id']];
						$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
						$value ['word_game_server_id'] = $gameServerListOneArr [$value ['game_server_id']];
						$value ['word_status'] = $verifyStatus [$value ['status']];
						$value ['word_type'] = $verifyTypeToGameType [$value ['type']];
						$value ['word_level'] = $verifyLevel [$value ['level']];
						$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
						$value ['word_department_id'] = $department [$value ['department_id']];
						$value ['url_detail'] = Tools::url ( CONTROL, 'Detail', array ('Id' => $value ['Id'], 'work_order_id' => $_GET ['work_order_id'] ) );
						if ($value ['log'])
							$value ['log'] = unserialize ( $value ['log'] );
					}
					$this->_view->assign ( 'dataList', $dataList );
				}
			}


			#------selected------#
			$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
			$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
			$this->_view->assign('selectedServerId',$_GET['game_server_id']);
			$this->_view->assign('gameUserId',$_GET['game_user_id']);
			$this->_view->assign('userAccount',$_GET['user_account']);
			$this->_view->assign('userNickname',$_GET['user_nickname']);
			#------selected------#

			$this->_view->assign('verifySource',$verifySource);
			$this->_view->assign ( 'gameServerList', json_encode ( $gameServerList ) );
			$this->_view->assign ( 'department', $department );
			$this->_view->assign ( 'verifyStatus', $verifyStatus );
			$this->_view->assign ( 'verifyType', json_encode ( $verifyType ) );
			$this->_view->assign ( 'verifyLevel', $verifyLevel );
			$this->_view->assign ( 'gameType', $gameType );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			if (!$_REQUEST['work_order_id'])$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	/**
	 * 我的查证处理
	 */
	public function actionMyVerify(){
		$this->_loadCore ( 'Help_SqlSearch' );
		$this->_loadCore ( 'Help_Page' );
		$this->_helpSqlSearch = new Help_SqlSearch ();
		$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();

		$users=$this->_getGlobalData('user');
		$users=Model::getTtwoArrConvertOneArr($users,'Id','nick_name');
		$verifyStatus = $this->_getGlobalData ( 'verify_status' );
		$verifyType = $this->_getGlobalData ( 'verify_type' );
		$verifyLevel = $this->_getGlobalData ( 'verify_level' );
		$verifySource=$this->_getGlobalData('verify_source');
		$gameType = $this->_getGlobalData ( 'game_type' );
		$gameType = Model::getTtwoArrConvertOneArr ( $gameType, 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );
		$gameServerListOneArr = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
		$department = $this->_getGlobalData ( 'department' );
		$department = Model::getTtwoArrConvertOneArr ( $department, 'Id', 'name' );
		$this->_helpSqlSearch->set_tableName ( $this->_modelVerify->tName () );
		$this->_helpSqlSearch->set_conditions("user_id={$userClass['_id']}");
		if ($_REQUEST['department_id']){
			$this->_view->assign('selectedDepartment',$_REQUEST['department_id']);
			$this->_helpSqlSearch->set_conditions("department_id={$_REQUEST['department_id']}");
		}
		if ($_REQUEST['game_type_id']){
			$this->_view->assign('selectedGameTypeId',$_REQUEST['game_type_id']);
			$this->_helpSqlSearch->set_conditions("game_type_id={$_REQUEST['game_type_id']}");
		}
		if ($_REQUEST['operator_id']){
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
			$this->_helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
		}
		if ($_REQUEST['game_server_id']){
			$this->_view->assign('selectedGameServerId',$_REQUEST['game_server_id']);
			$this->_helpSqlSearch->set_conditions("game_server_id={$_REQUEST['game_server_id']}");
		}
		if ($_REQUEST['status']){
			$this->_view->assign('selectedStatus',$_REQUEST['status']);
			$this->_helpSqlSearch->set_conditions("status={$_REQUEST['status']}");
		}
		if ($_REQUEST['type']){
			$this->_view->assign('selectedType',$_REQUEST['type']);
			$this->_helpSqlSearch->set_conditions("type={$_REQUEST['type']}");
		}
		if ($_REQUEST['level']){
			$this->_view->assign('selectedLevel',$_REQUEST['level']);
			$this->_helpSqlSearch->set_conditions("level={$_REQUEST['level']}");
		}
		$this->_helpSqlSearch->set_orderBy ( "Id desc" );
		$this->_helpSqlSearch->setPageLimit ( $_GET ['page'], 20 );
		$helpPage = new Help_Page ( array ('total' => $this->_modelVerify->findCount ($this->_helpSqlSearch->get_conditions()), 'perpage' => 20 ) );

		$sql = $this->_helpSqlSearch->createSql ();
		$dataList = $this->_modelVerify->select ( $sql );
		if ($dataList) {
			Tools::import('Util_FontColor');
			foreach ( $dataList as &$value ) {
				$verifyTypeToGameType = $this->_getVerifyTypeByGameType ( $value ['game_type_id'] );
				$value ['word_game_type_id'] = $gameType [$value ['game_type_id']];
				$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
				$value ['word_game_server_id'] = $gameServerListOneArr [$value ['game_server_id']];
				$value ['word_status'] = $verifyStatus [$value ['status']];
				$value ['word_type'] = $verifyTypeToGameType [$value ['type']];
				$value ['word_level'] = Util_FontColor::getVerifyLevel($value['level'],$verifyLevel [$value ['level']]);
				$value ['create_time'] = date ( 'Y-m-d H:i:s', $value ['create_time'] );
				$value ['word_department_id'] = $department [$value ['department_id']];
				$value ['url_detail'] = Tools::url ( CONTROL, 'Detail', array ('Id' => $value ['Id'], 'work_order_id' => $value ['work_order_id'] ) );
				$value ['url_order_detail'] = Tools::url ( 'WorkOrder', 'Detail', array ('Id' => $value ['work_order_id'] ) );
				$value ['work_user_id']=$users[$value['user_id']];
				$value ['word_source']=Util_FontColor::getVerifySource($value['source'],$verifySource[$value['source']]);
				$value ['content']=(strpos($value['content'],'\\'))?str_replace('\\','',$value['content']):$value['content'];
				if ($value ['log'])
					$value ['log'] = unserialize ( $value ['log'] );
			}
			$this->_view->assign ( 'dataList', $dataList );
		}
		$this->_view->set_tpl(array('body'=>'Verify/Index.html'));
		$this->_view->assign ( 'gameServerList', json_encode ( $gameServerList ) );
		$this->_view->assign ( 'department', $department );
		$this->_view->assign ( 'verifyStatus', $verifyStatus );
		$this->_view->assign ( 'verifyTypeJson', json_encode ( $verifyType ) );
		$this->_view->assign ( 'verifyLevel', $verifyLevel );
		$this->_view->assign ( 'gameType', $gameType );
		$this->_view->assign ( 'operatorList', $operatorList );
		$this->_view->assign ( 'js', 'Verify/Index.js.html' );
		$this->_view->assign ( 'pageBox', $helpPage->show () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 察看详细
	 */
	public function actionDetail() {
		$verifyStatus = $this->_getGlobalData ( 'verify_status' );
		$verifyLevel = $this->_getGlobalData ( 'verify_level' );
		$verifySource=$this->_getGlobalData('verify_source');

		$gameType = $this->_getGlobalData ( 'game_type' );
		$gameType = Model::getTtwoArrConvertOneArr ( $gameType, 'Id', 'name' );
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameServerList = $this->_getGlobalData ( 'gameser_list' );
		$gameServerListOneArr = Model::getTtwoArrConvertOneArr ( $gameServerList, 'Id', 'server_name' );
		$department = $this->_getGlobalData ( 'department' );
		$department = Model::getTtwoArrConvertOneArr ( $department, 'Id', 'name' );

		$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
		$dataList = $this->_modelVerify->findById ( $_GET ['Id'] );
		if ($dataList) {
			$verifyType = $this->_getVerifyTypeByGameType ( $dataList ['game_type_id'] );
			$dataList ['word_game_type_id'] = $gameType [$dataList ['game_type_id']];
			$dataList ['word_operator_id'] = $operatorList [$dataList ['operator_id']];
			$dataList ['word_game_server_id'] = $gameServerListOneArr [$dataList ['game_server_id']];
			$dataList ['word_type'] = $verifyType [$dataList ['type']];
			$dataList ['word_level'] = $verifyLevel [$dataList ['level']];
			$dataList ['create_time'] = date ( 'Y-m-d H:i:s', $dataList ['create_time'] );
			$dataList ['word_department_id'] = $department [$dataList ['department_id']];
			$dataList['work_source']=$verifySource[$dataList['source']];
			if (strpos ( $dataList ['content'], '\\' ))
				$dataList ['content'] = str_replace ( '\\', '', $dataList ['content'] );
			if ($dataList ['log'])
				$dataList ['log'] = unserialize ( $dataList ['log'] );
			$this->_view->assign ( 'dataList', $dataList );
		}

		$this->_view->assign ('department',$department);
		$this->_view->assign ( 'verifyStatus', $verifyStatus );
		$this->_view->assign ( 'js', $this->_view->get_curJs () );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	/**
	 * 回复留言对话
	 */
	public function actionReplyDialog() {
		if ($this->_isPost ()) {
			$this->_modelVerify = $this->_getGlobalData ( 'Model_Verify', 'object' );
			$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
			$userClass = $this->_utilRbac->getUserClass ();
			$dataList = $this->_modelVerify->findById ( $_POST ['Id'] );

			$contentArr = $dataList ['log'];
			if ($contentArr) {
				$contentArr = unserialize ( $contentArr );
				if (!is_array($contentArr))$contentArr=array();
			} else {
				$contentArr = array ();
			}
			
			if ($dataList['status']!=$_POST['status']){//如果更改过状态
				$newLog=$this->_addLog(array('status'=>$_POST['status'],'action'=>'modify'));
				array_push($contentArr,$newLog);
			}

			if ($dataList['department_id']!=$_POST['department_id']){
				$department=$this->_getGlobalData('department');
				$content="转交部门  <font color='#FF0000'><b>{$department[$_POST['department_id']]['name']}</b></font> ";
				if (empty($_POST['content']))$content=$this->_addLog(array('msg'=>$content));
			}
			
			if (!empty($_POST ['content'])){//如果content不为空的话,就继续加留言
				$content.='：'.$_POST ['content'];
				$content = $this->_addLog ( array ('msg' => $content ) );
			}
			
			if (!empty($content))array_push ( $contentArr, $content );

			if ($this->_modelVerify->update ( array ('log' => serialize ( $contentArr ),'department_id'=>$_POST['department_id'],'status'=>$_POST['status'] ), "Id={$_POST['Id']}" )) {
				
				#------发送邮件------#
				$this->_utilUserMailManage=$this->_getGlobalData('Util_UserMailManage','object');
				$this->_utilUserMailManage->addUser($dataList['user_id']);
				$mail=array(
					'title'=>"Buglist改变：{$dataList['title']}",
					'href'=>Tools::url(CONTROL,'Detail',array('Id'=>$dataList['Id'],'work_order_id'=>$dataList['work_order_id'])),
					'type'=>3,
				);
				$this->_utilUserMailManage->addMail($mail);
				$this->_utilUserMailManage->send();
				#------发送邮件------#
				
				$this->_utilMsg->showMsg ( false );
			} else {
				$this->_utilMsg->showMsg ( '添加留言失败', - 2 );
			}
		}
	}

}