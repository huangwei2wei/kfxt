<?php
/**
 * 游戏服务器列表
 * @author PHP-朱磊
 *
 */
class Model_GameSerList extends Model {
	
	protected $_tableName = 'gameser_list'; //表名
	
	
	/**
	 * Util_ApiSftx
	 * @var Util_ApiSftx
	 */
	private $_utilApiSftx;

	private $_cacheFile; //缓存文件
	

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
			$this->_registerGlobalData($this->_tableName,$serverList);
			return $serverList;
		}
	}
	
	/**
	 * 编辑
	 */
	public function edit($postArr){
//		if ($_POST['modify_mark']){
//			$this->_utilApiSftx=$this->_getGlobalData('Util_ApiSftx','object');
//		}
	}
	
	/**
	 * 建立缓存
	 * @return void
	 */
	public function createToCache() {
		$serverList = $this->select ( "select * from {$this->tName()} order by game_type_id" );
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameTypeList=$this->_getGlobalData('game_type');
		$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
		$tmpArr=array();
		$num=count($serverList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$serverList[$i]['full_name']="{$serverList[$i]['server_name']}({$operatorList[$serverList[$i]['operator_id']]})[{$gameTypeList[$serverList[$i]['game_type_id']]}]";
			$tmpArr[$serverList[$i]['Id']]=$serverList[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
	}
	
	/**
	 * 通过标识来查找服务器
	 */
	public function findByMarking($gameTypeId,$marking){
		return $this->select("select * from {$this->tName()} where game_type_id={$gameTypeId} and marking='{$marking}'",1);
	}
	
	/**
	 * 根据游戏类型id查找服务器列表
	 * @param int $gameTypeId 游戏类型ID
	 * @return array
	 */
	public function findByGameTypeId($gameTypeId) {
		$serverList = $this->findAll ();
		$findGameList = array ();
		foreach ( $serverList as $value ) {
			if ($gameTypeId == $value ['game_type_id'])
				array_push ( $findGameList, $value );
		}
		return $findGameList;
	}
	
	/**
	 * 根据ID查找记录
	 * @param int $id
	 */
	public function findById($id,$isCache=TRUE){
		if ($isCache){
			$serverList=$this->_getGlobalData('gameser_list');
			return $serverList[$id];
		}else {
			return parent::findById($id);
		}
	}
	
	/**
	 * 查找所有roomid为0的
	 */
	public function findNoRoomId(){
		return $this->select("select * from {$this->tName()} where room_id=0");
	}
	
	public function findByRoomId($id,$isCache=TRUE){
		if ($isCache){
			$gameServerList=$this->_getGlobalData($this->_tableName);
			$arr=array();
			foreach ($gameServerList as $list){
				if ($list['room_id']==$id)array_push($arr,$list);
			}
			return $arr;
		}else {
			return $this->select("select * from {$this->tName()} where room_id={$id} order by game_type_id,operator_id");
		}
	}
	
	/**
	 * 查找指定房间登录用户可显示的服务器列表(缓存搜索)
	 */
	public function findByRoomUserList($roomId,Object_UserInfo $userClass){
		$gameSerList=$this->_getGlobalData($this->_tableName);
		$arr=array();
		foreach ($userClass['_operatorIds'] as $operatorIds){
			foreach ($gameSerList as $list){
				if ($list['room_id']==$roomId && $list['game_type_id']==$operatorIds['game_type_id'] && $list['operator_id']==$operatorIds['operator_id']){
					array_push($arr,$list);
				}
			}
		}
		return $arr;
	}
	
	/**
	 * 根据运营商id查找服务器列表
	 * @param int $operatingId
	 * @return array
	 */
	public function findByOperatingId($operatingId) {
		$serverList = $this->findAll ();
		$findGamelist = array ();
		foreach ( $serverList as $value ) {
			if ($operatingId == $value ['operator_id'])
				array_push ( $findGamelist, $value );
		}
		return $findGamelist;
	}

}