<?php
class Model_OnlineUser extends Model {
	
	protected $_tableName='online_user';
	
	/**
	 * 根据用户名查找用户
	 * @param string $userName 用户名
	 */
	public function findByUserName($userName){
		return $this->select("select user_name from {$this->tName()} where user_name='{$userName}'",1);
	}
	
	/**
	 * 清除在线用户2分钟内的用户都将被清掉,如果加了userid,将清除指定的用户
	 * @param int $userId 要清除指定的用户
	 */
	public function deleteFromUser($userId=NULL){
		if ($userId===null){
			$time=CURRENT_TIME-120;
			return $this->execute("delete from {$this->tName()} where last_time<$time");
		}else {
			$this->execute("delete from {$this->tName()} where user_id = {$userId}");
		}

	}
	
	/**
	 * 更新在线用户room_id为$roomId的id,清理所有的房间内用户
	 * @param int $roomId
	 */
	public function clearRoomUsers($roomId){
		return $this->execute("update {$this->tName()} set room_id=0 where room_id={$roomId}");
	}
	
	/**
	 * 更新一个在线用户的room_id为0,清理房间内单一用户
	 * @param int $userId
	 */
	public function clearSingleRoomUser($userId){
		return $this->execute("update {$this->tName()} set room_id=0 where user_id={$userId} limit 1");
	}
	
	/**
	 * 更新在线表用户进入一个房间
	 * @param $userName 用户名
	 * @param $roomId 房间id
	 */
	public function addRoomUser($userName,$roomId){
		return $this->execute("update {$this->tName()} set room_id={$roomId} where user_name='{$userName}' limit 1");
	}
	
	/**
	 * 对一个用户的工单数加1
	 */
	public function userOrderNumToAdd($userName){
		return $this->execute("update {$this->tName()} set order_num=order_num+1 where user_name='{$userName}' limit 1");
	} 
	
}	