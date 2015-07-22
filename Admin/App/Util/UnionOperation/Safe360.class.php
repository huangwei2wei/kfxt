<?php
/**
 * 联合运营360
 * @author php-朱磊
 *
 */
class Util_UnionOperation_Safe360 extends Util_UnionOperation_UnionBase {
	
	const MARKING='360safe';
	
	public function __construct(){
		
	}
	
	/**
	 * 富人国获取礼包卡号动作 
	 * @todo 未完成...
	 */
	public function getLibaoCard_2(){
		exit();
		if (!$_REQUEST['marking'])return array('status'=>0,'info'=>null,'data'=>'not params marking');
		if (!$_REQUEST['card_id'])return array('status'=>0,'info'=>null,'data'=>'not params card_id');
		$cardId=Tools::coerceInt($_REQUEST['card_id']);
		$serverMarking=strtoupper($_REQUEST['marking']);
		
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$serverDetail=$this->_modelGameSerList->findByMarking(2,self::MARKING.$serverMarking);
		$serverId=$serverDetail['Id'];
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$get=array('c'=>'Card','a'=>'Create','doaction'=>'save');
		$post=array('TypeId'=>$cardId,'cardbyte'=>32,'Number'=>1,'TimeLimit'=>0);
		$this->_utilApiFrg->addHttp($serverId,$get,$post);
		$this->_utilApiFrg->send();
		$data=$this->_utilApiFrg->getResult();
		
	}
	
	
	
}