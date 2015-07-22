<?php
/**
 * 大唐GM后台
 * @author PHP-兴源
 */
abstract class DaTang extends Control {
	
	const GAME_ID=7;	//大唐游戏ID
	
	const PACKAGE='DaTang';
	
	const RPC_KEY = 'test';
	
	const KEY = 'e23&^$)(&HJjkdwi^&%$';
	
	/**
	 * Util_Rbac
	 * @var Util_Rbac
	 */
	protected $_utilRbac;
	
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	protected $_utilMsg;
	
	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc=null;
	

	
	private $_operatorId;
	
	private $_utilHttpMInterface;
	
	/**
	 * 测试数据库信息
	 */
	private $_DbHost;
	
	private $_DbUser ;
	
	private $_DbPWD ;
	
	private $_DbName;
	
	private $_DbPort;
	
	private $_dbInstance;
	
	private function ConnectXianHun(){
		if(empty($this->_DbHost) || empty($this->_DbUser) || empty($this->_DbName)){
			return false;
		}
		try {
			$dbInstance = mysql_connect ($this->_DbHost,$this->_DbUser,$this->_DbPWD );
			mysql_select_db ( $this->_DbName, $dbInstance );
			mysql_query ( 'SET NAMES \'utf8\'', $dbInstance );
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
		return $dbInstance;
	}

	protected function SelectXianHun($sql){
		if(!$this->_dbInstance){
			$this->_dbInstance = $this->ConnectXianHun();
			if(!$this->_dbInstance){
				return false;
			}
		}
		$result = mysql_query ( $sql, $this->_dbInstance );
		if (!$result){
			throw new Exception ( "errorSQL:{$sql}" ,0);
		}
		$arr = array ();
		while ( $row = mysql_fetch_assoc ( $result ) ) {
			array_push ( $arr, $row );
		}
		return $arr;
	}
	
	protected function CountXianHun($table,$conditions){
		if(empty($table)){
			return 0;
		}
		$sql = "select count(*) as total_num  from {$table} where {$conditions}";
		$count = $this->SelectXianHun($sql);
		if($count === false){
			return false;
		}
		return array_shift($count [0]);
	}
	
	protected function CountXianHunBySql($sql){
		$count = $this->SelectXianHun($sql);
		if($count === false){
			return false;
		}
		return array_shift($count [0]);
	}
	
	protected function SetDb($_DbHost='',$_DbUser='',$_DbName='',$_DbPWD='',$_DbPort=''){
		if($_DbHost!=''){
			$this->_DbHost = $_DbHost;
		}
		if($_DbUser!=''){
			$this->_DbUser = $_DbUser;
		}
		if($_DbName!=''){
			$this->_DbName = $_DbName;
		}
		if($_DbPWD!=''){
			$this->_DbPWD = $_DbPWD;
		}
		if($_DbPort!=''){
			$this->_DbPort = $_DbPort;
		}
	}
	
	/**
	 * @return Util_Rpc
	 */
	protected function getApi(){
		if (is_null($this->_utilRpc)){
			$this->_utilRpc=$this->_getGlobalData('Util_Rpc','object');
		}
		return $this->_utilRpc;
	}
	
	protected function getMResults($serverids,$UrlAppend=NULL,$get=NULL,$post=NULL){
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->curlInit();
		foreach ($serverids as $serverId){
			$this->_utilHttpMInterface->addHttp($serverId,$UrlAppend,$get,$post);
		}
		$this->_utilHttpMInterface->send();
		return $this->_utilHttpMInterface->getResults();
	}
	
	protected function getResult($serverId,$UrlAppend='',$get=array(),$post=array(),$ToArray=true){
		$this->_utilHttpMInterface=$this->_getGlobalData('Util_HttpMInterface','object');
		$this->_utilHttpMInterface->curlInit();
		if(empty($get)){
			$get = array();
		}
		if(empty($post)){
			$post = array();
		}
		$post['_verifycode'] = CURRENT_TIME;
		$post['_sign'] = md5(self::KEY.CURRENT_TIME);
		$this->_utilHttpMInterface->setTimeOut(20);
		$this->_utilHttpMInterface->addHttp($serverId,$UrlAppend,$get,$post);
		$this->_utilHttpMInterface->send();
		$data = $this->_utilHttpMInterface->getResults();
		if($ToArray){
			return json_decode(array_shift($data),true);
		}
		return json_decode(array_shift($data));
	}

	
	/**
	 * 单服务器检测权限
	 * @param boolean $type 如果为真就表示检测运营商 $_REQUEST['operator_id'],否则就检测 $_REQUEST['server_id']
	 */
	protected function _checkOperatorAct($type=NULL){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::GAME_ID));
		$id=($type===null)?$_REQUEST['server_id']:$_REQUEST['operator_id'];
		$type=($type===null)?Util_Rbac::CHECK_SERVER:Util_Rbac::CHECK_OPERATOR;
		if(!$this->_utilRbac->checkOperatorAct($id,$type))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
	}
	
	
	/**
	 * 多服务器检测权限
	 */
	protected function _checkOperatorsAct(){
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$this->_view->assign('operatorList',$this->_utilRbac->getOperatorActList(self::GAME_ID));
		if (count($_REQUEST['server_ids'])){
			foreach ($_REQUEST['server_ids'] as $value){
				if(!$this->_utilRbac->checkOperatorAct($value))$this->_utilMsg->showMsg(Tools::getLang('NOT_ACTSERVER','Common'),-2);
			}
		}
	}
	
	/**
	 * 单服务器选择列表
	 */
	protected function _createServerList(){
		$operatorList=$this->_getGlobalData('operator_list');
		$operatorList=Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','DaTang/DaTangServerSelect/ServerSelect.html');
		if ($_REQUEST['server_id']){
			$this->_view->assign('display',true);
			$this->_view->assign('selectedServerId',$_REQUEST['server_id']);
			$this->_view->assign('selectedServername',$gameServerList[$_REQUEST['server_id']]['server_name']);
			$this->_operatorId=$gameServerList[$_REQUEST['server_id']]['operator_id'];
			$this->_view->assign('selectedOperatorId',$this->_operatorId);
			$ext = unserialize($gameServerList[$_REQUEST['server_id']]['ext']);
			
			$this->_DbHost = $ext['db_host'];
			$this->_DbName = $ext['db_name'];
			$this->_DbUser = $ext['db_user'];			
			$this->_DbPWD = $ext['db_pwd'];	
			$this->_DbPort = $ext['db_port'];
		}
	}
	
	/**
	 * 建立多服务器选择列表
	 */
	protected function _createMultiServerList(){
		$gameServerList=$this->_getGlobalData('server/server_list_'.self::GAME_ID);
		$this->_view->assign('gameServerList',json_encode($gameServerList));
		$this->_view->assign('tplServerSelect','DaTang/DaTangServerSelect/MultiServerSelect.html');
	}
	
	protected function _makeSql($sqlexp){
		if($sqlexp['main'] == '')return '';
		$sql = $sqlexp['main'].' ';
		if(isset($sqlexp['conditions'])){
			$sql .= $sqlexp['conditions'].' ';
		}
		if(isset($sqlexp['order'])){
			$sql .= $sqlexp['order'].' ';
		}
		if(isset($sqlexp['limit'])){
			$sql .= $sqlexp['limit'].' ';
		}
		return $sql;
	}
	#########以下用户运营商权限管理#############
	
	private $_modelUserProiorityOperator = null;
	private $__modelGameOperator = null;
	/**
	 * 用户运营商权限设置 
	 */
	public function UserSetup(){
		$this->_modelUserProiorityOperator = $this->_getGlobalData('Model_UserProiorityOperator','object');
		$this->_modelGameOperator = $this->_getGlobalData ( 'Model_GameOperator', 'object' );
		switch ($_REQUEST['doaction']){
			case 'managerOperator' :{//用户管理 
				$this->_userManagerOperator();
				return ;
			}
			case 'addOperator' :{//增加运营商
				$this->_userAddOperator();
				return ;
			}
			case 'delOperator' :{
				$this->_userDelOperator();
				return ;
			}
			default:
				$this->_userSetupSearch();
				return ;
		}
	}
	
	private function _userSetupSearch(){
		if($this->_isPost()){
			$modelUser = $this->_getGlobalData('Model_User','object');
			$userName = trim($_POST['user_name']);
			$userInfo = $modelUser->findByUserName($userName);
			if($userInfo){
				$userInfo['URL_managerOperator'] = Tools::url(CONTROL,ACTION,array('zp'=>PACKAGE,'Id'=>$userInfo['Id'],'doaction'=>'managerOperator'));
				$this->_view->assign('userInfo',$userInfo);
			}
		}
		$this->_view->set_tpl(array('body'=>'User/UserSetupSearch.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display ();
	}
	
	private function _userManagerOperator(){
		$operatorList = $this->_getGlobalData ( 'operator_list' );
		$operatorList = Model::getTtwoArrConvertOneArr ( $operatorList, 'Id', 'operator_name' );
		$gameTypeList = $this->_getGlobalData ( 'game_type' );
		if($gameTypeList[self::GAME_ID]){
			$gameTypeList = array(self::GAME_ID=>$gameTypeList[self::GAME_ID]['name']);
		}else{
			$gameTypeList = array();
		}
		//$gameTypeList = Model::getTtwoArrConvertOneArr ( $gameTypeList, 'Id', 'name' );
		$this->_modelGameOperator->tName();
		$sql1 = "select * from ".$this->_modelGameOperator->tName()." where game_type_id = ".self::GAME_ID;
		$gameOperatorIndex = $this->_modelGameOperator->select ($sql1);
		foreach ( $gameOperatorIndex as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
		}
		$_GET ['Id'] = intval($_GET ['Id']);
		$sql2 = "select * from ".$this->_modelUserProiorityOperator->tName()." where user_id = {$_GET ['Id']} and game_type_id=".self::GAME_ID;
		$userOperatorList = $this->_modelUserProiorityOperator->select($sql2);
		foreach ( $userOperatorList as &$value ) {
			$value ['word_operator_id'] = $operatorList [$value ['operator_id']];
			$value ['word_game_type_id'] = $gameTypeList [$value ['game_type_id']];
			$value ['url_del'] = Tools::url ( CONTROL, ACTION, array ('zp'=>PACKAGE,'operator_id' => $value ['operator_id'], 'user_id' => $_GET ['Id'], 'game_type_id' => $value ['game_type_id'],'doaction'=>'delOperator' ) );
		}
		$this->_view->assign ( 'userOperatorList', $userOperatorList );
		$this->_view->assign ( 'gameOperatorIndex', json_encode ( $gameOperatorIndex ) );
		$this->_view->assign ( 'gameTypeList', $gameTypeList );
		$this->_view->assign ( 'userId', $_GET ['Id'] );
		$this->_view->set_tpl(array('body'=>'User/UserManagerOperator.html'));
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display ();
	}
	
	private function _userAddOperator(){
		if (count ( $_POST ['operator_id'] ) && $_POST ['game_type_id']) {
			if($_POST ['game_type_id']!=self::GAME_ID){
				$this->_utilMsg->showMsg ('游戏ID越界', - 2 );
			}
			foreach ( $_POST ['operator_id'] as $value ) {
				$this->_modelUserProiorityOperator->addUserOperator ( array ('user_id' => $_POST ['user_id'], 'game_type_id' => $_POST ['game_type_id'], 'operator_id' => $value ) );
			}
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( '添加运营商失败,请正确选择游戏类型和营商', - 2 );
		}
	}
	
	private function _userDelOperator(){
		if($_GET ['game_type_id']!=self::GAME_ID){
			$this->_utilMsg->showMsg ('游戏ID越界', - 2 );
		}
		if ($this->_modelUserProiorityOperator->delByOperatorId ( $_GET ['game_type_id'], $_GET ['operator_id'], $_GET ['user_id'] )) {
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}
	#########以上用户运营商权限管理#############
	/**
	 * 多运营商选择器
	 */
	protected function _multiOperatorSelect($optGroupFunName=''){
		$serverList = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
		if($optGroupFunName && is_callable(array(&$this,$optGroupFunName))){
			$operatorGroup = call_user_func(array(&$this,$optGroupFunName));
		}
		if(!$operatorGroup){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.self::GAME_ID);
			$operatorList = Model::getTtwoArrConvertOneArr($operatorList,'Id','operator_name');
			$operatorGroup = array(0=>$operatorList);
		}
		$optSer = array();
		foreach($serverList as $serverId => $server){
			$optSer[$server['operator_id']][$serverId] = $server['server_name'];
		}
		$this->_view->assign('optSer',json_encode($optSer));
		$this->_view->assign('operatorGroup',$operatorGroup);
		$this->_view->assign('tplServerSelect','OperationFRG/MultiServerSelect.html');
	}
}