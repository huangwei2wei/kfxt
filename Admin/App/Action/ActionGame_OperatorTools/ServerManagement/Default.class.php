<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_ServerManagement_Default extends Action_ActionBase{

	private $isCenterGame = array();//array(21);
	public function _init(){}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		$this->_modelSysconfig 		= $this->_getGlobalData ( 'Model_Sysconfig', 'object' );
		$this->_modelOperatorList 	= $this->_getGlobalData ( 'Model_OperatorList', 'object' );
		$this->_modelGameSerList 	= $this->_getGlobalData ( 'Model_GameSerList', 'object' );
		switch($_GET['doaction']){
			case 'add':{
				return $this->_serverlistAdd();
			}
			case 'del':{
				$this->_serverlistDel();
				return;
			}
			case 'edit':{
				return $this->_serverlistEdit();
			}
			case 'cache' :{
				$this->_serverlistcreateCache();
				return ;
			}
			default :{
				return $this->_serverlistIndex();
			}
		}
	}
	
	private function _serverlistIndex(){
		$this->_checkOperatorAct();
		$_GET['game_type']	=	$_GET["__game_id"];
		$server_msg			=	$this->_modelGameSerList->getSqlSearch($_GET);
		$timer = array('0'=>'关闭','1'=>'开启',''=>'全部');
		foreach($server_msg['serverList'] as &$_msg){
			$_msg["url_edit"]	.=	"&__game_id=".$_GET["__game_id"];
			$_msg["url_del"]	.=	"&__game_id=".$_GET["__game_id"];
		}
		$this->_assign['selectedGameTypeId']=$_GET["__game_id"];
		$this->_assign['get']=$_GET;
		$this->_assign['pageBox']=$server_msg['pageBox'];
		$this->_assign['zp']=	"ActionGame";
		$this->_assign['timer']=$timer;
		$this->_assign['cacheurl']		=	$this->_createcache();
		$this->_assign['addurl']		=	$this->_createaddurl();
		$this->_assign['dataList']= $server_msg['serverList'];
		$this->_assign['operatorList']= $server_msg['operatorList'];
		if(in_array($_REQUEST['__game_id'], $this->isCenterGame)){
			$this->_assign['isCenter']      	=	array(0=>'不是',1=>'是');
			$this->_assign['_body']				=	"ActionGame_OperatorTools/ServerManagement/DefaultHaveCenter.html";
		}
		return $this->_assign;
	}
	
	/**
	 * 编辑服务器
	 */
	private function _serverlistedit() {
		if ($this->_isPost ()) {
			if ($this->_modelGameSerList->updateServerlist($_POST)) {
				$this->jump('修改成功',-1);
			} else {
				$this->jump('修改失败',-1);
			}
		} else {
			$data = $this->_modelGameSerList->findById ( $_GET ['Id'], false );
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );	
			$data['ext'] 					= 	unserialize($data['ext']);
			$timer 							= 	array('0'=>'关闭','1'=>'开启');
			$this->_assign['data']			=	$data;
			$this->_assign['get']			=	$_GET;
			$this->_assign['operatorList']	=	$operatorList;
			$this->_assign['gameTypeList']	=	$gameTypeList;
			$this->_assign['timer']			=	$timer;
			$this->_assign['game_type']		=	$_GET["__game_id"];
			
			if(in_array($_REQUEST['__game_id'], $this->isCenterGame)){
				$this->_assign['isCenter']      	=	array(0=>'不是',1=>'是');
				$this->_assign['_body']				=	"ActionGame_OperatorTools/ServerManagement/AddHaveCenter.html";
			}else{
				$this->_assign['_body']				=	"ActionGame_OperatorTools/ServerManagement/Add.html";
			}
			return $this->_assign;
		}
	}
	
	/**
	 * 删除服务器
	 */
	private function _serverlistdel() {
		if ($this->_modelGameSerList->delById ( $_GET ['Id'] )) {
			$this->jump('删除成功',-1);
		} else {
			$this->jump('删除失败',-1);
		}
	}
	

	/**
	 * 添加服务器
	 */
	private function _serverlistadd() {
		if ($this->_isPost ()) {
			if ($this->_modelGameSerList->addServerlist($_POST)) {
				$this->jump('添加成功',1,Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'__game_id'=>$this->_gameObject->_gameId)));
			} else {
				$this->jump('添加失败',-1);
			}
		} else {
			$gameTypeList = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
			$operatorList = $this->_modelOperatorList->findAll ();
			$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
			$timer = array('0'=>'关闭','1'=>'开启');
			$this->_assign['operatorList']		=	$operatorList;
			$this->_assign['gameTypeList']		=	$gameTypeList;
			$this->_assign['game_type']			=	$_GET["__game_id"];
			$this->_assign['get']			=	$_GET;
			$this->_assign['timer']				=	$timer;
		}
		if(in_array($_REQUEST['__game_id'], $this->isCenterGame)){
			$this->_assign['isCenter']      	=	array(0=>'不是',1=>'是');
			$this->_assign['_body']				=	"ActionGame_OperatorTools/ServerManagement/AddHaveCenter.html";
		}else{
			$this->_assign['_body']				=	"ActionGame_OperatorTools/ServerManagement/Add.html";
		}
		return $this->_assign;
	}
	
	private function _serverlistcreateCache() {
		if ($this->_modelGameSerList->createToCache ()) {
			$this->jump('生成成功',-1);
		} else {
			$this->jump('生成失败',-1);
		}
	}
	
	private function _createIndexurl(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
		);
		return Tools::url(CONTROL,'ServerManagement',$query);
	}
	
	private function _createcache(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'doaction'		=>	"cache",
		);
		return Tools::url(CONTROL,'ServerManagement',$query);
	}
	
	private function _createaddurl(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'doaction'		=>	"add",
		);
		return Tools::url(CONTROL,'ServerManagement',$query);
	}
	

}