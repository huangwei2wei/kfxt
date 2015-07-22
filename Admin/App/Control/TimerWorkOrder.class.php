<?php
/**
 * 定时工单获取
 * @author php-兴源
 */
class Control_TimerWorkOrder extends Control {
	
	private $_key=TAKE_KEY;	//到时要改掉
	
	private $_validateRQ;
	
	private $_utilHttpMInterface;	//多路请求对象
	
	private $_modelWorkOrder;
	
	private $_modelGameSerList;
	
	private $_modelOrderLog;
	
	private $_utilWorkOrder;
	
	private $_modelWorkOrderDetail;
	
	private $_modelWorkOrderQa;
	
	private $_utilOnline;
	
	private $_gameClass;
	
	private $_serverList;	//该游戏所有设置可同步的服务器
	
	private $_urlApdWO = array(	//使用定时器的工单请求地址附加字符
		'new'=>'',
		'newCbk'=>'',
		'del'=>'',
		'delCbk'=>'',
		'ev'=>'',
		'evCbk'=>'',
	);
	
	private $_preImgPath = '';//图片前置路径
	
	private $_contentSelf;	//新获取内容（对话）是否结构独立
	
	public function __construct(){
		$this->_validate();
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');

	}
	
	public function actionScan(){
		$_modelGameTimer = $this->_getGlobalData('Model_GameTimer','object');		
		switch ($_REQUEST['doaction']){
			case 'del':
				$field = 'time_wo_del';
				$method = '_getDel';
				break;
			case 'ev':
				$field = 'time_wo_ev';
				$method = '_getEv';
				break;
			case 'new':
				$field = 'time_wo_new';
				$method = '_getNew';
				break;
			default:
				$this->_error('doaction parameter error');
		}
		if($_REQUEST['game_id'] && $_REQUEST['chunk'] ){
			$result = $_modelGameTimer->checkOne($_REQUEST['game_id'],$_REQUEST['chunk']);
		}else{
			$result = $_modelGameTimer->getEarly($field);
		}
		
		if($result){
			$gameId = intval($result['game_type']);
			$chunk = intval($result['chunk']);
			$this->_serverList = $this->_getGlobalData("server/server_timer_{$gameId}_{$chunk}");
			//暂时屏蔽大唐某些服的评价同步
//			if(intval($gameId) === 7 && $_REQUEST['doaction'] == 'ev'){
//				unset($this->_serverList[883],$this->_serverList[905]);
//			}
			if($this->_serverList === false){
				$this->_error('serverList cache non-existent');
			}
			$this->_gameClass = $this->_getGlobalData($gameId,'game');
			//使用游戏配置的数据
			if($this->_gameClass){
				$this->_urlApdWO = $this->_gameClass->_urlApdWO;
				$this->_contentSelf = $this->_gameClass->_contentSelf;
				$this->_validateRQ['_verifycode'] = CURRENT_TIME;
				$this->_validateRQ['_sign'] = md5($this->_gameClass->_key.CURRENT_TIME);
				$this->_preImgPath = $this->_gameClass->_preImgPath;
			}
			$_modelGameTimer->updateOne($gameId,$chunk,$field);
			call_user_func(array(&$this,$method),$gameId);
		}else{
			$this->_error('game_id non-existent');
		}
	}
	
	/**
	 * 验证
	 */
	private function _validate() {
		$sign = strval($_REQUEST ['_sign']);
		$verifyCode = strval($_REQUEST ['_verifycode']);
		if (md5 ( $this->_key . $verifyCode ) !== $sign){
			$this->_error('SIGN ERROR');
		}
	}
	
	/**
	 * 失败返回
	 * @param unknown_type $info
	 */
	private function _error($info=''){
		$this->_returnAjaxJson(array ('status'=>0,'info'=>$info,'data'=>NULL));
	}
	
	/**
	 * 成功返回
	 * @param unknown_type $data
	 * @param unknown_type $info
	 */
	private function _success($data=NULL,$info=''){
		if(empty($info))$info=CONTROL.'_'.ACTION;
		$this->_returnAjaxJson(array ('status'=>1,'info'=>$info,'data'=>$data));
	}
	
	/**
	 * 处理同步回来的新工单
	 * @param array $workOrder
	 */
	private function _saveWorkOrder($workOrder,$serverId=0){
		$orderArr = array ();
		$orderArr ['game_type'] = $workOrder ['game_id'];
		$orderArr ['user_account'] = $workOrder ['user_account']?$workOrder ['user_account']:0;
		$orderArr ['user_nickname'] = $workOrder ['user_nickname'];
		$orderArr ['money'] = intval($workOrder ['money_total']);
		$orderArr ['money'] = $orderArr ['money']<0?0:$orderArr ['money'];
		$orderArr ['source'] = $workOrder ['source']; //工单来源
		$orderArr ['title'] = strip_tags($workOrder['title']);
		$orderArr ['question_type'] = $workOrder ['question_type'];
		$DialogCount = max(1,intval(count($workOrder['contents'])));
		$orderArr ['question_num'] = $DialogCount;
		$orderArr ['create_time'] = $workOrder['create_time'];
		$orderArr ['create_time'] = CURRENT_TIME;	//使用系统当时时间
//		if(is_double($orderArr ['create_time'])){
//			$orderArr ['create_time'] = $orderArr ['create_time']/1000;
//		}
		$orderArr ['game_user_id']=$workOrder['user_id'];
		$serverMarking = $workOrder ['server_marking'];
		if ($workOrder['partner']){
			$serverMarking=$workOrder['partner'].'|'.$serverMarking;
		}
		$this->_modelGameSerList = $this->_getGlobalData ( 'Model_GameSerList', 'object' );
//		$serverList = $this->_getGlobalData('server/server_timer_'.$workOrder ['game_id']);
		$gameServerList = $this->_serverList[$serverId];
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperatorIndex = $this->_modelGameOperator->findByGidOId ( $workOrder ['game_id'], $gameServerList ['operator_id'] ); //找到此运营商的详细 资料.
		if (! $gameOperatorIndex) { //未找到游戏与运营商的索引 
			return ( array ('status' => 0, 'info' => 'game server or operator non-existent', 'data' => null ) );
		}
		//vip等级，如果从游戏中有传$workOrder['vip_level']过来，就使用$workOrder['vip_level']，否则使用配置值
		$orderArr ['vip_level'] = isset($workOrder['vip_level'])?intval($workOrder['vip_level']):$this->_modelGameOperator->getVipLevel ( $gameOperatorIndex ['vip_setup'] ['vip_pay'], $workOrder ['money_total'] );
		$orderArr ['vip_level'] = min(6,$orderArr ['vip_level']);
		$orderArr ['timeout'] = $this->_modelGameOperator->getTimeOut ( $gameOperatorIndex ['vip_setup'] ['vip_timeout'], $orderArr ['vip_level'] );
		$orderArr ['timeout'] *= 60; //换成秒
		$orderArr ['is_verify']=0;
		$orderArr ['operator_id'] = $gameServerList ['operator_id'];
		$orderArr ['game_server_id'] = $gameServerList ['Id'];
		if ($gameServerList ['room_id']){
			$orderArr ['room_id'] = $gameServerList ['room_id'];
		}			
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		if (! $this->_modelWorkOrder->add ( $orderArr )) { //创建表单失败
			return ( array ('status' => 0, 'info' => 'Failure to create order', 'data' => null ) );
		}
		$workOrderId = $this->_modelWorkOrder->returnLastInsertId (); //获取工单id
		
		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('game_type_id'=>$orderArr['game_type'],'operator_id'=>$orderArr['operator_id'],'server_id'=>$orderArr['game_server_id'],'Id'=>$workOrderId),Model_OrderLog::ASK);
		#------追加日志------#
		
		#------增加新的工单到队列------#
		if ($orderArr ['room_id']) {
			$addOrder = array (
						'Id' => $workOrderId, 
						'vip_level' => $orderArr ['vip_level'], 
						'room_id' => $orderArr ['room_id'], 
						'game_type_id' => $orderArr ['game_type'], 
						'operator_id' => $orderArr ['operator_id'] );
			$this->_utilWorkOrder = $this->_getGlobalData ( 'Util_WorkOrder', 'object' );
			$orderManageClass = $this->_utilWorkOrder->getOrderManage ();
			$orderManageClass->addOrder ( $addOrder );
			$orderManageClass = null;
		}
		#------增加新的工单到队列------#
		$orderDetail = array ();
		$orderDetail ['form_detail'] = array ();
		
		$orderDetail ['user_data'] = array (
									'user_id' => $workOrder ['user_id'], 
									'user_account' => $workOrder ['user_account'], 
									'user_nickname' => $workOrder ['user_nickname'], 
									'money_total' => isset($workOrder ['money_total'])?$workOrder ['money_total']:-1, 
									'money_month' => isset($workOrder ['money_month'])?$workOrder ['money_month']:-1, 
									'register_date' => (!$workOrder ['register_date'] || $workOrder ['register_date']=='null')?null:$workOrder ['register_date'], 
									'ip' => $workOrder ['ip'] );
		
		//钻类型
		if(isset($workOrder ['flatType'])){
			$orderDetail ['ext']['flatType']['desc'] = '钻类型';
			switch($workOrder ['flatType']){
				case '1':
					$orderDetail ['ext']['flatType']['value']='<font color="#FF9900">黄钻</font>';
					break;
				case '10':
					$orderDetail ['ext']['flatType']['value']='<font color="#0000FF">蓝钻</font>';
					break;
				default:
					$orderDetail ['ext']['flatType']['value']='<font color="#999999">无</font>';
			}
			
		}
		//钻等级
		if(isset($workOrder ['diamondLevel'])){
			$orderDetail ['ext']['diamondLevel'] = array(
				'desc'=>'钻等级',
				'value'=>$workOrder ['diamondLevel'],
			);
		}
		//是否年费黄钻
		if(isset($workOrder['yearDiamond'])){
			$orderDetail ['ext']['yearDiamond'] = array(
				'desc'=>'是否年费',
				'value'=>$workOrder ['yearDiamond']?'是':'否',
			);
		}
		//游戏VIP等级
		if(isset($workOrder ['vip'])){
			$orderDetail ['ext']['vip'] = array(
				'desc'=>'游戏VIP等级',
				'value'=>$workOrder ['vip'],
			);
		}

		$orderDetail = serialize ( $orderDetail );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$this->_modelWorkOrderDetail->add ( array ('work_order_id' => $workOrderId, 'content' => $orderDetail ) );
		
		#-----插入对话表------#		
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		if($workOrder ['contents'] && is_array($workOrder ['contents'] )){
			$gameContentIds = array();
			foreach ($workOrder ['contents'] as $content){
				if($content['id']){
					$gameContentIds[] = $content['id'];
				}
				$content['content']=strip_tags($content['content']);
				$content['content'] = str_replace(array('\r\n','\r'),chr(10),$content['content']);
//				if(is_double($content['create_time'])){
//					$content['create_time'] = $content['create_time']/1000;
//				}
				//使用截图
				if($content['image_path']){
					$content['image_path'] = explode(',',$content['image_path']);
					$content['content'].='<br />玩家截图：<br />';
					foreach($content['image_path'] as $img){
						$img =str_replace('{server_url}',$gameServerList['server_url'],$this->_preImgPath).$img;
						$content['content'].="<img src='{$img}' /><br><hr size='1' />";
					}
				}
				$orderDialog = array ('user_id'=>0,'game_type_id' => $workOrder ['game_id'], 'operator_id' => $orderArr ['operator_id'], 'work_order_id' => $workOrderId, 'qa' => '0','content' => $content['content'],'create_time' => $content['create_time'] );
				$this->_modelWorkOrderQa->add ( $orderDialog );
			}
		}
		#-----插入对话表------#
		if($gameContentIds){
			$workOrderId .= ':'.implode('#',$gameContentIds);
		}
		$retArr=array('order_id'=>$workOrderId,'dialog_count'=>$DialogCount);
		return ( array ('status' => 1, 'info' =>'', 'data' => $retArr ) );
	}
	
	/**
	 * 处理同步回来的用户追问
	 * @param array $workOrder
	 */
	private function _saveReply($workOrder,$serverId) {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrderQa = $this->_getGlobalData ( 'Model_WorkOrderQa', 'object' );
		$DialogCount = 0;
		$gameContentIds = array();
		foreach($workOrder['contents'] as $content){
			if($content['id']){
				$gameContentIds[] = $content['id'];
			}
			$content['content'] = str_replace(array('\r\n','\r'),chr(10),$content['content']);
//			if(is_double($content['create_time'])){
//				$content['create_time'] = $content['create_time']/1000;
//			}
			//使用截图
			if($content['image_path']){
				$content['image_path'] = explode(',',$content['image_path']);
				$content['content'].='<br />玩家截图：<br />';
				foreach($content['image_path'] as $img){
					$img =str_replace('{server_url}',$this->_serverList[$serverId]['server_url'],$this->_preImgPath).$img;
					$content['content'].="<img src='{$img}' /><br><hr size='1' />";
				}
			}
			$addArr = array ('work_order_id' => $workOrder ['work_order_id'], 'content' =>$content['content'], 'qa' => 0, 'user_id' => 0 , 'create_time' => $content['create_time'] );
			if($this->_modelWorkOrderQa->add ( $addArr )){
				$DialogCount++;
			}			
		}
		 
		//$updateArr = array ('create_time' => $content['create_time'], 'status' => 1, 'question_num' => 'question_num+'.$DialogCount );
		//使用系统当时时间
		$updateArr = array ('create_time' => CURRENT_TIME, 'status' => 1, 'question_num' => 'question_num+'.$DialogCount );
		$this->_modelWorkOrder->update ( $updateArr, "Id={$workOrder['work_order_id']}" );
		
		#------检测如果当前负责工单的用户下线了,将重新载入工单------#
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');			
		$orderClass=$this->_utilWorkOrder->getOrderManage();
		$orderList=$this->_modelWorkOrder->findById($workOrder['work_order_id']);	//工单详细
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		$userIsOnline=$this->_utilOnline->isUserOnline($orderList['owner_user_id']);	//是否在线
		if (!$userIsOnline){//不在线
			$orderList['game_type_id']=$orderList['game_type'];//处理兼容
			$orderClass->addOrder($orderList);//增加到order队列 
		}
		#------检测如果当前负责工单的用户下线了,将重新载入工单------#

		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('Id'=>$workOrder['work_order_id']),Model_OrderLog::BACK_ASK);
		#------追加日志------#
		if($gameContentIds){
			$workOrder['work_order_id'] .= ':'.implode('#',$gameContentIds);
		}
		$retArr=array('order_id'=>$workOrder['work_order_id'],'dialog_count'=>$DialogCount);
		return ( array ('status' => 1, 'info' =>'', 'data' => $retArr ) );
	}
	
	private function _callback($workOrderResult,$gameId,$action=NULL){		
		if($workOrderResult){
			if($action){
				$UrlAppend = $this->_urlApdWO[$action];
			}			
//			$serverList = $this->_getGlobalData('server/server_timer_'.$gameId);
			$this->_utilHttpMInterface->curlInit();
			$workOrderTotal = 0;
			$MoreInformation = '';
			foreach($workOrderResult as $serverId =>$value){
				$serverUrl = $this->_serverList[$serverId]['server_url'];				
				if(is_array($value)){
					$post['received'] = implode(',',$value);
				}else{
					$post['received'] = trim($value);					
				}
				$oneServerCount = substr_count($post['received'],',')+1;
				$workOrderTotal+=$oneServerCount;
				$MoreInformation .= $serverId.':'.$oneServerCount.',';
				$post['_verifycode'] = $this->_validateRQ['_verifycode'];
				$post['_sign'] = $this->_validateRQ['_sign'];
				
//				//日志测试		
//				$logs= "received({$serverId}) = {$post['received']}";				
//				$logs .=";".date('Y-m-d H:i:s',CURRENT_TIME)."\n\r-------------------------\n\r";
//				error_log($logs, 3, RUNTIME_DIR.'/Logs/'.$action.'_'.$gameId.'_'.date('Y_m_d_H',time()).".log");
				$this->_utilHttpMInterface->addHttp($serverUrl,$UrlAppend,NULL,$post,$serverId);
			}
			$this->_utilHttpMInterface->send();
			$dataResult = $this->_utilHttpMInterface->getResults();
			//大唐返回的数据
			//"$dataResult" = Array [1]	
			//	956 = (string:22) {"status":1,"info":""}
			$MoreInformation = 'Detail->'.$MoreInformation.' Total->'.$workOrderTotal;
			$this->_success($MoreInformation);
		}else {
			$this->_success(0);
		}
	}
	
	/**
	 * 根据不同的方式，从游戏服务器获取待更新的数据
	 * @param unknown_type $gameId
	 * @param unknown_type $action
	 */
	private function _getData($gameId,$action=NULL){
		if($action){
			$UrlAppend = $this->_urlApdWO[$action];
		}
		
		$post['count'] = 100;
		$post['_verifycode'] = $this->_validateRQ['_verifycode'];
		$post['_sign'] = $this->_validateRQ['_sign'];
		if($this->_serverList){
			$this->_utilHttpMInterface->curlInit();
			$this->_utilHttpMInterface->setTimeOut(20);
			if($_REQUEST['server_ids']){
				$_REQUEST['server_ids'] = explode(',',$_REQUEST['server_ids']);
				foreach($_REQUEST['server_ids'] as $serverId){
					if($this->_serverList[$serverId]){
						$this->_utilHttpMInterface->addHttp($this->_serverList[$serverId]['server_url'],$UrlAppend,NULL,$post,$serverId);
					}
				}
			}else{
				foreach($this->_serverList as $serverId =>$sub){
					$this->_utilHttpMInterface->addHttp($sub['server_url'],$UrlAppend,NULL,$post,$serverId);
				}
			}
		}
		$this->_utilHttpMInterface->send();
		$dataResult = $this->_utilHttpMInterface->getResults();
		return $dataResult;
	}
	
	private function _getNew($gameId){
		$gameId = intval($gameId);
		$dataResult = $this->_getData($gameId,'new');	//从游戏服务器获取待更新的数据
		if(!$dataResult){
			$this->_error('请求发生错误');
		}
		$workOrderResult = array();
		$DialogCount = array();
		foreach($dataResult as $serverId=>$sub){
			$sub = json_decode($sub,true);
//"$sub" = Array [2]	
//	status = (int) 1	
//	data = Array [3]	
//		0 = Array [16]	
//			appraise = (string:0) 	
//			content = (string:4) 图片内容	
//			create_time = (string:10) 1315397218	
//			game_id = (string:1) 5	
//			id = (int) 13	
//			image_path = (string:119) http://183.60.64.177/kefu/image377340100008000004189858.jpg,http://183.60.64.177/kefu/image387886100008000004189858.jpg	
//			isLook = (string:0) 	
//			question_type = (string:2) 32	
//			source = (string:1) 2	
//			status = (string:1) 1	
//			title = (string:11) 提交问题(主要是图片)	
//			type = (string:2) 32	
//			user_id = (string:16) 1000080000041898	
//			user_nickname = (string:6) ooooll	
//			work_id = (string:1) 0	
//			work_order_id = (string:1) 0	
//		1 = Array [16]	
//		2 = Array [16]	
			
//0 = Array [20]	
//	appraise = (string:0) 	
//	content = (string:10) 1521登录问题答案	
//	create_time = (string:10) 1314948307	
//	diamondLevel = (string:1) 0	//砖的等级
//	flatType = (string:1) 0		 //黄砖1或蓝砖10
//	game_id = (string:1) 5	
//	id = (int) 23	
//	image_path = (string:0) 	
//	isLook = (string:0) 	
//	question_type = (string:2) 31	
//	source = (string:1) 2	
//	status = (string:1) 1	
//	title = (string:9) 1521登录问题1	
//	type = (string:2) 31	
//	user_id = (string:5) 10001	
//	user_nickname = (string:5) 天下无敌2	
//	vip = (string:1) 0	//游戏VIP等级
//	work_id = (string:1) 0	
//	work_order_id = (string:1) 0	
//	yearDiamond = (string:1) 0	//是否年费黄钻			
			$DialogCount[$serverId] = 0;
			if($sub['status']!=1){
//				$this->_writeLog($serverId,$sub,__FUNCTION__,$sub['info']);
				continue;
			}
			if($this->_contentSelf){
				$workOrders = array();
				foreach($sub['data'] as $k=>$dialog){
					$tmp = array();
					if(isset($workOrders[$dialog['id']]['contents'])){
						$tmp = $workOrders[$dialog['id']]['contents'];
					}
					$workOrders[$dialog['id']] = $dialog;
					$tmp[]=array(
						'image_path'=>$dialog['image_path'],
						'create_time'=>$dialog['create_time'],
						'content'=>$dialog['content'],
					);
					$workOrders[$dialog['id']]['contents'] = $tmp;
					unset($workOrders[$dialog['id']]['content'],$tmp);
				}
			}else{
				$workOrders = $sub['data'];
			}
			$preventRepeat = array();//检查数组，用于防止游戏中工单重复
			$isLogRepeatError = false;
			$errorLog = false;
			foreach($workOrders as $key => $workOrder){
				if(isset($preventRepeat[$workOrder['id']])){
					$isLogRepeatError = true;
					continue;	//如果发现有重复工单，放弃这条工单
				}
				$preventRepeat[$workOrder['id']] = 1;	//放进防止重复检查数组
				if($workOrder['work_order_id']==0){
					$saveResult = $this->_saveWorkOrder($workOrder,$serverId);
				}else{
					$saveResult = $this->_saveReply($workOrder,$serverId);
				}
				
				if($saveResult['status']==1){
					$workOrderResult[$serverId][]=$workOrder['id'].':'.$saveResult['data']['order_id'];
					$DialogCount[$serverId]=$DialogCount[$serverId]+$saveResult['data']['dialog_count'];
				}
				else{
					$errorLog = true;
				}
			}
			if($isLogRepeatError || $errorLog){	//是否记录错误信息
				$errorInfo = '';
				if($isLogRepeatError){
					$errorInfo .='工单有重复';
				}
				if($errorLog){
					$errorInfo .='、'.$saveResult['info'];
				}
				$this->_writeLog($serverId,$sub,__FUNCTION__,$errorInfo);
			}
		}
		$this->_callback($workOrderResult,$gameId,'newCbk');
	}
	
	private function _reGet(){
		
	}
	
	private function _orderDel($workOrder){
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$workOrderId = array();
		$IdWid = array();
		if ($workOrder && is_array($workOrder)) { //判断单个删除或是多个删除
			foreach ($workOrder as $sub){
				$IdWid[] = $sub['id'].':'.$sub['work_order_id'];
				$workOrderId[]= $sub['work_order_id'];
				$this->_modelOrderLog->addLog(array('Id'=>$sub['work_order_id']),Model_OrderLog::DEL);
			}
			$isOk = $this->_modelWorkOrder->update ( array ('status' => 4 ), "Id in (".implode ( ',', $workOrderId ).")" );
		}else{
			return array ('status' => 1, 'info' => '', 'data' => null );
		}
		if ($isOk) { //是否删除成功 
			$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
			$orderClass=$this->_utilWorkOrder->getOrderManage();
			$orderClass->delOrder($workOrderId);
			return array ('status' => 1, 'info' =>'', 'data' => $IdWid );
		} else {
			return array ('status' => 0, 'info' => 'error id', 'data' => null );
		}
	}
	
	private function _getDel($gameId){
		$gameId = intval($gameId);
		$dataResult = $this->_getData($gameId,'del');	//从游戏服务器获取待更新的数据
		if(!$dataResult){
			$this->_error('请求发生错误');
		}
		$workOrderResult = array();
		foreach($dataResult as $serverId=>$sub){
			$sub = json_decode($sub,true);
			if($sub['status']!=1){
//				$this->_writeLog($serverId,$sub,__FUNCTION__,$sub['info']);
				continue;	
			}
			$saveResult = $this->_orderDel($sub['data']);
			if($saveResult['status']==1){
				if($saveResult['data']){
					$workOrderResult[$serverId]=$saveResult['data'];
				}
			}else{
				$this->_writeLog($serverId,$sub,__FUNCTION__,$saveResult['info']);
			}
		}
		$this->_callback($workOrderResult,$gameId,'delCbk');
	}
	
	/**
	 * 工单评价处理
	 */
	private function _evaluate($workOrder) {
		$this->_modelWorkOrder = $this->_getGlobalData ( 'Model_WorkOrder', 'object' );
		$this->_modelWorkOrderDetail = $this->_getGlobalData ( 'Model_WorkOrderDetail', 'object' );
		$workOrderId = $workOrder ['work_order_id'];
		$ev = intval($workOrder ['ev']);
		if($ev<1 || $ev>3){
			return( array ('status' => 0, 'info' => 'error ev:'.$ev, 'data' => null ) );
		}
		$workOrderCheck = $this->_modelWorkOrder->findById ( $workOrderId );
		if (! $workOrderCheck) { //未找到工单
			return( array ('status' => 0, 'info' => 'work order non-existent', 'data' => null ) );
		}
		if ($workOrderCheck ['evaluation_status'] != '0') { //工单已经评价,也告知游戏
			return( array ('status' => 1, 'info' => 'already evaluation', 'data' => null ) );
		}
		$updateArr=array('evaluation_status'=>$ev);
		if ($ev==3){//如果等于3就表示不满意,将更新字段
			$des=intval($workOrder['des']);
			$updateArr['evaluation_desc']=$des?$des:6;	//默认其他
		}
		$this->_modelWorkOrder->update ( $updateArr, "Id={$workOrderId}" );
		#------追加日志------#
		$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
		$this->_modelOrderLog->addLog(array('Id'=>$workOrderId),Model_OrderLog::EV);
		#------追加日志------#
		return ( array ('status' => 1, 'info' =>'InterfaceWorkOrder_Evaluate', 'data' => null ) );
	}
	
	/**
	 * 获取评价
	 */
	private function _getEv($gameId){
		$gameId = intval($gameId);
		$dataResult = $this->_getData($gameId,'ev');	//从游戏服务器获取待更新的数据
		if(!$dataResult){
			$this->_error('请求发生错误');
		}
		$workOrderResult = array();
		foreach($dataResult as $serverId=>$sub){			
			$sub = json_decode($sub,true);
			if($sub['status']!=1){
//				$this->_writeLog($serverId,$sub,__FUNCTION__,$sub['info']);
				continue;
			}
			$errorLog = false;	
			foreach($sub['data'] as $key => $workOrder){
				$saveResult = $this->_evaluate($workOrder);
				if($saveResult['status']==1){
					$workOrderResult[$serverId][]=$workOrder['id'].':'.$workOrder['work_order_id'];
				}else{
					$errorLog = true;
				}					
			}
			if($errorLog){
				$this->_writeLog($serverId,$sub,__FUNCTION__,$saveResult['info']);
			}
		}
		$this->_callback($workOrderResult,$gameId,'evCbk');
	}
	
	/**
	 * 记录错误日志
	 * @param int $serverId	服务器id
	 * @param array $errorData	错误数据
	 * @param string $functionName	发生错误的函数名
	 * @param string $errorInfo	错误提示信息
	 */
	private function _writeLog($serverId=0,$errorData='',$functionName='',$errorInfo='null'){
		if(empty($functionName)){
			$functionName = __FUNCTION__;
		}
		$_nr_ = "\n\r";
		$serverUrl = $this->_serverList[$serverId]['server_url'];
		$logs  = "GameId:{$this->_gameClass->_gameId}";
		$logs .= "{$_nr_}Url:{$serverUrl}";
		$logs .= "{$_nr_}ErrorData : ".var_export($errorData,true);
		$logs .= "{$_nr_}ErrorInfo : {$errorInfo}";
		$logs .= "{$_nr_}Time : ".date('Y-m-d H:i:s',CURRENT_TIME);
		$logs .= "{$_nr_}###################################################{$_nr_}";
		error_log($logs, 3, RUNTIME_DIR.'/Logs/TimerWorkOrder_'.$functionName.'_'.date('Y-m-d',CURRENT_TIME).".log");
	}
	
}