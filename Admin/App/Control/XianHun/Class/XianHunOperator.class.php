<?php
/**
 * 仙魂游戏运营工具 
 * @author PHP-兴源
 *
 */
class Control_XianHunOperator extends XianHun {
	/**
	 * Help_Page
	 * @var Help_Page
	 */
	private $_helpPage;
	
	private $_modelGamePlayerLogTpl;
	
	Const DaTang_ITEMS_CACHE_NAME = 'xian_hun_items_list';

	public function __construct(){
		parent::__construct();
		//$_GET['page']=$_GET['page']?$_GET['page']:1;
	}
	
	protected function _createUrl(){
		$this->_url ['GameSerList_Add'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'add','zp'=>self::PACKAGE) );
		$this->_url ['GameSerList_Edit'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'edit','zp'=>self::PACKAGE) );
		$this->_url ['GameSerList_CreateCache'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'cache','zp'=>self::PACKAGE) );
		$this->url['XianHunOperator_Announcement'] = Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun'));
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
			$senddata	=	array(
				'game_type'	=>	self::GAME_ID,
				'page'		=>  max(1,intval($_GET['page'])),
				'title'		=>	trim($_GET['content']),
				'content'	=>	trim($_GET['content']),
				'zp'		=>	self::PACKAGE,
				'server_id'	=>	$_REQUEST['server_id'],
			);
			$game		=	$this->_getGlobalData('Game_'.self::GAME_ID,'object');
			$dataList	=	$game->getNotice($senddata);
//			$rpc = $this->getApi();
//			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
//			$rpc->setPrivateKey(self::RPC_KEY);
//			$dataList=$rpc->getAnnouncements(0,$_GET['title'],$_GET['content'],1,PAGE_SIZE);
			
			$URL_AnnouncementAdd = Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementDel = Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			
			$this->_view->assign('URL_AnnouncementAdd',$URL_AnnouncementAdd);
			$this->_view->assign('URL_AnnouncementDel',$URL_AnnouncementDel);
			
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				foreach($dataList['results'] as &$sub){
					$sub['URL_Edit'] =  Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','doaction'=>'edit','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
					$sub['URL_Del'] =  Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','doaction'=>'del','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList['results']);
				$_GET['page'] = $dataList['pageIndex'];
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
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['startTime']);
			$_POST['endTime'] = trim($_POST['endTime']);
			$_POST['interval'] = intval($_POST['interval']);
			$_POST['url'] = trim($_POST['url']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addAnnouncement($_POST['title'],$_POST['content'],$_POST['url'],$_POST['interval'],$_POST['startTime'],$_POST['endTime']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				if($dataList['msgno'] == 1){
					$JumpUrl = Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
					$this->_utilMsg->showMsg('操作成功',1,$JumpUrl,1);
				}else{
					$this->_utilMsg->showMsg($dataList['message'],-1);
				}				
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/AnnouncementEdit.html'));
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
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['startTime']);
			$_POST['endTime'] = trim($_POST['endTime']);
			$_POST['interval'] = intval($_POST['interval']);
			$_POST['url'] = trim($_POST['url']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->updateAnnouncement($_GET['id'],$_POST['title'],$_POST['content'],$_POST['url'],$_POST['interval'],$_POST['startTime'],$_POST['endTime']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				if($dataList['msgno'] == 1){
					$JumpUrl = Tools::url(CONTROL,'Announcement',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
					$this->_utilMsg->showMsg('操作成功',1,$JumpUrl,1);
				}else{
					$this->_utilMsg->showMsg($dataList['message'],-1);
				}				
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getAnnouncements($_GET['id'],'','',1,1);
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$selected = array_shift($dataList['results']);
				$this->_view->assign('selected',$selected);
			}
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/AnnouncementEdit.html'));
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 单服公告删除
	 */
	private function _announcementDel(){
		$_GET['id'] = intval($_GET['id']);
		if($_GET['id']){
			$ids = $_GET['id'];
		}elseif($_POST['ids']){
			$ids = implode(',',$_POST['ids']);
		}else{
			$this->_utilMsg->showMsg('没有选择');
		}
		$senddata	=	array(
				'game_type'	=>	self::GAME_ID,
				'server_id'	=>	$_REQUEST['server_id'],
				'ids'		=>	$ids
			);
		$game		=	$this->_getGlobalData('Game_'.self::GAME_ID,'object');
		$dataList	=	$game->delNotice($senddata);
		if($dataList instanceof PHPRPC_Error ){
			$this->_utilMsg->showMsg($dataList->Message,-1);
		}elseif($dataList){
			$this->_utilMsg->showMsg('操作成功',1,1,1);				
		}else{
			$this->_utilMsg->showMsg('操作失败',-1);
		}		
	}
	
	/**
	 * 全服发邮件
	 */
	public function actionSendMailToAll(){
//		2)全服发短信邮件
//		/**
//	     * 给全服注册玩家群发邮件
//	     * 送金币了|送金币了||0|50000
//	     * @param title			标题
//	     * @param content		内容
//	     * @param itemAndNums	物品id和数量:	100001:1,100002:2
//	     * @param gold			游戏币（银两）
//	     * @param cash			金币（人民币）
//	     * @return 是否成功
//	     */
//	    public boolean sendMailsToAll(String title, String content, String itemAndNums, int gold, int cash)
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->sendMailsToAll($_POST['title'],$_POST['content'],'',0,0);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
//		$URL_itemsUpdate = Tools::url('XianHunUser','ItemsUpdate',array('zp'=>$this->game_id,'server_id'=>$_REQUEST['server_id']));
//		$this->_view->assign('URL_itemsUpdate',$URL_itemsUpdate);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionSendItemsToAllApply(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			if(trim($_POST['cause']) == ''){
				$this->_utilMsg->showMsg('操作原因不能为空',-1);
			}
			if(trim($_POST['title']) == '' || trim($_POST['content']) == ''){
				$this->_utilMsg->showMsg('标题和内容不能为空',-1);
			}	
			$_POST['gold'] = intval($_POST['gold']);
			$_POST['cash'] = intval($_POST['cash']);
			
			$ItemAndNums = array();
			$ItemInfo = array();
			if(is_array($_POST['ItemId']) && is_array($_POST['ItemNum'])){
				foreach($_POST['ItemId'] as $key => $sub){
					$ItemAndNums['ItemId'.$_POST['ItemId'][$key]] = $_POST['ItemId'][$key].':'.$_POST['ItemNum'][$key];
					$ItemInfo[] = $_POST['ItemName'][$key].'(<font style="color:red">'.$_POST['ItemNum'][$key].'</font>)';
				}
			}
			$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
			$apply_info.='<div>邮件标题：</div><div style="padding-left:10px;">'.$_POST['title'].'</div>';
			$apply_info.='<div>邮件内容：</div><div style="padding-left:10px;">'.$_POST['content'].'</div>';
			$apply_info.='<div>点数：</div><div style="padding-left:10px;">游戏币(<font style="color:red">'.$_POST['gold'].'</font>) , 金币(<font style="color:red">'.$_POST['cash'].'</font>)</div>';
			$apply_info.='<div>道具：</div><div style="padding-left:10px;">'.implode(' , ',$ItemInfo).'</div>';
			unset($ItemAndNums['ItemId']);	//删除页面上没有传输id的道具
			$ItemAndNums = implode(',',$ItemAndNums);
			$gameser_list = $this->_getGlobalData('server/server_list_'.$this->game_id);
			$applyData = array(
				'type'=>8,	//	全服发道具
				'server_id'=>$_REQUEST['server_id'],
				'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
				'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
				'list_type'=>2,	//2	运营审核列表
				'apply_info'=>$apply_info,
				'send_type'=>3,	//3	phprpc
				'send_data'=>array (
					'url_append'=>'rpc/server',
					'phprpc_method'=>'sendMailsToAll',
					'phprpc_params'=>array(
						$_POST['title'],
						$_POST['content'],
						$ItemAndNums,
						$_POST['gold'],
						$_POST['cash'],
					),
					'phprpc_key'=>self::RPC_KEY,
					'end'=>array(
						'cal_local_object'=>'Game_6',	//使用本地对象，如果为空，则使用内置函数
						'cal_local_method'=>'SendItemsToAllBack',	//使用本地方法
						'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
					),
				),
				'receiver_object'=>array($_REQUEST['server_id']=>''),
				'player_type'=>0,
				'player_info'=>'',
			);
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$applyInfo = $_modelApply->AddApply($applyData);
			if( true === $applyInfo){
				$Url_OperatorIndex = Tools::url('Apply','OperatorIndex');
				$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='{$Url_OperatorIndex}'>运营审核列表</a>",1,1,false);
			}else{
				$this->_utilMsg->showMsg($applyInfo['info'],-1);
			}
		}
		//创建更新道具的RUL
		$URL_ItemUpdate = Tools::url('XianHunUser','ItemsUpdate',array('zp'=>'XianHun','server_id'=>$_REQUEST['server_id']));
		$itemsData = $this->_getGlobalData( self::XIANHUN_ITEMS_CACHE_NAME );
		$this->_view->assign('URL_ItemUpdate',$URL_ItemUpdate);
		$this->_view->assign('itemsData',json_encode($itemsData));		
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
	
	
	public function actionGameNotice(){
		
		switch($_GET['doaction']){
			case 'add':{
				$this->_gamenoticeAdd();
				return ;
			}
			case 'del':{
				$this->_gamenoticeDel();
				return;
			}
			case 'edit':{
				$this->_gamenoticeEdit();
				return;
			}
			case 'alladd':{
				$this->_gamenoticeAllAdd();
				return;
			}
			default :{
				$this->_gamenoticeIndex();
				return ;
			}
		}
		
	}
	
	private function _gamenoticeAllAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isAjax()){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['startTime']);
			$_POST['endTime'] = trim($_POST['endTime']);
			$_POST['img'] = intval($_POST['img']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewActivity($_POST['img'],$_POST['title'],$_POST['startTime'],$_POST['endTime'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'连接出错'));
			}elseif($dataList){
					$this->_returnAjaxJson(array('status'=>1,'msg'=>'操作成功'));	
			}else{
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'操作失败'));
			}
		}
		$this->_view->assign ('tplServerSelect','Notice/MultiServerSelect.html');
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/gamenoteceAlladd.html'));
		$this->_view->display();
	}
	
	private function _gamenoticeEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['startTime']);
			$_POST['endTime'] = trim($_POST['endTime']);
			$_POST['img'] = trim($_POST['img']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->editActivity($_GET["id"],$_POST['img'],$_POST['title'],$_POST['startTime'],$_POST['endTime'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);			
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getActivitys();
			
			foreach($dataList as $_msg){
				if($_msg["id"]==$_GET["id"]){
					$pagedata	=	$_msg;
					break;
				}
			}
			$this->_view->assign('selected',$pagedata);
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/gamenoticeEdit.html'));
		}
		$this->_view->display();
	}
	
	
	private function _gamenoticeAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost()){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['startTime']);
			$_POST['endTime'] = trim($_POST['endTime']);
			$_POST['img'] = trim($_POST['img']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewActivity($_POST['img'],$_POST['title'],$_POST['startTime'],$_POST['endTime'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);	
			}else{
				$this->_utilMsg->showMsg('操作失败',1,1,1);
			}
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/gamenoticeEdit.html'));
		}
		$this->_view->display();
	}
	
	private function _gamenoticeIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getActivitys();
			$URL_AnnouncementAdd 	= Tools::url(CONTROL,'GameNotice',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementDel 	= Tools::url(CONTROL,'GameNotice',array('zp'=>'XianHun','doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementAllAdd = Tools::url(CONTROL,'GameNotice',array('zp'=>'XianHun','doaction'=>'alladd','server_id'=>$_REQUEST['server_id']));
			
			$this->_view->assign('URL_AnnouncementAdd',$URL_AnnouncementAdd);
			$this->_view->assign('URL_AnnouncementDel',$URL_AnnouncementDel);
			$this->_view->assign('URL_AnnouncementAllAdd',$URL_AnnouncementAllAdd);
//			[0] => Array
//        	(
//            [content] => xxxwoshi shenme 
//            [startTime] => 2011
//            [id] => 5
//            [title] => xxx
//            [link] => http://
//            [img] => xx
//            [endTime] => 2012
//        	)
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',"连接错误");
			}elseif($dataList){
				foreach($dataList as &$sub){
					$sub['URL_Edit'] =  Tools::url(CONTROL,'GameNotice',array('zp'=>'XianHun','doaction'=>'edit','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
					$sub['URL_Del'] =  Tools::url(CONTROL,'GameNotice',array('zp'=>'XianHun','doaction'=>'del','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->display();
	}
	
	private function _gamenoticeDel(){

		if(empty($_POST['ids'])){
			$post	=	array($_GET['id']);
		}else{
			$post	=	$_POST['ids'];
		}
		if(count($post)>0){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$data=$rpc->deleteActivity(implode(",",$post));
			if($data){
				$this->_utilMsg->showMsg('操作成功',1,1,1);		
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
					
		}else{		
			$this->_utilMsg->showMsg('没有选择所要删除的信息',-1);
		}
	}
	
	public function actionUpdateNotice(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_updatenoticeAdd();
				return ;
			}
			case 'alladd':{
				$this->_updatenoticeAllAdd();
				return ;
			}
			case 'del':{
				$this->_updatenoticeDel();
				return;
			}
			case 'edit':{
				$this->_updatenoticeEdit();
				return;
			}
			default :{
				$this->_updatenoticeIndex();
				return ;
			}
		}
	}
	
	private function _updatenoticeAllAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isAjax()){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);;			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewUpdate($_POST['title'],$_POST['content']);
			if($dataList instanceof PHPRPC_Error ){
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'连接出错'));
			}elseif($dataList){
					$this->_returnAjaxJson(array('status'=>1,'msg'=>'操作成功'));	
			}else{
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'操作失败'));
			}
		}
		$this->_view->assign ('tplServerSelect','Notice/MultiServerSelect.html');
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/updatealladd.html'));
		$this->_view->display();
	}
	
	private function _updatenoticeEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->editUpdate($_GET["id"],$_POST['title'],$_POST['content']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);			
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getUpdates();
			
			foreach($dataList as $_msg){
				if($_msg["id"]==$_GET["id"]){
					$pagedata	=	$_msg;
					break;
				}
			}
			$this->_view->assign('selected',$pagedata);
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/updatenoticeEdit.html'));
		}
		$this->_view->display();
	}
	
	private function _updatenoticeDel(){
	
		if(empty($_POST['ids'])){
			$post	=	array($_GET['id']);
		}else{
			$post	=	$_POST['ids'];
		}
		if(count($post)>0){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$data=$rpc->deleteUpdate(implode(",",$post));
			if($data){
				$this->_utilMsg->showMsg('操作成功',1,1,1);		
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
					
		}else{		
			$this->_utilMsg->showMsg('没有选择所要删除的信息',-1);
		}
	}
	
	private function _updatenoticeAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);;			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewUpdate($_POST['title'],$_POST['content']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);			
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/updatenoticeEdit.html'));
		}
		$this->_view->display();
	}
	
	private function _updatenoticeIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getUpdates();
			$URL_AnnouncementAdd = Tools::url(CONTROL,'UpdateNotice',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementDel = Tools::url(CONTROL,'UpdateNotice',array('zp'=>'XianHun','doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementAllAdd = Tools::url(CONTROL,'UpdateNotice',array('zp'=>'XianHun','doaction'=>'alladd','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('URL_AnnouncementAllAdd',$URL_AnnouncementAllAdd);
			$this->_view->assign('URL_AnnouncementAdd',$URL_AnnouncementAdd);
			$this->_view->assign('URL_AnnouncementDel',$URL_AnnouncementDel);
//			[0] => Array
//        	(
//            [content] => xxxwoshi shenme 
//            [startTime] => 2011
//            [id] => 5
//            [title] => xxx
//            [link] => http://
//            [img] => xx
//            [endTime] => 2012
//        	)
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',"连接错误");
			}elseif($dataList){
				foreach($dataList as &$sub){
					$sub['URL_Edit'] =  Tools::url(CONTROL,'UpdateNotice',array('zp'=>'XianHun','doaction'=>'edit','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
					$sub['URL_Del'] =  Tools::url(CONTROL,'UpdateNotice',array('zp'=>'XianHun','doaction'=>'del','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->display();
	}
	
	
	public function actionBulletin(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_bulletinAdd();
				return ;
			}
			case 'alladd':{
				$this->_bulletinAllAdd();
				return ;
			}
			case 'del':{
				$this->_bulletinDel();
				return;
			}
			case 'edit':{
				$this->_bulletinEdit();
				return;
			}
			default :{
				$this->_bulletinIndex();
				return ;
			}
		}
	}
	
	private function _bulletinAllAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		
		if ($this->_isAjax()){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['time'] = date('m-d',strtotime(trim($_POST['time'])));
			$_POST['typeName'] = trim($_POST['typeName']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewBulletin($_POST['typeName'],$_POST['title'],$_POST['time'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'连接出错'));
			}elseif($dataList){
					$this->_returnAjaxJson(array('status'=>1,'msg'=>'操作成功'));	
			}else{
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>'操作失败'));
			}
		}
		$this->_view->assign ('tplServerSelect','Notice/MultiServerSelect.html');
		$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/bulletinAllAdd.html'));
		$this->_view->display();
	}
	
	private function _bulletinEdit(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['time'] = date('m-d',strtotime(trim($_POST['time'])));
			$_POST['typeName'] = trim($_POST['typeName']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->editBulletin($_GET["id"],$_POST['typeName'],$_POST['title'],$_POST['time'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);			
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getBulletins();
			
			foreach($dataList as $_msg){
				if($_msg["id"]==$_GET["id"]){
					$pagedata	=	$_msg;
					break;
				}
			}
			$this->_view->assign('selected',$pagedata);
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/bulletinEdit.html'));
		}
		$this->_view->display();
	}
	
	private function _bulletinDel(){
		if(empty($_POST['ids'])){
			$post	=	array($_GET['id']);
		}else{
			$post	=	$_POST['ids'];
		}
		if(count($post)>0){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$data=$rpc->deleteBulletin(implode(",",$post));
			if($data){
				$this->_utilMsg->showMsg('操作成功',1,1,1);		
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
					
		}else{		
			$this->_utilMsg->showMsg('没有选择所要删除的信息',-1);
		}
	}
	
	private function _bulletinAdd(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($this->_isPost() && $_REQUEST['server_id']){
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['time'] = date('m-d',strtotime(trim($_POST['time'])));
			$_POST['typeName'] = trim($_POST['typeName']);
			$_POST['link'] = trim($_POST['link']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addNewBulletin($_POST['typeName'],$_POST['title'],$_POST['time'],$_POST['content'],$_POST['link']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg("连接出错",-1);
			}elseif($dataList){
				$this->_utilMsg->showMsg('操作成功',1,1,1);			
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}
		
		if ($_REQUEST['server_id']){
			$this->_view->set_tpl(array('body'=>'XianHun/XianHunOperator/bulletinEdit.html'));
		}
		$this->_view->display();
	}
	
	
	private function _bulletinIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getBulletins();
			$URL_AnnouncementAdd = Tools::url(CONTROL,'Bulletin',array('zp'=>'XianHun','doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementDel = Tools::url(CONTROL,'Bulletin',array('zp'=>'XianHun','doaction'=>'del','server_id'=>$_REQUEST['server_id']));
			$URL_AnnouncementAllAdd = Tools::url(CONTROL,'Bulletin',array('zp'=>'XianHun','doaction'=>'alladd','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('URL_AnnouncementAllAdd',$URL_AnnouncementAllAdd);
			$this->_view->assign('URL_AnnouncementAdd',$URL_AnnouncementAdd);
			$this->_view->assign('URL_AnnouncementDel',$URL_AnnouncementDel);
//			[0] => Array
//        	(
//            [content] => xxxwoshi shenme 
//            [startTime] => 2011
//            [id] => 5
//            [title] => xxx
//            [link] => http://
//            [img] => xx
//            [endTime] => 2012
//        	)
			if($dataList instanceof PHPRPC_Error ){
				$this->_view->assign('ConnectErrorInfo',"连接错误");
			}elseif($dataList){
				foreach($dataList as &$sub){
					$sub['URL_Edit'] =  Tools::url(CONTROL,'Bulletin',array('zp'=>'XianHun','doaction'=>'edit','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
					$sub['URL_Del'] =  Tools::url(CONTROL,'Bulletin',array('zp'=>'XianHun','doaction'=>'del','id'=>$sub['id'],'server_id'=>$_REQUEST['server_id']));
				}
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->display();
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
				'content' 	=> null,
				'page' => 1,
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
		$this->_returnAjaxJson(array('status'=>0,'msg'=>'抓取异常'));
		
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
		$this->_view->assign ( 'tplServerSelect','Notice/MultiServerSelect.html');
		$this->_view->set_tpl(array('body'=>'Notice/AllNotice.html'));
		$this->_view->display();
	}
	
	public function actionSynchronous(){
		if ($this->_isAjax()){
			
			$_POST['title'] = trim($_POST['title']);
			$_POST['content'] = trim($_POST['content']);
			$_POST['startTime'] = trim($_POST['begin']);
			$_POST['endTime'] = trim($_POST['end']);
			$_POST['interval'] = intval($_POST['interval']);
			$_POST['url'] = trim($_POST['url']);			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->addAnnouncement($_POST['title'],$_POST['content'],$_POST['url'],$_POST['interval'],$_POST['startTime'],$_POST['endTime']);
			if($dataList instanceof PHPRPC_Error ){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				if($dataList['msgno'] == 1){
					$this->_returnAjaxJson(array('status'=>1,'msg'=>'发送成功'));
				}else{
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>$dataList['message']));
				}				
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'发送失败'));
			}
		}else {
			$this->_checkOperatorAct();	//检测服务器
			$this->_createMultiServerList();
			$this->_view->assign ('tplServerSelect','Notice/MultiServerSelect.html');
			$this->_view->set_tpl(array('body'=>'Notice/Synchronous.html'));
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
		
	}	
	
	public function actionVersion(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			
			if($this->_isPost()){
				$rpc = $this->getApi();
				$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
				$rpc->setPrivateKey(self::RPC_KEY);
				$dataList=$rpc->editVersion($_POST["Version"]);
				if($dataList){
					$this->_utilMsg->showMsg('修改成功',1,1,1);
				}else{
					$this->_utilMsg->showMsg('操作失败',-1);
				}
			}
			
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->getVersion();
			$this->_view->assign ('Version',$dataList);
		}
		$this->_view->display();
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
	
	public function actionGroupSendemail(){
		switch ($_REQUEST['doaction']){
//			case 'send':
//				$this->_groupSendemail();
//				return ;
			default:
				$this->_groupSendemailindex();
				
		}
	}
	
	private function _groupSendemailindex(){
		if($this->_isAjax()){
			$post['title']				=	$_POST['title'];
			$post['content']			=	$_POST['content'];
			$post['itemAndNums']		=	$_POST['itemAndNums'];
			$post['gold']				=	$_POST['gold'];
			$post['cash']				=	$_POST['cash'];
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'rpc/server');
			$rpc->setPrivateKey(self::RPC_KEY);
			$dataList=$rpc->sendMailsToOnline($post['title'],$post['content'],$post['itemAndNums'],$post['gold'],$post['cash']);
			
			if($dataList instanceof PHPRPC_Error ){
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>$dataList->Message));
			}elseif($dataList){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>'发送成功'));
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>'操作失败'));
			}
		}else{
			$this->_checkOperatorAct();
			$this->_createMultiServerList();
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
		
	}
	
	public function actionServerStatus(){
		switch ($_REQUEST['doaction']){
			case 'operation':
				$this->_ServerStatusoperation();
				return ;
			case 'kickOnline':
				$this->_ServerStatuskickOnline();
				return ;
			case 'ajaxstatus':
				$this->_ServerStatusCheck();
				return ;
			default:
				$this->_ServerStatusindex();
				
		}
	}
	
	private function _ServerStatusCheck(){
		$rpc = $this->getApi();
		$rpc->setUrl($_GET['id'],'rpc/status');
		$rpc->setPrivateKey(self::RPC_KEY);
		$dataList=$rpc->status();
		if($dataList instanceof PHPRPC_Error ){
			$this->_returnAjaxJson(array('status'=>-2,'msg'=>"连接失败"));
		}else{
			$this->_returnAjaxJson(array('status'=>1,'msg'=>$dataList));
		}
		$this->_returnAjaxJson(array('status'=>1,'msg'=>$_GET['id']));
	}
	
	private function _ServerStatusindex(){
		$this->_checkOperatorAct();
		$serverList=$this->_getGlobalData('server/server_list_'.$this->game_id);
//		if($_REQUEST['server_id']){
//			$rpc = $this->getApi();
//			$rpc->setUrl($_REQUEST['server_id'],'rpc/status');
//			$rpc->setPrivateKey(self::RPC_KEY);
//			$dataList=$rpc->status();
//			if($dataList instanceof PHPRPC_Error ){
//				$this->_utilMsg->showMsg($dataList->Message,-1);
//			}
//			$this->_view->assign ( 'Status', $dataList );
//			$this->_view->assign ( 'selectedServerId', $_REQUEST['server_id'] );
//		}
		if ($_GET['operator_id']){
				
			foreach ($serverList as $key=>&$value){
				if (empty($value['server_url']))unset($serverList[$key]);
				if ($value['operator_id']!=$_GET['operator_id'])unset($serverList[$key]);
				$value['server_status']	=	"未获取";
			}
//			[859] => Array
//        (
//            [Id] => 859
//            [room_id] => 0
//            [game_type_id] => 6
//            [operator_id] => 9
//            [ordinal] => 0
//            [marking] => xs1.h.uwan.com:7001
//            [time_zone] => 0
//            [timezone] => PRC
//            [server_name] => xS1 仙魂
//            [server_url] => http://xs1.h.uwan.com:7001/
//            [timer] => 0
//            [ext] => a:5:{s:7:"db_host";s:15:"119.145.130.191";s:7:"db_name";s:10:"xianhun_s1";s:7:"db_user";s:6:"client";s:6:"db_pwd";s:20:"ji4*H^T7(65(4452)h6=";s:7:"db_port";s:4:"3306";}
//        )
			$this->_view->assign('dataList',$serverList);
		}
		$this->_view->assign('checkuserurl',Tools::url ( CONTROL, 'ServerStatus',array('zp'=>self::PACKAGE,'doaction'=>'ajaxstatus') ));
		$this->_view->assign('manageurl',Tools::url ( CONTROL, 'ServerStatus',array('zp'=>self::PACKAGE,'doaction'=>'operation') ));
		$this->_view->assign('kickOnlineurl',Tools::url ( CONTROL, 'ServerStatus',array('zp'=>self::PACKAGE,'doaction'=>'kickOnline') ));
		$this->_view->assign('selectedOperatorId',$_GET['operator_id']);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _ServerStatusoperation(){
		$rpc = $this->getApi();
		$rpc->setUrl($_GET['id'],'rpc/status');
		$rpc->setPrivateKey(self::RPC_KEY);
		if($_GET['statusid']==1){
			$dataList=$rpc->open();
			if($dataList instanceof PHPRPC_Error ){
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>"操作失败"));
			}elseif($dataList){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>$dataList));
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>"操作失败"));
			}
		}else{
			$dataList=$rpc->close();
			if($dataList instanceof PHPRPC_Error ){
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>"操作失败"));
			}elseif($dataList){
				$this->_returnAjaxJson(array('status'=>1,'msg'=>$dataList));
			}else{
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>"操作失败"));
			}
		}
	}
	
	private function _ServerStatuskickOnline(){
		$rpc = $this->getApi();
		$rpc->setUrl($_GET['id'],'rpc/status');
		$rpc->setPrivateKey(self::RPC_KEY);
		$dataList=$rpc->kickOnline();
		if($dataList instanceof PHPRPC_Error ){
			$this->_returnAjaxJson(array('status'=>-2,'msg'=>"踢人操作失败"));
		}elseif($dataList){
			$this->_returnAjaxJson(array('status'=>1,'msg'=>"踢人操作成功"));
		}else{
			$this->_returnAjaxJson(array('status'=>-2,'msg'=>"踢人操作失败"));
		}
	}
	
	public function actionModification(){
		if(!$this->_isPost()){$this->_checkOperatorAct();}
		$this->_createMultiServerList();
		if($_GET["playerId"]){
			$playerId	=	$_GET["playerId"];
			$this->_view->assign("playerId",$playerId);
			$this->_view->assign("modifdisplay",true);
		}
		if($this->_isPost()){	
			switch ($_POST['actionfunction']){
				case "addSingleInstanceCount":
					$method	=	"addSingleInstanceCount";
					$parameter	=	array($_POST["addcount"]);
					$playerId	=	"发送至全部玩家";
					$content	=	"增加所有人每天单人副本次数:".$_POST["addcount"];
					break;
				case "addMultiInstanceCount":
					$method	=	"addMultiInstanceCount";
					$parameter	=	array($_POST["addcount"]);
					$playerId	=	"发送至全部玩家";
					$content	=	"增加所有人每天多人副本次数:".$_POST["addcount"];
					break;
				case "setSingleInstanceCount":
					$method	=	"setSingleInstanceCount";
					$parameter	=	array($playerId,$_POST["addcount"]);
					$content	=	"增加某人每天单人副本次数:".$_POST["addcount"];
					break;
				case "setMultiInstanceCount":
					$method	=	"setMultiInstanceCount";
					$parameter	=	array($playerId,$_POST["addcount"],);
					$content	=	"增加某人每天多人副本次数:".$_POST["addcount"];
					break;
				case "setInstanceCount":
					$method	=	"setInstanceCount";
					$parameter	=	array($_POST['fbid'],$_POST["addcount"]);
					$content	=	"设置某个副本每天次数:次数".$_POST["addcount"]."；副本ID".$_POST['fbid'];
					break;
				case "setInstanceProgress":
					//setInstanceProgress($playerId,$post["instanceId"],$post['progressNum']);
					$method	=	"setInstanceProgress";
					$parameter	=	array($playerId,$_POST["instanceId"],$_POST['progressNum']);
					$content	=	"设置某个用户某个副本的进度， 天神阁就是层数:副本id:".$_POST["instanceId"]."；层数:".$_POST['progressNum'];
					break;
				case "setForgeLevel":
					$method	=	"setForgeLevel";
					//setInstanceProgress($playerId,$post["itemId"],$post['level'])
					$parameter	=	array($playerId,$_POST["itemId"],$_POST['level']);
					$content	=	"设置某人某道具的强化等级:道具id:".$_POST["itemId"]."；层数:".$_POST['level'];
					break;
				
			}
			
			foreach($_POST["server_id"] as $item){
				$apply_info='<div>申请原因：</div><div style="padding-left:10px;">'.$_POST['cause'].'</div>';
				$apply_info.='<div>玩家ID:'.$playerId.'</div>';
				$apply_info.='<div style="padding-left:10px;">'.$content.'</div>';
				$gameser_list = $this->_getGlobalData('gameser_list');
				$applyData = array(
					'type'=>20,	//扣除道具申请
					'server_id'=>$item,
					'operator_id'=>$gameser_list[$item]['operator_id'],
					'game_type'=>$gameser_list[$item]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>3,	//3	phprpc
					'send_data'=>array (
						'url_append'=>'rpc/role',
						'phprpc_method'=>$method,
						'phprpc_params'=>$parameter,
						'phprpc_key'=>self::RPC_KEY,
					),
					'receiver_object'=>array($item=>''),
					'player_type'=>0,
					'player_info'=>'',
				);	
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
			}
			$URL_CsIndex = Tools::url('Apply','CsIndex');
			$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='$URL_CsIndex'>客服审核列表</a>",1,1,false);
			
		}
		
		$this->_view->display();
	}
}