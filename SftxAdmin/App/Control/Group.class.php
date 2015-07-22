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

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilRooms = $this->_getGlobalData ( 'Util_Rooms', 'object' );
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_utilRbac = $this->_getGlobalData ( 'Util_Rbac', 'object' );
	}

	private function _createUrl() {
		$this->_url ['Group_Add'] = Tools::url ( CONTROL, 'RoomSetup',array('doaction'=>'add') );
		$this->_url ['Group_Edit'] = Tools::url ( CONTROL, 'RoomSetup',array('doaction'=>'edit') );
		$this->_url ['Group_SetServer']=Tools::url(CONTROL,'RoomSetup',array('doaction'=>'setServer'));
		$this->_url ['Group_DelServerToRoom']=Tools::url(CONTROL,'RoomSetup',array('doaction'=>'delServer'));
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
			default:{
				$this->_index();
				return ;
			}
		}
	}
	
	/**
	 * 任务分区管理
	 */
	public function actionRoomSetup(){
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
			case 'setServer' :{//设置服务器
				$this->_setServer();
				return ;
			}
			case 'delServer' :{//删除服务器
				$this->_delServer();
				return ;
			}
			case 'initialize' :{//初始化
				$this->_initialize();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}

	private function _index() {
		$roomList = $this->_utilRooms->findAllRooms ();
		if ($roomList){
			$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
			$userClass=$this->_utilRbac->getUserClass();
			foreach ( $roomList as &$value ) {
				$value ['server_list'] = $this->_modelGameSerList->findByRoomUserList($value['_id'],$userClass);
				$value ['url_edit_server'] = Tools::url(CONTROL,'RoomSetup',array('Id'=>$value['_id'],'doaction'=>'setServer'));
				$value ['url_edit'] = Tools::url ( CONTROL, 'RoomSetup', array ('Id' => $value ['_id'],'doaction'=>'edit' ) );
				$value ['url_del'] = Tools::url ( CONTROL, 'RoomSetup', array ('Id' => $value ['_id'],'doaction'=>'del' ) );
				$value ['url_Initialize']=Tools::url(CONTROL,'RoomSetup',array('Id'=>$value['_id'],'doaction'=>'initialize'));
				$value ['url_monitor'] = Tools::url ( CONTROL, 'RoomSetup', array ('Id' => $value ['_id'],'doaction'=>'monitor' ) );
				$value ['word_entrance'] = $value ['_entrance'] ? '开启' : '关闭';
				$value ['word_exit'] = $value ['_exit'] ? '开启' : '关闭';
				$value ['word_workOrderStatus'] = $value ['_workOrderStatus'] ? '开启' : '关闭';
				$value ['userClassNum'] = count ( $value ['_userClassList'] );
				if ($userClass['_roomId']==$value['_id']){
					$value['in_room']='<a style="color:#F00" href="'.Tools::url(CONTROL,'Room',array('doaction'=>'outRoom')).'">退出回复专区</a>';
				}else {
					$value['in_room']='<a href="'.Tools::url(CONTROL,'Room',array('Id'=>$value['_id'],'doaction'=>'inRoom')).'">进入回复专区</a>';
				}
			}
			$this->_view->assign ( 'dataList', $roomList );
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Group/Index.html'));
		$this->_view->display ();
	}

	private function _del() {
		$this->_utilRooms->delRoom ( $_GET ['Id'] );
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$this->_modelGameSerList->update(array('room_id'=>0),"room_id={$_GET['Id']}");	//所有属于这个房间的服务器都置0
		$this->_utilMsg->showMsg ( '删除房间成功', 1 );
	}

	private function _edit() {
		if ($this->_isPost ()) {
			$roomClass = $this->_utilRooms->getRoom ( $_POST ['Id'] );
			$roomClass ['_roomName'] = $_POST ['name'];
			$roomClass->setUpdateInfo ( 1 );
			$this->_utilMsg->showMsg ( '更新成功', 1 );
		} else {
			$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
			$this->_view->assign ( 'roomClass', $roomClass );
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
				$userClass ['online'] = '在线';
			} else {
				$userClass ['online'] = '离线';
			}
			$userClass['cur_order_num']=$userClass->getOrderNum();	//当天的工单数
			$userClass['url_edit_operator']=Tools::url('User','User',array('Id'=>$userClass['_id'],'doaction'=>'managerOperator'));
			$userClass['url_kill_user']=Tools::url(CONTROL,ACTION,array('user_name'=>$userClass['_userName'],'room_id'=>$_GET['Id'],'doaction'=>'kickUser'));
		}
		$this->_view->assign('serverList',$serverList);
		$this->_view->assign ( 'dataList', $roomUsers );
		$this->_view->assign ( 'roomClass', $roomClass );
		$this->_url ['WorkOrder_RoomOnOff'] = Tools::url ( CONTROL, ACTION, array ('Id' => $_GET ['Id'],'doaction'=>'onOff' ) );
		$this->_url ['WorkOrder_KickAllUser'] = Tools::url ( CONTROL, ACTION, array ('Id' => $_GET ['Id'],'doaction'=>'kickAllUser' ) );
		$this->_view->assign ( 'url', $this->_url );
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'Group/Monitor.html'));
		$this->_view->display ();
	}

	/**
	 * 清除单个用户退出房间
	 */
	private function _kickUser() {
		if (!$_GET['user_name'])$this->_utilMsg->showMsg('请选择你要踢出的用户',-1);
		if (!$_GET['room_id'])$this->_utilMsg->showMsg('请选择房间',-1);
		$userName = $_GET ['user_name'] ;
		$userClass=$this->_utilRbac->getUserClass($userName);
		$roomClass = $this->_utilRooms->getRoom ( $_GET['room_id'] );
		if ($roomClass->kickUser ( $userClass )){
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('踢出用户失败',-2);
		}
		
	}

	/**
	 * 清除所有用户退出房间
	 */
	private function _kickAllUser() {
		$roomClass = $this->_utilRooms->getRoom ( $_GET ['Id'] );
		$roomClass->kickAllUser ();
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
				$this->_utilMsg->showMsg('更改房间列表成功');
			}else {
				$this->_utilMsg->showMsg('更改房间列表失败',-2);
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
				$this->_utilMsg->showMsg('删除指定服务器列表成功');
			}else {
				$this->_utilMsg->showMsg('删除指定服务器列表失败.',-2);
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
					$this->_utilMsg->showMsg ( '您已经加入了别的房间', - 1 );
					break;
				}
			case - 2 : //出口未打开
				{
					$this->_utilMsg->showMsg ( '对不起,此房间入口未打开', - 1 );
					break;
				}
			case - 1 : //已经有此员工
				{
					$this->_utilMsg->showMsg ( '对不起,您已经加入此房间', - 1 );
				}
			case 1 : //正常加入员工
				{
					$this->_utilMsg->showMsg ( false, 1, Tools::url ( 'MyTask', 'Index' ) );
					break;
				}
			default :
				{
					$this->_utilMsg->showMsg ( '加入房间失败', - 2 );
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
			$this->_utilMsg->showMsg('退出回复专区失败,或者房间关闭了出口',-2,Tools::url(CONTROL,'Room'));
		}
	}

	/**
	 * 创建房间
	 */
	private function _add() {
		if ($this->_isPost ()) {
			Tools::import ( 'Util_Rooms' );
			$utilRoom = new Util_Rooms ();
			$utilRoom->createRoom ( $_POST );
			$this->_utilMsg->showMsg ( '创建房间成功', 1, Tools::url ( CONTROL, 'Room' ) );
		} else {
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

}