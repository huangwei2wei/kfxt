<?php
/**
 * 客服系统受理统计
 * @author php-朱磊
 *
 */
class Control_ServiceAccept extends Stats {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;
	
	/**
	 * Model_OrderLog
	 * @var Model_OrderLog
	 */
	private $_modelOrderLog;
	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	private function _createUrl(){
		$this->_view->assign('url',$this->_url);
	}
	
	/**
	 * 系统受理情况统计
	 */
	public function actionOrder(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operatorIds=$this->_getGlobalData('operator_list');
		$operatorIds=Model::getTtwoArrConvertOneArr($operatorIds,'Id','operator_name');
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$roomList=$this->_modelRooms->findAll();
		$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
		//$roomList['']=Tools::getLang('All','Common');
		if ($this->_isPost()){
			$selected=array();
			if (!$_POST['game_type_id'])$this->_utilMsg->showMsg('请至少选择一个游戏类型',-1);
			if (!$_POST['game_type_id'])$this->_utilMsg->showMsg('请至少选择一个运营商',-1);
			if (!$_POST['start_time'] || !$_POST['end_time'])$this->_utilMsg->showMsg('请指定时间范围',-1);
			$selected['roomList']=$_POST['roomList'];
			$selected['game_type_id']=$_POST['game_type_id'];
			$selected['operator_id']=$_POST['operator_id'];
			$selected['start_time']=$_POST['start_time'];
			$selected['end_time']=$_POST['end_time'];
			$this->_view->assign('selected',$selected);
			$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
			$time=array('start_time'=>strtotime($_POST['start_time']),'end_time'=>strtotime($_POST['end_time'])+86399);
			$dataList=$this->_modelWorkOrder->statsAccept($time,$_POST['game_type_id'],$_POST['operator_id'],$_POST['roomList']);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('display',true);
		}
		$this->_view->assign('roomList',$roomList);
		$this->_view->assign('vipIndex',Tools::getLang('VIP_LEVEL','Common'));
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('operatorList',$operatorIds);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	/**
	 * 满意度统计
	 * @author xy
	 */
	public function actionSatisfaction(){
		$ModelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		$gameOperator = $ModelGameOperator->getGameOperator(true);//游戏与运营商对应的数组
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');	//游戏类型
		$vipIndex=Tools::getLang('VIP_LEVEL','Common');
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$roomList=$this->_modelRooms->findAll();
		$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
		//$roomList['']=Tools::getLang('All','Common');
		array_pop($vipIndex);//弹出最后一个		
		if ($this->_isPost()){
// 			print_r($_POST);exit;
			$selected=array();
			if (!$_POST['game_operator_id'] && !$_POST['game_type_id']){
				$this->_utilMsg->showMsg('请至少选择一个运营商或游戏',-1);
			}
			if (!$_POST['start_time'] || !$_POST['end_time'])$this->_utilMsg->showMsg('请指定时间范围',-1);
			$selected['roomList'] = $_POST['roomList'];
			$selected['game_type_id'] = $_POST['game_type_id'];
			$selected['game_operator_id'] =  $_POST['game_operator_id']?json_encode($_POST['game_operator_id']):'""';
			$selected['start_time'] = $_POST['start_time'];
			$selected['end_time'] = $_POST['end_time'];
			$selected['vip_level'] = $_POST['vip_level'];
			$this->_view->assign('selected',$selected);
			$time = array('start_time'=>strtotime($_POST['start_time']),'end_time'=>strtotime($_POST['end_time'])+86399);
			$this->_modelWorkOrder = $this->_getGlobalData('Model_WorkOrder','object');
			$dataList = $this->_modelWorkOrder->statsSatisfaction($time,$_POST['game_type_id'],$_POST['game_operator_id'],$_POST['vip_level'],$_POST['roomList']);
			//返回的数组中，第一个元素是游戏数组，第二个元素是
// 			$gmIdOptId = $ModelGameOperator ->getGmIdOptId($_POST['game_operator_id']);
// 			if(!$_POST['game_operator_id']){
// 				$gmIdOptId['gameIds'] = $_POST['game_type_id'];
// 				$gmIdOptId['opreatorIds'] = array();
// 			}
// 			$dataList=$this->_modelWorkOrder->statsSatisfaction($time,$gmIdOptId['gameIds'],$gmIdOptId['opreatorIds'],$_POST['vip_level'],$_POST['roomList']);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('display',true);
		}
		$this->_view->assign('roomList',$roomList);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('gameOperator',json_encode($gameOperator));		
		$this->_view->assign('vipIndex',$vipIndex);
		$this->_utilMsg->createPackageNavBar();
// 		print_r($gameOperator);exit;
		$this->_view->display();
	}
	
	/**
	 * 工单处理时长统计
	 */
	public function actionTime(){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$operatorIds=$this->_getGlobalData('operator_list');
		$operatorIds=Model::getTtwoArrConvertOneArr($operatorIds,'Id','operator_name');
		$vipIndex=Tools::getLang('VIP_LEVEL','Common');
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$roomList=$this->_modelRooms->findAll();
		$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
		//$roomList['']=Tools::getLang('All','Common');
		array_pop($vipIndex);//弹出最后一个
		if ($this->_isPost()){
			$selected=array();
			if (!$_POST['game_type_id'])$this->_utilMsg->showMsg('请至少选择一个游戏类型',-1);
			if (!$_POST['operator_id'])$this->_utilMsg->showMsg('请至少选择一个运营商',-1);
			if (!$_POST['start_time'] || !$_POST['end_time'])$this->_utilMsg->showMsg('请指定时间范围',-1);
			$selected['roomList']=$_POST['roomList'];
			$selected['game_type_id']=$_POST['game_type_id'];
			$selected['operator_id']=$_POST['operator_id'];
			$selected['start_time']=$_POST['start_time'];
			$selected['end_time']=$_POST['end_time'];
			$selected['vip_level']=$_POST['vip_level'];
			$this->_view->assign('selected',$selected);
			$time=array('start_time'=>strtotime($_POST['start_time']),'end_time'=>strtotime($_POST['end_time']));
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			$dataList=$this->_modelOrderLog->statsTime($time,$_POST['game_type_id'],$_POST['operator_id'],$_POST['vip_level'],$_POST['roomList']);
			$this->_view->assign('dataList',$dataList);
			$this->_view->assign('display',true);
		}
		$this->_view->assign('roomList',$roomList);
		$this->_view->assign('vipIndex',$vipIndex);
		$this->_view->assign('gameTypes',$gameTypes);
		$this->_view->assign('operatorList',$operatorIds);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	

}