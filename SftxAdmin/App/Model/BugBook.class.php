<?php
/**
 * bug留言表
 * @author php-朱磊
 *
 */
class Model_BugBook extends Model {
	protected $_tableName='bug_book';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function add($postArr){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$addArr=array('user_id'=>$userClass['_id'],'create_time'=>CURRENT_TIME,'content'=>$postArr['content']);
		if (parent::add($addArr)){
			return array('status'=>1,'msg'=>false,'href'=>Tools::url(CONTROL,'BugBack'));
		}else {
			return array('status'=>-2,'msg'=>'添加bug失败','href'=>Tools::url(CONTROL,'BugBack'));
		}
	}
	
	/**
	 * 批量删除记录
	 * @param array $ids
	 */
	public function batchDel($ids){
		$count=count($ids);
		if (!$count)return array('status'=>1,'msg'=>'请选择要删除的记录','href'=>1);
		$ids=implode(',',$ids);
		if ($this->execute("delete from {$this->tName()} where Id in({$ids})")){
			return array('status'=>1,'msg'=>false,'href'=>1);
		}else {
			return array('status'=>-2,'msg'=>'删除失败','href'=>1);
		}
	}
}