<?php
if (! defined ( 'ROOT_PATH' ))
	exit ();

/**
 * 连接通用数据库
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core
 */
abstract class Model extends Base {
	/**
	 * 数据库实例
	 * @var Model_Mysql
	 */
	private static $_instance = null;

	/**
	 * 单例模式返回句柄
	 * @return Model_Mysql
	 */
	private function _getInstance() {
		if ($this->_instance == null) {
			switch (DB_TYPE) {
				case 'pdo' :
					{
						$this->_loadCore ( 'Model_Pdo' );
						$this->_instance = new Model_Pdo ();
						break;
					}
				case 'mysql' :
					{
						$this->_loadCore ( 'Model_Mysql' );
						$this->_instance = new Model_Mysql ();
						break;
					}
				default : //默认为mysql
					{
						$this->_loadCore ( 'Model_Mysql' );
						$this->_instance = new Model_Mysql ();
						break;
					}
			}
		}
		return $this->_instance;
	}

	/**
	 * 转换字符串,防注入
	 * @param string $string
	 * @return string
	 */
	public static function escapeString($string) {
		return mysql_escape_string ( trim($string) );
	}

	/**
	 * 读操作，获取满足条件的数据
	 * @param string $sql
	 * @param int $number
	 * @return array $result
	 */
	public function select($sql, $number = '') {
		return $this->_getInstance ()->select ( $sql, $number );
	}

	/**
	 * 写操作，插入或者更新
	 * @param string $sql
	 * @return int $result
	 */
	public function execute($sql) {
		return $this->_getInstance ()->execute ( $sql );
	}

	/**
	 * 新增一条记录,参数1:表名,参数2:更新的数据
	 *
	 * @param string $table
	 * @param array $keyvalue
	 * @return boolen
	 */
	public function add($keyvalue, $table = null) {
		if (! $table)
			$table = $this->tName ();
		return $this->_getInstance ()->add ( $keyvalue, $table );
	}

	/**
	 * 更新一条记录
	 * @param string $table
	 * @param array $keyvalue
	 * @param string $conditions
	 * @return booblen
	 */
	public function update($keyvalue, $conditions = '', $table = null) {
		if (! $table)
			$table = $this->tName ();
		return $this->_getInstance ()->update ( $keyvalue, $table, $conditions );
	}

	/**
	 * 返回当前表名
	 * @param 表名 $name
	 * @return string 完整表名
	 */
	public function tName($name = null) {
		if ($name) {
			return DB_PREFIX . $name;
		} else {
			if (! $this->_tableName)
				//@todo 示完成
				throw new Error ( '表名不存在' );
			return DB_PREFIX . $this->_tableName;
		}
	}

	/**
	 * 将此表二维数组转换成一维数组
	 * @param $dataBase	一般为数据库里搜出来的二维数组
	 * @param $key			key值
	 * @param $value		value值
	 * @return array
	 */
	public static function getTtwoArrConvertOneArr($dataBase,$key='Id',$value) {
		if (empty($dataBase))return false;
		$dataList = array ();
		foreach ( $dataBase as $list ) {
			if ($key==null){
				array_push($dataList,$list[$value]);
			}else {
				$dataList [$list [$key]] = $list [$value];
			}
		}
		return $dataList;
	}

	/**
	 * @return int 返回上一次insert的id
	 */
	public function returnLastInsertId(){
		return $this->_getInstance()->returnLastInsertId();
	}

	/**
	 * 搜索表所有数据
	 * @return array
	 */
	public function findAll(){
		$sql="select * from {$this->tName()}";
		return $this->select($sql);
	}
	
	/**
	 * 替换/插入一条记录
	 * @param array $keyvalue
	 * @param string $table 表名
	 */
	public function replace($keyvalue,$table=null){
		if (! $table)
			$table = $this->tName ();
		return $this->_getInstance ()->replace ( $keyvalue, $table );
	}

	/**
	 * 跟据ID返回一条记录
	 * @param int $id
	 * @return array
	 */
	public function findById($id){
		$id=abs(intval($id));
		$sql="select * from {$this->tName()} where Id={$id}";
		return $this->select($sql,1);
	}
	
	/**
	 * 根据id删除记录
	 * @param int/array $id
	 * @return boolean
	 */
	public function delById($id){
		if (is_array($id)){//批量删除
			return $this->execute("delete from {$this->tName()} where Id in (".implode(',',$id).")");
		}else {//单一删除
			return $this->execute("delete from {$this->tName()} where Id={$id} limit 1");
		}
		
	}
	
	/**
	 * 插入多条数据
	 * @param array $arr 二维数据
	 */
	public function adds($keyvalue,$table=NULL){
		if (! $table)
			$table = $this->tName ();
		$first=reset($keyvalue);
		$key = implode ( ',', array_keys ( $first ) );
		
		$insertArr=array();
		foreach ($keyvalue as $list){
			foreach ( $list as $tempkey => $val ) {
				$val = mysql_escape_string ( $val );
				$list [$tempkey] = "'{$val}'";
			}
			$value = implode ( ',', $list );
			$value="($value)";
			array_push($insertArr,$value);
		}
		
		$insertValue=implode(',',$insertArr);
		$sql = "insert into {$table} ({$key}) values {$insertValue}";
		return $this->execute ( $sql );
	}
	
	/**
	 * 插入替换多条数据
	 * @param array $keyvalue
	 * @param string $table
	 */
	public function replaces($keyvalue,$table=NULL){
		if (! $table)
			$table = $this->tName ();
		$first=reset($keyvalue);
		$key = implode ( ',', array_keys ( $first ) );
		
		$insertArr=array();
		foreach ($keyvalue as $list){
			foreach ( $list as $tempkey => $val ) {
				$val = mysql_escape_string ( $val );
				$list [$tempkey] = "'{$val}'";
			}
			$value = implode ( ',', $list );
			$value="($value)";
			array_push($insertArr,$value);
		}
		
		$insertValue=implode(',',$insertArr);
		$sql = "replace into {$table} ({$key}) values {$insertValue}";
		return $this->execute ( $sql );
	}
	
	/**
	 * 返回一次动作执行的sql搜索条数
	 */
	public function getSqlSearchCount(){
		return $this->_getInstance()->getSqlSearchCount();
	}
	
	/**
	 * 返回表总数
	 * @return int 返回表总记录数
	 */
	public function findCount($conditions=null) {
		$sql = "select count(*) as total_num from {$this->tName()}";
		if ($conditions!=null)$sql.=" where {$conditions} ";
		$count = $this->select ( $sql );
		return $count [0] ['total_num'];
	}

}