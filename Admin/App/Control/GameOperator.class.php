<?php
/**
 * 游戏列表控制器
 * @author PHP-朱磊
 *
 */
class Control_GameOperator extends Control {

	/**
	 * Model_OperatorList
	 * @var Model_OperatorList
	 */
	private $_modelOperatorList;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

	/**
	 * Model_GameOperator
	 * @var Model_GameOperator
	 */
	private $_modelGameOperator;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_modelOperatorList = $this->_getGlobalData('Model_OperatorList','object');
		$this->_modelSysconfig =$this->_getGlobalData('Model_Sysconfig','object');
		$this->_utilMsg = $this->_getGlobalData('Util_Msg','object');
	}

	private function _createUrl() {
		$this->_url ['GameOperator_Edit'] = Tools::url ( CONTROL, 'Operator',array('doaction'=>'edit') );
		$this->_url ['GameOperator_Add'] = Tools::url ( CONTROL, 'Operator',array('doaction'=>'add') );
		$this->_url ['GameOperator_CreateCache'] = Tools::url ( CONTROL, 'Operator',array('doaction'=>'cache') );
		
		$this->_url ['GameOperator_VipAdd'] =Tools::url(CONTROL,'Vip',array('doaction'=>'vipAdd'));
		$this->_url ['GameOperator_SetupGameOperator']=Tools::url(CONTROL,'Vip',array('doaction'=>'vipSetup'));
		$this->_url ['GameOperator_CreateCacheGameOperator']=Tools::url(CONTROL,'Vip',array('doaction'=>'vipCache'));
		$this->_url ['GameOperator_OperatorExtParam']=Tools::url(CONTROL,'GameOperatorSet',array('doaction'=>'extParam'));
		$this->_url ['GameOperator_AddGameOperator'] =Tools::url(CONTROL,'GameOperatorSet',array('doaction'=>'addGameOperator'));
		
		$this->_view->assign ( 'url', $this->_url );
	}

	/**
	 * 游戏运营商设置
	 */
	public function actionOperator(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_add();
				return ;
			}
			case 'edit' :{
				$this->_edit();
				return ;
			}
			case 'del' :{
				$this->_del();
				return ;
			}
			case 'cache' :{
				$this->_createCache();
				return ;
			}
			default:{
				$this->_index();
				return ;
			}
		}
	}

	/**
	 * 运营商列表
	 */
	private function _index() {
		$operatorList = $this->_modelOperatorList->findAll ( false );
		foreach ( $operatorList as &$value ) {
			$value ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'edit' ) );
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $value ['Id'],'doaction'=>'del' ) );
		}
		$this->_view->assign ( 'dataList', $operatorList );
		$this->_utilMsg->createNavBar();
		$this->_view->set_tpl(array('body'=>'GameOperator/Index.html'));
		$this->_view->display ();
	}

	/**
	 * 添加运营商
	 */
	private function _add() {
		if ($this->_isPost ()) {
			$addArr = array ('operator_name' => $_POST ['operator_name'] );
			if ($this->_modelOperatorList->add ( $addArr )) {
				$this->_modelOperatorList->createToCache();
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('ADD_ERROR','Common'), - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'GameOperator/Add.html'));
			$this->_view->display ();
		}

	}

	private function _createCache() {
		if ($this->_modelOperatorList->createToCache ()) {
			$this->_utilMsg->showMsg ( Tools::getLang('CACHE_SUCCERR','Common'), 1 );
		} else {
			$this->_utilMsg->showMsg ( Tools::getLang('CACHE_ERROR','Common'), - 2 );
		}
	}

	private function _edit() {
		if ($this->_isPost ()) {
			if ($this->_modelOperatorList->update ( array ('operator_name' => $_POST ['operator_name'] ), "Id={$_POST['Id']}" )) {
				$this->_modelOperatorList->createToCache();
				$this->_utilMsg->showMsg ( Tools::getLang('EDIT_SUCCESS','Common'), 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( Tools::getLang('EDIT_ERROR','Common'), - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$data = $this->_modelOperatorList->findById ( $_GET ['Id'], false );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_view->assign ( 'data', $data );
			$this->_view->set_tpl(array('body'=>'GameOperator/Edit.html'));
			$this->_view->display ();
		}
	}

	private function _del() {
		$this->_modelOperatorList->deleteByIdAndServierList ( $_GET ['Id'] );
		$this->_utilMsg->showMsg ( Tools::getLang('DEL_SUCCESS','Common'), 1 );
	}

	/**
	 * VIP等级索引设置
	 */
	public function actionVip(){
		switch ($_GET['doaction']){
			case 'vipAdd' :{
				$this->_vipAdd();
				return ;
			}
			case 'addGameOperator':
				$this->_addGameOperator();
				return;
			case 'vipCache' :{
				$this->_vipCreateCache();
				return ;
			}
			case 'vipSetup' :{
				$this->_vipSetup();
				return ;
			}
			case 'vipDel' :{
				$this->_vipDel();
				return ;
			}
			default:{
				$this->_vipIndex();
				return ;
			}
		}
	}

	/**
	 * 运营商,游戏关联索引
	 */
	private function _vipIndex(){
		$gameTypeList=$this->_getGlobalData('game_type');
		$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
		$dataList=$this->_modelGameOperator->findAll();
		foreach ($dataList as &$value){
			$value['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'vipDel'));
			$value['word_game_type_id']=$gameTypeList[$value['game_type_id']];
			$value['word_operator_id']=$operatorList[$value['operator_id']];
			$value['vip_setup']=unserialize($value['vip_setup']);
			$value['url_setup']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'doaction'=>'vipSetup'));
			$value['url_edit']=Tools::url(CONTROL,'GameOperatorSet',array('Id'=>$value['Id'],'doaction'=>'edit'));
			
			$value['vip_setup']['vip_timeout']=implode(',',$value['vip_setup']['vip_timeout']);
			$value['vip_setup']['vip_pay']=implode(',',$value['vip_setup']['vip_pay']);
		}
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('gameTypeList',$gameTypeList);
		$this->_view->assign('operatorList',$operatorList);
		$this->_view->set_tpl(array('body'=>'GameOperator/VipIndex.html'));
		$this->_utilMsg->createNavBar();
		$this->_view->display();
	}

	/**
	 * 建立缓存
	 */
	private function _vipCreateCache(){
		$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
		if ($this->_modelGameOperator->createCache()){
			$this->_utilMsg->showMsg(Tools::getLang('CACHE_SUCCERR','Common'),1);
		}else{
			$this->_utilMsg->showMsg(Tools::getLang('CACHE_ERROR','Common'),-2);
		}
	}

	/**
	 * 设置游戏里运营商的相关设置
	 */
	private function _vipSetup(){
		$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
		if ($this->_isPost()){
			$vipTimeOut=explode(',',$_POST['vip_timeout']);
			$vipPay=explode(',',$_POST['vip_pay']);
			$setup=serialize(array('vip_timeout'=>$vipTimeOut,'vip_pay'=>$vipPay));
			if ($this->_modelGameOperator->update(array('vip_setup'=>$setup),"Id={$_POST['Id']}")){
				$this->_utilMsg->showMsg(Tools::getLang('UPDATE_SUCCESS','Common'),1,Tools::url(CONTROL,ACTION));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('UPDATE_ERROR','Common'),-2);
			}
		}else{
			$dataList=$this->_modelGameOperator->findById($_GET['Id']);
			$gameTypes=$this->_getGlobalData('game_type');
			$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
			$operatorList=$this->_getGlobalData('operator_list');
			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');

			$dataList['word_game_type_id']=$gameTypes[$dataList['game_type_id']];
			$dataList['word_operator_id']=$operatorList[$dataList['operator_id']];
			$dataList['vip_setup']=unserialize($dataList['vip_setup']);
			$dataList['vip_timeout']=implode(',',$dataList['vip_setup']['vip_timeout']);
			$dataList['vip_pay']=implode(',',$dataList['vip_setup']['vip_pay']);
			$this->_view->assign('dataList',$dataList);
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'GameOperator/VipSetup.html'));
			$this->_view->display();
		}
	}

	private function _vipDel(){
		$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
		if ($this->_modelGameOperator->delById($_GET['Id'])){
			$this->_utilMsg->showMsg(Tools::getLang('DEL_SUCESS','Common'),1);
		}else{
			$this->_utilMsg->showMsg(Tools::getLang('DEL_ERROR','Common'),-2);
		}
	}

	/**
	 * 增加游戏,运营商索引
	 */
	private function _vipAdd(){
		if($this->_isPost()){
			if (count($_POST['operator_ids']) && isset($_POST['game_type'])){
				$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
				$vipSetup=serialize(array('vip_timeout'=>array(1440,1440,1440,1440,1440,1440,1440),'vip_pay'=>array(0,10000,20001,30001,40001,50001,60001)));//默认的vipsetup
				foreach ($_POST['operator_ids'] as $value){
					$this->_modelGameOperator->add(array('game_type_id'=>$_POST['game_type'],'operator_id'=>$value,'vip_setup'=>$vipSetup));
				}
				$this->_modelGameOperator->createCache();
				$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,ACTION));
			}else {
				$this->_utilMsg->showMsg(Tools::getLang('ADD_ERROR','Common'),-2);
			}
		}else{
			$gameTypeList=$this->_getGlobalData('game_type');
			$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
			$operatorList=$this->_getGlobalData('operator_list');
			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$this->_view->assign('gameTypeList',$gameTypeList);
			$this->_view->assign('operatorList',$operatorList);
			$this->_view->set_tpl(array('body'=>'GameOperator/VipAdd.html'));
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	

	
	public function actionGameOperatorSet(){
		switch ($_GET['doaction']){
			case 'edit' :{
				$this->_gameOperatorEdit();
				return ;
			}
			case 'extParam':
				$this->_getOperatorExtParam();
				return;
			default:{
				$this->_gameOperatorAdd();
				return ;
			}
		}
	}
	
	private function _gameOperatorAdd(){
		if($this->_isPost()){
			$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
			$vipSetup = array(
				'vip_timeout'=>explode(',',$_POST['vip_timeout']),
				'vip_pay'=>explode(',',$_POST['vip_pay']),
			);
			$addData = array(
				'game_type_id' =>$_POST['game_type'],
				'operator_id'=>$_POST['operator_id'],
				'url'=>trim($_POST['url']),
				'vip_setup'=>serialize($vipSetup),
			);
			if($_POST['ext']){
				$addData['ext'] = serialize($_POST['ext']);
			}			
			$this->_modelGameOperator->add($addData);
			$this->_modelGameOperator->createCache();
			$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,ACTION));
		}else{
			$gameTypeList=$this->_getGlobalData('game_type');
			$gameTypeList=Model::getTtwoArrConvertOneArr($gameTypeList,'Id','name');
			$operatorList=$this->_getGlobalData('operator_list');
			$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$this->_view->assign('gameTypeList',$gameTypeList);
			$this->_view->assign('operatorList',$operatorList);
			$this->_view->set_tpl(array('body'=>'GameOperator/EditGameOperator.html'));
			$this->_view->assign('isAdd',true);
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}
	
	private function _getOperatorExtParam($gameId = 0){
		if(!$gameId){
			$gameId = intval($_REQUEST['game_id']);
		}
		$gameObject = $this->_getGlobalData($gameId,'game');
		$ExtParam = array();
		if($gameObject && is_callable(array($gameObject,'operatorExtParam'))){
			$ExtParam = $gameObject->operatorExtParam();
		}
		if($this->_isAjax()){
			$this->_returnAjaxJson(array('status'=>1,'info'=>'','data'=>$ExtParam));
		}else{
			return $ExtParam;
		}		
	}
	
	private function _gameOperatorEdit(){
		$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
		$Id = intval($_GET['Id']);
		if(!$Id){
			$this->_utilMsg->showMsg('ID错误');
		}
		if($this->_isPost()){
			$vipSetup = array(
				'vip_timeout'=>explode(',',$_POST['vip_timeout']),
				'vip_pay'=>explode(',',$_POST['vip_pay']),
			);
			$addData = array(
				//'game_type_id' =>$_POST['game_type'],
				//'operator_id'=>$_POST['operator_id'],
				'url'=>trim($_POST['url']),
				'vip_setup'=>serialize($vipSetup),
			);
			if($_POST['ext']){
				$addData['ext'] = serialize($_POST['ext']);
			}			
			$this->_modelGameOperator->update($addData,'Id='.$Id);
			$this->_modelGameOperator->createCache();
			$this->_utilMsg->showMsg(false,1,Tools::url(CONTROL,'Vip'));
		}else{
			$data = $this->_modelGameOperator->findById($Id);
			$this->_view->assign('inputData',$this->_getOperatorExtParam($data['game_type_id']));
			$gameTypeList=$this->_getGlobalData('game_type');
			$data['game_type'] = $gameTypeList[$data['game_type_id']]['name'];			
			$operatorList=$this->_getGlobalData('operator_list');
			$data['operator_id'] = $operatorList[$data['operator_id']]['operator_name'];
			$vipSetup = unserialize($data['vip_setup']);
			unset($data['vip_setup']);
			$data['vip_timeout']=implode(',',$vipSetup['vip_timeout']);
			$data['vip_pay']=implode(',',$vipSetup['vip_pay']);
			if($data['ext']){
				$data['ext'] = unserialize($data['ext']);
			}
			$this->_view->assign('dataObject',$data);
			$this->_view->assign('isAdd',false);
			$this->_view->set_tpl(array('body'=>'GameOperator/EditGameOperator.html'));			
			$this->_utilMsg->createNavBar();
			$this->_view->display();
		}
	}

}