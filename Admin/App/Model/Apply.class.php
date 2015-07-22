<?php
/**
 * 申请模型
 * @author PHP-兴源
 */
class Model_Apply extends Model {
	
	const APPLY_TYPE = 'apply_type';	//申请类型
	
	protected $_tableName = 'apply';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;

	private $_sendType = array(1,2,3);	//	1/2/3	本地接口/http/phprpc
	
	private $_playerType = array(-1,0,1,2,3);	//-1/0/1/2/3 混搭/无玩家/UserId/UserName/NickName

	/**
	 * 添加数据时必须的字段
	 * @var $_addFields unknown_type
	 */
	private $_addFields = array(
		'type',
		'server_id',
		'operator_id',
		'game_type',
		'list_type',
		'apply_info',
		'send_type',
		'send_data',
		'receiver_object',
		'player_type',
		'player_info',	
//		'apply_user_id',	//no
//		'apply_ip',	//no
//		'create_time',	//no
//		'is_send',	//no
	);
	
	/**
	 * 初始化
	 */
	public function _initialize(){}
	
	public function __construct(){
		$this->_utilRbac = $this->_getGlobalData('Util_Rbac','object');
		$this->_initialize();
	}
	
	public function AuditUpdata($data){
		if($data && is_array($data)){
			foreach($data as $key=>$val){
				if($val){
					$Id = $val['Id'];
					unset($val['Id']);
					$this->update($val,"Id={$Id}");
				}
			}
		}
	}
	
	private function _getData($data,$fields){
		$tmp = array();
		if($fields && is_array($fields)){
			foreach($fields as $field){
				$tmp[$field] = $data[$field];
			}	
		}		
		return $tmp;
	}
	
	public function AddApply($data){
		//检查申请原因
		if(!trim($data['apply_info'])){
			return array('status'=>0,'info'=>'申请原因、数据为空','data'=>NULL);
		}
		
		//检查发送类型
		if(!in_array($data['send_type'],$this->_sendType)){
			return array('status'=>0,'info'=>'发送类型ID错误','data'=>NULL);
		}
		
		//检查玩家类型
		if(!in_array($data['player_type'],$this->_playerType)){
			return array('status'=>0,'info'=>'玩家类型错误','data'=>NULL);
		}
		
		
		//检查申请类型
		
		$type = $data['type'];
		$game_type = $data['game_type'];
		$list_type = $data['list_type'];
		$ApplyType = $this->_getGlobalData(self::APPLY_TYPE);
		
		
		if(!isset($ApplyType[$list_type][$game_type][$type])){

			return array('status'=>0,'info'=>'申请类型不存在','data'=>NULL);
		}
		//检查服务器、运营商、游戏
		if($data['server_id'] >0 ){
			$gameser_list = $this->_getGlobalData('gameser_list');
			$info = false;
			if(!isset($gameser_list[$data['server_id']])){
				$info = '服务器ID不存在';
			}elseif($data['operator_id']!=-1 && $gameser_list[$data['server_id']]['operator_id'] != $data['operator_id']){
				$info = '运营商ID错误';
			}elseif($gameser_list[$data['server_id']]['game_type_id'] != $data['game_type']){
				$info = '游戏ID错误';
			}
			if($info){
				return array('status'=>0,'info'=>$info,'data'=>NULL);
			}
		}
		
		//检查发送数据
		if($data['send_data']){
			if(!is_string($data['send_data'])){
				$data['send_data'] = serialize($data['send_data']);
			}
		}else{
			return array('status'=>0,'info'=>'发送数据为空','data'=>NULL);
		}
		
		if(!is_string($data['receiver_object'])){
			$data['receiver_object'] = serialize($data['receiver_object']);
		}
		
		$data = $this->_getData($data,$this->_addFields);
		
		$userClass=$this->_utilRbac->getUserClass();
		$data['apply_user_id'] = $userClass['_id'];
		$data['apply_ip'] = Tools::getClientIP();
		$data['create_time'] = CURRENT_TIME;
		$data['is_send'] = 0;
		return $this->add($data);
	}
	
	/**
	 * 从一些游戏操作（例如发礼包卡）中获取审核的信息
	 * @param unknown_type $resultMark
	 * @param unknown_type $game_type
	 * @param unknown_type $server_id
	 * @param unknown_type $number
	 */
	public function getByMark($resultMark=NULL,$game_type=NULL,$server_id=NULL,$number=NULL){
		if(is_null($resultMark) || is_null($game_type) || is_null($server_id)){
			return false;
		}	
		$sql = 'select Id,apply_user_id,audit_user_id,create_time,send_time from '.$this->tName()." where game_type = {$game_type} and server_id = {$server_id} ";
		if(strpos($resultMark,',',1)){
			$resultMark = str_ireplace("'",'',$resultMark);
			$resultMark = str_ireplace(',',"','",$resultMark);
			$sql .= " and result_mark in ('{$resultMark}')";
		}else{
			$sql .= " and result_mark = '{$resultMark}'";
		}		
		return $this->select($sql,$number);
	}
	
	
	
	
}