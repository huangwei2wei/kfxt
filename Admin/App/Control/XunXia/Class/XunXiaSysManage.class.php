<?php
/**
 * 寻侠GM工具
 * @author php-朱磊
 *
 */
class Control_XunXiaSysManage extends XunXia {
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

	private $_modelGameOperateLog;

	public function __construct(){
		$_GET['page']=$_GET['page']?$_GET['page']:1;
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}

	private function _createUrl(){
		$this->_url['XunXiaSysManage_IpDel']=Tools::url(CONTROL,'IpDel',array('zp'=>'XunXia'));
		$this->_url['XunXiaSysManage_ResUserDel']=Tools::url(CONTROL,'ResUserDel',array('zp'=>'XunXia'));
		$this->_url['XunXiaSysManage_TalkUserDel']=Tools::url(CONTROL,'TalkUserDel',array('zp'=>'XunXia'));

		$this->_url['XunXiaSysManage_IpAdd']=Tools::url(CONTROL,'IpAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
		$this->_url['XunXiaSysManage_ResUserAdd']=Tools::url(CONTROL,'ResUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
		$this->_url['XunXiaSysManage_TalkUserAdd']=Tools::url(CONTROL,'TalkUserAdd',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));

		//服务器管理
		$this->_url ['GameSerList_Add'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'add','zp'=>'XunXia') );
		$this->_url ['GameSerList_Edit'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'edit','zp'=>'XunXia') );
		$this->_url ['GameSerList_CreateCache'] = Tools::url ( CONTROL, 'Serverlist',array('doaction'=>'cache','zp'=>'XunXia') );

		$this->_view->assign('url',$this->_url);
	}

	/**
	 * 群发短信
	 */
	public function actionSendMsg(){
		if ($this->_isPost() && isset($_POST['submit'])){
			$this->getApi()->setUrl($_REQUEST['server_id'],'msg/msg');
			$data=$this->getApi()->saveMsg(intval($_POST['userType']),$_POST['title'],$_POST['content'],$_POST['userIds']);
			if (isset($data->code)){
				//	"$data" = Object of: xn_util_ResultInfo
				//		NoExists = (string:5) OMG啦,	
				//		data = (string:24) 400211,871213,512,:OMG啦,	
				//		code = (int) 0
				//		playerNameList = (string:0)
				//		userIdLists = (string:18) 400211,871213,512,

				if($data->userIdLists){
					$data->userIdLists = trim($data->userIdLists,',');
					$data->userIdLists = explode(',',$data->userIdLists);
					$userInfo = array();
					foreach($data->userIdLists as $key => $val){
						$userInfo[$key]['UserId'] = $val;
					}
					//记录游戏后台新操作日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
					array('操作','<font style="color:#F00">发邮件</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('标题',$_POST['title']),
					array('内容',$_POST['content']),
					array('原因',$_POST['cause']),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($userInfo,4,$_REQUEST['server_id'],$AddLog);
					if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
						foreach($GameOperateLog as $sub){
							$this->_modelGameOperateLog->add($sub);
						}
					}
				}
				$NoExist = '';
				if($data->data){
					$NoExist = "<br />其中,无效玩家有:<font color='#ff0000'>{$data->data}</font>";
				}
				$this->_utilMsg->showMsg('发送成功'.$NoExist,1,1,null);
			}else {
				$this->_utilMsg->showMsg('发送失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			if ($_REQUEST['server_id']){//如果设置了服务器id
				//				$this->getApi()->setUrl($_REQUEST['server_id'],'oneNotice/notice');
				//				$dataList=$this->getApi()->queryNotice();
				//				$this->_view->assign('dataList',$dataList->page->data);
				$users = '';
				if($_POST['ids'] && is_array($_POST['ids'])){
					$users = implode(',',$_POST['ids']);
				}
				$this->_view->assign('users',$users);
			}
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}
	/*
	 * 道具查询
	 */
	public function actionSearchDj(){
		$this->_checkOperatorAct();
		// 		$this->_createServerList();
		$this->_createCenterServer();
		$data = array();
		if ($this->_isPost() && isset($_POST['submit'])){
			$this->getApi()->setUrl($_REQUEST['server_id'],'goods/goodsServe');

			$data=$this->getApi()->querySpecialGoods(trim($_POST['Goodsname']));
			$this->_view->assign('Goodsname',trim($_POST['Goodsname']));
			// 			print_r($data);exit;
			// 			if (isset($data->code)){}else {
			// 				$this->_utilMsg->showMsg('发送失败',-2);
			// 			}
		}
		$info = array('goodsId'=>'装备ID',
				'equipTypeDesc'=>'装备类别',
				'qualityDesc'=>'品质',
				'name' =>'名称',
				'attribute'=>'属性',
				'fromIntro'=>'掉落来源',
				'intro'=>'介绍'
				);
				$this->_view->assign('data',$data);
				$this->_view->assign('info',$info);
				$this->_utilMsg->createPackageNavBar();
				$this->_view->display();
	}


	/**
	 * 封IP显示列表
	 */
	public function actionIpIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');

			$dataList=$this->getApi()->queryRefuseIP($_GET['page'],PAGE_SIZE);
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				if (count($dataList->page->data)){
					foreach ($dataList->page->data as $list){
						$list->createTime="{$list->createAt->year}-{$list->createAt->month}-{$list->createAt->day} {$list->createAt->hour}:{$list->createAt->minute}:{$list->createAt->second}";
						$list->endTime="{$list->end->year}-{$list->end->month}-{$list->end->day} {$list->end->hour}:{$list->end->minute}:{$list->end->second}";
						//$list->URL_TimeEnd		=	Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'detail','id'=>$list->id,'server_id'=>$_REQUEST['server_id']));
					}
				}
				//echo $list->URL_TimeEnd;
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 解除封IP
	 */
	public function actionIpDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');
		$data=$this->getApi()->deleteRefuseIP($_POST['idList']);
		if ($data->code==0){
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
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');
			$data=$this->getApi()->saveRefuseIP($_POST['ips'],$_POST['endTime']);
			if ($data->code==0){
				$this->_utilMsg->showMsg("封锁IP成功<br />失败的IP:<font color='#ff0000'>{$data->data}</font>",1,Tools::url(CONTROL,'IpIndex',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])));
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
	 * 封号
	 */
	public function actionResUserIndex(){
		switch ($_GET['doaction']){
			case 'detail':
				$this->_operateDetail(1);
				break;
			default :
				$this->_resUserIndex();
		}
	}
	/*
	 * 全服封号
	 *  */
	public function actionLockUserByBatch(){
		switch($_GET['doaction']){
			case 'add':{
				$this->_lockUserAdd();
				return ;
			}
			case 'addForSY':{
				$this->_lockUserAddForSY();
				return;
			}
			case 'del':{
				$this->_lockUserDel();
				return;
			}
			case 'time_end':{
				$this->_lockUserTimeEnd();
				return;
			}
			case 'detail':{
				$this->_operateDetail(1);
				return;
			}
			default :{
				$this->_lockUserIndex();
				return ;
			}
		}

	}
	/**
	 * 删除全服封号
	 */
	private function _lockUserDel(){
		$playerId = (array)$_REQUEST['playerId'];
		if($playerId){

			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			$dataList=$this->getApi()->deleteForbidden($playerId);
			// 			print_r($dataList);//exit;

			if($dataList == 0){
				$this->_utilMsg->showMsg('操作成功',1,1,1);
			}elseif(is_array($dataList)){
				$this->_utilMsg->showMsg('提交用户不存在',-1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}
		}else{
			$this->_utilMsg->showMsg('没有选择',-1);
		}

	}
	/**
	 * 全服封号sye
	 */
	public function _lockUserIndex(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			if(empty($_GET["page"])){$_GET["page"]=1;}

			$dataList=$this->getApi()->queryForbidden($_GET["page"],PAGE_SIZE);
			// 			print_r($dataList);
			foreach($dataList->page->data as &$sub){
				$id =  $sub->id;
				$sub->URL_Detail = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'playerId'=>$id,'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
				$sub->URL_TimeEnd = Tools::url(CONTROL,ACTION,array('zp'=>self::PACKAGE,'doaction'=>'del','playerId'=>$id,'server_id'=>$_REQUEST['server_id']));
			}
			$this->_loadCore('Help_Page');
			$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
			$this->_view->assign('dataList',$dataList->page->data);
			$this->_view->assign('pageBox',$this->_helpPage->show());
		}
		$UrlLockUserAdd = Tools::url(CONTROL,'LockUserByBatch',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$UrlLockUserAddForSY = Tools::url(CONTROL,'LockUserByBatch',array('zp'=>self::PACKAGE,'doaction'=>'addForSY','server_id'=>$_REQUEST['server_id']));
		$this->_view->assign('UrlLockUserAdd',$UrlLockUserAdd);
		$this->_view->assign('UrlLockUserAddForSY',$UrlLockUserAddForSY);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/*
	 * 增加全服封号 
	 * */
	public function _lockUserAdd(){

		$this->_checkOperatorAct();
		// 		$this->_createCenterServer();
		if ($this->_isPost()){
			$cause = trim($_POST['cause']);

			$playerids = '';
			if (is_array($_POST['ids'])){
				foreach ($_POST['ids'] as $v){
					$playerids .= $v.',';
				}
				$playerids = substr($playerids,0,strlen($playerids)-1);
				// 				$this->getApi()->setUrl('http://192.168.22.44:8080/xunxia-gm/forbidden/forbidden0');
			}elseif ($_POST['userIds']){
				$playerids = trim($_POST['userIds']);
				// 				$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			}else{
				$this->_utilMsg->showMsg('用户id 不能空',-1);
			}

			// 			if($_POST['endTime']){
			// 				$endTime = $_POST['endTime'];
			// 			}else{
			// 				$endTime = date('Y-m-d H:i:s',time()+10*365*24*60*60);
			// 			}
			// 			$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			//			$this->getApi()->setUrl('http://183.60.65.94/xxfk/forbidden/forbidden0');
			$this->getApi()->setUrl('http://183.60.65.94/xxfk/forbidden/forbidden0');
			// 			$this->getApi()->setUrl('http://192.168.22.44:8080/xunxia-gm/forbidden/forbidden0');
			// 			echo $playerids;

			$dataList=$this->getApi()->saveForbiddenByBatch($playerids);

			// 		 	print_r($dataList);//exit;

			if($dataList == 0){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
				array('操作','<font style="color:#F00">全服封号</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array('封号ID','<b>'.$playerids.'</b>'),
				array('封号结束时间',$endTime),
				array('原因',$cause),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				foreach($dataList as $sub){
					$userInfo =$sub;
					$userInfo['UserId'] = $sub['id'];
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,1,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
				}
				#------->>>记录游戏后台新操作日志-------#
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'LockUserByBatch',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
			}else{
				$this->_utilMsg->showMsg('操作失败',-1);
			}

		}
		$UrlLockUserDel = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
		$this->_view->assign('UrlLockUserDel',$UrlLockUserDel);
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/LockUserByBatchAdd.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	/*
	 * 增加全服封号
	* */
	public function _lockUserAddForSY(){
	
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		if ($this->_isPost()){
			$cause = trim($_POST['cause']);
	
			$playerids = '';
			if (is_array($_POST['ids'])){
				foreach ($_POST['ids'] as $v){
					$playerids .= $v.',';
				}
				$playerids = substr($playerids,0,strlen($playerids)-1);
				// 				$this->getApi()->setUrl('http://192.168.22.44:8080/xunxia-gm/forbidden/forbidden0');
			}elseif ($_POST['userIds']){
				$playerids = trim($_POST['userIds']);
				// 				$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			}else{
				$this->_utilMsg->showMsg('用户id 不能空',-1);
			}
	
			// 			if($_POST['endTime']){
			// 				$endTime = $_POST['endTime'];
			// 			}else{
			// 				$endTime = date('Y-m-d H:i:s',time()+10*365*24*60*60);
			// 			}
						$this->getApi()->setUrl($_REQUEST['server_id'],'forbidden/forbidden0');
			//			$this->getApi()->setUrl('http://183.60.65.94/xxfk/forbidden/forbidden0');
// 						$this->getApi()->setUrl('http://183.60.65.94/xxfk/forbidden/forbidden0');
			// 			$this->getApi()->setUrl('http://192.168.22.44:8080/xunxia-gm/forbidden/forbidden0');
			// 			echo $playerids;
	
			$dataList=$this->getApi()->saveForbiddenByBatch($playerids);
	
			// 		 	print_r($dataList);//exit;
	
			if($dataList == 0){
				#-------<<<记录游戏后台新操作日志-------#
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
						array('操作','<font style="color:#F00">全服封号</font>'),
						array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
						array('操作人','<b>{UserName}</b>'),
						array('封号ID','<b>'.$playerids.'</b>'),
						array('封号结束时间',$endTime),
						array('原因',$cause),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				foreach($dataList as $sub){
					$userInfo =$sub;
					$userInfo['UserId'] = $sub['id'];
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,1,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
				}
				#------->>>记录游戏后台新操作日志-------#
				$this->_utilMsg->showMsg('操作成功',1,Tools::url(CONTROL,'LockUserByBatch',array('zp'=>self::PACKAGE,'server_id'=>$_REQUEST['server_id'])),1);
				}else{
				$this->_utilMsg->showMsg('操作失败',-1);
				}
	
			}
			$UrlLockUserDel = Tools::url(CONTROL,'LockUser',array('zp'=>self::PACKAGE,'doaction'=>'add','server_id'=>$_REQUEST['server_id']));
			$this->_view->assign('UrlLockUserDel',$UrlLockUserDel);
			$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/LockUserByBatchAdd.html'));
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	

	/**
	 * 封号显示列表
	 */
	private function _resUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$status = array(0=>'强制解封',1=>'封号中',2=>'自动解封');
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseUser/refuseUser');
			//			$dataList=$this->getApi()->queryRefuseUser($_GET['page'],PAGE_SIZE);
			$dataList=$this->getApi()->queryRefuseUser1($_GET['page'],PAGE_SIZE);
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				if (count($dataList->page->data)){
					foreach ($dataList->page->data as $list){
						$list->uid = $this->d2s($list->uid);
						$list->URL_Detail = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','game_user_id'=>$list->uid,'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
						$list->status = isset($status[$list->status])?$status[$list->status]:$list->status;
						$list->createTime="{$list->createAt->year}-{$list->createAt->month}-{$list->createAt->day} {$list->createAt->hour}:{$list->createAt->minute}:{$list->createAt->second}";
						$list->endTime="{$list->end->year}-{$list->end->month}-{$list->end->day} {$list->end->hour}:{$list->end->minute}:{$list->end->second}";
					}
				}
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 增加封号
	 */
	public function actionResUserAdd(){
		if ($this->_isPost() && isset($_POST['submit'])){
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseUser/refuseUser');
			$data=$this->getApi()->saveRefuseUser($_POST['userType'],$_POST['userIds'],$_POST['endTime']);
			//	"$data" = Object of: xn_util_ResultInfo
			//		NoExists = (string:5) OMG啦,	
			//		data = (string:24) 400211,871213,512,:OMG啦,	
			//		code = (int) 0
			//		playerNameList = (string:0)
			//		userIdLists = (string:18) 400211,871213,512,
			if ($data->code===0){
				if($data->userIdLists){
					$data->userIdLists = trim($data->userIdLists,',');
					$data->userIdLists = explode(',',$data->userIdLists);
					$userInfo = array();
					foreach($data->userIdLists as $key => $val){
						$userInfo[$key]['UserId'] = $val;
					}
					//记录游戏后台新操作日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
					array('操作','<font style="color:#F00">封号</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('封号结束时间',$_POST['endTime']),
					array('原因',$_POST['cause']),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
					$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($userInfo,1,$_REQUEST['server_id'],$AddLog);
					if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
						foreach($GameOperateLog as $sub){
							$this->_modelGameOperateLog->add($sub);
						}
					}
				}
				$NoExist = '';
				if($data->data){
					$existUser = array_diff(explode(',',$_POST['userIds']),explode(',',$data->data));
					$NoExist = "<br />其中,无效玩家有:<font color='#ff0000'>{$data->data}</font>";
				}else{
					$existUser = explode(',',$_POST['userIds']);
				}

				$this->_utilMsg->showMsg("封号成功".$NoExist,1,Tools::url(CONTROL,'ResUserIndex',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])),false);
			}else {
				$this->_utilMsg->showMsg('封号失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$users = '';
			if($_POST['ids'] && is_array($_POST['ids'])){
				$users = implode(',',$_POST['ids']);
			}
			$this->_view->assign('users',$users);
			$this->_view->display();
		}
	}

	/**
	 * 解除封号
	 */
	public function actionResUserDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'refuseUser/refuseUser');
		//		$data=$this->getApi()->deleteRefuseUser($_POST['idList']);
		$data=$this->getApi()->updateStatus($_POST['idList']);
		if ($data===0){
			$this->_utilMsg->showMsg("解除封号成功");
		}else {
			$this->_utilMsg->showMsg('解除封号失败',-2);
		}
	}


	public function actionTalkUserIndex(){
		switch ($_GET['doaction']){
			case 'detail':
				$this->_operateDetail(2);
				break;
			default :
				$this->_talkUserIndex();
		}
	}

	/**
	 * 禁言显示列表
	 */
	private function _talkUserIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseMessage/refuseMessage');
			//			$dataList=$this->getApi()->queryRefuseMsg($_GET['page'],PAGE_SIZE);
			$dataList=$this->getApi()->queryRefuseMsg1($_GET['page'],PAGE_SIZE);
			//"$dataList" = Object of: xn_util_QueryResultInfo
			//	page = Object of: xn_util_Page
			//		totalCount = (double) 3
			//		start = (double) 0
			//		data = Array [3]
			//			0 = Object of: xn_domain_admin_RefuseMessage
			//				uid = (double) 512
			//				id = (double) 2
			//				createAt = Object of: PHPRPC_Date
			//				status = (int) 0
			//				serverId = null
			//				userAccount = (string:11) 512@163.com
			//				operator = null
			//				end = Object of: PHPRPC_Date
			//				begin = Object of: PHPRPC_Date
			//			1 = Object of: xn_domain_admin_RefuseMessage
			//			2 = Object of: xn_domain_admin_RefuseMessage
			//		pageSize = (int) 20
			//	code = (int) 0
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				if (count($dataList->page->data)){
					$status = array(0=>'强制解禁',1=>'禁言中',2=>'自动解禁');
					foreach ($dataList->page->data as $list){
						$list->uid = $this->d2s($list->uid);
						$list->URL_Detail = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','game_user_id'=>$list->uid,'server_id'=>$_REQUEST['server_id'],'doaction'=>'detail'));
						$list->status = isset($status[$list->status])?$status[$list->status]:$list->status;
						$list->createTime="{$list->createAt->year}-{$list->createAt->month}-{$list->createAt->day} {$list->createAt->hour}:{$list->createAt->minute}:{$list->createAt->second}";
						$list->endTime="{$list->end->year}-{$list->end->month}-{$list->end->day} {$list->end->hour}:{$list->end->minute}:{$list->end->second}";
					}
				}
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	/**
	 * 增加禁言
	 */
	public function actionTalkUserAdd(){
		if ($this->_isPost() && isset($_POST['submit'])){
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseMessage/refuseMessage');
			$data=$this->getApi()->saveRefuseMsg($_POST['userType'],$_POST['userIds'],$_POST['endTime']);
			if ($data->code===0){
				//"$data" = Object of: xn_util_ResultInfo
				//	NoExists = (string:5) OMG啦,	
				//	data = (string:24) 400211,871213,512,:OMG啦,	
				//	code = (int) 0
				//	playerNameList = (string:0)
				//	userIdLists = (string:18) 400211,871213,512,
				if($data->userIdLists){
					$data->userIdLists = trim($data->userIdLists,',');
					$data->userIdLists = explode(',',$data->userIdLists);
					$userInfo = array();
					foreach($data->userIdLists as $key => $val){
						$userInfo[$key]['UserId'] = $val;
					}
					//记录游戏后台新操作日志
					$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
					$AddLog = array(
					array('操作','<font style="color:#F00">禁言</font>'),
					array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
					array('操作人','<b>{UserName}</b>'),
					array('禁言结束时间',$_POST['endTime']),
					array('操作原因',$_POST['cause']),
					);
					$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);

					$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($userInfo,2,$_REQUEST['server_id'],$AddLog);

					if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){

						foreach($GameOperateLog as $sub){
							$this->_modelGameOperateLog->add($sub);
						}
					}
				}
				$NoExist = '';
				if($data->data){
					$NoExist = "<br />其中,无效玩家有:<font color='#ff0000'>{$data->NoExists}</font>";
				}
				$this->_utilMsg->showMsg("禁言成功".$NoExist,1,Tools::url(CONTROL,'TalkUserIndex',array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])),false);
			}else {
				$this->_utilMsg->showMsg('禁言失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$this->_utilMsg->createPackageNavBar();
			$users = '';
			if($_POST['ids'] && is_array($_POST['ids'])){
				$users = implode(',',$_POST['ids']);
			}
			$this->_view->assign('users',$users);
			$this->_view->display();
		}
	}

	/**
	 * 解除禁言
	 */
	public function actionTalkUserDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'refuseMessage/refuseMessage');
		//		$data=$this->getApi()->deleteRefuseMsg($_POST['idList']);
		$data=$this->getApi()->updateStatus($_POST['idList']);
		if ($data===0){
			$this->_utilMsg->showMsg("解除禁言成功");
		}else {
			$this->_utilMsg->showMsg('解除禁言失败',-2);
		}
	}

	public function actionGiftCard(){
		switch ($_GET['doaction']){
			case 'add':
				//目前屏蔽直接添加
				$this->_utilMsg->showMsg('目前屏蔽直接添加',-1);
				return;
				$this->_addGiftCard();
				break;
			case 'apply':{
				$this->_applyGiftCard();
				break;
			}
			case 'reCache':
				$this->_getAllServerIds(-1);
				$this->_getGoods(-1);
				$this->_utilMsg->showMsg('操作成功',1);
				break;
			case 'serialnumber':
				$this->_getCardSerialNumber();
				break;
			case 'applyinfo':
				$this->_giftCardApplyInfo();
				break;
			case 'snQuery':
				$this->_giftCardSnQuery();
				break;
			case 'ajax':
				$this->_giftAjax();
				break;
			default:
				$this->_giftCardList();
		}
	}


	private function _giftAjax(){
		if(empty($_GET['id'])){
			$this->_returnAjaxJson(array('status'=>-2,'msg'=>'没有传入ID'));
		}
		if(count(explode(",",$_GET['id']))>=2){
			$this->_returnAjaxJson(array('status'=>-2,'msg'=>'只能查询一个ID'));
		}
		$playerId = trim($_GET['id']);
		$rpc=$this->getApi();
		$rpc->setUrl($_REQUEST['server_id'],'player/player');
		$dataList= $rpc->queryPlayer($playerId);
		$this->_returnAjaxJson(array('status'=>-2,'msg'=>$dataList));
	}

	private function _giftCardApplyInfo(){
		$mark = trim($_GET['mark']);
		if($_REQUEST['server_id']){
			$_modelApply = $this->_getGlobalData('Model_Apply','object');
			$dataList = $_modelApply->getByMark($mark,self::XUN_XIA_ID,$_REQUEST['server_id'],1);
		}
		//
		if($dataList){
			$users=$this->_getGlobalData('user');
			$dataList['apply_user_id'] = $users[$dataList['apply_user_id']]['full_name'];
			$dataList['audit_user_id'] = $users[$dataList['audit_user_id']]['full_name'];
			$dataList['create_time'] = date('Y-m-d H:i:s',$dataList['create_time']);
			$dataList['send_time'] = date('Y-m-d H:i:s',$dataList['send_time']);
		}else{
			$noRecode = '<font color="#999999">无记录</font>';		
			$dataList['apply_user_id'] 	= $noRecode;
			$dataList['audit_user_id'] 	= $noRecode;
			$dataList['create_time'] 	= $noRecode;
			$dataList['send_time'] 		= $noRecode;
		}
		$return = array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$dataList);
		$this->_returnAjaxJson($return);
	}

	private function _giftCardList(){
		$this->_checkOperatorAct();
		$this->_createCenterServer();
		if ($_REQUEST['server_id']){
			$server_list	=	$this->_getGlobalData('gameser_list');
			//			$this->_view->assign("server_url",$server_list[$_REQUEST['server_id']]["server_url"]);
			//写死
			$this->_view->assign("server_url",'http://183.60.65.94/kefu/');

			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$_GET['page'] = max(1,intval($_GET['page']));	//接口用整形
			$dataList=$this->getApi()->queryGiftCard(($_GET['page']-1)*PAGE_SIZE,PAGE_SIZE);//第一个参数是偏移量
			//"$dataList" = Object of: com_cndw_gm_util_Page
			//	totalCount = (double) 93
			//	start = (double) 0
			//	data = Array [20]
			//		0 = Object of: com_cndw_gm_pojo_GiftContentDto
			//			goodsNumList = (string:0)
			//			copper = (double) 12
			//			goodIdList = (string:0)
			//			goodId = null
			//			contentName = (string:0)
			//			equips = null
			//			forces = (double) 12
			//			exploit = (double) 12
			//			id = (double) 92
			//			prestige = (double) 12
			//			token = null
			//			goodsnum = null
			//			name = (string:3) 111
			//			giftId = (double) 93
			//			goodnameList = (string:0)
			//			gold = null
			//			serverId = (string:14) 192.168.15.21,
			//		1 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		2 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		3 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		4 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		5 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		6 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		7 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		8 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		9 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		10 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		11 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		12 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		13 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		14 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		15 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		16 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		17 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		18 = Object of: com_cndw_gm_pojo_GiftContentDto
			//		19 = Object of: com_cndw_gm_pojo_GiftContentDto
			//	pageSize = (int) 15
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$dataList->totalCount,'perpage'=>PAGE_SIZE));
			$this->_view->assign('pageBox',$helpPage->show());

			$serversInfo = $this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
			$serversInfo = Model::getTtwoArrConvertOneArr($serversInfo,'marking','server_name');

			if($dataList->data){
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				foreach($dataList->data as $list){
					$list->mark = $list->giftId.'_'.$list->id;
					$list->URL_SerialNumber = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'serialnumber','server_id'=>$_REQUEST['server_id'],'giftId'=>$list->giftId,'giftContentId'=>$list->id));
					$list->URL_ApplyInfo = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'applyinfo','server_id'=>$_REQUEST['server_id'],'mark'=>$list->mark));
					$list->goodIdList = trim($list->goodIdList,',');
					$list->serverId = trim($list->serverId,',');
					$list->ServerArr = explode(',',trim($list->serverId));
					$ll = $_modelApply->getByMark($list->mark,self::XUN_XIA_ID,$_REQUEST['server_id'],1);
					if(empty($ll)){
						$list->quan = 0;
					}else{
						$userClass =$this->_utilRbac->getUserClass();
						if($ll["apply_user_id"]==$userClass["_id"]){
							$list->quan = 1;
						}else{
							$list->quan = 0;
						}
					}
					foreach($list->ServerArr as &$serverMark){
						if(isset($serversInfo[$serverMark])){
							$serverMark = $serversInfo[$serverMark];
						}
					}
					$tmp = array();
					if($list->goodIdList){
						$goodIdList = explode(',',$list->goodIdList);
						$goodnameList = explode(',',trim($list->goodnameList,','));
						$goodsNumList = explode(',',trim($list->goodsNumList,','));
						foreach ($goodIdList as $key => $val){
							$tmp[$key]=array($goodnameList[$key],$goodsNumList[$key]);
						}
					}
					$list->goods = $tmp;
				}
			}
			$this->_view->assign('dataList',$dataList->data);
		}
		$this->_view->assign('URL_ApplyGiftCard',Tools::url(CONTROL,'GiftCard',array('zp'=>'XunXia','doaction'=>'apply','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,'GiftCard',array('zp'=>'XunXia','doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_AddGiftCard',Tools::url(CONTROL,'GiftCard',array('zp'=>'XunXia','doaction'=>'add','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_SnQuery',Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'snQuery','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/GiftCardList.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	private function _getCardSerialNumber(){
		//		Long giftId
		//		Long giftContentId
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$giftId = intval($_REQUEST['giftId']);
			$giftContentId = intval($_REQUEST['giftContentId']);
			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$data = $this->getApi()->getCardSerialNumber($giftId,$giftContentId);
			$cardList = array();
			foreach($data as $key => $val){
				$cardList[$key] = $val->identification;
			}
			if($this->_isAjax()){
				$return = array('status'=>1,'info'=>CONTROL.'_'.ACTION,'data'=>$cardList);
				$this->_returnAjaxJson($return);
			}else{
				$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/CardSerialNumber.html'));
				$this->_view->assign('cardList',$cardList);
				$this->_view->display();
			}

		}
	}

	/**
	 * 申请
	 */
	private function _applyGiftCard(){
		$this->_checkOperatorAct();
		//		$this->_createServerList();
		$this->_createCenterServer();
		$servers = $this->_getAllServerIds();
		$serversInfo = $this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
		$serversInfo = Model::getTtwoArrConvertOneArr($serversInfo,'marking','server_name');
		$cardTypeInfo = array(
		1=>array('name'=>'标准礼物卡','bindPlayer'=>true),
		2=>array('name'=>'24字礼物卡','bindPlayer'=>false),
		3=>array('name'=>'媒体卡','bindPlayer'=>true),
		4=>array('name'=>'四字礼物卡','bindPlayer'=>false),
		);
		//		$cardType = array(
		//			1=>'标准礼物卡',
		//			2=>'24字礼物卡',
		//			4=>'四字礼物卡',
		//		);
		$cardType = array();
		foreach($cardTypeInfo as $key =>$val){
			$cardType[$key] = $val['name'];
		}
		if($_REQUEST['server_id']){
			if($this->_isPost()){

				$sendData = array();
				//				$sendData['serverId'] = $_POST['serverId'];
				if($_POST['endTime'] && strtotime($_POST['endTime'])<=CURRENT_TIME){
					$this->_utilMsg->showMsg('结束时间不能比现在时间早',-1);
				}
				if($_POST['serverId'] && is_array($_POST['serverId'])){
					//					$temp = array();
					//					foreach($sendData['serverId'] as $val){
					//						$temp[$val.'_'.$servers[$val]] = $servers[$val];
					//					}
					//					$sendData['serverId'] = $temp;
					//					unset($temp);
					foreach($_POST['serverId'] as $val){
						if($servers[$val]){
							$sendData['pkids'][$val] = $servers[$val];
							//$sendData['serversInfo'][$val] = isset($serversInfo[$servers[$val]])?$serversInfo[$servers[$val]]:$servers[$val];
						}
					}
					if(empty($sendData['pkids'])){
						$this->_utilMsg->showMsg('服务器信息错误，请更新服务器',-1);
					}
					$sendData['pkids'] = implode(',',$sendData['pkids']);
					$sendData['serversInfo'] = implode(',',$_POST['serverId'] );
				}else{
					$this->_utilMsg->showMsg('请选择服务器',-1);
				}

				if($_POST['bindPlayer']){
					//					if(count($_POST['serverId'])>1){
					//						$this->_utilMsg->showMsg('绑定用户只允许选择一个服务器',-1);
					//					}
					$sendData['playerId'] = trim($_POST['playerId']);
					$sendData['numbers'] = 1;
					if(!trim($_POST['title']) && !trim($_POST['content'])){
						$this->_utilMsg->showMsg('标题或内容为空',-1);
					}
					$sendData['title'] = trim($_POST['title']);
					$sendData['content'] =  $_POST['content'];
				}else{
					$sendData['playerId'] = '0';
					$sendData['numbers'] = intval($_POST['numbers']);
					$sendData['title'] = '';
					$sendData['content'] =  '';
				}
				if(empty($_POST["upfile"])){
					$sendData["upfile"]=0;
				}else{
					$sendData["upfile"]=1;
				}

				if($sendData['playerId']){
					$player_type=1;	//1 UserId
					$player_info=$sendData['playerId'];
				}else{
					$player_type=0;	//0 无玩家
					$player_info='';
				}

				$sendData['cardType'] = intval($_POST['cardType']);
				if(!array_key_exists($sendData['cardType'],$cardType) ){
					$this->_utilMsg->showMsg('无权申请此类型的卡',-1);
				}
				$sendData['isSendMail'] = intval($_POST['isSendMail']);	//后来加的是否发送邮件
				$sendData['name'] = trim($_POST['name']);

				$sendData['endTime'] = trim($_POST['endTime']);
				$sendData['copper'] = intval($_POST['copper']);
				$sendData['forces'] = intval($_POST['forces']);
				$sendData['exploit'] = intval($_POST['exploit']);
				$sendData['prestige'] = intval($_POST['prestige']);
				$sendData['activePoints'] = intval($_POST['activePoints']);
				$sendData['huanYuanDan'] = intval($_POST['huanYuanDan']);
				$sendData['token'] = intval($_POST['token']);
				$sendData['trumpet'] = intval($_POST['trumpet']);	//后来加的喇叭
				$sendData['pwnuNum'] = intval($_POST['pwnuNum']);	//后来加的天山雪莲
				$sendData['goldType'] = intval($_POST['goldType']);
				$sendData['gold'] = intval($_POST['gold']);

				$sendData['potion_1'] = trim($_POST['potion_1']);
				$sendData['potion_2'] = trim($_POST['potion_2']);
				$sendData['potion_3'] = intval($_POST['potion_3']);
				$sendData['potion_4'] = intval($_POST['potion_4']);
				$sendData['potion_5'] = intval($_POST['potion_5']);
				$sendData['puncher'] = intval($_POST['puncher']);
				$sendData['extirpator'] = intval($_POST['extirpator']);
				$sendData['spar'] = intval($_POST['spar']);

				$sendData['goods'] = array();
				$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
				$userClass=$this->_utilRbac->getUserClass();
				if($userClass['_departmentId']==1 && in_array('kf_xz', $userClass['_roles'])){
					if($sendData['gold'] >20000){
						$this->_utilMsg->showMsg('元宝不能过20000',-1);
					}
				}

				if($_POST['goods'] && is_array($_POST['goods'] )){
					foreach($_POST['goods'] as $k => $v){
						if($v){
							$sendData['goods'][$k] = intval($v);
						}
					}
				}
				$apply_info = "<div>申请原因:</div><div style='padding-left:10px;'>{$_POST['cause']}</div>";
				$apply_info .= $this->_getCardInfo($sendData);
				$URL_giftCardList = Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id']));
				$apply_info .='<a href="'.$URL_giftCardList.'">礼包卡列表</a>';

				//				if($sendData['serverId']){
				//					$sendData['serverId'] = array_values($sendData['serverId']);
				//				}

				unset($sendData['serversInfo']);
				$sendData = json_encode($sendData);

				$gameser_list = $this->_getGlobalData('gameser_list');
				$applyData = array(
					'type'=>2,
					'server_id'=>$_REQUEST['server_id'],
					'operator_id'=>$gameser_list[$_REQUEST['server_id']]['operator_id'],
					'game_type'=>$gameser_list[$_REQUEST['server_id']]['game_type_id'],
					'list_type'=>1,
					'apply_info'=>$apply_info,
					'send_type'=>3,	//3	phprpc
					'send_data'=>array (
						'url_append'=>'card2/giftCard',
						'phprpc_method'=>'generateCard',
						'phprpc_params'=>array($sendData),
						'end'=>array(	//结束后调用此方法对结果进行处理
								'cal_local_object'=>'Util_ApplyInterface',	//使用本地对象，如果为空，则使用内置函数
								'cal_local_method'=>'XunXiaBackDt',	//使用本地方法
								'params'=>array('ExtParam'=>'1'),	//用传入的参数代替此参数
				),
				),
					'receiver_object'=>array($_REQUEST['server_id']=>''),
					'player_type'=>$player_type,
					'player_info'=>$player_info,
				);
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$applyInfo = $_modelApply->AddApply($applyData);
				if( true === $applyInfo){
					$URL_CsIndex = Tools::url('Apply','CsIndex');
					$this->_utilMsg->showMsg("申请成功,等待审核...<br><a href='{$URL_CsIndex}'>客服审核列表</a>",1,$URL_giftCardList,false);
				}else{
					$this->_utilMsg->showMsg($applyInfo['info'],-1);
				}
			}else{
				if($servers){
					$serverToSlt = array();
					foreach($servers as $k =>$v){
						$serverToSlt[$k] = "{$k}";
						//$serverToSlt[$k] = isset($serversInfo[$v])?$serversInfo[$v]."($v)":$v;
					}
					$this->_view->assign('servers',$serverToSlt);
				}
				$Goods = $this->_getGoods();
				$this->_view->assign('Goods',$Goods);
				$GoodsDesc= array(
					'AllBook'		=>'饰品',
					'AllBingFu'		=>'鞋',
					'AllCap'		=>'帽子',
					'AllCloak'		=>'披风',
					'AllHorse'		=>'书籍',
					'AllLoricae'	=>'铠甲',
					'AllWeapon'		=>'武器',
				//'AllBoxs'	=>'书籍',
					'AllRings'		=>'戒指',
					'AllFashions'	=>'时装',
					'AllAllShenDan'	=>'神丹',
				);
				$this->_view->assign('GoodsDesc',$GoodsDesc);
			}
		}
		$endTime = date('Y-m-d H:i:s',strtotime('+1 month',CURRENT_TIME));
		$this->_view->assign('endTime',$endTime);
		$this->_view->assign('cardTypeInfo',json_encode($cardTypeInfo));
		$this->_view->assign('cardType',$cardType);
		$this->_view->assign('checkuserurl',Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'ajax','server_id'=>$_REQUEST['server_id'])));
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,'GiftCard',array('zp'=>'XunXia','doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/AddGiftCard.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();

	}

	private function _getCardInfo($data){

		if(!$data){
			return false;
		}
		$sendData['playerId'] ='绑定的UID';
		$sendData['serversInfo'] = '服务器';
		$sendData['cardType'] = array('卡片类型',array(1=>'标准礼物卡',2=>'24字礼物卡',3=>'媒体卡',4=>'四字礼物卡'));
		$sendData['name'] = '卡片名称';
		$sendData['numbers'] = '生成数量';
		$sendData['endTime'] = '结束时间';
		$sendData['copper'] = '铜钱';
		$sendData['forces'] = '血量';
		$sendData['activePoints'] = '活跃积分';
		$sendData['huanYuanDan'] = '还原丹';

		$sendData['potion_1'] = "初级药水";
		$sendData['potion_2'] = "中级药水";
		$sendData['potion_3'] = "高级药水";
		$sendData['potion_4'] = "上级药水";
		$sendData['potion_5'] = "顶级药水";
		$sendData['puncher'] = "打孔器";
		$sendData['extirpator'] = "摘除器";
		$sendData['spar'] = "晶石";


		$sendData['exploit'] = '修为';
		$sendData['prestige'] = '声望';
		$sendData['token'] = '精力';
		$sendData['trumpet'] = '喇叭';
		$sendData['pwnuNum'] = '天山雪莲';
		$sendData['goldType'] = array('元宝类型',array(4=>'GM充值元宝',5=>'GM套餐元宝',6=>'系统元宝'));
		$sendData['gold'] = '元宝数量';
		$sendData['goods'] = '道具';
		$sendData['isSendMail'] = array('是否发送邮件',array(0=>'否',1=>'是'));
		$sendData['title'] = '邮件标题';
		$sendData['content'] = '邮件内容';
		$str = '';

		foreach($data as $key =>$val){
			if(!isset($sendData[$key]))continue;
			if(is_array($val)){
				if($str) $str .='<br>';
				$str .="<b>{$sendData[$key]}</b>:";
				foreach($val as $k=>$v){
					$str .=ltrim(strstr($k,'_'),'_')."(<font style='color:red'>{$v}</font>)、";
				}
				$str .='<br>';
			}else{
				if(is_array($sendData[$key])){
					$str .= "<b>{$sendData[$key][0]}</b>"."(<font style='color:red'>{$sendData[$key][1][$val]}</font>)、 ";
				}else{
					$str .= "<b>{$sendData[$key]}</b>"."(<font style='color:red'>{$val}</font>)、 ";
				}

			}
		}
		return "<div>礼包内容：</div><div style='padding-left:10px;'>{$str}</div>";
	}


	private function _addGiftCard(){
		$this->_checkOperatorAct();
		//		$this->_createServerList();
		$this->_createCenterServer();
		$servers = $this->_getAllServerIds();
		$serversInfo = $this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
		$serversInfo = Model::getTtwoArrConvertOneArr($serversInfo,'marking','server_name');

		$cardTypeInfo = array(
		1=>array('name'=>'标准礼物卡','bindPlayer'=>true),
		2=>array('name'=>'24字礼物卡','bindPlayer'=>false),
		3=>array('name'=>'媒体卡','bindPlayer'=>true),
		4=>array('name'=>'四字礼物卡','bindPlayer'=>false),
		);
		$cardType = array();
		foreach($cardTypeInfo as $key =>$val){
			$cardType[$key] = $val['name'];
		}
		if($_REQUEST['server_id']){
			if($this->_isPost()){
				$sendData = array();
				if($_POST['serverId'] && is_array($_POST['serverId'])){
					foreach($_POST['serverId'] as $val){
						if($servers[$val]){
							$sendData['serverId'][$val] = $servers[$val];
							$sendData['serversInfo'][$val] = isset($serversInfo[$servers[$val]])?$serversInfo[$servers[$val]]:$servers[$val];
						}
					}
					if(empty($sendData['serverId'])){
						$this->_utilMsg->showMsg('服务器信息错误，请更新服务器',-1);
					}
					$sendData['serverId'] = implode(',',$sendData['serverId']);
					$sendData['serversInfo'] = implode(',',$sendData['serversInfo']);
				}else{
					$this->_utilMsg->showMsg('请选择服务器',-1);
				}

				if($_POST['bindPlayer']){
					if(count($_POST['serverId'])>1){
						$this->_utilMsg->showMsg('绑定用户只允许选择一个服务器',-1);
					}
					$sendData['playerId'] = trim($_POST['playerId']);
					$sendData['numbers'] = 1;
					if(!trim($_POST['title']) && !trim($_POST['content'])){
						$this->_utilMsg->showMsg('标题或内容为空',-1);
					}
					$sendData['title'] = trim($_POST['title']);
					$sendData['content'] =  $_POST['content'];
				}else{
					$sendData['playerId'] = '0';
					$sendData['numbers'] = intval($_POST['numbers']);
					$sendData['title'] = '';
					$sendData['content'] =  '';
				}

				if($sendData['playerId']){
					$player_type=1;	//1 UserId
					$player_info=$sendData['playerId'];
				}else{
					$player_type=0;	//0 无玩家
					$player_info='';
				}

				$sendData['cardType'] = intval($_POST['cardType']);
				if(!array_key_exists($sendData['cardType'],$cardType) ){
					$this->_utilMsg->showMsg('无权申请此类型的卡',-1);
				}

				$sendData['name'] = trim($_POST['name']);
				$sendData['endTime'] = trim($_POST['endTime']);
				$sendData['copper'] = intval($_POST['copper']);
				$sendData['forces'] = intval($_POST['forces']);
				$sendData['exploit'] = intval($_POST['exploit']);
				$sendData['prestige'] = intval($_POST['prestige']);
				$sendData['token'] = intval($_POST['token']);
				$sendData['goldType'] = intval($_POST['goldType']);
				$sendData['gold'] = intval($_POST['gold']);
				$sendData['goods'] = array();
				if($_POST['goods'] && is_array($_POST['goods'] )){
					foreach($_POST['goods'] as $k => $v){
						if($v){
							$sendData['goods'][$k] = intval($v);
						}
					}
				}
				unset($sendData['serversInfo']);

				$sendData = json_encode($sendData);
				$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
				$dataList=$this->getApi()-> generateCard($sendData);
				print_r($dataList);
				exit();
			}else{
				if($servers){
					$serverToSlt = array();
					foreach($servers as $k =>$v){
						$serverToSlt[$k] = isset($serversInfo[$v])?$serversInfo[$v]."($v)":$v;
					}
					$this->_view->assign('servers',$serverToSlt);
				}
				$Goods = $this->_getGoods();
				$this->_view->assign('Goods',$Goods);
				$GoodsDesc= array(
					'AllBook'		=>'饰品',
					'AllBingFu'		=>'鞋',
					'AllCap'		=>'帽子',
					'AllCloak'		=>'披风',
					'AllHorse'		=>'书籍',
					'AllLoricae'	=>'铠甲',
					'AllWeapon'		=>'武器',
					'AllRings'		=>'戒指',
					'AllFashions'	=>'时装',
					'AllAllShenDan'	=>'神丹',
				);

				$this->_view->assign('GoodsDesc',$GoodsDesc);
			}
		}
		$this->_view->assign('cardTypeInfo',json_encode($cardTypeInfo));
		$this->_view->assign('cardType',$cardType);
		$this->_view->assign('URL_ReCache',Tools::url(CONTROL,'GiftCard',array('zp'=>'XunXia','doaction'=>'reCache','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/AddGiftCard.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();

	}



	private function _getAllServerIds($effectiveTime=86400){
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$ServerArr = $this->_f('xunxia_server_id_'.$_REQUEST['server_id'],'',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($ServerArr){
				return $ServerArr;
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$dataList=$this->getApi()->getAllServerIds();
			if ($dataList && !$dataList instanceof PHPRPC_Error){
				//新
				//"$sub" = Object of: com_cndw_gm_pojo_ServerInfo
				//	port = (int) 8000
				//	id = (int) 4
				//	createAt = Object of: PHPRPC_Date
				//	outterIp = (string:13) 183.60.66.237
				//	status = (int) 1
				//	name = (string:2) s1
				//	domain = (string:26) s31.app24599.qqopenapp.com
				//	innerIp = (string:13) 10.142.27.111
				//	type = (int) 0



				//旧
				//"$dataList" = Array [3]
				//	0 = Object of: com_cndw_gm_dao_IPStringVo
				//		ipStr = (string:13) 192.168.12.25
				//		ipCode = (string:1) 3
				//	1 = Object of: com_cndw_gm_dao_IPStringVo
				//		ipStr = (string:14) 192.168.15.105
				//		ipCode = (string:1) 2
				//	2 = Object of: com_cndw_gm_dao_IPStringVo
				//		ipStr = (string:13) 192.168.14.21
				//		ipCode = (string:1) 1
				$ServerArr = array();
				foreach($dataList as $sub){
					if($sub->name  && $sub->id){
						$ServerArr[$sub->name] = $sub->id;
					}else{
						$ServerArr[intval($sub->ipCode)] = $sub->ipStr;
					}
				}
				$this->_f('xunxia_server_id_'.$_REQUEST['server_id'],$ServerArr);	//缓存数据数据
				return $ServerArr;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	private function _getGoods($effectiveTime=86400){
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$goods = $this->_f('xunxia_goods_'.$_REQUEST['server_id'],'',CACHE_DIR,$effectiveTime);	//取24小时内有效的缓存数据
			if($goods){
				return $goods;
			}
			$this->getApi()->setUrl($_REQUEST['server_id'],'goods/goodsServe');
			$AllBook 		= 	$this->getApi()->queryAllBook();		//书籍
			$AllBingFu 		= 	$this->getApi()->queryAllBingFu();		//兵符
			$AllCap 		= 	$this->getApi()->queryAllCap();			//帽子
			$AllCloak 		= 	$this->getApi()->queryAllCloak();		//披风
			$AllHorse 		= 	$this->getApi()->queryAllHorse();		//马
			$AllLoricae 	= 	$this->getApi()->queryAllLoricae();		//铠甲
			$AllWeapon 		= 	$this->getApi()->queryAllWeapon();		//武器
			$AllRings		=	$this->getApi()->queryAllRings();		//戒指
			$AllFashions	=	$this->getApi()->queryAllFashions();	//时装,
			$AllShenDan		=	$this->getApi()->queryAllShenDan();		//神丹
			//$AllBoxs		=	$this->getApi()->queryAllBoxs();		//书籍

			$dataList= array(
				'AllBook'		=>$AllBook,
				'AllBingFu'		=>$AllBingFu,
				'AllCap'		=>$AllCap,
				'AllCloak'		=>$AllCloak,
				'AllHorse'		=>$AllHorse,
				'AllLoricae'	=>$AllLoricae,
				'AllWeapon'		=>$AllWeapon,
				'AllRings'		=>$AllRings,
				'AllFashions'	=>$AllFashions,
				'AllAllShenDan'	=>$AllShenDan,
			//'AllBoxs'	=>$AllBoxs
			);

			$goods = array();
			foreach($dataList as $key => $sub){
				foreach($sub as $k => $val){
					$goods[$key][$val->goodsId] = $val->name;
				}
			}
			$this->_f('xunxia_goods_'.$_REQUEST['server_id'],$goods);	//缓存数据数据
			return $goods;
		}else{
			return false;
		}
	}

	private function _operateDetail($operateType=0){
		if ($_REQUEST['server_id']){
			$gameUserId = $_GET['game_user_id'];
			$operateType = intval($operateType);
			$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
			$dataList = $this->_modelGameOperateLog->getDetail($_REQUEST['server_id'],$gameUserId,$operateType,1,50);
			//print_r($dataList);
			$jsonData = array('status'=>1,'info'=>NULL,'data'=>$dataList);
		}else{
			$jsonData = array('status'=>0,'info'=>'server id error','data'=>NULL);
		}
		$this->_returnAjaxJson($jsonData);
	}

	private function _giftCardSnQuery(){
		$this->_checkOperatorAct();
		//		$this->_createServerList();
		$this->_createCenterServer();
		if ($_REQUEST['server_id'] && $this->_isPost()){
			$sn = trim($_POST['sn']);
			$this->getApi()->setUrl($_REQUEST['server_id'],'card2/giftCard');
			$data = $this->getApi()->selectByIdentify($sn);

			if($data->giftId && $data->contentId){
				$dataList['userId'] 	= $this->d2s($data->userId);
				$dataList['useServerId'] = $data->useServerId;//useServerId
				$mark = $data->giftId.'_'.$data->contentId;
				$_modelApply = $this->_getGlobalData('Model_Apply','object');
				$dataList = $_modelApply->getByMark($mark,self::XUN_XIA_ID,$_REQUEST['server_id'],1);
			}
			$dataList['userId'] 	= $this->d2s($data->userId);
			$dataList['useServerId'] = $data->useServerId;//useServerId
			if($dataList){
				$users=$this->_getGlobalData('user');
				$dataList['apply_user_id'] = $users[$dataList['apply_user_id']]['full_name'];
				$dataList['audit_user_id'] = $users[$dataList['audit_user_id']]['full_name'];
				$dataList['create_time'] = date('Y-m-d H:i:s',$dataList['create_time']);
				$dataList['send_time'] = date('Y-m-d H:i:s',$dataList['send_time']);
				
			}else{
				$noRecode = '<font color="#999999">无记录</font>';		
				$dataList['apply_user_id'] = $noRecode;
				$dataList['audit_user_id'] = $noRecode;
				$dataList['create_time'] = $noRecode;
				$dataList['send_time'] = $noRecode;

			}
			$this->_view->assign('sn',$sn);
			$this->_view->assign('dataList',$dataList);
		}
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/GiftCardSnQuery.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	public function actionLockIP(){
		switch ($_REQUEST['doaction']){
			case 'add':
				$this->_lockIPAdd();
				break;
			case 'del':
				$this->_lockIPDel();
				break;
			case 'detail':{
				$this->_operateDetail(5);
				return ;
			}
			default :
				$this->_lockIPIndex();
		}
	}

	private function _lockIPIndex(){
		$this->_checkOperatorAct();
		$this->_createServerList();
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');
			//"$dataList" = Object of: xn_util_QueryResultInfo
			//	page = Object of: xn_util_Page
			//		totalCount = (double) 4
			//		start = (double) 0
			//		data = Array [4]
			//			0 = Object of: xn_domain_admin_RefuseIP
			//				uid = (double) 3212015
			//				id = (double) 4
			//				createAt = Object of: PHPRPC_Date
			//				status = (int) 1
			//				beginAt = (double) 1.317132848E+012
			//				endAt = (double) 1.323490085E+012
			//				serverId = null
			//				operator = null
			//				end = Object of: PHPRPC_Date
			//				ip = (string:14) 192.168.12.104
			//				begin = Object of: PHPRPC_Date
			//			1 = Object of: xn_domain_admin_RefuseIP
			//			2 = Object of: xn_domain_admin_RefuseIP
			//			3 = Object of: xn_domain_admin_RefuseIP
			//		pageSize = (int) 20
			//	code = (int) 0
			$dataList=$this->getApi()->queryRefuseIP1($_GET['page'],PAGE_SIZE);
			//print_r($dataList);
			foreach ($dataList->page->data as &$sub){
				$sub->URL_TimeEnd		=	Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'detail','id'=>$sub->id,'server_id'=>$_REQUEST['server_id']));
			}
			if (is_object($dataList) && !$dataList instanceof PHPRPC_Error){
				$this->_loadCore('Help_Page');
				$this->_helpPage=new Help_Page(array('total'=>$dataList->page->totalCount,'perpage'=>PAGE_SIZE));
				$this->_view->assign('dataList',$dataList->page->data);
				$this->_view->assign('pageBox',$this->_helpPage->show());
			}
		}
		$this->_view->assign('URL_LockIPAdd',Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','doaction'=>'add','server_id'=>$_REQUEST['server_id'])));
		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/LockIPIndex.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}

	private function _lockIPAdd(){
		if ($this->_isPost() && isset($_POST['submit'])){
			$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');
			$data	=	$this->getApi()->saveRefuseIP1($_POST['userIds'],$_POST['endTime']);
			//	"$data" = Object of: xn_util_ResultInfo
			//		NoExists = (string:5) OMG啦,	
			//		data = (string:24) 400211,871213,512,:OMG啦,	
			//		code = (int) 0
			//		playerNameList = (string:0)
			//		userIdLists = (string:18) 400211,871213,512,
			//print_r($data);

			if ($data->code===0){
				$userids = explode(",", $_POST['userIds']);
				$this->_modelGameOperateLog = $this->_getGlobalData('Model_GameOperateLog','object');
				$AddLog = array(
				array('操作','<font style="color:#F00">封IP</font>'),
				array('操作时间',date('Y-m-d H:i:s',CURRENT_TIME)),
				array('操作人','<b>{UserName}</b>'),
				array('封IP结束时间',$_POST['endTime']),
				array('原因',$_POST['cause']),
				);
				$AddLog = $this->_modelGameOperateLog->addInfoMake($AddLog);
				foreach($userids as $sub){
					//todo();记录日志
					$userInfo['UserId'] = $sub;
					$GameOperateLog = $this->_modelGameOperateLog->MakeDataForStore($userInfo,5,$_REQUEST['server_id'],$AddLog);
					if(false !== $GameOperateLog){
						$this->_modelGameOperateLog->add($GameOperateLog);
					}
				}
				//				if($data->userIdLists){
				//					$data->userIdLists = trim($data->userIdLists,',');
				//					$data->userIdLists = explode(',',$data->userIdLists);
				//					$userInfo = array();
				//					foreach($data->userIdLists as $key => $val){
				//						$userInfo[$key]['UserId'] = $val;
				//					}
				//					//记录游戏后台新操作日志
				//
				//					$GameOperateLog = $this->_modelGameOperateLog->GameOperateLogMake($userInfo,5,$_REQUEST['server_id'],$AddLog);
				//					if(false != $GameOperateLog && is_array($GameOperateLog) && count($GameOperateLog)>0){
				//						foreach($GameOperateLog as $sub){
				//							$this->_modelGameOperateLog->add($sub);
				//						}
				//					}
				//				}
				$NoExist = '';
				if($data->data){
					$existUser = array_diff(explode(',',$_POST['userIds']),explode(',',$data->data));
					$NoExist = "<br />其中,无效玩家有:<font color='#ff0000'>{$data->data}</font>";
				}else{
					$existUser = explode(',',$_POST['userIds']);
				}

				$this->_utilMsg->showMsg("操作成功".$NoExist,1,Tools::url(CONTROL,ACTION,array('zp'=>'XunXia','server_id'=>$_REQUEST['server_id'])),false);
			}else {
				$this->_utilMsg->showMsg('操作失败',-2);
			}
		}else {
			$this->_checkOperatorAct();
			$this->_createServerList();
			$users = '';
			if($_POST['ids'] && is_array($_POST['ids'])){
				$users = implode(',',$_POST['ids']);
			}
			$this->_view->assign('users',$users);
			$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/LockIPAdd.html'));
			$this->_utilMsg->createPackageNavBar();
			$this->_view->display();
		}
	}

	private function _lockIPDel(){
		$this->getApi()->setUrl($_REQUEST['server_id'],'refuseIP/refuseIP');
		//		$data=$this->getApi()->deleteRefuseUser($_POST['idList']);
		$data=$this->getApi()->updateStatus($_POST['idList']);
		if ($data===0){
			$this->_utilMsg->showMsg("解除封号成功");
		}else {
			$this->_utilMsg->showMsg('解除封号失败',-2);
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
		$_GET['game_type']	=	self::XUN_XIA_ID;
		$server_msg			=	$this->_modelGameSerList->getSqlSearch($_GET);
		$timer = array('0'=>'关闭','1'=>'开启',''=>'全部');
		$this->_utilMsg->createPackageNavBar();
		$this->_view->assign('selectedGameTypeId',self::XUN_XIA_ID);
		$this->_view->assign('get',$_GET);
		$this->_view->assign('pageBox', $server_msg['pageBox']);
		$this->_view->assign('zp', "xunxia");
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
				$this->_utilMsg->showMsg ( Tools::getLang('UPDATE_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION , array('zp'=>"xunxia") ) );
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
			$this->_view->assign('game_type',self::XUN_XIA_ID);
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
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION,array('zp'=>"xunxia")) );
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
			$this->_view->assign('game_type',self::XUN_XIA_ID);
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
	
	
	
	public function actionComplementActivityKey(){
		
		if ($_REQUEST['server_id']){//如果设置了服务器id
			$rpc = $this->getApi();
			if ($this->_isPost ()) {
				
				$rpc->setUrl($_REQUEST['server_id'],'sendServerMsg/sendServerMsg');
				$domain = $this->_getServerMarking($_REQUEST['server_id']);
				
				$playerId = $_POST['playerId'];
				$codeId = $_POST['activeList'];
			  
				$dataList= $rpc->send($domain,$playerId,$codeId);
				var_dump($dataList);
				if($dataList == ' succes !'){
					$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1 );
				}
			}else{
				$rpc->setUrl($_REQUEST['server_id'],'sendServerMsg/sendServerMsg');
				$dataList = $rpc -> getActivityCode();
// 				print_r($dataList);
// 				var_dump(is_object($dataList));
				
				$activeList = array();
				if($dataList instanceof PHPRPC_Error){
					$this->_view->assign('ConnectErrorInfo',$dataList->Message);
				}elseif (is_array($dataList)){
					foreach ($dataList as $k => $v){
						$activeList [$v->codeId] = $v->codeName;
					}
				}
				$this->_view->assign('activeList',$activeList);
			}
		}
		
		$this->_checkOperatorAct();
		$this->_createServerList();
		$this->_view->assign('UrlLockUserDel',$UrlLockUserDel);
// 		$this->_view->set_tpl(array('body'=>'XunXia/XunXiaSysManage/ComplementActivityKey.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
		
	}
	private  function _getServerMarking($serverId=0){
		if(!$serverId){
			$serverId = $_REQUEST['server_id'];
		}
		if($serverId){
			$serverList=$this->_getGlobalData('gameser_list');
			
			$marking = $serverList[$serverId]['marking'];
		}
		return $marking;
	}


}