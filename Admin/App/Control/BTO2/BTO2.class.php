<?php
class BTO2 extends Control {
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	protected $_utilRbac;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;
	
	/**
	 * operator_id
	 * @var int
	 */
	protected $_operatorId;
	
	/**
	 * 时间差（秒），在$this->_createServerList()中计算
	 * @var $_timeDifference integer
	 */
	protected $_timeDifference = 0;
	
	const GAME_ID=9;	
	const PACKAGE='BTO2';	
	const APPLY_TPYE_GOLD = 5;	
	const MASTER = 'BTO2Master';	
	const OPT = 'BTO2Operation';	
	const GOLD = 'BTO2Gold';
		
	/**
	 * 单服务器检测权限
	 * @param boolean $type 如果为真就表示检测运营商 $_REQUEST['operator_id'],否则就检测 $_REQUEST['server_id']
	 */
	protected function _checkOperatorAct($type=NULL){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::GAME_ID));
		$id=($type===null)?$_REQUEST['server_id']:$_REQUEST['operator_id'];
		$type=($type===null)?Util_Rbac::CHECK_SERVER:Util_Rbac::CHECK_OPERATOR;
		if(!$this->_utilRbac->checkOperatorAct($id,$type))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
	}

	/**
	 * 单服务器选择列表
	 */
	protected function _createServerList(){
//		$operatorList=$this->_getGlobalData('operator_list');
//		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect',self::PACKAGE.'/ServerSelect/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}
	}
	
	/**
	 * 多服务器检测权限
	 */
	protected function _checkOperatorsAct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::GAME_ID));
		if (count($_REQUEST['server_ids'])){
			foreach ($_REQUEST['server_ids'] as $value){
				if(!$this->_utilRbac->checkOperatorAct($value))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
			}
		}
	}
	


	/**
	 * 建立多服务器选择列表
	 */
	protected function _createMultiServerList(){
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect',self::PACKAGE.'/ServerSelect/MultiServerSelect.html');
	}
	
	protected function timeZone($gameId=NULL){
		if($gameId){
			$gameServerList=$this->_getGlobalData('server/server_list_'.$gameId);
		}else{
			$gameServerList=$this->_getGlobalData('gameser_list');
		}		
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
		$this->_view->assign('current_time_zone',date_default_timezone_get());
	}
	
	protected function getItemsFromOneServer($time = 86400){
		$fileName = self::GAME_ID.'_Items';
		//过时刷新
		if($_REQUEST['timeout']){
			$time = -1;
		}
		$data = $this->_f($fileName,'',CACHE_DIR,$time);
		if(false === $data){			
			$gameser_list = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
			$count = count($gameser_list);
			if($count && is_array($gameser_list)){
				$utilFRGInterface=$this->_getGlobalData('Util_FRGInterface','object');
				if($_REQUEST['server_id']){
					$_msg = $gameser_list[$_REQUEST['server_id']];
					$utilFRGInterface->setServerUrl($_msg["Id"]);	//初始化连接url地址
					$utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add'));
					$data=$utilFRGInterface->callInterface();
				}else{
					for($i=0;$i<$count;$i++){
						$_msg = array_pop($gameser_list);
						$utilFRGInterface->setServerUrl($_msg["Id"]);	//初始化连接url地址
						$utilFRGInterface->setGet(array('c'=>'Reward','a'=>'Add'));
						$data=$utilFRGInterface->callInterface();
						if($data){
							break;
						}
					}
				}
			}
			if($data){
				$this->_f($fileName,$data);
			}
		}
		if($_REQUEST['timeout'] && $this->_isAjax()){
			$this->_returnAjaxJson(array('status'=>1,'info'=>NULL,'data'=>NULL));
		}
		return $data;
	}
	
}