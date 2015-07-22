<?php
class Model_Quality extends Model {
	protected $_tableName='quality';
	
	/**
	 * 根据qa_id来查找质检详细
	 * @param int $id
	 */
	public function findByQaId($id){
		return $this->select("select * from {$this->tName()} where qa_id={$id}",1);
	}
	
}