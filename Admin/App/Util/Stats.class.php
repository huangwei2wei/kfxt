<?php
/**
 * 客服系统统计功能
 * @author PHP-朱磊
 *
 */
class Util_Stats extends Base {

	const STATS_OPERATOR='operator';	//跟据运营商判断
	const STATS_USER='user';	//跟据用户判断
	
	private $_statsType='';	
	
	private $_operator=null;
	
	private $_users=null;
	
	private $_statsTime=null;
	
	private $_hour=null;
	
	private $_gameType=NULL;
	
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
	
	public function __construct($type){
		$this->_statsType=$type;
	}
	
	public function setOperator($operator){
		$this->_operator=$operator;
	}
	
	public function setStatsTime($time){
		$this->_statsTime=$time;
	}
	
	public function setHour($hour){
		$this->_hour=$hour;
	}
	
	public function setUser($users){
		$this->_users=$users;
	}
	
	public function setGameType($gameType){
		$this->_gameType = $gameType;
	}
	
	
	public function stats(){
		switch ($this->_statsType){
			case self::STATS_OPERATOR :{
				return $this->_getStatsOperator();
				break;
			}
			case self::STATS_USER :{
				return $this->_getStatusUser();
				break;
			}
			default:{
				break;
			}
		}
	}
	
	/**
	 * 获取统计表格标题 
	 */
	public function getTableTitle($gameTypeId=null){
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');	//游戏类型
		$questionTypes=$this->_getGlobalData('question_types');
		if ($gameTypeId===null){
			foreach ($questionTypes as $key=>&$question){
					$question=" [<b>{$gameTypes[$question['game_type_id']]}</b>] ".$question['title'];
			}
		}else {
			foreach ($questionTypes as $key=>&$question){
				if ($question['game_type_id']==$gameTypeId){
					$question=$question['title'];
				}else {
					unset($questionTypes[$key]);
				}
			}
		}
		

		$operators=$this->_getGlobalData('operator_list');
		$operators=Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');	//运营商
		$workOrderSource=$this->_getGlobalData('workorder_source');	//来源
		$vipLevel=array(0,1,2,3,4,5,6);	//vip等级
		$evaluation=$this->_getGlobalData('player_evaluation');
		$evaluationList=array();
		foreach ($evaluation as $key=>&$ev){
			$evaluationList[$key]=$ev['title'];
		}
		//扩展出服务不满  和 游戏不满度
		$evaluationList[3]='对服务不满意';
		$evaluationList[32]='对游戏不满意';
		$evaluationList[0]='未评价';
		
		$evaluationList['satisfaction']='服务满意度';
		$evaluationList['ev_percentage']='评价率';
		$replyQuality=array('0'=>'未质检','1'=>'已质检');
		$replyTimeout=array('0'=>'未超时','1'=>'已超时');
		$qualityOption=$this->_getGlobalData('quality_options');
		$qualityStatus=$this->_getGlobalData('quality_status');
		$qualityScores=array('bonus'=>'加分','deeduction'=>'减分','bonus_num'=>'加分次数','deeduction_num'=>'减分次数');
		$badEv=$this->_getGlobalData('player_evaluation');
		$badEv=$badEv[3]['Description'];
		$tableTitle=array(
			'question_type'=>$questionTypes,
			'game_type'=>$gameTypes,
			'operator'=>$operators,
			'work_order_source'=>$workOrderSource,
			'vip_level'=>$vipLevel,
			'evaluation'=>$evaluationList,
			'reply_quality'=>$replyQuality,
			'reply_timeout'=>$replyTimeout,
			'quality_options'=>$qualityOption,
			'quality_status'=>$qualityStatus,
			'quality_scores'=>$qualityScores,
			'bad_ev'=>$badEv,
		);
		return $tableTitle;
	}
	
	private function _getStatsOperator(){
		$addArrs=array();
		$gameTypeId=$this->_operator['game_type_id'];
		$operator=$this->_operator['operator'];
		$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
		$workOrderList=$this->_modelWorkOrder->select("select Id,game_type,question_type,source,operator_id,vip_level,evaluation_status,evaluation_desc,create_time from {$this->_modelWorkOrder->tName()} where create_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and game_type={$gameTypeId} and operator_id in (".implode(',',$operator).")");
		if ($workOrderList){
			//1游戏BUG
			//2游戏设置不满意
			//3回复不清晰
			//4回复错误
			//5客服服务态度恶劣
			//6其他	
			$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
			foreach ($workOrderList as $list){
				if (is_numeric($this->_hour)){
					if (date('H',$list['create_time'])!=$this->_hour)continue;
				}
				$addArrs['game_type_num'][$list['game_type']]++;
				$addArrs['question_type_num'][$list['question_type']]++;
				$addArrs['source_num'][$list['source']]++;
				$addArrs['operator_num'][$list['operator_id']]++;
				$addArrs['vip_level_num'][$list['vip_level']]++;
				if($list['evaluation_status'] == 3){
					$addArrs['evaluation_num'][ $evaluationDesc[$list['evaluation_desc']] ]++;
					$addArrs['evaluation_desc_num'][$list['evaluation_desc']]++;
				}else{
					$addArrs['evaluation_num'][$list['evaluation_status']]++;
				}
//				$addArrs['evaluation_num'][$list['evaluation_status']]++;
//				if ($list['evaluation_desc'])$addArrs['evaluation_desc_num'][$list['evaluation_desc']]++;
			}
			$isEvNum=$addArrs['evaluation_num'][1]+$addArrs['evaluation_num'][2]+$addArrs['evaluation_num'][3]+$addArrs['evaluation_num'][32];
			$addArrs['evaluation_num']['satisfaction']=$addArrs['evaluation_num'][1]/($isEvNum-$addArrs['evaluation_num'][32])*100 ;	//服务满意度
			$totalEvNum=$addArrs['evaluation_num'][0]+$isEvNum;
			$addArrs['evaluation_num']['ev_percentage']=$isEvNum/$totalEvNum*100 ;//评价率
			
		}
		
		$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
		$workOrderQaList=$this->_modelWorkOrderQa->select("select Id,is_quality,is_timeout,create_time from {$this->_modelWorkOrderQa->tName()} where create_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and qa=1 and game_type_id={$gameTypeId} and operator_id in (".implode(',',$operator).")");
		if ($workOrderQaList){
			foreach ($workOrderQaList as $list){
				if (is_numeric($this->_hour)){
					if (date('H',$list['create_time'])!=$this->_hour)continue;
				}
				$list['is_quality']=$list['is_quality']?'1':'0';
				$addArrs['reply_quality_num'][$list['is_quality']]++;	//总数,被质检增加
				if ($list['is_timeout']!=null){
					$addArrs['reply_timeout_num'][$list['is_timeout']]++; //总数超时,增加
				}
			}
		}
		
		$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
		$qualityList=$this->_modelQuality->select("select option_id,status,scores from {$this->_modelQuality->tName()} where quality_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and game_type_id={$gameTypeId} and operator_id in (".implode(',',$operator).")");
		if ($qualityList){
			foreach ($qualityList as $list){
				if (is_numeric($this->_hour)){
					if (date('H',$list['quality_time'])!=$this->_hour)continue;
				}
				$addArrs['quality_option_num'][$list['option_id']]++;
				$addArrs['quality_status_num'][$list['status']]++;
				if ($list['scores']>0){
					$addArrs['quality_scores']['bonus']+=$list['scores'];
					$addArrs['quality_scores']['bonus_num']++;
				}elseif ($list['scores']<0) {
					$addArrs['quality_scores']['deeduction']+=$list['scores'];
					$addArrs['quality_scores']['deeduction_num']++;
				}
			}
			
		}
		foreach ($addArrs as $key=>$list){
			$list['game_type_num']=(array)$list['game_type_num'];
			$list['question_type_num']=(array)$list['question_type_num'];
			$list['source_num']=(array)$list['source_num'];
			$list['operator_num']=(array)$list['operator_num'];
			$list['vip_level_num']=(array)$list['vip_level_num'];
			$list['evaluation_num']=(array)$list['evaluation_num'];
			$list['reply_quality_num']=(array)$list['reply_quality_num'];
			$list['reply_timeout_num']=(array)$list['reply_timeout_num'];
			$list['quality_option_num']=(array)$list['quality_option_num'];
			$list['quality_status_num']=(array)$list['quality_status_num'];
			$list['quality_scores']=(array)$list['quality_scores'];
		}
		return $addArrs;
	}
	
	/**
	 * 根据用户统计 
	 */
	private function _getStatusUser(){
		if (is_array($this->_users)){
			$users=implode(',',$this->_users);
		}else {
			$users=$this->_users;
		}
		
		$addArrs=array();
		$this->_modelWorkOrder=$this->_getGlobalData('Model_WorkOrder','object');
		$sql = "select Id,game_type,question_type,source,operator_id,vip_level,evaluation_status,evaluation_desc,create_time from {$this->_modelWorkOrder->tName()} where create_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and owner_user_id in (".$users.")";
		$gameType = NULL;
		if($this->_gameType){
			if(is_array($this->_gameType)){
				$gameType = implode(',',$this->_gameType);
			}
			$sql .= " and game_type in ({$gameType})";
		}
		$workOrderList=$this->_modelWorkOrder->select($sql);
		if ($workOrderList){
			//1游戏BUG
			//2游戏设置不满意
			//3回复不清晰
			//4回复错误
			//5客服服务态度恶劣
			//6其他	
			$evaluationDesc = array(1=>32,2=>32,3=>3,4=>3,5=>3,6=>32,''=>3);//32扩展为游戏差评、3保留为服务差评
			foreach ($workOrderList as $list){
				$addArrs['game_type_num'][$list['game_type']]++;
				$addArrs['question_type_num'][$list['question_type']]++;
				$addArrs['source_num'][$list['source']]++;
				$addArrs['operator_num'][$list['operator_id']]++;
				$addArrs['vip_level_num'][$list['vip_level']]++;
				//对差评进行扩展
				if($list['evaluation_status'] == 3){
					$addArrs['evaluation_num'][ $evaluationDesc[$list['evaluation_desc']] ]++;
					$addArrs['evaluation_desc_num'][$list['evaluation_desc']]++;
				}else{
					$addArrs['evaluation_num'][$list['evaluation_status']]++;
				}				
//				$addArrs['evaluation_num'][$list['evaluation_status']]++;
//				if ($list['evaluation_desc'])$addArrs['evaluation_desc_num'][$list['evaluation_desc']]++;
			}
			$isEvNum=$addArrs['evaluation_num'][1]+$addArrs['evaluation_num'][2]+$addArrs['evaluation_num'][3]+$addArrs['evaluation_num'][32];
			$addArrs['evaluation_num']['satisfaction']=$addArrs['evaluation_num'][1]/($isEvNum-$addArrs['evaluation_num'][32])*100 ;	//服务满意度
			$totalEvNum=$addArrs['evaluation_num'][0]+$isEvNum;
			$addArrs['evaluation_num']['ev_percentage']=$isEvNum/$totalEvNum*100 ;//评价率
		}
		$this->_modelWorkOrderQa=$this->_getGlobalData('Model_WorkOrderQa','object');
		$sql = "select Id,is_quality,is_timeout,create_time from {$this->_modelWorkOrderQa->tName()} where create_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and qa=1 and user_id in (".$users.")";
		if($gameType){
			$sql .= " and game_type_id in ({$gameType})";
		}
		$workOrderQaList=$this->_modelWorkOrderQa->select($sql);
		if ($workOrderQaList){
			foreach ($workOrderQaList as $list){
				$list['is_quality']=$list['is_quality']?'1':'0';
				$addArrs['reply_quality_num'][$list['is_quality']]++;	//总数,被质检增加
				if ($list['is_timeout']!=null){
					$addArrs['reply_timeout_num'][$list['is_timeout']]++; //总数超时,增加
				}
			}
		}
		
		$this->_modelQuality=$this->_getGlobalData('Model_Quality','object');
		$sql = "select option_id,status,scores from {$this->_modelQuality->tName()} where quality_time between {$this->_statsTime['start']} and {$this->_statsTime['end']} and  (quality_user_id in ({$users}) or reply_user_id in ({$users})) ";
		if($gameType){
			$sql .= " and game_type_id in ({$gameType})";
		}
		$qualityList=$this->_modelQuality->select($sql);
		if ($qualityList){
			foreach ($qualityList as $list){
				$addArrs['quality_option_num'][$list['option_id']]++;
				$addArrs['quality_status_num'][$list['status']]++;
				if ($list['scores']>0){
					$addArrs['quality_scores']['bonus']+=$list['scores'];
					$addArrs['quality_scores']['bonus_num']++;
				}elseif ($list['scores']<0) {
					$addArrs['quality_scores']['deeduction']+=$list['scores'];
					$addArrs['quality_scores']['deeduction_num']++;
				}
			}
			
		}
		foreach ($addArrs as $key=>&$list){
			$list['game_type_num']=(array)$list['game_type_num'];
			$list['question_type_num']=(array)$list['question_type_num'];
			$list['source_num']=(array)$list['source_num'];
			$list['operator_num']=(array)$list['operator_num'];
			$list['vip_level_num']=(array)$list['vip_level_num'];
			$list['evaluation_num']=(array)$list['evaluation_num'];
			$list['reply_quality_num']=(array)$list['reply_quality_num'];
			$list['reply_timeout_num']=(array)$list['reply_timeout_num'];
			$list['quality_option_num']=(array)$list['quality_option_num'];
			$list['quality_status_num']=(array)$list['quality_status_num'];
			$list['quality_scores']=(array)$list['quality_scores'];
		}
		return $addArrs;
	}
	
	
}