<?php
class Model_ProgramStatsQuarter extends Model {
	protected $_tableName='program_stats_quarter';
	
	public function getQuarter(){
		return array('1'=>'第一季度','2'=>'第二季度','3'=>'第三季度','4'=>'第四季度');
	}
	
	public function add($postArr){
		if (empty($postArr['user_id']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		if (empty($postArr['year']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		if (empty($postArr['quarter']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$monthScorce=Tools::coerceInt($postArr['month_scorce']);
		$codeScorce=Tools::coerceInt($postArr['code_scorce']);
		$addArr=array(
			'month_scorce'=>$monthScorce,
			'code_scorce'=>$codeScorce,
			'total_scorce'=>round($monthScorce*0.8+$codeScorce*0.2),
			'user_id'=>$postArr['user_id'],
			'year'=>$postArr['year'],
			'quarter'=>$postArr['quarter'],
		);
		if (parent::add($addArr)){
			return array('msg'=>'评分成功','status'=>1,'href'=>2);
		}else {
			return array('msg'=>'评分失败','status'=>-2,'href'=>1);
		}
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$monthScorce=Tools::coerceInt($postArr['month_scorce']);
		$codeScorce=Tools::coerceInt($postArr['code_scorce']);
		$totalScorce=round($monthScorce*0.8+$codeScorce*0.2);
		$updateArr=array(
			'month_scorce'=>$monthScorce,
			'code_scorce'=>$codeScorce,
			'total_scorce'=>$totalScorce,
		);
		$this->update($updateArr,"Id={$postArr['Id']}");
		return array('msg'=>'修改评分成功','status'=>1,'href'=>2);
	}
	
	public function findByUserId($userId){
		$dataList=$this->select("select * from {$this->tName()} where user_id={$userId}");
		if (!count($dataList))return array();
		foreach ($dataList as &$list){
			$list['time']=$list['year']." - 第{$list['quarter']}季度";
		}
		return $dataList;
	}
	
	/**
	 * 统计季度
	 * @param int $userId	用户ID
	 * @param int $year		年份
	 * @param int $quarter	季度
	 */
	public function stats($userId,$year,$quarter){
		$times=$this->_getMonth($year,$quarter);
		$dataList=$this->select("select * from {$this->tName('program_stats_month')} where user_id={$userId}");
		if (!count($dataList))return array();
		$curStats=array();	//当前统计分数
		foreach ($dataList as &$list){
			if (in_array($list['time'],$times)){
				array_push($curStats,$list['total_scorce']);
			}
			$list['time']=date('Y-m',$list['time']);
		}
		$returnArr=array('list'=>$dataList);
		#------计算平均数------#
		$count=count($curStats);
		if ($count){
			$sum=array_sum($curStats);
			$curStats=round($sum/$count);
			$returnArr['cur_stats']=$curStats;
		}
		#------计算平均数------#
		return $returnArr;
	}
	
	/**
	 * 获取月份
	 * @param int $year
	 * @param int $quarter
	 */
	private function _getMonth($year,$quarter){
		switch ($quarter){
			case 1 :
				$months=array(1,2,3);
				break;
			case 2 :
				$months=array(4,5,6);
				break;
			case 3 :
				$months=array(7,8,9);
				break;
			case 4 :
				$months=array(10,11,12);
				break;
			default:
				return false;
		}
		$statsMonths=array();
		foreach ($months as $month){
			$statsMonths[]=strtotime("{$year}-{$month}");
		}
		return $statsMonths;
	}
}