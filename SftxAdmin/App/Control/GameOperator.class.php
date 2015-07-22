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
		
		$this->_url ['GameOperator_AddGameOperator'] =Tools::url(CONTROL,'Vip',array('doaction'=>'vipAdd'));
		$this->_url ['GameOperator_SetupGameOperator']=Tools::url(CONTROL,'Vip',array('doaction'=>'vipSetup'));
		$this->_url ['GameOperator_CreateCacheGameOperator']=Tools::url(CONTROL,'Vip',array('doaction'=>'vipCache'));
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
				$this->_utilMsg->showMsg ( '添加成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '添加失败', - 2 );
			}
		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->set_tpl(array('body'=>'GameOperator/Add.html'));
			$this->_view->display ();
		}
	
	}
	
	private function _createCache() {
		if ($this->_modelOperatorList->createToCache ()) {
			$this->_utilMsg->showMsg ( '缓存生成成功', 1 );
		} else {
			$this->_utilMsg->showMsg ( '缓存生成失败', - 2 );
		}
	}
	
	private function _edit() {
		if ($this->_isPost ()) {
			if ($this->_modelOperatorList->update ( array ('operator_name' => $_POST ['operator_name'] ), "Id={$_POST['Id']}" )) {
				$this->_modelOperatorList->createToCache();
				$this->_utilMsg->showMsg ( '更改运营商成功', 1, Tools::url ( CONTROL, ACTION ) );
			} else {
				$this->_utilMsg->showMsg ( '更改运营商失败', - 2 );
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
		$this->_utilMsg->showMsg ( '删除运营商成功', 1 );
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
			$this->_utilMsg->showMsg('建立缓存成功',1);
		}else{
			$this->_utilMsg->showMsg('建立缓存失败',-2);
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
				$this->_utilMsg->showMsg('更新成功',1,Tools::url(CONTROL,ACTION));
			}else {
				$this->_utilMsg->showMsg('更新失败',-2);
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
			$this->_utilMsg->showMsg('删除成功',1);
		}else{
			$this->_utilMsg->showMsg('删除失败',-2);
		}
	}
	
	/**
	 * 增加游戏,运营商索引
	 */
	private function _vipAdd(){
		if (count($_POST['operator_ids']) && isset($_POST['game_type'])){
			$this->_modelGameOperator=$this->_getGlobalData('Model_GameOperator','object');
			$vipSetup=serialize(array('vip_timeout'=>array(70,60,50,40,30,10,5),'vip_pay'=>array(0,1000,2000,3000,4000,5000,6000)));//默认的vipsetup
			foreach ($_POST['operator_ids'] as $value){
				$this->_modelGameOperator->add(array('game_type_id'=>$_POST['game_type'],'operator_id'=>$value,'vip_setup'=>$vipSetup));
			}	
			$this->_modelGameOperator->createCache();
			$this->_utilMsg->showMsg(false);
		}else {
			$this->_utilMsg->showMsg('添加索引失败',-2);
		}

	}

}