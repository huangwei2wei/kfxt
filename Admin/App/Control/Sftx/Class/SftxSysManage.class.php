<?php
/**
 * 三分天下GM工具
 * @author php-朱磊
 *
 */
class Control_SftxSysManage extends Sftx {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;

	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;

	public function __construct(){
		$_GET['page']=$_GET['page']?$_GET['page']:1;
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['SftxSysManage_IpDel']=Tools::url(CONTROL,'IpDel',array('zp'=>'Sftx'));
		$this->_url['SftxSysManage_ResUserDel']=Tools::url(CONTROL,'ResUserDel',array('zp'=>'Sftx'));
		$this->_url['SftxSysManage_TalkUserDel']=Tools::url(CONTROL,'TalkUserDel',array('zp'=>'Sftx'));

		$this->_url['SftxSysManage_IpAdd']=Tools::url(CONTROL,'IpAdd',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
		$this->_url['SftxSysManage_ResUserAdd']=Tools::url(CONTROL,'ResUserAdd',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
		$this->_url['SftxSysManage_TalkUserAdd']=Tools::url(CONTROL,'TalkUserAdd',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));

		//服务器管理
		$this->_url ['GameSerList_Add'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'add','zp'=>'Sftx') );
		$this->_url ['GameSerList_Edit'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'edit','zp'=>'Sftx') );
		$this->_url ['GameSerList_CreateCache'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'cache','zp'=>'Sftx') );
		
		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 群发短信
	 */
	public function actionSendMsg(){
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/msg');
			$data=$this->getApi()->save($_POST['title'],$_POST['content'],$_POST['userIds']);
			$data=json_decode($data,true);
			if ($data['status']==1){
				$this->_utilMsg->showMsg('发送成功',1);
			}else {
				$this->_utilMsg->showMsg('发送失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			if ($_REQUEST['users'])$this->_view->assign('users',implode(',',$_REQUEST['users']));
			if ($_REQUEST['idList'])$this->_view->assign('users',implode(',',$_REQUEST['idList']));
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 封IP显示列表
	 */
	public function actionIpIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseIP');
			$dataList=$this->getApi()->select(intval($_GET['type']),$_GET['dataMin'],$_GET['dataMax'],intval($_GET['page']),PAGE_SIZE);
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if (count($dataList['data']['dataList']['result'])){
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['status']=$dataList['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,ACTION,array('doaction'=>'release','id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
				$this->_view->assign('optionList',$dataList['data']['optionList']);
				$this->_view->assign('selectedArr',$dataList['data']['select']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 解除封IP
	 */
	public function actionIpDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseIP');
		$data=$this->getApi()->delete($_POST['idList']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg('解除IP成功');
		}else {
			$this->_utilMsg->showMsg('解除IP失败',-2);
		}
	}

	/**
	 * 增加封IP
	 */
	public function actionIpAdd(){
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseIP');
			$data=$this->getApi()->save($_POST['ips'],$_POST['endTime']);
			$data=json_decode($data,true);
			if (is_array($data) && $data['status']==1){
				$this->_utilMsg->showMsg("封锁IP成功",1,Tools::url(CONTROL,'IpIndex',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('封锁IP失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 封号显示列表
	 */
	public function actionResUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseUser');
			$dataList=$this->getApi()->select($_GET['type'],$_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if (count($dataList['data']['dataList']['result'])){
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['status']=$dataList['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,'ResUserCoerce',array('zp'=>PACKAGE,'Id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
				$this->_view->assign('selectedArr',$dataList['data']['select']);
				$this->_view->assign('optionList',$dataList['data']['optionList']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 增加封号
	 */
	public function actionResUserAdd(){
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseUser');
			$data=$this->getApi()->save($_POST['userIds'],$_POST['endTime']);
			$data=json_decode($data,true);
			if (is_array($data) && $data['status']==1){
				$this->_utilMsg->showMsg("封号成功",1,Tools::url(CONTROL,'ResUserIndex',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('封号失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	
	

	/**
	 * 解除封号
	 */
	public function actionResUserDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseUser');
		$data=$this->getApi()->delete($_POST['idList']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg("解除封号成功");
		}else {
			$this->_utilMsg->showMsg('解除封号失败',-2);
		}
	}
	
	/**
	 * 强制解禁
	 */
	public function actionResUserCoerce(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseUser');
		$data=$this->getApi()->release($_GET['Id']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg("强制解禁成功");
		}else {
			$this->_utilMsg->showMsg('强制解禁失败',-2);
		}
	}

	/**
	 * 禁言显示列表
	 */
	public function actionTalkUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseMessage');
			$dataList=$this->getApi()->select($_GET['type'],$_GET['dataMin'],$_GET['dataMax'],$_GET['page'],PAGE_SIZE);
			if (!$dataList instanceof PHPRPC_Error){
				$dataList=json_decode($dataList,true);
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList['data']['dataList']['totalCount'],'perpage'=>PAGE_SIZE));
				if (count($dataList['data']['dataList']['result'])){
					foreach ($dataList['data']['dataList']['result'] as &$list){
						$list['status']=$dataList['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,'TalkUserCoerce',array('zp'=>'Sftx','Id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList['data']['dataList']['result']);
				$this->_view->assign('pageBox',$this->_helpPage->show());
				$this->_view->assign('optionList',$dataList['data']['optionList']);
				$this->_view->assign('selectedArr',$dataList['data']['select']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 强制解禁
	 */
	public function actionTalkUserCoerce(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseMessage');
		$data=$this->getApi()->release($_GET['Id']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg("强制解禁成功");
		}else {
			$this->_utilMsg->showMsg('强制解禁失败',-2);
		}
	}

	/**
	 * 增加禁言
	 */
	public function actionTalkUserAdd(){
		if ($this->_isPost()){
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseMessage');
			$data=$this->getApi()->save($_POST['userIds'],$_POST['endTime']);
			$data=json_decode($data,true);
			if (is_array($data) && $data['status']==1){
				$this->_utilMsg->showMsg("禁言成功",1,Tools::url(CONTROL,'TalkUserIndex',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg('禁言失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 解除禁言
	 */
	public function actionTalkUserDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'api/refuseMessage');
		$data=$this->getApi()->delete($_POST['idList']);
		$data=json_decode($data,true);
		if ($data['status']==1){
			$this->_utilMsg->showMsg("解除禁言成功");
		}else {
			$this->_utilMsg->showMsg('解除禁言失败',-2);
		}
	}
	
	public function actionServerlist(){
		$this->_modelSysconfig 		= $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelOperatorList 	= $this->_getGlobalData ( 'Model_OperatorList', 'object' );
		$this->_modelGameSerList 	= $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		switch($_GET['doaction']){
			case 'add':{
				$this->_serverlistAdd();
				return ;
			}
			case 'del':{
				$this->_serverlistDel();
				return;
			}
			case 'edit':{
				$this->_serverlistEdit();
				return;
			}
			case 'cache' :{
				$this->_serverlistcreateCache();
				return ;
			}
			default :{
				$this->_serverlistIndex();
				return ;
			}
		}
	}
	
	private function _serverlistIndex(){
		$this->_checkOperatorAct();
		$_GET['game_type']	=	self::SFTX_ID;
		$server_msg			=	$this->_modelGameSerList->getSqlSearch($_GET);
		$timer = array('0'=>'关闭','1'=>'开启',''=>'全部');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign('selectedGameTypeId',self::SFTX_ID);
		$this->_view->assign('get',$_GET);
		$this->_view->assign('pageBox', $server_msg['pageBox']);
		$this->_view->assign('zp', "Sftx");
		$this->_view->assign('timer', $timer );
		$this->_view->assign('dataList', $server_msg['serverList']);
		$this->_view->assign('operatorList',$server_msg['operatorList']);
		$this->_view->set_tpl(array('body'=>'ServerList/Index.html'));
		$this->_view->display();
	}
	
	/**
	 * 编辑服务器
	 */
	private function _serverlistedit() {
		if ($this->_isPost ()) {
			if ($this->_modelGameSerList->updateServerlist($_POST)) {
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION , array('zp'=>"sftx") ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_ERROR','Common'), - 2 );
			}
		} else {
			$data = $this->_modelGameSerList->findById ( $_GET ['Id'], false );
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );	
			$data['ext'] = unserialize($data['ext']);
			$this->_view->assign ('data', $data );
			$this->_view->assign ('operatorList', $operatorList );
			$this->_view->assign ('gameTypeList', $gameTypeList );
			$timer = array('0'=>'关闭','1'=>'开启');
			$this->_view->assign ( 'timer', $timer );
			$this->_utilMsg->createPackageNavBar();
			$this->_view->assign('game_type',self::SFTX_ID);
			$this->_view->set_tpl(array('body'=>'ServerList/Edit.html'));
			$this->_view->display ();
		}
	}
	
	/**
	 * 删除服务器
	 */
	private function _serverlistdel() {
		if ($this->_modelGameSerList->delById ( $_GET ['Id'] )) {
			$this->_utilMsg->showMsg ( Tools::getLang('DEL_SUCCESS','Common'), 1, 1 );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('DEL_ERROR','Common'), - 2 );
		}
	}
	

	/**
	 * 添加服务器
	 */
	private function _serverlistadd() {
		if ($this->_isPost ()) {
			if ($this->_modelGameSerList->addServerlist($_POST)) {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION,array('zp'=>"xianhun")) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_ERROR','Common'), - 2 );
			}
		} else {
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$this->_view->assign ( 'operatorList', $operatorList );
			$this->_view->assign ( 'gameTypeList', $gameTypeList );
			$timer = array('0'=>'关闭','1'=>'开启');
			$this->_view->assign('game_type',self::SFTX_ID);
			$this->_view->assign ( 'timer', $timer );
			$this->_utilMsg->createPackageNavBar();
			$this->_view->set_tpl(array('body'=>'ServerList/Add.html'));
			$this->_view->display ();
		}
	}
	
	private function _serverlistcreateCache() {
		if ($this->_modelGameSerList->createToCache ()) {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1 );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_ERROR','Common'), - 2 );
		}
	}



}