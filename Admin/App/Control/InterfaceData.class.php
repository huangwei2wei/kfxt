<?php
Tools::import('Control_Interface');
/**
 * 数据部接口
 * @author php-朱磊
 */
class Control_InterfaceData extends ApiInterface {
	
	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;
	
	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * sphinx工作状态
	 * @return boolean
	 */
	public function actionSphinxStatus(){
		$this->_utilFaqSearch=$this->_getGlobalData('Util_FaqSearch','object');
		$keyWords=$_REQUEST['keywords'];
		$gameTypeId=Tools::coerceInt(1);
		$page=Tools::coerceInt(1);
		if (!$page)$page=1;
		$pageSize=Tools::coerceInt(8);
		if (!$pageSize)$pageSize=8;
		$this->_utilFaqSearch->setFaqStatus(1);	//设置不需要官网的
		$this->_utilFaqSearch->setLimit($page,$pageSize);
		$data=$this->_utilFaqSearch->search('赢在大亨',$gameTypeId,$this->_curLang);
		if (count($data['data'])){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 礼包卡号统计
	 */
	public function actionLibaoCardState(){
		if (!$_REQUEST['game_type_id'])$this->_returnAjaxJson(array('status'=>0,'info'=>'not game_type_id','data'=>null));
		if (!$_REQUEST['server_mark'])$this->_returnAjaxJson(array('status'=>0,'info'=>'not server_mark','data'=>null));
		if (!$_REQUEST['type_id'])$this->_returnAjaxJson(array('status'=>0,'info'=>'not type_id','data'=>null));
		if (!$_REQUEST['time'])$this->_returnAjaxJson(array('status'=>0,'info'=>'not time','data'=>null));
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$serverDetail=$this->_modelGameSerList->findByMarking($_REQUEST['game_type_id'],$_REQUEST['server_mark']);
		if (!$serverDetail)$this->_returnAjaxJson(array('status'=>0,'info'=>'game server non-existent'));
		$cardId=$_REQUEST['type_id'];
		$time=$_REQUEST['time'];
		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array('c'=>'Card','a'=>'ImportCard','doaction'=>'state');
		$postArr=array('type_id'=>$cardId,'DateTime'=>$time);
		$this->_utilApiFrg->addHttp($serverDetail['Id'],$getArr,$postArr);
		$this->_utilApiFrg->send();
		$data=$this->_utilApiFrg->getResult();
		if ($data){
			$this->_returnAjaxJson(array('status'=>1,'info'=>null,'data'=>$data));
		}else {
			$this->_returnAjaxJson(array('status'=>0,'info'=>'game server error','data'=>null));
		}
	}
	
	
}