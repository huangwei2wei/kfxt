<?php
/**
 * 项目表
 * @author php-朱磊
 */
class Model_ProgramProject extends Model {
	protected $_tableName='program_project';
	
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;
	
	public function add($postArr){
		if (empty($postArr['name']))return array('msg'=>'项目名称不能为空','status'=>-1,'href'=>2);
		if (empty($postArr['principal_user_id']))return array('msg'=>'请选择项目负责人','status'=>-1,'href'=>2);
		if (empty($postArr['detail']))$postArr['detail']='暂无内容';
		$addArr=array('name'=>$postArr['name'],'principal_user_id'=>$postArr['principal_user_id'],'detail'=>$postArr['detail']);
		parent::add($addArr);
		$this->createCache();
		return array('msg'=>'增加项目成功','status'=>1,'href'=>Tools::url('ProgramProject','Index',array('zp'=>'Program')));
	}
	
	public function edit($postArr){
		if (empty($postArr['Id']))return array('msg'=>'参数错误','status'=>-1,'href'=>2);
		if (empty($postArr['name']))return array('msg'=>'项目名称不能为空','status'=>-1,'href'=>2);
		if (empty($postArr['principal_user_id']))return array('msg'=>'请选择项目负责人','status'=>-1,'href'=>2);
		if (empty($postArr['detail']))$postArr['detail']='暂无内容';
		$updateArr=array('name'=>$postArr['name'],'principal_user_id'=>$postArr['principal_user_id'],'detail'=>$postArr['detail']);
		$this->update($updateArr,"Id={$postArr['Id']}");
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$this->_modelUser->execute("update {$this->_modelUser->tName()} set project_id=0 where project_id = {$postArr['Id']}");
		if (count($postArr['user_id']))$this->_modelUser->execute("update {$this->_modelUser->tName()} set project_id={$postArr['Id']} where Id in (".implode(',',$postArr['user_id']).")");
		$this->_modelUser->createCache();
		$this->createCache();
		return array('msg'=>'项目修改成功','status'=>1,'href'=>Tools::url('ProgramProject','Index',array('zp'=>'Program')));
	}
	
	public function createCache(){
		$dataList=$this->findAll();
		$cacheDir=CACHE_DIR.'/program';
		if (!is_dir($cacheDir))mkdir($cacheDir,0777,true);
		$cacheFile=$cacheDir . '/project.cache.php';
		return $this->_addCache($dataList,$cacheFile);
	}
	
	public function findAllProjectUser(){
		$allUsers=$this->_getGlobalData('user_index_id');
		$projects=$this->findAll();
		foreach ($projects as $key=>&$value){
			$value['word_principal_user_id']=$allUsers[$value['principal_user_id']];
			$value['users']=$this->select("select * from {$this->tName('user')} where project_id={$value['Id']}");
		}
		return $projects;
	}
	
	/**
	 * 查找没有加入项目组的人
	 * @param int $departmentId
	 * @param int $projectId
	 */
	public function findProjectUser($departmentId,$projectId){
		$users=$this->select("select * from {$this->tName('user')} where department_id='{$departmentId}'");
		$allUser=$users;
		$isSelect=array();
		foreach ($users as $key=>$user){
			if ($user['project_id']==$projectId){//加入选中按钮
				array_push($isSelect,$user['Id']);
				continue;
			}
			if ($user['project_id']!=0)unset($users[$key]);	//如果不等于0,就表示在其它项目组,删除掉
		}
		return array('all_user'=>$users,'is_selected'=>$isSelect,'full_user'=>$allUser);
	}
	
	
}