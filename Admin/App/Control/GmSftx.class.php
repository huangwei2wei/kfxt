<?php
Tools::import('Control_BaseGm');
/**
 * GM工具-三分天下
 * @author PHP-朱磊
 *
 */
class Control_GmSftx extends BaseGm {
	
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
	
	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc;
	
	
	private $_key = '1mXz0G4LJ24AGmPcS90091AP';


	public function __construct(){
		$this->game_id=3;
		$this->_createView();
		$this->_createUrl();
		parent::_checkOperatorAct();
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

		$gameServerList=$this->_getGlobalData('server/server_list_3');	
			
		//《时差 by xingyuan		
		if(isset($gameServerList[$_REQUEST['server_id']]['timezone']) && !empty($gameServerList[$_REQUEST['server_id']]['timezone']) ){
			$TimeZone = $gameServerList[$_REQUEST['server_id']]['timezone'];
			if(intval($TimeZone)){
				$this->_timeDifference = intval($TimeZone)*3600;
			}else{
				date_default_timezone_set($TimeZone);
				if('PRC' == date_default_timezone_get()){
					$this->_view->assign('BeiJing_time',true);
				}
			}
		}
		elseif(isset($gameServerList[$_REQUEST['server_id']]['time_zone'])){
			$TimeDifference = $gameServerList[$_REQUEST['server_id']]['time_zone'];
			$this->_timeDifference = intval($TimeDifference)*3600;
			if($this->_timeDifference == 0){
				$this->_view->assign('BeiJing_time',true);
			}
		}
		//时差》
		
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','GmSftx/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}		
	}
	
	/**
	 * 建立多服务器列表
	 */
	private function _createMultiServerList(){
		$gameServerList=$this->_getGlobalData('gameser_list');
		foreach ($gameServerList as $key=>&$value){
			if ($value['game_type_id']!=3)unset($gameServerList[$key]);
		}
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','GmSftx/MultiServerSelect.html');
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
	/**
	 * 多服发邮件
	 */
	public function actionMultiEmail(){
		switch ($_GET['doaction']){
			case 'sendMultiEmail' :{
				$this->_multiEmailAdd();
				return ;
			}
			case 'delEmail' :{
				$this->_delEmail();
				return ;
			}
			default:{
				$this->_multiEmailIndex();
				return ;
			}
		}
	}
	private function _multiEmailIndex(){
		$this->_createServerList();
		$URL_sendMultiEmail = Tools::url(CONTROL,ACTION,array('doaction'=>'sendMultiEmail','server_id'=>$_REQUEST['server_id']));
		
		$this->_view->assign('URL_sendMultiEmail',$URL_sendMultiEmail);
		
		if ($_REQUEST['server_id']){
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'sysMail','act'=>'selectNotices');
			$sendParams = Tools::getFilterRequestParam();
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$sendParams);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (is_array($data)){
				if( is_array($data['data']['result'])){
					foreach($data['data']['result'] as &$sub){
						if($this->_timeDifference!=0){
							$sub['begin'] = intval($sub['begin'])+$this->_timeDifference;
							$sub['end'] = intval($sub['end'])+$this->_timeDifference;
							$sub['createAt'] = intval($sub['createAt'])+$this->_timeDifference;
						}
						$sub['URL_delEmail'] = Tools::url(CONTROL,ACTION,array('doaction'=>'delEmail','server_id'=>$_REQUEST['server_id'],'id'=>$sub['id']));
					}
				}
				$this->_view->assign('dataList',$data['data']['result']);
				$this->_loadCore('Help_Page');
				$helpPage=new Help_Page(array('total'=>$data['data']['totalCount'],'perpage'=>$data['data']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/EmailList.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _multiEmailAdd(){
		$this->_createMultiServerList();
		
		if ($this->_isAjax()){
			$server_id = $_REQUEST['server_id'];
			if (!isset($server_id)){
				$this->_returnAjaxJson(array('status'=>0,'info'=>'请选择服务器'));
			}
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'sysMail','act'=>'save');
			$_POST['url']=urlencode($_POST['url']);
			$postData = array(
				    'begin' => strtotime($_POST['begin']),
				    'end' => strtotime($_POST['end']),
				    'interval' => $_POST['IntervalTime'],
				    'title' => $_POST['title'],
				    'content' => $_POST['content'],
				    'isNew' => $_POST['isNew'],
					);
			$this->_utilApiSftx->addHttp($server_id,$getArr,$postData);
			$this->_utilApiSftx->send();
			$datas=$this->_utilApiSftx->getResults();
			
			$message=array();
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($datas as $key=>$value){
				if ($value['status']==1)
					$this->_returnAjaxJson(array('status'=>1,'info'=>'操作成功'));
				else 
					$this->_returnAjaxJson(array('status'=>0,'info'=>'操作失败'));
			}
		}
		$isNew = array(1=>'是',0=>'否');
		$this->_view->assign('isNew',$isNew);
		$this->_view->set_tpl(array('body'=>'GmSftx/sendMultiEmailList.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
		
	}
	private function _delEmail(){
			$server_id = $_REQUEST['server_id'];
			if (!isset($server_id)){
				$this->_returnAjaxJson(array('status'=>0,'info'=>'请选择服务器'));
			}
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'sysMail','act'=>'deleteNotice');
			$postData = array('idList'=>array(0=>$_GET['id']));
			$this->_utilApiSftx->addHttp($server_id,$getArr,$postData);
			$this->_utilApiSftx->send();
			$datas=$this->_utilApiSftx->getResults();
			$message=array();
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($datas as $key=>$value){
				if ($value['status']==1)
					array_push($message,"{$serverList[$key]['server_name']} <font color='#00cc00'>删除成功</font>");
				else
					array_push($message,"{$serverList[$key]['server_name']} <font color='#FF0000'>删除失败</font>");
			}
			$message=Tools::formatLog($message);
			$this->_utilMsg->showMsg($message,1,1,null);
	}
	
	
	
	
	
	
	private function _multiNoticeAdd(){
		if ($this->_isPost()){
			if (!count($_REQUEST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'oneNotice','act'=>'save');
			$_POST['url']=urlencode($_POST['url']);
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
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'oneNotice','act'=>'deleteNotice');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if (!is_array($data))$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'oneNotice','act'=>'save');
			$_POST['title']=urlencode($_POST['title']);
			$_POST['content']=urlencode($_POST['content']);	
			$_POST['url']=urlencode($_POST['url']);		
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if (!is_array($data))$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-2);
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('ADD_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
				
				if( $this->_timeDifference!=0 && is_array($data['data']['result'])){
					foreach($data['data']['result'] as &$sub){
						$sub['begin'] = intval($sub['begin'])+$this->_timeDifference;
						$sub['end'] = intval($sub['end'])+$this->_timeDifference;
						$sub['createAt'] = intval($sub['createAt'])+$this->_timeDifference;
					}
				}
				
				$this->_view->assign('dataList',$data['data']['result']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
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
				foreach ($dataList as &$sub){
					$sub[URL_del] = Tools::url(CONTROL,'UserDel',array('server_id'=>$_REQUEST['server_id'],'playerId'=>$sub['id']));
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
				//用于是否显示删除用户的链接
				$PermissionUserDel = $this->checkAct(CONTROL.'_UserDel');
				$this->_view->assign('PermissionUserDel',$PermissionUserDel);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/User.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}	
	/**
	 * 用户删除
	 */
	public function actionUserDel(){
		if($_REQUEST['server_id'] && $playerId = trim($_GET['playerId']) ){
			$serverList = $this->_getGlobalData('server/server_list_3');
			$server = $serverList[$_REQUEST['server_id']];
			$post['playerId'] = $playerId;
			$post['sid'] = $server['marking'];//'192.168.12.127:8080';//
			$post['time'] = CURRENT_TIME.'000';	//时间（毫秒）
			$post['sign'] = md5($post['sid'].$this->_key.$post['time']);
			$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
			$this->_utilHttpMInterface->addHttp($server['server_url'],'player/delPlayerById',array(),$post);
			$this->_utilHttpMInterface->send();
			$data = $this->_utilHttpMInterface->getResults();
			$dataInfo = json_decode(array_shift($data),true);
//			if($data['status']==1){
//				$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1);
//			}
		}
//		$this->_utilMsg->showMsg(Tools::getLang('DEL_ERROR','Common'),-1);
		$this->_utilMsg->showMsg($dataInfo,1);
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
				
				if( $this->_timeDifference!=0){
					foreach($dataList as &$sub){
						$sub['createAtStr'] = intval($sub['createAtStr'])+$this->_timeDifference;
					}
				}
				
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
				//xy add
				$selected['detailId'] = $_GET['detailId']?$_GET['detailId']:0;
				$selected['playerId'] = $_GET['playerId']?$_GET['playerId']:0;
				$this->_view->assign('selected',$selected);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
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
				
				if( $this->_timeDifference!=0){
					foreach($dataList as &$sub){
						$sub['createAtStr'] = intval($sub['createAtStr'])+$this->_timeDifference;
					}
				}
				
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
				//xy add
				$selected['detailId'] = $_GET['detailId']?$_GET['detailId']:0;
				$selected['playerId'] = $_GET['playerId']?$_GET['playerId']:0;
				$this->_view->assign('selected',$selected);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
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
				foreach ($dataList as &$list){
					$list['typeDes']=isset(Util_ApiSftx::$logType[$list['type']])?Util_ApiSftx::$logType[$list['type']]:$list['type'];
					$list['subTypeDes']=isset(Util_ApiSftx::$logType[$list['subType']])?Util_ApiSftx::$logType[$list['subType']]:$list['subType'];
					$list['logTime'] = intval($list['logTime'])+$this->_timeDifference;
				}
				$this->_view->assign('dataList',$dataList);
				$this->_loadCore('Help_Page');
				$data['data']['dataList']['totalCount']=$data['data']['dataList']['totalCount']?$data['data']['dataList']['totalCount']:1;
				$data['data']['dataList']['pageSize']=$data['data']['dataList']['pageSize']?$data['data']['dataList']['pageSize']:PAGE_SIZE;
				$helpPage=new Help_Page(array('total'=>$data['data']['dataList']['totalCount'],'perpage'=>$data['data']['dataList']['pageSize'],'pagename'=>'pageNo'));
				$this->_view->assign('pageBox',$helpPage->show());
				$this->_view->assign('optionList',$data['data']['optionList']);
				$this->_view->assign('selectedArr',$data['data']['select']);
				//xy add
				$selected['detailId'] = $_GET['detailId']?$_GET['detailId']:0;
				$selected['playerId'] = $_GET['playerId']?$_GET['playerId']:0;
				$this->_view->assign('selected',$selected);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
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
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseUser2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('USER_UNLOCKSUCCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockUserDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseUser2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
						$list['begin']=date('Y-m-d H:i:s',intval($list['begin'])+$this->_timeDifference);
						$list['end']=date('Y-m-d H:i:s',intval($list['end'])+$this->_timeDifference);
						$list['createAt']=date('Y-m-d H:i:s',intval($list['createAt'])+$this->_timeDifference);
						$list['url_release']=Tools::url(CONTROL,ACTION,array('doaction'=>'release','id'=>$list['uid'],'server_id'=>$_REQUEST['server_id']));
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
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LockUser.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _lockUserAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseUser2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('USER_LOCKSUCCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseMessage2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('USER_UNLOCKSUCCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _donttalkDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseMessage2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
						$list['begin']=date('Y-m-d H:i:s',intval($list['begin'])+$this->_timeDifference);
						$list['end']=date('Y-m-d H:i:s',intval($list['end'])+$this->_timeDifference);
						$list['createAt']=date('Y-m-d H:i:s',intval($list['createAt'])+$this->_timeDifference);
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
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/Donttalk.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _donttalkAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseMessage2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('USER_DONTTALKSUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseIP2','act'=>'release','id'=>$_GET['id']);
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('USER_UNLOCKSUCCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
		}else {
			$this->_utilMsg->showMsg($data['info'],-2);
		}
	}
	
	private function _lockIPDel(){
		if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
		$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
		$getArr=array('ctl'=>'refuseIP2','act'=>'delete');
		$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
		$this->_utilApiSftx->send();
		$data=$this->_utilApiSftx->getResult();
		
		if ($data['status']==1){
			$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
						$list['begin']=date('Y-m-d H:i:s',intval($list['begin'])+$this->_timeDifference);
						$list['end']=date('Y-m-d H:i:s',intval($list['end'])+$this->_timeDifference);
						$list['createAt']=date('Y-m-d H:i:s',intval($list['createAt'])+$this->_timeDifference);
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
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LockIP.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _lockipAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'refuseIP2','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('IP_LOCKSUCCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'msg','act'=>'save');
			//			http://www.xxx.com/?ctl=msg&act=save
//			$_POST['title'] = urlencode($_POST['title']);
//			$_POST['content'] = urlencode($_POST['content']);
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('SEND_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
			}else {
				$this->_utilMsg->showMsg($data['info'],-2);
			}
		}else {
			$this->_createServerList();
			if ($_REQUEST['users'])$this->_view->assign('users',implode(',',$_REQUEST['users']));
			if ($_REQUEST['idList'])$this->_view->assign('users',implode(',',$_REQUEST['idList']));
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
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'GmSftx/LibaoCard.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _libaoCardAdd(){
		if ($this->_isPost() && $_POST['submit']){
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
			$getArr=array('ctl'=>'card','act'=>'save');
			$this->_utilApiSftx->addHttp($_REQUEST['server_id'],$getArr,$_POST);
			$this->_utilApiSftx->send();
			$data=$this->_utilApiSftx->getResult();
			if ($data['status']==1){
				$this->_utilMsg->showMsg(Tools::getLang('CARD_ADDSUCESS',__CLASS__),1,Tools::url(CONTROL,ACTION,array('server_id'=>$_REQUEST['server_id'])));
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
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
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
	
	private function _modifyNameIndex(){				
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$_POST['playerType'] = intval($_POST['playerType']);
			$_POST['player'] = trim($_POST['player']);
			$rpc = $this->_getGlobalData('Util_Rpc','object');
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
				$jump = Tools::url(CONTROL,'ModifyName',array('server_id'=>$_REQUEST['server_id']));
				if(intval($dataList['playerId']) == 0){
					$this->_utilMsg->showMsg(Tools::getLang('NO_RESULT','Common'),-1,$jump);
				}
				$this->_view->assign('URL_ReQuery',$jump);
				$this->_view->assign('dataList',$dataList);
			}
		}
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _modifyNameDoit(){
		if(!$_REQUEST['server_id']){
			$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);		
		}
		if ($this->_isPost()){
			$_POST['playerId'] = intval($_POST['playerId']);
			$_POST['rename'] = trim($_POST['rename']);
			if($_POST['playerId']<=0 && $_POST['rename']==''){
				$this->_utilMsg->showMsg('Error Submit',-1);	
			}
			$rpc = $this->_getGlobalData('Util_Rpc','object');
			$rpc->setUrl($_REQUEST['server_id'],'api/modifyPlayer');			
			$dataList=$rpc->modifyName($_POST['playerId'],$_POST['rename']);
			if($dataList instanceof PHPRPC_Error){
				$this->_utilMsg->showMsg($dataList->Message,-1);
			}elseif($dataList){
				$jump = Tools::url(CONTROL,'ModifyName',array('server_id'=>$_REQUEST['server_id']));
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$jump,1);	
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'),-1);
			}
		}else{
			$this->_utilMsg->showMsg('submit not by POST',-1);	
		}
	}
	
	
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
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1);
			}else{
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'),-1);
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
	
	private function _updaterewardConfLog($postData){
		$effectiveData = $this->_updateRewardConfig(true);
		$str = '';
		$postData = array_filter($postData,'strlen');
		foreach($effectiveData as $field => $name){
			if(isset($postData[$field])){
				$str .= $name . intval($postData[$field]).'; ';
			}
		}
		return $str;
	}
	
	private function _checkupdatereward(){
		$serverList = $this->_getGlobalData('server/server_list_3');
		$server = $serverList[$_GET["server_id"]];
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->addHttp($server['server_url'],'playerReward/isExist',array('uid'=>$_GET["ids"]));
		$this->_utilHttpMInterface->send();
		$arr	=	$this->_utilHttpMInterface->getResults();
		echo $arr['1'];
	}
	
	private function _updaterewardindex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $this->_isPost()){				
			$serverList = $this->_getGlobalData('server/server_list_3');
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
//				print_r($dataResult);
//				die();
				if(is_array($dataResult) && 'success' == strval($dataResult['m']['message'])){
//				if('success' == $dataResult){
					$sendtype	=	array(
						'1'	=>	Tools::getLang('SYS_GOLE',__CLASS__),
						'2'	=>	Tools::getLang('USER_GOLD',__CLASS__),
					);
					$log	=	"资源修改用户：".$_POST['uid'].$_POST['username'].";<br>";
					$log	.=	"发送类型为：".$sendtype[$_POST['goldtype']].";<br>";
					$log	.=	$this->_updaterewardConfLog($_POST);
					$log .= "<br/>道具ID:{$_POST['goodsId']}";
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
						array('操作','<font style="color:#F00">玩家资源修改</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
						array('操作原因',$_POST['cause']),
						array('修改内容',"<div style='margin-left:10px;'>$log</div>"),
					);	
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore(array('UserId'=>'0'),6,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
					$this->_utilMsg->showMsg($dataResult['m']['message'].";".$dataResult['m']['STARSOUL'],1);
				}else{
					$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common').' '.$dataResult,-1);
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
	 * 新玩家操作日志
	 * Enter description here ...
	 */
	public function actionPlayerLogFind(){
		switch ($_REQUEST['doaction']){
			case 'ordermun':
				$this->_ordermun();
				return ;
			default:
				$this->_playerLogFind();
				
		}
	}
	
	private function _playerLogFind(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id'] && $_REQUEST['submit']){				
			$serverList = $this->_getGlobalData('server/server_list_3');
			$server = $serverList[$_REQUEST['server_id']];
			if($server){
				//pageSize 分页大小，可空，默认15
				//currentPage 当前页数
				//startTime 开始时间（不能为空，初始传0,时间为毫秒）
				//endTime 结束时间（不能为空，初始传0,时间为毫秒）
				//playerId 玩家uid
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
				$post['cmdID'] = $_GET['cmdID'];
				$post['playerId'] = $_GET['playerId'];
				$post['startTime'] = $startTime;
				$post['endTime'] = $endTime;
				
				$post['sid'] = $server['marking'];//'192.168.12.127:8080';//
				$post['time'] = CURRENT_TIME.'000';	//时间（毫秒）
				$post['sign'] = md5($post['sid'].$this->_key.$post['time']);
				
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
		$ajaxurl	=	Tools::url(CONTROL,ACTION,array("doaction"=>"ordermun","time"=>CURRENT_TIME));
		$this->_view->assign('ajaxurl',$ajaxurl);
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _ordermun(){
		$getvalue	=	$this->_getLogDetail();
		$value = strval($_POST["value"]);
		if(empty($value)){
			$returnDate = array('status'=>0,'info'=>'Parameters can not be empty','data'=>NULL);
			$this->_returnAjaxJson($returnDate);
			return;
		}
		foreach($getvalue as $key=>$_msg){
			if(stripos($_msg,$value)!==FALSE){
				$returninfo[$key] = $_msg;
			}
		}
		if(empty($returninfo)){
			$returnDate = array('status'=>0,'info'=>'no results','data'=>NULL);
		}else{
			$returnDate = array('status'=>1,'info'=>NULL,'data'=>$returninfo);
		}
		$this->_returnAjaxJson($returnDate);
	}
	
	private function _getLogDetail(){
		return Tools::gameConfig('LogDetail',$this->game_id);
	}
	
	public function actionRewardRank(){
		$this->_checkOperatorAct();
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
		$serverList = $this->_getGlobalData('server/server_list_3');
		$server = $serverList[$_GET["server_id"]];
		$post['sid'] = $server['marking'];//'192.168.12.127:8080';//
		$post['time'] = CURRENT_TIME.'000';	//时间（毫秒）
		$post['sign'] = md5($post['sid'].$this->_key.$post['time']);
		
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->addHttp($server['server_url'],'reward/rewardRank',$sendData,$post);
		
		$this->_utilHttpMInterface->send();
		$arr	=	$this->_utilHttpMInterface->getResults();
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
		
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	public function actionSearchPlayerEmail(){
// 		$this->_checkOperatorAct();
// 		$this->_createServerList();
		if($this->_isAjax()){
// 			$serverList = $this->_getGlobalData('server/server_list_3');
// 			$server = $serverList[$_POST["server_id"]];
			
			$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
			$post = array('uid'=>$_POST['uid'],'platformKey'=>$_POST['platformKey']);
			$this->_utilHttpMInterface->addHttp('http://s0.3f.uwan.com/wfgm/','queryPlayerMail/query',array(),$post);
			$this->_utilHttpMInterface->send();
			$dataResult = $this->_utilHttpMInterface->getResults();
			if($dataResult){
				$this->_returnAjaxJson($dataResult[1]);
			}else{
				$this->_returnAjaxJson("失败");
			}
		}
		
		
		$this->_view->assign('searchType',$this->_searchType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	
}