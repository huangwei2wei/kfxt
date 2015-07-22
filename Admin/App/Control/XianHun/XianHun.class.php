<?php
/**
 * 仙魂GM后台
 * @author PHP-兴源
 */
Tools::import('Control_BaseZp');
abstract class XianHun extends BaseZp {

	//	const XIAN_HUN_ID=6;	//仙魂游戏ID

	const RPC_KEY = 'test';

	const GAME_ID=6;

	const PACKAGE	=	'XianHun';

	const XIANHUN_ITEMS_CACHE_NAME = 'xian_hun_items_list';

	const HTTP_DB_ITF_KEY = 'abkl5734qqDb@u#';	//使用数据部 颜兴华 的接口执行sq

	/**
	 * Util_Rpc
	 * @var Util_Rpc
	 */
	private $_utilRpc=null;

	/**
	 * 测试数据库信息
	 */
	private $_DbHost;

	private $_DbUser ;

	private $_DbPWD ;

	private $_DbName;

	private $_DbPort;

	private $_dbInstance;

	function __construct(){
		parent::__construct();
		$this->game_id = 6;
		$this->package = 'XianHun';
		//		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		//		$this->_createView();
		//		$this->_createUrl();
	}

	private function ConnectXianHun(){
		if(empty($this->_DbHost) || empty($this->_DbUser) || empty($this->_DbName)){
			return false;
		}
		try {
			$dbInstance = mysql_pconnect ($this->_DbHost,$this->_DbUser,$this->_DbPWD );
			mysql_select_db ( $this->_DbName, $dbInstance );
			mysql_query ( 'SET NAMES \'utf8\'', $dbInstance );
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
		return $dbInstance;
	}

	protected function SelectXianHun($sql){
		//return $this->SelectXianHunOld($sql);
		$sql = trim($sql);
		$serverList = $this->_getGlobalData('server/server_list_'.$this->game_id);
		$dataInfo = unserialize($serverList[$_REQUEST['server_id']]["ext"]);
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		//$testUrl = $serverUrl.'?'.http_build_query($sendData);
		//server=s82.app24599.qqopenapp.com;user id=dataviewer;Password=qq@7%ssAjk3D;database=hsxz_s4;port=8010;Allow User Variables=true;character set=utf8;extrakey=kkyt678;extra=10.142.1.47:3319;tgw=tgw_l7_forward
		$getData["user"]  = "dataviewer";
		$getData["pwd"]  = "qq@7%ssAjk3D";
		$getData["link"]  = $dataInfo["db_host"];
		//		$getData["user"]  = "dataviewer";
		//		$getData["pwd"]  = "qq@7%ssAjk3D";
		//		$getData["link"]  = "jdbc:mysql://s82.app24599.qqopenapp.com:8010/hsxz_s4?characterEncoding=utf8&connectTimeout=30&extrakey=kkyt678&extra=10.142.1.47:3319&tgw=tgw_l7_forward";
		$getData["sql"]  = $sql;
		$dataList = $utilHttpInterface->result("http://121.9.245.117:8090/HbaseCollector/Agency.action",'',"",$getData);
		$dataList = json_decode($dataList,true);
		$returnData = array();
//		print_r($dataList);
		if(is_array($dataList['Column']) && is_array($dataList['data'])){
			foreach($dataList['data'] as $key => $sub){
				foreach($sub as $filed =>$val){
					$returnData[$key][$dataList['Column'][$filed]] = $val;
				}
			}
		}else{
			echo $dataList["info"];
		}
		return $returnData;
	}

	protected function SelectXianHunOld($sql){
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
	/**
	 * 那仙魂的时间
	 * @param max $time
	 */
	protected function getXianHunTime($time){
		if(is_array($time)){
			return date('Y-m-d H:i:s',$time['time']/1000);
		}else{
			return $time;
		}
	}

	protected function CountXianHun($table,$conditions){
		if(empty($table)){
			return 0;
		}
		if($conditions && 'AND' != strtoupper(substr(trim($conditions),0,3))){
			$conditions = ' and '.$conditions;
		}
		$sql = "select count(*) as total_num  from {$table} where 1 {$conditions}";
		$count = $this->SelectXianHun($sql);
		if($count === false){
			return false;
		}
		if(is_array($count [0])){
			return array_shift($count [0]);
		}
		return 0;
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

	function _createServerListAfter($server){
		$ext = unserialize($server['ext']);
		if($ext && is_array($ext)){
			$this->_DbHost = trim($ext['db_host']);
			$this->_DbName = trim($ext['db_name']);
			$this->_DbUser = trim($ext['db_user']);
			$this->_DbPWD = trim($ext['db_pwd']);
			$this->_DbPort = trim($ext['db_port']);
		}
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
	/**
	 * 获得当前服务器的url
	 * @param $Id 服务器id或者运营商id
	 * @param $isOperatorId 是否运营商id
	 */
	protected function _getUrl($Id=0,$isOperatorId = false){
		if($isOperatorId){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.self::GAME_ID);
			if(!$Id){
				$Id = $_REQUEST['operator_id'];
			}
			if($Id && is_array($operatorList[$Id])){
				return $operatorList[$Id]['url'];
			}
		}else{
			if(!$Id){
				$Id = $_REQUEST['server_id'];
			}
			$serverList = $this->_getGlobalData('server/server_list_'.self::GAME_ID);
			if($Id && is_array($serverList[$Id])){
				return $serverList[$Id]['server_url'];
			}
		}
		return false;
	}
	/**
	 * 直接获取接口返回的结果(json转array)
	 * @param srting $UrlAppend
	 * @param array $getData
	 * @param array $postData
	 */
	public function getResult($UrlAppend=null,$getData=null,$postData=null,$isOperatorId=false){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$sendUrl = $this->_getUrl(false,$isOperatorId);
		if(!$sendUrl)return false;
		$data = $utilHttpInterface->result($sendUrl,$UrlAppend,$getData,$postData);
		return json_decode($data,true);
	}
}