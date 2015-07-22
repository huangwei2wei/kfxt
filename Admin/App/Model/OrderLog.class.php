<?php
/**
 * 工单日志表
 * @author php-朱磊
 *
 */
class Model_OrderLog extends Model {
	protected $_tableName='order_log';
	
	const ASK=1;	//提问
	const REPLY_2=2;	//处理中
	const REPLY_3=3;	//已解决 
	const CHANGE_STATUS=4;	//更改状态
	const BACK_ASK=5;	//追问
	
	const QUALITY=10;	//质检
	const COMPLAIN=11;	//申请
	const AGAIN=12;		//复检
	const ADD_DOC=13;	//加入归档
	const EV=14;	//评价
	const DEL=15;	//删除
	
	private $_params;
	
	private $_curAction;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function __construct(){
	}
	
	public function getStatus(){
		return array(
			self::ASK=>'提问',
			self::REPLY_2=>'处理中',
			self::REPLY_3=>'已处理',
			self::CHANGE_STATUS=>'更改状态',
			self::BACK_ASK=>'追问',
			self::QUALITY=>'质检',
			self::AGAIN=>'复检',
			self::ADD_DOC=>'加入归档',
			self::EV=>'评价工单',
			self::DEL=>'删除工单',
		);
	}
	
	/**
	 * 填充日志
	 */
	public function addLog($params,$action){
		$this->_params=$params;
		$this->_curAction=$action;
		if ($action==self::ASK){
			$this->_createLog();
		}else {
			$this->_updateLog();
		}
	}
	
	/**
	 * 新加新日志
	 */
	private function _createLog(){
		$addArr=$this->_params;
		$addArr['runtime']=0;
		$addArr['last_run_time']=CURRENT_TIME;
		$log=array();
		$log[]=array('user_id'=>0,'time'=>CURRENT_TIME,'action'=>self::ASK);
		$addArr['log']=serialize($log);
		$this->add($addArr);
	}
	
	/**
	 * 更新日志
	 */
	private function _updateLog(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$userId=$userClass?$userClass['_id']:0;
		$log=$this->findById($this->_params['Id']);
		if (!$log)return ;
		$log['log']=unserialize($log['log']);
		$log['log'][]=array('user_id'=>$userId,'time'=>CURRENT_TIME,'action'=>$this->_curAction);
		$log['log']=serialize($log['log']);
		if (in_array($this->_curAction,array(self::REPLY_2,self::REPLY_3,self::CHANGE_STATUS))){
			$log['runtime']+=CURRENT_TIME-$log['last_run_time'];
		}
		$log['last_run_time']=CURRENT_TIME;
		$this->update($log,"Id={$log['Id']}");
	}
	
	/**
	 * 获取日志接口
	 * @param int $id
	 */
	public function getLog($id){
		$users=$this->_getGlobalData('user_index_id');
		$data=$this->findById($id);
		if (!$data)return false;
		$data['log']=unserialize($data['log']);
		$status=$this->getStatus();
		foreach ($data['log'] as $key=>&$log){
			if ($key==0){
				$lastLog=array();
				$log['word_use_time']='0分钟';
			}else {
				$lastLog=$data['log'][$key-1];
				$log['use_time']=$log['time']-$lastLog['time'];
				$log['word_use_time']=floor($log['use_time']/60).'分钟';
			}
			
			if (intval($log['user_id'])){
				$log['word_user_id']=$users[$log['user_id']];
			}else {
				$log['word_user_id']='玩家';
			}
			$log['word_time']=date('Y-m-d H:i:s',$log['time']);
			$log['word_action']=$status[$log['action']];
			
		}
		$data['word_runtime']=floor($data['runtime']/60).'分钟';
		return $data;
	}
	
	/**
	 * 处理时长统计
	 * @param $times
	 * @param $gameTypeId
	 * @param $operatorId
	 * @param $vipLevel
	 */
	public function  statsTime($times,$gameTypeId=NULL,$operatorId=NULL,$vipLevel=NULL,$roomList=NULL){
		$logTableName=$this->tName();
		$orderTableName=$this->tName('work_order');
		$sql="select {$logTableName}.Id,{$logTableName}.game_type_id,{$logTableName}.operator_id,{$logTableName}.runtime,{$orderTableName}.vip_level,{$orderTableName}.create_time from {$logTableName},{$orderTableName} ".
			"where {$logTableName}.Id={$orderTableName}.Id ";
		if (count($gameTypeId))$sql.=" and {$logTableName}.game_type_id in (".implode(',',$gameTypeId).")";
		if (count($operatorId))$sql.=" and {$logTableName}.operator_id in (".implode(',',$operatorId).")";
		if (count($times))$sql.=" and {$orderTableName}.create_time between {$times['start_time']} and {$times['end_time']}";
		if (count($vipLevel))$sql.=" and {$orderTableName}.vip_level in (".implode(',',$vipLevel).") ";
//		$roomList = intval($roomList);
//		if($roomList>0)$sql.=" and {$orderTableName}.room_id = {$roomList}";
		if (count($roomList))$sql.=" and {$orderTableName}.room_id in (".implode(',',$roomList).") ";
		$dataList=$this->select($sql);
		if (!$dataList)return array();
		$orderNum=array();
		$useUpTime=array();
		$avgUseTime=array();
		foreach ($dataList as $list){
			$orderNum['total']++;
			$orderNum[$list['game_type_id']]++;
			$useUpTime['total']+=$list['runtime'];
			$useUpTime[$list['game_type_id']]+=$list['runtime'];
		}
		if ($useUpTime['total']>1 || $orderNum['total']>1){
			$useUpTime['total']/=60;
			$useUpTime['total']=sprintf('%.2f',$useUpTime['total']);
			$avgUseTime['total']=sprintf('%.2f',$useUpTime['total']/$orderNum['total']);	//平均处理时长
		}else {
			$avgUseTime['total']='∞';
			$useUpTime['total']=0;
			$orderNum['total']=0;
		}
		foreach ($gameTypeId as $id){
			if ($useUpTime[$id]>0 || $orderNum[$id]>0){
				$useUpTime[$id]/=60;
				$useUpTime[$id]=sprintf('%.2f',$useUpTime[$id]);
				$avgUseTime[$id]=sprintf('%.2f',$useUpTime[$id]/$orderNum[$id]);	//平均处理时长
			}else {
				$avgUseTime[$id]='∞';
				$useUpTime[$id]=0;
				$orderNum[$id]=0;
			}
		}
		ksort($orderNum);
		ksort($useUpTime);
		ksort($avgUseTime);
		return array('order_num'=>$orderNum,'use_up_time'=>$useUpTime,'avg_up_time'=>$avgUseTime);
		
	}
	
	
	
	
	
}