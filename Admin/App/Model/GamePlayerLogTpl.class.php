<?php
class Model_GamePlayerLogTpl extends Model {
	protected $_tableName = 'game_player_log_tpl'; //表名
	

	public function getModelTableName() {
		return $this->_tableName;
	}
	
	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	public function createToCache() {
		
		//删除本来的缓存
		$gameTypes = $this->_getGlobalData ( 'game_type' );
		foreach ( $gameTypes as $game ) {
			$GameCacheFile = CACHE_DIR . "/{$this->_tableName}_{$game['Id']}.cache.php";
			if (file_exists ( $GameCacheFile )) {
				unlink ( $GameCacheFile );
			}
		}
		
		$dataList = $this->select ( "select Id,game_type,rootid,typeid,typename,var_count,tpl from {$this->tName()} order by game_type, Id " );
		$tmpAll = array ();
		$tmpRoot = array ();
		foreach ( $dataList as $sub ) {
			$tmpAll [$sub ['game_type']] [$sub ['typeid']] = $sub;
			$tmpRoot [$sub ['game_type']] [$sub ['rootid']] [$sub ['typeid']] = $sub;
		}
		foreach ( $tmpAll as $GameKey => $type ) {
			if (! $this->_addCache ( $tmpAll[$GameKey], CACHE_DIR . "/{$this->_tableName}_{$GameKey}.cache.php" )) {
				return false;
			}
			foreach($tmpRoot[$GameKey] as $RootKey => $root)
			{
				if (! $this->_addCache ( $tmpRoot[$GameKey][$RootKey], CACHE_DIR . "/{$this->_tableName}_{$GameKey}_{$RootKey}.cache.php" )) {
					return false;
				}
			}
		}
		return true;
	}

}