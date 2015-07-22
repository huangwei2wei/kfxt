<?php
class Model_UserProiorityOperator extends Model {
	protected $_tableName = 'user_priority_operator';
	
	/**
	 * 根据用户id查找运营商
	 * @param int $userId
	 */
	public function findByUserId($userId) {
		return $this->select ( "select * from {$this->tName()} where user_id={$userId} order by priority_level" );
	}
	
	/**
	 * 为用户增加一个运营商
	 * @param array $arr
	 * @return boolean
	 */
	public function addUserOperator($arr) {
		if ($this->select ( "select * from {$this->tName()} where operator_id={$arr['operator_id']} and user_id={$arr['user_id']} and game_type_id={$arr['game_type_id']}" ))
			return false; //如果有相同记录将返回
		if ($this->add ( $arr )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 根据user_id删除user_id所有记录
	 * @param int $id
	 * @return boolean
	 */
	public function delByUserId($id){
		return $this->execute("delete from {$this->tName()} where user_id={$id} ");
	}
	
	/**
	 * 更新用户运营商排序
	 * @param array $arr
	 * @param int $userId
	 * @return void
	 */
	public function updateSort($arr, $userId) {
		foreach ( $arr as $key => $value ) {
			$this->update ( array ('priority_level' => $value ), "user_id={$userId} and operator_id={$key}" );
		}
	}
	
	/**
	 * 删除用户里的一个运营商
	 * @param int $gameTypeId
	 * @param int $operatorId
	 * @param int $userId
	 * @return boolean
	 */
	public function delByOperatorId($gameTypeId, $operatorId, $userId) {
		if ($this->execute ( "delete from {$this->tName()} where game_type_id={$gameTypeId} and operator_id = {$operatorId} and user_id={$userId}" )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 查找用户下所有的运营商
	 * @param int $userId	用户id
	 */
	public function findByUserOperatorList($userId) {
		$user = $this->select ( "select * from {$this->tName()} where user_id={$userId} order by operator_id" );
		$userOperatorList = array ();
		foreach ( $user as $value ) {
			array_push ( $userOperatorList, $value ['operator_id'] );
		}
		return $userOperatorList;
	}
}