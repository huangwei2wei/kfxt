<?php
/**
 * 模块编辑
 * @author php-朱磊
 *
 */
class Model_Moudle extends Model {
	
	protected $_tableName='moudle';

	public function add($postArr){
		if (empty($postArr['value']))return array('msg'=>'模块值不能为空','status'=>-1,'href'=>1);
		if (empty($postArr['name']))return array('msg'=>'模块名不能为空','status'=>-1,'href'=>1);
		$addArr=array('value'=>$postArr['value'],'name'=>$postArr['name']);
		if (parent::add($addArr)){
			return array('msg'=>'增加成功','status'=>1,'href'=>1);
		}else {
			return array('msg'=>'增加失败','status'=>-2,'href'=>1);
		}
	}
	
	public function edit($postArr){
		if (empty($postArr['value']))return array('msg'=>'模块值不能为空','status'=>-1,'href'=>1);
		if (empty($postArr['name']))return array('msg'=>'模块名不能为空','status'=>-1,'href'=>1);
		if (empty($postArr['old_value']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$updateArr=array('value'=>$postArr['value'],'name'=>$postArr['name'],'act'=>$postArr['act']);
		if ($this->update($updateArr,"value='{$postArr['old_value']}'")){
			return array('msg'=>'修改成功','status'=>1,'href'=>1);
		}else {
			return array('msg'=>'修改失败','status'=>-2,'href'=>1);
		}
	}
	
	public function del($moudelValue){
		$this->execute("delete from {$this->tName()} where value='{$moudelValue}'");
		return array('msg'=>'删除成功','status'=>1,'href'=>1);
	}
	

	
	/**
	 * 获取act
	 * @param string $moudleValue
	 * @param string $roleValue
	 */
	public function getAct($moudleValue,$roleValue=NULL){
		$moudleValue=ucwords($moudleValue);
		$dirPath=APP_PATH . "/Control/{$moudleValue}/Class";
		if (!is_dir($dirPath))return false;
		require_once APP_PATH . "/Control/{$moudleValue}/{$moudleValue}.class.php";
		$menu=require_once APP_PATH . "/Control/{$moudleValue}/menu.cache.php";
		$classDetail=array();
		foreach (glob($dirPath.'/*.php') as $file){
			require_once $file;
			$controlName=basename($file,'.class.php');
			$className='Control_'.basename($file,'.class.php');
			if (!class_exists($className))continue;
			$curClass=new $className();
			$classDetail[$controlName]=$curClass->getClassDetail($className,$menu[$controlName]);
		}
		$act=$this->getActFile($moudleValue);
		if ($act){
			foreach ($classDetail as &$class){
				$class['act']=empty($act[$class['value']])?array():$act[$class['value']];
				if ($roleValue)$class['selected']=(in_array($roleValue,$class['act']) || $class['act']==RBAC_EVERYONE)?true:false;
				if ($class['class_methods']){
					foreach ($class['class_methods'] as &$method){
						$method['act']=empty($act["{$class['value']}_{$method['value']}"])?array():$act["{$class['value']}_{$method['value']}"];
						if ($roleValue)$method['selected']=(in_array($roleValue,$method['act']) || $method['act']==RBAC_EVERYONE)?true:false;
					}
				}
			}
		}
		return $classDetail;
	}
	
	public function getActFile($moudleValue){
		$cacheFile=CACHE_DIR . "/moudle_act/{$moudleValue}.cache.php";
		if (file_exists($cacheFile)){
			return $this->_getGlobalData("moudle_act/{$moudleValue}");
		}else {
			return false;
		}
	}
	
	public function createCache($moudleValue=NULL,$postArr=NULL){
		$dirPath=CACHE_DIR.'/moudle_act';
		if (!is_dir($dirPath))mkdir($dirPath,0777,true);
		if ($moudleValue===null){
			$dataList=$this->findAll();
			foreach ($dataList as &$list){
				if ($list['act']!=RBAC_EVERYONE)
					$list['act']=explode(',',$list['act']);
			}
			$this->_addCache($dataList,CACHE_DIR.'/moudle_act/moudle.cache.php');
		}else {
			$classDetail=$this->getAct($moudleValue);
			$actArr=array();
			if (!is_null($postArr)){
				foreach ($postArr as $key=>&$value){
					if ($value==RBAC_EVERYONE || empty($value))continue;
					$value=explode(',',$value);
				}
			}
			foreach ($classDetail as $class){
				$actArr[$class['value']]=(is_null($postArr))?$class['act']:$postArr[$class['value']];
				if ($class['class_methods']){
					foreach ($class['class_methods'] as $method){
						$actArr[$class['value'].'_'.$method['value']]=(is_null($postArr))?$method['act']:$postArr[$class['value'].'_'.$method['value']];
					}
				}
			}
			$this->_addCache($actArr,CACHE_DIR . "/moudle_act/{$moudleValue}.cache.php");
		}
		return array('msg'=>'创建缓存成功','status'=>1,'href'=>1);
	}
	
	/**
	 * 编辑角色
	 */
	public function editRoleAct($postArr){
		$roleValue=trim($postArr['role_value']);
		$moudleValue=$postArr['value'];
		if (!$moudleValue)$moudleValue=array();//防止出错
		$dataList=$this->findAll();
		foreach ($dataList as &$value){
			if ($value['act']==RBAC_EVERYONE)continue;	//如果是所有用户将跳过不执行
			if (empty($value['act'])){
				$roles=array();
			}else {
				$roles=explode(',',$value['act']);
			}
			$key=array_search($value['value'],$moudleValue);
			if ($key===false){//如果没有找到,就表示用户让角色对此模块没有权限,将更新此模块删除allow字段里这个角色
				$rolesKey = array_search ( $roleValue, $roles );
				if ($rolesKey !== false)
					unset ( $roles [$rolesKey] ); //如果有这个角色,将删除这个角色
				$roles = implode ( ',', $roles );
				$updateArr = array ('act' => $roles );
			}else {//否则将加上这个角色,并且更新allow字段
				$rolesKey = array_search ( $roleValue, $roles );
				if ($rolesKey !== false)
					continue; //如果找到此值,就说明此模块已经有这个角色,不用做操作.
				array_push ( $roles, $roleValue );
				$roles = implode ( ',', $roles );
				$updateArr = array ('act' => $roles );
			}
			
			$this->update($updateArr,"value='{$value['value']}'");
		}
		return array('msg'=>'更改权限成功','status'=>1,'href'=>Tools::url('User','Roles'));
	}
	
	/**
	 * 编辑
	 * @param array $postArr
	 */
	public function editMoudleAct($postArr){
		if (empty($postArr['role_value']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		if (empty($postArr['moudle']))return array('msg'=>'参数错误','status'=>-1,'href'=>1);
		$moudle=ucwords($postArr['moudle']);
		$roleValue=strtolower(trim($postArr['role_value']));
		$moudleValue=$postArr['value'];
		if (!count($moudleValue))$moudleValue=array();//防止出错
		$dataList=$this->getActFile($postArr['moudle']);
		if (!$dataList)return array('msg'=>'未创建缓存','status'=>-1,'href'=>1);
		foreach ($dataList as $key=>&$value){
			if ($value==RBAC_EVERYONE)continue;
			if (!is_array($value))$value=array();
			$value=count($value)?$value:array();	//防止出错
			if (in_array($key,$moudleValue)){//如果选中了此权限,就加入此权限
				if (!in_array($roleValue,$value))array_push($value,$roleValue);
			}else {//删除此权限
				$key=array_search($roleValue,$value);
				if ($key!==false)unset($value[$key]);
			}
		}
		$this->_addCache($dataList,CACHE_DIR . "/moudle_act/{$moudle}.cache.php");
		return array('msg'=>'更改权限成功','status'=>1,'href'=>Tools::url('User','Roles'));
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}