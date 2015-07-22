<?php
Tools::import('Action_ActionBase');
class ActionGame_OperatorTools_GetIngot_SanFen  extends Action_ActionBase{
	private $_DbHost,$_DbName,$_DbUser,$_DbPWD;
	
	
	private $_ingotTypes = array();
	
	public function _init(){
		$this->_assign['URL_LogTypeUpdate'] =  $this->_urlLogTypeUpdate();
		$this->_ingotTypes = $this->partner('GetIngotType');	//从日志类型Action接口中拿数据
		$this->_assign['playerLogTypes'] = json_encode($this->_ingotTypes);
		$this->_assign['selectType'] = array(1=>'银币',2=>'军功',3=>'金币',4=>'威望');
	}
	

	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['submit']){
			return $this->_assign;
		}
		$tables = array(1=>' CopperFlow_log ',2=>' ExploitFlow_log ',3=>' GoldFlow_log ',4=>' PrestigeFlow_log ');
		$select = array(1=>' sum(newCopper),sum(copper) ',2=>'ExploitFlow_log',3=>'GoldFlow_log',4=>'PrestigeFlow_log');
		$selectType = $_GET['selectType'];
		$EndTime = $_GET['EndTime'];
		$StartTime = $_GET['StartTime'];
		if(!($EndTime  && $StartTime)){
			$this->jump("时间必须",-1);
		}
		
		$sql = "select ".$select[$selectType].' from '.$tables[$selectType].'where 1=1 and logTime between "'.$StartTime.'" and "'. $EndTime .'"';
		
		echo $sql;
		
		print_r($_GET);
		exit;
		
		
		
		$playerLogTypes = $this->_ingotTypes;
		$getData = $this->_gameObject->getGetData($get);
		$postData = $this->getPostData($post);
		$data = $this->getResult($UrlAppend,$getData,$postData);


// print_r($getData);
// print_r($postData);
// print_r($data);
// exit;

		if($data['status'] == '1'){
			$this->_assign['playerName'] = $data['data'][0]['playerName'];
			$this->_assign['playerAccount'] = $data['data'][1]['playerAccount'];
			$logs = $data['data'][2]['logs'];
			foreach($logs as &$sub){
				$sub['logType'] = $playerLogTypes[$sub['typeId']]['rootTypeName'].'-->'.$playerLogTypes[$sub['typeId']]['subTypeList'][$sub['subTypeId']]['subTypeName'];
				$sub['actiontime'] = date('Y-m-d H:i:s',$sub['actiontime']['time']/1000);
				
				$search = array('coins','exploit');
				$replace = array('银币','军功');
				$sub['parms'] = str_replace($search, $replace, $sub['parms']);
// 				$sub['rootType'] = $playerLogTypes [$sub['objectId']] ['rootTypeName'];
// 				$sub['subType'] = $playerLogTypes [$sub['SerialType']] ['subTypeList'] [$sub['Oper']] ['subTypeName'];
// 				$des = $playerLogTypes [$sub['objectId']] ['subTypeList'] [$sub['eventId']]['des'];
// 				$params = explode('$@',$sub['params']); //参数的分隔符是  $@
// 				$paramsCount = count($params);
// 				for($i = 0; $i < $paramsCount ;$i++){
// 					$des = str_replace('{'.$i.'}','<font color="#FF0000">'.array_shift($params).'</font>',$des);
// 				}
// 				$sub['params'] = $des;
			}
			 
			$this->_assign['dataList'] = $logs;
			$this->_loadCore('Help_Page');//载入分页工具
			$helpPage=new Help_Page(array('total'=>$data['data'][3]['totals'],'perpage'=>PAGE_SIZE));
			$this->_assign['pageBox'] = $helpPage->show();
		}else{
			$this->_assign['connectError'] = 'Error Message:'.$data['info'];
		}
		return $this->_assign;
	}
	
	private function _urlLogTypeUpdate(){
		$query = array(
			'zp'=>PACKAGE,
			'__game_id'=>$this->_gameObject->_gameId,
			'server_id'=>$_REQUEST['server_id'],
			'timeout'=>1,
		);
		return Tools::url(CONTROL,'GetIngotType',$query);
	}
	
	
/* 	private function setDBinfo($_DbHost,$_DbUser,$_DbPWD,$_DbName){
		$this->_DbHost = $_DbHost;
		$this->_DbName = $_DbName;
		$this->_DbUser = $_DbUser;
		$this->_DbPWD = $_DbPWD;
	}
	private function _connectDb(){
		if(empty($this->_DbHost) || empty($this->_DbUser) || empty($this->_DbName)){
			return false;
		}
		try {
			$link = mysql_connect($this->_DbHost,$this->_DbUser,$this->_DbPWD );
			if (!$link) {
				return false;
			}
			mysql_query("SET CHARSET utf8", $link);
			mysql_select_db ( $this->_DbName, $link );
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}
		return $link;
	}
	
	public function getResult($sql){
		$link = $this->_connectDb();
		$result = mysql_query($sql,$link);
		@mysql_close($link);
		return $result;
	} */
	
	
}