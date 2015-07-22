<?php
class Model_Menu extends Model {
	protected $_tableName = 'menu';
	
	private $_cacheFile;
	
	private $_cacheIndexFile;
	
	public function __construct() {
		$this->_cacheIndexFile=CACHE_DIR . "/{$this->_tableName}Index.cache.php";
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	/**
	 * 通过父类id查找子列表
	 * @param int $parent_id
	 */
	public function findByParentIdToChildList($parent_id, $status = false) {
		$sql = "select * from {$this->tName()} where parent_id={$parent_id} ";
		if ($status)
			$sql .= ' and status=1';
		$sql.=' order by sort';
		return $this->select ( $sql );
	}
	
	/**
	 * 查找所有主菜单项
	 */
	public function findByMainList($status = false) {
		$sql = "select * from {$this->tName()} where parent_id=0 order by sort";
		if ($status)
			$sql .= " and status=1";
		return $this->select ( $sql );
	}
	
	/**
	 * 检测是否有相同的菜单value
	 * @param int $value
	 */
	private function _hasValue($value) {
		return $this->select ( "select value from {$this->tName()} where value='{$value}'", 1 );
	}
	
	public function add($keyValue, $table = null) {
		if ($this->_hasValue ( $keyValue ['value'] )) {
			return false;
		} else {
			return parent::add ( $keyValue );
		}
	}
	
	/**
	 * 创建索引cache
	 */
	private function _createIndexCache(){
		$dataList=$this->findAll();
		$num=count($dataList);
		$newArr=array();
		for ($i=0;$i<$num;$i++){
			$newArr[$dataList[$i]['value']]=$dataList[$i];
		}
		return $this->_addCache($newArr,$this->_cacheIndexFile);
	}
	

	/**
	 * 创建缓存
	 */
	public function createCache() {
		$dataList = $this->findByMainList ( false );
		foreach ( $dataList as &$value ) {
			$value ['actions'] = $this->findByParentIdToChildList ( $value ['Id'], false );
			if ($value ['actions']) { //如果有值,将删除不必要的参数
				foreach ( $value ['actions'] as &$childValue ) {
					unset ( $childValue ['Id'] );
					unset ( $childValue ['parent_id'] );
				}
			}
			unset ( $value ['Id'] );
			unset ( $value ['parent_id'] );
		}
		$this->_createIndexCache();
		return $this->_addCache ( $dataList, $this->_cacheFile );
	}
	
}