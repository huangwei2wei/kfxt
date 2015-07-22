<?php
/**
 * GM工具-三分天下
 * @author PHP-朱磊
 *
 */
class Control_GmSftx extends Control {
	
	/**
	 * Util_Msg;
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * operator_id
	 * @var int
	 */
	private $_operatorId;
	
	/**
	 * 三分天下API请求接口
	 * @var Util_ApiSftx
	 */
	private $_utilApiSftx;
	
	private $_key = '1mXz0G4LJ24AGmPcS90091AP';
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
	}
	
	private function _createUrl(){
		#------公告链接------#
		$this->_url['GmSftx_PublicNotice_Add']=Tools::url(CONTROL,'PublicNotice',array('doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_PublicNotice_Del']=Tools::url(CONTROL,'PublicNotice',array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		#------公告链接------#
		
		$this->_url['GmSftx_LockUser_Add']=Tools::url(CONTROL,'LockUser',array('doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_LockUser_Del']=Tools::url(CONTROL,'LockUser',array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_Donttalk_Add']=Tools::url(CONTROL,'Donttalk',array('doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_Donttalk_Del']=Tools::url(CONTROL,'Donttalk',array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_LockIP_Add']=Tools::url(CONTROL,'LockIP',array('doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_LockIP_Del']=Tools::url(CONTROL,'LockIP',array('doaction'=>'del','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_SendMsg']=Tools::url(CONTROL,'SendMsg',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_LibaoCard_Add']=Tools::url(CONTROL,'LibaoCard',array('server_id'=>$_REQUEST['server_id'],'doaction'=>'add'));
		
		$this->_url['GmSftx_UserLog_Gold']=Tools::url(CONTROL,'UserLog',array('server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_UserLog_Food']=Tools::url(CONTROL,'UserLog',array('doaction'=>'food','server_id'=>$_REQUEST['server_id']));
		$this->_url['GmSftx_UserLog_Copper']=Tools::url(CONTROL,'UserLog',array('doaction'=>'copper','server_id'=>$_REQUEST['server_id']));
		
		$this->_url['GmSftx_UserLog']=Tools::url(CONTROL, 'UserLog');
		
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 建立服务器选择列表
	 */
	private function _createServerList(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('gameser_list');
		foreach ($gameServerList as $key=>&$value){
			if ($value['game_type_id']!=3)unset($gameServerList[$key]);
		}
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','GmSftx/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}		
	}
	
	/**
	 * 建立多服务器列表
	 */
	private function _createMultiServerList(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('gameser_list');
		foreach ($gameServerList as $key=>&$value){
			if ($value['game_type_id']!=3)unset($gameServerList[$key]);
		}
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','GmSftx/MultiServerSelect.html');
		$this->_view->assign('operatorList',$operatorList);
	}

	/**
	 * 发布多广告
	 */
	public function actionMultiPublicNotice(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_multiNoticeAdd();
				return ;
			}
			default:{
				$this->_multiNoticeAdd();
				return ;
			}
		}
	}
	
	private function _multiNoticeAdd(){
		if ($this->_isPost()){
			if (!count($_REQUEST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'oneNotice','act'=>'save');
			foreach ($_REQUEST['server_ids'] as $serverId){
				$this->_utilApiSftx->addHttp($serverId,$getArr,$_POST);
			}
			$this->_utilApiSftx->send();
			$datas=$this->_utilApiSftx->getResults();
			$message=array();
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($datas as $key=>$value){
				if ($value['status']==1)
					array_push($message,"{$serverList[$key]['server_name']} <font color='#00cc00'>发送成功</font>");
				else 
					array_push($message,"{$serverList[$key]['server_name']} <font color='#FF0000'>发送失败</font>");
			}
			$message=Tools::formatLog($message);
			$this->_utilMsg->showMsg($message,1,1,null);
		}else {
			$this->_createMultiServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/MultiPublicNoticeAdd.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	private function _multiNoticeIndex(){
		
	}
		
	/**
	 * 公告总控制器
	 */
	public function actionPublicNotice(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_noticeAdd();
				return ;
			}
			case 'del' :{
				$this->_noticeDel();
				return ;
			}
			default:{
				$this->_noticeIndex();
				return ;
			}
		}
	}
	
	/**
	 * 删除公告
	 */
	private function _noticeDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'oneNotice','act'=>'deleteNotice');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if (!is_array($data))$this->_utilMsg->showMsg('连接游戏服务器失败',-2);
		if ($data['status']==1){
			$this->_utilMsg->showMsg('删除成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else{
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	/**
	 * 增加公告
	 */
	private function _noticeAdd(){
		$this->_createServerList();
		if ($this->_isPost()){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'oneNotice','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (!is_array($data))$this->_utilMsg->showMsg('连接游戏服务器失败',-2);
			if ($data['status']==1){
				$this->_utilMsg->showMsg('添加公告成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/NoticeAdd.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 公告显示页面
	 */
	private function _noticeIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'oneNotice','act'=>'selectNotices');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$this->_view->assign('dataList',$data['data']['result']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
			
			
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/NoticeIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 用户查询
	 */
	public function actionUser(){
		switch ($_GET['doaction']){
			default:{
				$this->_userIndex();
			}
		}
	}
	
	/**
	 * 用户查询页面
	 */
	private function _userIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'player','act'=>'select');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$data['data']['select']['pageSize']=$data['data']['dataList']['pageSize'];
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/User.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}	
	
	/**
	 * 游戏玩家操作日志
	 */
	public function actionUserLog(){
		switch ($_GET['doaction']){
			case 'copper' :{//银币
				$this->_userLogCopper();
				return ;
			}
			case 'food' :{//粮食
				$this->_userLogFood();
				return ;
			}
			default:{
				$this->_userLogGold();
				return ;
			}
		}
	}
	
	private function _userLogCopper(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'operationLog','act'=>'select','pageSize'=>PAGE_SIZE,'operationType'=>1);
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
			
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/UserLogCopper.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _userLogFood(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'operationLog','act'=>'select','pageSize'=>PAGE_SIZE,'operationType'=>2);
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
			
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/UserLogFood.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _userLogGold(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'expendLog','act'=>'select','pageSize'=>PAGE_SIZE);
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
			
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/UserLog.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 封号
	 */
	public function actionLockUser(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_lockUserAdd();
				return ;
			}
			case 'del' :{
				$this->_lockUserDel();
				return ;
			}
			case 'release' :{//强制解禁
				$this->_lockUserRelease();
				return ;
			}
			default:{
				$this->_lockUserIndex();
				return ;
			}
		}
	}
	
	private function _lockUserRelease(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseUser2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg('强制解禁成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockUserDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseUser2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg('删除成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockUserIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'refuseUser2','act'=>'select');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				if ($dataList){
					foreach ($dataList as &$list){
						$list['status']=$data['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,ACTION,array('doaction'=>'release','id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$data['data']['select']['pageSize']=$data['data']['dataList']['pageSize'];
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LockUser.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _lockUserAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseUser2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			
			if ($data['status']==1){
				$this->_utilMsg->showMsg('封号成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/LockUserAdd.html'));
			if ($_POST['idList'])$this->_view->assign('users',implode(',',$_POST['idList']));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 禁言
	 */
	public function actionDonttalk(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_donttalkAdd();
				return ;
			}
			case 'release' :{
				$this->_donttalkRelease();
				return ;
			}
			case 'del' :{
				$this->_donttalkDel();
				return ;
			}
			default:{
				$this->_donttalkIndex();
				return ;
			}
		}
	}
	
	private function _donttalkRelease(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseMessage2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if ($data['status']==1){
			$this->_utilMsg->showMsg('强制解禁成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _donttalkDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseMessage2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if ($data['status']==1){
			$this->_utilMsg->showMsg('删除成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _donttalkIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'refuseMessage2','act'=>'select');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				if ($dataList){
					foreach ($dataList as &$list){
						$list['status']=$data['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,ACTION,array('doaction'=>'release','id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$data['data']['select']['pageSize']=$data['data']['dataList']['pageSize'];
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/Donttalk.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _donttalkAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseMessage2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg('禁言成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/DonttalkAdd.html'));
			if ($_POST['idList'])$this->_view->assign('users',implode(',',$_POST['idList']));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 封IP
	 */
	public function actionLockIP(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_lockipAdd();
				return ;
			}
			case 'del' :{
				$this->_lockIPDel();
				return ;
			}
			case 'release' :{
				$this->_lockIPRelease();
				return ;
			}
			default:{
				$this->_lockipIndex();
				return ;
			}
		}
	}
	
	private function _lockIPRelease(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseIP2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg('强制解禁成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockIPDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseIP2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg('删除成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockipIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'refuseIP2','act'=>'select');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				if ($dataList){
					foreach ($dataList as &$list){
						$list['status']=$data['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
						$list['url_release']=Tools::url(CONTROL,ACTION,array('doaction'=>'release','id'=>$list['id'],'server_id'=>$_REQUEST['server_id']));
					}
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$data['data']['select']['pageSize']=$data['data']['dataList']['pageSize'];
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LockIP.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _lockipAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseIP2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg('增加封锁IP成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/LockIPAdd.html'));
			if ($_POST['idList'])$this->_view->assign('users',implode(',',$_POST['idList']));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	/**
	 * 向用户发送消息
	 */
	public function actionSendMsg(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'msg','act'=>'save');
			$serverList=$this->_getGlobalData('gameser_list');
			$server=$serverList[$_REQUEST['server_id']];
			$serverUrl=$server['server_url'];
			$random=CURRENT_TIME.rand(100000,900000);
			$verifyCode=md5(Util_ApiSftx::ENCRYPT_KEY.$random);
			$serverUrl=$serverUrl.$getArr['ctl'].'/'.$getArr['act']."?_sign={$verifyCode}&_verifycode={$random}&operator=".Util_ApiSftx::USER_NAME;
			foreach ($_POST as $key=>$value){
				$serverUrl.="&{$key}=".str_replace('%','_^_',urlencode($value));
			}
			$data=json_decode(file_get_contents($serverUrl),true);
			
			
//			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
//			$this->_utilApiSftx->send();
//			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg('发送消息成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			if ($_POST['idList'])$this->_view->assign('users',implode(',',$_POST['idList']));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	public function actionLibaoCard(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_libaoCardAdd();
				return ;
			}
			default:{
				$this->_libaoCardIndex();
				return ;
			}
		}
	}
	
	/**
	 * 礼包卡号
	 */
	private function _libaoCardIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'refuseIP2','act'=>'select');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				$_GET['page']=$data['data']['dataList']['currentPageNo'];
				$dataList=$data['data']['dataList']['result'];	//数据
				if ($dataList){
					foreach ($dataList as &$list){
						$list['status']=$data['data']['optionList']['statusList'][$list['status']];
						$list['begin']=date('Y-m-d H:i:s',$list['begin']);
						$list['end']=date('Y-m-d H:i:s',$list['end']);
						$list['createAt']=date('Y-m-d H:i:s',$list['createAt']);
					}
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$data['data']['select']['pageSize']=$data['data']['dataList']['pageSize'];
				$this->_view->assign('selectedArr',$data['data']['select']);
			}else {
				$this->_view->assign('errorConn','连接游戏服务器失败');
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LibaoCard.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _libaoCardAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg('请选择服务器',-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'card','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg('生成卡号成功',1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			$this->_view->set_tpl(array('body'=>'GmSftx/LibaoCardAdd.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	
	/**
	 * 操作日志记录.
	 */
	public function actionLog(){
		
	}
	
	/**
	 * 物品日志
	 */
	public function actionGoodsLog(){
		$this->_createServerList();
		if ($_REQUEST['server_id']){
			$_GET['detailId'] = trim($_GET['detailId']);
			$_GET['playerId'] = trim($_GET['playerId']);
			$_GET['goodsId'] = trim($_GET['goodsId']);
			$_GET['page'] = max(1,intval($_GET['page']));
			$_POST['toPage'] = $_GET['page'];
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$sendParams=Tools::getFilterRequestParam();
			$getArr=array('ctl'=>'goodsLog','act'=>'findGoodsLg');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$dataList=$this->_utilApiSftx->getResult();			
			if(is_array($dataList['data'])){
				$this->_view->assign('dataList',$dataList['data']);
				$total = $dataList['totalCount'];
				$this->_loadCore('Help_Page');//载入分页工具
				$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
				$this->_view->assign('pageBox',$helpPage->show());
			}elseif(is_string($dataList)){
				$this->_view->assign('error',$dataList);
			}			
			$this->_view->assign('selected',$_GET);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	
	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc=null;

	/**
	 * @return Util_Rpc
	 */
	private function getApi(){
		if (is_null($this->_utilRpc)){
			$this->_utilRpc=$this->_getGlobalData('Util_Rpc','object');
		}
		return $this->_utilRpc;
	}
	
	/**
	 * 修改玩家的游戏名
	 */
	public function actionModifyName(){		
		switch($_REQUEST['doaction']){
//			case 'findPlayer':{
//				$this->_modifyNameFindPlayer();
//			}
			case 'modifyName':{
				$this->_modifyNameDoit();
			}
			default:{
				$this->_modifyNameIndex();
			}
		}
	}

	/**
	 * 改名前查询
	 */
	private function _modifyNameIndex(){
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$_POST['playerType'] = intval($_POST['playerType']);
			$_POST['player'] = trim($_POST['player']);
			$rpc = $this->getApi();
			$rpc->setUrl($_REQUEST['server_id'],'api/modifyPlayer');
			if($_POST['playerType']){
				$dataList=$rpc->findPlayerByName($_POST['player']);
			}else{
				$dataList=$rpc->findPlayerById(intval($_POST['player']));
			}			
			if($dataList instanceof PHPRPC_Error){
				$this->_view->assign('ConnectErrorInfo',$dataList->Message);
			}elseif($dataList){
				$dataList = json_decode($dataList,true);
				$jump = Tools::url(CONTROL,'ModifyName',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
				if(intval($dataList['playerId']) == 0){
					$this->_utilMsg->showMsg('查无数据',-1,$jump);
				}
				$this->_view->assign('URL_ReQuery',$jump);
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 操作改名
	 */
	private function _modifyNameDoit(){
		if(!$_REQUEST['server_id']){
			$this->_utilMsg->showMsg('服务器为空',-1);		
		}
		if ($this->_isPost()){
			$_POST['playerId'] = intval($_POST['playerId']);
			$_POST['rename'] = trim($_POST['rename']);
			if($_POST['playerId']<=0 && $_POST['rename']==''){
				$this->_utilMsg->showMsg('提交有误',-1);	
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'api/modifyPlayer');			
			$dataList=$this->getApi()->modifyName($_POST['playerId'],$_POST['rename']);
			if($dataList instanceof PHPRPC_Error){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$jump = Tools::url(CONTROL,'ModifyName',array('zp'=>'Sftx','server_id'=>$_REQUEST['server_id']));
				$this->_utilMsg->showMsg('操作成功',1,$jump,1);	
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$this->_utilMsg->showMsg('非POST提交',-1);	
		}
	}
	/**
	 * 用户资源修改
	 */
	public function actionUpdateReward(){
		switch ($_GET['doaction']){
			case 'config':
				$this->_updateRewardConfig();
				break;
			case 'check':
				$this->_checkupdatereward();
				break;
			default:
				$this->_updaterewardindex();
				break;
		}
	}
	
	private function _updateRewardConfig($getData = false){
		$fileName = array(
			$this->game_id,PACKAGE,CONTROL,ACTION,__FUNCTION__
		);
		$fileName = implode('_',$fileName);
		if($getData){
			$data = $this->_f($fileName);
			return $data?$data:array();
		}
		if($this->_isPost()){
			$data = array();
			$config = explode("\n",trim($_POST['config']));
			
			foreach($config as $value){
				$value = trim($value);
				if($value){
					$value = explode('=',$value,2);
					if(count($value) == 2){
						$data[trim($value[0])] = trim($value[1]);
					}
				}
			}
			if($this->_f($fileName,$data)){
				$this->_utilMsg->showMsg('操作成功',1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$data = $this->_f($fileName);
			if(!$data){
				$data = array();
			}
			$dataTmp = array();
			foreach ($data as $key => $val){
				array_push($dataTmp,"{$key}={$val}");
			}
			$data = implode("\n",$dataTmp);
			$this->_view->assign('data',$data);
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/UpdateRewardConfig.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
//	private function _updaterewardConfLog($postData){
//		$effectiveData = $this->_updateRewardConfig(true);
//		$str = '';
//		$postData = array_filter($postData,'strlen');
//		foreach($effectiveData as $field => $name){
//			if(isset($postData[$field])){
//				$str .= $name . intval($postData[$field]).'; ';
//			}
//		}
//		return $str;
//	}
	
	private function _checkupdatereward(){
		$serverList = $this->_getGlobalData('gameser_list');
		$server = $serverList[$_GET["server_id"]];
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->addHttp($server['server_url'],'playerReward/isExist',array('uid'=>$_GET["ids"]));
		$this->_utilHttpMInterface->send();
		$arr	=	$this->_utilHttpMInterface->getResults();
		echo $arr['1'];
	}
	
	private function _updaterewardindex(){
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){				
			$serverList = $this->_getGlobalData('gameser_list');
			$server = $serverList[$_REQUEST['server_id']];
			if($server){
				if($_POST['usertype']==1){
					$_POST['uid'] = $_POST['user'];
				}else{
					$_POST['username'] = $_POST['user'];
				}
				unset($_POST['user'],$_POST['usertype']);
				foreach ($_POST as $key =>$value){
					if(empty($value)){
						unset($_POST[$key]);
					}
				}
				$_POST['sid'] = $server['marking'];//'192.168.12.127:8080';//
				$_POST['time'] = CURRENT_TIME.'000';	//时间（毫秒）
				$_POST['sign'] = md5($_POST['sid'].$this->_key.$_POST['time']);
				$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
				$this->_utilHttpMInterface->addHttp($server['server_url'],'playerReward/reward',array(),$_POST);
				$this->_utilHttpMInterface->send();
				$dataResult = $this->_utilHttpMInterface->getResults();
				$dataResult =  json_decode(array_shift($dataResult),true);
				if(is_array($dataResult) && 'success' == strval($dataResult['m']['message'])){
					$this->_utilMsg->showMsg($dataResult['m']['message'].';'.$dataResult['m']['STARSOUL'],1);
				}else{
					$this->_utilMsg->showMsg('操作失败 '.$dataResult,-1);
				}
			}
		}
		$this->_view->assign('dataConfig',$this->_updateRewardConfig(true));
		$this->_view->assign('URL_updateRewardConfig',Tools::url(CONTROL,ACTION,array('doaction'=>'config','server_id'=>$_REQUEST["server_id"])));
		$this->_view->assign('checkurl',Tools::url(CONTROL,'UpdateReward',array('doaction'=>'check','server_id'=>$_REQUEST["server_id"])));
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	/**
	 * 新用户日志
	 */
	public function actionPlayerLogFind(){		
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $_REQUEST['submit']){				
			$serverList = $this->_getGlobalData('gameser_list');
			$server = $serverList[$_REQUEST['server_id']];
			if($server){
				$startTime = strtotime($_GET['startTime']);				
				if($startTime){
					$startTime .= '000'; 
				}else{
					$startTime = '0';
				}
				$endTime = strtotime($_GET['endTime']);
				if($endTime){
					$endTime .= '000';
				}else{
					$endTime = '0';
				}
				$_GET['page'] = intval(max(1,$_GET['page']));
				$post['pageSize'] = PAGE_SIZE;
				$post['currentPage'] = $_GET['page'];
				$post['cmdID'] = intval($_GET['cmdID']);
				$post['playerId'] = intval($_GET['playerId']);
				$post['startTime'] = $startTime;
				$post['endTime'] = $endTime;
				
				$post['sid'] = $server['marking'];//'192.168.12.127:8080';//
				$post['time'] = CURRENT_TIME.'000';	//时间（毫秒）
				$post['sign'] = md5($_POST['sid'].$this->_key.$post['time']);
				
				$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
				$this->_utilHttpMInterface->addHttp($server['server_url'],'playerLog/find',array(),$post);
				$this->_utilHttpMInterface->send();
				$dataResult = $this->_utilHttpMInterface->getResults();
				$dataResult =  json_decode(array_shift($dataResult),true);				
				if(strval($dataResult['status']) == '1'){
					$optDetail = $this->_getLogDetail();
					if(is_array($dataResult['data']['result'])){
						foreach($dataResult['data']['result'] as &$sub){
							$sub['optDetail'] = $optDetail[$sub['cmdId']];
						}
					}
					$this->_view->assign('dataLiat',$dataResult['data']['result']);					
					$this->_loadCore('Help_Page');//载入分页工具
					$helpPage=new Help_Page(array('total'=>$dataResult['data']['totalCount'],'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$helpPage->show());
				}else{
					$this->_utilMsg->showMsg($dataResult['info']);
				}
			}
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _getLogDetail(){
		return array (
			10100 => '用户登录 ***需要log',
			10101 => '登出 ***需要log',
			10102 => '心跳检测',
			10104 => '停服提前通知',
			10105 => '强制下线，只有指定IP可进入',
			10112 => '充许管员进入',
			10106 => '去掉限制IP',
			10109 => '充值 （按照列表对应的关系来充金币）***需要log',
			10107 => '时实在线数',
			10108 => '登录时推送历史记录',
			10117 => '0#^##^#0#^#1#^#Wall?Street		是否有金币#^#角色名#^#游戏币#^#服务器状态#^#服名',
			10118 => '0:您在该服务器没有建立角色，不可充值		1:您在该服务器已建立角色，可充值		2:该服务器正在维护中，不可充值',
			10403 => '用户领取礼品卡奖励  ***需要log',
			10408 => '获取蛋的信息',
			10409 => '砸蛋',
			10410 => '刷金蛋',
			11010 => '修改玩家的银币 ***需要log',
			11011 => '修改玩家的军令 ***需要log ',
			11012 => '修改玩家的军功 ***需要log',
			11013 => '获取威望排行',
			11014 => '获取军团排行',
			11015 => '修改用户姓名 ***需要log ',
			11016 => '修改军团姓名 ',
			11017 => '给用户新增装备 ***需要log ',
			11018 => 'T用户下线',
			11019 => '删除角色 (1.3.1.2 new)',
			11020 => '重读配置表信息(1.3.1.2 new)',
			41009 => '更新HistoryForm',
			41010 => '清空对阵表缓存',
			11021 => '游戏奖励',
			11021 => '查看用户信息',
			11022 => '查看用户装备',
			11023 => '修改用户装备',
			11027 => '获取游戏配置文件',
			11025 => '获取游戏配置文件（数据库）',
			11026 => '游戏奖励',
			111001 => '獲取對陣表外部接口',
			12001 => '删除玩家装备',
			15201 => '获取游戏配置类型',
			11100 => '创建用户',
			11101 => '创建用户--攻城 ***需要log',
			11102 => '刷新用户信息',
			11104 => '心跳器',
			11103 => '主动下发用户信息',
			11200 => '购买军令--确定 ***需要log',
			11201 => '购买军令 ',
			11202 => '购买军令--确定 ***需要log ',
			11203 => '购买军令 ',
			11300 => '操作队伍冷却时间清零 ',
			11301 => '操作队伍冷却时间清零确定 ***需要log',
			11303 => '增加建造队 ',
			11304 => '增加建造队--确定 ***需要log',
			11305 => '获取VIP等级信息 ',
			31116 => 'TODO 修改玩家名称',
			31117 => 'TODO 修改玩家名称--确定 ***需要log(1.3.1.2 new)',
			10201 => '下一步指导',
			10202 => '买许多军令',
			10204 => '买许多军令,价格计算',
			20101 => '获得系统控制开关',
			12100 => '建筑升级 ***需要log',
			12200 => '获取主城信息',
			12300 => '升官界面',
			12302 => '领取俸禄 ***需要log',
			12303 => '捐军功 ***需要log',
			12400 => '获取征收界面信息',
			12401 => '征收 ***需要log',
			12406 => '征收时间答案选择 ***需要log',
			13100 => '市场界面',
			13101 => '正常和黑市的 买卖粮 ***需要log',
			14100 => '征义兵操作 ***需要log',
			14101 => '征兵界面',
			14102 => '征兵操作 ***需要log',
			14103 => '打开属臣界面获取数据',
			14104 => '放弃属臣操作 ***需要log',
			14105 => '我的信息',
			14106 => '预计多次强征的得到的银币',
			18601 => '玩家登录后显示圣诞树//玩家登录后显示圣诞树',
			18602 => '单个任务详细信息查询//单个任务详细信息查询',
			18603 => '领取任务包的奖励//领取任务包的奖励',
			18604 => '领取任务组的奖励//领取任务组的奖励',
			18605 => '领取任务组的每日奖励//领取任务组的每日奖励',
			18606 => '领取任务组的分红奖励//领取任务组的分红奖励',
			20001 => '打開月桂',
			20011 => '領取任務包獎勵 ***需要log(1.3.1.2 new)',
			20012 => '領取層獎勵 ***需要log(1.3.1.2 new)',
			20013 => '領取分紅 ***需要log(1.3.1.2 new)',
			20014 => '領取每日奖励 ***需要log(1.3.1.2 new)',
			20015 => '测试用',
			31101 => '进入地区场景获取数据',
			31102 => '在地区场景翻页',
			31103 => '进入农田场景获取数据',
			31104 => '占领农田 ***需要log',
			31105 => '放弃农田 ***需要log',
			31106 => '点击选择城池',
			31107 => '占领银矿 ***需要log',
			31108 => '攻击其它地区玩家城池 ***需要log',
			31109 => '抢收农田、银矿 ',
			31110 => '免战操作 ',
			31111 => '免战操作 confirm ***需要log',
			31112 => '征服属臣 ***需要log',
			31113 => '修改留言 ',
			31114 => '修改旗号',
			31115 => '获取玩家信息',
			30100 => '进入世界场景获取数据',
			30201 => '确认投资 ***需要log',
			30202 => '获取投资信息',
			30203 => '发起地区战 ',
			30204 => '迁移操作 ***需要log',
			30205 => '在需要用户选择国家的时候 确认迁移操作 ***需要log',
			30400 => '攻占农场 ',
			30401 => '世界--农场-收获 ***需要log',
			30402 => '世界--农场--收获--清零 ',
			30403 => '世界--农场--收获--清零 --确定 ***需要log',
			30406 => '放弃农场 ***需要log',
			30407 => '放棄農塲站',
			30408 => '腾讯 选国家',
			32100 => '打开军团窗口',
			32101 => '军团所有成员',
			32102 => '创建军团 ***需要log',
			32103 => '军团--申请--玩家列表',
			32104 => '军团--申请--同意 ',
			32105 => '军团--申请--拒绝',
			32106 => '发送军团邮件',
			32107 => '军团--成员--请出军团',
			32108 => '军团--成员--转让军长',
			32109 => '申请加入军团',
			32110 => '取消申请加入军团',
			32111 => '军团--修改个人留言',
			32112 => '军团--修改军团留言',
			32113 => '退出军团',
			32114 => '军团明细',
			32115 => '军团--成员',
			32116 => '军团--成员--升官',
			32117 => '军团--科技',
			32118 => '军团--科技--捐献 --默认捐献  ***需要log',
			32119 => '军团--科技--捐献 ',
			32120 => '军团--科技--捐献--确定  ***需要log',
			32121 => '军团--情况',
			32122 => '军团--情况--升级军徽   ***需要log',
			32123 => '军团--情况--修改团徽',
			32125 => '修改军团名  ***需要log',
			32126 => '解散军团  ***需要log',
			32127 => '解散军团  ***需要log',
			32130 => '主动下发成员职务变化的消息',
			32131 => '军团切磋',
			32132 => '军团切磋--确定',
			32133 => '设置军团成员加入限制  开启或关闭',
			32134 => '军徽升级记录',
			33100 => '打开征战页',
			33101 => '发起战斗  ***需要log',
			33102 => '领取奖励  ***需要log',
			33104 => '攻略',
			33105 => '一条详细战报',
			33200 => '地图',
			33201 => '当前世界大区域下的所有势力列表',
			33202 => '获取整个世界大区域的地图',
			33203 => '发起战斗|多次强攻',
			33204 => '强征NPC 价格计算',
			34100 => '读取队伍战役数据',
			34101 => '创建队五',
			34102 => '加入队五',
			34103 => '调整成员排列',
			34104 => '踢走成员',
			34105 => '解散队伍',
			34106 => '退出队伍',
			34107 => '开始战斗 ***需要log',
			36101 => '点击军团战',
			35101 => '点击地团战',
			36102 => '读取军团战队伍信息',
			35102 => '读取地区战队伍信息',
			36103 => '鼓舞  ***需要log',
			36104 => '推出战斗',
			37100 => '推出战斗',
			37101 => '打开银矿',
			37102 => '银矿战--刷新',
			37103 => '银矿战--开战  ***需要log',
			37104 => '银矿战--移动',
			37105 => '银矿战--挖掘  ***需要log',
			37106 => '骚扰',
			37107 => '银矿战--征  ***需要log',
			37108 => '建立影子部队 ',
			37109 => '获取建立影子部队的金币数 ',
			37110 => '银矿战--查看人数 ',
			37111 => '银矿战--查看人数 --确定   ***需要log',
			37112 => '银矿战--退出',
			39100 => '点击仓库',
			39101 => '卖仓库的东西  ***需要log',
			39102 => '增加仓库  ***需要log',
			39103 => '降级装备	(同强化的降级）  ***需要log',
			39200 => '装备—武将列表',
			39201 => '装装备',
			39202 => '卸下装备',
			39203 => '全卸',
			39204 => '点击装备项',
			39205 => '点击装备',
			39206 => '点击切换菜单项',
			39301 => '点击强化 ',
			39302 => '升级装备  ***需要log',
			39303 => '升级清零--确定  ***这个command未实现',
			39304 => '升级清零  ***这个command未实现',
			39305 => '降级装备 同39103  ***需要log',
			39306 => '绑定装备',
			39307 => '取消绑定装备',
			39308 => '强化--终止取消',
			40101 => '打开委派窗口',
			40102 => '委派 ***需要log',
			40103 => '委派 ***确认log',
			40105 => '直接卖出 ***需要log',
			40106 => '刷新物品',
			15100 => '点击商城/商店',
			15101 => '商城/商店--领取 ***需要log',
			15102 => '商城/商店--购买 ***需要log',
			46310 => '开箱子 ***需要log',
			46311 => '读取箱子内容',
			47100 => '打开藏宝图 ***需要log',
			48100 => '读取礼包 ',
			48110 => '購買禮包 ***需要log',
			48111 => '购买许多箱子(与商店购买传的参数一样,只是多了个总数)',
			48112 => '购买许多箱子,计算价格(与商店购买传的参数一样,只是多了个总数)',
			48113 => '开好多箱子',
			48114 => '增加仓库',
			48115 => '增加仓库,价格计算',
			48116 => '点击黑市',
			48117 => '刷新黑市',
			48118 => '购买黑市物品 确定',
			48119 => '购买黑市物品 预算',
			49100 => '打开阵灵面板--获取全部未被使用的阵灵，包括所有品质和类型',
			49101 => '打开阵灵面板--按种类，包括使用与未被使用的阵灵与未领取的',
			49102 => '得到用户正在使用的阵灵',
			49103 => '喂养阵灵--通过晶石 ***需要log',
			49104 => '喂养阵灵--通过兵符  ***需要log',
			49105 => '阵灵升级  ***需要log',
			49106 => '阵灵降级  ***需要log',
			49107 => '阵灵出售  ***需要log',
			49108 => '购买增加阵灵倉庫格子--确定  ***需要log',
			49109 => '刷新阵灵升级的成功率  ***需要log',
			49110 => '得到阵灵升级的成功率',
			49111 => '绑定阵灵',
			49112 => '取消绑定',
			49113 => '购买增加阵灵倉庫格子--询问金币消耗数量',
			49114 => '阵灵分解  ***需要log',
			49115 => '终止取消绑定',
			49116 => '领取未领取的阵灵--需要仓库格子足够  ',
			49117 => '购买非常多的阵灵格子',
			49118 => '购买非常多的阵灵格子,价格计算',
			41100 => '点击部队',
			41101 => '训练//训练 ***需要log',
			41102 => '突飞猛进 / 结束训练   每30S给经验//突飞猛进 / 结束训练   每30S给经验 ***需要log',
			41103 => '训练-突飞猛进-清零//训练-突飞猛进-清零 ***这个command未实现',
			41104 => '训练-突飞猛进-清零 -确定 //训练-突飞猛进-清零 -确定 ***这个command未实现',
			41105 => '训练模式  ***需要log',
			41106 => '购买位置  ***需要log',
			41107 => '获取武将信息',
			41108 => '打开武魂训练师界面',
			41109 => '开启训练师   ***需要log',
			41200 => '点击招募菜单',
			41201 => '招募   ***需要log',
			41202 => '招募后刷新',
			41300 => '点击培养菜单',
			41301 => '培养--洗属性   ***需要log',
			41302 => '培养--刷新将军明细',
			41303 => '维持或替换属性   ***需要log',
			41304 => '转生 等级',
			41203 => '增加招幕位  ***需要log',
			41305 => '获取可淬炼的装备列表',
			41306 => '获取分解装备列表',
			41307 => '获取装备信息',
			41308 => '淬炼装备  ***需要log',
			41309 => '分解装备  ***需要log',
			41310 => '淬炼 退化  ***需要log',
			41311 => '淬炼 替换属性  ***需要log',
			41312 => '突飞(军功/突飞令、金币)  确定操作',
			42200 => '升级科技  ***需要log',
			42201 => '获取科技',
			42102 => '上阵下阵',
			42103 => '获取阵型信息',
			42104 => '设置默认阵行',
			42105 => '下野',
			42106 => '切换阵型',
			42107 => '全部下阵',
			42108 => '获取御灵阵信息',
			42109 => '开启御灵阵',
			42110 => '切换御灵阵',
			42111 => '御灵阵上阵下阵',
			43100 => '主城--商队--强制通商  ***需要log',
			43101 => '打开商队窗口',
			43102 => '请求通商   ***需要log',
			43103 => '通过  ***需要log',
			43104 => '主城--商队--取消通商  ***需要log',
			43105 => '拒绝  ***需要log',
			43106 => '设定是否自动通过通商申请',
			44401 => '获取系统活动信息',
			44402 => '领取玩家的活动奖励  ***需要log',
			44403 => '打開登錄活動',
			44404 => '領取獎勵',
			44101 => '接收任务',
			44102 => '取消任务',
			44103 => '领取奖励  ***需要log',
			44104 => '立即完成任务  ***需要log',
			44201 => '任务明细',
			44202 => '点击任务',
			44301 => '每日任务列表',
			44302 => '刷新任务  ***需要log',
			44303 => '刷新任务(金币刷新)  ***需要log',
			45100 => '读取邮件列表',
			45102 => '将邮件设为已读状态',
			45103 => '发送/回复新邮件',
			45104 => '保存邮件',
			45105 => '删除邮件',
			45106 => '删除当页所有邮件',
			45107 => '删除所有邮件',
			45200 => '点击纺织局',
			45201 => '点击组队界面',
			45202 => '建队',
			45205 => '选择模式 ',
			45206 => '踢人',
			45207 => '解散',
			45208 => '点击制造  ***需要log',
			45209 => '加入',
			45210 => '退出',
			45211 => '转生',
			45212 => '买纺织次数  ***需要log',
			45213 => '点击清零 ***这个command未实现',
			45214 => '清零-确定 ***这个command未实现',
			43200 => '打开商盟',
			43201 => '获取以聘请商人id ',
			43202 => '聘请商人id ***需要log',
			43203 => '购买好多纺织次数',
			43204 => '购买好多纺织次数',
			43205 => '聘请全部商人',
			46300 => '获取城市排行',
			46301 => '获取农场排行',
			46302 => '获取军团排行',
			46304 => '获取威望排行',
			46305 => '获取等级排行 获取敌对排行',
			47001 => '创建队五',
			47002 => '加入队五',
			47003 => '踢走成员',
			47004 => '调整成员排列',
			47005 => '退出队伍',
			47006 => '解散队伍',
			47007 => '開始戰鬥 ***需要log',
			47008 => '读取战斗数据',
			47101 => '玩家进入战役  ***需要log',
			47102 => '战役中移动',
			47103 => '战役中攻击 ***需要log',
			47104 => '补给 ***需要log',
			47105 => '玩家使用令牌',
			47106 => '玩家退出战役',
			47107 => '玩家主动下载战役信息',
			47110 => '缩短战役花费时间 ***需要log',
			47201 => '获取所有老大信息',
			47202 => '进入该boss场景',
			47203 => '打boss',
			47204 => '退出boss',
			47205 => '鼓舞',
			51003 => '72小时免战',
			51004 => '72小时免战 确定',
			51101 => '年兽  打开征信息',
			51102 => '年兽 创建队五',
			51103 => '年兽 加入队五',
			51104 => '年兽 战斗',
			51105 => '匝蛋活动',
			52101 => '保存所有的flash界面配置',
			52102 => '保存flash界面的聊天配置',
			52103 => '获取某个玩家的所有flash界面配置',
			52104 => '保存某个类型的cd设置',
			60001  => '获取临时活动列表（显示在每日任务表旁边的）',
			60002  => '领取',
			60003  => '获取周年活动信息',
			60004  => '领取奖励',
			60005  => '排行榜',
			60006  => '領取積分獎品',
			60007  => '读取领奖信息',
			61001 => '报名',
			61002 => '同服同步',
			61003 => '同服鼓舞',
			61004 => '打開面板',
			53002 => '获得对阵表',
			53004 => '檢查玩家是否有足夠金幣',
			53005 => '献花',
			53006 => '给玩家发比赛胜胜利的奖励',
			53007 => '根据raceId获取每轮比赛的raceResult',
			53008 => '鼓舞確認',
			53009 => '鼓舞',
			53101 => '获取战报内容',
			53102 => '跨服同步',
			53103 => '在對陣表中點擊獲得歷史戰績',
			53104 => '跨服比賽中獲得勝利',
			53105 => '扣除跨服比赛中玩家鼓舞的所需金币',
			53106 => '同步跨服比賽中玩家的數據',
			53107 => '獲取跨服賽獎勵',
			53108 => '領取跨服賽獎勵',
			60008 => '活动排行榜',
			60009 => '将当前服务器的献花数据更新到当前服务器中',
			60010 => '将跨服服务器的鼓舞数据更新到当前服务器中',
			60011 => '将跨服服务器中的当前同步数据更新到',
			60012 => '将跨服服务器中的历史同步数据更新到子服务器中',
			60013 => '将跨服服务器中的匹配数据更新到子服务器中',
			60014 => '将跨服服务器中的对阵表数据更新到子服务器中',
			81001 => '打开阵灵天赋界面',
			81002 => '開啟陣靈天賦結點',
			81003 => '打开幻化阵灵天赋结点',
			71007 => '参与叛国',
			71002 => '选择国家',
			71003 => '叛国 －－ 确定 －－－ 出威望武将选择',
			71004 => '叛国',
			71005 => '金幣購買位置增加武將',
			71006 => '参与叛国－－ 取消',
			60601 => '查看奖励内容',
			60602 => '刷新奖励内容 ***需要log',
			60603 => '领取奖励 ***需要log',
			60604 => '生成奖励',
			60605 => '打开通辑面板',
			60606 => '通辑',
			60701 => ' 查看签到',
			60702 => ' 领取签到奖励 ***需要log',
			60703 => ' 补签 ***需要log',
			60801 => '打开图腾',
			60802 => '升级图腾',
			60803 => '銀幣－－增加--確定',
			60804 => '銀幣－－返回--確定',
			60901 => '打开合成面板',
			60902 => '獲得沒有使用的星魂列表',
			60903 => '合成星魂',
			60904 => '打开附魂界面时获得武将分页列表',
			60905 => '開啟武將中的星魂格子',
			60906 => '附魂',
			60907 => '拆卸星魂',
			60908 => '获得可以购买的星魂分页列表',
			60910 => '獲得單個武將的相關信息(包括星魂)',
			60911 => '确认開啟武將中的星魂格子的金币',
			60912 => '購買星魂',
			60913 => '購買多個星魂',
		);
	}
	
	public function actionRewardRank(){
		$this->_createServerList();
		$sendData	=	array();
		if($_GET['playerId']){
			$sendData['playerId']	=	$_GET['playerId'];
		}
		if($_GET['activityId']){
			$sendData['activityId']	=	$_GET['activityId'];
		}
		if($_GET['rewardId']){
			$sendData['rewardId']	=	$_GET['rewardId'];
		}
		$serverList = $this->_getGlobalData('gameser_list');
		$server = $serverList[$_GET["server_id"]];
		$post['sid'] = $server['marking'];//'192.168.12.127:8080';//
		$post['time'] = CURRENT_TIME.'000';	//时间（毫秒）
		$post['sign'] = md5($post['sid'].$this->_key.$post['time']);
		
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->addHttp($server['server_url'],'reward/rewardRank',$sendData,$post);
		
		$this->_utilHttpMInterface->send();
		$arr	=	$this->_utilHttpMInterface->getResults();
		//print_r($arr);
		if($arr){
			$arr	=	json_decode($arr['1'],true);
			foreach($arr['result'] as &$value){
				if($value['isReceive']==1){
					$value['isReceive']	=	"是";
				}else{
					$value['isReceive']	=	"否";
				}
			}
			$this->_view->assign('data',$arr['result']);
		}
		$this->_view->assign('server_id',$_GET["server_id"]);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
}