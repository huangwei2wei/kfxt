<?php
/**
 * faq统计表
 * @author php-朱磊
 *
 */
class Model_StatsFaq extends Model {
	protected $_tableName='stats_faq';
	
	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;
	
	public function add($inserArr){
		parent::add(array('game_type_id'=>$inserArr['game_type_id'],'kind_id'=>$inserArr['kind_id'],'time'=>CURRENT_TIME,'source'=>$inserArr['source'],'lang_id'=>$inserArr['lang_id']));
	}
	
	/**
	 * 统计(按天)
	 * @param array $date
	 * @param int $gameTypeId
	 * @param int $source
	 * @param int $lang
	 */
	public function statsDay($date,$gameTypeId=null,$source=null,$lang=NULL){
		$sql="select kind_id,time from {$this->tName()} where 1";
		$startTime=strtotime($date['start_time']);
		$endTime=strtotime($date['end_time']);
		$sql.=" and time between {$startTime} and {$endTime}";
		if ($gameTypeId)$sql.=" and game_type_id={$gameTypeId}";
		if ($source)$sql.=" and source={$source}";
		if ($lang)$sql.=" and lang_id={$lang}";
		$dataList=$this->select($sql);
		if (!$dataList)return false;
		$stats=array();
		foreach ($dataList as $list){
			$stats['total']['total']++;
			$stats['total'][$list['kind_id']]++;
			$stats[date('Y-m-d',$list['time'])]['total']++;
			$stats[date('Y-m-d',$list['time'])][$list['kind_id']]++;
		}
		$allDay=Tools::getdateArr($date['start_time'],$date['end_time']);
		foreach ($allDay as $key=>$value){
			if (!isset($stats[$key]))$stats[$key]=array();
		}
		krsort($stats);
		return $stats;
	}
	
	/**
	 * 统计(按小时)
	 * @param array $date
	 * @param int $gameTypeId
	 * @param int $source
	 * @param int $lang
	 */
	public function statsHour($date,$gameTypeId=NULL,$source=NULL,$lang=NULL){
		$sql="select kind_id,time from {$this->tName()} where 1";
		$startTime=strtotime(date('Y-m-d 00:00:00',strtotime($date['start_time'])));
		$endTime=strtotime(date('Y-m-d 23:59:59',strtotime($date['end_time'])));		
		$sql.=" and time between {$startTime} and {$endTime}";
		if ($gameTypeId)$sql.=" and game_type_id={$gameTypeId}";
		if ($source)$sql.=" and source={$source}";
		if ($lang)$sql.=" and lang_id={$lang}";
		$dataList=$this->select($sql);
		if (!$dataList)return false;
		$stats=array();
		foreach ($dataList as $list){
			$stats['total']['total']++;
			$stats['total'][$list['kind_id']]++;
			$stats[intval(date('H',$list['time']))]['total']++;
			$stats[intval(date('H',$list['time']))][$list['kind_id']]++;
		}
		
		$this->_modelPlayerKindFaq=$this->_getGlobalData('Model_PlayerKindFaq','object');
		$kindFaq=$this->_modelPlayerKindFaq->findListAll($gameTypeId);
		
		for ($i=0;$i<24;$i++){
			if (!$stats[$i])$stats[$i]['total']=0;
			foreach ($kindFaq as $key=>$value){
				$stats[$i][$key]=intval($stats[$i][$key]);
			}
		}
		
		ksort($stats);
		return $stats;
	}
}