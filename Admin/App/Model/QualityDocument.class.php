<?php
/**
 * 质检归档表
 * @author php-朱磊
 *
 */
class Model_QualityDocument extends Model {
	protected $_tableName='quality_document';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * Model_OrderLog
	 * @var Model_OrderLog
	 */
	private $_modelOrderLog;

	public function add($postArr){
		if (empty($postArr['title']))return array('status'=>-2,'msg'=>'标题不能为空','href'=>2);
		if (empty($postArr['content']))return array('status'=>-2,'msg'=>'内容不能为空','href'=>2);
		$addArr=array();
		$addArr['org_id']=$postArr['org_id'];
		$addArr['reply_user_id']=$postArr['reply_user_id'];
		$addArr['source']=$postArr['source'];
		$addArr['quality_status']=$postArr['quality_status'];
		$addArr['quality_user_id']=$postArr['quality_user_id'];
		$addArr['scores']=$postArr['scores'];
		$addArr['feedback']=$postArr['feedback'];
		$addArr['title']=$postArr['title'];
		$addArr['content']=$postArr['content'];
		if ($postArr['work_order_id']){
			$addArr['work_order_id']=$postArr['work_order_id'];
			#------添加日志------#
			$this->_modelOrderLog=$this->_getGlobalData('Model_OrderLog','object');
			$this->_modelOrderLog->addLog(array('Id'=>$_POST['work_order_id']),Model_OrderLog::ADD_DOC);
			#------添加日志------#
		}
		if ($postArr['qa_id'])$addArr['qa_id']=$postArr['qa_id'];
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr['create_user_id']=$userClass['_id'];
		$addArr['create_time']=CURRENT_TIME;
		if (parent::add($addArr)){
			return array('status'=>1,'msg'=>'添加成功','href'=>Tools::url(CONTROL,'Document'));
		}else{
			return array('status'=>1,'msg'=>'添加失败','href'=>2);
		}
	}
	
	public function edit($data){
		if (empty($data['title']))return array('status'=>-2,'msg'=>'标题不能为空','href'=>2);
		if (empty($data['content']))return array('status'=>-2,'msg'=>'内容不能为空','href'=>2);
		$editArr=array();
		$editArr['org_id']=$data['org_id'];
		$editArr['reply_user_id']=$data['reply_user_id'];
		$editArr['source']=$data['source'];
		$editArr['quality_status']=$data['quality_status'];
		$editArr['quality_user_id']=$data['quality_user_id'];
		$editArr['scores']=$data['scores'];
		$editArr['feedback']=$data['feedback'];
		$editArr['title']=$data['title'];
		$editArr['content']=$data['content'];
		if ($this->update($editArr,"Id={$data['Id']}")){
			return array('status'=>1,'msg'=>'更新成功','href'=>Tools::url(CONTROL,'Document'));
		}else {
			return array('status'=>1,'msg'=>'更新失败','href'=>2);
		}
	}
	
}