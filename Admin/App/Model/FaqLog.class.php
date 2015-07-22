<?php
/**
 * 
 * @author php-朱磊
 *
 */
class Model_FaqLog extends Model {
	protected $_tableName='player_faq_log';
	
	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;
	
	/**
	 * 计算出FAQ评评
	 * @param int $faqId
	 */
	public function getFaqEvaluation($faqId){
		$list=$this->select("select * from {$this->tName()} where player_faq_id={$faqId}");
		if ($list){
			$evArr=array();
			foreach ($list as $value){
				if ($value['faq_whether']){
					$evArr['good']++;
				}else{
					$evArr['bad']++;
					if($value['faq_opinion']!==null)$evArr['opinion'][$value['faq_opinion']]++;
				}
			}
			return $evArr;
		}
	}
	
	/**
	 * 分页
	 * @param array $conditions sql条件
	 * @param int $page 第几页
	 */
	public function findPage($conditions,$page=0){
		$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
		$thisTableName=$this->tName();
		$faqTableName=$this->_modelPlayerFaq->tName();
		$sql="select {$thisTableName}.*,{$faqTableName}.lang_id,{$faqTableName}.kind_id,{$faqTableName}.ratio,{$faqTableName}.question from {$thisTableName} left join {$faqTableName} on {$thisTableName}.player_faq_id={$faqTableName}.Id where 1 ";
		$where='';
		if ($conditions['faq_id']){
			$where.=" and {$thisTableName}.player_faq_id={$conditions['faq_id']} ";
		}else {
			if ($conditions['whether']!='')$where.=" and {$thisTableName}.faq_whether={$conditions['whether']}";
			if ($conditions['game_type_id'])$where.=" and {$thisTableName}.game_type_id={$conditions['game_type_id']}";
			if ($conditions['faq_opinion']!='')$where.=" and {$thisTableName}.faq_opinion={$conditions['faq_opinion']}";
			if ($conditions['source'])$where.=" and {$thisTableName}.source={$conditions['source']}";
			if ($conditions['time']['start'] && $conditions['time']['end'])$where.=" and date_create between ".strtotime($conditions['time']['start'])." and ".strtotime($conditions['time']['end']);
			if ($conditions['lang'])$where.=" and {$thisTableName}.lang_id = {$conditions['lang']}";
		}

		$sql.=$where;
		$sql.=' order by Id desc';
		if ($page!=0)$page--;
		$begin=$page*PAGE_SIZE;
		$sql.=" limit {$begin},".PAGE_SIZE;
		$dataList=$this->select($sql);
		$total=$this->findCount(substr($where,5));
		return array('dataList'=>$dataList,'total'=>$total);
	}
}