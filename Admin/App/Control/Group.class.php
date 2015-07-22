<?php
/**
 * 房间管理模块
 * @author php-朱磊
 */
class Control_Group extends Control {

	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;

	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

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
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;

	/**
	 * Model_Rooms
	 * @var Model_Rooms
	 */
	private $_modelRooms;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilRooms = $this->_getGlobalData ( 'Util_Rooms', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}

	private function _createUrl() {
		$this->_url ['Group_Add'] = Tools::url ( CONTROL, 'Edit',array('doaction'=>'add') );
		$this->_url ['Group_Edit'] = Tools::url ( CONTROL, 'Edit',array('doaction'=>'edit') );
		$this->_url ['Group_SetServer']=Tools::url(CONTROL,'Server',array('doaction'=>'setServer'));
		$this->_url ['Group_DelServerToRoom']=Tools::url(CONTROL,'Server',array('doaction'=>'delServer'));
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 任务分区
	 */
	public function actionRoom(){
		switch ($_GET['doaction']){
			case 'inRoom':{
				$this->_inRoom();
				return ;
			}
			case 'outRoom' :{
				$this->_outRoom();
				return ;
			}
			case 'cache':{
				$this->_createcache();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}

	/**
	 * 缓存
	 * */

	private function _createcache(){
		$room = $this->_getGlobalData ( 'Model_Rooms', 'object' );
		if($room->createCache()){
			$this->_utilMsg->showMsg ( '缓存更新成功', 1 );
		}else{
			$this->_utilMsg->showMsg ( '缓存更新失败', - 2 );
		}
	}

	/**
	 * 监控
	 */
	public function actionMonitor(){
		switch ($_GET['doaction']){
			case 'monitor' :{//监控
				$this->_monitor();
				return ;
			}
			case 'kickUser':{
				$this->_kickUser();
				return ;
			}
			case 'kickAllUser' :{
				$this->_kickAllUser();
				return ;
			}
			case 'onOff':{//设置房间开关
				$this->_onOff();
				return ;
			}
		}
	}

	/**
	 * 设置服务器
	 */
	public function actionServer(){
		switch ($_GET['doaction']){
			case 'setServer' :{//设置服务器
				$this->_setServer();
				return ;
			}
			case 'delServer' :{//删除服务器
				$this->_delServer();
				return ;
			}
		}
	}

	/**
	 * 编辑
	 */
	public function actionEdit(){
		switch ($_GET['doaction']){
			case 'add':{
				$this->_add();
				return ;
			}
			case 'edit' :{
				$this->_edit();
				return ;
			}
			case 'del':{
				$this->_del();
				return ;
			}
		}
	}

	/**
	 * 重置工作量
	 */
	public function actionRest(){
		$this->_rest();
	}

	/**
	 * 初始化
	 */
	public function actionInit(){
		$this->_initialize();
	}

	private function _index() {
		$roomList = $this->_utilRooms->findAllRooms ();
		if ($roomList){
			$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
			$userClass=$this->_utilRbac->getUserClass();
			foreach ( $roomList as &$value ) {
				$value ['server_list'] = $this->_modelGameSerList->findByRoomUserList($value['_id'],$userClass);
				$value ['url_edit_server'] = Tools::url(CONTROL,'Server',array('Id'=>$value['_id'],'doaction'=>'setServer'));
				$value ['url_edit'] = Tools::url ( CONTROL, 'Edit', array ('Id' => $value ['_id'],'doaction'=>'edit' ) );
				$value ['url_del'] = Tools::url ( CONTROL, 'Edit', array ('Id' => $value ['_id'],'doaction'=>'del' ) );
				$value ['url_Initialize']=Tools::url(CONTROL,'Init',array('Id'=>$value['_id']));
				$value ['url_rest']=Tools::url(CONTROL,'Rest',array('Id'=>$value['_id']));
				$value ['url_monitor'] = Tools::url ( CONTROL, 'Monitor', array ('Id' => $value ['_id'],'doaction'=>'monitor' ) );
				$value ['word_entrance'] = $value ['_entrance'] ? Tools::getLang('OPEN','Common') : Tools::getLang('CLOSE','Common');
				$value ['word_exit'] = $value ['_exit'] ? Tools::getLang('OPEN','Common') : Tools::getLang('CLOSE','Common');
				$value ['word_workOrderStatus'] = $value ['_workOrderStatus'] ? Tools::getLang('OPEN','Common') : Tools::getLang('CLOSE','Common');
				$value ['userClassNum'] = count ( $value ['_userClassList'] );
				if ($userClass['_roomId']==$value['_id']){
					$value['in_room']='<a style="color:#F00" href="'.Tools::url(CONTROL,'Room',array('doaction'=>'outRoom')).'">'.Tools::getLang('OUT_ROOM',__CLASS__).'</a>';
				}else {
					$value['in_room']='<a href="'.Tools::url(CONTROL,'Room',array('Id'=>$value['_id'],'doaction'=>'inRoom')).'">'.Tools::getLang('IN_ROOM',__CLASS__).'</a>';
				}
			}
			$this->_view->assign ( 'dataList', $roomList );
		}
		$this->_view->assign("cacheurl",Tools::url(CONTROL,'Room',array("doaction"=>"cache")));
		$this->_view->assign("cacheuser",Tools::url('user','user',array("doaction"=>"cache")));
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Group/Index.html'));
		$this->_view->display ();
	}

	private function _del() {
		$this->_utilRooms->delRoom ( $_GET ['Id'] );
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$this->_modelGameSerList->update(array('room_id'=>0),"room_id={$_GET['Id']}");	//所有属于这个房间的服务器都置0
		$this->_utilMsg->showMsg ( Tools::getLang('DEL_SUCESS','Common'), 1 );
	}

	private function _edit() {
		if ($this->_isPost ()) {
			$_POST["autoreply"]	=	serialize($_POST["autoreply"]);
			$this->_utilRooms->editRoom($_POST);
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1 ,Tools::url(CONTROL,'Room'));
		} else {
			$gameTypes=Model::getTtwoArrConvertOneArr($this->_getGlobalData('game_type'),'Id','name');
			$operatorList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('operator_list'),'Id','operator_name');

			$this->_view->assign('gameTypes',$gameTypes);
			$this->_view->assign('operatorList',$operatorList);
			$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
			$room=$this->_modelRooms->findById($_GET['Id']);
			if($room["autoreply"]){
				$room["autoreply"]	=	unserialize($room["autoreply"]);
			}
			$this->_view->assign ( 'roomClass',$room );
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Group/Edit.html'));
			$this->_view->display ();
		}
	}

	/**
	 * 房间监控
	 */
	private function _monitor() {
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$serverList=$this->_modelGameSerList->findByRoomId($_GET['Id']);
		$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
		$this->_utilOnline = $this->_getGlobalData ( 'Util_Online', 'object' );
		$onlinUser = $this->_utilOnline->getOnlineUser ('user_name');
		$roomUsers = $roomClass->findAllUser ();

		foreach ( $roomUsers as $userClass ) {
			if (in_array ( $userClass ['_userName'], $onlinUser )) {
				$userClass ['online'] = Tools::getLang('ONLINE','Common');
			} else {
				$userClass ['online'] = Tools::getLang('OUTLINE','Common');
			}
			if(method_exists($userClass,"getOrderNum")){
				$userClass['cur_order_num']=$userClass->getOrderNum();
			}
			//			$userClass['cur_order_num']=$userClass->getOrderNum();	//当天的工单数
			$userClass['url_edit_operator']=Tools::url('User','UserSetup',array('Id'=>$userClass['_id'],'doaction'=>'managerOperator'));
			$userClass['url_clear_order']=Tools::url('User','UserClearOrder',array('user_id'=>$userClass['_id']));
			$userClass['url_clear_quality_check']=Tools::url('User','ClearQualityCheck',array('user_id'=>$userClass['_id']));

			$userClass['url_kill_user']=Tools::url(CONTROL,'Monitor',array('user_name'=>$userClass['_userName'],'room_id'=>$_GET['Id'],'doaction'=>'kickUser'));
		}
		$this->_view->assign('serverList',$serverList);
		$this->_view->assign ( 'dataList', $roomUsers );
		$this->_view->assign ( 'roomClass', $roomClass );
		$this->_url ['WorkOrder_RoomOnOff'] = Tools::url ( CONTROL, 'Monitor', array ('Id' => $_GET ['Id'],'doaction'=>'onOff' ) );
		$this->_url ['WorkOrder_KickAllUser'] = Tools::url ( CONTROL, 'Monitor', array ('Id' => $_GET ['Id'],'doaction'=>'kickAllUser' ) );
		$this->_view->assign ( 'url', $this->_url );
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Group/Monitor.html'));
		$this->_view->display ();
	}

	/**
	 * 清除单个用户退出房间
	 */
	private function _kickUser() {
		if (!$_GET['user_name'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_KICKUSER',__CLASS__),-1);
		if (!$_GET['room_id'])$this->_utilMsg->showMsg(Tools::getLang('SELECT_ROOM',__CLASS__),-1);
		$userName = $_GET ['user_name'] ;
		$userClass=$this->_utilRbac->getUserClass($userName);
		$roomClass = $this->_utilRooms->getRoom ( $_GET['room_id'] );
		if ($roomClass->kickUser ( $userClass )){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('KICKUSER_ERROR',__CLASS__),-2);
		}

	}

	/**
	 * 清除所有用户退出房间
	 */
	private function _kickAllUser() {
		$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
		if (method_exists($roomClass,"kickAllUser")){
			$roomClass->kickAllUser ();
		}
		$this->_utilMsg->showMsg ( false );
	}

	/**
	 * 房间的出口,入口,开关,开始.结束时间设置
	 */
	private function _onOff() {
		$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
		$roomClass ['_entrance'] = $_POST ['entrance'] ? true : false;
		$roomClass ['_exit'] = $_POST ['exit'] ? true : false;
		$roomClass->setUpdateInfo ( 1 );
		$this->_utilMsg->showMsg ( false );
	}

	/**
	 * 设置房间服务器列表
	 */
	private function _setServer(){
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		if ($this->_isPost()){
			if (!count($_POST['server_list']))$this->_utilMsg->showMsg(false);
			$selectedList=implode(',',$_POST['server_list']);
			if ($this->_modelGameSerList->update(array('room_id'=>$_POST['room_id']),"Id in ({$selectedList})")){
				$this->_modelGameSerList->createToCache();
				$this->_utilMsg->showMsg(Tools::getLang('UPDATE_SUCCESS','Common'));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('UPDATE_ERROR','Common'),-2);
			}
		}else{
			$alreadyServerList=$this->_modelGameSerList->findByRoomId($_GET['Id'],false);
			$dataList=$this->_modelGameSerList->findNoRoomId();
			$gameTypeList=$this->_getGlobalData('game_type');
			$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
			$operatorList=$this->_getGlobalData('operator_list');
			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');

			if ($alreadyServerList){	//如果该房间设置了服务器列表
				$roomServerList=array();
				foreach ($alreadyServerList as $serList){
					$roomServerList[$serList['Id']]="{$serList['server_name']}({$operatorList[$serList['operator_id']]})[{$gameTypeList[$serList['game_type_id']]}]";
				}

				$this->_view->assign('roomServerList',$roomServerList);
			}

			if ($dataList){	//如果还有没设置的服务器将转换出来显示
				$serverList=array();
				foreach ($gameTypeList as $key=>$value){
					foreach ($dataList as $list){
						$list['full_name']="{$list['server_name']}({$operatorList[$list['operator_id']]})";
						if ($list['game_type_id']==$key)$serverList[$key][$list['Id']]=$list['full_name'];
					}
				}
				$this->_view->assign('serverList',$serverList);
			}
			$this->_view->assign('roomId',$_GET['Id']);
			$this->_view->assign('gameTypeList',$gameTypeList);
			$this->_view->set_tpl(array('body'=>'Group/SetServer.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 删除房间内的指定服务器
	 */
	private function _delServer(){
		if ($_POST['server_list'] && $this->_isPost()){
			$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
			$updateServer=implode(',',$_POST['server_list']);
			if ($this->_modelGameSerList->update(array('room_id'=>'0'),"Id in ({$updateServer})")){
				$this->_modelGameSerList->createToCache();
				$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('DEL_ERROR','Common'),-2);
			}
		}
	}

	/**
	 * 用户进入房间
	 */
	private function _inRoom() {
		$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
		$userClass = $this->_utilRbac->getUserClass ();
		switch ($roomClass->addUser ( $userClass )) {
			case - 3 : //此员工已经加入了别的房间
				{
					$this->_utilMsg->showMsg ( Tools::getLang('IN_ROOMERROR1',__CLASS__), - 1 );
					break;
				}
			case - 2 : //出口未打开
				{
					$this->_utilMsg->showMsg ( Tools::getLang('IN_ROOMERROR2',__CLASS__), - 1 );
					break;
				}
			case - 1 : //已经有此员工
				{
					$this->_utilMsg->showMsg ( Tools::getLang('IN_ROOMERROR3',__CLASS__), - 1 );
				}
			case 1 : //正常加入员工
				{
					$this->_utilMsg->showMsg ( false, 1, Tools::url ( 'MyTask', 'Index' ) );
					break;
				}
			default :
				{
					$this->_utilMsg->showMsg ( Tools::getLang('IN_ROOMERROR4',__CLASS__), - 2 );
				}
		}
	}

	/**
	 * 用户退出房间
	 */
	private function _outRoom(){
		$userClass=$this->_utilRbac->getUserClass();
		if ($userClass->outRoom()){
			$userClass->setUpdateInfo(1);
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg(Tools::getLang('OUT_ROOMERROR1',__CLASS__),-2,Tools::url(CONTROL,'Room'));
		}
	}

	/**
	 * 创建房间
	 */
	private function _add() {
		if ($this->_isPost ()) {
			$_POST["autoreply"]	=	serialize($_POST["autoreply"]);
			Tools::import ( 'Util_Rooms' );
			$utilRoom = new Util_Rooms ();
			$utilRoom->createRoom ( $_POST );
			$this->_utilMsg->showMsg ( Tools::getLang('CREATE_ROOMSUCCESS',__CLASS__), 1, Tools::url ( CONTROL, 'Room' ) );
		} else {
			$gameTypes=Model::getTtwoArrConvertOneArr($this->_getGlobalData('game_type'),'Id','name');
			$operatorList=Model::getTtwoArrConvertOneArr($this->_getGlobalData('operator_list'),'Id','operator_name');
			$this->_view->assign('gameTypes',$gameTypes);
			$this->_view->assign('operatorList',$operatorList);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'Group/Add.html'));
			$this->_view->display ();
		}
	}

	/**
	 * 房间初始化
	 */
	private function _initialize(){
		$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
		$roomClass=$this->_utilRooms->getRoom($_GET['Id']);
		$roomClass->setUpdateInfo(1);
		$roomClass['_entrance']=false;
		$roomClass['_exit']=false;
		$roomClass['_userClassList']=array();
		$roomClass['_roomStartEndTime']=array();
		$roomClass['_orderNum']=array();
		$this->_utilMsg->showMsg(false);
	}

	/**
	 * 重置工作量
	 */
	private function _rest(){
		$roomId=Tools::coerceInt($_GET['Id']);	//需要重置工作量的房间ID
		$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
		$data=$this->_modelWorkOrder->clearRoomOrder($roomId);
		$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
	}

}