<?php
class Model_Department extends Model {
	protected $_tableName = 'department';
	
	private $_cacheFile;
	
	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	/**
	 * 建立缓存
	 */
	public function createCache() {
		$dataList = $this->findAll ();
		$tmpArr=array();
		$num=count($dataList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$tmpArr[$dataList[$i]['Id']]=$dataList[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
	}
}