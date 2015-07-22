<?php
/**
 * 问题类型表
 * @author PHP-朱磊
 *
 */
class Model_QuestionType extends Model {
	protected $_tableName = 'question_types';//表名
	
	private $_cacheFile;	//缓存文件路径
	
	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	/**
	 * 查找当前表所有数据,默认查找缓存
	 * @param boolean $isCache 是否强制生成缓存,默认查找缓存
	 * @return array
	 */
	public function findAll($isCache = true) {
			if ($isCache == true) {
			return $this->_getGlobalData($this->_tableName);
		} else {
			$sql = "select * from {$this->tName()}";
			$serverList = $this->select ( $sql );
			return $serverList;
		}
	}
	
	/**
	 * 建立缓存
	 * @return boolean
	 */
	public function createToCache() {
		$questionTypes = $this->findAll(false);
		$tmpArr=array();
		$num=count($questionTypes);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$questionTypes[$i]['form_table']=unserialize($questionTypes[$i]['form_table']);
			$tmpArr[$questionTypes[$i]['Id']]=$questionTypes[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
	}
	
	/**
	 * 根据id 查找数据,默认查找缓存
	 * @param int $id
	 * @param boolean $isCache
	 * @return array
	 */
	public function findById($id, $isCache = true) {
		if ($isCache == true) {
			$dataList = $this->_getGlobalData($this->_tableName);
			return $dataList[$id];
		} else {
			return parent::findById($id);
		}
	}
}