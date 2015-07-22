<?php
/**
 * 寻侠GM后台
 * @author PHP-朱磊
 */
abstract class XunXia extends Control {
	
	const XUN_XIA_ID=5;	//寻侠游戏ID
	const PACKAGE='XunXia';
	protected $_centerServerIds = array(959,900,912,918,1846,1094,1118);

	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	protected $_utilRbac;
	
	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc=null;
	
	/**
	 * @return Util_Rpc
	 */
	protected function getApi(){
		if (is_null($this->_utilRpc)){
			$this->_utilRpc=$this->_getGlobalData('Util_Rpc','object');
		}
		return $this->_utilRpc;
	}

	
	/**
	 * 单服务器检测权限
	 * @param boolean $type 如果为真就表示检测运营商 $_REQUEST['operator_id'],否则就检测 $_REQUEST['server_id']
	 */
	protected function _checkOperatorAct($type=NULL){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::XUN_XIA_ID));
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
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::XUN_XIA_ID));
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
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
		
		//去除中心服
		if($this->_centerServerIds){
			foreach($this->_centerServerIds as $serverId){
				if(isset($gameServerList[$serverId])){
					unset($gameServerList[$serverId]);
				}
			}
		}
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','XunXia/XunXiaServerSelect/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}
	}
	
	/**
	 * 中心服列表
	 */
	protected function _createCenterServer(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
//		foreach($gameServerList as $key =>$server){
//			if(!in_array($key,$this->_centerServerIds)){
//				unset($gameServerList[$key]);
//			}
//		}
		$tmp = array();
		if($this->_centerServerIds){
			foreach($this->_centerServerIds as $serverId){
				if(isset($gameServerList[$serverId])){
					$tmp[$serverId] = $gameServerList[$serverId];
				}
			}
		}
		$gameServerList = $tmp;
		unset($tmp);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','XunXia/XunXiaServerSelect/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
		}
	}
	
	/**
	 * 建立多服务器选择列表
	 */
	protected function _createMultiServerList(){
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::XUN_XIA_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','XunXia/XunXiaServerSelect/MultiServerSelect.html');
	}

	protected function d2s($digital){
		if(is_double($digital)){
			$digital = rtrim(ltrim(serialize($digital),'d:'),';');
		}else{
			$digital = strval($digital);
		}
		return $digital;
	}
	
}