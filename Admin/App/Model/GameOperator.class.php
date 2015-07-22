<?php
class Model_GameOperator extends Model {
	protected $_tableName='game_operator';
	
	private $_cacheFile;
	
	
	public function __construct(){
		$this->_cacheFile = CACHE_DIR . "/{$this->_tableName}.cache.php";
	}
	
	/**
	 * 检测是否存在相同的记录
	 * @param int $gameTypeId
	 * @param int $operatorId
	 */
	private function _hasValue($gameTypeId,$operatorId){
		if ($this->select("select Id from {$this->tName()} where game_type_id={$gameTypeId} and operator_id={$operatorId}",1)){
			return true;
		}else {
			return false;
		}
	}
	
	public function add($keyValue,$table=null){
		if ($this->_hasValue($keyValue['game_type_id'],$keyValue['operator_id'])){
			return false;
		}else {
			return parent::add($keyValue,$table);
		}
	}
	
	/**
	 * 根据gameTypeId,operatorId查找指定的记录
	 * @param int $gameTypeId
	 * @param int $operatorId
	 */
	public function findByGidOId($gameTypeId,$operatorId){
		$dataList=$this->_getGlobalData('game_operator');
		foreach ($dataList as $value){
			if ($value['game_type_id']==$gameTypeId && $value['operator_id']==$operatorId){
				return $value;
			}
		}
		return false;
	}
	
	/**
	 * 根据游戏ID查找服务商记录
	 * @param int $gameTypeId 游戏ID
	 */
	public function findByGameTypeId($gameTypeId){
		$dataList=$this->select("select * from {$this->tName()} where game_type_id={$gameTypeId}");
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList, 'Id','operator_name');
		foreach($dataList as &$value){
			$value['operator_name']=$operatorList[$value['operator_id']];
		}
		return $dataList;
	}
	
	/**
	 * 返回二维数组，第一维键名：游戏id，第二维键名：游戏与运营商对应的id，第二维键值：运营商名
	 * @author xy
	 */
	public function getGameOperator($returnOptId = false){
		$dataList=$this->select("select * from {$this->tName()}");
		$game = array();
		$operatorIds=$this->_getGlobalData('operator_list');
		$operatorIds=Model::getTtwoArrConvertOneArr($operatorIds,'Id','operator_name');	//运营商
		if($returnOptId){
			foreach($dataList as $val){
				$gameId = $val['game_type_id'];
				$opeartorId = $val['operator_id'];
				if(isset($operatorIds[$opeartorId])){
					$game[$gameId][$opeartorId] = $operatorIds[$opeartorId];
				}				
			}
		}
		else{
			foreach($dataList as $val){
				$Id = $val['Id'];
				$gameId = $val['game_type_id'];
				$opeartorId = $val['operator_id'];
				if(isset($operatorIds[$opeartorId])){
					$game[$gameId][$Id] = $operatorIds[$opeartorId];
				}
			}
		}
		return $game;
	}
	
	
	/**
	 * 根据游戏与运营商对应的id，返回二维数组，第一元素：所属的游戏id的数组，第二元素：所属的运营商id数组
	 * @author xy
	 */
	public function getGmIdOptId($gameOperatorId,$fromData=NULL){
		$gameIds = array();
		$opreatorIds = array();
		if(is_array($gameOperatorId)){			
			$dataList = $fromData==NULL?$this->select("select * from {$this->tName()}"):$fromData;
			$dataList = $this->idForKey($dataList);
			foreach($gameOperatorId as $val){
				$gameIdTmp = $dataList[$val]['game_type_id'];
				if(!is_null($gameIdTmp))$gameIds[$gameIdTmp] = $gameIdTmp;
				$opreatorIdsTmp = $dataList[$val]['operator_id'];
				if(!is_null($opreatorIdsTmp))$opreatorIds[$opreatorIdsTmp] = $opreatorIdsTmp;
			}
		}
		return array('gameIds' =>$gameIds,'opreatorIds' =>$opreatorIds);
	}
	
	/**
	 * 把数组中以id为数组索引返回，id必须唯一
	 * @param array $arr
	 * @param string $key
	 */
	public function idForKey($arr,$id = 'Id'){
		$arr_key = array();
		foreach($arr as $key=>$val){
			if(empty($val[$id]))return array();
			$arr_key[$val[$id]] = $val;
		}
		return $arr_key;
	}
	
	/**
	 * 获得3维数组，游戏，运营商，服务器 的3层结构
	 */
	public function getGmOptSev(){
		$gameServerList=$this->_getGlobalData('gameser_list');
		unset($gameServerList[100],$gameServerList[200]);
		$data = array();
		foreach($gameServerList as $sub){
			$game_type_id = $sub['game_type_id'];
			$operator_id = $sub['operator_id'];
			$server_id = $sub['Id'];
			$server_name = $sub['server_name'];
			$data[$game_type_id][$operator_id][$server_id]=$server_name = $sub['server_name'];
		}
		unset($gameServerList);
		return $data;
	}
	
	/**
	 * 根据充值数得到VIP等级
	 * @param array $vipPay
	 * @param int $money 用户充值金币数
	 */
	public function getVipLevel($vipPay,$money = 0) {
		$money = abs ( intval ( $money ) );
		arsort ( $vipPay, SORT_NUMERIC );
		foreach ( $vipPay as $key => $value ) {
			if ($money >= $value)
				return $key;
		}
		return 0;
	}
	
	/**
	 * 超时时间
	 * @param array $vipTimeout
	 * @param int $vipLevel
	 */
	public function getTimeOut($vipTimeout,$vipLevel){
		return $vipTimeout[$vipLevel];
	}
	
	public function createCache(){
		$dataList=$this->findAll();
		$tmpArr=array();
		$gameOptArr =array();
		$operatorList=$this->_getGlobalData('operator_list');
		$num=count($dataList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$dataList[$i]['vip_setup']=unserialize($dataList[$i]['vip_setup']);
			if($dataList[$i]['ext']){
				$dataList[$i]['ext']=unserialize($dataList[$i]['ext']);
			}
			$tmpArr[$dataList[$i]['Id']]=$dataList[$i];
			$gameId = $dataList[$i]['game_type_id'];
			$operatorId = $dataList[$i]['operator_id'];
			
			$gameOptArr[$gameId][$operatorId] = $operatorList[$operatorId];
			if($dataList[$i]['url']){
				$gameOptArr[$gameId][$operatorId]['url'] = $dataList[$i]['url'];
			}
			if($dataList[$i]['ext']){
				$gameOptArr[$gameId][$operatorId]['ext'] = $dataList[$i]['ext'];
			}
		}
		$check = $this->_addCache ( $tmpArr, $this->_cacheFile );
		$operatorDir=CACHE_DIR.'/operator';
		if (!is_dir($operatorDir))mkdir($operatorDir,0755,true);
		//缓存每一个游戏的数据
		foreach($gameOptArr as $key =>$val){
			$check = $check && $this->_addCache ( $val, $operatorDir."/operator_list_{$key}.cache.php" );
		}
		return $check;
		
	}
	

}