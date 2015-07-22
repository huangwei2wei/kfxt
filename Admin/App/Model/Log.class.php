<?php
/**
 * 系统日志表
 * @author php-朱磊
 *
 */
class Model_Log extends Model {
	protected $_tableName='log';
	
	
	
	/**
	 * 当前日期
	 * @var string
	 */
	private $_curMonth;
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	public function __construct(){
		$this->_curMonth=date('Ym',CURRENT_TIME);
		$this->_tableName.='_'.$this->_curMonth;	//当前表名
	}
	
	private function _createTable(){
		$this->_curMonth=date('Ym',CURRENT_TIME);
		$this->_tableName.='_'.$this->_curMonth;	//当前表名
		if (!$this->isTableExists($this->tName())){//如果表名不存在就创建表
			$this->execute(
				"CREATE TABLE `{$this->tName()}` (
				`Id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`user_id` INT( 10 ) NULL COMMENT '操作人',
				`time` INT( 10 ) NOT NULL COMMENT '操作时间',
				`control` VARCHAR( 50 ) NOT NULL COMMENT '操作控制器',
				`action` VARCHAR( 50 ) NOT NULL COMMENT '操作动作',
				`doaction` VARCHAR( 50 ) NULL COMMENT '子操作类型',
				`msg` TEXT NULL COMMENT '操作留言'
				) ENGINE = MYISAM COMMENT = '系统日志表{$this->_curMonth}';"
			);
		}
	}
	
	/**
	 * 新增一条记录
	 * @param string $msg
	 */
	public function add($msg=null,$constraint=FALSE){
		if ($constraint===false){
			if (in_array(CONTROL,array('Index','Default','Log','InterfaceFaq','InterfacePassport','InterfaceUpdate','InterfaceWorkOrder')))return ;
		}
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$insertArr=array();
		$insertArr['user_id']=$userClass['_id']?$userClass['_id']:0;
		$insertArr['time']=CURRENT_TIME;
		$insertArr['ip']=ip2long(Tools::getClientIP());
		$insertArr['control']=strtolower(CONTROL);
		$insertArr['action']=strtolower(ACTION);
		$insertArr['doaction']=strtolower($_GET['doaction']);
		if ($msg)$insertArr['msg']=$msg;
		parent::add($insertArr);
	}
	
	
	public function setTableName($tableName){
		$this->_tableName=$tableName;
	}
	
}