<?php
/**
 * 申请模型
 * @author PHP-兴源
 */
class Model_ApplyType extends Model {
	protected $_tableName = 'apply_type';
	
	protected $_cacheApplyType= 'apply_type';
	
	protected $_cacheApplyTypeAll= 'apply_type_all';
	
	public function addType($a,$b){
		//return array('cal_local_method'=>'addType','params'=>array(5,8),'cal_local_object'=>'Model_ApplyType');
		$a = intval($a);
		$b = intval($b);
		return $a+$b;
		
		
	}
	
	public function createToCache(){
		$dataList = $this->select ( "select * from {$this->tName()} order by game_type, Id " );
		$CacheApplyType = array();
		$CacheApplyTypeAll = array();
		foreach ($dataList as $sub){
			$CacheApplyType[$sub['list_type']][$sub['game_type']][$sub['Id']] = $sub;
			$CacheApplyTypeAll[$sub['game_type']][$sub['Id']] = $sub;
		}
		$check1 = $this->_addCache ( $CacheApplyType, CACHE_DIR .'/'. $this->_cacheApplyType .'.cache.php' );
		$check2 = $this->_addCache ( $CacheApplyTypeAll, CACHE_DIR .'/'. $this->_cacheApplyTypeAll .'.cache.php' );
		if($check1 && $check2){
			return true;
		}else{
			return false;
		}
	}
	
	public function getApplyType($ListType=0,$gameId=0){		
		switch ($ListType){
			case 0:
				return false;
				break;
			case -1:
				//从缓存中拿到所有申请类型
				$ApplyType=$this->_getGlobalData($this->_cacheApplyTypeAll);
				break;
			default:
				//从缓存中拿到申请类型
				$ApplyType=$this->_getGlobalData($this->_cacheApplyType);
				$ApplyType = $ApplyType[$ListType];
				break;
		}
		
		if(empty($ApplyType)){
			return false;
			//$this->_utilMsg->showMsg('List Type Error',-1);
		}
		if($gameId){			
			$ApplyType = $ApplyType[$gameId];
		}else{
			$tmp = array();
			foreach($ApplyType as $sub){
				$tmp = array_merge($tmp,$sub);
			}
			$ApplyType = $tmp;
			unset($tmp);
		}
		
		if($ApplyType){
			return Model::getTtwoArrConvertOneArr($ApplyType,'Id','name');
		}else{
			return array();
		}
		
	}
	
	
	
}