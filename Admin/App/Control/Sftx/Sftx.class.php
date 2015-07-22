<?php
/**
 * 三分天下GM后台
 * @author PHP-朱磊
 */
abstract class Sftx extends Control {

	const SFTX_ID=3;	//三分天下游戏ID

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
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::SFTX_ID));
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
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::SFTX_ID));
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
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::SFTX_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','Sftx/ServerSelect/ServerSelect.html');
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
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::SFTX_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','Sftx/ServerSelect/MultiServerSelect.html');
	}



	public static function getSubType(){
		return array (
		1001 => '建造队列CD',
		1002 => '征收队列CD',
		1003 => '科技升级队列CD',
		1004 => '军令队列CD',
		1005 => '世界农场CD',
		1006 => '突飞猛进队列CD',
		1007 => '强化装备队列CD',
		1008 => '委派队列CD',
		1009 => '鼓舞队列CD',
		1010 => '迁移地区CD',
		1011 => '纺织CD',
		1012 => '收获队列CD',
		1013 => '征仪兵队列CD',
		1014 => '捐军功队列CD',
		1015 => '武将训练队列CD',
		2001 => '军令',
		2002 => '修改军团名',
		2003 => '训练位',
		2004 => '建筑位',
		2005 => '军徽',
		2006 => '仓库格子',
		2007 => '商城兵符',
		2008 => '8小时免战',
		2009 => '全服发言',
		2010 => '装备购买',
		2010074 => '碧玉群狼兵符',
		2010075 => '碧玉猛虎兵符',
		2010076 => '碧玉鳳翔兵符',
		2010077 => '碧玉龍飛兵符',
		2010082 => '墨玉鳳翔兵符',
		2010078 => '紅玉猛虎兵符',
		2010083 => '墨玉龍飛兵符',
		2010086 => '黃玉龍飛兵符',
		2010090 => '白玉麒麟兵符',
		2010079 => '紅玉鳳翔兵符',
		2010084 => '墨玉麒麟兵符',
		2010087 => '黃玉麒麟兵符',
		2010091 => '白玉君王兵符',
		2010080 => '紅玉龍飛兵符',
		2010085 => '墨玉君王兵符',
		2010088 => '黃玉君王兵符',
		2010092 => '白玉霸王兵符',
		2010081 => '紅玉麒麟兵符',
		2010089 => '黃玉霸王兵符',
		2010093 => '白玉軍神兵符',
		20111 => '购买铁箱子',
		20112 => '购买铜箱子',
		20113 => '购买银箱子',
		20114 => '购买金箱子',
		3001 => '商队强制通商',
		3002 => '刷新每日任务',
		3003 => '立即完成每日任务',
		3004 => '强征',
		3005 => '购买纺织次数',
		3006 => '增加纺织经验',
		4001 => '军团战金币鼓舞',
		4002 => '地区战金币鼓舞',
		4003 => '银矿战金币鼓舞',
		4004 => '银矿战侦查',
		4005 => '银矿幻影部队',
		4006 => '强攻精英部队',
		5001 => '训练时间',
		5002 => '训练模式',
		5003 => '改变模式',
		5004 => '结束训练',
		5005 => '金币突飞',
		5006 => '金币洗属性',
		5007 => '金币魔力值100',
		1 => '加速消费',
		2 => '购买功能',
		3 => '内政',
		4 => '军事',
		5 => '武将',
		);
	}

}