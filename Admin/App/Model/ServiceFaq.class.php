<?php
class Model_ServiceFaq extends Model {
	protected $_tableName='service_faq';
	
	/**
	 * 根据kind_id 查找相同类型的问题
	 * @param $id
	 * @return array
	 */
	public function findByKindId($id){
		return $this->select("select * from {$this->tName()} where kind_id={$id}");
	}
}