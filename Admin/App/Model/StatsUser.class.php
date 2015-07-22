<?php
/**
 * 统计个人详细表
 * @author php-朱磊
 *
 */
class Model_StatsUser extends Model {
	protected $_tableName='stats_user';
	
	/**
	 * Model_WorkOrder
	 * @var Model_WorkOrder
	 */
	private $_modelWorkOrder;
	
	/**
	 * Model_WorkOrderQa
	 * @var Model_WorkOrderQa
	 */
	private $_modelWorkOrderQa;
	
	/**
	 * Model_Quality
	 * @var Model_Quality
	 */
	private $_modelQuality;
	
	/**
	 * 统计并且存入表.
	 * key=0的为总数,其它的都会user_id,统计完成后会自动入库.
	 * @param int $startTime
	 * @param int $endTime
	 */
	public function user($startTime,$endTime){
		$addArrs=array();
		$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
		$workOrderList=$this->_modelWorkOrder->select("select * from {$this->_modelWorkOrder->tName()} where create_time between {$startTime} and {$endTime}");
		if ($workOrderList){
			foreach ($workOrderList as $list){
				$addArrs[0]['game_type_num'][$list['game_type']]++;
				$addArrs[0]['question_type_num'][$list['question_type']]++;
				$addArrs[0]['source_num'][$list['source']]++;
				$addArrs[0]['operator_num'][$list['operator_id']]++;
				$addArrs[0]['vip_level_num'][$list['vip_level']]++;
				$addArrs[0]['evaluation_num'][$list['evaluation_status']]++;
				
				$curUserId=$list['owner_user_id'];
				$addArrs[$curUserId]['game_type_num'][$list['game_type']]++;//游戏类型计数
				$addArrs[$curUserId]['question_type_num'][$list['question_type']]++;//问题类型计数
				$addArrs[$curUserId]['source_num'][$list['source']]++;//来源计数
				$addArrs[$curUserId]['operator_num'][$list['operator_id']]++;//运营商计数
				$addArrs[$curUserId]['vip_level_num'][$list['vip_level']]++;//VIP等级计数
				$addArrs[$curUserId]['evaluation_num'][$list['evaluation_status']]++;//评价计数
			}
		}
		
		$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
		$workOrderQaList=$this->_modelWorkOrderQa->select("select * from {$this->_modelWorkOrderQa->tName()} where create_time between {$startTime} and {$endTime} and qa=1");
		if ($workOrderQaList){
			foreach ($workOrderQaList as $list){
				$curUserId=$list['user_id'];
				$list['is_quality']=$list['is_quality']?'1':'0';
				$addArrs[0]['reply_quality_num'][$list['is_quality']]++;	//总数,被质检增加
				$addArrs[$curUserId]['reply_quality_num'][$list['is_quality']]++;	//用户被质检数增加
				if ($list['is_timeout']!=null){
					$addArrs[0]['reply_timeout_num'][$list['is_timeout']]++; //总数超时,增加
					$addArrs[$curUserId]['reply_timeout_num'][$list['is_timeout']]++; //用户超时,增加
				}
			}
		}
		
		$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
		$qualityList=$this->_modelQuality->select("select * from {$this->_modelQuality->tName()} where quality_time between {$startTime} and {$endTime}");
		if ($qualityList){
			foreach ($qualityList as $list){
				$curUserId=$list['quality_user_id'];
				$addArrs[0]['quality_option_num'][$list['option_id']]++;
				$addArrs[0]['quality_status_num'][$list['status']]++;
				$addArrs[$curUserId]['quality_option_num'][$list['option_id']]++;
				$addArrs[$curUserId]['quality_status_num'][$list['status']]++;
				if ($list['scores']>0){
					$addArrs[0]['quality_scores']['bonus']+=$list['scores'];
					$addArrs[0]['quality_scores']['bonus_num']++;
					$addArrs[$curUserId]['quality_scores']['bonus']+=$list['scores'];
					$addArrs[$curUserId]['quality_scores']['bonus_num']++;
				}else {
					$addArrs[0]['quality_scores']['deeduction']+=$list['scores'];
					$addArrs[0]['quality_scores']['deeduction_num']++;
					$addArrs[$curUserId]['quality_scores']['deeduction']+=$list['scores'];
					$addArrs[$curUserId]['quality_scores']['deeduction_num']++;
				}
				
			}
		}
		
		$insertArr=array();
		foreach ($addArrs as $key=>$list){
			if ($key==='' || $key===null)continue;
			$list['user_id']=$key;
			$list['create_time']=$startTime;
			$list['game_type_num']=serialize((array)$list['game_type_num']);
			$list['question_type_num']=serialize((array)$list['question_type_num']);
			$list['source_num']=serialize((array)$list['source_num']);
			$list['operator_num']=serialize((array)$list['operator_num']);
			$list['vip_level_num']=serialize((array)$list['vip_level_num']);
			$list['evaluation_num']=serialize((array)$list['evaluation_num']);
			$list['reply_quality_num']=serialize((array)$list['reply_quality_num']);
			$list['reply_timeout_num']=serialize((array)$list['reply_timeout_num']);
			$list['quality_option_num']=serialize((array)$list['quality_option_num']);
			$list['quality_status_num']=serialize((array)$list['quality_status_num']);
			$list['quality_scores']=serialize((array)$list['quality_scores']);
			$this->add($list);
			array_push($insertArr,$list);
		}
	}

	/**
	 * 统计用户
	 * @param int $startTime 开始时间
	 * @param int $endTime 结束时间
	 * @param int $userIds	用户ID
	 */
	public function statsUser($startTime,$endTime,$userIds){
		array_push($userIds,0);
		$dataList=$this->select("select * from {$this->tName()} where create_time between {$startTime} and {$endTime} and user_id in (".implode(',',$userIds).")");
		if ($dataList){
			$users=$this->_getGlobalData('user');
			$userDatas=array();
			foreach ($dataList as $list){
				$list['game_type_num']=unserialize($list['game_type_num']);
				$list['question_type_num']=unserialize($list['question_type_num']);
				$list['source_num']=unserialize($list['source_num']);
				$list['operator_num']=unserialize($list['operator_num']);
				$list['vip_level_num']=unserialize($list['vip_level_num']);
				$list['evaluation_num']=unserialize($list['evaluation_num']);
				$list['reply_quality_num']=unserialize($list['reply_quality_num']);
				$list['reply_timeout_num']=unserialize($list['reply_timeout_num']);
				$list['quality_option_num']=unserialize($list['quality_option_num']);
				$list['quality_status_num']=unserialize($list['quality_status_num']);
				$list['quality_scores']=unserialize($list['quality_scores']);
				$list['user_fullname']=$list['user_id']?$users[$list['user_id']]['full_name']:'total';
				$list['create_time']=date('Y-m-d',$list['create_time']);
				$userDatas[$list['user_id']][$list['create_time']]=$list;
			}
			
			$keyArr=array(
				'game_type_num',
				'question_type_num',
				'source_num',
				'operator_num',
				'vip_level_num',
				'evaluation_num',
				'reply_quality_num',
				'reply_timeout_num',
				'quality_option_num',
				'quality_status_num',
				'quality_scores',
			);
			
			foreach ($userDatas as &$user){
				$curTotal=array();
				foreach ($user as $dateNum){
					foreach ($keyArr as $filedKey){
						if (!is_array($curTotal[$filedKey]))$curTotal[$filedKey]=array();
						if (count($dateNum[$filedKey])){
							foreach ($dateNum[$filedKey] as $key=>$childList){
								$curTotal[$filedKey][$key]+=$childList;
							}
						}
					}
				}
				$curTotal['create_time']='total';
				$curTotal['user_fullname']='total'?'total':$dateNum['user_fullname'];
				$user['total']=$curTotal;
			}
			return $userDatas;
		}
	}
}
