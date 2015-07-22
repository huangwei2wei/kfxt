<?php
/**
 * 仙魂游戏运营工具 
 * @author PHP-兴源
 *
 */
class Control_DaTangOperator extends DaTang {
	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;

	private $_modelGamePlayerLogTpl;

	private $paramsStr = '';

	Const DaTang_ITEMS_CACHE_NAME = 'xian_hun_items_list';

	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');

		$this->_createView();
		$this->_createUrl();
	}


	private function _createUrl(){


		$this->url['DaTangOperator_Announcement'] = Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE));

		$this->_url ['GameSerList_Add'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'add','zp'=>self::PACKAGE) );
		$this->_url ['GameSerList_Edit'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'edit','zp'=>self::PACKAGE) );
		$this->_url ['GameSerList_CreateCache'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'cache','zp'=>self::PACKAGE) );

		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 单服公告
	 */
	public function actionAnnouncement(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_announcementAdd();
				return ;
			}
			case 'del':{
				$this->_announcementDel();
				return;
			}
			case 'edit':{
				$this->_announcementEdit();
				return;
			}
			default :{
				$this->_announcementIndex();
				return ;
			}
		}
	}

	/**
	 * 单服公告列表
	 */
	private function _announcementIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$game=$this->_getGlobalData('Game_'.self::GAME_ID,'object');
			$post['title'] = trim($_GET['title']);
			$post['context'] = trim($_GET['content']);
			$post['pageIndex'] = max(1,intval($_GET['page']));
			$post['pageSize'] = PAGE_SIZE;
			$post['server_id']	=	$_REQUEST['server_id'];
			$dataList=$game->getNotice($post);
			//"$dataList"	Array [2]
			//	count	(int) 8
			//	wlist	Array [8]
			//		0	Array [7]
			//			id	(int) 6
			//			title	(string:6) ew3232
			//			context	(string:5) 33323
			//			interval	(int) 30
			//			url	(string:20) http://www.baidu.com
			//			startTime	(string:19) 2011-07-20 17:10:43
			//			endTime	(string:19) 2011-07-21 17:10:44
			//		1	Array [7]
			//		2	Array [7]
			//		3	Array [7]
			//		4	Array [7]
			//		5	Array [7]
			//		6	Array [7]
			//		7	Array [7]


			$URL_AnnouncementAdd = Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementDel = Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'doaction'=>'del','server_id'=>$_REQUEST['server_id']));

			$this->_view->assign('URL_AnnouncementAdd',$URL_AnnouncementAdd);
			$this->_view->assign('URL_AnnouncementDel',$URL_AnnouncementDel);

			if($dataList && is_array($dataList)){
				foreach($dataList['wlist'] as &$sub){
					$sub['URL_Edit'] =  Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'doaction'=>'edit','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
					$sub['URL_Del'] =  Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'doaction'=>'del','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList['wlist']);
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$dataList['total'],'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}
		}
		$this->_view->assign('selected',$_GET);
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 单服公告添加
	 */
	private function _announcementAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$post['startTime'] = trim($_POST['startTime']);
			$post['endTime'] = trim($_POST['endTime']);
			if(!strtotime($post['startTime']) || !strtotime($post['endTime'])){
				$this->_utilMsg->showMsg('时间格式有误',-1);
			}
			$post['title'] = trim($_POST['title']);
			$post['context'] = trim($_POST['content']);
			$post['interval'] = intval($_POST['interval']);
			$post['url'] = trim($_POST['url']);
			$dataList=$this->getResult($_REQUEST['server_id'],'server/addpost',array(),$post);
			if(is_array($dataList) && $dataList['status'] == 1){
				$JumpUrl = Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
				$this->_utilMsg->showMsg('操作成功',1,$JumpUrl,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/AnnouncementEdit.html'));
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 单服公告编辑
	 */
	private function _announcementEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		//从列表中获取数据
		$_GET['id'] = intval($_GET['id']);
		if(!$_GET['id']){
			$this->_utilMsg->showMsg('Error Id');
		}
		if ($this->_isPost() && $_REQUEST['server_id']){
			$post['id']			= $_POST['id'];
			$post['title'] 		= trim($_POST['title']);
			$post['context'] 	= trim($_POST['content']);
			$post['startTime'] 	= trim($_POST['startTime']);
			$post['endTime'] 	= trim($_POST['endTime']);
			$post['interval'] 	= intval($_POST['interval']);
			$post['url'] 		= trim($_POST['url']);
			$dataList=$this->getResult($_REQUEST['server_id'],'server/updatepost',array(),$post);
			if($dataList){
				$JumpUrl = Tools::url(CONTROL,'Announcement',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id']));
				$this->_utilMsg->showMsg('操作成功',1,$JumpUrl,1);				
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$dataList=$this->getResult($_REQUEST['server_id'],'server/getpost',array(),array('id'=>$_GET['id']));
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('selected',$dataList);
			}
			$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/AnnouncementEdit.html'));
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 单服公告删除
	 */
	private function _announcementDel(){
		$post['ids'] = '';
		$_GET['id'] = trim($_GET['id']);
		if($_GET['id']!==''){
			$post['ids'] = $_GET['id'];
		}elseif($_POST['ids']){
			$post['ids'] = implode(',',$_POST['ids']);
		}else{
			$this->_utilMsg->showMsg('没有选择');
		}
		$dataList=$this->getResult($_REQUEST['server_id'],'server/removepost',array(),$post);
		//"$dataList"	Array [3]
		//	0	Array [1]
		//		1	(boolean) true
		//	1	Array [1]
		//	2	Array [1]
		if($dataList){
			$this->_utilMsg->showMsg('操作成功',1,1,1);				
		}else{
			$this->_utilMsg->showMsg('操作失败',-1);
		}
	}

	/**
	 * 全服发邮件
	 */
	public function actionSendMailToAll(){
		$this->_multiOperatorSelect();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			if($_POST['title'] == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}
			$get = null;
			$post['title'] = $_POST['title'];
			$post['content'] = $_POST['content'];
			$dataList = $this->getResult($_REQUEST['server_id'],'mail/mailtoall',$get,$post);

			if($this->_isAjax()){
				if($dataList['status'] == 1){
					$sendMailCount = intval($dataList['data']);
					$this->_returnAjaxJson(array('status'=>1,'data'=>$sendMailCount,'info'=>"共发{$sendMailCount}"));
				}
				$this->_returnAjaxJson(array('status'=>0,'info'=>$dataList['info']));
			}else{
				if($dataList['status'] == 1){
					$this->_utilMsg->showMsg('操作成功,共发邮件数：'.intval($dataList['data']),1,1,false);
				}
				$this->_utilMsg->showMsg('操作失败<br/>'.$dataList['info'],-1);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 全服大喇叭
	 */
	public function actionSpeakToAll(){
		$this->_multiOperatorSelect();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['message']) == ''){
				$this->_utilMsg->showMsg('消息内容不能为空',-1);
			}
			$get = null;
			$post['message'] = $_POST['message'];
			$dataList = $this->getResult($_REQUEST['server_id'],'mail/speaktoall',$get,$post);
			if($this->_isAjax()){
				if($dataList['status'] == 1){
					$sendMailCount = intval($dataList['data']);
					$this->_returnAjaxJson(array('status'=>1,'info'=>'操作成功'));
				}
				$this->_returnAjaxJson(array('status'=>0,'info'=>$dataList['info']));
			}else{
				if($dataList['status'] == 1){
					$this->_utilMsg->showMsg('操作成功',1,1,false);
				}else{
					$this->_utilMsg->showMsg('操作失败<br/>'.$dataList['info'],-1);
				}
			}

		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 单服屏蔽字管理
	 */
	public function actionFilteredWords(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if($this->_isPost() && $_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->saveKeywords($_POST['FilteredWords']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}elseif ($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getKeywords();
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('searchType',$this->_searchUserType);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**********************************************************************/
	/*服务器添加修改*/
	/**********************************************************************/
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
		$_GET['game_type']	=	self::GAME_ID;
		$server_msg			=	$this->_modelGameSerList->getSqlSearch($_GET);
		$timer = array('0'=>'关闭','1'=>'开启',''=>'全部');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign('selectedGameTypeId',self::GAME_ID);
		$this->_view->assign('get',$_GET);
		$this->_view->assign('pageBox', $server_msg['pageBox']);
		$this->_view->assign('zp', self::PACKAGE);
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
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION , array('zp'=>self::PACKAGE) ) );
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
			$this->_view->assign('game_type',self::GAME_ID);
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
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION,array('zp'=>self::PACKAGE)) );
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
			$this->_view->assign('game_type',self::GAME_ID);
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

	//同步公告
	public function actionSynchronous(){
		if ($this->_isAjax()){
			$post['startTime'] = trim($_POST['begin']);
			$post['endTime'] = trim($_POST['end']);
			$post['title'] = trim($_POST['title']);
			$post['context'] = trim($_POST['content']);
			$post['interval'] = intval($_POST['interval']);
			$post['url'] = trim($_POST['url']);
			$dataList=$this->getResult($_REQUEST['server_id'],'server/addpost',array(),$post);
			if(is_array($dataList) && $dataList['status'] == 1){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>'发送成功'));
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'发送失败'));
			}
		}else {
			$this->_checkOperatorAct();	//检测服务器
			$this->_createMultiServerList();
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}

	}

	public function actionAllNotice(){
		switch($_GET['doaction']){
			case 'ajax':{
				$this->_ajaxallnotice();
			}
			case 'del':{
				$this->_delallnotice();
			}
			default :{
				$this->_allnoticeIndex();
				return ;
			}
		}
	}

	private function _delallnotice(){
		$model	=	$model=$this->_getGlobalData('Model_Notice','object');
		if($_POST['ids']){
			foreach($_POST['ids'] as $_msg){
				if(!$model->deleteNotice($_msg,self::GAME_ID)){
					$error	.=	'{id:'.$_msg.'}';
				}
			}
			if(empty($error)){
				$this->_utilMsg->showMsg('删除成功',1,1,1);
			}else{
				$this->_utilMsg->showMsg($error."删除失败",-1);
			}
		}
	}

	private function _ajaxallnotice(){
		$data	=	array(
			'server_id'	=>	$_POST['server_id'],
			'game_type'	=>	self::GAME_ID,
			'post'		=>	array(
				'title' 	=> null,
				'context' 	=> null,
				'pageIndex' => 1,
				'pageSize' 	=> 10000,
				'server_id'	=>	$_POST['server_id']
		),
		);
		$model=$this->_getGlobalData('Model_Notice','object');
		if($model->crawlNotice($data)){
			$this->_returnAjaxJson(array('status'=>1,'msg'=>'抓取成功'));
		}else{
			$this->_returnAjaxJson(array('status'=>0,'msg'=>'抓取失败'));
		}

	}

	private function _allnoticeIndex(){
		$this->_checkOperatorAct();	//检测服务器
		$this->_createMultiServerList();
		$senddata	=	array(
			'game_type'	=>	self::GAME_ID,
			'page'		=>  $_GET['page']?$_GET['page']:'1',
			'zp'		=>	self::PACKAGE,
		);
		if($this->_isPost ()){
			if($_POST['start_time']){
				$senddata['start_time']	=	$_POST['start_time'];
			}

			if($_POST['end_time']){
				$senddata['end_time']	=	$_POST['end_time'];
			}
			if($_POST['content']){
				$senddata['content']	=	$_POST['content'];
			}
		}
		$model=$this->_getGlobalData('Model_Notice','object');
		$data	=	$model->getNoticelist($senddata);

		if($data['dataList']){
			$this->_view->assign ( 'datalist', $data['dataList']);
			$this->_view->assign ( 'pageBox', $data['pageBox']);
		}
		$this->_view->assign ('post',$_POST);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign ( 'ajax', Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'ajax')));
		$this->_view->assign ( 'del', Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'del')));
		$this->_view->set_tpl(array('body'=>'Notice/AllNotice.html'));
		$this->_view->display();
	}

	public function actionTest(){
		$model=$this->_getGlobalData('Model_Notice','object');
		if($model->_createCache('7')){
			echo "成功";
		}
	}
	/**
	 * 运营商权限管理
	 */
	public function actionUserSetup(){
		$this->UserSetup();
	}

	/**
	 * 充值统计
	 */
	public function actionRechargeStat(){
		switch ($_REQUEST['doaction']){
			case 'stat':
				$this->_rechargeStat();
				return;
			default:
				$this->_rechargeStatIndex();
		}
	}

	private function _rechargeStat(){
		$returnData = array(
			'status'=>0,
			'data'=>null,
			'info'=>'操作失败',
		);
		//		$returnData = array(
		//			'status'=>1,
		//			'data'=>array(
		//				'totalMoney'=>10.1,
		//				'totalIngot'=>10.2,
		//				'remainedIngot'=>10.02,
		//			),
		//			'info'=>'操作失败',
		//		);
		//		$this->_returnAjaxJson($returnData);

		if(!$_REQUEST['server_id']){
			$returnData['info']= '服务器异常';
			$this->_returnAjaxJson($returnData);
		}
		$post['startTime'] = trim($_POST['startTime']);
		$post['endTime'] = trim($_POST['endTime']);
		if(!strtotime($post['startTime']) || !strtotime($post['endTime'])){
			$returnData['info']= '时间范围错误';
			$this->_returnAjaxJson($returnData);
		}
		$dataList=$this->getResult($_REQUEST['server_id'],'user/payStatistics',array(),$post);
		if($dataList && is_array($dataList)){
			$returnData = array(
				'status'=>1,
				'data'=>array(
					'totalMoney'=>$dataList['totalMoney'],
					'totalIngot'=>$dataList['totalIngot'],
					'remainedIngot'=>$dataList['remainedIngot'],
			),
				'info'=>'操作成功',
			);
			$this->_returnAjaxJson($returnData);
		}
		$this->_returnAjaxJson($returnData);
	}

	private function _rechargeStatIndex(){
		$this->_multiOperatorSelect();
		$this->_view->assign('URL_stat',Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'stat')));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 活动配置
	 */
	public function actionActivity(){
		switch($_REQUEST['doaction']){
			case 'edit':
				$this->_activityEdit();
				return;
			case 'index':
			default:
				$this->_activityIndex();
				return;
		}
	}
	/**
	 * 配置接口
	 */
	public function actionInterface(){
		switch($_REQUEST['doaction']){
			case 'edit':
				$this->_interfaceEdit();
				return;
			case 'index':
			default:
				$this->_interfaceIndex();
				return;
		}
	}
	/**
	 * 编辑活动某一配置
	 */
	private function _activityEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		// 		print_r($_REQUEST);exit;
		if ($_REQUEST['server_id'] && $_REQUEST['do']=='edit'){
			unset($_POST['sbm']);
			unset($_POST['check_dj']);
			unset($_POST['boogs']);
			$_POST['params'] = json_encode($_POST['params']);

			$data=$this->getResult($_REQUEST['server_id'],'activity/update',array(),$_POST);

			if($data['status'] == 1){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
						array('操作','<font style="color:#F00">活动配置</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
			
					
					$userInfo['UserId'] = 0;
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,9,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
				#------->>>记录游戏后台新操作日志-------#
				
				echo json_encode(array('code'=>1,'msg'=>'修改成功'));
				return;
				// 				$jumpUrl = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id']));
				// 				$this->_utilMsg->showMsg('操作成功',1,$jumpUrl);
			}else{
				echo json_encode(array('code'=>0,'msg'=>'修改失败'));
				return;
			}
			// 			$this->_utilMsg->showMsg('操作失败:'.$data,-1,1,false);
		}elseif ($_REQUEST['server_id']){
			$allData = $this->_activityIndex(true,2);
			if(!is_array($allData)){
				$this->_utilMsg->showMsg('服务器出错:'.$allData,-1,1,false);
			}
			$data = array();
			$id = intval($_REQUEST['id']);
			foreach($allData as $sub){
				if($sub['id'] == $id){
					$sub['arrlv'] = $this->getArrLv($sub['params']); //获取params变量 数组维
					$data = $sub;
					break;
				}
			}
			if(empty($data)){
				$this->_utilMsg->showMsg("id:{$id}的数据不存在",-1);
			}
			$Goods = $this->_itemsReCache();//获取道具
			$this->_view->assign('Goods',$Goods);
			// 			print_r($data);
			$this->_view->assign('data',$data);
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/ActivityEdit.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 活动配置列表
	 * @param boolean $_getData 是否被其他接口直接调用获得数据 $_serverListSel 1 单选 2 多选
	 */
	private function _activityIndex($_getData=false,$_serverListSel=1){
		$this->_checkOperatorAct();
		if($_serverListSel == 1){
			$this->_createServerList();
		}elseif($_serverListSel == 2){
			$this->_createMultiServerList();
		}
		if ($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'activity/getlist');
			if($_getData){
				return $dataList;
			}
			if(is_array($dataList)){
				foreach($dataList as &$sub){
					$sub['URL_edit'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit','id'=>$sub['id']));
					if(is_array($sub['params'])){
						$this->paramsStr = '';
						$sub['params'] = $this->_array_multi2single($sub['params']);
					}
				}
				$this->_view->assign('dataList',$dataList);
			}else{
				$this->_view->assign('errorInfo',$dataList);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 接口配置列表
	 * @param boolean $_getData 是否被其他接口直接调用获得数据 $_serverListSel 1 单选 2 多选
	 */
	private function _interfaceIndex($_getData=false,$_serverListSel=1){
		$this->_checkOperatorAct();
		if($_serverListSel == 1){
			$this->_createServerList();
		}elseif($_serverListSel == 2){
			$this->_createMultiServerList();
		}
		if ($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'configure/getConfigureList');
// print_r($dataList);
			foreach ($dataList as &$v){
				$v['content'] = json_encode($v);
			}


			if($_getData){
				return $dataList;
			}
			if(is_array($dataList)){
				foreach($dataList as &$sub){
					$sub['URL_edit'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit','id'=>$sub['id']));
					if(is_array($sub['params'])){
						$this->paramsStr = '';
						$sub['params'] = $this->_array_multi2single($sub['params']);
					}
				}
				$this->_view->assign('dataList',$dataList);
			}else{
				$this->_view->assign('errorInfo',$dataList);
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 编辑接口某一配置
	 */
	private function _interfaceEdit(){
		$this->_checkOperatorAct();
		// 		print_r($_REQUEST);//exit;
		if ($_REQUEST['server_id'] && $_REQUEST['do']=='edit'){
			$this->_createServerList();
			$sid = $_REQUEST['server_id'];
			unset($_POST['do']);
			unset($_POST['server_id']);
			$data=$this->getResult($sid,'configure/update',array(),$_POST);

			if($data['status'] == 1){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
						array('操作','<font style="color:#F00">配置接口</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				$userInfo['UserId'] = 0;
				$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,10,$_REQUEST['server_id'],$AddLog);
				if(false !== $GameOperateLog){
					$this->_modelGameOperateLog->add($GameOperateLog);
				}
				#------->>>记录游戏后台新操作日志-------#
				echo json_encode(array('code'=>1,'msg'=>'修改成功'));
				return;
			}else{
				echo json_encode(array('code'=>0,'msg'=>'修改失败'));
				return;
			}
		}elseif ($_REQUEST['server_id']){
			$allData = $this->_interfaceIndex(true,2);
			if(!is_array($allData)){
				$this->_utilMsg->showMsg('服务器出错:'.$allData,-1,1,false);
			}
			$data = array();
			$id = intval($_REQUEST['id']);
			foreach($allData as $sub){
				if($sub['id'] == $id){
					$sub['arrlv'] = $this->getArrLv($sub['params']); //获取params变量 数组维
					$data = $sub;
					break;
				}
			}
			if(empty($data)){
				$this->_utilMsg->showMsg("id:{$id}的数据不存在",-1);
			}
			$Goods = $this->_itemsReCache();//获取道具
			$this->_view->assign('Goods',$Goods);
			$this->_view->assign('data',$data);
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/interfaceEdit.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 游戏接口
	 */
	public function actionInvokeGMRemoteInf(){
		$this->_checkOperatorAct();
		$this->_createMultiServerList();
		// 		print_r($_REQUEST);exit;
		if ($_REQUEST['server_id'] && $_REQUEST['do']=='edit'){
			$sid = $_REQUEST['server_id'];
			unset($_POST['do']);
			unset($_POST['server_id']);
			$data=$this->getResult($sid,'configure/invokeGMRemoteInf',array(),$_POST);

			if($data['status'] == 1){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
						array('操作','<font style="color:#F00">游戏接口</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				$userInfo['UserId'] = 0;
				$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,11,$_REQUEST['server_id'],$AddLog);
				if(false !== $GameOperateLog){
					$this->_modelGameOperateLog->add($GameOperateLog);
				}
				#------->>>记录游戏后台新操作日志-------#
				echo json_encode(array('code'=>1,'msg'=>'修改成功'));
				return;
			}else{
				echo json_encode(array('code'=>0,'msg'=>'修改失败'));
				return;
			}
		}
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/invokeGMRemoteInf.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/**
	 * 返回的  params  变量处理
	 * @author doter
	 * @param  数组
	 */
	function _array_multi2single($array){
		foreach($array as $k => $v){
			if(is_array($v)){
				$this->_array_multi2single($v);
			}else{
				$this->paramsStr .= $k .':'.$v.'</br>';
			}
		}
		return $this->paramsStr ;
	}
	/**
	 * 计算数组维数
	 * @author doter
	 * @param 数组
	 */
	function getArrLv($arr) {
		if (is_array($arr)) {
			function AWRSetNull(&$val) {
				$val = NULL;
			}
			#递归将所有值置NULL，目的1、消除虚构层如array("array(\n  ()")，2、print_r 输出轻松点，
			array_walk_recursive($arr, 'AWRSetNull');
			$ma = array();
			#从行首匹配[空白]至第一个左括号，要使用多行开关'm'
			preg_match_all("'^\(|^\s+\('m", print_r($arr, true), $ma);
			#回调转字符串长度
			//$arr_size = array_map('strlen', current($ma));
			#取出最大长度并减掉左括号占用的一位长度
			//$max_size = max($arr_size) - 1;
			#数组层间距以 8 个空格列，这里要加 1 个是因为 print_r 打印的第一层左括号在行首
			//return $max_size / 8 + 1;
			return (max(array_map('strlen', current($ma))) - 1) / 8 + 1;
		} else {
			return 0;
		}
	}
	public function actionOnlinePlayer(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'server/getonline');
			$this->_view->assign('online',$dataList["online"]);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionGoldCount(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'user/getStatistics',array(),$_POST);
			$this->_view->assign('GoldCount',$dataList);
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionServerStop(){
		// 		print_r($_POST);
		// 		print_r($_GET);
		// 		exit;
		switch($_POST['Whatfunction']){
			case 'stopserver':{
				$this->_stopserver();
				return ;
			}
			case 'addgm':{
				$this->_addgm();
				return ;
			}
			case 'delgm':{
				$this->_delgm();
				return ;
			}
			default :{
				$this->_serverstopindex();
				return ;
			}
		}
	}

	private function _stopserver(){
		$gameServerList=$this->_getGlobalData('gameser_list');
		$url = $gameServerList[$_REQUEST['server_id']]['server_url'];
		$data=$this->getResult($url,'game/stopserver',array(),array());
		if($data['status'] == 1){
			#-------<<<记录游戏后台新操作日志-------#
			$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
			$AddLog = array(
					array('操作','<font style="color:#F00">服务器停服</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
			);
			$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
			$userInfo['UserId'] = 0;
			$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,12,$_REQUEST['server_id'],$AddLog);
			if(false !== $GameOperateLog){
				$this->_modelGameOperateLog->add($GameOperateLog);
			}
			#------->>>记录游戏后台新操作日志-------#
			// 			$jumpUrl = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id']));
			// 			$this->_utilMsg->showMsg('操作成功',1,$jumpUrl);
			echo json_encode(array('code'=>1,'msg'=>'停服成功'));
		}else{
			// 			$this->_utilMsg->showMsg('操作失败:',-1,1,false);
			echo json_encode(array('code'=>0,'msg'=>'停服失败'));
		}
	}

	private function _addgm(){
		$gameServerList=$this->_getGlobalData('gameser_list');
		$url = $gameServerList[$_REQUEST['server_id']]['server_url'];
		$get["playerId"]	=	$_POST["userid"];
		$data=$this->getResult($url,'game/addplayeridtotesters',$get,array());
		if($data['status'] == 1){
			echo json_encode(array('code'=>1,'msg'=>'添加成功'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'添加失败'));
		}
	}

	private function _delgm(){
		$gameServerList=$this->_getGlobalData('gameser_list');
		$url = $gameServerList[$_REQUEST['server_id']]['server_url'];
		$get["playerId"]	=	$_POST["userid"];
		$data=$this->getResult($url,'game/deleteplayeridtotesters',$get,array());
		if($data['status'] == 1){
			echo json_encode(array('code'=>1,'msg'=>'删除成功'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'删除失败'));
		}
	}
	private function _serverstopindex(){
		$this->_checkOperatorAct();
		// 		$this->_createServerList();
		$this->_createMultiServerList();
		$this->_utilMsg->createPackageNavBar();
		$ajaxUrl = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE));
		$this->_view->assign('ajaxUrl',$ajaxUrl);
		$this->_view->display();
	}

	private function _itemsReCache($effectiveTime=86400){
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$items = $this->_f(self::PACKAGE.'_Items','',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($items){
				return $items;
			}
			//if(true || in_array($_REQUEST['server_id'],$this->_newServers) ){	//已经更新的服
			$dataList = $this->getResult($_REQUEST['server_id'],'game/itemlist',array(),array());
			$items = array(
			1=>array('Name'=>'装备'),
			2=>array('Name'=>'道具'),
			3=>array('Name'=>'宝石'),
			);
			if($dataList && is_array($dataList)){
				foreach($dataList as $k => $val){
					$goodTypeId = $val['itemType'];
					$items[$goodTypeId]['Item'][$val['itemId']] = $val['itemName'];
				}
			}
			//			}else{	//使用旧接口的服
			//				$dataList = $this->getResult($_REQUEST['server_id'],'game/itemmap',array(),array());
			//				$items = array();
			//				if($dataList && is_array($dataList)){
			//					$items[0]['Name'] = '全部道具';
			//					foreach($dataList as $k => $val){
			//						$items[0]['Item'][$k] = $val;
			//					}
			//				}
			//			}
			$this->_f(self::PACKAGE.'_Items',$items);	//缓存数据数据
			return $items;
		}else{
			return false;
		}
	}

	public function actionActivityOpen(){
		switch($_REQUEST['doaction']){
			case 'edit':
				$this->_ActivityOpenCloseEdit();
				return;
			default:
				$this->_ActivityOpenCloseIndex();
				return;
		}
	}
	
	public function _ActivityOpenCloseIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$dataList=$this->getResult($_REQUEST['server_id'],'configure/getByOption',Array("pageIndex"=>$_GET["page"],"controllerName"=>$_GET["controllerName"],"methodName"=>$_GET["methodName"]));
			 
			if(is_array($dataList)){
				foreach($dataList["item"] as &$sub){
					$sub['URL_edit'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'edit','controllerName'=>$sub["controllerName"],'methodName'=>$sub["methodName"]));
// 					$sub['URL_Open'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'Open','controllerName'=>$sub["controllerName"],'methodName'=>$sub["methodName"]));
// 					$sub['URL_Close'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'server_id'=>$_REQUEST['server_id'],'doaction'=>'Close','controllerName'=>$sub["controllerName"],'methodName'=>$sub["methodName"]));
				}
				$this->_loadCore('Help_Page');
				$helpPage=new Help_Page(array('total'=>$dataList['page']*20,'perpage'=>PAGE_SIZE));
	
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('dataList',$dataList["item"]);
			}else{
				$this->_view->assign('errorInfo',$dataList);
			}
		}
		$this->_view->assign('selected',$_GET);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	
	public function _ActivityOpenCloseEdit(){
		$this->_checkOperatorAct();
		$this->_createMultiServerList();
		
		switch($_REQUEST['do']){
			case 'Open':
				$get["isOpen"]=0;
				$get['controllerName'] = $_GET['controllerName'];
				$get['methodName'] = $_GET['methodName'];
				print_r($get);
				$dataList = $this->getResult($_REQUEST['server_id'],'configure/updateControllerMethodsObject',$get);
				var_dump($dataList);exit;
				if($dataList["status"]=="1"){
					#-------<<<记录游戏后台新操作日志-------#
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
							array('操作','<font style="color:#F00">活动开关-open</font>'),
							array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
							array('操作人','<b>{UserName}</b>'),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					$userInfo['UserId'] = 0;
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,13,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
					#------->>>记录游戏后台新操作日志-------#
// 					$this->_utilMsg->showMsg('操作成功',1,1,1);
					$this->_returnAjaxJson(array('status'=>1,'msg'=>"成功"));
				}else{
					$this->_returnAjaxJson(array('status'=>0,'msg'=>"失败"));
// 					$this->_utilMsg->showMsg('操作失败',-1);
				}
				return;
				case 'Close':
				$get["isOpen"]=1;
				$get['controllerName'] = $_GET['controllerName'];
				$get['methodName'] = $_GET['methodName'];
				$dataList = $this->getResult($_REQUEST['server_id'],'configure/updateControllerMethodsObject',$get);
				if($dataList["status"]=="1"){
				#-------<<<记录游戏后台新操作日志-------#
						$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
							array('操作','<font style="color:#F00">活动开关-Close</font>'),
							array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
							array('操作人','<b>{UserName}</b>'),
											);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
							$userInfo['UserId'] = 0;
							$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,13,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
							$this->_modelGameOperateLog->add($GameOperateLog);
					}
				#------->>>记录游戏后台新操作日志-------#
// 				$this->_utilMsg->showMsg('操作成功',1,1,1);
				$this->_returnAjaxJson(array('status'=>1,'msg'=>"成功"));
				}else{
// 				$this->_utilMsg->showMsg('操作失败',-1);
					$this->_returnAjaxJson(array('status'=>0,'msg'=>"失败"));
				}
				return;
		}
		$this->_view->assign('closeOrOpen',array(0=>'开启',1=>'关闭'));
		$this->_view->set_tpl(array('body'=>'DaTang/DaTangOperator/ActivityOpenCloseEdit.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
}