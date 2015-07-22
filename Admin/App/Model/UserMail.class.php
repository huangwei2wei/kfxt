<?php
/**
 * 用户邮件
 * @author php-朱磊
 *
 */
class Model_UserMail extends Model {
	protected $_tableName='user_mail';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	/**
	 * 批量删除记录
	 * @param array $ids
	 */
	public function batchDel($ids){
		$count=count($ids);
		if (!$count)return array('status'=>1,'msg'=>'请选择要删除的记录','href'=>1);
		$ids=implode(',',$ids);
		if ($this->execute("delete from {$this->tName()} where Id in({$ids})")){
			$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
			$userClass=$this->_utilRbac->getUserClass();
			$userClass->getUserMail()->setMailCount('-'.$count);
			$userClass->setUpdateInfo(2);
			return array('status'=>1,'msg'=>false,'href'=>1);
		}else {
			return array('status'=>-2,'msg'=>'删除失败','href'=>1);
		}
	}
}