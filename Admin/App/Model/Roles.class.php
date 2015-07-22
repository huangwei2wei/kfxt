<?php
/**
 * 用户角色表
 * @author php-朱磊
 *
 */
class Model_Roles extends Model {
	protected $_tableName = 'roles';
	
	/**
	 * 查找用户的角色
	 * @param int $userId 用户id
	 */
	public function findRoles($userId) {
		$sql = "select {$this->tName()}.role_value from {$this->tName()},{$this->tName('rose')}
			where {$this->tName('rose')}.user_id={$userId} 
			and 
			{$this->tName('rose')}.role_id={$this->tName()}.Id";
		$list = $this->select ( $sql );
		foreach ( $list as &$value ) {
			$value = $value ['role_value'];
		}
		return $list;
	}
	
	/**
	 * 通过角色名来查找名字
	 * @param string $roleValue
	 */
	public function findByRoleToName($roleValue){
		$data=$this->select("select role_name from {$this->tName()} where role_value='{$roleValue}'",1);
		return $data['role_name'];
	}
	

}