<?php
/**
 * PDO数据库句柄
 * 
 * @author 程序开发组-朱磊
 * @version 1.0
 * @package Core.Model
 *
 */
class Model_Pdo {
	
	/**
	 * 数据库实例
	 *
	 * @var Model_Pdo
	 */
	private $_dbInstance = null;
	
	/**
	 * 单例封装模式
	 */
	public function __construct() {
		$this->_getInstance ();
	}
	
	/**
	 * 单例模式,返回句柄
	 *
	 * @return Object Model_Pdo
	 */
	private function _getInstance() {
		if ($this->_dbInstance == null) {
			try {
				$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
				$this->_dbInstance = new PDO ( $dsn, DB_USER, DB_PWD );
				$this->_dbInstance->query ( 'set names utf8' );
				return $this->_dbInstance;
			} catch ( Error_PdoException $e ) {
				die ( 'Error!: ' . $e->getMessage () . '<br>' );
			}
		} else {
			return $this->_dbInstance;
		}
		return FALSE;
	}
	
	/**
	 * 读操作，获取满足条件的数据
	 * @param string $sql
	 * @param int $number
	 * @return array $result
	 */
	public function select($sql, $number ) {
		try {
			$statement = $this->_dbInstance->query ( $sql );
			if (! $statement) {
				throw new Exception ( "<br/>errorSQL:<b style='color:f00'>{$sql}</b><br/>\n" );
			}
			if (1 == $number) {
				$result = $statement->fetch ( PDO::FETCH_ASSOC );
			}
			if ('' == $number) {
				$result = $statement->fetchAll ( PDO::FETCH_ASSOC );
			}
			if (1 < $number) {
				$result = $statement->fetchAll ( PDO::FETCH_ASSOC );
				is_array ( $result ) && array_splice ( $result, $number );
			}
			return $result;
		} catch ( PDOException $e ) {
			exit ( "<br/>error<br/>" . $e->getMessage () );
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
			$result = $this->_dbInstance->exec ( $sql );
			if (! $result) {
				throw new Exception ( "<br/>errorSQL:<b style='color:f00'>{$sql}</b><br/>\n" );
			}
			return $result;
		} catch ( Exception $e ) {
			exit ( "<br/>error<br/>" . $e->getMessage () );
		}
		return FALSE;
	}
	
	/**
	 * 新增一条记录,参数1:表名,参数2:更新的数据
	 *
	 * @param string $table
	 * @param array $keyvalue
	 * @return boolen
	 */
	public function add($table, $keyvalue) {
		$key = implode ( ',', array_keys ( $keyvalue ) );
		foreach ( $keyvalue as $tempkey => $val ) {
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
	public function update($table, $keyvalue, $conditions = '') {
		foreach ( $keyvalue as $key => $value ) {
			$keyvalue [$key] = "'$value'";
		}
		foreach ( $keyvalue as $key => $value ) {
			$update .= "$key=$value,";
		}
		if (substr ( $update, - 1 ) == ",")
			$update = substr ( $update, 0, strlen ( $update ) - 1 );
		$sql = "update $table set $update";
		if ($conditions != "") {
			$sql .= " where $conditions";
		} else {
			return false;
		}
		return $this->execute ( $sql );
	}
	
}
