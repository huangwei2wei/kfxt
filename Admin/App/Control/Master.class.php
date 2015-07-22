<?php
/**
 * 管理员功能模块
 * @author php-朱磊
 */
class Control_Master extends Control {
	
	/**
	 * Util_WorkOrder
	 * @var Util_WorkOrder
	 */
	private $_utilWorkOrder;
	
	/**
	 * 工单队列表
	 * @var Model_AutoOrderQueue
	 */
	private $_modelAutoOrderQueue;
	
	/**
	 * Util_Rooms
	 * @var Util_Rooms
	 */
	private $_utilRooms;
	
	/**
	 * 房间表
	 * @var Model_Rooms
	 */
	private $_modelRooms;
	
	/**
	 * Util_Online
	 * @var Util_Online
	 */
	private $_utilOnline;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	
	public function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();	
	}
	
	private function _createUrl(){
		$this->_url['Master_OrderQueue_del']=Tools::url(CONTROL,'OrderQueue',array('doaction'=>'clear'));
		$this->_url['Master_OrderQueue_assign']=Tools::url(CONTROL,'OrderQueue',array('doaction'=>'assign'));
		$this->_url['Master_OrderQueue_setnum']=Tools::url(CONTROL,'OrderQueue',array('doaction'=>'setnum'));
		$this->_url['Master_OrderQueue_clear']=Tools::url(CONTROL,'OrderQueue',array('doaction'=>'clear'));
		$this->_view->assign('url',$this->_url);
	}
	
	
	/**
	 * 工单队列管理
	 */
	public function actionOrderQueue(){
		switch ($_REQUEST['doaction']){
			case 'clear' :{
				$this->_orderClear();
				break;
			}
			case 'assign' :{	//分配工单
				$this->_orderAssign();
				break;
			}
			case 'setnum' :{	//设置工单数字 
				$this->_orderSetNum();
				break;
			}
			default:{
				$this->_orderIndex();
				break;
			}
		}
	}
	
	/**
	 * 对象管理页面
	 */
	private function _orderIndex(){
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelRooms=$this->_getGlobalData('Model_Rooms','object');
		$this->_utilOnline=$this->_getGlobalData('Util_Online','object');
		
		$onlineUsers=$this->_utilOnline->getOnlineUser();
		$users=$this->_getGlobalData('user_index');
		foreach ($onlineUsers as &$user){
			$user=$users[$user];
		}
		
		$objectOrderManage=$this->_utilWorkOrder->getOrderManage();
		
		$dataList=$this->_modelAutoOrderQueue->findAll();
		if ($dataList){
			$this->_utilRooms=$this->_getGlobalData('Util_Rooms','object');
			$roomList=$this->_modelRooms->findAll();
			$roomList=Model::getTtwoArrConvertOneArr($roomList,'Id','name');
			$gameTypes=$this->_getGlobalData('game_type');
			$operators=$this->_getGlobalData('operator_list');
			foreach ($dataList as &$list){
				$roomClass=$this->_utilRooms->getRoom($list['room_id']);
				$roomUsers=$roomClass->findAllUser();
				if (count($roomUsers))$list['add_users']=$roomUsers;
				$list['game_type_id']=$gameTypes[$list['game_type_id']]['name'];
				$list['operator_id']=$operators[$list['operator_id']]['operator_name'];
				$list['room_id']=$roomList[$list['room_id']];
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['order_detail']=Tools::url('WorkOrder','Detail',array('Id'=>$list['work_order_id']));
			}
			$this->_view->assign('dataList',$dataList);
		}
		$this->_view->assign('onlineUsers',$onlineUsers);
		$this->_view->assign('orderNum',$objectOrderManage['_orderNum']);
		$this->_view->assign('dataList',$dataList);
		$this->_view->set_tpl(array('body'=>'Master/OrderIndex.html'));
		$this->_view->display();
	}
	
	/**
	 * 工单设置计数器
	 */
	private function _orderSetNum(){
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$objectOrderManage=$this->_utilWorkOrder->getOrderManage();
		$orderNum=array();
		$orderNum[0]=$_POST['vip_0'];
		$orderNum[1]=$_POST['vip_1'];
		$orderNum[2]=$_POST['vip_2'];
		$orderNum[3]=$_POST['vip_3'];
		$orderNum[4]=$_POST['vip_4'];
		$orderNum[5]=$_POST['vip_5'];
		$orderNum[6]=$_POST['vip_6'];
		$objectOrderManage['_orderNum']=$orderNum;
		$objectOrderManage->setUpdateInfo(1);
		$this->_utilMsg->showMsg(false);
	}
	
	/**
	 * 工单队列清理动作
	 */
	private function _orderClear(){
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->execute("TRUNCATE TABLE {$this->_modelAutoOrderQueue->tName()}");
		$this->_utilMsg->showMsg(false);
	}
	
	/**
	 * 工单分配
	 */
	private function _orderAssign(){
		$this->_modelAutoOrderQueue=$this->_getGlobalData('Model_AutoOrderQueue','object');
		$this->_modelAutoOrderQueue->delById($_GET['Id']);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_utilWorkOrder=$this->_getGlobalData('Util_WorkOrder','object');
		$objectOrderManage=$this->_utilWorkOrder->getOrderManage();
		$userClass=$this->_utilRbac->getUserClassById($_GET['userId']);
		$objectOrderManage->updateWorkOrderOwner($userClass,array('work_order_id'=>$_GET['workOrderId']));
		$this->_utilMsg->showMsg(false);
	}
	
	
	/**
	 * 用户class文件管理
	 */
	public function actionUserClass(){
		
	}
}