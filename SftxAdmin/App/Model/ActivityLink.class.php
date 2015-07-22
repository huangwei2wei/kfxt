<?php
/**
 * 客服日常工具-活动链接表
 * @author php-朱磊
 *
 */
class Model_ActivityLink extends Model {
	protected $_tableName='activity_link';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function add($postArr){
		if (empty($postArr['title']))return array('msg'=>'请输入标题','status'=>-1,'href'=>1);
		if (empty($postArr['href']))return array('msg'=>'请输入链接','status'=>-1,'href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array();
		$addArr['title']=$postArr['title'];
		$addArr['href']=$postArr['href'];
		$addArr['game_type_id']=$postArr['game_type_id'];
		$addArr['operator_id']=$postArr['operator_id'];
		$addArr['user_id']=$userClass['_id'];
		$addArr['edit_time']=CURRENT_TIME;
		if (parent::add($addArr)){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'添加失败','status'=>-2,'href'=>1);
		}
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('msg'=>'请选择要修改的链接','status'=>-1,'href'=>1);
		if (empty($postArr['title']))return array('msg'=>'请输入标题','status'=>-1,'href'=>1);
		if (empty($postArr['href']))return array('msg'=>'请输入链接','status'=>-1,'href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$editArr=array();
		$editArr['title']=$postArr['title'];
		$editArr['href']=$postArr['href'];
		$editArr['game_type_id']=$postArr['game_type_id'];
		$editArr['operator_id']=$postArr['operator_id'];
		$editArr['user_id']=$userClass['_id'];
		$editArr['edit_time']=CURRENT_TIME;
			if ($this->update($editArr,"Id={$postArr['Id']}")){
			return array('msg'=>false,'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'修改失败','status'=>-2,'href'=>1);
		}
	}
}