<?php
/**
 * 游戏内部FAQ
 * @author php-朱磊
 *
 */
class Model_GameFaq extends Model {
	protected $_tableName='game_faq';
	
	/**
	 * 根据kind_id 查找相同类型的问题
	 * @param $id
	 * @return array
	 */
	public function findByKindId($id){
		return $this->select("select * from {$this->tName()} where kind_id={$id}");
	}
	
	/**
	 * 根据ID删除指定的一条记录
	 * @param int $id
	 */
	public function deleteById($id){
		return $this->execute("delete from {$this->tName()} where Id={$id}");
	}
	
	/**
	 * 根据kind_id删除FAQ记录
	 * @param int $id
	 */
	public function deleteByKindId($id){
		return $this->execute("delete from {$this->tName()} where kind_id={$id}");
	}
	
	/**
	 * 查找游戏类型为$gameTypeId,数量为$num的最高点击率FAQ
	 * @param int $gameTypeId
	 * @param int $num
	 */
	public function findHotList($gameTypeId,$num){
		return $this->select("select Id,ratio,game_type_id,kind_id,question from {$this->tName()} where game_type_id={$gameTypeId} order by ratio desc limit {$num}");
	}
	
	/**
	 * 更新faq点击率
	 * @param array $postArr
	 */
	public function ratioEdit($postArr){
		if (!Tools::coerceInt($postArr['Id']))return array('msg'=>'请选择正确的FAQ','status'=>-1,'href'=>1);
		$ratio=Tools::coerceInt($postArr['ratio']);
		if ($this->update(array('ratio'=>$ratio),"Id={$postArr['Id']}")){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'更改失败','status'=>-2,'href'=>1);
		}
	}
}