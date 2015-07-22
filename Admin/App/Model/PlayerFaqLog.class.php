<?php
/**
 * faq日志表
 * @author php-朱磊
 *
 */
class Model_PlayerFaqLog extends Model {
	protected $_tableName='player_faq_log';
	
	/**
	 * 统计FAQ意见表
	 * @param int $source
	 * @param int $gameTypeId
	 * @param array $time =array('start'=>'2011-2-21 12:00:00','end'=>'2011-2-21 23:00:00')
	 */
	public function stats($source=NULL,$gameTypeId=NULL,$time,$lang=array()){
		$startTime=strtotime($time['start']);
		$endtime=strtotime($time['end']);
		$sql="select * from {$this->tName()} where date_create between {$startTime} and {$endtime}";
		if ($source)$sql.=" and source={$source}";
		if ($gameTypeId)$sql.=" and game_type_id={$gameTypeId}";
		if (count($lang))$sql.=" and lang_id in (".implode(',',$lang).")";
		$dataList=$this->select($sql);
		if (!$dataList)return false;
		$stats=array();
		foreach ($dataList as $list){
			$stats['total']['whether'][$list['faq_whether']]++;
			if (!$list['faq_whether']){
				$stats['total']['opinion'][$list['faq_opinion']]++;	
				$stats[date('Y-m-d',$list['date_create'])]['opinion'][$list['faq_opinion']]++;
			}
			$stats[date('Y-m-d',$list['date_create'])]['whether'][$list['faq_whether']]++;
		}
		$allDay=Tools::getdateArr($startTime,$endtime);
		foreach ($allDay as $key=>$day){
			if (!isset($stats[$key]))$stats[$key]=array();
		}
		krsort($stats);
		return $stats;
	}
	
}