<?php
/**
 * 技术管理后台每日工作计划表
 * @author php-朱磊
 *
 */
class Model_ProgramDatework extends Model {
	protected $_tableName='program_datework';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function add($postArr){
		if (empty($postArr['content']))return array('href'=>2,'msg'=>'工作计划不能为空','status'=>-1);
		if (empty($postArr['group_id']))return array('href'=>2,'msg'=>'请选择项目组','status'=>-1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();	
		
		$addArr=array();
		$addArr['start_time']=strtotime($postArr['start_time']);
		$isAdd=$this->select("select Id from {$this->tName()} where user_id={$userClass['_id']} and start_time={$addArr['start_time']}",1);
		if ($isAdd)return array('href'=>2,'msg'=>'您今天已经填写过工作计划','status'=>-1);

		$addArr['end_time']=strtotime($postArr['end_time']);
		$addArr['content']=$postArr['content'];
		$addArr['group_id']=$postArr['group_id'];
		$addArr['user_id']=$userClass['_id'];
		if (parent::add($addArr)){
			return array('href'=>Tools::url('ProgramDateWork','Index',array('zp'=>'Program')),'msg'=>'添加成功','status'=>1);
		}else {
			return array('href'=>Tools::url('ProgramDateWork','Index',array('zp'=>'Program')),'msg'=>'添加失败','status'=>-2);
		}
	}
	
	/**
	 * 完成任务
	 */
	public function finish($id){
		if (!$id)return array('href'=>1,'msg'=>'参数错误','status'=>-1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$task=$this->findById($id);
		if ($task['actual_time'])return array('href'=>1,'msg'=>'此工作已经完成','status'=>-1);
		if ($task['user_id']!=$userClass['_id'])return array('href'=>1,'msg'=>'此工作您不能完成','status'=>-1);
		$updateArr=array();
		$updateArr['actual_time']=CURRENT_TIME;
		$this->update($updateArr,"Id={$id}");
		return array('href'=>1,'msg'=>'任务完成,完成时间为：'.date('Y-m-d H:i:s',CURRENT_TIME),'status'=>1);
	}
	
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('href'=>Tools::url('ProgramDateWork','Index'),'msg'=>'参数错误','status'=>-1);
		if (empty($postArr['content']))return array('href'=>2,'msg'=>'内容不能为空','status'=>-1);
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$task=$this->findById($postArr['Id']);
		if ($task['actual_time'])return array('href'=>1,'msg'=>'此工作已经完成,您不能编辑','status'=>-1);
		if ($task['user_id']!=$userClass['_id'])return array('href'=>1,'msg'=>'您不能编辑只内容','status'=>-1);
		$this->update(array('content'=>$postArr['content']),"Id={$postArr['Id']}");
		return array('href'=>Tools::url('ProgramDateWork','Index',array('zp'=>'Program')),'msg'=>'修改成功','status'=>1);
	}
}