<?php
class Model_ProgramStatsMonth extends Model {
	protected $_tableName='program_stats_month';
	
	public function add($postArr){
		if (empty($postArr['user_id']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		if (empty($postArr['time']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$efficiencyScorce=Tools::coerceInt($postArr['quality_scorce']);
		$qualityScorce=Tools::coerceInt($postArr['efficiency_scorce']);
		$activeScorce=Tools::coerceInt($postArr['active_scorce']);
		$innovationScorce=Tools::coerceInt($postArr['innovation_scorce']);
		$addArr=array(
			'qulaity_scorce'=>$qualityScorce,
			'efficiency_scorce'=>$efficiencyScorce,
			'active_scorce'=>$activeScorce,
			'innovation_scorce'=>$innovationScorce,
			'total_scorce'=>round($qualityScorce*0.4+$efficiencyScorce*0.4+$activeScorce*0.1+$innovationScorce*0.1),
			'user_id'=>$postArr['user_id'],
			'time'=>strtotime($postArr['time']),
		);
		if (parent::add($addArr)){
			return array('msg'=>'评分成功','status'=>1,'href'=>2);
		}else {
			return array('msg'=>'评分失败','status'=>-2,'href'=>1);
		}
	}
	
	/**
	 * 获取用户历史评分记录
	 * @param int $userId
	 */
	public function getUserHistory($userId){
		$dataList=$this->select("select * from {$this->tName()} where user_id={$userId}");
		return $dataList;
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$dataList=$this->findById($postArr['Id']);
		$qualityScorce=Tools::coerceInt($postArr['qulaity_scorce']);
		$efficiencyScorce=Tools::coerceInt($postArr['efficiency_scorce']);
		$activeScorce=Tools::coerceInt($postArr['active_scorce']);
		$innovationScorce=Tools::coerceInt($postArr['innovation_scorce']);
		$totalScorce=round($qualityScorce*0.4+$efficiencyScorce*0.4+$activeScorce*0.1+$innovationScorce*0.1);
		$updateArr=array(
			'qulaity_scorce'=>$qualityScorce,
			'efficiency_scorce'=>$efficiencyScorce,
			'active_scorce'=>$activeScorce,
			'innovation_scorce'=>$innovationScorce,
			'total_scorce'=>$totalScorce,
		);
		$this->update($updateArr,"Id={$postArr['Id']}");
		return array('msg'=>'修改成功','status'=>1,'href'=>2);
	}
}