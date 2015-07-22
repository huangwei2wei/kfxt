<?php
class Model_PlayerKindFaq extends Model {
	protected $_tableName='player_kind_faq';
	
	/**
	 * 根据游戏ID查找分类
	 * @param int $gameTypeId
	 */
	public function findByGameTypeId($gameTypeId){
		return $this->select("select * from {$this->tName()} where game_type_id={$gameTypeId}");
	}
	
	/**
	 * 根据ID删除一条记录
	 * @param int $id
	 */
	public function deleteById($id){
		return $this->execute("delete from {$this->tName()} where Id={$id} limit 1");
	}
	
}