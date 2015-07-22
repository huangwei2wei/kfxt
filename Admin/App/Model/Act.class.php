<?php
/**
 * 用户角色表
 * @author php-朱磊
 *
 */
class Model_Act extends Model {
	protected $_tableName='act';	//表名
	
	/**
	 * Model_User
	 * @var Model_User
	 */
	private $_modelUser;
	
	/**
	 * Model_Roles
	 * @var Model_Roles
	 */
	private $_modelRoles;
	
	public function __construct(){
		
	}
	
	/**
	 * 获取所有的角色
	 */
	public function getAllRoles(){
		$this->_modelRoles=$this->_getGlobalData('Model_Roles','object');
		$allRoles=$this->_modelRoles->findAll();
		return $this->getTtwoArrConvertOneArr($allRoles,'role_value','role_name');
	}
	
	/**
	 * 获取指定控制器或方法用户所拥有的
	 * @param string $actId
	 */
	public function getUserAct($actValue){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$users=$this->_modelUser->select("select * from {$this->_modelUser->tName()} where act like '%{$actValue}%'");
		return $this->getTtwoArrConvertOneArr($users,'Id','nick_name');
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
	 * 获取用户所得到的权限
	 * @param int $userId
	 */
	public function getUseRoleAct($userId){
		$this->_modelUser=$this->_getGlobalData('Model_User','object');
		$userInfo=$this->_modelUser->findById($userId);
		$userRolesArr=$userInfo['roles'];
		$userRolesArr=explode(',',$userRolesArr);
		#------用户act------#
		$userAct=$userInfo['act'];
		$userAct=$userAct?explode(',',$userAct):array();
		#------用户act------#
		$dataList=$this->findAll();
		foreach ($dataList as $key=>$value){
			if ($value['allow']==RBAC_EVERYONE){	//如果为所有马上为true
				$dataList[$key]['selected']=1;
				continue;
			}
			$curRoles=explode(',',$value['allow']);
			$intersect=array_intersect($curRoles,$userRolesArr);
			if (count($intersect)){
				$dataList[$key]['selected']=1;
			}else {
				if (in_array($value['value'],$userAct)){
					$dataList[$key]['selected']=2;
				}else {
					$dataList[$key]['selected']=0;
				}
			}
		}		
		return $dataList;
	}
	/**
	 * 获取拥有该权限的所有用户组
	 * @param string $actVal
	 * @author doter
	 */
	function getActRoles($actVal){
		$sql="select allow from {$this->tName()} where value = '$actVal'";
		return $this->select($sql,1);
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