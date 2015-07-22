<?php
abstract class Program extends Control {
	
	/**
	 * 技术部门ID
	 * @var int
	 */
	const DEPARTMENT_IT=2;
	
	private $_itUsers=null;
	
	/**
	 * 获取技术部所有人员
	 */
	public function getItUsers(){
		if ($this->_itUsers===null){
			$users=$this->_getGlobalData('user');
			foreach ($users as $user){
				if ($user['department_id']==self::DEPARTMENT_IT){
					$this->_itUsers[$user['Id']]=$user['nick_name'];
				}
			}
		}
		return $this->_itUsers;
	}
}