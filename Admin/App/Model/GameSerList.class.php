<?php
/**
 * 游戏服务器列表
 * @author PHP-朱磊
 *
 */
class Model_GameSerList extends Model {
	
	protected $_tableName = 'gameser_list'; //表名
	
	private $_cacheFile; //缓存文件
	
	private $_pagesize	=	20;
	
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
	 * 建立缓存
	 * @return void
	 */
	public function createToCache() {
		$serverList = $this->select ( "select * from {$this->tName()} where server_url !='' order by ordinal,Id " );
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameTypeList=$this->_getGlobalData('game_type');
		$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
		$tmpArr=array();
		//$num=count($serverList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		$gameSerCacheList=array();
		$gameSerTimerList=array();
		$tmp = array();
		foreach($serverList as $server){
			$gameId = intval($server['game_type_id']);
			$serverId = intval($server['Id']);
			if($server['timer']){
				$tmp[$gameId]['chunk'] = intval($tmp[$gameId]['chunk']);
				$tmp[$gameId]['chunkNo'] = intval($tmp[$gameId]['chunkNo']);
				$gameSerTimerList["{$gameId}_{$tmp[$gameId]['chunkNo']}"][$serverId] = $server;
				if($tmp[$gameId]['chunk']%30 == 29){
					$tmp[$gameId]['chunkNo']++;
				}
				$tmp[$gameId]['chunk']++;
			}
			$gameSerCacheList[$gameId][$serverId]=$server;
			$server['full_name']="{$server['server_name']}({$operatorList[$server['operator_id']]})[{$gameTypeList[$server['game_type_id']]}]";
			$tmpArr[$server['Id']]=$server;
		}
//		for ($i=0;$i<$num;$i++){
//			//将服务器按游戏分组
//			if($serverList[$i]['timer']){
//				$gameId = $serverList[$i]['game_type_id'];
//				$serverId = $serverList[$i]['Id'];
//				//$gameSerTimerList[$gameId][$serverId]=$serverList[$i];
//				//$gameSerTimerList[$chunkNo][$serverList[$i]['Id']]=$serverList[$i];
//				$tmp[$gameId]['chunk'] = intval($tmp[$gameId]['chunk']);
//				$tmp[$gameId]['chunkNo'] = intval($tmp[$gameId]['chunkNo']);
//				if($tmp[$gameId]['chunk']%30 == 29){
//					$tmp[$gameId]['chunkNo']++;
//				}
//				$gameSerTimerList["{$gameId}_{$tmp[$gameId]['chunkNo']}"][$serverId] = $serverList[$i];
//				
//				$tmp[$gameId]['chunk']++;
//			}
//			$gameSerCacheList[$serverList[$i]['game_type_id']][$serverList[$i]['Id']]=$serverList[$i];
//			$serverList[$i]['full_name']="{$serverList[$i]['server_name']}({$operatorList[$serverList[$i]['operator_id']]})[{$gameTypeList[$serverList[$i]['game_type_id']]}]";
//			$tmpArr[$serverList[$i]['Id']]=$serverList[$i];
//		}
		$serverDir=CACHE_DIR.'/server';
		if (!is_dir($serverDir))mkdir($serverDir,0755,true);
		$gameTimer = array();
		foreach($gameSerTimerList as $key =>$cache){
			$gameTimer[] = $key;
			$this->_addCache($cache,"{$serverDir}/server_timer_{$key}.cache.php");//有定时器的服务器缓存（分游戏）
		}
		//更新需要定时扫描的游戏
		if($gameTimer){
			$this->_gameTimerUpdate($gameTimer);
		}		
		foreach ($gameSerCacheList as $key=>$cache){
			$this->_addCache($cache,"{$serverDir}/server_list_{$key}.cache.php");//服务器缓存（分游戏）
		}
		
		return $this->_addCache ( $tmpArr, $this->_cacheFile );//全部服务器缓存
	}
	
	/**
	 * 更新需要定时扫描的游戏
	 * @param unknown_type $gameTimer
	 */
	private function _gameTimerUpdate($gameTimer){
		$_modelGameTimer = $this->_getGlobalData('Model_GameTimer','object');
		$_modelGameTimer->updateAll($gameTimer);
	}
	
	/**
	 * 通过标识来查找服务器
	 */
	public function findByMarking($gameTypeId,$marking,$serverName=NULL){
		$gameTypeId = intval($gameTypeId);
		$marking = trim($marking);
		$serverList=$this->select("select * from {$this->tName()} where game_type_id={$gameTypeId} and marking='{$marking}'",1);
		if ($serverList)return $serverList;//如果有服务器列表就直接返回
		$serverResult=$this->_getOperatorIdByMark($gameTypeId,$marking);
		if (!$serverResult)return false;
		$addArr=array();
		$addArr['game_type_id']=$gameTypeId;
		$addArr['marking']=$marking;
		$addArr['time_zone']=0;
		$addArr['server_name']=$serverResult['server'] . $serverName;
		$addArr['operator_id']=$serverResult['operator_id'];
		$addArr['server_url']=$serverResult['url'];
		$this->add($addArr);
		$addArr['Id']=$this->returnLastInsertId();
		$this->createToCache();
		return $addArr;
	}
	
	public function _getOperatorIdByMark($gameTypeId,$marking){
		if (strpos($marking,'|')===false)return false;
		list($mark,$server)=explode('|',$marking);
		Tools::import('Cache_OperatorSetup');
		switch ($gameTypeId){
			case 1 :
				$operatorList	=	Cache_OperatorSetup::getBtoConf();
				break;
			case 2 :
				$operatorList	=	Cache_OperatorSetup::getFrgConf();
				break;
		}
		if (!isset($operatorList))return false;
		foreach ($operatorList as $key=>$list){
			if ($list['mark']==$mark)return array('operator_id'=>$key,'url'=>str_replace('{$var}',Tools::getNum($server),$list['url']),'server'=>$server);
		}
		return false;
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

	/**
	 * 通过game_type_id和operator_id来查找服务器列表 
	 * @param int $gameTypeId
	 * @param int $operatorId
	 */
	public function findByGameIdOperatorId($gameTypeId,$operatorId){
		$serverList = $this->findAll ();
		$findGamelist = array ();
		foreach ( $serverList as $key=>$value ) {
			if ($operatorId == $value ['operator_id'] && $gameTypeId==$value['game_type_id'])
				$findGamelist[$key]=$value;
		}
		return $findGamelist;
	}
	
	/**
	 * 根据游戏id 和 运营商id获取相关的房间id字符串
	 * @param array $gameTypeIds
	 * @param array $operatorIds
	 */
	public function getAllRoomIds($gameTypeIds,$operatorIds){
		if (!count($gameTypeIds) || !count($operatorIds))return '';	//如果没有设置游戏或是没有设置运营商的话,就退出
		$serverStr=$this->select("SELECT GROUP_CONCAT(DISTINCT room_id) as room_ids FROM {$this->tName()} WHERE room_id!=0 AND game_type_id IN (".implode(',',$gameTypeIds).") AND operator_id IN (".implode(',',$operatorIds).")");
		if($serverStr){
			$serverStr = array_shift($serverStr);
			return $serverStr['room_ids'];
		}else{
			return '';
		}
	}
	
	public function getSqlSearch($sqldata){
		$this->_loadCore ( 'Help_SqlSearch' );
		$_modelOperatorList = $this->_getGlobalData ( 'Model_OperatorList', 'object' );
		$modelOperatorList	= $_modelOperatorList->findAll ();
		$operatorList 		= $this->_getGlobalData('operator/operator_list_'.$sqldata['game_type']);
		$operatorList 		= $_modelOperatorList->getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$helpSqlSearch 		= new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ($this->tName());
		//$helpSqlSearch->set_conditions ( 'Id !=100 and Id !=200' );
		$helpSqlSearch->set_conditions("game_type_id=".$sqldata['game_type']);
		if($sqldata['operator_id']){
			$helpSqlSearch->set_conditions("operator_id={$sqldata['operator_id']}");
		}
		if($sqldata['timer']){
			$helpSqlSearch->set_conditions('timer = '.$sqldata['timer']);
		}
		if($sqldata['Id']){
			$helpSqlSearch->set_conditions('Id='.intval($sqldata['Id']));
		}
		if($sqldata['marking']){
			$helpSqlSearch->set_conditions("marking like '%{$sqldata['marking']}%'");
		}
		if($sqldata['server_url']){
			$helpSqlSearch->set_conditions("server_url like '%{$sqldata['server_url']}%'");
		}
		if ($sqldata['server_name']){
			$helpSqlSearch->set_conditions("server_name like '%{$sqldata['server_name']}%'");
		}
		$helpSqlSearch->set_orderBy('operator_id,ordinal,Id');
		$helpSqlSearch->setPageLimit ($sqldata['page']);
		
		$conditions 		= 	$helpSqlSearch->get_conditions();
		$sql 				= 	$helpSqlSearch->createSql();
		$serverList			=	$this->select($sql);
		$_modelSysconfig 	= $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$gameTypeList 		= Model::getTtwoArrConvertOneArr ( $_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		
		if ($serverList) {
			foreach ( $serverList as &$value ) {
				$value ['word_game_type'] 		= $gameTypeList [$value ['game_type_id']];
				$value ['word_operator_name'] 	= $modelOperatorList [$value ['operator_id']]['operator_name'];
				$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'edit','zp'=>$sqldata['zp']) );
				$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'del' ,'zp'=>$sqldata['zp'])  );
			}
			$this->_loadCore ( 'Help_Page' );
			$helpPage 	= new Help_Page ( array ('total' => $this->findCount ( $conditions ), 'perpage' => $this->_pagesize ) );
			$pageshow	=	$helpPage->show();
		}
		$operatorList['']=Tools::getLang('ALL','Common');
		$data	=	array(
			'serverList'	=>	$serverList,
			'pageBox'		=>	$pageshow,
			'operatorList'	=>	$operatorList,
		);
		return $data;
	}
	
	public function addServerlist($data){
		if ($data['batch_add']){
			$start=Tools::coerceInt($data['start']);
			$end=Tools::coerceInt($data['end']);
			$addArrs=array();
			for ($i=$start;$i<=$end;$i++){
				$addArrs[$i]=array(
					'game_type_id'=>intval($data['game_type']),
					'operator_id'=>intval($data['operator_id']),
					'ordinal'=>$i,
					'server_name'=>str_replace('{$var}',$i,$data['server_name']),
					'marking'=>str_replace('{$var}',$i,$data['marking']),
					'server_url'=>str_replace('{$var}',$i,$data['server_url']),
					'timezone' => trim($data ['timezone']),
					'time_zone'=>trim($data['time_zone']),
					'timer'=>intval($data['timer']),
					'data_url'=>str_replace('{$var}',$i,$data['data_url']),
					'isCenter'=>intval($data['isCenter']),
				);
			}
			$reuslt=$this->adds($addArrs);
		}else {
			$addArr = array (
				'game_type_id' => intval($data ['game_type']), 
				'operator_id' => intval($data ['operator_id']), 
				'ordinal'=>intval($data['ordinal']),
				'server_name' => trim($data ['server_name']), 
				'marking' => trim($data ['marking']), 
				'server_url' => trim($data ['server_url']), 
				'timezone' => trim($data ['timezone']),
				'time_zone'=>trim($data['time_zone']),
				'timer'=>intval($data['timer']),
				'data_url'=>trim($data['data_url']),
				'isCenter'=>intval($data['isCenter']),
			);
			$reuslt=$this->add($addArr);
		}
		if($reuslt){
			return true;
		}else{
			return false;
		}
	}
	public function updateServerlist($updatedata){
	
		$ext= array(
			'db_host'=>trim($updatedata ['db_host']),
			'db_name'=>trim($updatedata['db_name']),
			'db_user'=>trim($updatedata ['db_user']),
			'db_pwd'=>trim($updatedata ['db_pwd']),
			'db_port'=>trim($updatedata ['db_port']),
		);
		foreach($ext as $key => $value){
			if(!$value){
				unset($ext[$key]);
			}
		}
		$data = array ('game_type_id' => intval($updatedata ['game_type']),
						'operator_id' => intval($updatedata ['operator_id']),
						'ordinal'=>intval($updatedata['ordinal']),
						'server_name' => trim($updatedata ['server_name']),
						'marking' => trim($updatedata ['marking']),
						'server_url' => trim($updatedata ['server_url']),
						'time_zone' => trim($updatedata ['time_zone']),
						'timezone' => trim($updatedata ['timezone']),
						'timer'=>intval($updatedata['timer']),
						'data_url'=>trim($updatedata['data_url']),
						'isCenter'=>intval($updatedata['isCenter']),
		);
		if($ext){
			$data['ext'] = serialize($ext);
		}
		if($this->update($data,"Id={$updatedata['Id']}" )){
			return true;
		}else{
			return false;
		}
	}
}