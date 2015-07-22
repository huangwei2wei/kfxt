<?php
/**
 * 问卷主表
 * @author 社游-陈成禧
 *
 */
class Model_Askform extends Model{
	protected $_tableName = 'askform';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * Model_AskformOption
	 * @var Model_AskformOption
	 */
	private $_modelAskformOption;
	
	public function add($postArr){
		if (empty($postArr['title']))return array('status'=>-1,'msg'=>'请填写标题','href'=>1);
		if (empty($postArr['description']))return array('status'=>-1,'msg'=>'请填写描述','href'=>1);
		if (empty($postArr['start_time']))return array('status'=>-1,'msg'=>'请选择开始时间','href'=>1);
		if (empty($postArr['end_time']))return array('status'=>-1,'msg'=>'请选择结束时间','href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array();
		$addArr['title']=$postArr['title'];
		$addArr['description']=$postArr['description'];
		$addArr['start_time']=strtotime($postArr['start_time']);
		$addArr['end_time']=strtotime($postArr['end_time']);
		$addArr['create_time']=CURRENT_TIME;
		$addArr['status']=$postArr['status'];
		$addArr['user_id']=$userClass['_id'];
		if (parent::add($addArr)){
			return array('status'=>1,'msg'=>false,'href'=>Tools::url('Askform','Askform'));
		}else {
			return array('status'=>-2,'msg'=>'添加问卷失败','href'=>1);
		}
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('status'=>-1,'msg'=>'请选择要更新的问卷','href'=>1);
		if (empty($postArr['title']))return array('status'=>-1,'msg'=>'请填写标题','href'=>1);
		if (empty($postArr['description']))return array('status'=>-1,'msg'=>'请填写描述','href'=>1);
		if (empty($postArr['start_time']))return array('status'=>-1,'msg'=>'请选择开始时间','href'=>1);
		if (empty($postArr['end_time']))return array('status'=>-1,'msg'=>'请选择结束时间','href'=>1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$updateArr=array();
		$updateArr['title']=$postArr['title'];
		$updateArr['description']=$postArr['description'];
		$updateArr['start_time']=strtotime($postArr['start_time']);
		$updateArr['end_time']=strtotime($postArr['end_time']);
		$updateArr['status']=$postArr['status'];
		$updateArr['user_id']=$userClass['_id'];
		if (parent::update($updateArr,"Id={$postArr['Id']}")){
			return array('status'=>1,'msg'=>false,'href'=>1);
		}else {
			return array('status'=>-2,'msg'=>'编辑问卷失败','href'=>1);
		}
	}
	
	public function delById($id){
		if (empty($id))return array('status'=>-1,'msg'=>'请选择要删除的问卷','href'=>1);
		parent::delById($id);
		$this->_modelAskformOption=$this->_getGlobalData('Model_AskformOption','object');
		$this->_modelAskformOption->execute("delete from {$this->_modelAskformOption->tName()} where askform_id={$id}");
		return array('status'=>1,'msg'=>false,'href'=>1);
	}
}