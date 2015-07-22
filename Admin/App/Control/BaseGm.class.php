<?php
class BaseGm extends Control {
	
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
	
	/**
	 * 时间差（秒），在$this->_createServerList()中计算
	 * @var $_timeDifference integer
	 */
	protected $_timeDifference = 0;
	/**
	 * 游戏id
	 * @var int
	 */
	protected $game_id=0;
	/**
	 * 模块包
	 * @var string
	 */
	protected $package = '';
	
	const FRG_SPACTIVITY_TYPE = 'FRG_SpActivityType';
	
	/**
	 * 检查是否有权限
	 * @param unknown_type $c_a
	 */
	protected function checkAct($c_a=''){
		if(!$c_a){
			return false;
		}
		return $this->_utilRbac->checkAct($c_a)===1?true:false;
	}
		
	/**
	 * 单服务器检测权限
	 * @param boolean $type 如果为真就表示检测运营商 $_REQUEST['operator_id'],否则就检测 $_REQUEST['server_id']
	 */
	protected function _checkOperatorAct($type=NULL){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList($this->game_id));
		$id=($type===null)?$_REQUEST['server_id']:$_REQUEST['operator_id'];
		$type=($type===null)?Util_Rbac::CHECK_SERVER:Util_Rbac::CHECK_OPERATOR;
		if(!$this->_utilRbac->checkOperatorAct($id,$type))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
	}
	
	
	/**
	 * 多服务器检测权限
	 */
	protected function _checkOperatorsAct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList($this->game_id));
		if (count($_REQUEST['server_ids'])){
			foreach ($_REQUEST['server_ids'] as $value){
				if(!$this->_utilRbac->checkOperatorAct($value))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
			}
		}
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
	/**
	 * 多运营商选择器
	 */
	protected function _multiOperatorSelect($optGroupFunName=''){
		$serverList = $this->_getGlobalData('server/server_list_'.$this->game_id);
		if($optGroupFunName && is_callable(array(&$this,$optGroupFunName))){
			$operatorGroup = call_user_func(array(&$this,$optGroupFunName));
		}
		if(!$operatorGroup){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->game_id);
			$operatorList = Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$operatorGroup = array(0=>$operatorList);
		}
		$optSer = array();
		foreach($serverList as $serverId => $server){
			$optSer[$server['operator_id']][$serverId] = $server['server_name'];
		}
		$this->_view->assign('optSer',json_encode($optSer));
		$this->_view->assign('operatorGroup',$operatorGroup);
		$this->_view->assign('tplServerSelect','OperationFRG/MultiServerSelect.html');
	}
	
	/**
	 * 运营商分组缓存
	 * @param $updateDate
	 */
	protected function _getOperatorGroup($updateDate=false){
		$cacheName = $this->game_id.'_BaseGm_getOperatorGroup';
		if($updateDate!==false){
			return $this->_f($cacheName,$updateDate);
		}
		$operatorGroup = $this->_f($cacheName);
		$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->game_id);
		if($operatorGroup){
			foreach($operatorGroup as $group){
				foreach($group as $operatorId => $sub){
					unset($operatorList[$operatorId]);
				}
			}
		}
		$operatorList = Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$operatorGroup['no_group'] = $operatorList;
		return $operatorGroup;
	}
}