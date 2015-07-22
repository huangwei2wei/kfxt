<?php
/**
 * 系统配置表
 * @author Administrator
 *
 */
class Model_Sysconfig extends Model {
	protected $_tableName = 'sysconfig';	//表名
	
	public function __construct() {
	
	}
	
	/**
	 * 查找所有记录
	 * @return array
	 */
	public function findAll() {
		$sql = "select * from {$this->tName()}";
		return $this->select ( $sql );
	}
	
	/**
	 * 跟据ID查找记录
	 * @param int $id
	 * @return array
	 */
	public function findById($id) {
		$sql = "select * from {$this->tName()} where Id={$id}";
		return $this->select ( $sql, 1 );
	}
	
	/**
	 * 生成缓存文件
	 * @param string $configName 生成要缓存变量
	 */
	public function createToCache($configName = null) {
		$this->_cacheDir();
		if ($configName == null) {
			$dataList = $this->findAll ();
			foreach ( $dataList as &$value ) {
				$arrCn=$arrEn=array();
				$arrCn = unserialize ( $value ['config_value'] );
				$arrEn = unserialize ( $value ['config_value_2'] );
				$filePathCn = CACHE_DIR . "/sys_lang/1/{$value['config_name']}.cache.php";
				$filePathEn = CACHE_DIR . "/sys_lang/2/{$value['config_name']}.cache.php";
				$this->_addCache ( $arrCn, $filePathCn );
				$this->_addCache ( $arrEn, $filePathEn );
			}
		} else {
			$sql = "select * from {$this->tName()} where config_name='{$configName}'";
			$dataList = $this->select ( $sql, 1 );
			if (! $dataList)
				return false;
			$arrCn = unserialize ( $value ['config_value'] );
			$arrEn = unserialize ( $value ['config_value_2'] );
			$filePathCn = CACHE_DIR . "/sys_lang/1/{$value['config_name']}.cache.php";
			$filePathEn = CACHE_DIR . "/sys_lang/2/{$value['config_name']}.cache.php";
			$this->_addCache ( $arrCn, $filePathCn );
			$this->_addCache ( $arrEn, $filePathEn );
		}
	}
	
	/**
	 * 检测缓存文件是否存在
	 */
	private function _cacheDir(){
		$cacheDir=array(CACHE_DIR . "/sys_lang/1/",CACHE_DIR . "/sys_lang/2/",);
		foreach ($cacheDir as $dir){
			if (!file_exists($dir))mkdir($dir,0777,true);
		}
	}
	
	/**
	 * 返回指定缓存信息
	 * @param string $configName 缓存唯一标识
	 * @return array
	 */
	public function getValueToCache($configName) {
		if (! $configName)
			return false;
		return $this->_getGlobalData($configName);	//新增全局注册函数,兼容
	}
}