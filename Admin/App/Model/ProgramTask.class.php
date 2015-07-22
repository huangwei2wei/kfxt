<?php
class Model_ProgramTask extends Model {
	protected $_tableName='program_task';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * 新建任务
	 */
	public function newTask($postArr){
		$difficulty=Tools::coerceInt($postArr['difficulty']);
		$precastHour=Tools::coerceInt($postArr['precast_hour']);
		if (empty($postArr['task_content']))return array('status'=>-1,'msg'=>'任务描述不能为空','href'=>2);
		if (!$precastHour)return array('status'=>-1,'msg'=>'预计完成时间不能为0','href'=>2);
		if (!$postArr['accept_user_id'])return array('status'=>-1,'msg'=>'请选择任务负责人','href'=>2);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array();
		$addArr['Id']=date('Ymd',CURRENT_TIME).Tools::getRandCode(5,1);
		$addArr['assign_user_id']=$userClass['_id'];
		$addArr['accept_user_id']=$postArr['accept_user_id'];
		$addArr['difficulty']=$difficulty;
		$addArr['precast_hour']=$precastHour;
		$addArr['assign_time']=CURRENT_TIME;
		$addArr['task_content']=$postArr['task_content'];
		if (parent::add($addArr)){
			return array('status'=>1,'msg'=>'增加任务成功','href'=>Tools::url('ProgramTask','Index',array('zp'=>'Program')));
		}else{
			return array('status'=>-2,'msg'=>'增加任务失败','href'=>2);
		}
	}
	
	/**
	 * 接收任务
	 */
	public function acceptTask($id){
		if (!$id)return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$task=$this->findById($id);
		if (!$task)return array('status'=>-1,'msg'=>'任务不存在','href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		if ($task['start_time']!=null)return array('status'=>-1,'msg'=>'此任务已经接受过','href'=>1);
		if ($task['accept_user_id']!=$userClass['_id'])return array('status'=>-1,'msg'=>'您不能接收此任务','href'=>1);
		$this->update(array('start_time'=>CURRENT_TIME),"Id='{$id}'");
		return array('status'=>1,'msg'=>'已接收此任务','href'=>1);
	}
	
	/**
	 * 完成任务
	 */
	public function finishTask($postArr){
		$id=$postArr['Id'];
		if (!$id)return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$task=$this->findById($id);
		if (!$task)return array('status'=>-1,'msg'=>'任务不存在','href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		if ($task['start_time']==null)return array('status'=>-1,'msg'=>'您还没有接收此任务','href'=>1);
		if ($task['accept_user_id']!=$userClass['_id'])return array('status'=>-1,'msg'=>'您不能完成此任务','href'=>1);
		$updateArr=array();
		
		$updateArr['actual_hour']=$this->getWorkHour($task['start_time'],CURRENT_TIME);
		if ($updateArr['actual_hour']>$task['precast_hour'] && empty($postArr['timeout_cause'])){
			return array('status'=>-1,'msg'=>null,'href'=>Tools::url('ProgramTask','TimeoutCause',array('Id'=>$task['Id'])));//如果超时时间范围就跳到写原因的画面
		}else {
			$updateArr['timeout_cause']=$postArr['timeout_cause'];
		}
		$updateArr['end_time']=CURRENT_TIME;
		$updateArr['quality_scorce']=$this->_getQualityScorce($task['precast_hour'],$updateArr['actual_hour']);
		$updateArr['finish_speed']=$this->_getFinishSpeed($task['precast_hour'],$updateArr['actual_hour']);
		$this->update($updateArr,"Id='{$id}'");
		return array('status'=>1,'msg'=>'已完成任务,完成任务时间：'.date('Y-m-d H:i:s',CURRENT_TIME),'href'=>Tools::url('ProgramTask','Index',array('zp'=>'Program')));
	}
	
	/**
	 * 修改任务
	 */
	public function editTask($postArr){
		if (empty($postArr['Id']))return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$updateArr=array();
		if ($postArr['accept_user_id']!='')$updateArr['accept_user_id']=$postArr['accept_user_id'];
		if ($postArr['difficulty']!='')$updateArr['difficulty']=$postArr['difficulty'];
		if ($postArr['precast_hour']!='')$updateArr['precast_hour']=$postArr['precast_hour'];
		if ($postArr['actual_hour']!='')$updateArr['actual_hour']=$postArr['actual_hour'];
		if ($postArr['start_time']!='')$updateArr['start_time']=strtotime($postArr['start_time']);
		if ($postArr['end_time']!='')$updateArr['end_time']=strtotime($postArr['end_time']);
		if ($postArr['quality_scorce']!='')$updateArr['quality_scorce']=$postArr['quality_scorce'];
		if ($postArr['efficiency_scorce']!='')$updateArr['efficiency_scorce']=$postArr['efficiency_scorce'];
		if ($postArr['efficiency_level']!='')$updateArr['efficiency_level']=$postArr['efficiency_level'];
		if ($postArr['quality_scorce']!='')$updateArr['quality_scorce']=$postArr['quality_scorce'];
		if ($postArr['timeout_cause']!='')$updateArr['timeout_cause']=$postArr['timeout_cause'];
		if ($postArr['task_content']!='')$updateArr['task_content']=$postArr['task_content'];
		if (count($updateArr)){
			$this->update($updateArr,"Id='{$postArr['Id']}'");
			return array('status'=>1,'msg'=>'编辑成功','href'=>Tools::url('ProgramTask','Index',array('zp'=>'Program')));
		}else{
			return array('status'=>-2,'msg'=>'编辑错误','href'=>1);
		}
	}
	
	/**
	 * 获取工作时间
	 * @param $startTime
	 * @param $endTime
	 */
	public function getWorkHour($startTime,$endTime){
		$day=Tools::getdateArr(intval($startTime),intval($endTime),'Y-m-d H:i:s');
		$days=count($day);
		if ($days==1){
			$useHour=$endTime-$startTime;
			$useHour=round($useHour/3600);//四舍五入取小时
		}else {
			foreach ($day as $key=>$curDay){	//如果是星期六或是星期天将不计算时间
				if (in_array(date('w',strtotime($curDay)),array(5,6)))unset($day[$key]);
			}
			$days=count($day);
			$useHour=$days*8;//总时间,每天8小时计算
			$startWorkTime=getdate($startTime);
			$startOffDutyTime=strtotime("{$startWorkTime['year']}-{$startWorkTime['mon']}-{$startWorkTime['mday']} 18:00:00");
			$startHour=$startOffDutyTime-$startTime;
			$startHour=round($startHour/3600);
			
			
			$endWorkTime=getdate($endTime);
			$endOffDutyTime=strtotime("{$endWorkTime['year']}-{$endWorkTime['mon']}-{$endWorkTime['mday']} 18:00:00");
			$endHour=$endOffDutyTime-$endTime;
			$endHour=round($endHour/3600);
			$useHour=$useHour-16+$startHour+$endHour;
		}
		if ($useHour<1)$useHour=1;
		return $useHour;
	}
	
	/**
	 * 审核任务
	 */
	public function auditTask($postArr){
		if (empty($postArr['Id']))return array('status'=>-1,'msg'=>'参数错误','href'=>1);
		$bugScorce=Tools::coerceInt($postArr['bug_scorce']);
		$task=$this->findById($postArr['Id']);
		$updateArr=array();
		$updateArr['bug_scorce']=$bugScorce;
		if ($task['actual_hour']!=''){
			$updateArr['efficiency_scorce']=$this->_getEfficiency($task['precast_hour'],$task['actual_hour'],$bugScorce,$task['difficulty']);
			$updateArr['efficiency_level']=$this->_getEfficiencyLevel($updateArr['efficiency_scorce']);
		}
		$this->update($updateArr,"Id='{$postArr['Id']}'");
		return array('status'=>1,'msg'=>'任务已审核','href'=>Tools::url('ProgramTask','Index',array('zp'=>'Program')));
	}
	
	/**
	 * 完成速度
	 * @param int $precastHour
	 * @param int $actualHour
	 */
	private function _getFinishSpeed($precastHour,$actualHour){
		if ($precastHour==$actualHour)return 2;
		return ($precastHour>$actualHour)?1:3;
	}
	
	/**
	 * 获取效率得分
	 * @param int $precastHour
	 * @param int $actualHour
	 */
	private function _getQualityScorce($precastHour,$actualHour){
		$source=round(($precastHour/$actualHour)*100);
//		if ($source>100)$source=100;	//限制最大100分
		return $source;
	}
	
	/**
	 * 获取质量得分
	 * @param int $precastHour	预计完成时间
	 * @param int $actualHour	实际完成时间
	 * @param int $bugScorce	BUG评分
	 * @param int $difficulty	难度
	 */
	private function _getEfficiency($precastHour,$actualHour,$bugScorce,$difficulty){
		$source = round(1-$bugScorce/($actualHour*$difficulty))*100;
//		if ($source>100)$source=100;	//限制最大100分
		return $source;
	}
	
	/**
	 * 获取质量等级
	 * @param int $efficiencyScorce
	 */
	private function _getEfficiencyLevel($efficiencyScorce){
		if ($efficiencyScorce>85){
			return '1';
		}elseif ($efficiencyScorce>75){
			return '2';
		}elseif ($efficiencyScorce>60){
			return '3';
		}else {
			return '4';
		}
	}
	
	/**
	 * 获取质量等级数组
	 */
	public function getEfficiencyLevel(){
		return array('1'=>'优秀','2'=>'良好','3'=>'及格','4'=>'差');
	}
	
	public function getFinishSpeed(){
		return array('1'=>'提前完成 ','2'=>'按时完成','3'=>'延时完成');
	}
	
	/**
	 * 统计
	 * @param int $time
	 * @param int $userId
	 * @return array
	 */
	public function statsMonth($time,$userId){
		$endTime=strtotime('+1 month',$time);
		$dataList=$this->select("select * from {$this->tName()} where efficiency_scorce is not null and assign_time between {$time} and {$endTime} and accept_user_id={$userId}");
		if (!count($dataList))return array();
		$taskNum=0;//任务数
		$efficiency=array();//质量
		$taskFinish=array();//完成效率
		$precastHour=0;//预计完成时间
		$actualHour=0;//实际完成时间
		$efficiencyScorce=0;	//质量得分
		$efficiencyList=$this->getEfficiencyLevel();
		$finishSpeed=$this->getFinishSpeed();
		foreach ($dataList as &$list){
			$taskNum++;
			$efficiency[$list['efficiency_level']]++;
			$taskFinish[$list['finish_speed']]++;
			$precastHour+=$list['precast_hour'];
			$actualHour+=$list['actual_hour'];
			$efficiencyScorce+=$list['efficiency_scorce'];
			
			$list['assign_time']=date('Y-m-d H:i:s',$list['assign_time']);
			$list['start_time']=$list['start_time']?date('Y-m-d H:i:s',$list['start_time']):'';
			$list['end_time']=$list['end_time']?date('Y-m-d H:i:s',$list['end_time']):'';
			$list['efficiency_level']=$efficiencyList[$list['efficiency_level']];
			$list['finish_speed']=$finishSpeed[$list['finish_speed']];
		}
		$qualityScorce=round($precastHour/$actualHour*100);	//效率月统计得分
		$efficiencyScorce=round($efficiencyScorce/$taskNum);	//质量月统计得分
		return array('task_num'=>$taskNum,'task_finish'=>$taskFinish,'efficiency'=>$efficiency,'quality_scorce'=>$qualityScorce,'efficiency_scorce'=>$efficiencyScorce,'task_list'=>$dataList);
	}
	
}




















