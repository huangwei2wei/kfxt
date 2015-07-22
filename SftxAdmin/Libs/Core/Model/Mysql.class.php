<?php
/**
 * mysql
 *
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core.Model
 *
 */
class Model_Mysql extends Base {
	
	/**
	 * 数据句柄
	 * @param result
	 */
	private $_dbInstance = null;
	
	private static $_selectSql=array();
	
	/**
	 * 单例封装模式
	 */
	public function __construct() {
		$this->_loadCore ( 'Error_DbException' );
		$this->_connect ();
	}
	
	/**
	 * 单例模式,返回句柄
	 * @return Model_Mysql
	 */
	private function _connect() {
		try {
			$this->_dbInstance = mysql_connect ( DB_HOST, DB_USER, DB_PWD );
			mysql_select_db ( DB_NAME, $this->_dbInstance );
			mysql_query ( 'SET NAMES \'utf8\'', $this->_dbInstance );
			return $this;
		} catch ( Error_DbException $e ) {
			$e->displayMsg ();
		}
	}
	
	/**
	 * 检测重复搜索,避免一次动作重复搜索同一条sql
	 */
	private function  _checkRepeatSql($sql=null){
		if ($sql===null)return false;
		$sql=md5($sql);
		if (array_key_exists($sql,self::$_selectSql)){
			return self::$_selectSql[$sql];
		}
		return false;
	}
	
	/**
	 * 加入此条记录
	 * @param string $sql
	 * @param array $raw
	 */
	private function _addExistSql($sql,$raw){
		self::$_selectSql[md5($sql)]=$raw;
	}
	
	/**
	 * 返回sql搜索的次数
	 */
	public function getSqlSearchCount(){
		return count(self::$_selectSql);
	}
	
	/**
	 * 读操作，获取满足条件的数据
	 * @param string $sql
	 * @param int $number
	 * @return array $result
	 */
	public function select($sql, $number) {
		try {
			if ($number == 1)
				$sql .= ' limit 1';
			
			#------拦截多次重复搜索一条相同的sql------#
			$raw=$this->_checkRepeatSql($sql); 
			if ($raw!==false)return $raw;
			#------拦截多次重复搜索一条相同的sql------#
			
			$result = mysql_query ( $sql, $this->_dbInstance );
			if (! $result)
				throw new Error_DbException ( "<br/>errorSQL:<b style='color:f00'>{$sql}</b><br/>\n" );
			$arr = array ();
			if ($number == 1) {
				$arr = mysql_fetch_assoc ( $result );
			} else {
				while ( $row = mysql_fetch_assoc ( $result ) ) {
					array_push ( $arr, $row );
				}
			}
			$this->_addExistSql($sql,$arr);
			return $arr;
		} catch ( Error_DbException $e ) {
			$e->displayMsg ();
		}
		return false;
	}
	
	/**
	 * 写操作，插入或者更新
	 * @param string $sql
	 * @return int $result
	 */
	public function execute($sql) {
		try {
			$result = mysql_query ( $sql, $this->_dbInstance );
			if (! $result) {
				throw new Error_DbException ( "<br/>errorSQL:<b style='color:f00'>{$sql}</b><br/>\n" );
			}
			return $result;
		} catch ( Error_DbException $e ) {
			$e->displayMsg ();
		}
		return FALSE;
	}
	
	/**
	 * 替换一条记录,参数1:表名,参数2:更新的数据
	 *
	 * @param string $table
	 * @param array $keyvalue
	 * @return boolen
	 */
	public function replace($keyvalue, $table) {
		$key = implode ( ',', array_keys ( $keyvalue ) );
		foreach ( $keyvalue as $tempkey => $val ) {
			$val = mysql_escape_string ( $val );
			$keyvalue [$tempkey] = "'{$val}'";
		}
		$value = implode ( ',', $keyvalue );
		$sql = "replace into {$table} ({$key}) values ({$value})";
		return $this->execute ( $sql );
	}
	
	/**
	 * 新增一条记录,参数1:表名,参数2:更新的数据
	 *
	 * @param string $table
	 * @param array $keyvalue
	 * @return boolen
	 */
	public function add($keyvalue, $table) {
		$key = implode ( ',', array_keys ( $keyvalue ) );
		foreach ( $keyvalue as $tempkey => $val ) {
			$val = mysql_escape_string ( $val );
			$keyvalue [$tempkey] = "'{$val}'";
		}
		$value = implode ( ',', $keyvalue );
		
		$sql = "insert into {$table} ({$key}) values ({$value})";
		return $this->execute ( $sql );
	}
	
	/**
	 * 更新一条记录
	 *
	 * @param string $table
	 * @param array $keyvalue
	 * @param string $conditions
	 * @return booblen
	 */
	public function update($keyvalue, $table, $conditions = '') {
		foreach ( $keyvalue as $key => $value ) {
			$value = mysql_escape_string ( $value );
			if (strstr($value,$key)) {
				$keyvalue [$key] = "$value";
			} else {
				$keyvalue [$key] = "'$value'";
			}
		}
		foreach ( $keyvalue as $key => $value ) {
			$update .= "`$key`=$value,";
		}
		if (substr ( $update, - 1 ) == ",")
			$update = substr ( $update, 0, strlen ( $update ) - 1 );
		$sql = "update $table set $update";
		if ($conditions != "") {
			$sql .= " where $conditions";
		}
		return $this->execute ( $sql );
	}
	
	public function returnLastInsertId() {
		return mysql_insert_id ();
	}

}
