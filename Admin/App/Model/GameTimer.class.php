<?php
/**
 * 被定时器扫描过的游戏（memory表）
 * @author php-兴源
 *
 */
class Model_GameTimer extends Model {
	protected $_tableName='game_timer';
	
	private $_timeTypeFields = array(
		'time_wo_new',
		'time_wo_del',
		'time_wo_ev',
	);
	/**
	 * 删除表的所有记录
	 */
	private function _clearAll(){
		$sql = 'TRUNCATE TABLE '.$this->tName();
		return $this->execute($sql);
	}
	
	public function updateAll($games=array()){
		if($games && is_array($games)){
			$sql = '';
			$games = array_unique($games);
			foreach($games as $gameId_chunk){
				$tmp = explode('_',$gameId_chunk);
				$gameId = intval($tmp[0]);
				$chunk = intval($tmp[1]);
				$sql .= ",('{$gameId}','{$chunk}')";
			}
			$sql = ltrim($sql,',');
			$sql = "INSERT INTO {$this->tName()} (game_type,chunk) values {$sql}";
			$this->_clearAll();
			return $this->execute($sql);
		}
		return false;		
	}
	/**
	 * 获得指定字段里最早更新过的一条数据
	 * @param String $field
	 */
	public function getEarly($field=NULL){
		if(in_array($field,$this->_timeTypeFields)){
			$sql = "SELECT game_type,chunk FROM {$this->tName()} ORDER BY {$field}";
			$result = $this->select($sql,1);
			if($result){
				return $result;
			}
		}
		return false;
	}
	/**
	 * 检查是否存在游戏和分块
	 * @param int $gameId
	 * @param int $chunk
	 */
	public function checkOne($gameId=0,$chunk=0){
		$gameId = intval($gameId);
		$chunk = intval($chunk);
		if($gameId && $chunk){
			$sql = "SELECT game_type,chunk FROM {$this->tName()} WHERE game_type = {$gameId} and chunk = {$chunk}";
			$result = $this->select($sql,1);
			if($result){
				return $result;
			}
		}
		return false;
	}
	/**
	 * 更新一条记录，将时间设置当前的更新时间
	 * @param int $gameId
	 * @param int $chunk
	 * @param String $field
	 */
	public function updateOne($gameId=0,$chunk=0,$field=NULL){
		$gameId = intval($gameId);
		$chunk = intval($chunk);
		if($gameId && in_array($field,$this->_timeTypeFields)){
			$this->update(array($field => CURRENT_TIME),"game_type={$gameId} and chunk = {$chunk}");
		}else{
			return false;
		}
	}
	
}