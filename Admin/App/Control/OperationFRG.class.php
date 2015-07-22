<?php
Tools::import('Control_BaseGm');
/**
 * 运营群发功能
 * @author php-朱磊
 *
 */
class Control_OperationFRG extends BaseGm {

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Util_FRGInterface
	 * @var Util_FRGInterface
	 */
	private $_utilFRGInterface;

	/**
	 * Model_ApplyDataFrg
	 * @var Model_ApplyDataFrg
	 */
	private $_modelApplyDataFrg;

	/**
	 * Model_FrgNotice
	 * @var Model_FrgNotice
	 */
	private $_modelFrgNotice;

	/**
	 * Model_FrgReward
	 * @var Model_FrgReward
	 */
	private $_modelFrgReward;

	/**
	 * 富人国礼包管理表
	 * @var Model_FrgLibao
	 */
	private $_modelFrgLibao;

	/**
	 * 富 人国玩家导入数据表
	 * @var Model_FrgPlayerData
	 */
	private $_modelFrgPlayerData;

	/**
	 * 新版富人国API接口,主要解决并发问题
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;

	/**
	 * 默认通哪过个服务器的id来获取数据库,用于显示页面表单
	 * @var int
	 */
	private $_defaultServerId=DEFAULT_FRG_SERVER_ID;
	
	/**
	 * 联运链接
	 * @var unknown_type
	 */
	private $_modelLyLink;
	
	/**
	 * 特殊活动
	 * @var unknown_type
	 */
	private $_modelFrgSpecialActivity;
	
	/**
	 * 游戏后台操作日志
	 * @var ModelGameOperateLog
	 */
	private $_modelGameOperateLog;

	public function __construct(){
		$this->game_id=2;
		$this->_createView();
		$this->_createUrl();
		$this->_checkOperatorsAct();	//多服务器权限检测操作
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
	}



	private function _createUrl(){
		$this->_url['OperationFRG_BatchNotice']=Tools::url(CONTROL,'Notice');
		$this->_url['OperationFRG_BatchNoticeAdd']=Tools::url(CONTROL,'Notice',array('doaction'=>'add'));
		$this->_url['OperationFRG_SynNotice']=Tools::url(CONTROL,'Notice',array('doaction'=>'syn'));
		$this->_url['OperationFRG_NoticeDel']=Tools::url(CONTROL,'Notice',array('doaction'=>'del'));


		$this->_url['OperationFRG_BatchRewardAdd']=Tools::url(CONTROL,'Reward',array('doaction'=>'add'));
		$this->_url['OperationFRG_BatchReward']=Tools::url(CONTROL,'Reward');
		$this->_url['OperationFRG_SynReward']=Tools::url(CONTROL,'Reward',array('doaction'=>'syn'));
		$this->_url['OperationFRG_RewardDel']=Tools::url(CONTROL,'Reward',array('doaction'=>'del'));

		$this->_url['OperationFRG_ImportPlayerData']=Tools::url(CONTROL,'ImportPlayerData');
		$this->_url['OperationFRG_PlayerData_del']=Tools::url(CONTROL,'PlayerData',array('doaction'=>'del'));
		$this->_url['OperationFRG_PlayerData_import']=Tools::url(CONTROL,'PlayerData',array('doaction'=>'import'));
		$this->_url['OperationFRG_PlayerData_import_excel']=Tools::url(CONTROL,'PlayerData',array('doaction'=>'import_excel'));
		
		$this->_url['OperationFRG_AuditDel']=Tools::url(CONTROL,'AuditDel');
		$this->_url['OperationFRG_AuditAccept']=Tools::url(CONTROL,'AuditAccept');
		$this->_url['OperationFRG_AuditReject']=Tools::url(CONTROL,'AuditReject');
		$this->_url['OperationFRG_BatchRewardEdit']=Tools::url(CONTROL,'BatchRewardEdit');

		$this->_url['OperationFRG_Libao']=Tools::url(CONTROL,'Libao');
		$this->_url['OperationFRG_AddLibao']=Tools::url(CONTROL,'Libao',array('doaction'=>'add'));
		$this->_url['OperationFRG_SynLibao']=Tools::url(CONTROL,'Libao',array('doaction'=>'syn'));
		$this->_url['OperationFRG_DelLibao']=Tools::url(CONTROL,'Libao',array('doaction'=>'del'));
		$this->_url['OperationFRG_EditLibao']=Tools::url(CONTROL,'Libao',array('doaction'=>'edit'));

		$this->_url['OperationFRG_AddCard']=Tools::url(CONTROL,'AddCard');
		$this->_url['OperationFRG_RewardBefore']=Tools::url(CONTROL,'RewardBefore');

		$this->_url['OperationFRG_KeyWords_RegName']=Tools::url(CONTROL,'KeyWords');
		$this->_url['OperationFRG_KeyWords_Regular']=Tools::url(CONTROL,'KeyWords',array('doaction'=>'regular'));

		$this->_url['Link_Add']=Tools::url(CONTROL,'LinkOpt',array('doaction'=>'add'));
		$this->_url['Link']=Tools::url(CONTROL,'Link',array());
		
		$this->_url['OperationFRG_SpecialActivity'] = Tools::url(CONTROL,'SpecialActivity');
		$this->_url['OperationFRG_SpecialActivity_Syn'] = Tools::url(CONTROL,'SpecialActivity',array('doaction'=>'syn'));
		$this->_url['OperationFRG_SpecialActivity_Del'] = Tools::url(CONTROL,'SpecialActivity',array('doaction'=>'del'));
		$this->_url['OperationFRG_SpecialActivityDetail'] = Tools::url(CONTROL,'SpecialActivity',array('doaction'=>'serverDetail'));

		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 建立服务器选择列表
	 */
	private function _createServerList(){
		$gameServerList=$this->_getGlobalData('server/server_list_'.$this->game_id);
		unset($gameServerList[200]);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		foreach ($gameServerList as &$server){
			unset($server['game_type_id'],$server['room_id'],$server['marking'],$server['time_zone'],$server['server_url']);
		}
		$this->_view->assign('tplServerSelect','OperationFRG/ServerSelect.html');
	}

	/**
	 * 公告管理
	 */
	public function actionNotice(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_noticeAdd();
				return ;
			}
			case 'syn' :{
				$this->_noticeSyn();
				return ;
			}
			case 'edit' :{
				$this->_noticeEdit();
				return ;
			}
			case 'serverDetail' :{
				$this->_noticeServerDetail();
				return ;
			}
			case 'del' :{
				$this->_noticeDel();
				return ;
			}
			case 'serverSyn' :{
				$this->_serverSyn();
				return ;
			}
			case 'ajaxSyn':{
				$this->_noticeAjaxSyn();
				return;
			}
			default:{
				$this->_noticeIndex();
				return ;
			}
		}
	}
	
	/**
	 * ajax同步系统公告
	 */
	private function _noticeAjaxSyn(){
		$data = array('status'=>0,'info'=>'操作失败','data'=>null);
		if (!count($_POST['data'])){
			$data['info'] = '无同步数据';
			$this->_returnAjaxJson($data);
		}
		if(!$_POST['server_id']){
			$data['info'] = '服务器id错误';
			$this->_returnAjaxJson($data);
		}
		if($this->_isPost()){
			$postArr=array('NoticeArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_POST['server_id'],array('c'=>'SystemNotice','a'=>'Add','doaction'=>'receive'),$postArr);
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			$sendStatusMsgs='';
			foreach ($getResult as $key=>$value){
				if ($value['msgno']==1){
					$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_SUCCESS','Common');
					$sendStatusMsgs.="<font color='#00CC00'>{$value['message']}</font>";
				}else {
					$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_FAILURE','Common');
					$sendStatusMsgs.="<font color='#FF0000'>{$value['message']}</font>";
				}
			}
			$data['info'] = $sendStatusMsgs;
			$data['status'] = 1;
		}
		$this->_returnAjaxJson($data);
	}

	/**
	 * 服务器同步公告
	 */
	private function _serverSyn(){
		if ($this->_isPost() && $_POST['submit']){
			//屏蔽原有流程
			$this->_utilMsg->showMsg("此同步已屏蔽",-1);
			
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_SERVER_FOR_SYN',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('NO_ANNOUNCEMENT_TO_SYN',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);
			$postArr=array('NoticeArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,array('c'=>'SystemNotice','a'=>'Add','doaction'=>'receive'),$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($getResult);
			$this->_utilMsg->showMsg($msg,1,Tools::url('MasterFRG','Notice'));
		}else {
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_ANNOUNCEMENT_FOR_SYN',__CLASS__),-1);
			$selectedIds=array_unique($_REQUEST['Id']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'SystemNotice','a'=>'ShowList'));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if (!count($data['data']['list']))$this->_utilMsg->showMsg(Tools::getLang('NO_ANNOUNCEMENT_FOR_SYN',__CLASS__),-1);
			$synArr=array();
			foreach ($data['data']['list'] as $list){
				if (in_array($list['Id'],$selectedIds)){
					unset($list['Id'],$list['CreateTime']);
					array_push($synArr,$list);
				}
			}
			if (!count($synArr))$this->_utilMsg->showMsg(Tools::getLang('NO_ANNOUNCEMENT_FOR_SYN',__CLASS__),-1);


			$this->_view->assign('dataList',$synArr);
			$this->_view->assign('serverName',$serverName);
			$this->_multiOperatorSelect('_getOperatorGroup');//使用_getOperatorGroup获得分组
			//$this->_createServerList();
			$this->_view->assign('URL_ajaxSyn',Tools::url(CONTROL,ACTION,array('doaction'=>'ajaxSyn')));
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/NoticeServerSyn.html'));
			$this->_view->display();
		}
	}

	private function _noticeDel(){
		if ($this->_isPost()){
			$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
			if ($_POST['title']){
				$this->_modelFrgNotice->delByTitle($_POST);
			}else {
				$this->_modelFrgNotice->delByids($_POST);
			}
			$this->_utilMsg->showMsg(false);
		}
	}

	private function _noticeSyn(){
		if ($this->_isPost()){
			if (!$_POST['operator_id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1);
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
			$getArr=array('c'=>'SystemNotice','a'=>'ShowList');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			foreach ($data as $key=>$dataList){
				if (count($dataList['data']['list'])){
					$this->_modelFrgNotice->synNotice($dataList['data']['list'],array('server_id'=>$key,'operator_id'=>$_POST['operator_id']));
				}else {
					$this->_modelFrgNotice->delByServerId($key);
				}
			}
			$this->_utilMsg->showMsg(Tools::getLang('SYN_SUCCESS','Common'));
		}
	}

	private function _noticeAdd(){
		if ($this->_isPost()){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1,2);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'SystemNotice','a'=>'Add','doaction'=>'saveadd');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$_POST);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($data);
			$this->_utilMsg->showMsg($msg,1);
		}else{
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);
			$this->_utilFRGInterface->setGet(array('c'=>'SystemNotice','a'=>'Add'));
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/BatchNoticeAdd.html'));
			$this->_view->display();
		}
	}

	private function _noticeEdit(){
		if ($this->_isPost()){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$ids=array();
			$post=$_POST;
			unset($post['server_ids'],$post['main_id'],$post['Id']);
			$getArr=array('c'=>'SystemNotice','a'=>'Add','doaction'=>'saveadd');
			foreach ($_POST['server_ids'] as $key=>$serverId){
				array_push($ids,$key);
				$curPost=$post;
				$curPost['Id']=$_POST['main_id'][$key];
				$this->_utilApiFrg->addHttp($serverId,$getArr,$curPost);
			}
			$this->_utilApiFrg->send();
			$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
			$updateArr=$post;
			$updateArr['auto_id']=$ids;
			$this->_modelFrgNotice->edit($updateArr);
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'));
		}else {
			$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
			$servers=$this->_modelFrgNotice->findServers($_GET);
			$servers=$servers['data']['servers'];
			$data=$this->_modelFrgNotice->findById($_GET['Id']);
			$this->_view->assign('servers',$servers);
			$this->_view->assign('data',$data);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/BatchNoticeEdit.html'));
			$this->_view->display();
		}
	}

	private function _noticeServerDetail(){
		$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
		$data=$this->_modelFrgNotice->findServers($_GET);
		$string=$_GET['title'] . '：';
		if ($data['data']['servers']){
			foreach ($data['data']['servers'] as $list){
				$string.=$list['server_name'].' . ';
			}
		}
		$data['data']['servers']=$string;
		$this->_returnAjaxJson($data);
	}

	private function _noticeIndex(){
		$this->_modelFrgNotice=$this->_getGlobalData('Model_FrgNotice','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelFrgNotice->tName());
		$helpSqlSearch->set_orderBy('create_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],10);
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("start_time>{$startTime} and end_time<{$endTime}");
			$this->_view->assign('selectedStartTime',$_REQUEST['start_time']);
			$this->_view->assign('selectedEndTime',$_REQUEST['end_time']);
		}
		$MyClass=$this->_utilRbac->getUserClass();
		$MyCount = $MyClass['_userName'];
		$MyOperators = $MyClass['_operatorIds'];
		$_REQUEST['operator_id'] = intval($_REQUEST['operator_id']);
		if ($_REQUEST['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
		}
		//如果不是管理员
		elseif(!in_array($MyCount,explode(',',MasterAccount) ) && is_array($MyOperators) ){
			$MyOperators = Model::getTtwoArrConvertOneArr($MyOperators,'operator_id','operator_id');
			if(count($MyOperators)>0){
				$MyOperators = implode(',',$MyOperators);
				$helpSqlSearch->set_conditions("operator_id in({$MyOperators})");
			}else{
				$helpSqlSearch->set_conditions("operator_id = 0");
			}
		}

		if ($_REQUEST['content']){
			$helpSqlSearch->set_conditions("(title like '%{$_REQUEST['content']}%' or content like '%{$_REQUEST['content']}%')");
			$this->_view->assign('selectedContent',$_REQUEST['content']);
		}

		if ($_REQUEST['is_group']){
			$helpSqlSearch->set_groupBy('title');
			$helpSqlSearch->set_field('count(*) as server_num,title,content,start_time,end_time,create_time,url,interval_time,Id,main_id');
			$this->_view->assign('selectedGroupBy',true);
		}
		$helpSqlSearch->set_orderBy('server_id');
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelFrgNotice->select($sql);
		if ($dataList){
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($dataList as &$list){
				$list['word_server_id']=$serverList[$list['server_id']]['full_name'];
				$list['start_time']=date('Y-m-d H:i:s',$list['start_time']);
				$list['end_time']=date('Y-m-d H:i:s',$list['end_time']);
				$list['last_send_time']=date('Y-m-d H:i:s',$list['last_send_time']);
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['url']=wordwrap($list['url'],60);
				if ($_REQUEST['is_group']){
					$list['url_server_detail']=Tools::url(CONTROL,'Notice',array('Id'=>$list['Id'],'title'=>urlencode($list['title']),'doaction'=>'serverDetail'));
					$list['url_edit']=Tools::url(CONTROL,'Notice',array('Id'=>$list['Id'],'title'=>urlencode($list['title']),'doaction'=>'edit'));
				}else {
					$list['url_edit']=Tools::url('MasterFRG','Notice',array('doaction'=>'edit','Id'=>$list['main_id'],'server_id'=>$list['server_id']));
				}
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelFrgNotice->findCount($conditions),'prepage'=>10));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_createServerList();
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'OperationFRG/BatchNotice.html'));
		$this->_view->display();
	}

	/**
	 * 多服奖励触发管理
	 */
	public function actionReward(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_rewardAdd();
				return ;
			}
			case 'edit' :{
				$this->_rewardEdit();
				return ;
			}
			case 'del' :{
				$this->_rewardDel();
				return ;
			}
			case 'serverDetail' :{
				$this->_rewardServerDetail();
				return ;
			}
			case 'syn' :{
				$this->_rewardSyn();
				return ;
			}
			case 'serversyn':{
				$this->_rewardServerSyn();
				return ;
			}
			default:{
				$this->_rewardIndex();
				return ;
			}
		}
	}

	private function _rewardAdd(){
		if ($this->_isPost()){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1,2);
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			$postArr=array();
			$postArr['server_ids']=$_POST['server_ids'];
			$postArr['type']=11;//多服务器奖励触发
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['send_action']=array('c'=>'Reward','a'=>'Add','Action'=>'Save');
			$postArr['post_data']=$_POST;
			$this->_modelApplyDataFrg->set_postArr($postArr);
			$data=$this->_modelApplyDataFrg->add();
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
		}else {
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$this->_view->assign('objData',json_encode($data['data']['ObjData']));
				$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
				$effData=$data['data']['ObjProp'];
				$newEffData=array();
				foreach ($effData as $key=>$value){
					$newEffData[$key]=array();
					foreach ($value as $childValue){
						$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
						$newEffData[$key][$childValue]['Name']=$data['data']['ObjData'][$key][$childValue]['Name'];
					}
				}
				$this->_view->assign('effData',json_encode($newEffData));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_ERROR_PAGE_INIT_ERROR',__CLASS__),-2);
			}
			$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
			$this->_createServerList();
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/BatchRewardAdd.html'));
			$this->_view->display();
		}
	}

	private function _rewardEdit(){
		$this->_createServerList();
		if ($this->_isAjax()){//提交表单
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add','Action'=>'Save'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
					$this->_modelFrgReward->edit($_POST);
					$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('SEND_SUCCESS','Common'),'data'=>array('server_id'=>$_POST['server_id'])));
				}else {
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>$data['message'],'data'=>array('server_id'=>$_POST['server_id'])));
				}
			}else {
					$this->_returnAjaxJson(array('status'=>-2,'msg'=>Tools::getLang('CONNECT_SERVER_ERROR','Common'),'data'=>array('server_id'=>$_POST['server_id'])));
			}
		}else {//显示表单
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add','Id'=>$_GET['Id']));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				Tools::import('Util_FRGTools');
				$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
				$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
				$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
				$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
				$this->_view->assign('data',$data['data']['Reward']);
				$this->_utilFrgTools->setEditResult($data['data']['Reward']);
				$dataResult=$this->_utilFrgTools->getEditResult();
				$this->_view->assign('chageCond',$dataResult['chageCond']);
				$this->_view->assign('chageEffect',$dataResult['chageEffect']);
				$this->_view->assign('chageTool',$dataResult['chageTool']);
				$this->_view->assign('num',$this->_utilFrgTools->getEditNum());

				$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
				$servers=$this->_modelFrgReward->findServers($_GET);
				$servers=$servers['data']['servers'];

				$everyDay=array('1'=>Tools::getLang('YES','Common'),'0'=>Tools::getLang('NO','Common'));
				$this->_view->assign('everyDayRadio',$everyDay);
				$this->_view->assign('servers',$servers);
				$this->_view->assign('systemTime',$data['data']['SYSTEM_TIME']);
				$this->_view->assign('readOnly',$data['data']['ReadOnly']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'OperationFRG/BatchRewardEdit.html'));
		$this->_view->display();
	}

	private function _rewardDel(){
		if ($this->_isPost()){
			$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
			if ($_POST['title']){
				$this->_modelFrgReward->delByTitle($_POST);
			}else {
				$this->_modelFrgReward->delByids($_POST);
			}
			$this->_utilMsg->showMsg(false);
		}
	}

	private function _rewardServerDetail(){
		$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
		$data=$this->_modelFrgReward->findServers($_GET);
		$string=$_GET['title'] . '：';
		if ($data['data']['servers']){
			foreach ($data['data']['servers'] as $list){
				$string.=$list['server_name'].' . ';
			}
		}
		$data['data']['servers']=$string;
		$this->_returnAjaxJson($data);
	}

	private function _rewardSyn(){
		if ($this->_isPost()){
			if (!$_POST['operator_id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1);
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
			$getArr=array('c'=>'Reward','a'=>'ShowList');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			foreach ($data as $key=>$dataList){
				if (count($dataList['data']['Result'])){
					$this->_modelFrgReward->syn($dataList['data']['Result'],array('server_id'=>$key,'operator_id'=>$_POST['operator_id']));
				}else {
					$this->_modelFrgReward->delByServerId($key);
				}
			}
			$this->_utilMsg->showMsg(Tools::getLang('SYN_SUCCESS','Common'));
		}
	}
	
	private function _rewardServerSyn(){
		if ($this->_isPost() && $_POST['submit']){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('LIBAO_SYN_SELECT_ERROR1',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('SPECIAL_AVTIVE_SYN_SELECT_ERROR2',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			foreach($_POST['data'] as $key =>$val){
				$postArr=array();
				$post_data = array();
				$post_data = unserialize(stripcslashes($val['post_data']));
				unset($val['post_data']);
				$post_data = array_merge($val,$post_data);
				$post_data['server_ids'] = $serverids;
				$postArr['server_ids']=$serverids;
				$postArr['type']=11;//多服务器奖励触发
				$postArr['cause']=$_POST['cause'];
//				unset($_POST['cause']);
				$postArr['send_action']=array('c'=>'Reward','a'=>'Add','Action'=>'Save');
				$postArr['post_data']=$post_data;
				$this->_modelApplyDataFrg->set_postArr($postArr);
				$data=$this->_modelApplyDataFrg->add();
			}
			$this->_utilMsg->showMsg('申请成功,等待审核...',1,Tools::url('FrgAudit','OperationIndex'),false);
		}else {
			#------多服务器选择列表------#
			$this->_multiOperatorSelect('_getOperatorGroup');//使用_getOperatorGroup获得分组
//			$gameServerList=$this->_getGlobalData('gameser_list');
//			foreach ($gameServerList as $key=>&$value){
//				if ($key==100 || $key==200){//100和200是特殊服务器,不显示
//					unset($gameServerList[$key]);
//					continue;
//				}
//				if ($value['game_type_id']!=2)unset($gameServerList[$key]);
//			}
//			$this->_view->assign('gameServerList',json_encode($gameServerList));
//			$this->_view->assign('tplServerSelect','OperationFRG/ServerSelect.html');
			#------多服务器选择列表------#
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Ids'])$this->_utilMsg->showMsg('请选择需要同步的数据',-1);
			$postData['Ids']=array_unique($_REQUEST['Ids']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Reward','a'=>'ShowList','Action'=>'syn'),$postData);
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			$synArr=array();
			foreach ($data['data']['Result'] as $list){
				if (in_array($list['Id'],$postData['Ids'])){
					$list['GetCond'] = unserialize($list['GetCond']);
					$list['Prize'] = unserialize($list['Prize']);
					
					$post_data['GetObj'] = array();
					$post_data['GetOpcode'] = array();
					$post_data['GetValue'] = array();
					$post_data['GetObjName'] = array();
					if($list['GetCond'] && is_array($list['GetCond'])){
						foreach($list['GetCond'] as $key => $val){
							$post_data['GetObj'][$key] = $val[0].'.'.$val[1];
							$post_data['GetOpcode'][$key] = $val[2];
							$post_data['GetValue'][$key] = $val[3];
							$post_data['GetObjName'][$key] = $val[4];
							$post_data['GetObj_name_'.$key] = $val[4]?$val[4]:$val[0].'_'.$val[1];
						}
					}
					$post_data['EffObj'] = array();					
					$post_data['EffOpcode'] = array();
					$post_data['EffValue'] = array();
					$post_data['EffObjName'] = array();
					if($list['Prize']['Effects'] && is_array($list['Prize']['Effects'])){
						foreach($list['Prize']['Effects'] as $key =>$val){
							$post_data['EffObj'][$key] = $val[0].'.'.$val[1];
							$post_data['EffOpcode'][$key] = $val[2];
							$post_data['EffValue'][$key] = $val[3];
							$post_data['EffObjName'][$key] = $val[4];
							$post_data['EffObj_name_'.$key] = $val[4];
						}
					}
					$post_data['ToolId'] = array();
					$post_data['ToolNum'] = array();
					$post_data['ToolIdName'] = array();
					$post_data['ToolIdImg'] = array();
					$post_data['ToolIdEName'] = array();
					if($list['Prize']['Tools'] && is_array($list['Prize']['Tools'])){
						foreach($list['Prize']['Tools'] as $key => $val){
							$post_data['ToolId'][$key] = $val[0];
							$post_data['ToolNum'][$key] = $val[1];
							$post_data['ToolIdName'][$key] = $val[2];
							$post_data['ToolIdImg'][$key] = $val[3];
							$post_data['ToolIdEName'][$key] = $val[4];
							$post_data['Tool_name_'.$key] = $val[2];
						}
					}
					$list['post_data']=serialize($post_data);
					$list['SendTime'] = date('Y-m-d H:i:s',$list['SendTime']);
					$list['EndTime'] = date('Y-m-d H:i:s',$list['EndTime']);
					unset($list['Id'],$list['AddTime'],$list['GetNum'],$list['GetCond'],$list['Prize']);
					array_push($synArr,$list);
				}
			}
			if (!count($synArr))$this->_utilMsg->showMsg('同步内容为空',-1);
			
			$this->_view->assign('dataList',$synArr);
			$this->_view->assign('serverName',$serverName);
			$this->_view->set_tpl(array('body'=>'OperationFRG/RewardServerSyn.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	private function _rewardIndex(){
		$this->_modelFrgReward=$this->_getGlobalData('Model_FrgReward','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelFrgReward->tName());
		$helpSqlSearch->set_orderBy('add_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],10);
		if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
			$startTime=strtotime($_REQUEST['start_time']);
			$endTime=strtotime($_REQUEST['end_time']);
			$helpSqlSearch->set_conditions("send_time>{$startTime} and end_time<{$endTime}");
			$this->_view->assign('selectedStartTime',$_REQUEST['start_time']);
			$this->_view->assign('selectedEndTime',$_REQUEST['end_time']);
		}

		if ($_REQUEST['content']){
			$helpSqlSearch->set_conditions("(title like '%{$_REQUEST['content']}%')");
			$this->_view->assign('selectedContent',$_REQUEST['content']);
		}

		if ($_REQUEST['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
		}

		if ($_REQUEST['main_id']){
			$helpSqlSearch->set_conditions("main_id={$_REQUEST['main_id']}");
			$this->_view->assign('selectedMainId',$_REQUEST['main_id']);
		}

		if ($_REQUEST['is_group']){
			$helpSqlSearch->set_groupBy('title');
			$helpSqlSearch->set_field('count(*) as server_num,title,send_time,end_time,add_time,Id,main_id,server_id');
			$this->_view->assign('selectedGroupBy',true);
		}
		$helpSqlSearch->set_orderBy('server_id');
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelFrgReward->select($sql);
		if ($dataList){
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($dataList as &$list){
				$list['word_server_id']=$serverList[$list['server_id']]['full_name'];
				$list['send_time']=date('Y-m-d H:i:s',$list['send_time']);
				$list['end_time']=date('Y-m-d H:i:s',$list['end_time']);
				$list['add_time']=date('Y-m-d H:i:s',$list['add_time']);
				if ($_REQUEST['is_group']){
					$list['url_edit']=Tools::url(CONTROL,'Reward',array('title'=>$list['title'],'server_id'=>$list['server_id'],'Id'=>$list['main_id'],'doaction'=>'edit'));
				}else {
					$list['url_edit']=Tools::url('MasterFRG','Reward',array('Id'=>$list['main_id'],'server_id'=>$list['server_id'],'doaction'=>'edit'));
				}
				$list['url_server_detail']=Tools::url(CONTROL,'Reward',array('Id'=>$list['Id'],'title'=>urlencode($list['title']),'doaction'=>'serverDetail'));
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelFrgReward->findCount($conditions),'prepage'=>10));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_createServerList();
		$this->_view->set_tpl(array('body'=>'OperationFRG/BatchReward.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	public function actionLibao(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_libaoAdd();
				return ;
			}
			case 'edit' :{
				$this->_libaoEdit();
				return ;
			}
			case 'del' :{
				$this->_libaoDel();
				return ;
			}
			case 'serverDetail' :{
				$this->_libaoServerDetail();
				return ;
			}
			case 'syn' :{
				$this->_libaoSyn();
				return ;
			}
			case 'serverSyn' :{
				$this->_libaoServerSyn();
				return ;
			}
			case 'syn_card' :{	//同步礼包卡号
				$this->_libaoSynCard();
				return ;
			}
			default:{
				$this->_libaoIndex();
				return ;
			}
		}
	}

	private function _libaoServerSyn(){
		if ($this->_isPost() && $_POST['submit']){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_SERVER_FOR_SYN',__CLASS__),-1,2);
			if (!count($_POST['data']))$this->_utilMsg->showMsg(Tools::getLang('NO_PACK_TO_SYN',__CLASS__),-1,2);
			$serverids=array_unique($_POST['server_ids']);
			$postArr=array('CardTypeArray'=>$_POST['data']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,array('c'=>'Card','a'=>'Add','doaction'=>'receive'),$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($getResult);
			$this->_utilMsg->showMsg($msg,1,Tools::url('MasterFRG','Libao'));
		}else {
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (!$_REQUEST['Id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_PACK_FOR_SYN',__CLASS__),-1);
			$selectedIds=array_unique($_REQUEST['Id']);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Card','a'=>'TypeList'));
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResult();
			if (!count($data['data']['Data']))$this->_utilMsg->showMsg(Tools::getLang('NO_PACK_FOR_SYN',__CLASS__),-1);
			$synArr=array();
			foreach ($data['data']['Data'] as $list){
				if (in_array($list['Id'],$selectedIds)){
					unset($list['Id'],$list['CreateTime']);
					array_push($synArr,$list);
				}
			}
			if (!count($synArr))$this->_utilMsg->showMsg(Tools::getLang('NO_PACK_FOR_SYN',__CLASS__),-1);
			$this->_view->assign('dataList',$synArr);
			$this->_view->assign('serverName',$serverName);
			$this->_createServerList();
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/LibaoServerSyn.html'));
			$this->_view->display();
		}
	}
	
	/**
	 * 同步礼包卡号到多服务器
	 */
	private function _libaoSynCard(){
		if ($this->_isPost() && $_POST['submit']){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_SERVER_FOR_SYN',__CLASS__),-1,2);
			if (!count($_POST['card']))$this->_utilMsg->showMsg(Tools::getLang('NO_CARD_TO_SYN',__CLASS__),-1,2);
			if (!$_POST['card_name'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_PACK_FOR_SYN',__CLASS__),-1,2);
			$cardByte=strlen(reset($_POST['card']));//卡号长度
			if (!in_array($cardByte,array(10,32)))$this->_utilMsg->showMsg(Tools::getLang('CARD_LENGTH_ERROR',__CLASS__),-1,2);
			$cardData=implode("\r\n",$_POST['card']);
			$StartTime = implode("\r\n",$_POST['StartTime']);
			$EndTime = implode("\r\n",$_POST['EndTime']);
			$serverids=array_unique($_POST['server_ids']);
			$getArr=array('c'=>'Card','a'=>'ImportCard');
			$postArr=array('card'=>$cardData,'type_id'=>$_POST['card_name'],'StartTime'=>$StartTime,'EndTime'=>$EndTime,'cardbyte'=>$cardByte,'TimeLimit'=>0);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			foreach ($serverids as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$postArr);
			}
			$this->_utilApiFrg->send();
			$getResult=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($getResult);
			$this->_utilMsg->showMsg($msg,1,Tools::url('MasterFRG','Libao'));
		}else {
			if (!$_REQUEST['server_id'])$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			if (count($_REQUEST['Id'])!=1)$this->_utilMsg->showMsg(Tools::getLang('LIMIT_1_CARD',__CLASS__),-1);
			$serverList=$this->_getGlobalData('gameser_list');
			$serverName=$serverList[$_REQUEST['server_id']]['full_name'];
			$cardId=reset($_REQUEST['Id']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->addHttp($_REQUEST['server_id'],array('c'=>'Card','a'=>'ImportCard','doaction'=>'export'),array('type_id'=>$cardId));			
			$this->_utilApiFrg->send();
			$cardList=$this->_utilApiFrg->getResult();
			$cardList=$cardList['params'];
			$this->_view->assign('dataList',$cardList);
			$this->_view->assign('cardName',$_REQUEST['card_name'][$cardId]);	//卡号名称
			$this->_view->assign('serverName',$serverName);
			$this->_createServerList();
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/LibaoSynCard.html'));
			$this->_view->display();
		}
	}

	private function _libaoAdd(){
		if ($this->_isPost()){//提交表单
			#------新版并发http请求------#
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'Card','a'=>'Add','doaction'=>'Save');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$_POST);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($data);
			$this->_utilMsg->showMsg($msg,1);
			#------新版并发http请求------#
		}else {//显示表单
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$this->_view->assign('objData',json_encode($data['data']['ObjData']));
				$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
				$effData=$data['data']['ObjProp'];
				$newEffData=array();
				foreach ($effData as $key=>$value){
					$newEffData[$key]=array();
					foreach ($value as $childValue){
						$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
						$newEffData[$key][$childValue]['Name']=$data['data']['ObjData'][$key][$childValue]['Name'];
					}
				}
				$this->_view->assign('effData',json_encode($newEffData));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_ERROR_PAGE_INIT_ERROR',__CLASS__),-2);
			}
			$this->_view->set_tpl(array('body'=>'OperationFRG/AddLibao.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	private function _libaoEdit(){
		$this->_createServerList();
		if ($this->_isPost()){//提交表单
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$ids=array();
			$post=$_POST;
			unset($post['server_ids'],$post['main_id'],$post['Id']);
			$getArr=array('c'=>'Card','a'=>'Add','doaction'=>'Save');
			foreach ($_POST['server_ids'] as $key=>$serverId){
				array_push($ids,$_POST['Id'][$key]);
				$curPost=$post;
				$curPost['Id']=$_POST['main_id'][$key];
				$this->_utilApiFrg->addHttp($serverId,$getArr,$curPost);
			}
			$this->_utilApiFrg->send();
			$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
			$updateArr=$post;
			$updateArr['auto_id']=$ids;
			$this->_modelFrgLibao->edit($updateArr);
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'));
		}else {//显示表单
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Add','Id'=>$_GET['Id']));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				Tools::import('Util_FRGTools');
				$this->_utilFrgTools=new Util_FRGTools($data['data']['ObjData'],$data['data']['ToolData'],$data['data']['ObjProp']);
				$this->_view->assign('objData',json_encode($this->_utilFrgTools->get_objData()));
				$this->_view->assign('toolData',json_encode($this->_utilFrgTools->get_toolData()));
				$this->_view->assign('effData',json_encode($this->_utilFrgTools->get_effData()));
				$this->_utilFrgTools->setEditResult($data['data']['Reward']);
				$dataResult=$this->_utilFrgTools->getEditResult();
				$this->_view->assign('chageCond',$dataResult['chageCond']);
				$this->_view->assign('chageEffect',$dataResult['chageEffect']);
				$this->_view->assign('chageTool',$dataResult['chageTool']);
				$this->_view->assign('num',$this->_utilFrgTools->getEditNum());
				$this->_view->assign('readOnly',$data['data']['ReadOnly']);
				$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
				$servers=$this->_modelFrgLibao->findServers($_GET);
				$servers=$servers['data']['servers'];
				$this->_view->assign('servers',$servers);
				$this->_view->assign('readOnly',$data['data']['ReadOnly']);
				$this->_view->assign('data',$data['data']['Reward']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'OperationFRG/EditLibao.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	private function _libaoDel(){
		if ($this->_isPost()){
			$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
			if ($_POST['title']){
				$this->_modelFrgLibao->delByTitle($_POST);
			}else {
				$this->_modelFrgLibao->delByids($_POST);
			}
			$this->_utilMsg->showMsg(false);
		}
	}

	private function _libaoServerDetail(){
		$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
		$data=$this->_modelFrgLibao->findServers($_GET);
		$string=$_GET['title'] . '：';
		if ($data['data']['servers']){
			foreach ($data['data']['servers'] as $list){
				$string.=$list['server_name'].' . ';
			}
		}
		$data['data']['servers']=$string;
		$this->_returnAjaxJson($data);
	}

	private function _libaoSyn(){
		if ($this->_isPost()){
			if (!$_POST['operator_id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1);
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
			$getArr=array('c'=>'Card','a'=>'TypeList');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			foreach ($data as $key=>$dataList){
				if (count($dataList['data']['Data'])){
					$this->_modelFrgLibao->syn($dataList['data']['Data'],array('server_id'=>$key,'operator_id'=>$_POST['operator_id']));
				}else {
					$this->_modelFrgLibao->delByServerId($key);
				}
			}
			$this->_utilMsg->showMsg(Tools::getLang('SYN_SUCCESS','Common'));
		}
	}

	private function _libaoIndex(){
		#------初始化------#
		$this->_createServerList();
		$serverList=$this->_getGlobalData('gameser_list');
		$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		#------初始化------#

		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelFrgLibao->tName());

		if (is_numeric($_REQUEST['operator_id'])){
			$helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
		}

		if ($_REQUEST['title']){
			$helpSqlSearch->set_conditions("(title like '%{$_REQUEST['title']}%' or description like '%{$_REQUEST['title']}%')");
			$this->_view->assign('selectedTitle',$_REQUEST['title']);
		}

		if ($_REQUEST['is_group']){
			$helpSqlSearch->set_groupBy('title');
			$helpSqlSearch->set_field('count(*) as server_num,Id,title,description,server_id,main_id,img');
			$this->_view->assign('selectedGroupBy',true);
		}
		$helpSqlSearch->setPageLimit($_GET['page']);
		$helpSqlSearch->set_orderBy('server_id');
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelFrgLibao->select($sql);
		if ($dataList){
			foreach ($dataList as &$list){
				$list['word_server_id']=$serverList[$list['server_id']]['full_name'];
				$list['url_server_detail']=Tools::url(CONTROL,'Libao',array('Id'=>$list['Id'],'title'=>urlencode($list['title']),'doaction'=>'serverDetail'));
				if ($_REQUEST['is_group']){//如果以组显示
					$list['url_edit']=Tools::url(CONTROL,'Libao',array('title'=>$list['title'],'server_id'=>$list['server_id'],'Id'=>$list['main_id'],'doaction'=>'edit'));
					$list['url_add_card']=Tools::url(CONTROL,'AddCard',array('Id'=>$list['main_id'],'title'=>urlencode($list['title'])));
				}else {//单个显示
					$list['url_edit']=Tools::url('MasterFRG','Libao',array('Id'=>$list['main_id'],'doaction'=>'edit','server_id'=>$list['server_id']));
					$list['url_add_card']=Tools::url('MasterFRG','AddCard',array('Id'=>$list['main_id'],'card_name'=>urlencode($list['title']),'server_id'=>$list['server_id']));
					$list['url_import_card']=Tools::url('MasterFRG','ImportCard',array('Id'=>$list['main_id'],'card_name'=>urlencode($list['title']),'server_id'=>$list['server_id']));
					$list['url_export_card']=Tools::url('MasterFRG','ImportCard',array('doaction'=>'export','server_id'=>$list['server_id'],'type_id'=>$list['main_id']));
					$list['url_view_card']=Tools::url('MasterFRG','CardList',array('server_id'=>$list['server_id'],'Query[Items]'=>1,'Query[typeName]'=>urlencode($list['title']),'PageSize'=>10));
				}
			}
			$this->_view->assign('dataList',$dataList);
			$helpPage=new Help_Page(array('total'=>$this->_modelFrgLibao->findCount($conditions),'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 多服务器生成礼包卡号
	 */
	public function actionAddCard(){
		if ($this->_isAjax()){
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);
			$sendParams=Tools::getFilterRequestParam();
			$this->_utilFRGInterface->setGet(array('c'=>'Card','a'=>'Create','doaction'=>'save'));
			$this->_utilFRGInterface->setPost($sendParams);
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				if ($data['msgno']==1){
					$this->_returnAjaxJson(array('status'=>1,'msg'=>Tools::getLang('CREATE_CARD_SUCCESS',__CLASS__),'data'=>array('server_id'=>$_POST['server_id'])));
				}else {
					$this->_returnAjaxJson(array('status'=>1,'msg'=>$data['message'],'data'=>array('server_id'=>$_POST['server_id'])));
				}
			}else {
				$this->_returnAjaxJson(array('status'=>-2,'msg'=>Tools::getLang('CONNECT_SERVER_ERROR','Common'),'data'=>array('server_id'=>$_POST['server_id'])));
			}
		}else {
			if (!$_GET['title'])$this->_utilMsg->showMsg(false);//如果没有标题将跳出.
			$this->_modelFrgLibao=$this->_getGlobalData('Model_FrgLibao','object');
			$servers=$this->_modelFrgLibao->findServers($_GET);
			$servers=$servers['data']['servers'];
			$this->_view->assign('servers',$servers);
			$this->_view->assign('cardName',$_GET['title']);
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 玩家数据
	 */
	public function actionPlayerData(){
		switch ($_GET['doaction']){
			case 'import' :{
				$this->_playerDataImport();
				return ;
			}
			case 'import_excel' :{	//excel导入
				$this->_playerImportExcel();
			}
			case 'del' :{
				$this->_playerDataDel();
				return ;
			}
			default:{
				$this->_playerDataIndex();
				return ;
			}
		}
	}
	
	/**
	 * 导入excel
	 */
	private function _playerImportExcel(){
		$this->_modelFrgPlayerData=$this->_getGlobalData('Model_FrgPlayerData','object');
		$info=$this->_modelFrgPlayerData->importExcel($_POST,$_FILES['upload']);
		if ($info['status']==1){
			$this->_playerDataIndex($info['data']);
			exit();
		}else{
			$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
		}
	}
	
	private function _playerDataDel(){
		$this->_modelFrgPlayerData=$this->_getGlobalData('Model_FrgPlayerData','object');
		$this->_modelFrgPlayerData->delById($_POST['ids']);
		$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,'PlayerData'));
	}
	
	private function _playerDataImport(){
		if ($this->_isPost()){
			$this->_modelFrgPlayerData=$this->_getGlobalData('Model_FrgPlayerData','object');
			$info=$this->_modelFrgPlayerData->add($_POST);
			if ($info['status']==1){
				$this->_playerDataIndex($info['data']);
			}else {
				$this->_utilMsg->showMsg($info['msg'],$info['status'],$info['href']);
			}
		}else {
			$modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
			$this->_view->assign('operatorList',$this->_getGlobalData('operator_list'));
			$serverList=$modelGameSerList->findByGameTypeId(2);
			$this->_view->assign('serverList',json_encode($serverList));
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'OperationFRG/PlayerDataImport.html'));
			$this->_view->display();
		}
	}
	
	private function _playerDataIndex($dataList=NULL){
		#------初始化------#
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$operatorList['0']=Tools::getLang('All_OPERATORS','Common');
		$this->_modelFrgPlayerData=$this->_getGlobalData('Model_FrgPlayerData','object');
		$user=$this->_getGlobalData('user');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		#------初始化------#
		if ($dataList===null){//如果没有数据的话就要开始搜索了
			$helpSqlSearch=new Help_SqlSearch();
			$helpSqlSearch->set_tableName($this->_modelFrgPlayerData->tName());
			$selected=array();

			if ($_REQUEST['start_time'] && $_REQUEST['end_time']){
				$startTime=strtotime($_REQUEST['start_time']);
				$endTime=strtotime($_REQUEST['end_time']);
				$helpSqlSearch->set_conditions("create_time between {$startTime} and {$endTime}");
				$selected['start_time']=$_REQUEST['start_time'];
				$selected['end_time']=$_REQUEST['end_time'];
			}

			if ($_REQUEST['batch_num']){
				$helpSqlSearch->set_conditions("batch_num='{$_REQUEST['batch_num']}'");
				$selected['batch_num']=$_REQUEST['batch_num'];
			}
			$this->_view->assign('selected',$selected);
			$conditions=$helpSqlSearch->get_conditions();
			//以下屏蔽了分页（sql不含有limit）
			//$helpSqlSearch->setPageLimit($_GET['page']);
			$sql=$helpSqlSearch->createSql();
			$dataList=$this->_modelFrgPlayerData->select($sql);
			//以下屏蔽了分页
//			if ($dataList){//如果有数据,生成翻页
//				$helpPage=new Help_Page(array('total'=>$this->_modelFrgPlayerData->findCount($conditions),'perpage'=>PAGE_SIZE));
//				$this->_view->assign('pageBox',$helpPage->show());
//			}
			$this->_view->assign('pageBox','');		//暂无分页
		}

		if ($dataList){
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($dataList as &$list){
				$list['word_user_id']=$user[$list['user_id']]['nick_name'];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['word_operator_id']=$operatorList[$list['operator_id']];
				$list['word_server_id']=$serverList[$list['server_id']]['server_name'];

			}
			$this->_view->assign('dataList',$dataList);

		}
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->set_tpl(array('body'=>'OperationFRG/PlayerData.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	

	/**
	 * 多服务器玩家即时发放道具
	 */
	public function actionRewardBefore(){
		if ($this->_isPost() && $_POST['submit']){
			unset($_POST['submit']);
			$_POST['ToolId']=$_POST['Tool'];
			$_POST['ToolIdName']=$_POST['ToolName'];
			$_POST['ToolIdImg']=$_POST['ToolImg'];
			unset($_POST['Tool'],$_POST['ToolName'],$_POST['ToolImg']);
			$this->_modelApplyDataFrg=$this->_getGlobalData('Model_ApplyDataFrg','object');
			$postArr=array();
			$postArr['server_id']=$_POST['server_id'];
			unset($_POST['server_id']);
			$postArr['type']=10;//'批量多玩家奖励发放'
			$postArr['cause']=$_POST['cause'];
			unset($_POST['cause']);
			$postArr['send_action']=array('c'=>'Reward','a'=>'SendAward','Action'=>'Save');
			$postArr['post_data']=$_POST;
			$this->_modelApplyDataFrg->set_postArr($postArr);
			$data=$this->_modelApplyDataFrg->add();
			$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href'],false);
		}else {
			if (!count($_POST['ids']))$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_USER','Common'),1,-1);
			if ($_POST['player_type']=='')$this->_utilMsg->showMsg(Tools::getLang('PARAMETER_ERROT','Common'),1,-1);
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);	//初始化连接url地址
			
			$this->_utilFRGInterface->setGet(array('c'=>'Reward','a'=>'SendAward'));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				$this->_view->assign('toolData',json_encode($data['data']['ToolData']));
				$effData=$data['data']['ObjProp'];
				$newEffData=array();
				foreach ($effData as $key=>$value){
					$newEffData[$key]=array();
					foreach ($value as $childValue){
						$newEffData[$key][$childValue]['Key']="{$key}.{$childValue}";
						$newEffData[$key][$childValue]['Name']=$data['data']['ObjData'][$key][$childValue]['Name'];
					}
				}
				$this->_view->assign('effData',json_encode($newEffData));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('CONNECT_SERVER_ERROR','Common'),-1);
			}
			$serverList=$this->_getGlobalData('gameser_list');
			$playerIds=array();
			foreach ($_POST['ids'] as $id){
				$playerIds[$_POST['server_id'][$id]][]=$_POST['player_id'][$id];
			}
			foreach ($playerIds as &$serverPlayers){
				$serverPlayers=array_unique($serverPlayers);
			}
			$this->_view->assign('playerIds',$playerIds);
			$this->_view->assign('serverList',$serverList);
			$this->_view->assign('serializePlayerIds',serialize($playerIds));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 屏蔽关键字管理
	 */
	public function actionKeyWords(){
		switch ($_GET['doaction']){
			case 'regular' :{
				$this->_regular();
				return ;
			}default:{
				$this->_regName();
				return ;
			}
		}

	}

	private function _regular(){
		if ($this->_isPost()){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1,2);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'ForbidWord','a'=>'Regular','doaction'=>'save');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$_POST);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($data);
			$this->_utilMsg->showMsg($msg,1);
		}else{
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);
			$this->_utilFRGInterface->setGet(array('c'=>'ForbidWord','a'=>'Regular'));
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('keywords',$data['data']['Regular_Str']);
			$this->_view->set_tpl(array('body'=>'OperationFRG/Regular.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	private function _regName(){
		if ($this->_isPost()){
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1,2);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$getArr=array('c'=>'ForbidWord','a'=>'RegName','doaction'=>'save');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$_POST);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			$msg=$this->_getMultiMsg($data);
			$this->_utilMsg->showMsg($msg,1);
		}else{
			$this->_createServerList();
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($this->_defaultServerId);
			$this->_utilFRGInterface->setGet(array('c'=>'ForbidWord','a'=>'RegName'));
			$data=$this->_utilFRGInterface->callInterface();
			$this->_view->assign('keywords',$data['data']['ForbidWords']);
			$this->_view->set_tpl(array('body'=>'OperationFRG/KeyWords.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

	/**
	 * 并发时生成的消息
	 * @param array $data back_result
	 * @return string
	 */
	private function _getMultiMsg($data){
		$serverList=$this->_getGlobalData('gameser_list');
		$sendStatusMsgs='';
		foreach ($data as $key=>$value){
			if ($value['msgno']==1){
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_SUCCESS','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#00CC00'>{$value['message']}</font><br>";
			}else {
				$value['message']=$value['message']?$value['message']:Tools::getLang('SEND_FAILURE','Common');
				$sendStatusMsgs.="<b>{$serverList[$key]['full_name']}</b>:<font color='#FF0000'>{$value['message']}</font><br>";
			}
		}
		return $sendStatusMsgs;
	}
	/**
	 * 联运链接
	 */
	public function actionLink(){
		
		$this->_modelLyLink=$this->_getGlobalData('Model_LyLink','object');
		
		$gameTypes = $this->_modelLyLink->getMyGame();
		
		$operators = $this->_modelLyLink->getMyOperator();
		
		$linkType = $this->_modelLyLink->getType();
		
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		$helpSqlSearch=new Help_SqlSearch();
		
		$helpSqlSearch->set_tableName($this->_modelLyLink->tName());
		
		#----------<选择-----------#
		$selected=array();
		
		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		if($_GET['game_type_id']){
			$selected['game_type_id'] = $_GET['game_type_id'];
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
		}
		elseif(is_array($gameTypes) && count($gameTypes)){
			$in = implode(',',array_keys($gameTypes));
			$helpSqlSearch->set_conditions("game_type_id in ({$in})");
		}
		
		$_GET['operator_id'] = intval($_GET['operator_id']);
		if($_GET['operator_id']){
			$selected['operator_id'] = $_GET['operator_id'];
			$helpSqlSearch->set_conditions("operator_id={$_GET['operator_id']}");
		}
		elseif(is_array($operators) && count($operators)){
			$in = implode(',',array_keys($operators));
			$helpSqlSearch->set_conditions("operator_id in ({$in})");
		}
		
		$_GET['link_type'] = intval($_GET['link_type']);
		if($_GET['link_type']){
			$selected['link_type'] = $_GET['link_type'];
			$helpSqlSearch->set_conditions("link_type={$_GET['link_type']}");
		}

		
		#----------选择>-----------#

		$helpSqlSearch->set_orderBy('edit_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();
		$dataList = $this->_modelLyLink->select($sql);
		foreach($dataList as &$sub){
			$sub['url_edit'] = Tools::url(CONTROL,'LinkOpt',array('Id'=>$sub['Id'],'doaction'=>'edit'));
			$sub['url_del'] = Tools::url(CONTROL,'LinkOpt',array('Id'=>$sub['Id'],'doaction'=>'del'));
		}
		
		$conditions=$helpSqlSearch->get_conditions();
		$helpPage=new Help_Page(array('total'=>$this->_modelLyLink->findCount($conditions),'perpage'=>PAGE_SIZE));
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign('selected',$selected);
		$gameTypes['']=Tools::getLang('ALL','Common');
		$this->_view->assign('gameTypes',$gameTypes);
		$operators['']=Tools::getLang('ALL','Common');
		$this->_view->assign('operators',$operators);
		$linkType['']=Tools::getLang('ALL','Common');
		$this->_view->assign('linkType',$linkType);
		$this->_view->assign('users',$this->_getGlobalData('user_index_id'));
		$this->_view->display();
	}
	
	public function actionLinkOpt(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_linkAdd();
				break ;
			}
			case 'del' :{
				$this->_linkDel();
				break ;
			}
			case 'edit' :{
				$this->_linkEdit();
				break ;
			}
		}
	}
	
	private function _linkAdd(){
		$this->_modelLyLink=$this->_getGlobalData('Model_LyLink','object');
		
		$gameTypes = $this->_modelLyLink->getMyGame();
		
		$operators = $this->_modelLyLink->getMyOperator();
		
		$linkType = $this->_modelLyLink->getType();
		
		if($this->_isPost()){
		
			$_POST['game_type_id'] = intval($_POST['game_type_id']);
			if(!$_POST['game_type_id'] || !array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_GAME','Common'),-1,2);
			}
			$_POST['operator_id'] = intval($_POST['operator_id']);
			if(!$_POST['operator_id'] || !array_key_exists($_POST['operator_id'],$operators)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1,2);
			}
			$_POST['link_type'] = intval($_POST['link_type']);
			if(!$_POST['link_type'] || !array_key_exists($_POST['link_type'],$linkType)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_LINK_TYPE',__CLASS__),-1,2);
			}
			if(trim($_POST['title']) == ''){
				$this->_utilMsg->showMsg(Tools::getLang('TITLE_NEED',__CLASS__),-1,2);
			}
			if(trim($_POST['href']) == ''){
				$this->_utilMsg->showMsg(Tools::getLang('LINK_NEED',__CLASS__),-1,2);
			}
			
			$this->_modelLyLink->linkAdd();
			
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$this->_url['Link'],1);
		}

		$this->_view->assign('gameTypes',$gameTypes);

		$this->_view->assign('operators',$operators);

		$this->_view->assign('linkType',$linkType);
		
		$this->_view->display();
	}
	
	private function _linkDel(){
		$this->_modelLyLink=$this->_getGlobalData('Model_LyLink','object');
		if ($this->_modelLyLink->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(false);
		}else{
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'),-2);
		}
	}

	private function _linkEdit(){
		$this->_modelLyLink=$this->_getGlobalData('Model_LyLink','object');
		$gameTypes = $this->_modelLyLink->getMyGame();
		$operators = $this->_modelLyLink->getMyOperator();
		$linkType = $this->_modelLyLink->getType();
		
		$selected = $this->_modelLyLink->findById($_GET['Id']);
		if(!$selected){
			$this->_utilMsg->showMsg(Tools::getLang('LINK_DELETED',__CLASS__),-1,2);
		}
		
		if($this->_isPost()){
			$_POST['Id'] = intval($_POST['Id']);
			if(!$this->_modelLyLink->findById($_POST['Id'])){
				$this->_utilMsg->showMsg(Tools::getLang('LINK_DELETED',__CLASS__),-1,2);
			}			
			$_POST['game_type_id'] = intval($_POST['game_type_id']);
			if(!$_POST['game_type_id'] || !array_key_exists($_POST['game_type_id'],$gameTypes)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_GAME','Common'),-1,2);
			}
			$_POST['operator_id'] = intval($_POST['operator_id']);
			if(!$_POST['operator_id'] || !array_key_exists($_POST['operator_id'],$operators)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1,2);
			}
			$_POST['link_type'] = intval($_POST['link_type']);
			if(!$_POST['link_type'] || !array_key_exists($_POST['link_type'],$linkType)){
				$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_LINK_TYPE',__CLASS__),-1,2);
			}
			if(trim($_POST['title']) == ''){
				$this->_utilMsg->showMsg(Tools::getLang('TITLE_NEED',__CLASS__),-1,2);
			}
			if(trim($_POST['href']) == ''){
				$this->_utilMsg->showMsg(Tools::getLang('LINK_NEED',__CLASS__),-1,2);
			}
			$this->_modelLyLink->linkUpdate();
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,$this->_url['Link'],1);
		}
		$this->_view->assign('selected',$selected);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('operators',$operators);
		$this->_view->assign('linkType',$linkType);
		$this->_view->display();
	}
	
	/**
	 * 特殊活动管理
	 * @author php-兴源
	 */
	public function actionSpecialActivity(){
		switch ($_GET['doaction']){
//			case 'add' :{
//				$this->_spActivityAdd();
//				return ;
//			}
			case 'edit' :{
				$this->_spActivityEdit();
				return;
			}
//			case 'rest' :{
//				$this->_spActivityRest();
//				return ;
//			}
			case 'del' :{
				$this->_spActivityDel();
				return ;
			}
//			case 'onoff' :{
//				$this->_spActivityOnOff();
//				return ;
//			}
			case 'syn' :{
				$this->_spActivitySyn();
				return ;
			}
			case 'serverDetail' :{
				$this->_spActivityServerDetail();
				return ;
			}
			default:{
				$this->_spActivityIndex();
				return ;
			}
		}
	}
	
	/**
	 * 特殊批量修改
	 * @author php-兴源
	 */
	private function _spActivityEdit(){
		set_time_limit(100);	//设置max_execution_time
		$this->_createServerList();
		if ($this->_isPost()){//提交表单
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_utilApiFrg->setTimeOut(90);	//设置CURL超时时间
			$post = $_POST;
			unset($post['server_ids'],$post['special_activity_id'],$post['Id']);
			$getArr=array('c'=>'Activity','a'=>'AddSpecialActivity','action'=>'save');
			foreach ($_POST['server_ids'] as $key=>$serverId){
				$curPost=$post;
				$curPost['Id']=$_POST['special_activity_id'][$key];
				$this->_utilApiFrg->addHttp($serverId,$getArr,$curPost);
			}
			$this->_utilApiFrg->send();
//			$resules = $this->_utilApiFrg->getResults();
//			$successMsg = Tools::getLang('OPERATION_SUCCESS','Common');
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION));
		}else {//显示表单			
			$this->_utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
			$this->_utilFRGInterface->setServerUrl($_REQUEST['server_id']);	//初始化连接url地址
			$this->_utilFRGInterface->setGet(array('c'=>'Activity','a'=>'AddSpecialActivity','Id'=>$_GET['special_activity_id']));
			$data=$this->_utilFRGInterface->callInterface();
			if ($data){
				//同一个标题下表单服务器列表
				$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
				$servers=$this->_modelFrgSpecialActivity->findServers($_GET);
				$servers=$servers['data']['servers'];
				$this->_view->assign('servers',$servers);
				$data['data']['Activity']['word_Identifier']=$data['data']['ActivityTypes'][$data['data']['Activity']['Identifier']]['Description'];
				$this->_view->assign('types',$data['data']['ActivityTypes']);
				$this->_view->assign('rewardsList',$data['data']['AwardsForm']);
				$this->_view->assign('dataList',$data['data']['Activity']);
			}else {
				$this->_view->assign('errorConn',Tools::getLang('CONNECT_SERVER_ERROR','Common'));
			}
		}
		$this->_view->set_tpl(array('body'=>'OperationFRG/SpecialActivityEdit.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	private function _spActivityIndex(){
		#------初始化------#		
		$this->_createServerList();
		$serverList=$this->_getGlobalData('gameser_list');
		$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
		$this->_loadCore('Help_SqlSearch');
		$this->_loadCore('Help_Page');
		#------初始化------#
		

		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelFrgSpecialActivity->tName());
		$_REQUEST['operator_id'] = intval($_REQUEST['operator_id']);
		if ($_REQUEST['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$_REQUEST['operator_id']}");
			$this->_view->assign('selectedOperatorId',$_REQUEST['operator_id']);
		}
		
		if($_REQUEST['IdentifierValue']){
			$helpSqlSearch->set_conditions("IdentifierValue = '{$_REQUEST['IdentifierValue']}'");
			$this->_view->assign('selectedIdentifierValue',$_REQUEST['IdentifierValue']);
		}

		if ($_REQUEST['title']){
			$helpSqlSearch->set_conditions("(Title like '%{$_REQUEST['title']}%' )");
			$this->_view->assign('selectedTitle',$_REQUEST['title']);
		}

		if ($_REQUEST['is_group']){
//			$helpSqlSearch->set_groupBy('Title,IdentifierValue,StartTime,EndTime');
			$helpSqlSearch->set_groupBy('Title,IdentifierValue');
			$helpSqlSearch->set_field('count(*) as server_num,Id,operator_id,server_id,special_activity_id,Identifier,IdentifierValue,Img,IsOpen,Title,Content,AwardDesc,StartTime,EndTime,CheckType,AwardIds,Awards,GetCond,Status,MsgTitle,MsgContent,MsgContent,IsShow');
			$this->_view->assign('selectedGroupBy',true);
		}
		
		$open=array('0'=>Tools::getLang('CLOSE','Common'),'1'=>Tools::getLang('OPEN','Common'));
		$show = array('0'=>Tools::getLang('NOT_DISPLAY','Common'),'1'=>Tools::getLang('DISPLAY','Common'));

		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$helpSqlSearch->set_orderBy('server_id');
		$conditions=$helpSqlSearch->get_conditions();
		$sql=$helpSqlSearch->createSql();
		$dataList=$this->_modelFrgSpecialActivity->select($sql);
		if($dataList){
			foreach($dataList as $key=>&$val){
				$val['URL_edit'] = Tools::url(CONTROL,ACTION,array('doaction'=>'edit','title'=>$val['Title'],'IdentifierValue'=>$val['IdentifierValue'],'StartTime'=>$val['StartTime'],'EndTime'=>$val['EndTime'],'operator_id'=>$_REQUEST['operator_id'],'server_id'=>$val['server_id'],'special_activity_id'=>$val['special_activity_id']));
			}
		}
		$serverListFullName =Model::getTtwoArrConvertOneArr($serverList,'Id','full_name');
		
		$this->_view->assign('serverListFullName',$serverListFullName);
		if ($_REQUEST['is_group']){
			$FiledGroupBy = $helpSqlSearch->getFiledGroupBy();
			$count = $this->_modelFrgSpecialActivity->countGroupBy($conditions,$FiledGroupBy);
		}else{
			$count = $this->_modelFrgSpecialActivity->findCount($conditions);
		}
		$helpPage=new Help_Page(array('total'=>$count,'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		
		$this->_view->assign('dataList',$dataList);		
		$this->_view->assign('is_open',$open);
		$this->_view->assign('is_show',$show);
		//从缓存中获得特殊活动类型
		$SpActivityType = $this->_f($this->game_id.self::FRG_SPACTIVITY_TYPE);
		$this->_view->assign('SpActivityType',$SpActivityType);
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
		

	private function _spActivitySyn(){
		if ($this->_isPost()){
			if (!$_POST['operator_id'])$this->_utilMsg->showMsg(Tools::getLang('PLZ_SLT_OPERATOR','Common'),-1);
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1);
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
			$getArr=array('c'=>'Activity','a'=>'ListSpecialActivity');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			foreach ($data as $key=>$dataList){
				if (count($dataList['data']['Activities'])){
					$this->_modelFrgSpecialActivity->syn($dataList['data']['Activities'],array('server_id'=>$key,'operator_id'=>$_POST['operator_id']),$dataList['data']['ActivityTypes']);
				}else {
					$this->_modelFrgSpecialActivity->delByServerId($key);
				}
			}
			$this->_utilMsg->showMsg(Tools::getLang('SYN_SUCCESS','Common'));
		}
		
	}
	
	private function _spActivityDel(){
		if ($this->_isPost()){
			$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
			if ($_POST['title']){
				$this->_modelFrgSpecialActivity->delByTitle($_POST);
			}else {
				$this->_modelFrgSpecialActivity->delByids($_POST);
			}
			$this->_utilMsg->showMsg(false);
		}
	}
	
	private function _spActivityServerDetail(){
		$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
		$data=$this->_modelFrgSpecialActivity->findServers($_GET);
		$string=$_GET['title'] . '：<br>';
		if ($data['data']['servers']){
			foreach ($data['data']['servers'] as $list){
				$string.=$list['server_name'].'<br>';
			}
		}
		$data['data']['servers']=$string;
		$this->_returnAjaxJson($data);
	}
	
	public function actionLockUserAdd(){
		$this->_createServerList();
		$serverList=$this->_getGlobalData('gameser_list');
		if ($this->_isPost()){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
			$getArr=array('c'=>'LockUser','a'=>'Add','doaction'=>'saveadd','ReceiveType'=>'1');
			$postArr = $_POST;
			unset($postArr['cause'],$postArr['server_ids']);
			if (!count($_POST['server_ids']))$this->_utilMsg->showMsg(Tools::getLang('PLEASE_SELECTSERVER','Common'),-1,2);
			if(!is_array($_POST['server_ids']))$this->_utilMsg->showMsg('error post data',-1,2);
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr,$postArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();
			if($data){
				//记录操作日志
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				foreach($data as $key => $SubData){
					if ($SubData['msgno']==1){
						//记录游戏后台新操作日志
						$AddLog = '操作:<font style="color:#F00">封号</font>';
						$AddLog .= '<br>操作人:<b>{UserName}</b>';
						$AddLog .= '<br>封号结束时间:'.$_POST['Data']['EndTime'];
						$AddLog .= '<br>原因:'.$_POST['cause'];
						$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($SubData['backparams']['Exist'],1,$key,$AddLog);
						if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
							foreach($GameOperateLog as $sub){
								$this->_modelGameOperateLog->add($sub);
							}
						}
					}
				}
				$this->_utilMsg->showMsg(Tools::getLang('OPERATION_SUCCESS','Common'));
			}
			$this->_utilMsg->showMsg(Tools::getLang('OPERATION_FAILURE','Common'),-1,2);
		}
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}
	
	public function actionShowImportantConf(){
		$this->_createServerList();
		$serverList=$this->_getGlobalData('gameser_list');
		if($this->_isPost()){
			$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
			$this->_modelFrgSpecialActivity=$this->_getGlobalData('Model_FrgSpecialActivity','object');
			$getArr=array('c'=>'Conf','a'=>'ShowImportantConf');
			foreach ($_POST['server_ids'] as $serverId){
				$this->_utilApiFrg->addHttp($serverId,$getArr);
			}
			$this->_utilApiFrg->send();
			$data=$this->_utilApiFrg->getResults();			
			//true需要相同(不同时警告)，false需要不同(相同时警告)，0 无需检验
			$fields = array(
				'GameName'=>array(
								0=>false,
								1=>'游戏名称',
							),
				'co_action'=>array(
								0=>true,
								1=>'合作方标识',
							),							
				'ServerSSID'=>array(
								0=>false,
								1=>'服务器ID',
							),	
				'GameId'=>array(
								0=>true,
								1=>'游戏id',
							),	
				'JS_LanguageName'=>array(
								0=>true,
								1=>'前端语言',
							),		
				'SysVindicate'=>array(
								0=>true,
								1=>'游戏开关',
							),									
				'DuplicateCrossStatus'=>array(
								0=>true,
								1=>'是否开启跨服方程式',
							),		
				'GAME_LANGUAGE_NAME'=>array(
								0=>true,
								1=>'服务器语言',
							),		
				'SERVER_INIT_TIME'=>array(
								0=>0,
								1=>'初始化',	
								),
				'SERVER_UNIQUEID'=>array(
								0=>false,
								1=>'服务器唯一ID',
							),	
				'JS_ServerHost'=>array(
								0=>true,
								1=>'聊天服务器',
							),	
				'JS_ServerPort'=>array(
								0=>true,
								1=>'聊天端口',
							),	
								
							
							
			);
			$dataList = array();
			$CheckData = array();			
			foreach($data as $ServerId=>$sub){
				if($sub && $sub['msgno'] === 0){
					foreach($fields as $field =>$val){
						$dataList[$ServerId] = $sub['data'];
						$CheckData[$field][0][$sub['data'][$field]] = $CheckData[$field][1][$ServerId] = $sub['data'][$field];
					}
				}elseif($sub && $sub['msgno'] === 2){
					$dataList[$ServerId]['error'] = $sub['message'];
				}else{
					$dataList[$ServerId]['error'] = '连接失败';
				}
			}
			//如果多于2个服务器,检查有问题的字段
			$GetBad = array();
			if(count($dataList)>1){
				foreach($CheckData as $field=>$sub){
					if($fields[$field][0] === 0)continue;				
					if($fields[$field][0] == (count($sub[0]) == count($sub[1]))){//(count($sub[0]) == count($sub[1])))不同为true，相同为false
						$GetBad[$field] = true;
					}
				}
			}
		}
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('fields',$fields);
		$this->_view->assign('GetBad',$GetBad);		
		$this->_view->display();
	}

	
	
}