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
		$num=count($dataList);
		//建立缓存时,让条记录的ID,成为这个数组的key值,方便后期优化
		for ($i=0;$i<$num;$i++){
			$dataList[$i]['vip_setup']=unserialize($dataList[$i]['vip_setup']);
			$tmpArr[$dataList[$i]['Id']]=$dataList[$i];
		}
		return $this->_addCache ( $tmpArr, $this->_cacheFile );
		
	}
	

}