<?php
class Model_Rooms extends Model {
	protected $_tableName='rooms';
	
	private $_cacheFile;
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	private $_utilRbac;
	
	
	public function __construct() {
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	public function getAllRoom(){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$gameTypeIds=$userClass->getUserGameTypeIds();
		$operatorIds=$userClass->getUserOperatorIds();
		if (!count($gameTypeIds) || !count($operatorIds))return array();	//如果没有设置游戏或是没有设置运营商的话,就退出
		$roomList=$this->select("select * from {$this->tName()} where game_type_id in (".implode(',',$gameTypeIds).") and operator_id in (".implode(',',$operatorIds).")");
		return $roomList;
	}
	
	public function getRoomByIds($roomIds){
		if(empty($roomIds)){
			return array();
		}
		if(is_array($roomIds)){
			$roomIds = implode(',',$roomIds);
		}else{
			$roomIds = strval($roomIds);
		}
		$roomList=$this->select("select * from {$this->tName()} where Id in ({$roomIds})");
		return $roomList;		
	}
	
	public function createCache(){
		$rooms	=	$this->getAllRoom();
		foreach($rooms as $_room){
			if($_room["autoreply"]){
				$_room["autoreply"]	=	unserialize($_room["autoreply"]);
			
			}
			$_rooms[$_room["Id"]]	=	$_room;
		}
		
		return $this->_addCache ( $_rooms, $this->_cacheFile );
	}
	
}