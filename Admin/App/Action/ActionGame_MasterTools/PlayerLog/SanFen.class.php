<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLog_SanFen extends Action_ActionBase{
	
	private $_playerLogTypes = array();
	
	public function _init(){
		$this->_assign['URL_LogTypeUpdate']= $this->_urlLogTypeUpdate();
		$this->_playerLogTypes = $this->partner('PlayerLogType');	//从日志类型Action接口中拿数据
		$this->_assign['playerLogTypes'] = json_encode($this->_playerLogTypes);
		$this->_assign['playerType'] = array(1=>'玩家id',2=>'玩家账号',3=>'玩家昵称');
	}
	
	public function getPostData($post=NULL){
		$postData = array(
			'pageSize'=>PAGE_SIZE,
			'pageCount'=>max(1,intval($_GET['page'])),
			'playerType' => trim($_GET['playerType']),
			'player' => trim($_GET['player']),
			'keywords'=>trim($_GET['keywords']),
			'objectId'=>intval($_GET['rootid']),
			'eventId'=>intval($_GET['typeid']),
			'beginTime'=>$_GET['StartTime']?$this->_d2s(strtotime(trim($_GET['StartTime']))):'',
			'endTime'=>$_GET['EndTime']?$this->_d2s(strtotime(trim($_GET['EndTime']))):'',
		);
		if($post){
			$postData = array_merge($post,$postData);
		}
		if(strlen($postData['player'])<=0){
			$this->jump('玩家不能空！');
		}
// 		$postData = array_filter($postData);
		return $postData;
	}
	
	public function main($UrlAppend=null,$get=null,$post=null){
		if(!$_REQUEST['server_id'] || !$_REQUEST['submit']){
			return $this->_assign;
		}
		$playerLogTypes = $this->_playerLogTypes;
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
		return Tools::url(CONTROL,'PlayerLogType',$query);
	}
}