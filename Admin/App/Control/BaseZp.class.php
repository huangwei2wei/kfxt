<?php
/**
 * GM功能_控制器抽象基类
 * @author php-兴源
 * 2011-09-29
 */
abstract class BaseZp extends Control {
	
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
	 * 时间差（秒），在$this->_createServerList()中计算
	 * @var $_timeDifference integer
	 */
	protected $_timeDifference = 0;
	/**
	 * 模块包
	 * @var string
	 */
	protected $package = '';
	/**
	 * 游戏id
	 * @var int
	 */
	protected $game_id=0;
	/**
	 * 运营商id
	 * @var int
	 */
	protected $_operatorId;
	
	protected $_gameIfConf;
	
	function __construct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_createView();
		$this->_createUrl();
	}
	
	/**
	 * 被子类覆盖
	 */
	protected function _createUrl(){}


	
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
		
	/**
	 * 单服务器选择列表
	 */
	protected function _createServerList(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('server/server_list_'.$this->game_id);		
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','BaseZp/ServerSelect.html');
		$serverId = false;
		if (isset($_REQUEST['server_id'])){
			if($_REQUEST['server_id']){
				setcookie($this->game_id.'_serverId',$_REQUEST['server_id'],CURRENT_TIME+21600);
			}else{
				setcookie($this->game_id.'_serverId',NULL,CURRENT_TIME-3600);
			}			
		}elseif(isset($_COOKIE[$this->game_id.'_serverId'])){
			$_REQUEST['server_id'] = $_COOKIE[$this->game_id.'_serverId'];
		}
		if($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
			$this->_createServerListAfter($gameServerList[$_REQUEST['server_id']]);
		}
		$this->timeZone($this->game_id);
	}
	/**
	 * 让子类覆盖回调
	 */
	protected function _createServerListAfter(){}
	
	/**
	 * 建立多服务器选择列表
	 */
	protected function _createMultiServerList(){
		$gameServerList=$this->_getGlobalData('server/server_list_'.$this->game_id);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','BaseZp/MultiServerSelect.html');
	}
	
	protected function _createOperatorList(){
		$this->_view->assign('tplServerSelect','BaseZp/OperatorSelect.html');
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
				$this->_timeDifference = intval($TimeZone)*180;
			}else{
				date_default_timezone_set($TimeZone);
				if('PRC' == date_default_timezone_get()){
					$this->_view->assign('BeiJing_time',true);
				}
			}
		}
		elseif(isset($gameServerList[$_REQUEST['server_id']]['time_zone'])){
			$TimeDifference = $gameServerList[$_REQUEST['server_id']]['time_zone'];
			$this->_timeDifference = intval($TimeDifference)*180;
			if($this->_timeDifference == 0){
				$this->_view->assign('BeiJing_time',true);
			}
		}
		//时差》
		$this->_view->assign('current_time_zone',date_default_timezone_get());
	}
	
}