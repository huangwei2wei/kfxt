<?php
/**
 * 运营商列表
 * @author PHP-朱磊
 *
 */
class Model_OperatorList extends Model {
	protected $_tableName='operator_list';	//表名
	
	/**
	 * 缓存文件
	 * @param string 文件路径
	 */
	private $_cacheFile;
	
	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	/**
	 * 查找当前表所有数据,默认查找缓存
	 * @param boolean $isCache 是否强制生成缓存,默认查找缓存
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
		$serverList = $this->select ( "select * from {$this->tName()}" );
		$tmpArr=array();
		$num=count($serverList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$tmpArr[$serverList[$i]['Id']]=$serverList[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
	}
	
	/**
	 * 根据游戏类型id查找运营商列表
	 * @param int $gameTypeId
	 * @return array
	 */
	public function findByGameTypeId($gameTypeId) {
		$serverList = $this->findAll ();
		$findOperatorList = array ();
		foreach ( $serverList as $value ) {
			if ($gameTypeId == $value ['game_type_id'])
				array_push ( $findOperatorList, $value );
		}
		return $findOperatorList;
	}
	
	/**
	 * 根据id查找指定的id
	 * @param int $id
	 * @param boolean $isCache
	 * @return array
	 */
	public function findById($id,$isCache=true){
		if ($isCache==true){
			$serverList=$this->_getGlobalData('operator_list');
			return $serverList[$id];
		}else {
			return parent::findById($id);
		}

	}

	/**
	 * 删除运营商,并且删除运营商下面所有的服务器列表
	 * @param int $id
	 */
	public function deleteByIdAndServierList($id){
		$this->execute("delete from {$this->tName()} where Id={$id}");
		$this->execute("delete from {$this->tName('gameser_list')} where operator_id={$id}");
	}
	
	
}