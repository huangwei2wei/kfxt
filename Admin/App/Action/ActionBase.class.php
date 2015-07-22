<?php
require_once 'reader.php';
abstract class Action_ActionBase extends Base implements SplSubject{

	//	abstract function propSet();	//属性设置

	protected $_gameObject;	//游戏对象
	protected $_serverList;	//服务器列表
	protected $_observers;	//监听对象
	public $_modelLog;
	public $_modelLogDesc;

	public $_requestCount = 0;

	public $_logGame = array(18,27);

	protected $_assign = array();	//返回给控制器的数据

	public function __call($method,$args){
		return array('errorInfo'=>"'{$method}' not found");
	}
	public function __get($varName){
		return $this->$varName;
	}
	public function __construct(){

		$gameId = intval($_REQUEST['__game_id']);
		$this->_gameObject = $this->_getGlobalData($gameId,'game');	//加载游戏对象
		if(!is_object($this->_gameObject)){
			exit('gameObjectError');
		}
		$this->_modelLog		=	$this->_getGlobalData('Model_newLog','object');
		$this->_modelLogDesc 	= 	$this->_getGlobalData('Model_descLog','object');
		$this->_serverList 		= 	$this->_getGlobalData('server/server_list_'.$gameId);
		$this->_observers 		= 	new SplObjectStorage ();	//监听对象储存器
		$this->_init();
	}
	public function _init(){}	//子类回调用于初始化

	//	public function __destruct(){
	//		$this->notify();	//用析构来通知监听器
	//	}
	/**
	 * 结束程序
	 * @param string $info
	 * @param int $status
	 */
	public function stop($info=null,$status = 1){
		if($status ==1){
			$this->notify();	//操作成功情况下,通知监听器
		}
		exit($info);
	}
	/**
	 * 结束程序,并返回json数据
	 * @param array $result
	 */
	public function ajaxReturn($result=array()){
		if($result['status'] == 1){
			$this->notify();	//操作成功情况下,通知监听器
		}
		exit(json_encode($result));
	}
	/**
	 * 成功与失败的跳转,并结束程序
	 * @param $code	等于false时,直接跳转而不数秒提示
	 * @param $errorLevel
	 * @param $url
	 * @param $reftime
	 */
	public function jump($code=null, $errorLevel = 1, $url = 1, $reftime = 3){
		if($this->_isAjax()){
			$result = array(
				'status'=>$errorLevel==1?1:0,
				'info'=>$code,
				'data'=>func_get_args(),
			);
			$this->ajaxReturn($result);
		}else{
			if($errorLevel == 1){
				$this->notify();	//操作成功情况下,通知监听器
			}
			$utilMsg = $this->_getGlobalData('Util_Msg','object');
			$utilMsg->showMsg($code,$errorLevel,$url,$reftime);
		}

	}
	/**
	 * 让子类覆盖，POST数据的整理,通常整理成属性,并返回可用数组
	 * @param $post
	 */
	public function getPostData($post=null){
		return $post;
	}

	/**
	 * 是否为post请求
	 */
	protected function _isPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}

	/**
	 * 是否为ajax提交
	 */
	protected function _isAjax(){
		return isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest';
	}


	/**
	 * 获得当前服务器的url
	 * @param $Id 服务器id或者运营商id
	 * @param $isOperatorId 是否运营商id
	 */
	public function _getUrl($Id=0,$isOperatorId = false){
		if($isOperatorId){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->_gameObject->_gameId);
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
			if($Id && is_array($this->_serverList[$Id])){
				return $this->_serverList[$Id]['server_url'];
			}
		}
		return false;
	}

	public function _getServerID($Id=0,$isOperatorId = false){
		if($isOperatorId){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->_gameObject->_gameId);
			if(!$Id){
				$Id = $_REQUEST['operator_id'];
			}
			if($Id && is_array($operatorList[$Id])){
				return $operatorList[$Id]['ordinal'];
			}
		}else{
			if(!$Id){
				$Id = $_REQUEST['server_id'];
			}
			if($Id && is_array($this->_serverList[$Id])){
				return $this->_serverList[$Id]['ordinal'];
			}
		}
		return false;
	}

	public function _getMark($Id=0,$isOperatorId = false){
		if($isOperatorId){
			$operatorList = $this->_getGlobalData('operator/operator_list_'.$this->_gameObject->_gameId);
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
			if($Id && is_array($this->_serverList[$Id])){
				return $this->_serverList[$Id]['marking'];
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
	public function getResult($UrlAppend=null,$getData=null,$postData=null,$isOperatorId=false,$isAjax=false){
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		$sendUrl = $this->_getUrl(false,$isOperatorId);
		if(!$sendUrl)return false;
		
		$data = $utilHttpInterface->result($sendUrl,$UrlAppend,$getData,$postData,$isAjax);
		if(in_array($this->_gameObject->_gameId,$this->_logGame)){
			if($this->_gameObject->_gameId==18){
				$this->_RecordLog(substr($data,0,200),"UrlAppend:".$UrlAppend."<br/>GET:".var_export($getData,true)."<br/>POST:".var_export(json_decode($postData["data"],true),true));
			}else{
				$this->_RecordLog(substr($data,0,200),"UrlAppend:".$UrlAppend."<br/>GET:".var_export($getData,true)."<br/>POST:".var_export($postData,true));
			}

		}
		$data = json_decode($data,true);
		return $data;
	}
	/**
	 * 直接获取接口返回的结果(json转array)
	 * @param srting $UrlAppend
	 * @param array $getData
	 * @param array $postData
	 */
	public function getResultOpt($UrlAppend=null,$getData=null,$postData=null){
		return $this->getResult($UrlAppend,$getData,$postData,$isOperatorId=true);
	}

	/**
	 * double型转字符串
	 * @param $i
	 */
	protected function _d2s($i){
		if(is_double($i)){
			return number_format($i,0,'','');
		}
		return $i;
	}

	/**
	 * 文件缓存统一名
	 * @param string $name 自定义结尾
	 */
	public function getFileCacheName($name=null){
		if(!$name){
			$name = get_class($this);
		}
		return $this->_gameObject->_gameId.'_'.$name;
	}

	/**
	 * 获得同一游戏中其他接口返回的数据
	 * @param $action
	 * @param $method
	 * @param $extParams
	 */
	public function partner($action,$method = 'main',$extParams=array('get'=>array(),'post'=>array()),$control=''){
		static $partnerObject = array();
		$gameIfConf = $this->_gameObject->getIfConf();
		if(!$control){
			$control = CONTROL;
		}
		$package_control = PACKAGE.'_'.$control;
		if(is_array($action)){	//对$action进行了扩展
			$package_control = $action[0];
			$action = $action[1];
		}
		$package_control_action = $package_control.'_'.$action;
		if(!is_object($partnerObject[$package_control_action])){
			$gameAction = $gameIfConf[$action]['action'];
			$className = $package_control_action.'_'.$gameAction;
			if(class_exists($className)){
				$partnerObject[$package_control_action] = new $className ();
			}else {
				$file = APP_PATH.'/Action/'.$package_control.'/'.$action.'/'."{$gameAction}.class.php";
				if(is_file($file)){
					include $file;
					if(class_exists($className)){
						$partnerObject[$package_control_action] = new $className ();
					}else{
						throw new Error ( "class:{$className} Not Fount" );
					}
				}else{
					throw new Error ( "file:{$file} Not Fount" );
				}
			}

		}
		if(is_object($partnerObject[$package_control_action])){
			$params = array(
				'UrlAppend'=>$gameIfConf[$action]['UrlAppend'],
				'get'=>$gameIfConf[$action]['get'],
				'post'=>$gameIfConf[$action]['post'],
			);
			if($extParams['get']){
				$params['get'] = $params['get']?array_merge($params['get'],$extParams['get']):$extParams['get'];
			}
			if($extParams['post']){
				$params['post'] = $params['post']?array_merge($params['post'],$extParams['post']):$extParams['post'];
			}
			return call_user_func_array(array($partnerObject[$package_control_action],$method),$params);
		}
		return false;
	}

	/**
	 * 增加监听对象
	 * @param SplObserver $observer
	 */
	public function attach(SplObserver $observer) {
		if (! $this->_observers->contains ( $observer )) {
			$this->_observers->attach ( $observer );
		}
		return true;
	}

	/**
	 * 删除监听对象 
	 * @param SplObserver $observer
	 */
	public function detach(SplObserver $observer) {
		if (! $this->_observers->contains ( $observer )) {
			return false;
		}
		$this->_observers->detach ( $observer );
		return true;
	}

	/**
	 * 传送对象 
	 */
	public function notify() {
		foreach ( $this->_observers as $observer ) {
			$observer->update ( $this );
		}
	}

	public function twSelect($sql,$dataBase,$url,$user="",$pwd="",$is_ac=false){
		$sql = trim($sql);
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if(empty($user)){
			$getData["user"]  	= 	"dataviewer";
		}else{
			$getData["user"]	=	$user;
		}
		if(empty($pwd)){
			$getData["pwd"]  	= 	"qq@7%ssAjk3D";
		}else{
			$getData["pwd"]		=	$pwd;
		}
		$con = mysql_connect($url,$getData["user"],$getData["pwd"]);
		mysql_query("SET NAMES 'UTF8'",$con);
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($dataBase, $con);

		$result = mysql_query($sql,$con);
		mysql_close($con);
		$data=array();
		while($row = mysql_fetch_array($result))
		{
			$data[] = $row;
		}
		return $data;
	}

	public function Select($sql,$url,$user="",$pwd="",$is_ac=false){
		$sql = trim($sql);
		$utilHttpInterface = $this->_getGlobalData('Util_HttpInterface','object');
		if(empty($user)){
			$getData["user"]  	= 	"dataviewer";
		}else{
			$getData["user"]	=	$user;
		}
		if(empty($pwd)){
			$getData["pwd"]  	= 	"qq@7%ssAjk3D";
		}else{
			$getData["pwd"]		=	$pwd;
		}
		if($is_ac){
			$getData["modify"]  = "1";
		}
		$getData["sql"]  = $sql;
		$getData["link"]  = $url;
		//		print_r($getData);
		$dataList = $utilHttpInterface->result("http://121.9.245.117:8090/HbaseCollector/Agency.action",'','',$getData);
		//		$dataList = $utilHttpInterface->result("http://127.0.0.1:8080/HbaseCollector/Agency.action",'','',$getData);
		echo $getData["sql"];
		if(in_array($this->_gameObject->_gameId,$this->_logGame)){
			$this->_RecordLog(substr($dataList,0,200),"HbaseCollector/Agency.action<br/>GET:".$getData["sql"]);
		}
		$dataList = json_decode($dataList,true);
		$returnData = array();
		if($dataList["state"]==1){
			foreach($dataList['data'] as $key => $sub){
				foreach($sub as $filed =>$val){
					$returnData[$key][$dataList['Column'][$filed]] = $val;
				}
			}
		}else{
			echo $dataList["info"];
			return false;
		}
		if($is_ac){
			return true;
		}else{
			return $returnData;
		}
	}

	protected function _RecordLog($returnData,$subData){
		if($this->_requestCount<1){
			$this->_requestCount++;
			$this->_modelLog->addLog();
		}

		$this->_modelLogDesc->addLog($returnData,$subData);
	}

	protected function _returnAjaxJson($result){
		exit(json_encode($result));
	}
}