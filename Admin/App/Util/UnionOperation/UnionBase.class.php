<?php
abstract class Util_UnionOperation_UnionBase extends Base { 
	
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	protected $_utilApiFrg;
	
	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	protected $_modelGameSerList;
	
	
	public function __call($fun,$arg){
		return array('status'=>0,'info'=>null,'data'=>null);
	}
}