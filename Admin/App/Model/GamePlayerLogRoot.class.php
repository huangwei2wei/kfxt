<?php
class Model_GamePlayerLogRoot extends Model {
	protected $_tableName = 'game_player_log_root'; //表名
	

	private $_cacheFile; //缓存文件
	

	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	public function getModelTableName(){
		return $this->_tableName;
	}
	
	/**
	 * 建立缓存
	 * @return void
	 */
	public function createToCache() {
		
		//删除本来的缓存
		$gameTypes=$this->_getGlobalData('game_type');
		foreach($gameTypes as $game){			
			$GameCacheFile = CACHE_DIR . "/{$this->_tableName}_{$game['Id']}.cache.php";
			if(file_exists($GameCacheFile)){
				unlink($GameCacheFile);
			}
		}
		
		$dataList = $this->select ( "select Id,game_type,rootid,rootname from {$this->tName()} order by game_type, Id " );
		$tmpAll = array ();
		$tmpGame = array();
		foreach ( $dataList as $sub ) {
			$tmpAll[$sub['rootid']] = $sub;
			$tmpGame[$sub['game_type']][$sub['rootid']] = $sub;
		}
		if(!$this->_addCache ( $tmpAll, $this->_cacheFile ))return false;
		foreach($tmpGame as $key => $val){
			if(!$this->_addCache ( $val, CACHE_DIR . "/{$this->_tableName}_{$key}.cache.php" ))return false;
		}
		return true;
	}

}