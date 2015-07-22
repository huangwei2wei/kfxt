<?php
/**
 * 用户角色表
 * @author php-朱磊
 *
 */
class Model_Act extends Model {
	protected $_tableName='act';	//表名
	
	public function __construct(){
		
	}
	
	/**
	 * 增加控制器
	 * @param array $postArr
	 */
	public function addCtl($postArr){
		if (empty($postArr['control_name']))return array('msg'=>'控制器名不能为空','status'=>-1,'href'=>1);
		$controlName=ucwords ( $_POST ['control_name'] );
		$result=$this->select("select Id from {$this->tName()} where value='{$controlName}'");
		if ($result)return array('msg'=>'已有相同的控制器名','status'=>-1,'href'=>1);
		$addArr = array ('allow' => $_POST ['all_role'] ? RBAC_EVERYONE : '', 'value' => $controlName );
		if ($this->add($addArr)){
			return array('msg'=>false,'href'=>1,'status'=>1);
		}else {
			return array('msg'=>'添加失败','href'=>1,'status'=>-2);
		}
	}
	
	/**
	 * 增加动作
	 * @param array $postArr
	 */
	public function addAct($postArr){
		$postArr ['action_name']=trim($postArr ['action_name']);
		if (empty($postArr ['action_name']))return array('msg'=>'动作名不能为空','status'=>-1,'href'=>1);
		$controlList=$this->findById($postArr['control_id']);
		$controlList=$controlList['value'];
		$actionValue=$controlList . '_' . ucwords ( $postArr ['action_name'] );
		$result=$this->select("select Id from {$this->tName()} where value='{$actionValue}'");
		if ($result)return array('msg'=>'已经有相同的动作名','status'=>-1,'href'=>1);
		$addArr=array('allow' => $postArr ['all_role'] ? RBAC_EVERYONE : '', 'value' => $actionValue,'parent_id' => $postArr ['control_id']	);
		if ($this->add($addArr)){
			return array('msg'=>false,'href'=>1,'status'=>1);
		}else{
			return array('msg'=>'添加失败','href'=>1,'status'=>-2);
		}
	}
	
	/**
	 * 查找所有子方法控制器
	 * @param int $id
	 */
	public function findByChild($id){
		return $this->select("select * from {$this->tName()} where parent_id='{$id}'");
	}
	
	/**
	 * 查找所有的控制器
	 */
	public function findByAllCtl(){
		return $this->select("select * from {$this->tName()} where parent_id=0");
	}
	
	/**
	 * 查找表所有数据
	 */
	public function findAll(){
		$sql="select * from {$this->tName()} order by parent_id";
		return $this->select($sql);
	}
	
	/**
	 * 删除控制器并且同时删除下面的子控制器
	 * @param int $id
	 * @return void
	 */
	public function deleteCtlandChildAct($id){
		$this->execute("delete from {$this->tName()} where Id={$id}");
		$this->execute("delete from {$this->tName()} where parent_id={$id}");
	}
	
	/**
	 * 删除单个方法或控制器
	 * @param int $id
	 */
	public function deleteToId($id){
		return $this->execute("delete from {$this->tName()} where Id={$id}");
	}
	
}